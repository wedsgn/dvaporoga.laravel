<?php

namespace App\Observers;

use App\Models\CarMake;
use App\Support\Feeds\RebuildYandexFeed;

class CarMakeObserver
{
    public function saved(CarMake $make): void
    {
        RebuildYandexFeed::run();
    }

    public function deleted(CarMake $make): void
    {
        RebuildYandexFeed::run();
    }

    public function restored(CarMake $make): void
    {
        RebuildYandexFeed::run();
    }
}
