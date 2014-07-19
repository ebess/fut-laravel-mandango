<?php namespace Fut\Mandango;

use Illuminate\Support\ServiceProvider;
use Model\Mapping\MetadataFactory;


class MandangoServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('fut/mandango');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this
			->registerMandango()
			->registerMondator()
			->registerCommand();
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('mandango', 'mandango.mondator');
	}

	/**
	 * register the mandango command
	 * 
	 * @return $this
	 */
	private function registerCommand()
	{
		$this->app->bindShared('mandango::command.flush', function(){
			return new \Fut\Mandango\FlushCommand();
		});

		$this->app->bindShared('mandango::command.install', function(){
			return new \Fut\Mandango\InstallCommand();
		});

		$this->commands('mandango::command.flush', 'mandango::command.install');

		return $this;
	}

	/**
	 * register the singleton mondator of the mandango package
	 * 
	 * @return $this
	 */
	private function registerMondator()
	{
		$self = $this;

		// register the mondator
		$this->app->singleton('mandango.mondator', function() use ($self) {

			$mondator = new \Mandango\Mondator\Mondator();

			// set the schema
			$mondator->setConfigClasses($self->getSchema());

			// set the extension
			$mondator->setExtensions(array(
			    new \Mandango\Extension\Core(array(
			        'metadata_factory_class'  => 'Model\Mapping\MetadataFactory',
			        'metadata_factory_output' => $self->getModelDir().'/Mapping',
			        'default_output'          => $self->getModelDir(),
			    )),
			));

			return $mondator;
		});

		return $this;
	}

	/**
	 * register the singleton mandago instance
	 * 
	 * @return $this
	 */
	private function registerMandango()
	{

		$self = $this;

		// bind mandango cache
		$this->app->bind('Mandango\Cache\CacheInterface', function() use ($self) {

			return new \Mandango\Cache\FilesystemCache(
				$self->getCacheDir()
			);
		});

		// bind the mandango instance
		$this->app->bind('Mandango\Mandango', function() use ($self) {

			return new \Mandango\Mandango(
				new MetadataFactory(), 
				$self->app->make('Mandango\Cache\CacheInterface')
			);
		});

		// bind the mandango ioc container
		$this->app->singleton('mandango', function() use ($self) {

			// get the mandango instance
			$mandango = $self->app->make('Mandango\Mandango');

			// inject the connections
			foreach ($self->getConnectionsConfig() as $connectionName => $connectionData) {
				$connection = new \Mandango\Connection(
					$this->factorConnectionString($connectionData),
					$connectionData['database'],
					$connectionData['options']
				);

				$mandango->setConnection($connectionName, $connection);
			}

			// set default connection
			$mandango->setDefaultConnectionName($this->getDefaultConnection());

			return $mandango;
		});

		return $this;
	}

	/**
	 * returns the connection string for given configs
	 *
	 * @param  string[] $config
	 * @return string
	 */
	private function factorConnectionString($config)
	{
		return 'mongodb://' .
			$config['username'] . ':' .
			$config['password'] . '@' . 
			$config['host'] . ':'  .
			$config['port'] .
			(isset($config['authdatabase']) && !empty($config['authdatabase']) ? '/' . $config['authdatabase'] : '');
	}

	/**
	 * get the mongo db schema
	 * 
	 * @return array
	 */
	private function getSchema()
	{
		return \Config::get('mandango::schema');
	}

	/**
	 * return the model dir of mandango
	 * 
	 * @return string
	 */
	private function getModelDir()
	{
		return \Config::get('mandango::model_dir');
	}

	/**
	 * get the mandango cache dir
	 * 
	 * @return string
	 */
	private function getCacheDir()
	{
		return \Config::get('mandango::cache_dir');
	}

	/**
	 * returns all connection configs
	 * 
	 * @return string
	 */
	private function getConnectionsConfig()
	{
		return \Config::get('mandango::connections');
	}


	/**
	 * returns the name of the default connection
	 * 
	 * @return string
	 */
	private function getDefaultConnection()
	{
		return \Config::get('mandango::default_connection');
	}
}
