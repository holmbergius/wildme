$(document).ready(function(e) {
    
    
//adopt form
$(".adopt-button").click(function(){
	 $("#adopt-form").show("fade", 600);
});

//Step 1
$("#adopt-btn1").click(function(){
	console.log('imran');
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

});
