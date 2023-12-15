<?php
namespace App\Controlador;
use App\Modelo;
use App\Modelo\Empleado;
use FMT\Logger;
// use FMT\Helper;
use FMT\Mailer;
use App\Helper\Vista;
use FMT\Consola;
use FMT\Consola\Modelo\ColaTarea;
use App\Helper\Conexiones;

/**
 * Acciones para procesar por cron del servidor.
*/
class Cron extends Consola {

/**
 * Obtiene el listado de anticorrupcion de los empleados 
*/
	protected function accion_notificaciones() {

		$listado = \App\Modelo\Anticorrupcion::listar_notificaciones();
		foreach ($listado as $value) {
			if ($value->tipo_presentacion == \App\Modelo\Anticorrupcion::ANUAL && $value->empleado->estado == \App\Modelo\Empleado::EMPLEADO_ACTIVO) {
			 	$this->_presentacion_anual($value);
			} elseif (!empty($value->fecha_aceptacion_renuncia) && $value->empleado->estado == \App\Modelo\Empleado::EMPLEADO_INACTIVO) {
				$this->_presentacion_baja($value);
			} elseif (in_array($value->tipo_presentacion, [null,\App\Modelo\Anticorrupcion::INICIAL])  &&  $value->empleado->estado == \App\Modelo\Empleado::EMPLEADO_ACTIVO) {
				$this->_presentacion_inicial($value);
			}
		}
	}
	

	protected function _presentacion_anual($value) {
		if($value->periodo < (date('Y') -1)) {
			$fecha_actual = new \DateTime('now');
			$f_inicio = \App\Modelo\Anticorrupcion::$FECHA_VENCIMIENTO;
			$f_control = clone $f_inicio;
			$f_control = $f_control->add(new \DateInterval('P4D'));
			if($fecha_actual <= $f_control) {
				if($fecha_actual > $f_inicio){
					$cant_dias = \FMT\Informacion_fecha::cantidad_dias_habiles($f_inicio);
					if ($cant_dias == 1) {
					 	$this->envio_email($value,\App\Modelo\Anticorrupcion::ANUAL);
					}
				}
			}
		}	
	}


	protected function _presentacion_baja ($value) {
		if(!$value->tipo_presentacion == \App\Modelo\Anticorrupcion::BAJA) {
			$fecha_actual = new \DateTime('now');
			$f_inicio = $value->fecha_aceptacion_renuncia;
			if($fecha_actual > $f_inicio){
				$intervalo = $f_inicio->diff($fecha_actual);
				$semanas = $intervalo->format('%d')/7;
				/*
				* Se corta la evaluación a las 9 semanas despues del inicio porque aproximadamente  pasaron 45 dias habiles.
				*/
				if($semanas > 9) {
					$cant_dias = \FMT\Informacion_fecha::cantidad_dias_habiles($f_inicio);
					/*
					* Se envia el día habil 38 porque quedan 7 dias habiles para el vencimiento.
					*/
					if ($cant_dias == 38 ) {
					 	$this->envio_email($value,\App\Modelo\Anticorrupcion::BAJA);
					}
				}
			}
		}
	}


	protected function _presentacion_inicial ($value){
		if($value->tipo_presentacion == \App\Modelo\Anticorrupcion::INICIAL && $value->periodo < date('Y')) {
			return $this->_presentacion_anual($value);
		} elseif ($value->periodo == date('Y')) {
			return true;
		}
		$fecha_actual = new \DateTime('now');
		$f_inicio = $value->fecha_publicacion_designacion;
		if($fecha_actual > $f_inicio){
			$intervalo = $f_inicio->diff($fecha_actual);
			$semanas = $intervalo->format('%d')/7;
			/*
			* Se corta la evaluación a las 9 semanas despues del inicio porque aproximadamente  pasaron 45 dias habiles.
			*/			
			if($semanas > 9) {
				$cant_dias = \FMT\Informacion_fecha::cantidad_dias_habiles($f_inicio);
				/*
				* Se envia el día habil 38 porque quedan 7 dias habiles para el vencimiento.
				*/
				if ($cant_dias == 38 ) {
				 	$this->envio_email($value,\App\Modelo\Anticorrupcion::INICIAL);
				}
			}					
		}
	}


	protected function envio_email ($value, $presentacion){
		$config = \FMT\Configuracion::instancia();
		switch ($presentacion) {
			case \App\Modelo\Anticorrupcion::INICIAL:
			case \App\Modelo\Anticorrupcion::BAJA:
				$plazo = 'en el plazo de 7 días'; 
				break;
			case \App\Modelo\Anticorrupcion::ANUAL:
				$plazo = 'con fecha '.\App\Modelo\Anticorrupcion::$FECHA_VENCIMIENTO->format('d/m'); 
				break;			
			default:
				# code...
				break;
		}
		$Email	= new Mailer();
		$Email->servidor = $config['email']['host'];
		$Email->puerto = $config['email']['port'];
		$Email->usuario = $config['email']['user'];
		$Email->clave = $config['email']['pass'];
		$Email->SMTPAuth = $config['email']['SMTPAuth'];
		$Email->SMTPAutoTLS = $config['email']['SMTPAutoTLS'];
		$Email->CharSet = 'utf8';
		$Email->isHTML();
		$Email->agregarDestinatario($value->empleado->email, $value->empleado->nombre);
		$Email->setearRemitente($config['email']['from'], '');
		$Email->titulo ='Aviso de vencimiento de la presentación de la DDJJPI';
		$vars_template = [
							'NOMBRE_COMPLETO'	=> $value->empleado->nombre,
							'ANIO'				=> $value->periodo,
							'PRESENTACION'		=>\App\Modelo\Anticorrupcion::$TIPO_DJ[$presentacion]['nombre'],
							'PLAZO'				=> $plazo
						]; 
		$html = new Vista(VISTAS_PATH.'/cron/presentacion_general.php',compact('vars_template'));
		$Email->cuerpo =  "$html";
		$Email->enviar();
		$datos	= [
				'modelo'	=> 'Anticorrupcion',
				'msj'	 	=> 'Exito en el envio.',
				'destinatario' => $value->empleado->email
			];

		if($Email->ErrorInfo) {
				$this->debug($datos);
				$datos['msj'] = 'Error en el envio. '.$Email->ErrorInfo[2];
		}
		Logger::event('cron_notificaciones_enviar_email', $datos);	
	}

	private function notificar(ColaTarea $tarea=null, $file_nombre=null){
		if(!is_numeric($tarea->id_usuario) && empty($tarea->id_usuario)){
			return false;
		}
		$usuario = Modelo\Usuario::obtener($tarea->id_usuario);
		$config	= \FMT\Configuracion::instancia();

		try {
			$email				= new \FMT\Mailer();
			$email->servidor	= '';
			$email->puerto		= '';
			$email->usuario		= '';
			$email->clave		= '';
			$email->SMTPAuth	= '';
			$email->SMTPAutoTLS	= '';
			$email->CharSet		= 'utf8';
			$email->isHTML();
			$email->agregarDestinatario($usuario->email, $usuario->nombre.' '.$usuario->apellido);
			$email->setearRemitente($config['email']['from'], $config['email']['name']);
			$email->titulo		= 'SIGARHU - PDF Informe Historial Anticorrupción disponible"';
			$email->cuerpo		= (new Vista(VISTAS_PATH.'/cron/notificacion_historico_anticorrupcion.php', [
				'usuario'		=> $usuario,
				'file_nombre'	=> $file_nombre,
			]))->render();
			$email->enviar();
			if($email->ErrorInfo){
				$this->debug($email->ErrorInfo, false, 'consola-notificar');
			}
		} catch (\Exception $e) {
			$this->debug($e, false, 'consola-notificar');
		}
	}

	private function notificar_excel_legajos(ColaTarea $tarea=null, $file_nombre=null){
		if(!is_numeric($tarea->id_usuario) && empty($tarea->id_usuario)){
			return false;
		}
		$usuario = Modelo\Usuario::obtener($tarea->id_usuario);
		$config	= \FMT\Configuracion::instancia();

		try {
			$email				= new \FMT\Mailer();
			$email->servidor	= '';
			$email->puerto		= '';
			$email->usuario		= '';
			$email->clave		= '';
			$email->SMTPAuth	= '';
			$email->SMTPAutoTLS	= '';
			$email->CharSet		= 'utf8';
			$email->isHTML();
			$email->agregarDestinatario($usuario->email, $usuario->nombre.' '.$usuario->apellido);
			$email->setearRemitente($config['email']['from'], $config['email']['name']);
			$email->titulo		= 'SIGARHU - Excel Reporte de Agentes disponible"';
			$email->cuerpo		= (new Vista(VISTAS_PATH.'/cron/notificar_excel_legajos.php', [
				'usuario'		=> $usuario,
				'file_nombre'	=> $file_nombre,
			]))->render();
			$email->enviar();
			if($email->ErrorInfo){
				$this->debug($email->ErrorInfo, false, 'consola-notificar');
			}
		} catch (\Exception $e) {
			$this->debug($e, false, 'consola-notificar');
		}
	}

/**
 * --------------------------------------------------------------------------------------------------------
 * -- CUALQUIER MODIFICACION QUE SE HAGA ACA, DEBE SER REPLICADA EN Legajos::accion_anticorrupcion_pdf() --
 * --------------------------------------------------------------------------------------------------------
 */
	public function accion_anticorrupcion_pdf() {
		// Sin esto mata el servidor --- Mala idea no ponerlo ;) ;)
		// Hace referencia a si mismo
		if (static::ClonadoProcessAlive('anticorrupcion_pdf')) {
			exit;
		}
		$params		= $this->getParams();
		// Obtener el registro de la cola de tareas
		$tarea		= ColaTarea::obtenerPorAccion('anticorrupcion_pdf', $params);
		// Indicar que el proceso se inicio
		ColaTarea::tareaEjecutando($tarea);
		
		
		$PATH			= Modelo\Documento::getDirectorioTMP();
		$hash_archivo	= base64_encode('Informe-Historico-de-Anticorrupcion-'.date('d-m-Y_H:i:s'));
		$file_nombre	= $hash_archivo.'.pdf';

		$resultado	= Modelo\Anticorrupcion::exportar($params['params']);
		$fecha		= \DateTime::createFromFormat('d/m/Y', gmdate('d/m/Y'))->format('d/m/Y');
		$logueado	= Modelo\Usuario::obtener($tarea->id_usuario);
		$usuario	= $logueado->nombre.' '.$logueado->apellido;
		$titulo		= $params['titulo'];

		if(empty($resultado)) {
			ColaTarea::tareaFinalizar($tarea);
			$this->matarProceso();
			exit;
		}
		$modo_asincrono	= true;
		(new Vista(VISTAS_PATH.'/generar_pdf.php' ,compact(
			'resultado',
			'titulo',
			'fecha',
			'usuario',
			'modo_asincrono',
			'file_nombre'
		)))->pre_render();

		if(file_exists($PATH.'/'.$file_nombre)){
			ColaTarea::tareaFinalizar($tarea);
			$this->notificar($tarea, $hash_archivo);
			$this->matarProceso();
		}
	}

	public function accion_exportar_legajos_excel() {
		// Sin esto mata el servidor --- Mala idea no ponerlo ;) ;)
		// Hace referencia a si mismo
		if (static::ClonadoProcessAlive('exportar_legajos_excel')) {
			exit;
		}
		$params		= $this->getParams();
		// Obtener el registro de la cola de tareas
		$tarea		= ColaTarea::obtenerPorAccion('exportar_legajos_excel', $params);
		// Indicar que el proceso se inicio
		ColaTarea::tareaEjecutando($tarea);
		
		
		$PATH			= Modelo\Documento::getDirectorioTMP();
		$hash_archivo	= base64_encode('Informe-Historico-de-Anticorrupcion-'.date('d-m-Y_H:i:s'));
		$file_nombre	= $hash_archivo.'.pdf';
		
		$PATH			= Modelo\Documento::getDirectorioTMP();
		$hash_archivo	= base64_encode($params['titulo']);
		$file_nombre	= $hash_archivo.'.xlsx';

		$resultado	= Modelo\Empleado::exportarExcel($params['params'], $params['extras']);
		
		foreach ($params['params']
		 as $value) {
			if($value !='dependencia')
				$titulos[] = ucwords(str_replace('_', ' ', $value));
		}
		foreach ($resultado as $key => &$value) {
			if(array_key_exists('turno', $value)) {
				$value['turno'] = (empty($value['turno']))? 'S/D' : $value['turno'];
			}

    		$campos_fechas = [
				'fecha_nac',
				'fecha_otorgamiento_grado',
				'fecha_inicio_lic_esp',
				'fecha_fin_lic_especiales',
				'fecha_ven_cud',
				'fecha_ven_credencial',
				'fecha_obtencion_result',
				'fecha_ingreso_mtr',
				'fecha_otorgamiento',
				'fecha_vigencia_mandato',
				'fecha_designacion',
				'fecha_publicacion_designacion',
				'fecha_aceptacion_renuncia',
				'fecha_presentacion',
				'fecha_desde',
				'fecha_hasta',
				'fecha_baja',
			];
			foreach ($campos_fechas as $campos_fecha) {
				if(isset($value[$campos_fecha]) && in_array($campos_fecha, $params['params'])){
					$aux = \DateTime::createFromFormat('Y-m-d H:i:s', $value[$campos_fecha].' 0:00:00');
					if(empty($aux)){
						$aux	= \DateTime::createFromFormat('Y-m-d H:i:s', $value[$campos_fecha]);
					}
					$value[$campos_fecha]	= ($aux) ? $aux->format('d/m/Y') : 'S/D';
				} elseif(isset($value[$campos_fecha]) && !in_array($campos_fecha, $params['params'])){
					unset($value[$campos_fecha]);
				}
			}

			if(array_key_exists('horarios', $value)){
				if(!is_null($value['horarios'])) {
					$horarios = json_decode($value['horarios']);
					$dias = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
					$value['horarios'] = [];
					foreach ($horarios as $dia => $hora) {
						if(!empty($hora[0]) && !empty($hora[1])){
							$value['horarios'][] = $dias[$dia].':'.$hora[0].'-'.$hora[1];
						}else{
							$value['horarios'][] = $dias[$dia].':S/D';
						}
					}
					$value['horarios'] = '['.implode('],[', $value['horarios']).']';
				} else {
					$value['horarios'] = 'S/D';
				}				 
			}

			if(array_key_exists('fecha_otorgamiento_grado', $value)){
				$value['fecha_otorgamiento_grado'] = !empty($value['fecha_otorgamiento_grado'])
					? $value['fecha_otorgamiento_grado']
					: 'S/D';
			}

			if(array_key_exists('telefonos', $value)){
				$value['telefonos'] = ($value['telefonos']) ? $value['telefonos'] : 'S/D';
			}
        }
		
		$count		= count($resultado) +2;
		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$workSheet = $spreadsheet->getActiveSheet();
		$workSheet->setTitle('Reporte');
		$workSheet->fromArray(array_values($titulos), NULL, 'A1');
		$workSheet->fromArray(array_values($resultado), NULL, 'A2');
		$workSheet->fromArray(["{$count} registros totales."], NULL, "A{$count}");

		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		$writer->save($PATH.'/'.$file_nombre);
		
		
		if(file_exists($PATH.'/'.$file_nombre)){
			ColaTarea::tareaFinalizar($tarea);
			$this->notificar_excel_legajos($tarea, $hash_archivo);
			$this->matarProceso();
		}
	}

	public function accion_simular_promocion_grado(){
		if (static::ClonadoProcessAlive('simular_promocion_grado')) {
			exit;
		}
		$params		= $this->getParams();
		$tarea		= ColaTarea::obtenerPorAccion('simular_promocion_grado', $params);
		if(!empty($tarea)){
			ColaTarea::tareaEjecutando($tarea);
		}

		try {
			Modelo\SimuladorPromocionGrado::runSimulacionGrados();
		} catch (\Exception $e) {
			var_export($e);
		}

		if(!empty($tarea)){
			ColaTarea::tareaFinalizar($tarea);
			$this->matarProceso();
			exit;
		}
	}

	public function accion_actualizar_designaciones_transitorias(){
		if (static::ClonadoProcessAlive('actualizar_designaciones_transitorias')) {
			exit;
		}

		$cnx    = new Conexiones();

		// Este obtener solo se usa para inicializar la clase modelo y setear el id_usuario para las consultas SQL
		Modelo\Designacion_transitoria::obtener(null);

		$sql    = <<<SQL
	SELECT 
		emp.id, 
		esca.id_situacion_revista, 
		anti.fecha_publicacion_designacion
	FROM
		empleados emp
			INNER JOIN
		empleado_escalafon esca ON (esca.id_empleado = emp.id
			AND esca.fecha_fin IS NULL)
			INNER JOIN
		anticorrupcion anti ON (anti.id_empleado = emp.id
			AND anti.borrado = 0
			AND anti.fecha_publicacion_designacion IS NOT NULL
			AND anti.fecha_publicacion_designacion != '0000-00-00'
			AND anti.fecha_aceptacion_renuncia IS NULL)
			LEFT JOIN 
		designacion_transitoria des_tran ON (des_tran.id_empleado = emp.id AND des_tran.borrado = 0)
	WHERE
		esca.id_situacion_revista IN (:situaciones) AND des_tran.id_empleado IS NULL
SQL;

		$resp   = $cnx->consulta(Conexiones::SELECT, $sql, [
			':situaciones'  => array_keys(Modelo\Designacion_transitoria::$SR_DESIGNACION_TRANSITORIA)
		]);

		if(empty($resp) || empty($resp[0])){
			return false;
		}

		foreach ($resp as $data) {
			$campos = [
				'id_empleado',
				'fecha_desde',
				'fecha_hasta',
				'tipo',
			];
			$fecha_desde    = \DateTime::createFromFormat('Y-m-d H:i:s.u', $data['fecha_publicacion_designacion'].' 0:00:00.000000');
			$fecha_hasta    = \DateTime::createFromFormat('Y-m-d H:i:s.u', \FMT\Informacion_fecha::dias_habiles_hasta_fecha($fecha_desde, 180).' 0:00:00.000000');
			if(empty($fecha_desde) || empty($fecha_hasta)){
				continue;
			}
			$sql_params = [
				':id_empleado' => $data['id'],
				':fecha_desde' => $fecha_desde->format('Y-m-d'),
				':fecha_hasta' => $fecha_hasta->format('Y-m-d'),
				':tipo'        => Modelo\Designacion_transitoria::TRANSITORIA,
			];
			$sql	= 'INSERT INTO designacion_transitoria('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';

			$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
			if($res !== false){
				$this->id	= $res;
				$datos = (array) $this;
				$datos['modelo'] = 'Designacion_transitoria';
				Logger::event('alta', $datos);
			}
		}
		return true;
	}

/**
 * Trae las locaciones de la api, por cada una de ellas, busca en la tabla ubicaciones_api, si existe, y si tiene id_ubicacion sigarhu asociado
 * si no tiene id asociado inserta en ubicacion_edificios todos los datos, si no, actualiza todos los datos de la locación.
 *
 * @return void
 */
    public function accion_actualizar_locacion(){
		
		$locaciones = Modelo\LocacionesApi::getListadoLocaciones();
    if(!empty($locaciones)){
     Modelo\UbicacionEdificio::borradoInicialDeLocaciones(); //borro todas la Ubicaciones para luego activa las que corresponden.
    }
	 	
	 	foreach ($locaciones as $key => $locacion) {
	        
	        $resp = Modelo\UbicacionEdificio::buscarLocacionApi($locacion['id_locacion']);
	        
          if(!empty($resp)){ //viene una fila de resultado  id_ubicacion_api - id_ubicacion 
	        	if(!empty($resp['id_ubicacion'])){
	        		Modelo\UbicacionEdificio::actualizarLocacion($locacion, $resp['id_ubicacion']);
	        	}else{
	        		Modelo\UbicacionEdificio::insertarLocacion($locacion);
	        	}

	        }else{
	        	Modelo\UbicacionEdificio::insertarLocacion($locacion);
	        }

		}
	}	
}