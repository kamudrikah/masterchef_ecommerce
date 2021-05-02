<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class NewUser extends Notification
{
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    public function toTelegram($notifiable)
    {
        // return (new TelegramMessage())
        //     ->content('Hello, world!');

        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->content("Hello there!\nNew user has been created!");
    }
}
