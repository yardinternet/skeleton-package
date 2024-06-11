<?php

declare(strict_types=1);

namespace Yard\SkeletonPackage\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app)
    {
        return [
            'Yard\SkeletonPackage\ExampleServiceProvider',
        ];
    }
}
