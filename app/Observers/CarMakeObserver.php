<?php

namespace App\Observers;

use App\Models\CarMake;
use App\Support\Feeds\MarkYandexFeedDirty;

class CarMakeObserver
{
    public function saved(CarMake $carMake): void
    {
        MarkYandexFeedDirty::mark();
    }

    public function deleted(CarMake $carMake): void
    {
        MarkYandexFeedDirty::mark();
    }

    public function restored(CarMake $carMake): void
    {
        MarkYandexFeedDirty::mark();
    }
}
