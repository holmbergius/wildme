<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WILDME</title>
<style>
.widget ul li {
	margin-right: 3px !important;
}
</style>
<?php
error_reporting(0);
$app_url	=	Config::get('application.app_url');
$web_url	=	Config::get('application.web_url');

$animal        = json_decode($animal, true); 
$animal_record = $animal['records'][0];
$id	=	$animal_record['id'];
$label	=	$animal_record['label'];
$shareImage	=	($animal_record['profile_pic'])?$animal_record['profile_pic']:$web_url."images/batch@2x.png";
/*
	
	try{
		$size =   	getimagesize($shareImage);
	}
	catch(Exception $e) {	
     $shareImage = $web_url."images/batch@2x.png";
	}*/
	
	if(is_array(getimagesize($animal_record['profile_pic']))){
		 $shareImage =$animal_record['profile_pic'];
	}else{
		 $shareImage = $web_url."images/batch@2x.png";
	}

$nick_name = $animal_record['nick_name'];
if($nick_name  == NULL  ){
$nick_name = '';
}
$title = 'I just adopted '.$nick_name.' '.$label;
$description = 'I care for the wildlife and I have shown this by adopting the '.$nick_name.' '.$label.' Join in and show your support for the wildlife and adopt a few animals yourself.';

?>
<meta charset="utf-8">
<meta name="viewport" content="width=1024,maximum-scale=1.0">
<meta property="fb:app_id" content="306566776167234">
<meta property="og:title" content="<?php echo $title;?>">
<meta property="og:description" content="<?php echo $description;?>">
<meta property="og:image" content="<?php echo $shareImage;?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="Wild Me">
<meta property="og:url" content="http://fb.wildme.org/wildme/public/share/<?php echo $id;?>">
</head>
<body>
<script>

	window.top.location = 'http://fb.wildme.org/wildme/public/profile/<?php echo $label;?>';


</script>
</body>
</html>