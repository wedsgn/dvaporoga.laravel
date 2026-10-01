import { YM_ID } from "./ym-config";

(() => {
  "use strict";

  if (window.__ymFormsGoalInstalled) return;
  window.__ymFormsGoalInstalled = true;

  const ATTR = "data-ym-goal";
  const MODE_ATTR = "data-ym-mode";
  const DEV = true;
  const CALLBACK_TIMEOUT_MS = 5000;
  const ACK_MAX_ATTEMPTS = 2;
  const ACK_RETRY_MS = 600;

  const GOAL_MAP = {
    "cart-lead": "lead",
    "calculator": "calculator",
    "banner": "banner",
    "faq": "faq",
    "company": "company",
    "delivery": "delivery",
    "automatic": "automatic",
    "partnership": "partnership",
  };

  const perFormTs = new WeakMap();

  const resolveGoal = (raw) => {
    if (!raw) return null;
    raw = String(raw).trim().toLowerCase();
    return GOAL_MAP[raw] || raw;
  };

  const getAction = (form) =>
    form.getAttribute("action") || (form.dataset ? form.dataset.action || "" : "");

  const devLog = (...args) => {
    if (!DEV) return;
    console.log("%c[YMGoals]", "color:#32a852;font-weight:bold;", ...args);
  };

  const csrfToken = () =>
    document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") ||
    document.querySelector('input[name="_token"]')?.value ||
    "";

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

  const sendAck = (trackingAckUrl, trackingId, status, extra) => {
    if (!trackingAckUrl || !trackingId) return;

    postAck(trackingAckUrl, {
      channel: "yandex_metrika",
      status,
      error_code: extra?.error_code || null,
      error_message: extra?.error_message || null,
      meta: {
        goal: extra?.goal || null,
        form_id: extra?.form_id || null,
        mode: extra?.mode || null,
        trigger: extra?.trigger || null,
      },
    });
  };

  const fire = (form, extra) => {
    if (!form) return;

    const last = perFormTs.get(form) || 0;
    if (Date.now() - last < 4000) {
      devLog("SKIP duplicate fire:", form);
      return;
    }
    perFormTs.set(form, Date.now());

    const rawGoal = form.getAttribute(ATTR);
    const goal = resolveGoal(rawGoal);
    if (!goal) return;

    const fidEl = form.querySelector('[name="form_id"]');
    const action = getAction(form);
    const mode = (form.getAttribute(MODE_ATTR) || "auto").toLowerCase();
    const extraParams = Object.assign({}, extra || {});
    delete extraParams.trackingAckUrl;
    delete extraParams.trackingId;
    delete extraParams.ymTrigger;

    const params = Object.assign(
      {
        goal_name: goal,
        label: rawGoal || "unknown",
        form_id: fidEl ? fidEl.value : form.id || "",
        action: action || null,
        page: window.location.origin + window.location.pathname,
        mode,
        tracking_id: extra?.trackingId || null,
      },
      extraParams
    );

    devLog("FIRE", { goal, rawGoal, params });

    const trackingAckUrl = extra?.trackingAckUrl || "";
    const trackingId = extra?.trackingId || "";

    if (typeof window.ym !== "function") {
      sendAck(trackingAckUrl, trackingId, "unavailable", {
        goal,
        form_id: params.form_id,
        mode,
        trigger: params.trigger,
        error_code: "ym_not_available",
        error_message: "window.ym is not available",
      });
      return;
    }

    let callbackResolved = false;
    let timeoutId = null;

    const clearCallbackTimeout = () => {
      if (!timeoutId) return;

      try {
        window.clearTimeout(timeoutId);
      } catch (_) {}
    };

    const sendFinalAck = (status, ackExtra = {}) => {
      if (callbackResolved) return;
      callbackResolved = true;
      clearCallbackTimeout();
      sendAck(trackingAckUrl, trackingId, status, {
        goal,
        form_id: params.form_id,
        mode,
        trigger: params.trigger,
        ...ackExtra,
      });
    };

    try {
      timeoutId = window.setTimeout(() => {
        if (callbackResolved) return;

        sendAck(trackingAckUrl, trackingId, "unconfirmed", {
          goal,
          form_id: params.form_id,
          mode,
          trigger: params.trigger,
          error_code: "callback_timeout",
          error_message: "Yandex Metrika callback was not received in time",
        });
      }, CALLBACK_TIMEOUT_MS);

      window.ym(YM_ID, "reachGoal", goal, params, () => {
        sendFinalAck("sent");
      });
    } catch (error) {
      sendFinalAck("failed", {
        error_code: "ym_exception",
        error_message: shortText(error?.message || error || "Yandex Metrika reachGoal failed"),
      });
    }
  };

  window.YMGoals = window.YMGoals || { fire };

  // ВАЖНО: мы слушаем успех именно от AJAX (form:success)
  document.addEventListener(
    "form:success",
    (e) => {
      const form = e && e.detail && e.detail.form;
      if (!form) return;
      if (!form.hasAttribute(ATTR)) return;

      const mode = (form.getAttribute(MODE_ATTR) || "auto").toLowerCase();
      if (mode !== "manual") {
        devLog("SKIP: form is not manual", form);
        return;
      }

      fire(form, {
        trigger: e?.detail?.ymTrigger || "ajax_success",
        trackingId: e?.detail?.trackingId || "",
        trackingAckUrl: e?.detail?.trackingAckUrl || "",
      });
    },
    true
  );
})();
