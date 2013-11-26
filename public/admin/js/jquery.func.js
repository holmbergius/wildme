	$(function(){
		
	var dates = $( "#expiry" ).datepicker({
			defaultDate: "+1w",
			dateFormat: "yy-mm-dd",
			changeMonth: true,
			numberOfMonths: 1,
			onSelect: function( selectedDate ) {
				var option = this.id == "from" ? "minDate" : "maxDate",
					instance = $( this ).data( "datepicker" ),
					date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings );
				dates.not( this ).datepicker( "option", option, date );
			}
	});
		
//left nav pannel
	if($("body").attr("class")=="onpage-user") {
		$("#page-user").addClass("selected");
	}
	if($("body").attr("class")=="onpage-tournament") {
		$("#page-tournament").addClass("selected");
	}
    if($("body").attr("class")=="onpage-venue") {
        $("#page-venue").addClass("selected");
    }    
	if($("body").attr("class")=="onpage-player") {
		$("#page-player").addClass("selected");
	}	
	if($("body").attr("class")=="onpage-team") {
		$("#page-team").addClass("selected");
	}
	if($("body").attr("class")=="onpage-password") {
		$("#page-password").addClass("selected");
	}
	

});