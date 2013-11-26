<?php echo render('admin_inc.js'); ?>
<?php
 if ($admin_id > 0)
 	{
		echo "<script>window.location=AdminUrl+'category';</script>";
	}
	else
	{
		echo "<script>window.location=AdminUrl+'login';</script>";
	}
?>