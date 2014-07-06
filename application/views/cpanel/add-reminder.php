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
<body class="onpage-reminder">

<?php echo render('admin_inc.header'); ?>

 <?php 

 if (isset($_REQUEST['id']))
 {
    $id = $_REQUEST['id'];
    $actiontext = 'Update ';
}

else
  {
    $id =  0;
    $actiontext = 'Add New ';
  }
?>

<script>
  $(document).ready(function() {
getSingleReminder('<?php echo $id;?>');
});

 </script>

<div class="wrapper">
  <div class="container con-pad">
    <h1><?php echo $actiontext; ?> Reminder</h1>
    <a href="reminders"><div class="btn blue add-btn"><i class="icon-chevron-left"></i>Back</div></a>
    <div class="alert-bar error" id="Message-error" style="display:none; margin-top:10px;"></div>
    <div class="alert-bar success" id="Message-success"  style="display:none ;font-size: 16px;margin-bottom: 15px;margin-top: 15px;text-align: center;" ><i class="icon-ok"></i></div>
    <div class="manage-team-player">
      <h1><?php echo $actiontext; ?> Reminder</h1>
      <div class="add-new-schedule">
        <div class="form-row">
		
         <input type="hidden" id = "id" name="id" value="<?php echo $id;?>">
          <label>Title</label>
          <input name="title" id="title" type="text" placeholder="Enter title"/>
        </div>
        
         <div class="form-row">
          <label>Time Interval</label>
           <input name="interval" id="interval" type="text" placeholder="E.g 10" style="width:62px;" />
         <!--<select id="interval" name="interval" style="width:80px;">
          <option value="1" >1</option>
		  <option value="2" >2</option>
          <option value="3" >3</option>
          <option value="4" >4</option>
          <option value="5">5</option>
          <option value="6" >6</option>
          <option value="7" >7</option>
          <option value="8">8</option>
          <option value="9" >9</option>
          <option value="10" >10</option>
          <option value="11" >11</option>
          <option value="12" >12</option>
          <option value="13" >13</option>
          <option value="14" >14</option>
          <option value="15" >15</option>
          <option value="16" >16</option>
          <option value="17" >17</option>
          <option value="18" >18</option>
          <option value="19" >19</option>
          <option value="20" >20</option>
          <option value="21" >21</option>
          <option value="22" >22</option>
          <option value="23" >23</option>
          <option value="24" >24</option>
          <option value="25" >25</option>
          <option value="26" >26</option>
          <option value="27" >27</option>
          <option value="28" >28</option>
          <option value="29" >29</option>
          <option value="30" >30</option>
          
		  </select>-->
          
		  <select id="period" name="period" style="width:140px;">
          <option value="months" >Months</option>
		  <option value="days" >Days</option>
		  </select>
         </div>
       
         
         <div class="form-row">
          <label style="float:left;">Template</label>
         	<textarea  name="template"  id="template" rows="4"  placeholder="Enter template" style="width:440px; height:200px;"></textarea>
         </div>

         <div id="tool-tip" style="color: #666666;font: 14px Calibri,Arial,Helvetica,sans-serif;margin-left:200px;width: 600px;">
         <h2 style="21px 'droid_sansbold',Arial; color:#2F2F2F; font-weight:bold; font-size:18px;">Legends : </h2>
          [USERNAME] = User name <br/>
          [MARKEDINDIVIDUAL] = Animal name<br/>
          [MARKEDINDIVIDUAL_LINK] = The link goes to animal's profile <br/>
          [MARKEDINDIVIDUAL_ADOPTION_LINK] = The link goes to payment of adoption fees<br/>
         </div>

         <!--<div style="width: 240px; height: 260px; margin-bottom: -274px; border: 1px solid grey; left: 686px; top: -229px;" class="form-row">
          <h1>Hint</h1>
          <label style="padding-left:20px;padding-right:20px;padding-top:10px;padding-bottom:30px;font-size:16px;">
             [USERNAME] : User name<br>
             [MARKEDINDIVIDUAL] : Animal name<br>
             [MARKEDINDIVIDUAL_LINK] : The link goes to animal's profile  <br>
             [MARKEDINDIVIDUAL_ADOPTION_LINK] : The link goes to payment of adoption fees
           
           </label>
        </div>-->
</div>
	
        <div class="btn sche-save" onclick="AddReminder('<?php echo $id;?>');" style="margin-bottom: 10px;right: -19px;"><i class="icon-save"></i>Save</div>
		
         <div style="position: absolute; left: 347px;bottom: 28px; display:none" id="reminder_spinner"><img src="../admin/images/spinner.gif" /></div>
      </div>
    </div>
  </div>

<!--footer start-->
<?php echo render('admin_inc.footer'); ?>
</body> 
</html>
