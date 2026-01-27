<?php

namespace App\Observers;

use App\Models\CarModel;
use App\Support\Feeds\RebuildYandexFeed;

class CarModelObserver
{
    public function saved(CarModel $model): void
    {
        RebuildYandexFeed::run();
    }

    public function deleted(CarModel $model): void
    {
        RebuildYandexFeed::run();
    }

    public function restored(CarModel $model): void
    {
        RebuildYandexFeed::run();
    }
}
