var state = {
  ids: [],
  panel: null,
  limit: 10,
  offset: 0,
  stopLoad: false,
};
var stateMarker = {
  geo: [],
  clusterer: null,
  limit: 100,
  offset: 0,
  stopLoad: false,
};
// Пример реализации боковой панели на основе наследования от collection.Item.
// Боковая панель отображает информацию, которую мы ей передали.
ymaps.modules.define("Panel", ["util.augment", "collection.Item"], function (
  provide,
  augment,
  item
) {
  // Создаем собственный класс.
  var Panel = function (options) {
    Panel.superclass.constructor.call(this, options);
  };

  // И наследуем его от collection.Item.
  augment(Panel, item, {
    onAddToMap: function (map) {
      Panel.superclass.onAddToMap.call(this, map);
      this.getParent()
        .getChildElement(this)
        .then(this._onGetChildElement, this);
      // Добавим отступы на карту.
      // Отступы могут учитываться при установке текущей видимой области карты,
      // чтобы добиться наилучшего отображения данных на карте.
      map.margin.addArea({
        top: 0,
        left: 0,
        width: "250px",
        height: "100%",
      });
    },

    onRemoveFromMap: function (oldMap) {
      if (this._$control) {
        this._$control.remove();
      }
      Panel.superclass.onRemoveFromMap.call(this, oldMap);
    },

    _onGetChildElement: function (parentDomContainer) {
      // Создаем HTML-элемент с текстом.
      // По-умолчанию HTML-элемент скрыт.
      this._$control = $(
        `<div class="map__panel__filter-btn btn btn-primary btn-block mb-10">Фильтр</div>
        <div class="map__panel">
          <div class="map__panel__content"></div>
          <div class="map__panel__close"></div>
          <div class="spinner-wrapper map__lot__preload"><div class="spinner"></div>Загрузка лотов...</div>
        </div>
        <div class="map__preload"><div class="spinner-wrapper"><div class="spinner"></div></div></div>`
      ).appendTo(parentDomContainer);

      this._$content = $(".map__panel__content");
      // При клике по крестику будем скрывать панель.
      $(".map__panel__close").on("click", this._onClose);
      $(".map__panel__filter-btn").on("click", this._onFilter);
      $(".map__filter__close").on("click", this._onFilterClose);
      $(".map__panel__content").on("scroll", this._loadLots);
    },
    clearContent: function () {
      this._$content.html("");
    },
    _loadLots: function (e) {
      var height = -100;
      $.each(e.target.children, function (index, item) {
        if (item.offsetHeight == 1) {
          height = height + 20;
        }
        height = height + item.offsetHeight;
      });
      var scrollTop = e.target.scrollTop + e.target.offsetHeight;
      if (scrollTop >= height && !state.stopLoad) {
        state.offset = state.offset + state.limit;
        getLots();
      }
    },
    _onClose: function () {
      $(".map__placemark").removeClass("active");
      $(".map__panel").removeClass("active");
      setTimeout(function () {
        $(".map__panel__content").html("");
      }, 500);
    },
    _onFilter: function () {
      $(".map__filter").toggleClass("active");
    },
    _onFilterClose: function () {
      $(".map__filter").removeClass("active");
    },
    // Метод задания контента панели.
    setContent: function (content) {
      // При задании контента будем показывать панель.
      this._$content.append(content);
      this._$control.addClass("active");
    },
  });

  provide(Panel);
});

var createChipsLayout = function (calculateSize, eventClass) {
  // Создадим макет метки.
  var Chips = ymaps.templateLayoutFactory.createClass(
    '<div class="map__placemark {% if state.hover %}hover{% endif %} {% if properties.isActive %}active{% endif %}">{{ properties.geoObjects.length }}</div>',
    {
      build: function () {
        Chips.superclass.build.call(this);
        var map = this.getData().geoObject.getMap();
        if (!this.inited) {
          this.inited = true;
          // Получим текущий уровень зума.
          var zoom = map.getZoom();
          // Подпишемся на событие изменения области просмотра карты.
          map.events.add(
            "boundschange",
            function () {
              // Запустим перестраивание макета при изменении уровня зума.
              var currentZoom = map.getZoom();
              if (currentZoom != zoom) {
                zoom = currentZoom;
                this.rebuild();
              }
            },
            this
          );
        }
        var options = this.getData().options,
          // Получим размер метки в зависимости от уровня зума.
          size = calculateSize(map.getZoom()),
          element = this.getParentElement().getElementsByClassName(
            "map__placemark"
          )[0],
          // По умолчанию при задании своего HTML макета фигура активной области не задается,
          // и её нужно задать самостоятельно.
          // Создадим фигуру активной области "Круг".
          circleShape = {
            type: "Circle",
            coordinates: [0, 0],
            radius: size / 2,
          };
        // Зададим высоту и ширину метки.
        element.style.width = element.style.height = size + "px";
        // Зададим смещение.
        element.style.marginLeft = element.style.marginTop = -size / 2 + "px";
        // Зададим фигуру активной области.
        options.set("shape", circleShape);
      },
    }
  );

  return Chips;
};

ymaps.ready(["Panel"]).then(function () {
  var geolocation = ymaps.geolocation;
  var map = new ymaps.Map("map", {
      center: [55.755249, 37.617437],
      zoom: 12,
      controls: ["smallMapDefaultSet"],
    }),
    clusterer = new ymaps.Clusterer({
      clusterDisableClickZoom: true,
      // Зададим макет метки кластера.
      clusterIconLayout: createChipsLayout(function (zoom) {
        // Минимальный размер метки будет 8px, а максимальный 200px.
        // Размер метки будет расти с квадратичной зависимостью от уровня зума.
        return Math.min(Math.pow(zoom, 1.6) + 2, 100);
      }, ""),
      // Чтобы метка была кликабельной, переопределим ее активную область.
      hasBalloon: false,
      clusterIconShape: {
        type: "Rectangle",
        coordinates: [
          [20, 20],
          [60, 60],
        ],
      },
    });

  var panel = new ymaps.Panel();
  state.panel = panel;

  map.controls.add(panel, {
    float: "left",
  });

  var collection = new ymaps.GeoObjectCollection(null, {
    // Запретим появление балуна.
    hasBalloon: false,
    clusterize: true,
  });

  collection.add(clusterer);
  map.geoObjects.add(collection);
  // Подпишемся на событие клика по коллекцииx

  collection.events.add("click", function (e) {
    // Получим ссылку на геообъект, по которому кликнул пользователь.
    var target = e.get("target"),
      geoObjects = target.properties.get("geoObjects"),
      geo = target.geometry.getCoordinates();
    // Зададим контент боковой панели.
    panel._onFilterClose();
    panel.clearContent();
    var lotIds = [];

    if (geoObjects) {
      geoObjects.map(function (item, index) {
        lotIds[index] = item.properties.get("balloonContent");
      });
    } else {
      lotIds[0] = target.properties.get("balloonContent");
    }

    $(".map__placemark").removeClass("active");
    target.properties.set("isActive", true);

    state.ids = lotIds;
    state.offset = 0;
    state.stopLoad = false;

    getLots();
    // Переместим центр карты по координатам метки с учётом заданных отступов.
    // map.panTo([Number(geo[0]), Number(geo[1])], { useMapMargin: true });
  });

  map.events.add("boundschange", function (e) {
    if (
      e.get("newZoom") !== e.get("oldZoom") &&
      e.get("newZoom") < e.get("oldZoom")
    ) {
      if (e.get("newZoom") >= 7) {
        clusterer.removeAll();
        stateMarker.offset = 0;
        stateMarker.stopLoad = true;

        stateMarker.geo = e.get("newBounds");
        getPlacemark(true);
      } else {
        clusterer.removeAll();
      }
    } else if (
      e.get("newBounds")[0][0] !== e.get("oldBounds")[0][0] ||
      e.get("newBounds")[0][1] !== e.get("oldBounds")[0][1] ||
      e.get("newBounds")[1][0] !== e.get("oldBounds")[1][0] ||
      e.get("newBounds")[1][1] !== e.get("oldBounds")[1][1]
    ) {
      clusterer.removeAll();
      stateMarker.offset = 0;
      stateMarker.stopLoad = true;

      stateMarker.geo = e.get("newBounds");
      getPlacemark(true);
    }
  });

  stateMarker.geo = map.getBounds();
  stateMarker.clusterer = clusterer;

  getPlacemark(true);

  $("#search-map-lot-form").on("submit", function (e) {
    e.preventDefault();
    var newUrl = window.location.pathname + "?" + $(this).serialize();
    history.pushState("", "", newUrl);
    stateMarker.offset = 0;
    stateMarker.stopLoad = true;
    clusterer.removeAll();
    panel._onFilterClose();
    panel._onClose();
    getPlacemark(true);
  });

  map.events.add("actionbegin", function (e) {
    stateMarker.stopLoad = true;
  });
});

function getPlacemark(firstLoad) {
  if (!firstLoad) {
    stateMarker.stopLoad = false;
  }
  $(".map__preload").show();

  $.ajax({
    url: "/map-ajax",
    type: "POST",
    data: {
      north_west_lat: stateMarker.geo[0][0],
      north_west_lon: stateMarker.geo[0][1],
      south_east_lat: stateMarker.geo[1][0],
      south_east_lon: stateMarker.geo[1][1],
      limit: stateMarker.limit,
      offset: stateMarker.offset,
      filter: $("#search-map-lot-form").serialize(),
    },
    success: function (items) {
      if (items[0]) {
        // Добавим геообъекты в коллекцию.
        items.map(function (item) {
          var point = new ymaps.Placemark(
            [item.geo_lat, item.geo_lon],
            {
              balloonContent: item.parent_id,
              hintContent: item.address,
              isActive: false,
            },
            {
              iconLayout: createChipsLayout(function (zoom) {
                // Минимальный размер метки будет 8px, а максимальный 200px.
                // Размер метки будет расти с квадратичной зависимостью от уровня зума.
                return Math.min(Math.pow(zoom, 1.4) + 2, 100);
              }, ""),
            }
          );

          stateMarker.clusterer.add(point);
        });

        stateMarker.offset = stateMarker.offset + stateMarker.limit;

        if (!stateMarker.stopLoad || firstLoad) {
          getPlacemark(false);
        }
      } else {
        $(".map__preload").hide();
      }
    },
  }).fail(function (qwe) {
    stateMarker.stopLoad = true;
    toastr.error("Ошибка загрузки данных");
  });
}

function getLots() {
  state.stopLoad = true;
  $(".map__lot__preload").show();

  $.ajax({
    url: "/map-lot-ajax",
    type: "POST",
    data: {
      ids: state.ids,
      limit: state.limit,
      offset: state.offset,
    },
    success: function (res) {
      state.panel.setContent(res);
      $(".map__lot__preload").hide();
      if (res) {
        state.stopLoad = false;
      }
    },
  }).fail(function () {
    toastr.error("Ошибка загрузки данных");
  });
}
