fut-laravel-mandango
====================

Mandango package for laravel

Installation:

Add to compposer.json

```
	"require": {
        "fut/mandango": "dev-master"
	},
	"autoload": {
		"classmap": [
			"app/mandango"
		]
	},
```

config/app.php

```php
	'providers' => array(
		'Fut\Mandango\MandangoServiceProvider'
	),
	'aliases' => array(
		'Mandango'			=> 'Fut\Mandango\Mandango',
		'Mandango\Mondator'	=> 'Fut\Mandango\Mondator'
	)
```