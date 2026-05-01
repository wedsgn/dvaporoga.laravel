export const burger = () => {
  const burger = document.querySelector(".burger");

  if (!burger) return;

  const nav = document.querySelector(".mobile-nav");
  const links = document.querySelectorAll(".mobile-nav__wrap a");

  burger.addEventListener("click", function () {
    if (burger.classList.contains("active")) {
      nav.classList.remove("active");
      burger.classList.remove("active");
      document.body.style.overflow = "";
    } else {
      nav.classList.add("active");
      burger.classList.add("active");
      document.body.style.overflow = "hidden";
    }
  });

  links.forEach((item) => {
    item.addEventListener("click", () => {
      nav.classList.remove("active");
      burger.classList.remove("active");
      document.body.style.overflow = "";
    });
  });
};
