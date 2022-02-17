<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Chargement;
use DB;
use App\Http\Controllers\Sess;


class ChargementController extends Controller
{


     public function create($id){
       
        $sess = new Sess;

        if (! $sess->checkSession() ){
            return view('/account/login');
        } 
        
        $lot   = DB::table('dc_lots')
        ->join('dc_bulletin_pesage', 'dc_lots.bp_numero' , 'dc_bulletin_pesage.bp_num')
        ->select('dc_lots.*','dc_bulletin_pesage.guichet_numero')
        ->where('lot_num', $id)->first();

        $seals = DB::table('scellage_dgda')->get();
      
    	return view('chargement.create', compact('lot','seals'));
    }


     public function store(Request $request){

    	  $data["guichet_numero"] = $request->guichet;
          $data["bp_numero"] = $request->bp;
          $data["lot_numero"] = $request->lot;
          $data["inspecteur_numero"] = session('AGENT_NUMERO');
          $data["inter_numeroExportateur"] = $request->exportateur;
          $data["produit_numero"] = $request->produit;
          $data["truck_numero"] = $request->truck;
          $data["chargement_qualiteTeneur"] = $request->teneur;
          $data["chargement_humidite"] = $request->humidite;
          $data["chargement_nbreColis"] = $request->colis;
          $data["chargement_poidsBrut"] = $request->brut;
          $data["chargement_poidsNet"] = $request->net;
          $data["chargement_sealDGDAnumero"] = $request->seal;
          $data["chargement_sealDescription"] = $request->seal_type;
          $data["chargement_dateEnr"] = date('Y-m-d H:i:s');
          $data["chargement_enregistrerPar"] = session('AGENT_NUMERO');
          $data["chargement_statut"] = 'actif';
        

          $fichier    = $request->file('signature');

          if($fichier){
            $prefix   = date('y-m-d_His');
            $ext      = strtolower($fichier->getClientOriginalExtension());
            $fullname = "lot_".$request->bp_numero."-".$prefix.".".$ext;
            $upload_path = 'uploads/docs/';
            $img_url = $upload_path.$fullname;
            $success = $fichier->move($upload_path,$fullname) ;
            $data['chargement_fichierJoint'] = $img_url;
        }
        else{
            $data['chargement_fichierJoint'] = "uploads/docs/noimage.png";
        }

    	 $lot = DB::table('bp_lot_chargement')->insert($data);

         $update = DB::table('dc_lots')->where('lot_num',$request->lot)->update(['lot_chargement'=>'1']);

    	 return $this->showLot($request->lot);

    }


    public function edit($id){

        $char = DB::table('bp_lot_chargement')->where('chargement_num',$id)->first();

        return view('chargement.edit',compact('char'));

    }


    public function update(Request $request, $id)
    {
          $data["prelevement_qualiteTeneur"] = $request->teneur;
          $data["prelevement_pourcentageHumidite"] = $request->humidite;
          $data["prelevement_nbreColis"] = $request->colis;
          $data["prelevement_poidsBrut"] = $request->brut;
          $data["prelevement_poidsNet"] = $request->net;
          $data["prelevement_statut"] = 'actif';

        DB::table('bp_lot_prelevement')->where('bp_num', $id)->update($data);

        return redirect()->route('bp.index')->with('success','Données mises à Jour ');
    }


    public function validateChar(Request $request, $id)
    {
 
      $data["chargement_numero"] = $id;
      $data["inter_service"] = session('AGENT_CATEGORIE');
      $data["charge_operPar"] = session('AGENT_NUMERO');
      $data["charge_operValiderDate"] = date('Y-m-d H:i:s');

      DB::table('bp_lot_chargement_operation')->insert($data);

      $lot = DB::table('bp_lot_chargement')->select('lot_numero')->where('chargement_num',$id)->first();

      return $this->showLot($lot->lot_numero);

    }



    public function delete($id)
    {
        $prel = DB::table('bp_lot_prelevement')->select('lot_numero')->where('prelevement_num', $id)->first();

        DB::table('bp_lot_prelevement')->where('prelevement_num', $id)->delete();

        return $this->showLot($prel->lot_numero);
    }


    public function showBp($id)
    {
        $bulletin = DB::table('dc_bulletin_pesage')
        ->join('intervenants', 'dc_bulletin_pesage.inter_numeroExportateur','intervenants.inter_num')
        ->join('z_produits_type', 'dc_bulletin_pesage.produit_numero','z_produits_type.typeproduit_num')
        ->join('z_guichets', 'dc_bulletin_pesage.guichet_numero','z_guichets.guichet_num')
        ->join('banques', 'dc_bulletin_pesage.banque_numero','banques.banque_num')
        ->join('acheteurs', 'dc_bulletin_pesage.bp_acheteur_numero','acheteurs.acheteur_num')
        ->select('dc_bulletin_pesage.*','intervenants.inter_nomComplet','intervenants.inter_rccm','intervenants.inter_idNat','intervenants.inter_logo','z_produits_type.typeproduit_nom','banques.banque_designation','z_guichets.guichet_nom','acheteurs.acheteur_raisonSocial')
        ->where('dc_bulletin_pesage.bp_num', $id)->first();

        $lots = DB::table('dc_lots')
        ->join('z_produits', 'dc_lots.produit_numero', 'z_produits.produit_num')
        ->join('z_admin_provinces', 'dc_lots.lot_provinceExtraction', 'z_admin_provinces.province_num')
        ->join('z_ville_territoire', 'dc_lots.lot_villeTerritoireExtraction' ,'z_ville_territoire.villeterritoire_num')
        ->join('transporteur_truck', 'dc_lots.truck_numero', 'transporteur_truck.truck_num')
        ->join('transporteur_chauffeur', 'dc_lots.chauff_numero', 'transporteur_chauffeur.chauff_num')
        ->select('dc_lots.lot_num','dc_lots.lot_lotNumRefExportateur','dc_lots.lot_enregistrerPar','dc_lots.lot_shipmentNum','dc_lots.lot_qualiteTeneur','dc_lots.lot_humidite','dc_lots.lot_nbreColis','dc_lots.lot_poidsBrut','dc_lots.lot_poidsNet','dc_lots.lot_emballage','dc_lots.lot_fichierJoint','z_produits.produit_nature','z_produits.produit_teneurIntervalBas','z_produits.produit_teneurIntervalHaut','z_admin_provinces.province_nom','z_ville_territoire.villeterritoire_nom','transporteur_truck.truck_id','transporteur_chauffeur.chauff_nomComplet')
        ->where('bp_numero', $id)->get();

        return view('bp.show', compact('bulletin','lots'));
    }


    public function showLot($id)
    {
        
        $lot = DB::table('dc_lots')
        ->join('z_produits', 'dc_lots.produit_numero', 'z_produits.produit_num')
        ->join('z_admin_provinces', 'dc_lots.lot_provinceExtraction', 'z_admin_provinces.province_num')
        ->join('z_ville_territoire', 'dc_lots.lot_villeTerritoireExtraction' ,'z_ville_territoire.villeterritoire_num')
        ->join('transporteur_truck', 'dc_lots.truck_numero', 'transporteur_truck.truck_num')
        ->join('transporteur_chauffeur', 'dc_lots.chauff_numero', 'transporteur_chauffeur.chauff_num')
        ->select('dc_lots.*','z_produits.produit_nature','z_produits.produit_teneurIntervalBas','z_produits.produit_teneurIntervalHaut','z_admin_provinces.province_nom','z_ville_territoire.villeterritoire_nom','transporteur_truck.truck_id','transporteur_chauffeur.chauff_nomComplet')
        ->where('lot_num', $id)->first();

        $bulletin = DB::table('dc_bulletin_pesage')
        ->join('intervenants', 'dc_bulletin_pesage.inter_numeroExportateur','intervenants.inter_num')
        ->join('z_produits_type', 'dc_bulletin_pesage.produit_numero','z_produits_type.typeproduit_num')
        ->join('z_guichets', 'dc_bulletin_pesage.guichet_numero','z_guichets.guichet_num')
        ->join('banques', 'dc_bulletin_pesage.banque_numero','banques.banque_num')
        ->join('acheteurs', 'dc_bulletin_pesage.bp_acheteur_numero','acheteurs.acheteur_num')
        ->select('dc_bulletin_pesage.*','intervenants.inter_nomComplet','intervenants.inter_rccm','intervenants.inter_idNat','intervenants.inter_logo','z_produits_type.typeproduit_nom','banques.banque_designation','z_guichets.guichet_nom','acheteurs.acheteur_raisonSocial')
        ->where('dc_bulletin_pesage.bp_num', $lot->bp_numero)->first();

         $prelevement = DB::table('bp_lot_prelevement')
        ->join('agents', 'bp_lot_prelevement.prelevement_enregistrerPar', 'agents.agent_num')
        ->where('bp_lot_prelevement.lot_numero', $id)
        ->select('bp_lot_prelevement.*','agents.agent_nom')
        ->first();

         $chargement = DB::table('bp_lot_chargement')
        ->join('agents', 'bp_lot_chargement.chargement_enregistrerPar', 'agents.agent_num')
        ->where('bp_lot_chargement.lot_numero', $id)
        ->select('bp_lot_chargement.*','agents.agent_nom')
        ->first();
        
        if($prelevement){
          $prel_validations = DB::table('bp_lot_prelevement_operation')->where('prelevement_numero', $prelevement->prelevement_num)->get();
        }
        else{
          $prel_validations = [];
        }

        if($chargement){
          $char_validations = DB::table('bp_lot_chargement_operation')->where('chargement_numero', $chargement->chargement_num)->get();
        }
        else{
          $char_validations = [];
        }

        return view('lot.show', compact('bulletin','lot','prelevement','chargement','prel_validations','char_validations'));
    }

}
