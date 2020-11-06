<?php


namespace Uteq\Signature\Tests\Features;

use Uteq\Signature\Tests\Fixtures\Action;
use Uteq\Signature\Tests\TestCase;

class SignatureTest extends TestCase
{
    /** @test */
    public function signature_generate_key()
    {
        $payload = [
            'test' => 'test1',
            'test2' => 'test3',
        ];
        $url = \Uteq\Signature\SignatureFacade::make(Action::class, $payload)->get();
        $this->assertTrue(is_string($url));
        $this->get($url)->assertOk()->assertJson($payload);
    }
}
