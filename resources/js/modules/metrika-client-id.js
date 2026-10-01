import { YM_ID } from "./ym-config";

const WAIT_FOR_YM_MS = 5000;
const CALLBACK_TIMEOUT_MS = 5000;
const POLL_MS = 250;
const CLIENT_ID_PATTERN = /^[A-Za-z0-9._:-]+$/;

let cachedClientId = null;
let captureStarted = false;
let captureCompleted = false;

export const normalizeMetrikaClientId = (value) => {
  if (value === null || value === undefined) return null;

  const type = typeof value;
  if (type !== "string" && type !== "number" && type !== "bigint") {
    return null;
  }

  const clientId = String(value).trim();
  if (!clientId || clientId.length > 100) return null;

  return CLIENT_ID_PATTERN.test(clientId) ? clientId : null;
};

export const getMetrikaClientId = () => cachedClientId;

const finishCapture = (value, timeoutId) => {
  captureCompleted = true;

  if (timeoutId) {
    try {
      window.clearTimeout(timeoutId);
    } catch (_) {}
  }

  const normalized = normalizeMetrikaClientId(value);
  if (normalized) {
    cachedClientId = normalized;
  }
};

const requestClientId = () => {
  if (
    captureCompleted ||
    typeof window === "undefined" ||
    typeof window.ym !== "function"
  ) {
    return;
  }

  let settled = false;
  let timeoutId = null;

  const finishOnce = (value = null) => {
    if (settled) return;
    settled = true;
    finishCapture(value, timeoutId);
  };

  timeoutId = window.setTimeout(() => finishOnce(null), CALLBACK_TIMEOUT_MS);

  try {
    window.ym(YM_ID, "getClientID", finishOnce);
  } catch (_) {
    finishOnce(null);
  }
};

export const startMetrikaClientIdCapture = () => {
  if (captureStarted || typeof window === "undefined") return;

  captureStarted = true;
  const startedAt = Date.now();

  const poll = () => {
    if (captureCompleted) return;

    if (typeof window.ym === "function") {
      requestClientId();
      return;
    }

    if (Date.now() - startedAt >= WAIT_FOR_YM_MS) {
      captureCompleted = true;
      return;
    }

    window.setTimeout(poll, POLL_MS);
  };

  window.setTimeout(poll, 0);
};

startMetrikaClientIdCapture();
