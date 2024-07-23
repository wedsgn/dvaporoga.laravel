import "./bootstrap";
import micromodal from "micromodal";
import { accordition } from "./modules/accordition";
import { sliders } from "./modules/sliders";
import { burger } from "./modules/burger";
import { tabs } from "./modules/tabs";

window.addEventListener("load", () => {
  accordition();
  sliders();
  burger();
  micromodal.init();
  tabs();
});
