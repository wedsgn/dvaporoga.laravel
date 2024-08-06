<!DOCTYPE html>
<html lang="ru">


@include('parts.head')

<body>

    @include('parts.header')

    @yield('content')

    @include('parts.footer')

    {{-- @vite(['resources/js/app.js']) --}}

    <div class="modal micromodal-slide" id="modal-1" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
                <header class="modal__header">
                    <h2 class="modal__title" id="modal-1-title">Micromodal</h2>
                    <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                </header>
                <form class="index-hero-form" action="{{ route('request_product.store', 'product-home-page-form') }}"
                    id="indexHeroForm" method="POST">
                    @csrf
                    <input type="hidden" name="product_id[]" id="productIdInput" value="" />
                    <input type="text" placeholder="Имя" class="input" name="name" required />
                    <input type="tel" placeholder="+7 (___) ___ __ __" class="input" name="phone" required="">


                    <button class="btn lg" type="submit">Отправить</button>

                    <p class="copyright">
                        Нажимая кнопку “Отправить” вы соглашаетесь с нашей
                        <a href="" download=""> политикой конфиденциальности </a>
                    </p>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.product-btn').forEach(button => {
            button.addEventListener('click', () => {
                const productId = button.getAttribute('data-product-id');
                document.getElementById('productIdInput').value = productId;
                microModal.show('modal-1');
            });
        });
    </script>

</body>

</html>
