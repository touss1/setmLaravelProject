<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Acheteur;
use DB;

class AcheteurController extends Controller
{

    public function index(){
    	$categorie = DB::table('z_inter_cateorie')->get();

    	return view('acheteur.index', compact('categorie'));
    }

     public function create(){

    	return view('acheteur/create');
    }

      public function store(Request $request){

    	$data = array();
    	$data['acheteur_raisonSocial'] = $request->name;
    	$data['acheteur_sigle'] = $request->sigle;
    	$data['acheteur_lienJuridique'] = $request->lienJuridique;
    	$data['acheteur_telephone'] = $request->telephone;
    	$data['acheteur_email'] = $request->email;
    	$data['acheteur_pays'] = $request->pays;
    	$data['acheteur_ville'] = $request->ville;
    	$data['acheteur_adresseComplet'] = $request->adresse;
    	$data['acheteur_enregistrePar'] = '1';
    	$data['acheteur_dateEnr'] = date('Y-m-d');
    	$data['acheteur_statut'] = $request->status;
    	$data['inter_numero'] = $request->intervenant;
    	$data['categorie_statut'] = $request->cat_status;

    	$categorie = DB::table('acheteurs')->insert($data);

    	return redirect()->route('acheteur.index')->with('success','Donnée inserée avec succes');
    }

    public function edit($id){

        $categorie = DB::table('acheteurs')->where('acheteur_num',$id)->first();

        return view('acheteur.edit',compact('categorie'));

    }

    public function update(Request $request, $id)
    {

        $data = array();
    	$data['acheteur_raisonSocial'] = $request->name;
    	$data['acheteur_sigle'] = $request->sigle;
    	$data['acheteur_lienJuridique'] = $request->lienJuridique;
    	$data['acheteur_telephone'] = $request->telephone;
    	$data['acheteur_email'] = $request->email;
    	$data['acheteur_pays'] = $request->pays;
    	$data['acheteur_ville'] = $request->ville;
    	$data['acheteur_adresseComplet'] = $request->adresse;
    	$data['acheteur_enregistrePar'] = '1';
    	$data['acheteur_dateEnr'] = date('Y-m-d');
    	$data['acheteur_statut'] = $request->status;
    	$data['inter_numero'] = $request->intervenant;
    	$data['categorie_statut'] = $request->cat_status;

        DB::table('acheteurs')->where('acheteur_num', $id)->update($data);

        return redirect()->route('acheteur.index')->with('success','Donnée mise à Jour ');
    }


    public function delete($id)
    {
        DB::table('acheteurs')->where('acheteur_num', $id)->delete();

        return redirect()->route('acheteur.index')->with('success','Donnée supprimée avec succes');
    }
}
