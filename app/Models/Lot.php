<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    protected $fillable = [
      "bp_numero",
      "inter_numeroExportateur",
      "inter_numeroMandataire",
      "produit_numero",
      "lot_provinceExtraction",
      "lot_villeTerritoireExtraction",
      "lot_communeGroupementExtraction",
      "lot_quartieLocaliteExtraction",
      "lot_nomZoneExtraction",
      "lot_emplacementSiteExtraction",
      "lot_chantierExtraction",
      "truck_numero",
      "chauff_numero",
      "lot_lotNumRefExportateur",
      "lot_shipmentNum",
      "lot_qualiteTeneur",
      "lot_humidite",
      "lot_nbreColis",
      "lot_poidsBrut",
      "lot_poidsNet",
      "lot_emballage",
      "lot_fichierJoint",
      "lot_dateEnr",
      "lot_enregistrerPar",
      "lot_statut"
    ]
}
