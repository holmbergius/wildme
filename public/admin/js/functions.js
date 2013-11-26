function SubmitAdminLogin(e)
{
   // look for window.event in case event isn't passed in
   if (window.event) { e = window.event; }
   if ((e.keyCode == 13) || (e.type == 'click'))
   {
		AdminLogin();
   }
}


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
				window.location.href	=	AdminUrl+"category";
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
				resultDiv +='<tr class="'+setclass+'"><td align="center" class="image"><img src="http://graph.facebook.com/'+user[i].id+'/picture?width=45&height=45" ></td><td class="width-name" id="cus_name'+user[i].id+'">'+user[i].name+'</td><td class="width-name" id="cus_email'+user[i].id+'">'+user[i].email+'</td><td class="width-name" id="cus_gender'+user[i].id+'">'+user[i].gender.capitalize()+'</td><td class="width-name" id="cus_date_added'+user[i].id+'">'+user[i].date_added+'</td></tr>';
			}
			
			document.getElementById("Paging").innerHTML = Paging('UserListing',start_limit,page,totalrecords,limit);
		}
		else
		{
			resultDiv = '<tr><td colspan="9" valign="top"><div style="text-align:center"><h2>No user found.</h2></div></td></tr>';			
		}
		
	$("#user_rows").html(resultDiv);
	$("#TotalUser").html(data.totalrecords) ;
		

	}, "json");
}



/******************************************************************************************************
-----------------------------------Functions Category Start
*******************************************************************************************************/

function ApplyCategoryOptions(category_id)
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
			
			for (var i=0;i<currentLength;i++)
			{
				setclass 	 = 'gray';
				if (i%2==0) {setclass = 'gray'; }
				else { setclass = ''; }

				resultDiv +='<tr class="'+setclass+'"><td align="center" class="first bo">'+category[i].id+'</td><td valign="middle" width="38%" style=" height:70px;" ><div class="align-left '+category[i].color_code.lower()+'"><img src="../'+category[i].icon+'"  align="left"  style="margin-right:7px"></div><div style=" font-weight:bold; color: #4D4D4D; margin-top: 7px; margin-left:115px"> '+category[i].title.capitalize()+' </div><td class="width-name">'+category[i].color_code.capitalize()+'</td><td class="width-name">'+category[i].type.capitalize()+'</td><td class="width-name">'+category[i].api_url+'</td><td width="14%"><select name="categoryoptions_'+category[i].id+'" id="categoryoptions_'+category[i].id+'" class="sel" style="width:95px;"><option value="edit_category">Edit</option><option value="delete_category">Delete</option></select><i class="icon-arrow-right" title="Apply" onclick="ApplyCategoryOptions('+category[i].id+');"></i></td></tr>';


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
	
	 $("#title").css("border-color","");
	 $("#color").css("border-color","");
	 $("#url").css("border-color","");
	 $("#image_url").css("border-color","");
	
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
			  'title':cat_title,'icon':icon,'color_code':color,'type':type,'api_url':url,'image_url':image_url}, 
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
	  
	 $("#title").css("border-color","");
	 $("#color").css("border-color","");
	 $("#type").css("border-color","");
	 $("#url").css("border-color","");
	 $("#image_url").css("border-color","");
	
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
		data:{'id':id ,'title':cat_title,'icon':icon,'color_code':color,'type':type,'api_url':url,'image_url':image_url},
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
