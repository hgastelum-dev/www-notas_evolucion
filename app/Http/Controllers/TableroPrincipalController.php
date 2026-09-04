<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DiagnosticoCIE10;
use App\Models\CitaEstado;
use App\Models\CitaPaciente;
use App\Models\Paciente;
use App\Models\CitaObjetivo;

class TableroPrincipalController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function getViewMain(){

        $citaEstados = CitaEstado::all();
        $enProgreso = CitaPaciente::where('en_progreso', true)->count();

        $pacientes = Paciente::select('id', 'nombre_s', 'apellido_paterno')->orderBy('nombre_s')->get();

        return view('tablero_principal.main', compact(['citaEstados', 'enProgreso', 'pacientes']));
    }

    public function citasConcluidas($id)
    {
        $citas = CitaPaciente::where('paciente_id', $id)
            ->where('cita_estado_id', 4)
            ->orderBy('fecha', 'asc')
            ->get(['id', 'fecha']);

        return response()->json($citas);
    }

    public function parametros(Request $request)
    {
        $citaIds = $request->citas;

        $columnas = \Schema::getColumnListing('citas_objetivo');

        // excluir columnas no deseadas
        $columnas = collect($columnas)->reject(function ($col) {
            return in_array($col, [
                'id', 'cita_paciente_id', 'created_at', 'updated_at', 'exploracion_fisica'
            ]);
        })->values();

        // buscar columnas que si tengan valores
        $parametrosDisponibles = [];

        foreach ($columnas as $col) {
            $tieneValores = CitaObjetivo::whereIn('cita_paciente_id', $citaIds)
                ->whereNotNull($col)
                ->exists();

            if ($tieneValores) {
                $parametrosDisponibles[] = $col;
            }
        }

        return response()->json($parametrosDisponibles);
    }

    public function graficar(Request $request)
    {
        $parametro = $request->parametro;
        $citaIds = $request->citas;

        $datos = CitaObjetivo::whereIn('cita_paciente_id', $citaIds)
            ->join('citas_pacientes', 'citas_objetivo.cita_paciente_id', '=', 'citas_pacientes.id')
            ->orderBy('citas_pacientes.fecha', 'asc')
            ->get([
                'citas_pacientes.fecha',
                "citas_objetivo.$parametro"
            ]);

        return response()->json($datos);
    }

    public function getDiagnosticosCie10(Request $request){
        if (isset($request->term)) {

            $query = DiagnosticoCIE10::where('diagnostico', 'like', '%' . $request->term . '%')->get();

            if (count($query) > 0) {
                $res = $query;
            } else {
                $res = array();
            }
            return $res;
        }
    }
}
