 <div class="popup-inner activites-poup photo-popup" style="display:none; position:absolute;">
  <div class="popup-inner-content" style="position:relative;">
  <h3><a href="javascript:;" id="animal_title">Rex (TX-004)</a><i id="animal_location" style="margin-left:5px;"> at West Bank of Flower Gardens National Marine Sanctuary </i> <span class="hide-activites-poup"><i class="icon-remove"></i></span></h3>
  <div class="slider-image" id="scrollable2" >
  
  <ul class="items" id="photo-items">
  	<li><img src="images/dummy-pic/slider-pic.jpg" /></li>
  </ul>
  
  </div>
  <div class="slider-pic-left-arrow prev"><i id="photo_pre" onclick="getLocation();" class="icon-angle-left"></i></div>
   <div class="slider-pic-right-arrow next"><i id="photo_nxt" onclick="getLocation();" class="icon-angle-right"></i></div>
  
  </div>
  
  
  </div>
  
  
  <div class="popup-inner" id="popup-inner" style="display:none;left: 65% !important;top:5% !important;position:absolute;">
  <div class="popup-inner-content">
    <h3 id="popup-title"></h3>
    <div class="view-all-content scrollbar3">
    <div class="scrollbar">
        <div class="track">
          <div class="thumb">
            <div class="end"></div>
          </div>
        </div>
      </div>
      <div class="viewport">
      <div class="overview">
      <ul id="popup-list">
        <?php /*?><li>
          <div class="pic">
            <div class="align-div  blue"> <img src="images/icon/01.png"> </div>
          </div>
          <div class="listing-details">
            <h4><a href="#">Alex <span>(MZ614)</span></a></h4>
            <p>Whale Shark, Male</p>
            <div class="list-comment-counter">
              <div class="list-comment-counters-inner"> <span>-</span> FOLLOW</div>
              <div class="list-comment-count-bg">125</div>
            </div>
          </div>
        </li><?php */?>
        
        
      </ul>
      </div>
    </div>
    <div class="row" style="margin-top:8px; display:none;" id="loadmore_popup"> <a href="#"><span class="loadmore">LOAD MORE <img src="<?php echo Config::get('application.web_url'); ?>images/s-spinne.png" style="display:none;" class="icon-spin" id="loadmore-popup-spinner"></span></a> </div>
    </div>
  </div>
</div>



<div class="popup-inner pic-poup" style="display:none">
  <div class="popup-inner-content" style="position:relative;">
    <h3><a href="#">Rex (TX-004</a>) at West Bank of Flower Gardens National Marine Sanctuary <span class="hide-pic-poup"><i class="icon-remove"></i></span></h3>
    <div class="slider-image" id="scrollable1">
      <ul class="items">
        <li><img src="images/dummy-pic/slider-pic.jpg" /></li>
        <li><img src="images/dummy-pic/slider-pic.jpg" /></li>
        <li><img src="images/dummy-pic/slider-pic.jpg" /></li>
      </ul>
    </div>
    <div class="slider-pic-left-arrow prev"><i class="icon-angle-left"></i></div>
    <div class="slider-pic-right-arrow next"><i class="icon-angle-right"></i></div>
  </div>
</div>
  
  <?php /*?><div class="popup-inner pic-poup" style="display:none">
  <div class="popup-inner-content" style="position:relative;">
  <h3><a href="#">Rex (TX-004</a>) at West Bank of Flower Gardens National Marine Sanctuary <span class="hide-pic-poup"><i class="icon-remove"></i></span></h3>
  <div class="slider-image scrollable1" id="scrollable1">
  <ul class="items">
  <li><img src="images/dummy-pic/slider-pic.jpg" /></li>
   <li><img src="images/dummy-pic/slider-pic.jpg" /></li>
    <li><img src="images/dummy-pic/slider-pic.jpg" /></li>
  </ul>
  
  </div>
  <div class="slider-pic-left-arrow prev"><i class="icon-angle-left"></i></div>
   <div class="slider-pic-right-arrow next"><i class="icon-angle-right"></i></div>
  
  </div>
  
  
  </div><?php */?>
  
  
 <!-- delete popup-->
 <div class="popup" style="display:none;" id="delete_popup">
 <div class="popup-inner report-spam-popup activites-poup" style="display:block; position:absolute;">
  <div class="popup-inner-content white" style="position:relative;">
     <h3 class="report-popup-title"><i id="animal_location" style="margin-left:5px; font-size:15px"> Report / Spam</i></h3>
     <div id="report-text"><p class="report-text" >Are you sure you want to report this comment? </br>
     <span class="yes-no-btn"><a href="javascript:;" id="confirm">Yes</a></span>
       <span class="yes-no-btn"><a href="javascript:;" onclick="$('#delete_popup').fadeOut();">No</a></span>
     <!--<button class="yes-no-btn" href="javascript:;" id="confirm" >Yes</button> <button href="javascript:;" class="hide-activites-poup">No</button>--></p> </div>
   </div>
</div>
 </div>


 <!-- ban user popup-->
 <div class="popup" style="display:none;" id="ban_popup">
 <div class="popup-inner report-spam-popup activites-poup" style="display:block; position:absolute;">
  <div class="popup-inner-content white" style="position:relative;">
     <h3 class="report-popup-title"><i id="login_location" style="margin-left:5px; font-size:15px">Sorry you are Banned</i></h3>
     <div><p style="padding: 27px 17px 38px;" class="report-text">Your account has been temporary banned, please contact to the admin to continue.</br>
    <!--  <span class="yes-no-btn"><a href="javascript:;" id="confirm1" >Okey</a></span> -->
       <!-- <span class="yes-no-btn"><a href="javascript:;" onclick="$('#delete_popup').fadeOut();">No</a></span> -->
     <!--<button class="yes-no-btn" href="javascript:;" id="confirm" >Yes</button> <button href="javascript:;" class="hide-activites-poup">No</button>--></p> </div>
   </div>
</div>
 </div>
 
