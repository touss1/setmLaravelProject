<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Sess;

class HomeController extends Controller
{
    //


    public function index(){

	    $sess = new Sess;

	    if (! $sess->checkSession() ){
	    	return view('/account/login');
	    } 
	    else{
	    	if(session()->get('AGENT_CATEGORIE') == "minier"){
               return view('dashboard/minier-dash');
	    	}
	    	else{
	    	  return view('dashboard/default-dash');
	    	}
	    }
	    	 
        
     
    }
}
