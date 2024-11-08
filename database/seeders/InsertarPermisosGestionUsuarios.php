<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class InsertarPermisosGestionUsuarios extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permisosGestionUsuarios = ['Usuarios_Ver', 'Usuarios_Crear', 'Usuarios_Editar', 'Usuarios_Eliminar', 'Usuarios_Gestionar_Permisos'];
        $usuario = User::find(1);

        foreach($permisosGestionUsuarios as $permiso){

            $nuevoPermiso = Permission::create(['name' => $permiso]);

            $usuario->givePermissionTo($nuevoPermiso);
        }
    }
}
