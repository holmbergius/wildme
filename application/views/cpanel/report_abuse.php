<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo render('admin_inc.js'); ?>
</head>
<body class="onpage-report_abuse">
<?php echo render('admin_inc.header'); ?>
<div class="wrapper">
  <div class="container" style="padding:10px 10px 33px">
	<h1 style="font-size:28px;">Report Abuse (<span id="TotalReports">0</span>)</h1>
<div style="float:right; width:550px; margin-top:-42px;">
 
  </div>

    </span>
    <div class="table-liting-data" style="width:982px">
    
     <div class="alert-bar success" id="Message-success"  style="display:none ;font-size: 16px;margin-bottom: 15px;margin-top: 15px;text-align: center;" ><i class="icon-ok"></i></div>
     
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="title">
          <td align="center" width="50px" class="first" style=" font-size: 14px;">ID</td>
          <td class="width" style=" font-size: 14px;width:200px;">Commenter</td>
          <td class="width" style=" font-size: 14px; width:200px;">Reporter</td>
          <td class="width" style=" font-size: 14px;width:100px;">Marked Individual</td>
          <td class="width" style=" font-size: 14px; width:150px;">Comment</td>
          <td class="width" style=" font-size: 14px; width:100px;">Date</td>
          <td  class="width" style=" font-size: 14px; width:150px;">Action</td>
        </tr>
        <tbody id="reports_rows">
        </tbody>
      </table>
    </div>
   
    
    <div id="content  group" style=" bottom: 7px;position: absolute;right: 6px;">
      <ul id="pagination-freebie">
        <li>
          <ul class="pagination light" id="Paging">
          </ul>
        </li>
      </ul>
    </div>
    
  </div>
</div>
<!--footer start-->
<?php echo render('admin_inc.footer'); ?>
</body>
</html>
