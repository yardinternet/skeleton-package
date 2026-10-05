<?php

declare(strict_types=1);

namespace Yard\SkeletonPackage\Components;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\Component;
use Illuminate\View\View;
use Yard\SkeletonPackage\AssetService; // @asset

class ExampleComponent extends Component
{
	public function __construct(
		private AssetService $assets, // @asset
		public ?string $title = 'Default example component title',
	) {
	}

	public function render(): Factory|View
	{
		$this->assets->enqueue('example-component'); // @asset

		return view('skeleton-package::components/example-component');
	}
}
