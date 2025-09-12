<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramNotificationConsultation extends Notification
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

        $text = "📣 Новая — ".($this->message['subject'] ?? 'Заявка')."\n"
              . "🧑‍💼 Имя — ".($this->message['name'] ?? '—')."\n"
              . "📞 Телефон — ".($this->message['phone'] ?? '—');

        return TelegramMessage::create()
            ->token($token)
            ->to($chatId)
            ->content($text)
            ->options(['disable_web_page_preview' => true]);
    }
}
