$(function(){  
//form	
 /*$(".show-form").click(function(){
	$("#form").show("drop", {direction: "up"}, 500);
 });	
	$(".hide-form").click(function(){
	$("#form").hide("drop", {direction: "up"}, 500);
 });*/
	
	$('.scrollbar2').tinyscrollbar();
/**********Add team popup******/	
	
 

if($("body").attr("class")=="onpage-user") {
		$("#page-user").addClass("selected");
	}
	if($("body").attr("class")=="onpage-category") {
		$("#page-category").addClass("selected");
	}
   if($("body").attr("class")=="onpage-artist") {
		$("#page-artist").addClass("selected");
	}
	
	
	if($("body").attr("class")=="onpage-password") {
		$("#page-password").addClass("selected");
	}
	if($("body").attr("class")=="onpage-adopters") {
		$("#page-adopters").addClass("selected");
	}
	if($("body").attr("class")=="onpage-song") {
		$("#page-song").addClass("selected");
	}
	
	if($("body").attr("class")=="onpage-playlist") {
		$("#page-playlist").addClass("selected");
	}
	if($("body").attr("class")=="onpage-order") {
		$("#page-order").addClass("selected");
	}
	if($("body").attr("class")=="onpage-setting") {
		$("#page-setting").addClass("selected");
	}
	if($("body").attr("class")=="onpage-genre") {
		$("#page-genre").addClass("selected");
	}
	if($("body").attr("class")=="onpage-dashboard") {
		$("#page-dashboard").addClass("selected");
	}
 	if($("body").attr("class")=="onpage-revenue") {
		$("#page-revenue").addClass("selected");
	}
	if($("body").attr("class")=="onpage-individuals") {
		$("#page-individual").addClass("selected");
	}
	if($("body").attr("class")=="onpage-report_abuse") {
		$("#page-reports").addClass("selected");
	}
	
	if($("body").attr("class")=="onpage-reminder") {
		$("#page-reminder").addClass("selected");
	}
	if($("body").attr("class")=="onpage-report_abuse") {
		$("#page-reports").addClass("selected");
	}
	
});	 
	
$(document).ready(function(){

$('#checkbox').click(function(event) {   
    if(this.checked) {
    
        // Iterate each checkbox
        $(':checkbox').each(function() {
            this.checked = true;                        
        });
    }
    else
    {
		$(':checkbox').each(function() {
            this.checked = false;                        
        });

    }
	});

/*$('.numbers_only').bind('keyup blur',function()
{
	$(this).removeClass('invalid');
	$(this).val( $(this).val().replace(/[^0-9]/g,'') );
});*/

});

function showTab(curObj, div_id)
{
	$('.tab_ul li').removeClass('selected');
	$(curObj).addClass('selected');
	$('.step').hide();
	$('.step').hide();
	$(div_id).show();
	$('.scrollbar2').tinyscrollbar(); 
}

