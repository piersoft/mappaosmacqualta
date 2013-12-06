<?php

$url = 'http://paolomainardi.com:3050/api/devices?type=geo';
$file = "mappa.json";
$src = fopen($url, 'r');
$dest = fopen($file, 'w');
//echo stream_copy_to_stream($src, $dest) . "";
//sleep(1);
//header("Location:http://www.apposta.biz/prove/mappacqualta.html");

?>

<!DOCTYPE html>
<html lang="it">
  <head>
  <title>Leaflet GeoJSON Acqualta.org</title>
  <link rel="stylesheet" href="http://necolas.github.io/normalize.css/2.1.3/normalize.css" />
  <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7/leaflet.css" />
  <script src="http://cdn.leafletjs.com/leaflet-0.7/leaflet.js"></script>
<script type="text/javascript">
function microAjax(B,A){this.bindFunction=function(E,D){return function(){return E.apply(D,[D])}};this.stateChange=function(D){if(this.request.readyState==4){this.callbackFunction(this.request.responseText)}};this.getRequest=function(){if(window.ActiveXObject){return new ActiveXObject("Microsoft.XMLHTTP")}else{if(window.XMLHttpRequest){return new XMLHttpRequest()}}return false};this.postBody=(arguments[2]||"");this.callbackFunction=A;this.url=B;this.request=this.getRequest();if(this.request){var C=this.request;C.onreadystatechange=this.bindFunction(this.stateChange,this);if(this.postBody!==""){C.open("POST",B,true);C.setRequestHeader("X-Requested-With","XMLHttpRequest"); C.setRequestHeader("Content-type","application/x-www-form-urlencoded");C.setRequestHeader("Connection","close")}else{C.open("GET",B,true)}C.send(this.postBody)}};
</script>
  <style>
  #mapdiv{
        position:fixed;
        top:0;
        right:0;
        left:0;
        bottom:0;
}
</style>
  </head>

<body>
  <div id="mapdiv"></div>

  <script type="text/javascript">
		var lat=45.4317,
        lon=12.3191,
        zoom=14;
        var osm = new L.TileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {maxZoom: 19, attribution: 'Map Data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'});
		var mapquest = new L.TileLayer('http://otile{s}.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png', {subdomains: '1234', maxZoom: 18, attribution: 'Map Data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'});        

        var map = new L.Map('mapdiv', {
                    editInOSMControl: true,
            editInOSMControlOptions: {
                position: "topright"
            },
            center: new L.LatLng(lat, lon),
            zoom: zoom,
            layers: [osm]
        });
        
        var baseMaps = {
    "Mapnik": osm,
    "Mapquest Open": mapquest        
        };
        L.control.layers(baseMaps).addTo(map);

      //  var ico=L.icon({iconUrl:'ico.png', iconSize:[20,20],iconAnchor:[10,0]});

        function loadLayer(url)
        {
                var myLayer = L.geoJson(url,{
                        onEachFeature:function onEachFeature(feature, layer) {
                                if (feature.properties && feature.properties.id) {
										var string='ID: '+feature.properties.id+"<br/>"+'Location: '+feature.properties.location;
										if (feature.properties.twitter_enabled && feature.properties.twitter_enabled == 1) string+="<br/><img src='https://f.cloud.github.com/assets/1304918/1693140/41adbcd2-5e89-11e3-85c0-278794202387.png'/>"
                                        layer.bindPopup(string);
                                }
                        },
                        pointToLayer: function (feature, latlng) {                
                               // var marker=L.marker(latlng, {icon:ico});
							   var marker=L.marker(latlng);
                                return marker;
                        }
                }).addTo(map);
        }

microAjax('mappa.json',function (res) { 
var feat=JSON.parse(res);
loadLayer(feat);
 } );
</script>
  
</body>
</html>


