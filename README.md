# Laravel log notifications channel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hotrush/laravel-log-notification-channel.svg?style=flat-square)](https://packagist.org/packages/hotrush/laravel-log-notification-channel)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/hotrush/laravel-log-notification-channel.svg?style=flat-square)](https://packagist.org/packages/hotrush/laravel-log-notification-channel)

Send notifications into log with Laravel, easier to pretend other services e.g. sms or push notifications.

## Contents

- [Installation](#installation)
	- [Setting up the log service](#setting-up-the-log-service)
- [Usage](#usage)
- [License](#license)


## Installation

```bash
composer require hotrush/laravel-log-notification-channel
```

### Setting up the log service

You can add `LOG_NOTIFICATIONS_CHANNEL` into your `.env` file to customize log channel to use, otherwise default one will be used.  

## Usage

```php
<?php

namespace App\Notifications;

use App\Post;
use Illuminate\Notifications\Notification;
use NotificationChannels\Log\LogChannel;
use NotificationChannels\Twilio\TwilioChannel;

class AuthCodeCreatedNotification extends Notification
{
    /**
     * @var Post
     */
    private $post;

    /**
     * Create a new notification instance.
     *
     * @param Post $post
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return app()->environment('production')
            ? [TwilioChannel::class]
            : [LogChannel::class];
    }

    /**
     * Get the log message representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return LogMessage
     */
    public function toLog($notifiable)
    {
        return new LogMessage('Pretended sms send to :number and with content: :content');
    }
}

```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
