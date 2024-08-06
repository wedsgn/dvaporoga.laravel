import IMask from "imask";
import { accordition } from "./modules/accordition";
import { sliders } from "./modules/sliders";
import { burger } from "./modules/burger";
import { tabs } from "./modules/tabs";
import MicroModal from "micromodal";

MicroModal.init({});
window.addEventListener("load", () => {
  accordition();
  sliders();
  burger();
  tabs();

  var phones = document.querySelectorAll('input[type="tel"]');
  var maskOptions = {
    mask: "+7 (000) 000 00 00",
  };

  phones.forEach((element) => {
    var mask = new IMask(element, maskOptions);
  });

  // Поле из карточки

  // Корзина

  const cartItems = document.querySelectorAll(".product-part");

  if (cartItems) {
    let selectedItems = [];
    const cartItemsPlace = document.querySelector(".total-form__parts");
    const totalPriceInput = document.querySelector(".product-form__price");
    const arrayFormInput = document.querySelector(".product-form__array");
    const clearCart = document.querySelector(".product-parts__total_clear");
    const totalPriceDiv = document.querySelector(
      ".total-form__total_value span"
    );

    let totalPrice = 0;

    clearCart.addEventListener("click", () => {
      selectedItems = [];
      totalPrice = 0;
      checkSum();
      pushArray();
      cartItemsPlace.insertAdjacentHTML(
        "afterbegin",
        `<div class="total-form__empty">Добавьте запчасти в заказ</div>`
      );
    });

    const checkSum = () => {
      totalPriceDiv.innerHTML = totalPrice;
      totalPriceInput.value = totalPrice;
      arrayFormInput.value = JSON.stringify(selectedItems);
    };

    const pushArray = () => {
      cartItemsPlace.innerHTML = "";
      selectedItems.forEach((item) => {
        cartItemsPlace.insertAdjacentHTML(
          "afterbegin",
          `<div class="total-form__part">
            <p class="total-form__part_title">${item.title}</p>
            <p class="total-form__part_price">${item.price} р.</p>
          </div>`
        );
      });
    };

    cartItems.forEach((item, idx) => {
      const addBtn = item.querySelector(".btn");
      addBtn.addEventListener("click", () => {
        const title = item.querySelector(".product-part__title").innerHTML;
        const itemId = item.querySelector(".product-part__id").value;
        const price = item.querySelector(
          ".product-part__price_num span"
        ).innerHTML;

        selectedItems.push({ id: itemId, title: title, price: price });
        totalPrice += +price;
        checkSum();
        pushArray();
      });
    });
  }
});
