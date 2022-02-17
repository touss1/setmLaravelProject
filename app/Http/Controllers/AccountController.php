<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class AccountController extends Controller
{
    //

    public function check(Request $request){
      
      $username = $request->username;
      $password = $request->password;

      if(empty($username) || empty($password)){
      	return back()->with('fail','Veuillez entrer vos identifiants');
      }

      $checking = DB::table('agents')->where('agent_username',$username)->where('agent_password',$password)->get();

      if(count($checking)>0){

      
        foreach ($checking as $agent) {
           
           $intervenant = DB::table('intervenants')->where('inter_num', $agent->inter_numero)->first();

           session()->put('AGENT_NUMERO', $agent->agent_num);
           session()->put('INTER_NUMERO', $agent->inter_numero);
           session()->put('INTER_NOM', $intervenant->inter_nomComplet);
           session()->put('AGENT_NOM', $agent->agent_nom);
           session()->put('AGENT_PHOTO', $agent->agent_photo);
           session()->put('AGENT_ROLE', $agent->agent_role);
           session()->put('AGENT_FONCTION', $agent->agent_fonction);
           session()->put('AGENT_CATEGORIE', $agent->agent_categorie);
           session()->put('AGENT_GUICHET', $agent->guichet_numero);

        }

      
        return redirect()->route('home');
      }
      else{
      	return back()->with('fail','Identifiants incorects');
      }
    }


    public function logout(){
      
      session()->forget('AGENT_NUMERO');

      return redirect('account/login');
    }
}
