<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class PresupuestoProyecto extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_programa;
/** @var int */
	public $id_subprograma;
/** @var int */
	public $codigo;
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
			'id_programa',
			'id_subprograma',
			'codigo',
			'nombre',
			'borrado'
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM presupuesto_proyectos
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
			'id_programa',
			'id_subprograma',
			'codigo',
			'nombre',
			'borrado'
		]);
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM presupuesto_proyectos
			ORDER BY id ASC
SQL;
		$resp	= (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql);
		if(empty($resp)) { return []; }
		foreach ($resp as &$value) {
			$value	= static::arrayToObject($value);
		}
		return $resp;
	}

	public function alta(){
		$cnx = new Conexiones();
		$campos	= [
			'id_programa',
			'id_subprograma',
			'codigo',
			'nombre',
		];
		$sql_params	= [
			':id_programa' 	=> $this->id_programa,
			'id_subprograma'=> $this->id_subprograma,
			':codigo' 		=> $this->codigo,
			':nombre' 		=> $this->nombre
		];
		$sql = 'INSERT INTO presupuesto_proyectos('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res = $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);

		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'PresupuestoProyecto';
			Logger::event('alta', $datos);
		}else{
			$this->errores = $cnx->errorInfo;
		}
		return $res;
	}

	public function modificacion(){
		$cnx = new Conexiones();
		$campos	= [
			'id_programa'		=> 'id_programa = :id_programa',
			'id_subprograma'	=> 'id_subprograma = :id_subprograma',
			'codigo'			=> 'codigo = :codigo',
			'nombre'			=> 'nombre = :nombre',
		];
		$sql_params	= [
			':id'				=> $this->id,
			':id_programa'		=> $this->id_programa,
			':id_subprograma'	=> $this->id_subprograma,
			':codigo'			=> $this->codigo,
			':nombre'			=> $this->nombre,
		];
		$sql = 'UPDATE presupuesto_proyectos SET '.implode(',', $campos).' WHERE id = :id';
		$res = $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'PresupuestoProyecto';
			Logger::event('modificacion', $datos);
			return true;
		}
		return false;
	}

	public function baja(){
		$cnx	= new Conexiones();
		$sql_params	= [
			':id'		=> $this->id,
		];
		$sql	= 'UPDATE presupuesto_proyectos SET borrado = 1 WHERE id = :id';
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);

		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'PresupuestoProyecto';
			if (is_numeric($res) && $res > 0) {
				$flag = true;
			} else {
				$datos['error_db'] = $cnx->errorInfo;
			}
			Logger::event('baja', $datos);
		}
		return $flag;
	}

	public static function listadoProyectos($params=array(), $count = false) {
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
		    p.id,
		    p.codigo,
		    p.nombre,
		    pr.nombre AS programa,
		    sp.nombre AS subprograma
SQL;

		$from = <<<SQL
			FROM presupuesto_proyectos p
			INNER JOIN presupuesto_programas pr ON pr.id = p.id_programa
			LEFT JOIN presupuesto_subprogramas sp ON sp.id = p.id_subprograma
SQL;

		$order =
 		<<<SQL
 			ORDER BY 
SQL;
 		$condicion =
 		<<<SQL
 			WHERE p.borrado = 0
SQL;

    $counter_query	= "SELECT COUNT(DISTINCT p.id) AS total {$from}";

	$recordsTotal	=  $cnx->consulta(Conexiones::SELECT, $counter_query . $condicion, $sql_params)[0]['total'];

	if(!empty($params['search'])){
		$indice = 0;
		$search[]	= <<<SQL
		(p.codigo LIKE :search{$indice} OR
		 p.nombre LIKE :search{$indice} OR
		 sp.nombre LIKE :search{$indice} OR
		 pr.nombre LIKE :search{$indice}) 
SQL;
		$texto = $params['search'];	
		$sql_params[":search{$indice}"]	= "%{$texto}%";

		$buscar =  implode(' AND ', $search);
		$condicion .= empty($condicion) ? "{$buscar}" : " AND {$buscar} ";
	}

	/**Orden de las columnas */
	$orderna = [];
	foreach ($params['order'] as $i => $val) {
		$orderna[]	= "{$val['campo']} {$val['dir']}";
	}

	$order 	.=  implode(',', $orderna);

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
			'id_programa'	=> ['required', 'integer'],
			'id_subprograma'=> ['integer'],
			'id' 		=> ['numeric'],
			'codigo'	=> ['required','integer'],
			'nombre'	=> ['required','texto', 'min_length(5)', 'max_length(200)', 
							'proyecto_unico(:id_programa,:id_subprograma,:id,:codigo)' => function($input, $id_programa, $id_subprograma, $id, $codigo){
				$params = [':nombre' => $input, ':id_programa'  => $id_programa, ':id_subprograma' => $id_subprograma,  ':codigo' => $codigo];
				$where_id = '';
				if($id){
					$where_id = " AND id != :id";
					$params[':id'] = $id;
				}

				$sql = <<<SQL
							SELECT count(*) count FROM presupuesto_proyectos WHERE nombre = :nombre  AND id_programa = :id_programa AND id_subprograma = :id_subprograma AND codigo = :codigo $where_id
SQL;
				$con = new Conexiones();
				$res = $con->consulta(Conexiones::SELECT, $sql, $params);
				if (is_array($res) && isset($res[0]) && isset($res[0]['count'])) {
					return ($res[0]['count'] == 0);
				}
				return false;
			} ],
			'borrado' 	=> ['numeric']
		];
		$nombres	= [
			'id_programa'	=> 'Programa',
			'id_subprograma'=> 'Subrograma',
			'codigo'		=> 'Código',
			'nombre'		=> 'Nombre de Presupuesto', 	
		];
		$validator	= Validator::validate($campos, $reglas, $nombres);
		$validator->customErrors([
            'proyecto_unico'      => 'Ya existe un Proyecto con el programa, subprograma, código y nombre.',
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
			'id_programa'	=> 'int',
			'id_subprograma'=> 'int',
			'codigo'		=> 'int',
			'nombre'		=> 'string',
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

	public function getProyectos(){
		$cnx = new Conexiones();
		$sql = 'SELECT id, codigo, nombre, borrado FROM presupuesto_proyectos';
		$res = $cnx->consulta(Conexiones::SELECT, $sql);
		$aux = [];
		if($res) {			
			 foreach ($res as $value) {
				$value['nombre'] = $value['codigo'].' - '.$value['nombre'];
		    	$aux[$value['id']] = $value;
		    }
		}
	    return $aux;
	}

/**
 * Metodo sin documentación
 *
 * @param array $param
 * @return array
 */
	static public function ajaxGetProyectos($param=array()){
		if(empty($param['id_programa']) || empty($param['id_subprograma'])){
			return [];
		}
		$cnx = new Conexiones();
		$sql_params	= [
			':id_programa'		=> $param['id_programa'],
			':id_subprograma'	=> $param['id_subprograma'],
		];
		$campos	= implode(',', [
			'id',
			'codigo',
			'nombre',
			'borrado',
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM presupuesto_proyectos
			WHERE id_programa = :id_programa
			AND id_subprograma = :id_subprograma
			AND borrado = 0
SQL;
		$res = $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		if($res) {
			array_walk($res, function(&$value){
				$value['nombre'] = $value['codigo'].' - '.$value['nombre'];
			});
			return $res;
		}else{
			return [];
		}
	    
	}
}