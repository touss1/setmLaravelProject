<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\IntervenantCategorie;
use DB;

class IntervenantCategorieController extends Controller
{

    public function index(){
    	$categorie = DB::table('z_inter_categorie')->get();

    	return view('intervenant-categorie/index', compact('categorie'));
    }

     public function create(){

    	return view('intervenant-categorie/create');
    }

      public function store(Request $request){

    	$data = array();
    	$data['categorie_nom'] = $request->cat_name;
    	$data['categorie_code'] = $request->cat_code;
    	$data['categorie_statut'] = $request->cat_status;

    	$categorie = DB::table('z_inter_categorie')->insert($data);

    	return redirect()->route('intervenant-categorie.index')->with('success','Donnée inserée avec succes');
    }

    public function edit($id){

        $categorie = DB::table('z_inter_categorie')->where('categorie_num',$id)->first();

        return view('intervenant-categorie.edit',compact('categorie'));

    }


    public function update(Request $request, $id)
    {

        $data = array();
    	$data['categorie_nom'] = $request->cat_name;
    	$data['categorie_code'] = $request->cat_code;
    	$data['categorie_statut'] = $request->cat_status;

        DB::table('z_inter_categorie')->where('categorie_num', $id)->update($data);

        return redirect()->route('intervenant-categorie.index')->with('success','Donnée mise à Jour ');
    }


    public function delete($id)
    {
        DB::table('z_inter_categorie')->where('categorie_num', $id)->delete();

        return redirect()->route('intervenant-categorie.index')->with('success','Donnée supprimée avec succes');
    }
}
