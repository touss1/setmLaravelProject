<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentModel extends Model
{
    protected $fillable = [
      "inter_numero",
      "guichet_numero",
      "banque_numero",
      "agent_photo",
      "agent_nom",
      "agent_telephone",
      "agent_email",
      "agent_role",
      "agent_fonction",
      " agent_categorie",
      "agent_matricule",
      "agent_signature",
      "agent_username",
      "agent_password",
      "agent_loginSessionKey",
      "agent_passwordKeyReset",
      "agent_enregistrerPar",
      "agent_statut"
    ]
}
