<?php

namespace Gateway\Sms\Drivers;

use Gateway\Sms\Channels\SmsMessage;
use Gateway\Sms\Contracts\DriverInterface;

class Log implements DriverInterface
{
    protected $config;
    protected $recipients;
    protected $message;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function send()
    {
        info([
            'to'      => $this->recipients,
            'message' => $this->message,
        ]);

        return null;
    }

    public function message(SmsMessage $message)
    {
        $this->message = $message;
        return $this;
    }

    public function to($recipients)
    {
        $this->recipients = $recipients;
        return $this;
    }
}