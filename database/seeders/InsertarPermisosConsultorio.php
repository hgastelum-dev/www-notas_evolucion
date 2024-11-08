<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class InsertarPermisosConsultorio extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permisosConsultorio = ['CitaGestionar', 'CitaAtender'];
        $usuario = User::find(1);

        foreach($permisosConsultorio as $permisoConsultorio){

            $nuevoPermiso = Permission::create(['name' => $permisoConsultorio]);

            $usuario->givePermissionTo($nuevoPermiso);
        }
    }
}
