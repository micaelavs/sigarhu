<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class Comision extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $borrado;
/** @var string */
	public $nombre;

	static public function obtener($id=null){
		$obj	= new static;
		if($id===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id'	=> $id,
		];
		$sql	= <<<SQL
			SELECT
				id,
				borrado,
				nombre
			FROM comisiones
			WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}

	static public function listar($array=false) {
		$sql	= <<<SQL
			SELECT
				id,
				borrado,
				nombre
			FROM comisiones
			WHERE borrado = 0
			ORDER BY id ASC
SQL;
		$resp	= (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql);
		if(empty($resp)) { return []; }
		$aux	= [];
		foreach ($resp as $key => &$value) {
			if($array){
				$aux[$value['id']]	= $value;
				unset($resp[$key]);
			} else {
				$resp[$key]	= static::arrayToObject($value);
			}
		}
		return !empty($aux) ? $aux : $resp;
	}

	public function alta(){
		if(!$this->validar()){
			return false;
		}
		$campos	= [
			'nombre',
		];
		$sql_params	= [
			'nombre'	=> $this->nombre
		];

		$sql	= 'INSERT INTO comisiones( nombre ) VALUES (:nombre)';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$this->id	= $res;
			$datos = (array) $this;
			$datos['modelo'] = 'Comision';
			Logger::event('alta', $datos);
			return true;
		}
		return false;
	}

	public function baja(){
		if(empty($this->id)) {
			return false;
		}
		$mbd	= new Conexiones();
		$sql	= <<<SQL
			UPDATE comisiones SET borrado = 1 WHERE id = :id
SQL;
		$res	= $mbd->consulta(Conexiones::UPDATE, $sql, [':id' => $this->id]);
		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'Comision';
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

		$sql_params	= [
			':id'		=> $this->id,
			':nombre'	=> $this->nombre,
		];

		$sql	= 'UPDATE comisiones SET nombre = :nombre WHERE id = :id';
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Comision';
			Logger::event('modificacion', $datos);
			return true;
		}
		return false;
	}

	public function validar() {
		$campos = (array)$this;
		$reglas		= [
			'nombre'	=> ['required','texto', 'min_length(5)', 'max_length(200)','nombreUnico()' => function($input) use ($campos){
				$where	= '';
				$input		= trim($input);
				$sql_params	= [
					':nombre'			=> '%'.$input.'%',
					':nombre_uppercase'	=> '%'.strtoupper($input).'%',
					':nombre_lowercase'	=> '%'.strtolower($input).'%',
				];
				if(!empty($campos['id'])){
					$where				= ' AND id != :id';
					$sql_params[':id']	= $campos['id'];
				}
				$sql		= 'SELECT nombre FROM comisiones WHERE (nombre LIKE :nombre OR nombre LIKE :nombre_uppercase OR nombre LIKE :nombre_lowercase)'.$where;
				$resp	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
				return empty($resp);
			}]
			
		];
		$nombre = [		
			'nombre'	=> 'Nombre de Organismo', 	
		];

		$validator	= Validator::validate($campos, $reglas, $nombre);
		$validator->customErrors([
			'nombreUnico()' => 'Ya existe una comision con el mismo nombre, modifique la existente.',
		]);

		if ($validator->isSuccess()) {
			return true;
		}
		$this->errores = $validator->getErrors();
		return false;
	}

	static public function arrayToObject($res = []) {
		$campos	= [
			'id'					=> 'int',
			'borrado'				=> 'int',
			'nombre'				=> 'string',
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