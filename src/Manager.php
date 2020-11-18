<?php
namespace Gateway\Sms;

use Gateway\Sms\Drivers\Farazsms;
use Gateway\Sms\Drivers\Log;
use Illuminate\Support\Manager as BaseManager;

class Manager extends BaseManager
{
    public function createLogDriver()
    {
        return new Log(config('sms.drivers.log'));
    }

    public function createFarazsmsDriver()
    {
        return new Farazsms(config('sms.drivers.farazsms'));
    }

    public function getDefaultDriver()
    {
        return config('sms.default');
    }
}