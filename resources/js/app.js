import "./bootstrap";
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
});
