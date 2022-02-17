<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntervenantCategorieModel extends Model
{
    protected $fillable = [
      "categorie_nom",
      "categorie_code",
      "categorie_statut"
     
    ]
}
