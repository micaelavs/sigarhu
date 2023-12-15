<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class PresupuestoPrograma extends Modelo {
/** @var int */
	public $id;
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
			'codigo',
			'nombre',
			'borrado'
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM presupuesto_programas
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
			'codigo',
			'nombre',
			'borrado'
		]);
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM presupuesto_programas
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
		$sql_params	= [
			':codigo' => $this->codigo,
			':nombre' => $this->nombre
		];
		$sql = 'INSERT INTO presupuesto_programas(nombre, codigo) VALUES (:nombre, :codigo)';
		$res = $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);

		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'PresupuestoPrograma';
			Logger::event('alta', $datos);
		}
		return $res;
	}

	public function modificacion(){
		$cnx = new Conexiones();
		$campos	= [
			'codigo'	=> 'codigo = :codigo',
			'nombre'	=> 'nombre = :nombre',
		];
		$sql_params	= [
			':id'		=> $this->id,
			':codigo'	=> $this->codigo,
			':nombre'	=> $this->nombre,
		];
		$sql = 'UPDATE presupuesto_programas SET '.implode(',', $campos).' WHERE id = :id';
		$res = $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'PresupuestoPrograma';
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
		$sql	= 'UPDATE presupuesto_programas SET borrado = 1 WHERE id = :id';
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);

		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'PresupuestoPrograma';
			if (is_numeric($res) && $res > 0) {
				$flag = true;
			} else {
				$datos['error_db'] = $cnx->errorInfo;
			}
			Logger::event('baja', $datos);
		}
		return $flag;
	}

	public static function listadoProgramas($params=array(), $count = false) {
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
		    id,
		    codigo,
		    nombre
SQL;

		$from = <<<SQL
			FROM presupuesto_programas
SQL;

		$order =
 		<<<SQL
 			ORDER BY 
SQL;
 		$condicion =
 		<<<SQL
 			WHERE borrado = 0
SQL;

    $counter_query	= "SELECT COUNT(DISTINCT id) AS total {$from}";

	$recordsTotal	=  $cnx->consulta(Conexiones::SELECT, $counter_query . $condicion, $sql_params)[0]['total'];

	if(!empty($params['search'])){
		$indice = 0;
		$search[]	= <<<SQL
		(codigo LIKE :search{$indice} OR
		 nombre LIKE :search{$indice}) 
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
			'id' 		=> ['numeric'],
			'codigo'	=> ['required','integer'],
			'nombre'	=> ['required','texto', 'min_length(5)', 'max_length(200)', 
							'programa_unico(:codigo,:id)' => function($input, $codigo, $id){
				$params = [':nombre' => $input, ':codigo' => $codigo];
				$where_id = '';
				if($id){
					$where_id = " AND id != :id";
					$params[':id'] = $id;
				}

				$sql = <<<SQL
							SELECT count(*) count FROM presupuesto_programas WHERE nombre = :nombre AND codigo = :codigo $where_id
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
			'codigo'	=> 'Código',
			'nombre'	=> 'Programa', 	
		];
		$validator	= Validator::validate($campos, $reglas, $nombres);
		$validator->customErrors([
            'programa_unico'      => 'Ya existe un Programa con el código y nombre.',
        ]);
		if ($validator->isSuccess()) {
			return true;
		}
		$this->errores = $validator->getErrors();
		return false;
	}

	static public function arrayToObject($res = []) {
		$campos	= [
			'id'		=> 'int',
			'codigo'	=> 'int',
			'nombre'	=> 'string',
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

	public function getProgramas(){
		$cnx = new Conexiones();
		$sql = 'SELECT id, nombre, borrado FROM presupuesto_programas';
		$res = $cnx->consulta(Conexiones::SELECT, $sql);
		$aux = [];
		if($res) {			
			 foreach ($res as $value) {
		    	$aux[$value['id']] = $value;
		    }
		}
	    return $aux;
	}
}
