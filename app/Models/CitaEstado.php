<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitaEstado extends Model
{
    use HasFactory;

    protected $table = 'citas_estados';

    public function getCitas(){
        return $this->hasMany('App\Models\CitaPaciente', 'cita_estado_id');
    }
}
