import { debounce, dispatch, listen } from '../lib/tools';
import { post } from '../lib/request';

const search = async (identifier, url, query, country, limit) => {
  if (query === '') {
    return;
  }
  dispatch(`address-autocomplete:${identifier}:error:hide`, {});
  dispatch(`address-autocomplete:${identifier}:search:start`, { query });
  try {
    const formData = new FormData();
    formData.append('query', query);
    formData.append('limit', limit);
    formData.append('country', country);
    const { data } = await post(url, formData);
    dispatch(`address-autocomplete:${identifier}:search:stop`, { query, result: data });
  } catch (error) {
    dispatch(`address-autocomplete:${identifier}:search:stop`, { query, error, result: null });
  }
};

const init = (component) => {
  const {
    // eslint-disable-next-line max-len
    serviceUrl, limit, identifier, hasReplace, hasSugestions, hasNoResultErrorMessage, noResultErrorMessage, technicalErrorMessage,
  } = component.dataset;
  const results = component.querySelector('[data-target="results"]');
  const loading = component.querySelector('[data-target="loading"]');
  const searchInput = component.querySelector('[data-target="search"]');
  const countryInput = component.querySelector('[data-target="country"]');
  const locationInput = component.querySelector('[data-target="location"]');
  const button = component.querySelector('[data-target="button"]');
  const errorContainer = component.querySelector('[data-target="error-container"]');
  const errorMessage = component.querySelector('[data-target="error-message"]');
  const hasButton = button !== undefined;

  if (hasButton) {
    button.addEventListener('click', async (event) => {
      event.preventDefault();
      await search(identifier, serviceUrl, searchInput.value, countryInput !== null ? countryInput.value : null, limit);
    });
  } else {
    searchInput.addEventListener('input', debounce(async (event) => {
      const input = event.target;
      await search(identifier, serviceUrl, input.value, countryInput !== null ? countryInput.value : null, limit);
    }, 500));
  }

  listen(`address-autocomplete:${identifier}:search:start`, () => {
    dispatch(`address-autocomplete:${identifier}:loading:start`, {});
  });

  listen(`address-autocomplete:${identifier}:loading:start`, () => {
    loading.classList.add(hasButton ? 'disabled' : 'loading');
  });

  listen(`address-autocomplete:${identifier}:loading:stop`, () => {
    loading.classList.remove(hasButton ? 'disabled' : 'loading');
  });

  listen(`address-autocomplete:${identifier}:error:show`, ({ message }) => {
    errorMessage.innerHTML = message;
    errorContainer.classList.remove('hidden');
  });

  listen(`address-autocomplete:${identifier}:error:hide`, () => {
    errorMessage.innerHTML = '';
    errorContainer.classList.add('hidden');
  });

  listen(`address-autocomplete:${identifier}:search:stop`, ({ result, error }) => {
    if (error !== undefined) {
      dispatch(`address-autocomplete:${identifier}:error:show`, { message: technicalErrorMessage });
      dispatch(`address-autocomplete:${identifier}:loading:stop`, {});
    }

    if (result === '' && hasNoResultErrorMessage === 'true') {
      dispatch(`address-autocomplete:${identifier}:error:show`, { message: noResultErrorMessage });
      dispatch(`address-autocomplete:${identifier}:loading:stop`, {});
      return;
    }

    results.innerHTML = result;
    dispatch(`address-autocomplete:${identifier}:loading:stop`, {});

    if (hasSugestions === 'true') {
      results.classList.add('visible');
    } else {
      const first = results.querySelector('[data-target="result"]');
      dispatch(`address-autocomplete:${identifier}:list:select`, {
        location: first ? first.dataset.location : null,
      });
    }

    results.querySelectorAll('[data-target="result"]').forEach((element) => {
      element.addEventListener('click', (event) => {
        const location = JSON.parse(event.target.dataset.location);
        dispatch('address-autocomplete:list:select', { location });
      });
    });
  });

  listen(`address-autocomplete:${identifier}:list:select`, ({ location }) => {
    if (hasSugestions === 'true') {
      results.classList.remove('visible');
    }
    // eslint-disable-next-line no-param-reassign
    locationInput.value = JSON.stringify(location);
    if (hasReplace === 'true') {
      searchInput.value = location.name;
    }
  });
};

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-component="address-search-autocomplete"]').forEach(async (component) => {
    init(component);
  });
});
