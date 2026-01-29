<?php

namespace App\Observers;

use App\Models\Car;
use App\Support\Feeds\MarkYandexFeedDirty;

class CarObserver
{
    public function saved(Car $car): void
    {
        MarkYandexFeedDirty::mark();
    }

    public function deleted(Car $car): void
    {
        MarkYandexFeedDirty::mark();
    }

    public function restored(Car $car): void
    {
        MarkYandexFeedDirty::mark();
    }
}
