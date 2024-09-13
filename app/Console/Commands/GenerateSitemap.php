<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\Car;
use App\Models\CarMake;
use App\Models\CarModel;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'sitemap:generate';
  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Automatically Generate an XML Sitemap';
  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    $postsitmap = Sitemap::create();




    $postsitmap->add(
      Url::create('/')
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
        ->setLastModificationDate(now()->format('Y-m-d'))
        ->setPriority(1.0)
    );

    CarMake::get()->each(function ($post) use ($postsitmap) {
      $postsitmap->add(
        Url::create("/katalog/{$post->slug}")
        Url::create("katalog/{$post->slug}")
          ->setPriority(0.9)
          ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
          ->setLastModificationDate($post->updated_at->format('Y-m-d'))
          ->setPriority(0.8)
      );
    });

    CarModel::get()->each(function ($post) use ($postsitmap) {
      $postsitmap->add(
        Url::create("/katalog/{$post->car_make->slug}/{$post->slug}")
        Url::create("katalog/{$post->car_make->slug}/{$post->slug}")
          ->setPriority(0.9)
          ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
          ->setLastModificationDate($post->updated_at->format('Y-m-d'))
          ->setPriority(0.8)
      );
    });

    Car::get()->each(function ($post) use ($postsitmap) {
      $postsitmap->add(
        Url::create("/katalog/{$post->car_model->car_make->slug}/{$post->car_model->slug}/{$post->slug}")
        Url::create("katalog/{$post->car_model->car_make->slug}/{$post->car_model->slug}/{$post->slug}")
          ->setPriority(0.9)
          ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
          ->setLastModificationDate($post->updated_at->format('Y-m-d'))
          ->setPriority(0.8)
      );
    });

    Blog::get()->each(function ($post) use ($postsitmap) {
      $postsitmap->add(
        Url::create("/blog/{$post->slug}")
        Url::create("blog/{$post->slug}")
          ->setPriority(0.9)
          ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
          ->setLastModificationDate($post->updated_at->format('Y-m-d'))
          ->setPriority(0.8)
      );
    });

    $postsitmap->writeToFile(public_path('sitemap.xml'));
  }
}
