<?php

declare(strict_types=1);

namespace Yard\SkeletonPackage\Console;

use Illuminate\Console\Command;

class ExampleCommand extends Command
{
	protected $signature = 'example';

	protected $description = 'My custom Acorn command.';

	/**
	 * Execute the console command.
	 */
	public function handle(): void
	{
		$this->info('Example command executed.');
	}
}
