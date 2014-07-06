<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<title>Form</title>
<link href="css/style.css" rel="stylesheet" />
<script src="js/jquery.js"></script>
<script src="js/jquery-function.js"></script>

<script>

$(document).ready(function(e) {
	$("#adoption_button").click(function(e) {
        DisplayPaymentPopup();
    });
});

function DisplayPaymentPopup(){
	
  var icon_image 	= getCookie("wildme_icon");
  var animal_id  	= getCookie("wildme_animal_id");
  var animal_type   = getCookie("wildme_animal_type");
  var animal_price  = getCookie("wildme_animal_price");
  var animal_nick   = getCookie("wildme_animal_nick");
  var animal_qoute  = getCookie("wildme_animal_qoute");
	
var popupHtml = '<link rel="stylesheet" href="http://fb.wildme.org/wildme/public/adopt-form/css/style.css?timer=4" type="text/css"><div class="popup" style="display:block;" id="wildme_adopt-form">\
  <div class="adopt-form-wrapper">\
  <span class="cross-icon-adopt"><img src="images/cross-icon.png" width="22" height="22" alt="" /></span>\
    <div class="adopt-form-title">\
      <div class="adopt-form-logo"><img src="images/logo.png" width="158" height="53" alt="" /></div>\
    </div>\
    <div class="adopt-error-div"> Invalid card number. Please enter correct card number </div>\
    <div class="animal-code-strip">\
      <div class="animal-thumb"><img src="'+current_server+icon_image+'" width="39" height="39"  alt=""/></div>\
      <h2>('+animal_id+')<br />\
        <span>Type: '+animal_type+' </span> </h2>\
      <div class="animal-price-box"> <span>Price: $'+animal_price+'</span> </div>\
    </div>\
    <hr class="hori-line" />\
    <div class="adopt-slide" id="adopt-step1" style="display:blcok;">\
      <h3>Your adoption has the following benefits</h3>\
      <ul>\
        <li>Earns you a Champion Badge for your WildMe Profile.</li>\
        <li>Adoptions are valid for one year.</li>\
        <li>The recent activities of the animal that you adopted will be displayed\
          on your Facebook wall.</li>\
        <li>You are able to adopt multiple animal profiles.</li>\
        <li> Your adoption is a donation to Wild Me, a 501(c)(3) non-profit organization.</li>\
        <li>Your adoption is tax deductible in the United States.</li>\
        <li>The largest share of the proceeds will go to the research organization studying this animal.</li>\
      </ul>\
      <div id="adopt-btn1" class="adopt-outline-btn"><span class="adopti-spinner rotating"></span><a href="javascript:;">get started</a></div>\
    </div>\
    <div class="adopt-slide" id="adopt-step2" style="display:none;">\
      <div class="adopt-slide-inner">\
        <div class="adopt-field">\
          <input type="text" id="wildme_cc_name" placeholder="Card Holder Name"/>\
        </div>\
        <div class="adopt-field card-field">\
          <input type="text" id="wildme_cc_num" placeholder="Card Number"/>\
        </div>\
	<div class="adopt-field select half-field last" >\
<select>\
<option>Card Type</option>\
<option></option>\
<option></option>\
</select>\
        </div>\
        <div class="adopt-field half-field">\
          <input type="text" id="wildme_cc_month" placeholder="09"/>\
        </div>\
        <div class="adopt-field half-field">\
          <input type="text" id="wildme_cc_year" placeholder="2018"/>\
        </div>\
        <div class="adopt-field half-field last">\
        <div class="cvv-notificaiton">3 digit CCV code</div>\
         <i class="ques-icon"></i>\
          <input type="text" id="wildme_cc_ccv" placeholder="CVV"/>\
        </div>\
        <br clear="all" />\
      </div>\
      <div id="adopt-btn2" class="adopt-outline-btn margin-top"> <span id="wildme_payment_spinner" class="adopti-spinner rotating"></span> <a id="wildme_do_transaction" href="javascript:;">Continue</a> </div>\
    </div>\
    <div class="adopt-slide" id="adopt-step3" style="display:none;">\
      <div class="adopt-slide-inner">\
        <div class="adopt-successful-div">The payment process has successfully completed !</div>\
        <p class="simple-text">Congratulations! You have the opportunity to give a unique nickname to this animal. Pick a good one for us!</p>';
		

		if(animal_nick != 0 && animal_nick != null){
		popupHtml += '<div class="adopt-field">\
          <input type="text" id="" placeholder="Enter nick name"/>\
        </div>';
		}
		
		if(animal_qoute != 0 && animal_qoute != null){
			
        popupHtml += '<div class="adopt-field ">\
          <input type="text" id="" placeholder="Enter quote"/>\
		  </div>';
		}
		
      popupHtml += '</div>\
      <div id="adopt-btn3" class="adopt-outline-btn margin-top"> <span class="adopti-spinner rotating"></span> <a href="javascript:;">Submit</a> </div>\
    </div>\
    <div class="adopt-slide" id="adopt-step4" style="display:none;">\
      <div class="adopt-slide-inner">\
        <p class="adopt-thank-you">Thank you for adopting stumpy. You have been assigned a Champion badge for your WildMe profile. Share the news with your friends and ask them to earn their champoin badge as well.</p>\
        <div class="batch"><img src="images/batch.png" width="116" height="116" /></div>\
      </div>\
      <div id="adopt-btn4" class="adopt-outline-btn margin-top "> <span class="adopti-spinner rotating"></span> <a href="javascript:;" class="share-btn"><i></i>Share</a> </div>\
      <span class="close-text" id="close-adopt">Close</span>\
    </div>\
  </div>\
</div>';
$("#wildme-aboption-div").html(popupHtml);
InitializeJs();
}

function InitializeJs(){

//adopt form
$(".adopt-button").click(function(){
	 $("#adopt-form").show("fade", 600);
});

//Step 1
$("#adopt-btn1").click(function(){
	
  $("#adopt-step1").fadeOut(function(){$("#adopt-step2").fadeIn()});
});


//Step 2
$("#adopt-btn2").click(function(){
  $("#adopt-step2").fadeOut(function(){$("#adopt-step3").fadeIn()});
  ;
});

//Step 3
$("#adopt-btn3").click(function(){
  $("#adopt-step3").fadeOut(function(){$("#adopt-step4").fadeIn()});
  ;
});


//Step 3
$(".ques-icon").hover(function(){
  $(".cvv-notificaiton").toggleClass('active');
});


//close adopt form
$("#close-adopt").click(function(){
	 $("#adopt-form").hide("fade", 600);
});

}

function del_cookie(name) {
document.cookie = name +
'=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
} 

function setCookie(c_name,value,exdays)
{
var exdate=new Date();
exdate.setDate(exdate.getDate() + exdays);
var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
document.cookie=c_name + "=" + c_value;
}

function getCookie(c_name)
{
var i,x,y,ARRcookies=document.cookie.split(";");
for (i=0;i<ARRcookies.length;i++)
  {
  x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
  y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
  x=x.replace(/^\s+|\s+$/g,"");
  if (x==c_name)
    {
    return unescape(y);
    }
  }
}
</script>

</head>
<body>

<div class="popup" style="display:block;" id="adopt-form">
  <div class="adopt-form-wrapper">
  <span class="cross-icon-adopt"><img src="images/cross-icon.png" width="22" height="22" alt="" /></span>
    <div class="adopt-form-title">
      <div class="adopt-form-logo"><img src="images/logo.png" width="158" height="53" alt="" /></div>
    </div>
    <div class="adopt-error-div"> Invalid card number. Please enter correct card number </div>
    <div class="animal-code-strip">
      <div class="animal-thumb"><img src="images/animal-thumb.jpg" width="39" height="39"  alt=""/></div>
      <h2>(A-001) <br />
        <span>Type: Humpback Whale </span> </h2>
      <div class="animal-price-box"> <span>Price: $10</span> </div>
    </div>
    <hr class="hori-line" />
    
    <!--step 1-->
    
    <div class="adopt-slide" id="adopt-step1" style="display:blcok;">
      <h3>Your adoption has the following benefits</h3>
      <ul>
        <li>Earns you a Champion Badge for your WildMe Profile.</li>
        <li>Adoptions are valid for one year. </li>
        <li>The recent activities of the animal that you adopted will be displayed 
          on your Facebook wall.</li>
        <li>You are able to adopt multiple animal profiles.</li>
        <li> Your adoption is a donation to Wild Me, a 501(c)(3) non-profit organization.</li>
        <li>Your adoption is tax deductible in the United States.</li>
        <li>The largest share of the proceeds will go to the research organization studying this animal.</li>
      </ul>
      <div id="adopt-btn1" class="adopt-outline-btn"> <span class="adopti-spinner rotating"></span> <a href="javascript:;">get started</a> </div>
    </div>
    
    <!--step 2-->
    
    <div class="adopt-slide" id="adopt-step2" style="display:none;">
      <div class="adopt-slide-inner">
        <div class="adopt-field">
          <input type="text" id="" placeholder="Card Holder Name"/>
        </div>
        <div class="adopt-field card-field">
          <input type="text" id="" placeholder="Card Number"/>
        </div>
       <div class="adopt-field select half-field last" >
<select>
<option>Card Type</option>
<option></option>
<option></option>
</select>
        </div>
        <div class="adopt-field half-field">
          <input type="text" id="" placeholder="09"/>
        </div>
        <div class="adopt-field half-field">
          <input type="text" id="" placeholder="2018"/>
        </div>
        <div class="adopt-field half-field last">
        <div class="cvv-notificaiton">lorem ispum</div>
         <i class="ques-icon"></i>
          <input type="text" id="" placeholder="CVV"/>
        </div>
        <br clear="all" />
      </div>
      <div id="adopt-btn2" class="adopt-outline-btn margin-top"> <span class="adopti-spinner rotating"></span> <a href="javascript:;">Continue</a> </div>
    </div>
    
    <!--step 3-->
    
    <div class="adopt-slide" id="adopt-step3" style="display:none;">
      <div class="adopt-slide-inner">
        <div class="adopt-successful-div">The payment process has successfully completed !</div>
        <p class="simple-text">Congratulations! You have the opportunity to give a unique nickname to this animal. Pick a good one for us!</p>
        <div class="adopt-field">
          <input type="text" id="" placeholder="Enter nick name"/>
        </div>
        <div class="adopt-field ">
          <input type="text" id="" placeholder="Enter quote"/>
        </div>
      </div>
      <div id="adopt-btn3" class="adopt-outline-btn margin-top"> <span class="adopti-spinner rotating"></span> <a href="javascript:;">Submit</a> </div>
    </div>
    
    <!--step 4-->
    
    <div class="adopt-slide" id="adopt-step4" style="display:none;">
      <div class="adopt-slide-inner">
        <p class="adopt-thank-you">Thank you for adopting stumpy. You have been assigned a Champion badge for your WildMe profile. Share the news with your friends and ask them to earn their champoin badge as well.</p>
        <div class="batch"><img src="images/batch.png" width="116" height="116" /></div>
      </div>
      <div id="adopt-btn4" class="adopt-outline-btn margin-top "> <span class="adopti-spinner rotating"></span> <a href="javascript:;" class="share-btn"><i></i>Share</a> </div>
      <span class="close-text" id="close-adopt">Close</span>
    </div>
  </div>
</div>

</body>
</html>
