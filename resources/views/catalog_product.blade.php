@extends('layouts.front')

@section('content')
    @php
        $img = null;

        if (!empty($car->image) && $car->image !== 'default') {
            $img = asset('storage/' . $car->image);
        } elseif (!empty($car->image_mob) && $car->image_mob !== 'default') {
            $img = asset('storage/' . $car->image_mob);
        } else {
            $img = asset('images/cars/merc.png');
        }

        $resolveProductImage = function ($product, bool $preferMobile = false) {
            $pivotPath =
                !empty($product->pivot?->image) && $product->pivot->image !== 'default'
                    ? ltrim($product->pivot->image, '/')
                    : null;

            $pivotMobilePath =
                !empty($product->pivot?->image_mob) && $product->pivot->image_mob !== 'default'
                    ? ltrim($product->pivot->image_mob, '/')
                    : null;

            $adminPath = !empty($product->image) && $product->image !== 'default' ? ltrim($product->image, '/') : null;
            $adminMobilePath =
                !empty($product->image_mob) && $product->image_mob !== 'default' ? ltrim($product->image_mob, '/') : null;

            $fallbackPath = null;
            foreach (['webp', 'jpg', 'jpeg', 'png'] as $ext) {
                $path = "products_default/{$product->slug}.{$ext}";
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                    $fallbackPath = $path;
                    break;
                }
            }

            $finalPath = $preferMobile
                ? ($pivotMobilePath ?: ($adminMobilePath ?: ($pivotPath ?: ($adminPath ?: $fallbackPath))))
                : ($pivotPath ?: ($adminPath ?: ($adminMobilePath ?: $fallbackPath)));

            return $finalPath ? asset('storage/' . $finalPath) : asset('images/no-image.jpg');
        };

        $carHeadingTitle = trim(
            implode(
                ' ',
                array_filter([
                    $car_make->title ?? null,
                    $car_model->title ?? null,
                ]),
            ),
        );

        if ($carHeadingTitle === '') {
            $carHeadingTitle = $car->title;
        }

        $formatDoorsLabel = function ($count) {
            $count = (int) $count;
            $mod100 = $count % 100;
            $mod10 = $count % 10;

            if ($mod100 >= 11 && $mod100 <= 14) {
                return $count . ' дверей';
            }

            if ($mod10 === 1) {
                return $count . ' дверь';
            }

            if ($mod10 >= 2 && $mod10 <= 4) {
                return $count . ' двери';
            }

            return $count . ' дверей';
        };

        $extractDoorsCount = function ($value) {
            if (!is_string($value) || $value === '') {
                return null;
            }

            if (preg_match('/\b(\d+)\s*(?:дв\.?|двер(?:ь|и|ей))\b/ui', $value, $matches)) {
                return (int) $matches[1];
            }

            return null;
        };

        $bodyLabel = trim((string) ($car->body ?? ''));
        $doorsCount = $extractDoorsCount($bodyLabel);

        if ($doorsCount !== null) {
            $bodyLabel = trim(
                preg_replace('/\b\d+\s*(?:дв\.?|двер(?:ь|и|ей))\b/ui', '', $bodyLabel) ?? '',
                " \t\n\r\0\x0B-–—,",
            );
            $bodyLabel = preg_replace('/\s{2,}/u', ' ', $bodyLabel) ?? $bodyLabel;
        }

        if ($doorsCount === null) {
            $doorsCount = $extractDoorsCount((string) ($car->title ?? ''));
        }

        $doorsLabel = $doorsCount !== null ? $formatDoorsLabel($doorsCount) : null;

        $carMetaParts = array_values(
            array_filter([
                $bodyLabel,
                $doorsLabel,
                $car->generation,
                empty($car->generation) ? $car->years : null,
            ]),
        );
        $carMeta = implode(' • ', $carMetaParts);

        $preparedProducts = $products->map(function ($product) use ($resolveProductImage) {
            return [
                'id' => $product->id,
                'image' => $resolveProductImage($product),
                'title' => $product->title,
                'description' => $product->description ?? '',
                'price' => $product->price ? number_format((int) $product->price, 0, '.', ' ') . ' ₽' : '',
                'price_old' => $product->price_old ? number_format((int) $product->price_old, 0, '.', ' ') . ' ₽' : '',
                'alt' => $product->alt ?: $product->title,
            ];
        });
    @endphp

    <main>
        {{ Breadcrumbs::render('car_generation.show', $car_make, $car_model, $car) }}

        <section class="car-single__hero-section">
            <div class="container">
                <div class="car-single-page">
                    <div class="car-single-page__image">
                        <img src="{{ $img }}" alt="{{ $car->title }}">
                    </div>

                    <div class="car-single-page__heading">
                        <h1 class="car-single-page__title">
                            <span class="car-single-page__title-line">Кузовные элементы</span>
                            <span class="car-single-page__title-line car-single-page__title-line--accent">для {{ $car_make->title }}</span>
                            <span class="car-single-page__title-line car-single-page__title-line--accent">{{ $car_model->title }}</span>
                        </h1>

                        @if (!empty($carMeta))
                            <p class="car-single-page__subtitle">{{ $carMeta }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="car-single-form-section">
            <div class="container">
                <div class="car-single-form-card">
                    <div class="car-single-form-card__content">
                        <div class="car-single-form-card__header">
                            <h2 class="car-single-form-card__title">Оставьте заявку</h2>

                            <p class="car-single-form-card__descr">
                                Мы подберем деталь под ваш автомобиль и ответим на все вопросы
                            </p>
                        </div>

                        <form id="car-request-form" action="{{ route('requests.car') }}" method="POST"
                            class="car-single-form car-single-form--page" data-action="{{ route('requests.car') }}"
                            data-ym-goal="calculator" data-ym-mode="manual">
                            @csrf

                            <input type="hidden" name="form_id" value="car-page-form">
                            <input type="hidden" name="car_id" value="{{ $car->id }}">
                            <input type="hidden" name="current_url" value="{{ request()->fullUrl() }}">

                            <input type="hidden" name="utm_source" value="{{ $utm['utm_source'] ?? '' }}">
                            <input type="hidden" name="utm_medium" value="{{ $utm['utm_medium'] ?? '' }}">
                            <input type="hidden" name="utm_campaign" value="{{ $utm['utm_campaign'] ?? '' }}">
                            <input type="hidden" name="utm_term" value="{{ $utm['utm_term'] ?? '' }}">
                            <input type="hidden" name="utm_content" value="{{ $utm['utm_content'] ?? '' }}">
                            <input type="hidden" name="cm_id" value="{{ $utm['cm_id'] ?? '' }}">

                            <div class="car-single-form__row">
                                <div class="input-item">
                                    <input class="input black" type="text" name="name" placeholder="Имя" required>
                                    <div class="field-error" data-error-for="name"></div>
                                </div>
                            </div>

                            <div class="car-single-form__row">
                                <div class="input-item">
                                    <input class="input black" type="tel" name="phone" placeholder="+7 (999) 000-00-00"
                                        required>
                                    <div class="field-error" data-error-for="phone"></div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-black car-single-form-btn">Отправить</button>

                            <div class="form-policy-wrap">
                                <div class="form-policy">
                                    <input type="checkbox" id="choose-check" name="policy" value="1" required>
                                    <label for="choose-check">
                                        <x-forms.policy-consent submit-text="Отправить" />
                                    </label>
                                </div>
                                <div class="field-error field-error--policy" data-error-for="policy"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section class="car-single-parts-section">
            <div class="container">
                <div class="car-single-parts-section__heading">
                    <h2 class="car-single-parts-section__title">Детали</h2>

                    <p class="car-single-parts-section__subtitle">
                        на {{ $car->title }}@if (!empty($carMeta))
                            / {{ $carMeta }}
                        @endif
                    </p>
                </div>

                <div class="car-single-parts">
                    @foreach ($preparedProducts as $product)
                        <x-car-single-part :id="$product['id']" :image="$product['image']" :title="$product['title']" :description="$product['description']"
                            :price="$product['price']" :priceOld="$product['price_old']" :alt="$product['alt']"
                            request-source="car" :request-car="$car->title" />
                    @endforeach
                </div>
            </div>
        </section>

        <x-section.about-parts />
        <x-section.repair-examples :block="$repairExamplesBlock" />
        <x-section.reviews />
        <x-section.how-we-work />
        <x-section.about-company />
        <x-section.faq />
    </main>
@endsection
