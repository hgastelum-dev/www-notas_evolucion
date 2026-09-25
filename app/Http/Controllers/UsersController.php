<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use Hash;
use Auth;
use \Mpdf\Mpdf;

class UsersController extends Controller
{
    public function __construct(){
        $this->middleware(['auth','verified']);
    }

    public function getViewUsers(){

        if (!Auth::user()->can('Usuarios_Ver')){
            return view('redirecciones.permiso-denegado');
        }
        
        $users = User::all();

        return view('users.main', compact(['users']));
    }

    public function getViewNewUser(){

        if (!Auth::user()->can('Usuarios_Crear')){
            return view('redirecciones.permiso-denegado');
        }

        $permisos = Permission::all();

        return view('users.registrar', compact(['permisos']));
    }

    public function insertUser(Request $request){

        if (!Auth::user()->can('Usuarios_Crear')){
            return view('redirecciones.permiso-denegado');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:1|max:250|unique:users',
            'email' => 'required|string|email|min:1|max:255|unique:users',
            'password' => 'required|string|min:8|max:250'
        ]);
 
        if ( $validator->fails() ){
            return redirect('/usuarios/registrar')
                ->withErrors($validator)
                ->withInput();
        }

        $fechaHoraActual = Carbon::now('America/Los_Angeles');

        $nuevoUsuario = new User();

        $nuevoUsuario->name = $request->name;
        $nuevoUsuario->email = $request->email;
        $nuevoUsuario->password = Hash::make($request->password);

        if ($request->activo){
            $nuevoUsuario->activo = true;
        }

        $nuevoUsuario->created_at = $fechaHoraActual;
        $nuevoUsuario->updated_at = $fechaHoraActual;

        $nuevoUsuario->save();

        if ($request->permisos && Auth::user()->can('Usuarios_Gestionar_Permisos')){
            foreach ($request->permisos as $key => $permiso) {
                $nuevoUsuario->givePermissionTo($permiso);
            }
        }

        $request->session()->flash('userAlerts', ['titulo' => 'Registro de usuario realizado exitosamente:', 'mensaje' => $nuevoUsuario->name, 'icono' => 'success']);

        return redirect('/usuarios/editar/' . $nuevoUsuario->id);
    }

    public function getViewEditUser($userId){

        if (!Auth::user()->can('Usuarios_Eliminar') && !Auth::user()->can('Usuarios_Editar') && !Auth::user()->can('Usuarios_Gestionar_Permisos')){
            return view('redirecciones.permiso-denegado');
        }

        $user = User::findOrFail($userId);

        $permisosNoAsignados = Permission::whereNotIn('id', $user->permissions->modelKeys())->get();

        return view('users.editar', compact(['user', 'permisosNoAsignados']));
    }

    public function updateUser(Request $request){

        if (!Auth::user()->can('Usuarios_Editar')){
            return view('redirecciones.permiso-denegado');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:1|max:250|unique:users,name,' . $request->user_id,
            'email' => 'required|string|email|min:1|max:255|unique:users,email,' . $request->user_id,
            'password' => 'required|string|min:8|max:250'
        ]);

        if ( $validator->fails() ){
            return redirect('/usuarios/editar/' . $request->user_id)
                ->withErrors($validator);
        }

        $fechaHoraActual = Carbon::now('America/Los_Angeles');

        $user = User::findOrFail($request->user_id);

        $user->name = $request->name;
        $user->email = $request->email;

        if($request->password != '**********'){
            $user->password = Hash::make($request->password);
        }

        $user->activo = (bool) $request->activo;

        $user->updated_at = $fechaHoraActual;

        $user->save();

        $request->session()->flash('userAlerts', ['titulo' => 'Registro de usuario actualizado exitosamente:', 'mensaje' => $user->name, 'icono' => 'success']);

        return redirect('/usuarios/editar/' . $user->id);
    }

    public function getViewDeleteUser($userId, $link){

        if (!Auth::user()->can('Usuarios_Eliminar')){
            return view('redirecciones.permiso-denegado');
        }

        $user = User::findOrFail($userId);

        return view('users.eliminar', compact(['user', 'link']));
    }

    public function deleteUser(Request $request){

        if (!Auth::user()->can('Usuarios_Eliminar')){
            return view('redirecciones.permiso-denegado');
        }

        // nadie puede eliminar su propia cuenta mientras tiene la sesion iniciada
        if ((int) $request->user_id === (int) Auth::id()){
            $request->session()->flash('userAlerts', ['titulo' => 'Operación no permitida', 'mensaje' => 'No puedes eliminar tu propia cuenta mientras tienes la sesión iniciada.', 'icono' => 'error']);
            return redirect('/usuarios');
        }

        $user = User::findOrFail($request->user_id);
        $nombreEliminado = $user->name;

        $user->delete();

        $request->session()->flash('userAlerts', ['titulo' => 'Registro de usuario eliminado exitosamente:', 'mensaje' => $nombreEliminado, 'icono' => 'info']);

        return redirect('/usuarios');
    }

    public function deleteMultipleUsers(Request $request){

        if (!Auth::user()->can('Usuarios_Eliminar')){
            return view('redirecciones.permiso-denegado');
        }

        $usersIds = json_decode($request->users_ids);

        if (!is_array($usersIds) || count($usersIds) < 1){
            $request->session()->flash('userAlerts', ['titulo' => 'Error', 'mensaje' => 'No se recibió ningún usuario para eliminar.', 'icono' => 'error']);
            return redirect('/usuarios');
        }

        $nombresEliminados = [];

        foreach($usersIds as $userId){

            // nadie puede eliminarse a si mismo en un borrado masivo
            if ((int) $userId === (int) Auth::id()){
                continue;
            }

            $usuario = User::find($userId);

            if (!$usuario){
                continue; // id invalido o ya eliminado, se ignora en vez de tronar
            }

            $nombresEliminados[] = $usuario->name;

            $usuario->delete();
        }

        if (count($nombresEliminados) < 1){
            $request->session()->flash('userAlerts', ['titulo' => 'Aviso', 'mensaje' => 'No se eliminó ningún registro (¿intentaste eliminar tu propia cuenta o los ids ya no existían?).', 'icono' => 'warning']);
            return redirect('/usuarios');
        }

        $request->session()->flash('userAlerts', ['titulo' => 'Operacion realizada correctamente', 'mensaje' => 'Registros de usuario eliminados: ' . implode(' / ', $nombresEliminados), 'icono' => 'success']);

        return redirect('/usuarios');
    }

    public function denegarPermiso(Request $request){

        if (!Auth::user()->can('Usuarios_Gestionar_Permisos')){
            return view('redirecciones.permiso-denegado');
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'permiso' => 'required|exists:permissions,name',
        ]);

        if ($validator->fails()){
            return redirect('/usuarios');
        }

        $user = User::findOrFail($request->user_id);

        $user->revokePermissionTo($request->permiso);

        $request->session()->flash('userAlerts', ['titulo' => 'Permiso revocado', 'mensaje' => $request->permiso, 'icono' => 'info']);

        return redirect('/usuarios/editar/' . $user->id);
    }

    public function asignarPermiso(Request $request){

        if (!Auth::user()->can('Usuarios_Gestionar_Permisos')){
            return view('redirecciones.permiso-denegado');
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'permiso' => 'required|exists:permissions,name',
        ]);

        if ($validator->fails()){
            return redirect('/usuarios');
        }

        $user = User::findOrFail($request->user_id);

        $user->givePermissionTo($request->permiso);

        $request->session()->flash('userAlerts', ['titulo' => 'Permiso asignado', 'mensaje' => $request->permiso, 'icono' => 'success']);

        return redirect('/usuarios/editar/' . $user->id);
    }

    public function exportarUsuarios() 
    {
        if (!Auth::user()->can('Usuarios_Ver')){
            return view('redirecciones.permiso-denegado');
        }

        return Excel::download(new UsersExport, 'Usuarios.xlsx');
    }

    public function exportarUsuariosPdf(){

        if (!Auth::user()->can('Usuarios_Ver')){
            return view('redirecciones.permiso-denegado');
        }

        $usuarios = User::all();

        $mpdf = new \Mpdf\Mpdf();
        
        $i = 1;
        foreach($usuarios as $usuario){
            $mpdf->WriteHTML('<span style="font-family: Arial;">' . $i . ' - ' . $usuario->name . ' / ' . $usuario->email . '</span>');

            $i++;
        }

        $mpdf->Output('Usuarios.pdf', 'D');
    }
}