<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoPlaneacion;

class InsertarTiposPlaneacion extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tiposPlaneacion = ['Diagnostico nefrologico', 'Diagnostico CIE-10', 'Tratamiento'];

        foreach($tiposPlaneacion as $tipoPlan){

            $nuevoPlan = new TipoPlaneacion();

            $nuevoPlan->tipo_plan = $tipoPlan;

            $nuevoPlan->save();
        }
    }
}
