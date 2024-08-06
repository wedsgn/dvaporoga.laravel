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

  // Коризина

  const cartItems = document.querySelectorAll(".product-part");

  if (cartItems) {
    let selectedItems = [];
    const cartItemsPlace = document.querySelector(".total-form__parts");

    cartItems.forEach((item) => {
      item.addEventListener("click", () => {
        console.log(item.querySelector(".product-part__title"));
      });
    });
  }
});
