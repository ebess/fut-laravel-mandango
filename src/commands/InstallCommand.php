<?php

namespace Fut\Mandango;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InstallCommand extends Command {

	/**
	 * marks whether an error has occured
	 * 
	 * @var boolean
	 */
	private $error = false;

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'mandango:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Installs the needed folders & configs for mandango usage.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{

		if ($this->error === true) {
			$this->info('Mandango package is ready to use.');
		} else {
			$this->error('An error has occured.');
		}
	}

	/**
	 * publish the configuration file for mandango
	 *
	 * @return this
	 */
	private function publishMandangoConfigs()
	{
		$this->call('config:publish', array('argument' => 'fut/laravel-mandango'));

		return $this;
	}

	/**
	 * creates the folder where all the generated mandango classes goes
	 * 
	 * @return this
	 */
	private function createMandangoFolder()
	{
		if (!mkdir(app_path() . "/mandango")) {
			$this->error = true;
			$this->error('Failed to create the mandago folder (app/mandango).')
		} else {
			$this->info('Mandango folder created.')
		}

		return $this;
	}

}