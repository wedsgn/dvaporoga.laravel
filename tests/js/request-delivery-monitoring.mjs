import assert from "node:assert/strict";
import { readFileSync } from "node:fs";
import vm from "node:vm";

const readModule = (relativePath) =>
  readFileSync(new URL(`../../${relativePath}`, import.meta.url), "utf8");

const readBrowserModule = (relativePath) =>
  readModule(relativePath).replace(
    'import { YM_ID } from "./ym-config";',
    "const YM_ID = 104319970;",
  );

const createTimers = () => {
  let nextId = 1;
  const timers = [];

  const setTimeout = (callback, delay = 0) => {
    const id = nextId++;
    timers.push({ id, callback, delay, active: true });
    return id;
  };

  const clearTimeout = (id) => {
    const timer = timers.find((item) => item.id === id);
    if (timer) timer.active = false;
  };

  const runDelay = (delay) => {
    const due = timers.filter((timer) => timer.active && timer.delay === delay);
    due.forEach((timer) => {
      timer.active = false;
      timer.callback();
    });
  };

  return { setTimeout, clearTimeout, runDelay };
};

const flushPromises = async () => {
  await Promise.resolve();
  await new Promise((resolve) => setImmediate(resolve));
};

const createDocument = () => {
  const listeners = new Map();

  return {
    listeners,
    addEventListener(type, handler) {
      listeners.set(type, handler);
    },
    querySelector() {
      return null;
    },
  };
};

const createFormData = (values) => ({
  get(name) {
    return values[name] ?? "";
  },
});

const runInBrowserVm = (code, window) => {
  const context = vm.createContext({
    console,
    document: window.document,
    window,
    URL,
    Promise,
    JSON,
    String,
    Date,
  });

  vm.runInContext(code, context);
};

const parseAck = (record) => JSON.parse(record.body);

async function testUisLateCallbackRecovery() {
  const timers = createTimers();
  const document = createDocument();
  const ackRecords = [];
  let addOfflineRequestCalls = 0;
  let uisCallback = null;
  let uisPayload = null;

  const window = {
    document,
    location: {
      href: "https://dvaporoga.ru/fallback?utm_source=ad&foo=1&cm_id=abc#hash",
    },
    setTimeout: timers.setTimeout,
    clearTimeout: timers.clearTimeout,
    fetch(url, options) {
      ackRecords.push({ url, ...options });
      return Promise.resolve({ status: 200 });
    },
    Comagic: {
      addOfflineRequest(payload, callback) {
        addOfflineRequestCalls += 1;
        uisPayload = payload;
        uisCallback = callback;
      },
    },
  };

  runInBrowserVm(readModule("resources/js/modules/uis-form-tracking.js"), window);

  document.listeners.get("form:success")({
    detail: {
      formData: createFormData({
        form_id: "modal-form-header",
        phone: "+79990000000",
        current_url:
          "https://dvaporoga.ru/page?utm_source=ad&utm_medium=cpc&foo=bar&cm_id=secret#lead",
      }),
      trackingId: "tracking-uis",
      trackingAckUrl: "/request-tracking/ack?signed=1",
    },
  });

  timers.runDelay(0);

  assert.equal(addOfflineRequestCalls, 1);
  assert.match(uisPayload.message, /foo=bar/);
  assert.match(uisPayload.message, /#lead/);
  assert.doesNotMatch(uisPayload.message, /utm_source/);
  assert.doesNotMatch(uisPayload.message, /utm_medium/);
  assert.doesNotMatch(uisPayload.message, /cm_id/);

  timers.runDelay(5000);
  await flushPromises();

  assert.equal(ackRecords.length, 1);
  assert.equal(parseAck(ackRecords[0]).status, "unconfirmed");

  uisCallback({ success: true });
  await flushPromises();

  assert.equal(addOfflineRequestCalls, 1);
  assert.equal(ackRecords.length, 2);
  assert.equal(parseAck(ackRecords[1]).status, "sent");
  assert.equal("attempts" in parseAck(ackRecords[0]), false);
  assert.equal("attempts" in parseAck(ackRecords[1]), false);
}

async function testYandexLateCallbackRecovery() {
  const timers = createTimers();
  const document = createDocument();
  const ackRecords = [];
  let reachGoalCalls = 0;
  let ymCallback = null;
  let ymParams = null;

  const form = {
    id: "lead-form",
    dataset: {},
    getAttribute(name) {
      return {
        "data-ym-goal": "calculator",
        "data-ym-mode": "manual",
        action: "/request-consultation",
      }[name] ?? "";
    },
    hasAttribute(name) {
      return name === "data-ym-goal";
    },
    querySelector(selector) {
      if (selector === '[name="form_id"]') {
        return { value: "modal-form-header" };
      }

      return null;
    },
  };

  const window = {
    document,
    location: {
      origin: "https://dvaporoga.ru",
      pathname: "/page",
    },
    setTimeout: timers.setTimeout,
    clearTimeout: timers.clearTimeout,
    fetch(url, options) {
      ackRecords.push({ url, ...options });
      return Promise.resolve({ status: 200 });
    },
    ym(id, method, goal, params, callback) {
      reachGoalCalls += 1;
      ymParams = params;
      ymCallback = callback;
    },
  };

  runInBrowserVm(readBrowserModule("resources/js/modules/ym-goals.js"), window);

  document.listeners.get("form:success")({
    detail: {
      form,
      trackingId: "tracking-ym",
      trackingAckUrl: "/request-tracking/ack?signed=1",
    },
  });

  assert.equal(reachGoalCalls, 1);
  assert.equal(ymParams.tracking_id, "tracking-ym");

  timers.runDelay(5000);
  await flushPromises();

  assert.equal(ackRecords.length, 1);
  assert.equal(parseAck(ackRecords[0]).status, "unconfirmed");

  ymCallback();
  await flushPromises();

  assert.equal(reachGoalCalls, 1);
  assert.equal(ackRecords.length, 2);
  assert.equal(parseAck(ackRecords[1]).status, "sent");
  assert.equal("attempts" in parseAck(ackRecords[0]), false);
  assert.equal("attempts" in parseAck(ackRecords[1]), false);
}

await testUisLateCallbackRecovery();
await testYandexLateCallbackRecovery();

console.log("request delivery monitoring JS checks passed");
