(() => {
  "use strict";

  const parsePrice = (raw) => {
    const normalized = String(raw || "")
      .replace(/\s+/g, "")
      .replace("₽", "")
      .trim();

    return normalized ? parseInt(normalized, 10) : "";
  };

  const fillProductModal = (btn) => {
    const form = document.getElementById("modal-product-form");
    if (!form) return;

    const pid = btn.dataset.productId || "";
    const title = btn.dataset.productTitle || "";
    const priceRaw = btn.dataset.productPrice || "";
    const requestSource = btn.dataset.requestSource || "car";
    const requestCar = btn.dataset.requestCar || "";

    const parsedPrice = parsePrice(priceRaw);

    const dataInput = document.getElementById("modal-product-data");
    const totalInput = document.getElementById("modal-product-total");
    const carInput = document.getElementById("modal-product-car");
    const titleNode = document.getElementById("modal-product-title");

    if (dataInput) {
      dataInput.value = JSON.stringify(
        pid
          ? [
              {
                id: Number(pid),
                title: title || "",
                source: requestSource,
                car: requestSource === "home" ? null : requestCar || null,
              },
            ]
          : []
      );
    }

    if (totalInput) {
      totalInput.value = parsedPrice || "";
    }

    if (carInput) {
      carInput.value =
        requestSource === "home" ? "Без привязки к авто(Блок на главной)" : requestCar || "";
    }

    if (titleNode) {
      titleNode.textContent = title ? `Заказ: ${title}` : "Заполните форму";
    }
  };

  const resetProductModal = () => {
    const titleNode = document.getElementById("modal-product-title");
    const dataInput = document.getElementById("modal-product-data");
    const totalInput = document.getElementById("modal-product-total");
    const carInput = document.getElementById("modal-product-car");

    if (titleNode) {
      titleNode.textContent = "Заполните форму";
    }

    if (dataInput) {
      dataInput.value = "[]";
    }

    if (totalInput) {
      totalInput.value = "";
    }

    if (carInput) {
      carInput.value = "";
    }
  };

  document.addEventListener("click", (e) => {
    const btn = e.target.closest('[data-micromodal-trigger="modal-product"]');
    if (!btn) return;

    fillProductModal(btn);
  });

  document.addEventListener("form:success", (e) => {
    const form = e.detail?.form;
    if (!form || form.id !== "modal-product-form") return;

    resetProductModal();
  });
})();
