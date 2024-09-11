<!DOCTYPE html>
<html lang="ru">


@include('parts.head')

<body>

    @include('parts.header')

    @yield('content')

    @include('parts.footer')


    <div class="modal micromodal-slide" id="modal-1" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
                <header class="modal__header">
                    <h2 class="modal__title" id="modal-1-title">Заполните форму</h2>
                    <p class="modal__description">Мы свяжемся с вами в течение 5-nb минут <br> и ответим на все вопросы
                    </p>
                    <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                </header>

                <form class="modal-form">
                    @csrf
                    <input type="text" placeholder="Имя" class="input" name="name" required />
                    <input type="tel" placeholder="+7 (___) ___ __ __" class="input" name="phone" required />
                    <input type="hidden" name="form_id" value="Форма в шапке">

                    <button class="btn lg submit-modal" type="submit">Отправить</button>

                    <p class="copyright">
                        Нажимая кнопку “Отправить” вы соглашаетесь с нашей
                        <a href="" download=""> политикой конфиденциальности </a>
                    </p>
                </form>

            </div>
        </div>
    </div>

    <div class="modal modal-success micromodal-slide" id="modal-2" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
                <header class="modal__header">
                    <h2 class="modal__title" id="modal-1-title">Заявка успешно отправлена</h2>
                    <p class="modal__description">Мы свяжемся с вами в течение 7 минут <br> и ответим на все вопросы</p>
                    <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                </header>
            </div>
        </div>
    </div>

    <script>
        const forms = document.querySelectorAll('.modal-form');


        forms.forEach(form => {
            const submitButton = form.querySelector('.submit-modal');

            form.addEventListener('submit', async function(event) {
                event.preventDefault();
                const formData = new FormData(form);
                try {
                    const response = await fetch("{{ route('request_consultation.store') }}", {
                        method: 'POST',
                        body: formData,

                    });

                    if (response.ok) {
                        form.reset();
                        MicroModal.close('modal-1');
                        MicroModal.show('modal-2');

                        setTimeout(() => {
                            MicroModal.close('modal-2');
                        }, 3000);
                    } else {
                        throw new Error('Ошибка отправки');
                    }
                } catch (error) {
                    alert(error.message);
                } finally {
                    submitButton.disabled = false;
                }
            });
        })
    </script>
    {{--
    <script>
        document.querySelectorAll('.product-btn').forEach(button => {
            button.addEventListener('click', () => {
                const productId = button.getAttribute('data-product-id');
                document.getElementById('productIdInput').value = productId;
                microModal.show('modal-1');
            });
        });
    </script> --}}

</body>

</html>
