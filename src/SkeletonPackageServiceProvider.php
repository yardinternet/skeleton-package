<?php

declare(strict_types=1);

namespace Yard\SkeletonPackage;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Yard\SkeletonPackage\Components\ExampleComponent;
use Yard\SkeletonPackage\Console\ExampleCommand;

class SkeletonPackageServiceProvider extends PackageServiceProvider
{
	private const COMPONENTS = [
		ExampleComponent::class,
	];

	private const COMMANDS = [
		ExampleCommand::class,
	];

	private const PROVIDERS = [
		AdminServiceProvider::class,
		BlockServiceProvider::class,
	];

	public function configurePackage(Package $package): void
	{
		$package
			->name('skeleton-package')
			->hasConfigFile()
			->hasViews()
			->hasViewComponents('skeleton-package', ...self::COMPONENTS)
			->hasCommands(...self::COMMANDS);
	}

	public function packageRegistered(): void
	{
		foreach (self::PROVIDERS as $provider) {
			$this->app->register($provider);
		}
	}
}
