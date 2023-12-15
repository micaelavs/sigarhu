<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class PersonaExperienciaLaboral extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_persona;
/** @var int */
	public $id_entidad;
/** @var int */
	public $tipo_entidad;
	/** @var int */
	public $jurisdiccion;
/** @var date */
	public $fecha_desde;
/** @var string */
	public $fecha_hasta;
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
		$sql	= <<<SQL
			SELECT p.id, p.id_persona, p.id_entidad, o.tipo as tipo_entidad, p.fecha_desde, p.fecha_hasta
			FROM persona_experiencia_laboral p
			INNER JOIN otros_organismos o ON p.id_entidad = o.id
			WHERE p.id = :id
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

		$sql_params	= [
			':id_persona'	=> $persona_id
		];
		$sql	= <<<SQL
			SELECT p.id, p.id_persona, p.id_entidad, o.tipo as tipo_entidad, o.jurisdiccion as jurisdiccion, p.fecha_desde, p.fecha_hasta
			FROM persona_experiencia_laboral p
			INNER JOIN otros_organismos o ON p.id_entidad = o.id 
			WHERE p.borrado = 0 AND p.id_persona = :id_persona
			ORDER BY p.fecha_desde DESC
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
			'id_persona',
			'id_entidad',
			'fecha_desde',
			'fecha_hasta',
		];
		$sql_params	= [
		];
		foreach ($campos as $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
		}

		if($this->fecha_desde instanceof \DateTime){
			$sql_params[':fecha_desde']	= $this->fecha_desde->format('Y-m-d');
		}
		if($this->fecha_hasta instanceof \DateTime){
			$sql_params[':fecha_hasta']	= $this->fecha_hasta->format('Y-m-d');
		}

		$sql	= 'INSERT INTO persona_experiencia_laboral('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$this->id	= $res;
			$datos = (array) $this;
			$datos['modelo'] = 'PersonaExperienciaLaboral';
			Logger::event('alta', $datos);
		}
		return $res;
	}

	public function baja(){
		if(empty($this->id)) {
			return false;
		}
		$sql	= <<<SQL
			UPDATE persona_experiencia_laboral SET borrado = 1 WHERE id = :id
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, [':id' => $this->id]);
		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'PersonaExperienciaLaboral';
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
			'id_entidad',
			'fecha_desde',
			'fecha_hasta',
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

		$sql	= 'UPDATE persona_experiencia_laboral SET '.implode(',', $campos).' WHERE id = :id';
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'PersonaExperienciaLaboral';
			Logger::event('modificacion', $datos);
			return true;
		}
		return false;
	}

	public function validar() {
		$persona_exp_lab = (array)$this;
		$reglas		= [
			'fecha_desde'	=> ['required','fecha', 'antesDe(:fecha_hasta)'],
			'fecha_hasta'  	=> ['required','fecha', 'despuesDe(:fecha_desde)', 'maxDate' => function($value){
											if (!empty($value)) {
                                                  $hoy =  \DateTime::createFromFormat('d/m/Y H:i:s', gmdate('d/m/Y').'0:00:00');
                                                  return $value <= $hoy;
                                            }
              }],
			'id_persona'	=> ['required'],
		];
		$nombres	= [
			'id_persona'	=> 'Persona',
			'fecha_desde'	=> 'Fecha desde',
			'fecha_hasta'	=> 'Fecha hasta',
		];
		$validator	= Validator::validate((array)$this, $reglas, $nombres);
	    $validator->customErrors(['maxDate'       => 'La fecha hasta NO puede ser posterior a la fecha actual']);

		if ($validator->isSuccess()) {
			return true;
		}
		$this->errores = $validator->getErrors();
		return false;
	}
	
	
	static public function rango_fechas($id_persona=null){
		$sql_params	= [
			':id_persona'	=> $id_persona,
			':tipo' 		=> 1
		];
		$sql	= <<<SQL
			SELECT p.id,p.fecha_desde,p.fecha_hasta
			FROM persona_experiencia_laboral p
			inner join otros_organismos o on o.id = p.id_entidad
			where p.borrado = 0 and o.tipo = :tipo and id_persona = :id_persona
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(empty($res)) { 
			return []; 
		}else{ 
			$return =[];			
			foreach ($res as $value) {
				$return[] = [
					'fecha_desde' =>  \DateTime::createFromFormat('Y-m-d H:i:s.u', $value['fecha_desde']. ' 00:00:00.000000'),
					'fecha_hasta' =>  \DateTime::createFromFormat('Y-m-d H:i:s.u', $value['fecha_hasta'] . ' 23:59:60.000000')
				];
			}		
			return $return;
		}
	}
	
	/**
	 * Recibe array de rangos que se superponen o no.
	 * Devuelve un array con rangos que se solapaban sumados 
	 ['fecha_desde' => DateTime,'fecha_hasta' => DateTime] y/o los rangos sin solapamiento.
	 *
	 * @param array $rangos	
	 * @param int $id	- Se usa para la recursividad del metodo.
	 * @return array
	*/

	static public function experiencia_solapadas($rangos=null,$id=0){
		/*condicion de corte*/
		if($id < count($rangos)){
			foreach ($rangos as $key => $value) {
				/*exclusión del propio id*/
				if($key != $id) {
					/*Verificacion de solapamiento de rangos de fechas.*/
					if(
						((($rangos[$id]['fecha_desde'] <= $value['fecha_desde']) && ($value['fecha_desde'] <= $rangos[$id]['fecha_hasta']))
						|| (($rangos[$id]['fecha_desde'] <= $value['fecha_hasta']) && ($value['fecha_hasta'] <= $rangos[$id]['fecha_hasta'])))
						|| ((($value['fecha_desde'] <= $rangos[$id]['fecha_desde']) && ($rangos[$id]['fecha_desde'] <= $value['fecha_hasta']))
						|| (($value['fecha_desde'] <= $rangos[$id]['fecha_hasta']) && ($rangos[$id]['fecha_hasta'] <= $value['fecha_hasta'])))
					) {
						/*Se selecciona la minima fecha_desde y la maxima fecha_hasta para conformar el nuevo rango*/ 
						$rangos[$id]['fecha_desde'] =  ($rangos[$id]['fecha_desde'] <  $value['fecha_desde']) ? $rangos[$id]['fecha_desde'] : $value['fecha_desde'];	
						$rangos[$id]['fecha_hasta'] =  ($rangos[$id]['fecha_hasta'] >  $value['fecha_hasta']) ? $rangos[$id]['fecha_hasta'] : $value['fecha_hasta'];	
						/*Elimina la posicion porque el rango se compuso con el rango considerado.*/
						unset($rangos[$key]);
					}			
				}			
			}
			/*Recontituye los indices del array*/
			$rangos = array_values($rangos);
			/*incrementa el id, para el avance de la función recursiva*/
			$id++;
			$rangos = static::experiencia_solapadas($rangos,$id);  			
		}
		return $rangos;		
	}	

	static public function arrayToObject($res = []) {
		$campos	= [
			'id'			=> 'int',
			'id_persona'	=> 'int',
			'id_entidad'	=> 'int',
			'tipo_entidad'  => 'int',
			'jurisdiccion'  => 'int',
			'fecha_desde'	=> 'date',
			'fecha_hasta'	=> 'date',
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
					$obj->{$campo}	= isset($res[$campo]) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res[$campo].' 0:00:00') : null;
					break;
				default:
					$obj->{$campo}	= isset($res[$campo]) ? $res[$campo] : null;
					break;
			}
		}
		return $obj;
	}

	/**
	 * Suma la antiguedad laboral de una persona en distintos sectores (publico, privado) teniendo en cuenta el solapamiento de fechas
	 *
	 * ------ Ejemplo para `$rango_extra` ------
	 * ```
	 * $rango_extra	= [[
	 *	'fecha_desde'	=> \DateTime::createFromFormat('d/m/Y H:i:s.u', '01/01/1970 0:00:00.000000'),
	 *	'fecha_hasta'	=> \DateTime::createFromFormat('d/m/Y H:i:s.u', gmdate('d/m/Y').' 0:00:00.000000')
	 * ]];
	 * ```
	 *
	 * @param int $id_persona
	 * @param array $rango_extra
	 * @return array - ['anios'=>int, 'meses'=>int]
	 */
	static public function total_antiguedad($id_persona=null, $rango_extra=null){
		if(empty($id_persona)){
			return ['anios'=>0, 'meses'=>0];
		}
		$rangos		= static::rango_fechas($id_persona);
		if(is_array($rango_extra)){
			$rangos = array_merge($rangos, $rango_extra);
		}
		$interval	= new \App\Helper\Fechas();
		$rangos		= static::experiencia_solapadas($rangos);
		foreach ($rangos as $i => $value) {
			$interval->sum_cantidad_dias($value['fecha_desde'],$value['fecha_hasta']);
		}
		return $interval->get_cantidad();		
	}
}
