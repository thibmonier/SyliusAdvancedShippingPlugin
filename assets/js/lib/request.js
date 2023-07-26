const defaultHeader = {
  'X-Requested-With': 'XMLHttpRequest'
};

const isJsonResponse = (response) => {
  if (undefined === response.headers || null === response.headers) {
    return false;
  }
  const contentType = response.headers.get("content-type");
  return contentType && contentType.indexOf("application/json") !== -1;
}

const isBlobResponse = (response) => {
  if (undefined === response.headers || null === response.headers) {
    return false;
  }
  const contentType = response.headers.get("content-type");
  return contentType && contentType.indexOf("text/html") === -1 && contentType.indexOf("application/json") === -1;
}

const manageResponse = async (response, resolve, reject) => {
  if (true !== response.ok) {
    const error = isJsonResponse(response)
      ? await response.json()
      : await response.text()
    ;
    reject({status:response.status, error});
    return;
  }

  let data = null;
  if (isBlobResponse(response))
    data = await response.blob();
   else  if (isJsonResponse(response))
    data = await response.json();
   else
     data = await response.text();

  resolve({
    data,
    bodyUsed: response.bodyUsed,
    ok: response.ok,
    redirected: response.redirected,
    status: response.status,
    statusText: response.statusText,
    type: response.type,
    url: response.url,
  });
}

export const get = (url, headers = {}) => {
  return new Promise(async (resolve, reject) => {
    let response = await fetch(url, {
      headers: new Headers({...defaultHeader, ...headers})
    })
    await manageResponse(response, resolve, reject);
  })
}

export const post = (url, body, headers = {}) => {
  return new Promise(async (resolve, reject) => {
    let response = await fetch(url, {
      method: 'POST',
      body: body,
      headers: new Headers({...defaultHeader, ...headers})
    })
    await manageResponse(response, resolve, reject);
  })
}
