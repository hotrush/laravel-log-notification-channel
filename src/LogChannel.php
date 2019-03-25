<?php

namespace NotificationChannels\Log;

use Exception;
use Illuminate\Support\Facades\Log;
use NotificationChannels\Log\Exceptions\CouldNotSendNotification;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;

class LogChannel
{
    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * LogChannel constructor.
     *
     * @param Dispatcher $events
     */
    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Log\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        try {
            $logChannel = $this->getLogChannel();

            if (!$logChannel) {
                throw CouldNotSendNotification::invalidLogChannel($logChannel);
            }

            $message = $notification->toLog($notifiable);

            if (! $message instanceof LogMessage) {
                throw CouldNotSendNotification::invalidMessageObject($message);
            }

            Log::channel($logChannel)->debug($message->content);
        } catch (Exception $exception) {
            $event = new NotificationFailed($notifiable, $notification, 'log', ['message' => $exception->getMessage(), 'exception' => $exception]);
            if (function_exists('event')) { // Use event helper when possible to add Lumen support
                event($event);
            } else {
                $this->events->fire($event);
            }
        }
    }

    /**
     * @return string
     */
    private function getLogChannel()
    {
        return config('LOG_NOTIFICATIONS_CHANNEL', config('logging.default'));
    }
}
