<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>404</title>
<?php echo render('inc.js'); ?>
</head>
<body>
<div class="main-wrapper"> <?php echo render('inc.header'); ?>
  <div class="app-wraper white">
    <div class="container">
      <div class="main-search">
        <input name="" onkeypress="SearchEncounters(event);" type="text" id="keyword" placeholder="Search for wildlife" />
        <!--<select name="">
          <option>Filter by type</option>
        </select>-->
        <div class="span3">
          <select name="category" class="select-block span3" id="category">
            <?php /*?><option value="0">Whale Shark</option>
            <option value="1">Snow Leopard</option>
            <option value="2">Peregrine Falcons</option>
            <option value="X-Men" selected="selected">Stumpy</option>
            <option value="Crocodile">Crocodile</option><?php */?>
          </select>
        </div>
        <a onclick="SearchEncounters(event);" href="javascript:SearchEncounters(event);" class="btns noSelect serach-btn"><i class="icon-search"></i></a> </div>
      <br clear="all" />
      <div class="gre-wrapper">
      <h1 class="hd-error-page">404</h1>      
      <div class="bear-img"></div>
      

      <h2 class="hd-error-page">Something Bad Happened</h2>
      <p class="hd-error-page">The page you are trying to reach wasn't there.  But the good thing is, we are still here and you can go back to the <a href="<?php echo Config::get('application.web_url'); ?>">application</a> to explore further.</p>
      
      
      <br clear="all" />
            <br clear="all" />
            
            
            <!--button-->
            <div style="width:auto; display:table; margin:0 auto">
      <div class="list-comment-follow2 custom1" style="display:none;">
                <div class="list-comment-counts-follow2 custom2" > <span class="custom5">-</span> WILD ME <span class="custom4">-</span></div>
                <div class="list-comment-count-bg-follow2 custom1">1</div>
              </div>
              <div>
                          <!--button end-->
      
      </div>
    </div>
  </div>
</div>
</body>
</html>