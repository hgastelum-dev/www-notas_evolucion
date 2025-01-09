<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaHistoricaPlan extends Model
{
    use HasFactory;

    protected $table = 'pacientes_notas_hist_plan';

    public function getTipoPlan(){
        return $this->belongsTo('App\Models\TipoPlaneacion', 'tipo_plan_id');
    }
}
