// JavaScript Document
var server		= window.location.hostname;
var canvas_url    = "";
if (server=='localhost')
{
	canvas_url       = location.protocol+"//localhost/wildme-v2/public/";
	AdminUrl		 = location.protocol+"//localhost/wildme-v2/public/cpanel/";
}
else if (server=='digital.cygnismedia.com')
{
	canvas_url       = location.protocol+"//digital.cygnismedia.com/wildme-v2/public/";
	AdminUrl		 = location.protocol+"//digital.cygnismedia.com/wildme-v2/public/cpanel/";
}
else if (server=='wildme.cygnismedia.com')
{
	canvas_url       = location.protocol+"//wildme.cygnismedia.com/wildme/public/";
	AdminUrl		 = location.protocol+"//wildme.cygnismedia.com/wildme/public/cpanel/";
}
else //if (server=='fb.wildme.org')
{
	canvas_url       = location.protocol+"//fb.wildme.org/wildme/public/";
	AdminUrl		 = location.protocol+"//fb.wildme.org/wildme/public/cpanel/";
}

var API_URL    = canvas_url+"api/";

var canvasPage = "http://apps.facebook.com/wildbook/";

var cUrl 		= window.location;
var sPath 		= window.location.pathname;
var sPage 		= sPath.substring(sPath.lastIndexOf('/') + 1);   //if(sPage == 'index.php')

var applicationId     = "306566776167234"; //414751805313094
var applicationSecret = "fa214567c0c9266f61908498ff9e7325"; //f65a47c37be9e536f7003649dbc3a851
var APP_URL	=	'http://apps.facebook.com/wildbook/'; //wildmeapp

var session_key;
var access_token;
var uid;
var user_name;

var language = 'en';
var userData;

var is_fb_user=0; 
var facebook_id=0;
var access_token=0;
var API_URL = canvas_url+'api/';


var appInstallUrl = "https://www.facebook.com/dialog/oauth/?client_id="+applicationId+"&redirect_uri="+canvasPage+"&scope=email,publish_actions,publish_stream,offline_access";
