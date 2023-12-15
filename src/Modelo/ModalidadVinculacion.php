<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class ModalidadVinculacion extends Modelo {
/** @var int */
	public $id;
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
			'nombre',
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM convenio_modalidad_vinculacion
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
			'nombre',
			'borrado',
		]);
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM convenio_modalidad_vinculacion
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
		$sql_params	= [
			':nombre' => $this->nombre
		];
		$sql	= 'INSERT INTO convenio_modalidad_vinculacion(nombre) VALUES (:nombre)';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, $sql_params);

		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'ModalidadVinculacion';
			Logger::event('alta_modalidad_vinculacion', $datos);
		}
		return $res;
	}

	public function modificacion(){
		$sql_params	= [
			':id'		=> $this->id,
			':nombre'	=> $this->nombre,
		];
		$sql	= 'UPDATE convenio_modalidad_vinculacion SET nombre = :nombre WHERE id = :id';
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'ModalidadVinculacion';
			Logger::event('modificacion_modalidad_vinculacion', $datos);
		}
		return $res;
	}

	public function baja(){
		$cnx	= new Conexiones();
		$sql_params	= [
			':id'		=> $this->id,
		];
		$sql	= 'UPDATE convenio_modalidad_vinculacion SET borrado = 1 WHERE id = :id';
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);

		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'ModalidadVinculacion';
			if (is_numeric($res) && $res > 0) {
				$flag = true;
			} else {
				$datos['error_db'] = $cnx->errorInfo;
			}
			Logger::event('baja_modalidad_vinculacion', $datos);
		}
		return $flag;
	}

	public static function listadoModalidades($params=array(), $count = false) {
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
		    nombre
SQL;

		$from = <<<SQL
			FROM convenio_modalidad_vinculacion
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
		(nombre LIKE :search{$indice})
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
			'nombre'	=> ['required', 'texto','unico(convenio_modalidad_vinculacion, nombre)'],
		];
		$nombres	= [
			'nombre'		=> 'Modalidad de Vinculación', 	
		];
		$validator	= Validator::validate($campos, $reglas, $nombres);
		$validator->customErrors([
            'unico' => 'La Modalidad de Vinculación ya se encuentra registrada',                         
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

	public function modalidades(){
		$cnx = new Conexiones();
		$sql = 'SELECT id, nombre, borrado FROM convenio_modalidad_vinculacion';
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
