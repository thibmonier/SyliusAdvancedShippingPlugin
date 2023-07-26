const closePanels = (panels) => {
  panels.forEach((panel) => {
    panel.classList.add('hidden');
  });
};

const openPanel = (panel) => {
  if (!panel) {
    return;
  }
  panel.classList.remove('hidden');
};

const init = () => {
  const form = document.querySelector('form[name="sylius_checkout_select_shipping"]');
  if (!form) {
    return;
  }

  const panels = form.querySelectorAll('[data-shipping-method]');
  closePanels(panels);

  form.querySelectorAll('input[type="radio"]').forEach((radio) => {
    const method = radio.value;
    const panel = form.querySelector(`[data-shipping-method="${method}"]`);
    if (panel !== null && radio.checked === true) {
      openPanel(panel);
    }

    radio.addEventListener('change', (event) => {
      closePanels(panels);
      openPanel(panel);

      document.dispatchEvent(new CustomEvent('advanced-shipping-selector:selected-method', {
        detail: {
          panel,
          input: event.target,
        },
      }));
    });
  });
}

document.addEventListener('DOMContentLoaded', () => {
  init();
});

document.addEventListener('advanced-shipping-selector:update-form', () => {
  init();
});
