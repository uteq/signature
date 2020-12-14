<?php

namespace Uteq\Signature\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use Uteq\Signature\SignatureServiceProvider;
use Uteq\Signature\Tests\Fixtures\Action;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('app.url', 'http://127.0.0.1');
        $this->app['config']->set('app.key', 'base64:vRKyP88/TPfeQKjibHMXufX3REU+T4TCGONzI/ZMUfk=');

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Uteq\\SignatureTest\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            SignatureServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        Schema::dropAllTables();

        $app['config']->set('session.driver', 'file');


        include_once __DIR__.'/../database/migrations/create_signature_table.php.stub';
        (new \CreateSignatureTable())->up();
    }
}
