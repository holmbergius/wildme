<!----------------terms-popup------------------->

<div class="popup" style="display:none;" id="terms">
  <div class=" popup-inner2">
    <div class="popup-inner2-content padding">
      <h1>Terms & Conditions <span><i class="icon-remove hide-terms"></i></span></h1>
      <div class="terms scrollbar3">
        <div class="scrollbar">
          <div class="track">
            <div class="thumb">
              <div class="end"></div>
            </div>
          </div>
        </div>
        <div class="viewport">
          <div class="overview">
            <ol>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!----------------terms-popup------------------->
<div class="popup" style="display:none;" id="privacy">
  <div class=" popup-inner2">
    <div class="popup-inner2-content padding">
      <h1>Privacy Policy<span><i class="icon-remove hide-privacy"></i></span></h1>
      <div class="terms scrollbar3">
        <div class="scrollbar">
          <div class="track">
            <div class="thumb">
              <div class="end"></div>
            </div>
          </div>
        </div>
        <div class="viewport">
          <div class="overview">
            <ol>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </li>
              <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="header-top">

<?php

if(Session::has('user_id') && Session::get('user_id') > 0  ){

}else{
echo '<script>FBLogin();</script>';

}

?>
<input name="input_fb_id" id="input_fb_id" value="<?php echo Session::get('user_id'); ?>" type="hidden"  />
<input name="animal_id" id="animal_id" value="<?php echo (isset($_REQUEST['id']))?$_REQUEST['id']:""; ?>" type="hidden"  />
  <div class="app-wraper">
    <div class="logo"><a href="<?php echo Config::get('application.web_url');?>home"><img src="<?php echo Config::get('application.web_url');?>images/logo.png" /></a></div>
    <div class="nav">
      <ul>
        <li class="first-child"></li>
        <li><a href="<?php echo Config::get('application.web_url');?>browse">Browse Wildlife</a></li>
        <li><a href="#" onclick="InviteFriends();">Invite Friends</a></li>
        <li><a href="<?php echo Config::get('application.web_url');?>about-us">About</a></li>
        <li><a href="<?php echo Config::get('application.web_url');?>user-page?id=<?php echo (Session::has('user_id'))?Session::get('user_id'):""; ?>" id="user-page">
          <div class="user-pic" id="user-pic"><img id="fb-user-pic" src="http://graph.facebook.com/<?php echo (Session::has('user_id'))?Session::get('user_id'):""; ?>/picture?width=33&height=33" height="33px" width="33px" /></div>
         <span id="fb_user_name"> <?php echo (Session::has('user_id'))?Session::get('name'):""; ?></span></a></li>
        <!--<li class="last"><a href="javascript:logout();"><i class="icon-cog"></i></a></li>-->
        <li class="last-child"></li>
      </ul>
    </div>
  </div>
</div>
