<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;
use Carbon\Carbon;

class InsertaUsuarioAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $fechaHoraActual = Carbon::now("America/Los_Angeles");

        $nuevoAdmin = new User();

        $nuevoAdmin->name = 'Administrador';
        $nuevoAdmin->email = 'admin@admin';
        $nuevoAdmin->password = Hash::make('admin');
        $nuevoAdmin->activo = true;

        $nuevoAdmin->created_at = $fechaHoraActual;
        $nuevoAdmin->updated_at = $fechaHoraActual;

        $nuevoAdmin->save();

        print('Usuario Administrador creado: ' . $nuevoAdmin->email . "\n");
    }
}
