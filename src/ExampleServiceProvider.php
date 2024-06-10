<?php

declare(strict_types=1);

namespace Yard\SkeletonPackage;

use Illuminate\Support\ServiceProvider;
use Yard\SkeletonPackage\Console\ExampleCommand;

class ExampleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Example', function () {
            return new Example($this->app);
        });

        $this->mergeConfigFrom(
            __DIR__.'/../config/example.php',
            'example'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/example.php' => $this->app->configPath('example.php'),
        ], 'config');

        $this->loadViewsFrom(
            __DIR__.'/../resources/views',
            'Example',
        );

        $this->commands([
            ExampleCommand::class,
        ]);

        $this->app->make('Example');
    }
}
