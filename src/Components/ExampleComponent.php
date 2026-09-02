<?php

declare(strict_types=1);

namespace Yard\SkeletonPackage\Components;

use Brain\Assets\Assets as BrainAssets;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\Component;
use Illuminate\View\View;

class ExampleComponent extends Component
{
	/**
	 * Create a new Example instance.
	 */
	public function __construct() {}

	/**
	 * Retrieve a random inspirational quote.
	 */
	public function getQuote(): string
	{
		return config('skeleton-package.quotes');
	}

	public function render(): Factory|View
	{
		$this->enqueueAssets();
		return view('skeleton-package::example');
	}

	private function enqueueAssets(): void
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
