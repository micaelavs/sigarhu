<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class Tramo extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_modalidad_vinculacion;
/** @var int */
	public $id_situacion_revista;
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
		$campos	= implode(',', [
			'id',
			'id_modalidad_vinculacion',
			'id_situacion_revista',
			'nombre',
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM convenio_tramos
			WHERE id = :id
SQL;
		$cnx = new Conexiones();
		$res = $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}
	 
	static public function listar() {
		$campos	= implode(',', [
			'id_modalidad_vinculacion',
			'id_situacion_revista',
			'nombre',
			'borrado',
		]);
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM convenio_tramos
			ORDER BY id ASC
SQL;
		$cnx = new Conexiones();
		$resp = $cnx->consulta(Conexiones::SELECT, $sql);
		if(empty($resp)) { return []; }
		foreach ($resp as &$value) {
			$value	= static::arrayToObject($value);
		}
		return $resp;
	}

	public function alta(){
		$cnx = new Conexiones();
		$campos	= [
			'id_modalidad_vinculacion',
			'id_situacion_revista',
			'nombre',
		];
		$sql_params	= [
		];
		foreach ($campos as $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
		}
		$sql = 'INSERT INTO convenio_tramos('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res = $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Tramo';
			Logger::event('alta_convenio_tramos', $datos);
		}
		return $res;
	}

	public function modificacion(){
		$cnx = new Conexiones();
		$campos	= [
			'id_modalidad_vinculacion',
			'id_situacion_revista',
			'nombre',
		];
		$sql_params	= [
			':id'	=> $this->id,
		];
		foreach ($campos as $key => $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
			unset($campos[$key]);
			$campos[$campo]	= $campo .' = :'.$campo;
		}

		$sql = 'UPDATE convenio_tramos SET '.implode(',', $campos).' WHERE id = :id';
		$res = $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Tramo';
			Logger::event('modificacion_convenio_tramo', $datos);
		}
		return $res;
	}

	public function baja(){
		$cnx	= new Conexiones();
		$sql_params	= [
			':id' => $this->id,
		];
		$sql = 'UPDATE convenio_tramos SET borrado = 1 WHERE id = :id';
		$res = $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);

		$flag = false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'Tramo';
			if (is_numeric($res) && $res > 0) {
				$flag = true;
			} else {
				$datos['error_db'] = $cnx->errorInfo;
			}
			Logger::event('baja_convenio_tramo', $datos);
		}
		return $flag;
	}

	public static function listadoTramos($params=array(), $count = false) {
		$cnx	= new Conexiones();
		$sql_params = [];
		$where = [];
		$condicion = '';
		$order = '';
		$search = [];

		$default_params	= [
			'order'		=> [
				[
					'campo'	=> 'nombre',
					'dir'	=> 'ASC',
				],
			],
			'start'		=> 0,
			'lenght'	=> 10,
			'search'	=> '',
			'count'		=> false
		];

		$params	= array_merge($default_params, $params);

		$sql= <<<SQL
			SELECT 
		    t.id,
		    t.nombre AS tramo,
			mv.nombre AS modalidad_vinculacion,
			sr.nombre AS situacion_revista
SQL;

		$from = <<<SQL
			FROM convenio_tramos t
			INNER JOIN convenio_modalidad_vinculacion mv  ON mv.id =  t.id_modalidad_vinculacion
			INNER JOIN convenio_situacion_revista sr  ON sr.id =  t.id_situacion_revista
SQL;

		$order =
 		<<<SQL
 			ORDER BY 
SQL;
 		$condicion =
 		<<<SQL
 			WHERE t.borrado = 0 AND mv.borrado = 0 AND sr.borrado = 0
SQL;

    $counter_query	= "SELECT COUNT(DISTINCT t.id) AS total {$from}";

	$recordsTotal	=  $cnx->consulta(Conexiones::SELECT, $counter_query . $condicion, $sql_params)[0]['total'];

	if(!empty($params['search'])){
		$indice = 0;
		$search[]	= <<<SQL
		(t.nombre LIKE :search{$indice} OR 
		 mv.nombre LIKE :search{$indice} OR
		 sr.nombre LIKE :search{$indice})
SQL;
		$texto = $params['search'];	
		$sql_params[":search{$indice}"]	= "%{$texto}%";

		$buscar =  implode(' AND ', $search);
		$condicion .= empty($condicion) ? "{$buscar}" : " AND {$buscar} ";
	}

	/**Orden de las columnas */
	$ordena = [];
		foreach ($params['order'] as $i => $val) {
			$ordena[]	= "{$val['campo']} {$val['dir']}";
		}

	$order 	.=  implode(',', $ordena);

	/**Limit: funcionalidad: desde-hasta donde se pagina */
	$limit	= (isset($params['lenght']) && isset($params['start']))
						? " LIMIT  {$params['start']}, {$params['lenght']}"	:	' ';

	$recordsFiltered= $cnx->consulta(Conexiones::SELECT, $counter_query. $condicion,  $sql_params)[0]['total'];

	$lista	= $cnx->consulta(Conexiones::SELECT,  $sql .$from. $condicion . $order . $limit, $sql_params);

		if($lista){
			foreach ($lista as $key => &$value) {
				$value	= (object)$value;
			}
		}
		return [
			'recordsTotal'    => !empty($recordsTotal) ? $recordsTotal : 0,
			'recordsFiltered' => !empty($recordsFiltered) ? $recordsFiltered : 0,
			'data'            => $lista ? $lista : [],
		];
	}

	public function validar() {
		$campos = (array)$this;
		$reglas		= [
			'id_modalidad_vinculacion'	=> ['required', 'integer'],
			'id_situacion_revista'		=> ['required', 'integer'],
			'nombre'					=> ['required', 'texto'],
		];
		$nombres	= [
			'id_modalidad_vinculacion'	=> 'Modalidad de Vinculación',
			'id_situacion_revista'		=> 'Situación de Revista',
			'nombre'					=> 'Tramo', 	
		];
		$validator	= Validator::validate($campos, $reglas, $nombres);
		if ($validator->isSuccess()) {
			return true;
		}
		$this->errores = $validator->getErrors();
		return false;
	}

	static public function arrayToObject($res = []) {
		$campos	= [
			'id'						=> 'int',
			'id_modalidad_vinculacion' 	=> 'int',
			'id_situacion_revista' 		=> 'int',
			'nombre'					=> 'string',
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

	public function getTramos($param){
		$cnx = new Conexiones();
		$sql_params	= [
			':id_modalidad'		=> $param->id_modalidad_vinculacion,
			':id_revista'		=> $param->id_situacion_revista,
		];
		$sql = 'SELECT id, nombre, borrado 
				FROM convenio_tramos 
				WHERE id_modalidad_vinculacion = :id_modalidad
				AND id_situacion_revista = :id_revista';
		$res = $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		$aux = [];
		if($res) {			
			 foreach ($res as $value) {
		    	$aux[$value['id']] = $value;
		    }
		}
	    return $aux;
	}

	public function ajaxGetTramos($param){
		$cnx = new Conexiones();
		$sql_params	= [
			':id_modalidad'		=> $param['id_modalidad'],
			':id_revista'		=> $param['id_revista'],
		];
		$campos	= implode(',', [
			'id',
			'nombre',
			'borrado',
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM convenio_tramos
			WHERE id_modalidad_vinculacion = :id_modalidad
			AND id_situacion_revista = :id_revista
			AND borrado = 0
SQL;
		$res = $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		if($res) {			
			return $res;
		}else{
			return false;
		}
	    
	}
}
