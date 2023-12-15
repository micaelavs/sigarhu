<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class EmpleadoUltimosCambios extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_empleado;
/** @var int */
	public $id_tipo;
/** @var int */
	public $id_convenios;
/** @var datetime */
	public $fecha_desde;
/** @var datetime */
	public $fecha_hasta;


	const NIVEL	= 1;
	const GRADO	= 2;
	protected static $TIPO	= [
		self::NIVEL	=> ['id'	=> self::NIVEL, 'nombre'	=> 'nivel'],
		self::GRADO	=> ['id'	=> self::GRADO, 'nombre'	=> 'grado']
	];

	static public function obtener($id=null,$tipo=null){
		$obj	= new static;
		if($id===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id_empleado'	=> $id,
			':tipo' => $tipo
		];
		$campos	= implode(',', [
			'id',
			'id_empleado',
			'id_tipo',
			'id_convenios',
			'fecha_desde',
			'fecha_hasta',
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM empleado_ultimos_cambios
			WHERE id_empleado = :id_empleado
			AND id_tipo = :tipo
			AND fecha_hasta IS null
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		$obj = static::arrayToObject();
		$obj->id_empleado = $id;
		$obj->id_tipo = $tipo;
		return $obj;
	}

	static public function obtener_nivel($id){
		$obj = static::obtener($id,self::NIVEL);
		if($obj->id_tipo === null){
			$obj->id_tipo	= self::NIVEL;
		}
		return $obj; 
	}

	static public function obtener_grado($id){
		$obj = static::obtener($id,self::GRADO);
		if($obj->id_tipo === null){
			$obj->id_tipo	= self::GRADO;
		}
		return $obj; 
	}

	public function guardar_grado(){
		$rta = false;
		$cnx	= new Conexiones();
		if ($this->fecha_desde instanceof \DateTime) {
			$control = self::obtener_grado($this->id_empleado);
			if ($control->id_convenios == $this->id_convenios) {
				if ($control->fecha_desde != $this->fecha_desde) {
					#UPDATE DE LA FECHA DESDE, RETURN TRUE SI HAY EXITO Y EL ERRRO SQL SI NO
					$sql_params	= [
						':id_empleado' 	=> $this->id_empleado,
						':fecha_desde'	=>	$this->fecha_desde->format('Y-m-d'),
						':id_tipo'		=>	$this->id_tipo,
					];
			
					$sql	= <<<SQL
						UPDATE empleado_ultimos_cambios SET fecha_desde = :fecha_desde WHERE id_empleado= :id_empleado AND id_tipo = :id_tipo  AND fecha_hasta is null 
SQL;
					$cnx	= new Conexiones();
					$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);

					if($res !== 0){
						$datos = (array) $this;
						$datos['modelo'] = 'EmpleadoUltimosCambios';
						Logger::event('modificacion_grado', $datos);
						$rta= true;
					}else {
						$this->errores['empleado_ultimos_cambios'] = $cnx->errorInfo[2];
						$rta = false;
					}
				}else{
					#this errores NO hay cambios para guardar
					$this->errores['empleado_ultimos_cambios'] = $cnx->errorInfo[2];
					$rta = false;
				} 
			} else {
				#baja del registro actual;
				$sql_params	= [
					':id_tipo'		=> $this->id_tipo,
					':id_empleado' 	=> $this->id_empleado,
					':fecha_hasta'	=> $this->fecha_desde->format('Y-m-d'),			
				];

				$sql	= <<<SQL
				UPDATE empleado_ultimos_cambios SET fecha_hasta = :fecha_hasta WHERE id_tipo = :id_tipo AND id_empleado = :id_empleado
				AND fecha_hasta IS NULL
SQL;
				$cnx	= new Conexiones();
				$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);

					if($res !== false){
						$datos = (array) $this;
						$datos['modelo'] = 'EmpleadoUltimosCambios';
						Logger::event('baja_grado', $datos);

						#INSERT del nuevo
						$campos	= [
							'id_empleado',
							'id_tipo',
							'id_convenios',
							'fecha_desde',
							'fecha_hasta',
						];
						$sql_params	= [
							':id_empleado'		=> $this->id_empleado,
							':id_tipo'			=> $this->id_tipo,
							':id_convenios'		=> $this->id_convenios,
							':fecha_desde'		=> $this->fecha_desde->format('Y-m-d'),	
							':fecha_hasta'		=> null,				 
						];

						$sql	= 'INSERT INTO empleado_ultimos_cambios('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
						$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);

						if($res !== false){
							$this->id	= $res;
							$datos = (array) $this;
							$datos['modelo'] = 'EmpleadoUltimosCambios';
							Logger::event('alta_grado', $datos);
						}else {
							$this->errores['empleado_ultimos_cambios'] = $cnx->errorInfo[2];
							$rta = false;
						}
					}else {
						$this->errores['empleado_ultimos_cambios'] = $cnx->errorInfo[2];
						$rta = false;
					}
			}		
			return $rta;
		}
	}

	public function guardar_nivel(){
		$rta = true;
		if ($this->fecha_desde instanceof \DateTime) {
			$control = self::obtener_nivel($this->id_empleado);
			if ($control->id_convenios == $this->id_convenios) {
				if ($control->fecha_desde != $this->fecha_desde) {
					#UPDATE DE LA FECHA DESDE, RETURN TRUE SI HAY EXITO Y EL ERRRO SQL SI NO
					$sql_params	= [
						':id_empleado' 	=> $this->id_empleado,
						':fecha_desde'	=>	$this->fecha_desde->format('Y-m-d'),
						':id_tipo'		=>	$this->id_tipo,
					];
			
					$sql	= <<<SQL
						UPDATE empleado_ultimos_cambios SET fecha_desde = :fecha_desde WHERE id_empleado= :id_empleado AND id_tipo = :id_tipo  AND fecha_hasta is null 
SQL;
					$cnx	= new Conexiones();
					$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);

					if($res !== false){
						$datos = (array) $this;
						$datos['modelo'] = 'EmpleadoUltimosCambios';
						Logger::event('modificacion_nivel', $datos);
					}else {
						$this->errores['empleado_ultimos_cambios'] = $cnx->errorInfo[2];
						$rta = false;
					}
				}else{
					#this errores NO hay cambios para guardar
					$this->errores['empleado_ultimos_cambios'] = null;
					$rta = false;
				} 
			} else {
				#baja del registro actual;
				$sql_params	= [
					':id_tipo'		=> $this->id_tipo,
					':id_empleado' 	=> $this->id_empleado,
					':fecha_hasta'	=> $this->fecha_desde->format('Y-m-d'),			
				];

				$sql	= <<<SQL
				UPDATE empleado_ultimos_cambios SET fecha_hasta = :fecha_hasta WHERE id_tipo = :id_tipo AND id_empleado = :id_empleado
				AND fecha_hasta IS NULL
SQL;
				$cnx	= new Conexiones();
				$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);

					if($res !== false){
						$datos = (array) $this;
						$datos['modelo'] = 'EmpleadoUltimosCambios';
						Logger::event('baja_nivel', $datos);

						#INSERT del nuevo
						$campos	= [
							'id_empleado',
							'id_tipo',
							'id_convenios',
							'fecha_desde',
							'fecha_hasta',
						];
						$sql_params	= [
							':id_empleado'		=> $this->id_empleado,
							':id_tipo'			=> $this->id_tipo,
							':id_convenios'		=> $this->id_convenios,
							':fecha_desde'		=> $this->fecha_desde->format('Y-m-d'),	
							':fecha_hasta'		=> null,				 
						];

						$sql	= 'INSERT INTO empleado_ultimos_cambios('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
						$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);

						if($res !== false){
							$this->id	= $res;
							$datos = (array) $this;
							$datos['modelo'] = 'EmpleadoUltimosCambios';
							Logger::event('alta_nivel', $datos);
						}else {
							$this->errores['empleado_ultimos_cambios'] = $cnx->errorInfo[2];
							$rta = false;
						}
					}else {
						$this->errores['empleado_ultimos_cambios'] = $cnx->errorInfo[2];
						$rta = false;
					}
			}		
			return $rta;
		}
	}


	public function alta() {
	}

	public function modificacion() {
	}

	public function baja() {
	}

	public function validar() {
	}

	static public function arrayToObject($res = []) {
		$campos	= [
			'id'			=> 'int',
			'id_empleado' 	=> 'int',
			'id_tipo' 		=> 'int',
			'id_convenios' 	=> 'int',
			'fecha_desde'	=> 'date',
			'fecha_hasta'	=> 'date',
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