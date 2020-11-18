<?php

namespace Gateway\Sms\Channels;

use Illuminate\Support\Arr;

class SmsMessage
{
    /**
     * The phone number the message should be sent to.
     *
     * @var string
     */
    private $recipients = [];

    /**
     * The message content.
     *
     * @var string
     */
    private $content;

    /**
     * The phone number the message should be sent from.
     *
     * @var string
     */
    private $from;

    /**
     * The driver sms.
     *
     * @var string
     */
    private $driver;

    /**
     * The pattern code of sms.
     *
     * @var string
     */
    private $pattern_code;

    /**
     * The pattern data of sms.
     *
     * @var string
     */
    private $pattern_data;

    /**
     * Create a new message instance.
     *
     * @param string $content
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * Set the message content.
     *
     * @param string $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set the phone number the message should be sent from.
     *
     * @param bool $from
     * @return $this
     */
    public function from($from = false)
    {
        $this->from = $from;

        return $this;
    }

    public function to($recipients)
    {
        $this->recipients = Arr::wrap($recipients);

        return $this;
    }

    public function via($driver)
    {
        $this->driver = $driver;

        return $this;
    }

    public function patternCode($pattern_code)
    {
        $this->pattern_code = $pattern_code;

        return $this;
    }

    public function patternData($pattern_data)
    {
        $this->pattern_data = $pattern_data;

        return $this;
    }

    public function getRecipients()
    {
        return $this->recipients;
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getPatternCode()
    {
        return $this->pattern_code;
    }

    public function getPatternData()
    {
        return $this->pattern_data;
    }

    public function toArray()
    {
        return call_user_func('get_object_vars', $this);
    }
}
