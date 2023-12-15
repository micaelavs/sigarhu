<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class Perfil extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_empleado;
/** @var string */
	public $denominacion_funcion;
/** @var string */
	public $denominacion_puesto;
/** @var string */
	public $objetivo_gral;
/** @var string */
	public $objetivo_especifico;
/** @var string */
	public $actividad;
/** @var string */
	public $resultados_parciales_finales;
/** @var string */
	public $estandares;
/** @var \DateTime */
	public $fecha_obtencion_result;
/** @var int */
	public $familia_de_puestos;
/** @var int */
	public $nivel_destreza;
/** @var int */
	public $nombre_puesto;
/** @var int */
	public $puesto_supervisa;
/** @var int */	
	public $nivel_complejidad;
/** @var array*/
	public $evaluacion;
/** @var datetime */
	public $fecha_desde;
/** @var datetime */
	public $fecha_hasta;
	
/** Niveles de Destrezas */
	const INICIAL = 1;
	const MEDIO  = 2;
	const AVANZADO = 3;

	public static $NIVELES_DESTREZA = [
		self::INICIAL 	=> 	['id' => self::INICIAL, 	'nombre' => 'Inicial', 		'borrado' => 0],
		self::MEDIO 	=> 	['id' => self::MEDIO, 		'nombre' => 'Medio', 		'borrado' => 0],
		self::AVANZADO 	=> 	['id' => self::AVANZADO, 	'nombre' => 'Avanzado', 	'borrado' => 0]
	];

/** Niveles de Complejidad */
	const MUY_COMPLEJO 	= 1;
	const COMPLEJO 		= 2;
	const SEMI_COMPLEJO = 3;
	const RUTINARIO 	= 4;
	
	public static $NIVELES_COMPLEJIDAD = [
		self::MUY_COMPLEJO 	=> ['id' => self::MUY_COMPLEJO, 	'nombre' => 'Muy Complejo', 	'borrado' => 0],
		self::COMPLEJO 		=> ['id' => self::COMPLEJO, 		'nombre' => 'Complejo', 		'borrado' => 0],
		self::SEMI_COMPLEJO => ['id' => self::SEMI_COMPLEJO, 	'nombre' => 'Semi-Complejo', 	'borrado' => 0],
		self::RUTINARIO 	=> ['id' => self::RUTINARIO, 		'nombre' => 'Rutinario', 		'borrado' => 0]
	];
/** Niveles de Puesto Supervisa */
	const _EL_PUESTO_NO_SUPERVISA = 1;
	const _1_A_5_PERSONAS = 2;
	const _6_A_15_PERSONAS = 3;
	const _16_A_30_PERSONAS = 4;
	const _MAS_DE_30_PERSONAS = 5;

	public static $NIVELES_PUESTO_SUPERVISA = [		
		self::_EL_PUESTO_NO_SUPERVISA => ['id' => self::_EL_PUESTO_NO_SUPERVISA, 	'nombre' => 'El puesto no supervisa', 	'borrado' => 0],
		self::_1_A_5_PERSONAS => ['id' => self::_1_A_5_PERSONAS, 		'nombre' => '1 a 5 personas', 		'borrado' => 0],
		self::_6_A_15_PERSONAS => ['id' => self::_6_A_15_PERSONAS, 	'nombre' => '6 a 15 personas', 	'borrado' => 0],
		self::_16_A_30_PERSONAS => ['id' => self::_16_A_30_PERSONAS, 		'nombre' => '16 a 30 personas', 		'borrado' => 0],
		self::_MAS_DE_30_PERSONAS => ['id' => self::_MAS_DE_30_PERSONAS, 		'nombre' => 'Más de 30 personas', 		'borrado' => 0]
	];

	
	/**
	 * Obtiene los valores de los array parametricos.
	 * E.J.: Perfil::getParam('NIVELES_DESTREZA');
	*/
	static public function getParam($attr=null){
		if($attr === null || empty(static::${$attr})){
			return [];
		}
		return static::${$attr};
	}

	static public function obtener($id_empleado=null){
		$conexion = new Conexiones();

		if($id_empleado===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id_empleado'	=> $id_empleado,
		];
		$campos	= '
			id,
			id_empleado,
			denominacion_funcion,
			denominacion_puesto,
			objetivo_gral,
			objetivo_especifico,
			estandares,
			fecha_obtencion_result,
			familia_de_puestos,
			nivel_destreza,
			nombre_puesto,
			puesto_supervisa,
			nivel_complejidad
		';
		$sql	= <<<SQL
			SELECT {$campos}
			FROM empleado_perfil
			WHERE id_empleado = :id_empleado AND ISNULL(fecha_hasta)
			ORDER BY id DESC
			LIMIT 1
SQL;
		$res	= $conexion->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}

	static public function obtenerxid($id=null){

		$obj	= new static;
		$conexion = new Conexiones();

		if($id===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id'	=> $id,
		];
		$campos	= '
			id,
			id_empleado,
			denominacion_funcion,
			denominacion_puesto,
			objetivo_gral,
			objetivo_especifico,
			estandares,
			fecha_obtencion_result,
			familia_de_puestos,
			nivel_destreza,
			nombre_puesto,
			puesto_supervisa,
			nivel_complejidad
		';
		$sql	= <<<SQL
			SELECT {$campos}
			FROM empleado_perfil
			WHERE id = :id AND ISNULL(fecha_hasta)
SQL;
		$res	= $conexion->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}

	static public function listar() {
		$campos	= '
			id_empleado,
			denominacion_funcion,
			denominacion_puesto,
			objetivo_gral,
			objetivo_especifico,
			estandares,
			fecha_obtencion_result,
			nivel_destreza,
			nombre_puesto,
			puesto_supervisa,
			nivel_complejidad
		';
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM empleado_perfil
			WHERE ISNULL(fecha_hasta)
			ORDER BY id DESC
SQL;
		$resp	= (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql);
		if(empty($resp)) { return []; }
		foreach ($resp as &$value) {
			$value	= static::arrayToObject($value);
		}
		return $resp;
	}

	public function alta(){
		$sql_params = [];
		$conexion =  new Conexiones();

		$campos	= [
			"id_empleado",
			"denominacion_funcion",
			"denominacion_puesto",
			"objetivo_gral",
			"objetivo_especifico",
			"estandares",
			"fecha_obtencion_result",
			"nivel_destreza",
			"nombre_puesto",
			"puesto_supervisa",
			"nivel_complejidad",
			"familia_de_puestos",
			'fecha_desde',
		];

		foreach ($campos as $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};	
		}

		if($sql_params[':fecha_obtencion_result'] instanceof \DateTime){
			$sql_params[':fecha_obtencion_result']	= $sql_params[':fecha_obtencion_result']->format('Y-m-d');
		} else {
			$sql_params[':fecha_obtencion_result']	= null;
		}

		if($sql_params[':fecha_desde'] instanceof \DateTime){
			$sql_params[':fecha_desde']	= $sql_params[':fecha_desde']->format('Y-m-d');
		} else {
			$sql_params[':fecha_desde']	= (new \DateTime())->format('Y-m-d');
		}

		$sql	= 'INSERT INTO empleado_perfil('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= $conexion->consulta(Conexiones::INSERT, $sql, $sql_params);

		if ($res !== false) {
			$this->id = $res;
			$datos = (array)$this;
			$datos['modelo'] = 'Empleado_Perfil';
			Logger::event('alta', $datos);			
		}

		$this->tipoAccionItemActividades();
		$this->tipoAccionItemResultados();

		return $res;
	}

	public function baja(){
		$mbd = new Conexiones;
		$sql_params	= [
			':id'	=> $this->id,
			':fecha_hasta' =>  date('Y-m-d')
		];
		$sql = <<<SQL
		UPDATE empleado_perfil SET  fecha_hasta = :fecha_hasta WHERE id = :id
SQL;
		$res = $mbd->consulta(Conexiones::SELECT, $sql, $sql_params);
		if ($res !== false) {
			$datos = (array) $this;
			$datos['modelo'] = 'Perfil';
			Logger::event('baja', $datos);
		} else {
			$datos['error_db'] = $mbd->errorInfo;
			Logger::event("error_baja",$datos);
		}
		return $res;
	}

	public function modificacion(){
		$campos	= [
			"id_empleado"			=> 'id_empleado = :id_empleado',
			"denominacion_funcion"	=> 'denominacion_funcion = :denominacion_funcion',
			"denominacion_puesto"	=> 'denominacion_puesto = :denominacion_puesto',
			"objetivo_gral"			=> 'objetivo_gral = :objetivo_gral',
			"objetivo_especifico"	=> 'objetivo_especifico = :objetivo_especifico',
			"estandares"			=> 'estandares = :estandares',
			"nivel_destreza"		=> 'nivel_destreza = :nivel_destreza',
			"nombre_puesto"			=> 'nombre_puesto = :nombre_puesto',
			"puesto_supervisa"		=> 'puesto_supervisa = :puesto_supervisa',
			"nivel_complejidad"		=> 'nivel_complejidad = :nivel_complejidad',
			"familia_de_puestos"	=> 'familia_de_puestos = :familia_de_puestos',
			"fecha_obtencion_result"=> 'fecha_obtencion_result = :fecha_obtencion_result',
			"fecha_desde"			=> 'fecha_desde = :fecha_desde',
		];
		$sql_params	= [
			':id'	=> $this->id,
		];


		$actividades= $this->tipoAccionItemActividades();
		$resultados	= $this->tipoAccionItemResultados();
		$actividades = $this->actividad;		
		// $resultado_par_final = $this->resultados_parciales_finales;

		if(empty(($this->fecha_desde))){
			$this->fecha_desde = new \DateTime();
		}

		foreach ($campos as $campo => $query) {
			$sql_params[':'.$campo]	= $this->{$campo};
		}

		if($sql_params[':fecha_desde'] instanceof \DateTime){
			$sql_params[':fecha_desde']	= $sql_params[':fecha_desde']->format('Y-m-d');
		}
		if($sql_params[':fecha_obtencion_result'] instanceof \DateTime){
			$sql_params[':fecha_obtencion_result']	= $sql_params[':fecha_obtencion_result']->format('Y-m-d');
		} else {
			$sql_params[':fecha_obtencion_result']	= null;
		}

		$cnx	= new Conexiones();
		$sql	= 'UPDATE empleado_perfil SET '.implode(', ',$campos) .' WHERE id = :id';
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);

		if($res !== false){
			if($actividades){
				$res = ['perfil' => $res, 'actividades'=> $actividades];
			}
			if($resultados){
				$res = ['perfil' => $res, 'resultados' => $resultados];
			}

			$datos = (array) $this;
			$datos['modelo'] = 'Perfil';
			Logger::event('modificacion', $datos);
		}
		return $res;
	}

	public function validar() {		
		$reglas		= [
			'id_empleado' => ['empleado' => function($val){
				return (is_int($val));
			}],
			'denominacion_puesto' 				=> ['numeric'],
			'denominacion_funcion' 				=> ['numeric'],
			'actividad' 						=> [ 'is_array' => function($val){
				if(!empty($val)){
					foreach($val as $item){
						if($item === '')
						return false;
					}
					return true;					
				}
				
			}],
			'resultados_parciales_finales' 		=> [ 'is_array' => function($input){
				if(!empty($input)){
					foreach($input as $item){						
						if($item === ''){
							return false;
						}
					}					
				}
				return true;
				
			}],
			'estandares' 						=> ['texto'],
			'fecha_obtencion_result' 			=> ['fecha']
		];
		$nombres	= [
			'id_empleado' 					=> 'CONTRATO',
			'denominacion_puesto' 			=> 'DENOMINACIÓN DELPUESTO',
			'denominacion_funcion' 			=> 'DENOMINACIÓN DE LA FUNCIÓN',
			'actividad' 					=> 'ACTIVIDADES',
			'resultados_parciales_finales'	=> 'RESULTADOS PARCIALES/FINALES',
			'estandares'					=> 'ESTANDARES',
			'fecha_obtencion_result'		=> 'FECHA DE OBTENCIÓN DE RESULTADOS'
		];

		
		$validacion	= Validator::validate((array)$this, $reglas, $nombres);
		$validacion->customErrors([
			"is_array"      => "El campo <b> :attribute </b> es obligatorio y sus campos deben estar completos.",
			"empleado"		=> "El puesto de perfil no está asociado a ningun <b> :attribute </b>."
			]);
			
			
			if ($validacion->isSuccess()) {
				return true;
			}
			$this->errores = $validacion->getErrors();
		return false;
	}

	static public function arrayToObject($res = []) {
		$campos	= [
			'id' => 'int',
			'id_empleado' => 'int',
			'denominacion_funcion' => 'int',
			'denominacion_puesto' => 'int',
			'objetivo_gral' => 'string',
			'objetivo_especifico' => 'string',
			'estandares' => 'string',
			'fecha_obtencion_result' => 'date',
			'familia_de_puestos' => 'int',
			'nivel_destreza' => 'int',
			'nombre_puesto' => 'int',
			'puesto_supervisa' => 'int',
			'nivel_complejidad' => 'int',
			'fecha_desde' => 'date',
			'fecha_hasta' => 'date',
		];

		$obj = parent::arrayToObject($res, $campos);
		$obj->actividad 					= $obj->obtenerActividadTarea();
		$obj->resultados_parciales_finales	= $obj->obtenerResultadoParcialFinal();
		return $obj;
	}

	public static function listarFamiliaPuestos(){
		$data = [];
		$campos	= 'id, nombre, borrado';
		$sql = 
<<<SQL
	SELECT {$campos} FROM
	familia_puestos
SQL;

		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql);
		if($res){
			foreach($res as $campo){
				$data [$campo['id']] = [
					'id' => $campo['id'],
					'nombre' => $campo['nombre'],
					'borrado' => $campo['borrado']
				];
			}
		}		
		return $data;
	}

	public static function listarDenominacionDelPuesto(){
		$data = [];
		$campos	= 
			'id,
			nombre,
			borrado'
		;

		$sql = 
<<<SQL
	SELECT {$campos} FROM
	denominacion_puesto
SQL;

		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql);
		if($res){
			foreach($res as $campo){
				$data [$campo['id']] = [
					'id' => $campo['id'],
					'nombre' => $campo['nombre'],
					'borrado' => $campo['borrado']
				];
			}
		}		
		return $data;
	}

	public static function listarDenominacionFuncion(){
		$data = [];
		$campos	= implode(',', [
			'id',
			'nombre',
			'borrado'
		]);

		$sql = 
<<<SQL
	SELECT {$campos} FROM
	denominacion_funcion
	ORDER BY nombre;
SQL;

		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql);
		if($res){
			foreach($res as $campo){
				$data [$campo['id']] = [
					'id' => $campo['id'],
					'nombre' => $campo['nombre'],
					'borrado' => $campo['borrado']
				];
			}
		}		
		return $data;
	}

	public static function listarNombrePuestos(){
		$data = [];
		$campos	= implode(',', [
			'id',
			'nombre',
			'borrado'
		]);

		$sql = 
<<<SQL
	SELECT {$campos} FROM
	puestos
SQL;

		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql);
		if($res){
			foreach($res as $campo){
				$data [ $campo['id']] = [
					'id' => $campo['id'],
					'nombre' => $campo['nombre'],
					'borrado' => $campo['borrado']
				];
			}
		}		
		return $data;
	}

	private function modificarResultadoParcialFinal($id, $nombre){
		$conexion = new Conexiones();
		$sql = <<<SQL
		UPDATE perfil_resultado_parc_final
		SET			
			nombre = :nombre		
		WHERE id = :id; 
SQL;

		$resultado = $conexion->consulta(Conexiones::SELECT, $sql, [
				':id' => 		$id,
				':nombre' => 	$nombre 
			]);

	}

	public function obtenerResultadoParcialFinal(){				
		$conexion 	=  new Conexiones();
		$return = [];
		$sql = 
<<<SQL
		SELECT 	id, nombre
		FROM 	perfil_resultado_parc_final
		WHERE 	id_perfil = :id_perfil  AND borrado = 0
SQL;

		$parametros = [':id_perfil' => $this->id];

		$resultado = $conexion->consulta(Conexiones::SELECT, $sql, $parametros);

		if($resultado){
			foreach($resultado as $dato){
				$respuesta 	= new \stdClass();
				$respuesta->id 		= $dato['id'];
				$respuesta->nombre 	= $dato['nombre'];
				$return[$respuesta->id] = $respuesta;
			}
		} else {
			$respuesta = new \stdClass();
			$respuesta->id 		= '';
			$respuesta->nombre 	= '';
			$return[] = $respuesta;
		}
		return $return;
	}

	/**
	 * obtenerActividadTarea	 
	 *
	 * @return stdClass tiene dos funciones, lista las actividades/tareas
	 * y por otro lado modifica el objeto por referencia
	 */
	private function obtenerActividadTarea(){
		
		$conexion =  new Conexiones();
		$return = []		;

		$sql = 
<<<SQL
		SELECT 	id, nombre
		FROM 	perfil_actividades
		WHERE 	id_perfil = :id_perfil AND borrado = 0
SQL;

		$parametros = [':id_perfil' => $this->id];

		$resultado = $conexion->consulta(Conexiones::SELECT, $sql, $parametros);

		if($resultado){
			foreach($resultado as $dato){
				$respuesta = new \stdClass();
				$respuesta->id 		= $dato['id'];
				$respuesta->nombre 	= $dato['nombre'];
				$return[$dato['id']] = $respuesta;
			}
		} else {
			$respuesta = new \stdClass();
			$respuesta->id 		= '';
			$respuesta->nombre 	= '';
			$return[] = $respuesta;
		}
		return $return;
	}

	
	public function borrarItemResultado($id){
		$conexion = new Conexiones();
		$sql =
<<<SQL
		UPDATE perfil_resultado_parc_final SET borrado = 1
		WHERE id = :id
SQL;

		$resultado = $conexion->consulta(
			Conexiones::UPDATE,
			$sql,
			[':id' => $id ]
		);

		return $resultado;	

	}


	public function borrarItemActividad($id){
		$conexion = new Conexiones();
		$sql =
<<<SQL
		UPDATE perfil_actividades SET borrado = 1
		WHERE id = :id
SQL;

		$resultado = $conexion->consulta(
			Conexiones::UPDATE,
			$sql,
			[':id' => $id ]
		);

		return $resultado;	

	}

	public  function actualizarItemResultado($id, $nombre){
		$conexion = new Conexiones();
		$sql =
<<<SQL
		UPDATE perfil_resultado_parc_final SET nombre = :nombre
		WHERE id = :id
SQL;

		$resultado = $conexion->consulta(
			Conexiones::UPDATE,
			$sql,
			[
				':id' => 		$id,
				':nombre' => 	$nombre 
			]
		);

		return $resultado;	
	}

		
		public function actualizarItemActividad($id,$nombre){
			$conexion = new Conexiones();
			$sql =
<<<SQL
			UPDATE perfil_actividades SET nombre = :nombre
			WHERE id = :id
SQL;
	
			$resultado = $conexion->consulta(
				Conexiones::UPDATE,
				$sql,
				[
					':id' => $id,
					':nombre' => $nombre 
				]
			);
	
			return $resultado;	
	
		}

		public function altaItemResultado($nuevo){
			$conexion = new Conexiones();
			$sql = <<<SQL
			INSERT INTO perfil_resultado_parc_final (nombre, id_perfil) VALUES (:resultado, :id_perfil)
SQL;
			$param = [
				':resultado' 	=> $nuevo,
				':id_perfil'	=> $this->id
			];

			return $conexion->consulta(Conexiones::INSERT, $sql, $param);
		}

		
/**
 * altaItemActividades
 * Agrega item de actividades/tareas al perfil
 * @return bool
 */
		public function altaItemActividades($nuevo){			
			$conexion = new Conexiones();
			$sql = <<<SQL
			INSERT INTO perfil_actividades (nombre, id_perfil) VALUES (:actividad, :id_perfil)
SQL;
			$param = [
				':actividad' 	=> $nuevo,
				':id_perfil'	=> $this->id
			];
			$conexion->consulta(Conexiones::INSERT, $sql, $param);
			return true;
		}

			
		private function tipoAccionItemActividades(){
			$resultado = false;
			$actuales = $this->obtenerActividadTarea();
			foreach($this->actividad as $data){
				if(!isset($actuales[0]) && array_key_exists($data->id, $actuales)){
					$modificados[] =$data->id;
					if($data->nombre != $actuales[$data->id]->nombre) {
						$resultado = $this->actualizarItemActividad($data->id, $data->nombre);
					}	
				} else {
					$resultado = $this->altaItemActividades($data->nombre);
				}
			}
			if(isset($modificados)) {
				foreach ($actuales as $actual) {
					if(!in_array($actual->id, $modificados)) {
						$resultado = $this->borrarItemActividad($actual->id);
					}
				}
			}
			$this->actividad = $this->obtenerActividadTarea();

			return $resultado;
		}

		private function tipoAccionItemResultados(){
			$resultado = false;
			$actuales = $this->obtenerResultadoParcialFinal();
			foreach($this->resultados_parciales_finales as $data){
				if(!isset($actuales[0]) && array_key_exists($data->id, $actuales)){
					$modificados[] =$data->id;
					if($data->nombre != $actuales[$data->id]->nombre) {
						$resultado = $this->actualizarItemResultado($data->id, $data->nombre);
					}	
				} else {
					$resultado = $this->altaItemResultado($data->nombre);
				}
			}
			if(isset($modificados)) {
				foreach ($actuales as $actual) {
					if(!in_array($actual->id, $modificados)) {
						$resultado = $this->borrarItemResultado($actual->id);
					}
				}
			}
			$this->resultados_parciales_finales = $this->obtenerResultadoParcialFinal();
			return $resultado;

		}
}