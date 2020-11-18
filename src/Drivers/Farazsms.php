<?php

namespace Gateway\Sms\Drivers;

use Gateway\Sms\Channels\SmsMessage;
use Gateway\Sms\Contracts\DriverInterface;
use Gateway\Sms\HTTPClient;

class Farazsms implements DriverInterface
{
    protected $client;
    protected $config;
    protected $recipients;
    protected $message;

    public function __construct($config)
    {
        $this->config = $config;
        $this->client = new HTTPClient($this->config['url'], 30, [
            sprintf("Authorization: AccessKey %s", $this->config['api_key']),
        ]);
    }

    public function send()
    {
        $response = collect();

        $is_pattern_code = false;
        $url = '/v1/messages';
        if (!empty($this->message->getPatternCode()) && !empty($this->message->getPatternData())) {
            $is_pattern_code = true;
            $url = $url . '/patterns/send';
        }
        foreach ($this->recipients as $recipient) {
            try {
                $result = $this->client->post($url, $this->payload($recipient, $is_pattern_code));
                $response->put($recipient, $result);
            } catch (\Exception $exception) {
                $response->put($recipient, $exception->getMessage());
            }
        }

        return $response;
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

    protected function payload($recipient, $is_pattern_code)
    {
        if ($is_pattern_code) {
            $array = [
                'pattern_code' => $this->message->getPatternCode(),
                'values'       => $this->message->getPatternData(),
                'recipient'    => $recipient,
                'originator'   => data_get($this->config, 'from'),
            ];
        } else {
            $array = [
                'message'    => $this->message->getContent(),
                'op'         => 'send',
                'recipients' => [$recipient],
                'originator' => data_get($this->config, 'from'),
            ];
        }
        return $array;
    }

}
