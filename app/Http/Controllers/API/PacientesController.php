<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;
use App\Models\Paciente;

class PacientesController extends BaseController
{

    public function getPacientes(){

        $pacientes = Paciente::all();
        return $pacientes;
    }

    public function resetPacientes(){

        $pacientes = Paciente::truncate();
        return $this->sendResponse('Done: reset tabla Pacientes', 'Realizado');
    }

    public function insert(Request $request){

        $input = $request->all();
   
        $validator = Validator::make($input, [
            'nombre_s' => 'required|max:255',
            'email' => 'nullable|unique:pacientes|max:255',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Error: ', $validator->errors());       
        }
   
        $paciente = new Paciente();

        $paciente->nombre_s = $request->nombre_s;
	$paciente->apellido_paterno = $request->apellido_paterno;
	$paciente->apellido_materno = $request->apellido_materno;
        $paciente->email = $request->email;
	$paciente->foto_path = $request->foto_path;

        // datos predeterminados de la ficha de identificacion 
        $paciente->tipo_sangre = $request->tipo_sangre;
	$paciente->alergias = $request->alergias;
        $paciente->fecha_nacimiento = $request->fecha_nacimiento;
        $paciente->direccion = $request->direccion;
        $paciente->telefono = $request->telefono;
        $paciente->lugar_nacimiento = $request->lugar_nacimiento;
        $paciente->lugar_residencia = $request->lugar_residencia;
        $paciente->genero_id = $request->generoId;
        $paciente->ocupacion = $request->ocupacion;
        $paciente->escolaridad = $request->escolaridad;
        $paciente->religion = $request->religion;
        $paciente->estado_civil_id = $request->estado_civil_id;
        $paciente->fecha_ingreso = $request->fecha_ingreso;
        $paciente->fecha_elaboracion = $request->fecha_elaboracion;
        $paciente->user_id = 1;
        $paciente->activo = true;
        $paciente->created_at = Carbon::now('America/Los_Angeles');

        $paciente->save();

        $nuevoPaciente = Paciente::find($paciente->id);

        $nuevoPaciente->numero_expediente = 'EXP_' . $paciente->id;

        $nuevoPaciente->save();
   
        return $this->sendResponse($paciente, 'Paciente registrado');
    }
}
