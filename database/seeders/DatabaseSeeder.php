<?php

namespace Database\Seeders;

use App\Models\MainInfo;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    MainInfo::truncate();

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
      'name' => 'Test User',
      'password' => Hash::make('aspire5745g'),
      'email' => 'test@example.com',
    ]);
  }
}
