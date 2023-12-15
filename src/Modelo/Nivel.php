<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class Nivel extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_agrupamiento;
/** @var string */
	public $nombre;
	public $agrupamiento;

	static public function obtener($id=null){
		$obj	= new static;
		if($id===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id'	=> $id,
		];
		$campos	= 'cn.' . implode(', cn.', [
			'id',
			'id_agrupamiento',
			'nombre',
		]);
		$campos	= $campos . ', agrup.' . implode(', agrup.', [
			'nombre  AS nombre_agrup',
		]);
		$campos	= $campos . ', mv.' . implode(', mv.', [
			'id 	 AS id_mod_vinc',
			'nombre  AS nombre_modalidad',
		]);
		$campos	= $campos . ', sr.' . implode(', sr.', [
			'id 	 AS id_sit_revista',
			'nombre  AS nombre_revista',
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM convenio_niveles cn
			INNER JOIN convenio_agrupamientos agrup ON agrup.id = cn.id_agrupamiento
			INNER JOIN convenio_modalidad_vinculacion mv ON mv.id = agrup.id_modalidad_vinculacion
			INNER JOIN convenio_situacion_revista sr ON sr.id = agrup.id_situacion_revista
			WHERE cn.id = :id
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
			'id_agrupamiento',
			'nombre',
			'borrado',
		]);
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM convenio_niveles
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
		$sql_params	= [
			':id_agrupamiento' => $this->id_agrupamiento,
			':nombre' => $this->nombre
		];
		$sql = 'INSERT INTO convenio_niveles(nombre, id_agrupamiento) VALUES (:nombre, :id_agrupamiento)';
		$res = $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);

		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Nivel';
			Logger::event('alta_convenio_nivel', $datos);
		}
		return $res;
	}

	public function modificacion(){
		$cnx = new Conexiones();
		$sql_params	= [
			':id'		=> $this->id,
			':id_agrupamiento' => $this->id_agrupamiento,
			':nombre'	=> $this->nombre,
		];
		$sql = 'UPDATE convenio_niveles SET id_agrupamiento = :id_agrupamiento, nombre = :nombre WHERE id = :id';
		$res = $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Nivel';
			Logger::event('modificacion_convenio_nivel', $datos);
		}
		return $res;
	}

	public function baja(){
		$cnx	= new Conexiones();
		$sql_params	= [
			':id'		=> $this->id,
		];
		$sql	= 'UPDATE convenio_niveles SET borrado = 1 WHERE id = :id';
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);

		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'Nivel';
			if (is_numeric($res) && $res > 0) {
				$flag = true;
			} else {
				$datos['error_db'] = $cnx->errorInfo;
			}
			Logger::event('baja_convenio_nivel', $datos);
		}
		return $flag;
	}

	public static function listadoNiveles($params=array(), $count = false) {
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
		    n.id,
		    n.nombre  AS nivel,
			a.nombre  AS agrupamiento,
			mv.nombre AS modalidad,
			sr.nombre AS revista
SQL;

		$from = <<<SQL
			FROM convenio_niveles n
			INNER JOIN convenio_agrupamientos a  ON a.id =  n.id_agrupamiento
			INNER JOIN convenio_modalidad_vinculacion mv ON mv.id = a.id_modalidad_vinculacion
			INNER JOIN convenio_situacion_revista sr ON sr.id = a.id_situacion_revista
SQL;

		$order =
 		<<<SQL
 			ORDER BY 
SQL;
 		$condicion =
 		<<<SQL
 			WHERE n.borrado = 0 AND a.borrado = 0 AND sr.borrado = 0
SQL;

    $counter_query	= "SELECT COUNT(DISTINCT n.id) AS total {$from}";

	$recordsTotal	=  $cnx->consulta(Conexiones::SELECT, $counter_query . $condicion, $sql_params)[0]['total'];

	if(!empty($params['search'])){
		$indice = 0;
		$search[]	= <<<SQL
		(n.nombre LIKE :search{$indice} OR
		 a.nombre LIKE :search{$indice} OR
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

	$lista		= $cnx->consulta(Conexiones::SELECT,  $sql .$from. $condicion . $order . $limit, $sql_params);

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
			'id_agrupamiento'	=> ['required', 'integer'],
			'nombre'	=> ['required', 'texto'],
		];
		$nombres	= [
			'id_agrupamiento'	=> 'Agrupamiento',
			'nombre'		=> 'Nivel', 	
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
			'id'				=> 'int',
			'id_agrupamiento' 	=> 'int',
			'nombre'			=> 'string',
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
		$obj->agrupamiento								= new \StdClass();
		$obj->agrupamiento->nombre_agrupamiento			= \FMT\Helper\Arr::get($res,'nombre_agrup');
		$obj->agrupamiento->id_modalidad_vinculacion	= \FMT\Helper\Arr::get($res,'id_mod_vinc');
		$obj->agrupamiento->nombre_modalidad			= \FMT\Helper\Arr::get($res,'nombre_modalidad');
		$obj->agrupamiento->id_situacion_revista		= \FMT\Helper\Arr::get($res,'id_sit_revista');
		$obj->agrupamiento->nombre_revista				= \FMT\Helper\Arr::get($res,'nombre_revista');

		return $obj;
	}
}
