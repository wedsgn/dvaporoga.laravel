<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProductImageDownloadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 120;

    public function __construct(
        public int $runId,
        public int $carId,
        public int $productId,
        public string $url
    ) {}

    public function handle(): void
    {
        $url = trim($this->url);
        if ($url === '' || !preg_match('~^https?://~i', $url)) {
            return;
        }

        $resp = Http::timeout(30)->retry(2, 500)->get($url);
        if (!$resp->ok()) return;

        $bin = $resp->body();
        if ($bin === '' || strlen($bin) < 100) return;

        $path = 'products/' . $this->carId . '/' . $this->productId . '.jpg';
        Storage::disk('public')->put($path, $bin);

        DB::table('car_product')
            ->where('car_id', $this->carId)
            ->where('product_id', $this->productId)
            ->update([
                'image' => $path,
                'updated_at' => now(),
            ]);
    }
}
