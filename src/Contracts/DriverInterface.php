<?php

namespace Gateway\Sms\Contracts;

use Gateway\Sms\Channels\SmsMessage;

interface DriverInterface
{
    public function message(SmsMessage $message);
    public function to($recipients);
    public function send();
}