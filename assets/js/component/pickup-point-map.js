// eslint-disable-next-line import/no-unresolved
import { get } from '@lib/request';
import { urlParameters } from '@lib/tools';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

/**
 * How to use:
 *
 * <div data-component="map">
 *   <ul>
 *       <li>
 *          <input type="radio" name="pickupPoint" id="pickupPoint-P72703" value="P72703"
 *                 data-target="pickup-point"
 *                 data-value="{{ {'latitude': 50.70076800000, 'longitude': 3.13620000000}|json_encode }}"
 *          />
 *          <label for="pickupPoint-P72703">
 *            P72703<br/>
 *            1 place saint ..<br/>
 *            <a href="#" data-action="pickup-point-hours" data-selector=".hours">Horaires</a>
 *            <div class="hidden hours">
 *              Lundi xxx
 *              Mardi xxxx
 *              ...
 *              Fermetures expectionnelles:
 *              du 19 au 26/02/XX
 *            </div>
 *          </label>
 *       </li>
 *   </ul>
 *  <div data-target="map-zone" style="height: 500px">
 *  </div>
 * </div>
 */

const config = {};

// ### UTILITY METHODS ###
const startLoader = (identifier, zones) => {
  document.dispatchEvent(new CustomEvent(`address-autocomplete:${identifier}:loading:start`));
  zones.forEach((elem) => {
    elem.classList.add('loading');
    elem.classList.add('active');
  });
};

const stopLoader = (identifier, zones) => {
  document.dispatchEvent(new CustomEvent(`address-autocomplete:${identifier}:loading:stop`));
  zones.forEach((elem) => {
    elem.classList.remove('loading');
    elem.classList.remove('active');
  });
};

const getConfig = component => config[component];

const getUnactiveIcon = config => L.icon({
  iconUrl: config.unactiveMarkerIcon,
  iconSize: [40, 40],
});

const getActiveIcon = config => L.icon({
  iconUrl: config.activeMarkerIcon,
  iconSize: [40, 40],
});

const changeIcon = (config, marker, markersList) => {
  markersList.forEach((currentMarker) => {
    currentMarker.setIcon(getUnactiveIcon(config));
  });
  marker.setIcon(getActiveIcon(config));
};

const uniqId = (component) => {
  let counter = 0;
  let identifier = '';
  do {
    counter += 1;
    identifier = `pickup-point-map-${counter}`;
  } while (document.getElementById(identifier) !== null);
  component.id = identifier;
  return identifier;
};

// ### EVENT MANAGEMENT UTILITIES ###
const dispatchEvent = (name, details) => {
  document.dispatchEvent(new CustomEvent(name, { detail: details }));
};

const addListener = (event, element, callback) => {
  const eventCallback = (event) => {
    event.stopImmediatePropagation();
    callback(event);
  };
  element.removeEventListener(event, eventCallback);
  element.addEventListener(event, eventCallback, true);
};

// ### PICKUP POINT MANAGEMENT ###
const createPickupPoints = (component) => {
  const config = getConfig(component);

  const markersGroup = L.featureGroup();
  const markersList = [];
  config.map.addLayer(markersGroup);
  const pickupPointList = config.component.querySelectorAll('[data-target="pickup-point"]');

  const icon = getUnactiveIcon(config);
  pickupPointList.forEach((pickupPoint) => {
    const pickupPointModel = JSON.parse(pickupPoint.dataset.value);
    const markerPosition = [pickupPointModel.latitude, pickupPointModel.longitude];
    const marker = L.marker(markerPosition, {
      icon,
    }).addTo(markersGroup);

    // Choose pickup point on the left pannel when mouse over map marker
    marker.on('mouseover', () => {
      // eslint-disable-next-line no-use-before-define
      selectPickupPoint(config, pickupPoint, pickupPointList, marker, markersList);
    });

    // Choose map marker when mouse click pickup point on the left pannel
    addListener('mouseover', pickupPoint, () => {
      // eslint-disable-next-line no-use-before-define
      selectPickupPoint(config, pickupPoint, pickupPointList, marker, markersList);
    });

    // Choose map marker when mouse click pickup point on the left pannel
    marker.on('click', () => {
      // eslint-disable-next-line no-use-before-define
      selectPickupPoint(config, pickupPoint, pickupPointList, marker, markersList);
      config.listZone.scrollTop = pickupPoint.offsetTop - 10;
      dispatchEvent('advanced-shipping-map:pickup-point:marker-click', {
        component: config.component,
        pickupPoint,
        listZone: config.listZone
      });
    });

    addListener('change', pickupPoint, () => {
      changeIcon(config, marker, markersList);
    });

    const choiceButton = pickupPoint.querySelector('[data-action="choice-pickup-point"]');
    addListener('click', choiceButton, () => {
      const information = pickupPoint.querySelector('[data-target="pickup-point-information"]');
      dispatchEvent('advanced-shipping-map:pickup-point:selected', {
        component,
        pickupPointModel,
        pickupPointInformation: information,
        shippingMethod: config.shippingMethod,
      });
    });

    markersList.push(marker);
  });


  config.map.fitBounds(markersGroup.getBounds().pad(0.05));
};

const selectPickupPoint = (config, pickupPoint, pickupPointList, marker, markersList) => {
  changeIcon(config, marker, markersList);
  pickupPointList.forEach((element) => {
    element.querySelectorAll('[data-target="selectable"]').forEach(e => e.classList.remove(config.activeClass));
  });
  pickupPoint.querySelectorAll('[data-target="selectable"]').forEach(e => e.classList.add(config.activeClass));
  dispatchEvent('advanced-shipping-map:pickup-point:map-marker-selected', {
    component: config.component,
    pickupPoint,
    listZone: config.listZone
  });
};

// ### SEARCH ACTION MANAGEMENT ###
const search = async (component, searchLabel, location) => {
  const config = getConfig(component);
  startLoader(config.id, config.loadingZones);
  config.searchFieldTarget.value = searchLabel;
  const { data: list } = await get(`${config.serviceUrl}?${urlParameters(JSON.parse(location))}`);
  config.listZone.innerHTML = list;
  activeResultMode(component);
  initAction(component);
  stopLoader(config.id, config.loadingZones);
  searchDone(component, true);
  dispatchEvent('advanced-shipping-map:search:done', { component });
};

const searchDone = (component, status = null) => {
  const config = getConfig(component);
  if (status !== null) {
    config.component.dataset.searchDone = status;
  }
  return !config.component.dataset.searchDone ? false : (config.component.dataset.searchDone === 'true');
};

// ### INIT COMPONENT PARTS ###
const initLeafletMap = (url, parameters, zone) => {
  const map = L.map(zone);

  L.tileLayer(url, parameters).addTo(map);

  return { map };
};

const initScheduleDisplay = (component) => {
  const config = getConfig(component);
  config.component.querySelectorAll('[data-action="display-schedule"]').forEach((action) => {
    addListener('click', action, (event) => {
      const button = event.target;
      config.component.querySelectorAll(`[data-target="${button.dataset.scheduleTarget}"]`).forEach((target) => {
        target.classList.toggle('hidden');
      });
    });
  });
};

const initChangeButton = (component) => {
  const config = getConfig(component);

  config.component.querySelectorAll('[data-action="change-pickup-point"]').forEach((action) => {
    addListener('click', action, async () => {
      dispatchEvent('advanced-shipping-map:pickup-point:unselected', config);
      if (searchDone(component) === true) {
        activeResultMode(component);
        return;
      }
      activeSearchMode(component);
      if (config.searchFieldTarget.value !== '') {
        dispatchEvent('advanced-shipping-map:search:start', {
          component,
          searchLabel: config.searchFieldTarget.value,
          location: JSON.parse(config.locationFieldTarget.value),
        });
      }
    });
  });
};

const activeSearchMode = (component) => {
  const config = getConfig(component);
  config.searchZone.classList.remove('hidden');
  config.choiceZone.classList.add('hidden');
  // config.selectedZone.classList.add('hidden');
};

const activeResultMode = (component) => {
  const config = getConfig(component);
  config.searchZone.classList.remove('hidden');
  config.choiceZone.classList.remove('hidden');
  // config.selectedZone.classList.add('hidden');
};

const activeSelectedMode = (component) => {
  const config = getConfig(component);
  config.searchZone.classList.add('hidden');
  config.choiceZone.classList.add('hidden');
  config.selectedZone.classList.remove('hidden');
};

/**
 * Init buttons that should/could be reinit after some actions
 */
const initAction = (component) => {
  initScheduleDisplay(component);
  initChangeButton(component);
};

const init = async (component) => {
  // Asign an uniq ID to our element if not exists
  const id = component.id ? component.id : uniqId(component);

  component.querySelector('[data-component="address-search-autocomplete"]').dataset.identifier = id;

  // Component base config
  config[id] = {
    component,
    id,
    mapUrl: component.dataset.mapUrl,
    mapParameters: component.dataset.mapParameters,
    serviceUrl: component.dataset.serviceUrl,
    activeMarkerIcon: component.dataset.activeMarkerIcon,
    unactiveMarkerIcon: component.dataset.unactiveMarkerIcon,
    activeClass: component.dataset.unactiveMarkerIcon,
    shippingMethod: component.dataset.shippingMethod,

    searchFieldTarget: component.querySelector('input[data-target="search"]'),
    locationFieldTarget: component.querySelector('input[data-target="location"]'),
    searchActionTrigger: component.querySelector('[data-action="search-pickup-point"]'),
    selectedValueTarget: component.querySelector('[data-target="pickup-point-selected-value"]'),

    searchZone: component.querySelector('[data-target="search-zone"]'),
    listZone: component.querySelector('[data-target="list-zone"]'),
    loadingZones: component.querySelectorAll('[data-target="loading-zone"]'),
    choiceZone: component.querySelector('[data-target="choice-zone"]'),
    mapZone: component.querySelector('[data-target="map-zone"]'),
    selectedZone: component.querySelector('[data-target="selected-zone"]'),
    selectedAddressZone: component.querySelector('[data-target="selected-address-zone"]'),

    ...initLeafletMap(component.dataset.mapUrl, component.dataset.mapParameters, component.querySelector('[data-target="map-zone"]')),
  };

  // Manage enter button
  config[id].searchFieldTarget.addEventListener("keypress", function(event) {
    if (event.key === "Enter") {
      event.preventDefault();
      const targetButton = config[id].searchFieldTarget.parentElement.querySelector('[data-target="button"]');
      if (targetButton) {
        targetButton.click();
      }
    }
  });

  // Add relative potion on listZone to be sure that the scroll to pickup works
  config[id].listZone.style.position = 'relative';

  document.addEventListener(`address-autocomplete:${id}:list:select`, (e) => {
    dispatchEvent('advanced-shipping-map:search:start', {
      component: id,
      searchLabel: config[id].searchFieldTarget.value,
      location: e.detail.location,
    });
  });

  initAction(component.id);
};

// ### DECLARE LISTENERS ###
/**
 * Init all components
 */
document.addEventListener('advanced-shipping-map:init', () => {
  document.querySelectorAll('[data-component="pickup-point-map"]').forEach(async (component) => {
    await init(component);
  });
});

/**
 * Init listen for search event
 * To reload a map, you can dispatch an event:
 *  `document.dispatchEvent(new CustomEvent('advanced-shipping-map:search:start', {
 *        detail: {
 *          component: ID of the component,
 *          postcode: null // or a value to prefetch with a postcode
 *        }
 *    }));`
 */
document.addEventListener('advanced-shipping-map:search:start', async (event) => {
  const { component, searchLabel, location } = event.detail;
  await search(component, searchLabel, location);
});

/**
 * Init listen for search done event
 */
document.addEventListener('advanced-shipping-map:search:done', async (event) => {
  const { component } = event.detail;
  createPickupPoints(component);
});

/**
 * Init listen for a selected pickup-point
 */
document.addEventListener('advanced-shipping-map:pickup-point:selected', async (event) => {
  const { component, pickupPointModel, pickupPointInformation } = event.detail;
  const config = getConfig(component);
  config.selectedAddressZone.innerHTML = pickupPointInformation.innerHTML;
  activeSelectedMode(component);
  initAction(component);
  config.selectedValueTarget.value = JSON.stringify(pickupPointModel);
});

/**
 * Entry point
 */
document.addEventListener('DOMContentLoaded', () => {
  dispatchEvent('advanced-shipping-map:init');
});
