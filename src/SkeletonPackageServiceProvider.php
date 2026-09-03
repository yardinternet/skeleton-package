<?php

declare(strict_types=1);

namespace Yard\SkeletonPackage;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Yard\SkeletonPackage\Components\ExampleComponent;
use Yard\SkeletonPackage\AdminService;
use Yard\SkeletonPackage\Console\ExampleCommand;
use Yard\SkeletonPackage\BlockService;

class SkeletonPackageServiceProvider extends PackageServiceProvider
{
	private const COMPONENTS = [
		ExampleComponent::class,
	];

	private const COMMANDS = [
		ExampleCommand::class,
	];

	private const SERVICES = [
		AdminService::class,
		BlockService::class,
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

	public function packageRegistered(): void {}

	public function packageBooted(): void
	{
		foreach (self::SERVICES as $service) {
			$this->app->make($service)->register();
		}
	}
}
