<?php

namespace App\Http\Controllers;
use App\Models\Paciente;
use App\Models\CitaPaciente;
use App\Models\CitaEstado;
use App\Models\CitaSubjetivo;
use App\Models\CitaObjetivo;
use App\Models\CitaAnalisis;
use App\Models\CitaPlaneacion;
use stdClass;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function getViewMain(){
        $citaEstados = CitaEstado::all();
        $pacientes = Paciente::select('id','nombre_s','apellido_paterno','apellido_materno')->get();
        return view('agenda.main', compact(['pacientes','citaEstados']));
    }

    // metodo que envia los datos (citas) al fullcalendar
    public function getCitas(){
        
        $pacientes = Paciente::select('id','nombre_s','apellido_paterno','apellido_materno')->get();
        $citasPacientes = CitaPaciente::whereIn("paciente_id", $pacientes->modelKeys())->get();
        $citasArreglo = [];

        foreach ($citasPacientes as $citaPaciente){
            $sesionObjeto = new stdClass();

            $sesionObjeto->title = $citaPaciente->getPaciente->nombre_s . ' ' . $citaPaciente->getPaciente->apellido_paterno . ' ' . $citaPaciente->getPaciente->apellido_materno;
            $sesionObjeto->start = $citaPaciente->fecha . ' ' . $citaPaciente->hora_inicio;
            $sesionObjeto->end = $citaPaciente->fecha . ' ' . $citaPaciente->hora_termino;
            $sesionObjeto->resourceId = "a"; 
            $sesionObjeto->sesionId = $citaPaciente->id;
            $sesionObjeto->statusSesion = $citaPaciente->cita_estado_id;    
            $sesionObjeto->color = $citaPaciente->getEstado->class_color;

            array_push($citasArreglo, $sesionObjeto);
        }
        return $citasArreglo;
    }

    // este metodo es invocado cada que el usuario selecciona un id de paciente distinto en el input select
    public function getPaciente($pacienteId){
        $paciente = Paciente::where("id", $pacienteId);

        return $paciente->with("getCitas")->get();
    }

    public function insertCitas(Request $request){

        $totalInsertadas = 0;

        foreach ($request->arrayNvasSesiones as $nuevaSesion) {
            
            $citaDuplicada = CitaPaciente::where('paciente_id', $nuevaSesion['pacienteId'])
                ->where('fecha', $nuevaSesion['fechaNva'])
                ->get();

            $citasConcluidasPosteriores = CitaPaciente::where('paciente_id', $nuevaSesion["pacienteId"])
            ->where('cita_estado_id', 4)
            //->whereNotIn('id', [$sesion->id])
            ->where('fecha', '>=', $nuevaSesion["fechaNva"])
            ->get();
            
            if(count($citaDuplicada) < 1 && count($citasConcluidasPosteriores) < 1){

                $totalInsertadas = $totalInsertadas + 1;

                $sesion = new CitaPaciente();

                $sesion->paciente_id = $nuevaSesion["pacienteId"];
                $sesion->fecha = $nuevaSesion["fechaNva"];
                $sesion->hora_inicio = $nuevaSesion["horaIniciaNva"];
                $sesion->hora_termino = $nuevaSesion["horaIniciaNva"]; //$nuevaSesion["horaTerminaNva"];
                $sesion->user_id = 0;
                $sesion->cita_estado_id = 1; // estado inicial 1 = Programado 

                $sesion->save();
            }
        }

        $userAlert = ['titulo' => 'Correcto', 'mensaje' => $totalInsertadas . ' citas agendadas correctamente', 'icono' => 'success', 'operacion' => 'Programadas', 'background' => 'info'];

        return $userAlert;
    }

    public function updateCita(Request $request){
        
        // 4 = concluido
        if ($request->statusId == 4){
            $userAlert = ['titulo' => '¡Aviso!', 'mensaje' => '<h4><b>Aviso</b>: Unicamente el personal Medico puede concluir una cita en sistema</h4>', 'icono' => 'warning', 'operacion' => '', 'background' => ''];
            return $userAlert;
        }

        $sesion = CitaPaciente::find($request->sesionId);

        if ($sesion->cita_estado_id == 4){
            $userAlert = ['titulo' => '¡Aviso!', 'mensaje' => '<h4><b>Aviso</b>: La cita que intenta modificar ya ha sido concluida por parte del personal Medico, por lo que ya no es posible realizar modificaciones</h4>', 'icono' => 'warning', 'operacion' => '', 'background' => ''];
            return $userAlert;
        }

        $citasConcluidasPosteriores = CitaPaciente::where('paciente_id', $sesion->paciente_id)
            ->where('cita_estado_id', 4)
            ->whereNotIn('id', [$sesion->id])
            ->where('fecha', '>=', $request->fecha)
            ->get();
        
        if(count($citasConcluidasPosteriores) > 0 && $request->statusId == 2){
            $userAlert = ['titulo' => '¡Aviso!', 'mensaje' => '<h4><b>Aviso</b>: El sistema detecta que existen citas concluidas en fechas posteriores, por lo que no es posible reactivar la cita en pantalla</h4>', 'icono' => 'warning', 'operacion' => '', 'background' => ''];
            return $userAlert;
        }

        $sesion->fecha = $request->fecha;
        $sesion->hora_inicio = $request->horaInicio;
        $sesion->cita_estado_id = $request->statusId;
        if($request->statusId == '3'){
            $sesion->user_id = 0;

            // eliminar registros de la nota de evolucion
            CitaSubjetivo::where('cita_paciente_id', $sesion->id)->delete();
            CitaObjetivo::where('cita_paciente_id', $sesion->id)->delete();
            CitaAnalisis::where('cita_paciente_id', $sesion->id)->delete();
            CitaPlaneacion::where('cita_paciente_id', $sesion->id)->delete();
        }

        $sesion->save();

        $userAlert = ['titulo' => 'Correcto', 'mensaje' => '<h4><b>Sesion actualizada</b>: De ' . $sesion->hora_inicio . " a " . $sesion->hora_termino . '</h4>', 'icono' => 'primary', 'operacion' => '', 'background' => ''];

        return $userAlert;
    }

    public function deleteCita(Request $request){
        $sesion = CitaPaciente::find($request->sesionId);
        
        // 4 = concluido
        if ($sesion->cita_estado_id == 4){
            $userAlert = ['titulo' => '¡Aviso!', 'mensaje' => 'La cita que intenta borrar ya ha sido concluida por parte del personal Medico, por lo que ya no es posible realizar modificaciones', 'icono' => 'warning', 'operacion' => '', 'background' => ''];
            return $userAlert;
        }
        
        // eliminar registros de la nota de evolucion
        CitaSubjetivo::where('cita_paciente_id', $sesion->id)->delete();
        CitaObjetivo::where('cita_paciente_id', $sesion->id)->delete();
        CitaAnalisis::where('cita_paciente_id', $sesion->id)->delete();
        CitaPlaneacion::where('cita_paciente_id', $sesion->id)->delete();

        $sesion->delete();

        $userAlert = ['titulo' => '¡Aviso!', 'mensaje' => 'Cita eliminada correctamente', 'icono' => 'warning', 'operacion' => '', 'background' => ''];
        return $userAlert;
    }
}
