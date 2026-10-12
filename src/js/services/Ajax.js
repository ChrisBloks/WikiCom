// ============================================================
// ajaxPOST frame for making ajax requests through POST
// ============================================================
export async function ajaxPOST(url, response_type, data, success, fail) {
  let result;

  try {
    const response = await fetch(url, {
      method: "POST",
      headers: { "X-Requested-With": "XMLHttpRequest" },
      // URLSearchParams: urlencoded, fills $_POST because JS can't by itself
      // jquery does this for you so we have to do it manually
      // this is also probably not the correct way to do it but it works:
      // https://developer.mozilla.org/en-US/docs/Web/API/URLSearchParams
      body: data instanceof FormData ? data : new URLSearchParams(data),
    });

    if (!response.ok) {
      return fail(await response.text());
    }

    // if its json, use json. Otherwise text. Might need XML later?
    result =
      response_type === "json" ? await response.json() : await response.text();
  } catch (error) {
    // network failure OR invalid JSON from the server
    return fail(error.message || "Network error");
  }

  success(result);
}

export async function ajaxGET(url, response_type, success, fail) {
  let result;

  try {
    const response = await fetch(url, {
      method: "GET",
      headers: { "X-Requested-With": "XMLHttpRequest" },
    });

    if (!response.ok) {
      return fail(await response.text());
    }

    // if its json, use json. Otherwise text. Might need XML later?
    result =
      response_type === "json" ? await response.json() : await response.text();
  } catch (error) {
    // network failure OR invalid JSON from the server
    return fail(error.message || "Network error");
  }

  success(result);
}
