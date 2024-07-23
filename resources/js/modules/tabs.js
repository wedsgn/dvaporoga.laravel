export const tabs = () => {
  const tabs = document.querySelectorAll(".tab");

  if (!tabs) return;

  const tabButton = document.querySelectorAll(".tab-button");
  const tabContent = document.querySelectorAll(".tab-content");

  tabButton.forEach((tab, i) => {
    tab.addEventListener("click", function () {
      tabButton.forEach((tab) => tab.classList.remove("active"));
      this.classList.add("active");
      tabContent.forEach((content) => content.classList.remove("active"));
      tabContent[i].classList.add("active");
    });
  });
};
