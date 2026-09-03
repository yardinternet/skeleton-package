<?php

declare(strict_types=1);

namespace Yard\SkeletonPackage;

use Illuminate\Support\ServiceProvider;
use Yard\SkeletonPackage\Blocks\ExampleBlock;

class BlockServiceProvider extends ServiceProvider
{
	private AssetService $assets;

	private const RENDERERS = [
		'yard/example-dynamic' => ExampleBlock::class,
	];

	public function boot(): void
	{
		$this->assets = $this->app->make(AssetService::class);

		add_filter('plugins_url', $this->blockAssetUrl(...), 10, 3);
		add_filter('block_type_metadata_settings', $this->addRenderCallback(...), 10, 2);
		add_action('init', $this->registerBlocks(...));
	}

	private function registerBlocks(): void
	{
		$blocksPath = $this->assets->path('/public');
		$manifestPath = $this->assets->path('/public/blocks-manifest.php');

		if (null === $blocksPath || null === $manifestPath) {
			return;
		}

		wp_register_block_types_from_metadata_collection($blocksPath, $manifestPath);
	}

	/**
	 * @param array<string, mixed> $settings
	 * @param array<string, mixed> $metadata
	 *
	 * @return array<string, mixed>
	 */
	private function addRenderCallback(array $settings, array $metadata): array
	{
		$renderer = self::RENDERERS[$metadata['name'] ?? ''] ?? null;

		if (null === $renderer) {
			return $settings;
		}

		$settings['render_callback'] = fn (array $attributes, string $content, \WP_Block $block): string
		=> app($renderer)->render($attributes, $content, $block);

		return $settings;
	}

	/**
	 * block.json asset paths resolve through core's get_block_asset_url(), which only knows
	 * wp-includes and the theme directories and falls back to plugins_url() for everything
	 * else. plugin_basename() there strips only a WP_PLUGIN_DIR / WPMU_PLUGIN_DIR prefix, so
	 * for a package installed outside both nothing is stripped and the whole absolute path
	 * gets glued onto WP_PLUGIN_URL:
	 *
	 *     https://site.test/app/plugins/Users/me/site/app/packages-src/pkg/public/index.js
	 *
	 * Core has no block asset URL filter, so plugins_url() is the only interception point;
	 * $plugin is the asset's absolute path and is remapped while the broken $url is dropped.
	 * The guards keep other plugins' URLs untouched, since this filter fires globally.
	 */
	private function blockAssetUrl(string $url, string $path, string $plugin): string
	{
		$packagePath = $this->assets->path();
		$plugin = wp_normalize_path($plugin);

		if (null === $packagePath || ! str_starts_with($plugin, $packagePath . '/')) {
			return $url;
		}

		return $this->assets->url(substr($plugin, strlen($packagePath))) ?? $url;
	}
}
