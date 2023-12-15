<?php 
namespace App\Controlador;

use App\Helper\Vista;
use App\Helper\Util;
use App\Modelo\AppRoles;
use App\Modelo;
use App\Modelo\Empleado;
use App\Modelo\Dependencia;
use App\Modelo\Contrato;
use App\Modelo\Observacion;
use App\Modelo\Usuario;
use App\Modelo\Anticorrupcion;
use App\Modelo\Embargo;
use App\Modelo\Curso;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use \FMT\Helper\Arr;
use \FMT\Consola;

class Legajos extends Base {
	protected function despues() {
		parent::despues();
		$vars_vista	= ['JS_FOOTER' => []];
		$vars_vista['JS_FOOTER'][0]	= ['JS_SCRIPT'	=> Vista::get_url('script.js')];
		$this->vista->add_to_var('vars',$vars_vista);
	}

	protected function datos_parametricos(){
		$incluir_estados = [
			Modelo\Empleado::EMPLEADO_INACTIVO	=> Modelo\Empleado::EMPLEADO_INACTIVO,
			Modelo\Empleado::EMPLEADO_ACTIVO	=> Modelo\Empleado::EMPLEADO_ACTIVO,
		];

		switch (true) {
			case (!empty($this->setGetVarSession('info_anti'))):
				$volver	= Vista::get_url('index.php/legajos/listado_anticorrupcion');
				break;
			case(!empty($this->setGetVarSession('buscar_cuit'))) :
				$volver	= Vista::get_url('index.php/legajos/buscar_cuit');
				break;
			case(!empty($this->setGetVarSession('info_global'))) :
				$volver	= Vista::get_url('index.php/legajos/datos_globales'); 
				break;
			case(!empty($this->setGetVarSession('info_recoleccion'))) :
				 $volver	= Vista::get_url('index.php/legajos/datos_recoleccion');
				break;				 
			default:
				$volver	= Vista::get_url('index.php/legajos/agentes');
				break;
		}
		return [

			'genero'				 		=> Modelo\Persona::getParam('GENERO'),
			'estado_civil'			 		=> Modelo\Persona::getParam('ESTADO_CIVIL'),
			'tipo_documento'		 		=> Modelo\Persona::getParam('TIPO_DOCUMENTO'),
			'tipo_telefono'			 		=> Modelo\PersonaTelefono::getParam('TIPO_TELEFONO'),
			'nacionalidad'			 		=> json_decode(json_encode(\FMT\Ubicaciones::get_gentilicios()), JSON_UNESCAPED_UNICODE),
			'nivel_organigrama' 	 		=> \App\Modelo\Dependencia::getParam('NIVEL_ORGANIGRAMA'),
			'saf'					 		=> \App\Modelo\Presupuesto::getSaf(),
			'jurisdicciones'		 		=> \App\Modelo\Presupuesto::getJurisdiccion(),
			'ubicaciones_geograficas'		=> \App\Modelo\Presupuesto::getUbicacionesGeograficas(),
			'programas'				 		=> \App\Modelo\Presupuesto::getProgramas(),
			'actividades'			 		=> \App\Modelo\Presupuesto::getActividades(),
			'modalidad_vinculacion'	 		=> Modelo\Contrato::obtenerVinculacionRevista(null,false,true)['modalidad_vinculacion'],
			'situacion_revista'		 		=> Modelo\Contrato::obtenerVinculacionRevista(null,false,true)['situacion_revista'],
			'nivel_organigrama'		 		=> \App\Modelo\Dependencia::getParam('NIVEL_ORGANIGRAMA'),
			'tipo_discapacidad' 	 		=> \App\Modelo\Persona::getDiscapacidad(),
			'formacion_tipo_titulo'		    => Modelo\NivelEducativo::getNivelEducativo(),
			'formacion_estado_titulo'	    => Modelo\PersonaTitulo::getParam('ESTADO_TITULO'),
			'formacion_cursos'				=> \App\Modelo\EmpleadoCursos::getCursos(), //CURSOS
			'familia_de_puesto' 			=> \App\Modelo\Perfil::listarFamiliaPuestos(),
			'denominacion_del_puesto' 		=> \App\Modelo\Perfil::listarDenominacionDelPuesto(),
			'denominacion_de_la_funcion'	=> \App\Modelo\Perfil::listarDenominacionFuncion(),
			'nivel_de_destreza' 			=> \App\Modelo\Perfil::$NIVELES_DESTREZA,
			'nombre_de_puesto' 				=> \App\Modelo\Perfil::listarNombrePuestos(),
			'niveles_complejidad' 			=> \App\Modelo\Perfil::$NIVELES_COMPLEJIDAD,
			'niveles_puesto_supervisa' 		=> \App\Modelo\Perfil::$NIVELES_PUESTO_SUPERVISA,
			'plantilla_horario' 			=> \App\Modelo\Horario::getPlantillaHorario(),
			'turno'							=> \App\Modelo\Empleado::getParam('TURNO'),
			'motivo_baja'					=> Modelo\Empleado::getMotivoBaja(),
			'estado_administracion'			=> array_intersect_key(Modelo\Empleado::$TIPO_ESTADOS_EMPLEADOS, $incluir_estados),
			'estado_comision'				=> Modelo\Empleado::getParam('ESTADOS'),
			'comisiones'					=> Modelo\Comision::listar(true),
			'licencias_especiales' 			=> \App\Modelo\LicenciaEspecial::getLicenciasEspeciales(),
			'ubicaciones'					=> \App\Modelo\Ubicacion::getEdificios(),
			'tipo_presentacion' 	 		=> \App\Modelo\Anticorrupcion::getParam('TIPO_DJ'),
			'exc_art_14' 	 				=> Modelo\Empleado::getParam('EXCEPCION_ART_14'),
			'id_sindicato' 	 				=> Modelo\Empleado::getSindicato(),
			'obras_sociales'				=> \App\Modelo\Empleado::getObraSociales(),
			'seguros_vida'					=> \App\Modelo\Empleado::getSegurosVida(),
			'sindicatos'					=> \App\Modelo\EmpleadoSindicato::getSindicatos(false),
			'parentesco'					=> \App\Modelo\GrupoFamiliar::getParam('PARENTESCO'),
			'opcion_sino'					=> \App\Modelo\GrupoFamiliar::getParam('OPCION_SINO'),
			'porcentaje_desgrava'			=> \App\Modelo\GrupoFamiliar::getParam('PORCENTAJE_DESGRAVA'),
			'boton_volver'					=> $volver,
			'entidades'		   				=> Modelo\OtroOrganismo::getOtrosOrganismos(),
			'tipo_entidades'		   		=> Modelo\OtroOrganismo::getParam('TIPO_ORGANISMO'),
			'jurisdicciones'		   		=> Modelo\OtroOrganismo::getParam('JURISDICCION'),
			'formularios_evaluacion'		=> Modelo\Evaluacion::getParam('formularios'),
			'resultados_evaluacion'			=> Modelo\Evaluacion::getParam('resultados'),
        ];

	}

	protected function accion_ajax_ubicaciones(){
		if(!empty($this->request->query('id_pais')) && !empty($this->request->query('id_region')) ) {
			$data	= [
				'ubicacion_regiones'	=> \FMT\Ubicaciones::get_regiones($this->request->query('id_pais')),
				'ubicacion_localidades'	=> \FMT\Ubicaciones::get_localidades($this->request->query('id_region')),
			];
		} else {
			if( !empty($this->request->query('id_pais')) ){
				$data	= [
					'ubicacion_regiones'	=> \FMT\Ubicaciones::get_regiones($this->request->query('id_pais')),
				];
			} else if( !empty($this->request->query('id_region')) ){
				$data	= [
					'ubicacion_localidades'	=> \FMT\Ubicaciones::get_localidades($this->request->query('id_region')),
				];
			} else {
				$data	= [];
			}
		}
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

	protected function accion_gestionar() {
		$vista = $this->vista;
		$empleado	= Empleado::obtener($this->request->query('id'));
		$anticorrupcion = Modelo\Anticorrupcion::obtener($empleado->id);
		AppRoles::excepcion_permisos($empleado->situacion_escalafonaria->id_modalidad_vinculacion);
		$lista_embargo = Modelo\Embargo::listar($empleado->id);
		$lista_historial_embargo = Modelo\Embargo::listar_historial($empleado->id);

		$convenio_ur= (array)Modelo\ConvenioUR::listar();
		foreach ($convenio_ur as $key => $value) {
			$unidad_retributiva[$value->id_nivel][$value->id_grado] = ['min'=> $value->unidad->minimo,'max'=> $value->unidad->maximo];
		}
		if($empleado->situacion_escalafonaria->id_modalidad_vinculacion 
		&& !in_array($empleado->situacion_escalafonaria->id_modalidad_vinculacion, AppRoles::obtener_modalidades_vinculacion_autorizadas()) && $empleado->estado != Empleado::EMPLEADO_INACTIVO) {
			$text = 'No esta autorizado para gestionar a este agentes.';
			$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
			$this->redirect(\App\Helper\Vista::get_url('index.php/legajos/agentes'));
		}
		
		if(!empty($this->request->query('id'))){
			$empleado->cuit = (int)$this->request->query('id');
		}
		
		$select_tab = '';
		$no_bloquear= null;
		switch ($this->request->post('gestionar_form')) {
			case 'datos_personales':
				$this->am_datos_personales($empleado);
				$select_tab = 'tab_datos_personales';
				break;
			case 'situacion_escalafonaria':
				$no_bloquear = $this->am_situacion_escalafonaria($empleado);
				$select_tab = 'tab_escalafonaria';
				break;
			case 'ubicacion_estructura':
				$this->am_ubicacion_estructura($empleado);
				$select_tab = 'tab_ubicacion_estructura';
				break;
			case 'perfiles_puestos':
				$this->am_perfiles_puestos($empleado);
				$select_tab = 'tab_perfiles_puestos';
				break;
			case 'formacion':
				$this->am_formacion($empleado);
				$select_tab = 'tab_formacion';
				break;
			case 'antiguedad':
				$this->m_antiguedad($empleado);
				$select_tab = 'tab_antiguedad';
					break;
			case 'administracion':
				$this->am_administracion($empleado);
				$select_tab = 'tab_administracion';
				break;
			case 'varios':
				$this->am_varios($empleado);
				$select_tab = 'tab_varios';
				break;
			case 'presupuesto':
				$this->am_presupuesto($empleado);
				$select_tab = 'tab_presupuesto';
				break;
			case 'anticorrupcion':
				$this->am_anticorrupcion($anticorrupcion);
				$select_tab = 'tab_anticorrupcion';
				break;	
			default:
				# code...
				break;
		}

		if(empty($this->request->query('id')) && $empleado->cuit){
			$permisos				= 
			$estructura				=
			$lista_dependencia		= 
			$lista_dep_informales	= 
			$lista_familiares		= [];
			$cantidad				=
			$cantidad_total 		= ['anios' => 0, 'meses' => 0];
			$parametricos			= $this->datos_parametricos();
			$parametricos['ubicacion_regiones']			= json_decode(json_encode(\FMT\Ubicaciones::get_regiones('AR')), JSON_UNESCAPED_UNICODE);
			$parametricos['ubicacion_localidades']		= !empty($empleado->persona->domicilio->id_provincia)
														? json_decode(json_encode(\FMT\Ubicaciones::get_localidades($empleado->persona->domicilio->id_provincia)), JSON_UNESCAPED_UNICODE) : [];
			$parametricos['sum_antiguedad']				= null;
			$parametricos['sum_grado']					= null;
 			$parametricos['convenios_parametricos']		= [];

			(new Vista($this->vista_default,compact('empleado','permisos', 'vista', 'parametricos', 'estructura', 'lista_dependencia', 'lista_dep_informales', 'select_tab', 'id_plantilla_horario', 'anticorrupcion', 'lista_embargo', 'lista_historial_embargo', 'lista_familiares', 'no_bloquear', 'unidad_retributiva', 'cantidad', 'cantidad_total')))->pre_render();
		}elseif(empty($this->request->query('id')) && $empleado->cuit && empty($empleado->id)){
			$this->redirect(\App\Helper\Vista::get_url('index.php/legajos/gestionar/'.$empleado->cuit));
		}

		switch ($this->request->post('id_bloque')) {
			case \App\Helper\Bloques::DATOS_PERSONALES:
				$select_tab = 'tab_datos_personales';
				break;
			case \App\Helper\Bloques::SITUACION_ESCALAFONARIA:
				$select_tab = 'tab_escalafonaria';
				break;
			case \App\Helper\Bloques::UBICACION_ESTRUCTURA:
				$select_tab = 'tab_ubicacion_estructura';
				break;
			case \App\Helper\Bloques::PERFILES_PUESTO:
				$select_tab = 'tab_perfiles_puestos';
				break;
			case \App\Helper\Bloques::FORMACION:
				$select_tab = 'tab_formacion';
				break;
			case \App\Helper\Bloques::ANTIGUEDAD:
				$select_tab = 'tab_antiguedad';
					break;
			case \App\Helper\Bloques::ADMINISTRACION:
				$select_tab = 'tab_administracion';
				break;
			case \App\Helper\Bloques::VARIOS:
				$select_tab = 'tab_varios';
				break;
			case \App\Helper\Bloques::PRESUPUESTO:
				$select_tab = 'tab_presupuesto';
				break;
			case \App\Helper\Bloques::ANTICORRUPCION:
				$select_tab = 'tab_anticorrupcion';
				break;
			case \App\Helper\Bloques::EMBARGO:
				$select_tab = 'tab_embargo';
				break;
			case \App\Helper\Bloques::GRUPO_FAMILIAR:
				$select_tab = 'tab_grupo_familiar';
				break;
			default:
				# code...
				break;
		}

		if( $this->request->post('nivel_organigrama')) {
			$empleado->dependencia->nivel = $this->request->post('nivel_organigrama');

			$empleado->dependencia->id_dependencia = $this->request->post('dep_id_dependencia');
			$select_tab = 'tab_ubicacion_estructura';
		}
		$lista_dependencia		= Dependencia::obtener_dependencias($empleado->dependencia->nivel);
		$lista_dep_informales	= Dependencia::lista_dep_informales($empleado->dependencia->id_dependencia);
		$estructura = Dependencia::obtener_cadena_dependencias($empleado->dependencia->id_dependencia, $empleado->fecha_baja);
		$id_plantilla_horario = '';
		if($this->request->post('select_horario')) {
			
			$plantilla_horario = \App\Modelo\Horario::listar();
			$id_plantilla_horario = $this->request->post('select_horario');
			$key_horario = array_search($id_plantilla_horario, array_column($plantilla_horario,'id'));
			$empleado->horario->horarios = $key_horario !== false ? $plantilla_horario[$key_horario]['horario'] : array_fill(0, 7, '');
			$empleado->horario->id_turno	= $this->request->post('id_turno');
			$empleado->planilla_reloj		= $this->request->post('planilla_reloj'); 
			$empleado->ubicacion->id_ubicacion	= $this->request->post('id_ubicacion'); 
			$empleado->licencia->id_licencia	= $this->request->post('id_licencia'); 
			$empleado->licencia->fecha_desde	= $this->request->post('fecha_desde'); 
			$empleado->licencia->fecha_hasta	= $this->request->post('fecha_hasta'); 
			$empleado->estado					= $this->request->post('activo'); 
			$empleado->fecha_baja 	= ($this->request->post('fecha_baja')) ? \DateTime::createFromFormat('d/m/Y H:i:s', $this->request->post('fecha_baja') . ' 00:00:00') : null;
			$empleado->id_motivo 		= $this->request->post('id_motivo');

			if($this->request->post('comision')==1) {
				$empleado->en_comision->id = ($empleado->en_comision->id) ? $empleado->en_comision->id :1; 	
			} else {
				$empleado->en_comision->id = null;
			}

			$select_tab = 'tab_administracion';
		}
		
		$permisos = $this->_get_permisos();


		if ($this->request->post('buscar_presupuesto')) {
			$select_tab = 'tab_presupuesto';
			$empleado->presupuesto->id_saf  = $this->request->post('saf');
			$empleado->presupuesto->id_jurisdiccion = $this->request->post('jurisdiccion');
			$empleado->presupuesto->id_ubicacion_geografica = $this->request->post('ubicacion_geografica');
			$empleado->presupuesto->id_programa  = $this->request->post('programa');
			$empleado->presupuesto->id_subprograma = $this->request->post('subprograma');
			$empleado->presupuesto->id_proyecto = $this->request->post('proyecto');
			$empleado->presupuesto->id_actividad  = $this->request->post('actividad');
			$empleado->presupuesto->id_obra = $this->request->post('obra');
		}


		$parametricos	= $this->datos_parametricos();
		$parametricos['ubicacion_regiones']			= json_decode(json_encode(\FMT\Ubicaciones::get_regiones('AR')), JSON_UNESCAPED_UNICODE);
		$parametricos['ubicacion_localidades']		= !empty($empleado->persona->domicilio->id_provincia)
													? json_decode(json_encode(\FMT\Ubicaciones::get_localidades($empleado->persona->domicilio->id_provincia)), JSON_UNESCAPED_UNICODE) : [];
		$parametricos['ubicacion_localidades_edificio']		= !empty($empleado->ubicacion->id_provincia)
													? json_decode(json_encode(\FMT\Ubicaciones::get_localidades($empleado->ubicacion->id_provincia)), JSON_UNESCAPED_UNICODE) : [];
		$parametricos['sum_antiguedad'] = $this->sum_fecha($empleado->antiguedad->fecha_ingreso);
		

		$parametricos['sum_grado'] 		= $this->sum_fecha($empleado->antiguedad->fecha_grado);
 		$parametricos['convenios_parametricos']	= Modelo\Contrato::obtenerConvenio($empleado->situacion_escalafonaria->id_modalidad_vinculacion, $empleado->situacion_escalafonaria->id_situacion_revista);
 		
 		if(count($parametricos['saf']) == 1) $empleado->presupuesto->id_saf = current($parametricos['saf'])['id'];
		
		if(count($parametricos['jurisdicciones']) == 1) $empleado->presupuesto->id_jurisdiccion =  current($parametricos['jurisdicciones'])['id'];
		$parametricos['titulo'] = [];
		foreach ($empleado->persona->titulos as $value) {
		 	$parametricos['titulo'][$value->id_tipo_titulo] = Modelo\PersonaTitulo::obtenerTitulo($value->id_tipo_titulo);
		}
		$parametricos['cursos']	= (array)Modelo\EmpleadoCursos::getCursos();
 		$parametricos['subprogramas']	= (array)Modelo\Presupuesto::getSubProgramas($empleado->presupuesto->id_programa);
 		$parametricos['proyectos']		= (array)Modelo\Presupuesto::getProyectos($empleado->presupuesto->id_subprograma);
		$parametricos['obras']			= (array)Modelo\Presupuesto::getObras($empleado->presupuesto->id_proyecto);
		$parametricos['puestos']		= Modelo\Puesto::getPuesto($empleado->perfil_puesto->familia_de_puestos);
		$puesto = \FMT\Helper\Arr::path($parametricos['nombre_de_puesto'],"{$empleado->perfil_puesto->nombre_puesto}.nombre",'-');
 		$vars = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit];
 		if($puesto != '') {
 			$vars['DENOMINACION']=  $puesto;
 		}
 		if($this->setGetVarSession('data_legajo')){
			$select_tab	= \FMT\Helper\Arr::get($this->setGetVarSession('data_legajo'), 'select_tab');
			$this->setGetVarSession('data_legajo', false);
 		}
 		$lista_familiares = \App\Modelo\GrupoFamiliar::listar($empleado->id);
		$cantidad			= Modelo\PersonaExperienciaLaboral::total_antiguedad($empleado->persona->id);
		$cantidad_total		= Empleado::total_antiguedad_adm_publica($empleado->persona->id,$empleado->antiguedad->fecha_ingreso);

 		$vista->add_to_var('vars',$vars);
		(new Vista($this->vista_default,compact('empleado','permisos', 'vista', 'parametricos', 'estructura', 'lista_dependencia', 'lista_dep_informales', 'select_tab', 'id_plantilla_horario', 'anticorrupcion', 'lista_embargo', 'lista_historial_embargo', 'lista_familiares', 'no_bloquear', 'unidad_retributiva', 'cantidad', 'cantidad_total')))->pre_render();
	}

	protected function accion_buscar_cuit() {
		if ($this->request->post('boton_buscar_cuit')) {
			$empleado = Empleado::obtener($this->request->post('cuit'));
			if (!empty($empleado->cuit)) {
				$this->setGetVarSession('info_global', false);
				$this->setGetVarSession('info_anti', false);
				$this->setGetVarSession('buscar_cuit', true);
				$redirect =Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");
				$this->redirect($redirect);
			}else{
				$this->mensajeria->agregar(
					"No existe el Agente en el Sistema.",
					\FMT\Mensajeria::TIPO_ERROR,
					$this->clase
				);
			}
		}
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista')))->pre_render();
	}

	private function am_situacion_escalafonaria(Modelo\Empleado $empleado=null){
		$no_bloquear = false;
		$modificacion = ($empleado->situacion_escalafonaria->id && $this->request->query('id'));
		$save	= true;
		$clone_situacion_escalafonaria	= unserialize(serialize($empleado->situacion_escalafonaria));
		$empleado->situacion_escalafonaria->id_modalidad_vinculacion	= !empty($temp = $this->request->post('id_modalidad_vinculacion'))
			? $temp : $empleado->situacion_escalafonaria->id_modalidad_vinculacion;
		$empleado->situacion_escalafonaria->id_situacion_revista		= !empty($temp = $this->request->post('id_situacion_revista')) 
			? $temp : $empleado->situacion_escalafonaria->id_situacion_revista;
		$empleado->situacion_escalafonaria->id_nivel					= !empty($temp = $this->request->post('id_nivel')) 
			? $temp : $empleado->situacion_escalafonaria->id_nivel;
		$empleado->situacion_escalafonaria->id_tramo					= !empty($temp = $this->request->post('id_tramo')) 
			? $temp : $empleado->situacion_escalafonaria->id_tramo;
		$empleado->situacion_escalafonaria->id_agrupamiento				= !empty($temp = $this->request->post('id_agrupamiento')) 
			? $temp : $empleado->situacion_escalafonaria->id_agrupamiento;
		$empleado->situacion_escalafonaria->compensacion_geografica		= !empty($temp = $this->request->post('compensacion_geografica')) 
			? $temp : $empleado->situacion_escalafonaria->compensacion_geografica;
		$empleado->situacion_escalafonaria->compensacion_transitoria	= !empty($temp = $this->request->post('compensacion_transitoria')) 
			? $temp : $empleado->situacion_escalafonaria->compensacion_transitoria;
		$empleado->situacion_escalafonaria->id_grado					= !empty($temp = $this->request->post('id_grado')) 
			? $temp : $empleado->situacion_escalafonaria->id_grado;
		$empleado->situacion_escalafonaria->id_grado_liquidacion		= !empty($temp = $this->request->post('id_grado_liquidacion')) 
			? $temp : $empleado->situacion_escalafonaria->id_grado_liquidacion;
		$empleado->situacion_escalafonaria->id_funcion_ejecutiva		= !empty($temp = $this->request->post('id_funcion_ejecutiva')) 
			? $temp :$empleado->situacion_escalafonaria->id_funcion_ejecutiva;
		$empleado->situacion_escalafonaria->unidad_retributiva			= !empty($temp = $this->request->post('unidad_retributiva')) 
			? $temp :$empleado->situacion_escalafonaria->unidad_retributiva;
		$empleado->situacion_escalafonaria->fecha_inicio				= (!$empleado->situacion_escalafonaria->fecha_inicio) 
			? \DateTime::createFromFormat('U', strtotime('now')) : $empleado->situacion_escalafonaria->fecha_inicio;
		$empleado->situacion_escalafonaria->ultimo_cambio_nivel 		= (!empty($temp = $this->request->post('cambio_nivel')))
			? \DateTime::createFromFormat('d/m/Y H:i:s.u', $temp . '0:00:00.000000') : $empleado->situacion_escalafonaria->ultimo_cambio_nivel;
		// $empleado->situacion_escalafonaria->ultimo_cambio_grado 		= (!empty($temp = $this->request->post('cambio_grado')))
		// 	? \DateTime::createFromFormat('d/m/Y H:i:s.u', $temp . '0:00:00.000000') : $empleado->situacion_escalafonaria->ultimo_cambio_grado;
		$empleado->situacion_escalafonaria->ultimo_cambio_grado 		= $empleado->situacion_escalafonaria->ultimo_cambio_grado;
		if(
			$empleado->situacion_escalafonaria->id_modalidad_vinculacion != Modelo\Contrato::PRESTACION_SERVICIOS 
			&& !empty($empleado->situacion_escalafonaria->unidad_retributiva)
			){
				$empleado->situacion_escalafonaria->unidad_retributiva	= null;
			}
			

		$ultimo_nivel =  Modelo\EmpleadoUltimosCambios::obtener_nivel($empleado->id);
		$ultimo_nivel->id_convenios = $empleado->situacion_escalafonaria->id_nivel;
		$ultimo_nivel->fecha_desde = (!empty($temp = $this->request->post('cambio_nivel')))
			? \DateTime::createFromFormat('d/m/Y H:i:s.u', $temp . '0:00:00.000000'): $ultimo_nivel->fecha_desde;
		$v_nivel = $ultimo_nivel->guardar_nivel();
		if($ultimo_nivel->errores) {
			foreach ($ultimo_nivel->errores as $text) {
				$no_bloquear = true;
				$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
			}
		}


		$aux = $empleado->situacion_escalafonaria->exc_art_14;
		if ($this->request->post('exc_art_14')){
			if (!empty($this->request->post('formacion'))){
				$aux	= [];
				foreach ((array)$this->request->post('formacion') as $value) {
					$aux[]['excepcion'] = $value; 
				}
				$aux = json_encode($aux,JSON_UNESCAPED_SLASHES);
			}else{
				$aux = null;
			}
		} else if(!empty($this->request->post('formacion'))){
			$aux = null;
		}

		$empleado->situacion_escalafonaria->exc_art_14	= $aux;
		if($this->request->post('delegado_gremial')) {
			$empleado->fecha_vigencia_mandato = 	!empty($temp = $this->request->post('fecha_vigencia')) 
				? \DateTime::createFromFormat('d/m/Y H:i:s.u', $temp . '0:00:00.000000') : $empleado->fecha_vigencia_mandato;
			$empleado->id_sindicato		= !empty($temp = $this->request->post('id_sindicato')) ? $temp : $empleado->id_sindicato;
		} else {
			$empleado->fecha_vigencia_mandato 	= null;
			$empleado->id_sindicato				= null;
		}								

		$save =	false;
		if (
			!is_null($empleado->situacion_escalafonaria->id_modalidad_vinculacion)
			|| !is_null($empleado->situacion_escalafonaria->id_situacion_revista)
			|| !is_null($empleado->situacion_escalafonaria->id_nivel)
			|| !is_null($empleado->situacion_escalafonaria->id_grado)
			|| !is_null($empleado->situacion_escalafonaria->id_grado_liquidacion)
			|| !is_null($empleado->situacion_escalafonaria->id_tramo)
			|| !is_null($empleado->situacion_escalafonaria->id_agrupamiento)
			|| !is_null($empleado->situacion_escalafonaria->id_funcion_ejecutiva)
			|| !is_null($empleado->situacion_escalafonaria->compensacion_geografica)
			|| !is_null($empleado->situacion_escalafonaria->compensacion_transitoria)
			|| !is_null($empleado->situacion_escalafonaria->unidad_retributiva)
			|| !is_null($empleado->situacion_escalafonaria->exc_art_14)
			|| $empleado->situacion_escalafonaria !== $clone_situacion_escalafonaria
		) {

			if($empleado->validar()){
				if($this->request->post('escalafon_accion') == 'alta') {
					if($empleado->situacion_escalafonaria->id) {
						$empleado->baja_situacion_escalafonaria();
					}
					$empleado->situacion_escalafonaria->id = null;
					$empleado->situacion_escalafonaria->fecha_inicio	= \DateTime::createFromFormat('d/m/Y H:i:s', gmdate('d/m/Y').'0:00:00');
					$empleado->alta_situacion_escalafonaria();
				} elseif (in_array($this->request->post('escalafon_accion'), ['modificar', 'modificacion']) || !empty($empleado->situacion_escalafonaria->exc_art_14)) {
					$empleado->modificacion_situacion_escalafonaria();
				}
				$empleado->modificacion();

				$save = (!isset($empleado->errores['empleado_escalafon']));
			} else {
				foreach ($empleado->errores as $text) {
					$no_bloquear = true;
					$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
				}
			}
			if($save) {
				$this->mensajeria->agregar(
					"La situación escalafonaria del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue ".($modificacion ? 'modificada':'cargada')." exitosamente.",
					\FMT\Mensajeria::TIPO_AVISO,
					$this->clase
				);
			} else {
				$this->mensajeria->agregar('Ocurrio un error al cargar los datos de situación escalafonaria.', \FMT\Mensajeria::TIPO_ERROR, $this->clase);
			}
		}else{
			$this->mensajeria->agregar('No hay datos que guardar.', \FMT\Mensajeria::TIPO_ERROR, $this->clase);
		}
		return $no_bloquear;
	  }

/**
 * Alta/Modificacion de datos personales.
 * Si alguno o todos los datos no existen, se da de alta. Caso contrario se modifican.
 * Para forzar el alta de una propiedad (e.j.: Domicilio) se debe setear el "id" de vinculacion en "null".
*/
	private function am_datos_personales(Empleado $empleado=null){
		$modificacion = ($empleado->id && $this->request->query('id'));
		$empleado->cuit						= $this->request->post('cuit');
		$empleado->email					= $this->request->post('email');
		$empleado->estado					= isset($empleado->estado) ?  $empleado->estado : Modelo\Empleado::EMPLEADO_ACTIVO;
		$empleado->persona->nombre			= $this->request->post('nombre');
		$empleado->persona->apellido		= $this->request->post('apellido');
		$empleado->persona->tipo_documento	= $this->request->post('tipo_documento');
		$empleado->persona->documento		= $this->request->post('documento');
		$empleado->persona->fecha_nac		= \DateTime::createFromFormat('d/m/Y H:i:s', $this->request->post('fecha_nac'). ' 0:00:00');
		$empleado->persona->genero			= $this->request->post('genero');
		$empleado->persona->estado_civil	= $this->request->post('estado_civil');
		$empleado->persona->email			= $this->request->post('email');
		$domicilio 					= new \stdClass();
		$domicilio->id 				= $empleado->persona->domicilio->id;
		$domicilio->id_provincia	= $this->request->post('id_provincia');
		$domicilio->id_localidad	= $this->request->post('id_localidad');
		$domicilio->calle			= $this->request->post('calle');
		$domicilio->numero			= $this->request->post('numero');
		$domicilio->piso			= $this->request->post('piso');
		$domicilio->depto			= $this->request->post('depto');
		$domicilio->cod_postal		= $this->request->post('cod_postal');
		$domicilio->fecha_alta		= $empleado->persona->domicilio->fecha_alta;
		$domicilio->fecha_baja		= $empleado->persona->domicilio->fecha_baja;	
		$empleado->persona->nacionalidad	= $this->request->post('nacionalidad');


		//$empleado->persona->foto_persona = null;
		$empleado->persona->foto_persona =  $this->request->post('foto');


		if($this->request->post('domicilio_accion') == 'alta') {
			if($empleado->persona->domicilio->id) {
				$empleado->persona->domicilio->fecha_baja	= \DateTime::createFromFormat('U', strtotime('now'));
				$empleado->persona->modificacion();
			}
			$empleado->persona->domicilio				= $domicilio;
			$empleado->persona->domicilio->id			= null;
			$empleado->persona->domicilio->fecha_baja	= null;
			$empleado->persona->domicilio->fecha_alta	= \DateTime::createFromFormat('U', strtotime('now'));
		} elseif ($this->request->post('domicilio_accion') == 'modificacion') {
			$empleado->persona->domicilio		= $domicilio;
		}

// Alta Baja Modificacion de persona_telefono
		if(!empty($telefonoPost = $this->request->post('telefono_numero'))){
			$telefonos_temp	= [];
			$tel_am_ids	= [];

			for($i=0; $i < count($telefonoPost); $i++){
				$tipo_telefono		= $telefonoPost[$i];
				$i++;
				$id_telefono		= $telefonoPost[$i];
				$i++;
				$numero_telefono	= $telefonoPost[$i];

				if(!is_numeric($numero_telefono) || empty($numero_telefono)) {
					continue;
				}
				$telefono					= Modelo\PersonaTelefono::obtener(!empty($id_telefono) ? $id_telefono : null);
				$telefono->id_persona		= $empleado->persona->id;
				$telefono->id_tipo_telefono	= $tipo_telefono;
				$telefono->telefono			= $numero_telefono;

				$telefonos_temp[]	= $telefono;
			}

			$comparacion_telefonos			= array_map(function($aux){return $aux->telefono;}, $empleado->persona->telefonos);
			foreach ($telefonos_temp as $key => $value) {
				if(!in_array($value->telefono, $comparacion_telefonos)){
					$empleado->persona->telefonos[]	= $telefonos_temp[$key];
				}
			}
		}

		if($empleado->persona->validar() && $empleado->validar()){
			if(empty($empleado->persona->id)){
				$empleado->persona->alta();
			} else {
				$empleado->persona->modificacion();
			}

// alta de persona_telefono
			foreach ($telefonos_temp as $telefono) {
				$telefono->id_persona	= $empleado->persona->id;
				if($telefono->validar()){
					if(!empty($telefono->id)){
						$telefono->modificacion();
					} else {
						$telefono->alta();
					}
					$tel_am_ids[]	= $telefono->id;
				} else {
					foreach ($telefono->errores as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
// baja de persona_telefono
			foreach ($empleado->persona->telefonos as $key => $obj) {
				if(!in_array($obj->id, $tel_am_ids)){
					$obj->baja();
				}
			}
			$empleado->persona->telefonos	= Modelo\PersonaTelefono::listar($empleado->persona->id);

			if(empty($empleado->persona->errores_sql)){
				if(empty($empleado->id)){
					$empleado->alta();
				} else {
					$empleado->modificacion();
				}
			}
		} else {
			$err	= array_merge((array)$empleado->persona->errores, (array)$empleado->errores);
			foreach ($err as $text) {
				$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
			}
			return $empleado;
		}

		$this->mensajeria->agregar(
			"El empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue ".($modificacion ? 'modificado':'cargado')." exitosamente.",
			\FMT\Mensajeria::TIPO_AVISO,
			$this->clase
		);
		return $empleado;
	}
	
	private function am_perfiles_puestos(Empleado $empleado){	

		if($this->request->post('familia_puestos'))
			$empleado->perfil_puesto->familia_de_puestos	= $this->request->post('familia_puestos');
		if($this->request->post('denominacion_puesto'))
			$empleado->perfil_puesto->denominacion_puesto 	= $this->request->post('denominacion_puesto');
		if($this->request->post('nombre_puesto'))
			$empleado->perfil_puesto->nombre_puesto 		= $this->request->post('nombre_puesto');
		if($this->request->post('nivel_destreza'))
			$empleado->perfil_puesto->nivel_destreza 		= $this->request->post('nivel_destreza');
		if($this->request->post('denominacion_funcion'))
			$empleado->perfil_puesto->denominacion_funcion 	= $this->request->post('denominacion_funcion');
		if($this->request->post('puesto_supervisa'))
			$empleado->perfil_puesto->puesto_supervisa 		= $this->request->post('puesto_supervisa');	
			$empleado->perfil_puesto->id_empleado 			= $empleado->id;
		if($this->request->post('objetivo_general'))
			$empleado->perfil_puesto->objetivo_gral			= $this->request->post('objetivo_general');
		if($this->request->post('objetivo_especificos'))
			$empleado->perfil_puesto->objetivo_especifico	= $this->request->post('objetivo_especificos');
		if($this->request->post('estandares'))
			$empleado->perfil_puesto->estandares			= $this->request->post('estandares');
		if($this->request->post('complejidad_tareas'))
			$empleado->perfil_puesto->nivel_complejidad		= $this->request->post('complejidad_tareas');
		if($this->request->post('fecha_obtencion_result'))		
			$empleado->perfil_puesto->fecha_obtencion_result = \DateTime::createFromFormat('d/m/Y',$this->request->post('fecha_obtencion_result'));

		$actividades 							= $this->request->post('actividades');
		$resultados_finales 					= $this->request->post('resultados_finales');
		$old_actividad 							= isset($empleado->perfil_puesto->actividad) ? $empleado->perfil_puesto->actividad : null;
		$old_resultados 						= isset($empleado->perfil_puesto->resultados_parciales_finales) ? $empleado->perfil_puesto->resultados_parciales_finales : null;

		if(!empty($actividades)) {
			$empleado->perfil_puesto->actividad = []; 			
			foreach($actividades as $index => $dato){
				if(!empty($dato)){
					$aux = new \stdClass();
					$aux->id = $index;
					$aux->nombre = $dato;
					$empleado->perfil_puesto->actividad[$index] = $aux;
				}
			}
		}
		if(!empty($resultados_finales)){
			$empleado->perfil_puesto->resultados_parciales_finales = [];
			foreach($resultados_finales as $index => $dato){
				if(!empty($dato)){
					$aux = new \stdClass();
					$aux->id = $index;
					$aux->nombre = $dato;
					$empleado->perfil_puesto->resultados_parciales_finales[$index] = $aux;
				}
			}
		}
		if($empleado->perfil_puesto->validar()){
			$sin_cambios = true;
			if(!$empleado->perfil_puesto->id){
				$respuesta = $empleado->perfil_puesto->alta();
				if($respuesta){
					$msj = "El perfil del puesto se creo con éxito. ";
					$this->mensajeria->agregar(
						$msj,
						\FMT\Mensajeria::TIPO_AVISO,
						$this->clase
					);
				$sin_cambios = false;					
				}
			} else {
				$respuesta = $empleado->perfil_puesto->modificacion();
				if($respuesta['perfil']){
					$msj = "El perfil del puesto fue modificado con éxito. ";
					$this->mensajeria->agregar(
						$msj,
						\FMT\Mensajeria::TIPO_AVISO,
						$this->clase
					);
					$sin_cambios = false;
				}
				if(isset($respuesta['actividades'])){
					if ($respuesta['actividades'] != $old_actividad) {
						$msj = "Se actualizó las <b>Actividades/Tareas</b>. ";
						$this->mensajeria->agregar(
							$msj,
							\FMT\Mensajeria::TIPO_AVISO,
							$this->clase
						);
						$sin_cambios = false;
					}
				}
				if (isset($respuesta['resultados'])) {
					if ($respuesta['resultados'] != $old_resultados) {
						$msj = "Se actualizó los <b>Resultados Parciales/Finales</b>. ";
						$this->mensajeria->agregar(
						$msj,
						\FMT\Mensajeria::TIPO_AVISO,
						$this->clase
						);
						$sin_cambios = false;
					}
				}
			}
			if($sin_cambios)
				$this->mensajeria->agregar('No se detectaton modificacion en el Perfil de puesto', \FMT\Mensajeria::TIPO_ERROR, $this->clase);

		} else {
			$err = $empleado->perfil_puesto->errores;
			foreach ($err as $text) {
				$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
			}			
		}
	}



	private function m_antiguedad(Empleado $empleado=null){
		$modificacion	= !empty($empleado->persona->experiencia_laboral);
		///---------- Entidades ----------///

		$entidades_ids	= [];
		$save		= true;
		$el = false;
		if (!empty($this->request->post('entidad'))) {
			foreach ((array)$this->request->post('entidad') as $id => $data) {
				if($id !== 'new' && is_numeric($id)){
					$entidad					= Modelo\PersonaExperienciaLaboral::obtener($id);
					$control_entidad			= unserialize(serialize($entidad));

					$entidad->id_entidad		= (int)$data['id_entidad'];
					$entidad->fecha_desde		= !empty($data['fecha_desde']) ? \DateTime::createFromFormat('d/m/Y H:i:s',$data['fecha_desde'] . '0:00:00') : null;
					$entidad->fecha_hasta		= !empty($data['fecha_hasta']) ? \DateTime::createFromFormat('d/m/Y H:i:s',$data['fecha_hasta'] . '0:00:00') : null;
					if ($control_entidad != $entidad) {
						if($entidad->validar()){
							$save	= ($save && $el = $entidad->modificacion()) ? true : false;
						} else {
							foreach ((array)$entidad->errores as $text) {
								$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
							}
							return;
						}
					}
					$entidades_ids[]				= $entidad->id;
				}
			if($id == 'new'){
				$fila_indice	= 1;
				for($i=0; $i<count($data); $i++){
					$id_entidad		= $data[$i]['id_entidad'];
					$fecha_desde	= $data[++$i]['fecha_desde'];
					$fecha_hasta	= $data[++$i]['fecha_hasta'];

					if(empty($id_entidad) && empty($fecha_desde) && empty($fecha_hasta)){
						$fila_indice++;
						continue;
					}
					$entidad					= Modelo\PersonaExperienciaLaboral::obtener(null);
					$entidad->id_persona		= $empleado->persona->id;
					$entidad->id_entidad		= (int)$id_entidad;
					$entidad->fecha_desde		= !empty($fecha_desde) ? \DateTime::createFromFormat('d/m/Y H:i:s',$fecha_desde . ' 0:00:00') : null;
					$entidad->fecha_hasta		= !empty($fecha_hasta) ? \DateTime::createFromFormat('d/m/Y H:i:s',$fecha_hasta . ' 0:00:00') : null;

					if($entidad->validar()){
						$save	= ($save && $el =$entidad->alta()) ? true : false;
					} else {
						foreach ($entidad->errores as $text) {
							$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
						}
						return;
					}
					$fila_indice++;
					$entidades_ids[]				= $entidad->id;
				}
			}
		}
			if(!empty($empleado->persona->experiencia_laboral)){
				foreach ($empleado->persona->experiencia_laboral as $entidad) {
					if(!in_array($entidad->id, $entidades_ids)){
						$save	= ($save && $el = $entidad->baja()) ? true : false;
					}
				}
			}
		}
		$empleado->persona->experiencia_laboral	= Modelo\PersonaExperienciaLaboral::listar($empleado->persona->id);
		if(!$save){
			$this->mensajeria->agregar('Ocurrio un error al cargar los datos de experiencia laboral.', \FMT\Mensajeria::TIPO_ERROR, $this->clase);
		} else if($el && !empty($entidades_ids)){
			$this->mensajeria->agregar(
				"La experiencia laboral del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue ".($modificacion ? 'modificada':'cargada')." exitosamente.",
				\FMT\Mensajeria::TIPO_AVISO,
				$this->clase);
		}

		///---------- Antiguedad en el Ministerio ----------///
		$fim =false;
		$fg = false;
		$control = serialize(unserialize($empleado->antiguedad));
		$fecha_ing_aux = ($temp = \DateTime::createFromFormat('d/m/Y', $this->request->post('fecha_ingreso_mtr'))) ? $temp : $empleado->antiguedad->fecha_ingreso;
		$empleado->antiguedad->fecha_ingreso	= $fecha_ing_aux;
		$fecha_grado_aux = ($temp2 = \DateTime::createFromFormat('d/m/Y', $this->request->post('fecha_grado'))) ? $temp2 : $empleado->antiguedad->fecha_grado;
		$empleado->antiguedad->fecha_grado		= $fecha_grado_aux;

		if($empleado->validar()){
			if($empleado->cuit) { 
				if($this->request->post('fecha_ingreso_mtr') && $control->fecha_ingreso !== $empleado->antiguedad->fecha_ingreso){
					$fim = ($empleado->modificacion_ingreso()) ? true : false;
				}
				$ultimo_grado =  Modelo\EmpleadoUltimosCambios::obtener_grado($empleado->id);
				$ultimo_grado->id_convenios = $empleado->situacion_escalafonaria->id_grado;
				$ultimo_grado->fecha_desde = $empleado->antiguedad->fecha_grado;
				if(empty($ultimo_grado->id_convenios)){
					$this->mensajeria->agregar('Para asignar una <strong>Fecha Otorgamiento Grado</strong> debe especificar un Grado escalafonario.', \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					$empleado->antiguedad->fecha_grado	= $control->antiguedad->fecha_grado;
				} else {
					$fg = $ultimo_grado->guardar_grado();
				}
				if($ultimo_grado->errores) {
					foreach ($ultimo_grado->errores as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
                }
                if($empleado->errores){
                    foreach ($empleado->errores as $text) {
                        $this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
                    }
                }
			}
		} else {
			foreach ($empleado->errores as $text) {
				$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
			}
		}
		if ($fim) {
				$this->mensajeria->agregar(
				"La fecha de ingreso a MTR del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue modificada exitosamente.",
				\FMT\Mensajeria::TIPO_AVISO,
				$this->clase
			);
		}
		if ($fg) {
			$this->mensajeria->agregar(
				"La antigüedad en el grado del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue modificada exitosamente.",
				\FMT\Mensajeria::TIPO_AVISO,
				$this->clase
			);
		}
		return;
	}

	protected function sum_fecha($fecha) {
		if ($fecha) {
	    	$datetime2 = new \DateTime(date("Y-m-d H:i:s"));
	   		$interval = $fecha->diff($datetime2);
	   		return $interval->format('%y años, %m meses, %d días');
	    }
	    
	}
	
	protected function sum_fecha_total($fecha) {
		if ($fecha) {
	    	$datetime2 = new \DateTime(date("Y-m-d H:i:s"));
	   		$interval = $fecha->diff($datetime2);
	   		return $interval;
	    }
	    
	}


	private function am_varios(Empleado $empleado=null){
		$control 	 	= clone $empleado;
		$control_dis 	= clone $empleado->persona->discapacidad;
		$control_salud 	= clone $empleado->empleado_salud;
		$control_seguro = [];
		$err= '';
		foreach ($empleado->empleado_seguro as $value) {
			$control_seguro[$value->id] = $value->seguros;
		}

		$empleado->credencial									= ($this->request->post('credencial')) ? '1' : '0';
		$empleado->persona->discapacidad->id_tipo_discapacidad  = $this->request->post('tipo_discapacidad');
		$empleado->persona->discapacidad->cud					= $this->request->post('cud');
		$empleado->persona->discapacidad->observaciones			= $this->request->post('observaciones');
		$empleado->fecha_vencimiento		= \DateTime::createFromFormat('d/m/Y H:i:s', $this->request->post('fecha_vencimiento'). ' 0:00:00');
		$empleado->persona->discapacidad->fecha_vencimiento	= !empty($temp = $this->request->post('fecha_vencimiento_cud'))
																? \DateTime::createFromFormat('d/m/Y H:i:s', $temp . '0:00:00')
																: $empleado->persona->discapacidad->fecha_vencimiento;

		$empleado->empleado_salud->id_obra_social		= !empty($temp = $this->request->post('obra_social')) ? $temp : null;

		$empleado->empleado_salud->fecha_desde			= \DateTime::createFromFormat('d/m/Y H:i:s', gmdate('d/m/Y').'0:00:00');

		$empleado->empleado_salud->fecha_hasta			= !empty($temp = $this->request->post('fecha_hasta')) ? $temp : null;
		
		if(!empty($this->request->post('seguro_vida'))) {
			$empleado->empleado_seguro = [];
			$seguros_post = [];
			foreach ( $this->request->post('seguro_vida') as $key => $value) {
		    	
		    	$aux = new \StdClass();
				$aux->id = null;
		    	$aux->seguros = $value ;
		    	$empleado->empleado_seguro[] = $aux;
		    	$seguros_post[] = $value;					 
		    }
		} else  {
			$empleado->empleado_seguro = [];
		}    		

		
		$empleado->veterano_guerra						= ($this->request->post('veterano_guerra')) ? '1' : '0';

		if($empleado->persona->validar() && $empleado->validar()){
			if($empleado->cuit){
				if($control_dis != $empleado->persona->discapacidad){
					if($empleado->persona->discapacidad->id){
						if(
							$control_dis->id_tipo_discapacidad !== $empleado->persona->discapacidad->id_tipo_discapacidad
							|| $control_dis->cud != $empleado->persona->discapacidad->cud
							|| $control_dis->observaciones != $empleado->persona->discapacidad->observaciones
							|| $control_dis->fecha_vencimiento !== $empleado->persona->discapacidad->fecha_vencimiento
						){
							$empleado->persona->modificacion_discapacidad();
							$camb_disca = true;
						}else{
							$camb_disca = false;
						}
					} else {
						$empleado->persona->alta_discapacidad();
						$camb_disca = true;
					}
				}else{
					$camb_disca = false;
				}

				if(($control->credencial != $empleado->credencial) 
					|| ($control->fecha_vencimiento !== $empleado->fecha_vencimiento)
					|| ($control->veterano_guerra != $empleado->veterano_guerra)
				){
					$empleado->modificacion();
					$camb_cred = true;
				}else{
					$camb_cred = false;
				}
				        

				if($control_salud->id_obra_social != $empleado->empleado_salud->id_obra_social){
					if($empleado->empleado_salud->id){	
						$empleado->baja_empleado_salud();
						$camb_salud = true;
					}	
					$empleado->alta_empleado_salud();
					$camb_salud = true;

				}else{
					$camb_salud = false;
				}

					$resultado1 = array_diff((array)$seguros_post, $control_seguro);
					if (!empty ($resultado1)) {
							foreach ($resultado1 as $key => $value) {
								$empleado->alta_empleado_seguro($value);
							}
						}  
					$resultado2 = array_diff($control_seguro, (array)$seguros_post);
						if (!empty ($resultado2)) {
							foreach ($resultado2 as $key => $value) {
								$empleado->baja_empleado_seguro($key);
							}
					}						
						

				$post_sindicatos = (array)$this->request->post('sindicato');
				$post_otro_sindicato = $this->request->post('otro_sindicato');

				if($post_otro_sindicato ){
					$otro_sindicato = Modelo\EmpleadoSindicato::alta_otro_sindicato($post_otro_sindicato );
					if($otro_sindicato){
						array_push($post_sindicatos, $otro_sindicato);
						$camb_otro_sindicato = true;
					}else{
						$camb_otro_sindicato = false;
					}
				}

				$sindicatos = Modelo\EmpleadoSindicato::obtener();
				$sindicatos->id_empleado = $empleado->id;
				$empleado->sindicato = 	($camb_sindicato = $sindicatos->guardar($post_sindicatos)) ? $camb_sindicato : '';

				if(!$sindicatos->errores) {
					if((isset($camb_disca) && $camb_disca) || (isset($camb_cred) && $camb_cred) || (isset($camb_salud) && $camb_salud) || (isset($camb_otro_sindicato) && $camb_otro_sindicato) || (isset($camb_sindicato) && $camb_sindicato)){
						$texto = "Los datos <strong>varios</strong> del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fueron modificados exitosamente.";
						$tipo = \FMT\Mensajeria::TIPO_AVISO;
					}else{
						$texto = "No se modificaron los datos <strong>varios</strong> del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong>.";
						$tipo = \FMT\Mensajeria::TIPO_ERROR;
					}
					$this->mensajeria->agregar($texto, $tipo, $this->clase);
				}else{
					foreach ($sindicatos as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
		 	}
		} else {
			$err = array_merge((array)$empleado->persona->errores, (array)$empleado->errores);
			foreach ($err as $text) {
				$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
			}
		}
	}

	protected function am_ubicacion_estructura($empleado) {

		$control = clone $empleado->dependencia;
		$modificacion = false;
		$empleado->dependencia->id_dependencia = $this->request->post('dep_id_dependencia');
		$empleado->dependencia->nivel = $this->request->post('nivel_organigrama');
		$empleado->dependencia->id_dep_informal = $this->request->post('dep_id_informal');
		if($empleado->cuit && $control->id_dependencia != $empleado->dependencia->id_dependencia) {
			if($empleado->dependencia->fecha_desde) {
				if($empleado->validar()) {
					$aux = clone $empleado->dependencia;
					$empleado->dependencia= $control;
					$empleado->dependencia->fecha_hasta = \DateTime::createFromFormat('d/m/Y H:i:s', gmdate('d/m/Y').'0:00:00');
					$empleado->dependencia->id_dep_informal = $this->request->post('dep_id_informal');
					$empleado->modificacion();
					$empleado->baja_dependencia_informal();
					$empleado->dependencia = $aux;
					$empleado->dependencia->id = null;
					$modificacion = true;
				} else {
					$err	= $empleado->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
			$empleado->dependencia->fecha_desde = \DateTime::createFromFormat('d/m/Y H:i:s', gmdate('d/m/Y').'0:00:00');
			$empleado->dependencia->fecha_hasta = null;
			if($empleado->validar()) {
				$respuesta = $empleado->alta();
				if ($respuesta) {
					$texto = "La ubicación en la estructura del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue ".($modificacion ? 'modificado':'cargado')." exitosamente.";
					$tipo = \FMT\Mensajeria::TIPO_AVISO;
					$modificacion = true;
				}else{
					$texto = "No se modificaron los datos de la <strong>Ubicación en la Estructura</strong> del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong>.";
					$tipo = \FMT\Mensajeria::TIPO_ERROR;
				}
				$this->mensajeria->agregar($texto, $tipo, $this->clase);
				
			} else {
				$err	= $empleado->errores;
				foreach ($err as $text) {
					$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
				}
			}
		} else {
			if($control->id_dep_informal != $empleado->dependencia->id_dep_informal){
				if($empleado->validar()) {
					$empleado->dependencia->id_dep_informal = $this->request->post('dep_id_informal');
					$respuesta = $empleado->modificacion_dependencia_informal();
					if ($respuesta) {
						$this->mensajeria->agregar(
					 	"La ubicación en la estructura del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue actualizada correctamente",
						\FMT\Mensajeria::TIPO_AVISO,
					 	$this->clase);	
						$modificacion = true;
					}else{
						$this->mensajeria->agregar(
					 	"No se pudo modificar los datos de <strong>Ubicación en la Estructura</strong> del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong>.",
						 \FMT\Mensajeria::TIPO_ERROR,
					 	$this->clase);	
					}
				} else {
					$err	= $empleado->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		}
		
	}	


	protected function accion_ajax_convenios_parametricos(){
		if( empty($this->request->query('id_modalidad_vinculacion')) ){
			$this->json->setError();
			$this->json->render();
		}
		$empleado_cuit			= $this->request->query('id');
		Modelo\Empleado::contiene(['situacion_escalafonaria']);
		$empleado				= Modelo\Empleado::obtener($empleado_cuit);
		$permisos 				= $this->_get_permisos();
		$parametricos 			= $this->datos_parametricos();
		$modalidad_vinculacion 	= $this->request->query('id_modalidad_vinculacion');
		$situacion_revista 		= $this->request->query('id_situacion_revista');
		$data 					= [];
		if(empty($situacion_revista)){
			$data	= [
				'situacion_revista' => Modelo\Contrato::obtenerVinculacionRevista($this->request->query('id_modalidad_vinculacion'), false, true)['situacion_revista'],
			];
		} else {
			$data 	= Modelo\Contrato::obtenerConvenio($this->request->query('id_modalidad_vinculacion'), $this->request->query('id_situacion_revista'));
		}
		$tpl 						= new Vista(VISTAS_PATH.'/legajos/form_cambio_modalidad.php',compact(
			'empleado_cuit','empleado','parametricos','permisos','modalidad_vinculacion','situacion_revista'
		));
		$data['formulario']['html'] = "$tpl";
		$data['formulario']['tipo'] = ($modalidad_vinculacion == \App\Modelo\Contrato::PRESTACION_SERVICIOS) ? 2 : 1;

		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

	protected function accion_ajax_art_14_parametricos(){

		$data = Modelo\Empleado::getParam('EXCEPCION_ART_14');
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

		protected function accion_ajax_sindicato(){

		$data = Empleado::getSindicato();
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}




	protected function am_presupuesto($empleado) {
		$control = clone $empleado->presupuesto;
		$modificacion = false;
		$flag =true;
		$empleado->presupuesto->id_saf 					= $this->request->post('saf');
		$empleado->presupuesto->id_jurisdiccion 		= $this->request->post('jurisdiccion');
		$empleado->presupuesto->id_ubicacion_geografica = $this->request->post('ubicacion_geografica');
		$empleado->presupuesto->id_programa 			= $this->request->post('programa');
		$empleado->presupuesto->id_subprograma 			= $this->request->post('subprograma');
		$empleado->presupuesto->id_proyecto 			= $this->request->post('proyecto');
		$empleado->presupuesto->id_actividad 			= $this->request->post('actividad');
		$empleado->presupuesto->id_obra					= $this->request->post('obra');

		$presupuesto = Modelo\Presupuesto::obtener();
		$presupuesto->id_saf 				= $this->request->post('saf');
		$presupuesto->id_jurisdiccion 		= $this->request->post('jurisdiccion');
		$presupuesto->id_ubicacion_geografica = $this->request->post('ubicacion_geografica');
		$presupuesto->id_programa 			= $this->request->post('programa');
		$presupuesto->id_subprograma 		= $this->request->post('subprograma');
		$presupuesto->id_proyecto 			= $this->request->post('proyecto');
		$presupuesto->id_actividad 			= $this->request->post('actividad');
		$presupuesto->id_obra				= $this->request->post('obra');

		$presupuesto->obtener_presupuesto();
		$empleado->presupuesto->id_presupuesto = $presupuesto->id;

		if($empleado->presupuesto->id_presupuesto) {
			if($empleado->cuit &&  $empleado->presupuesto->id) {	  
				if( $empleado->presupuesto->id_presupuesto != $control->id_presupuesto) {

					if($empleado->validar()) {
						$aux = clone $empleado->presupuesto;
						$empleado->presupuesto = $control;
						$empleado->presupuesto->fecha_hasta = \DateTime::createFromFormat('d/m/Y H:i:s', gmdate('d/m/Y').'0:00:00');
						$empleado->modificacion_presupuesto();
						$empleado->presupuesto = $aux;
						$empleado->presupuesto->id = null;
						$modificacion = true;
					} else {
						$err	= $empleado->errores;
						foreach ($err as $text) {
							$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
						}
						return false;
					}
				} else {
					$flag = false;
				}	
			}
			if($flag) {
				$empleado->presupuesto->fecha_desde = \DateTime::createFromFormat('d/m/Y H:i:s', gmdate('d/m/Y').'0:00:00');
				if($empleado->validar()) {
					$empleado->alta_presupuesto();
					$this->mensajeria->agregar(
					"El presupuesto del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue ".($modificacion ? 'modificado':'cargado')." exitosamente.",
					\FMT\Mensajeria::TIPO_AVISO,
					$this->clase
					 );
				}else {
					$err	= $empleado->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
					return false;
				}
			}
		} else {
				$this->mensajeria->agregar(
				"La selección no está asociada a un <strong> PRESUPUESTO</strong> ",
				\FMT\Mensajeria::TIPO_ERROR,
				$this->clase
				 );
		}
	}

	protected function am_anticorrupcion(Modelo\Anticorrupcion $anticorrupcion=null) {

		$control = unserialize(serialize($anticorrupcion));
		$empleado	= Empleado::obtener($this->request->query('id'));
		$modificacion = false;
		$anticorrupcion->id_empleado 					= $empleado->id;
		$anticorrupcion->fecha_designacion 				= !empty($temp = $this->request->post('fecha_designacion')) ? \DateTime::createFromFormat('d/m/Y H:i:s', $temp . ' 00:00:00') : null;
		$anticorrupcion->fecha_publicacion_designacion  = !empty($temp = $this->request->post('fecha_publicacion_designacion')) ? \DateTime::createFromFormat('d/m/Y H:i:s', $temp . ' 00:00:00') : null;
		$anticorrupcion->fecha_aceptacion_renuncia 		= !empty($temp = $this->request->post('fecha_aceptacion_renuncia')) ? \DateTime::createFromFormat('d/m/Y H:i:s', $temp . ' 00:00:00') : null;
			if($anticorrupcion->id) {
				if($this->request->post('obligado_dj') == 1){
					if($control->fecha_designacion !== $anticorrupcion->fecha_designacion 
					|| $control->fecha_publicacion_designacion !== $anticorrupcion->fecha_publicacion_designacion
					|| $control->fecha_aceptacion_renuncia !== $anticorrupcion->fecha_aceptacion_renuncia
					|| $control->tipo_presentacion !== $anticorrupcion->tipo_presentacion
					|| $control->fecha_presentacion !== $anticorrupcion->fecha_presentacion
					|| $control->nro_transaccion !== $anticorrupcion->nro_transaccion)
					{
					if($anticorrupcion->validar()){
							$aux = unserialize(serialize($anticorrupcion));
							$anticorrupcion = $control;
							$aux->modificacion();
							$anticorrupcion = $aux;
							$modificacion = true;
							$this->a_designacion_transitoria($empleado, $anticorrupcion->fecha_publicacion_designacion);

							$this->mensajeria->agregar(
							 	"La Anticorrupcion para el empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue ".($modificacion ? 'modificada':'cargada')." exitosamente.",
							 	\FMT\Mensajeria::TIPO_AVISO,
							 	$this->clase
							 );
						}else {
							$err	= $anticorrupcion->errores;
								foreach ($err as $text) {
									$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
								}
							return false;
						}
					}
				}else{
					$anticorrupcion->baja();
						$this->mensajeria->agregar(
						 	"la Anticorrupcion para el empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue dada de baja exitosamente.",
						 	\FMT\Mensajeria::TIPO_AVISO,
						 	$this->clase
						 );
				}
			}
			else{
				if($anticorrupcion->validar()){
					if (isset($control->fecha_aceptacion_renuncia) || is_null($anticorrupcion->fecha_aceptacion_renuncia) ) {
						if ($anticorrupcion->alta()){
						$this->a_designacion_transitoria($empleado, $anticorrupcion->fecha_publicacion_designacion);

						$this->mensajeria->agregar(
						 	"la Anticorrupcion para el empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue ".($modificacion ? 'modificada':'cargada')." exitosamente.",
						 	\FMT\Mensajeria::TIPO_AVISO,
						 	$this->clase
						 );
						}
					}
				}
				if($anticorrupcion->errores){
					foreach ($anticorrupcion->errores as $value) {
						$this->mensajeria->agregar($value,\FMT\Mensajeria::TIPO_ERROR,$this->clase
						 );
					}
				}
			}
	}

/**
 * Alta Designacion Transitoria para modificaciones o altas puras de Anticorrupcion.
 *
 * @param Emppleado::	$empleado
 * @param \DateTime		$fecha_publicacion_designacion
 * @return bool
*/
	private function a_designacion_transitoria(Empleado $empleado=null, $fecha_publicacion_designacion=null){
		if(empty($empleado) || !($fecha_publicacion_designacion instanceof \DateTime)){
			return false;
		}
		$designacion_transitoria	= Modelo\Designacion_transitoria::obtener();
		$transitorias				= Modelo\Designacion_transitoria::$SR_DESIGNACION_TRANSITORIA;
		if(array_key_exists($empleado->situacion_escalafonaria->id_situacion_revista, $transitorias)) {
			$designacion_transitoria->id_empleado = $empleado->id;
			$designacion_transitoria->fecha_desde = $fecha_publicacion_designacion;
			$designacion_transitoria->tipo = Modelo\Designacion_transitoria::TRANSITORIA;
			return $designacion_transitoria->alta();
		}
		return false;
	}

/**
 * Metodo privado para carga de Titulos Obtenidos en la pestaña Formación.
 *
 * @param Empleado $empleado
 * @return bool - Si modificado = true
 */
    private function _am_formacion_titulos(Empleado $empleado=null){

        $titulo_checked	= (int)$this->request->post('titulo_checked');
		$titulos_ids	= [];
		$save			= false;
		$titulos_cambios = [];

		foreach ((array)$this->request->post('titulo') as $id => $data) {
			if($id !== 'new' && is_numeric($id)){
				$titulo						= Modelo\PersonaTitulo::obtener($id); //busca en persona_titulo, con el id que le pasas si ya existe el titulo
				$clon_comparacion   		= unserialize(serialize($titulo));
				$titulo->id_tipo_titulo		= !empty($data['id_tipo_titulo'])
					? (int)$data['id_tipo_titulo'] : $titulo->id_tipo_titulo;
				$titulo->id_estado_titulo	= !empty($data['id_estado_titulo'])
					? (int)$data['id_estado_titulo'] : $titulo->id_estado_titulo;
				$titulo->id_titulo			= !empty($data['id_titulo']) 
					? $data['id_titulo'] : $titulo->id_titulo;
				$titulo->fecha				= !empty($data['fecha']) 
					? \DateTime::createFromFormat('d/m/Y H:i:s.u',$data['fecha'] . ' 0:00:00.000000') : $titulo->fecha;
				$titulo->principal			= (bool)($titulo_checked == $titulo->id);

				if($clon_comparacion->id_persona == $titulo->id_persona 
					&& $clon_comparacion->id_tipo_titulo == $titulo->id_tipo_titulo
					&& $clon_comparacion->id_estado_titulo == $titulo->id_estado_titulo
					&& $clon_comparacion->id_titulo == $titulo->id_titulo
					&& $clon_comparacion->fecha == $titulo->fecha
					&& $clon_comparacion->principal == $titulo->principal
				){
					$titulos_ids[] = $titulo->id;
					continue;
				}
				if($titulo->id_persona == $empleado->persona->id && $titulo->validar()){
					$titulo_nuevo = Modelo\Titulo::obtener($titulo->id_titulo);
                    $_save	= $titulo->modificacion();
                    $save   = ($save || $_save);
                    if($save){
						$this->mensajeria->agregar(
						"El título <strong>{$titulo_nuevo->nombre}</strong> del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue modificado correctamente",
						\FMT\Mensajeria::TIPO_AVISO,
						$this->clase);
					}else{
						$this->mensajeria->agregar(
							"No se pudo modificar el título del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong>",
							\FMT\Mensajeria::TIPO_ERROR,
							$this->clase);
					}
				} else if(!empty($titulo->errores)) {
					foreach ($titulo->errores as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
					return false;
				}
				$titulos_ids[] = $titulo->id;
			}
			if($id == 'new'){
				$fila_indice	= 1;
				for($i=0; $i<count($data); $i++){					
					$id_tipo_titulo		= Arr::get($data[$i], 'id_tipo_titulo', null);
					$id_estado_titulo	= Arr::get($data[$i], 'id_estado_titulo', null);
					$id_titulo		    = Arr::get($data[$i], 'id_titulo', null);
					$fecha				= Arr::get($data[$i], 'fecha', null);

					if(empty($id_tipo_titulo) && empty($id_estado_titulo) && empty($id_titulo) && empty($fecha)){
						$fila_indice++;
						continue;
					}
					$titulo						= Modelo\PersonaTitulo::obtener(null);
					$titulo->id_persona			= $empleado->persona->id;
					$titulo->id_tipo_titulo		= (int)$id_tipo_titulo;
					$titulo->id_estado_titulo	= (int)$id_estado_titulo;
					$titulo->id_titulo			= $id_titulo;
					$titulo->fecha				= !empty($fecha) ? \DateTime::createFromFormat('d/m/Y H:i:s.u',$fecha . ' 0:00:00.000000') : null;
					$titulo->principal			= (bool)($titulo_checked == $i);

					if($titulo->validar()){
						$titulo_nuevo = Modelo\Titulo::obtener($titulo->id_titulo);
						$_save	= $titulo->alta();
                        $save   = ($save || $_save);
                        if($save){
							$this->mensajeria->agregar(
							"El título <strong>{$titulo_nuevo->nombre}</strong> del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue cargado correctamente",
					\FMT\Mensajeria::TIPO_AVISO,
					$this->clase);
						}else{
							$this->mensajeria->agregar(
							"No se pudo cargar el título del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong>",
					\FMT\Mensajeria::TIPO_ERROR,
					$this->clase);
						}
					} else if(!empty($titulo->errores)){
						foreach ($titulo->errores as $text) {
							$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
						}
						$empleado->persona->titulos	= Modelo\PersonaTitulo::listar($empleado->persona->id);
						return false;
					}
					$fila_indice++;
				}
			}
		}
		
		if(!empty($empleado->persona->titulos)){
			foreach ($empleado->persona->titulos as $titulo) {
				if(!in_array($titulo->id, $titulos_ids)){
					$titulo_borrado = Modelo\PersonaTitulo::obtener($titulo->id);
					$titulo_elim = Modelo\Titulo::obtener($titulo_borrado->id_titulo);
                    $_save	= $titulo->baja();
                    $save   = ($save || $_save);
                    if($save){
							$this->mensajeria->agregar(
							"Ha dado de baja el título <strong>{$titulo_elim->nombre}</strong> del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> correctamente", \FMT\Mensajeria::TIPO_AVISO, $this->clase);
					}else{
							$this->mensajeria->agregar(
							"No se ha podido dar de baja el título <strong>{$titulo_elim->nombre}</strong> del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong>", \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		}
        $empleado->persona->titulos	= Modelo\PersonaTitulo::listar($empleado->persona->id);
        return true;
    }

/**
 * Metodo privado para carga de "Otros Estudios Realizados" y "Conocimiento Específico de Sistemas/Software" en la pestaña Formación.
 *
 * @param Empleado $empleado
 * @return bool - Si modificado = true
 */
    private function _am_formacion_estudios_realizados(Empleado $empleado=null){
        $save				= true;
		$otros_estudios_ids	= [];
		$new				= [];
		$merge 				= [];
		for($i=0; $i < count($temp = $this->request->post('otros_estudios')['new']); $i++ ){
			$i_1 = $i;
			$i_2 = $i + 1;
			if(!empty($temp[$i_1]['descripcion']) && isset($temp[$i_2]['fecha'])) {
				$new[]	= [
					'descripcion'	=> $temp[$i_1]['descripcion'],
					'fecha'			=> $temp[$i_2]['fecha'],
				];
			}
		}
		for($i=0; $i < count($temp = $this->request->post('otros_conocimientos')['new']); $i++ ){
			if(!empty($descripcion = $temp[$i]['descripcion'])) {
				$new[]	= [
					'descripcion'	=> $descripcion,
				];
			}
		}
	
		$merge 	= (array)$this->request->post('otros_estudios');
		$merge += (array)$this->request->post('otros_conocimientos');
		foreach ($merge as $id => $data) {
			if($id !== 'new' && is_numeric($id)){
				$otro_estudio					= Modelo\PersonaOtroConocimiento::obtener($id);
				$clon_comparacion   		= unserialize(serialize($otro_estudio));
				$otro_estudio->descripcion		= $data['descripcion'];
				$otro_estudio->fecha		= !empty($data['fecha']) ? \DateTime::createFromFormat('d/m/Y H:i:s.u', $data['fecha'] . ' 0:00:00.000000') : null;
				
				if($clon_comparacion->id_persona == $otro_estudio->id_persona
					&& $clon_comparacion->id_tipo == $otro_estudio->id_tipo 
					&& $clon_comparacion->fecha == $otro_estudio->fecha
					&& $clon_comparacion->descripcion == $otro_estudio->descripcion){

					$otros_estudios_ids[]				= $otro_estudio->id;
					continue;
				}

				if($otro_estudio->id_persona == $empleado->persona->id && $otro_estudio->validar()){
					$save	= ($save && $ee_otro_estudio = $otro_estudio->modificacion()) ? true : false;
					if($save){
							$this->mensajeria->agregar(
							"El estudio/conocimiento <strong>{$otro_estudio->descripcion}</strong> del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue modificado correctamente",
					\FMT\Mensajeria::TIPO_AVISO,
					$this->clase);
						}else{
							$this->mensajeria->agregar(
							"No se pudo modificar el estudio/conocimiento <strong>{$otro_estudio->descripcion}</strong> del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong>",
					\FMT\Mensajeria::TIPO_ERROR,
					$this->clase);
						}
				} else {
					foreach ($otro_estudio->errores as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
					return false;
				}
				$otros_estudios_ids[]				= $otro_estudio->id;
			}
		}

		foreach ($new as $id => $data) {
			$otro_estudio					= Modelo\PersonaOtroConocimiento::obtener(null);
			$otro_estudio->id_persona		= $empleado->persona->id;
			$otro_estudio->id_tipo			= (isset($data['fecha'])) ? Modelo\PersonaOtroConocimiento::ESTUDIO : Modelo\PersonaOtroConocimiento::CONOCIMIENTO;
			$otro_estudio->descripcion		= $data['descripcion'];
			$otro_estudio->fecha			= (isset($data['fecha']) && !empty($data['fecha'])) ? \DateTime::createFromFormat('d/m/Y H:i:s',$data['fecha'] . ' 0:00:00') : null;
			if($otro_estudio->validar()){
				$save	= ($save && $ee_otro_estudio =$otro_estudio->alta()) ? true : false;
				if($save){
							$this->mensajeria->agregar(
							"El estudio/conocimiento <strong>{$otro_estudio->descripcion}</strong> del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue cargado correctamente",
					\FMT\Mensajeria::TIPO_AVISO,
					$this->clase);
						}else{
							$this->mensajeria->agregar(
							"No se pudo cargar el estudio/conocimiento del agente<strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong>",
					\FMT\Mensajeria::TIPO_ERROR,
					$this->clase);
						}	
			} else {
				foreach ($otro_estudio->errores as $text) {
					$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
				}
				return false;
			}
			$otros_estudios_ids[]				= $otro_estudio->id;
		}

		
		if(!empty($empleado->persona->otros_conocimientos)){
			foreach ($empleado->persona->otros_conocimientos as $otros_conocimientos) {
				if(!in_array($otros_conocimientos->id, $otros_estudios_ids)){
					$otro_conocimiento = Modelo\PersonaOtroConocimiento::obtener($otros_conocimientos->id);
					$save	= ($save && $ee_otro_estudio = $otros_conocimientos->baja());
					if($save){
							$this->mensajeria->agregar(
							"Ha dado de baja el conocimiento <strong>{$otro_conocimiento->descripcion}</strong> del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> correctamente", \FMT\Mensajeria::TIPO_AVISO, $this->clase);
					}else{
							$this->mensajeria->agregar(
							"No se ha podido dar de baja el conocimiento <strong>{$otro_conocimiento->descripcion}</strong> del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong>", \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		}

		$empleado->persona->otros_conocimientos	= Modelo\PersonaOtroConocimiento::listar($empleado->persona->id);
		return true;
    }

    private function _am_formacion_cursos(Empleado $empleado=null){
		$empleado_cursos_ids		= [];
		$save						= true;

		foreach ((array)$this->request->post('empleado_cursos') as $id => $data) {
			if($id != 'new' && is_numeric($id)){
				$empleado_cursos			= Modelo\EmpleadoCursos::obtener($id); //busca en empleados_cursos donde el id sea ese que le pasas de curso.
				$control_empleado_cursos	= unserialize(serialize($empleado_cursos));
				$empleado_cursos->id_curso	= $data['id_curso'];
				$empleado_cursos->fecha		= !empty($data['fecha']) ? \DateTime::createFromFormat('d/m/Y H:i:s.u',$data['fecha'] . ' 0:00:00.000000') : null;
				$empleado_cursos->tipo_promocion	= !empty($data['tipo_promocion']) 
						? Modelo\Curso::PROMOCION_TRAMO
						: Modelo\Curso::PROMOCION_GRADO;
				if($control_empleado_cursos->id_empleado == $empleado_cursos->id_empleado
					&& $control_empleado_cursos->id_curso == $empleado_cursos->id_curso 
					&& $control_empleado_cursos->fecha == $empleado_cursos->fecha
					&& $control_empleado_cursos->tipo_promocion == $empleado_cursos->tipo_promocion){

					$empleado_cursos_ids[]				= $empleado_cursos->id;
					continue;
				}

				if($empleado_cursos->id_empleado == $empleado->id && $empleado_cursos->validar()){
					$curso = Curso::obtener($empleado_cursos->id_curso);
					$save	= ($save && $ee_cursos = $empleado_cursos->modificacion()) ? true : false;
					if($save){
							$this->mensajeria->agregar(
							"El curso <strong>{$curso->nombre_curso}</strong> del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue modificado correctamente", \FMT\Mensajeria::TIPO_AVISO, $this->clase);
					}else{
							$this->mensajeria->agregar(
							"No se pudo modificar el curso <strong>{$curso->nombre_curso}</strong> del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong>", \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				} else {
					foreach ($empleado_cursos->errores as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
					return false;
				}
				$empleado_cursos_ids[]				= $empleado_cursos->id;
			}
		}

		foreach ((array)$this->request->post('empleado_cursos') as $id => $data) {
			if($id == 'new'){
				for($i=0; $i<count($data); $i++){
					$id_curso			= $data[$i]['id_curso'];
					$fecha				= $data[$i]['fecha'];
					$tipo_promocion     = !empty($data[$i]['tipo_promocion']) 
						? Modelo\Curso::PROMOCION_TRAMO
						: Modelo\Curso::PROMOCION_GRADO;
					if(empty($id_curso) && empty($fecha)){
						continue;
					}
					$empleado_cursos					= Modelo\EmpleadoCursos::obtener();
					$empleado_cursos->id_empleado		= $empleado->id;
					$empleado_cursos->id_curso			= (int)$id_curso;
					$empleado_cursos->fecha				= !empty($fecha) ? \DateTime::createFromFormat('d/m/Y H:i:s',$fecha . ' 0:00:00') : null;
					$empleado_cursos->tipo_promocion	= $tipo_promocion;

					if($empleado_cursos->validar()){
						$curso = Curso::obtener($empleado_cursos->id_curso);
						$save	= ($save && $ee_cursos = $empleado_cursos->alta()) ? true : false;
						if($save){
							$this->mensajeria->agregar(
							"El curso {$curso->nombre_curso} del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue cargado correctamente",
					\FMT\Mensajeria::TIPO_AVISO,
					$this->clase);
						}else{
							$this->mensajeria->agregar(
							"No se pudo cargar el curso {$curso->nombre_curso} del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong>",
					\FMT\Mensajeria::TIPO_ERROR,
					$this->clase);
						}
					} else {
						foreach ($empleado_cursos->errores as $text) {
							$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
						}
						return false;
					}

					$empleado_cursos_ids[]				= $empleado_cursos->id;
				}
			}
		}

		if(!empty($empleado->empleado_cursos)){
			foreach ($empleado->empleado_cursos as $cursos) {
				if(!in_array($cursos->id, $empleado_cursos_ids)){
					$curso = Modelo\EmpleadoCursos::obtener($cursos->id);
					$curso_borrado = Curso::obtener($curso->id_curso);
					$save	= ($save && $ee_cursos = $cursos->baja()) ? true : false;
					if($save){
							$this->mensajeria->agregar(
							"Ha dado de baja el curso <strong>{$curso_borrado->nombre_curso}</strong> del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> correctamente", \FMT\Mensajeria::TIPO_AVISO, $this->clase);
					}else{
							$this->mensajeria->agregar(
							"No se ha podido dar de baja el curso <strong>{$curso_borrado->nombre_curso}</strong> del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong>", \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		}
		$empleado->empleado_cursos	= Modelo\EmpleadoCursos::listar($empleado->id);
		return true;
    }

    private function am_formacion(Empleado $empleado=null) {
        if(!empty($this->request->post('titulo'))){
            $result_titulo = $this->_am_formacion_titulos($empleado);
            if(!$result_titulo){
            	$this->mensajeria->agregar(
							"Ha ocurrido un error al cargar información de títulos para guardar.",
					\FMT\Mensajeria::TIPO_ERROR,
					$this->clase);
            }
        }
        if(!empty($this->request->post('otros_conocimientos')) || !empty($this->request->post('otros_estudios'))){
            $result_otros_con = $this->_am_formacion_estudios_realizados($empleado);
            if(!$result_otros_con){
            	$this->mensajeria->agregar(
							"Ha ocurrido un error al cargar información de otros estudios/conocimientos",
					\FMT\Mensajeria::TIPO_ERROR,
					$this->clase);
            }
        }
        if(!empty($this->request->post('empleado_cursos'))){
            $result_cursos = $this->_am_formacion_cursos($empleado);
             if(!$result_cursos){
            	$this->mensajeria->agregar(
							"Ha ocurrido un error al cargar información de cursos",
					\FMT\Mensajeria::TIPO_ERROR,
					$this->clase);
            }
        }
	}


	/** Obtiene los permisos necesarios para la accion 
	*/
	protected function _get_permisos() {
		//Obtención de permisos.
		$excepcion= AppRoles::excepcion_permisos();
 		$permisos['datos_personales'] = AppRoles::puede('datos_personales','alta');
 		$permisos['escalafonario'] = AppRoles::puede('escalafon','alta');
		if($permisos['escalafonario']) {
			$permisos['escalafonario'] = [
				'nivel'						=> $excepcion && AppRoles::puede_atributo('escalafon','alta','campos','nivel'),
				'grado'						=> $excepcion && AppRoles::puede_atributo('escalafon','alta','campos','grado'),
				'grado_liquidacion'			=> $excepcion && AppRoles::puede_atributo('escalafon','alta','campos','grado_liquidacion'),
				'compensacion_transitoria'	=> $excepcion &&AppRoles::puede_atributo('escalafon','alta','campos','compensacion_transitoria'),
				'agrupamiento'				=> $excepcion && AppRoles::puede_atributo('escalafon','alta','campos','agrupamiento'),
				'tramo'						=> $excepcion && AppRoles::puede_atributo('escalafon','alta','campos','tramo'),
				'modalidad_vinculacion'		=> AppRoles::puede_atributo('escalafon','alta','campos','modalidad_vinculacion'),
				'situacion_revista'			=> AppRoles::puede_atributo('escalafon','alta','campos','situacion_revista'),
				'compensacion_geografica'	=> AppRoles::puede_atributo('escalafon','alta','campos','compensacion_geografica'),
				'funcion_ejecutiva'			=> AppRoles::puede_atributo('escalafon','alta','campos','funcion_ejecutiva'),
				'ultimo_cambio_nivel'		=> $excepcion && AppRoles::puede_atributo('escalafon','alta','campos','ultimo_cambio_nivel'),
				'fecha_vigencia_mandato'	=> AppRoles::puede_atributo('escalafon','alta','campos','fecha_vigencia_mandato'),
				'id_sindicato'				=> AppRoles::puede_atributo('escalafon','alta','campos','id_sindicato'),
				'delegado_gremial'			=> $excepcion && AppRoles::puede_atributo('escalafon','alta','campos','delegado_gremial'),
				'exc_art_14'			    => AppRoles::puede_atributo('escalafon','alta','campos','exc_art_14'),
				'formacion'			        => AppRoles::puede_atributo('escalafon','alta','campos','exc_art_14'),
				'unidad_retributiva'		=> AppRoles::puede_atributo('escalafon','alta','campos','unidad_retributiva'),
			];
		}

		$permisos['perfiles_puestos'] = AppRoles::puede('perfil','alta');
		if($permisos['perfiles_puestos']) {
			$permisos['perfiles_puestos'] = [
				'familia_puestos'			=> $excepcion && AppRoles::puede_atributo('perfil','alta','campos','familia_puestos'),
				'nombre_puesto'				=> $excepcion && AppRoles::puede_atributo('perfil','alta','campos','nombre_puesto'),
				'nivel_destreza'			=> $excepcion && AppRoles::puede_atributo('perfil','alta','campos','nivel_destreza'),
				'puesto_supervisa'			=> $excepcion && AppRoles::puede_atributo('perfil','alta','campos','puesto_supervisa'),
				'denominacion_funcion'		=> AppRoles::puede_atributo('perfil','alta','campos','denominacion_funcion'),
				'denominacion_puesto'		=> AppRoles::puede_atributo('perfil','alta','campos','denominacion_puesto'),
				'objetivo_general'			=> AppRoles::puede_atributo('perfil','alta','campos','objetivo_general'),
				'objetivo_especificos'		=> AppRoles::puede_atributo('perfil','alta','campos','objetivo_especificos'),
				'estandares'				=> AppRoles::puede_atributo('perfil','alta','campos','estandares'),
				'nivel_complejidad'			=> $excepcion && AppRoles::puede_atributo('perfil','alta','campos','nivel_complejidad'),
				'actividades'				=> AppRoles::puede_atributo('perfil','alta','campos','actividades'),
				'fecha_obtencion_result'	=> AppRoles::puede_atributo('perfil','alta','campos','fecha_obtencion_result'),
				'resultados_finales'		=> AppRoles::puede_atributo('perfil','alta','campos','resultados_finales'),
			];
		}
 		$permisos['antiguedad'] = AppRoles::puede('antiguedad','alta');
 		$permisos['antiguedad_grado'] = $excepcion && AppRoles::puede('antiguedad_grado','alta');
		$permisos['ubicacion_estructura'] = AppRoles::puede('ubicacion_estructura','alta');
		$permisos['formacion'] = $excepcion && AppRoles::puede('formacion','alta');
		$permisos['administracion'] = AppRoles::puede('administracion','alta');
 		$permisos['varios'] = AppRoles::puede('varios','alta');
		$permisos['presupuesto'] = AppRoles::puede('presupuestos','alta');
 		$permisos['bloque_presupuesto'] = AppRoles::puede_atributo('Presupuestos','alta','tab_visible','presupuesto');
		$permisos['anticorrupcion'] = AppRoles::puede('anticorrupcion','alta');
		$permisos['bloque_anticorrupcion'] = AppRoles::puede_atributo('Anticorrupcion','alta','tab_visible','anticorrupcion');
		$permisos['embargo'] = AppRoles::puede('Legajos','alta_embargo');
		$permisos['bloque_embargo'] = AppRoles::puede_atributo('Embargos','index','tab_visible','embargo');	
		$permisos['grupo_familiar'] = [
			'alta'						=> AppRoles::puede('Legajos','alta_familiar'),
			'baja'						=> AppRoles::puede('Legajos','baja_familiar'),
			'modificacion'				=> AppRoles::puede('Legajos','modificar_familiar'),
		];

		 return $permisos;
	}

	protected function am_administracion($empleado){
		$control_ubi			= $empleado->ubicacion->id_ubicacion;
		$control_horario		= clone $empleado->horario;
		$control_lic			= clone $empleado->licencia;
		$control_estado			= (object)[
			'estado'		=> $empleado->estado,
			'fecha_baja'	=> $empleado->fecha_baja,
			'id_motivo'		=> $empleado->id_motivo,
		];
		$control_en_comision	= clone $empleado->en_comision;

		$empleado->horario->horarios     = json_encode($this->request->post('horarios'));
		$empleado->horario->id_turno     = $this->request->post('id_turno');
		$empleado->horario->fecha_inicio = $empleado->horario->fecha_inicio;
		$empleado->horario->fecha_fin    = $empleado->horario->fecha_fin;
		$post_p_reloj = $this->request->post('planilla_reloj');
		$empleado->ubicacion->id_ubicacion = $this->request->post('id_ubicacion');
		$empleado->licencia->id_licencia = $this->request->post('id_licencia');
		$empleado->licencia->fecha_desde = ($temp = \DateTime::createFromFormat('d/m/Y H:i:s', $this->request->post('fecha_desde').'0:00:00')) ? $temp : '';
		$empleado->licencia->fecha_hasta = ($temp = \DateTime::createFromFormat('d/m/Y H:i:s', $this->request->post('fecha_hasta').'0:00:00')) ? $temp : '';

		$empleado->id_motivo 	= ($temp = $this->request->post('id_motivo')) ? $temp : $empleado->id_motivo;
		$empleado->fecha_baja	= ($temp = \DateTime::createFromFormat('d/m/Y H:i:s', $this->request->post('fecha_baja').'0:00:00')) 
								? $temp : $empleado->fecha_baja;
		$empleado->estado 		= is_numeric($temp = $this->request->post('activo')) ? $temp : $empleado->estado;

		$empleado->en_comision->activo		= is_numeric($temp = $this->request->post('comision')) ? $temp : $empleado->en_comision->activo;
		$empleado->en_comision->id_origen	= ($temp = $this->request->post('organismo_origen')) ? $temp : $empleado->en_comision->id_origen;
		$empleado->en_comision->id_destino	= ($temp = $this->request->post('organismo_destino')) ? $temp : $empleado->en_comision->id_destino;

		if($empleado->cuit){
			$grilla = $this->request->post('horarios');
			$horas = false;
			foreach ($grilla as $value) {
				if(!empty($value[0]) && !empty($value[1])){
					$horas = true;
				}
			}
			if($horas && ($control_horario != $empleado->horario)){
				if($empleado->validar()){
					if($empleado->horario->id){
						$empleado->modificacion_horario();											
					}elseif(!empty($empleado->horario->horarios)){						
						$empleado->horario->fecha_inicio = \DateTime::createFromFormat('d/m/Y H:i:s', gmdate('d/m/Y').'0:00:00');
						$empleado->alta_horario();
					}
				}else{
					$err    = $empleado->errores;
	                foreach ($err as $text) {
	                    $this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
	                }
	                return false;
				}
				$camb_horario = true;
			}else {
				$camb_horario = false;
			}
			if($this->request->post('adm_ubicacion_accion') && ($control_ubi != $empleado->ubicacion->id_ubicacion) && !empty($empleado->ubicacion->id_ubicacion)){
				if($this->request->post('adm_ubicacion_accion') == 'alta'){
					if($empleado->validar()){
						if($empleado->ubicacion->id){
							$aux = $empleado->ubicacion->id_ubicacion;
							$aux2 =\App\Modelo\Ubicacion::obtener($aux);
							$empleado->ubicacion->id_ubicacion = $control_ubi;
							$empleado->ubicacion->fecha_hasta = \DateTime::createFromFormat('d/m/Y H:i:s', gmdate('d/m/Y').'0:00:00');
							$empleado->modificacion_ubicacion();
							$empleado->ubicacion->id_ubicacion = $aux;
							$empleado->ubicacion->id_edificio = $aux2->id_edificio;
							$empleado->ubicacion->nombre	  = $aux2->nombre;
							$empleado->ubicacion->calle		  = $aux2->calle;
							$empleado->ubicacion->numero	  = $aux2->numero;
							$empleado->ubicacion->piso		  = $aux2->piso;
							$empleado->ubicacion->oficina	  = $aux2->oficina;
						}
						$empleado->ubicacion->fecha_desde = \DateTime::createFromFormat('d/m/Y H:i:s', gmdate('d/m/Y').'0:00:00');
						$empleado->ubicacion->fecha_hasta = null;
						$empleado->alta_ubicacion();
					} else {
						$err    = $empleado->errores;
		                foreach ($err as $text) {
		                    $this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
		                }
		                return false;
					}
				}
				if($this->request->post('adm_ubicacion_accion') == 'modificacion'){		
					if($empleado->validar()){
						$aux =\App\Modelo\Ubicacion::obtener($empleado->ubicacion->id_ubicacion);
						$empleado->ubicacion->id_edificio = $aux->id_edificio;
						$empleado->ubicacion->nombre	  = $aux->nombre;
						$empleado->ubicacion->calle		  = $aux->calle;
						$empleado->ubicacion->numero	  = $aux->numero;
						$empleado->ubicacion->piso		  = $aux->piso;
						$empleado->ubicacion->oficina	  = $aux->oficina;
						$empleado->modificacion_ubicacion();
					}else{
						$err    = $empleado->errores;
		                foreach ($err as $text) {
		                    $this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
		                }
		                return false;
					}
				}
				$camb_ubi = true;
			} else if($this->request->post('adm_ubicacion_accion') && empty($empleado->ubicacion->id_ubicacion)){
				$this->mensajeria->agregar('Para cambiar los <strong>Datos de Ubicación</strong> es requisito llenar todos los campos; <strong>Edificio, Calle/Número, Piso y Oficina</strong>', \FMT\Mensajeria::TIPO_ERROR, $this->clase);
				$camb_ubi = false;
			} else {
				$camb_ubi = false;	
			}
			if($this->request->post('adm_licencia_accion') && $control_lic != $empleado->licencia) {
					if($this->request->post('adm_licencia_accion') == 'alta'){
						if($empleado->validar()){				
							if($control_lic->id){
								$aux = clone $empleado->licencia;
								$empleado->licencia = $control_lic; 
								$empleado->baja_licencias_especiales();
								$empleado->licencia = $aux;
							}
							$empleado->alta_licencias_especiales();
						}else{
							$err    = $empleado->errores;
			                foreach ($err as $text) {
			                    $this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
			                }
			                return false;	
						}
					}
					if($this->request->post('adm_licencia_accion') == 'modificacion'){		
						if($empleado->validar()){
							$empleado->modificacion_licencias_especiales();
						}else{
							$err    = $empleado->errores;
			                foreach ($err as $text) {
			                    $this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
			                }
			                return false;
						}
					}
					$camb_licencia = true;
			}else{
				$camb_licencia = false;
			}
			if($empleado->planilla_reloj != $post_p_reloj){
				$empleado->planilla_reloj = $this->request->post('planilla_reloj');
				$empleado->modificacion_empleado_planilla_reloj();
				if($empleado->errores){
					foreach ($empleado->errores as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
					$camb_planilla =false;
				} else {
					$camb_planilla =true;
				}
			}else{
				$camb_planilla = false;
			}
			$mod_estado = false;
			if($control_estado->fecha_baja != $empleado->fecha_baja  || $control_estado->id_motivo != $empleado->id_motivo) {
				$mod_estado = true;
			}
			if($control_estado->estado != $empleado->estado) {
				if($empleado->estado == Modelo\Empleado::EMPLEADO_ACTIVO){
					$empleado->estado		= Modelo\Empleado::EMPLEADO_ACTIVO;
					$empleado->fecha_baja	= null;
					$empleado->id_motivo	= null;
					$mod_estado = true;
				}
			}
			if($mod_estado) {
				$empleado->modificacion_estado();
				if($empleado->errores){
					foreach ($empleado->errores as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
					$camb_estado =false;
				} else {
					$camb_estado =true;
				}
			}else{
				$camb_estado =false;
			}

			if($control_en_comision->activo 	!= $empleado->en_comision->activo 
			|| $control_en_comision->id_origen 	!= $empleado->en_comision->id_origen 
			|| $control_en_comision->id_destino != $empleado->en_comision->id_destino) {
				if($empleado->en_comision->activo!= Modelo\Empleado::ESTADO_ACTIVO) {
					$empleado->en_comision->activo			= Modelo\Empleado::ESTADO_INACTIVO;
					$empleado->en_comision->id_origen		= null;
					$empleado->en_comision->id_destino		= null;
				}
				$empleado->modificacion_comision();
				if($empleado->errores){
					foreach ($empleado->errores as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
					$camb_comision =false;
				} else {
					$camb_comision =true;
				}
			}else{
				$camb_comision =false;	
			}
			if($camb_horario || $camb_ubi || $camb_licencia || $camb_planilla || $camb_estado || $camb_comision) {
				$text = "Los datos del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> han sido cargados y/o modificados exitosamente.";
				$tipo = \FMT\Mensajeria::TIPO_AVISO; 
			}else{
				$text = "No se modificaron los datos del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong>.";
				$tipo = \FMT\Mensajeria::TIPO_ERROR;				
			} 
			$this->mensajeria->agregar($text, $tipo, $this->clase);
		}

	}

	protected function accion_agentes(){
		$incluir_estados = [
			Modelo\Empleado::EMPLEADO_INACTIVO	=> Modelo\Empleado::EMPLEADO_INACTIVO,
			Modelo\Empleado::EMPLEADO_ACTIVO	=> Modelo\Empleado::EMPLEADO_ACTIVO,
		];
		$parametros = [
			'select_dependencia' 		=> Dependencia::listar(true),
			'situacion_revista'			=> [],
			'modalidad_contratacion' 	=> Contrato::listadoModalidadVinculacion(),
			'estado' 					=> array_intersect_key(Modelo\Empleado::getParam('TIPO_ESTADOS_EMPLEADOS'), $incluir_estados),
		];
		$this->setGetVarSession('info_global', false);
		$this->setGetVarSession('buscar_cuit', false);
		$this->setGetVarSession('info_anti'  , false);
		$this->setGetVarSession('info_recoleccion'  , false);

		$vista = $this->vista;
 		$permisos['nuevos'] = AppRoles::puede('datos_personales','alta');
		$permisos['exportar'] = AppRoles::puede('Legajos', 'exportar');
		$permisos['recibos'] = AppRoles::puede('Recibos','index');
		(new Vista($this->vista_default,compact('parametros','vista','permisos')))->pre_render();
	}

	protected function accion_ajax_lista_agentes(){
		$dataTable_columns	= $this->request->query('columns');
		$orders	= [];
		foreach($orden = (array)$this->request->query('order') as $i => $val){
			$orders[]	= [
				'campo'	=> (!empty($tmp = $orden[$i]) && !empty($dataTable_columns) && is_array($dataTable_columns[0]))
						? $dataTable_columns[ (int)$tmp['column'] ]['data']	:	'id',
				'dir'	=> !empty($tmp = $orden[$i]['dir'])
						? $tmp	:	'desc',
			];
		}
		$params	= [
			'order'		=> $orders,
			'start'		=> !empty($tmp =$this->request->query('start'))
						? $tmp : 0,
			'lenght'	=> !empty($tmp = $this->request->query('length'))
						? $tmp : 10,
			'search'	=> !empty($tmp = $this->request->query('search'))
						? $tmp['value'] : '',
			'filtros'	=> [
				'dependencia'				=> $this->request->query('dependencia'),
				'directos'					=> $this->request->query('directos'),
				'modalidad_contratacion'	=> $this->request->query('modalidad_contratacion'),
				'situacion_revista'			=> $this->request->query('situacion_revista'),
				'estado' 					=> $this->request->query('estado')
			],
		];

				

		$data = Empleado::listadoAgentes($params);

		$datos['draw']	= (int) $this->request->query('draw');


		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
		
	}

	protected function accion_ajax_lista_observaciones(){
		Empleado::contiene();
        $empleado = Empleado::obtener($this->request->query('id')); 
		$dataTable_columns	= $this->request->query('columns');
		$orders	= [];
		foreach($orden = (array)$this->request->query('order') as $i => $val){
			$orders[]	= [
				'campo'	=> (!empty($tmp = $orden[$i]) && !empty($dataTable_columns) && is_array($dataTable_columns[0]))
						? $dataTable_columns[ (int)$tmp['column'] ]['data']	:	'id',
				'dir'	=> !empty($tmp = $orden[$i]['dir'])
						? $tmp	:	'desc',
			];
		}
		$params	= [
			'order'		=> $orders,
			'start'		=> !empty($tmp =$this->request->query('start'))
						? $tmp : 0,
			'lenght'	=> !empty($tmp = $this->request->query('length'))
						? $tmp : 10,
			'search'	=> !empty($tmp = $this->request->query('search'))
						? $tmp['value'] : '',
			'id_empleado' => $empleado->id			
		];

		$data = Observacion::listadoObservaciones($params);

		$datos['draw']	= (int) $this->request->query('draw');


		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
		
	}
		protected function accion_ajax_lista_historico_anticorrupcion(){

		$dataTable_columns	= $this->request->query('columns');
		$orders	= [];

		foreach($orden = (array)$this->request->query('order') as $i => $val){
				$orders[]	= [
					
				'campo'	=> (!empty($tmp = $orden[$i]) && !empty($dataTable_columns) && is_array($dataTable_columns[0]))
						? $dataTable_columns[(int)$tmp['column']]['data']	:	'id',
				'dir'	=> !empty($tmp = $orden[$i]['dir'])
						? $tmp	:	'desc',
			];
		}
		$params	= [
			'order'		=> $orders,
			'start'		=> !empty($tmp =$this->request->query('start'))
						? $tmp : 0,
			'lenght'	=> !empty($tmp = $this->request->query('length'))
						? $tmp : 10,
			'search'	=> !empty($tmp = $this->request->query('search'))
						? $tmp['value'] : '',
			'filtros'	=> [
				'periodo'	=> $this->request->query('periodo'),
				'tipo_dj'	=> $this->request->query('tipo_dj')
			],
		];		
		$data = Anticorrupcion::listadoHistorialAnticorrupcion($params);
		$datos['draw']	= (int) $this->request->query('draw');


		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
		
	}


	protected function accion_observaciones(){
		Empleado::contiene();
		$empleado = Empleado::obtener($this->request->post('cuit'));
		$observacion = \App\Modelo\Observacion::obtener($this->request->post('id_ob'));
		$observacion->id_empleado = $empleado->id;
		if ($this->request->post('borrado')) {
			$observacion->borrado = $this->request->post('borrado');
		}else{
			$observacion->descripcion = $this->request->post('descripcion');
			$user 	= Usuario::obtenerUsuarioLogueado();
			$observacion->id_usuario = $user->id;
			$observacion->id_bloque = $this->request->post('bloque');
		}
		if(!$observacion->id) {
			$observacion->fecha = \DateTime::createFromFormat('d/m/Y H:i:s', gmdate('d/m/Y').'0:00:00');
			if(!empty($observacion->descripcion)){
				$datos =	$observacion->alta();
			}else{
				$data['error']= true;
				(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
			}
		} else {
			$datos =	$observacion->modificacion();
		}
 
		$data = [ 'result' => true];

		 (new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}


	protected function accion_mostrar_presentacion(){
		$archivo = Modelo\Anticorrupcion::obtener_archivo($this->request->query('id'));
		$doc_content = preg_replace("/\d{14}_/", "", $archivo);
		$doc = BASE_PATH.'/uploads/anticorrupcion/'.$archivo ;
		header("Content-Disposition:inline;filename=".$doc_content."");
		header("Content-type: application/pdf;");		
		readfile($doc);
	}

	protected function accion_historial_presentacion() {
		$empleado	= Empleado::obtener($this->request->query('id'));
		$anticorrupcion = Anticorrupcion::obtener($empleado->id);
		$presentacion = $anticorrupcion->listar_presentacion();
		$vista = $this->vista;
		$vars = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit];
 		$vista->add_to_var('vars',$vars);
		(new Vista($this->vista_default,compact('vista','presentacion','empleado')))->pre_render();
	}



	protected function accion_historial_horas_extras() {
		$empleado	= Empleado::obtener($this->request->query('id'));
	 	$horas_extras = $empleado->listar_horas_extras();
		$vista = $this->vista;
		$vars = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit];
 		$vista->add_to_var('vars',$vars);
		(new Vista($this->vista_default,compact('vista','empleado' ,'horas_extras')))->pre_render();
	}


	protected function accion_alta_embargo() {
		$empleado	= Empleado::obtener($this->request->query('id'));
		$embargo = Embargo::obtener();
		if (!empty($empleado->id) && !empty($empleado->cuit)){
			$tipo_embargo= \App\Modelo\Embargo::getParam('TIPO_EMBARGO');
			$embargo->id_empleado 		= $empleado->id;
			$embargo->tipo_embargo 		= !empty($this->request->post('tipo_embargo')) ?  (int)$this->request->post('tipo_embargo'): $embargo->tipo_embargo;
			$embargo->autos 			= ($temp = $this->request->post('autos')) ?  $temp : null;
			$embargo->fecha_alta 		= ($temp = $this->request->post('fecha_alta')) ?  \DateTime::createFromFormat('d/m/Y', $temp) : null;
			$embargo->fecha_cancelacion	= ($temp = $this->request->post('fecha_cancelacion')) ?  \DateTime::createFromFormat('d/m/Y', $temp) : null;
			$embargo->monto 			= ($temp = $this->request->post('monto')) ?  $temp : null;

			if($this->request->post('boton_embargo') == 'alta') {
					if($embargo->validar()){
						$embargo->alta();
						$this->mensajeria->agregar(
							"El Embargo del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue cargado exitosamente.",
							\FMT\Mensajeria::TIPO_AVISO,
							$this->clase
						 );
						$select_tab = 'tab_embargo';
						$this->setGetVarSession('data_legajo', ['select_tab' => $select_tab]);
						$redirect =Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");
						$this->redirect($redirect);
					}else {
						$err	= $embargo->errores;
						foreach ($err as $text) {
							$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
						}
					}
			}

			$vista = $this->vista;
			$nombre_de_puesto = \App\Modelo\Perfil::listarNombrePuestos();
			$puesto = \FMT\Helper\Arr::path($nombre_de_puesto,"{$empleado->perfil_puesto->nombre_puesto}.nombre",'-');
	 		$vars = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit];
	 		if($puesto != '') {
	 			$vars['DENOMINACION']=  $puesto;
	 		}
			$vista->add_to_var('vars',$vars);
			(new Vista($this->vista_default,compact('vista','permisos','empleado', 'tipo_embargo', 'embargo')))->pre_render();
		}else{
			$this->mensajeria->agregar("PARA DEFINIR EL <strong>EMBARGO</strong>, ES REQUISITO TENER LOS DATOS BÁSICOS DEL <strong>AGENTE</strong>.", \FMT\Mensajeria::TIPO_ERROR, $this->clase);
		}
	}

	protected function accion_historial_embargo() {
		$empleado	= Empleado::obtener($this->request->query('id'));
		$historial = Embargo::listar_historial($empleado->id);
		$vista = $this->vista;
		$nombre_de_puesto = \App\Modelo\Perfil::listarNombrePuestos();
		$puesto = \FMT\Helper\Arr::path($nombre_de_puesto,"{$empleado->perfil_puesto->nombre_puesto}.nombre",'-');

		(new Vista($this->vista_default,compact('vista', 'historial' ,'empleado','puesto')))->pre_render();
	}

	protected function accion_modificar_embargo() {
		$embargo = Embargo::obtener_embargo($this->request->query('id'));
		$empleado	= Empleado::obtener($embargo->id_empleado, true);
		if (!empty($empleado->id) && !empty($empleado->cuit)){
			$tipo_embargo= \App\Modelo\Embargo::getParam('TIPO_EMBARGO');
			$embargo->id_empleado 		= $empleado->id;
			$embargo->tipo_embargo 		= !empty($this->request->post('tipo_embargo')) ?  (int)$this->request->post('tipo_embargo'): $embargo->tipo_embargo;
			$embargo->autos 			= !empty($temp = $this->request->post('autos')) ?  $temp : $embargo->autos;
			$embargo->fecha_alta 		= !empty($temp = $this->request->post('fecha_alta')) ?  \DateTime::createFromFormat('d/m/Y', $temp) : $embargo->fecha_alta;
			$embargo->fecha_cancelacion	= !empty($temp = $this->request->post('fecha_cancelacion')) ?  \DateTime::createFromFormat('d/m/Y', $temp) : $embargo->fecha_cancelacion;
			$embargo->monto 			= !empty($temp = $this->request->post('monto')) ?  $temp : $embargo->monto;
			if($this->request->post('boton_embargo') == 'modificacion') {
					if($embargo->validar()){
						$embargo->modificacion();
						$this->mensajeria->agregar(
						"El embargo del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);

						$select_tab = 'tab_embargo';
						$this->setGetVarSession('data_legajo', ['select_tab' => $select_tab]);
						$redirect =Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");
						$this->redirect($redirect);

					}else {
						$err	= $embargo->errores;
						foreach ($err as $text) {
							$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
						}
					}
			}
			$vista = $this->vista;
			$nombre_de_puesto = \App\Modelo\Perfil::listarNombrePuestos();
			$puesto = \FMT\Helper\Arr::path($nombre_de_puesto,"{$empleado->perfil_puesto->nombre_puesto}.nombre",'-');
	 		$vars = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit];
	 		if($puesto != '') {
	 			$vars['DENOMINACION']=  $puesto;
	 		}
			$vista->add_to_var('vars',$vars);
			(new Vista($this->vista_default,compact('vista','empleado', 'embargo', 'tipo_embargo')))->pre_render();
		}else{
			$this->mensajeria->agregar("PARA DEFINIR EL <strong>EMBARGO</strong>, ES REQUISITO TENER LOS DATOS BÁSICOS DEL <strong>AGENTE</strong>.", \FMT\Mensajeria::TIPO_ERROR, $this->clase);
		}
	}

	 protected function accion_baja_embargo() {
		$embargo = Modelo\Embargo::obtener_embargo($this->request->query('id'));
		$empleado	= Empleado::obtener($embargo->id_empleado, true);
		if (!empty($empleado->id) && !empty($empleado->cuit)){
			if($embargo->id) {
				$select_tab = 'tab_embargo';
				$this->setGetVarSession('data_legajo', ['select_tab' => $select_tab]);
				if ($this->request->post('confirmar')) {
					$res = $embargo->baja();
					if ($res) {
						$this->mensajeria->agregar('AVISO: El embargo se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);

					} else {
						$this->mensajeria->agregar('AVISO: No es posible eliminar el embargo con agentes asignados.',\FMT\Mensajeria::TIPO_ERROR,$this->clase);
					}
					$redirect =Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");
					$this->redirect($redirect);
			}
			$vista = $this->vista;
			$vars = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit];
				$vista->add_to_var('vars',$vars);
			(new Vista($this->vista_default,compact('vista','empleado', 'embargo')))->pre_render();
			}
		}
	}

		protected function accion_datos_globales(){
		$id_unidad_ministros = 1;
		$id_sca = 6;
		$id_sgt = 3;
		$id_spt = 2;
		$id_sot = 4;
		$dotacion_total 	= Empleado::dotacion_total();
		$personal_unidad 	= Empleado::personal_por_unidad([$id_unidad_ministros,$id_sca,$id_sgt,$id_spt,$id_sot]);
		$vinculacion 		= Empleado::vinculacion();
		$formacion 			= Empleado::formacion();
		$situacion_genero	= Empleado::situacion_genero();
		$total =$dotacion_total[0]['total'];
		$vinc[0] = ['total' => $total ,'cantidad' =>0 , 'id_modalidad_vinculacion' =>'adp', 'id_situacion_revista' =>'' ];
		$ii=1;
		$flag = null;
		##############################################################################################
		# LISTADO DE COMBINACIONES BUSCADAS 													    ##
		# 1 - 3  SINEP - DESIGNACION TRANSITORIA EN CARGO DE PLANTA PERMANENTE CON FUNCION EJECUTIVA #	
		# 1 - 4  SINEP - PLANTA PERMANENTE MTR CON DESIGNACION TRANSITORIA    						##
		# 1 - 15 SINEP - PLANTA PERMANENTE MTR DESIGNACION TRANSITORIA CON FUNCION EJECUTIVA  		##
		##############################################################################################
		foreach ($vinculacion as $key => $value) {
			switch (true) {
				case ($value['id_modalidad_vinculacion'] == 1 && $value['id_situacion_revista'] == 3):
				case ($value['id_modalidad_vinculacion'] == 1 && $value['id_situacion_revista'] == 4):
				case ($value['id_modalidad_vinculacion'] == 1 && $value['id_situacion_revista'] == 15):
				case ($value['id_modalidad_vinculacion'] == 4 && $value['id_situacion_revista'] == 11):
					$vinc[0]['cantidad'] = $vinc[0]['cantidad'] +$value['cantidad'];
					break;
				case ($value['id_modalidad_vinculacion'] == 6):
					$flag = ($flag) ? $ii :$flag;
					if(!isset($vinc[$flag])){
						$vinc[$flag] =  $value;						
					}else{
						$vinc[$flag]['cantidad'] = $vinc[0]['cantidad'] + $value['cantidad'];	
					}					
					break;					
				case(is_null($value['id_modalidad_vinculacion']) && is_null($value['id_situacion_revista'])):
					$aux_vinc = $value;
					break; 					
				default:
					$vinc[] =$value;
					break;
			}
			$ii++;
		}
		$vinc[0]['porcentaje'] 		=  (string)round($vinc[0]['cantidad']*100/$total,2);
		$vinc[$flag]['porcentaje']	=  (string)round($vinc[$flag]['cantidad']*100/$total,2);
		if(isset($aux_vinc)) {
			$vinc[$ii] = $aux_vinc;
			$vinc[$ii]['id_modalidad_vinculacion'] ='dpr';
		}	
		$vinculacion = $vinc;


		##########################################################################################
		#																						##
		#	CALCULO DE PORCENTAJES Y TOTALES DE RESULTADO DE QUERY DE FORMACION					##
		#																						##
		##########################################################################################
		foreach ($formacion as $key => $value) {
			if($key != 'reco'){ 
				foreach ($value as $est => $val) {
					$aux = array_count_values(array_column($val, 'genero'));
					$h = Arr::get($aux, \App\Modelo\Persona::MASCULINO,0);
					$m = Arr::get($aux,\App\Modelo\Persona::FEMENINA,0);
					$total_titulo = count($val);
					$porc_titulo = $total_titulo * 100/$total;
					$porc_genero_h = $h * 100/$total;
					$porc_genero_m = $m * 100/$total;
					$resultado[$key][$est] = ['total' => $total_titulo,
											'porc_titulo' => $porc_titulo ,
											'h' => $h,
											'm' => $m,
											'porc_genero_m' => $porc_genero_m,
											'porc_genero_h' => $porc_genero_h];
				}
			} else {
				$aux = array_count_values(array_column($value, 'genero'));
					$h = Arr::get($aux, \App\Modelo\Persona::MASCULINO,0);
					$m = Arr::get($aux,\App\Modelo\Persona::FEMENINA,0);
					$total_titulo = count($value);
					$porc_titulo = $total_titulo * 100/$total;
					$porc_genero_h = $h * 100/$total;
					$porc_genero_m = $m * 100/$total;
					$resultado[$key] = ['total' => $total_titulo,
											'porc_titulo' => $porc_titulo ,
											'h' => $h,
											'm' => $m,
											'porc_genero_m' => $porc_genero_m,
											'porc_genero_h' => $porc_genero_h];
			}
		}

		$modalidad_revista = Modelo\Contrato::obtenerVinculacionRevista(null,false,true);
		$estado_titulo = \App\Modelo\PersonaTitulo::getParam('ESTADO_TITULO');
		$nivel_e = \App\Modelo\NivelEducativo::getParam('NIVEL_EDUCATIVO');

		$this->setGetVarSession('info_global',true);
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista','modalidad_revista','nivel_e','estado_titulo','dotacion_total','personal_unidad','vinculacion','resultado','situacion_genero')))->pre_render();
	}

	protected function accion_datos_recoleccion(){
		$this->setGetVarSession('info_anti', false);	
		$this->setGetVarSession('buscar_cuit', false);
		$this->setGetVarSession('info_global', false);
		$this->setGetVarSession('info_recoleccion',1);
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista')))->pre_render();
	}

	protected function accion_ajax_datos_recoleccion(){
		$dataTable_columns	= $this->request->query('columns');
		$orders	= [];
		foreach($orden = (array)$this->request->query('order') as $i => $val){
			$orders[]	= [
				'campo'	=> (!empty($tmp = $orden[$i]) && !empty($dataTable_columns) && is_array($dataTable_columns[0]))
						? $dataTable_columns[ (int)$tmp['column'] ]['data']	:	'id',
				'dir'	=> !empty($tmp = $orden[$i]['dir'])
						? $tmp	:	'desc',
			];
		}
		$params	= [
			'order'		=> $orders,
			'start'		=> !empty($tmp =$this->request->query('start'))
						? $tmp : 0,
			'lenght'	=> !empty($tmp = $this->request->query('length'))
						? $tmp : 10,
			'search'	=> !empty($tmp = $this->request->query('search'))
						? $tmp['value'] : '',
		];

		$data = Empleado::datos_recoleccion_por_unidad($params);
		$datos['draw']	= (int) $this->request->query('draw');
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

	protected function accion_ajax_datos_vinculacion(){
		$dataTable_columns	= $this->request->query('columns');
		$orders	= [];
		foreach($orden = (array)$this->request->query('order') as $i => $val){
			$orders[]	= [
				'campo'	=> (!empty($tmp = $orden[$i]) && !empty($dataTable_columns) && is_array($dataTable_columns[0]))
						? $dataTable_columns[ (int)$tmp['column'] ]['data']	:	'id',
				'dir'	=> !empty($tmp = $orden[$i]['dir'])
						? $tmp	:	'desc',
			];
		}
		$params	= [
			'order'		=> $orders,
			'start'		=> !empty($tmp =$this->request->query('start'))
						? $tmp : 0,
			'lenght'	=> !empty($tmp = $this->request->query('length'))
						? $tmp : 10,
			'search'	=> !empty($tmp = $this->request->query('search'))
						? $tmp['value'] : '',
		];

		$data = Empleado::datos_recoleccion_por_vinculacion($params);
		$datos['draw']	= (int) $this->request->query('draw');
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

	protected function accion_ajax_datos_formacion(){
		$dataTable_columns	= $this->request->query('columns');
		$orders	= [];
		foreach($orden = (array)$this->request->query('order') as $i => $val){
			$orders[]	= [
				'campo'	=> (!empty($tmp = $orden[$i]) && !empty($dataTable_columns) && is_array($dataTable_columns[0]))
						? $dataTable_columns[ (int)$tmp['column'] ]['data']	:	'id',
				'dir'	=> !empty($tmp = $orden[$i]['dir'])
						? $tmp	:	'desc',
			];
		}
		$params	= [
			'order'		=> $orders,
			'start'		=> !empty($tmp =$this->request->query('start'))
						? $tmp : 0,
			'lenght'	=> !empty($tmp = $this->request->query('length'))
						? $tmp : 10,
			'search'	=> !empty($tmp = $this->request->query('search'))
						? $tmp['value'] : '',
		];

		$data = Empleado::datos_recoleccion_por_formacion($params);
		$datos['draw']	= (int) $this->request->query('draw');
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

	protected function accion_listado_anticorrupcion(){
		$id_bloque = \App\Helper\Bloques::ANTICORRUPCION;
		$this->setGetVarSession('info_global', false);
		$this->setGetVarSession('buscar_cuit', false);
		$this->setGetVarSession('info_anti', 1);
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'id_bloque')))->pre_render();
	}
	
	protected function accion_historial_anticorrupcion(){
		$parametros = [
			'tipo_dj' 			=> Modelo\Anticorrupcion::getParam('TIPO_DJ'),
		];
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'parametros')))->pre_render();
	}

	protected function accion_ajax_listado_anticorrupcion(){
		$col_cant_dias	= 7;
		$col_estado 	= 8;
		$excep_orden = [7 => 'cant_dias', 8 => 'id_estado'];
        $campo_orden = false;
        $dataTable_columns	= $this->request->query('columns');
		$orders	= [];
		foreach($orden = (array)$this->request->query('order') as $i => $val){
			if(!in_array($val['column'],[$col_cant_dias,$col_estado])) {
				$orders[]	= [
					'campo'	=> (!empty($tmp = $orden[$i]) && !empty($dataTable_columns) && is_array($dataTable_columns[0]))
							? $dataTable_columns[ (int)$tmp['column'] ]['data']	:	'id',
					'dir'	=> !empty($tmp = $orden[$i]['dir'])
							? $tmp	:	'desc',
				];
			} else {
				 $campo_orden = $excep_orden[$val['column']];
				 $campo_dir   = $orden[$i]['dir'];	
			}
		}
		$params	= [
			'order'		=> $orders,
			'start'		=> !empty($tmp =$this->request->query('start'))
						? $tmp : 0,
			'lenght'	=> !empty($tmp = $this->request->query('length'))
						? $tmp : 10,
			'search'	=> !empty($tmp = $this->request->query('search'))
						? $tmp['value'] : '',
		
		];

		$params2 = $params;
		
		if ($campo_orden) {
			unset($params2['start'], $params2['lenght']);
		}

		$listado =  Anticorrupcion::informe_anticorrupcion($params2);

		$id_bloque = \App\Helper\Bloques::ANTICORRUPCION;
		$fecha_actual = date_create(gmdate('Y-m-d'));

		foreach ($listado['data'] as &$value) { 
			$value->bloque = $id_bloque;
			$value->estado = 'sin_presentacion';
			$value->id_estado = 1;
			$value->cant_dias = '-';
			$flag = false;
			switch (true) {
				case ($value->tipo_presentacion == Anticorrupcion::INICIAL):
					if($value->fecha_presentacion->format('Y') == date('Y')) {
						$f_inicio = $fecha_actual;
						$value->estado = 'verde';
						$value->id_estado = 0;
					} else {
						$f_inicio = date_create(gmdate('Y').'-05-31');
						//$value->tipo_presentacion = Anticorrupcion::ANUAL;
					}
					break;
				case (IS_NULL($value->tipo_presentacion)):
					$f_inicio = $value->fecha_publicacion_designacion;
					$value->tipo_presentacion = Anticorrupcion::INICIAL;
					break;
				case ($value->fecha_aceptacion_renuncia || $value->tipo_presentacion == Anticorrupcion::BAJA):

					if($value->tipo_presentacion == Anticorrupcion::BAJA) {
						$f_inicio = $fecha_actual; 
						$value->estado = 'verde';
						$value->id_estado = 0;
					}else{
						if($value->fecha_presentacion->format('Y') < date('Y') && $value->tipo_presentacion == Anticorrupcion::BAJA) {
							$f_inicio = $value->fecha_aceptacion_renuncia;
						}else{
							$f_inicio = $value->fecha_aceptacion_renuncia;
							$value->tipo_presentacion = Anticorrupcion::BAJA;
							$value->estado = 'verde';
							$value->id_estado = 0;
						}
					}
					break;
				case ($value->tipo_presentacion == Anticorrupcion::ANUAL):
					if($value->fecha_presentacion->format('Y') == date('Y') ) {
						$f_inicio = $fecha_actual;
						$value->estado = 'verde';
						$value->id_estado = 0;
					}else{
						$f_inicio = date_create(gmdate('Y').'-05-31');
						//$value->fecha_presentacion = '';
						//$value->periodo = '';	
					}
					break;
				default:
					break;
			}
				
			if (isset($value)) {
				if (!is_null($f_inicio) && $fecha_actual > $f_inicio) {
					$cant_dias = \FMT\Informacion_fecha::cantidad_dias_habiles($f_inicio);
					$value->cant_dias = $cant_dias;
					if($value->tipo_presentacion == Anticorrupcion::INICIAL){
						if($cant_dias <= 15){
							$value->estado = 'amarillo';
							$value->id_estado = 2;
						}
						if($cant_dias > 15){
							$value->estado = 'rojo';
							$value->id_estado = 3;
						}
					}else{
						if($cant_dias > 30 && $cant_dias <= 45){
							$value->estado = 'amarillo';
							$value->id_estado = 2;
						}
						if($cant_dias > 45){
							$value->estado = 'rojo';
							$value->id_estado = 3;
						}
					}
				}
				$value->cant_dias = ($value->estado == 'sin_presentacion') ? '' : $value->cant_dias;
			}
		}
		if ($campo_orden) {
			$listado = \App\Modelo\Anticorrupcion::informe_anticorrupcion_temporal($listado,[$campo_orden, $campo_dir, $params['start'], $params['lenght']]);
		}	
		
		array_walk($listado['data'], function(&$item){
			$camp=['fecha_designacion','fecha_publicacion_designacion','fecha_aceptacion_renuncia','fecha_presentacion'];
			for($i=0;$i<count($camp);$i++) {
				if($item->{$camp[$i]} instanceof \DateTime) {
					$item->{$camp[$i]} = $item->{$camp[$i]}->format('d/m/Y');
				}
			}
			$item->tipo_presentacion = (is_numeric($item->tipo_presentacion)) ? \App\Modelo\Anticorrupcion::$TIPO_DJ[$item->tipo_presentacion]['nombre'] : $item->tipo_presentacion;
		});

		$data = $listado;
		$datos['draw']	= (int) $this->request->query('draw');
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

	protected function accion_presentacion() {

		$empleado	= Empleado::obtener($this->request->query('id'));
		
		$anticorrupcion = Anticorrupcion::obtener($empleado->id);
		
		$tipo_presentacion = \App\Modelo\Anticorrupcion::getParam('TIPO_DJ');

		$anticorrupcion->fecha_presentacion 			= ($temp = $this->request->post('fecha_presentacion')) ?  \DateTime::createFromFormat('d/m/Y', $temp) : null;

		$anticorrupcion->periodo 						= ($temp = $this->request->post('periodo')) ?  $temp : null;

		$anticorrupcion->nro_transaccion 				= ($temp = $this->request->post('nro_transaccion')) ?  $temp : null;

		$anticorrupcion->archivo =null;
		
		if ($this->request->post('boton_presentacion') == 'alta') {
			$anticorrupcion->id_empleado 					= $empleado->id;
			$anticorrupcion->tipo_presentacion  			= !empty($this->request->post('tipo_presentacion')) ?  (int)$this->request->post('tipo_presentacion'): $anticorrupcion->tipo_presentacion;
			$anticorrupcion->archivo				= ($_FILES['anticorrupcion_file']['error'] == UPLOAD_ERR_OK) ? $_FILES['anticorrupcion_file'] : null;
			$anticorrupcion->id_presentacion = '';
			if($anticorrupcion->id) {
				if($anticorrupcion->validar()){
					$anticorrupcion->alta_presentacion();
					$this->mensajeria->agregar(
					 	"la Presentación del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue cargada exitosamente.",
					 	\FMT\Mensajeria::TIPO_AVISO,
					 	$this->clase
					 );
					$select_tab = 'tab_anticorrupcion';
					$this->setGetVarSession('data_legajo', ['select_tab' => $select_tab]);
					$redirect =Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");
					$this->redirect($redirect);	
				
				}else {
					$err	= $anticorrupcion->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}	 
		}

		$vista = $this->vista;
		$vars = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit];
			$vista->add_to_var('vars',$vars);
		(new Vista($this->vista_default,compact('vista','empleado', 'tipo_presentacion', 'anticorrupcion')))->pre_render();
	}

	
	protected function accion_nueva_evaluacion() {
		Empleado::contiene(['persona', 'perfil_puesto', 'situacion_escalafonaria']);
		$empleado	= Modelo\Empleado::obtener($this->request->query('id'));
		$perfil 	= $empleado->perfil_puesto;
		$evaluacion	= Modelo\Evaluacion::obtener();
		$formularios= Modelo\Evaluacion::getParam('formularios');
		$resultados	= Modelo\Evaluacion::getParam('resultados');
		$evaluacion->acto_administrativo 	= !empty($this->request->post('acto')) ?  $this->request->post('acto'): $evaluacion->acto_administrativo;
		$evaluacion->evaluacion 			= !empty($this->request->post('resultados')) ?  (int)$this->request->post('resultados'): $evaluacion->evaluacion;
		$evaluacion->anio 					= !empty($this->request->post('anio')) ?  (int)$this->request->post('anio'): $evaluacion->anio;
		$evaluacion->formulario 			= !empty($this->request->post('formularios')) ?  (int)$this->request->post('formularios'): $evaluacion->formulario;
		$evaluacion->puntaje 				= !empty($this->request->post('puntaje')) ?  (int)$this->request->post('puntaje'): $evaluacion->puntaje;
		$evaluacion->bonificado 			= !empty($this->request->post('bonificado')) ?  (int)$this->request->post('bonificado'): $evaluacion->bonificado;

		if ($this->request->post('boton_evaluacion') == 'alta') {
			$evaluacion->id_perfil 				= $perfil->id;
			$evaluacion->id_empleado			= $empleado->id;
			$evaluacion->acto_administrativo 	= !empty($this->request->post('acto')) ?  $this->request->post('acto'): $evaluacion->acto_administrativo;
			$evaluacion->archivo				= ($_FILES['evaluacion_file']['error'] == UPLOAD_ERR_OK) ? $_FILES['evaluacion_file'] : null;
			$evaluacion->evaluacion 			= !empty($this->request->post('resultados')) ?  (int)$this->request->post('resultados'): $evaluacion->evaluacion;
			$evaluacion->anio 					= !empty($this->request->post('anio')) ?  (int)$this->request->post('anio'): $evaluacion->anio;
			$evaluacion->formulario 			= !empty($this->request->post('formularios')) ?  (int)$this->request->post('formularios'): $evaluacion->formulario;
			$evaluacion->fecha_evaluacion =   date('Y-m-d');
			$evaluacion->puntaje = !empty($this->request->post('puntaje')) ?  (int)$this->request->post('puntaje'): $evaluacion->puntaje;
			$evaluacion->bonificado = !empty($this->request->post('bonificado')) ?  (int)$this->request->post('bonificado'): $evaluacion->bonificado;
			if($evaluacion->alta()){
				$this->mensajeria->agregar(
					"La Evaluación del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue cargada exitosamente.",
					\FMT\Mensajeria::TIPO_AVISO,
					$this->clase
					);
				$select_tab = 'tab_perfiles_puestos';
				$this->setGetVarSession('data_legajo',['select_tab'	=> $select_tab]);
				$redirect =Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");
				$this->redirect($redirect);
			}else {
				foreach ((array)$evaluacion->errores as $text) {
					$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
				}
			}
		}
		$vista = $this->vista;
		$vars = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit];
			$vista->add_to_var('vars',$vars);
		(new Vista($this->vista_default,compact('vista','evaluacion','empleado', 'formularios', 'resultados')))->pre_render();
	}

	protected function accion_historial_evaluacion() {
		Modelo\Empleado::contiene(['persona']);
		$empleado		= Modelo\Empleado::obtener($this->request->query('id'));
		$evaluaciones	= Modelo\Evaluacion::listar($empleado->id);
		$formularios	= Modelo\Evaluacion::getParam('formularios');
		$resultados		= Modelo\Evaluacion::getParam('resultados');
		$vista = $this->vista;
		$vars = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit];
		 $vista->add_to_var('vars',$vars);
		(new Vista($this->vista_default,compact('vista','formularios','resultados','evaluaciones','empleado')))->pre_render();
	}

	protected function accion_update_evaluacion() {
		$evaluacion = \App\Modelo\Evaluacion::obtener($this->request->query('id'));
		Empleado::contiene(['persona', 'perfil_puesto', 'situacion_escalafonaria']);
		$empleado							= Empleado::obtener($evaluacion->id_empleado,true);
		$perfil								= $empleado->perfil_puesto;
		$formularios						= \App\Modelo\Evaluacion::getParam('formularios');
		$resultados							= \App\Modelo\Evaluacion::getParam('resultados');	
		$control							= unserialize(serialize($evaluacion));

		$evaluacion->acto_administrativo 	= !empty($this->request->post('acto')) ?  $this->request->post('acto'): $evaluacion->acto_administrativo;
		$evaluacion->evaluacion 			= !empty($this->request->post('resultados')) ?  (int)$this->request->post('resultados'): $evaluacion->evaluacion;
		$evaluacion->anio 					= !empty($this->request->post('anio')) ?  (int)$this->request->post('anio'): $evaluacion->anio;
		$evaluacion->formulario 			= !empty($this->request->post('formularios')) ?  (int)$this->request->post('formularios'): $evaluacion->formulario;
		$evaluacion->puntaje				= !empty($this->request->post('puntaje')) ?  (int)$this->request->post('puntaje'): $evaluacion->puntaje;
		$evaluacion->bonificado				= !empty($this->request->post('bonificado')) ?  (int)$this->request->post('bonificado') : (int)$evaluacion->bonificado;
		$archivo_anterior = $evaluacion->archivo;
		if($this->request->post('boton_evaluacion') == 'modificacion') {
			$evaluacion->id_perfil 				= empty($evaluacion->id_perfil) ? $perfil->id : $evaluacion->id_perfil;
			$evaluacion->acto_administrativo 	= !empty($this->request->post('acto')) ?  $this->request->post('acto'): $evaluacion->acto_administrativo;
			$evaluacion->archivo				= ($_FILES['evaluacion_file']['size'] == 0) ? $archivo_anterior : $_FILES['evaluacion_file'];
			$evaluacion->evaluacion 			= !empty($this->request->post('resultados')) ?  (int)$this->request->post('resultados'): $evaluacion->evaluacion;
			$evaluacion->anio 					= !empty($this->request->post('anio')) ?  (int)$this->request->post('anio'): $evaluacion->anio;
			$evaluacion->formulario 			= !empty($this->request->post('formularios')) ?  (int)$this->request->post('formularios'): $evaluacion->formulario;
			$evaluacion->fecha_evaluacion =   date('Y-m-d');
			$evaluacion->puntaje = !empty($this->request->post('puntaje')) ?  (int)$this->request->post('puntaje'): $evaluacion->puntaje;
			$evaluacion->bonificado = !empty($this->request->post('bonificado')) ?  (int)$this->request->post('bonificado'): 0;

			if($evaluacion->modificacion()){	
				if($control != $evaluacion) {
					$this->mensajeria->agregar(
					"la Evaluación del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue modificada exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$select_tab = 'tab_perfiles_puestos';
					$this->setGetVarSession('data_legajo', ['select_tab' => $select_tab]);
					$redirect =Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");
					$this->redirect($redirect);			
				}		
			}else {
				$err	= $evaluacion->errores;
				foreach ((array)$err as $text) {
					$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
				}
			}
		}		
		$vista = $this->vista;
		$vars = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit];
			$vista->add_to_var('vars',$vars);
		(new Vista($this->vista_default,compact('vista','empleado', 'evaluacion', 'formularios','resultados')))->pre_render();
	}

	protected function accion_mostrar_evaluacion(){
		$archivo = Modelo\Evaluacion::obtener_archivo($this->request->query('id'));
		$doc_content = preg_replace("/\d{14}_/", "", $archivo);
		$doc = BASE_PATH.'/uploads/evaluacion/'.$archivo ;
		header("Content-Disposition:inline;filename=".$doc_content."");
		header("Content-type: application/pdf;");		
		readfile($doc);
	}

	protected function accion_mostrar_foto_persona(){
		Modelo\Empleado::contiene(['persona'=>[]]);
		$empleado	= Modelo\Empleado::obtener($this->request->query('id'));
		$archivo	= empty($empleado->persona->foto_persona) ? '' : $empleado->persona->foto_persona;

		$doc		= BASE_PATH.'/uploads/foto_persona/'.$archivo ;
		if(!file_exists($doc)){
			$archivo	= ''; 
		}
		if(preg_match('/((jpg)|(jpeg)|(JPG)|(JPEG))$/', $archivo)){
			$image	= imagecreatefromjpeg($doc);
		} elseif(preg_match('/((png)|(PNG))$/', $archivo)){
			$image	= imagecreatefrompng($doc);
		} elseif(preg_match('/((bmp)|(BMP))$/', $archivo)){
			$image	= imagecreatefrombmp($doc);
		} else {
			$image	= imagecreatetruecolor(100, 100);
			$fondo	= imagecolorallocate($image, 255, 255, 255);
			$ct		= imagecolorallocate($image, 0, 0, 0);
			imagefilledrectangle($image, 0, 0, 100, 100, $fondo);
			imagestring($image, 1, 25, 40, 'Sin imagen', $ct);
		}

		header("Content-Type: image/jpg;");
		imagejpeg($image);
		imagedestroy($image);
		exit;
	}

	protected function accion_horas_extras() {
		$empleado	= Empleado::obtener($this->request->query('id'));
		if (!empty($empleado->id) && !empty($empleado->cuit)){
			$empleado->horas_extras->id = null;
			$empleado->horas_extras->id_empleado 	= $empleado->id;
			$temp = explode('/', $this->request->post('anio_mes'));
			$empleado->horas_extras->anio			= \FMT\Helper\Arr::get($temp,1);
			$empleado->horas_extras->mes 			= \FMT\Helper\Arr::get($temp,0);
			$empleado->horas_extras->acto_administrativo 			= ($temp = $this->request->post('acto_administrativo')) ?  $temp : null;
			if($this->request->post('boton_extra') == 'alta') {
				if($empleado->id) {
					if($empleado->validar()){
						$empleado->alta_hora_extra();
						$this->mensajeria->agregar(
						 	"Las Horas Extras del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fueron cargadas exitosamente.",
						 	\FMT\Mensajeria::TIPO_AVISO,
						 	$this->clase
						 );
						$select_tab = 'tab_administracion';
						$this->setGetVarSession('data_legajo', ['select_tab' => $select_tab]);
						$redirect =Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");
						$this->redirect($redirect);	
					
					}else {
						$err	= $empleado->errores;
						foreach ($err as $text) {
							$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
						}
					}
				}	 
			}
			$vista = $this->vista;
			$vars = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit];
				$vista->add_to_var('vars',$vars);
			(new Vista($this->vista_default,compact('vista','empleado')))->pre_render();
		}else{
			$this->mensajeria->agregar("PARA DEFINIR LAS <strong>HORAS EXTRAS</strong>, ES REQUISITO TENER LOS DATOS BÁSICOS DEL <strong>AGENTE</strong>.", \FMT\Mensajeria::TIPO_ERROR, $this->clase);
		}
	}

	protected function accion_update_presentacion() {
		$empleado	= Empleado::obtener($this->request->query('id'));

		$anticorrupcion = Anticorrupcion::obtener($empleado->id);
		$control = Clone $anticorrupcion;
		$tipo_presentacion = \App\Modelo\Anticorrupcion::getParam('TIPO_DJ');
		$anticorrupcion->tipo_presentacion  		= !empty($this->request->post('tipo_presentacion')) ?  (int)$this->request->post('tipo_presentacion'): $anticorrupcion->tipo_presentacion;
		$anticorrupcion->fecha_presentacion 		= !empty($temp = $this->request->post('fecha_presentacion')) ?  \DateTime::createFromFormat('d/m/Y', $temp) : $anticorrupcion->fecha_presentacion;
		$anticorrupcion->periodo 					= !empty($temp = $this->request->post('periodo')) ?  $temp : $anticorrupcion->periodo;
		$anticorrupcion->nro_transaccion 			= !empty($temp = $this->request->post('nro_transaccion')) ?  $temp : $anticorrupcion->nro_transaccion;
		
		if($this->request->post('boton_presentacion') == 'modificacion') {
				$anticorrupcion->id_empleado 					= $empleado->id;
				$anticorrupcion->archivo				= ($_FILES['anticorrupcion_file']['error'] == UPLOAD_ERR_OK) ? $_FILES['anticorrupcion_file'] : $anticorrupcion->archivo;
					if($anticorrupcion->validar()){	
						if($control != $anticorrupcion) {

							$anticorrupcion->modificacion_presentacion();
							$this->mensajeria->agregar(
						 	"la Presentación del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue modificada exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);

							$select_tab = 'tab_anticorrupcion';
							$this->setGetVarSession('data_legajo', ['select_tab' => $select_tab]);
							$redirect =Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");
	    					$this->redirect($redirect);			
						}		


				}else {
					$err	= $anticorrupcion->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
		}		
		$vista = $this->vista;
		$vars = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit];
			$vista->add_to_var('vars',$vars);
		(new Vista($this->vista_default,compact('vista','empleado', 'anticorrupcion', 'tipo_presentacion')))->pre_render();
	}

	protected function accion_update_hora_extra() {
		$control	= Empleado::obtener_hora_extra($this->request->query('id'));
		$empleado	= Empleado::obtener($control->id_empleado, true);
		if($empleado->horas_extras->id != $control->id){
			//Es el caso de la modificacion de una horas extra mayor a las horas extras del mes actual. 
			$empleado->horas_extras = clone $control;
		}
		if (!empty($empleado->id) && !empty($empleado->cuit)){
			$aux = explode('/', $this->request->post('anio_mes'));
			($temp=\FMT\Helper\Arr::get($aux,1)) ? $empleado->horas_extras->anio = $temp : ''; 
			($temp = \FMT\Helper\Arr::get($aux,0)) ? $empleado->horas_extras->mes = $temp : '';
			$empleado->horas_extras->acto_administrativo 	= ($temp = $this->request->post('acto_administrativo')) ?  $temp : $empleado->horas_extras->acto_administrativo;
			if($this->request->post('boton_extra') == 'modificacion') {
				 if ($control->anio !== $empleado->horas_extras->anio || $control->mes !== $empleado->horas_extras->mes || $control->acto_administrativo !== $empleado->horas_extras->acto_administrativo) {
					if($empleado->validar()){	
							$empleado->modificacion_hora_extra();
							$this->mensajeria->agregar(
						 	"El registro de Horas Extras del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);

							$select_tab = 'tab_administracion';
							$this->setGetVarSession('data_legajo', ['select_tab' => $select_tab]);
							$redirect =Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");
							$this->redirect($redirect);			
						}else {
							$err	= $empleado->errores;
							foreach ($err as $text) {
								$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
							}
						}

				 } else {
					$this->mensajeria->agregar(
					"El registro de Horas Extras del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> no tiene modificaciones para guardar.",\FMT\Mensajeria::TIPO_ERROR,$this->clase);
				 }
						
			}	
		$vista = $this->vista;
		$vars = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit];
			$vista->add_to_var('vars',$vars);
		(new Vista($this->vista_default,compact('vista','empleado')))->pre_render();
		}else{
			$this->mensajeria->agregar("PARA DEFINIR LAS <strong>HORAS EXTRAS</strong>, ES REQUISITO TENER LOS DATOS BÁSICOS DEL <strong>AGENTE</strong>.", \FMT\Mensajeria::TIPO_ERROR, $this->clase);
		}
	}

	protected function accion_baja_hora_extra() {
		$control = Empleado::obtener_hora_extra($this->request->query('id'));
		$empleado	= Empleado::obtener($control->id_empleado, true);
		if (!empty($empleado->id) && !empty($empleado->cuit)){
			if($empleado->id) {
				$select_tab = 'tab_administracion';
				$this->setGetVarSession('data_legajo', ['select_tab' => $select_tab]);
				if ($this->request->post('confirmar')) {
					$res = $empleado->baja_hora_extra($control->id);
					if ($res) {
						$this->mensajeria->agregar('AVISO: las Horas extras se eliminaron de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					}
					$redirect =Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");
					$this->redirect($redirect);
			}
			$vista = $this->vista;
			$vars = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit];
				$vista->add_to_var('vars',$vars);
			(new Vista($this->vista_default,compact('vista','empleado', 'control')))->pre_render();
			}
		}
	}


	public function accion_alta_familiar() {
		$empleado = Empleado::obtener($this->request->query('id'));
		$param = $this->datos_parametricos();
		$parametricos = [
			'parentesco' 		=> $param['parentesco'],
			'tipo_documento'	=> $param['tipo_documento'],
			'nacionalidad'		=> $param['nacionalidad'],
			'nivel_educativo'   => $param['formacion_tipo_titulo'],
			'opcion_sino'		=> $param['opcion_sino'],
			'tipo_discapacidad' => $param['tipo_discapacidad'],
			'porcentaje_desgrava' => $param['porcentaje_desgrava'],
		];
		
		$familiar = \App\Modelo\GrupoFamiliar::obtener();
		if($this->request->post('tipo_form') == 'alta_familiar'){
			$familiar->id_empleado   	   = $empleado->id;
			$familiar->parentesco 		   = $this->request->post('parentesco');
			$familiar->nombre 			   = $this->request->post('nombre');
			$familiar->apellido 		   = $this->request->post('apellido');
			$familiar->fecha_nacimiento    = \DateTime::createFromFormat('d/m/Y H:i:s', $this->request->post('fecha_nacimiento').'0:00:00');
			$familiar->nacionalidad 	   = $this->request->post('nacionalidad');
			$familiar->tipo_documento 	   = $this->request->post('tipo_documento');
			$familiar->documento 		   = $this->request->post('documento');
			$familiar->nivel_educativo 	   = $this->request->post('nivel_educativo');
			$familiar->desgrava_afip 	   = $this->request->post('desgrava_afip');
			$familiar->fecha_desde 	   	   = \DateTime::createFromFormat('d/m/Y H:i:s', $this->request->post('fecha_desde').'0:00:00');
			$familiar->fecha_hasta 	       = \DateTime::createFromFormat('d/m/Y H:i:s', $this->request->post('fecha_hasta').'0:00:00');
			$familiar->reintegro_guarderia = $this->request->post('reintegro_guarderia');
			$familiar->discapacidad 	   = $this->request->post('discapacidad');

			if(!empty($this->request->post('discapacidad')) && !empty($this->request->post('tipo_discapacidad'))){
				$familiar->fam_discapacidad->id_tipo_discapacidad 	= $this->request->post('tipo_discapacidad');
				$familiar->fam_discapacidad->cud 				  	= $this->request->post('cud');
				$familiar->fam_discapacidad->fecha_alta				= \DateTime::createFromFormat('d/m/Y H:i:s', $this->request->post('fecha_alta_discapacidad').'0:00:00');
				$familiar->fam_discapacidad->fecha_vencimiento    	= \DateTime::createFromFormat('d/m/Y H:i:s', $this->request->post('fecha_vencimiento').'0:00:00');
			}

			if(!empty($empleado->id) && !empty($empleado->cuit)){
				if($familiar->validar()){ 
					$res = $familiar->alta();
					if($res){
						if(!empty($familiar->fam_discapacidad->id_tipo_discapacidad) && !empty($familiar->discapacidad)){
					      	$familiar->fam_discapacidad->id_familiar = $res;
					      	$res = $familiar->alta_discapacidad();
					    }
					}
					if($res){
						$this->mensajeria->agregar("El familiar fue cargado exitosamente.", \FMT\Mensajeria::TIPO_AVISO, $this->clase);
					}else{
						$this->mensajeria->agregar("Ha ocurrido un error en el registro del familiar.", \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
					$select_tab = 'tab_grupo_familiar';
					$this->setGetVarSession('data_legajo', ['select_tab' => $select_tab]);
					$redirect =Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");
					$this->redirect($redirect);	
				}else{
					$err = array_merge((array)$familiar->errores);
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}

			}
		}

		$permisos['bloque_grupo_familiar'] = [
			'parentesco'				=> AppRoles::puede_atributo('grupo_familiar','alta','campos','parentesco'),
			'nombre'					=> AppRoles::puede_atributo('grupo_familiar','alta','campos','nombre'),
			'apellido'					=> AppRoles::puede_atributo('grupo_familiar','alta','campos','apellido'),
			'fecha_nacimiento'			=> AppRoles::puede_atributo('grupo_familiar','alta','campos','fecha_nacimiento'),
			'tipo_documento'			=> AppRoles::puede_atributo('grupo_familiar','alta','campos','tipo_documento'),
			'documento'					=> AppRoles::puede_atributo('grupo_familiar','alta','campos','documento'),
			'nacionalidad'				=> AppRoles::puede_atributo('grupo_familiar','alta','campos','nacionalidad'),
			'nivel_educativo'			=> AppRoles::puede_atributo('grupo_familiar','alta','campos','nivel_educativo'),
			'desgrava_afip'				=> AppRoles::puede_atributo('grupo_familiar','alta','campos','desgrava_afip'),
			'fecha_desde'				=> AppRoles::puede_atributo('grupo_familiar','alta','campos','fecha_desde'),
			'fecha_hasta'				=> AppRoles::puede_atributo('grupo_familiar','alta','campos','fecha_hasta'),
			'reintegro_guarderia'		=> AppRoles::puede_atributo('grupo_familiar','alta','campos','reintegro_guarderia'),
			'discapacidad'				=> AppRoles::puede_atributo('grupo_familiar','alta','campos','discapacidad'),
			'tipo_discapacidad'			=> AppRoles::puede_atributo('grupo_familiar','alta','campos','tipo_discapacidad'),
			'cud'						=> AppRoles::puede_atributo('grupo_familiar','alta','campos','cud'),
			'fecha_alta_discapacidad'	=> AppRoles::puede_atributo('grupo_familiar','alta','campos','fecha_alta_discapacidad'),
			'fecha_vencimiento'			=> AppRoles::puede_atributo('grupo_familiar','alta','campos','fecha_vencimiento'),
		];

		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'parametricos', 'familiar', 'empleado', 'permisos')))->pre_render();
	}

	public function accion_exportacion(){
		$filtros	= [
			'dependencia'			=> $this->request->post('dependencia'),
			'estado'				=> $this->request->post('estado'),
			'modalidad_contratacion'=> $this->request->post('modalidad_contratacion'),
			'situacion_revista'		=> $this->request->post('situacion_revista'),
		];

		$incluir_estados = [
			Modelo\Empleado::EMPLEADO_INACTIVO	=> Modelo\Empleado::EMPLEADO_INACTIVO,
			Modelo\Empleado::EMPLEADO_ACTIVO	=> Modelo\Empleado::EMPLEADO_ACTIVO,
		];

		$parametricos = [
			'select_dependencia' 	=> Dependencia::listar(true),
			'situacion_revista'		=> Modelo\Contrato::obtenerVinculacionRevista($filtros['modalidad_contratacion'])['situacion_revista'],
			'modalidad_contratacion'=> Contrato::listadoModalidadVinculacion(),
			'estado' 				=> array_intersect_key(Modelo\Empleado::getParam('TIPO_ESTADOS_EMPLEADOS'), $incluir_estados),
		];


		$campos_datos_personales = 
		[
			'ICONO' 	=> 'fa-user',
			'BLOCK'	=> 'Datos Personales',
			'CAMPO'		=> [
				//'id' 						=> "ID",
				'cuit' 						=> "CUIT",
				'nombre' 					=> "NOMBRE",
				'apellido' 					=> "APELLIDO",
				'email'						=> "EMAIL",
				'tipo_documento'			=> "TIPO DOCUMENTO",
				'documento'					=> "DOCUMENTO",
				'nacionalidad'				=> "NACIONALIDAD",
				'fecha_nac'					=> "FECHA NACIMIENTO",
				'genero'					=> "GENERO",
				'estado_civil'				=> "ESTADO CIVIL",
				'telefono'					=> "TELEFONO",
				'cod_postal'				=> "COD POSTAL",
				'calle'						=> "CALLE",
				'numero'					=> "NUMERO",
				'piso'						=> "PISO",
				'depto'						=> "DEPTO",
				'localidad'					=> "LOCALIDAD",
				'provincia'					=> "PROVINCIA",
			]
		];
		$campos_escalafonario =
		[
			'ICONO' 	=> 'fa-cubes',
			'BLOCK'	=> 'Situación Escalafonaria',
			'CAMPO'		=> [
				'modalidad_vinculacion' 	=> "MODALIDAD VINCULACION",
				'situacion_revista' 		=> "SITUACION REVISTA",
				'funcion_ejecutiva'			=> "FUNCION EJECUTIVA",
				'agrupamiento' 				=> "AGRUPAMIENTO",
				'nivel_funcion'				=> "NIVEL FUNCION",
				'compensacion_geografica'	=> "COMPENSACION GEOGRAFICA",
				'tramo'						=> "TRAMO",
				'grado' 					=> "GRADO",
				'grado_liquidacion' 		=> "GRADO DE LIQUIDACION",
				'compensacion_transitoria'	=> "COMPENSACION TRANSITORIA",
				'exc_art_14'				=> "EXC.ART.14",
				'opc_art_14'				=> "OPCIONES ART.14",
				'delegado_gremial'			=> 'DELEGADO GREMIAL',
				'fecha_vigencia_mandato'	=> 'FECHA VIGENCIA MANDATO',
				'sindicato'					=> 'SINDICATO',
			]
		];
		$campos_estructura = 
		[
			'ICONO' 	=> 'fa-sitemap',
			'BLOCK'	=> 'Ubicación en la Estructura',
			'CAMPO'		=> [
				'ministro'					=> 'MINISTRO',
				'secretaria'				=> 'SECRETARIA',
	            'subsecretaria'				=> 'SUBSECRETARIA',
	            'direccion_general'			=> 'DIRECCION GENERAL',
	            'direccion_simple'			=> 'DIRECCION SIMPLE',
	            'coordinacion' 				=> 'COORDINACION',
	            'unidad_o_area'				=> 'UNIDAD O AREA',		
				'dependencia_informal'		=> "DEPENDENCIA INFORMAL",
	   		],	
		];
		$campos_perfiles_puesto =
		[
			'ICONO' 	=> 'fa-product-hunt',
			'BLOCK'	=> 'Perfil de Puesto',
			'CAMPO'		=> [
				'familia_puestos'			=> "FAMILIA PUESTOS",
				'nombre_puesto'				=> "NOMBRE PUESTO",
				'nivel_destreza'			=> "NIVEL DESTREZA",
				'puesto_supervisa'			=> "PUESTO SUPERVISA",
				'denominacion_funcion'		=> "DENOMINACION FUNCION",
				'denominacion_puesto'		=> "DENOMINACION PUESTO",
				'objetivo_general'			=> "OBJETIVO GENERAL",
				'objetivo_especificos'		=> "OBJETIVOS ESPECIFICOS",
				'estandares'				=> "ESTANDARES CUANTITATIVOS/CUALITATIVOS",
				'nivel_complejidad'			=> "NIVEL COMPLEJIDAD",
				'actividades'				=> "ACTIVIDADES/TAREAS",
				'fecha_obtencion_result'	=> "FECHA OBT. RESULTADO",
				'resultados_finales'		=> "RESULTADOS PARCIALES FINALES",
				'evaluacion_anio'			=> 'AÑO DE EVALUACIÓN',
				'evaluacion_formulario'		=> 'FORMULARIO DE EVALUACIÓN',
				'evaluacion_resultado'		=> 'RESULTADO DE EVALUACIÓN',
				'evaluacion_puntaje'		=> 'PUNTAJE DE EVALUACIÓN',
				'evaluacion_bonificado'		=> 'BONIFICACIÓN',
				'evaluacion_acto'			=> 'ACTO ADMINISTRATIVO',
			]
		];
		$campos_formacion = 
		[
			'ICONO' 	=> 'fa fa-university',
			'BLOCK'	=> 'Formación',
			'CAMPO'		=> [
				'nivel_educativo'			=> "NIVEL EDUCATIVO",
				'estado_titulo'				=> "ESTADO TITULO",
				'nombre_titulo'				=> "NOMBRE TITULO",
				'fecha_otorgamiento'		=> "FECHA OTORGAMIENTO TITULO",
				'titulos_adicionales'		=> "TITULOS ADICIONALES",
				'otros_estudios'			=> "OTROS ESTUDIOS",
				'otros_conocimientos'		=> "CONOCIMIENTOS EN SOFTWARE",
				'cursos'					=> "CURSOS",
			],	
		];
		$campos_antiguedad =
		[
			'ICONO' 	=> 'fa-hourglass',
			'BLOCK'	=> 'Antigüedad',
			'CAMPO'		=> [
				'antiguedad_adm_publica'	=> "ANTIGUEDAD ADM PUBLICA",
				'antiguedad_otros_organismos'=> "ANTIGUEDAD OTROS ORGANISMOS",
				'experiencia_laboral'		=> 'EXPERIENCIA LABORAL',
				'fecha_ingreso_mtr'			=> "FECHA INGRESO MTR",
				'fecha_otorgamiento_grado'  => "FECHA OTORGAMIENTO GRADO",
			]
		];
		$campos_adm =
		[
			'ICONO' 	=> 'fa-building',
			'BLOCK'	=> 'Administración',
			'CAMPO'		=> [
				'horarios'					=> "HORARIOS",
				'turno'						=> "TURNO",
				'piso_oficina'				=> "PISO OFICINA",
				'num_oficina'				=> "OFICINA",
				'calle_edificio'		=> "CALLE UBICACIÓN",
				'numero_edificio'		=> "NUMERO UBICACIÓN",
				'localidad_edificio'		=> "LOCALIDAD UBICACIÓN",
				'provincia_edificio'		=> "PROVINCIA UBICACIÓN",
				'licencias_especiales'		=> "LICENCIAS ESPECIALES",
				'fecha_inicio_lic_esp'		=> "FECHA INICIO LIC. ESPECIALES",
				'fecha_fin_lic_especiales'  => "FECHA FIN LIC. ESPECIALES",
				'estado'					=> 'ESTADO',
				'fecha_baja'				=> "FECHA BAJA",
				'motivo_baja'				=> "MOTIVO BAJA",
				'en_comision'				=> "EN COMISIÓN",
				'destino_comision'			=> "DESTINO COMISIÓN",
				'origen_comision'			=> "ORIGEN COMISIÓN",
				'horas_extras' 				=> 'HORAS EXTRAS',
			]	
		];
		$campos_varios =
		[
			'ICONO' 	=> 'fa-child',
			'BLOCK'	=> 'Varios',
			'CAMPO'		=> [
				'credencial_acceso'			=> "CREDENCIAL ACCESO",
				'fecha_ven_credencial'		=> "FECHA VENCIMIENTO CREDENCIAL",
				'tipo_discapacidad'			=> "TIPO DISCAPACIDAD",
				'cud'						=> "CUD",
				'fecha_ven_cud'				=> "FECHA VENCIMIENTO CUD",
				'obs_cud'					=> "OSBERVACIONES CUD",
				'sindicatos_varios'			=> 'SINDICATOS',
				'obra_social'				=> 'OBRA SOCIAL',
				'seguro_vida'				=> 'SEGURO DE VIDA',
				'veterano_guerra'			=> 'VETERANO GUERRA',
			]	
		];

		$campos_presupuesto =
		[
			'ICONO' 	=> 'fa-bar-chart',
			'BLOCK'	=> 'Presupuesto',
			'CAMPO'		=> [
				'saf'						=> "SERVICIO ADMINISTRATIVO FINANCIERO",
				'jurisdiccion'				=> "JURISDICCION",
				'ubicacion_geografica'		=> "UBICACION GEOGRAFICA",
				'programa'					=> "PROGRAMA",
				'subprograma'				=> "SUBPROGRAMA",
				'proyecto'					=> "PROYECTO",
				'actividad'					=> "ACTIVIDAD",
				'obra'						=> "OBRA"
			]	
		];

		$campos_anticorrupcion	=
		[
			'ICONO' 	=> 'fa-ban',
			'BLOCK'	=> 'Anticorrupción',
			'CAMPO'		=> [
				'obligado_prensentar_declaracion'	=> 'PRESENTA DECLARACION JURADA',
				'fecha_designacion'					=> 'FECHA DESIGNACION',
				'fecha_publicacion_designacion'		=> 'FECHA PUBLICACION DESIGNACION',
				'fecha_aceptacion_renuncia'			=> 'FECHA ACEPTACION RENUNCIA',
				'tipo_presentacion'					=> 'TIPO DECLARACION JURADA',
				'fecha_presentacion'				=> 'FECHA ULTIMA PRESENTACION',
				'periodo'							=> 'PERIODO PRESENTADO',
				'nro_transaccion'					=> 'Nº TRANSACCIÓN',
			]
		];
		$campos_designacion_transitoria	=
		[
			'ICONO' 	=> 'fa-ban',
			'BLOCK'	=> 'Designación Transitoria',
			'CAMPO'		=> [
				'fecha_desde'	=> 'FECHA PUBLICACION DESIGNACION TRANSITORIA',
				'fecha_hasta'	=> 'FECHA VENCIMIENTO DESIGNACION TRANSITORA',
				'tipo'			=> 'TIPO DESIGNACION TRANSITORIA',
			]
		];
		$campos_grupo_familiar	=
		[
			'ICONO' 	=> 'fa-users',
			'BLOCK'	=> 'Grupo Familiar',
			'CAMPO'		=> [
				'familiares'				=> 'FAMILIARES',
			]
		];
		$campos_embargo			=
		[
			'ICONO' 	=> 'fa-gavel',
			'BLOCK'	=> 'Embargos',
			'CAMPO'		=> [
				'embargos'					=> 'EMBARGOS',
			]
		];

		$parametros		= [];
		$parametros[] = $campos_datos_personales;
		$parametros[] = $campos_escalafonario;
		$parametros[] = $campos_estructura;
		$parametros[] = $campos_perfiles_puesto;
		$parametros[] = $campos_formacion;
		$parametros[] = $campos_antiguedad;
		$parametros[] = $campos_adm;
		$parametros[] = $campos_varios;
		$parametros[] = $campos_presupuesto;
		$parametros[] = $campos_anticorrupcion;
		$parametros[] = $campos_designacion_transitoria;
		$parametros[] = $campos_grupo_familiar;
		$parametros[] = $campos_embargo;

		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista','filtros','parametros','parametricos')))->pre_render();
	}

	public function accion_exportar_anticorrupcion_pdf(){

		$campo = $this->request->post('campo');
		$dir   = $this->request->post('dir');
		$search	 = $this->request->post('search');
		$periodo = $this->request->post('periodo');
		$tipo_dj = $this->request->post('tipo_dj');
		$total_rows = $this->request->post('rows');
		$vista = $this->vista;
		if ($total_rows > 200) {
			$this->mensajeria->agregar(
					"El proceso de generación del documento demorará unos minutos, cuando finalice, recibirá un email con el link al archivo que solicitó.",'NOTA',$this->clase);

		}
		(new Vista($this->vista_default,compact('vista', 'campo','dir','search','periodo','tipo_dj', 'total_rows')))->pre_render();
	}

/**
 * -----------------------------------------------------------------------------------------------------
 * -- CUALQUIER MODIFICACION QUE SE HAGA ACA, DEBE SER REPLICADA EN Cron::accion_anticorrupcion_pdf() --
 * -----------------------------------------------------------------------------------------------------
 */
	protected function accion_anticorrupcion_pdf() {
		$periodo =  !empty($this->request->post('periodo')) ? explode(',', $this->request->post('periodo')) : '';
		$tipo_dj =  !empty($this->request->post('tipo_dj')) ? explode(',', $this->request->post('tipo_dj')) : '';
		$total_rows = $this->request->post('total_rows');
		$params	= [
			'order' => [!empty($this->request->post('campo')) ? ['campo'=> $this->request->post('campo'), 'dir' => $this->request->post('dir')] : ''],
			'search'	=> !empty($tmp = $this->request->post('search')) ? $tmp : '',
			'start'		=> '',
			'lenght'	=> '',
			'filtros'	=> [
				'periodo'	=> $periodo,
				'tipo_dj'	=> $tipo_dj,
			],
		];

		$resultado	= Anticorrupcion::exportar($params);
		if ($this->request->post('exportar_pdf')) {
			$fecha	= \DateTime::createFromFormat('d/m/Y', gmdate('d/m/Y'))->format('d/m/Y');
			$logueado = Usuario::obtenerUsuarioLogueado();
			$usuario = $logueado->nombre.' '.$logueado->apellido;
			$titulo = !empty($this->request->post('titulo')) ? $this->request->post('titulo') : 'Informe de Historial Anticorrupción';

			if(!empty($resultado)) {
				if($total_rows <= 200){
					$modo_asincrono	= false;
					$file_nombre	= 'Informe-Historico-Anticorrupcion-'.date('d-m-Y-H:i:s').'.pdf';
					(new Vista(VISTAS_PATH.'/generar_pdf.php' ,compact('resultado', 'titulo', 'fecha', 'usuario', 'modo_asincrono','file_nombre')))->pre_render();
				}else{
					//Aqui va la cola de tareas para generar el pdf mayor a 200 registros, que queda pendiente para la proxima tarea
					$data	= [
						'params'	=> $params,
						'titulo'	=> $titulo,
					];
					if(Consola\Modelo\ColaTarea::agregar('anticorrupcion_pdf', $data)){
						$this->mensajeria->agregar('Será notificado por email cuando el documento este disponible.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					} else {
						$this->mensajeria->agregar('El documento está siendo procesado, será notificado por email cuando esté disponible.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					}
					$this->redirect(Vista::get_url("index.php/legajos/historial_anticorrupcion"));
				}
			} else {
				$this->mensajeria->agregar(
					"No se encontraron datos.",\FMT\Mensajeria::TIPO_ERROR,$this->clase);
			}
		}
	}

	protected function accion_exportar() {
		$parametros = $this->request->post('campos_exportar');
		$extras['dependencia'] = [];		
		
		foreach ($this->request->post('id_dependencia') as $key => $value) {
			if(!empty($value)) {
				if (\FMT\Helper\Arr::get($this->request->post('nivel'),$key)) {	
					if(\FMT\Helper\Arr::get($this->request->post('dependencias'),$key)) {
						$extras['dependencia'] = array_merge($extras['dependencia'], $this->request->post('dependencias')[$key]);
					} else {
						$depnvl = Dependencia::obtener_dependencias_niveles($value,$this->request->post('nivel')[$key]);
						$extras['dependencia'] = array_merge($extras['dependencia'],  array_column($depnvl['dependencias'],'id'));
					}
				} else {
					$extras['dependencia'] = array_merge($extras['dependencia'], [$value]);
				}
			}
		}

		$extras	+= [
			'estado'				=> !empty($this->request->post('estado')) ? (int) $this->request->post('estado') : 1,
			'modalidad_contratacion'=> !empty($this->request->post('modalidad_contratacion')) ? (int) $this->request->post('modalidad_contratacion') : AppRoles::obtener_modalidades_vinculacion_autorizadas(),
			'situacion_revista'		=> !empty($this->request->post('situacion_revista')) ? (int) $this->request->post('situacion_revista') : '',
		];
		$parametros['dependencia'] = 'dependencia';
		
		$filename	= date("Ymd_His") . "_reporte_de_agentes";

		$data	= [
			'params'	=> $parametros,
			'titulo'	=> $filename,
			'extras'	=> $extras,
		];
		if(Consola\Modelo\ColaTarea::agregar('exportar_legajos_excel', $data)){
			$this->mensajeria->agregar('Será notificado por email cuando el documento este disponible.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
		} else {
			$this->mensajeria->agregar('El documento está siendo procesado, será notificado por email cuando esté disponible.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
		}
		$this->redirect(Vista::get_url("index.php/legajos/exportacion"));
	}

	protected function accion_exportar_anticorrupcion() {

		$params	= [
			'order' => [!empty($this->request->post('campo')) ? ['campo'=> $this->request->post('campo'), 'dir' => $this->request->post('dir')] : ''],
			'search'	=> !empty($tmp = $this->request->post('search')) ? $tmp : '',
			'start'		=> '',
			'lenght'	=> '',
			'filtros'	=> [
				'periodo'	=> $this->request->post('periodo'),
				'tipo_dj'	=> $this->request->post('tipo_dj')
			],
		];
		$resultado	= Anticorrupcion::exportar($params);

		foreach ($resultado[0] as $value => $key) {
				if(!in_array($value,['id','id_empleado','tipo_presentacion']))
					$titulos[] = ucwords(str_replace(['_','dj'], [' ','tipo'], $value));
		}

		$filename	= date("Ymd_His") . "_reporte_de_anticorrupcion.xlsx";
		$writer 	= WriterFactory::create(Type::XLSX);
		$writer->openToBrowser($filename);
		$titles 	= array_values($titulos);
		$sheet		= $writer->getCurrentSheet();
		$sheet->setName('Reporte');
		$style		= (new StyleBuilder())->setFontBold()->build();
		$writer->addRowWithStyle($titles, $style);
		$contador	= count($resultado);
		$offset		= 5000; //limite de paginado
		for ($i = 0; $i < $contador; $i += $offset) {
			foreach ($resultado as $row) {
				unset($row['id'],$row['id_empleado'], $row['tipo_presentacion']);
				$writer->addRow($row);
			}
		}
		$writer->addRow(["{$contador} registros totales."]);
		$writer->close();
		exit;
	}


	public function accion_modificar_familiar(){
		$param = $this->datos_parametricos();
		$parametricos = [
			'parentesco' 		  => $param['parentesco'],
			'tipo_documento'	  => $param['tipo_documento'],
			'nacionalidad'		  => $param['nacionalidad'],
			'nivel_educativo'     => $param['formacion_tipo_titulo'],
			'opcion_sino'		  => $param['opcion_sino'],
			'tipo_discapacidad'   => $param['tipo_discapacidad'],
			'porcentaje_desgrava' => $param['porcentaje_desgrava'],
		];

		$familiar = \App\Modelo\GrupoFamiliar::obtener($this->request->query('id'));
		$empleado	= Empleado::obtener($familiar->id_empleado, true);
		$permisos['bloque_grupo_familiar'] = [
			'parentesco'				=> AppRoles::puede_atributo('grupo_familiar','alta','campos','parentesco'),
			'nombre'					=> AppRoles::puede_atributo('grupo_familiar','alta','campos','nombre'),
			'apellido'					=> AppRoles::puede_atributo('grupo_familiar','alta','campos','apellido'),
			'fecha_nacimiento'			=> AppRoles::puede_atributo('grupo_familiar','alta','campos','fecha_nacimiento'),
			'tipo_documento'			=> AppRoles::puede_atributo('grupo_familiar','alta','campos','tipo_documento'),
			'documento'					=> AppRoles::puede_atributo('grupo_familiar','alta','campos','documento'),
			'nacionalidad'				=> AppRoles::puede_atributo('grupo_familiar','alta','campos','nacionalidad'),
			'nivel_educativo'			=> AppRoles::puede_atributo('grupo_familiar','alta','campos','nivel_educativo'),
			'desgrava_afip'				=> AppRoles::puede_atributo('grupo_familiar','alta','campos','desgrava_afip'),
			'fecha_desde'				=> AppRoles::puede_atributo('grupo_familiar','alta','campos','fecha_desde'),
			'fecha_hasta'				=> AppRoles::puede_atributo('grupo_familiar','alta','campos','fecha_hasta'),
			'reintegro_guarderia'		=> AppRoles::puede_atributo('grupo_familiar','alta','campos','reintegro_guarderia'),
			'discapacidad'				=> AppRoles::puede_atributo('grupo_familiar','alta','campos','discapacidad'),
			'tipo_discapacidad'			=> AppRoles::puede_atributo('grupo_familiar','alta','campos','tipo_discapacidad'),
			'cud'						=> AppRoles::puede_atributo('grupo_familiar','alta','campos','cud'),
			'fecha_alta_discapacidad'	=> AppRoles::puede_atributo('grupo_familiar','alta','campos','fecha_alta_discapacidad'),
			'fecha_vencimiento'			=> AppRoles::puede_atributo('grupo_familiar','alta','campos','fecha_vencimiento'),
		];

		if($this->request->post('tipo_form') == 'modificar_familiar'){	
			$familiar->parentesco 		   = $this->request->post('parentesco');
			$familiar->nombre 			   = $this->request->post('nombre');
			$familiar->apellido 		   = $this->request->post('apellido');
			$familiar->fecha_nacimiento    = \DateTime::createFromFormat('d/m/Y H:i:s', $this->request->post('fecha_nacimiento').'0:00:00');
			$familiar->nacionalidad 	   = $this->request->post('nacionalidad');
			$familiar->tipo_documento 	   = $this->request->post('tipo_documento');
			$familiar->documento 		   = $this->request->post('documento');
			$familiar->nivel_educativo 	   = $this->request->post('nivel_educativo');
			$familiar->desgrava_afip 	   = $this->request->post('desgrava_afip');
			$familiar->fecha_desde 	   	   = ($tmp = \DateTime::createFromFormat('d/m/Y H:i:s', $this->request->post('fecha_desde').'0:00:00')) ? $tmp : null;
			$familiar->fecha_hasta 	       = ($tmp = \DateTime::createFromFormat('d/m/Y H:i:s', $this->request->post('fecha_hasta').'0:00:00')) ? $tmp : null;
			$familiar->reintegro_guarderia = $this->request->post('reintegro_guarderia');
			$familiar->discapacidad 	   = $this->request->post('discapacidad');

			if(!empty($this->request->post('discapacidad')) && !empty($this->request->post('tipo_discapacidad'))){
				$familiar->fam_discapacidad->id_familiar			= $familiar->id;
				$familiar->fam_discapacidad->id_tipo_discapacidad 	= $this->request->post('tipo_discapacidad');
				$familiar->fam_discapacidad->cud 				  	= $this->request->post('cud');
				$familiar->fam_discapacidad->fecha_alta				= \DateTime::createFromFormat('d/m/Y H:i:s', $this->request->post('fecha_alta_discapacidad').'0:00:00');
				$familiar->fam_discapacidad->fecha_vencimiento    	= \DateTime::createFromFormat('d/m/Y H:i:s', $this->request->post('fecha_vencimiento').'0:00:00');
			}


			$res_dis = true;
			if($familiar->id){
				if($familiar->validar()){
					$res = $familiar->modificacion();
					if($this->request->post('dis_familiar_accion') == 'modificacion'){
						if($familiar->fam_discapacidad->id_tipo_discapacidad){
							$res_dis = $familiar->modificacion_discapacidad();
						}
					}
					if($this->request->post('dis_familiar_accion') == 'alta'){
						if($familiar->fam_discapacidad->id){
							$res_dis = $familiar->baja_discapacidad();
						}
						$res_dis = $familiar->alta_discapacidad();
					}
					if(!$res_dis){
						if(!$res_dis){
							$this->mensajeria->agregar("Ha ocurrido un error al modificar los datos de discapacidad del familiar.", \FMT\Mensajeria::TIPO_ERROR, $this->clase);
						}
					}else{
						$this->mensajeria->agregar("Los datos del familiar han sido modificados exitosamente.", \FMT\Mensajeria::TIPO_AVISO, $this->clase);
						$select_tab = 'tab_grupo_familiar';
						$this->setGetVarSession('data_legajo', ['select_tab' => $select_tab]);
						$redirect =Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");
						$this->redirect($redirect);							
					}
				}else{
					$err = array_merge((array)$familiar->errores);
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		}
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'parametricos', 'familiar', 'empleado', 'permisos')))->pre_render();
	}

	public function accion_baja_familiar(){
		$familiar = \App\Modelo\GrupoFamiliar::obtener($this->request->query('id'));
		$empleado	= Empleado::obtener($familiar->id_empleado, true);
		if (!empty($empleado->id) && !empty($empleado->cuit)){
			if($this->request->post('confirmar')) {
				if($familiar->id) {
					$res = $familiar->baja();
					if ($res) {
						$this->mensajeria->agregar('El familiar se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);

					} else {
						$this->mensajeria->agregar('No es posible eliminar el familiar.',\FMT\Mensajeria::TIPO_ERROR,$this->clase);
					}
					$select_tab = 'tab_grupo_familiar';
					$this->setGetVarSession('data_legajo', ['select_tab' => $select_tab]);
					$redirect =Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");
					$this->redirect($redirect);
				}
			} else {
				$vista = $this->vista;
				(new Vista($this->vista_default,compact('vista', 'familiar', 'empleado')))->pre_render();				
			}	
		}
	}


	protected function accion_historial_titulo_creditos() {
		$persona_titulo	= Modelo\PersonaTitulo::obtener($this->request->query('id'));
		$titulo_completo= Modelo\TituloCredito::tituloCompleto($persona_titulo->id);
		$historial		= Modelo\TituloCredito::listar_historial_creditos($persona_titulo->id);
		Modelo\Empleado::contiene(['perfil_puesto','persona']);
		if(!empty($historial)){
			$empleado = Modelo\Empleado::obtener($historial[0]['id_empleado'], true);
		}else{
			$empleado_id 	= Modelo\Persona::getEmpleado($persona_titulo->id_persona);
			$empleado		= Modelo\Empleado::obtener($empleado_id, true);
		}
		$vista = $this->vista;
		$nombre_de_puesto = \App\Modelo\Perfil::listarNombrePuestos();
		$puesto = \FMT\Helper\Arr::path($nombre_de_puesto,"{$empleado->perfil_puesto->nombre_puesto}.nombre",'-');
		(new Vista($this->vista_default,compact('vista', 'historial', 'puesto', 'empleado', 'persona_titulo', 'titulo_completo')))->pre_render();
	}

	protected function accion_alta_titulo_creditos() {
		$persona_titulo = Modelo\PersonaTitulo::obtener($this->request->query('id'));
		$id_empleado	= Modelo\Persona::getEmpleado($persona_titulo->id_persona);
		Modelo\Empleado::contiene(['persona', 'perfil_puesto']);
		$empleado		= Modelo\Empleado::obtener($id_empleado, true);
		$titulo_creditos= Modelo\TituloCredito::obtener();
		if (!empty($empleado->id) && !empty($empleado->cuit)){
			$titulo_creditos->id_persona_titulo		= $persona_titulo->id;
			$titulo_creditos->id_persona			= $empleado->persona->id;
			$titulo_creditos->fecha 				= ($temp = $this->request->post('fecha')) ?  \DateTime::createFromFormat('d/m/Y', $temp) : null;
			$titulo_creditos->acto_administrativo	= ($temp = $this->request->post('acto_administrativo')) ?  $temp : null;
			$titulo_creditos->creditos 				=  ($temp = $this->request->post('creditos')) ?  $temp : null;
			$titulo_creditos->archivo				=   null;
			$titulo_creditos->estado_titulo		= $persona_titulo->id_estado_titulo;
			if($this->request->post('boton_titulo_creditos') == 'alta') {
				$titulo_creditos->archivo				= ($_FILES['titulo_credito_file']['error'] == UPLOAD_ERR_OK) ? $_FILES['titulo_credito_file'] : null;
				if($titulo_creditos->validar()){
					$alta = $titulo_creditos->alta();
					if($alta){
							$this->mensajeria->agregar(
						"El credito al titulo del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue cargado exitosamente.",
						\FMT\Mensajeria::TIPO_AVISO,
						$this->clase
						);
					$redirect =Vista::get_url("index.php/legajos/historial_titulo_creditos/{$persona_titulo->id}");
					$this->redirect($redirect);
					}else{
						$this->mensajeria->agregar(
						"Ha ocurrido un error al cargar el crédito.",
						\FMT\Mensajeria::TIPO_ERROR,
						$this->clase);
					}
				}else {
					foreach ((array)$titulo_creditos->errores as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
			$vista = $this->vista;
			$nombre_de_puesto = \App\Modelo\Perfil::listarNombrePuestos();
			$puesto = \FMT\Helper\Arr::path($nombre_de_puesto,"{$empleado->perfil_puesto->nombre_puesto}.nombre",'-');
			(new Vista($this->vista_default,compact('vista','permisos','empleado', 'titulo_creditos', 'puesto',  'persona_titulo')))->pre_render();
		}
	}

	protected function accion_modificar_titulo_creditos() {
		$titulo_creditos= Modelo\TituloCredito::obtener($this->request->query('id'));
		$persona_titulo	= Modelo\PersonaTitulo::obtener($titulo_creditos->id_persona_titulo);
		$id_empleado	= Modelo\Persona::getEmpleado($persona_titulo->id_persona);
		Modelo\Empleado::contiene(['persona', 'perfil_puesto']);
		$empleado		= Modelo\Empleado::obtener($id_empleado, true);

		if (!empty($empleado->id) && !empty($empleado->cuit)){
			$tipo_embargo= \App\Modelo\Embargo::getParam('TIPO_EMBARGO');
			$titulo_creditos->fecha 				= !empty($temp = $this->request->post('fecha')) ?  \DateTime::createFromFormat('d/m/Y', $temp) : $titulo_creditos->fecha;
			$titulo_creditos->acto_administrativo 	= !empty($temp = $this->request->post('acto_administrativo')) ?  $temp : $titulo_creditos->acto_administrativo;
			$titulo_creditos->creditos 				= !empty($temp = $this->request->post('creditos')) ?  $temp : $titulo_creditos->creditos;
			if($this->request->post('boton_titulo_creditos') == 'modificacion') {
				$titulo_creditos->id_persona_titulo		= $persona_titulo->id;
				$titulo_creditos->id_persona			= $empleado->persona->id;
				$titulo_creditos->archivo				= ($_FILES['titulo_credito_file']['error'] == UPLOAD_ERR_OK) ? $_FILES['titulo_credito_file'] : $titulo_creditos->archivo;
				$titulo_creditos->estado_titulo		= $persona_titulo->id_estado_titulo;
				if($titulo_creditos->validar()){
					$titulo_creditos->modificacion();
					$this->mensajeria->agregar(
					"El credito para el titulo del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/legajos/historial_titulo_creditos/{$persona_titulo->id}");
					$this->redirect($redirect);

				}else {
					foreach ((array)$titulo_creditos->errores as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
			$vista = $this->vista;
			$nombre_de_puesto = \App\Modelo\Perfil::listarNombrePuestos();
			$puesto = \FMT\Helper\Arr::path($nombre_de_puesto,"{$empleado->perfil_puesto->nombre_puesto}.nombre",'-');
			(new Vista($this->vista_default,compact('vista','empleado', 'titulo_creditos', 'persona_titulo')))->pre_render();
		}
	}

	protected function accion_mostrar_titulo_credito(){
		$archivo = Modelo\TituloCredito::obtener_archivo($this->request->query('id'));
		$doc_content = preg_replace("/\d{14}_/", "", $archivo);
		$doc = BASE_PATH.'/uploads/tituloCreditos/'.$archivo ;
		header("Content-Disposition:inline;filename=".$doc_content."");
		header("Content-type: application/pdf;");
		readfile($doc);
	}

	protected function accion_historial_cursos() {
		Modelo\Empleado::contiene(['persona' => []]);
		$empleado		= Modelo\Empleado::obtener($this->request->query('id'));
		$cursos_emple	= Modelo\EmpleadoCursos::listar($empleado->id);
		$cursos			= Modelo\Curso::listarParaSelect();
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'empleado', 'cursos_emple', 'cursos')))->pre_render();
	}

	protected function accion_alta_curso() {
		$cursos = Modelo\Curso::listarConcatenado();
		$empleado		= Modelo\Empleado::obtener($this->request->query('id'));
		$empleado_cursos					= Modelo\EmpleadoCursos::obtener($this->request->query('id'));

		if($this->request->post('boton_curso') == 'alta') {
			$empleado_cursos->id_empleado		= $empleado->id;
			$empleado_cursos->id_curso			= !empty($this->request->post('nombre_curso')) ? $this->request->post('nombre_curso') : null;
			$empleado_cursos->fecha				= !empty($this->request->post('fecha')) ? \DateTime::createFromFormat('d/m/Y H:i:s',$this->request->post('fecha') . ' 0:00:00') : null;
			$empleado_cursos->tipo_promocion = !empty($this->request->post('tipo_promocion')) ? $this->request->post('tipo_promocion') : null;
				if($empleado_cursos->validar()){
					$curso = Curso::obtener($empleado_cursos->id_curso);
					if($empleado_cursos->alta()){
						$this->mensajeria->agregar(
						"El curso {$curso->nombre_curso} del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue cargado correctamente",
				\FMT\Mensajeria::TIPO_AVISO,
				$this->clase);
						$redirect = Vista::get_url("index.php/Legajos/historial_cursos/$empleado->cuit");
						$this->redirect($redirect);	
					}else{
						$this->mensajeria->agregar(
						"No se pudo cargar el curso {$curso->nombre_curso} del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong>",
				\FMT\Mensajeria::TIPO_ERROR,
				$this->clase);
					}
				} else {
					foreach ($empleado_cursos->errores as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}

		}	
		
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'empleado','cursos')))->pre_render();
		
	}

	protected function accion_modificar_curso() {
		$cursos					= Modelo\Curso::listarConcatenado();
		$empleado_cursos		= Modelo\EmpleadoCursos::obtener($this->request->query('id'));
		Modelo\Empleado::contiene(['persona'=>[]]);
		$empleado				= Modelo\Empleado::obtener($empleado_cursos->id_empleado, true);
		$curso					= Modelo\Curso::obtener($empleado_cursos->id_curso);

		if($this->request->post('boton_curso') == 'modificacion') {
			$empleado_cursos->id_empleado		= $empleado->id;
			$empleado_cursos->id_curso			= !empty($this->request->post('nombre_curso')) ? $this->request->post('nombre_curso') : $$empleado_cursos->id_curso	;
			$empleado_cursos->fecha				= !empty($this->request->post('fecha')) ? \DateTime::createFromFormat('d/m/Y H:i:s',$this->request->post('fecha') . ' 0:00:00') : $empleado_cursos->fecha;
			$empleado_cursos->tipo_promocion	= ($this->request->post('tipo_promocion') == Modelo\Curso::PROMOCION_TRAMO) ? Modelo\Curso::PROMOCION_TRAMO : Modelo\Curso::PROMOCION_GRADO;
			if($empleado_cursos->validar()){
				$curso = Curso::obtener($empleado_cursos->id_curso);
				if($empleado_cursos->modificacion()){
						$this->mensajeria->agregar(
							"El curso {$curso->nombre_curso} del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> ha sido modificado correctamente",
							\FMT\Mensajeria::TIPO_AVISO,
						$this->clase);
						$redirect = Vista::get_url("index.php/Legajos/historial_cursos/$empleado->cuit");
						$this->redirect($redirect);	
				}else{
					$this->mensajeria->agregar(
						"No se pudo modificar el curso {$curso->nombre_curso} del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong>",
						\FMT\Mensajeria::TIPO_ERROR,
					$this->clase);
				}
			} else {
				foreach ((array)$empleado_cursos->errores as $text) {
					$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
				}
			}
		}
		
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'empleado','cursos','curso', 'empleado_cursos')))->pre_render();
		
	}
	protected function accion_baja_curso(){	
		$empleado_cursos					= Modelo\EmpleadoCursos::obtener($this->request->query('id'));
		Modelo\Empleado::contiene(['persona']);
		$empleado = Modelo\Empleado::obtener($empleado_cursos->id_empleado, true);
		$curso = Modelo\Curso::obtener($empleado_cursos->id_curso);

		if (!empty($empleado_cursos->id)){
			if($this->request->post('confirmar')){
				if($empleado_cursos->id){
					$curso = Curso::obtener($empleado_cursos->id_curso);
					if ($empleado_cursos->baja()){
							$this->mensajeria->agregar(
							"El curso {$curso->nombre_curso} del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> ha sido eliminado correctamente",
							\FMT\Mensajeria::TIPO_AVISO,
							$this->clase);
					} else {
						$this->mensajeria->agregar(
						"No se ha podido eliminar el curso {$curso->nombre_curso} del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong>",
						\FMT\Mensajeria::TIPO_ERROR,
						$this->clase);
					}
					$redirect =Vista::get_url("index.php/legajos/historial_cursos/{$empleado->cuit}");
					$this->redirect($redirect);
				}
			} 
		}


		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista','curso', 'empleado')))->pre_render();
	}	
	protected function accion_buscar_curso(){
		if($this->request->is_ajax()){
			$data = $this->_get_curso($this->request->post('codigo_curso'));
			$this->json->setData($data);
			$this->json->render();
			exit;
		}
	}	

	private function _get_curso($id = null) {
		if (!is_null($id)) {
			$curso	=  Modelo\Curso::obtener($id);
			}	 	
		return $curso;
	}

}
