<?php
namespace App\Controlador;

//use App\Modelo\AppRoles;
use App\Modelo;
use App\Modelo\Empleado;

class Api extends \FMT\Controlador {
	protected function accion_index(){
		$info	= [
			'No existe la información solicitada.',
			'Los metodos disponibles son: /agente, /search_agentes, /createEmpleado, /parametricos, /dependencias, /convenios, /responsables_contrato, /agente_mail, /convenio_ur',
		];
		$this->json->setData();
		$this->json->setMensajes($info);
		$this->json->setError();
		$this->json->render();
	}

/**
 * Interactua con el legajo de un agente en particular.
 * - Permite consultar por CUIT o por ID.
 * - Permite interactuar con `::contiene()`
 * - El comportamiento cambia segun el metodo de consulta, *GET* para obtener informacion. *POST* y *PUT* para crear o actualizar datos.
 *
 * Ejemplo de endpoint: sigarhu/api.php/agente/20121231237
 *
 * @return void
*/
	protected function accion_agente(){
		$cuit		= $this->request->query('id');
		$contiene	= json_decode($this->request->query('contiene'), true);
		$filtros	= [
			'contiene'	=> ($contiene === 1) ? true : $contiene,
			'by_id'		=> (bool)$this->request->query('by_id'),
		];
		$data		= (object)['id' => null];
		switch ($this->request->method()) {
			case 'GET':
				Modelo\Empleado::contiene($filtros['contiene']);
				$data	= Modelo\Empleado::obtener($cuit, $filtros['by_id']);
				break;
			case 'POST':
				Modelo\Empleado::contiene($filtros['contiene']);
				$data	= Modelo\Empleado::obtener($cuit, $filtros['by_id']);
				if(!empty($data->id)){
					$this->createEmpleado($data);
				}
				break;
			case 'PUT':
				Modelo\Empleado::contiene($filtros['contiene']);
				$data	= Modelo\Empleado::obtener($cuit, $filtros['by_id']);
				if(!empty($data->id)){
					$this->updateEmpleado($data);
				}
				break;
			case 'DELETE':
				break;
		}
		if(empty($data->id)){
			$this->json->setError();
			$this->json->setMensajes(['Agente no encontrado']);
		}
		$data	= json_decode(json_encode($data), true);
		$this->json->setData($data);
		$this->json->render();
	}

	protected function accion_search_agentes(){
		$params	= json_decode($this->request->query('params'), true);
		$filtros	= [
			'params'	=> ($params === 1) ? [] : $params,
		];

		$data	= Modelo\Empleado::ajaxBuscarAgentes($filtros['params']);
		if(empty($data)){
			$this->json->setError();
			$this->json->setMensajes(['Agente no encontrado']);
		}
		$data	= json_decode(json_encode($data), true);
		$this->json->setData($data);
		$this->json->render();
	}

	protected function accion_createEmpleado(){
		$cuit		= $this->request->query('id');
		$legajo_sigeco = json_decode($_POST['data'], 1);
		$legajo_sigeco	= static::arrayToObject($legajo_sigeco, false);
		$empleado	= Modelo\Empleado::obtener($cuit);
		$control_persona = unserialize(serialize($empleado->persona));
		$control_titulos = [];
		foreach ($empleado->persona->titulos as $key => $value) {
			$control_titulos[$key] = unserialize(serialize($value));
		}
		$control_horario = unserialize(serialize($empleado->horario));
		$control_ubicacion = unserialize(serialize($empleado->ubicacion));
		$control_perfil = unserialize(serialize($empleado->perfil_puesto));
		$control_escalafonaria = unserialize(serialize($empleado->situacion_escalafonaria));
		$control_dependencia = unserialize(serialize($empleado->dependencia));

		$empleado->cuit 					=  $legajo_sigeco['cuit'];
	    $empleado->email 					=  (empty($legajo_sigeco['email'])) ? 'sin_mail@transporte.gob.ar' : $legajo_sigeco['email'];

		$empleado->persona->nombre 			=  $legajo_sigeco['nombre'];
		$empleado->persona->apellido 		=  $legajo_sigeco['apellido'];
		$empleado->persona->tipo_documento  =  $legajo_sigeco['tipo_documento'];
		$empleado->persona->documento 		=  $legajo_sigeco['documento'];
		$empleado->persona->nacionalidad 	=  $legajo_sigeco['nacionalidad'];
		$empleado->persona->fecha_nac		=  $legajo_sigeco['fecha_nac'];
		$empleado->persona->estado_civil    =  $legajo_sigeco['estado_civil'];
		$empleado->persona->genero   		=  $legajo_sigeco['genero'];
		$empleado->persona->email    		=  (empty($legajo_sigeco['email'])) ? 'sin_mail@transporte.gob.ar' : $legajo_sigeco['email'] ;


		$domicilio 					= new \stdClass();
		$domicilio->id_provincia 	= (string)$legajo_sigeco['id_provincia'];
		$domicilio->id_localidad 	= (string)$legajo_sigeco['id_localidad'];
		$domicilio->calle  		 	= $legajo_sigeco['calle'];
		$domicilio->numero 			= (string)$legajo_sigeco['numero'];
		$domicilio->piso 			= $legajo_sigeco['piso'];
		$domicilio->depto  			= $legajo_sigeco['depto'];
		$domicilio->cod_postal 		= null;
		$domicilio->fecha_alta 		= new \DateTime('now');
		$domicilio->fecha_baja      = null;
		$empleado->persona->domicilio	= $domicilio;

		$this->_am_datos_personales($empleado, $control_persona);

		$empleado->persona->titulos = [];
		$empleado->persona->titulos[0] = Modelo\PersonaTitulo::obtener();
		$empleado->persona->titulos[0]->id_persona = $empleado->persona->id;
		$empleado->persona->titulos[0]->id_tipo_titulo  = $legajo_sigeco['id_tipo_titulo'];
		$empleado->persona->titulos[0]->id_titulo		= (string)$legajo_sigeco['id_titulo'];
		$empleado->persona->titulos[0]->id_estado_titulo = $legajo_sigeco['id_estado_titulo'];
		$empleado->persona->titulos[0]->principal		= 1;

		$this->_am_formacion($empleado, $control_titulos);

		$empleado->perfil_puesto->id_empleado = (int)$empleado->id;
		$empleado->perfil_puesto->denominacion_funcion  		= $legajo_sigeco['denominacion_funcion'];
		$empleado->perfil_puesto->denominacion_puesto  			= null;
		$empleado->perfil_puesto->objetivo_especifico 			= $legajo_sigeco['objetivo_especifico'];
		$empleado->perfil_puesto->objetivo_gral					= $legajo_sigeco['objetivo_gral'];
		$empleado->perfil_puesto->estandares					= $legajo_sigeco['estandares'];
		$empleado->perfil_puesto->actividad						= $this->_procesa_items($legajo_sigeco['tarea']);
		$empleado->perfil_puesto->resultados_parciales_finales 	= $this->_procesa_items($legajo_sigeco['resultado']);
		$empleado->perfil_puesto->fecha_obtencion_result		= null;
		$empleado->perfil_puesto->familia_de_puestos			= null;
		$empleado->perfil_puesto->nivel_destreza				= null;
		$empleado->perfil_puesto->nombre_puesto					= null;
		$empleado->perfil_puesto->puesto_supervisa				= null;
		$empleado->perfil_puesto->nivel_complejidad				= null;

		$this->_am_perfiles_puestos($empleado, $control_perfil);

		(string)$empleado->situacion_escalafonaria->id_agrupamiento	   	= !empty($temp = $legajo_sigeco['id_agrupamiento']) ?  (string)$temp : (string)$empleado->situacion_escalafonaria->id_agrupamiento;
		$empleado->situacion_escalafonaria->id_nivel 		   			= !empty($temp = $legajo_sigeco['id_nivel']) ?  (string)$temp : (string)$empleado->situacion_escalafonaria->id_nivel;
		$empleado->situacion_escalafonaria->id_tramo		   			= !empty($temp = $legajo_sigeco['id_tramo']) ?  (string)$temp : (string)$empleado->situacion_escalafonaria->id_tramo;
		$empleado->situacion_escalafonaria->id_grado		   			= !empty($temp = $legajo_sigeco['id_grado']) ?  (string)$temp : (string)$empleado->situacion_escalafonaria->id_grado;
		$empleado->situacion_escalafonaria->compensacion_geografica		= !empty($temp = $legajo_sigeco['compensacion_geografica']) ?  (string)$temp : (string)$empleado->situacion_escalafonaria->compensacion_geografica;
		$empleado->situacion_escalafonaria->compensacion_transitoria	= !empty($temp = $legajo_sigeco['compensacion_transitoria']) ?  (string)$temp : (string)$empleado->situacion_escalafonaria->compensacion_transitoria;
	    $empleado->situacion_escalafonaria->id_modalidad_vinculacion	= !empty($temp = $legajo_sigeco['id_modalidad_vinculacion']) ?  (string)$temp : (string)$empleado->situacion_escalafonaria->id_modalidad_vinculacion;
		$empleado->situacion_escalafonaria->id_situacion_revista		= !empty($temp = $legajo_sigeco['id_situacion_revista']) ? (string)$temp : (string)$empleado->situacion_escalafonaria->id_situacion_revista;
		$empleado->situacion_escalafonaria->unidad_retributiva			= !empty($temp = $legajo_sigeco['unidad_retributiva']) ? (string) $temp : (string) $empleado->situacion_escalafonaria->unidad_retributiva;

		$this->_am_situacion_escalafonaria($empleado, $control_escalafonaria);

		$empleado->dependencia->id_empleado = (int)$empleado->id;
		$empleado->dependencia->id_dependencia	= !empty($temp = $legajo_sigeco['id_dependencia']) ?  (string)$temp : (string)$empleado->dependencia->id_dependencia;
		$empleado->dependencia->nombre =   null;
		$empleado->dependencia->nivel =  null;
		$empleado->dependencia->fecha_desde =  new \DateTime('now');
		$empleado->dependencia->fecha_hasta = null;
		$empleado->dependencia->id_informal = null;
		$empleado->dependencia->id_dep_informal =  null;

		$this->_am_dependencia($empleado, $control_dependencia);

		$horario				= new \stdClass();
		$horario->horarios 		= !empty($legajo_sigeco['horarios']) ?   json_encode($legajo_sigeco['horarios']) : (string)$empleado->horario->horarios;
		$horario->id_turno      = (int)null;
		$horario->fecha_inicio  = new \DateTime('now');
		$horario->fecha_fin 	= null;
		$empleado->horario	= $horario;

		$this->_am_horarios($empleado, $control_horario);

		$ubicacion				 = new \stdClass();
		$ubicacion->id_ubicacion = !empty($legajo_sigeco['id_ubicacion']) ? (string)$legajo_sigeco['id_ubicacion'] : $empleado->ubicacion->id_ubicacion;
		$ubicacion->id_edificio  = null;
		$ubicacion->nombre  	 = null;
		$ubicacion->calle 		 = null;
		$ubicacion->numero		 = null;
		$ubicacion->piso		 = null;
		$ubicacion->oficina		 = null;
		$ubicacion->id_localidad = null;
		$ubicacion->id_provincia = null;
		$ubicacion->fecha_desde	 = new \DateTime('now');
		$ubicacion->fecha_hasta	 = null;
		$empleado->ubicacion	= $ubicacion;

		$this->_am_ubicacion($empleado, $control_ubicacion);
	}

	private function _am_datos_personales($empleado, $control_persona){
		if($empleado->persona->validar() && $empleado->validar()){
			if(empty($empleado->persona->id)){
				if (empty($empleado->persona->domicilio->id)) {
					$empleado->persona->domicilio->id			= null;
					$empleado->persona->domicilio->fecha_alta	= new \DateTime('now');
					$empleado->persona->domicilio->fecha_baja	= null;
				}
				$empleado->persona->alta();
				$empleado->alta();

			} else {
				if ($empleado->persona->nombre != $control_persona->nombre ||
					$empleado->persona->apellido 	 != $control_persona->apellido ||
					$empleado->persona->tipo_documento  != $control_persona->tipo_documento ||
					$empleado->persona->documento 	 != $control_persona->documento ||
					$empleado->persona->nacionalidad != $control_persona->nacionalidad ||
					$empleado->persona->fecha_nac		 != $control_persona->fecha_nac ||
					$empleado->persona->estado_civil  != $control_persona->estado_civil ||
					$empleado->persona->email     != $control_persona->email ||
					$empleado->persona->genero  != $control_persona->genero) {
						$empleado->persona->domicilio->id	=  $control_persona->domicilio->id;
						$empleado->persona->modificacion();
				}
				$empleado->estado	= Modelo\Empleado::EMPLEADO_ACTIVO;
				$empleado->modificacion();
				if ($control_persona->domicilio->id_provincia != $empleado->persona->domicilio->id_provincia ||
					$control_persona->domicilio->id_localidad != $empleado->persona->domicilio->id_localidad ||
					$control_persona->domicilio->calle != $empleado->persona->domicilio->calle ||
					$control_persona->domicilio->numero != $empleado->persona->domicilio->numero ||
					$control_persona->domicilio->piso != $empleado->persona->domicilio->piso ||
					$control_persona->domicilio->depto != $empleado->persona->domicilio->depto) {
						$aux = unserialize(serialize($empleado->persona->domicilio));
						$empleado->persona->domicilio = $control_persona->domicilio;
						$empleado->persona->domicilio->fecha_baja	= new \DateTime('now');
						$empleado->persona->modificacion();
						$empleado->persona->domicilio = $aux;
						$empleado->persona->domicilio->id			= null;
						$empleado->persona->domicilio->fecha_baja	= null;
						$empleado->persona->alta();
				}
			}
		}
		return $empleado;
	}

	private function _am_formacion($empleado, $control_titulos) {
		$ctrl = [];
		array_walk($control_titulos,function($value , $index ) use (&$ctrl) {
			$ctrl[$index] = $value->id_titulo;
		 });
		$flag = array_search($empleado->persona->titulos[0]->id_titulo,$ctrl);

		if ($flag !== false){
			$empleado->persona->titulos[0]->id =  $control_titulos[$flag]->id;
			$empleado->persona->titulos[0]->modificacion();
		} elseif(!is_null($empleado->persona->titulos[0]->id_titulo)) {
			$empleado->persona->titulos[0]->alta();
		}

	}

	private function _am_perfiles_puestos(Empleado $empleado=null, $control_perfil){
		if ($empleado->perfil_puesto->validar()) {
			if (empty($control_perfil->id) ){
				$empleado->perfil_puesto->alta();
			} else {
				if ($control_perfil->denominacion_funcion != $empleado->perfil_puesto->denominacion_funcion) {

					$aux = unserialize(serialize($empleado->perfil_puesto));
					$empleado->perfil_puesto = $control_perfil;
					$empleado->perfil_puesto->fecha_hasta	= new \DateTime('now');
					$resp = $empleado->perfil_puesto->baja();
					$empleado->perfil_puesto = $aux;
					$empleado->perfil_puesto->id				= null;
					$empleado->perfil_puesto->fecha_desde		= new \DateTime('now');
					$empleado->perfil_puesto->fecha_hasta		= null;
					$empleado->perfil_puesto->alta();
				}elseif ($control_perfil->objetivo_gral != $empleado->perfil_puesto->objetivo_gral || $control_perfil->objetivo_especifico != $empleado->perfil_puesto->objetivo_especifico || $control_perfil->estandares != $empleado->perfil_puesto->estandares) {
					$empleado->perfil_puesto->modificacion();
				}
			}
		}
	}

	private function _am_situacion_escalafonaria(Empleado $empleado=null, $control_escalafonaria){

		if (empty($control_escalafonaria->id)) {
			$empleado->alta_situacion_escalafonaria();
		}else{
			if ($control_escalafonaria->id_nivel != $empleado->situacion_escalafonaria->id_nivel || $control_escalafonaria->id_grado != $empleado->situacion_escalafonaria->id_grado){
				$aux = unserialize(serialize($empleado->situacion_escalafonaria));
				$empleado->situacion_escalafonaria = $control_escalafonaria;
				$resp = $empleado->baja_situacion_escalafonaria();
				$empleado->situacion_escalafonaria = $aux;
					if ($resp) {
						$empleado->situacion_escalafonaria->id = null;
						$empleado->situacion_escalafonaria->fecha_inicio = new \DateTime('now');
						$empleado->alta_situacion_escalafonaria();
					}
			}elseif ($control_escalafonaria->id_agrupamiento != $empleado->situacion_escalafonaria->id_agrupamiento || $control_escalafonaria->id_tramo != $empleado->situacion_escalafonaria->id_tramo || $control_escalafonaria->compensacion_geografica != $empleado->situacion_escalafonaria->compensacion_geografica || $control_escalafonaria->compensacion_transitoria != $empleado->situacion_escalafonaria->compensacion_transitoria || $control_escalafonaria->unidad_retributiva != $empleado->situacion_escalafonaria->unidad_retributiva ) {
				$empleado->modificacion_situacion_escalafonaria();
			}
		}
	}

	private function _am_dependencia(Empleado $empleado=null, $control_dependencia){
		if (empty($control_dependencia->id_dependencia)) {
			$empleado->alta();
		}else{
			if($control_dependencia->id_dependencia != $empleado->dependencia->id_dependencia){
				if($control_dependencia->fecha_desde) {
					if($empleado->validar()) {
						$aux = unserialize(serialize($empleado->dependencia));
						$empleado->dependencia= $control_dependencia;
						$empleado->dependencia->id_dep_informal = null;
						$empleado->dependencia->fecha_hasta = new \DateTime('now');
						$empleado->modificacion();
						$empleado->dependencia = $aux;
						$empleado->dependencia->id = null;
						$empleado->dependencia->fecha_desde = new \DateTime('now');
						$empleado->dependencia->fecha_hasta = null;
						$empleado->alta();
					}
				}
			}
		}
	}

	private function _am_horarios(Empleado $empleado=null, $control_horario){
		if (empty($control_horario->id)) {
			$empleado->alta_horario();
		}else{
			if($control_horario->horarios != $empleado->horario->horarios){
				if($control_horario->fecha_inicio) {
					if($empleado->validar()) {
						$aux = unserialize(serialize($empleado->horario));
						$empleado->horario = $control_horario;
						$empleado->horario->fecha_fin = new \DateTime('now');
						$resp = $empleado->modificacion_horario();
						$empleado->horario = $aux;
						if ($resp) {
							$empleado->horario->id = null;
							$empleado->horario->fecha_inicio  = new \DateTime('now');
							$empleado->horario->fin  =  null;
							$empleado->alta_horario();
						}
					}
				}
			}
		}
	}

	private function _am_ubicacion(Empleado $empleado=null, $control_ubicacion){

		if (empty($control_ubicacion->id)) {
			$empleado->alta_ubicacion();
		}else{
			if($control_ubicacion->id_ubicacion != $empleado->ubicacion->id_ubicacion){
				if($control_ubicacion->fecha_desde) {
					if($empleado->validar()) {
						$aux = unserialize(serialize($empleado->ubicacion));
						$empleado->ubicacion = $control_ubicacion;
						$empleado->ubicacion->fecha_hasta = new \DateTime('now');
						$resp = $empleado->modificacion_ubicacion();
						$empleado->ubicacion = $aux;
						if ($resp) {
							$empleado->ubicacion->id = null;
							$empleado->ubicacion->fecha_desde  =   new \DateTime('now');
							$empleado->ubicacion->fecha_hasta  =  null;
							$empleado->alta_ubicacion();
						}
					}
				}
			}
		}
	}

/**
 * Devuelve datos parametricos usados en Sigarhu
 * - Permite interactuar con `::contiene()`
 *
 * Ejemplo de endpoint: sigarhu/api.php/parametricos
 *
 * TODO: Documentar mejor que datos estan disponibles y que significan.
 * @return void
*/
	protected function accion_parametricos(){
		if($this->request->method() !== 'GET'){
			$this->json->setMensajes(['Solo el metodo "GET" es valido para este recurso.']);
			$this->json->setError();
			$this->json->render();
		}
		$contiene	= json_decode($this->request->query('contiene'), true);
		$filtros	= [
			'contiene'	=> ($contiene === 1) ? [] : $contiene,
		];

		$incluir_estados = [
			Modelo\Empleado::EMPLEADO_INACTIVO	=> Modelo\Empleado::EMPLEADO_INACTIVO,
			Modelo\Empleado::EMPLEADO_ACTIVO	=> Modelo\Empleado::EMPLEADO_ACTIVO,
		];

		if(is_array($filtros['contiene']) && count($filtros['contiene']) > 0){
			$parametricos	= [];
			foreach ($filtros['contiene'] as $parametro) {
				switch ($parametro) {
					case 'genero':
						$parametricos['genero']						= Modelo\Persona::getParam('GENERO');
						break;
					case 'estado_civil':
						$parametricos['estado_civil']			 	= Modelo\Persona::getParam('ESTADO_CIVIL');
						break;
					case 'tipo_documento':
						$parametricos['tipo_documento']		 		= Modelo\Persona::getParam('TIPO_DOCUMENTO');
						break;
					case 'tipo_telefono':
						$parametricos['tipo_telefono']			 	= Modelo\PersonaTelefono::getParam('TIPO_TELEFONO');
						break;
					case 'nacionalidad':
						$parametricos['nacionalidad']			 	= json_decode(json_encode(\FMT\Ubicaciones::get_gentilicios()), JSON_UNESCAPED_UNICODE);
						break;
					case 'nivel_organigrama':
						$parametricos['nivel_organigrama'] 	 		= Modelo\Dependencia::getParam('NIVEL_ORGANIGRAMA');
						break;
					case 'saf':
						$parametricos['saf']					 	= Modelo\Presupuesto::getSaf();
						break;
					case 'jurisdicciones':
						$parametricos['jurisdicciones']		 		= Modelo\Presupuesto::getJurisdiccion();
						break;
					case 'ubicaciones_geograficas':
						$parametricos['ubicaciones_geograficas']	= Modelo\Presupuesto::getUbicacionesGeograficas();
						break;
					case 'programas':
						$parametricos['programas']				 	= Modelo\Presupuesto::getProgramas();
						break;
					case 'actividades':
						$parametricos['actividades']			 	= Modelo\Presupuesto::getActividades();
						break;
					case 'modalidad_vinculacion':
						$parametricos['modalidad_vinculacion']	 	= Modelo\Contrato::obtenerVinculacionRevista()['modalidad_vinculacion'];
						break;
					case 'situacion_revista':
						$parametricos['situacion_revista']		 	= Modelo\Contrato::obtenerVinculacionRevista()['situacion_revista'];
						break;
					case 'vinculacion_revista':
						$parametricos['vinculacion_revista']		 	= Modelo\Contrato::obtenerVinculacionRevista();
						break;
					case 'tipo_discapacidad':
						$parametricos['tipo_discapacidad'] 	 		= Modelo\Persona::getDiscapacidad();
						break;
					case 'formacion_tipo_titulo':
						$parametricos['formacion_tipo_titulo']		= Modelo\NivelEducativo::getNivelEducativo();
						break;
					case 'formacion_estado_titulo':
						$parametricos['formacion_estado_titulo']	= Modelo\PersonaTitulo::getParam('ESTADO_TITULO');
						break;
					case 'familia_de_puesto':
						$parametricos['familia_de_puesto'] 			= Modelo\Perfil::listarFamiliaPuestos();
						break;
					case 'denominacion_del_puesto':
						$parametricos['denominacion_del_puesto'] 	= Modelo\Perfil::listarDenominacionDelPuesto();
						break;
					case 'denominacion_de_la_funcion':
						$parametricos['denominacion_de_la_funcion']	= Modelo\Perfil::listarDenominacionFuncion();
						break;
					case 'nivel_de_destreza':
						$parametricos['nivel_de_destreza'] 			= Modelo\Perfil::$NIVELES_DESTREZA;
						break;
					case 'nombre_de_puesto':
						$parametricos['nombre_de_puesto'] 			= Modelo\Perfil::listarNombrePuestos();
						break;
					case 'niveles_complejidad':
						$parametricos['niveles_complejidad'] 		= Modelo\Perfil::$NIVELES_COMPLEJIDAD;
						break;
					case 'niveles_puesto_supervisa':
						$parametricos['niveles_puesto_supervisa'] 	= Modelo\Perfil::$NIVELES_PUESTO_SUPERVISA;
						break;
					case 'plantilla_horario':
						$parametricos['plantilla_horario'] 			= Modelo\Horario::getPlantillaHorario();
						break;
					case 'turno':
						$parametricos['turno']						= Modelo\Empleado::getParam('TURNO');
						break;
					case 'motivo_baja':
						$parametricos['motivo_baja']				= Modelo\Empleado::getParam('MOTIVO_BAJA');
						break;
					case 'estado_administracion':
						$parametricos['estado_administracion']		= array_intersect_key(Modelo\Empleado::$TIPO_ESTADOS_EMPLEADOS, $incluir_estados);
						break;
					case 'estados_empleado':
						$parametricos['estados_empleado']			= Modelo\Empleado::getParam('TIPO_ESTADOS_API');
						break;
					case 'estado_comision':
						$parametricos['estado_comision']			= Modelo\Empleado::getParam('ESTADOS');
						break;
					case 'comisiones':
						$parametricos['comisiones']					= Modelo\Comision::listar(true);
						break;
					case 'licencias_especiales':
						$parametricos['licencias_especiales'] 		= Modelo\LicenciaEspecial::getLicenciasEspeciales();
						break;
					case 'ubicaciones':
						$parametricos['ubicaciones']				= Modelo\Ubicacion::getEdificios();
						break;
					case 'tipo_presentacion':
						$parametricos['tipo_presentacion'] 	 		= Modelo\Anticorrupcion::getParam('TIPO_DJ');
						break;
					case 'exc_art_14':
						$parametricos['exc_art_14'] 	 			= Modelo\Empleado::getParam('EXCEPCION_ART_14');
						break;
					case 'id_sindicato':
						$parametricos['id_sindicato'] 	 			= Modelo\Empleado::getSindicato();
						break;
					case 'obras_sociales':
						$parametricos['obras_sociales']				= Modelo\Empleado::getObraSociales();
						break;
					case 'seguros_vida':
						$parametricos['seguros_vida']				= Modelo\Empleado::getSegurosVida();
						break;
					case 'sindicatos':
						$parametricos['sindicatos']					= Modelo\EmpleadoSindicato::getSindicatos(false);
						break;
					case 'parentesco':
						$parametricos['parentesco']					= Modelo\GrupoFamiliar::getParam('PARENTESCO');
						break;
					case 'opcion_sino':
						$parametricos['opcion_sino']				= Modelo\GrupoFamiliar::getParam('OPCION_SINO');
						break;
					case 'porcentaje_desgrava':
						$parametricos['porcentaje_desgrava']		= Modelo\GrupoFamiliar::getParam('PORCENTAJE_DESGRAVA');
						break;
					case 'titulo':
						$parametricos['titulo']						= Modelo\Titulo::listar();
						break;
					case 'select_dependencia':
						$parametricos['select_dependencia'] 		= Modelo\Dependencia::listar(true);
						break;
                    case 'dependencias':
                        Modelo\Dependencia::anularFiltro();
						$parametricos['dependencias'] 		        = Modelo\Dependencia::listar();
						break;
					case 'lista_dep_informales':
						$parametricos['lista_dep_informales'] 		        = Modelo\Dependencia::lista_dep_informales();
						break;
					case 'ubicacion_regiones':
						$parametricos['ubicacion_regiones']			= json_decode(json_encode(\FMT\Ubicaciones::get_regiones('AR')), JSON_UNESCAPED_UNICODE);
						break;
				}
			}
			$this->json->setData($parametricos);
			$this->json->render();
		} else {
			$parametricos	= [
				'genero',
				'estado_civil',
				'tipo_documento',
				'tipo_telefono',
				'nacionalidad',
				'nivel_organigrama',
				'saf',
				'jurisdicciones',
				'ubicaciones_geograficas',
				'programas',
				'actividades',
				'modalidad_vinculacion',
				'situacion_revista',
				'vinculacion_revista',
				'tipo_discapacidad',
				'formacion_tipo_titulo',
				'formacion_estado_titulo',
				'familia_de_puesto',
				'denominacion_del_puesto',
				'denominacion_de_la_funcion',
				'nivel_de_destreza',
				'nombre_de_puesto',
				'niveles_complejidad',
				'niveles_puesto_supervisa',
				'plantilla_horario',
				'turno',
				'motivo_baja',
				'estado_administracion',
				'estados_empleado',
				'estado_comision',
				'comisiones',
				'licencias_especiales',
				'ubicaciones',
				'tipo_presentacion',
				'exc_art_14',
				'id_sindicato',
				'obras_sociales',
				'seguros_vida',
				'sindicatos',
				'parentesco',
				'opcion_sino',
				'porcentaje_desgrava',
				'titulo',
				'select_dependencia',
                'ubicacion_regiones',
                'dependencias',
	        ];
	        $this->json->setMensajes(['Este recurso recibe en el parametro "contiene" alguno de los siguientes elementos.', $parametricos]);
			$this->json->setError();
			$this->json->render();
		}
	}

/**
 * Devuelve datos parametricos usados en Sigarhu
 * - Permite interactuar con `::contiene()`
 *
 * Ejemplo de endpoint: sigarhu/api.php/dependencias
 *
 * @return void
*/
	protected function accion_dependencias(){
		$id		= $this->request->query('id');

		$data		= (object)['id' => null];
		switch ($this->request->method()) {
			case 'GET':
				if(!empty($id)){
					$data	= Modelo\Dependencia::obtener($id);
				} else {
					$data	= [
						'data'				=> Modelo\Dependencia::listar(),
						'nivel_organigrama'	=> Modelo\Dependencia::getParam('NIVEL_ORGANIGRAMA'),
					];
				}
				$this->json->setMensajes(['Si recibe un ID obtiene la informacion completa, caso contrario devuelve un listado.']);
				break;
			default:
				$this->json->setMensajes([
					'Solo el metodo "GET" es valido para este recurso.',
					'Si recibe un ID obtiene la informacion completa, caso contrario devuelve un listado.'
				]);
				$this->json->setError();
				$this->json->render();
				break;
		}
		if(!(empty($data->id) || (is_array($data) && empty($data)))){
			$this->json->setError();
			$this->json->setMensajes(['Dependencia no encontrada']);
		}
		$data	= json_decode(json_encode($data), true);
		$this->json->setData($data);
		$this->json->render();
	}

	protected function accion_convenios(){
		switch ($this->request->method()) {
			case 'GET':
				$data	= Modelo\Contrato::obtenerConvenio($this->request->query('id_modalidad_vinculacion'), $this->request->query('id_situacion_revista'));
				break;
			default:
				$this->json->setMensajes(['Solo el metodo "GET" es valido para este recurso.']);
				$this->json->setError();
				$this->json->render();
				break;
		}
		$this->json->setData($data);
		$this->json->render();
	}

	protected function accion_responsables_contrato(){
		$id			= $this->request->query('id');
		$data		= [];
		$data_extra	= [];
		switch ($this->request->method()) {
			case 'GET':
				$data = Modelo\ResponsableContrato::obtener($id);
				$data_extra['contratante']	= !empty($tmp = $data->getContratante()) ? $tmp : [];
				$data_extra['firmante']		= !empty($tmp = $data->getFirmante()) ? $tmp : [];
				if(empty($data_extra['contratante']) && empty($data_extra['firmante'])){
					$this->json->setMensajes(['La dependencia no tiene contrantates o firmantes.']);
					$this->json->setError();
				}
				break;
			default:
				$this->json->setMensajes(['Solo el metodo "GET" es valido para este recurso.']);
				$this->json->setError();
				$this->json->render();
				break;
		}
		$data	= json_decode(json_encode($data), true);
		$data	+= $data_extra;
		$this->json->setData($data);
		$this->json->render();
	}

	protected function _procesa_items($items) {
		foreach($items as &$dato){
			if(!empty($dato)){
				$aux = new \stdClass();
				$aux->id = is_array($dato) ? $dato['id'] : $dato->id;
				$aux->nombre = is_array($dato) ? $dato['nombre'] : $dato->nombre;
				$dato = $aux;
			}
		}
		return $items;
	}

	protected function accion_agente_mail(){
		$mail		= $this->request->query('id');	
		$contiene	= ['situacion_escalafonaria'];
		$data		= (object)['id' => null];
		Modelo\Empleado::contiene($contiene);
		$data	= Modelo\Empleado::obtener_mail($mail);
		if(empty($data->id)){
			$this->json->setError();
			$this->json->setMensajes(['Agente no encontrado']);
		}
		$data	= json_decode(json_encode($data), true);
		$this->json->setData($data);
		$this->json->render();
	}

/**
 * Interactua con la tabla de Unidades Retributivas.
 * Para consultar los valores para una funcion  y nivel determinado debe pasar los ID separados por guion medio "-" e.j: 15-22
 * En caso de no pasar `ID` devuelve el listado completo.
 *
 * Ejemplo de endpoint: sigarhu/api.php/convenio_ur/37-51
 *
 * @return void
*/
	protected function accion_convenio_ur(){
		$funcion_nivel		= $this->request->query('id');
		$id_funcion			= null;
		$id_nivel			= null;
		if(preg_match('/-/', $funcion_nivel)){
			$funcion_nivel	= preg_split('/-/', $funcion_nivel);
			$id_funcion	= $funcion_nivel[0];
			$id_nivel	= $funcion_nivel[1];
		} else if(!empty($funcion_nivel)){
			$this->json->setMensajes(['Para trabajar la tabla de unidades retributivas debe pasar los ID de funcion y nivel separados por guion medio "-" e.j: 15-22']);
			$this->json->setError();
			$this->json->render();
		}
		if(
			!empty($fecha_filtro = $this->request->query('fecha_filtro'))
			&& empty($fecha_filtro	= \DateTime::createFromFormat('Y-m-d H:i:s', $fecha_filtro.' 0:00:00'))
		){
			$this->json->setMensajes(['La fecha pasada como filtro debe tener el formato "Y-m-d"']);
			$this->json->setError();
			$this->json->render();
		}

		$data		= (object)['id_nivel' => null];
		switch ($this->request->method()) {
			case 'GET':
				Modelo\ConvenioUR::setFiltro('fecha', $fecha_filtro);
				if(empty($funcion_nivel)){
					$data	= Modelo\ConvenioUR::listar();
				} else {
					$data	= Modelo\ConvenioUR::obtener($id_funcion,$id_nivel);
				}
				break;
			case 'POST':
				$post_data	= json_decode($this->request->post('data'), true);
				$post_data	= static::arrayToObject($post_data, true);

				if(!isset($post_data->id_nivel) || !isset($post_data->id_grado)){
					$this->json->setMensajes(['Se debe especificar que funcion y nivel se esta modificando.']);
					$this->json->setError();
					$this->json->render();
				}
				$data	= Modelo\ConvenioUR::obtener($post_data->id_nivel, $post_data->id_grado);

				$alta_unidad	= false;
				$alta_monto		= false;

				$data->monto->monto		= $post_data->monto->monto;
				$data->unidad->minimo	= $post_data->unidad->minimo;
				$data->unidad->maximo	= $post_data->unidad->maximo;
				$data->id_nivel			= $post_data->id_nivel;
				$data->id_grado			= $post_data->id_grado;

				if($data->monto->fecha_inicio != $post_data->monto->fecha_inicio){
					$data->monto->id			= null;
					$data->monto->monto			= $post_data->monto->monto;
					$data->monto->fecha_inicio	= ($post_data->monto->fecha_inicio instanceof \DateTime)
						? $post_data->monto->fecha_inicio
						: \DateTime::createFromFormat('d/m/Y H:i:s', $post_data->monto->fecha_inicio . ' 0:00:00');
					$alta_monto	= true;
				}

				if($data->unidad->fecha_inicio != $post_data->unidad->fecha_inicio){
					$data->unidad->id			= null;
					$data->unidad->maximo		= $post_data->unidad->maximo;
					$data->unidad->minimo		= $post_data->unidad->minimo;
					$data->unidad->fecha_inicio	= ($post_data->unidad->fecha_inicio instanceof \DateTime)
						? $post_data->unidad->fecha_inicio
						: \DateTime::createFromFormat('d/m/Y H:i:s', $post_data->unidad->fecha_inicio. ' 0:00:00');

					$alta_unidad	= true;
				}

				if(!$data->validar()){
					$this->json->setMensajes($data->errores);
					$this->json->setError();
					$this->json->render();
				}

				if($alta_monto){
					$data->alta_monto();
				} else {
					$data->modificacion_monto();
				}

				if($alta_unidad){
					$data->alta_unidad();
				} else {
					$data->modificacion_unidad();
				}
				break;
		}
		if(!(!empty($data->id_nivel) || (is_array($data) && count($data) > 0))){
			$this->json->setError();
			$this->json->setMensajes(['No se encontraron Unidades Retributivas.']);
		}
		$data	= json_decode(json_encode($data), true);
		$this->json->setData($data);
		$this->json->render();
	}

/**
 * Interactua con el modulo Auditoria.
 * Posee 2 comportamientos:
 * - Si el `id` pasado es un CUIT, entonces va a devolver la ultima fecha que tuvo modificaciones.
 * - Si se pasa un rango de fechas, se obtiene un array con los cuits modificados en ese rango. Si la `fecha_hasta` no se pasa, se asume que es el presente.
 *
 * El uso principal es para que un sistema pueda ir buscando actualizaciones regulares a intervalos de tiempo.
 *
 * Ejemplo de endpoint: sigarhu/api.php/auditoria/?fecha_desde=2019-12-12%2010:11:12
 * Ejemplo de endpoint: sigarhu/api.php/auditoria/00121231234/
 * @return void
 */
	protected function accion_auditoria(){
		$cuit			= $this->request->query('id');
		$fecha_desde	= $this->request->query('fecha_desde');
		$fecha_desde	= !empty( $tmp = \DateTime::createFromFormat('Y-m-d H:i:s', $fecha_desde)) ? $tmp : \DateTime::createFromFormat('Y-m-d H:i:s', $fecha_desde.' 0:00:00');
		$fecha_hasta	= $this->request->query('fecha_hasta');
		$fecha_hasta	= !empty( $tmp = \DateTime::createFromFormat('Y-m-d H:i:s', $fecha_hasta)) ? $tmp : \DateTime::createFromFormat('Y-m-d H:i:s', $fecha_hasta.' 0:00:00');
		if(empty($fecha_desde) && empty($cuit)){
			$info	= [
				'Muy pocos parametros para devolver resultados.',
                'Requisitos minimos; CUIT (como id) o "fecha_desde" o "fecha_desde" y "fecha_hasta"',
                'Las fechas deben estar en formato "Y-m-d H:i:s" o "Y-m-d"',
			];
			$this->json->setMensajes($info);
			$this->json->setError();
			$this->json->render();
		}

		if($this->request->method() !== 'GET'){
			$this->json->setError();
			$this->json->render();
		}

        $data   = Modelo\Auditoria::apiBuscarActualizaciones([
            'cuit'          => $cuit,
            'fecha_desde'   => $fecha_desde,
            'fecha_hasta'   => $fecha_hasta,
        ]);

		$this->json->setData($data);
		$this->json->render();
	}

/**
 * Este metodo deberia usarse una sola vez en produccion.
 * Sirve para completar a `self::accion_auditoria()` y sincronizar los CUITs de los agentes que no tengan historial de modificaciones.
 *
 * Ejemplo de endpoint: sigarhu/api.php/sincronizar_extra
 * @return void
 */
	protected function accion_sincronizar_extra(){
		if($this->request->method() !== 'GET'){
			$this->json->setError();
			$this->json->render();
		}
		$cnx			= new \App\Helper\Conexiones();
		$config			= \FMT\Configuracion::instancia();
		$db_historial	= \FMT\Helper\Arr::path($config['database'], 'db_historial.database');

		$sql	= 'SELECT cuit FROM empleados WHERE cuit NOT IN (SELECT DISTINCT cuit FROM '.$db_historial.'.empleados)';
		$res	= $cnx->consulta(\App\Helper\Conexiones::SELECT, $sql, []);

		$data	= ['data'	=> [
			'tipo'			=> 'agentes',
			'fecha_desde'	=> null,
			'fecha_hasta'	=> null,
			'cuits'			=> [],
		]];

		if(empty($res[0])){
			$this->json->render();
		}
		foreach ($res as $_data) {
			$data['data']['cuits'][]	= $_data['cuit'];
		}
		$this->json->setData($data);
		$this->json->render();
	}

/**
 * Convierte un array a objecto en forma recursiva. Si algun indice es de tipo string y contiene la palabra *fecha* lo combierte a objecto DateTime::
 *
 * @param array		$data	- Informacion a convertir
 * @param bool		$como_objeto - Default: true. Devuelve un objeto o un array si es es `false`
 * @return array|object
 */
	static private function arrayToObject(&$data = null, $como_objeto = true)
	{
		foreach ($data as $attr => &$val) {
			if (is_array($val) && count($val) == 0) {
				$data[$attr]	= array();
				continue;
			}
			if (is_string($attr) && !is_array($val)) {
				$data[$attr]	= $val;
			} else if (is_string($attr) && preg_match('/fecha/i', $attr) && is_array($val) && !empty($val)) {
                $tmp    = \DateTime::createFromFormat('Y-m-d H:i:s', $val['date']);
                $tmp	= !empty($tmp) ? $tmp : \DateTime::createFromFormat('Y-m-d H:i:s.u', $val['date']); // arreglo para mantener compatibilidad entre versiones 5.5 y 5.6 o mayor de PHP

				$data[$attr]	= !empty($tmp) ? $tmp : \DateTime::createFromFormat('Y-m-d H:i:s.u', $val['date'].' 0:00:00.000000');
			} else if (is_array($val)) {
				if (is_array($val)) {
					$aux	= array_keys($val);
					$indice_numerico	= is_numeric(array_pop($aux));
				} else {
					$indice_numerico	= false;
				}

				$data[$attr]	= static::arrayToObject($val, !$indice_numerico);
			}
		}
		return ($como_objeto) ? (object) $data : $data;
	}
}
