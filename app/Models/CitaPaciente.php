<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitaPaciente extends Model
{
    use HasFactory;

    protected $table = 'citas_pacientes';

    public function getPaciente(){
        return $this->belongsTo('App\Models\Paciente', 'paciente_id');
    }

    public function getUser(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function getEstado(){
        return $this->belongsTo('App\Models\CitaEstado', 'cita_estado_id');
    }

    public function getPlaneacion(){
        return $this->hasMany('App\Models\CitaPlaneacion', 'cita_paciente_id')->where('padre_id', 0);
    }

    public function getObjetivo(){
        return $this->hasOne('App\Models\CitaObjetivo', 'cita_paciente_id');
    }

    public function getSubjetivo(){
        return $this->hasOne('App\Models\CitaSubjetivo', 'cita_paciente_id');
    }

    public function getAnalisis(){
        return $this->hasOne('App\Models\CitaAnalisis', 'cita_paciente_id');
    }

    public function getCitaAnterior(){
        return $this->belongsTo($this, 'cita_anterior_id');
    }

    public function getDoctor(){
      return $this->belongsTo('App\Models\User', 'doctor_id');
    }
}
