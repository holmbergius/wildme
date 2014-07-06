<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo render('admin_inc.js'); ?>
</head>
<body class="onpage-revenue">
<?php echo render('admin_inc.header'); ?>
<div class="wrapper">
  <div class="container" style="padding:10px 10px 33px">
	<h1 style="font-size:20px;">Transaction History(<span id="TotalAdopters">0</span>)</h1>
    
  <div style="float:right; width:195px; margin-top:-42px;">
    
  <select onchange="GetOptions();" class="sortBy" style="float: right; height: 36px; width: 179px; margin-left: 15px; margin-right: 19px;" id="adv_search" name="adv_search">
  <option value="">Search By </option>
  <option value="name">Animal Or Adoptor</option>
  <option value="date_range">Date Range</option>
  <option value="category">Choose Category</option>
  <option value="user_type">Sort By User Type</option>
  </select>
  </div>

  <div id="user_type_div" style="float:right; width:195px; margin-top:-42px;display:none;margin-right: 201px;">

      <select name="sortBy" id="getSortTypehistory" style="float:right; height:36px; width:179px; margin-left:15px; margin-right:10px;" class="sortBy" onchange="GetAdoptersHistory(0);">
       <option value="id">Sort By</option>
        <option value="application">Application Users</option>
        <option value="website">Website Users</option>
        </select>
 
  </div>
  
  <div style="float: right; margin-top: -42px; width: 325px; margin-right: 201px; display:none" id="date_range_div">
    <div style="float: left;" onclick="GetAdoptersHistory(0);" class="btn">Search</div>
    
  <input type="text" value="" name="date1" id="date1" style="float: left; margin-left: 7px; margin-right: 10px;" placeholder="Select Start Date" class="">
  
  <input type="text" value="" name="date2" id="date2" style="float: left; margin-left: 107px; margin-top: 0px;" placeholder="Select End Date" class="">
  </div>
  
  <div id="name_search_div" style="float: right; margin-top: -42px; margin-right: 201px; width: 241px; display:none">
    
  <input type="text" class="" placeholder="Search Animal Or Adoptor" style="float: left; margin-left: 7px; margin-right: 10px; width: 214px;" id="search" name="search" value=""  onkeypress="Search(event,'transactions');" >
  
  </div>
  
  <div id="category_search_div" style="float: right; margin-top: -42px; margin-right: 201px; width: 321px; display:none">
    
  <select name="" id="getCategoryList" style="float:left; height:36px; width:215px; margin-left:97px;" class="sortBy" onchange="GetAdoptersHistory(0);">
  </select>
  
  </div>
  
  
<h1 style="font-size:16px;"  id="total_amount">Total Amount : </h1>

    </span>
    
    
    <div class="table-liting-data" style="width:982px">
    
     <div class="alert-bar success" id="Message-success"  style="display:none ;font-size: 16px;margin-bottom: 15px;margin-top: 15px;text-align: center;" ><i class="icon-ok"></i></div>
     
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr class="title">
          <td align="center" width="50px" class="first" style=" font-size: 14px;">ID</td>
          <td class="width" style=" font-size: 14px;">Adopter</td>
          <td class="width" style=" font-size: 14px; width:200px;">Marked Individual</td>
           <td class="width" style=" font-size: 14px; width:150px;">Amount</td>
          <td class="width" style=" font-size: 14px;">Date</td>
         
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
<script>


$(document).ready(function() {
	$("#date1").datepicker({ 'autoclose': true, format: 'MM dd , yyyy'});
	$("#date2").datepicker({ 'autoclose': true, format: 'MM dd, yyyy'});	
	GetCategoryList();  
});
</script>

</body>
</html>
