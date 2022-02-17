<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\BulletinPesage;
use DB;
use App\Http\Controllers\Sess;


class BulletinPesageController extends Controller
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


    // returns bp created by mandataire
     public function indexBpMandataire($id){

      $bulletins = DB::table('dc_bulletin_pesage')
      ->join('intervenants', 'dc_bulletin_pesage.inter_numeroExportateur','intervenants.inter_num')
      ->join('z_produits', 'dc_bulletin_pesage.produit_numero','z_produits.produit_num')
      ->join('acheteurs', 'dc_bulletin_pesage.bp_acheteur_numero','acheteurs.acheteur_num')
      ->select('intervenants.inter_nomComplet','intervenants.inter_rccm','intervenants.inter_idNat','intervenants.inter_logo','z_produits.produit_nature','dc_bulletin_pesage.bp_num','dc_bulletin_pesage.bp_numeroUnique','dc_bulletin_pesage.bp_paysDestination','dc_bulletin_pesage.bp_enregistrerPar','acheteurs.acheteur_raisonSocial')
      ->where('dc_bulletin_pesage.inter_numeroMandataire', $id)->get();

       return view('bp.index', compact('bulletins'));
     }

     // returns bp created by mandataire  by status
     public function indexBpMandataireStatus($status, $id){

      $bulletins = DB::table('dc_bulletin_pesage')
      ->join('intervenants', 'dc_bulletin_pesage.inter_numeroExportateur','intervenants.inter_num')
      ->join('z_produits', 'dc_bulletin_pesage.produit_numero','z_produits.produit_num')
      ->join('acheteurs', 'dc_bulletin_pesage.bp_acheteur_numero','acheteurs.acheteur_num')
      ->select('intervenants.inter_nomComplet','intervenants.inter_rccm','intervenants.inter_idNat','intervenants.inter_logo','z_produits.produit_nature','dc_bulletin_pesage.bp_num','dc_bulletin_pesage.bp_numeroUnique','dc_bulletin_pesage.bp_paysDestination','dc_bulletin_pesage.bp_enregistrerPar','acheteurs.acheteur_raisonSocial')
      ->where('dc_bulletin_pesage.bp_statut', $status)
      ->where('dc_bulletin_pesage.inter_numeroMandataire', $id)->get();

       return view('bp.index', compact('bulletins'));
     }

    

      // returns bp created that belongs to exportateur
     public function indexBpExportateur($id){
       
       $bulletins = DB::table('dc_bulletin_pesage')
      ->join('intervenants', 'dc_bulletin_pesage.inter_numeroExportateur','intervenants.inter_num')
      ->join('z_produits', 'dc_bulletin_pesage.produit_numero','z_produits.produit_num')
      ->join('acheteurs', 'dc_bulletin_pesage.bp_acheteur_numero','acheteurs.acheteur_num')
      ->select('intervenants.inter_nomComplet','intervenants.inter_rccm','intervenants.inter_idNat','intervenants.inter_logo','z_produits.produit_nature','dc_bulletin_pesage.bp_num','dc_bulletin_pesage.bp_enregistrerPar','dc_bulletin_pesage.bp_numeroUnique','dc_bulletin_pesage.bp_paysDestination','acheteurs.acheteur_raisonSocial')     
      ->where('dc_bulletin_pesage.inter_numeroExportateur', $id)->get();

       return view('bp.index', compact('bulletins'));
     }

      // returns bp belongs to exportateur by status
     public function indexBpExportateurStatus($status, $id){

      $bulletins = DB::table('dc_bulletin_pesage')
      ->join('intervenants', 'dc_bulletin_pesage.inter_numeroExportateur','intervenants.inter_num')
      ->join('z_produits', 'dc_bulletin_pesage.produit_numero','z_produits.produit_num')
      ->join('acheteurs', 'dc_bulletin_pesage.bp_acheteur_numero','acheteurs.acheteur_num')
      ->select('intervenants.inter_nomComplet','intervenants.inter_rccm','intervenants.inter_idNat','intervenants.inter_logo','z_produits.produit_nature','dc_bulletin_pesage.bp_num','dc_bulletin_pesage.bp_numeroUnique','dc_bulletin_pesage.bp_paysDestination','dc_bulletin_pesage.bp_enregistrerPar','acheteurs.acheteur_raisonSocial')
      ->where('dc_bulletin_pesage.bp_statut', $status)
      ->where('dc_bulletin_pesage.inter_numeroExportateur', $id)->get();

       return view('bp.index', compact('bulletins'));
     }

      // returns bp belongs to mandataire with element 
     public function indexBpMandataireWith($element, $value , $id){

      if($element == 'no'){
        $column = 'dc_bulletin_pesage.bp_nonObjection';
      }

      if($element == 'divmin'){
        $column = 'dc_bulletin_pesage.bp_validerDirMin';
      }

      $bulletins = DB::table('dc_bulletin_pesage')
      ->join('intervenants', 'dc_bulletin_pesage.inter_numeroExportateur','intervenants.inter_num')
      ->join('z_produits', 'dc_bulletin_pesage.produit_numero','z_produits.produit_num')
      ->join('acheteurs', 'dc_bulletin_pesage.bp_acheteur_numero','acheteurs.acheteur_num')
      ->select('intervenants.inter_nomComplet','intervenants.inter_rccm','intervenants.inter_idNat','intervenants.inter_logo','z_produits.produit_nature','dc_bulletin_pesage.bp_num','dc_bulletin_pesage.bp_numeroUnique','dc_bulletin_pesage.bp_paysDestination','dc_bulletin_pesage.bp_enregistrerPar','acheteurs.acheteur_raisonSocial')
      ->where($column, $value)
      ->where('dc_bulletin_pesage.inter_numeroMandataire', $id)->get();

       return view('bp.index', compact('bulletins'));
     }

     // returns bp belongs to exportateur with element 
     public function indexBpExportateurWith($element, $value , $id){

      if($element == 'no'){
        $column = 'dc_bulletin_pesage.bp_nonObjection';
      }

      if($element == 'divmin'){
        $column = 'dc_bulletin_pesage.bp_validerDirMin';
      }

      $bulletins = DB::table('dc_bulletin_pesage')
      ->join('intervenants', 'dc_bulletin_pesage.inter_numeroExportateur','intervenants.inter_num')
      ->join('z_produits', 'dc_bulletin_pesage.produit_numero','z_produits.produit_num')
      ->join('acheteurs', 'dc_bulletin_pesage.bp_acheteur_numero','acheteurs.acheteur_num')
      ->select('intervenants.inter_nomComplet','intervenants.inter_rccm','intervenants.inter_idNat','intervenants.inter_logo','z_produits.produit_nature','dc_bulletin_pesage.bp_num','dc_bulletin_pesage.bp_numeroUnique','dc_bulletin_pesage.bp_paysDestination','dc_bulletin_pesage.bp_enregistrerPar','acheteurs.acheteur_raisonSocial')
      ->where($column, $value)
      ->where('dc_bulletin_pesage.inter_numeroExportateur', $id)->get();

       return view('bp.index', compact('bulletins'));
     }

   
   // returns bp belongs to mandataire Has Not element 
     public function indexBpMandataireHas($element, $id){

     if($element == 'prelevement'){
       $table = 'bp_lot_prelevement';
     }

     if($element == 'chargement'){
       $table = 'bp_lot_chargement';
     }

     $bulletins = DB::table('dc_bulletin_pesage')
    ->join('intervenants', 'dc_bulletin_pesage.inter_numeroExportateur','intervenants.inter_num')
    ->join('z_produits', 'dc_bulletin_pesage.produit_numero','z_produits.produit_num')
    ->join('acheteurs', 'dc_bulletin_pesage.bp_acheteur_numero','acheteurs.acheteur_num')
    ->select('dc_bulletin_pesage.bp_num','intervenants.inter_nomComplet','intervenants.inter_rccm','intervenants.inter_idNat','intervenants.inter_logo','z_produits.produit_nature','dc_bulletin_pesage.bp_enregistrerPar','dc_bulletin_pesage.bp_numeroUnique','dc_bulletin_pesage.bp_paysDestination','acheteurs.acheteur_raisonSocial')     
    ->where('dc_bulletin_pesage.inter_numeroMandataire', $id)
    ->whereIn('dc_bulletin_pesage.bp_num', DB::table($table)->select('bp_numero')->where('bp_numero','dc_bulletin_pesage.bp_num')->get()->toArray())
    ->get();

    return view('bp.index', compact('bulletins'));
    }

    // returns bp belongs to mandataire Has Not element 
     public function indexBpMandataireHasNot($element, $id){

     if($element == 'prelevement'){
       $table = 'bp_lot_prelevement';
     }

     $bulletins = DB::table('dc_bulletin_pesage')
    ->join('intervenants', 'dc_bulletin_pesage.inter_numeroExportateur','intervenants.inter_num')
    ->join('z_produits', 'dc_bulletin_pesage.produit_numero','z_produits.produit_num')
    ->join('acheteurs', 'dc_bulletin_pesage.bp_acheteur_numero','acheteurs.acheteur_num')
    ->select('dc_bulletin_pesage.bp_num','intervenants.inter_nomComplet','intervenants.inter_rccm','intervenants.inter_idNat','intervenants.inter_logo','z_produits.produit_nature','dc_bulletin_pesage.bp_enregistrerPar','dc_bulletin_pesage.bp_numeroUnique','dc_bulletin_pesage.bp_paysDestination','acheteurs.acheteur_raisonSocial')     
    ->where('dc_bulletin_pesage.inter_numeroMandataire', $id)
    ->whereNotIn('dc_bulletin_pesage.bp_num', DB::table($table)->select('bp_numero')->where('bp_numero','dc_bulletin_pesage.bp_num')->get()->toArray())
    ->get();

    return view('bp.index', compact('bulletins'));
    }


    // returns bp belongs to exportateur Has Not element 
     public function indexBpExportateurHas($element, $id){

     if($element == 'prelevement'){
       $table = 'bp_lot_prelevement';
     }

     if($element == 'chargement'){
       $table = 'bp_lot_chargement';
     }

     $bulletins = DB::table('dc_bulletin_pesage')
    ->join('intervenants', 'dc_bulletin_pesage.inter_numeroExportateur','intervenants.inter_num')
    ->join('z_produits', 'dc_bulletin_pesage.produit_numero','z_produits.produit_num')
    ->join('acheteurs', 'dc_bulletin_pesage.bp_acheteur_numero','acheteurs.acheteur_num')
    ->select('dc_bulletin_pesage.bp_num','intervenants.inter_nomComplet','intervenants.inter_rccm','intervenants.inter_idNat','intervenants.inter_logo','z_produits.produit_nature','dc_bulletin_pesage.bp_enregistrerPar','dc_bulletin_pesage.bp_numeroUnique','dc_bulletin_pesage.bp_paysDestination','acheteurs.acheteur_raisonSocial')     
    ->where('dc_bulletin_pesage.inter_numeroExportateur', $id)
    ->whereIn('dc_bulletin_pesage.bp_num', DB::table($table)->select('bp_numero')->where('bp_numero','dc_bulletin_pesage.bp_num')->get()->toArray())
    ->get();

    return view('bp.index', compact('bulletins'));
    }

    // returns bp belongs to exportateur Has Not element 
     public function indexBpExportateurHasNot($element, $id){

     if($element == 'prelevement'){
       $table = 'bp_lot_prelevement';
     }

     $bulletins = DB::table('dc_bulletin_pesage')
    ->join('intervenants', 'dc_bulletin_pesage.inter_numeroExportateur','intervenants.inter_num')
    ->join('z_produits', 'dc_bulletin_pesage.produit_numero','z_produits.produit_num')
    ->join('acheteurs', 'dc_bulletin_pesage.bp_acheteur_numero','acheteurs.acheteur_num')
    ->select('dc_bulletin_pesage.bp_num','intervenants.inter_nomComplet','intervenants.inter_rccm','intervenants.inter_idNat','intervenants.inter_logo','z_produits.produit_nature','dc_bulletin_pesage.bp_enregistrerPar','dc_bulletin_pesage.bp_numeroUnique','dc_bulletin_pesage.bp_paysDestination','acheteurs.acheteur_raisonSocial')     
    ->where('dc_bulletin_pesage.inter_numeroExportateur', $id)
    ->whereNotIn('dc_bulletin_pesage.bp_num', DB::table($table)->select('bp_numero')->where('bp_numero','dc_bulletin_pesage.bp_num')->get()->toArray())
    ->get();

    return view('bp.index', compact('bulletins'));
    }

    
     // returns bp created that relates to guichet
     public function indexBpGuichet($id){
       
       $bulletins = DB::table('dc_bulletin_pesage')
      ->join('intervenants', 'dc_bulletin_pesage.inter_numeroExportateur','intervenants.inter_num')
      ->join('z_produits', 'dc_bulletin_pesage.produit_numero','z_produits.produit_num')
      ->join('acheteurs', 'dc_bulletin_pesage.bp_acheteur_numero','acheteurs.acheteur_num')
      ->select('intervenants.inter_nomComplet','intervenants.inter_rccm','intervenants.inter_idNat','intervenants.inter_logo','z_produits.produit_nature','dc_bulletin_pesage.bp_num','dc_bulletin_pesage.bp_enregistrerPar','dc_bulletin_pesage.bp_numeroUnique','dc_bulletin_pesage.bp_paysDestination','acheteurs.acheteur_raisonSocial')
      ->where('dc_bulletin_pesage.guichet_numero', $id)
      ->get();

       return view('bp.index', compact('bulletins'));
     }

     
    // returns Bp creation form
     public function create(){
       
        $sess = new Sess;

        if (! $sess->checkSession() ){
            return view('/account/login');
        } 
        
        $acheteurs    = DB::table('acheteurs')->get();
        $produits     = DB::table('z_produits_type')->get(); 
        $guichets     = DB::table('z_guichets')->get();
        $banques      = DB::table('banques')->get();
        $pays         = DB::table('z_pays')->get();
        if(session('AGENT_CATEGORIE')=='mandataire'){
        $droit        = DB::table('droits_miniers')->where('droit_type', 'Exploitant Minier')->where('droit_titreAgrementDateValidite','>',date('Y-m-d'))->get();
        }
        else{
        $droit        = DB::table('droits_miniers')->where('inter_numero',session('INTER_NUMERO'))->where('droit_type', 'Exploitant Minier')->where('droit_titreAgrementDateValidite','>',date('Y-m-d'))->get();  
        }
        $transporteurs= DB::table('intervenants')->where('inter_categorie', 'transporteur')->get();
        $miniers =  DB::table('exportateur_mandataire')->join('intervenants', 'exportateur_mandataire.inter_exportateur', 'intervenants.inter_num')
        ->select('intervenants.inter_num','intervenants.inter_nomComplet')->get(); 
       
    	return view('bp/create', compact('produits','acheteurs','guichets','banques','miniers','transporteurs','droit','pays'));
    }



     // save bp to DB
      public function store(Request $request){

    	$data = array();
    	$data['droit_numero'] = $request->droit;
    	$data['guichet_numero'] = $request->guichet;
    	$data['inter_numeroExportateur'] = $request->exportateur;
    	$data['inter_numeroMandataire'] = $request->mandataire;
      $data['inter_numeroTransporteur'] = $request->transporteur;
    	$data['produit_numero'] = $request->produit;
    	$data['banque_numero'] = $request->banque;
    	$data['bp_paysDestination'] = $request->pays;
    	$data['bp_acheteur_numero'] = $request->acheteur;
    	$data['bp_enregistrerDateEnr'] = date('Y-m-d');
      $data['bp_statut'] = 'incomplet';
      $data['bp_enregistrerPar'] = session('AGENT_NUMERO');
      if(session('AGENT_CATEGORIE')=='minier'){
          $data['bp_validerExportateur'] = 1 ;
          $data['bp_validerExportateurDate'] = date('Y-m-d H:i:s');
          $data['bp_validerExportateurPar'] = session('AGENT_NUMERO');
      }
      else{
          $data['bp_validerExportateur'] = 0;
          $data['bp_validerExportateurPar'] = 0;
      }   
      $data['bp_receptionDirMin'] = 0;
      $data['bp_receptionDirMinPar'] = 0;
      $data['bp_validerDirMin'] = 0;
      $data['bp_validerInspecteurDirMin'] = 0;
      $data['bp_validerDirMinPar'] = 0;

        //code BpUnique
        $guichet = DB::table('z_guichets')->select('guichet_code')->where('guichet_num', $request->guichet)->first();

        $data['bp_numeroUnique'] = mt_rand(00,99).'/'.$guichet->guichet_code;

    	$bp = DB::table('dc_bulletin_pesage')->insertGetId($data);

      return redirect('/bp/show/'.$bp);
      

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

    // Validate BP - exportateur
    public function expValidate($id)
    {

        $data = array();
        $data['bp_validerExportateur'] = 1;
        $data['bp_statut'] = 'valide';
        $data['bp_validerExportateurDate'] = date('Y-m-d H:i:s');
        $data['bp_validerExportateurPar'] = session('AGENT_NUMERO');

        DB::table('dc_bulletin_pesage')->where('bp_num', $id)->update($data);

        return redirect('/bp/show/'.$id);
    }

    // Validate BP - Division mines
    public function divminValidate($id)
    {

        $data = array();
        $data['bp_validerDirMin'] = 1;
        $data['bp_validerDirMinDate'] = date('Y-m-d H:i:s');
        $data['bp_validerInspecteurDirMin'] = session('AGENT_NUMERO');

        DB::table('dc_bulletin_pesage')->where('bp_num', $id)->update($data);

        return redirect('/bp/show/'.$id);
    }

    // Validate BP - Comm exterieur
    public function comexValidate($id)
    {

        $data = array();
        $data['bp_nonObjection'] = 1;
        $data['bp_nonObjectionDate'] = date('Y-m-d H:i:s');
        $data['bp_nonObjectionPar'] = session('AGENT_NUMERO');

        DB::table('dc_bulletin_pesage')->where('bp_num', $id)->update($data);

        return redirect('/bp/show/'.$id);
    }

    // Marquer BP - complet, incomplet, valide , invalide
    public function mark($status, $id)
    {

        $data = array();
        $data['bp_statut'] = $status;

        if($status == 'invalide'){
          $data['bp_validerExportateur'] = 0; 
        }

        DB::table('dc_bulletin_pesage')->where('bp_num', $id)->update($data);

        return redirect('/bp/show/'.$id);

    }


    // Delete Bp
    public function delete($id)
    {
        DB::table('dc_bulletin_pesage')->where('bp_num', $id)->delete();
        DB::table('dc_lots')->where('bp_num', $id)->delete();

       switch (session('AGENT_CATEGORIE')) {
           case 'mandataire':
               return redirect('bp/mandataire/'.session('INTER_NUMERO'))->with('success','Données supprimées avec succes');
               break;
           case 'exportateur':
               return redirect('bp/exportateur/'.session('INTER_NUMERO'))->with('success','Données supprimées avec succes');
               break;    
           
           default:
               return redirect()->route('bp.index')->with('success','Données supprimées avec succes');
               break;
       }

    }

    
    // Display Bp

    public function show(Request $request )
    {
         $id = $request->id;

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
