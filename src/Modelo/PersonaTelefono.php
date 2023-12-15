<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class PersonaTelefono extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_persona;
/** @var int */
	public $id_tipo_telefono;
/** @var string */
	public $telefono;
/** @var date */
	public $fecha_alta;
/** @var date */
	public $fecha_baja;

	const MOVIL 		= 1;
	const FIJO			= 2;
	const LABORAL		= 3;
	const LABORAL_MOVIL	= 4;
	const OTRO			= 5;
	static protected $TIPO_TELEFONO	= [
		self::MOVIL			=> ['id' => self::MOVIL, 'nombre' => 'Movil', 'borrado' => '0'],
		self::FIJO			=> ['id' => self::FIJO, 'nombre' => 'Fijo', 'borrado' => '0'],
		self::LABORAL		=> ['id' => self::LABORAL, 'nombre' => 'Laboral', 'borrado' => '0'],
		self::LABORAL_MOVIL	=> ['id' => self::LABORAL_MOVIL, 'nombre' => 'Laboral Movil', 'borrado' => '0'],
		self::OTRO			=> ['id' => self::OTRO, 'nombre' => 'Otro', 'borrado' => '0'],
	];

/**
 * Obtiene los valores de los array parametricos.
 * E.J.: PersonaTelefono::getParam('TIPO_TELEFONO');
*/
	static public function getParam($attr=null){
		if($attr === null || empty(static::${$attr})){
			return [];
		}
		return static::${$attr};
	}

	static public function obtener($id=null){
		$obj	= new static;
		if($id===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id'	=> $id,
		];
		$campos	= implode(',', [
			'id',
			'id_persona',
			'id_tipo_telefono',
			'telefono',
			'fecha_alta',
			'fecha_baja',
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM persona_telefono
			WHERE id = :id
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}

	static public function listar($id_persona=null) {
		if($id_persona	== null){
			return [];
		}
		$campos	= implode(',', [
			'id_persona',
			'id_tipo_telefono',
			'telefono',
			'fecha_alta',
			'fecha_baja',
		]);
		$sql_params	= [
			':id_persona'	=> $id_persona
		];
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM persona_telefono
			WHERE id_persona = :id_persona AND fecha_baja IS NULL
			ORDER BY id ASC
SQL;
		$cnx	= new Conexiones();
		$resp	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(empty($resp)) { return []; }
		foreach ($resp as &$value) {
			$value	= static::arrayToObject($value);
		}
		return $resp;
	}

	public function alta(){
		if(!$this->validar()){
			return false;
		}
		$campos	= [
			'id_persona',
			'id_tipo_telefono',
			'telefono',
			'fecha_alta',
		];
		$this->fecha_alta	= \DateTime::createFromFormat('U', strtotime('now'));
		$sql_params	= [
		];
		foreach ($campos as $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
		}
		$sql_params[':fecha_alta']	= $this->fecha_alta->format('Y-m-d');

		$sql	= 'INSERT INTO persona_telefono('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$this->id	= $res;
			$datos = (array) $this;
			$datos['modelo'] = 'PersonaTelefono';
			Logger::event('alta', $datos);
		}
		return $res;
	}

	public function baja(){
		if(empty($this->id)) {
			return false;
		}
		$sql	= <<<SQL
			UPDATE persona_telefono SET fecha_baja = :fecha_baja WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, [
			':id'			=> $this->id,
			':fecha_baja'	=> (\DateTime::createFromFormat('U', strtotime('now'))->format('Y-m-d')),
		]);
		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'PersonaTelefono';
			if (is_numeric($res) && $res > 0) {
				$flag = true;
			} else {
				$datos['error_db'] = $mbd->errorInfo;
			}
			Logger::event('baja', $datos);
		}
		return $flag;
	}

	public function modificacion(){
		if(!$this->validar() || empty($this->id)){
			return false;
		}

		$campos	= [
			'id_persona',
			'id_tipo_telefono',
			'telefono',
			'fecha_alta',
		];
		$sql_params	= [
			':id'	=> $this->id,
		];
		foreach ($campos as $key => $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
			unset($campos[$key]);
			$campos[$campo]	= $campo .' = :'.$campo;
		}

		if($this->fecha_alta instanceof \DateTime){
			$sql_params[':fecha_alta']	= $this->fecha_alta->format('Y-m-d');
		}

		$sql	= 'UPDATE persona_telefono SET '.implode(',', $campos).' WHERE id = :id';
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'PersonaTelefono';
			Logger::event('modificacion', $datos);
		}
		return $res;
	}

	public function validar() {
		$reglas		= [
			'id_tipo_telefono'	=> ['required'],
			'telefono'			=> ['required'],
		];
		$nombres	= [
			'telefono'			=> 'Telefono',
			'id_tipo_telefono'	=> 'Descripcion de Telefono',
		];
		$validator	= Validator::validate((array)$this, $reglas, $nombres);
		if ($validator->isSuccess()) {
			return true;
		}
		$this->errores = $validator->getErrors();
		return false;
	}

	static public function arrayToObject($res = []) {
		$campos	= [
			'id'				=> 'int',
			'id_persona'		=> 'int',
			'id_tipo_telefono'	=> 'int',
			'telefono'			=> 'string',
			'fecha_alta'		=> 'date',
			'fecha_baja'		=> 'date',
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
					$obj->{$campo}	= isset($res[$campo]) ? \DateTime::createFromFormat('Y-m-d', $res[$campo]) : null;
					break;
				default:
					$obj->{$campo}	= isset($res[$campo]) ? $res[$campo] : null;
					break;
			}
		}
		return $obj;
	}
}