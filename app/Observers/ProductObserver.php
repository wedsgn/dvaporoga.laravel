<?php

namespace App\Observers;

use App\Models\Product;
use App\Support\Feeds\RebuildYandexFeed;

class ProductObserver
{
    public function saved(Product $product): void
    {
        RebuildYandexFeed::run();
    }

    public function deleted(Product $product): void
    {
        RebuildYandexFeed::run();
    }

    public function restored(Product $product): void
    {
        RebuildYandexFeed::run();
    }
}
