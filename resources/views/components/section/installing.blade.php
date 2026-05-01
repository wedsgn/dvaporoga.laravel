@php
    $data = [
        [
            'title' => 'Новый порог',
            'image' => '',
        ],
        [
            'title' => 'Проблемный порог',
            'image' => '',
        ],
        [
            'title' => 'Срез старого порога',
            'image' => '',
        ],
        [
            'title' => 'Установка нового порога',
            'image' => '',
        ],
        [
            'title' => 'Результат',
            'image' => '',
        ],
    ];
@endphp

<section class="installing-section section">
    <div class="container">
        <div class="installing-section__top">
            <h2 class="h2 installing-section__title">
                Легкий и выгодный монтаж
            </h2>

            <p class="installing-section__description">
                Благодаря высокому качеству наших деталей - вы сможете с легкостью их поставить на свою машину и никто
                не сможет отличить их от оригинальных.
            </p>
        </div>

        <div class="installing-wrap">
            @foreach ($data as $item)
                <div class="installing-item">
                    <div class="installing-item__image">
                        <img src="/images/installing/1.jpg" alt="asdasd">
                    </div>
                    <h4 class="installing-item__title">{{ $item['title'] }}</h4>
                </div>
            @endforeach
        </div>
    </div>
</section>
