<?php

declare(strict_types=1);

namespace Yard\SkeletonPackage;

use Brain\Assets\Assets as BrainAssets;

class AssetService
{
	public function register(): void
	{
		add_action('enqueue_block_assets', $this->enqueueEditor(...)); // Or any other appropriate hook for enqueuing assets
	}

	private function enqueueEditor(): void
	{
		$assets = BrainAssets::forLibrary(
			'skeleton-package',
			package_path('yard/skeleton-package', '/public/'), //@phpstan-ignore argument.type
			package_url('yard/skeleton-package', '/public/') // @phpstan-ignore argument.type
		)->useDependencyExtractionData();

		$assets->enqueueScript('example');
		$assets->enqueueStyle('example');
	}
}
