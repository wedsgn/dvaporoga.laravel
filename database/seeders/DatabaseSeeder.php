<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\CarMake;
use App\Models\MainInfo;
use App\Models\Order;
use App\Models\Page;
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
    Page::truncate();

    $main_info = [
      [
        'company_title' => 'Два Порога',
        'company_details' => 'Тут надо указать реквизиты компании (ИНН, ОГРН, Адрес)',
        'phone' => '8 800 560 12 12',
        'whats_app' => 'whats_app',
        'telegram' => 'telegram'
      ],
    ];

    $pages = [
      [
        'title_admin' => 'Главная',
        'slug' => 'home',
        'title' => 'Ремонтные <br /><span>пороги</span>

                    и <span>арки</span>',
        'description' => '',
        'meta_title' => 'Кузовные ремонтные арки и пороги для всех моделей авто! | Два порога',
        'meta_description' => 'Изготовление арок и порогов для авто - собственное производство, низкие цены. Широкий выбор деталей для всех моделей авто, изготовленных из высококачественных материалов. Доставка по всей России, быстрая обработка заказов.',
        'meta_keywords' => '',
        'og_title' => 'Кузовные ремонтные арки и пороги для всех моделей авто!',
        'og_description' => 'Изготовление арок и порогов для авто - собственное производство, низкие цены. Широкий выбор деталей для всех моделей авто, изготовленных из высококачественных материалов. Доставка по всей России, быстрая обработка заказов.',
        'og_url' => 'https://dvaporoga.ru/'
      ],

      [
        'title_admin' => 'Каталог',
        'slug' => 'katalog',
        'title' => 'Каталог запчастей',
        'description' => '<p>
                            Каталог кузовных запчастей от компании "Два порога" предлагает широкий ассортимент деталей для
                            99% всех марок и моделей автомобилей.
                          </p>

                          <p>
                            Здесь вы найдете крылья, пороги, пенки дверей, заглушки, усилители порогов и другие кузовные
                            элементы, изготовленные с высокой точностью на современном оборудовании.
                          </p>

                          <p>
                            Мы гарантируем высокое качество и точное соответствие каждой детали. Выбирайте надежные кузовные
                            запчасти для вашего авто с быстрой доставкой по всей стране. Выберите марку автомобиля.
                          </p>',
        'meta_title' => 'Каталог запчастей | Два порога',
        'meta_description' => 'Каталог кузовных запчастей от компании "Два порога" предлагает широкий ассортимент деталей для 99% всех марок и моделей автомобилей.
         Арки, пороги, пенки дверей, заглушки, усилители порогов и другие детали для ремонта авто от производителя. Высокое качество, точное соответствие, быстрая доставка по всей стране.',
        'meta_keywords' => '',
        'og_title' => 'Каталог запчастей | Два порога',
        'og_description' => 'Каталог кузовных запчастей. Арки, пороги, пенки дверей, заглушки, усилители порогов и другие детали для ремонта авто от производителя. Высокое качество, точное соответствие, быстрая доставка по всей стране.',
        'og_url' => 'https://dvaporoga.ru/katalog'
      ],

      [
        'title_admin' => 'Блог',
        'slug' => 'blog',
        'title' => 'Блог',
        'description' => '<h2 class="h2"> Добро пожаловать в наш блог о кузовном ремонте!</h2>
                        <br>
                        Здесь вы найдете полезные советы по восстановлению
                        кузова автомобиля, узнаете о новейших технологиях ремонта и актуальных трендах кузовного ремонта.
                        <br>
                        <br>
                        Мы делимся экспертными рекомендациями, которые помогут вам сохранить авто в идеальном
                        состоянии или восстановить кузов правильно.
                        <br>
                        <br>
                        Следите за нашими обновлениями, чтобы быть в курсе всех новинок и лайфхаков.',
        'meta_title' => 'Блог | Два порога',
        'meta_description' => 'Здесь вы найдете полезные советы по восстановлению кузова автомобиля, узнаете о новейших технологиях ремонта и актуальных трендах кузовного ремонта',
        'meta_keywords' => '',
        'og_title' => 'Блог | Два порога',
        'og_description' => 'Здесь вы найдете полезные советы по восстановлению кузова автомобиля, узнаете о новейших технологиях ремонта и актуальных трендах кузовного ремонта',
        'og_url' => 'https://dvaporoga.ru/blog'
      ],
    ];

    foreach ($pages as $key => $value) {
      Page::create($value);
    }

    foreach ($main_info as $key => $value) {
      MainInfo::create($value);
    }

    $order = Order::firstOrCreate([
      'title' => 'order_car_makes_home_page'
    ]);


    User::factory()->create([
      'name' => 'Anton',
      'password' => Hash::make('sYn7Dj0lff'),
      'email' => 'a.rodionov14@gmail.com',
    ]);

    // $blogs = Blog::factory()->count(50)->make()->each(function ($blog) {
    //   $blog->description_short = Str::words($blog->description, 15);
    //   $blog->description = Str::words(implode(' ', array_fill(0, 100, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam, quidem.')), 100);
    //   $blog->save();
    // });
  }
}
