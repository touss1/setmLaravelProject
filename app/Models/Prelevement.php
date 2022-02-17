<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prelevement extends Model
{
    protected $fillable = [
      "guichet_numero",
      "bp_numero",
      "lot_numero",
      "inspecteur_numero",
      "inter_numeroExportateur",
      "produit_numero",
      "truck_numero",
      "prelevement_qualiteTeneur",
      "prelevement_pourcentageHumidite",
      "prelevement_nbreColis",
      "prelevement_poidsBrut",
      "prelevement_poidsNet",
      "prelevement_fichierJoint",
      "prelevement_dateEnr",
      "prelevement_enregistrerPar",
      "prelevement_statut"
      
    ]
}
