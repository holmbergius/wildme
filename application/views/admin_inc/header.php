<?php echo render('admin_inc.popup'); ?>
<div class="header">
  <div class="wrapper">
    <div class="logo"><img src="../images/logo.png"/ height="48px"></div>
    <div class="ct-panel">Control Panel</div>
    <div class="login-panel">
      <ul>
        <li class="first">Welcome Admin</li>
        <li id="page-password" style="font: 14px 'droid_sansbold',Arial; padding: 20px 9px;"><a href="account">Change Password</a></li>
        <li class="last selected"><a href="javascript:;" onclick="adminLogout();"><i class="icon-off"></i>Logout</a></li>
      </ul>
    </div>
  </div>
  <div class="clear-fix"></div>
</div>
<div class="nav">
  <div class="wrapper">
    <ul>
      <li class="first"></li>
      <li id="page-dashboard"><a href="dashboard">Dashboard</a></li>
      <li id="page-category"><a href="category">Category</a></li>
      <li id="page-user"><a href="user">Users</a></li>
      <li id="page-reminder"><a href="reminders">Reminders</a></li>
      <li id="page-reports"><a href="report_abuse">Report Abuse</a></li>
      <li id="page-adopters"><a href="adopters">Adopters</a></li>
      <li id="page-individual"><a href="individuals">Individuals</a></li>
       <li id="page-revenue"><a href="revenue">Revenue</a></li>
      <li class="last"></li>
    </ul>
  </div>
</div>
