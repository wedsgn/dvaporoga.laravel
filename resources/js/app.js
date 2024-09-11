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
  // element.forEach((item) => {
  //   const choices = new Choices(item, {
  //     searchEnabled: false,
  //     itemSelectText: "",
  //   });
  // });
  // Карточка калькулятор

  const products = document.querySelectorAll(".product");

  if (products) {
    products.forEach((product) => {
      const priceDeiv = product.querySelector(".product-price span");
      const data = product.getAttribute("data-item");
      const dataRes = JSON.parse(data);

      const steelSelector = product.querySelector(".steel-select");
      const priceInput = product.querySelector("#productPriceInput");
      const thicknessSelector = product.querySelector(".thickness_select");
      const typeSelector = product.querySelector(".type-selector");
      const sizeSelector = product.querySelector(".size-selector");

      let options = {
        size_id: sizeSelector.value,
        steel_type_id: steelSelector.value,
        thickness_id: thicknessSelector.value,
        type_id: typeSelector.value,
      };

      console.log(dataRes, dataRes.title);

      const getPrice = () => {
        const price = dataRes.prices.find((item) => {
          return (
            item.size_id == options.size_id &&
            item.steel_type_id == options.steel_type_id &&
            item.thickness_id == options.thickness_id &&
            item.type_id == options.type_id
          );
        });

        if (price) {
          priceDeiv.innerHTML = price.one_side;
          priceInput.value = price;
        }
      };

      steelSelector.addEventListener("change", function (e) {
        options.steel_type_id = +e.target.value;
        getPrice();
      });

      thicknessSelector.addEventListener("change", function (e) {
        options.thickness_id = +e.target.value;
        getPrice("thickness");
      });

      typeSelector.addEventListener("change", function (e) {
        options.type_id = +e.target.value;
        getPrice();
      });
      sizeSelector.addEventListener("change", function (e) {
        options.size_id = +e.target.value;
        getPrice();
      });

      priceDeiv.innerHTML = "";
      getPrice();
    });
  }
  // Корзина
});
