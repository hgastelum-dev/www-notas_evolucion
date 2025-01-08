<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'pacientes';
	protected $hidden = ['foto_path'];
    public function getCitas(){
        return $this->hasMany('App\Models\CitaPaciente', 'paciente_id');
    }

    public function getAntecedentes(){
        return $this->hasOne('App\Models\PacienteAntecedente', 'paciente_id');
    }

    public function getPadecimientos(){
        return $this->hasOne('App\Models\PacientePadecimiento', 'paciente_id');
    }

    public function getExploracionFisica(){
        return $this->hasOne('App\Models\PacienteExploracionFisica', 'paciente_id');
    }

    public function getNotasHistoricas(){
        return $this->hasMany('App\Models\NotaHistorica', 'paciente_id');
    }
}
