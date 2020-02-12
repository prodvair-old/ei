    var REGIONS_DATA = {
        region: {
            title: 'Регион',
            items: [{
                id: '001',
                title: 'Страны мира'
            }, {
                id: 'BY',
                title: 'Беларусь'
            }, {
                id: 'KZ',
                title: 'Казахстан'
            }, {
                id: 'RU',
                title: 'Россия'
            }, {
                id: 'TR',
                title: 'Турция'
            }, {
                id: 'UA',
                title: 'Украина'
            }]
        }
    },
    // Шаблон html-содержимого макета.
    optionsTemplate = [
        ''
    ].join('');
    
    
    ymaps.ready(async () => {
    
        let map = new ymaps.Map('map', {
            center: [55.774995968061866, 37.627800587707505],
            zoom: 15,
            controls: ['zoomControl'],
            // type: null,
            restrictMapArea: [[10, 10], [85,-160]]
        }, {
            searchControlProvider: false,
            typeSelectorSize: 'small',
            minZoom: 14,
            
        })
    
    
    
        /**
         * Создадим кластеризатор, вызвав функцию-конструктор.
         * Список всех опций доступен в документации.
         * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/Clusterer.xml#constructor-summary
         */
            
        let clusterer = new ymaps.Clusterer({
            /**
             * Через кластеризатор можно указать только стили кластеров,
             * стили для меток нужно назначать каждой метке отдельно.
             * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/option.presetStorage.xml
             */
            preset: 'islands#invertedVioletClusterIcons',
            /**
             * Ставим true, если хотим кластеризовать только точки с одинаковыми координатами.
             */
            groupByCoordinates: false,
            /**
             * Опции кластеров указываем в кластеризаторе с префиксом "cluster".
             * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/ClusterPlacemark.xml
             */
            clusterDisableClickZoom: true,
            clusterHideIconOnBalloonOpen: false,
            geoObjectHideIconOnBalloonOpen: false
        })
    
    
        /**
         * Функция возвращает объект, содержащий данные метки.
         * Поле данных clusterCaption будет отображено в списке геообъектов в балуне кластера.
         * Поле balloonContentBody - источник данных для контента балуна.
         * Оба поля поддерживают HTML-разметку.
         * Список полей данных, которые используют стандартные макеты содержимого иконки метки
         * и балуна геообъектов, можно посмотреть в документации.
         * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/GeoObject.xml
         */
        let getPointData = function (item) {
            console.log(item)
            const {id, title, price, address, link } = item
            return {
                balloonContentHeader: `<strong>${title}</strong>`,
                balloonContentBody: `
                <ul>
                    <li>Адрес: ${address}</li>
                    <li>Цена: ${price}</li>
                </ul>
                <hr>
                <a href="${link}" class="btn btn-primary">Смотреть</a>
                `
                // balloonContentFooter: '<font size=1>Информация предоставлена: </font> балуном <strong>метки ' + index + '</strong>',
                // clusterCaption: 'метка <strong>' + index + '</strong>'
            };
        }
    
    
        /**
         * Функция возвращает объект, содержащий опции метки.
         * Все опции, которые поддерживают геообъекты, можно посмотреть в документации.
         * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/GeoObject.xml
         */
        let getPointOptions = function () {
            return {
                preset: 'islands#redIcon'
            };
        }
    
        const get_lots = async (square) => {
           
    


            const url = `/map?northWest[lat]=${square.bottom}&northWest[lng]=${square.left}&southEast[lat]=${square.top}&southEast[lng]=${square.right}`
    
            // const urlTest = [
                
            //     '/map?northWest[lat]=55.9385544&northWest[lng]=37.1422992&southEast[lat]=55.5636891&southEast[lng]=38.0054127'
            // ]
            
            return new Promise(async (resolve, reject) => {
            

                try {
                const response = await fetch( url
                    ,{ 
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Access-Control-Allow-Credentials': '*'
                    },
                })
                const lots = await response.json()
                resolve(lots)
                } catch(e) {
                    console.log(e)
                reject(e)
                }
            })
        }
    
        const getBounds = () => {
            const bounds = map.getBounds()
            return {
                bottom: bounds[0][0],
                left:   bounds[0][1],
                top:    bounds[1][0],
                right:  bounds[1][1],
            }
        }

        map.panes.get('ground').getElement().style.filter = 'grayscale(100%)';
    
        const points  = await get_lots(getBounds());

        console.log(points)
    
        const geoObjects = [];
    
        /**
         * Данные передаются вторым параметром в конструктор метки, опции - третьим.
         * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/Placemark.xml#constructor-summary
         */
        for(var i = 0, len = points.length; i < len; i++) {
            geoObjects[i] = new ymaps.Placemark(
                [points[i].position.lat, points[i].position.lng], 
                getPointData(points[i]), 
                getPointOptions()
            );
        }
        
    
        /**
         * Можно менять опции кластеризатора после создания.
         */
        clusterer.options.set({
            gridSize: 80,
            clusterDisableClickZoom: true
        });
    
        /**
         * В кластеризатор можно добавить javascript-массив меток (не геоколлекцию) или одну метку.
         * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/Clusterer.xml#add
         */
    
        clusterer.add(geoObjects);
        map.geoObjects.add(clusterer);
       
    
    
    
        
    
        
    
        /**
         * Спозиционируем карту так, чтобы на ней были видны все объекты.
         */
    
    //   map.setBounds(clusterer.getBounds(), {
    //       checkZoomRange: true
    //   });
    });
    