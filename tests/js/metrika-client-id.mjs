import assert from "node:assert/strict";
import { readFileSync } from "node:fs";
import vm from "node:vm";

const readModule = (relativePath) =>
  readFileSync(new URL(`../../${relativePath}`, import.meta.url), "utf8");

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

const runClientIdModule = (window) => {
  const code = readModule("resources/js/modules/metrika-client-id.js")
    .replace('import { YM_ID } from "./ym-config";', "const YM_ID = 104319970;")
    .replaceAll("export const ", "const ")
    .concat(
      "\nglobalThis.__exports = { getMetrikaClientId, normalizeMetrikaClientId, startMetrikaClientIdCapture };",
    );

  const context = vm.createContext({
    Date,
    String,
    console,
    window,
  });

  vm.runInContext(code, context);
  return context.__exports;
};

const runFormsAjaxModule = (clientId) => {
  const code = readModule("resources/js/modules/forms-ajax.js")
    .replace(
      'import { getMetrikaClientId } from "./metrika-client-id";',
      "const getMetrikaClientId = () => globalThis.__clientId;",
    )
    .replace("export const appendMetrikaClientId", "const appendMetrikaClientId")
    .concat("\nglobalThis.__exports = { appendMetrikaClientId };");

  const document = {
    body: {
      classList: { remove() {} },
      style: { removeProperty() {} },
    },
    documentElement: {
      style: { removeProperty() {} },
    },
    readyState: "complete",
    activeElement: null,
    addEventListener() {},
    querySelector() {
      return null;
    },
    querySelectorAll() {
      return [];
    },
    getElementById() {
      return null;
    },
  };

  const context = vm.createContext({
    __clientId: clientId,
    alert() {},
    console,
    CustomEvent: class {},
    CSS: { escape: (value) => String(value) },
    document,
    FormData: class {},
    HTMLFormElement: class {},
    HTMLInputElement: class {},
    MicroModal: {},
    setTimeout() {},
    window: { document },
  });

  vm.runInContext(code, context);
  return context.__exports;
};

const createFormData = () => {
  const values = new Map();

  return {
    set(name, value) {
      values.set(name, value);
    },
    get(name) {
      return values.get(name) ?? null;
    },
    has(name) {
      return values.has(name);
    },
  };
};

function testClientIdIsCapturedWhenYmIsAvailable() {
  const timers = createTimers();
  const window = {
    setTimeout: timers.setTimeout,
    clearTimeout: timers.clearTimeout,
    ym(id, method, callback) {
      assert.equal(id, 104319970);
      assert.equal(method, "getClientID");
      callback("12345");
    },
  };

  const module = runClientIdModule(window);
  timers.runDelay(0);

  assert.equal(module.getMetrikaClientId(), "12345");
}

function testMissingYmLeavesGetterNullWithoutThrowing() {
  const timers = createTimers();
  const module = runClientIdModule({
    setTimeout: timers.setTimeout,
    clearTimeout: timers.clearTimeout,
  });

  timers.runDelay(0);
  assert.equal(module.getMetrikaClientId(), null);
}

function testThrowingYmLeavesGetterNull() {
  const timers = createTimers();
  const module = runClientIdModule({
    setTimeout: timers.setTimeout,
    clearTimeout: timers.clearTimeout,
    ym() {
      throw new Error("ym failed");
    },
  });

  timers.runDelay(0);
  assert.equal(module.getMetrikaClientId(), null);
}

function testMissingCallbackLeavesGetterNull() {
  const timers = createTimers();
  const module = runClientIdModule({
    setTimeout: timers.setTimeout,
    clearTimeout: timers.clearTimeout,
    ym() {},
  });

  timers.runDelay(0);
  timers.runDelay(5000);

  assert.equal(module.getMetrikaClientId(), null);
}

function testClientIdIsAddedToFormDataWhenCached() {
  const { appendMetrikaClientId } = runFormsAjaxModule("12345");
  const formData = createFormData();

  const result = appendMetrikaClientId(formData);

  assert.equal(result, formData);
  assert.equal(formData.get("metrika_client_id"), "12345");
}

function testSubmitPayloadDoesNotWaitWhenClientIdIsMissing() {
  const { appendMetrikaClientId } = runFormsAjaxModule(null);
  const formData = createFormData();

  const result = appendMetrikaClientId(formData);

  assert.equal(result, formData);
  assert.equal(formData.has("metrika_client_id"), false);
}

testClientIdIsCapturedWhenYmIsAvailable();
testMissingYmLeavesGetterNullWithoutThrowing();
testThrowingYmLeavesGetterNull();
testMissingCallbackLeavesGetterNull();
testClientIdIsAddedToFormDataWhenCached();
testSubmitPayloadDoesNotWaitWhenClientIdIsMissing();

console.log("metrika client id JS checks passed");
