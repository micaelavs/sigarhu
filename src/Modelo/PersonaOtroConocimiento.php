<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class PersonaOtroConocimiento extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_persona;
/** @var int */
	public $id_tipo;
/** @var date */
	public $fecha;
/** @var string */
	public $descripcion;
/** @var int */
	public $borrado;

	const ESTUDIO		= 1;
	const CONOCIMIENTO	= 2;
	
	static protected $TIPO	= [
		self::ESTUDIO		=> ['id' => self::ESTUDIO, 'nombre' => 'Estudio', 'borrado' => '0'],
		self::CONOCIMIENTO	=> ['id' => self::CONOCIMIENTO, 'nombre' => 'Conocimiento', 'borrado' => '0'],
	];

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
			'id_tipo',
			'fecha',
			'descripcion',
			'borrado',
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM persona_otros_conocimientos
			WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}

	static public function listar($persona_id=null) {
		if(!is_numeric($persona_id)) {
			return [];
		}
		$campos		= implode(',', [
			'id_persona',
			'id_tipo',
			'fecha',
			'descripcion',
			'borrado',
		]);
		$sql_params	= [
			':id_persona'	=> $persona_id
		];
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM persona_otros_conocimientos
			WHERE borrado = 0 AND id_persona = :id_persona
			ORDER BY id DESC
SQL;
		$resp	= (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
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
			'fecha',
			'descripcion',
			'id_persona',
			'id_tipo',
		];
		$sql_params	= [
			':fecha'		=> $this->fecha,
			':descripcion'	=> $this->descripcion,
			':id_persona'	=> $this->id_persona,
			':id_tipo'		=> $this->id_tipo,
		];

		if($this->fecha instanceof \DateTime){
			$sql_params[':fecha']	= $this->fecha->format('Y-m-d');
		}

		$sql	= 'INSERT INTO persona_otros_conocimientos('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$this->id	= $res;
			$datos = (array) $this;
			$datos['modelo'] = 'PersonaOtroConocimiento';
			Logger::event('alta', $datos);
		}
		return $res;
	}

	public function baja(){
		if(empty($this->id)) {
			return false;
		}
		$sql	= <<<SQL
			UPDATE persona_otros_conocimientos SET borrado = 1 WHERE id = :id
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, [':id' => $this->id]);
		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'PersonaOtroConocimiento';
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
		if(empty($this->id)){
			return false;
		}

		$campos	= [
			'id_tipo' 		=>'id_tipo 		= :id_tipo',
			'fecha'			=>'fecha 		= :fecha',
			'descripcion'	=>'descripcion 	= :descripcion',
		];
		$sql_params	= [
			':fecha'		=> $this->fecha,
			':descripcion'	=> $this->descripcion,
			':id_tipo'		=> $this->id_tipo,
			':id'			=> $this->id,
		];

		if($this->fecha instanceof \DateTime){
			$sql_params[':fecha']	= $this->fecha->format('Y-m-d');
		}

		$sql	= 'UPDATE persona_otros_conocimientos SET '.implode(',', $campos).' WHERE id = :id';
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'PersonaOtroConocimiento';
			Logger::event('modificacion', $datos);
			return true;
		}
		return false;
	}

	public function validar() {
		$reglas		= [
			'id_persona'	=> ['required'],
			'descripcion'	=> ['required']
		];
	
		if($this->id_tipo == static::ESTUDIO){
			$reglas += ['fecha' => ['required','fecha']];
		}

		$nombres	= [
			'id_persona'	=> 'Persona',
			'descripcion'	=> 'Descripción',
			'fecha'			=> 'Fecha'
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
			'id'			=> 'int',
			'id_persona'	=> 'int',
			'id_tipo'		=> 'int',
			'fecha'			=> 'date',
			'descripcion'	=> 'string',
			'borrado'		=> 'int',
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
					$obj->{$campo}	= isset($res[$campo]) ? \DateTime::createFromFormat('Y-m-d H:i:s.u', $res[$campo].'.000000') : null;
					break;
				case 'date':
					$obj->{$campo}	= isset($res[$campo]) ? \DateTime::createFromFormat('Y-m-d H:i:s.u', $res[$campo].' 0:00:00.000000') : null;
					break;
				default:
					$obj->{$campo}	= isset($res[$campo]) ? $res[$campo] : null;
					break;
			}
		}
		return $obj;
	}
}