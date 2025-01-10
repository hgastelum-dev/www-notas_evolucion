<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CitaPaciente;
use App\Models\TipoPlaneacion;
use App\Models\CitaSubjetivo;
use App\Models\CitaAnalisis;
use App\Models\CitaPlaneacion;
use App\Models\CitaObjetivo;
use App\Models\Paciente;
use App\Models\NotaHistorica;
use Auth;

class CitaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function iniciarCita(Request $request){
        
        $cita = CitaPaciente::find($request->cita_id);

        if (!Auth::user()->can('CitaAtender') || $cita->getEstado->bloqueo_cita){
            return view('redirecciones.permiso-denegado');
        }

        $citasInconclusas = CitaPaciente::where('paciente_id', $cita->paciente_id)
            ->where('cita_estado_id', '!=', 3)
            ->where('cita_estado_id', '!=', 4)
            ->whereNotIn('id', [$cita->id])
            //->where('user_id', '!=', 0)
            ->where('fecha', '<=', $cita->fecha)
            ->get();

        // validar que no se encuentren citas pendientes de cerrar en sistema
        if(count($citasInconclusas) > 0){

            $citasListado = '';
            foreach($citasInconclusas as $citaInconclusa){

                $citasListado .= '<i class="fas fa-clock"></i>' . $citaInconclusa->fecha . '<br>';
            }

            $request->session()->flash('userAlerts', ['titulo' => 'Notificacion:', 'mensaje' => 'Tiene citas programadas del paciente <b>' . $cita->getPaciente->nombre_s . '</b> que se encuentran pendientes de Concluir.', 'icono' => $citasListado]);
            
            return redirect('/agenda');
            //return 'Tiene citas programadas del paciente ' . $cita->getPaciente->nombre_s . ' que se encuentran pendientes de Concluir.<br>' . $citasInconclusas;
        }

        $citasConcluidas = CitaPaciente::where('paciente_id', $cita->paciente_id)
            ->where('cita_estado_id', 4)
            ->whereNotIn('id', [$cita->id]);

        //return count($citasConcluidas->get());

        if(count($citasConcluidas->get()) > 0 && $cita->user_id == 0){
            // ... logica para extraer la ultima cita concluida (por fecha) e 
            // insertar los datos de la planeacion en la cita que se esta atendiendo
            
            $ultimaCita = CitaPaciente::where('paciente_id', $cita->paciente_id)
            ->where('cita_estado_id', 4)
            ->whereNotIn('id', [$cita->id])
            ->orderBy('fecha', 'DESC')
            ->first();

            $cita->cita_anterior_id = $ultimaCita->id;

            // insertar la planeacion de la ultima cita concluida...
            foreach ($ultimaCita->getPlaneacion as $plan) {
                
                $nuevoPlan = new CitaPlaneacion();
                
                $nuevoPlan->plan = $plan->plan;
                $nuevoPlan->padre_id = $plan->padre_id; // 0
                $nuevoPlan->tipo_plan_id = $plan->tipo_plan_id;
                $nuevoPlan->cita_paciente_id = $cita->id;

                $nuevoPlan->indicador_seguimiento = true;
                $nuevoPlan->paciente_id = $plan->paciente_id;

                $nuevoPlan->save();

                foreach($plan->getTipoPlanAnidado as $planAnidado){
                    
                    $nuevoPlanAnidado = new CitaPlaneacion();
                
                    $nuevoPlanAnidado->plan = $planAnidado->plan;
                    $nuevoPlanAnidado->padre_id = $nuevoPlan->id;
                    $nuevoPlanAnidado->tipo_plan_id = $planAnidado->tipo_plan_id;
                    $nuevoPlanAnidado->cita_paciente_id = $cita->id;

                    $nuevoPlanAnidado->indicador_seguimiento = true;
                    $nuevoPlanAnidado->paciente_id = $plan->paciente_id;

                    $nuevoPlanAnidado->save();    
                }
            }
        } elseif ( count($citasConcluidas->get()) < 1 ) {

            $ultimoSoapHist = NotaHistorica::where('paciente_id', $cita->paciente_id)
                ->orderBy('fecha', 'DESC')
                ->first();

            if(isset($ultimoSoapHist->getPlanHist) && count($ultimoSoapHist->getPlanHist) > 0 && $cita->user_id == 0){
                
                // insertar la planeacion de la ultima Nota historica a la cita actual...
                foreach ($ultimoSoapHist->getPlanHist as $planHist) {
                        
                    $nuevoPlan = new CitaPlaneacion();
                    
                    $nuevoPlan->plan = $planHist->plan;
                    $nuevoPlan->padre_id = 0; // 0
                    $nuevoPlan->tipo_plan_id = $planHist->tipo_plan_id;
                    $nuevoPlan->cita_paciente_id = $cita->id;

                    $nuevoPlan->indicador_seguimiento = true;
                    $nuevoPlan->paciente_id = $planHist->paciente_id;

                    $nuevoPlan->save();
                }
            } else {
                // verificar si hay registros del plan provenientes de la historia clinica sin cita
                $planeacion = CitaPlaneacion::where('paciente_id', $cita->paciente_id)
                    ->where('indicador_seguimiento', 0)
                    ->where('cita_paciente_id', 0)
                    ->where('padre_id', 0)
                    ->get();

                if(count($planeacion) > 0 && $cita->user_id == 0){
                    
                    // insertar la planeacion de la historia clinica...
                    foreach ($planeacion as $plan) {
                            
                        $nuevoPlan = new CitaPlaneacion();
                        
                        $nuevoPlan->plan = $plan->plan;
                        $nuevoPlan->padre_id = $plan->padre_id; // 0
                        $nuevoPlan->tipo_plan_id = $plan->tipo_plan_id;
                        $nuevoPlan->cita_paciente_id = $cita->id;

                        $nuevoPlan->indicador_seguimiento = true;
                        $nuevoPlan->paciente_id = $plan->paciente_id;

                        $nuevoPlan->save();

                        foreach($plan->getTipoPlanAnidado as $planAnidado){
                            
                            $nuevoPlanAnidado = new CitaPlaneacion();
                        
                            $nuevoPlanAnidado->plan = $planAnidado->plan;
                            $nuevoPlanAnidado->padre_id = $nuevoPlan->id;
                            $nuevoPlanAnidado->tipo_plan_id = $planAnidado->tipo_plan_id;
                            $nuevoPlanAnidado->cita_paciente_id = $cita->id;

                            $nuevoPlanAnidado->indicador_seguimiento = true;
                            $nuevoPlanAnidado->paciente_id = $plan->paciente_id;

                            $nuevoPlanAnidado->save();    
                        }
                    }
                }
            }
        }

        $planeacion = CitaPlaneacion::where('paciente_id', $cita->paciente_id)
                ->where('indicador_seguimiento', 0)
                ->where('cita_paciente_id', 0)
                ->where('padre_id', 0)
                ->get();
        $primeraCita = Paciente::find($cita->paciente_id)
            ->getCitas
            ->sortBy('fecha')
            ->first();

        $ultimoSoapHist = NotaHistorica::where('paciente_id', $cita->paciente_id)
                ->orderBy('fecha', 'DESC')
                ->first();

        if(isset($ultimoSoapHist)){
            $totalHist = count($ultimoSoapHist->getPlanHist);
        } else {
            $totalHist = 0;
        }
        
        if($totalHist < 1 && count($planeacion) < 1 && $cita->id == $primeraCita->id){

            /*$userAlert = array(
                'titulo' => 'data',
                'mensaje' => 'data',
                'icono' => 'data',
                'operacion' => 'data',
                'background' => 'data'
            );*/
            
            $request->session()->flash('CitaInicialOpciones', ['titulo' => $cita->id, 'mensaje' => 'El paciente <b>' . $cita->getPaciente->nombre_s . ' ' . $cita->getPaciente->apellido_paterno . ' ' . $cita->getPaciente->apellido_materno . '</b> no cuenta con registros en su <b class="text-danger">Plan inicial</b> o <b class="text-danger">Historial de notas de evolucion</b>', 'icono' => 'info']);

            return redirect('/agenda');

            //$cita->user_id = Auth::user()->id;
            //$cita->en_progreso = true;
            //$cita->save();

            //return redirect('/paciente/plan/' . $cita->getPaciente->id);
            return '.... ¡undefined flow!';
        }
        
        // asigna el ID del usuario que tomo la cita
        $cita->user_id = Auth::user()->id;
        $cita->en_progreso = true;
        $cita->save();
        
        return redirect('/cita/soap01/subjetivo/' . $cita->id);
    }

    public function iniciarPlanInicial(Request $request){
        
        $cita = CitaPaciente::find($request->cita_id);

        $cita->user_id = Auth::user()->id;
        $cita->en_progreso = true;
        $cita->save();

        return redirect('/paciente/plan/' . $cita->getPaciente->id);
    }
    
    public function iniciarSoap(Request $request){

        $cita = CitaPaciente::find($request->cita_id);

        $cita->user_id = Auth::user()->id;
        $cita->en_progreso = true;
        $cita->save();

        return redirect('/cita/soap01/subjetivo/' . $cita->id);
    }

    public function getViewAtenderCita($citaId){

        if (!Auth::user()->can('CitaAtender')){
            return view('redirecciones.permiso-denegado');
        }
        $cita = CitaPaciente::find($citaId);

        if($cita->user_id == 0){
            return 'Acceso denegado. Favor de iniciar la cita desde la vista de la agenda.';
        }

        return view('citas.atender', compact(['cita']));
    }

    public function getViewSoap01($citaId){
        
        if (!Auth::user()->can('CitaAtender')){
            return view('redirecciones.permiso-denegado');
        }

        $cita = CitaPaciente::find($citaId);
        
        if($cita->user_id == 0){
            return 'Acceso denegado. Favor de iniciar la cita desde la vista de la agenda.';
        }

        return view('citas.soap01_subjetivo', compact(['cita']));
    }

    public function updateSoap01(Request $request){

        $validated = $request->validate([
            'subjetivo' => 'required'
        ]);

        $cita = CitaPaciente::find($request->cita_paciente_id);

        if($cita->getSubjetivo){

            $citaSubjetivo = CitaSubjetivo::find($cita->getSubjetivo->id);

            $citaSubjetivo->subjetivo = $request->subjetivo;

            $citaSubjetivo->save();

        } else {

            $citaSubjetivo = new CitaSubjetivo();

            $citaSubjetivo->subjetivo = $request->subjetivo;
            $citaSubjetivo->cita_paciente_id = $request->cita_paciente_id;

            $citaSubjetivo->save();
        }

        $request->session()->flash('userAlerts', ['titulo' => 'Notificacion:', 'mensaje' => 'Actualizado correctamente', 'icono' => 'success']);

        return redirect('/cita/soap01/subjetivo/' . $request->cita_paciente_id . '#btn-s');
    }

    public function getViewSoap02($citaId){

        if (!Auth::user()->can('CitaAtender')){
            return view('redirecciones.permiso-denegado');
        }

        $cita = CitaPaciente::find($citaId);

        if($cita->user_id == 0){
            return 'Acceso denegado. Favor de iniciar la cita desde la vista de la agenda.';
        }

        return view('citas.soap02_objetivo', compact(['cita']));
    }

    public function updateSoap02(Request $request){

        $cita = CitaPaciente::find($request->cita_paciente_id);

        if($cita->getObjetivo){
            
            $citaObjetivo = CitaObjetivo::find($cita->getObjetivo->id);

            $citaObjetivo->ta = $request->ta;
            $citaObjetivo->fc = $request->fc;
            $citaObjetivo->fr = $request->fr;
            $citaObjetivo->temp = $request->temp;
            $citaObjetivo->talla = $request->talla;
            $citaObjetivo->peso = $request->peso;
            $citaObjetivo->imc = $request->imc;
            $citaObjetivo->sat_o2 = $request->sat_o2;
            $citaObjetivo->hb = $request->hb;
            $citaObjetivo->hto = $request->hto;
            $citaObjetivo->vcm = $request->vcm;
            $citaObjetivo->hcm = $request->hcm;
            $citaObjetivo->porcentaje_eritrocitos_hipocromicos = $request->porcentaje_eritrocitos_hipocromicos;
            $citaObjetivo->plaq = $request->plaq;
            $citaObjetivo->leuc = $request->leuc;
            $citaObjetivo->cr = $request->cr;
            $citaObjetivo->ckdepi = $request->ckdepi;
            $citaObjetivo->bun = $request->bun;
            $citaObjetivo->g = $request->g;
            $citaObjetivo->hba1c_porcentaje = $request->hba1c_porcentaje;
            $citaObjetivo->insulina_serica = $request->insulina_serica;
            $citaObjetivo->homa = $request->homa;
            $citaObjetivo->au = $request->au;
            $citaObjetivo->na = $request->na;
            $citaObjetivo->k = $request->k;
            $citaObjetivo->cl = $request->cl;
            $citaObjetivo->ca = $request->ca;
            $citaObjetivo->p = $request->p;
            $citaObjetivo->mg = $request->mg;
            $citaObjetivo->alb = $request->alb;
            $citaObjetivo->col = $request->col;
            $citaObjetivo->tgs = $request->tgs;
            $citaObjetivo->hdl_col = $request->hdl_col;
            $citaObjetivo->ldl_col = $request->ldl_col;
            $citaObjetivo->ego = $request->ego;
            $citaObjetivo->albu_cru = $request->albu_cru;
            $citaObjetivo->exploracion_fisica = $request->exploracion_fisica;

            $citaObjetivo->save();
        } else {
            
            $citaObjetivo = new CitaObjetivo();

            $citaObjetivo->ta = $request->ta;
            $citaObjetivo->fc = $request->fc;
            $citaObjetivo->fr = $request->fr;
            $citaObjetivo->temp = $request->temp;
            $citaObjetivo->talla = $request->talla;
            $citaObjetivo->peso = $request->peso;
            $citaObjetivo->imc = $request->imc;
            $citaObjetivo->sat_o2 = $request->sat_o2;
            $citaObjetivo->hb = $request->hb;
            $citaObjetivo->hto = $request->hto;
            $citaObjetivo->vcm = $request->vcm;
            $citaObjetivo->hcm = $request->hcm;
            $citaObjetivo->porcentaje_eritrocitos_hipocromicos = $request->porcentaje_eritrocitos_hipocromicos;
            $citaObjetivo->plaq = $request->plaq;
            $citaObjetivo->leuc = $request->leuc;
            $citaObjetivo->cr = $request->cr;
            $citaObjetivo->ckdepi = $request->ckdepi;
            $citaObjetivo->bun = $request->bun;
            $citaObjetivo->g = $request->g;
            $citaObjetivo->hba1c_porcentaje = $request->hba1c_porcentaje;
            $citaObjetivo->insulina_serica = $request->insulina_serica;
            $citaObjetivo->homa = $request->homa;
            $citaObjetivo->au = $request->au;
            $citaObjetivo->na = $request->na;
            $citaObjetivo->k = $request->k;
            $citaObjetivo->cl = $request->cl;
            $citaObjetivo->ca = $request->ca;
            $citaObjetivo->p = $request->p;
            $citaObjetivo->mg = $request->mg;
            $citaObjetivo->alb = $request->alb;
            $citaObjetivo->col = $request->col;
            $citaObjetivo->tgs = $request->tgs;
            $citaObjetivo->hdl_col = $request->hdl_col;
            $citaObjetivo->ldl_col = $request->ldl_col;
            $citaObjetivo->ego = $request->ego;
            $citaObjetivo->albu_cru = $request->albu_cru;
            $citaObjetivo->exploracion_fisica = $request->exploracion_fisica;
            
            $citaObjetivo->cita_paciente_id = $request->cita_paciente_id;

            $citaObjetivo->save();
        }

        $request->session()->flash('userAlerts', ['titulo' => 'Notificacion:', 'mensaje' => 'Actualizado correctamente', 'icono' => 'success']);

        return redirect('/cita/soap02/objetivo/' . $request->cita_paciente_id . '#btn-s');
    }

    public function getViewSoap03($citaId){

        if (!Auth::user()->can('CitaAtender')){
            return view('redirecciones.permiso-denegado');
        }
        $cita = CitaPaciente::find($citaId);

        if($cita->user_id == 0){
            return 'Acceso denegado. Favor de iniciar la cita desde la vista de la agenda.';
        }

        return view('citas.soap03_analisis', compact(['cita']));
    }

    public function updateSoap03(Request $request){
        
        $validated = $request->validate([
            'analisis' => 'required'
        ]);

        $cita = CitaPaciente::find($request->cita_paciente_id);

        if($cita->getAnalisis){

            $citaAnalisis = CitaAnalisis::find($cita->getAnalisis->id);

            $citaAnalisis->analisis = $request->analisis;

            $citaAnalisis->save();

        } else {

            $citaAnalisis = new CitaAnalisis();

            $citaAnalisis->analisis = $request->analisis;
            $citaAnalisis->cita_paciente_id = $request->cita_paciente_id;

            $citaAnalisis->save();
        }

        $request->session()->flash('userAlerts', ['titulo' => 'Notificacion:', 'mensaje' => 'Actualizado correctamente', 'icono' => 'success']);

        return redirect('/cita/soap03/analisis/' . $request->cita_paciente_id . '#btn-s');
    }

    public function getViewSoap04($citaId){

        if (!Auth::user()->can('CitaAtender')){
            return view('redirecciones.permiso-denegado');
        }

        $tiposPlaneacion = TipoPlaneacion::all();

        $cita = CitaPaciente::find($citaId);

        if($cita->user_id == 0){
            return 'Acceso denegado. Favor de iniciar la cita desde la vista de la agenda.';
        }

        $grouped = $cita->getPlaneacion->groupBy(function ($item, $key) {
            return TipoPlaneacion::find($item['tipo_plan_id'])->tipo_plan;
            //substr($item['tipo_plan_id'], -3);
        });
         
        $planesAgrupado = $grouped->all();

        return view('citas.soap04_planeacion', compact(['cita', 'tiposPlaneacion', 'planesAgrupado']));
    }

    public function insertSoap04(Request $request){

        $validated = $request->validate([
            'plan' => 'required',
            'tipo_plan_id' => 'required'
        ]);
        
        $citaPlan = new CitaPlaneacion();

        $citaPlan->plan = $request->plan;
        $citaPlan->cita_paciente_id = $request->cita_paciente_id;
        $citaPlan->tipo_plan_id = $request->tipo_plan_id;
        if($request->padre_id){
            $citaPlan->padre_id = $request->padre_id;

        } else {
            $citaPlan->padre_id = 0;
        }
        
        $cita = CitaPaciente::find($request->cita_paciente_id);
        
        if($cita->getCitaAnterior || isset($request->desdeSoap)){
            $citaPlan->indicador_seguimiento = true;
        
        } else {
            $citaPlan->indicador_seguimiento = false;
        }

        $citaPlan->paciente_id = $cita->paciente_id;
        $citaPlan->save();
        
        $request->session()->flash('userAlerts', ['titulo' => 'success', 'mensaje' => '', 'icono' => $citaPlan->id]);
        
        return redirect('/cita/soap04/planeacion/' . $citaPlan->cita_paciente_id . '#tr-tp-' . $citaPlan->id);
    }

    public function updateSoap04(Request $request){

        $validated = $request->validate([
            'plan_edit' => 'required',
            'plan_id_edit' => 'required'
        ]);

        $citaPlan = CitaPlaneacion::find($request->plan_id_edit);

        $citaPlan->plan = $request->plan_edit;

        $citaPlan->save();

        $request->session()->flash('userAlerts', ['titulo' => 'success', 'mensaje' => '', 'icono' => $citaPlan->id]);

        return redirect('/cita/soap04/planeacion/' . $citaPlan->cita_paciente_id . '#tr-tp-' . $citaPlan->id);
    }

    public function deleteSoap04(Request $request){

        $citaPlan = CitaPlaneacion::find($request->cita_planeacion_id);

        // elimina los registros hijos anidados
        if(count($citaPlan->getTipoPlanAnidado) > 0){
            CitaPlaneacion::where('padre_id', $citaPlan->id)->delete();
        } 

        CitaPlaneacion::where('id', $citaPlan->id)->delete();

        return redirect('/cita/soap04/planeacion/' . $citaPlan->cita_paciente_id);
    }

    public function updateLaboratorio(Request $request){

        $citaPaciente = CitaPaciente::find($request->cita_id);

        $citaPaciente->laboratorio = $request->laboratorio;
        $citaPaciente->gabinete = $request->gabinete;

        $citaPaciente->save();

        return redirect('/cita/soap01/subjetivo/' . $citaPaciente->id);
    }

    public function cerrarCita(Request $request){
        
        $citaPaciente = CitaPaciente::find($request->citaId);

        if($request->primerCita == 1){
            if(count($citaPaciente->getPlaneacion) == 0){

                $userAlert = ['titulo' => 'Correcto', 'mensaje' => 'Mensaje', 'icono' => 'success', 'operacion' => 'Programadas', 'background' => 'info'];
                return $userAlert;
    
            } else {
    
                // 4 = concluido
                $citaPaciente->en_progreso = false;
                $citaPaciente->cita_estado_id = 4;
    
                $citaPaciente->save();
    
                return $citaPaciente;
            }
        } else {
            if(!$citaPaciente->getSubjetivo || !$citaPaciente->getObjetivo || !$citaPaciente->getAnalisis || count($citaPaciente->getPlaneacion) == 0){

                $userAlert = ['titulo' => 'Correcto', 'mensaje' => 'Mensaje', 'icono' => 'success', 'operacion' => 'Programadas', 'background' => 'info'];
                return $userAlert;
    
            } else {
    
                // 4 = concluido
                $citaPaciente->en_progreso = false;
                $citaPaciente->cita_estado_id = 4;
    
                $citaPaciente->save();
    
                return $citaPaciente;
            }
        }
    }
}
