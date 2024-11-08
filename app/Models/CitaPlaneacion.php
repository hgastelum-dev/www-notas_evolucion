<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitaPlaneacion extends Model
{
    use HasFactory;

    protected $table = 'citas_planeacion';

    public function getTipoPlan(){
        return $this->belongsTo('App\Models\TipoPlaneacion', 'tipo_plan_id');
    }

    public function getTipoPlanAnidado(){
        return $this->hasMany('App\Models\CitaPlaneacion', 'padre_id');
    }
}
