<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo render('admin_inc.js'); ?>
</head>
<body class="onpage-category">
<?php echo render('admin_inc.header'); ?>
<div class="wrapper">
  <div class="container" style="padding:10px 10px 33px">
	<h1 style="font-size:28px;">Categories (<span id="TotalCategory">0</span>)</h1>
<div style="float:right; width:550px; margin-top:-42px;">
  <input type="text" name="search" id="search" style="float:left;" placeholder="Search Category..." onkeypress="Search(event,'category');"/>
  <select name="sortBy" id="sortBy" style="float:left; height:36px; width:179px; margin-left:10px;" class="sortBy" onchange="GetCategory(0);">
  <option value="id">Sort By</option>
  <option value="title_asc">Title (A-Z)</option>
  <option value="title_desc">Title (Z-A)</option>
  </select>
  </div>
  <a href="addCategory"><div class="btn blue add-btn " style="margin-top:5px;"><i class="icon-plus-sign"></i>Add Category</div></a>
    </span>
    <div class="table-liting-data" style="width:982px">
    
     <div class="alert-bar success" id="Message-success"  style="display:none ;font-size: 16px;margin-bottom: 15px;margin-top: 15px;text-align: center;" ><i class="icon-ok"></i></div>
     
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="title">
          <td align="center" width="50px" class="first" style=" font-size: 14px;"><!-- <input name="" type="checkbox" id="checkbox" value="" /> -->
            ID</td>
          <td class="width" style=" font-size: 14px;">Title</td>
          <td class="width" style=" font-size: 14px;">Color</td>
          <td class="width" style=" font-size: 14px;">Type</td>
          <td class="width" style=" font-size: 14px;">Api Prefix</td>
          <td style=" font-size: 14px;">Actions</td>
        </tr>
        <tbody id="category_rows">
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
