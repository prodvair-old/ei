var state = {
    ids: [],
    panel: null,
    limit: 10,
    offset: 0,
    stopLoad: false,
  },
  stateMarker = {
    geo: [],
    isActiveId: null,
    objectManager: null,
    limit: 100,
    offset: 0,
    stopLoad: false,
  },
  stateMap = null;
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
        width: "150px",
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
        `<div class="map__panel__filter-btn btn btn-primary btn-block mb-10"><i class="fa fa-filter"></i> Фильтры</div>
        <div class="map__panel">
          <div class="map__panel__content"></div>
          <div class="map__panel__close"></div>
          <div class="spinner-wrapper map__lot__preload"><div class="spinner"></div></div>
        </div>
        <div class="map__preload"><div class="spinner-wrapper"><div class="spinner"></div></div></div>`
      ).appendTo(parentDomContainer);

      this._$content = $(".map__panel__content");
      // При клике по крестику будем скрывать панель.
      $(".map__panel__close").on("click", this._onClose);
      $(".map__panel__filter-btn").on("click", this._onFilter);
      $(".map__panel__content").on("scroll", this._loadLots);
      $(".map__filter__close").on("click", this._onFilterClose);
      checkFilter();
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
      if (stateMarker.isActiveId) {
        var state = stateMarker.objectManager.getObjectState(
          stateMarker.isActiveId
        );
        if (!state.isClustered) {
          stateMarker.objectManager.objects.setObjectOptions(
            stateMarker.isActiveId,
            {
              iconLayout: createChipsLayout(function (zoom) {
                return Math.min(Math.pow(zoom, 1.4) + 15, 70);
              }, ""),
            }
          );
        } else {
          objectManager.clusters.setClusterOptions(objectId, {
            iconLayout: createChipsLayout(function (zoom) {
              // Минимальный размер метки будет 8px, а максимальный 200px.
              // Размер метки будет расти с квадратичной зависимостью от уровня зума.
              return Math.min(Math.pow(zoom, 1.6) + 20, 70);
            }, ""),
          });
        }
      }
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
    `<div 
      class="map__placemark ` +
      eventClass +
      `"
      style="background-color: 
        {% if (properties.propertyId == 1) %}#0fa958{% endif %}
        {% if (properties.propertyId == 2) %}#ff8577{% endif %}
        {% if (properties.propertyId == 3) %}#404040{% endif %}
        {% if (properties.propertyId == 4) %}#18a0fb{% endif %}">
      {{ properties.geoObjects.length }}
    </div>`,
    {
      build: function () {
        Chips.superclass.build.call(this);
        var map = stateMap;
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
  var map = new ymaps.Map(
      "map",
      {
        center: [55.755249, 37.617437],
        zoom: 12,
        controls: ["smallMapDefaultSet"],
      },
      {
        minZoom: 7,
      }
    ),
    objectManager = new ymaps.ObjectManager({
      // Чтобы метки начали кластеризоваться, выставляем опцию.
      clusterize: true,
      clusterHasBalloon: false,
      clusterDisableClickZoom: true,
      clusterIconLayout: createChipsLayout(function (zoom) {
        // Минимальный размер метки будет 8px, а максимальный 200px.
        // Размер метки будет расти с квадратичной зависимостью от уровня зума.
        return Math.min(Math.pow(zoom, 1.6) + 20, 70);
      }, ""),
    }),
    collection = new ymaps.GeoObjectCollection(null, {
      // Запретим появление балуна.
      hasBalloon: false,
      clusterize: true,
    }),
    panel = new ymaps.Panel(),
    closeButton = new ymaps.control.Button({
      data: {
        url: urlBack,
      },
      options: {
        layout: ymaps.templateLayoutFactory.createClass(
          `<a href="{{data.url}}" class="map__close"></a>`
        ),
      },
    });

  stateMap = map;
  state.panel = panel;
  map.controls
    .add(panel, {
      float: "left",
    })
    .add(closeButton, {
      float: "right",
    });

  collection.add(objectManager);
  map.geoObjects.add(collection);
  stateMarker.geo = map.getBounds();
  stateMarker.objectManager = objectManager;
  getPlacemark(true);

  // Подпишемся на событие клика по коллекцииx
  objectManager.objects.events
    .add("click", function (e) {
      // Получим ссылку на геообъект, по которому кликнул пользователь.
      var objectId = e.get("objectId"),
        object = objectManager.objects.getById(objectId),
        geo = object.geometry.coordinates,
        lotIds = [];
      // Зададим контент боковой панели.
      panel._onFilterClose();
      panel.clearContent();

      stateMarker.isActiveId = objectId;
      lotIds[0] = object.properties.lotId;

      $(".map__placemark").removeClass("active");
      objectManager.objects.setObjectOptions(objectId, {
        iconLayout: createChipsLayout(function (zoom) {
          // Минимальный размер метки будет 8px, а максимальный 200px.
          // Размер метки будет расти с квадратичной зависимостью от уровня зума.
          return Math.min(Math.pow(zoom, 1.4) + 15, 70);
        }, "active"),
      });

      state.ids = lotIds;
      state.offset = 0;
      state.stopLoad = false;

      getLots();
      // Переместим центр карты по координатам метки с учётом заданных отступов.
      map.panTo([Number(geo[0]), Number(geo[1])], { useMapMargin: true });
    })
    .add(["mouseenter", "mouseleave"], function (e) {
      var objectId = e.get("objectId");
      if (stateMarker.isActiveId !== objectId) {
        if (e.get("type") == "mouseenter") {
          // Метод setObjectOptions позволяет задавать опции объекта "на лету".
          objectManager.objects.setObjectOptions(objectId, {
            iconLayout: createChipsLayout(function (zoom) {
              // Минимальный размер метки будет 8px, а максимальный 200px.
              // Размер метки будет расти с квадратичной зависимостью от уровня зума.
              return Math.min(Math.pow(zoom, 1.4) + 15, 70);
            }, "hover"),
          });
        } else {
          objectManager.objects.setObjectOptions(objectId, {
            iconLayout: createChipsLayout(function (zoom) {
              // Минимальный размер метки будет 8px, а максимальный 200px.
              // Размер метки будет расти с квадратичной зависимостью от уровня зума.
              return Math.min(Math.pow(zoom, 1.4) + 15, 70);
            }, ""),
          });
        }
      }
    });

  objectManager.clusters.events
    .add("click", function (e) {
      // Получим ссылку на геообъект, по которому кликнул пользователь.
      var objectId = e.get("objectId"),
        cluster = objectManager.clusters.getById(objectId),
        geo = cluster.geometry.coordinates,
        lotIds = [];

      // Зададим контент боковой панели.
      panel._onFilterClose();
      panel.clearContent();
      stateMarker.isActiveId = objectId;

      cluster.properties.geoObjects.map(function (item, index) {
        lotIds[index] = item.properties.lotId;
      });
      $(".map__placemark").removeClass("active");

      objectManager.clusters.setClusterOptions(objectId, {
        iconLayout: createChipsLayout(function (zoom) {
          // Минимальный размер метки будет 8px, а максимальный 200px.
          // Размер метки будет расти с квадратичной зависимостью от уровня зума.
          return Math.min(Math.pow(zoom, 1.6) + 20, 70);
        }, "active"),
      });

      state.ids = lotIds;
      state.offset = 0;
      state.stopLoad = false;

      getLots();
      // Переместим центр карты по координатам метки с учётом заданных отступов.
      map.panTo([Number(geo[0]), Number(geo[1])], { useMapMargin: true });
    })
    .add(["mouseenter", "mouseleave"], function (e) {
      var objectId = e.get("objectId");
      if (stateMarker.isActiveId !== objectId) {
        if (e.get("type") == "mouseenter") {
          objectManager.clusters.setClusterOptions(objectId, {
            iconLayout: createChipsLayout(function (zoom) {
              // Минимальный размер метки будет 8px, а максимальный 200px.
              // Размер метки будет расти с квадратичной зависимостью от уровня зума.
              return Math.min(Math.pow(zoom, 1.6) + 20, 70);
            }, "hover"),
          });
        } else {
          objectManager.clusters.setClusterOptions(objectId, {
            iconLayout: createChipsLayout(function (zoom) {
              // Минимальный размер метки будет 8px, а максимальный 200px.
              // Размер метки будет расти с квадратичной зависимостью от уровня зума.
              return Math.min(Math.pow(zoom, 1.6) + 20, 70);
            }, ""),
          });
        }
      }
    });

  map.events.add("boundschange", function (e) {
    if (
      e.get("newZoom") !== e.get("oldZoom") &&
      e.get("newZoom") < e.get("oldZoom")
    ) {
      if (e.get("newZoom") >= 7) {
        stateMarker.offset = 0;
        stateMarker.stopLoad = true;

        stateMarker.geo = e.get("newBounds");
        getPlacemark(true);
      } else {
        objectManager.removeAll();
      }
    } else if (
      e.get("newBounds")[0][0] !== e.get("oldBounds")[0][0] ||
      e.get("newBounds")[0][1] !== e.get("oldBounds")[0][1] ||
      e.get("newBounds")[1][0] !== e.get("oldBounds")[1][0] ||
      e.get("newBounds")[1][1] !== e.get("oldBounds")[1][1]
    ) {
      stateMarker.offset = 0;
      stateMarker.stopLoad = true;

      stateMarker.geo = e.get("newBounds");
      getPlacemark(true);
    }
  });

  $("#search-map-lot-form").on("submit", function (e) {
    e.preventDefault();
    var newUrl = window.location.pathname + "?" + $(this).serialize();
    checkFilter();
    objectManager.removeAll();
    history.pushState("", "", newUrl);
    stateMarker.offset = 0;
    stateMarker.stopLoad = true;
    panel._onFilterClose();
    panel._onClose();
    getPlacemark(true);
  });

  map.events.add("actionbegin", function (e) {
    stateMarker.stopLoad = true;
    $(".map__preload").hide();
  });
});

function checkFilter() {
  var btn = $(".map__panel__filter-btn");
  btn.removeClass("active");
  $("#search-map-lot-form")
    .serializeArray()
    .map(function (item, index) {
      if (item.name !== "_csrf-frontend") {
        if (item.value !== "" && item.value !== "0") {
          if (!btn.hasClass("active")) {
            btn.addClass("active");
          }
        }
      }
    });
}

function getPlacemark(firstLoad) {
  if (!firstLoad) {
    stateMarker.stopLoad = false;
  }
  $(".map__preload").show();

  $.ajax({
    url: "/map-ajax",
    type: "POST",
    cache: true,
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
          var eventClass = "";

          if (stateMarker.isActiveId == item.id) {
            eventClass = "active";
          }

          stateMarker.objectManager.add({
            type: "Feature",
            id: item.id,
            geometry: {
              type: "Point",
              coordinates: [item.geo_lat, item.geo_lon],
            },
            properties: {
              hintContent: item.address,
              propertyId: item.property,
              lotId: item.parent_id,
            },
            options: {
              iconLayout: createChipsLayout(function (zoom) {
                // Минимальный размер метки будет 8px, а максимальный 200px.
                // Размер метки будет расти с квадратичной зависимостью от уровня зума.
                return Math.min(Math.pow(zoom, 1.4) + 15, 70);
              }, eventClass),
            },
          });
        });

        stateMarker.offset = stateMarker.offset + stateMarker.limit;

        if (!stateMarker.stopLoad || firstLoad) {
          getPlacemark(false);
        }
      } else {
        $(".map__preload").hide();
        stateMarker.stopLoad = true;
        if (firstLoad) {
          toastr.warning("Лотов в этой области не найдено");
        }
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
