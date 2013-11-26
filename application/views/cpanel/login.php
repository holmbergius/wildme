<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php echo render('admin_inc.js'); ?>
</head>

<body>
<div class="wrapper-login-section "></div>
<div class="login-pannel">
  <div class="login-form-box">
    <h1>Control Panel</h1>
    <div class="form-row">
	<div class="alert-bar success" style="display:none;" id="loginSuccess"><i class="icon-ok"></i>Login successfully... redirecting..</div>
    <div class="alert-bar error" style="display:none;" id="loginError"><i class="icon-remove"></i>Invalid username or password</div>
      <input name="username" type="text" id="username" value="Enter Username"  onblur="if (this.value=='') { this.value='Enter Username'; }" onFocus="if (this.value=='Enter Username') { this.value=''; }" onKeyPress="document.getElementById('username').className='';"  />
    </div>
    <div class="form-row">
     <input name="password" id="password" value="Enter Password" type="text" onBlur="if (this.value=='') { this.value='Enter Password';document.getElementById('password').type = 'text'; }" onFocus="if (this.value=='Enter Password') { this.value=''; };document.getElementById('password').type = 'password';" onKeyPress="SubmitAdminLogin(event);" />
    </div>
    <div class="btn login"><a href="#" onClick="AdminLogin();">Log in</a></div>
  </div>
  <p>Copyright © 2013 Wildme, All Rights reserved<span>Powered by Cygnis Media</span></p>
</div>
</div>

<!--footer start-->

</body>
</html>