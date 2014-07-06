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
  background:#FFF !important;
}

#map_canvas {
  height: 100%;
}

@media print {
  html, body {
    height: auto;
  }

  #map_canvas {
    height: 510px;
  }
}
</style>
<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script src="js/jquery.js"></script>
<script>
      var geocoder;
      var map;
	  //var profileMarkers= new Array();
      function initialize() {
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(0, 0);
        var mapOptions = {
          zoom: 8,
          center: latlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
		
		//$("#MapLoader").hide();
      }

      function codeAddress(encounter,i) {
       		var latlng = new google.maps.LatLng(encounter.latitude, encounter.longitude);
            var marker = new google.maps.Marker({
                map: map,
				icon: 'images/map-pin.png',
         		title: encounter.verbatim_locality,
                position: latlng
            });
			
			var contentString = '<div style="width:400px; height:100px;"><div class="pic"><div class="align-div '+encounter.category_color+'"> <img src="'+encounter.category_icon+'"> </div></div><div class="locations-details"><h4>'+encounter.verbatim_locality+'</h4>';
			if (encounter.photographer_name!='')
			contentString +='<p><span>Photographer:</span> '+encounter.photographer_name+'</p>';
			if (encounter.recorded_by!='')
			contentString +='<p><span>Recorded By:</span> '+encounter.recorded_by+'</p>';
			
			contentString +='</div></div>';
			
			var infowindow = new google.maps.InfoWindow({
				content: contentString
			});
			
			//profileMarkers[encounter.id] = encounter;
			if (i==0)
			{
				map.setZoom(6);
			    map.setCenter(latlng);
			}
			
		  	google.maps.event.addListener(marker, 'click', function() {
			  //$("#locationDiv").hide();
			  //GetProfileDetails(encounter);
			  infowindow.open(map,marker);
			});
		
      }
  
    </script>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link rel="icon" type="image/gif" href="images/logo-16.gif" />
<link rel="stylesheet" href="css/style.css" type="text/css">
<link rel="stylesheet" type="text/css" href="css/font-awesome.css"/>
 <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="css/flat-ui.css" rel="stylesheet">

<!--[if lte IE 7]>
<link rel="stylesheet" type="text/css" href="css/ie7.css"/>
<link rel="stylesheet" type="text/css" href="css/font-awesome-ie7.min.css"/>
<![endif]-->
<!--[if lte IE 9]>
<link rel="stylesheet" type="text/css" href="css/ie9.css"/>
<![endif]-->
<!--[if lte IE 8]>
<link rel="stylesheet" type="text/css" href="css/ie8.css"/>
<![endif]-->
<script src="js/config.js" type="text/javascript"></script>
<script src="js/functions.js?timer=<?php echo date("Ymdhis");?>" type="text/javascript"></script>
</head>
<body onLoad="initialize()" style="padding-top:0px;">
<div style="width:771px;">
  <div id="map_canvas" style="height:510px;top:0px; width:771px;"></div>
</div>
<div class="map-locations group" style="top: 52px;left: 181px; display:none; width:284px; height:90px;" id="locationDiv"></div>
<script>
var animal_id = '<?php echo $_REQUEST['id'];?>';


function GetProfileDetails(encounter)
{
	var html = '<div class="pic"><div class="align-div '+encounter.category_color+'"> <img src="'+encounter.category_icon+'"> </div></div><div class="locations-details"><h4>'+encounter.verbatim_locality+'</h4><p><span>Photographer:</span> '+encounter.photographer_name+'</p><p><span>Recorded By:</span> '+encounter.recorded_by+'</p></div><i class="icon-angle-right"></i> <i class="icon-caret-down"></i>';
  	$("#locationDiv").html(html);
	$("#locationDiv").show();
	//$("#address").html('<img src="'+canvas_url+'images/loader2.gif" style="margin-left:220px;" />');
	
}

$(function(){	
	
	getAnimalPlaces(animal_id);
	//parent.document.getElementById("spinner-places").style.display = 'none';
	//parent.document.getElementById("loadMap").style.display = '';
});
</script>
</body>
</html>
