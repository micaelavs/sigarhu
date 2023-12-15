<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class Sindicato extends Modelo {

/**@var int*/
	public $id;
/**@var string*/
	public $codigo;
/**@var string*/
	public $nombre;
/** @var int */
	public $borrado;


	
 	public static function obtener($id = null){
    	$obj	= new static;
		if($id===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id'	=> $id,
		];
		$campos	= implode(',', [
			'id',
			'codigo',
			'nombre',
			'borrado'
		
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM sindicatos
			WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
  }

	public static function listar(){
		$conexion = new Conexiones();
		$resultado = $conexion->consulta(Conexiones::SELECT,
		'SELECT
		id,
		codigo,
		nombre,
		borrado
		FROM sindicatos
		WHERE borrado = 0');
		return $resultado;
	}

	public function alta(){
		$campos	= [
		'codigo',
		'nombre'
		];
		$sql_params	= [
		];
		foreach ($campos as $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
		}

		$sql	= 'INSERT INTO sindicatos('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Sindicatos';
			Logger::event('alta', $datos);
		}
		return $res;
	}

	public function modificacion(){
		$campos	= [
		'codigo',
		'nombre'
		];
		$sql_params	= [
		':id'	=> $this->id,
		];
		foreach ($campos as $key => $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
			unset($campos[$key]);
			$campos[$campo]	= $campo .' = :'.$campo;
		}

		$sql	= 'UPDATE sindicatos SET '.implode(',', $campos).' WHERE id = :id';
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Sindicatos';
			Logger::event('modificacion', $datos);
		}
		return $res;
	}

	public function baja(){
		$mbd = new Conexiones;
		$params = [':id' => $this->id];
		$sql = <<<SQL
		UPDATE sindicatos SET  borrado = 1 WHERE id = :id
SQL;
		$res = $mbd->consulta(Conexiones::SELECT, $sql, $params);
		if ($res !== false) {
			$datos = (array) $this;
			$datos['modelo'] = 'Sindicatos';
			Logger::event('baja', $datos);
		} else {
			$datos['error_db'] = $conexion->errorInfo;
			Logger::event("error_baja",$datos);
		}
		return $res;
	}

	public function validar(){
		$rules = [
			'id' => ['numeric', ],
			'codigo' => ['texto', 'min_length(2)', 'max_length(20)', ],
			'nombre' => [ 'required','texto', 'min_length(5)', 'max_length(200)','unico(sindicatos,nombre,:id)' ],
			'borrado' => ['numeric', ]
		];
		$nombres	= [
			'codigo'		=> 'Código',
			'nombre'		=> 'Nombre Sindicato',
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
		'codigo'		=> 'string',
		'nombre'	=> 'string'
		];
		$obj = new self();
		foreach ($campos as $campo => $type) {
			switch ($type) {
				case 'int':
					$obj->{$campo}	= isset($res[$campo]) ? (int)$res[$campo] : null;
					break;
				default:
					$obj->{$campo}	= isset($res[$campo]) ? $res[$campo] : null;
					break;
			}
		}

		return $obj;
	}
}