<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcheteurModel extends Model
{
    protected $fillable = [
      "acheteur_raisonSocial",
      "acheteur_sigle",
      "acheteur_lienJuridique",
      "acheteur_telephone",
      "acheteur_email",
      "acheteur_pays",
      "acheteur_ville",
      "acheteur_adresseComplet",
      "acheteur_enregistrePar",
      "acheteur_dateEnr",
      "acheteur_statut",
      "inter_numero",
      "categorie_statut"
    ]
}
