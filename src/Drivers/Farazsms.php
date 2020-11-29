<?php

namespace Gateway\Sms\Drivers;

use Gateway\Sms\Channels\SmsMessage;
use Gateway\Sms\Contracts\DriverInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Farazsms implements DriverInterface
{
    /** @var Client $client */
    protected $client;

    protected $config;

    protected $recipients;

    /** @var SmsMessage $message */
    protected $message;

    public function __construct($config)
    {
        $this->config = $config;
        $this->client = new Client([
            'base_uri' => $this->config['url'],
            'headers'  => [
                'Authorization' => 'AccessKey ' . $this->config['api_key'],
                'Content-Type'  => 'application/json'
            ]
        ]);
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

    public function send()
    {
        $response = collect();

        $url = '/v1/messages';
        $is_pattern_code = false;
        if (!empty($this->message->getPatternCode())) {
            $is_pattern_code = true;
            $url = $url . '/patterns/send';
        }
        foreach ($this->recipients as $recipient) {
            try {
                $result = $this->client->request('POST', $url, [
                    'body' => $this->payload($recipient, $is_pattern_code)
                ]);
                $response->put($recipient, json_decode($result->getBody()->getContents(), true));
            } catch (ClientException $exception) {
                $response->put($recipient, json_decode($exception->getResponse()->getBody()->getContents(), true));
            }
        }

        return $response;
    }

    private function payload($recipient, $is_pattern_code)
    {
        if ($is_pattern_code) {
            $array = [
                'pattern_code' => $this->message->getPatternCode(),
                'originator'   => data_get($this->config, 'from'),
                'recipient'    => $recipient,
                'values'       => $this->message->getPatternData()
            ];
        } else {
            $array = [
                "originator" => data_get($this->config, 'from'),
                "recipients" => [
                    $recipient,
                ],
                "message"    => $this->message->getContent()
            ];

        }
        return json_encode($array);
    }

}
