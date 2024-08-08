<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\MainInfo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    MainInfo::truncate();
    Blog::truncate();

    $pages = [
      [
        'company_title' => 'Два Порога',
        'company_details' => 'Тут надо указать реквизиты компании (ИНН, ОГРН, Адрес)',
        'phone' => '8 800 560 12 12',
        'whats_app'=> 'whats_app',
        'telegram' => 'telegram'
      ],
    ];

    foreach ($pages as $key => $value) {
      MainInfo::create($value);
    }

    User::factory()->create([
      'name' => 'Anton',
      'password' => Hash::make('sYn7Dj0lff'),
      'email' => 'a.rodionov14@gmail.com',
    ]);

    $blogs = Blog::factory()->count(50)->make()->each(function ($blog) {
      $blog->description_short = Str::words($blog->description, 15);
      $blog->description = Str::words(implode(' ', array_fill(0, 100, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam, quidem.')), 100);
      $blog->save();
    });
  }
}

