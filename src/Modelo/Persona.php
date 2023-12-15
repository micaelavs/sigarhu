<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class Persona extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $tipo_documento;
/** @var int */
	public $documento;
/** @var string */
	public $nombre;
/** @var string */
	public $apellido;
/** @var Date */
	public $fecha_nac;
/** @var int */
	public $genero;
/** @var string */
	public $nacionalidad;
/** @var int */
	public $estado_civil;
/** @var string */
	public $email;
/** @var string */
	public $discapacidad;	
/** @var object */
	public $domicilio; 
/** @var array [PersonaOtroConocimiento::] */
	public $otros_conocimientos = array();
/** @var array [PersonaExperienciaLaboral::] */
	public $experiencia_laboral = array();
/** @var array [PersonaTitulo::] */
	public $titulos				= array();
/** @var array [PersonaTelefono::] */
	public $telefonos			= array();
	/** @var string */
	public $foto_persona; 

	public $errores_sql	= [];

	const DNI	= 1;
	const DU	= 2;
	static protected $TIPO_DOCUMENTO	= [
		self::DNI	=> ['id'	=> self::DNI, 'nombre'	=> 'DNI', 'borrado' => 0],
		self::DU	=> ['id'	=> self::DU, 'nombre'	=> 'DU', 'borrado' => 0],
	];

	const CASADO		= 1;
	const CONCUBINATO	= 2;
	const SOLTERO		= 3;
	const DIVORCIADO	= 4;
	const VIUDO			= 5;
	const SD			= 0;
	static protected $ESTADO_CIVIL	= [
		self::CASADO		=> ['id'	=> self::CASADO, 'nombre'	=> 'Casada/o', 'borrado' => 0],
		self::CONCUBINATO	=> ['id'	=> self::CONCUBINATO, 'nombre'	=> 'Concubinato', 'borrado' => 0],
		self::SOLTERO		=> ['id'	=> self::SOLTERO, 'nombre'	=> 'Soltera/o', 'borrado' => 0],
		self::DIVORCIADO	=> ['id'	=> self::DIVORCIADO, 'nombre'	=> 'Divorciada/o', 'borrado' => 0],
		self::VIUDO			=> ['id'	=> self::VIUDO, 'nombre'	=> 'Viuda/o', 'borrado' => 0],
		self::SD	=> ['id'	=> self::SD, 'nombre'	=> 'S/D', 'borrado' => 0],
	];

	const FEMENINA	= 1;
	const MASCULINO	= 2;
	const MUJER_TRANS	= 3;
	const HOMBRE_TRANS	= 4;
	const TRAVESTI	= 5;
	const TRANSGENERO	= 6;
	const QUEER	= 7;
	const INTERSEXUAL	= 8;
	const NO_BINARIE	= 9;
	const NO_DECIRLO	= 10;
	const NINGUNA	= 11;

	static protected $GENERO	= [
		self::FEMENINA	=> ['id'	=> self::FEMENINA, 'nombre'	=> 'Mujer', 'borrado' => 0],
		self::MASCULINO	=> ['id'	=> self::MASCULINO, 'nombre'	=> 'Hombre', 'borrado' => 0],
		self::MUJER_TRANS	=> ['id'	=> self::MUJER_TRANS, 'nombre'	=> 'Mujer Trans', 'borrado' => 0],
		self::HOMBRE_TRANS	=> ['id'	=> self::HOMBRE_TRANS, 'nombre'	=> 'Hombre Trans', 'borrado' => 0],
		self::TRAVESTI	=> ['id'	=> self::TRAVESTI, 'nombre'	=> 'Travesti', 'borrado' => 0],
		self::TRANSGENERO	=> ['id'	=> self::TRANSGENERO, 'nombre'	=> 'Trangénero', 'borrado' => 0],
		self::QUEER	=> ['id'	=> self::QUEER, 'nombre'	=> 'Queer', 'borrado' => 0],
		self::INTERSEXUAL	=> ['id'	=> self::INTERSEXUAL, 'nombre'	=> 'Intersexual', 'borrado' => 0],
		self::NO_BINARIE	=> ['id'	=> self::NO_BINARIE, 'nombre'	=> 'No binarie', 'borrado' => 0],
		self::NO_DECIRLO	=> ['id'	=> self::NO_DECIRLO, 'nombre'	=> 'Prefiero no decirlo', 'borrado' => 0],
		self::NINGUNA	=> ['id'	=> self::NINGUNA, 'nombre'	=> 'Ninguna de las anteriores', 'borrado' => 0],
	];

/**
 * Obtiene los valores de los array parametricos.
 * E.J.: Dependencia::getParam('ESTADO_CIVIL');
*/
	static public function getParam($attr=null){
		if($attr === null || empty(static::${$attr})){
			return [];
		}
		return static::${$attr};
	}

	static public function getDiscapacidad(){
		$aux = [];
		$campos	= implode(',', [
			'id',
			'nombre',
			'descripcion',
			'borrado'
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM tipo_discapacidad
			ORDER BY id ASC
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::SELECT, $sql);
		foreach ((array)$res as $value) { $aux[$value['id']] = $value; }
		return $aux;
	}

	static public function obtener($id=null){
		$obj	= new static;
		if($id===null){
			$return	= static::arrayToObject();
			return static::borrarContiene($return);
		}
		$sql_params	= [
			':id'	=> $id,
		];
		$campos	= 'per.' . implode(', per.', [
			'id',
			'tipo_documento',
			'documento',
			'nombre',
			'apellido',
			'fecha_nac',
			'genero',
			'nacionalidad',
			'estado_civil',
			'email',
			'foto_persona',
		]);
		$campos	= $campos . ', p_dis.' . implode(', p_dis.', [
			'id 					AS dis_id',
			'id_tipo_discapacidad 	AS dis_id_tipo_discapacidad',
			'cud 					AS dis_cud',
			'observaciones 			AS dis_observaciones',
			'fecha_vencimiento		AS dis_fecha_vencimiento'
		]);
		$campos	= $campos . ', p_dom.' . implode(', p_dom.', [
			'id				AS dom_id',
			'calle			AS dom_calle',
			'numero			AS dom_numero',
			'piso			AS dom_piso',
			'depto			AS dom_depto',
			'cod_postal		AS dom_cod_postal',
			'id_provincia	AS dom_id_provincia',
			'id_localidad	AS dom_id_localidad',
			'fecha_alta		AS dom_fecha_alta',
			'fecha_baja		AS dom_fecha_baja',
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM personas AS per
				LEFT JOIN persona_discapacidad AS p_dis ON (p_dis.id_persona = per.id AND p_dis.borrado = 0)
				LEFT JOIN persona_domicilio AS p_dom ON (p_dom.id_persona = per.id AND ISNULL(p_dom.fecha_baja))
			WHERE per.id = :id
			ORDER BY per.id DESC, p_dom.id DESC, p_dis.id DESC
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			$return	= static::arrayToObject($res[0]);
			return static::borrarContiene($return);
		}
		$return	= static::arrayToObject();
		return static::borrarContiene($return);
	}

	static public function listar() {
		$campos	= 'per.' . implode(', per.', [
			'id',
			'tipo_documento',
			'documento',
			'nombre',
			'apellido',
			'fecha_nac',
			'genero',
			'nacionalidad',
			'estado_civil',
			'email',
			'foto_persona',
		]);
		$campos	= $campos . ', p_dis.' . implode(', p_dis.', [
			'id 					AS dis_id',
			'id_tipo_discapacidad 	AS dis_id_tipo_discapacidad',
			'cud 					AS dis_cud',
			'observaciones 			AS dis_observaciones',
			'fecha_vencimiento		AS dis_fecha_vencimiento',
			'fecha_vencimiento_cud	AS dis_fecha_vencimiento_cud',
		]);
		$campos	= $campos . ', p_dom.' . implode(', p_dom.', [
			'id				AS dom_id',
			'calle			AS dom_calle',
			'numero			AS dom_numero',
			'piso			AS dom_piso',
			'depto			AS dom_depto',
			'cod_postal		AS dom_cod_postal',
			'id_provincia	AS dom_id_provincia',
			'id_localidad	AS dom_id_localidad',
			'fecha_alta		AS dom_fecha_alta',
			'fecha_baja		AS dom_fecha_baja',
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM personas
				LEFT JOIN persona_discapacidad AS p_dis ON (p_dis.id_persona = per.id AND p_dis.borrado = 0 AND per.borrado = 0)
				LEFT JOIN persona_domicilio AS p_dom ON (p_dom.id_persona = per.id AND per.borrado = 0)
			WHERE per.borrado = 0
			ORDER BY per.id DESC, p_dom.id DESC, p_dis.id DESC
SQL;
		$resp	= (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql);
		if(empty($resp)) {
			static::borrarContiene();
			return [];
		}
		foreach ($resp as &$value) {
			$value	= static::arrayToObject($value);
		}
		return static::borrarContiene($resp);
	}

	public function alta(){
		if(!$this->validar()){
			return false;
		}
		$cnx	= new Conexiones();
		$this->errores_sql	= [];
// personas
		if(empty($this->id)) {
			if($this->foto_persona){
				$this->upload_foto_persona($this->foto_persona);
			}
				
			$campos	= [
				'tipo_documento',
				'documento',
				'nombre',
				'apellido',
				'fecha_nac',
				'genero',
				'nacionalidad',
				'estado_civil',
				'email',
				'foto_persona'
			];
			$sql_params	= [
			];
			foreach ($campos as $campo) {
				$sql_params[':'.$campo]	= $this->{$campo};
			}

			if($this->fecha_nac instanceof \DateTime){
				$sql_params[':fecha_nac']	= $this->fecha_nac->format('Y-m-d H:i:s');
			}

			$sql	= 'INSERT INTO personas('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
			$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
			if($res !== false){
				$this->id		= $res;
				$datos = (array) $this;
				$datos['modelo'] = 'Persona';
				Logger::event('alta', $datos);
			} else {
				$this->errores_sql['personas']	= $cnx->errorInfo[2];
				return false;
			}
		}
// persona_domicilio
		if(empty($this->domicilio->id)) {
			$if_null	= true;
			$campos	= [
				'calle',
				'numero',
				'piso',
				'depto',
				'cod_postal',
				'id_provincia',
				'id_localidad',
				'fecha_alta',
				'fecha_baja',
			];
			$sql_params	= [
				':id_persona'	=> $this->id,
			];
			foreach ($campos as $campo) {
				$sql_params[':'.$campo]	= $this->domicilio->{$campo};
				if(!empty($this->domicilio->{$campo}) && $if_null) {
					$if_null = false;
				}
			}
			$campos[]	= 'id_persona';

			if($this->domicilio->fecha_alta instanceof \DateTime){
				$sql_params[':fecha_alta']	= $this->domicilio->fecha_alta->format('Y-m-d');
			}
			if($this->domicilio->fecha_baja instanceof \DateTime){
				$sql_params[':fecha_baja']	= $this->domicilio->fecha_baja->format('Y-m-d');
			}

		if(!$if_null) {
				$sql	= 'INSERT INTO persona_domicilio('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
				$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
				if($res !== false){
					$this->domicilio->id	= $res;
				} else {
					$this->errores_sql['persona_domicilio']	= $cnx->errorInfo[2];
				}
			}
		}

		return true;
	}

	public function alta_discapacidad() {
		if(empty($this->discapacidad->id)) {
			$sql_params	= [
				':id_persona'			=> $this->id,
				':id_tipo_discapacidad'	=> $this->discapacidad->id_tipo_discapacidad,
				':cud'					=> $this->discapacidad->cud,
				':observaciones'		=> $this->discapacidad->observaciones,
				':fecha_vencimiento'	=> $this->discapacidad->fecha_vencimiento
			];

			if($this->discapacidad->fecha_vencimiento instanceof \DateTime){
				$sql_params[':fecha_vencimiento']	= $this->discapacidad->fecha_vencimiento->format('Y-m-d H:i:s');
			}


			$sql	= <<<SQL
				INSERT INTO persona_discapacidad (
					id_persona,
					id_tipo_discapacidad,
					cud,
					observaciones,
					fecha_vencimiento
				)
				VALUES (
					:id_persona,
					:id_tipo_discapacidad,
					:cud,
					:observaciones,
					:fecha_vencimiento
				);
SQL;
			$cnx	= new Conexiones();	
			$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
			if(is_int($res)){
				$this->discapacidad->id	= $res;
				$datos = (array) $this->discapacidad;
				$datos['modelo'] = 'persona_discapacidad';
				Logger::event('alta', $datos);
			} else {	
				$this->errores_sql['persona_discapacidad']	= $cnx->errorInfo[2];
			}
		}
	return true;		
	}

	public static function getTipoDocumento(){
		return static::$TIPO_DOCUMENTO;
	}
	public static function getEstadoCivil(){
		return static::$ESTADO_CIVIL;
	}
	public static function getGenero(){
		return static::$GENERO;
	}

	public function baja_discapacidad(){
		if(empty($this->dis_id)) {
			return false;
		}
		$sql	= <<<SQL
			UPDATE persona_discapacidad SET borrado = 1 WHERE id = :id
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, [':id' => $this->dis_id]);
		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'Persona';
			if (is_numeric($res) && $res > 0) {
				$flag = true;
			} else {
				$datos['error_db'] = $cnx->errorInfo;
			}
			Logger::event('baja', $datos);
		}
		return $flag;
	}

	public function baja(){
		if(empty($this->id)) {
			return false;
		}
		$sql	= <<<SQL
			UPDATE personas SET borrado = 1 WHERE id = :id
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, [':id' => $this->id]);
		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'Persona';
			if (is_numeric($res) && $res > 0) {
				$flag = true;
			} else {
				$datos['error_db'] = $cnx->errorInfo;
			}
			Logger::event('baja', $datos);
		}
		return $flag;
	}

	public function modificacion(){
		if(!$this->validar() || empty($this->id)){
			return false;
		}
		$cnx	= new Conexiones();
		$this->errores_sql	= [];
// persona_domicilio
		if($this->domicilio->id) {
			$campos	= [
				'id_provincia'  => 'id_provincia = :id_provincia',
				'id_localidad'	=> 'id_localidad = :id_localidad',
				'calle'			=> 'calle = :calle',
				'numero'		=> 'numero = :numero',
				'piso'			=> 'piso = :piso',
				'depto'			=> 'depto = :depto',
				'cod_postal'	=> 'cod_postal = :cod_postal',
				'fecha_alta'	=> 'fecha_alta = :fecha_alta',
				'fecha_baja'	=> 'fecha_baja = :fecha_baja',
			];
			$sql_params	= [
				':calle'		=> $this->domicilio->calle,
				':numero'		=> $this->domicilio->numero,
				':piso'			=> $this->domicilio->piso,
				':depto'		=> $this->domicilio->depto,
				':cod_postal'	=> $this->domicilio->cod_postal,
				':fecha_alta'	=> $this->domicilio->fecha_alta,
				':fecha_baja'	=> $this->domicilio->fecha_baja,
				':id_provincia'	=> $this->domicilio->id_provincia,
				':id_localidad'	=> $this->domicilio->id_localidad,
				':id'			=> $this->domicilio->id,
			];

			if($this->domicilio->fecha_alta instanceof \DateTime){
				$sql_params[':fecha_alta']	= $this->domicilio->fecha_alta->format('Y-m-d');
			}
			if($this->domicilio->fecha_baja instanceof \DateTime){
				$sql_params[':fecha_baja']	= $this->domicilio->fecha_baja->format('Y-m-d');
			}

			$sql	= 'UPDATE persona_domicilio SET '.implode(',', $campos).' WHERE id = :id';
			$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);
			if($res === false){
				$this->errores_sql['persona_domicilio']	= $cnx->errorInfo[2];
			}
		}
// Alta de datos en caso de existir
		if(empty($this->domicilio->id)) {
			$this->alta();
		}
// personas
		if($this->foto_persona){
			$this->upload_foto_persona($this->foto_persona);
		}
		$campos	= [
			'tipo_documento',
			'documento',
			'nombre',
			'apellido',
			'fecha_nac',
			'genero',
			'nacionalidad',
			'estado_civil',
			'email',
			'foto_persona'
		];
		$sql_params	= [
			':id'	=> $this->id,
		];
		foreach ($campos as $key => $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
			unset($campos[$key]);
			$campos[$campo]	= $campo .' = :'.$campo;
		}

		if($this->fecha_nac instanceof \DateTime){
			$sql_params[':fecha_nac']	= $this->fecha_nac->format('Y-m-d H:i:s');
		}

		$sql	= 'UPDATE personas SET '.implode(',', $campos).' WHERE id = :id';
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Persona';
			Logger::event('modificacion', $datos);
		} else {
			$this->errores_sql['persona']	= $cnx->errorInfo[2];
		}
		return $res;
	}


	public function modificacion_discapacidad() {
		if($this->discapacidad->id) {
			$campos	= [
				'id_tipo_discapacidad'	=> 'id_tipo_discapacidad = :id_tipo_discapacidad',
				'cud'					=> 'cud = :cud',
				'observaciones'			=> 'observaciones = :observaciones',
				'fecha_vencimiento'		=> 'fecha_vencimiento = :fecha_vencimiento'
			];
			$sql_params	= [
				':id' 					=> $this->discapacidad->id,
				':id_tipo_discapacidad'	=> $this->discapacidad->id_tipo_discapacidad,
				':cud'					=> $this->discapacidad->cud,
				':observaciones'		=> $this->discapacidad->observaciones,
				':fecha_vencimiento'	=> $this->discapacidad->fecha_vencimiento
			];

			if($this->discapacidad->fecha_vencimiento instanceof \DateTime){
				$sql_params[':fecha_vencimiento']	= $this->discapacidad->fecha_vencimiento->format('Y-m-d H:i:s');
			}

			$sql	= 'UPDATE persona_discapacidad SET '.implode(',', $campos).' WHERE id = :id';
			$cnx	= new Conexiones();
			$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);
			if($res === false){
				$this->errores_sql['persona_discapacidad']	= $cnx->errorInfo[2];
			}
			return true;
		} else {
			$this->alta_discapacidad();
		}
	}

	public function validar() {
		$campos = (array)$this;
		$campos +=['dom_calle'	=> $this->domicilio->calle,'dom_numero'=> $this->domicilio->numero,'dom_fecha_alta' => $this->domicilio->fecha_alta, 'dom_id_provincia' => $this->domicilio->id_provincia , 'dom_id_localidad' => $this->domicilio->id_localidad,
			'foto' => is_array($this->foto_persona) ? $this->foto_persona["name"] : $this->foto_persona ];

		$reglas		= [
			'nombre'			=> ['required', 'texto'],
			'apellido'			=> ['required', 'texto'],
			'email'				=> ['required', 'email'],
			'documento'			=> ['required', 'documento'],
			'tipo_documento'	=> ['required', 'integer'],
			'genero'			=> ['required', 'integer'],
			'estado_civil'		=> ['required', 'integer'],
			'fecha_nac'			=> ['required', 'fecha'],
			'dom_calle' 		=> ['required', 'texto'],
			'dom_numero'		=> ['required', 'numeric'],
			'dom_fecha_alta'	=> ['required', 'fecha'],
			'dom_id_provincia'	=> ['required', 'numeric'],
			'dom_id_localidad'	=> ['required', 'numeric']
		];
		
		if($this->foto_persona){
			$reglas	+= [
				'foto' => ['extension' => function($bin){
					//$ext = pathinfo($input, PATHINFO_EXTENSION);
					$expl = explode(',', $bin);
					if(empty($expl[1])){
						return true;
					}
					$bin64 = $expl[1];
					$extension  = explode(';',explode('/', $expl[0])[1])[0];
					$data       = base64_decode($bin64);

					if (!empty($bin)){
						return  ($extension == 'jpeg');
					}
					return true;
	          	}],
			];
		}

		$nombres	= [
			'nombre'			=> 'Nombre',
			'apellido'			=> 'Apellido',
			'email'				=> 'Email',
			'documento'			=> 'Documento',
			'tipo_documento'	=> 'Tipo Documento',
			'genero'			=> 'Genero',
			'estado_civil'		=> 'Estado Civil',
			'fecha_nac'			=> 'Fecha Nacimiento',
			'dom_calle'			=> 'Calle',
			'dom_numero'		=> 'Numero',
			'dom_fecha_alta'	=> 'Fecha Alta Domicilio',
			'dom_id_provincia'	=> 'Provincia',
			'dom_id_localidad'	=> 'Localidad',
			'foto'				=> 'Foto',
		];
		if(!empty($this->discapacidad->id_tipo_discapacidad)) {
				$campos['id_tipo_discapacidad']  = $this->discapacidad->id_tipo_discapacidad;  
				$campos['cud']  				 = $this->discapacidad->cud;  
				$campos['observaciones']  		 = $this->discapacidad->observaciones;  
				$campos['fecha_vencimiento']  	 = $this->discapacidad->fecha_vencimiento;
				$reglas['id_tipo_discapacidad']  = ['required', 'integer'];
				$reglas['cud'] 				     = ['required', 'alpha_numeric' ];
				$reglas['observaciones'] 	 	 = ['texto'];
				$reglas['fecha_vencimiento']  	 = ['fecha'];
				$nombres['id_tipo_discapacidad'] = 'Tipo de Discapacidad';
				$nombres['cud'] 	 			 = 'Certificado Único de Discapacidad';
				$nombres['observaciones'] 	 	 = 'Observaciones';
				$nombres['fecha_vencimiento'] 	 = 'Fecha Vencimiento';
		}

		$validator	= Validator::validate($campos, $reglas, $nombres);
		$validator->customErrors([
            'extension'          => 'Extensión inválida. Se permiten solo archivos jpg'
        ]);
		if ($validator->isSuccess()) {
			return true;
		}
		$this->errores = $validator->getErrors();
		return false;
	}

/**
 * Obtiene el ID de empleado a partir del id_persona
 *
 * @param int $id_persona
 * @return null|int
 */
	public static function getEmpleado($id_persona=null){
		$sql_params= ['id_persona' => $id_persona];
		$sql	= <<<SQL
			SELECT id
			FROM empleados
			WHERE id_persona = :id_persona;
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		if (empty($res)) {
			return $res =null;
		}else{
			return (int)$res[0]['id'];
		}
	}

	static public function arrayToObject($res = []) {
		$campos	= [
			'id'			=> 'int',
			'tipo_documento'=> 'int',
			'documento'		=> 'int',
			'nombre'		=> 'string',
			'apellido'		=> 'string',
			'fecha_nac'		=> 'date',
			'genero'		=> 'int',
			'nacionalidad'	=> 'string',
			'estado_civil'	=> 'int',
			'email'			=> 'string',
			'foto_persona'  => 'string',
		];

		$obj = new self();
		foreach ($campos as $campo => $type) {
			switch ($type) {
				case 'int':
					$obj->{$campo}	= isset($res[$campo]) ? (int)$res[$campo] : null;
					break;
				case 'json':
					$obj->{$campo}	= isset($res[$campo]) ? json_decode($res[$campo], true) : null;
					break;
				case 'datetime':
					$obj->{$campo}	= isset($res[$campo]) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res[$campo]) : null;
					break;
				case 'date':
					$obj->{$campo}	= isset($res[$campo]) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res[$campo] . ' 0:00:00') : null;
					break;
				default:
					$obj->{$campo}	= isset($res[$campo]) ? $res[$campo] : null;
					break;
			}
		}
		if(static::getContiene()){
			return $obj;
		}

		if(static::getContiene('otros_conocimientos')){
			$obj->otros_conocimientos	= \App\Modelo\PersonaOtroConocimiento::listar($obj->id);
		}
		if(static::getContiene('titulos')){
			$obj->titulos				= \App\Modelo\PersonaTitulo::listar($obj->id);
		}
		if(static::getContiene('experiencia_laboral')){
			$obj->experiencia_laboral	= \App\Modelo\PersonaExperienciaLaboral::listar($obj->id);
		}
		if(static::getContiene('telefonos')){
			$obj->telefonos				= \App\Modelo\PersonaTelefono::listar($obj->id);
		}
		//============================================================================================//
		if(static::getContiene('domicilio')){
			$obj->domicilio				= new \StdClass();
			$obj->domicilio->id				= \FMT\Helper\Arr::get($res,'dom_id');
			$obj->domicilio->id_provincia	= \FMT\Helper\Arr::get($res,'dom_id_provincia');
			$obj->domicilio->id_localidad	= \FMT\Helper\Arr::get($res,'dom_id_localidad');
			$obj->domicilio->calle			= \FMT\Helper\Arr::get($res,'dom_calle');
			$obj->domicilio->numero			= \FMT\Helper\Arr::get($res,'dom_numero');
			$obj->domicilio->piso			= \FMT\Helper\Arr::get($res,'dom_piso');
			$obj->domicilio->depto			= \FMT\Helper\Arr::get($res,'dom_depto');
			$obj->domicilio->cod_postal		= \FMT\Helper\Arr::get($res,'dom_cod_postal');
			$obj->domicilio->fecha_alta		= isset($res['dom_fecha_alta']) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res['dom_fecha_alta'] . ' 0:00:00') : null;
			$obj->domicilio->fecha_baja		= isset($res['dom_fecha_baja']) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res['dom_fecha_baja'] . ' 0:00:00') : null;
		}
		//============================================================================================//
		if(static::getContiene('discapacidad')){
			$obj->discapacidad   						= new \StdClass();
			$obj->discapacidad->id   					= \FMT\Helper\Arr::get($res,'dis_id');
			$obj->discapacidad->id_tipo_discapacidad   	= \FMT\Helper\Arr::get($res,'dis_id_tipo_discapacidad');
			$obj->discapacidad->cud  					= \FMT\Helper\Arr::get($res,'dis_cud');
			$obj->discapacidad->observaciones   		= \FMT\Helper\Arr::get($res,'dis_observaciones');
			$obj->discapacidad->fecha_vencimiento   	= isset($res['dis_fecha_vencimiento']) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res['dis_fecha_vencimiento'] . ' 0:00:00') : null;
		}

		return $obj;
	}

	

	public function upload_foto_persona($bin){
		$expl = explode(',', $bin);
		$bin64 = $expl[1];
		$extension  = explode(';',explode('/', $expl[0])[1])[0];
		$data       = base64_decode($bin64);

		$date_time = gmdate('YmdHis');
		$directorio = BASE_PATH.'/uploads/foto_persona';

		$name =   $this->documento;
		$nombre_archivo = $date_time.'_'.$name;

		if(!is_dir($directorio)){
			mkdir($directorio, 0777, true);
		}
		$aux = $directorio."/".$nombre_archivo.".".$extension;
		$rta = false;
		if(!file_exists($aux))
		{
			$file = $aux;
			$this->foto_persona = $nombre_archivo.".".$extension;
			file_put_contents($file, $data);
			$rta = true;	
		}  
		return $rta;
	}

}
