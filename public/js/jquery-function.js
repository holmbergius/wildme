$(function(){  

	$(".slider-text h1").show("fade", { direction: "" }, 1000,function(){

	$(".slider-text p").show("fade", { direction: "" }, 1000,function(){
		$(".learn").show("fade", { direction: "" }, 800,function(){
			$(".slider-ani-pic").show("drop", { direction: "right" }, 1000, function() {
			
		});
	});
	});
});


//map Popup
/*$(".show-map-poup").click(function(){
	$(".map-poup").show("drop", {direction : "up" }, 400);
});*/	

$(".hide-map-poup").click(function(){
	$(".map-poup").hide("drop", {direction : "up" }, 400);
});


//pic Popup

/*$(".show-pic-poup").live('click',function(){
	$(".pic-poup").show("drop", {direction : "up" }, 400);
});*/

/*$(".show-pic-poup").click(function(){
	$(".pic-poup").show("drop", {direction : "up" }, 400);
	
});	*/

$(".hide-pic-poup").click(function(){
	$(".pic-poup").hide("drop", {direction : "up" }, 400);
});

/*$(".hide-pic-poup").click(function(){
	$(".pic-poup").hide("drop", {direction : "up" }, 400);
});
*/


//pic Popup
$(".show-activites-poup").click(function(){
	$(".activites-poup").show("drop", {direction : "up" }, 400);
	
});	

$(".hide-activites-poup").click(function(){
	$(".activites-poup").hide("drop", {direction : "up" }, 400);
});

$("#hide-activites-poup").click(function(){
	$("#popup-inner").hide("drop", {direction : "up" }, 400);
});


  // terms
$(".show-terms").click(function(){
	$("#terms").show("fade", {direction: "up"}, 500);
	 $('.scrollbar3').tinyscrollbar();
});	
	
$(".hide-terms").click(function(){
	$("#terms").hide("drop", {direction: "up"}, 300);
	
});	


  // privacy
$(".show-privacy").click(function(){
	$("#privacy").show("fade", {direction: "up"}, 500);
	 $('.scrollbar3').tinyscrollbar();
});	
	
$(".hide-privacy").click(function(){
	$("#privacy").hide("drop", {direction: "up"}, 300);
	
});	 

//Scroll to top
$(".scrollto").click(function(){
  $('html, body').animate({scrollTop: 0 }, 500); 
 });
 
 
 
//Team fan page 
//Tab 1
$(".show-team-tab1").click(function(){
  $("#team-tab1").show("fade", 600);
  $("#team-tab2, #team-tab3, #team-tab4, #team-tab5, #team-tab6, #team-tab7").hide();
  $("#tfp-1").addClass('selected');
  $("#tfp-2, #tfp-3, #tfp-4, #tfp-5 , #tfp-6 , #tfp-7").removeClass('selected');
});

//Tab 2
$(".show-team-tab2").click(function(){
  $("#team-tab2").show("fade", 600);
  $("#team-tab1, #team-tab3, #team-tab4, #team-tab4,#team-tab6, #team-tab7").hide();
  $("#tfp-2").addClass('selected');
  $("#tfp-1, #tfp-3, #tfp-4, #tfp-5 , #tfp-6 , #tfp-7").removeClass('selected');
});

//Tab 3
$(".show-team-tab3").click(function(){
  $("#team-tab3").show("fade", 600);
  $("#team-tab1, #team-tab2, #team-tab4, #team-tab5,#team-tab6, #team-tab7").hide();
  $("#tfp-3").addClass('selected');
  $("#tfp-2, #tfp-1, #tfp-4, #tfp-5 , #tfp-6 , #tfp-7").removeClass('selected');
});

//Tab 4
$(".show-team-tab4").click(function(){
  $("#team-tab4").show("fade", 600);
  $("#team-tab2, #team-tab3, #team-tab1, #team-tab5,#team-tab6, #team-tab7").hide();
  $("#tfp-4").addClass('selected');
  $("#tfp-2, #tfp-3, #tfp-1, #tfp-5 , #tfp-6 , #tfp-7").removeClass('selected');
});


//Tab 5
$(".show-team-tab5").click(function(){
  $("#team-tab5").show("fade", 600);
  $("#team-tab2, #team-tab3, #team-tab1, #team-tab4,#team-tab6, #team-tab7").hide();
  $("#tfp-5").addClass('selected');
  $("#tfp-2, #tfp-3, #tfp-1, #tfp-4 , #tfp-6 , #tfp-7").removeClass('selected');
});

//new functions


//Tab 6 stories
$(".show-team-tab6").click(function(){
  $("#team-tab6").show("fade", 600);
  $("#team-tab1, #team-tab1, #team-tab3, #team-tab4, #team-tab5 ,#team-tab7").hide();
  $("#tfp-6").addClass('selected');
  $("#tfp-1, #tfp-2, #tfp-3, #tfp-4 , #tfp-5 , #tfp-7").removeClass('selected');
});

//Tab 7 stories
$(".show-team-tab7").click(function(){
  $("#team-tab7").show("fade", 600);
  $("#team-tab1, #team-tab2, #team-tab3, #team-tab4, #team-tab5 ,#team-tab6").hide();
  $("#tfp-7").addClass('selected');
  $("#tfp-1, #tfp-2, #tfp-3, #tfp-4 , #tfp-5 , #tfp-6").removeClass('selected');
});


//Team fan page 
//Tab 1
$(".show-wild-tab1").click(function(){
  $("#tab1").show("fade", 600);
  $("#tab2").hide();
  $("#tf-1").addClass('selected');
  $("#tf-2").removeClass('selected');
});

//Tab 2
$(".show-wild-tab2").click(function(){
  $("#tab2").show("fade", 600);
  $("#tab1").hide();
  $("#tf-2").addClass('selected');
  $("#tf-1").removeClass('selected');
});

//$("#scrollable").scrollable({circular: true});

//$("#scrollable1").scrollable({circular: true});
$(".scrollable1").scrollable({circular: true});
$("#scrollable2").scrollable({circular: true});
$(".scrollable3").scrollable({circular: true});

 //$('.noSelect').disableTextSelect();//No text selection on elements with a class of 'noSelect'
}); 




 
