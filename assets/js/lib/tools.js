const debounce = (callback, wait) => {
  let timeoutId = null;
  return (...args) => {
    window.clearTimeout(timeoutId);
    timeoutId = window.setTimeout(() => {
      callback(...args);
    }, wait);
  };
};

const throttle = (callback, wait) => {
  let timer = null;
  return (...args) => {
    if (timer === null) {
      timer = setTimeout(() => {
        callback.apply(this, args);
        timer = null;
      }, wait);
    }
  };
};

const dispatch = (code, detail) => {
  const event = new CustomEvent(code, {
    bubbles: true,
    detail,
  });
  document.dispatchEvent(event);
};

const listen = (code, callback) => {
  document.addEventListener(code, e => callback(e.detail), false);
};

const urlParameters = params => Object.keys(params).map(key => `${key}=${params[key]}`).join('&');

// eslint-disable-next-line import/prefer-default-export
export { debounce, throttle, dispatch, listen, urlParameters };
