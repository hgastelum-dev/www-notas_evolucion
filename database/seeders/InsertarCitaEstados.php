<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CitaEstado;

class InsertarCitaEstados extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $estadosCita = array(
            array(1, 'Programado', false, '#17a2b8'),
            array(2, 'Reprogramado', false, '#ffc107'),
            array(3, 'No asistio', true, '#dc3545'),
            array(4, 'Concluida', true, '#20c997')
        );

        for ($i = 0; $i < count($estadosCita); $i++){ 
                
            $estadoCita = new CitaEstado();

            $estadoCita->secuencia = $estadosCita[$i][0];
            $estadoCita->cita_estado = $estadosCita[$i][1];
            $estadoCita->bloqueo_cita = $estadosCita[$i][2];
            $estadoCita->class_color = $estadosCita[$i][3];

            $estadoCita->save();
        }
    }
}
