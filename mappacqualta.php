<?php

$url = 'http://api.acqualta.org/api/devices?type=geo';
$file = "mappa.json";
$src = fopen($url, 'r');
$dest = fopen($file, 'w');
stream_copy_to_stream($src, $dest);
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
        <link rel="stylesheet" href="MarkerCluster.css" />
        <link rel="stylesheet" href="MarkerCluster.Default.css" />
  <script src="http://cdn.leafletjs.com/leaflet-0.7/leaflet.js"></script>
   <script src="leaflet.markercluster.js"></script>
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
#infodiv{
        position:fixed;
        left:30px;
        bottom:50px;
        z-index:9999;
        border-radius: 5px; 
        -moz-border-radius: 5px; 
        -webkit-border-radius: 5px; 
        border: 2px solid #808080;
        background-color:#fff;
        padding:5px;
        box-shadow: 0 3px 14px rgba(0,0,0,0.4)
}
</style>
  </head>

<body>
  <div id="mapdiv"></div>
  <div id="infodiv" style="leaflet-popup-content-wrapper">
  <h1>Acqua alta a Venezia</h1>
  <p>Mappa dei sensori con le ultime misurazioni del progetto <a href="http://www.acqualta.org">AcquAlta</a></p>
  </div>

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

       var ico=L.icon({iconUrl:'ico.png', iconSize:[20,20],iconAnchor:[10,0]});
       var markers = L.markerClusterGroup({spiderfyOnMaxZoom: true, showCoverageOnHover: false});
       
        function loadLayer(url)
        {
                var myLayer = L.geoJson(url,{
                        onEachFeature:function onEachFeature(feature, layer) {
                                if (feature.properties && feature.properties.id) {
                                }
                        },
                        pointToLayer: function (feature, latlng) {                
                        var marker = new L.Marker(latlng, { icon: ico });
                        markers[feature.properties.id] = marker;
                        return marker;
                        }
                }).addTo(map);
                markers.addLayer(myLayer);
        map.addLayer(markers);
markers.on('click', showMarker);
        }

microAjax('mappa.json',function (res) { 
var feat=JSON.parse(res);
loadLayer(feat);
 } );
 
 function showMarker(marker) {
var jsonref=marker.layer.feature;


microAjax('http://api.acqualta.org/api/data/'+jsonref.properties.id+'?limit=1',function (res) { 
var feat=JSON.parse(res);
if(feat['status']=='success')
{
    var last=feat['data'][0];
    var text="Location: <b>"+jsonref.properties.location+"</b><br/>";
    var date=new Date(last['date_sent']);
    var d = date.getDate();
    var m = date.getMonth() + 1;
    var y = date.getFullYear();
    var formatdate=date.getHours()+':'+date.getMinutes()+':'+date.getSeconds()+' '+(d <= 9 ? '0' + d : d)+'/'+(m<=9 ? '0' + m : m)+'/'+y;
    text+="Ultima ricezione: <b>"+formatdate+"</b><br/>";
    text+="Livello: <b>"+last['level']+" cm</b><br/>";
    text+="Temperatura: <b>"+last['temperature']+" &deg;C</b><br/>";
    if(last['twitter_enabled']==1)
    {
        text+="<img src='twitter.png' style='display:inline-block'/>";
        if(last['tweet']==1)text+="Ha twittato l'ultima misura"+"<br/>"; 
        else text+="Non ha twittato l'ultima misura"+"<br/>"; 
    }
    marker.layer.bindPopup(text);
}
else
console.log(feat);
 } );

}
</script>
  
</body>
</html>
