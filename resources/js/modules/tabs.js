export const tabs = () => {
  const tabs = document.querySelectorAll(".tab");

  if (!tabs) return;

  tabs.forEach(function (item) {
    const tabButton = item.querySelectorAll(".tab-button");
    const tabContent = item.querySelectorAll(".tab-content");
  });
};
