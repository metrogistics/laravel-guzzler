<?php

namespace Guzzler\Facades;

use Illuminate\Support\Facades\Facade;

class Guzzler extends Facade
{
    protected static function getFacadeAccessor()
    {
        return static::$app[\Guzzler\Guzzler::class];
    }
}
