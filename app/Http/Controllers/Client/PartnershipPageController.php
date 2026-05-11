<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PartnershipPageController extends Controller
{
    public function index()
    {
        $page = Page::query()->firstWhere('slug', 'sotrudnichestvo') ?? new Page();
        $brandName = 'ДВАПОРОГА';

        $page->title = $page->title ?: "Преимущества работы с {$brandName}";
        $page->description = $page->description ?: 'Для постоянных клиентов действуют <b>специальные условия</b> на покупку и доставку кузовных запчастей';
        $page->phone = $page->phone ?: '+7 (906) 244-41-51';

        $page->meta_title = $page->meta_title ?: "Сотрудничество | {$brandName}";
        $page->meta_description = $page->meta_description ?: "Сотрудничество с {$brandName}: специальные цены на детали, персональный менеджер, приоритет в отправке и работа по всей РФ.";
        $page->meta_keywords = $page->meta_keywords ?: 'сотрудничество, двапорога, кузовные детали, пороги, арки, опт, дропшиппинг';
        $page->og_title = $page->og_title ?: "Сотрудничество | {$brandName}";
        $page->og_description = $page->og_description ?: 'Приглашаем к сотрудничеству СТО, кузовные сервисы, оптовые и розничные сети, онлайн-магазины и дропшиппинг-проекты.';
        $page->og_url = $page->og_url ?: url()->current();

        $page->title = str_ireplace('2POROGA', $brandName, $page->title);
        $page->meta_title = str_ireplace('2POROGA', $brandName, $page->meta_title);
        $page->meta_description = str_ireplace('2POROGA', $brandName, $page->meta_description);
        $page->og_title = str_ireplace('2POROGA', $brandName, $page->og_title);

        return view('partnership', compact('page'));
    }
}
