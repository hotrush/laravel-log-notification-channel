<?php

namespace NotificationChannels\Log;

class LogMessage
{
    /**
     * The message content.
     *
     * @var string
     */
    public $content;

    /**
     * Create a message object.
     *
     * @param string $content
     * @return LogMessage
     */
    public static function create($content = '')
    {
        return new static($content);
    }

    /**
     * LogMessage constructor.
     *
     * @param string $content
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }
}
