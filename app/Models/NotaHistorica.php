<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaHistorica extends Model
{
    use HasFactory;

    protected $table = 'pacientes_notas_historic';

    public function getPlanHist(){
        return $this->hasMany('App\Models\NotaHistoricaPlan', 'nota_historica_id');
    }
}
