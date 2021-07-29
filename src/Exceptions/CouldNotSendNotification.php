<?php

namespace NotificationChannels\Log\Exceptions;

use NotificationChannels\Log\LogMessage;

class CouldNotSendNotification extends \Exception
{
    public static function invalidLogChannel($logChannel)
    {
        return new static(sprintf('Invalid log channel: %s', $logChannel));
    }

    public static function invalidMessageObject($message)
    {
        $className = is_object($message) ? get_class($message) : 'Unknown';

        return new static(
            "Notification was not sent. Message object class `{$className}` is invalid.
            It should be `" . LogMessage::class . '`'
        );
    }
}
