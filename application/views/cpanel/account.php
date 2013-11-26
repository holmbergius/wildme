<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo render('admin_inc.js'); ?>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<style>
#Authorize{
	float: left;
    padding-left: 324px;
    padding-top: 9px;
    position: absolute;
}
.ui-widget { font-size:12px; }
</style>
</head>
<body class="onpage-password">
<?php echo render('admin_inc.header'); ?>

<input type="hidden" value="<?php echo Session::get('s_admin_id') ?>" id="admin_id" />
<div class="wrapper">
  <div class="container con-pad">
    <div class="manage-team-player">
      <h1>Change Password</h1>
      <div class="add-new-schedule">
      	<div style="text-align: center;width:900px; display:none; background-color: rgb(232, 251, 190); color: rgb(85, 102, 82); border: 1px solid rgb(176, 191, 143); margin-bottom: 5px; border-radius: 2px 2px 2px 2px; font: 16px 'RobotoRegular',arial; padding: 10px;" id="AccountMessage-success">Your password has been changed!</div>
      	<div style="text-align:center; width:900px;background-color:#F88585; color:#fff; border:1px solid #B32F25; margin-bottom:5px; border-radius:2px; font:16px 'RobotoRegular',arial; padding:10px; display:none;" id="AccountMessage-error">The password is invalid!</div>
        <div class="form-row">
		<input type="hidden" name="id" id="id" value="" />
          <label>Current Password</label>
          <input name="oldpassword" id="oldpassword" type="password" value="" placeholder="Enter your password"/>
        </div>
        <div class="form-row">
          <label>New Password </label>
          <input name="newpassword" id="newpassword" type="password" value="" placeholder="Enter new password"/>
        </div>
        <div class="form-row">
          <label>Confirm Password</label>
          <input name="confirmpassword" id="confirmpassword" type="password" value="" placeholder="Enter password again"/>
        </div>
        
		<div id="Authorize" style="display:none;" ><img src="../admin/images/spinner.gif" /></div>
		<div class="btn sche-save" onclick="return UpdatePassword();"><i class="icon-save"></i>Submit</div>
		
      </div>
    </div>
  </div>
</div>
<!--footer start-->
<?php echo render('admin_inc.footer'); ?>
</body>
</html>
