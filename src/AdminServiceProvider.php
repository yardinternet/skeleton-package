<?php

declare(strict_types=1);

namespace Yard\SkeletonPackage;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
	public function boot(): void
	{
		add_action('admin_enqueue_scripts', $this->enqueueAdminAssets(...));
	}

	private function enqueueAdminAssets(): void
	{
		$this->app->make(AssetService::class)->enqueue('admin');
	}
}
