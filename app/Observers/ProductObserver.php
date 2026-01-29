<?php

namespace App\Observers;

use App\Models\Product;
use App\Support\Feeds\MarkYandexFeedDirty;

class ProductObserver
{
    public function saved(Product $product): void
    {
        MarkYandexFeedDirty::mark();
    }

    public function deleted(Product $product): void
    {
        MarkYandexFeedDirty::mark();
    }

    public function restored(Product $product): void
    {
        MarkYandexFeedDirty::mark();
    }
}
