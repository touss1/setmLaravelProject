<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulletinPesage extends Model
{
    protected $fillable = [
      "droit_numero",
      "guichet_numero",
      "inter_numeroExportateur",
      "inter_numeroMandataire",
      "inter_numeroTransporteur",
      "produit_numero",
      "banque_numero",
      "bp_numeroUnique",
      "bp_paysDestination",
      "bp_acheteur_numero",
      "bp_enregistrerDateEnr",
      "bp_statut",
      "bp_enregistrerPar",
      "bp_validerExportateur",
      "bp_validerExportateurDate",
      "bp_validerExportateurPar",
      "bp_receptionDirMin",
      "bp_receptionDirMinDate",
      "bp_receptionDirMinPar",
      "bp_validerDirMin",
      "bp_validerInspecteurDirMin",
      "bp_validerDirMinDate",
      "bp_validerDirMinPar",
      "bp_nonObjection",
      "bp_nonObjectionPar",
      "bp_nonObjectionDate"
    ]
}
