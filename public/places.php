<?php
$count_places 	 = 0;
	$encounterData = '';
	
$response_places = file_get_contents("http://fb.wildme.org/wildme/public/api/encounter?limit=100&offset=0&animal_id=".$_REQUEST['id']."&media_offset=0&all_location=1&sortby=date_added&orderby=ASC");
$response_places = json_decode($response_places,true);
if ($response_places['status']=='success')
{
	$count_places 	 = $response_places['totalrecords'];
	$encounterData		 = $response_places['records'];
}
?>
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

	<script type="text/javascript">
      function initialize() {
        var center = new google.maps.LatLng(0,0);
        var mapZoom = 10;
    	if($("#map_canvas").hasClass("full_screen_map")){mapZoom=3;}
    	var bounds = new google.maps.LatLngBounds();
        
        var map = new google.maps.Map(document.getElementById('map_canvas'), {
          zoom: mapZoom,
          center: center,
          mapTypeId: google.maps.MapTypeId.HYBRID
        });

    	  
        var markers = [];
 	    var movePathCoordinates = [];        
        
 		<?php
		$check_in = 0;
		
		if ($count_places>0) {
		$i=0;
		foreach ($encounterData as $encounter) {
			
			
			
			
		$i++;
		if ($encounter['photographer_name']=='')$encounter['photographer_name'] = 'Not Available';
		if ($encounter['recorded_by']=='')$encounter['recorded_by'] = 'Not Available';
		
		if($encounter['latitude']!='' && $encounter['latitude']!=0 && $encounter['longitude']!=0 && $encounter['longitude'] !='')
		{
				
			
		?>
 		  var latLng = new google.maps.LatLng(<?php echo $encounter['latitude'];?>,<?php echo $encounter['longitude'];?>);
          bounds.extend(latLng);
          movePathCoordinates.push(latLng);           
           
		  var marker = new google.maps.Marker({
        	   icon: 'images/map-pin.png',
        	   position:latLng,
        	   map:map			  
        	});
			
			var final_content ='<div style="width:400px; height:100px;"><div class="pic">'
			+'<div class="align-div <?php echo $encounter['category_color']?>">'
			+'<img src="<?php echo $encounter['category_icon']?>">'
			+'</div></div>'
			+'<div class="locations-details">'
			+'<h4><?php echo preg_replace( '/\s+/', ' ',$encounter['verbatim_locality']);?></h4>'
			+'<p><span>Photographer:</span><?php echo $encounter['photographer_name'];?></p>'
			+'<p><span>Recorded By:</span><?php echo $encounter['recorded_by'];?></p>';
			+'</div></div>';
			
            google.maps.event.addListener(marker,'click', function() {
                 (new google.maps.InfoWindow({content:final_content })).open(map, this);
			});
 
	
          	markers.push(marker);
          	map.fitBounds(bounds);        
        
 		 <?php $check_in = 1; } } }
		 
		
		  ?>
 
		 var movePath = new google.maps.Polyline({
			 path: movePathCoordinates,
			 geodesic: true,
			 strokeOpacity: 0.0,
			 strokeColor: 'white',
			 icons: [{
			   icon: {
				 path: 'M -1,1 0,0 1,1',
				 strokeOpacity: 1,
				 strokeWeight: 1.5,
				 scale: 6
				 
			   },
			   repeat: '20px'
			   
			 }
			 ],
			 map: map
		   });
		
		 } // end initialize function
    </script>
    
    <?php 
	 if($check_in != 1)
	 {
		  echo '<script>window.parent.document.getElementById("map-palces-heading").style.display="none";</script>';
		 echo '<script>window.parent.document.getElementById("map-palces").innerHTML="<h3 style=\'margin: 0px auto; width: 98%; text-align: center; border:none; background-color:#c6c6c6; padding-top: 13px; height: 27px; color: #fff; font-weight:bold; font: 15px \"latoregular\",Arial\'>No Places found.</h3>";</script>';
		//   echo '<script>window.parent.$("#map-palces-heading").hide();</script>';
		 
		
		
	 }
	?>

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
	
	//getAnimalPlaces(animal_id);
	//parent.document.getElementById("spinner-places").style.display = 'none';
	//parent.document.getElementById("loadMap").style.display = '';
});
</script>
</body>
</html>
