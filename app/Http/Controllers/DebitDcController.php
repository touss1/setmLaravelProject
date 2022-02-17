<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\NoteDebitDc;
use DB;
use App\Http\Controllers\Sess;

class DebitDcController extends Controller
{
    
    // returns all the Bp
    public function index(){ 
        
    	$bulletins = DB::table('dc_bulletin_pesage')
        ->join('intervenants', 'dc_bulletin_pesage.inter_numeroExportateur','intervenants.inter_num')
        ->join('z_produits', 'dc_bulletin_pesage.produit_numero','z_produits.produit_num')
        ->join('acheteurs', 'dc_bulletin_pesage.bp_acheteur_numero','acheteurs.acheteur_num')
        ->select('intervenants.inter_nomComplet','intervenants.inter_rccm','intervenants.inter_idNat','intervenants.inter_logo','z_produits.produit_nature','dc_bulletin_pesage.bp_num','dc_bulletin_pesage.bp_enregistrerPar','dc_bulletin_pesage.bp_numeroUnique','dc_bulletin_pesage.bp_paysDestination','acheteurs.acheteur_raisonSocial')
        ->get();

    	return view('bp.index', compact('bulletins'));
    }


     
    // returns debit creation form
     public function create($id){
       
        $sess = new Sess;

        if (! $sess->checkSession() ){
            return view('/account/login');
        } 
        

        $totalBrut = DB::table('bp_lot_prelevement')->where('bp_numero', $id)->sum('prelevement_poidsBrut');
        $totalNet = DB::table('bp_lot_prelevement')->where('bp_numero', $id)->sum('prelevement_poidsNet');
        $totalHumidite = DB::table('bp_lot_prelevement')->where('bp_numero', $id)->avg('prelevement_pourcentageHumidite');
        $totalTeneur = DB::table('bp_lot_prelevement')->where('bp_numero', $id)->avg('prelevement_qualiteTeneur');
        $totalColis = DB::table('bp_lot_prelevement')->where('bp_numero', $id)->sum('prelevement_nbreColis');
        $totalLots = DB::table('bp_lot_prelevement')->where('bp_numero', $id)->count('prelevement_num');
        $bp = DB::table('dc_bulletin_pesage')->where('bp_num', $id) ->first();
        $tauxProduitService = DB::table('z_taxes_services')->where('inter_numeroService', session('INTER_NUMERO'))->first();
        $tauxProduitProvince = DB::table('z_taxes_provinces')->where('typeproduit_numero', $bp->produit_numero)->first();
        $tauxVoirieProvince = DB::table('z_taxes_provinces')->where('taxeprovince_description', 'Taxe voirie')->first();
        
        if( in_array(session('AGENT_CATEGORIE') , array('OCC','CGEA','CEEC')) ){
          $montantProduit = $tauxProduitService->taxeservice_valeurBase * $totalLots ;
          $montantVoirie  = 0;
        }
        if( session('AGENT_CATEGORIE') == 'divmin' ){
          $montantProduit = $tauxProduitProvince->taxeprovince_valeurBase * $totalNet ;
          $montantVoirie  = $tauxVoirieProvince->taxeprovince_valeurBase * $totalNet ;
        }

        return view('debit/create', compact('bp','totalBrut','totalNet','totalHumidite','totalTeneur','totalColis','totalLots','montantProduit','montantVoirie'));
    }



     // save bp to DB
      public function store(Request $request){

    	$data = array();
    	$data['guichet_numero'] = $request->guichet;
        $data['bp_numero'] = $request->bp;
    	$data['inter_numeroExportateur'] = $request->exportateur;
    	$data['produit_numero'] = $request->produit;
    	$data['banque_numero'] = $request->banque;
    	$data['inter_service'] = session('AGENT_CATEGORIE');
    	$data['prelevement_poidsNet'] = $request->totalNet;
    	$data['prelevement_qualiteTeneur'] = $request->totalBrut;
        $data['prelevement_nbreLot'] = $request->totalLots;
        $data['prelevement_humidite'] = $request->totalHumidite;
        $data['ndebit_montantProduit'] = $request->montantProduit;
        $data['ndebit_montantVoirie'] = $request->montantVoirie;
      

    	$debit = DB::table('bp_noteservices_debits')->insert($data);

    	return $this->showBp($request->bp);

    }

     // Validate Notes Debit - CEEC
    public function validateCeec(Request $request, $id)
    {

        $data = array();
        $data['bp_noteDebitCeec'] = 1;
        $data['bp_noteDebitCeecDate'] = date('Y-m-d H:i:s');
        $data['bp_noteDebitCeecPar'] = session('AGENT_NUMERO');

        DB::table('dc_bulletin_pesage')->where('bp_num', $id)->update($data);

        return $this->showBp($id);
    }

    // Validate Notes Debit - Exportateur
    public function validateExp(Request $request, $id)
    {

        $data = array();
        $data['bp_noteDebitOp'] = 1;
        $data['bp_noteDebitOpDate'] = date('Y-m-d H:i:s');
        $data['bp_noteDebitOpPar'] = session('AGENT_NUMERO');

        DB::table('dc_bulletin_pesage')->where('bp_num', $id)->update($data);

        return $this->showBp($id);
    }

    // Edit BP
    public function edit($id){

        $bp = DB::table('dc_bulletin_pesage')->where('bp_num',$id)->first();

        return view('bp.edit',compact('bp'));

    }

    
    // Updates BP
    public function update(Request $request, $id)
    {

       $data = array();
        $data['droit_numero'] = $request->droit;
        $data['guichet_numero'] = $request->guichet;
        $data['inter_numeroExportateur'] = $request->exportateur;
        $data['inter_numeroMandataire'] = $request->mandataire;
        $data['inter_numeroTransporteur'] = $request->transporteur;
        $data['produit_numero'] = $request->produit;
        $data['banque_numero'] = $request->banque;
        $data['bp_numeroUnique'] = '0000';
        $data['bp_paysDestination'] = $request->pays;
        $data['bp_acheteur_numero'] = $request->acheteur;
        $data['bp_enregistrerDateEnr'] = date('Y-m-d');
        $data['bp_statut'] = 'actif';

        DB::table('dc_bulletin_pesage')->where('bp_num', $id)->update($data);

        return redirect()->route('bp.index')->with('success','Données mises à Jour ');
    }


    
    // Display Bp

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



}
