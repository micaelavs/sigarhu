<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class PresupuestoSubPrograma extends Modelo {
/** @var int */
	public $id;
	/** @var int */
	public $id_programa;
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
			'codigo',
			'nombre',
			'borrado'
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM presupuesto_subprogramas
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
			'codigo',
			'nombre',
			'borrado'
		]);
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM presupuesto_subprogramas
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
			'codigo',
			'nombre',
		];
		$sql_params	= [
			':id_programa' 	=> $this->id_programa,
			':codigo' 		=> $this->codigo,
			':nombre' 		=> $this->nombre
		];
		$sql = 'INSERT INTO presupuesto_subprogramas('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res = $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);

		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'PresupuestoSubPrograma';
			Logger::event('alta', $datos);
		}
		return $res;
	}

	public function modificacion(){
		$cnx = new Conexiones();
		$campos	= [
			'id_programa'	=> 'id_programa = :id_programa',
			'codigo'		=> 'codigo = :codigo',
			'nombre'		=> 'nombre = :nombre',
		];
		$sql_params	= [
			':id'			=> $this->id,
			':id_programa'	=> $this->id_programa,
			':codigo'		=> $this->codigo,
			':nombre'		=> $this->nombre,
		];
		$sql = 'UPDATE presupuesto_subprogramas SET '.implode(',', $campos).' WHERE id = :id';
		$res = $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'PresupuestoSubPrograma';
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
		$sql	= 'UPDATE presupuesto_subprogramas SET borrado = 1 WHERE id = :id';
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);

		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'PresupuestoSubPrograma';
			if (is_numeric($res) && $res > 0) {
				$flag = true;
			} else {
				$datos['error_db'] = $cnx->errorInfo;
			}
			Logger::event('baja', $datos);
		}
		return $flag;
	}

	public static function listadoSubProgramas($params=array(), $count = false) {
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
		    sp.id,
		    pr.nombre AS programa,
		    sp.codigo,
		    sp.nombre
SQL;

		$from = <<<SQL
			FROM presupuesto_subprogramas sp
			INNER JOIN presupuesto_programas pr ON pr.id = sp.id_programa
SQL;

		$order =
 		<<<SQL
 			ORDER BY 
SQL;
 		$condicion =
 		<<<SQL
 			WHERE sp.borrado = 0
SQL;

    $counter_query	= "SELECT COUNT(DISTINCT sp.id) AS total {$from}";

	$recordsTotal	=  $cnx->consulta(Conexiones::SELECT, $counter_query . $condicion, $sql_params)[0]['total'];

	if(!empty($params['search'])){
		$indice = 0;
		$search[]	= <<<SQL
		(sp.codigo LIKE :search{$indice} OR
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
			'id' 			=> ['numeric'],
			'codigo'		=> ['required','integer'],
			'nombre'		=> ['required','texto', 'min_length(5)', 'max_length(200)', 
								 'subprograma_unico(:id,:id_programa,:codigo)' => function($input, $id, $id_programa, $codigo){
				$params = [':nombre' => $input, ':id_programa' => $id_programa, ':codigo' => $codigo];
				$where_id = '';
				if($id){
					$where_id = " AND id != :id";
					$params[':id'] = $id;
				}

				$sql = <<<SQL
							SELECT count(*) count FROM presupuesto_subprogramas WHERE nombre = :nombre AND id_programa = :id_programa AND codigo = :codigo $where_id
SQL;
				$con = new Conexiones();
				$res = $con->consulta(Conexiones::SELECT, $sql, $params);
				if (is_array($res) && isset($res[0]) && isset($res[0]['count'])) {
					return ($res[0]['count'] == 0);
				}
				return false;
			} ],
			'borrado' 		=> ['numeric']
		];
		$nombres	= [
			'codigo'		=> 'Código',
			'nombre'		=> 'Programas', 	
		];
		$validator	= Validator::validate($campos, $reglas, $nombres);
		$validator->customErrors([
            'subprograma_unico'      => 'Ya existe un Subprograma con ese programa, código y nombre.',
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

/**
 * Devuelve un array con indice numerico y valor ['nombre' => string]
 *
 * @param int $id_programa
 * @return array
 */
	static public function ajaxSubprogramas($id_programa=null){
		if($id_programa == null){
			return [];
		}
		$cnx = new Conexiones();
		$sql_params	= [
			':id'		=> $id_programa,
		];
		$sql = 'SELECT id, nombre, codigo, borrado FROM presupuesto_subprogramas
				WHERE borrado = 0 AND id_programa = :id ORDER BY nombre ASC';
		$res = $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);

		if($res) {
			array_walk($res, function(&$val){
				$val['nombre'] = $val['codigo'].' - '.$val['nombre'];
			});			
			return $res;
		}else{
			return [];
		}
	}

/**
 * Devuelve un array con indice numerico (ID) y valor ['nombre' => string]
 *
 * @param int $id_programa
 * @return array
 */
	static public function getSubprogramas($id_programa=null){
		if($id_programa == null){
			return [];
		}
		$cnx = new Conexiones();
		$sql_params	= [
			':id'		=> $id_programa,
		];
		$sql = 'SELECT id, nombre, codigo, borrado FROM presupuesto_subprogramas WHERE id_programa = :id';
		$res = $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		$aux = [];
		if($res) {			
			 foreach ($res as $value) {
			 	$value['nombre'] = $value['codigo'].' - '.$value['nombre'];
		    	$aux[$value['id']] = $value;
		    }
		}
	    return $aux;
	}
}
