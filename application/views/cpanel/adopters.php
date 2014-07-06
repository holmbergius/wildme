<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo render('admin_inc.js'); ?>
</head>
<body class="onpage-adopters">
<?php echo render('admin_inc.header'); ?>
<div class="wrapper">
  <div class="container" style="padding:10px 10px 33px">
	<h1 style="font-size:28px;">Adopters (<span id="TotalAdopters">0</span>)</h1>
<div style="float:right; width:235px; margin-top:-42px;">
 
   <select name="sortBy" id="getSortType" style="float:left; height:36px; width:215px; margin-left:10px;" class="sortBy" onchange="GetAdopters(0);">
 <option value="id">Sort By</option>
  <option value="application">Application Users</option>
  <option value="website">Website Users</option>
  </select>
  <!--
   <input type="text" name="search" id="search" style="float:right; width:300px;" placeholder="Search " onkeypress="Search(event,'category');"/>-->
  </div>

    </span>
    <div class="table-liting-data" style="width:982px">
    
     <div class="alert-bar success" id="Message-success"  style="display:none ;font-size: 16px;margin-bottom: 15px;margin-top: 15px;text-align: center;" ><i class="icon-ok"></i></div>
     
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="title">
          <td align="center" width="50px" class="first" style=" font-size: 14px;">ID</td>
          <td class="width" width="250px" style=" font-size: 14px;">Adopter</td>
          <td class="width" width="70px" style=" font-size: 14px; width:200px;">Marked Individual</td>
          <td class="width" width="150px" style=" font-size: 14px;">Date</td>
          <td class="width" style=" font-size: 14px; width:150px;">Nick Name</td>
          <td  class="width" style=" font-size: 14px; width:180px;">Quote</td>
        </tr>
        <tbody id="adopter_rows">
        </tbody>
      </table>
    </div>
  <!--  <div class="activate" style="visibility:hidden;">
      <select name="" class="activate-select width150" id="action">
        <option value="Delete">Delete</option>
      </select>
      <div class="btn" onclick="">Apply</div>
    </div>-->
   
    
    <div id="content  group" style=" bottom: 7px;position: absolute;right: 6px;">
      <ul id="pagination-freebie">
        <li>
          <ul class="pagination light" id="Paging">
            <!--  <li class="prev"><i class="icon-chevron-left"></i>Prev</li>
            <li>1</li>
            <li>2</li>
            <li>3</li>
            <li>4</li>
            <li>5</li>
            <li>6</li>
            <li>...</li>
            <li>22</li>
            <li class="next">Next<i class="icon-chevron-right"></i></li>-->
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
