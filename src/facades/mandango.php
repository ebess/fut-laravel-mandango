<?php

namespace Fut\Mandango;

use Illuminate\Support\Facades\Facade;

class Mandango extends Facade
{
	protected static function getFacadeAccessor() { return 'mandango'; }
}