<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo render('admin_inc.js'); ?>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<style>
.ui-widget { font-size:12px; }
</style>
</head>
<body class="onpage-category">
<?php echo render('admin_inc.header'); ?>
<?php 
$mode = isset($_REQUEST['mode'])?$_REQUEST['mode']:'Add';
$id   = isset($_REQUEST['id'])?$_REQUEST['id']:0;
$category        = json_decode($category, true); 
$category_record = $category['record'];
//print_r($category_record); die();
if ($mode=='edit')
{ 
	$actiontext = 'Update ';
	$g_icon = explode('.',$category_record['icon']); $g_icon_new = $g_icon['0'].'-g.'.$g_icon['1']; //echo $image;
	$final_icon_break = explode('/', $category_record['icon']);
	$final_icon 	  = $final_icon_break[2];
	$g_pic_break 	  = explode('.',$final_icon_break[2]);
	$g_pic = $g_pic_break[0].'-g.'.$g_pic_break[1];
	//Session::put('temp_image_name', $final_icon);
	$_SESSION['temp_image_name'] = $final_icon;
} 
else
{ 
	$actiontext = 'Add New ';
	$g_icon_new = '';
	$final_icon = '';
	$g_pic = '';
}

?>

<div class="wrapper">
  <div class="container con-pad">
    <h1><?php echo $actiontext; ?> Category</h1>
    <a href="category"><div class="btn blue add-btn"><i class="icon-chevron-left"></i>Back</div></a>
    <div class="alert-bar error" id="Message-error" style="display:none; margin-top:10px;">fdfd</div>
    <div class="manage-team-player">
      <h1><?php echo $actiontext; ?> Category</h1>
      <div class="add-new-schedule">
        <div class="form-row">
		<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
          <label>Title</label>
          <input name="title" id="title" type="text" value="<?php echo $category_record['title'];  ?>" placeholder="Enter title"/>
        </div>
        
         <div class="form-row">
          <label>Color</label>
         
		  <select id="color" name="color">
          <option value="blue" <?php if($category_record['color_code'] == 'blue'){ echo 'selected="selected"'; }?>>Blue</option>
		  <option value="orange" <?php if($category_record['color_code'] == 'orange'){ echo 'selected="selected"'; }?>>Orange</option>
		  <option value="yellow" <?php if($category_record['color_code'] == 'yellow'){ echo 'selected="selected"'; }?>>Yellow</option>
          
		  </select>
         </div>
       
         
         <div class="form-row">
          <label>Type</label>
          
		  <select id="type" name="type" >
          <option value="air" <?php if($category_record['type'] == 'air'){ echo 'selected="selected"'; }?>>Air</option>
		  <option value="ground" <?php if($category_record['type'] == 'ground'){ echo 'selected="selected"'; }?>>Ground</option>
		  <option value="water" <?php if($category_record['type'] == 'water'){ echo 'selected="selected"'; }?>>Water</option>
          
		  </select>
         </div>
          
           <div class="form-row">
			<label>Api Prefix</label>
          <input name="url" id="url" type="text" value="<?php echo $category_record['api_url'];  ?>" placeholder="Enter URL"/>
        </div>
        
        <div class="form-row">
			<label>Image URL</label>
          <input name="image_url" id="image_url" type="text" value="<?php echo $category_record['image_url'];  ?>" placeholder="Enter image URL"/>
        </div>
        
		 <div class=" form-row"  >
          <label>White Icon: </label>
          <div class="input-box last"  style="margin: -44px 0 0 201px;">

                <input type="hidden" name="icon" id="icon" value="<?php echo $final_icon;?>" />
                <div id="FileFrame">
                  <iframe id="photo_iframe2" src="../upload-file.php?type=w" scrolling="no" frameborder="0" width="700px" height="40px"></iframe>
                </div>
              </div>
          <div class="browse-field" style="margin-left: 203px;margin-top: 10px; margin-bottom:10px; float:left;width: 100%;" ><div style="background-color:#999; width:80px"><img src="../<?php echo $category_record['icon'];?>" id="icon_preview" width="80" <?php if($category_record == NULL) {echo ' style="display:none;" ';}  ?> /></div></div>
        </div>
       
        <div class=" form-row" id="pic2" <?php if($category_record == NULL) echo ' style="display:none;" '; ?>>
          <label>Grey Icon: </label>
          <div class="input-box last"  style="margin: -44px 0 0 201px;">

                <input type="hidden" name="g_icon" id="g_icon" value="<?php echo $g_pic;?>" />
                <div id="FileFrame" >
                  <iframe id="photo_iframe2" src="../upload-file.php?type=g" scrolling="no" frameborder="0" width="700px" height="40px"></iframe>
                </div>
              </div>
          <div class="browse-field" style="margin-left: 203px;margin-top: 10px; margin-bottom:10px; float:left;width: 100%;"  ><img src="../<?php echo $g_icon_new;?>" id="g_icon_preview" width="80" <?php if($category_record == NULL) echo ' style="display:none;" '; ?> /></div>
        </div>
    
 </div>
		<?php if($mode=='edit') { ?>
        <div class="btn sche-save" onclick="UpdateCategory();" style="margin-bottom: 10px;right: -19px;"><i class="icon-save"></i>Save</div>
		<?php } else { ?>
		<div class="btn sche-save" onclick="PostCategory();" style="margin-bottom: 10px;right: -19px;"><i class="icon-save"></i>Save</div>
		<?php } ?>
         <div style="position: absolute; left: 347px;bottom: 28px; display:none" id="cate_spinner"><img src="../admin/images/spinner.gif" /></div>
      </div>
    </div>
  </div>

<!--footer start-->
<?php echo render('admin_inc.footer'); ?>
</body> 
</html>
