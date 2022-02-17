<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcheteurModel extends Model
{
    protected $fillable = [
      "guichet_numero",
      "bp_numero",
      "inter_numeroExportateur",
      "produit_numero",
      "banque_numero",
      "inter_service",
      "prelevement_poidsNet",
      "prelevement_qualiteTeneur",
      "prelevement_nbreLot",
      "prelevement_humidite",
      "ndebit_montantProduit",
      "ndebit_montantVoirie"
    ]
}
