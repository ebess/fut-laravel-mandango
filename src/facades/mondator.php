<?php

namespace Fut\Mandango;

use Illuminate\Support\Facades\Facade;

class Mondator extends Facade
{
	protected static function getFacadeAccessor() { return 'mandango.mondator'; }
}