<?php

namespace App\Observers;

use App\Models\Car;
use App\Support\Feeds\RebuildYandexFeed;

class CarObserver
{
    public function saved(Car $car): void
    {
        RebuildYandexFeed::run();
    }

    public function deleted(Car $car): void
    {
        RebuildYandexFeed::run();
    }

    public function restored(Car $car): void
    {
        RebuildYandexFeed::run();
    }
}
