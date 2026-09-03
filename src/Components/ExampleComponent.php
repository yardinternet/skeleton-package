<?php

declare(strict_types=1);

namespace Yard\SkeletonPackage\Components;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\Component;
use Illuminate\View\View;
use Yard\SkeletonPackage\AssetService;

class ExampleComponent extends Component
{
	public function __construct(
		private AssetService $assets,
		public ?string $title = 'Default example component title',
	) {
	}

	public function render(): Factory|View
	{
		$this->assets->enqueue('example-component');

		return view('skeleton-package::components/example-component');
	}
}
