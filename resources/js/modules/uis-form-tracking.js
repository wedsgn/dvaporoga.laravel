(() => {
  "use strict";

  if (typeof document === "undefined") return;

  const RETRY_INTERVAL_MS = 250;
  const MAX_WAIT_MS = 5000;

  const valueOf = (formData, field) => {
    const value = formData.get(field);
    return typeof value === "string" ? value.trim() : "";
  };

  const pageUrlOf = (formData) => {
    const currentUrl = valueOf(formData, "current_url");
    if (currentUrl) return currentUrl;

    try {
      return typeof window !== "undefined" ? window.location.href : "";
    } catch (_) {
      return "";
    }
  };

  const buildPayload = (formData) => {
    const message = [
      ["Форма", valueOf(formData, "form_id") || "не указана"],
      ["Страница", pageUrlOf(formData) || "не указана"],
      ["Авто", valueOf(formData, "car")],
      ["Car ID", valueOf(formData, "car_id")],
      ["Итого", valueOf(formData, "total_price")],
      ["Товары", valueOf(formData, "data")],
    ]
      .filter(([, value]) => value)
      .map(([label, value]) => `${label}: ${value}`)
      .join("\n");

    return {
      name: valueOf(formData, "name"),
      email: valueOf(formData, "email"),
      phone: valueOf(formData, "phone"),
      message,
    };
  };

  const getAddOfflineRequest = () => {
    try {
      if (
        typeof window === "undefined" ||
        !window.Comagic ||
        typeof window.Comagic.addOfflineRequest !== "function"
      ) {
        return null;
      }

      return window.Comagic.addOfflineRequest.bind(window.Comagic);
    } catch (_) {
      return null;
    }
  };

  const sendWhenReady = (payload) => {
    const startedAt = Date.now();

    const attempt = () => {
      const addOfflineRequest = getAddOfflineRequest();

      if (addOfflineRequest) {
        try {
          const result = addOfflineRequest(payload);

          if (result && typeof result.then === "function") {
            Promise.resolve(result).catch(() => {});
          }
        } catch (_) {}

        return;
      }

      if (Date.now() - startedAt >= MAX_WAIT_MS) return;

      try {
        window.setTimeout(attempt, RETRY_INTERVAL_MS);
      } catch (_) {}
    };

    try {
      window.setTimeout(attempt, 0);
    } catch (_) {}
  };

  document.addEventListener("form:success", (event) => {
    try {
      const formData = event?.detail?.formData;
      if (!formData || typeof formData.get !== "function") return;

      sendWhenReady(buildPayload(formData));
    } catch (_) {}
  });
})();
