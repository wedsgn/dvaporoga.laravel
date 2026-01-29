<?php

namespace App\Observers;

use App\Models\CarModel;
use App\Support\Feeds\MarkYandexFeedDirty;

class CarModelObserver
{
    public function saved(CarModel $carModel): void
    {
        MarkYandexFeedDirty::mark();
    }

    public function deleted(CarModel $carModel): void
    {
        MarkYandexFeedDirty::mark();
    }

    public function restored(CarModel $carModel): void
    {
        MarkYandexFeedDirty::mark();
    }
}
