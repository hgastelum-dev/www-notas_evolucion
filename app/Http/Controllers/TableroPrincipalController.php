<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DiagnosticoCIE10;
use App\Models\CitaEstado;
use App\Models\CitaPaciente;

class TableroPrincipalController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function getViewMain(){

        $citaEstados = CitaEstado::all();
        $enProgreso = CitaPaciente::where('en_progreso', true)->count();

        return view('tablero_principal.main', compact(['citaEstados', 'enProgreso']));
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
