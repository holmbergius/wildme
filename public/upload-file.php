<?php
if(!session_start())
{
	session_start();	
}
?>

<style>
body {
	font-family: Calibri,Arial,Helvetica,sans-serif;
	background-color:#FFFFFF;
	color: #666666;
	font-size:14px;
}
.inputFile {
    -moz-border-bottom-colors: none;
    -moz-border-image: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-attachment: scroll;
    background-clip: border-box;
    background-color: #fff;
    background-image: none;
    background-origin: padding-box;
    background-position: 0 0;
    background-repeat: repeat;
    background-size: auto auto;
    border-bottom-color: -moz-use-text-color;
    border-bottom-style: none;
    border-bottom-width: medium;
    border-left-color-ltr-source: physical;
    border-left-color-rtl-source: physical;
    border-left-color-value: -moz-use-text-color;
    border-left-style-ltr-source: physical;
    border-left-style-rtl-source: physical;
    border-left-style-value: none;
    border-left-width-ltr-source: physical;
    border-left-width-rtl-source: physical;
    border-left-width-value: medium;
    border-right-color-ltr-source: physical;
    border-right-color-rtl-source: physical;
    border-right-color-value: -moz-use-text-color;
    border-right-style-ltr-source: physical;
    border-right-style-rtl-source: physical;
    border-right-style-value: none;
    border-right-width-ltr-source: physical;
    border-right-width-rtl-source: physical;
    border-right-width-value: medium;
    border-top-color: -moz-use-text-color;
    border-top-style: none;
    border-top-width: medium;
    display: block;
    margin-bottom: 0;
    margin-left: -6px;
    margin-right: 0;
    margin-top: 6px;
    padding-bottom: 0;
    padding-left: 0;
    padding-right: 0;
    padding-top: 0;
}
a{
	color:#DC224E;
}
a:hover{
	text-decoration:none;	
}
</style>

<?php 
	//$server='';
//	$server_path = '';
//	$required_width = '400';
//	$required_height = '400';
	$image_name    = date("Ymdhis");
	
	
	
	if ($_SERVER['HTTP_HOST'] == 'developer.cygnismedia.com')
	{ 
		 $server='http://developer.cygnismedia.com/facebook/wildme/public/';
		 $server_path = '/var/www/facebook/wildme/public/';
	}
	else if ($_SERVER['HTTP_HOST'] == 'digital.cygnismedia.com')
	{ 
	 	$server ='http://digital.cygnismedia.com/facebook/wildme/public/';
		$server_path = '/var/www/facebook/wildme/public/';
	}
	else if ($_SERVER['HTTP_HOST'] == 'prod.cygnismedia.com')
	{ 
	 	$server ='http://prod.cygnismedia.com/wildme/public/';
		$server_path = '/var/www/prod.cygnismedia.com/wildme/public/';
	}
	else if ($_SERVER['HTTP_HOST'] == 'wildme.cygnismedia.com')
	{ 
	 	$server ='http://wildme.cygnismedia.com/wildme/public/';
		$server_path = '/var/www/wildme/public/';
	}
	else if ($_SERVER['HTTP_HOST'] == 'fb.wildme.org')
	{ 
	 	$server ='http://fb.wildme.org/wildme/public/';
		$server_path = '/var/www/wildme/public/';
	}
	else 
	{
	 	$server='http://localhost/wildme/public/';
		$server_path="C:/xampp/htdocs/wildme/public/";
	}

	$uploaddir1 = 'images/icon/';
	$required_width = '150';
	//$required_height = '260';
		
	
	
	define('SIZE_WIDTH', 		$required_width);
	//define('SIZE_HEIGHT', 		$required_height);
	
	if(isset($_FILES['imagepath']))
	{
			if($_REQUEST['type'] == 'g')
			{
				//$image_name    = Session::get('temp_image_name');
				$image_name    = $_SESSION['temp_image_name'];
			}
			else 
			{
				//Session::put('temp_image_name', $image_name);
				$_SESSION['temp_image_name']= $image_name;
			}
			$picture		= $_FILES['imagepath'];
			$size    		= getimagesize($picture['tmp_name']);
			$type    		= $size['mime'];
			$width    		= $size[0];
			$height   		= $size[1];
			$picture_extension = Extension($picture["name"]);
			if($width != '150')
			{
				?>
				<div id="error_msg_div" style="display:inline;">Invalid Resolution. <a style="cursor:pointer;font-size:13px; text-decoration:none;" onclick="document.getElementById('error_msg_div').style.display='none'; document.getElementById('show_form_again').style.display='inline';" >Please Try Again</a></div>
<div id="show_form_again" style="display:none;">
			  <?php 
			}
			else
			{
				if ((( $picture_extension=='png' )) )
				{
					if (isset($_REQUEST['type']))
					{
					if($_REQUEST['type'] == 'w')
					{
						$tempImageName = substr($_FILES['imagepath']['name'],-3);
						$temp1FileName = $image_name.'.'.$tempImageName;
						$temp2FileName = $image_name.'@2x.'.$tempImageName;
						
						$thumbfile 	   = $uploaddir1.$temp1FileName;
						$uploadfile1   = $uploaddir1 . basename($temp2FileName);
						move_uploaded_file($_FILES['imagepath']['tmp_name'], $uploadfile1);
						copy ($uploadfile1,$thumbfile);
						imageResize($type,$uploaddir1.$temp1FileName,$required_width/2,$height/2,$width,$height,$thumbfile);
						
						//imageResize($type,$thumbfile,$required_width,$height,$width/2,$height/2,$thumbfile);
						$imagepath = $temp1FileName;
						//echo $imagepath;
						//$imagepath_full =  $server.'public/files/profile/'.$imagepath;
						$imagepath_full =  '../images/icon/'.$imagepath;
						echo '<script>window.parent.document.getElementById("icon").value="'.$imagepath.'";</script>';
						echo '<script>window.parent.document.getElementById("icon_preview").src="'.$imagepath_full.'";</script>';
						echo '<script>window.parent.document.getElementById("icon_preview").style.display="block";</script>';
						echo '<script>window.parent.document.getElementById("pic2").style.display="block";</script>';
						
						?>
						
					   <div id="error_msg_div" style="display:inline;">Photo uploaded successfully.</div><a href="upload-file.php?type=w" style="text-decoration:none;font-size:13px;">Change</a>
		<div id="show_form_again" style="display:none;">
						<?php 
					}
					if($_REQUEST['type'] == 'g')
					{
						echo '<script>window.parent.document.getElementById("g_icon_preview").src="";</script>';
                        //$tempImageName = substr($_FILES['imagepath']['name'],-3);
						//$temp1FileName = $image_name.'-g.'.$tempImageName;
						//$temp2FileName = $image_name.'-g@2x.'.$tempImageName;
					
					$tempImageName = substr($_FILES['imagepath']['name'],-3);
					
					$break = explode('.',$image_name);
					
					$temp1FileName = $break[0].'-g.'.$tempImageName;
					$temp2FileName = $break[0].'-g@2x.'.$tempImageName;
					
					//$thumbfile 	   = $uploaddir1."thumbs/".$temp1FileName;
					$thumbfile 	   = $uploaddir1.$temp1FileName;
					$uploadfile1   = $uploaddir1 . basename($temp2FileName);
					move_uploaded_file($_FILES['imagepath']['tmp_name'], $uploadfile1);
					copy ($uploadfile1,$thumbfile);
					imageResize($type,$uploaddir1.$temp1FileName,$required_width/2,$height/2,$width,$height,$thumbfile);
					
					//imageResize($type,$thumbfile,$required_width,$height,$width/2,$height/2,$thumbfile);
					$imagepath = $temp1FileName;
					
					//$imagepath_full =  $server.'public/files/profile/'.$imagepath;
					echo '<script>window.parent.document.getElementById("g_icon_preview").src="";</script>';
					$imagepath_full =  '../images/icon/'.$imagepath;
					echo '<script>window.parent.document.getElementById("g_icon").value="'.$imagepath.'";</script>';
					echo '<script>window.parent.document.getElementById("g_icon_preview").src="'.$imagepath_full.'";</script>';
				    echo '<script>window.parent.document.getElementById("g_icon_preview").style.display="block";</script>';
					//session_unset('image_name');
					?>
					
				   <div id="error_msg_div" style="display:inline;">Photo uploaded successfully.</div><a href="upload-file.php?type=g" style="text-decoration:none;font-size:13px;">Change</a>
	<div id="show_form_again" style="display:none;">
					<?php 
					}
			}
				}
				else{
				?>
				<div id="error_msg_div" style="display:inline;">Invalid Format or width. <a style="cursor:pointer;font-size:13px; text-decoration:none;" onclick="document.getElementById('error_msg_div').style.display='none'; document.getElementById('show_form_again').style.display='inline';" >Please Try Again</a></div>
<div id="show_form_again" style="display:none;">
			  <?php  // echo '<script>window.parent.document.getElementById("upload_err").src="'.$imagepath_full.'";</script>';
			}
			}
		}
	else
	{
	}
		ShowForm();
?>
<?php
function ShowForm()
{
?>
<?php $show = '';//$_REQUEST['show'];
  ?>
<div style="display:inline;" id="form_div">
  <form name="upload_pic" id="upload_pic" method="post" action="" enctype="multipart/form-data">
    <input type="file" size="20" name="imagepath" id="imagepath" onChange="CheckValue(this);" class="inputFile" style="float:left; margin-top:-5px;"  /> &nbsp; (Image width should be: <?php echo SIZE_WIDTH.'px';?>)</span>
  </form>
</div>
<div id="img_div" style="display:none;">
  <div style="margin-top:0px;"> Please wait... </div>
</div>
<script language="javascript" type="text/javascript">
	function CheckValue(browse_pic)
	{
		if(browse_pic.value!='')
		{
			document.getElementById("upload_pic").submit();
			document.getElementById("form_div").style.display='none';
			document.getElementById("img_div").style.display='inline';
			
		}
	}
	
	


</script>
<?php
}
//Set Extension
function Extension($file)
{
	$ext		= explode(".", $file);
	$extension	= count($ext)-1;
	return strtolower($ext[$extension]);
}
  
  function imageResize($tempImageType,$uploadfile1,$newwidth,$newheight,$width,$height,$uploadedfile)
  { 
	  $quality = 9;
	 
	 
	  
			$width_t = $newwidth;
			$height_t = $newheight;
			$off_x=$off_y=0;
			
	
	  
	 $tmp   = imagecreatetruecolor($width_t,$height_t);
	 $bg 	= imagecolorallocate ( $tmp, 255, 255, 255 );
	 imagefill ( $tmp, 0, 0, $bg );
	 $filename   =  $uploadfile1;
	 $off_x = 0;
	 $off_y = 0;
	  
	 /////////if image is png
	 if($tempImageType == 'image/png')
	 {
		  $src    =  imagecreatefrompng($uploadedfile);
		  ////// creating with transparency
		  imagealphablending($tmp, false);
		  imagesavealpha($tmp,true);
		  $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
		  imagefilledrectangle($tmp, 0, 0, $width_t, $height_t, $transparent);		
		  imagecopyresampled($tmp,$src,$off_x,$off_y,0,0,$width_t,$height_t,$width,$height);
		  imagepng($tmp,$filename,$quality);
	 }
	 elseif($tempImageType == 'image/gif')
	 {
		  $src    =  imagecreatefromgif($uploadedfile);
		  imagecopyresampled($tmp,$src,$off_x,$off_y,0,0,$width_t,$height_t,$width,$height);
		  imagepng($tmp,$filename,$quality);
	 }
	 elseif($tempImageType == 'image/jpeg')
	 {
		  $src    =  imagecreatefromjpeg($uploadedfile);
		  imagecopyresampled($tmp,$src,$off_x,$off_y,0,0,$width_t,$height_t,$width,$height);
		  imagepng($tmp,$filename,$quality);
	 }
	 elseif($tempImageType == 'image/jpg')
	 {
		  $src    =  imagecreatefromjpeg($uploadedfile);
		  imagecopyresampled($tmp,$src,$off_x,$off_y,0,0,$width_t,$height_t,$width,$height);
		  imagepng($tmp,$filename,$quality);
	 }
	 else
	 {
		  $src    =  imagecreatefrompng($uploadedfile);
		  imagecopyresampled($tmp,$src,$off_x,$off_y,0,0,$width_t,$height_t,$width,$height);
		  imagepng($tmp,$filename,$quality);
	 }
	  
	 imagedestroy($src);
	 imagedestroy($tmp);
  }
?>