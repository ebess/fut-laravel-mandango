<?php

namespace Fut\Mandango;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MandangoFlushCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'mandango:flush';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Flushes the generated Mandango classes.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		Mondator::process();

		$this->info('Mandango classes were generated.');
	}

}
