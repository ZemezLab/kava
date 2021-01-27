( function( $, elementor ) {
    var widgetTourwareTravelMap = function( $scope, $ ) {
        var $travelMap = $scope.find( '.tourware-travel-map' ),
            map_settings       = $travelMap.data('map_settings'),
            map_style           = $travelMap.data('map_style'),
            tourware_map_settings       = $travelMap.data('tourware_map_settings');

        if ( ! $travelMap.length ) {
            return;
        }

        window.lodash = _;
        var TyTo = (function (_, $) {
            var privates = arguments;
            var configs = tourware_map_settings || {};

            return {
                setConfig: function(config, value) {
                    if (_.isObject(config)) {
                        configs = _.merge(configs, config);
                    } else {
                        _.set(configs, config, value);
                    }
                },

                getConfig: function(config) {
                    return _.get(configs, config);
                },

                /**
                 * Register a new module.
                 *
                 * @param moduleName
                 * @param fn
                 */
                register: function (moduleName, fn) {
                    _.set(TyTo, moduleName, {});

                    fn.apply(_.get(TyTo, moduleName), privates);
                }
            };
        })(lodash, jQuery);

        TyTo.register('Travels.Map', function (_, $) {

            /**
             * Private variable to hold existing maps.
             */
            var _existingMaps = {};

            /**
             * Add a marker to the existing map
             * @param config
             * @param iconConfig
             */
            this.addMarker = function(config, iconConfig) {
                if (!_.isString(iconConfig)) {
                    iconConfig = _.merge({
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 8,
                        fillColor: TyTo.getConfig('primaryColor'),
                        fillOpacity: 1,
                        strokeColor: '',
                        strokeWeight: 0
                    }, iconConfig);
                }

                config = _.merge({
                    icon: iconConfig,
                    latlng: false,
                    map: false,
                    bounds: false,
                    title: undefined
                }, config);

                if (!config.latlng) {
                    return console.error('TyTo.Map.addMarker: latlng is missing.');
                }

                if (!config.map) {
                    return console.error('TyTo.Map.addMarker: map is missing.');
                }

                if (config.latlng) {
                    config.position = {
                        lat: parseFloat(config.latlng.split(',')[0]),
                        lng: parseFloat(config.latlng.split(',')[1])
                    };
                }

                var marker = new google.maps.Marker(config);

                if (config.bounds) {
                    config.bounds.extend(new google.maps.LatLng(marker.position.lat(), marker.position.lng()));
                }
            };

            /**
             * This shows the map with routing in the specified dom element.
             *
             * @param config.postId String
             * @param config.element DOMElement
             */
            this.showMapWithAutoRouting = function (config) {
                config = _.merge({
                    map: null,
                    postId: TyTo.getConfig('postId'),
                    ajaxurl: TyTo.getConfig('ajaxurl')
                }, config);

                if (!config.postId) {
                    return console.error('TyTo.Map.showMapWithAutoRouting: postId is missing.');
                }

                if (!config.map) {
                    return console.error('TyTo.Map.showMapWithAutoRouting: map missing.');
                }

                jQuery.post(config.ajaxurl, {
                    action: 'getMapRouting',
                    postId: config.postId,
                }, function(response) {

                    var bounds = new google.maps.LatLngBounds();
                    var zoom_map = false;
                    var currentWaypointNumber = 1;
                    var showDistances = TyTo.getConfig('showDistances');

                    // draw routes
                    _.each(_.get(response, 'routes'), function (waypoints, step) {
                        if (waypoints.length > 1) {
                            var directionsService = new google.maps.DirectionsService;
                            var directionsDisplay = new google.maps.DirectionsRenderer({
                                suppressMarkers: true,
                                polylineOptions: { strokeColor: TyTo.getConfig('primaryColor') },
                                preserveViewport: true
                            });

                            directionsDisplay.setMap(config.map);

                            var firstWaypoint = waypoints.shift();
                            var lastWaypoint = waypoints.pop();
                            var color = '#fff';

                            // first waypoint marker
                            var firstIcon = {};
                            if (firstWaypoint.airport) {
                                firstIcon = TyTo.getConfig('airportIconPath');
                                color = 'transparent';
                            }
                            TyTo.Travels.Map.addMarker({
                                map: config.map,
                                bounds: bounds,
                                latlng: firstWaypoint.location,
                                label: {
                                    color: color,
                                    fontSize: '11px',
                                    text: String(currentWaypointNumber)
                                }
                            }, firstIcon);
                            currentWaypointNumber++;

                            waypoints.forEach(function (waypoint) {
                                TyTo.Travels.Map.addMarker({
                                    map: config.map,
                                    bounds: bounds,
                                    latlng: waypoint.location,
                                    label: {
                                        color: '#fff',
                                        fontSize: '11px',
                                        text: String(currentWaypointNumber)
                                    }
                                });

                                currentWaypointNumber++;
                            });

                            // last waypoint marker
                            var lastIcon = {};
                            if (lastWaypoint.airport) {
                                lastIcon = TyTo.getConfig('airportIconPath');
                                color = 'transparent';
                            }
                            TyTo.Travels.Map.addMarker({
                                map: config.map,
                                bounds: bounds,
                                latlng: lastWaypoint.location,
                                label: {
                                    color: color,
                                    fontSize: '11px',
                                    text: String(currentWaypointNumber)
                                }
                            }, lastIcon);

                            var routingWaypoints = _.map(waypoints, function (waypoint) {
                                return _.omit(waypoint, 'itineraryItemId')
                            });

                            var pos = 0;
                            directionsService.route({
                                origin: firstWaypoint.location,
                                destination: lastWaypoint.location,
                                waypoints: routingWaypoints,
                                optimizeWaypoints: false,
                                provideRouteAlternatives: true,
                                travelMode: google.maps.TravelMode.DRIVING
                            }, function (response, status) {
                                if (status === 'OK') {
                                    directionsDisplay.setDirections(response, status);
                                    response.routes[0].legs.forEach(function (leg, position) {
                                        if (leg.distance.value > 0 && showDistances) {
                                            var item = jQuery('.timeline-item._pos'+ step + '-' +  pos);
                                            var distance = jQuery('<div class="distance _s'+step+'a"></div>');

                                            distance.append('Geschätzte Entfernung und Fahrtzeit: ' + leg.distance.text + ' &middot; ');
                                            distance.append(leg.duration.text.replace('Stunden', 'h').replace('Minuten', 'min.'));
                                            var from = 'Von Punkt '+pos;
                                            if (firstWaypoint.airport && pos === 0) from = 'Vom Flughafen';
                                            var to = pos+1; to = ' bis Punkt '+to;
                                            if (lastWaypoint.airport && pos === array.length - 1) to = ' bis zum Flughafen';
                                            distance.append('<div style="font-size: .7em;line-height: .6">' + from + to + '</div>');

                                            item.find('.timeline-item-title').append(distance);

                                            distances = item.find('.timeline-item-title').find('.distance');
                                            if (distances.length > 1) var sorted = distances.sort(doSort);
                                            item.find('.timeline-item-title').remove('.distance').append(sorted);
                                        }
                                        pos++;
                                    });
                                } else {
                                    var markers = [];

                                    markers.push({
                                        lat: parseFloat(firstWaypoint.location.split(',')[0]),
                                        lng: parseFloat(firstWaypoint.location.split(',')[1])
                                    });

                                    _.each(waypoints, function (waypoint) {
                                        markers.push({
                                            lat: parseFloat(waypoint.location.split(',')[0]),
                                            lng: parseFloat(waypoint.location.split(',')[1])
                                        });
                                    });

                                    markers.push({
                                        lat: parseFloat(lastWaypoint.location.split(',')[0]),
                                        lng: parseFloat(lastWaypoint.location.split(',')[1])
                                    });

                                    var path = new google.maps.Polyline({
                                        path: markers,
                                        geodesic: true,
                                        strokeColor: TyTo.getConfig('primaryColor'),
                                        strokeOpacity: 1.0,
                                        strokeWeight: 2
                                    });

                                    path.setMap(config.map);
                                }
                            });
                        } else {
                            waypoints.forEach(function (waypoint) {


                                var marker = new google.maps.Marker({
                                    position: {
                                        lat: parseFloat(waypoint.location.split(',')[0]),
                                        lng: parseFloat(waypoint.location.split(',')[1])
                                    },
                                    map: config.map,
                                    bounds: bounds
                                });
                                var latLng = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
                                config.map.setCenter(latLng);
                                var zoom = TyTo.getConfig('singleWaypointZoom');
                                config.map.setZoom(parseInt(zoom));

                                marker.setMap(config.map);
                            });
                            zoom_map = true;
                        }
                    });

                    // draw flights
                    _.each(_.get(response, 'flights'), function (waypoint, step) {
                        new google.maps.Polyline({
                            path: [
                                new google.maps.LatLng(parseFloat(waypoint.origin.split(',')[0]), parseFloat(waypoint.origin.split(',')[1])),
                                new google.maps.LatLng(parseFloat(waypoint.destination.split(',')[0]), parseFloat(waypoint.destination.split(',')[1]))
                            ],
                            strokeColor: TyTo.getConfig('primaryColor'),
                            map: config.map
                        });

                        if (showDistances) {
                            var rad = Math.PI / 180,
                                origin_lat = parseFloat(waypoint.origin.split(',')[0]),
                                origin_lng = parseFloat(waypoint.origin.split(',')[1]),
                                destintation_lat = parseFloat(waypoint.destination.split(',')[0]),
                                destintation_lng = parseFloat(waypoint.destination.split(',')[1]);

                            //Calculate distance from latitude and longitude
                            var theta = origin_lng - destintation_lng;
                            var d = Math.sin(origin_lat * rad) * Math.sin(destintation_lat * rad) + Math.cos(origin_lat * rad)
                                * Math.cos(destintation_lat * rad) * Math.cos(theta * rad);
                            var distance = Math.ceil(Math.acos(d) / rad * 60 *  1.853);
                            var dur = distance / 600; // 600km/h - average airplane speed

                            var duration = '';
                            if (parseInt(dur) > 0) {
                                duration += parseInt(dur) + (parseInt(dur) > 1 ? ' Stunden ' : ' Stunde ' );
                            }
                            if (dur%1 > 0) {
                                var m = parseInt((dur%1) * 60);
                                duration += parseInt(m) + (parseInt(m) > 1 ? ' Minuten' : ' Minute' );
                            }

                            var item = jQuery('.timeline-item._flight' + step);
                            var distance_el = jQuery('<div class="distance _s'+step+'f"></div>');

                            distance_el.append('Geschätzte Entfernung und Flugzeit: ' + distance + ' km &middot; ');
                            distance_el.append(duration);
                            item.find('.timeline-item-title').append(distance_el);

                            distances = item.find('.timeline-item-title').find('.distance');
                            if (distances.length > 1) var sorted = distances.sort(doSort);
                            item.find('.timeline-item-title').remove('.distance').append(sorted);
                        }
                    });

                    if (!zoom_map) {
                        config.map.fitBounds(bounds);
                        config.map.panToBounds(bounds);
                    }
                });

                function doSort(a, b) {
                    if (a.className < b.className) return -1;
                    if (a.className > b.className) return 1;

                    return 0;
                }

            };

            /**
             * Shows a map based on a remote KML file.
             *
             * @param config.map
             * @param config.kmlFile
             */
            this.showMapWithKml = function (config) {
                config = _.merge({
                    map: null,
                    kmlFile: null
                }, config);

                new google.maps.KmlLayer(config.kmlFile, {
                    suppressInfoWindows: true,
                    preserveViewport: false,
                    map: config.map
                });
            };

            /**
             * Creates a new google maps instance.
             *
             * @param element
             * @param id
             * @param config
             * @returns {google.maps.Map}
             */
            this.getGoogleMapInstance = function(element, id, config) {
                config = _.merge({
                    zoom: 8,
                    disableDefaultUI: true
                }, config);


                if (_existingMaps[id]) {
                    return _existingMaps[id];
                }

                _existingMaps[id] = new google.maps.Map(element, config);

                return _existingMaps[id];
            };
        });

        // var $map = $('#map');
        var showMapPreview = tourware_map_settings.showMapPreview;
        var kml = tourware_map_settings.kmlFile;
        var element = document.getElementById('tourware-travel-map');

        if (showMapPreview) {
            $('.show-map').click(function () {
                if (kml) {
                    new google.maps.KmlLayer(kml.url, {
                        suppressInfoWindows: true,
                        preserveViewport: false,
                        map: TyTo.Travels.Map.getGoogleMapInstance(element, 'map', {styles : map_style})
                    });
                } else {
                    TyTo.Travels.Map.showMapWithAutoRouting({
                        map: TyTo.Travels.Map.getGoogleMapInstance(element, 'map', {styles : map_style})
                    });
                }
            });
        } else {
            if (kml) {
                new google.maps.KmlLayer(kml.url, {
                    suppressInfoWindows: true,
                    preserveViewport: false,
                    map: TyTo.Travels.Map.getGoogleMapInstance(element, 'map', {styles : map_style})
                });
            } else {
                try {
                    var waypoints = tourware_map_settings.cachedWaypoints;
                    if (waypoints) {
                        waypoints = JSON.parse(waypoints);
                    }
                    TyTo.Travels.Map.showMapWithAutoRouting({
                        map: TyTo.Travels.Map.getGoogleMapInstance(element, 'map', {styles : map_style})
                    });
                } catch (e) {}
            }
        }

    }

    jQuery(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/tourware-travel-map.default', widgetTourwareTravelMap );
    });
}( jQuery, window.elementorFrontend ) );
