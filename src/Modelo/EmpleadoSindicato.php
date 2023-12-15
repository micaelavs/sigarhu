<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class EmpleadoSindicato extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_empleado;
/** @var int */
	public $id_sindicato;
/** @var datetime */
	public $fecha_desde;
/** @var datetime */
	public $fecha_hasta;


	static public function obtener($id=null, $id_empleado =null){
		$obj	= new static;
		if($id===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id'	=> $id,
			':id_empleado'	=> $id_empleado,
		];
		$sql	= <<<SQL
			SELECT
				id,
				id_empleado,
				id_sindicato,
				fecha_desde,
				fecha_hasta
			FROM empleado_sindicatos
			/*WHERE id = :id*/
			WHERE id_empleado = :id_empleado
			AND id_sindicato = :id
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}

	static public function listar($id_empleado=null) {
		if($id_empleado	== null){
			return [];
		}

		$sql	= <<<SQL
			SELECT
				id,
				id_empleado,
				id_sindicato,
				fecha_desde,
				fecha_hasta
			FROM empleado_sindicatos
			WHERE id_empleado = $id_empleado AND fecha_hasta IS NULL
			ORDER BY id DESC
SQL;
		$cnx	= new Conexiones();
		$resp	= $cnx->consulta(Conexiones::SELECT, $sql);

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

		$cnx	= new Conexiones();
		$campos	= [
			'id_empleado',
			'id_sindicato',
			'fecha_desde',
			'fecha_hasta'
		];
		$sql_params	= [
			':id_empleado'		=> $this->id_empleado,
			':id_sindicato'		=> $this->id_sindicato,
			':fecha_desde'		=> $this->fecha_desde,	
			':fecha_hasta'		=> $this->fecha_hasta,				 
		];

		if($this->fecha_desde instanceof \DateTime){
			$sql_params[':fecha_desde']	= $this->fecha_desde->format('Y-m-d');
		}
		if($this->fecha_hasta instanceof \DateTime){
			$sql_params[':fecha_hasta']	= $this->fecha_hasta->format('Y-m-d');
		}

		$sql	= 'INSERT INTO empleado_sindicatos('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);

		if($res !== false){
			$this->id	= $res;
			$datos = (array) $this;
			$datos['modelo'] = 'EmpleadoSindicato';
			Logger::event('alta', $datos);
		}
		return $res;
	}

	public function baja(){
		if(empty($this->id)) {
			return false;
		}
		$sql	= <<<SQL
			UPDATE empleado_sindicatos SET fecha_hasta = :fecha_hasta WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, [
			':id'			=> $this->id,
			':fecha_hasta'	=> (\DateTime::createFromFormat('U', strtotime('now'))->format('Y-m-d')),
		]);

		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'EmpleadoSindicato';
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
			'id_empleado',
			'id_sindicato',
			'fecha_desde',
			'fecha_hasta'
		];
		$sql_params	= [
			':id'	=> $this->id,
		];
		foreach ($campos as $key => $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
			unset($campos[$key]);
			$campos[$campo]	= $campo .' = :'.$campo;
		}

		if($this->fecha_desde instanceof \DateTime){
			$sql_params[':fecha_desde']	= $this->fecha_desde->format('Y-m-d');
		}
		if($this->fecha_hasta instanceof \DateTime){
			$sql_params[':fecha_hasta']	= $this->fecha_hasta->format('Y-m-d');
		}

		$sql	= 'UPDATE empleado_sindicatos SET '.implode(',', $campos).' WHERE id = :id';
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);

		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'EmpleadoSindicato';
			Logger::event('modificacion', $datos);
		}
		return $res;
	}

	public function validar() {
		$reglas		= [
			'id_sindicato'		=> ['required'],
		];
		$nombres	= [
			'id_sindicato'		=> 'Sindicato',
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
			'id_empleado'		=> 'int',
			'id_sindicato'		=> 'int',
			'fecha_desde'		=> 'date',
			'fecha_hasta'		=> 'date',
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

	static public function alta_otro_sindicato($sindicato=null){
		$aux = \App\Modelo\EmpleadoSindicato::getSindicatos();
		foreach ($aux as $key => $value) {
			$res = ($value['nombre'] == $sindicato) ? $value['id'] : false; 
		}
		if(!$res){
			$cnx	= new Conexiones();
			$campos	= [
				'nombre',
			];
			$sql_params	= [
				':nombre'		=> $sindicato,			 
			];

			$sql	= 'INSERT INTO sindicatos('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
			$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);

			if($res !== false){
				$datos = (array) $cnx;
				$datos['modelo'] = 'EmpleadoSindicato';
				Logger::event('alta_sindicato', $datos);
			}
		}
		return $res;
	}

	static public function getSindicatos($cache=true){
		static $aux = [];
		if(!empty($aux) && $cache === true){
			return $aux;
		}
		$campos	= implode(',', [
			'id',
			'codigo',
			'nombre',
			'borrado'
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM sindicatos
			WHERE borrado = 0
			ORDER BY id ASC
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::SELECT, $sql);
		if($res){
			foreach ((array)$res as $value) 
			{ 
				$aux[$value['id']] = $value; 
			}
			return $aux;
		}else{
			return $aux;
		}
	}

	public function guardar($sindicatos=[]) {
		if(!is_array($sindicatos)){
			$sindicatos = [];
		}
		$aux = static::listar($this->id_empleado);
		$list = [];
		foreach ($aux as  $value) {
			$list[$value->id] = $value->id_sindicato;
		}
		$diff_alta = array_unique(array_diff($sindicatos, $list));
		foreach ($diff_alta as $value) {
			$this->id = null;
			$this->fecha_desde  = date('Y-m-d');
			$this->fecha_hasta  = null;
			$this->id_sindicato = $value;
			$this->alta(); 
		}

		$diff_baja = array_unique(array_diff($list, $sindicatos));
		foreach ($diff_baja as $id => $value) {
			$this->id = $id;
			$this->fecha_hasta  = date('Y-m-d');
			$this->id_sindicato = $value;
			$this->baja(); 
		}

		return static::listar($this->id_empleado);	
	}

}