(() => {
  "use strict";

  if (typeof document === "undefined") return;

  const RETRY_INTERVAL_MS = 250;
  const MAX_WAIT_MS = 15000;
  const CALLBACK_TIMEOUT_MS = 5000;
  const ACK_MAX_ATTEMPTS = 2;
  const ACK_RETRY_MS = 600;
  const URL_TRACKING_PARAMS = [
    "utm_source",
    "utm_medium",
    "utm_campaign",
    "utm_term",
    "utm_content",
    "cm_id",
  ];

  const valueOf = (formData, field) => {
    const value = formData.get(field);
    return typeof value === "string" ? value.trim() : "";
  };

  const csrfToken = () =>
    document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") ||
    document.querySelector('input[name="_token"]')?.value ||
    "";

  const pageUrlOf = (formData) => {
    const currentUrl = valueOf(formData, "current_url");
    if (currentUrl) return sanitizePageUrl(currentUrl);

    try {
      return typeof window !== "undefined" ? sanitizePageUrl(window.location.href) : "";
    } catch (_) {
      return "";
    }
  };

  const sanitizePageUrl = (url) => {
    try {
      const raw = String(url || "");
      if (!raw) return "";

      const parsed = new URL(
        raw,
        typeof window !== "undefined" && window.location ? window.location.href : undefined,
      );

      URL_TRACKING_PARAMS.forEach((param) => {
        parsed.searchParams.delete(param);
      });

      if (/^\/(?!\/)/.test(raw)) {
        return `${parsed.pathname}${parsed.search}${parsed.hash}`;
      }

      return parsed.toString();
    } catch (_) {
      return String(url || "");
    }
  };

  const shortText = (value, max = 500) => {
    if (value === null || value === undefined) return "";
    const text = String(value).trim();
    return text.length > max ? text.slice(0, max) : text;
  };

  const postAck = (url, body, attempt = 1) => {
    try {
      window.fetch(url, {
        method: "POST",
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
          ...(csrfToken() ? { "X-CSRF-TOKEN": csrfToken() } : {}),
        },
        credentials: "same-origin",
        keepalive: true,
        body: JSON.stringify(body),
      })
        .then((response) => {
          if (response && response.status >= 500 && attempt < ACK_MAX_ATTEMPTS) {
            window.setTimeout(() => postAck(url, body, attempt + 1), ACK_RETRY_MS);
          }
        })
        .catch(() => {
          if (attempt < ACK_MAX_ATTEMPTS) {
            window.setTimeout(() => postAck(url, body, attempt + 1), ACK_RETRY_MS);
          }
        });
    } catch (_) {}
  };

  const sendAck = (context, status, extra = {}) => {
    if (!context.trackingId || !context.trackingAckUrl) return;

    const body = {
      channel: "uis",
      status,
      error_code: extra.error_code || null,
      error_message: extra.error_message || null,
      meta: {
        form_id: valueOf(context.formData, "form_id") || null,
        callback_success: extra.callback_success ?? null,
        info: extra.info || null,
      },
    };

    postAck(context.trackingAckUrl, body);
  };

  const buildPayload = (formData, trackingId) => {
    const message = [
      ["Форма", valueOf(formData, "form_id") || "не указана"],
      ["Tracking ID", trackingId || "не указан"],
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
      form_name: valueOf(formData, "form_id") || undefined,
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

  const callbackSuccess = (response) => {
    if (response === true) return true;
    if (response === false) return false;

    if (!response || typeof response !== "object") return null;

    const explicit = response.success ?? response.ok ?? response.result;
    if (explicit === true || explicit === 1 || explicit === "1" || explicit === "true") {
      return true;
    }
    if (explicit === false || explicit === 0 || explicit === "0" || explicit === "false") {
      return false;
    }

    const status = String(response.status || "").toLowerCase();
    if (["ok", "success", "sent", "done"].includes(status)) return true;
    if (["error", "failed", "fail"].includes(status)) return false;

    if (response.error || response.error_code || response.errorMessage) return false;

    return null;
  };

  const errorInfo = (response) => {
    if (!response || typeof response !== "object") return {};

    return {
      error_code: shortText(response.error_code || response.code || response.error, 100),
      error_message: shortText(
        response.error_message ||
          response.errorMessage ||
          response.message ||
          response.info ||
          "UIS callback reported failure",
      ),
    };
  };

  const sendWhenReady = (payload, context) => {
    const startedAt = Date.now();

    const attempt = () => {
      const addOfflineRequest = getAddOfflineRequest();

      if (addOfflineRequest) {
        let callbackResolved = false;
        let timeoutId = null;

        const clearCallbackTimeout = () => {
          if (!timeoutId) return;

          try {
            window.clearTimeout(timeoutId);
          } catch (_) {}
        };

        const sendFinalAck = (status, extra = {}) => {
          if (callbackResolved) return;
          callbackResolved = true;
          clearCallbackTimeout();
          sendAck(context, status, extra);
        };

        const callback = (response) => {
          const success = callbackSuccess(response);

          if (success === true) {
            sendFinalAck("sent", { callback_success: true });
            return;
          }

          if (success === false) {
            sendFinalAck("failed", {
              callback_success: false,
              ...errorInfo(response),
            });
            return;
          }

          sendFinalAck("unconfirmed", {
            error_code: "callback_without_status",
            error_message: "UIS callback did not include an explicit success flag",
            info: shortText(JSON.stringify(response || null), 255),
          });
        };

        try {
          timeoutId = window.setTimeout(() => {
            if (callbackResolved) return;

            sendAck(context, "unconfirmed", {
              error_code: "callback_timeout",
              error_message: "UIS callback was not received in time",
            });
          }, CALLBACK_TIMEOUT_MS);

          const result = addOfflineRequest(payload, callback);

          if (result && typeof result.then === "function") {
            Promise.resolve(result).catch((error) => {
              sendFinalAck("failed", {
                error_code: "promise_rejected",
                error_message: shortText(error?.message || error || "UIS promise rejected"),
              });
            });
          }
        } catch (error) {
          sendFinalAck("failed", {
            error_code: "add_offline_request_exception",
            error_message: shortText(error?.message || error || "UIS addOfflineRequest failed"),
          });
        }

        return;
      }

      if (Date.now() - startedAt >= MAX_WAIT_MS) {
        sendAck(context, "unavailable", {
          error_code: "comagic_not_available",
          error_message: "Comagic object was not available before timeout",
        });
        return;
      }

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

      const context = {
        formData,
        trackingId: event?.detail?.trackingId || "",
        trackingAckUrl: event?.detail?.trackingAckUrl || "",
      };

      sendWhenReady(buildPayload(formData, context.trackingId), context);
    } catch (_) {}
  });
})();
