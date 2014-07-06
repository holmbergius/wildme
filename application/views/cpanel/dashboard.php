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
                
                   var newDate = new Date(firstDate);
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
<body class="onpage-dashboard">
<?php echo render('admin_inc.header'); ?>

<div class="wrapper">
  <div class="container" style="padding:10px 10px 33px">
  <div id="dashboard_spin" style="position: absolute; left: 122px; top: 11px;"><img src="../admin/images/spinner.gif" /></div>
	<h1 style="font-size:18px;">Dashboard</h1>
  
    <div class="table-liting-data" style="width:982px; border:none">
 	 
     <p style="margin-left:10px;font-size:14px;" id="count_log">  Total No. of Active Adopters : </p>    	
     
    </div>
	<h1 style="font-size:18px;">Breakdown of Active Adoptors</h1>
  
    <div class="table-liting-data" style="width:982px;  border:none">
      	  <table width="800" border="1">
 <tbody id="category_rows">
        </tbody>
</table>

    </div>
	<h1 style="font-size:18px;">Adoptions</h1>
    
    <div class="table-liting-data" style="width:982px">
      	  <div id="chartdiv" style="width: 100%; height: 400px;"></div>
    </div>
  </div>
</div>
<!--footer start-->
<?php echo render('admin_inc.footer'); ?>
<script>


$(document).ready(function() {
    GetCategoryDashboard(0);
	 GetStats(0);

	  
});
</script>
</body>
</html>
