<?php


namespace Uteq\Signature\Builders;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class Signature
{
    private string $handler;
    private array $payload = [];
    private Carbon $expirationDate;
    private ?string $password = null;
    private bool $oneTimeLink = false;
    private bool $longerKey = false;
    private int $longerKeyLenght = 64;
    private bool $group = false;
    private string $groupName;

    public function __construct($handler, $payload)
    {
        $this->handler = $handler;
        $this->payload = $payload;
        $this->expirationDate = now()->addWeeks(2);
    }

    /**
     * @param array $payload
     * @return Signature
     */
    public function payload(array $payload): Signature
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * @param Carbon $expirationDate
     * @return Signature
     */
    public function expirationDate(Carbon $expirationDate): Signature
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * @param string $password
     * @return Signature
     */
    public function password(string $password): Signature
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param bool $oneTimeLink
     * @return Signature
     */
    public function oneTimeLink(): Signature
    {
        $this->oneTimeLink = true;

        return $this;
    }

    public function longerKey(int $keyLenght = 64): Signature
    {
        $this->longerKeyLenght = ($keyLenght > 254) ? 254 : $keyLenght;
        $this->longerKey = true;

        return $this;
    }

    public function group(string $groupName): Signature
    {
        $this->group = true;
        $this->groupName = $groupName;

        return $this;
    }

    public function get(): string
    {
        $signature = new \Uteq\Signature\Models\SignatureModel();

        if ($this->longerKey) {
            $key = bin2hex(openssl_random_pseudo_bytes(intval(round($this->longerKeyLenght / 2))));
        } else {
            $key = (string)Str::uuid();
        }

        if ($this->group) {
            $signature->group = $this->groupName;
        }

        $signature->key = $key;
        $signature->handler = $this->handler;
        $signature->payload = Crypt::encrypt(json_encode($this->payload));
        if ($this->password !== null) {
            $signature->password = bcrypt($this->password);
        } else {
            $signature->password = $this->password;
        }

        $signature->expiration_date = $this->expirationDate;
        $signature->one_time_link = $this->oneTimeLink;

        $signature->save();

        return config("app.url") . Str::replaceFirst('{signature}', $key, config('signature.action_route'));
    }
}
