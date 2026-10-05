<?php

declare(strict_types=1);

namespace Yard\SkeletonPackage\Blocks;

use Illuminate\Contracts\View\View;
use WP_Block;

class ExampleBlock
{
	/**
	 * @param array<string, mixed> $attributes
	 */
	public function render(array $attributes, string $content, WP_Block $block): string
	{
		/** @var View $view */
		$view = view('skeleton-package::blocks/example-block', [
			'attributes' => $attributes,
			'content' => $content,
			'block' => $block,
		]);

		return $view->render();
	}
}
