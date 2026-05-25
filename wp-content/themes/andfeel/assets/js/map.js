/* ===== map.js — Googleマップ（モノクロ）初期化 ===== */
(function () {
  'use strict';

  var MONO_STYLE = [
    { elementType: 'geometry',           stylers: [{ color: '#e5e3e1' }] },
    { elementType: 'labels.text.fill',   stylers: [{ color: '#6b6b6b' }] },
    { elementType: 'labels.text.stroke', stylers: [{ color: '#f5f5f5' }] },
    { featureType: 'road',              elementType: 'geometry', stylers: [{ color: '#ffffff' }] },
    { featureType: 'road',              elementType: 'labels.text.fill', stylers: [{ color: '#9e9e9e' }] },
    { featureType: 'road.highway',      elementType: 'geometry', stylers: [{ color: '#dadada' }] },
    { featureType: 'water',             elementType: 'geometry', stylers: [{ color: '#c9c9c9' }] },
    { featureType: 'water',             elementType: 'labels.text.fill', stylers: [{ color: '#9e9e9e' }] },
    { featureType: 'poi',               elementType: 'geometry', stylers: [{ color: '#dcdcdc' }] },
    { featureType: 'poi',               elementType: 'labels.text.fill', stylers: [{ color: '#757575' }] },
    { featureType: 'poi.park',          elementType: 'geometry', stylers: [{ color: '#d5d5d5' }] },
    { featureType: 'transit',           elementType: 'geometry', stylers: [{ color: '#dcdcdc' }] }
  ];

  var LOCATIONS = {
    office: {
      address: '徳島県徳島市上八万町西山448',
      fallback: { lat: 34.0267, lng: 134.5350 },
      title: 'and feel. 自邸兼事務所'
    },
    studio: {
      address: '徳島県徳島市鷹匠町2丁目20',
      fallback: { lat: 34.0694, lng: 134.5513 },
      title: 'and feel. 徳島スタジオ'
    }
  };

  function createMap(el, position, loc) {
    var mapOptions = {
      center: position,
      zoom: 16,
      styles: MONO_STYLE,
      disableDefaultUI: true,
      zoomControl: true
    };
    if (google.maps.RenderingType && google.maps.RenderingType.RASTER) {
      mapOptions.renderingType = google.maps.RenderingType.RASTER;
    }
    var map = new google.maps.Map(el, mapOptions);

    var marker = new google.maps.Marker({
      position: position,
      map: map,
      title: loc.title
    });

    var lat = typeof position.lat === 'function' ? position.lat() : position.lat;
    var lng = typeof position.lng === 'function' ? position.lng() : position.lng;
    var mapsUrl = 'https://www.google.com/maps?q=' + lat + ',' + lng;
    marker.addListener('click', function () { window.open(mapsUrl, '_blank'); });
    map.addListener('click', function () { window.open(mapsUrl, '_blank'); });
    el.style.cursor = 'pointer';
  }

  window.initMap = function () {
    var geocoder = new google.maps.Geocoder();

    document.querySelectorAll('.js-map').forEach(function (el) {
      var key = el.dataset.location;
      var loc = LOCATIONS[key];
      if (!loc) return;

      geocoder.geocode({ address: loc.address }, function (results, status) {
        var position = (status === 'OK' && results[0])
          ? results[0].geometry.location
          : loc.fallback;
        createMap(el, position, loc);
      });
    });
  };
})();
