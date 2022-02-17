<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Intervenant;
use DB;

class IntervenantController extends Controller
{

    public function index(){
    	$intervenant = DB::table('intervenants')->get();

    	return view('intervenant/index', compact('intervenant'));
    }

     public function interMandataire($id){
         $intervenant =  DB::table('exportateur_mandataire')->join('intervenants', 'exportateur_mandataire.inter_mandataire', 'intervenants.inter_num')
         ->where('inter_exportateur',$id)
         ->select('intervenants.*')->get(); 
         
         return view('intervenant/index', compact('intervenant'));
    }

     public function create(){
        
        $province    = DB::table('z_admin_provinces')->get();
        $ville       = DB::table('z_ville_territoire')->get();
        $categorie   = DB::table('z_inter_categorie')->get();

    	return view('intervenant/create', compact('province','ville','categorie'));
    }

     public function store(Request $request){

    	$data = array();
    	$data['inter_nomComplet'] = $request->nom;
    	$data['inter_categorie'] = $request->categorie;
    	$data['inter_type'] = $request->type;
    	$data['inter_sigle'] = $request->sigle;
    	$data['inter_rccm'] = $request->rccm;
    	$data['inter_idNat'] = $request->idnat;
    	$data['inter_nif'] = $request->nif;
    	$data['inter_telephone'] = $request->telephone;
    	$data['inter_email'] = $request->email;
    	$data['province_siegeSocial'] = $request->province;
    	$data['villechefferie_siegeSocial'] = $request->ville;
    	$data['adresse_siegeSocial'] = $request->adresse;
    	$data['inter_documentChangeNumero'] = $request->change_numero;
    	$data['inter_attestationFiscalNumero'] = $request->attestation_numero;
    	$data['inter_attestationFiscalAnnee'] = $request->attestation_annee;
    	$data['inter_enregistrerPar'] = '1';
    	$data['inter_dateEnr'] = date('Y-m-d');
    	$data['inter_statut'] = 'actif';



        $doc_fiscale   = $request->file('doc_fiscale');
        $doc_logo      = $request->file('doc_logo');
        $doc_sceau     = $request->file('doc_sceau');
        $doc_change    = $request->file('doc_change');

    	if($doc_fiscale){
    		$prefix   = date('y-m-d');
    		$ext      = strtolower($doc_fiscale->getClientOriginalExtension());
    		$fullname = $prefix.".".$ext;
    		$upload_path = 'uploads/';
    		$img_url = $upload_path.$fullname;
    		$success = $doc_fiscale->move($upload_path,$fullname) ;
    		$data['inter_attestationFiscalFichier'] = $img_url;
    	}
    	else{
    		$data['inter_attestationFiscalFichier'] = "public/uploads/noimage.png";
    	}

    	if($doc_logo){
    		$prefix   = date('y-m-d');
    		$ext      = strtolower($doc_logo->getClientOriginalExtension());
    		$fullname = $prefix.".".$ext;
    		$upload_path = 'uploads/';
    		$img_url = $upload_path.$fullname;
    		$success = $doc_logo->move($upload_path,$fullname) ;
    		$data['inter_logo'] = $img_url;
    	}
    	else{
    		$data['inter_logo'] = "public/uploads/noimage.png";
    	}

    	if($doc_sceau){
    		$prefix   = date('y-m-d');
    		$ext      = strtolower($doc_sceau->getClientOriginalExtension());
    		$fullname = $prefix.".".$ext;
    		$upload_path = 'uploads/';
    		$img_url = $upload_path.$fullname;
    		$success = $doc_sceau->move($upload_path,$fullname) ;
    		$data['inter_cachetFichier'] = $img_url;
    	}
    	else{
    		$data['inter_cachetFichier'] = "public/uploads/noimage.png";
    	}

    	if($doc_change){
    		$prefix   = date('y-m-d');
    		$ext      = strtolower($doc_change->getClientOriginalExtension());
    		$fullname = $prefix.".".$ext;
    		$upload_path = 'uploads/';
    		$img_url = $upload_path.$fullname;
    		$success = $doc_change->move($upload_path,$fullname) ;
    		$data['inter_documentChangeFichier'] = $img_url;
    	}
    	else{
    		$data['inter_documentChangeFichier'] = "public/uploads/noimage.png";
    	}

    	$intervenants = DB::table('intervenants')->insert($data);

    	return redirect()->route('intervenant.index')->with('success','Donnée inserée avec succes');
    }


    public function edit($id){

        $intervenant = DB::table('intervenants')->where('inter_num',$id)->first();

        return view('intervenant.edit',compact('intervenant'));

    }


    public function update(Request $request, $id)
    {

        $data = array();
    	$data['inter_nomComplet'] = $request->nom;
    	$data['inter_categorie'] = $request->categorie;
    	$data['inter_type'] = $request->type;
    	$data['inter_sigle'] = $request->sigle;
    	$data['inter_rccm'] = $request->rccm;
    	$data['inter_idNat'] = $request->idNat;
    	$data['inter_nif'] = $request->nif;
    	$data['inter_telephone'] = $request->telephone;
    	$data['inter_email'] = $request->email;
    	$data['provinceSiegeSocial'] = $request->province;
    	$data['villechefferie_siegeSocial'] = $request->ville;
    	$data['adresse_siegeSocial'] = $request->adresse;
    	$data['inter_documentChangeNumero'] = $request->change_numero;
    	$data['inter_attestationFiscalNumero'] = $request->attestation_numero;
    	$data['inter_attestationFiscalAnnee'] = $request->attestation_annee;
    	$data['inter_enregistrerPar'] = '1';
    	$data['inter_dateEnr'] = date('Y-m-d');
    	$data['inter_statut'] = $request->status;

    	$doc_fiscale   = $request->file('doc_fiscale');
        $doc_logo      = $request->file('doc_logo');
        $doc_sceau     = $request->file('doc_sceau');
        $doc_change    = $request->file('doc_change');

    	if($doc_fiscale){
    		$prefix   = date('y-m-d');
    		$ext      = strtolower($doc_fiscale->getClientOriginalExtension());
    		$fullname = $prefix.".".$ext;
    		$upload_path = 'public/uploads/';
    		$img_url = $upload_path.$fullname;
    		$success = $doc_fiscale->move($upload_path,$fullname) ;
    		$data['inter_attestationFiscalFichier'] = $img_url;
    	}
    	else{
    		$data['inter_attestationFiscalFichier'] = "public/uploads/noimage.png";
    	}

    	if($doc_logo){
    		$prefix   = date('y-m-d');
    		$ext      = strtolower($doc_logo->getClientOriginalExtension());
    		$fullname = $prefix.".".$ext;
    		$upload_path = 'public/uploads/';
    		$img_url = $upload_path.$fullname;
    		$success = $doc_logo->move($upload_path,$fullname) ;
    		$data['inter_logo'] = $img_url;
    	}
    	else{
    		$data['inter_logo'] = "public/uploads/noimage.png";
    	}

    	if($doc_sceau){
    		$prefix   = date('y-m-d');
    		$ext      = strtolower($doc_sceau->getClientOriginalExtension());
    		$fullname = $prefix.".".$ext;
    		$upload_path = 'public/uploads/';
    		$img_url = $upload_path.$fullname;
    		$success = $doc_sceau->move($upload_path,$fullname) ;
    		$data['inter_cachetFichier'] = $img_url;
    	}
    	else{
    		$data['inter_cachetFichier'] = "public/uploads/noimage.png";
    	}

    	if($doc_change){
    		$prefix   = date('y-m-d');
    		$ext      = strtolower($doc_change->getClientOriginalExtension());
    		$fullname = $prefix.".".$ext;
    		$upload_path = 'public/uploads/';
    		$img_url = $upload_path.$fullname;
    		$success = $doc_change->move($upload_path,$fullname) ;
    		$data['inter_documentChangeFichier'] = $img_url;
    	}
    	else{
    		$data['inter_documentChangeFichier'] = "public/uploads/noimage.png";
    	}


        DB::table('acheteurs')->where('inter_num', $id)->update($data);

        return redirect()->route('intervenant.index')->with('success','Donnée mise à Jour ');
    }


    public function delete($id)
    {
        DB::table('intervenants')->where('inter_num', $id)->delete();

        return redirect()->route('intervenant.index')->with('success','Donnée supprimée avec succes');
    }
}
