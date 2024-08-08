<?php

namespace App\Notifications;

use NotificationChannels\Telegram\TelegramMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;

class TelegramNotificationConsultation extends Notification
{
  protected $message;

  public function __construct($message)
  {
      $this->message = $message;
  }
  public function via($notifiable)
  {
      return [TelegramChannel::class];
  }
  public function toTelegram($notifiable)
  {
      $message = "📣 Новая - " . $this->message['subject'] . "
🧑‍💼 Имя - " . $this->message['name'] . "
📞 Телефон -  " . $this->message['phone'] . "";
      return TelegramMessage::create()
          ->to(env('TELEGRAM_CHAT_ID'))
          ->content(" $message ");
  }
}
