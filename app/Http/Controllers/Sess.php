<?php

namespace App\Http\Controllers;

class Sess extends Controller
{
    //
    

    public function checkSession(){

    	if(session()->has('AGENT_NUMERO') ){

			 return '1';
		}
		else {
			return '0';
		}
     
    }
}
