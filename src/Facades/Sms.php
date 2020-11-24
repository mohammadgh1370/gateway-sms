<?php
namespace Gateway\Sms\Facades;

use Illuminate\Support\Facades\Facade;

class Sms extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sms';
    }

    protected static function resolveFacadeInstance($name)
    {
        return static::$app[$name];
    }
}