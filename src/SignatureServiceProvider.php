<?php

namespace Uteq\Signature;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Uteq\Signature\Commands\SignatureCommand;
use Uteq\Signature\Http\Controllers\ActionController;
use Uteq\Signature\Http\Controllers\ValidateActionPasswordController;
use Uteq\Signature\Models\SignatureModel;

class SignatureServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/signature.php' => config_path('signature.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../resources/views' => base_path('resources/views/vendor/signature'),
            ], 'views');

            $migrationFileName = 'create_signature_table.php';
            if (! $this->migrationFileExists($migrationFileName)) {
                $this->publishes([
                    __DIR__ . "/../database/migrations/{$migrationFileName}.stub" => database_path('migrations/' . date('Y_m_d_His', time()) . '_' . $migrationFileName),
                ], 'migrations');
            }

            $this->commands([
                SignatureCommand::class,
            ]);
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'signature');

        Route::bind('signature', function ($value) {
            return SignatureModel::query()->where('key', $value)->firstOrFail();
        });

        Route::get(config('signature.action_route'), ActionController::class)->middleware('web')->name('signature.action_route');
        Route::post(config('signature.validate_password_route'), ValidateActionPasswordController::class)->middleware('web')->name('signature.validate_password_route');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/signature.php', 'signature');

        $this->app->singleton('signature', Signature::class);
    }

    public static function migrationFileExists(string $migrationFileName): bool
    {
        $len = strlen($migrationFileName);
        foreach (glob(database_path("migrations/*.php")) as $filename) {
            if ((substr($filename, -$len) === $migrationFileName)) {
                return true;
            }
        }

        return false;
    }
}
