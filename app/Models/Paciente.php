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

    public function citasConcluidasAnteriores($fechaCita, $horaCita = null)
    {
        $fechaHoraLimite = $horaCita
            ? $fechaCita . ' ' . $horaCita
            : $fechaCita . ' 23:59:59'; // fallback si solo mandan fecha

        return $this->getCitas()
            ->where('cita_estado_id', 4)
            ->whereRaw("CONCAT(fecha, ' ', hora_inicio) < ?", [$fechaHoraLimite])
            ->orderByRaw("CONCAT(fecha, ' ', hora_inicio) DESC");
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
