<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.

use App\Models\CarMake;
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Главная', route('home'));
});

// Home > Catalog
Breadcrumbs::for('catalog', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Каталог', route('catalog'));
});

// Home > Catalog > Car Makes
Breadcrumbs::for('car_make.show', function (BreadcrumbTrail $trail, CarMake $make) {
    $trail->parent('catalog');
    $trail->push($make->title, route('car_make.show', $make));
});

// Home > Catalog > Car Makes > Car Models
Breadcrumbs::for('car_model.show', function (BreadcrumbTrail $trail, CarMake $make, $model) {
    $trail->parent('car_make.show', $make);
    $trail->push($model->title, route('car_model.show', [$make->slug, $model->slug]));
});

// Home > Catalog > Car Makes > Car Models > Car Generations
Breadcrumbs::for('car_generation.show', function (BreadcrumbTrail $trail, CarMake $make, $model, $generation) {
    $trail->parent('car_model.show', $make, $model);
    $trail->push($generation->title, route('car_generation.show',  [$make->slug, $model->slug, $generation->slug]));
});

// Home > Blog
Breadcrumbs::for('blog', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Блог', route('blog'));
});

// Home > Blog > [Post]
Breadcrumbs::for('blog.single', function (BreadcrumbTrail $trail, $post) {
    $trail->parent('blog');
    $trail->push($post->title, route('blog.single', $post->title));
});
