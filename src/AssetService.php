<?php

declare(strict_types=1);

namespace Yard\SkeletonPackage;

use function Yard\WordPressPackageInstaller\package_path;
use function Yard\WordPressPackageInstaller\package_url;

class AssetService
{
	private const PACKAGE = 'yard/skeleton-package';

	private const HANDLE_PREFIX = 'skeleton-package-';

	public function enqueue(string $name): void
	{
		$asset = $this->dependencyAsset($name);
		$scriptUrl = $this->url("/public/{$name}.js");
		$styleUrl = $this->url("/public/{$name}.css");

		if (null === $asset || null === $scriptUrl || null === $styleUrl) {
			return;
		}

		$handle = self::HANDLE_PREFIX . $name;

		wp_enqueue_script($handle, $scriptUrl, $asset['dependencies'], $asset['version'], true);

		wp_enqueue_style($handle, $styleUrl, [], $asset['version']);
		wp_style_add_data($handle, 'rtl', 'replace');
	}

	public function path(?string $path = null): ?string
	{
		return package_path(self::PACKAGE, $path);
	}

	public function url(?string $path = null): ?string
	{
		return package_url(self::PACKAGE, $path);
	}

	/**
	 * @return array{dependencies: array<int, string>, version: string}|null
	 */
	private function dependencyAsset(string $name): ?array
	{
		$assetPath = $this->path("/public/{$name}.asset.php");

		if (null === $assetPath || ! file_exists($assetPath)) {
			return null;
		}

		return require $assetPath;
	}
}
