<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Car;
use App\Models\CarMake;
use App\Models\MainInfo;
use App\Models\Order;
use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\CarModel;
class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // MainInfo::truncate();
    // Blog::truncate();
    // Page::truncate();

    // $main_info = [
    //   [
    //     'company_title' => 'Два Порога',
    //     'company_details' => 'ООО "АРТ ГРУПП" 192082, Россия, г. Санкт-Петербург, ул.Туристская, д.23 к.2 ИНН 7814593546 ОГРН/ОГРНИП 1137847459936',
    //     'phone' => '8 800 100 56 25',
    //     'whats_app' => 'https://wa.me/qr/Z7QXMOM35J45E1',
    //     'telegram' => 'https://t.me/dvaporoga'
    //   ],
    // ];

    // $pages = [
    //   [
    //     'title_admin' => 'Главная',
    //     'slug' => 'home',
    //     'title' => 'Ремонтные <br /><span>пороги</span>

    //                 и <span>арки</span>',
    //     'description' => '',
    //     'meta_title' => 'Кузовные ремонтные арки и пороги для всех моделей авто! | Два порога',
    //     'meta_description' => 'Изготовление арок и порогов - собственное производство, низкие цены. Широкий выбор деталей, изготовленных из высококачественных материалов. Доставка по всей России.',
    //     'meta_keywords' => '',
    //     'og_title' => 'Кузовные ремонтные арки и пороги для всех моделей авто!',
    //     'og_description' => 'Изготовление арок и порогов - собственное производство, низкие цены. Широкий выбор деталей, изготовленных из высококачественных материалов. Доставка по всей России.',
    //     'og_url' => 'https://dvaporoga.ru/'
    //   ],

    //   [
    //     'title_admin' => 'Каталог',
    //     'slug' => 'katalog',
    //     'title' => 'Каталог запчастей',
    //     'description' => '<p>В каталоге компании "Два порога" вы найдете все необходимое для восстановления кузова вашего авто. У нас представлен широкий ассортимент запчастей для <strong>более чем 99% марок и моделей автомобилей</strong>, включая популярные Volkswagen, Skoda, Nissan, Mazda, Opel, Renault, Kia, Chevrolet, Ford, Honda, Toyota, Hyundai.</p><p><strong>Мы предлагаем:</strong></p><ul><li>Ремонтные арки, пороги, ремкомплекты дверей, багажника и<i><strong> </strong></i>другие кузовные элементы.</li><li><strong>Детали для кузовного ремонта после ДТП:</strong> усилители, лонжероны.</li><li><strong>Высокое качество:</strong> Все детали производятся из современных материалов на современном оборудовании, что гарантирует их долговечность и надежность.</li><li><strong>Точная подгонка:</strong> Каждая деталь идеально подходит к конкретной модели автомобиля.</li><li><strong>Быстрая доставка по всей стране:</strong> Заказы обрабатываются и отправляются в кратчайшие сроки.</li></ul><p><strong>Почему выбирают нас?</strong></p><ul><li><strong>Большой опыт:</strong> Мы работаем на рынке автозапчастей уже много лет.</li><li><strong>Индивидуальный подход:</strong> Наши специалисты помогут подобрать необходимые запчасти для вашего автомобиля.</li><li><strong>Гарантия качества:</strong> Мы предоставляем гарантию на всю продукцию.</li></ul><p><strong>Выберите марку вашего автомобиля и найдите нужные запчасти прямо сейчас!</strong>"</p>',
    //     'meta_title' => 'Каталог запчастей | Два порога',
    //     'meta_description' => 'Каталог кузовных запчастей от компании "Два порога". Ремонтные арки, пороги, усилители, ремкомплекты дверей и багажника. Высокое качество, точная подгонка. Быстрая доставка по всей России.',
    //     'meta_keywords' => '',
    //     'og_title' => 'Каталог запчастей | Два порога',
    //     'og_description' => 'Каталог кузовных запчастей от компании "Два порога". Ремонтные арки, пороги, усилители, ремкомплекты дверей и багажника. Высокое качество, точная подгонка. Быстрая доставка по всей России.',
    //     'og_url' => 'https://dvaporoga.ru/katalog'
    //   ],

    //   [
    //     'title_admin' => 'Блог',
    //     'slug' => 'blog',
    //     'title' => 'Блог',
    //     'description' => '<h2 class="h2"> Добро пожаловать в наш блог о кузовном ремонте!</h2>
    //                     <br>
    //                     Здесь вы найдете полезные советы по восстановлению
    //                     кузова автомобиля, узнаете о новейших технологиях ремонта и актуальных трендах кузовного ремонта.
    //                     <br>
    //                     <br>
    //                     Мы делимся экспертными рекомендациями, которые помогут вам сохранить авто в идеальном
    //                     состоянии или восстановить кузов правильно.
    //                     <br>
    //                     <br>
    //                     Следите за нашими обновлениями, чтобы быть в курсе всех новинок и лайфхаков.',
    //     'meta_title' => 'Блог | Два порога',
    //     'meta_description' => 'Здесь вы найдете полезные советы по восстановлению кузова автомобиля, узнаете о новейших технологиях ремонта и актуальных трендах кузовного ремонта',
    //     'meta_keywords' => '',
    //     'og_title' => 'Блог | Два порога',
    //     'og_description' => 'Здесь вы найдете полезные советы по восстановлению кузова автомобиля, узнаете о новейших технологиях ремонта и актуальных трендах кузовного ремонта',
    //     'og_url' => 'https://dvaporoga.ru/blog'
    //   ],
    // ];

    // foreach ($pages as $key => $value) {
    //   Page::create($value);
    // }

    // foreach ($main_info as $key => $value) {
    //   MainInfo::create($value);
    // }

    // $order = Order::firstOrCreate([
    //   'title' => 'order_car_makes_home_page'
    // ]);


    User::factory()->create([
      'name' => 'Admin',
      'password' => Hash::make('Sp67edFAhum92qq34'),
      'email' => 'info@avtoporogi.ru',
    ]);

    // $blogs = Blog::factory()->count(50)->make()->each(function ($blog) {
    //   $blog->description_short = Str::words($blog->description, 15);
    //   $blog->description = Str::words(implode(' ', array_fill(0, 100, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam, quidem.')), 100);
    //   $blog->save();
    // });
    // Car::all()->chunk(1413)->each(function ($cars) {
    //   foreach ($cars as $car) {
    //     $car->update([
    //       'meta_title' => 'Каталог запчастей для кузовного ремонта ' . trim($car->title) . ' | Два порога',
    //       'meta_description' => 'Кузовные запчасти для автомобиля ' . trim($car->title) . ': ремонтные арки, пороги, лонжероны, усилители и другие детали. Высокое качество, низкие цены, доставка по России.',
    //       'meta_keywords' => 'запчасти для автомобилей ' . $car->car_model->car_make->title . ', арки ' . $car->car_model->car_make->title . ' ' . $car->car_model->title . ', пороги ' . $car->car_model->car_make->title . ' ' . $car->car_model->title . ', лонжероны ' . $car->car_model->car_make->title . ' ' . $car->car_model->title . ', усилители порогов ' . $car->car_model->car_make->title . ' ' . $car->car_model->title . ', детали кузова ' . $car->car_model->car_make->title . ' ' . $car->car_model->title . ', автозапчасти ' . $car->car_model->car_make->title . ' ' . $car->car_model->title . ', пороги для ' . $car->car_model->car_make->title . ', арки для ' . $car->car_model->car_make->title . ', автозапчасти с доставкой по России, запчасти для кузова ' . $car->car_model->car_make->title . ', автомобильные детали ' . $car->car_model->car_make->title . '',
    //       'og_title' => 'Каталог запчастей для кузовного ремонта ' . trim($car->title) . ' | Два порога',
    //       'og_description' => 'Кузовные запчасти для автомобиля ' . trim($car->title) . ': арки, пороги, лонжероны, усилители и другие детали. Высокое качество, низкие цены, доставка по России.',
    //       'og_url' => 'https://dvaporoga.ru/katalog/'. Str::slug(trim($car->car_model->car_make->title)) . '/' . $car->car_model->slug . '/' . Str::slug(trim($car->title)),
    //     ]);
    //   }
    // });
    // CarMake::all()->chunk(62)->each(function ($car_makes) {
    //   foreach ($car_makes as $car_make) {
    //     $car_make->update([
    //       'meta_title' => 'Каталог запчастей для кузовного ремонта ' . trim($car_make->title) . ' | Два порога',
    //       'meta_description' => 'Кузовные запчасти для автомобилей ' . trim($car_make->title) . ': ремонтные арки, пороги, лонжероны и другие кузовные детали. Высокое качество, доступные цены, доставка по всей России.',
    //       'meta_keywords' => 'кузовные запчасти для автомобилей ' . trim($car_make->title) . ', ремонтные арки для ' . trim($car_make->title) . ', пороги для ' . trim($car_make->title) . ', лонжероны для ' . trim($car_make->title) . ', кузовные детали для ' . trim($car_make->title) . ', автозапчасти для кузова ' . trim($car_make->title) . ', кузовной ремонт ' . trim($car_make->title) . ', кузовные запчасти с доставкой, ремонт кузова автомобиля ' . trim($car_make->title) . ', арки для автомобиля, пороги для автомобиля, лонжероны для автомобиля, авто детали кузова, запчасти для кузова с доставкой по России, кузовные арки и пороги, кузовной ремонт арки и пороги',
    //       'og_title' => 'Каталог запчастей для кузовного ремонта ' . trim($car_make->title) . ' | Два порога',
    //       'og_description' => 'Кузовные запчасти для автомобилей ' . trim($car_make->title) . ': арки, пороги, лонжероны и другие кузовные детали. Высокое качество, доступные цены, доставка по всей России.',
    //       'og_url' => 'https://dvaporoga.ru/katalog/' . $car_make->slug,
    //     ]);
    //   }
    // });
    
    // CarModel::all()->chunk(611)->each(function ($car_models) {
    //   foreach ($car_models as $car_model) {
    //     $car_model->update([
    //       'meta_title' => 'Каталог запчастей для кузовного ремонта ' . trim($car_model->car_make->title) . ' ' . trim($car_model->title) . ' | Два порога',
    //       'meta_description' => 'Кузовные запчасти для автомобилей ' . trim($car_model->car_make->title) . ' ' . trim($car_model->title) . ': ремонтные арки, пороги, лонжероны и другие кузовные детали. Высокое качество, доступные цены, доставка по всей России.',
    //       'meta_keywords' => 'кузовные запчасти для автомобилей ' . trim($car_model->car_make->title) . ' ' . trim($car_model->title) . ', ремонтные арки для ' . trim($car_model->car_make->title) . ' ' . trim($car_model->title) . ', пороги для ' . trim($car_model->car_make->title) . ' ' . trim($car_model->title) . ', лонжероны для ' . trim($car_model->car_make->title) . ' ' . trim($car_model->title) . ', кузовные детали для ' . trim($car_model->car_make->title) . ' ' . trim($car_model->title) . ', автозапчасти для кузова ' . trim($car_model->car_make->title) . ' ' . trim($car_model->title) . ', кузовной ремонт ' . trim($car_model->car_make->title) . ' ' . trim($car_model->title) . ', кузовные запчасти с доставкой, ремонт кузова автомобиля ' . trim($car_model->car_make->title) . ' ' . trim($car_model->title) . ', арки для автомобиля, пороги для автомобиля, лонжероны для автомобиля, авто детали кузова, запчасти для кузова с доставкой по России, кузовные арки и пороги, кузовной ремонт арки и пороги',
    //       'og_title' => 'Каталог запчастей для кузовного ремонта ' . trim($car_model->car_make->title) . ' ' . trim($car_model->title) . ' | Два порога',
    //       'og_description' => 'Кузовные запчасти для автомобилей ' . trim($car_model->car_make->title) . ' ' . trim($car_model->title) . ': арки, пороги, лонжероны и другие кузовные детали. Высокое качество, доступные цены, доставка по всей России.',
    //       'og_url' => 'https://dvaporoga.ru/katalog/' . $car_model->car_make->slug . '/' . $car_model->slug,
    //     ]);
    //   }
    // });
  }
}
