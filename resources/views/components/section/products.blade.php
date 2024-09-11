<section class="products-section" id='prices'>
    <div class="container">
        <div class="products-section__top">
            <h2 class="h2">
                <span>Фиксированная</span> <br />
                цена на все модели
            </h2>
            <p>
                Благодаря современному европейскому оборудованию и точности изготовления мы производим запчасти с
                геометрией, полностью повторяющей оригинал.
                <br>
                <br>
                Фиксированные цены на все кузовные запчасти, которые не зависят от марки или типа вашего
                авто.
            </p>
        </div>

        <div class="products-wrap">
            @foreach ($items as $item)
                <x-product-card :part="$item" />
            @endforeach
        </div>


        <script>
            const products = document.querySelectorAll(".product");

            products.forEach((product) => {
                const form = product.querySelector(".modal-form-product");
                form.addEventListener("submit", async function(event) {
                    event.preventDefault();
                    const form = event.target;
                    const formData = new FormData(form);

                    const response = await fetch(
                        "{{ route('request_product_section.store') }}", {
                            method: "POST",
                            body: formData,
                        }
                    );

                    if (response.ok) {
                        form.reset();
                        MicroModal.show("modal-2");
                        setTimeout(() => {
                            MicroModal.close("modal-2");
                        }, 3000);
                    } else {
                        throw new Error("Ошибка отправки");
                    }
                });
            });
        </script>
    </div>
</section>
