<?php

declare(strict_types=1);

namespace Yard\SkeletonPackage;

class AdminService
{
	public function __construct(
		private AssetService $assets,
	) {
	}

	public function register(): void
	{
		add_action('admin_enqueue_scripts', fn () => $this->assets->enqueue('admin'));
	}
}
