#!/usr/bin/env php
<?php

declare(strict_types=1);

const ASSET_PATHS = [
	'.husky',
	'.prettierrc.cjs',
	'.stylelintrc.cjs',
	'.github/workflows/npm-lock-diff.yml',
	'build',
	'eslint.config.cjs',
	'node_modules',
	'package.json',
	'pnpm-lock.yaml',
	'pnpm-workspace.yaml',
	'resources/blocks',
	'resources/scripts',
	'resources/styles',
	'resources/views/blocks',
	'src/AdminServiceProvider.php',
	'src/AssetService.php',
	'src/BlockServiceProvider.php',
	'src/Blocks',
	'src/Package.php',
	'tsconfig.json',
	'webpack.config.js',
];

function ask(string $question, string $default = ''): string
{
	$answer = readline($question . ($default ? " ({$default})" : null) . ': ');

	if (! $answer) {
		return $default;
	}

	return $answer;
}

function confirm(string $question, bool $default = false): bool
{
	$answer = ask($question . ' (' . ($default ? 'Y/n' : 'y/N') . ')');

	if (! $answer) {
		return $default;
	}

	return strtolower($answer) === 'y';
}

function writeln(string $line): void
{
	echo $line . PHP_EOL;
}

function run(string $command): string
{
	return trim((string) shell_exec($command));
}

function slugify(string $subject): string
{
	return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $subject), '-'));
}

/**
 * Mirrors Laravel's Str::kebab()
 */
function kebab_case(string $subject): string
{
	$subject = (string) preg_replace('/\s+/u', '', ucwords(str_replace(['-', '_'], ' ', $subject)));

	return strtolower((string) preg_replace('/(.)(?=[A-Z])/u', '$1-', $subject));
}

function title_case(string $subject): string
{
	return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $subject)));
}

function replace_in_file(string $file, array $replacements): void
{
	$contents = file_get_contents($file);

	file_put_contents(
		$file,
		str_replace(
			array_keys($replacements),
			array_values($replacements),
			$contents
		)
	);
}

function determineSeparator(string $path): string
{
	return str_replace('/', DIRECTORY_SEPARATOR, $path);
}

function delete_path(string $path): void
{
	$path = determineSeparator($path);

	if (is_link($path) || is_file($path)) {
		unlink($path);

		return;
	}

	if (! is_dir($path)) {
		return;
	}

	foreach (scandir($path) ?: [] as $entry) {
		if ('.' === $entry || '..' === $entry) {
			continue;
		}

		delete_path($path . DIRECTORY_SEPARATOR . $entry);
	}

	rmdir($path);
}

/**
 * Lines ending in `// @asset` exist only for the asset build: they are dropped when it is declined.
 */
function remove_asset_lines(string $file): void
{
	$lines = file($file) ?: [];

	$contents = implode('', array_filter(
		$lines,
		fn (string $line): bool => ! str_contains($line, '// @asset')
	));

	// An array literal whose every entry was marked collapses to an empty one.
	file_put_contents($file, preg_replace('/\[\n\t*\];/', '[];', $contents) ?? $contents);
}

function strip_asset_markers(string $file): void
{
	replace_in_file($file, [' // @asset' => '']);
}

function replaceForWindows(): array
{
	return preg_split('/\\r\\n|\\r|\\n/', run('dir /S /B * | findstr /v /i .git\ | findstr /v /i vendor | findstr /v /i node_modules | findstr /v /i public\ | findstr /v /i build\ | findstr /v /i ' . basename(__FILE__) . ' | findstr /r /i /M /F:/ ":package :class Example SkeletonPackage @asset"'));
}

function replaceForAllOtherOSes(): array
{
	return explode(PHP_EOL, run('grep -E -r -l -i ":class|:package|Example|SkeletonPackage|@asset" --exclude-dir=vendor --exclude-dir=node_modules --exclude-dir=public --exclude-dir=build --exclude=composer.lock --exclude=pnpm-lock.yaml ./* ./.github/* | grep -v ' . basename(__FILE__)));
}

function remove_readme_paragraphs(string $file): void
{
	$contents = file_get_contents($file);

	file_put_contents(
		$file,
		preg_replace('/<!--delete-->.*<!--\/delete-->/s', '', $contents) ?: $contents
	);
}

$currentDirectory = getcwd();
$folderName = basename($currentDirectory);

$packageName = ask('Package name', $folderName);
$packageSlug = slugify($packageName);

$nameSpace = title_case($packageName);
$nameSpace = ask('Namespace', $nameSpace);
$className = title_case(ask('Class name', $nameSpace));
$classSlug = kebab_case($className);

$description = ask('Package description', "This is my package {$packageName}");

$withAssets = confirm('Include the asset build (JS/CSS/blocks)?', true);

writeln('------');
writeln("Package     : {$packageSlug} <{$description}>");
writeln("Namespace   : Yard\\{$nameSpace}");
writeln("Class name  : {$className}");
writeln('Asset build : ' . ($withAssets ? 'yes' : 'no, asset files and tooling are deleted'));
writeln('------');

writeln('This script will replace the above values in all relevant files in the project directory.');

if (! confirm('Modify files?', true)) {
	exit(1);
}

if (! $withAssets) {
	foreach (ASSET_PATHS as $path) {
		delete_path($path);
	}

	replace_in_file('composer.json', [
		'"type": "wordpress-package"' => '"type": "package"',
	]);
}

delete_path('public');

$files = (str_starts_with(strtoupper(PHP_OS), 'WIN') ? replaceForWindows() : replaceForAllOtherOSes());

foreach ($files as $file) {
	$withAssets ? strip_asset_markers($file) : remove_asset_lines($file);

	replace_in_file($file, [
		':package_name' => $packageName,
		'skeleton-package' => $packageSlug,
		'Example' => $className,
		'SkeletonPackage' => $nameSpace,
		'example' => $classSlug,
		':package_description' => $description,
	]);

	match (true) {
		str_contains($file, determineSeparator('src/SkeletonPackageServiceProvider.php')) => rename($file, determineSeparator('./src/' . $nameSpace . 'ServiceProvider.php')),
		str_contains($file, determineSeparator('src/Components/ExampleComponent.php')) => rename($file, determineSeparator('./src/Components/' . $className . 'Component.php')),
		str_contains($file, determineSeparator('src/Blocks/ExampleBlock.php')) => rename($file, determineSeparator('./src/Blocks/' . $className . 'Block.php')),
		str_contains($file, determineSeparator('src/Console/ExampleCommand.php')) => rename($file, determineSeparator('./src/Console/' . $className . 'Command.php')),
		str_contains($file, determineSeparator('resources/views/components/example-component.blade.php')) => rename($file, determineSeparator('./resources/views/components/' . $classSlug . '-component.blade.php')),
		str_contains($file, determineSeparator('resources/views/blocks/example-block.blade.php')) => rename($file, determineSeparator('./resources/views/blocks/' . $classSlug . '-block.blade.php')),
		str_contains($file, determineSeparator('resources/scripts/example-component.ts')) => rename($file, determineSeparator('./resources/scripts/' . $classSlug . '-component.ts')),
		str_contains($file, determineSeparator('resources/styles/example-component.css')) => rename($file, determineSeparator('./resources/styles/' . $classSlug . '-component.css')),
		str_contains($file, determineSeparator('tests/Components/ExampleComponentTest.php')) => rename($file, determineSeparator('./tests/Components/' . $className . 'ComponentTest.php')),
		str_contains($file, 'README.md') => remove_readme_paragraphs($file),
		default => [],
	};
}

rename(determineSeparator('./config/skeleton-package.php'), determineSeparator('./config/' . $packageSlug . '.php'));

if ($withAssets && is_dir(determineSeparator('./resources/blocks/example-dynamic'))) {
	rename(determineSeparator('./resources/blocks/example-dynamic'), determineSeparator('./resources/blocks/' . $classSlug . '-dynamic'));
}

confirm('Execute `composer install`?') && run('composer install');

if (file_exists(determineSeparator('./vendor/bin/php-cs-fixer'))) {
	writeln('Normalising code style for the new namespace...');
	run('vendor/bin/php-cs-fixer fix --quiet');
} else {
	writeln('Run `composer install && composer format` to normalise import order for the new namespace.');
}

if ($withAssets) {
	confirm('Execute `pnpm install && pnpm build`?', true)
		? run('pnpm install && pnpm build')
		: writeln('Remember to run `pnpm install && pnpm build` before using the package.');
}

confirm('Let this script delete itself?', true) && unlink(__FILE__);
