<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Feeds\YandexFeedBuilder;

class BuildYandexFeed extends Command
{
    protected $signature = 'feed:yandex';
    protected $description = 'Build Yandex Market YML feed';

    public function handle(YandexFeedBuilder $builder): int
    {
        $builder->build();

        $this->info('Yandex feed generated');
        return self::SUCCESS;
    }
}
