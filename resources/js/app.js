import IMask from "imask";
import { accordition } from "./modules/accordition";
import { sliders } from "./modules/sliders";
import { burger } from "./modules/burger";
import { tabs } from "./modules/tabs";
import MicroModal from "micromodal";
import { cart } from "./modules/cart";
import Choices from "choices.js";

MicroModal.init({});
window.addEventListener("load", () => {
  accordition();
  sliders();
  burger();
  tabs();
  cart();

  var phones = document.querySelectorAll('input[type="tel"]');
  var maskOptions = {
    mask: "+7 (000) 000 00 00",
  };

  phones.forEach((element) => {
    var mask = new IMask(element, maskOptions);
  });

  const element = document.querySelectorAll(".js-choice");
  element.forEach((item) => {
    const choices = new Choices(item, {
      searchEnabled: false,
      itemSelectText: "",
    });
  });

  // Поле из карточки

  // Корзина
});
