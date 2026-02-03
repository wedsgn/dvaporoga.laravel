(() => {
  "use strict";

  if (window.__ymFormsGoalInstalled) return;
  window.__ymFormsGoalInstalled = true;

  const YM_ID = 104319970;
  const ATTR = "data-ym-goal";
  const MODE_ATTR = "data-ym-mode";
  const DEV = true;

  const GOAL_MAP = {
    "cart-lead": "lead",
    "calculator": "calculator",
    "banner": "banner",
    "faq": "faq",
    "company": "company",
    "delivery": "delivery",
    "automatic": "automatic",
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

  const fire = (form, extra) => {
    if (!form || typeof window.ym !== "function") return;

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

    const params = Object.assign(
      {
        goal_name: goal,
        label: rawGoal || "unknown",
        form_id: fidEl ? fidEl.value : form.id || "",
        action: action || null,
        page: window.location.origin + window.location.pathname,
        mode,
      },
      extra || {}
    );

    devLog("FIRE", { goal, rawGoal, params });
    window.ym(YM_ID, "reachGoal", goal, params);
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

      fire(form, { trigger: "ajax_success" });
    },
    true
  );
})();
