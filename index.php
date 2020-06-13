<!DOCTYPE html >
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Google Maps 2</title>
    <style>
      /*Definindo a altura do mapa, conforme os elementos que contém no mapa
      */
      #map {
        height: 100%;
      }
      /* Mostrando a página em tela cheia */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>

  <body>
    <div id="map"></div>

    <script>
      var customLabel = {
        restaurant: {
          label: 'R'
        },
        bar: {
          label: 'B'
        }
      };

        function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: new google.maps.LatLng(-2.530256, -44.298648),
          zoom: 16
        });
        var infoWindow = new google.maps.InfoWindow;

          //chamar a localizaçao atual do usuario
          if(navigator.geolocation){
              navigator.geolocation.getCurrentPosition(function(position){
                var pos = {
                  lat: position.coords.latitude,
                  lng: position.coords.longitude
                };
                infoWindow.setPosition(pos);
                infoWindow.setContent('Location found.');
                infoWindow.open(map);
                map.setCenter(pos);
              }, function(){
                handleLocationError(true, infoWindow, map.getCenter());
              });
          } else {
            handleLocationError(false, infoWindow, map.getCenter());
          }

          // inserindo a conexão pra pegar os dados
          downloadUrl('resultado.php', function(data) { 
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName('marker');
            Array.prototype.forEach.call(markers, function(markerElem) {
              var pontodeinteresse = markerElem.getAttribute('pontodeinteresse');
              var pontodereferencia = markerElem.getAttribute('pontodereferencia');
              var type = markerElem.getAttribute('type');
              var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('lat')),
                  parseFloat(markerElem.getAttribute('lng')));

              // Informações do ponto de interesse
              var infowincontent = document.createElement('div');
              var strong = document.createElement('strong');
              strong.textContent = pontodeinteresse
              infowincontent.appendChild(strong);
              infowincontent.appendChild(document.createElement('br'));

              var text = document.createElement('text');
              text.textContent = pontodereferencia
              infowincontent.appendChild(text);
              infowincontent.appendChild(document.createElement('br'));

              var strong = document.createElement('strong');
              strong.textContent = type
              infowincontent.appendChild(strong);


              var iconBase = 'http://localhost/CONEX%C3%83O/';
              var icon = customLabel[type] || {};
              var marker = new google.maps.Marker({
                map: map,
                position: point,
                icon: iconBase + 'icone_cadeirante.png'
              });



              marker.addListener('click', function() {
                infoWindow.setContent(infowincontent);
                infoWindow.open(map, marker);
              });
            });
          });
        }

        //funçao referente a chamada da localizaçao
        function handleLocationError(browserHasGeolocation, infoWindow, pos){
          infoWindow.setPosition(pos);
          infoWindow.setContent(browserHasGeolocation ? 
                                'Error: the Geolocation service failed.' :
                                'Error: You browser doesn\'t support geolocation.');
          infoWindow.open(map);
        }

      function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }

      function doNothing() {}
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCY4Ez4Bj_5rzzZ5zHIBw3luonP5WdgZ7c&callback=initMap">
    </script>
  </body>
</html>