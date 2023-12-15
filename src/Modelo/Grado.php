<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class Grado extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_tramo;
/** @var string */
	public $nombre;
/** @var stdClass */
	public $tramo;

	static public function obtener($id=null){
		$obj	= new static;
		if($id===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id'	=> $id,
		];
		$campos	= 'gr.' . implode(', gr.', [
			'id',
			'id_tramo',
			'nombre',
		]);
		$campos	= $campos . ', tra.' . implode(', tra.', [
			'nombre  AS nombre_tramo',
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
			FROM convenio_grados gr
			INNER JOIN convenio_tramos tra ON tra.id = gr.id_tramo
			INNER JOIN convenio_modalidad_vinculacion mv ON mv.id = tra.id_modalidad_vinculacion
			INNER JOIN convenio_situacion_revista sr ON sr.id = tra.id_situacion_revista
			WHERE gr.id = :id
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
			'id_tramo',
			'nombre',
			'borrado',
		]);
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM convenio_grados
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
			':id_tramo' => $this->id_tramo,
			':nombre' => $this->nombre
		];
		$sql = 'INSERT INTO convenio_grados(nombre, id_tramo) VALUES (:nombre, :id_tramo)';
		$res = $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);

		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Grado';
			Logger::event('alta_convenio_grado', $datos);
		}
		return $res;
	}

	public function modificacion(){
		$cnx = new Conexiones();
		$sql_params	= [
			':id'		=> $this->id,
			':id_tramo' => $this->id_tramo,
			':nombre'	=> $this->nombre,
		];
		$sql = 'UPDATE convenio_grados SET id_tramo = :id_tramo, nombre = :nombre WHERE id = :id';
		$res = $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Grado';
			Logger::event('modificacion_convenio_grado', $datos);
		}
		return $res;
	}

	public function baja(){
		$cnx	= new Conexiones();
		$sql_params	= [
			':id'		=> $this->id,
		];
		$sql	= 'UPDATE convenio_grados SET borrado = 1 WHERE id = :id';
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);

		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'Grado';
			if (is_numeric($res) && $res > 0) {
				$flag = true;
			} else {
				$datos['error_db'] = $cnx->errorInfo;
			}
			Logger::event('baja_convenio_grado', $datos);
		}
		return $flag;
	}

	public static function listadoGrados($params=array(), $count = false) {
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
		    g.id,
		    g.nombre AS grado,
			t.nombre AS tramo,
			mv.nombre AS modalidad,
			sr.nombre AS revista
SQL;

		$from = <<<SQL
			FROM convenio_grados g
			INNER JOIN convenio_tramos t  ON t.id =  g.id_tramo
			INNER JOIN convenio_modalidad_vinculacion mv ON mv.id = t.id_modalidad_vinculacion
			INNER JOIN convenio_situacion_revista sr ON sr.id = t.id_situacion_revista
SQL;

		$order =
 		<<<SQL
 			ORDER BY 
SQL;
 		$condicion =
 		<<<SQL
 			WHERE g.borrado = 0
SQL;

    $counter_query	= "SELECT COUNT(DISTINCT g.id) AS total {$from}";

	$recordsTotal	=  $cnx->consulta(Conexiones::SELECT, $counter_query . $condicion, $sql_params)[0]['total'];

	if(!empty($params['search'])){
		$indice = 0;
		$search[]	= <<<SQL
		(g.nombre LIKE :search{$indice} OR
		 mv.nombre LIKE :search{$indice} OR 
		 sr.nombre LIKE :search{$indice} OR  
		 t.nombre LIKE :search{$indice})
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
			'id_tramo'	=> ['required', 'integer'],
			'nombre'	=> ['required', 'texto'],
		];
		$nombres	= [
			'id_tramo'	=> 'Tramo',
			'nombre'	=> 'Grado', 	
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
			'id'		=> 'int',
			'id_tramo' 	=> 'int',
			'nombre'	=> 'string',
		];
		$obj = parent::arrayToObject($res, $campos);

		$obj->tramo								= new \StdClass();
		$obj->tramo->nombre_tramo				= \FMT\Helper\Arr::get($res,'nombre_tramo');
		$obj->tramo->id_modalidad_vinculacion	= \FMT\Helper\Arr::get($res,'id_mod_vinc');
		$obj->tramo->nombre_modalidad			= \FMT\Helper\Arr::get($res,'nombre_modalidad');
		$obj->tramo->id_situacion_revista		= \FMT\Helper\Arr::get($res,'id_sit_revista');
		$obj->tramo->nombre_revista				= \FMT\Helper\Arr::get($res,'nombre_revista');

		return $obj;
	}
}
