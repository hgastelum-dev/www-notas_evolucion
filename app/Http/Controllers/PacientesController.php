<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\PacienteAntecedente;
use App\Models\PacientePadecimiento;
use App\Models\PacienteExploracionFisica;
use App\Models\CitaPaciente;
use App\Models\TipoPlaneacion;
use App\Models\CitaPlaneacion;
use App\Models\NotaHistorica;
use App\Models\NotaHistoricaPlan;
use Intervention\Image\ImageManagerStatic as Image;
use Intervention\Image\ImageManager;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Auth;

class PacientesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function getViewAlta(){

        return view('pacientes.alta');
    }

    public function insertPaciente(Request $request){

        $validated = $request->validate([
            'nombre_s' => 'required|max:255',
            'apellido_paterno' => 'required|max:255',
            'apellido_materno' => 'required|max:255',
            'email' => 'nullable|unique:pacientes|max:255',
        ]);

        $paciente = new Paciente();

        $paciente->nombre_s = $request->nombre_s;
        $paciente->apellido_paterno = $request->apellido_paterno;
        $paciente->apellido_materno = $request->apellido_materno;
        $paciente->email = $request->email;

        // datos predeterminados de la ficha de identificacion 
        $paciente->tipo_sangre = 'Vacio';
        $paciente->fecha_nacimiento = null;
        $paciente->direccion = 'Vacio';
        $paciente->telefono = 'Vacio';
        $paciente->lugar_nacimiento = 'Vacio';
        $paciente->lugar_residencia = 'Vacio';
        $paciente->genero_id = 'Vacio';
        $paciente->ocupacion = 'Vacio';
        $paciente->escolaridad = 'Vacio';
        $paciente->religion = 'Vacio';
        $paciente->estado_civil_id = 'Vacio';
        $paciente->fecha_ingreso = 'Vacio';
        $paciente->fecha_elaboracion = 'Vacio';
        $paciente->user_id = Auth::user()->id;
        $paciente->activo = true;
        $paciente->created_at = Carbon::now('America/Los_Angeles');

        $paciente->save();

        $nuevoPaciente = Paciente::find($paciente->id);

        $nuevoPaciente->numero_expediente = 'EXP_' . $paciente->id;

        $nuevoPaciente->save();

        $request->session()->flash('userAlerts', ['titulo' => 'Nuevo registro de paciente:', 'mensaje' => $paciente->nombre_s, 'icono' => 'success']);

        return redirect('/pacientes');
    }

    public function getViewPacientes(){

        $pacientes = Paciente::select('id', 'numero_expediente','nombre_s','apellido_paterno', 'apellido_materno', 'email')->get();

        return view('pacientes.listado', compact(['pacientes']));
    }

    public function getViewEditar($pacienteId){
        $paciente = Paciente::find($pacienteId);

        $primeraCita = $paciente->getCitas->sortBy('fecha')->first();

        if(isset($primeraCita)){
            $citaEnProgreso = CitaPaciente::where('en_progreso', 1)
                ->whereNotIn('id', [$primeraCita->id])
                ->where('paciente_id', $paciente->id)
                ->first();
        } else {
            $citaEnProgreso = null;
        }
        return view('pacientes.editar', compact(['paciente', 'primeraCita', 'citaEnProgreso']));
    }

    public function updateFechaIngreso(Request $request){
        $paciente = Paciente::find($request->paciente_id);

        $paciente->fecha_ingreso = $request->fecha_ingreso;

        $paciente->save();

        return redirect('/paciente/notas-hist/' . $request->paciente_id);
    }

    public function updatePaciente(Request $request){

        $validated = $request->validate([
            'nombre_s' => 'required|string|min:1|max:250'/*,
            'email' => 'nullable|string|email|min:1|max:255|unique:pacientes,email,' . $request->paciente_id*/
        ]);
        
        $paciente = Paciente::find($request->paciente_id);

        $paciente->nombre_s = $request->nombre_s;
        $paciente->apellido_paterno = $request->apellido_paterno;
        $paciente->apellido_materno = $request->apellido_materno;
        $paciente->email = $request->email;
        $paciente->tipo_sangre = $request->tipo_sangre;
        $paciente->direccion = $request->direccion;
        $paciente->telefono = $request->telefono;
        $paciente->fecha_nacimiento = $request->fecha_nacimiento;
        $paciente->lugar_nacimiento = $request->lugar_nacimiento;
        $paciente->lugar_residencia = $request->lugar_residencia;
        $paciente->cat_procedencia_id = $request->cat_procedencia_id;
        
        if($request->cat_procedencia_id == 'Doctor' || $request->cat_procedencia_id == 'Enfermero' || $request->cat_procedencia_id == 'Paciente'){
            $paciente->contacto_procedencia = $request->contacto_procedencia;
        } else {
            $paciente->contacto_procedencia = '';
        }

        if ( $request->foto_path ){
            $pathFoto = $request->file('foto_path')->store('fotos_pacientes');

            $paciente->foto_path = $pathFoto;
        }

        $paciente->genero_id = $request->cat_genero_id;
        $paciente->ocupacion = $request->ocupacion;
        $paciente->escolaridad = $request->escolaridad;
        $paciente->religion = $request->religion;
        $paciente->estado_civil_id = $request->cat_estado_civil_id;
        $paciente->fecha_ingreso = $request->fecha_ingreso;

        $paciente->save();

        $request->session()->flash('userAlerts', ['titulo' => 'Registro de paciente actualizado:', 'mensaje' => $paciente->nombre_s, 'icono' => 'success']);

        return redirect('/paciente/editar/' . $paciente->id);
    }

    public function viewBorrarPaciente($pacienteId){

        $paciente = Paciente::find($pacienteId);

        return view('pacientes.paciente_borrar', compact(['paciente']));
    }

    public function deletePaciente(Request $request){

        $user = Paciente::find($request->paciente_id);

        $user->delete();

        CitaPaciente::where('paciente_id', $user->id)->delete();

        $request->session()->flash('userAlerts', ['titulo' => 'Registro de paciente eliminado exitosamente:', 'mensaje' => $user->nombre_s, 'icono' => 'info']);

        return redirect('/pacientes');
    }

    public function getFotoPaciente($pacienteId)
    {   
        $paciente = Paciente::find($pacienteId);

        $storagePath = storage_path('app/'.$paciente->foto_path);
        
        return Image::make($storagePath)->response();
    }

    public function getViewAntecedentes($pacienteId){
        $paciente = Paciente::find( $pacienteId );

        $primeraCita = $paciente->getCitas->sortBy('fecha')->first();

        if(isset($primeraCita)){
            $citaEnProgreso = CitaPaciente::where('en_progreso', 1)
                ->whereNotIn('id', [$primeraCita->id])
                ->where('paciente_id', $paciente->id)
                ->first();
        } else {
            $citaEnProgreso = null;
        }

        return view("pacientes.paciente_antecedentes", compact(["paciente", 'primeraCita', 'citaEnProgreso']));
    }

    public function updateAntecedentes(Request $request){
        $paciente = Paciente::find($request->paciente_id);

        if ( $paciente->getAntecedentes ){
            $antecedente = PacienteAntecedente::where("paciente_id", $request->paciente_id)->first();
        } else {
            $antecedente = new PacienteAntecedente();
        }

        $antecedente->paciente_id = $request->paciente_id;

        $antecedente->he_diabetes = $request->he_diabetes;
        $antecedente->he_has = $request->he_has;
        $antecedente->he_car_izq = $request->he_car_izq;
        $antecedente->he_cancer = $request->he_cancer;
        $antecedente->he_neumopatia = $request->he_neumopatia;
        $antecedente->he_enf_renal = $request->he_enf_renal;
        $antecedente->he_enf_hepatica = $request->he_enf_hepatica;
        $antecedente->he_otros = $request->he_otros;
        $antecedente->pnp_tabaco = $request->pnp_tabaco;
        $antecedente->pnp_tabaco_cantidad = $request->pnp_tabaco_cantidad;
        $antecedente->pnp_tabaco_inicio = $request->pnp_tabaco_inicio;
        $antecedente->pnp_tabaco_fin = $request->pnp_tabaco_fin;
        $antecedente->pnp_oh = $request->pnp_oh;
        $antecedente->pnp_oh_cantidad = $request->pnp_oh_cantidad;
        $antecedente->pnp_oh_inicio = $request->pnp_oh_inicio;
        $antecedente->pnp_oh_fin = $request->pnp_oh_fin;
        $antecedente->pnp_toxicos = $request->pnp_toxicos;
        $antecedente->pnp_toxicos_inicio = $request->pnp_toxicos_inicio;
        $antecedente->pnp_toxicos_fin = $request->pnp_toxicos_fin;
        $antecedente->pnp_toxicos_tipo = $request->pnp_toxicos_tipo;
        $antecedente->pnp_gineco_obstetricos = $request->pnp_gineco_obstetricos;
        $antecedente->pnp_otro = $request->pnp_otro;
        $antecedente->pp_qx = $request->pp_qx;
        $antecedente->pp_qx_tipo = $request->pp_qx_tipo;
        $antecedente->pp_fx = $request->pp_fx;
        $antecedente->pp_fx_tipo = $request->pp_fx_tipo;
        $antecedente->pp_alergia = $request->pp_alergia;
        $antecedente->pp_transfusiones = $request->pp_transfusiones;
        $antecedente->pp_transfusiones_numero = $request->pp_transfusiones_numero;
        $antecedente->pp_transfusiones_inicial = $request->pp_transfusiones_inicial;
        $antecedente->pp_transfusiones_ultima = $request->pp_transfusiones_ultima;
        $antecedente->pp_patias = $request->pp_patias;
        $antecedente->pp_anos = $request->pp_anos;

        $antecedente->save();
        
        $request->session()->flash('userAlerts', ['titulo' => 'Correcto', 'mensaje' => 'Antecedentes registrados exitosamente...', 'icono' => 'success']);

        return redirect("/paciente/antecedentes/" . $request->paciente_id);
    }

    public function getViewPadecimientos($pacienteId){

        $paciente = Paciente::find( $pacienteId );

        $primeraCita = $paciente->getCitas->sortBy('fecha')->first();

        if(isset($primeraCita)){
            $citaEnProgreso = CitaPaciente::where('en_progreso', 1)
            ->whereNotIn('id', [$primeraCita->id])
            ->where('paciente_id', $paciente->id)
            ->first();
        } else {
            $citaEnProgreso = null;
        }

        return view('pacientes.paciente_padecimientos', compact(['paciente', 'primeraCita', 'citaEnProgreso']));
    }

    public function updatePadecimientos(Request $request){
        $paciente = Paciente::find($request->paciente_id);

        if ( $paciente->getPadecimientos ){
            $padecimiento = PacientePadecimiento::where("paciente_id", $request->paciente_id)->first();

            
        } else {
            $padecimiento = new PacientePadecimiento();
            
        }

        $padecimiento->paciente_id = $request->paciente_id;
        $padecimiento->padecimiento = $request->padecimiento;

        $padecimiento->save();

        $request->session()->flash('userAlerts', ['titulo' => 'Correcto', 'mensaje' => 'Padecimientos actualizados exitosamente...', 'icono' => 'success']);

        return redirect('/paciente/padecimientos/' . $paciente->id);
    }

    public function getViewExpFisica($pacienteId){

        $paciente = Paciente::find( $pacienteId );

        $primeraCita = $paciente->getCitas->sortBy('fecha')->first();

        if(isset($primeraCita)){
            $citaEnProgreso = CitaPaciente::where('en_progreso', 1)
                ->whereNotIn('id', [$primeraCita->id])
                ->where('paciente_id', $paciente->id)
                ->first();
        } else {
            $citaEnProgreso = null;
        }

        return view('pacientes.paciente_expfisica', compact(['paciente', 'primeraCita', 'citaEnProgreso']));
    }

    public function updateExpFisica(Request $request){
        $paciente = Paciente::find($request->paciente_id);

        if ( isset($paciente->getExploracionFisica) ){
            $expFisica = PacienteExploracionFisica::where("paciente_id", $request->paciente_id)->first();

            $expFisica->ta = $request->ta;
            $expFisica->ta2 = $request->ta2;
            $expFisica->fc = $request->fc;
            $expFisica->fr = $request->fr;
            $expFisica->temp = $request->temp;
            $expFisica->talla = $request->talla;
            $expFisica->peso = $request->peso;
            $expFisica->imc = $request->imc;
            $expFisica->sat_o2 = $request->sat_o2;
            $expFisica->hb = $request->hb;
            $expFisica->hto = $request->hto;
            $expFisica->vcm = $request->vcm;
            $expFisica->hcm = $request->hcm;
            $expFisica->porcentaje_eritrocitos_hipocromicos = $request->porcentaje_eritrocitos_hipocromicos;
            $expFisica->plaq = $request->plaq;
            $expFisica->leuc = $request->leuc;
            $expFisica->cr = $request->cr;
            $expFisica->ckdepi = $request->ckdepi;
            $expFisica->bun = $request->bun;
            $expFisica->g = $request->g;
            $expFisica->hba1c_porcentaje = $request->hba1c_porcentaje;
            $expFisica->insulina_serica = $request->insulina_serica;
            $expFisica->homa = $request->homa;
            $expFisica->au = $request->au;
            $expFisica->na = $request->na;
            $expFisica->k = $request->k;
            $expFisica->cl = $request->cl;
            $expFisica->ca = $request->ca;
            $expFisica->p = $request->p;
            $expFisica->mg = $request->mg;
            $expFisica->alb = $request->alb;
            $expFisica->col = $request->col;
            $expFisica->tgs = $request->tgs;
            $expFisica->hdl_col = $request->hdl_col;
            $expFisica->ldl_col = $request->ldl_col;
            $expFisica->ego = $request->ego;
            $expFisica->albu_cru = $request->albu_cru;
            $expFisica->tsh = $request->tsh;
            $expFisica->vit_d_serica = $request->vit_d_serica;
        } else {
            $expFisica = new PacienteExploracionFisica();
            
            $expFisica->ta = $request->ta;
            $expFisica->ta2 = $request->ta2;
            $expFisica->fc = $request->fc;
            $expFisica->fr = $request->fr;
            $expFisica->temp = $request->temp;
            $expFisica->talla = $request->talla;
            $expFisica->peso = $request->peso;
            $expFisica->imc = $request->imc;
            $expFisica->sat_o2 = $request->sat_o2;
            $expFisica->hb = $request->hb;
            $expFisica->hto = $request->hto;
            $expFisica->vcm = $request->vcm;
            $expFisica->hcm = $request->hcm;
            $expFisica->porcentaje_eritrocitos_hipocromicos = $request->porcentaje_eritrocitos_hipocromicos;
            $expFisica->plaq = $request->plaq;
            $expFisica->leuc = $request->leuc;
            $expFisica->cr = $request->cr;
            $expFisica->ckdepi = $request->ckdepi;
            $expFisica->bun = $request->bun;
            $expFisica->g = $request->g;
            $expFisica->hba1c_porcentaje = $request->hba1c_porcentaje;
            $expFisica->insulina_serica = $request->insulina_serica;
            $expFisica->homa = $request->homa;
            $expFisica->au = $request->au;
            $expFisica->na = $request->na;
            $expFisica->k = $request->k;
            $expFisica->cl = $request->cl;
            $expFisica->ca = $request->ca;
            $expFisica->p = $request->p;
            $expFisica->mg = $request->mg;
            $expFisica->alb = $request->alb;
            $expFisica->col = $request->col;
            $expFisica->tgs = $request->tgs;
            $expFisica->hdl_col = $request->hdl_col;
            $expFisica->ldl_col = $request->ldl_col;
            $expFisica->ego = $request->ego;
            $expFisica->albu_cru = $request->albu_cru;
            $expFisica->tsh = $request->tsh;
            $expFisica->vit_d_serica = $request->vit_d_serica;
        }

        $expFisica->paciente_id = $request->paciente_id;
        $expFisica->padecimiento = $request->exploracion_fisica;

        $expFisica->save();

        $request->session()->flash('userAlerts', ['titulo' => 'Correcto', 'mensaje' => 'Exploracion fisica actualizada exitosamente...', 'icono' => 'success']);

        return redirect('/paciente/exp-fisica/' . $paciente->id);
    }

    public function getViewPlan($pacienteId){
        $tiposPlaneacion = TipoPlaneacion::all();

        $paciente = Paciente::find( $pacienteId );

        $planeacion = CitaPlaneacion::where('paciente_id', $pacienteId)
            ->where('indicador_seguimiento', false)
            ->where('padre_id', 0)
            ->get();

        $grouped = $planeacion->groupBy(function ($item, $key) {
            return TipoPlaneacion::find($item['tipo_plan_id'])->tipo_plan;
            //substr($item['tipo_plan_id'], -3);
        });
         
        $planesAgrupado = $grouped->all();

        $primeraCita = $paciente->getCitas->sortBy('fecha')->first();

        if(isset($primeraCita)){
            $citaEnProgreso = CitaPaciente::where('en_progreso', 1)
                ->whereNotIn('id', [$primeraCita->id])
                ->where('paciente_id', $paciente->id)
                ->first();
        } else {
            $citaEnProgreso = null;
        }

        return view('pacientes.plan', compact(['paciente', 'tiposPlaneacion', 'planesAgrupado', 'primeraCita', 'citaEnProgreso']));
    }
    
    public function insertPlan(Request $request){
        $validated = $request->validate([
            'plan' => 'required',
            'tipo_plan_id' => 'required'
        ]);

        /*if(!$request->cita_inicial_id){
            return redirect('/paciente/plan/' . $request->paciente_id);
        }*/
        
        $citaPlan = new CitaPlaneacion();

        $citaPlan->plan = $request->plan;

        if($request->cita_inicial_id){
            $citaPlan->cita_paciente_id = $request->cita_inicial_id;
        } else {
            $citaPlan->cita_paciente_id = 0;
        }
        
        $citaPlan->tipo_plan_id = $request->tipo_plan_id;
        if($request->padre_id){
            $citaPlan->padre_id = $request->padre_id;

        } else {
            $citaPlan->padre_id = 0;
        }
        
        $citaPlan->indicador_seguimiento = false;
        $citaPlan->paciente_id = $request->paciente_id;

        $citaPlan->save();
        
        $request->session()->flash('userAlerts', ['titulo' => 'success', 'mensaje' => '', 'icono' => $citaPlan->id]);
        
        return redirect('/paciente/plan/' . $citaPlan->paciente_id . '#tr-tp-' . $citaPlan->id);
    }
    
    public function updatePlan(Request $request){
        $validated = $request->validate([
            'plan_edit' => 'required',
            'plan_id_edit' => 'required'
        ]);

        $citaPlan = CitaPlaneacion::find($request->plan_id_edit);

        $citaPlan->plan = $request->plan_edit;

        $citaPlan->save();

        $request->session()->flash('userAlerts', ['titulo' => 'success', 'mensaje' => '', 'icono' => $citaPlan->id]);

        return redirect('/paciente/plan/' . $citaPlan->paciente_id . '#tr-tp-' . $citaPlan->id);
    }

    public function deletePlan(Request $request){
        $citaPlan = CitaPlaneacion::find($request->cita_planeacion_id);

        // elimina los registros hijos anidados
        if(count($citaPlan->getTipoPlanAnidado) > 0){
            CitaPlaneacion::where('padre_id', $citaPlan->id)->delete();
        } 

        CitaPlaneacion::where('id', $citaPlan->id)->delete();

        return redirect('/paciente/plan/' . $citaPlan->paciente_id);
    }

    public function getViewNotasHist($pacienteId){

        $paciente = Paciente::find($pacienteId);

        $notasHistoricas = $paciente->getNotasHistoricas;

        return view('pacientes.notas_historicas', compact(['paciente', 'notasHistoricas']));
    }

    public function insertNotasHist(Request $request){

        $nuevaNota = new NotaHistorica();

        $nuevaNota->fecha = $request->fecha;
        $nuevaNota->subjetivo = $request->subjetivo;
        $nuevaNota->obj_ta = $request->ta;
        $nuevaNota->obj_ta2 = $request->ta2;
        $nuevaNota->obj_fc = $request->fc;
        $nuevaNota->obj_fr = $request->fr;
        $nuevaNota->obj_temp = $request->temp;
        $nuevaNota->obj_talla = $request->talla;
        $nuevaNota->obj_peso = $request->peso;
        $nuevaNota->obj_imc = $request->imc;
        $nuevaNota->obj_sat_o2 = $request->sat_o2;
        $nuevaNota->obj_hb = $request->hb;
        $nuevaNota->obj_hto = $request->hto;
        $nuevaNota->obj_vcm = $request->vcm;
        $nuevaNota->obj_hcm = $request->hcm;
        $nuevaNota->obj_porcentaje_eritrocitos_hipocromicos = $request->porcentaje_eritrocitos_hipocromicos;
        $nuevaNota->obj_plaq = $request->plaq;
        $nuevaNota->obj_leuc = $request->leuc;
        $nuevaNota->obj_cr = $request->cr;
        $nuevaNota->obj_ckdepi = $request->ckdepi;
        $nuevaNota->obj_bun = $request->bun;
        $nuevaNota->obj_g = $request->g;
        $nuevaNota->obj_hba1c_porcentaje = $request->hba1c_porcentaje;
        $nuevaNota->obj_insulina_serica = $request->insulina_serica;
        $nuevaNota->obj_homa = $request->homa;
        $nuevaNota->obj_au = $request->au;
        $nuevaNota->obj_na = $request->na;
        $nuevaNota->obj_k = $request->k;
        $nuevaNota->obj_cl = $request->cl;
        $nuevaNota->obj_ca = $request->ca;
        $nuevaNota->obj_p = $request->p;
        $nuevaNota->obj_mg = $request->mg;
        $nuevaNota->obj_alb = $request->alb;
        $nuevaNota->obj_col = $request->col;
        $nuevaNota->obj_tgs = $request->tgs;
        $nuevaNota->obj_hdl_col = $request->hdl_col;
        $nuevaNota->obj_ldl_col = $request->ldl_col;
        $nuevaNota->obj_ego = $request->ego;
        $nuevaNota->obj_albu_cru = $request->albu_cru;
        $nuevaNota->obj_exploracion_fisica = $request->exploracion_fisica;
        $nuevaNota->analisis = $request->analisis;
        $nuevaNota->paciente_id = $request->paciente_id;

        $nuevaNota->created_at = Carbon::now('America/Los_Angeles');
        $nuevaNota->updated_at = Carbon::now('America/Los_Angeles');

        $nuevaNota->save();

        foreach($request->plan as $key => $plan){
            
            $notaHistPlan = new NotaHistoricaPlan();
            
            $notaHistPlan->plan = $plan['plan'];
            $notaHistPlan->tipo_plan_id = $plan['tipo_plan_id'];
            $notaHistPlan->paciente_id = $request->paciente_id;
            $notaHistPlan->nota_historica_id = $nuevaNota->id;

            $notaHistPlan->updated_at = Carbon::now('America/Los_Angeles');
            $notaHistPlan->updated_at = Carbon::now('America/Los_Angeles');

            $notaHistPlan->save();
        }

        return redirect('/paciente/notas-hist/' . $request->paciente_id);
    }

    public function getViewNotaHist($notaHistId){
        $notaHistorica = NotaHistorica::find($notaHistId);
        $paciente = Paciente::find($notaHistorica->paciente_id);
        $tiposPlan = TipoPlaneacion::all();
        return view('pacientes.nota_historica', compact(['paciente', 'notaHistorica', 'tiposPlan']));
    }

    public function updateNotaHist(Request $request){

        $notaHistorica = NotaHistorica::find($request->nota_hist_id);

        $notaHistorica->fecha = $request->fecha;
        $notaHistorica->subjetivo = $request->subjetivo;
        $notaHistorica->analisis = $request->analisis;
        $notaHistorica->obj_ta = $request->ta;
        $notaHistorica->obj_ta2 = $request->ta2;
        $notaHistorica->obj_fc = $request->fc;
        $notaHistorica->obj_fr = $request->fr;
        $notaHistorica->obj_temp = $request->temp;
        $notaHistorica->obj_talla = $request->talla;
        $notaHistorica->obj_peso = $request->peso;
        $notaHistorica->obj_imc = $request->imc;
        $notaHistorica->obj_sat_o2 = $request->sat_o2;
        $notaHistorica->obj_hb = $request->hb;
        $notaHistorica->obj_hto = $request->hto;
        $notaHistorica->obj_vcm = $request->vcm;
        $notaHistorica->obj_hcm = $request->hcm;
        $notaHistorica->obj_porcentaje_eritrocitos_hipocromicos = $request->porcentaje_eritrocitos_hipocromicos;
        $notaHistorica->obj_plaq = $request->plaq;
        $notaHistorica->obj_leuc = $request->leuc;
        $notaHistorica->obj_cr = $request->cr;
        $notaHistorica->obj_ckdepi = $request->ckdepi;
        $notaHistorica->obj_bun = $request->bun;
        $notaHistorica->obj_g = $request->g;
        $notaHistorica->obj_hba1c_porcentaje = $request->hba1c_porcentaje;
        $notaHistorica->obj_insulina_serica = $request->insulina_serica;
        $notaHistorica->obj_homa = $request->homa;
        $notaHistorica->obj_au = $request->au;
        $notaHistorica->obj_na = $request->na;
        $notaHistorica->obj_k = $request->k;
        $notaHistorica->obj_cl = $request->cl;
        $notaHistorica->obj_ca = $request->ca;
        $notaHistorica->obj_p = $request->p;
        $notaHistorica->obj_mg = $request->mg;
        $notaHistorica->obj_alb = $request->alb;
        $notaHistorica->obj_col = $request->col;
        $notaHistorica->obj_tgs = $request->tgs;
        $notaHistorica->obj_hdl_col = $request->hdl_col;
        $notaHistorica->obj_ldl_col = $request->ldl_col;
        $notaHistorica->obj_ego = $request->ego;
        $notaHistorica->obj_albu_cru = $request->albu_cru;
        $notaHistorica->obj_exploracion_fisica = $request->exploracion_fisica;

        $notaHistorica->save();

        foreach($request->plan as $key => $plan){
            
            $notaHistPlan = NotaHistoricaPlan::find($plan['plan_hist_id']);
            
            $notaHistPlan->plan = $plan['plan'];
            $notaHistPlan->tipo_plan_id = $plan['tipo_plan_id'];
            
            $notaHistPlan->updated_at = Carbon::now('America/Los_Angeles');
            $notaHistPlan->updated_at = Carbon::now('America/Los_Angeles');

            $notaHistPlan->save();
        }

        return redirect('/nota-hist/' . $notaHistorica->id);
    }

    public function getViewBorrarNotaHist($notaHistId){

        $notaHistorica = NotaHistorica::find($notaHistId);
        return view('pacientes.borrar_notahist', compact(['notaHistorica']));
    }

    public function deleteNotaHist(Request $request){
        $notaHistorica = NotaHistorica::find($request->nota_hist_id);

        NotaHistoricaPlan::where('nota_historica_id', $notaHistorica->id)->delete();

        $notaHistorica->delete();

        return redirect('/paciente/notas-hist/' . $notaHistorica->paciente_id);
    }
}
