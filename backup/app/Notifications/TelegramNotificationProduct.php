<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramNotificationProduct extends Notification
{
    protected array $message;

    public function __construct(array $message) { $this->message = $message; }

    public function via($notifiable): array { return [TelegramChannel::class]; }

    public function toTelegram($notifiable)
    {
        $chatId = config('services.telegram-bot-api.chat_id');
        $token  = config('services.telegram-bot-api.token');
        if (!$chatId || !$token) {
            throw new \RuntimeException('Telegram chat_id or token is not set in services.telegram-bot-api.');
        }

        $products_message = '';
        if (!empty($this->message['products'])) {
            $productsGrouped = collect($this->message['products'])->groupBy('id');
            foreach ($productsGrouped as $group) {
                $title = $group->first()->title ?? '';
                $products_message .= "\n— {$title}";
            }
        }

        $text = "📦 Новая — ".($this->message['subject'] ?? 'Заявка на детали')."\n"
              . "🧑‍💼 Имя — ".($this->message['name'] ?? '—')."\n"
              . "📞 Телефон — ".($this->message['phone'] ?? '—')."\n"
              . "=================================\n"
              . "🛠️ Детали:".$products_message."\n"
              . "=================================\n"
              . "🚗 Транспорт — ".($this->message['car'] ?? '—')."\n"
              . "📈 Общая стоимость — ".(($this->message['total_price'] ?? '—'))." руб.";

        return TelegramMessage::create()
            ->token($token) // <-- явно укажем токен
            ->to($chatId)
            ->content($text)
            ->options(['disable_web_page_preview' => true]);
    }
}
