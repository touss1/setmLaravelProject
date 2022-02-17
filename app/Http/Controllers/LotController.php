<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Lot;
use DB;
use App\Http\Controllers\Sess;


class LotController extends Controller
{


     public function create($id){
       
        $sess = new Sess;

        if (! $sess->checkSession() ){
            return view('/account/login');
        } 
        
        $bulletin   = DB::table('dc_bulletin_pesage')->where('bp_num', $id)->first();
        $produits   = DB::table('z_produits')->where('typeproduit_numero',$bulletin->produit_numero)->get();
        $emballages = DB::table('z_emballages')->get();
        $droit     = DB::table('droits_miniers')->where('droit_num',$bulletin->droit_numero)->first();
        $province  = DB::table('z_admin_provinces')->where('province_nom', $droit->droit_provinceExploitation)->first();
        $ville     = DB::table('z_ville_territoire')->where('villeterritoire_nom',$droit->droit_villeterritoireExploitation)->first();
        $trucks     = DB::table('transporteur_truck')->where('inter_numeroTransporteur', $bulletin->inter_numeroTransporteur )->get();
        $chauffeurs = DB::table('transporteur_chauffeur')->where('inter_numeroTransporteur', $bulletin->inter_numeroTransporteur )->get();

    	return view('lot.create', compact('bulletin','produits','emballages','trucks','chauffeurs','droit','ville','province'));
    }

     public function store(Request $request){

    	  $data["bp_numero"] = $request->bp_numero;
          $data["inter_numeroExportateur"] = $request->exportateur;
          $data["inter_numeroMandataire"] = $request->mandataire;
          $data["produit_numero"] = $request->produit;
          $data["lot_provinceExtraction"] = $request->province;
          $data["lot_villeTerritoireExtraction"] = $request->ville;
          $data["lot_communeGroupementExtraction"] = $request->groupement;
          $data["lot_quartieLocaliteExtraction"] = $request->localite;
          $data["lot_nomZoneExtraction"] = $request->zone;
          $data["lot_emplacementSiteExtraction"] = $request->emplacement;
          $data["lot_chantierExtraction"] = $request->chantier;
          $data["truck_numero"] = $request->truck;
          $data["chauff_numero"] = $request->chauffeur;
          $data["lot_lotNumRefExportateur"] = $request->reference;
          $data["lot_shipmentNum"] = $request->shipment;
          $data["lot_qualiteTeneur"] = $request->teneur;
          $data["lot_humidite"] = $request->humidite;
          $data["lot_nbreColis"] = $request->colis;
          $data["lot_poidsBrut"] = $request->brut;
          $data["lot_poidsNet"] = $request->net;
          $data["lot_emballage"] = $request->emballage;
          $data["lot_dateEnr"] = date('Y-m-d H:i:s');
          $data["lot_enregistrerPar"] = session('AGENT_NUMERO');
          $data["lot_statut"] = 'actif';

          $fichier    = $request->file('fichier');

          if($fichier){
            $prefix   = date('y-m-d_His');
            $ext      = strtolower($fichier->getClientOriginalExtension());
            $fullname = "lot_".$request->bp_numero."-".$prefix.".".$ext;
            $upload_path = 'uploads/docs/';
            $img_url = $upload_path.$fullname;
            $success = $fichier->move($upload_path,$fullname) ;
            $data['lot_fichierJoint'] = $img_url;
        }
        else{
            $data['lot_fichierJoint'] = "uploads/docs/noimage.png";
        }

    	 $lot = DB::table('dc_lots')->insert($data);

    	

       return redirect('/bp/show/'.$request->bp_numero);

    }


    public function edit($id){

        $lot = DB::table('dc_lots')->where('lot_num',$id)->first();

        return view('lot.edit',compact('lot'));

    }

    public function update(Request $request, $id)
    {

       $data = array();
        $data['droit_numero'] = $request->droit;
        $data['guichet_numero'] = $request->guichet;
        $data['inter_numeroExportateur'] = $request->exportateur;
        $data['inter_numeroMandataire'] = $request->mandataire;
        $data['produit_numero'] = $request->produit;
        $data['banque_numero'] = $request->banque;
        $data['bp_paysDestination'] = $request->pays;
        $data['bp_acheteur_numero'] = $request->acheteur;
        $data['bp_enregistrerDateEnr'] = date('Y-m-d');
        $data['bp_statut'] = 'actif';

        DB::table('dc_bulletin_pesage')->where('bp_num', $id)->update($data);

        return $this->showLot($id);
    }



    public function delete($id)
    {
        $bp = DB::table('dc_lots')->select('bp_numero')->where('lot_num', $id)->first();

        DB::table('dc_lots')->where('lot_num', $id)->delete();

        return $this->showBp($bp->bp_numero);
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

        $debitService = DB::table('bp_noteservices_debits')->where('bp_numero', $id)->where('inter_service', session('AGENT_CATEGORIE'))->first();
       
        $debitAllService = DB::table('bp_noteservices_debits')->where('bp_numero', $id)->get();

        return view('bp.show', compact('bulletin','lots','debitService','debitAllService'));
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
        ->where('lot_num', $id)
        ->first();

        $bulletin = DB::table('dc_bulletin_pesage')
        ->join('intervenants', 'dc_bulletin_pesage.inter_numeroExportateur','intervenants.inter_num')
        ->join('z_produits_type', 'dc_bulletin_pesage.produit_numero','z_produits_type.typeproduit_num')
        ->join('z_guichets', 'dc_bulletin_pesage.guichet_numero','z_guichets.guichet_num')
        ->join('banques', 'dc_bulletin_pesage.banque_numero','banques.banque_num')
        ->join('acheteurs', 'dc_bulletin_pesage.bp_acheteur_numero','acheteurs.acheteur_num')
        ->select('dc_bulletin_pesage.*','intervenants.inter_nomComplet','intervenants.inter_rccm','intervenants.inter_idNat','intervenants.inter_logo','z_produits_type.typeproduit_nom','banques.banque_designation','z_guichets.guichet_nom','acheteurs.acheteur_raisonSocial')
        ->where('dc_bulletin_pesage.bp_num', $lot->bp_numero)
        ->first();

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
