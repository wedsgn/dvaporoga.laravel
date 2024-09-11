<?php

namespace App\Notifications;

use NotificationChannels\Telegram\TelegramMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;

class TelegramNotificationProduct extends Notification
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
      $products_message = "";
      if(isset($this->message['products']) && !empty($this->message['products'])) {
          $productsGrouped = collect($this->message['products'])->groupBy('id');
          foreach ($productsGrouped as $productId => $products) {
              $products_message .= "
" . $products->first()->title . "";
          }
      }
      $message = "📣 Новая - " . $this->message['subject'] . "
🧑‍💼 Имя - " . $this->message['name'] . "
📞 Телефон -  " . $this->message['phone'] . "
=================================
🛠️ 🔩 Детали: " . $products_message . "
=================================
🚗 Транспорт - " . $this->message['car'] . "
📈 Общая стоимость - " . $this->message['total_price'] . "руб.";
      return TelegramMessage::create()
          ->to(env('TELEGRAM_CHAT_ID'))
          ->content(" $message ");
  }
}
