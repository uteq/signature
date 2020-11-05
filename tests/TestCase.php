<?php

namespace Uteq\Signature\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Psalm\Config;
use Uteq\Signature\SignatureServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('app.url', 'http://127.0.0.1');

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Uteq\\Signature\\Database\\Factories\\'.class_basename($modelName).'Factory'
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
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);


        include_once __DIR__.'/../database/migrations/create_signature_table.php.stub';
        (new \CreateSignatureTable())->up();
    }
}
