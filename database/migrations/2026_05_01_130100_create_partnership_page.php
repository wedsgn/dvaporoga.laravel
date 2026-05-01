<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $page = DB::table('pages')
            ->where('slug', 'sotrudnichestvo')
            ->first();

        $now = Carbon::now();

        if ($page) {
            DB::table('pages')
                ->where('slug', 'sotrudnichestvo')
                ->update([
                    'title_admin' => $page->title_admin ?: 'Сотрудничество',
                    'title' => $page->title ?: 'Преимущества работы с 2POROGA',
                    'description' => $page->description ?: 'Для постоянных клиентов действуют <b>специальные условия</b> на покупку и доставку кузовных запчастей',
                    'phone' => $page->phone ?: '+7 (906) 244-41-51',
                    'meta_title' => $page->meta_title ?: 'Сотрудничество | 2POROGA',
                    'meta_description' => $page->meta_description ?: 'Сотрудничество с 2POROGA: специальные цены на детали, персональный менеджер, приоритет в отправке и работа по всей РФ.',
                    'meta_keywords' => $page->meta_keywords ?: 'сотрудничество, 2poroga, кузовные детали, пороги, арки, опт, дропшиппинг',
                    'og_title' => $page->og_title ?: 'Сотрудничество | 2POROGA',
                    'og_description' => $page->og_description ?: 'Приглашаем к сотрудничеству СТО, кузовные сервисы, оптовые и розничные сети, онлайн-магазины и дропшиппинг-проекты.',
                    'og_url' => $page->og_url ?: 'https://dvaporoga.ru/sotrudnichestvo',
                    'updated_at' => $now,
                ]);

            return;
        }

        DB::table('pages')->insert([
            'title_admin' => 'Сотрудничество',
            'slug' => 'sotrudnichestvo',
            'title' => 'Преимущества работы с 2POROGA',
            'description' => 'Для постоянных клиентов действуют <b>специальные условия</b> на покупку и доставку кузовных запчастей',
            'phone' => '+7 (906) 244-41-51',
            'meta_title' => 'Сотрудничество | 2POROGA',
            'meta_description' => 'Сотрудничество с 2POROGA: специальные цены на детали, персональный менеджер, приоритет в отправке и работа по всей РФ.',
            'meta_keywords' => 'сотрудничество, 2poroga, кузовные детали, пороги, арки, опт, дропшиппинг',
            'og_title' => 'Сотрудничество | 2POROGA',
            'og_description' => 'Приглашаем к сотрудничеству СТО, кузовные сервисы, оптовые и розничные сети, онлайн-магазины и дропшиппинг-проекты.',
            'og_url' => 'https://dvaporoga.ru/sotrudnichestvo',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('pages')
            ->where('slug', 'sotrudnichestvo')
            ->delete();
    }
};
