<?php

return [
	
	/**
	 * folder configs
	 */
	'model_dir' => app_path() . '/mandango/Model',
	'cache_dir' => app_path() . '/mandango/Cache',

	/**
	 * connection credentials with name of the connection as the array key
	 */
	'connections' => [
		'default' => [
			'host'     		=> 'localhost',
		    'port'     		=> 27017,
		    'username' 		=> 'username',
		    'password' 		=> 'password',
		    'database' 		=> 'database',
		    'authdatabase'	=> 'admin',
		    'options'  		=> []
		]
	],

	/**
	 * default connection which will be used if you don't specify any
	 */
	'default_connection' => 'default',

	/**
	 * mandango specific schema which will be used
	 */
	'schema' => [
	]
	
];