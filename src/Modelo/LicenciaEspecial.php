<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

/**
 * Descripcion de datos: Secretarias, Subsecretarias, etc
*/
class LicenciaEspecial extends Modelo {
	/** @var int */
	public $id;
	/** @var string */
	public $nombre;
	/** @var int */
	public $borrado;

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
			'nombre',
			'borrado',
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM licencias_especiales
			WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}

	static public function listar() {
		$campos	= implode(',', [
			'id',
			'nombre',
			'borrado',
		]);
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM licencias_especiales
			WHERE borrado = 0
			ORDER BY id ASC
SQL;
		$resp	= (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql);
		if(empty($resp)) { return []; }
		foreach ($resp as &$value) {
			$value	= static::arrayToObject($value);
		}
		return $resp;
	}


	static public function getLicenciasEspeciales(){
		$campos	= implode(',', [
			'nombre',
			'borrado',
		]);
		$aux = [];
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM licencias_especiales
			ORDER BY id ASC
SQL;
		$resp	= (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql);
		foreach ($resp as $value) {
	    	$aux[$value['id']] = $value;
	    }
	    return $aux;
	}

	public function alta(){
		if(!$this->validar()){
			return false;
		}
		$campos	= [
			'nombre'
		];
		$sql_params	= [
		];
		foreach ($campos as $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
		}

		$sql	= 'INSERT INTO licencias_especiales('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'LicenciaEspecial';
			Logger::event('alta', $datos);
		}
		return $res;
	}

	public function baja(){
		$mbd = new Conexiones;
		$params = [':id' => $this->id];
		$sql = <<<SQL
		UPDATE licencias_especiales SET  borrado = 1 WHERE id = :id
SQL;
		$res = $mbd->consulta(Conexiones::SELECT, $sql, $params);
		if ($res !== false) {
			$datos = (array) $this;
			$datos['modelo'] = 'LicenciasEspeciales';
			Logger::event('baja', $datos);
		} else {
			$datos['error_db'] = $conexion->errorInfo;
			Logger::event("error_baja",$datos);
		}
		return $res;
	}


	public function modificacion(){
		if(!$this->validar() || empty($this->id)){
			return false;
		}

		$campos	= [
			'nombre',
			'borrado',
		];
		$sql_params	= [
			':id'	=> $this->id,
		];
		foreach ($campos as $key => $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
			unset($campos[$key]);
			$campos[$campo]	= $campo .' = :'.$campo;
		}

		$sql	= 'UPDATE licencias_especiales SET '.implode(',', $campos).' WHERE id = :id';
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'LicenciaEspecial';
			Logger::event('modificacion', $datos);
		}
		return $res;
	}

	public function validar(){
		$rules = [
			'id' => ['numeric', ],
			'nombre' => ['required', 'texto', 'min_length(5)', 'max_length(100)', ],
			'borrado' => ['numeric', ]
		];
		$nombres	= [
			'nombre'		=> 'Licencia Especial',
		];

		$validator = Validator::validate((array)$this, $rules, $nombres);
		if ($validator->isSuccess()) {
			return true;
		}
		else {
			$this->errores = $validator->getErrors();
			return false;
		}
	}


	static public function arrayToObject($res = []) {
		$campos	= [
			'id'			=> 'int',
			'nombre'		=> 'string',
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