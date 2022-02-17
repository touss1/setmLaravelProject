<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntervenantModel extends Model
{
    protected $fillable = [
      "inter_nomComplet",
      "inter_categorie",
      "inter_type",
      "inter_sigle",
      "inter_rccm",
      "inter_idNat",
      "inter_nif",
      "inter_telephone",
      "inter_email",
      "provinceSiegeSocial",
      "villechefferie_siegeSocial",
      "adresse_siegeSocial",
      "inter_documentChangeNumero",
      "inter_documentChangeFichier",
      "inter_attestationFiscalNumero",
      "inter_attestationFiscalAnnee",
      "inter_attestationFiscalFichier",
      "inter_cachetFichier",
      "inter_enregistrerPar",
      "inter_dateEnr",
      "inter_statut"

    ]
}
