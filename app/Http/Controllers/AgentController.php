<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Agent;
use DB;

class AgentController extends Controller
{

    public function index(){
    	$agents = DB::table('agents')->get();

    	return view('agent/index', compact('agents'));
    }

     public function create(){
        $intervenants = DB::table('intervenants')->get();
        $categories = DB::table('z_inter_categorie')->get();
        $roles =  DB::table('z_agent_roles')->get();
        $fonctions = DB::table('agent_fonctions')->get();
        $guichets  = DB::table('z_guichets')->get();
        $banques = DB::table('banques')->get();

    	return view('agent/create', compact('intervenants','categories','roles','fonctions','guichets','banques'));
    }

      public function store(Request $request){

    	$data = array();
    	$data['inter_numero'] = $request->intervenant;
    	$data['guichet_numero'] = '1';
        $data['banque_numero'] = '1';
    	$data['agent_nom'] = $request->nom;
    	$data['agent_telephone'] = $request->telephone;
    	$data['agent_email'] = $request->email;
    	$data['agent_role'] = $request->role;
    	$data['agent_fonction'] = $request->fonction;
    	$data['agent_categorie'] = $request->categorie;
    	$data['agent_matricule'] = '1';
    	$data['agent_username'] = $request->username;
    	$data['agent_password'] = $request->password;
    	$data['agent_loginSessionKey'] = '0';
        $data['agent_passwordKeyReset'] = '0';
        $data['agent_enregistrerPar'] = '1';
        $data['agent_statut'] = 'actif';

        $signature    = $request->file('signature');
        $photo        = $request->file('photo');

        if($signature){
            $prefix   = date('y-m-d_His');
            $ext      = strtolower($signature->getClientOriginalExtension());
            $fullname = "signature_".$prefix.".".$ext;
            $upload_path = 'uploads/';
            $img_url = $upload_path.$fullname;
            $success = $signature->move($upload_path,$fullname) ;
            $data['agent_signature'] = $img_url;
        }
        else{
            $data['agent_signature'] = "uploads/noimage.png";
        }

        if($photo){
            $prefix   = date('y-m-d_His');
            $ext      = strtolower($photo->getClientOriginalExtension());
            $fullname = "photo_".$prefix.".".$ext;
            $upload_path = 'uploads/';
            $img_url = $upload_path.$fullname;
            $success = $photo->move($upload_path,$fullname) ;
            $data['agent_photo'] = $img_url;
        }
        else{
            $data['agent_photo'] = "uploads/noimage.png";
        }

    	$agent = DB::table('agents')->insert($data);

    	return redirect()->route('agent.index')->with('success','Donnée inserée avec succes');
    }

    public function edit($id){

        $agent = DB::table('agents')->where('agent_num',$id)->first();

        return view('acheteur.edit',compact('agent'));

    }

    public function update(Request $request, $id)
    {

        $data = array();
        $data['inter_numero'] = $request->intervenant;
        $data['guichet_numero'] = $request->guichet;
        $data['agent_nom'] = $request->nom;
        $data['agent_telephone'] = $request->telephone;
        $data['agent_email'] = $request->email;
        $data['agent_role'] = $request->role;
        $data['agent_fonction'] = $request->fonction;
        $data['agent_categorie'] = $request->categorie;
        $data['agent_matricule'] = $request->matricule;;
        $data['agent_username'] = $request->username;
        $data['agent_password'] = $request->password;
        $data['agent_statut'] = $request->status;;

        DB::table('agents')->where('agent_num', $id)->update($data);

        return redirect()->route('agent.index')->with('success','Donnée mise à Jour ');
    }


    public function delete($id)
    {
        DB::table('agents')->where('agent_num', $id)->delete();

        return redirect()->route('agent.index')->with('success','Donnée supprimée avec succes');
    }
}
