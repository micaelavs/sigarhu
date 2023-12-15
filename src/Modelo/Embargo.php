<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class Embargo extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_empleado;
/** @var int */
	public $tipo_embargo;
/** @var string  */
	public $autos;
/** @var Date */
	public $fecha_alta;
/** @var Date */
	public $fecha_cancelacion;
/** @var string  */
	public $monto;

	const EJECUTIVO	= 1;
	const FAMILIAR	= 2;


	static public $TIPO_EMBARGO 	= [
		self::EJECUTIVO		=> ['id'	=> self::EJECUTIVO, 'nombre' => 'Ejecutivo', 'borrado' => '0'],
		self::FAMILIAR		=> ['id'	=> self::FAMILIAR, 'nombre' => 'Familiar', 'borrado' => '0'],

	];



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
			':id_empleado'	=> $id,
		];
		
		$sql	= <<<SQL
		SELECT  id,
			    id_empleado,
			    tipo_embargo,
			    autos,
			    fecha_alta,
			    fecha_cancelacion,
			    monto
			FROM embargos 
			WHERE id_empleado = :id_empleado and borrado = 0
		
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}


	static public function obtener_embargo($id=null){
		$obj	= new static;
		if($id===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id'	=> $id,
		];
		
		$sql	= <<<SQL
		SELECT  id,
			    id_empleado,
			    tipo_embargo,
			    autos,
			    fecha_alta,
			    fecha_cancelacion,
			    monto
			FROM embargos a
			WHERE id = :id and borrado = 0
		
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}




	static public function listar($id=null) {
		
		$sql_params	= [
			':id_empleado'	=> $id,
		];
		
		$sql	= <<<SQL
		SELECT  id,
			    id_empleado,
			    tipo_embargo,
			    autos,
			    fecha_alta,
			    fecha_cancelacion,
			    monto
			FROM embargos 
			WHERE id_empleado = :id_empleado AND fecha_cancelacion > now() and borrado = 0
			ORDER BY fecha_alta DESC
		
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if (!empty($res) && is_array($res)) {
			foreach ($res as $key => &$value) {
				$value['tipo_embargo'] =  self::getParam('TIPO_EMBARGO')[$value['tipo_embargo']]['nombre'];	
				$value['fecha_alta'] = \DateTime::createFromFormat('Y-m-d',$value['fecha_alta'])->format('d/m/Y');
				!empty($value['fecha_cancelacion']) ? $value['fecha_cancelacion'] = \DateTime::createFromFormat('Y-m-d',$value['fecha_cancelacion'])->format('d/m/Y') : null;
				}
		}else{
			$res = [];
		}	
		return $res;
	}

	static public function listar_historial($id=null) {
		
		$sql_params	= [
			':id_empleado'	=> $id,
		];
		
		$sql	= <<<SQL
		SELECT  id,
			    id_empleado,
			    tipo_embargo,
			    autos,
			    fecha_alta,
			    fecha_cancelacion,
			    monto
			FROM embargos 
			WHERE id_empleado = :id_empleado  AND fecha_cancelacion < now() AND borrado = 0
			ORDER BY fecha_alta DESC
		
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if (!empty($res) && is_array($res)) {
			foreach ($res as $key => &$value) {
				$value['tipo_embargo'] =  self::getParam('TIPO_EMBARGO')[$value['tipo_embargo']]['nombre'];	
				$value['fecha_alta'] = \DateTime::createFromFormat('Y-m-d',$value['fecha_alta'])->format('d/m/Y');
				!empty($value['fecha_cancelacion']) ? $value['fecha_cancelacion'] = \DateTime::createFromFormat('Y-m-d',$value['fecha_cancelacion'])->format('d/m/Y') : null;
				}
		}else{
			$res = [];
		}	
		return $res;
	}


	public function alta(){

			$campos	= [
			'id_empleado',
			'tipo_embargo',
			'autos',
			'fecha_alta',
			'fecha_cancelacion',
			'monto'
		];
		$sql_params	= [
			':id_empleado' => $this->id_empleado,
			':tipo_embargo' => $this->tipo_embargo,
			':autos' => $this->autos,
			':fecha_alta' => $this->fecha_alta,
			':fecha_cancelacion'=>$this->fecha_cancelacion,
			':monto' => $this->monto
		];

		if($this->fecha_alta instanceof \DateTime){
			$sql_params[':fecha_alta']	= $this->fecha_alta->format('Y-m-d');
		}
		if($this->fecha_cancelacion instanceof \DateTime){
			$sql_params[':fecha_cancelacion']	= $this->fecha_cancelacion->format('Y-m-d');
		}

		$sql	= 'INSERT INTO embargos('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$this->id	= $res;
			$datos = (array) $this;
			$datos['modelo'] = 'Embargo';
			Logger::event('alta', $datos);
		}
		return $res;
	}




	public function baja(){
		$sql_params= [':id' => $this->id];
		$sql = <<<SQL
		update embargos set borrado = 1 where id = :id;
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Embargo';
			Logger::event('baja', $datos);		
		}
		return $res;
	}


	public function modificacion(){
		if(!$this->validar() || empty($this->id)){
			return false;
		}
		
		$campos	= [
			'id_empleado' ,
			'tipo_embargo',
			'autos',
			'fecha_alta',
			'fecha_cancelacion',
			'monto'
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
		if($this->fecha_cancelacion instanceof \DateTime){
			$sql_params[':fecha_cancelacion']	= $this->fecha_cancelacion->format('Y-m-d');
		}

		$sql	= 'UPDATE embargos SET '.implode(',', $campos).' WHERE id = :id';

		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Embargo';
			Logger::event('modificacion', $datos);
		}
		return $res;
	 }

	public function validar() {
		
		$reglas		= [
			'tipo_embargo'  		 => ['required','integer'],
			'autos'  		 		 => ['required','texto'],
			'fecha_alta'  			 => ['required', 'fecha', 'antesDe(:fecha_cancelacion)'],
			'fecha_cancelacion'  	 => ['required', 'fecha', 'despuesDe(:fecha_alta)'],
			'monto'  				 => ['required','integer'],

		];
		$nombres	= [
			'tipo_embargo'				=> 'Tipo de Embargo',
			'autos' 					=> 'Autos',
			'fecha_alta'				=> 'Fecha de Alta',
			'fecha_cancelacion'			=> 'Fecha de Cancelación',
			'monto' 					=> 'Monto',

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

            'id'                                => 'int',
			'id_empleado'                       => 'int',
			'tipo_embargo'                      => 'int',
			'autos'         					=> 'string',
			'fecha_alta'                		=> 'date',
			'fecha_cancelacion'     			=> 'date',
			'monto'         					=> 'string'
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
					$obj->{$campo}	= isset($res[$campo]) ? \DateTime::createFromFormat('Y-m-d 00:00:00', $res[$campo]) : null;
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