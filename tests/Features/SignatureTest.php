<?php


namespace Uteq\Signature\Tests\Features;

use Illuminate\Support\Facades\Config;
use Uteq\Signature\Exceptions\KeyAlreadyExists;
use Uteq\Signature\Exceptions\KeyIsTooLong;
use Uteq\Signature\Models\SignatureModel;
use Uteq\Signature\SignatureFacade;
use Uteq\Signature\Tests\Fixtures\Action;
use Uteq\Signature\Tests\Fixtures\ActionNoReturn;
use Uteq\Signature\Tests\TestCase;

class SignatureTest extends TestCase
{
    public function getKey(string $url): string
    {
        $array = explode("/", $url);

        return array_pop($array);
    }

    /** @test */
    public function signature_generate_key()
    {
        $payload = ['test' => 'Generate key',];
        $url = SignatureFacade::make(Action::class, $payload)->get();
        $this->assertTrue(is_string($url));
        $this->get($url)->assertOk()->assertJson($payload);
    }

    /** @test */
    public function signature_generate_key_with_password()
    {
        $payload = ['test' => 'Password protected'];
        $password = 'Password';
        $url = SignatureFacade::make(Action::class, $payload)->password($password)->get();

        $this->assertTrue(is_string($url));
        $this->get($url)->assertOk();
        $this->post(route('signature.validate_password_route', $this->getKey($url)), ['password' => "Password"])->assertStatus(302);
        $this->get($url)->assertOk()->assertJson($payload);
    }

    /** @test */
    public function signature_password_is_incorrect()
    {
        $payload = ['test' => 'Incorrect password'];
        $password = 'Password';
        $url = SignatureFacade::make(Action::class, $payload)->password($password)->get();

        $this->assertTrue(is_string($url));
        $this->get($url)->assertOk();
        $this->post(route('signature.validate_password_route', $this->getKey($url)), ['password' => "wrongPassword"])->assertSessionHasErrors('password');
    }

    /** @test */
    public function signature_expired()
    {
        $payload = ['test' => 'Expired signature'];

        $url = SignatureFacade::make(Action::class, $payload)->expirationDate(now()->subWeek())->get();

        $key = $this->getKey($url);

        $this->assertDatabaseHas('signatures', ['key' => $key]);

        $this->get($url)->assertStatus(404);

        $this->assertDatabaseMissing('signatures', ['key' => $key]);
    }

    /** @test */
    public function signature_one_time_link()
    {
        $payload = ['test' => 'One time link'];

        $url = SignatureFacade::make(Action::class, $payload)->oneTimeLink()->get();

        $this->get($url)->assertOk()->assertJson($payload);

        $this->assertDatabaseMissing('signatures', ['key' => $this->getKey($url)]);
        $this->get($url)->assertStatus(404);
    }

    /** @test */
    public function signature_payload()
    {
        $payload = [
            'test' => 'test1',
            'test2' => 'test3',
        ];
        $url = SignatureFacade::make(Action::class)->payload($payload)->get();
        $this->get($url)->assertOk()->assertJson($payload);
    }

    /** @test */
    public function signature_redirects_to_home_page_when_no_return_is_provided_in_action()
    {
        $url = SignatureFacade::make(ActionNoReturn::class)->get();
        $this->get($url)->assertRedirect('/');
    }

    /** @test */
    public function signature_can_use_anonymous_action_classes()
    {
        $payload = ['test' => 'Anonymous action class'];
        Config::set('signature.actions.action-class', Action::class);

        $url = SignatureFacade::make('action-class', $payload)->get();

        $this->get($url)->assertOk()->assertJson($payload);
    }

    /** @test */
    public function throw_exeption_when_anonymous_cannot_be_found()
    {
        $url = SignatureFacade::make('action-class')->get();
        $this->get($url)->assertStatus(404);
    }

    /** @test */
    public function singature_longer_key()
    {
        $payload = ['test', 'Longer key'];
        $url = SignatureFacade::make(Action::class)
            ->payload($payload)
            ->longerKey()
            ->get();

        $this->assertDatabaseHas('signatures', ['key' => $this->getKey($url)]);
        $this->assertEquals(64, strlen($this->getKey($url)));
        $this->get($url)->assertOk()->assertJson($payload);
    }

    /** @test */
    public function singature_longer_keylenght_cant_exceed_255()
    {
        $payload = ['test', 'Longer key'];
        $url = SignatureFacade::make(Action::class)
            ->payload($payload)
            ->longerKey(266)
            ->get();
        $this->assertEquals(254, strlen($this->getKey($url)));
        $this->get($url)->assertOk()->assertJson($payload);
    }

    /** @test */
    public function connected_signatures_get_deleted_when_one_signature_is_used()
    {
        $payload = ['test', 'Connected signatures'];
        $url1 = SignatureFacade::make(Action::class, $payload)->group('connected')->oneTimeLink()->get();
        $url2 = SignatureFacade::make(Action::class, $payload)->group('connected')->oneTimeLink()->get();
        $url3 = SignatureFacade::make(Action::class, $payload)->group('connected')->oneTimeLink()->get();

        $this->assertDatabaseHas('signatures', ['key' => $this->getKey($url1)]);
        $this->assertDatabaseHas('signatures', ['key' => $this->getKey($url2)]);
        $this->assertDatabaseHas('signatures', ['key' => $this->getKey($url3)]);

        $this->get($url1)->assertOk()->assertJson($payload);

        $this->assertDatabaseMissing('signatures', ['key' => $this->getKey($url1)]);
        $this->assertDatabaseMissing('signatures', ['key' => $this->getKey($url2)]);
        $this->assertDatabaseMissing('signatures', ['key' => $this->getKey($url3)]);
    }

    /** @test */
    public function signature_clean_command_deletes_all_signatures_that_are_expired()
    {
        SignatureModel::factory(['expiration_date' => now()->subWeek()])->count(5)->create();
        SignatureModel::factory(['expiration_date' => now()->addWeek()])->count(5)->create();

        $this->assertDatabaseCount('signatures', 10);
        $this->artisan('signature:clean')->expectsOutput('Deleted all expired signatures');

        $this->assertDatabaseCount('signatures', 5);
    }

    /** @test */
    public function signature_can_use_custom_key_name()
    {
        $payload = ['test' => 'Custom key'];
        $url = SignatureFacade::make(Action::class)
            ->payload($payload)
            ->customKey('veryCoolCustomKey')
            ->get();

        $this->assertDatabaseHas('signatures', ['key' => 'veryCoolCustomKey']);

        $this->get($url)->assertOk()->assertJson($payload);
    }

    /** @test */
    public function signature_cant_create_two_the_same_keys()
    {
        $payload = ['test' => 'Custom key'];
        SignatureFacade::make(Action::class)
            ->payload($payload)
            ->customKey('veryCoolCustomKey')
            ->get();

        $this->assertDatabaseHas('signatures', ['key' => 'veryCoolCustomKey']);

        $this->expectException(KeyAlreadyExists::class);
        SignatureFacade::make(Action::class)
            ->payload($payload)
            ->customKey('veryCoolCustomKey')
            ->get();
    }

    /** @test */
    public function signature_will_use_custom_key()
    {
        $url = SignatureFacade::make(Action::class, [])
            ->longerKey()
            ->customKey('veryCoolCustomKey')
            ->get();

        $this->assertDatabaseHas('signatures', ['key' => 'veryCoolCustomKey']);
    }

    /** @test */
    public function signature_will_use_longer_key()
    {
        $url = SignatureFacade::make(Action::class, [])
            ->customKey('veryCoolCustomKey')
            ->longerKey()
            ->get();

        $this->assertDatabaseHas('signatures', ['key' => $this->getKey($url)]);
    }

    /** @test */
    public function singature_custom_key_cant_exceed_max_length_of_254()
    {
        $this->expectException(KeyIsTooLong::class);
        SignatureFacade::make(Action::class, [])
            ->customKey('241234817249874510928374129834712093857498471230984172340985237450918237410298347120938723469081723490812745089327419083475120948573029853487562987456234985762398716295871645872364591827363789456897351235785623948756123987526345987126359876419827364192837461298475619387534');
    }
}
