<?php

declare(strict_types=1);

namespace Yard\SkeletonPackage;

use function Yard\WordPressPackageInstaller\package_path;
use function Yard\WordPressPackageInstaller\package_url;

class AdminService
{
	private const PACKAGE = 'yard/skeleton-package';
	private const HANDLE = 'skeleton-package-admin';

	public function register(): void
	{
		add_action('admin_enqueue_scripts', $this->enqueueAdminAssets(...));
	}

	private function enqueueAdminAssets(): void
	{
		$asset = $this->getDependencyAsset();
		$scriptUrl = package_url(self::PACKAGE, '/public/admin.js');
		$styleUrl = package_url(self::PACKAGE, '/public/admin.css');

		if (null === $asset || null === $scriptUrl || null === $styleUrl) {
			return;
		}

		wp_enqueue_script(self::HANDLE, $scriptUrl, $asset['dependencies'], $asset['version'], true);

		wp_enqueue_style(self::HANDLE, $styleUrl, [], $asset['version']);
		wp_style_add_data(self::HANDLE, 'rtl', 'replace');
	}

	/**
	 * @return array{dependencies: array<int, string>, version: string}|null
	 */
	private function getDependencyAsset(): ?array
	{
		$assetPath = package_path(self::PACKAGE, '/public/admin.asset.php');

		if (null === $assetPath || ! file_exists($assetPath)) {
			return null;
		}

		return require $assetPath;
	}
}
