<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo render('admin_inc.js'); ?>
 <link rel="stylesheet" href="../admin/css/chart/style.css" type="text/css">
<script src="../admin/js/amcharts/amcharts.js" type="text/javascript"></script>
<script src="../admin/js/amcharts/serial.js" type="text/javascript"></script>
<script type="text/javascript">


	

// generate some random data, quite different range
function generateChartData() {
   var firstDate = new Date();
   firstDate.setDate(firstDate.getDate() - 50);

   for (var i = 0; i < 5; i++) {
	   newDate.setDate(newDate.getDate() + i);

	   var amount = Math.round(Math.random() * 40) + 100;
	   var adoptions = Math.round(Math.random() * 80) + 500;

	   chartData.push({
		   date: newDate,
		   amount: amount,
		   adoptions: adoptions
	   });
   }
}
	 
</script>
</head>
<body class="onpage-revenue">
<?php echo render('admin_inc.header');

$curr_date = date('m/d/Y');
$date1 = date('m/d/Y', strtotime($curr_date.' - 4 months'));

?>

<div class="wrapper">
  <div class="container" style="padding:10px 10px 33px">
	<h1 style="font-size:16px;">Over All Revenue</h1>
   <!-- <h1 style="font-size:18px;float:right; margin-top: -33px;"><a href="rhistory">Revenue History</a></h1>-->
    <a href="rhistory"><div style="margin-top:5px;" class="btn blue add-btn ">Revenue History</div></a>
    
  <div id="dashboard_spin" style="position: absolute; left: 168px; top: 11px;"><img src="../admin/images/spinner.gif" /></div>
    <div class="table-liting-data" style="width:982px; border:none">
 	 
     <p style="margin-left:10px; font-size:14px;" id="count_log" >  Total Revenue Collected : </p>    	
     
    </div>
	<h1 style="font-size:16px;">Breakdown of Over All Revenue</h1>
  
    <div class="table-liting-data" style="width:982px;  border:none">
      	  <table width="800" border="1">
 <tbody id="category_rows_total">
        </tbody>
</table>
<br />
<br />
<hr>
    </div>
    
     <div class="table-liting-data" style="width:982px;  border:none; margin-bottom:100px;">
         <div id="revenue_total"></div>

        <div style="float: right; width: 470px;margin-top:20px;">
    <div class="btn" onclick="GetStatsRevenue();GetCategoryRevenue();" style="float: left;">Search</div>
    
  <input value="<?php echo  $date1; ?>" type="text" name="date1" id="date1" style="float: left; margin-left: 28px;" placeholder="Select Start Date" class="">
  
  <input value="<?php echo  $curr_date; ?>" type="text" name="date2" id="date2" style="float: left; margin-left: 306px; margin-top: -38px;" placeholder="Select End Date"  class="">
  </div>

     
     </div>
     
<div class="clear"></div>
<div class="clear-fix"></div>
    <div class="table-liting-data" style="width:982px;  border:none">
      	  <table width="800" border="1">
 <tbody id="category_rows">
        </tbody>
</table>

    </div>
    


	<h1 style="font-size:18px;width:200px;margin-top:10px;">Transactions</h1>  
   
    <div class="table-liting-data" style="width:982px">
      	  <div id="chartdiv" style="width: 100%; height: 400px;"></div>
    </div>
  </div>
</div>
<!--footer start-->
<?php echo render('admin_inc.footer'); ?>
<script>


$(document).ready(function() {
	$("#date1").datepicker({ 'autoclose': true, format: 'MM dd , yyyy'});
	$("#date2").datepicker({ 'autoclose': true, format: 'MM dd, yyyy'});
	
    GetCategoryRevenue(0);
	GetStatsRevenue();
	//$("#search").datepicker({ 'autoclose': true, format: 'MM, yyyy'});
	  
});
</script>
</body>
</html>
