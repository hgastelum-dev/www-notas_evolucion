<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeguroMedico extends Model
{
    use HasFactory;

    protected $table = 'seguros_medicos';

    protected $fillable = ['nombre'];

    public function getPacientes(){
        return $this->belongsToMany(
            Paciente::class,
            'paciente_seguro_medico',
            'seguro_medico_id',
            'paciente_id'
        )->withTimestamps();
    }
}