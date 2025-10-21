<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramNotificationCar extends Notification
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

        $m = $this->message;
        $make  = $m['make']  ?? null;
        $model = $m['model'] ?? null;
        $car   = $m['car']   ?? trim(($make ?: '').' '.($model ?: ''));

        $text = "📣 Новая — " . ($m['subject'] ?? 'Заявка') . "\n"
              . "🚘 Авто — " . ($car ?: '—') . "\n"
              // . "🧑‍💼 Имя — " . ($m['name']  ?? '—') . "\n"
              . "📞 Телефон — " . ($m['phone'] ?? '—');

        // if (!empty($m['form']))       $text .= "\n🗂 Форма — " . $m['form'];
        // if (!empty($m['current_url'])) $text .= "\n🔗 URL — " . $m['current_url'];

        return TelegramMessage::create()
            ->token($token)
            ->to($chatId)
            ->content($text)
            ->options(['disable_web_page_preview' => true]);
    }
}
