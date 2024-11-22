#!/usr/bin/env php
<?php

declare(strict_types=1);

function ask(string $question, string $default = ''): string
{
	$answer = readline($question.($default ? " ({$default})" : null).': ');

	if (! $answer) {
		return $default;
	}

	return $answer;
}

function confirm(string $question, bool $default = false): bool
{
	$answer = ask($question.' ('.($default ? 'Y/n' : 'y/N').')');

	if (! $answer) {
		return $default;
	}

	return strtolower($answer) === 'y';
}

function writeln(string $line): void
{
	echo $line.PHP_EOL;
}

function run(string $command): string
{
	return trim((string) shell_exec($command));
}

function str_after(string $subject, string $search): string
{
	$pos = strrpos($subject, $search);

	if (false === $pos) {
		return $subject;
	}

	return substr($subject, $pos + strlen($search));
}

function slugify(string $subject): string
{
	return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $subject), '-'));
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

function replaceForWindows(): array
{
	return preg_split('/\\r\\n|\\r|\\n/', run('dir /S /B * | findstr /v /i .git\ | findstr /v /i vendor | findstr /v /i '.basename(__FILE__).' | findstr /r /i /M /F:/ ":package :class Example SkeletonPackage"'));
}

function replaceForAllOtherOSes(): array
{
	return explode(PHP_EOL, run('grep -E -r -l -i ":class|:package|Example|SkeletonPackage" --exclude-dir=vendor ./* ./.github/* | grep -v '.basename(__FILE__)));
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
$className = ask('Class name', $nameSpace);
$classSlug = slugify($className);
$className = title_case($className);

$description = ask('Package description', "This is my package {$packageName}");

writeln('------');
writeln("Package    : {$packageSlug} <{$description}>");
writeln("Namespace  : Yard\\{$nameSpace}");
writeln("Class name : {$className}");
writeln('------');

writeln('This script will replace the above values in all relevant files in the project directory.');

if (! confirm('Modify files?', true)) {
	exit(1);
}

$files = (str_starts_with(strtoupper(PHP_OS), 'WIN') ? replaceForWindows() : replaceForAllOtherOSes());

foreach ($files as $file) {
	replace_in_file($file, [
		':package_name' => $packageName,
		'skeleton-package' => $packageSlug,
		'Example' => $className,
		'SkeletonPackage' => $nameSpace,
		'example' => $classSlug,
		':package_description' => $description,
	]);

	match (true) {
		str_contains($file, determineSeparator('src/Example.php')) => rename($file, determineSeparator('./src/'.$className.'.php')),
		str_contains($file, determineSeparator('src/SkeletonPackageServiceProvider.php')) => rename($file, determineSeparator('./src/'.$nameSpace.'ServiceProvider.php')),
		str_contains($file, determineSeparator('src/Console/ExampleCommand.php')) => rename($file, determineSeparator('./src/Console/'.$className.'Command.php')),
		str_contains($file, determineSeparator('src/Facades/Example.php')) => rename($file, determineSeparator('./src/Facades/'.$className.'.php')),
		str_contains($file, determineSeparator('resources/views/example.blade.php')) => rename($file, determineSeparator('./resources/views/'.$classSlug.'.blade.php')),
		str_contains($file, determineSeparator('tests/ExampleTest.php')) => rename($file, determineSeparator('./tests/'.$className.'Test.php')),
		str_contains($file, determineSeparator('tests/Console/ExampleCommandTest.php')) => rename($file, determineSeparator('./tests/Console/'.$className.'CommandTest.php')),
		str_contains($file, determineSeparator('tests/Facades/ExampleTest.php')) => rename($file, determineSeparator('./tests/Facades/'.$className.'Test.php')),
		str_contains($file, 'README.md') => remove_readme_paragraphs($file),
		default => [],
	};
}

rename(determineSeparator('./config/skeleton-package.php'), determineSeparator('./config/'.$packageSlug.'.php'));

confirm('Execute `composer install`?') && run('composer install');

confirm('Let this script delete itself?', true) && unlink(__FILE__);
