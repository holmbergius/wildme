<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
<meta charset="utf-8">
<title>WildMe</title>
<style>
html, body {
  height: 100%;
  margin: 0;
  padding: 0;
}

#map_canvas {
  height: 100%;
}

@media print {
  html, body {
    height: auto;
  }

  #map_canvas {
    height: 250px;
  }
}
</style>
<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script src="js/jquery.js"></script>
<script>
      var geocoder;
      var map;
      function initialize() {
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(<?php echo $_REQUEST['lat'];?>, <?php echo $_REQUEST['long'];?>);
        var mapOptions = {
          zoom: 4,
          center: latlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
		var marker = new google.maps.Marker({
                map: map,
				icon: 'images/map-pin.png',
         		title: '<?php echo $_REQUEST['title'];?>',
                position: latlng
            });
		
      }
      
    </script>
</head>
<body onLoad="initialize()">
<div id="map_canvas" style="height:250px;top:0px; width:454px;"></div>
<!--<a href="#" onClick="codeAddress('Apple Store Otay Ranch 2015 Birch Road Chula Vista, CA 91915');">Add New Store</a>-->
<!--<div class="map-tool-tip" style="left:180px; top:311px; display:none;position:absolute;" id="locationDiv" onClick="$('#locationDiv').hide();">
  <p><h1 id="address">Testing</h1></p>
  <div class="clear"></div>
  <div class="tip"><img src="images/tool-tip.png" /></div>
</div>-->
</body>
</html>