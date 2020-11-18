<?php

namespace Gateway\Sms\Channels;

use Exception;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function send($notifiable, Notification $notification)
    {
        /**
         * @psalm-suppress UndefinedMethod
         */
        $message = $notification->toSms($notifiable);

        $this->validate($message);
        $manager = app()->make('sms');

        $driver = $this->config['default'];
        if (!empty($message->getDriver())) {
            $driver = $message->getDriver();
            $manager->via($driver);
        }

        $response = $manager->to($message->getRecipients())->message($message)->send();

        if (method_exists($notification, 'response')) {
            $notification->response($driver, $message->toArray(), $response->toArray());
        }

        return $response;
    }

    private function validate($message)
    {
        $conditions = [
            'Invalid data for sms notification.'           => !is_a($message, SmsMessage::class),
            'Message body could not be empty.'             => empty($message->getPatternCode()) ? empty($message->getContent()) : false,
            'Message recipient could not be empty.'        => empty($message->getRecipients()),
            'Message pattern and data could not be empty.' => !empty($message->getPatternCode()) ? (empty($message->getPatternCode()) && empty($message->getPatternData())) : false,
        ];

        foreach ($conditions as $ex => $condition) {
            throw_if($condition, new Exception($ex));
        }
    }
}
