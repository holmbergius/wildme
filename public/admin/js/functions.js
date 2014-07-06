function SubmitAdminLogin(e)
{
   // look for window.event in case event isn't passed in
   if (window.event) { e = window.event; }
   if ((e.keyCode == 13) || (e.type == 'click'))
   {
		AdminLogin();
   }
}
var chartData = [];
var chart;

function GetUrlParms(name)
{
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( window.location.href );
  if( results == null )
    return "";
  else
    return results[1];
}

function Search(e,type)
{
   // look for window.event in case event isn't passed in
   if (window.event) { e = window.event; }
   if ((e.keyCode == 13) || (e.type == 'click'))
   {
		if (type=='user')
		{
			GetUser(0);
		}
		if(type == 'category')
		{
			GetCategory(0);	
		}
		if(type == 'individual')
		{
			GetIndividuals(0);	
		}
		if(type == 'transactions')
		{
			GetAdoptersHistory(0);	
		}
   }
}

function isEmail(em_address)  
{ 
	var email=/^[A-Za-z0-9]+([_\.-][A-Za-z0-9]+)*@[A-Za-z0-9]+([_\.-][A-Za-z0-9]+)*\.([A-Za-z]){2,4}$/i; 
	return (email.test(em_address)) 
}

String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

function inArrayCaseInsensitive(needle, haystackArray){
      //Iterates over an array of items to return the index of the first item that matches the provided val ('needle') in a case-insensitive way.  Returns -1 if no match found.
      var defaultResult = -1;
      var result = defaultResult;
      $.each(haystackArray, function(index, value) { 
          if (result == defaultResult && value.toLowerCase() == needle.toLowerCase()) {
              result = index;
          }
      });
      return result;
  }
  
  $(function(){ 
	$('.numbers_only2').bind('keyup blur',function(e){ 
	  $(this).removeClass('invalid');
	 
			var RE = /^\d*\.?\d{0,2}$/;
			if(RE.test($(this).val())){
			}else{
	//				$(this).val($(this).val().replace(/.$/g,'') );
					$(this).val($(this).val().replace(/[^0-9,.]/g,'') );
			}
	   }
	);
});	

function Paging(listing_type, start_limit,page,total_records,limit)
{
	var midNumbers 			=	4; //midNumbers number left right from the page , lets   1 2 3   4    1 2 3		 
	var pagging				= new Array(); 
	pagging['pagging']		= "";	
	pagging['t']			= "";
	pagging['sql']			= "";
	
	pagging['Start']		= "";
	pagging['Preveious']	= "";
	pagging['Mid']			= "";
	pagging['MidTemp']		= "";	
	pagging['Next']		= "";
	pagging['End']			= "";

	
	if(total_records>0)	
	{	
		var aNextImage				= 'Next';//Active next image
		var NextImage				= 'Next';
		
		var aPrevImage				= 'Previous';	
		var PrevImage				= 'Previous';
		
		//var pageprev = 0;
		
	    limitvalue = (page - 1) * limit;
	    numofpages = Math.ceil(total_records/limit);
		if (page==0) page = 1;	
		//pagging['t']		 =  'Pages '+(start_limit+1)+' - '+(start_limit+limit)+'<li> <ul class="pagination light">';
			
	    if(page > 1)
		{
	     pageprev = page-1;
		 pagging['Preveious']	+= "<a onclick=\"LoadPaging('"+listing_type+"',"+pageprev+");\" style='cursor:pointer'><li class='prev' style='margin-right:10px;'> <i class='icon-chevron-left'></i> "+aPrevImage+"</li></a>";
	    }
	    else
	    {
	    left_link = "&nbsp;";
		 pagging['Preveious']	+= '';
	    }

		
		pagging['pagging'] 	+= '';	
		for(i=1;i<=numofpages;i++)
	    {
		   if(i > page-midNumbers && i < page+midNumbers)
		   {
				   
				if(page == i)
				 {
				 pagging['MidTemp']  = '<li class="selected" style="margin-left:10px;">'+(i+"&nbsp;")+'</li>';
				 pagging['Mid'] 	+= pagging['MidTemp'];
				 }
				 else
				 {
				  pagging['MidTemp']  = "<a onclick=\"LoadPaging('"+listing_type+"',"+i+");\" style='cursor:pointer;'> <li style='margin-left:10px;'><div class='page-normal'>"+i+"</div></li></a>";
				  pagging['Mid'] 	 += pagging['MidTemp'];
				 }
				 
		   }
	    }   
		pagging['pagging'] 	+= '';
		
	    
		if(page < numofpages)
		{
	    pagenext = (page + 1);
		pagging['Next'] += "<a onclick=\"LoadPaging('"+listing_type+"',"+pagenext+");\" style='cursor:pointer'><li class='next'  style='margin-left:10px;'>"+aNextImage+" <i class='icon-chevron-right'></i></li></a>";
	    }
	    else
	    {
		pagging['Next'] += "";
	    }   

		pagging['pagging']		   +=  pagging['t'];
		pagging['pagging'] 	  	   += pagging['Preveious'];
		pagging['pagging']  	   += ''+pagging['Mid']+'';
		pagging['pagging']  	   += pagging['Next'];
	}

	if (total_records<limit)
	{
		//document.getElementById("Paging").style.display = "none";
	}
 
    return pagging['pagging'];
}

function LoadPaging(listing_type,page)
{
	if (listing_type=='CategoryListing')
	{
		GetCategory(page);
	}
	if (listing_type=='UserListing')
	{
		GetUser(page);
	}
	
	if (listing_type=='ReminderListing')
	{
		GetReminders(page);
	}
	
	if (listing_type=='IndividualListing')
	{
		GetIndividuals(page);
	}
	
	if (listing_type=='AdopterListing')
	{
		GetAdopters(page);
	}

	if (listing_type=='ReportsListing')
	{
		GetReports(page);
	}
}

$(function(){ 

if(sPage == 'category' || sPage == '')
{
	GetCategory(0);	
	if(GetUrlParms("add"))
	{
		$(document).ready(function() {
			$('div#Message-success').show();
			$('div#Message-success').html('Category has been added successfully!').fadeIn(1000).delay(2000).fadeOut(1000);
		});
	}
	if(GetUrlParms("update"))
	{
		$(document).ready(function() {
			$('div#Message-success').show();
			$('div#Message-success').html('Category has been updated successfully!').fadeIn(1000).delay(2000).fadeOut(1000);
		});
	}
}

if(sPage == 'user' )
{
	//var uid = GetUrlParms("uid");
	//if(uid > 0)
	
		GetUser(0);
}

if(sPage == 'reminders' )
{
	//var uid = GetUrlParms("uid");
	//if(uid > 0)
	
		GetReminders(0);
}


if(sPage == 'individuals' )
{
	//var uid = GetUrlParms("uid");
	//if(uid > 0)
	
		GetIndividuals(0);
}

if(sPage == 'adopters' )
{
	//var uid = GetUrlParms("uid");
	//if(uid > 0)
	
		GetAdopters(0);
}
if(sPage == 'rhistory' )
{
	//var uid = GetUrlParms("uid");
	//if(uid > 0)
	
		GetAdoptersHistory(0);
}
if(sPage == 'report_abuse' )
{
	//var uid = GetUrlParms("uid");
	//if(uid > 0)
	
		GetReports(0);
}


});


/******************************************************************************************************
-----------------------------------Functions Search Start
*******************************************************************************************************/
function SubmitCategorySearch(e)
{
   // look for window.event in case event isn't passed in
   if (window.event) { e = window.event; }
   if ((e.keyCode == 13) || (e.type == 'click'))
   {
		GetCategory(0);
   }
}



function SubmitUserSearch(e)
{
   // look for window.event in case event isn't passed in
   if (window.event) { e = window.event; }
   if ((e.keyCode == 13) || (e.type == 'click'))
   {
		GetUser(0);
   }
}

function SubmitIndividualSearch(e)
{
   // look for window.event in case event isn't passed in
   if (window.event) { e = window.event; }
   if ((e.keyCode == 13) || (e.type == 'click'))
   {
		GetIndividuals(0);
   }
}



/******************************************************************************************************
-----------------------------------Functions Admin Start
*******************************************************************************************************/

/**********************************admin login*************************************/
function AdminLogin()
{
	document.getElementById("username").className 			= "";
	document.getElementById("password").className 			= "";
	document.getElementById("loginError").style.display 	= "none";
	document.getElementById("loginSuccess").style.display 	= "none";
	
	var username = document.getElementById("username").value;
	var password = document.getElementById("password").value;

	if (username=='' || username == 'Enter Username')
	{
		document.getElementById("username").className 	= "invalid_text";
		document.getElementById("username").focus();
		return false;
	}
	else if (password=='' || password == 'Enter Password')
	{
		document.getElementById("password").className 	= "invalid_text";
		document.getElementById("password").focus();
		return false;
	}
	else
	{
		
		$("#Authorize").show();
		$.get(API_URL+"admin", {
			'username': username, 
			'password': password},
			 function(data){
			
			$("#Authorize").hide();
			if (data.status=='error')
			{
				document.getElementById("loginError").style.display   = "block";
				document.getElementById("loginSuccess").style.display = "none";
				document.getElementById("username").focus();
			}
			else if (data.status=='success')
			{
				document.getElementById("loginError").style.display   = "none";
				document.getElementById("loginSuccess").style.display = "block";
				window.location.href	=	AdminUrl+"dashboard";
			}		
		}, "json");
	}
}

/*******************************admin change password******************************/

function UpdatePassword()
{
  $("#AccountMessage-error").hide();
  $("#AccountMessage-success").hide();
  
  var password       = $("#oldpassword").val();
  var newpassword   = $("#newpassword").val();
  var confirmpassword  = $("#confirmpassword").val();
  var admin_id    = $("#admin_id").val();
  $("#oldpassword").css("border-color","");
  $("#newpassword").css("border-color","");
  $("#confirmpassword").css("border-color","");
  
  if (password == '')
  {
       $("#oldpassword").focus();
    $("#oldpassword").css("border-color","red");
    return false;
  }
  else if (newpassword == '')
  {
    document.getElementById("newpassword").focus();
    $("#newpassword").css("border-color","red");
    return false;
  }
  else if (confirmpassword == '' || confirmpassword != newpassword)
  {
    document.getElementById("newpassword").focus();
    $("#newpassword").css("border-color","red");
    document.getElementById("confirmpassword").focus();
    $("#confirmpassword").css("border-color","red");
    return false;
  }
  else
  {
   
   $("#Authorize").show();
  
     $.ajax({
  dataType: "json",
  url: API_URL+'admin',
  data:{admin_id:admin_id,password: password, newpassword: newpassword},
  type: 'PUT',
  success: function(response) {
   
      $("#Authorize").hide();
     $("#oldpassword").val('');
     $("#newpassword").val('');
     $("#confirmpassword").val('');
   
    if(response.status == 'success')
    {
      
     $("#AccountMessage-success").fadeIn(800).delay(1500).fadeOut(800);
    }
    else
    {
     
     $("#AccountMessage-error").fadeIn(800).delay(1500).fadeOut(800);
    }
    }
    });
  
    } 
}
/*********************************admin Logout*************************************/
function adminLogout()
{
	$.ajax({
      async:true, 
      dataType:'json', 
      type:'post', 
      url:"../../public/api/admin",
      success: function(response)
	  	{ 
      
			var status = response;
			if(status.status == 'success')
		  	 {
		   		window.location.href=AdminUrl+'login';
		   	 }
      	}
    }); 
}

/******************************************************************************************************
-----------------------------------Functions User Start
*******************************************************************************************************/

/*******************************Get User******************************/
function IsbanUser(id,value,page){
$.ajax({
	      async:true, 
	      data: {"_method":"post",'id':id,'ban_status':value},
	      dataType:'json', 
	      type:'post', 
	      url:API_URL+"update_banuser",
	      success: function(response)
		  	{
				GetUser(page);
				
	      	}
    	}); 
}
function GetUser(page)
{
	var limit		= 10;
	var start_limit	= (page-1)*limit;
		start_limit	= (start_limit<0) ? 0 : start_limit;
	var keyword = $("#search").val();
	if (keyword=='') keyword = '';
	
	var sortBy = $("#sortBy").val();
	var orderBy = 'DESC';
	if (sortBy=='') { sortBy = 'id'; orderBy = 'ASC';}
	if (sortBy=='name_asc') { sortBy = 'name'; orderBy = 'ASC';}
	if (sortBy=='name_desc') { sortBy = 'name'; orderBy = 'DESC';}
	
	$("#user_rows").html('<div style="position: absolute; top: 20px; left: 252px;"><img src="../admin/images/spinner.gif" /></div>').show();
	var resultDiv = '';
	var totalrecords,currentLength,user,reg_type;
	//var date_join;
	$.get(API_URL+"user", {
		'offset':start_limit,
		'limit':limit,
		'sortby':sortBy,
		'orderby':orderBy,
		'keyword': keyword }, 
		function(data){
		
		if (data.totalrecords>0)
		{
			totalrecords 	= data.totalrecords;
			user 			= data.records;
			currentLength   = user.length;
			var setclass 	= '';
			var above_age 	= '';
			
			for (var i=0;i<currentLength;i++)
			{
				setclass 	 = 'gray';
				if (i%2==0) {setclass = 'gray'; }
				else { setclass = ''; }
				
				if(user[i].email == null )
				{
					user[i].email = '';
				}
				badge_display  = '';
				is_ban  = '';
				
				if (user[i].adoptor_badge=='Yes'){
					 
					 badge_display ='<img style="width: 30px; height: 30px; float: left; cursor: pointer; margin-left: 22px;" src="../images/batch.png"><img src="../images/cross.png" onclick="updateBadge('+user[i].id+',\'No\');" style="width:20px; height:20px; margin-left:-2; margin-top:-12px; cursor:pointer;"/>'; 
					 }else{
					 
					 badge_display ='<a style="cursor:pointer" id="badge'+user[i].id+'" onclick="updateBadge('+user[i].id+',\'Yes\');">Add Badge</a>';
				 }
				 
				 /*if(reports[i]['commenter_data']['record']['is_ban'] == 'Yes'){

				user_status ='<a style="cursor:pointer" onclick="banUser('+reports[i].comment_id+',\'No\','+page+');">Unban User</a>'; 
			      }else{
			      
			    user_status ='<a style="cursor:pointer" onclick="banUser('+reports[i].comment_id+',\'Yes\','+page+');">Ban User</a>';
			    }*/
				if (user[i].is_ban=='Yes'){
					
					 is_ban = '<a style="cursor:pointer" onclick="IsbanUser('+user[i].id+',\'No\','+page+');">Unban User</a>';
					 
					 }else{
					 
					 is_ban = '<a style="cursor:pointer" onclick="IsbanUser('+user[i].id+',\'Yes\','+page+');">Ban User</a>';
				 }
				 
				
				resultDiv +='<tr class="'+setclass+'"><td align="center" class="image"><img src="http://graph.facebook.com/'+user[i].id+'/picture?width=45&height=45" ></td><td class="width-name" id="cus_name'+user[i].id+'">'+user[i].name+'</td><td class="width-name" id="cus_email'+user[i].id+'">'+user[i].email+'</td><td class="width-name" id="cus_gender'+user[i].id+'">'+user[i].gender.capitalize()+'</td><td class="width-name" id="cus_date_added'+user[i].id+'">'+user[i].date_added+'</td><td class="width-name" id="'+user[i].id+'">'+badge_display +' | '+is_ban+'</td></tr>';
				
			}
			
			document.getElementById("Paging").innerHTML = Paging('UserListing',start_limit,page,totalrecords,limit);
		}
		else
		{
			resultDiv = '<tr><td colspan="9" valign="top"><div style="text-align:center"><h2>No user found.</h2></div></td></tr>';			
		}
		
	$("#user_rows").html(resultDiv);
	$("#TotalUser").html(data.totalrecords);
		

	}, "json");
}

/*******************************Update badge******************************/

function updateBadge(id, adoptor_badge)
{
	//$("#badge"+id).show();
	$.post(API_URL+"update_badge", {'id': id, 'adoptor_badge': adoptor_badge}, function(data){	
	//$("#actionSpinner"+client_id).hide();	
	if (data.status =='success')
	{
		GetUser(0);
		/*$("#badge"+id).html('<img src="../images/badge.png" style="width:30px; height:30px; float:left;"/><a style="cursor:pointer" onclick="updateBadge('+id+',\'No\');"><img src="../images/cross.png" style="width:20px; height:20px; margin-left:-2; margin-top:-8px;"/></a>');*/
		
		
	}
	else
	{
		// Show error div
	}
	
	}, "json");
}

/******************************************************************************************************
-----------------------------------Functions Reminder
*******************************************************************************************************/

/*******************************Get Reminders******************************/

function GetReminders(page)
{
	var limit		= 10;
	var start_limit	= (page-1)*limit;
		start_limit	= (start_limit<0) ? 0 : start_limit;
	var keyword = $("#search").val();
	if (keyword=='') keyword = '';
	
	var sortBy = $("#sortBy").val();
	var orderBy = 'DESC';
	
	$("#reminder_rows").html('<div style="position: absolute; top: 20px; left: 252px;"><img src="../admin/images/spinner.gif" /></div>').show();
	var resultDiv = '';
	var totalrecords,currentLength,reminders;
	//var date_join;
	$.get(API_URL+"reminders", {
		'offset':start_limit,
		'limit':limit,
		'sortby':sortBy,
		'orderby':orderBy,
		'keyword': keyword }, 
		function(data){
		var reminders='';
		if (data.totalrecords>0)
		{
			totalrecords 	= data.totalrecords;
			reminders 		= data.records;
			currentLength   = reminders.length;
			var setclass 	= '';
			var above_age 	= '';
			
			for (var i=0;i<currentLength;i++)
			{
				setclass 	 = 'gray';
				if (i%2==0) {setclass = 'gray'; }
				else { setclass = ''; }

				resultDiv +='<tr class="'+setclass+'"><td align="center" class="first bo">'+reminders[i].id+'</td><td class="width-name">'+reminders[i].title+'</td><td class="width-name">'+reminders[i].interval+'&nbsp;'+reminders[i].period+'</td><td width="14%"><select name="reminderoptions'+reminders[i].id+'" id="reminderoptions'+reminders[i].id+'" class="sel" style="width:95px;"><option value="edit_reminder">Edit</option><option value="delete_reminder">Delete</option></select><i class="icon-arrow-right" title="Apply" onclick="ApplyReminderOptions('+reminders[i].id+');"></i></td></tr>';


			}
			
			document.getElementById("Paging").innerHTML = Paging('ReminderListing',start_limit,page,totalrecords,limit);
		}
		else
		{
			resultDiv = '<tr><td colspan="4" valign="top"><div style="text-align:center"><h2>No reminder found.</h2></div></td></tr>';			
		}
		
	$("#reminder_rows").html(resultDiv);
	$("#TotalReminders").html(data.totalrecords) ;
		

	}, "json");
}

/*******************************delete Reminder******************************/
function DeleteReminder(id,page)
{
	$.ajax({
      async:true, 
      data: {"_method":"delete",'id':id},
      dataType:'json', 
      type:'post', 
      url:API_URL+"reminder",
      success: function(response)
	  	{ 
       		if (response.status == 'success')
			{
  		   	    $('#Message-success').html('Reminder has been deleted successfully').fadeIn(1000).delay(2000).fadeOut(1000);
				
  		   	  
			}
			else
			{
				$('#Message-error').fadeIn(1000).delay(2000).fadeOut(1000);
			}
			
			GetReminders(page);
      	}
    }); 

}

/***************************************** Add Reminder ****************************************************/

function AddReminder(id)
{

	var title 		= 	$("#title").val();
	var interval 	= 	$("#interval").val();
	var period 		= 	$("#period").val();
	var template 	= 	$("#template").val();
		
	$('#title').css('border-color','#CCCCCC');	
	$('#template').css('border-color','#CCCCCC');
	$('#interval').css('border-color','#CCCCCC');	
	if(title == '')
	 {
	 	//$('#clientName').focus();
	  	$('#title').css('border-color','red');
	 }
	
	else if(interval == '')
	 {
	 	//$('#clientName').focus();
	  	$('#interval').css('border-color','red');
	 }
	
	else	if(template == '')
	 {
	 	//$('#clientName').focus();
	  	$('#template').css('border-color','red');
	 }
	
  else
 	 {
	  if(id == 0)	// Add case
	  {

		$.post(API_URL+"add_reminder", {title:title, interval:interval, period:period, template:template}, function(data){	
			
	   if (data.status=='success')
		{
			$("#reminder_spinner").show();
			 $("html, body").animate({ scrollTop: 0 }, 1000);
			 $("#Message-success").html('Reminder Added Successfully').fadeIn('fast').delay(1000).fadeOut(1000, function(){ 
			  document.location.href='reminders';

		 });
		   
		}
		else if( data=='error')   
		{
		 	$("#Message-error").html('Error occured while adding remindert').fadeIn('fast').delay(1000).fadeOut(1000)
		}}, "json");
	  }
	  else		// Edit case
	  {
	  		
		  $.post(API_URL+"update_reminder", {id:id,title:title, interval:interval, period:period, template:template}, function(data){	
	   if (data.status=='success')
		{
			 $("#reminder_spinner").show();
			 $("html, body").animate({ scrollTop: 0 }, 1000);
			 $("#Message-success").html('Update Successfully').fadeIn('fast').delay(1000).fadeOut(1000, function(){ 
			 document.location.href='reminders';
		 });
		   
		}
		else if( data.status=='error')   
		{
		 	$("#Message-error").html('Error occured while updating remindert').fadeIn('fast').delay(1000).fadeOut(1000)
		}}, "json");
	  }
	  
	}//end else 
	

}


/***************************************** Get Single Reminder ****************************************************/

function getSingleReminder(id)
{
		
		$.get(API_URL+"reminder", { id:id } , 
		function(data){
			
			var reminder = data.record;
		
			if(id != '' || id == 0)
			{
				
				$('#title').val(reminder.title);
				$('#interval').val(reminder.interval);
				$('#period').val(reminder.period);			
				$('#template').val(reminder.template);
				
				
			}
						
		},"json");
}	
/*******************************Apply reminder options******************************/

function ApplyReminderOptions(id)
{
	var selected_option = $("#reminderoptions"+id).val();
	if (selected_option=='edit_reminder')
	{
		window.location = 'addReminder?id='+id+'';
	}
	else if (selected_option=='delete_reminder')
	{
		DeleteReminder(id,0);	
	}
}


/******************************************************************************************************
-----------------------------------
Functions Adopters
*******************************************************************************************************/

/*******************************Get Adopters******************************/

function GetAdopters(page)
{
	var limit		= 50;
	var start_limit	= (page-1)*limit;
		start_limit	= (start_limit<0) ? 0 : start_limit;
	var keyword = $("#search").val();
	if (keyword=='') keyword = '';
	
	var sortBy = $("#sortBy").val();
	var orderBy = 'DESC';
	var getSortType = 'application';
	if(sPage == 'adopters'){

		getSortType = $("#getSortType").val();
	}
	
	$("#adopter_rows").html('<div style="position: absolute; top: 20px; left: 200px;"><img src="../admin/images/spinner.gif" /></div>').show();
	var resultDiv = '';
	var totalrecords,currentLength,adopters;
	//var date_join;
	$.get(API_URL+"adoptors", {
		'offset':start_limit,
		'limit':limit,
		'sortby':sortBy,
		'orderby':orderBy,
		'sort_type':getSortType,
		'keyword': keyword }, 
		function(data){
		var reminders='';
		var label ='';
		if (data.totalrecords>0)
		{
			totalrecords 	= data.totalrecords;
			adopters 		= data.records;
			currentLength   = adopters.length;
			var setclass 	= '';
			var above_age 	= '';
			
			for (var i=0;i<currentLength;i++)
			{
				
				setclass 	 = 'gray';
				if (i%2==0) {setclass = 'gray'; }
				else { setclass = ''; }
				
				if(adopters[i].nick_name== '') { adopters[i].nick_name='-';}
				if(adopters[i].quote== '') { adopters[i].quote='-';}

				if(adopters[i].animal_data.record != null)
				{
					label =  adopters[i].animal_data.record.label;
				}
				
				if(getSortType == 'website'){
				
				resultDiv +='<tr class="'+setclass+'"><td align="left" class="first bo">'+adopters[i].id+'</td><td align="left" class="image"><img src="../images/user@2x.jpg"  style="float:left;"><p style="margin-top:13px;text-align:left;">&nbsp;&nbsp;'+adopters[i].name+'</p></td><td class="width-name">'+label+'</td><td class="width-name">'+adopters[i].date_added+'</td><td class="width-name">'+adopters[i].nick_name+'</td><td class="width-name">'+adopters[i].quote+'</td></tr>';
				
				
				}else{
					
				resultDiv +='<tr class="'+setclass+'"><td align="left" class="first bo">'+adopters[i].id+'</td><td align="left" class="image"><img src="http://graph.facebook.com/'+adopters[i].uid+'/picture?width=45&height=45"  style="float:left;"><p style="margin-top:13px;text-align:left;">&nbsp;&nbsp;'+adopters[i]['user_data']['record']['name']+'</p></td><td class="width-name">'+label+'</td><td class="width-name">'+adopters[i].date_added+'</td><td class="width-name">'+adopters[i].nick_name+'</td><td class="width-name">'+adopters[i].quote+'</td></tr>';
				
				}
			}
			
			document.getElementById("Paging").innerHTML = Paging('AdopterListing',start_limit,page,totalrecords,limit);
		}
		else 
		{
			//$("#adopter_rows").html('<div style="position: absolute; top: 20px; left: 200px;"><img src="../admin/images/spinner.gif" /></div>').hide();
			resultDiv = '<tr><td colspan="6" valign="top"><div style="text-align:center"><h2>No adopter found.</h2></div></td></tr>';			
		}
		
	$("#adopter_rows").html(resultDiv);
	$("#TotalAdopters").html(data.totalrecords) ;
		

	}, "json");
}
function GetOptions(){
	
	var adv_serch = $("#adv_search").val();
	
	if(adv_serch == 'name'){
		
		$("#date_range_div").hide();
		$("#category_search_div").hide();
		$("#user_type_div").hide();
		$("#name_search_div").fadeIn();
	
	}else if(adv_serch == 'date_range'){
		
		$("#name_search_div").hide();
		$("#category_search_div").hide();
		$("#user_type_div").hide();
		$("#date_range_div").fadeIn();
		
	}else if(adv_serch == 'category'){
		
		$("#name_search_div").hide();
		$("#date_range_div").hide();
		$("#user_type_div").hide();
		$("#category_search_div").fadeIn();
		
	}else if(adv_serch == 'user_type'){
		
		$("#name_search_div").hide();
		$("#date_range_div").hide();
		$("#category_search_div").hide();
		$("#user_type_div").fadeIn();
		
	}else{}
}

function GetAdoptersHistory(page)
{
	var limit		= 50;
	var start_limit	= (page-1)*limit;
		start_limit	= (start_limit<0) ? 0 : start_limit;
	var keyword = $("#search").val();
	if (keyword=='') keyword = '';
	
	var sortBy = $("#sortBy").val();
	var orderBy = 'DESC';

	var getSortType = 'application';
	if(sPage == 'rhistory'){

		getSortType = $("#getSortTypehistory").val();
	}
	
	var cat_id = $("#getCategoryList").val();
	
	var date1 = $("#date1").val();
	var date2 = $("#date2").val();
	var adv_search = $("#adv_search").val();
	
	$("#adopter_rows").html('<div style="position: absolute; top: 13px; left: 250px;"><img src="../admin/images/spinner.gif" /></div>').show();
	var resultDiv = '';
	var totalrecords,currentLength,adopters;
	//var date_join;
	$.get(API_URL+"adoptors", {
		'offset':start_limit,
		'limit':limit,
		'sortby':sortBy,
		'orderby':orderBy,
		'keyword': keyword ,
		'category_id': cat_id ,
		'sort_type':getSortType,
		'date1': date1 ,
		'date2': date2 ,

		'adv_search': adv_search}, 
		function(data){
		var reminders='';
		
		if (data.totalrecords>0)
		{
			totalrecords 	= data.totalrecords;
			adopters 		= data.records;
			currentLength   = adopters.length;
			var setclass 	= '';
			var above_age 	= '';
			var label = '';
			var nickname ='';

			//alert(adopters[0].animal_data.record.label);
			
			for (var i=0;i<currentLength;i++)
			{
				setclass 	 = 'gray';
				if (i%2==0) {setclass = 'gray'; }
				else { setclass = ''; }
				
				if(adopters[i].nick_name== '') { nickname =' ';}
				else{ nickname = '('+adopters[i].nick_name+')'; }
				if(adopters[i].quote== '') { adopters[i].quote='-';}

				if(adopters[i].animal_data.record != null)
				{
					label =  adopters[i].animal_data.record.label;
				}

				if(getSortType == 'website'){

					resultDiv +='<tr class="'+setclass+'"><td align="center" class="first bo">'+adopters[i].id+'</td><td align="left" class="image"><img src="../images/user@2x.jpg"  style="float:left;"><p style="margin-top:13px;text-align:left;">&nbsp;&nbsp;'+adopters[i].name+'</p></td><td class="width-name">'+label+'&nbsp; '+nickname+'</td><td class="width-name">$'+adopters[i].amount+'</td><td class="width-name">'+adopters[i].date_added+'</td></tr>';

				}
				else{
					resultDiv +='<tr class="'+setclass+'"><td align="center" class="first bo">'+adopters[i].id+'</td><td align="left" class="image"><img src="http://graph.facebook.com/'+adopters[i].uid+'/picture?width=45&height=45" style="float:left;"><p style="margin-top:13px;text-align:left;">&nbsp;&nbsp;'+adopters[i]['user_data']['record']['name']+'</p></td><td class="width-name">'+label+'&nbsp; ('+adopters[i].nick_name+')</td><td class="width-name">$'+adopters[i].amount+'</td><td class="width-name">'+adopters[i].date_added+'</td></tr>';
				}
			}
			
			document.getElementById("Paging").innerHTML = Paging('AdopterListing',start_limit,page,totalrecords,limit);
		}
		else 
		{
			//$("#adopter_rows").html('<div style="position: absolute; top: 20px; left: 200px;"><img src="../admin/images/spinner.gif" /></div>').hide();
			resultDiv = '<tr><td colspan="6" valign="top"><div style="text-align:center"><h2>No adopter found.</h2></div></td></tr>';			
		}
	$("#adopter_rows").html(resultDiv);
	$("#TotalAdopters").html(data.totalrecords) ;
	$("#total_amount").html("Total Amount :  $"+data.totalamount) ;	

	}, "json");
}


/******************************************************************************************************
-----------------------------------Functions Marked Individuals
*******************************************************************************************************/

/*******************************Get Individuals******************************/

function GetIndividuals(page)
{
	var limit		= 100;
	var start_limit	= (page-1)*limit;
		start_limit	= (start_limit<0) ? 0 : start_limit;
	var keyword = $("#search").val();
	if (keyword=='') keyword = '';
	
	var sortBy = $("#sortBy").val();
	var orderBy = 'DESC';
	
	var sortByCategory = document.getElementById("getCategoryList").value;
	
	 if (sortByCategory=='') sortByCategory = '';
	
	$("#individual_rows").html('<div style="position: absolute; top: 20px; left: 352px;"><img src="../admin/images/spinner.gif" /></div>').show();
	var resultDiv = '';
	var totalrecords,currentLength,individuals;
	//var date_join;
	$.get(API_URL+"animal", {
		'offset':start_limit,
		'limit':limit,
		'sortby':sortBy,
		'orderby':orderBy,
		'keyword': keyword ,
		'category_id': sortByCategory
	}, 
		function(data){
		
		if (data.totalrecords>0)
		{
			totalrecords 	= data.totalrecords;
			individuals 	= data.records;
			
			currentLength   = individuals.length;
			var setclass 	= '';
			var above_age 	= '';
			var action ='';
			var separator ='';
						//console.log(data.records.length);
			for (var i=0;i<data.records.length;i++)
			{
				setclass 	 = 'gray';
				adoption_status  = '';
				gps_status  = '';
				if (i%2==0) {setclass = 'gray'; }
				else { setclass = ''; }
				
				if(individuals[i].animal_id == null) {individuals[i].animal_id = '';}
				
				if (individuals[i].category_active_adoption == 'Yes')
				{
					separator = '';
					if(individuals[i].active_adoption =='Yes'){
					 adoption_status ='<a style="cursor:pointer" id="individual'+individuals[i].id+'" onclick="updateAdoption(\''+individuals[i].id+'\',\'No\');">Inactivate Adoption  Process</a>'; 
					}else if(individuals[i].active_adoption =='No' || individuals[i].active_adoption == null ){
						 adoption_status ='<a style="cursor:pointer" id="individual'+individuals[i].id+'" onclick="updateAdoption(\''+individuals[i].id+'\',\'Yes\');">Activate Adoption  Process</a>';
					}
				}else{
					 adoption_status = '';
				}

				if(individuals[i].category_active_gps == 'Yes')
				{
					separator = '';
					if (individuals[i].active_gps =='Yes'){
					 gps_status ='<a style="cursor:pointer" id="individual'+individuals[i].id+'" onclick=" updateGps(\''+individuals[i].id+'\',\'No\');">Inactivate GPS</a>'; 
					}else if(individuals[i].active_gps =='No' || individuals[i].active_gps == null ){
						 gps_status ='<a style="cursor:pointer" id="individual'+individuals[i].id+'" onclick=" updateGps(\''+individuals[i].id+'\',\'Yes\');">Activate GPS</a>';	 
					}	
				}else{
					gps_status = '';
				}

				if(individuals[i].category_active_gps == 'No' && individuals[i].category_active_adoption == 'No') {action = '-'; separator = '';} 	
				else if(individuals[i].category_active_gps == 'Yes' && individuals[i].category_active_adoption == 'Yes') {separator = '|';}
				
				if(individuals[i].nick_name == '') nick_name = '';
				else nick_name =  '('+individuals[i].nick_name+')';
				resultDiv +='<tr class="'+setclass+'"><td align="center" class="first bo">'+individuals[i].label+' '+nick_name+'</td><td class="width-name">'+individuals[i].category_title+'</td><td class="width-name">'+individuals[i].user_data+'</td><td class="width-name" id="'+individuals[i].id+'">'+gps_status+' '+separator+'<br /> '+adoption_status +' '+action+'</td></tr>';

			}
			document.getElementById("Paging").innerHTML = Paging('IndividualListing',start_limit,page,totalrecords,limit);
		}
		else
		{
			resultDiv = '<tr><td colspan="6" valign="top"><div style="text-align:center"><h2>No marked individual found.</h2></div></td></tr>';			
		}
		
		
	$("#individual_rows").html(resultDiv);
	$("#TotalIndividuals").html(data.totalrecords) ;
		

	}, "json");
}

/*******************************Adoption Status change******************************/

function updateAdoption(id, status)
{

	$.post(API_URL+"update_adoption", {'id': id, 'status': status}, function(data){	

	if (data.status =='success')
	{
		GetIndividuals(0);		
	}
	else
	{
		// Show error div
	}
	
	}, "json");
}

/*******************************GPS Status change******************************/

function updateGps(id, status)
{
	
	$.post(API_URL+"update_gps", {'id': id, 'status': status}, function(data){	
	
	if (data.status =='success')
	{
		GetIndividuals(0);		
	}
	else
	{
		// Show error div
	}
	
	}, "json");
}

/***************************************** Get Category List ****************************************************/

function GetCategoryList()
{
	var resultDiv = '';

	$.get(API_URL+"category", {}, function(data){
		if (data.totalrecords>0)
		{
			var totalrecords = data.totalrecords;
			var individuals = data.records;
			var currentIndividual = individuals.length;
			
			resultDiv +='<option value="">Choose Category</option>';
			for (var i=0;i<currentIndividual;i++)
			{
				resultDiv +='<option value="'+individuals[i].id+'">'+individuals[i].title+'</option>';
			}
		}
			
			$('#getCategoryList').append(resultDiv);
	
	}, "json");
}

/******************************************************************************************************
-----------------------------------Functions Category Start
*******************************************************************************************************/

function ApplyCategoryOptions(category_id,adop_status,g_status)
{
	var selected_option = $("#categoryoptions_"+category_id).val();
	if (selected_option=='edit_category')
	{
		window.location = 'addCategory?id='+category_id+'&mode=edit';
	}
	else if (selected_option=='delete_category')
	{
		DeleteCategory(category_id,0);	
	}

	else if (selected_option=='active_adoption')
	{
		updateCatAdoption(category_id,adop_status);	
	}
	else if (selected_option=='active_gps')
	{
		updateCatGps(category_id,g_status);	
	}
}

/*******************************Get Category******************************/

function GetCategory(page)
{
	var limit		= 10;
	var start_limit	= (page-1)*limit;
		start_limit	= (start_limit<0) ? 0 : start_limit;
	var keyword = $("#search").val();
	if (keyword=='') keyword = '';
	var sortBy = $("#sortBy").val();
	var orderBy = 'DESC';
	if (sortBy=='') { sortBy = 'id'; orderBy = 'ASC';}
	if (sortBy=='title_asc') { sortBy = 'title'; orderBy = 'ASC';}
	if (sortBy=='title_desc') { sortBy = 'title'; orderBy = 'DESC';}
	
	
	$("#category_rows").html('<div style="position: absolute; top: 20px; left: 252px;"><img src="../admin/images/spinner.gif" /></div>').show();
	var resultDiv = '';
	var totalrecords,currentLength,category;
	//var date_join;
	$.get(API_URL+"category", {
		'offset':start_limit,
		'limit':limit,
		'sortby':sortBy,
		'orderby':orderBy,
		'keyword': keyword }, 
		function(data){
		
		if (data.totalrecords>0)
		{
			totalrecords 	= data.totalrecords;
			category 		= data.records;
			currentLength   = category.length;
			var setclass 	= '';
			var above_age 	= '';
			var status = '' ;
			
			for (var i=0;i<currentLength;i++)
			{
				setclass 	 = 'gray';
				if (i%2==0) {setclass = 'gray'; }
				else { setclass = ''; }

				if (category[i].active_adoption =='Yes' ){
					 adoption_status ='Inactivate Adoption  Process';
					 adop_status = 'No'; 
				}else if(category[i].active_adoption =='No' || category[i].active_adoption == null){
					 adoption_status ='Activate Adoption Process';
					 adop_status = 'Yes';
					 }	
					 
				if (category[i].active_gps =='Yes'){
					 gps_status ='Inactivate GPS';
					 g_status = 'No';  
				}else if(category[i].active_gps =='No' || category[i].active_gps == null ){
					 gps_status ='Activate GPS';
					 g_status = 'Yes'; 
					 
					 }	
//
				resultDiv +='<tr class="'+setclass+'"><td align="center" class="first bo">'+category[i].id+'</td><td valign="middle" style=" height:70px;" ><div class="align-left '+category[i].color_code.lower()+'"><img src="../'+category[i].icon+'"  align="left"  style="margin-right:7px"></div><div style=" font-weight:bold; color: #4D4D4D; margin-top: 7px; margin-left:88px; word-wrap:break-word;">'+category[i].title.capitalize()+' </div><td class="width-name">'+category[i].color_code.capitalize()+'</td><td class="width-name">'+category[i].type.capitalize()+'</td><td class="width-name" style="word-wrap: break-word; word-break: break-all;">'+category[i].api_url+'</td><td class="width-name">'+category[i].id_prefix+'</td><td class="width-name">$'+category[i].minimum_amount+'</td><td ><select name="categoryoptions_'+category[i].id+'" id="categoryoptions_'+category[i].id+'" class="sel" style="width:78px;"><option value="edit_category">Edit</option><option value="delete_category">Delete</option><option value="active_adoption">'+adoption_status+'</option><option value="active_gps">'+gps_status+'</option></select><i class="icon-arrow-right" title="Apply" onclick="ApplyCategoryOptions('+category[i].id+',\''+adop_status+'\',\''+g_status+'\');"></i></td></tr>';


			}
			
			document.getElementById("Paging").innerHTML = Paging('CategoryListing',start_limit,page,totalrecords,limit);
		}
		else
		{
			resultDiv = '<tr><td colspan="6" valign="top"><div style="text-align:center"><h2>No category found.</h2></div></td></tr>';			
		}
		
	$("#category_rows").html(resultDiv);
	$("#TotalCategory").html(data.totalrecords) ;
		

	}, "json");
}

String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

String.prototype.lower = function() {
    return this.charAt(0).toLowerCase() + this.slice(1);
}

/*******************************Category Adoption Status change******************************/

function updateCatAdoption(id, status)
{
	$.post(API_URL+"update_cat_adoption", {'id': id, 'status': status}, function(data){	

	if (data.status =='success')
	{
		GetCategory(0);		
	}
	else
	{
		// Show error div
	}
	
	}, "json");
}

/*******************************Category GPS Status change******************************/

function updateCatGps(id, status)
{
	
	$.post(API_URL+"update_cat_gps", {'id': id, 'status': status}, function(data){	
	
	if (data.status =='success')
	{
		GetCategory(0);		
	}
	else
	{
		// Show error div
	}
	
	}, "json");
}

/*******************************add Category******************************/

function PostCategory()
{
	 $('#cate_spinner').show();
	// $("#cat_name").removeClass("invalid_text");
	
	 var cat_title	= $("#title").val();
	 var color		= $("#color").val();
	 var type		= $("#type").val();
	 var url		= $("#url").val();
	 var image_url  = $('#image_url').val();
	 var icon  	    = $("#icon").val();  
	 var icon2      = $("#g_icon").val();
	 var id_prefix   = $("#id_prefix").val();
	 var minimum_amount      = $("#minimum_amount").val(); 
	  
	 $("#title").css("border-color","");
	 $("#color").css("border-color","");
	 $("#url").css("border-color","");
	 $("#image_url").css("border-color","");
	 $("#id_prefix").css("border-color","");
	  $("#minimum_amount").css("border-color","");
	  
	 if (cat_title=='')
	 {
		  $('#cate_spinner').hide();
		  //$("#title").addClass("invalid_text");
		  $("#title").focus();
		  $("#title").css("border-color","red");
		  return false;
	 }
	  else if (color=='')
	 {
		  $('#cate_spinner').hide();
		 // $("#color").addClass("invalid_text");
		  $("#color").focus();
		  $("#color").css("border-color","red");
		  return false;
	 }
	 else if (url=='')
	 {
		  $('#cate_spinner').hide();
		  //$("#url").addClass("invalid_text");
		  $("#url").focus();
		  $("#url").css("border-color","red");
		  return false;
	 }
	  else if (id_prefix=='')
	 {
		  $('#cate_spinner').hide();
		  //$("#url").addClass("invalid_text");
		  $("#id_prefix").focus();
		  $("#id_prefix").css("border-color","red");
		  return false;
	 }
	 else if (minimum_amount=='')
	 {
		  $('#cate_spinner').hide();
		  //$("#url").addClass("invalid_text");
		  $("#minimum_amount").focus();
		  $("#minimum_amount").css("border-color","red");
		  return false;
	 }
	  else if (image_url=='')
	 {
		  $('#cate_spinner').hide();
		  //$("#url").addClass("invalid_text");
		  $("#image_url").focus();
		  $("#image_url").css("border-color","red");
		  return false;
	 }
	 else if (icon=='')
	 {
		 $('html, body').animate({scrollTop: 0}, 500);
		  $('#Message-error').addClass('error');
		  $('#Message-error').text('Please upload white icon.');
		  $('#Message-error').show();
		   $('#cate_spinner').hide();
		  return false;
	 }
	  else if (icon2=='')
	 {
		 $('html, body').animate({scrollTop: 0}, 500);
		  $('#Message-error').addClass('error');
		  $('#Message-error').text('Please upload grey icon.');
		  $('#Message-error').show();
		   $('#cate_spinner').hide();
		  return false;
	 }
	 else
	 {
		  $.post(API_URL+"category", {
			  'title':cat_title,'icon':icon,'color_code':color,'type':type,'api_url':url,'image_url':image_url,'id_prefix':id_prefix,'minimum_amount':minimum_amount}, 
			  function(data){
			   if (data.status == "success")
			   { 
			   		 // $('#Message-error').addClass('success');
//					  $('#Message-error').text('Category added successfully!');
//					  $('#Message-error').show();
					  document.location.href='category?add=1';
			   }
			   else
			   {
					$('div#Message-error').css('background-color', '#F88585');
					$('#Message-error').text('Category could not be added!');
					$('div#Message-error').show();
			   }
		 
		  }, "json");
	 }
}

/*******************************delete Category******************************/
function DeleteCategory(id,page)
{
	$.ajax({
      async:true, 
      data: {"_method":"delete",'id':id},
      dataType:'json', 
      type:'post', 
      url:API_URL+"category",
      success: function(response)
	  	{ 
       		if (response.status == 'success')
			{
  		   	    $('#Message-success').html('Category has been deleted successfully').fadeIn(1000).delay(2000).fadeOut(1000);
				
  		   	  
			}
			else
			{
				$('#Message-error').fadeIn(1000).delay(2000).fadeOut(1000);
			}
			
			GetCategory(page);
      	}
    }); 

}

/*******************************update Category******************************/
function UpdateCategory()
{ 
	 $('#cate_spinner').show();
	 //$("#cat_name").removeClass("invalid_text");
	 var id = GetUrlParms('id');
	
	 var cat_title  = $("#title").val();
	 var color		= $("#color").val();
	 var type		= $("#type").val();
	 var url		= $("#url").val();
	 var image_url  = $("#image_url").val();
	 var icon  	    = $("#icon").val();
	 var icon2      = $("#g_icon").val();
	 var id_prefix   = $("#id_prefix").val();
	  var minimum_amount      = $("#minimum_amount").val(); 
	  
	 $("#title").css("border-color","");
	 $("#color").css("border-color","");
	 $("#type").css("border-color","");
	 $("#url").css("border-color","");
	 $("#image_url").css("border-color","");
	$("#id_prefix").css("border-color","");
	 if (cat_title=='')
	 {
		  $('#cate_spinner').hide();
		  //$("#title").addClass("invalid_text");
		  $("#title").focus();
		  $("#title").css("border-color","red");
		  return false;
	 }
	 else if (color=='')
	 {
		  $('#cate_spinner').hide();
		 // $("#color").addClass("invalid_text");
		  $("#color").focus();
		  $("#color").css("border-color","red");
		  return false;
	 }
	 else if (type=='')
	 {
		  $('#cate_spinner').hide();
		  //$("#type").addClass("invalid_text");
		  $("#type").focus();
		  $("#type").css("border-color","red");
		  return false;
	 }
	 else if (url=='')
	 {
		  $('#cate_spinner').hide();
		  //$("#url").addClass("invalid_text");
		  $("#url").focus();
		  $("#url").css("border-color","red");
		  return false;
	 }
	  else if (id_prefix=='')
	 {
		  $('#cate_spinner').hide();
		  //$("#url").addClass("invalid_text");
		  $("#id_prefix").focus();
		  $("#id_prefix").css("border-color","red");
		  return false;
	 }
	  else if (minimum_amount=='')
	 {
		  $('#cate_spinner').hide();
		  //$("#url").addClass("invalid_text");
		  $("#minimum_amount").focus();
		  $("#minimum_amount").css("border-color","red");
		  return false;
	 }
	 else if (image_url=='')
	 {
		  $('#cate_spinner').hide();
		  //$("#url").addClass("invalid_text");
		  $("#image_url").focus();
		  $("#image_url").css("border-color","red");
		  return false;
	 }
	 else if (icon=='')
	 {
		 $('html, body').animate({scrollTop: 0}, 500);
		  $('#Message-error').addClass('error');
		  $('#Message-error').text('Please upload white icon.');
		  $('#Message-error').show();
		   $('#cate_spinner').hide();
		  return false;

	 }
	/* else if (icon2=='')
	 {
		 $('html, body').animate({scrollTop: 0}, 500);
		  $('#Message-error').addClass('error');
		  $('#Message-error').text('Please upload grey icon.');
		  $('#Message-error').show();
		   $('#cate_spinner').hide();
		  return false;

	 }*/
	 else
	 {
		$.ajax({
		dataType: "json",
		url: API_URL+'category',
		data:{'id':id ,'title':cat_title,'icon':icon,'color_code':color,'type':type,'api_url':url,'image_url':image_url,'id_prefix':id_prefix,'minimum_amount':minimum_amount},
		type: 'PUT',
		success: function(response) {
		 
		 var status = response;
	 
		 if(status.status == 'success')
		 {
			document.location.href='category?update=1';
		 }
	  }
	  });
	  
	 }
}

function callChart(){

			  	  // generate some random data first
				 // generateChartData();
				//console.log(chartData.length);
			   // SERIAL CHART
			   chart = new AmCharts.AmSerialChart();
			   chart.pathToImages = "../amcharts/images/";
			   chart.dataProvider = chartData;
			   chart.categoryField = "date";

			   // listen for "dataUpdated" event (fired when chart is inited) and call zoomChart method when it happens
			 //  chart.addListener("dataUpdated", zoomChart);

			   // AXES
			   // category
			   var categoryAxis = chart.categoryAxis;
			   categoryAxis.parseDates = true; // as our data is date-based, we set parseDates to true
			   categoryAxis.minPeriod = "DD"; // our data is daily, so we set minPeriod to DD
			   categoryAxis.minorGridEnabled = true;
			   categoryAxis.axisColor = "#DADADA";
			   categoryAxis.twoLineMode = true;
			   categoryAxis.dateFormats = [{
					period: 'fff',
					format: 'JJ:NN:SS'
				}, {
					period: 'ss',
					format: 'JJ:NN:SS'
				}, {
					period: 'mm',
					format: 'JJ:NN'
				}, {
					period: 'hh',
					format: 'JJ:NN'
				}, {
					period: 'DD',
					format: 'DD'
				}, {
					period: 'WW',
					format: 'DD'
				}, {
					period: 'MM',
					format: 'MMM'
				}, {
					period: 'YYYY',
					format: 'YYYY'
				}];

			   // first value axis (on the left)
			   var valueAxis1 = new AmCharts.ValueAxis();
			   valueAxis1.axisColor = "#FF6600";
			   valueAxis1.axisThickness = 2;
			   valueAxis1.gridAlpha = 0;
			   chart.addValueAxis(valueAxis1);

			   // second value axis (on the right)
			   var valueAxis2 = new AmCharts.ValueAxis();
			   valueAxis2.position = "right"; // this line makes the axis to appear on the right
			   valueAxis2.axisColor = "#FCD202";
			   valueAxis2.gridAlpha = 0;
			   valueAxis2.axisThickness = 2;
			   chart.addValueAxis(valueAxis2);

			   // GRAPHS
			   // first graph
			  /* var graph1 = new AmCharts.AmGraph();
			   graph1.valueAxis = valueAxis1; // we have to indicate which value axis should be used
			   graph1.title = "Revenue";
			   graph1.valueField = "amount";
			   graph1.bullet = "round";
			   graph1.hideBulletsCount = 30;
			   graph1.bulletBorderThickness = 1;
			   chart.addGraph(graph1);*/

			   // second graph
			   var graph2 = new AmCharts.AmGraph();
			   graph2.valueAxis = valueAxis2; // we have to indicate which value axis should be used
			   graph2.title = "Adoptions";
			   graph2.valueField = "adoptions";
			   graph2.bullet = "square";
			   graph2.hideBulletsCount = 30;
			   graph2.bulletBorderThickness = 1;
			   chart.addGraph(graph2);

			   // third graph
	
			   // CURSOR
			   var chartCursor = new AmCharts.ChartCursor();
			   chartCursor.cursorAlpha = 0.1;
			   chartCursor.fullWidth = true;
			   chart.addChartCursor(chartCursor);

			   // SCROLLBAR
			   var chartScrollbar = new AmCharts.ChartScrollbar();
			   chart.addChartScrollbar(chartScrollbar);

			   // LEGEND
			   var legend = new AmCharts.AmLegend();
			   legend.marginLeft = 110;
			   legend.useGraphSettings = true;
			   chart.addLegend(legend);

			   // WRITE
			   chart.write("chartdiv");
			   $("#dashboard_spin").fadeOut();
}

function callChartRevenue(){

			  	  // generate some random data first
				 // generateChartData();
				//console.log(chartData.length);
			   // SERIAL CHART
			   chart = new AmCharts.AmSerialChart();
			   chart.pathToImages = "../amcharts/images/";
			   chart.dataProvider = chartData;
			   chart.categoryField = "date";

			   // listen for "dataUpdated" event (fired when chart is inited) and call zoomChart method when it happens
			//   chart.addListener("dataUpdated", zoomChart);

			   // AXES
			   // category
			   var categoryAxis = chart.categoryAxis;
			   categoryAxis.parseDates = true; // as our data is date-based, we set parseDates to true
			   categoryAxis.minPeriod = "DD"; // our data is daily, so we set minPeriod to DD
			   categoryAxis.minorGridEnabled = true;
			   categoryAxis.axisColor = "#DADADA";
			   categoryAxis.twoLineMode = true;
			   categoryAxis.dateFormats = [{
					period: 'fff',
					format: 'JJ:NN:SS'
				}, {
					period: 'ss',
					format: 'JJ:NN:SS'
				}, {
					period: 'mm',
					format: 'JJ:NN'
				}, {
					period: 'hh',
					format: 'JJ:NN'
				}, {
					period: 'DD',
					format: 'DD'
				}, {
					period: 'WW',
					format: 'DD'
				}, {
					period: 'MM',
					format: 'MMM'
				}, {
					period: 'YYYY',
					format: 'YYYY'
				}];

			   // first value axis (on the left)
			   var valueAxis1 = new AmCharts.ValueAxis();
			   valueAxis1.axisColor = "#FF6600";
			   valueAxis1.axisThickness = 2;
			   valueAxis1.gridAlpha = 0;
			   chart.addValueAxis(valueAxis1);

			   // second value axis (on the right)
			   var valueAxis2 = new AmCharts.ValueAxis();
			   valueAxis2.position = "right"; // this line makes the axis to appear on the right
			   valueAxis2.axisColor = "#FCD202";
			   valueAxis2.gridAlpha = 0;
			   valueAxis2.axisThickness = 2;
			   chart.addValueAxis(valueAxis2);

			   // GRAPHS
			   // first graph
			   var graph1 = new AmCharts.AmGraph();
			   graph1.valueAxis = valueAxis1; // we have to indicate which value axis should be used
			   graph1.title = "Revenue";
			   graph1.valueField = "revenue";
			   graph1.bullet = "round";
			   graph1.hideBulletsCount = 30;
			   graph1.bulletBorderThickness = 1;
			   chart.addGraph(graph1);

			   // second graph
			 /*  var graph2 = new AmCharts.AmGraph();
			   graph2.valueAxis = valueAxis2; // we have to indicate which value axis should be used
			   graph2.title = "Transaction";
			   graph2.valueField = "transaction";
			   graph2.bullet = "square";
			   graph2.hideBulletsCount = 30;
			   graph2.bulletBorderThickness = 1;
			   chart.addGraph(graph2);*/

			   // third graph
	
			   // CURSOR
			   var chartCursor = new AmCharts.ChartCursor();
			   chartCursor.cursorAlpha = 0.1;
			   chartCursor.fullWidth = true;
			   chart.addChartCursor(chartCursor);

			   // SCROLLBAR
			   var chartScrollbar = new AmCharts.ChartScrollbar();
			   chart.addChartScrollbar(chartScrollbar);

			   // LEGEND
			   var legend = new AmCharts.AmLegend();
			   legend.marginLeft = 110;
			   legend.useGraphSettings = true;
			   chart.addLegend(legend);

			   // WRITE
			   chart.write("chartdiv");
			      $("#dashboard_spin").fadeOut();
}

///////////

function zoomChart() {
	   // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
	   chart.zoomToIndexes(10, 20);
   }
   
function GetStats(page){
	
	$.get(API_URL+"chart", {}, 
		function(data){
		
		if (data.status =='success'){
			
			
			$("#count_log").html('Total No. of Active Adopters : '+data.count);
			//var chart_data = {};
			var records = data.records;
			//   var chartData = [];
			//	console.log(records);
				chartData = [];
				$(records).each(function(index, element) {
				  var newDate = new Date(element.date); 
				//var newDate = new Date(2014, 10, 05, 12, 15, 50, 55); 
					 chartData.push({
					   date: newDate,
					   amount: element.amount,
					   adoptions: element.adobtions 
				   });
					
				}).promise().done(function(){
			
				callChart();
			
				});			  
		}
		
	}, "json");
}


function GetStatsRevenue(page){
	
	var date1 = $("#date1").val();
	var date2 = $("#date2").val();
	 
	var date1check = date1.split('/');
	var date2check = date2.split('/');
	
	//console.log(parseInt(date1check[2]) + parseInt(date2check[2]));
	
	date1check[2] = parseInt(date1check[2]);
	date2check[2] = parseInt(date2check[2]);
	
	date1check[0] = parseInt(date1check[0]);
	date2check[0] = parseInt(date2check[0]);
	
	 $("#date1").css("border-color","");
	 $("#date2").css("border-color","");
	 
	if(date1 == ''){
		 $("#date1").focus();
    	 $("#date1").css("border-color","red");
    	 return false;
	}
	
	else if(date2 == ''){
	 
	  $("#date2").focus();
  	  $("#date2").css("border-color","red");
  	  return false;
	}	
	else if( date1check[0] >= date2check[0] && date1check[2] > date2check[2] ){
		
		$("#date1").css("border-color","red");
		$("#date2").css("border-color","red");
		 return false;
	}
	else if( date1check[0] >= date2check[0] && date1check[2] == date2check[2] ){
		
		$("#date1").css("border-color","red");
		$("#date2").css("border-color","red");
		 return false;
	}
	else{
		
	   $("#dashboard_spin").fadeIn();
	
	$.get(API_URL+"chart_revenue", {date1:date1, date2:date2}, 
		function(data){
		
		if (data.status =='success'){
			
			
			$("#count_log").html('Total Revenue Collected : $'+data.count);
			//var chart_data = {};
			var records = data.records;
			var html = '';
			//   var chartData = [];
			//	console.log(records);

			
				chartData = [];
				$(records).each(function(index, element) {
				  var newDate = new Date(element.date); 
				//var newDate = new Date(2014, 10, 05, 12, 15, 50, 55); 
					 chartData.push({
					   date: newDate,
					   revenue: element.amount,
					   transaction: element.adobtions 
				   });
					
				}).promise().done(function(){
			
				callChartRevenue();
			
				});			  
		}
		
	}, "json");
	}
}


function GetCategoryDashboard(page)
{

	var resultDiv = '';

	$.get(API_URL+"category_dashboard", {}, function(data){
		if (data.totalrecords>0)
		{
			var totalrecords = data.totalrecords;
			var individuals = data.records;
			var currentIndividual = individuals.length;
			var resultDiv = '';
			//resultDiv +='<option value="">Choose Category</option>';
			for (var i=0;i<currentIndividual;i++)
			{
				
			
				resultDiv +='<td valign="middle" width="20%" style=" height:70px;" ><div class="align-left '+individuals[i].color_code.lower()+'"><img src="../'+individuals[i].icon+'"  align="left"  style="margin-right:7px"></div><div style=" font-weight: bold; color: rgb(77, 77, 77); margin-top: 31px; margin-left: 96px;" id="category_'+individuals[i].id+'"> '+individuals[i].title.capitalize()+' : '+individuals[i].category_count+' </div></td>';
			}
		}
		
			$('#category_rows').html(resultDiv);
	
	}, "json");
}
function GetCategoryRevenue(page)
{

	var resultDiv = '';
	var date1 = $("#date1").val();
	var date2 =  $("#date2").val();
	
	$.get(API_URL+"category_revenue", {date1:date1, date2:date2}, function(data){
		if (data.totalrecords>0)
		{
			var totalrecords = data.totalrecords;
			var individuals = data.records;
			
			var individuals_total = data.records_total;
			
			var currentIndividual = individuals.length;
			var resultDiv = '';
			var resultDiv2 = '';
			var html ='';
			var total = 0;
			//resultDiv +='<option value="">Choose Category</option>';
			for (var i=0;i<currentIndividual;i++)
			{
								
				resultDiv +='<td valign="middle" width="20%" style=" height:70px;" ><div class="align-left '+individuals[i].color_code.lower()+'"><img src="../'+individuals[i].icon+'"  align="left"  style="margin-right:7px"></div><div style=" font-weight: bold; color: rgb(77, 77, 77); margin-top: 31px; margin-left: 96px;" id="category_'+individuals[i].id+'"> '+individuals[i].title.capitalize()+' : $'+individuals[i].category_count+' </div></td>';
				
				resultDiv2 +='<td valign="middle" width="20%" style=" height:70px;" ><div class="align-left '+individuals_total[i].color_code.lower()+'"><img src="../'+individuals_total[i].icon+'"  align="left"  style="margin-right:7px"></div><div style=" font-weight: bold; color: rgb(77, 77, 77); margin-top: 31px; margin-left: 96px;" id="category_'+individuals_total[i].id+'"> '+individuals_total[i].title.capitalize()+' : $'+individuals_total[i].category_count+' </div></td>';

					total += parseInt(individuals[i].category_count); 
			}
			//<span style="font-size: 14px; font-weight:bold">
			//</span>
			// <span style="font-size: 14px; font-weight:bold;">
			//</span>
			//<span style="font-size: 14px;font-weight:bold"></span>
			
			html = '<div style="float: left;font-size: 14px;margin-left: 10px;margin-top: 34px;font-weight:bold">Revenue Collected From '+individuals[0].date1+' To'+individuals[0].date2+' : $'+total+'</div>';
		}
			$('#category_rows_total').html(resultDiv2);
			$('#category_rows').html(resultDiv);
			$("#revenue_total").html(html);
	
	}, "json");
}

////////////////////


/******************************************************************************************************
-----------------------------------Functions Report Abuse
*******************************************************************************************************/

/*******************************Get Reports******************************/

function GetReports(page)
{
	var limit		= 10;
	var start_limit	= (page-1)*limit;
		start_limit	= (start_limit<0) ? 0 : start_limit;
		

	var keyword = $("#search").val();
	if (keyword=='') keyword = '';
	var sortBy = $("#sortBy").val();
	var orderBy = 'DESC';
	if (sortBy=='') { sortBy = 'id'; orderBy = 'ASC';}
	if (sortBy=='title_asc') { sortBy = 'title'; orderBy = 'ASC';}
	if (sortBy=='title_desc') { sortBy = 'title'; orderBy = 'DESC';}
	
	
	
	$("#reports_rows").html('<div style="position: absolute; top: 18px; left: 250px;"><img src="../admin/images/spinner.gif" /></div>').show();
	var resultDiv = '';
	var totalrecords,currentLength,reports;

	$.get(API_URL+"reports", {
		'offset':start_limit,
		'limit':limit,
		'sortby':sortBy,
		'orderby':orderBy,
		'keyword': keyword },  
		function(data){
		
		if (data.totalrecords>0)
		{
			console.log(data.records);
			totalrecords 	= data.totalrecords;
			reports 		= data.records;
			currentLength   = reports.length;
			var setclass 	= '';
			var above_age 	= '';
			

			for (var i=0;i<currentLength;i++)
			{

				if(reports[i]['commenter_data']['record']['is_ban'] == 'Yes'){

				user_status ='<a style="cursor:pointer" onclick="banUser('+reports[i].commenter_data.record.id+',\'No\','+page+');">Unban User</a>'; 
			      }else{
			      
			    user_status ='<a style="cursor:pointer" onclick="banUser('+reports[i].commenter_data.record.id+',\'Yes\','+page+');">Ban User</a>';
			    }
 				 /*user_status ='<a style="cursor:pointer" onclick="banUser('+reports[i].commenter_data.record.id+', '+reports[i].comment_id+',\'Yes\','+page+');">Ban User</a>';*/
			   /* if(reports[i]['animal_data']['record']['nick_name']== '') { reports[i]['animal_data']['record']['nick_name']='-';}*/
				
				resultDiv +='<tr class="'+setclass+'"><td align="center" class="first bo">'+reports[i].id+'</td><td align="center" class="image"><img src="http://graph.facebook.com/'+reports[i].commenter_data.record.id+'/picture?width=45&height=45" style="float:left;"><p style="margin-top: 0px; font-size: 14px; float: left; width: 128px;">&nbsp;&nbsp;'+reports[i].commenter_data.record.name+'</p></td><td align="center" class="image"><img src="http://graph.facebook.com/'+reports[i].reporter_id+'/picture?width=45&height=45" style="float:left;"><p style="margin-top: 0px; font-size: 14px; float: left; width: 128px;">&nbsp;&nbsp;'+reports[i].reporter_data.record.name+'</p></td><td class="width-name">'+reports[i].animal_data.record.id+'&nbsp;&nbsp;'+reports[i].animal_data.record.nick_name+'</td><td class="width-name">'+reports[i].message+'</td><td class="width-name">'+reports[i].report_date+'</td><td class="width-name"> '+user_status+' | <a href="#" onclick="deleteComment('+reports[i].comment_id+','+reports[i].encounter_data.record.id+','+page+')"> Delete Comment </a> | <a href="#" onclick="ignoreReport('+reports[i].id+','+page+')"> Ignore </a></td></tr>';
				
			}
			
			document.getElementById("Paging").innerHTML = Paging('ReportsListing',start_limit,page,totalrecords,limit);
		}
		else 
		{
			
			resultDiv = '<tr><td colspan="6" valign="top"><div style="text-align:center"><h2>No reports found.</h2></div></td></tr>';			
		}
		
	$("#reports_rows").html(resultDiv);
	$("#TotalReports").html(data.totalrecords) ;
		

	}, "json");
}

/*******************************Delete Abuse Comment******************************/

	function deleteComment(id,encounter_id,page)
	{
		$.ajax({
	      async:true, 
	      data: {"_method":"delete",'id':id ,'encounter_id':encounter_id},
	      dataType:'json', 
	      type:'post', 
	      url:API_URL+"comment_admin",
	      success: function(response)
		  	{ 
				GetReports(page);
	      	}
    	}); 
	}

	/*******************************Ignore Abuse Report******************************/

	function ignoreReport(id,page)
	{
		$.ajax({
	      async:true, 
	      data: {"_method":"delete",'id':id},
	      dataType:'json', 
	      type:'post', 
	      url:API_URL+"report",
	      success: function(response)
		  	{
	       		GetReports(page);
	      	}
    	}); 
	}

	/*******************************Ban User******************************/

	function banUser(uid,value,page)
	{
		$.ajax({
	      async:true, 
	      data: {"_method":"post",'id':uid,'ban_status':value},
	      dataType:'json', 
	      type:'post', 
	      url:API_URL+"update_banuser",
	      success: function(response)
		  	{
				GetReports(page);
	      	}
    	}); 
	}

