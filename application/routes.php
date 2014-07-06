<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your application using Laravel's RESTful routing and it
| is perfectly suited for building large applications and simple APIs.
|

*/

//Handle All Cases
Route::controller(Controller::detect());

/********************************************`*****************************************************************/
/*********************Admin Panel Routes
/*************************************************************************************************************/
//Handle All Cases


/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/


Route::get('/', function()
{
	
		return View::make('home.index');

});

Route::get('/login', function()
{
		return View::make('home.login');
});
Route::get('/home', function()
{
		return View::make('home.index');

});

Route::get('/phpinfo', function()
{
phpinfo();

});

Route::get('/about-us', function()
{
		return View::make('home.about-us');	

});

Route::get('/browse', function()
{
		return View::make('home.browse');	

});
Route::get('/cacheclear', function()
{
Cache:flush();

});
Route::get('/user-page', function()
{
		return View::make('home.user-page');	

});
/*
Route::get('/profile', function()
{
		return View::make('home.profile');	

});*/


Route::get('/profile/(:any)', function($id)
{
		$animal 			= 	new Animal;
		$param['offset']	=	isset($offset) ? $offset:0;
  	  	$param['sortby']	=	'id';
		$param['orderby']	=	'DESC';
		$param['limit']		=	isset($limit) ? $limit:1;
		
		$param['user_id']	=	Session::get('user_id');
		$param['all_location']   = 0;
	//	$param['animal_id']    	 = $id;
		$data_id = DB::first("SELECT IFNULL(COUNT(id),0) as total, `id` FROM `animal` where label ='".$id."'");
		if($data_id->total == 0){
		
			return View::make('error.404');	
		}
		
		$param_encounter['label']    	 =$id;
		$param['animal_id']    	 = $data_id->id;
		$param['id']		=	$data_id->id;
 		$data = $animal->get_animals($param);
	

		if($data['totalrecords'] > 0){
			
		$param_encounter['offset']	=	isset($offset) ? $offset:0;
  	  	$param_encounter['sortby']	=	'date_added';
		$param_encounter['orderby']	=	'ASC';
		$param_encounter['limit']	=	isset($limit) ? $limit:1;
		$param_encounter['user_id']	=	Session::get('user_id');
		$param_encounter['all_location']   = 0;
		$param_encounter['animal_id']    	 =$data_id->id;
		$param_encounter['id']    	 =$data_id->id;
		$param_encounter['animal_list']    	 = 0;
		$param_encounter['media_offset']    	 = 0;
		
		$encounter			= new Encounter;
		$encounter_data 	= $encounter->get_encounters($param_encounter);
		if ($encounter_data['status']=='success')
		$data['records'][0]['encounter_data']   = $encounter_data['records'][0];
		else
		{
			$data['records'][0]['encounter_data']	= array("genus"=>"","specific_epithet"=>"");
		}
		
		
		$data['records'][0]['show_qoute_popup']   = 0;
		$data['records'][0]['adopter_id']   = 0;
		
		$data = json_encode($data);
		$content['data']	=	array('id'=>$id);
		
			return View::make('home.profile',$content)->with('animal',$data);	
		}else{
			return View::make('error.404');	
		}

});


Route::get('/profile/(:any)/(:any)', function($id, $first_adoptor)
{
		$animal 			= 	new Animal;
		$param['offset']	=	isset($offset) ? $offset:0;
  	  	$param['sortby']	=	'id';
		$param['orderby']	=	'DESC';
		$param['limit']		=	isset($limit) ? $limit:1;
		
		$param['user_id']	=	Session::get('user_id');
		$param['all_location']   = 0;
		
		$data_id = DB::first("SELECT IFNULL(COUNT(id),0) as total, `id` FROM `animal` where label ='".$id."'");
		if($data_id->total == 0){
		
			return View::make('error.404');	
		}
		
		$param_encounter['label']    	 =$id;
		$param['animal_id']    	 = $data_id->id;
		$param['id']		=	$data_id->id;
 		$data = $animal->get_animals($param);
	
		$show_qoute_popup = 0;	
		$animal_price = 0;	
		$adopter_id =0;
		
		if($data['totalrecords'] > 0){
		//check adoption

		 $sql  = DB::first("select IFNULL(COUNT(id),0) as total, id from adoptor where `status` = 'Active' and `user_type` = 'application' and `animal_id` = '".$data_id->id."' and `uid` = '".$first_adoptor."' order by id desc limit 1");
							
		if($sql->total > 0 && $data['records'][0]['first_adoptor'] == $first_adoptor){	
		
			$show_qoute_popup = 1;
			$animal_price = 0;	
			$adopter_id = $sql->id;			
		}
		$param_encounter['offset']	=	isset($offset) ? $offset:0;
  	  	$param_encounter['sortby']	=	'date_added';
		$param_encounter['orderby']	=	'ASC';
		$param_encounter['limit']	=	isset($limit) ? $limit:1;
		$param_encounter['user_id']	=	Session::get('user_id');
		$param_encounter['all_location']   = 0;
		$param_encounter['animal_id']    	 =$data_id->id;
		$param_encounter['id']    	 =$data_id->id;
		$param_encounter['animal_list']    	 = 0;
		$param_encounter['media_offset']    	 = 0;
		
		$encounter			= new Encounter;
		$encounter_data 	= $encounter->get_encounters($param_encounter);
		if ($encounter_data['status']=='success')
		$data['records'][0]['encounter_data']   = $encounter_data['records'][0];
		else
		{
			$data['records'][0]['encounter_data']	= array("genus"=>"","specific_epithet"=>"");
		}
		
		$data['records'][0]['show_qoute_popup']   = $show_qoute_popup;
		$data['records'][0]['adopter_id']   = $adopter_id;
		//print_r($data['encounter_data']);
		//die();
		//$data = json_decode(json_encode($data),TRUE);

		/*$data['records'][0]['follow_check']   = 0;
		//follow check
		if($param['user_id'] != '')
		{
			$animal_id 		= $data['records'][0]['id'];
			$follow_count 	= DB::table('follow')->where('animal_id','=',$animal_id)->where('user_id','=',$param['user_id'])->count();
			if($follow_count > 0)
			{
				$data['records'][0]['follow_check']   = 1;
			}
		}*/
		$data = json_encode($data);
		$content['data']	=	array('id'=>$id);
		
		
			return View::make('home.profile',$content)->with('animal',$data);	
		}else{
			return View::make('error.404');	
		}

});


Route::get('/share/(:any)', function($id)
{
		$animal 			= 	new Animal;
		$param['offset']	=	isset($offset) ? $offset:0;
  	  	$param['sortby']	=	'id';
		$param['orderby']	=	'DESC';
		$param['limit']		=	isset($limit) ? $limit:1;
		$param['id']		=	$id;
		$param['user_id']	=	Session::get('user_id');
		$param['all_location']   = 0;
		$param['animal_id']    	 = $id;
		$data = $animal->get_animals($param);
		$data = json_encode($data);
		$content['data']	=	array('id'=>$id);
		
		return View::make('home.share',$content)->with('animal',$data);	

});

Route::get('/cpanel/login', function()
{
	if(Session::has('s_admin_id'))
	{
		$admin_id	= Session::get('s_admin_id');
		return View::make('cpanel.category');
	}
	else
	{
		return View::make('cpanel.login');
	}
});

Route::get('/cpanel/dashboard', function()
{
	if(Session::has('s_admin_id'))
	{
		$admin_id	= Session::get('s_admin_id');
		return View::make('cpanel.dashboard');
	}
	else
	{
		return Redirect::to('cpanel/login');
	}
});

Route::get('/cpanel/revenue', function()
{
	if(Session::has('s_admin_id'))
	{
		$admin_id	= Session::get('s_admin_id');
		return View::make('cpanel.revenue');
	}
	else
	{
		return Redirect::to('cpanel/login');
	}
});

Route::get('/cpanel/rhistory', function()
{
	if(Session::has('s_admin_id'))
	{
		$admin_id	= Session::get('s_admin_id');
		return View::make('cpanel.history');
	}
	else
	{
		return Redirect::to('cpanel/login');
	}
});

Route::get('/cpanel/category', function()
{
	if(Session::has('s_admin_id'))
	{
		$admin_id	= Session::get('s_admin_id');
		return View::make('cpanel.category');
	}
	else
	{
		return Redirect::to('cpanel/login');
	}
});

Route::get('/cpanel/user', function()
{
	if(Session::has('s_admin_id'))
	{
		$admin_id	= Session::get('s_admin_id');
		return View::make('cpanel.user');
	}
	else
	{
		return Redirect::to('cpanel/login');
	}
});

Route::get('/cpanel/reminders', function()
{
	if(Session::has('s_admin_id'))
	{
		$admin_id	= Session::get('s_admin_id');
		return View::make('cpanel.reminders');
	}
	else
	{
		return Redirect::to('cpanel/login');
	}
});

Route::get('/cpanel/individuals', function()
{
	if(Session::has('s_admin_id'))
	{
		$admin_id	= Session::get('s_admin_id');
		return View::make('cpanel.individuals');
	}
	else
	{
		return Redirect::to('cpanel/login');
	}
});


Route::get('/cpanel/adopters', function()
{
	if(Session::has('s_admin_id'))
	{
		$admin_id	= Session::get('s_admin_id');
		return View::make('cpanel.adopters');
	}
	else
	{
		return Redirect::to('cpanel/login');
	}
});

Route::get('/cpanel/report_abuse', function()
{
	if(Session::has('s_admin_id'))
	{
		$admin_id	= Session::get('s_admin_id');
		return View::make('cpanel.report_abuse');
	}
	else
	{
		return Redirect::to('cpanel/login');
	}
});


Route::get('/cpanel/account', function()
{
	if(Session::has('s_admin_id'))
	{
		$admin_id	= Session::get('s_admin_id');
		return View::make('cpanel.account');
	}
	else
	{
		return Redirect::to('cpanel/login');
	}
});

Route::get('/cpanel', function()
{
	if(Session::has('s_admin_id'))
	{
		$admin_id	= Session::get('s_admin_id');
		return Redirect::to('cpanel/category');
	}
	else
	{
		return Redirect::to('cpanel/login');
	}

});


Route::get('/cpanel/addCategory',function(){
	if(Session::has('s_admin_id'))
	{
  	  	$admin_id	= Session::get('s_admin_id');
  	  	$id 		= isset($_REQUEST['id']) ? $_REQUEST['id']:0;
  	  	$mode 		= isset($_REQUEST['mode']) ? $_REQUEST['mode']:'';
  	  	if (isset($mode) && isset($id))
  	  	{
  	  		$category 	= new Category;
  	  		$output 	= $category->get_category($id);
  	  		$output		= json_encode($output);
	  	  	return View::make('cpanel.add-category')->with('category',$output);
  	  	}
  	  	else
  	  	{
  	  		return View::make('cpanel.add-category');
  	  	}
	}
	else
	{
		return Redirect::to('cpanel/login');
	}
	}
);


Route::get('/cpanel/addReminder',function(){
	if(Session::has('s_admin_id'))
	{
		$admin_id	= Session::get('s_admin_id');
		return View::make('cpanel.add-reminder');
	}
	else
	{
		return Redirect::to('cpanel/login');
	}
});


Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Route::get('/', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('login');
});