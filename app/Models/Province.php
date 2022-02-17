<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvinceModel extends Model
{
    protected $fillable = [
      "province_nom",
      "province_statut"
     
    ]
}
