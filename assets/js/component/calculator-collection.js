// Reactivate native collection JS management after calculator selection
const initReloadCollection = () => {
  const config = document.querySelector('.ui.segment.configuration');
  if (config) {
    config.addEventListener('DOMSubtreeModified', () => {
      $('[data-form-type="collection"]').CollectionForm();
    });
  }
};

document.addEventListener('DOMContentLoaded', () => {
  if (window.jQuery) {
    initReloadCollection();
  }
});
