@extends('layouts.front')

@section('content')
    <main>
        <section class="breadcrumbs-section">
            <div class="container">
                <ul class="breadcrumbs">
                    <li><a href="/">Главная</a></li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M14 8.0013L10.6667 4.66797M14 8.0013L10.6667 11.3346M14 8.0013H2" stroke="#1E1E1E"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </li>
                    <li>Блог</li>
                </ul>
            </div>
        </section>

        <section class="blog-catalog-section">
            <div class="container">
                <div class="blog-catalog__top">
                    <h1 class="h1">Блог</h1>
                    <p class="blog-catalog__description">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam
                        eius officia, illum dignissimos cupiditate ab quo harum unde
                        labore, corporis, odio quisquam assumenda officiis consequatur rem
                        laboriosam explicabo obcaecati error!
                    </p>
                </div>

                <div class="blog-search">
                    <form action="#" method="get" id="blogSearchForm">
                        @csrf
                        <input type="text" class="blog-search__input" placeholder="Поиск статьи" id="blogSearchInput" />
                        <button type="submit" class="blog-search__btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                fill="none">
                                <path
                                    d="M8.75 15C12.2018 15 15 12.2018 15 8.75C15 5.29822 12.2018 2.5 8.75 2.5C5.29822 2.5 2.5 5.29822 2.5 8.75C2.5 12.2018 5.29822 15 8.75 15Z"
                                    stroke="#1E1E1E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M13.1696 13.168L17.5 17.4984" stroke="#1E1E1E" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <section class="blog-cards-catalog" id="blogCatalog">
            <div class="container">
                <div class="blog-cards__wrap">
                  @foreach ($blogs as $blog)
                  <x-blog-card :item="$blog" />
                  @endforeach
                </div>
            </div>
        </section>
        <button id="loadMoreBtn" class="load-more-btn">Показать больше</button>

    </main>

    <script>
        document.getElementById('blogSearchForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const searchInput = document.getElementById('blogSearchInput').value;
            const url = "{{ route('blog.search') }}?search=" + searchInput;
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('blogCatalog').innerHTML = data;
                });
        });

        let pageCount = Number("{{ $pageCount }}");
        let currentPage = Number("{{ $currentPage }}");

        document.getElementById('loadMoreBtn').addEventListener('click', function(event) {
            event.preventDefault();
            currentPage = currentPage + 1;
            const url = "{{ route('blog.add_more') }}?page=" + currentPage;

            if(currentPage == pageCount) {
              document.getElementById('loadMoreBtn').style.display = 'none';
            }
            else {
              document.getElementById('loadMoreBtn').style.display = 'block';
            }
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('blogCatalog').innerHTML += data;

                });
        });
    </script>
@endsection

