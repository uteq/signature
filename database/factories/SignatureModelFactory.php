<?php

namespace Uteq\Signature\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Uteq\Signature\Models\SignatureModel;
use Uteq\Signature\Tests\Fixtures\Action;

class SignatureModelFactory extends Factory
{
    protected $model = SignatureModel::class;

    public function definition()
    {
        return [
            'key' => $this->faker->uuid,
            'handler' => Action::class,
            'payload' => json_encode(['info' => 'created by factory']),
            'password' => null,
            'group' => null,
            'one_time_link' => false,
            'expiration_date' => now()->addWeek(),
        ];
    }
}
