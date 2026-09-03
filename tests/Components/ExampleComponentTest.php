<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Yard\SkeletonPackage\AssetService;

it('renders the example component with the title passed to the tag', function () {
	$this->mock(AssetService::class)->shouldIgnoreMissing();

	$html = Blade::render('<x-skeleton-package-example-component title="Hello from the tag" />', deleteCachedView: true);

	expect($html)
		->toContain('skeleton-example-component')
		->toContain('Hello from the tag');
});
