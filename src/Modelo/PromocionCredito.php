<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;


class PromocionCredito extends Modelo {
/** @var int */
	public $id;
/** @var Date */
	public $fecha_desde;
/** @var Date */
	public $fecha_hasta;
/** @var int */
    public $id_nivel;
/** @var int */
    public $id_tramo;
/** @var int */
	public $creditos;
/** @var int */
    public $borrado;


	static public function obtener($id=null){
		if($id===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id'	=> $id,
		];

		$campos	= implode(',', ['id','fecha_desde','fecha_hasta','id_nivel','id_tramo','creditos','borrado']);
		
		$sql	= <<<SQL
			SELECT {$campos}
			FROM promocion_creditos
			WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}


	public function alta(){
		$campos	= ['fecha_desde','id_nivel','id_tramo','creditos'];
		$sql_params	= [];
		foreach ($campos as $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
		}
		if($this->fecha_desde instanceof \DateTime){
			$sql_params[':fecha_desde']	= $this->fecha_desde->format('Y-m-d');
		}
		$sql	= 'INSERT INTO promocion_creditos('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'PromocionCredito';
			Logger::event('alta', $datos);
		}
		return $res;
	}


	static public function listar() {
		$cnx	= new Conexiones();
		$sql= <<<SQL
		SELECT 
		   p.id,p.fecha_desde,CONCAT(a.nombre,' - ',n.nombre) as 'nivel',t.nombre as 'tramo',p.creditos
		FROM promocion_creditos AS p
		INNER JOIN convenio_niveles AS n on (n.id = p.id_nivel)
		INNER JOIN convenio_tramos AS t on (t.id = p.id_tramo)
		INNER JOIN convenio_agrupamientos AS a on (a.id = n.id_agrupamiento)
		WHERE p.fecha_hasta is null
 		ORDER BY p.fecha_desde
SQL;
		$lista	= $cnx->consulta(Conexiones::SELECT,  $sql);
		$rta = [];
		if($lista){
			foreach ($lista as $key => $value) {
				$value['fecha_desde']	= \DateTime::createFromFormat('Y-m-d', $value['fecha_desde'])->format('d/m/Y');
				$rta[$value['id']]	= $value;
			}	
		}
		return $rta;
	}

	public function baja(){
		return false;
	}

	public function modificacion(){
		$campos	= ['fecha_desde','fecha_hasta','id_nivel','id_tramo','creditos'];
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
		$sql	= 'UPDATE promocion_creditos SET '.implode(',', $campos).' WHERE id = :id';
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'PromocionCredito';
			Logger::event('modificacion', $datos);
		}
		return $res;
	}


	public function validar() {
		$campos = (array)$this;
		 $reglas		= [
			'fecha_desde'	=> ['required', 'fecha'],
			'fecha_hasta'	=> ['fecha','mayor_que(:fecha_desde)' =>
			function($input,$fecha_desde){
				if(empty($input)) return true;
				if(!($input instanceof \DateTime && !empty($input)) && ($fecha_desde instanceof \DateTime && !empty($fecha_desde))){
					return false;
				}

				if($input < $fecha_desde){
					return false;
				}
				return true;
			}],
			'id_nivel' => ['required','integer', 'no_duplicidad(:id_tramo,:fecha_hasta)' => function($input,$id_tramo=null,$fecha_hasta=null){
				if(!empty($id_tramo) && !empty($input) && $fecha_hasta instanceof \DateTime && !empty($fecha_hasta)){
					return true;
				}
				$sql	= 'SELECT COUNT(id) AS valid FROM promocion_creditos WHERE id_tramo = :id_tramo AND id_nivel = :id_nivel AND borrado = 0 AND fecha_hasta IS NULL LIMIT 1';
				$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, [
					':id_tramo'	=> $id_tramo,
					':id_nivel'	=> $input
				]);
				return empty($res[0]['valid']);
			}],
			'id_tramo' => ['required','integer', 'no_duplicidad(:id_nivel,:fecha_hasta)' => function($input,$id_nivel=null,$fecha_hasta=null){
				if(!empty($id_nivel) && !empty($input) && $fecha_hasta instanceof \DateTime && !empty($fecha_hasta)){
					return true;
				}
				$sql	= 'SELECT COUNT(id) AS valid FROM promocion_creditos WHERE id_tramo = :id_tramo AND id_nivel = :id_nivel AND borrado = 0 AND fecha_hasta IS NULL LIMIT 1';
				$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, [
					':id_tramo'	=> $input,
					':id_nivel'	=> $id_nivel,
				]);
				return empty($res[0]['valid']);
			}],
			'creditos' => ['required','integer'],
			
		];
		$nombres	= [
			'fecha_desde' => 'Fecha Vigencia',
			'fecha_hasta' => 'Fecha Cierre',
			'id_nivel'		=> 'Nivel',
			'id_tramo'		=> 'Tramo',
			'creditos'		=> 'Creditos'		 	
		];

		$validator = Validator::validate($campos, $reglas, $nombres);
		$validator->customErrors([
            'mayor_que'	=> 'La <strong>Fecha de Cierre</strong> debe ser mayor o igual a la <strong>Fecha de Vigencia</strong>.',           
            'no_duplicidad'	=> 'Ya existe un registro vigente para este tramo y nivel.',
        ]);
		if ($validator->isSuccess()) {
			return true;
		}
		$this->errores = $validator->getErrors();

		return false;
	}

	static public function arrayToObject($res = []) {
		$campos	= [
			'id'			=> 'int',
			'fecha_desde'	=> 'date',
			'fecha_hasta'	=> 'date',
			'id_nivel'		=> 'int',
			'id_tramo'		=> 'int',
			'creditos'		=> 'int'
		];
		$obj = parent::arrayToObject($res,$campos);
		foreach ($campos as $campo => $type) {
			switch ($type) {
				case 'int':
					$obj->{$campo}	= isset($res[$campo]) ? (int)$res[$campo] : null;
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