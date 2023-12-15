<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;


class Titulo extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_tipo_titulo;
/** @var string */
	public $nombre;
/** @var string */
	public $abreviatura;
/** @var int */
	public $borrado;

	const PROMOCION_TRAMO = 2;
	const PROMOCION_GRADO = 1;
	
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
			'id_tipo_titulo',
			'nombre',
			'abreviatura'

		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM titulo
			WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}


	public function alta(){
		$campos	= [
			'id_tipo_titulo',
			'nombre',
			'abreviatura'
		];
		$sql_params	= [
		];
		foreach ($campos as $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
		}

		$sql	= 'INSERT INTO titulo('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Titulo';
			Logger::event('alta', $datos);
		}
		return $res;
	}


	static public function listar() {
		$cnx	= new Conexiones();
		$sql= <<<SQL
		SELECT 
		   id,
		   id_tipo_titulo,
		   nombre,
		   abreviatura,
		   borrado
		FROM titulo
 		WHERE borrado = 0
 		ORDER BY nombre;
SQL;
		$lista	= $cnx->consulta(Conexiones::SELECT,  $sql);
		$rta = [];
		if($lista){
			foreach ($lista as $key => $value) {
				$rta[$value['id']]	= $value;
			}	
		}
		return $rta;
	}

	public static function listadoTitulos($params=array(), $count = false) {

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
		   t.id_tipo_titulo,
		   t.nombre,
		   t.abreviatura,
		   n.nombre AS nombre_tipo
SQL;

		$from = <<<SQL
			FROM titulo t
			LEFT JOIN nivel_educativo n  ON t.id_tipo_titulo =  n.id 
SQL;

		$order =
 		<<<SQL
 			ORDER BY 
SQL;
 		$condicion =
 		<<<SQL
 			WHERE t.borrado = 0
SQL;

    $counter_query	= "SELECT COUNT(DISTINCT t.id) AS total {$from}";


	$recordsTotal	=  $cnx->consulta(Conexiones::SELECT, $counter_query . $condicion, $sql_params)[0]['total'];

		if(!empty($params['search'])){
			$search	= [];
			foreach (explode(' ', (string)$params['search']) as $indice => $texto) {
				$search[]	= <<<SQL
					(t.nombre LIKE :search{$indice} OR
					n.nombre LIKE :search{$indice} OR
					t.abreviatura LIKE :search{$indice})
SQL;
				$sql_params[":search{$indice}"]	= "%{$texto}%";
			}
			$buscar =  implode(' AND ', $search);
			$condicion .= empty($condicion) ? " WHERE {$buscar}" : " AND {$buscar} ";
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


	public function baja(){
		$sql_params= [':id' => $this->id];
		$sql = <<<SQL
		update titulo set borrado = 1 where id = :id;
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Titulo';
			Logger::event('baja', $datos);		
		}
		return $res;
	}

	public function modificacion(){

		$campos	= [
			'id_tipo_titulo',
			'nombre',
			'abreviatura'

		];
		$sql_params	= [
			':id'	=> $this->id,
		];
		foreach ($campos as $key => $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
			unset($campos[$key]);
			$campos[$campo]	= $campo .' = :'.$campo;
		}


		$sql	= 'UPDATE titulo SET '.implode(',', $campos).' WHERE id = :id';
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Titulo';
			Logger::event('modificacion', $datos);
		}
		return $res;
	}


	public function validar() {
		$campos = (array)$this;
		 $reglas		= [
		 	'id_tipo_titulo'=> ['required', 'integer'],
		 	//se comenta validación, para poder repetir títulos a pedido del usuario
		 	'nombre'		=> ['required', 'texto',/* 'nombreUnico()' => function($input) use ($campos){
				$where	= '';
				$input		= trim($input);
				$sql_params	= [
					':nombre'			=> '%'.$input.'%',
					':nombre_uppercase'	=> '%'.strtoupper($input).'%',
					':nombre_lowercase'	=> '%'.strtolower($input).'%',
				];
				if(!empty($campos['id'])){
					$where				= ' AND id != :id';
					$sql_params[':id']	= $campos['id'];
				}
				$sql		= 'SELECT nombre FROM titulo WHERE borrado = 0 AND (nombre LIKE :nombre OR nombre LIKE :nombre_uppercase OR nombre LIKE :nombre_lowercase)'.$where;
				$resp	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
				return empty($resp);
			}*/],
		 	'abreviatura'	=> ['texto']
		];
		$nombres	= [
			'id_tipo_titulo'=> 'Tipo Título',
			'nombre'		=> 'Nombre del Título',
		 	'abreviatura'   => 'Abreviatura'
		 	
		];

		$validator	= Validator::validate($campos, $reglas, $nombres);
		/*$validator->customErrors([
			'nombreUnico()' => 'Ya existe un título con el mismo nombre, modifique la existente.',
		]);*/
		if ($validator->isSuccess()) {
			return true;
		}
		$this->errores = $validator->getErrors();
		return false;
	}

	static public function arrayToObject($res = []) {
		$campos	= [
			'id'			=> 'int',
			'id_tipo_titulo'=> 'int',
			'nombre'		=> 'string',
			'abreviatura'	=> 'string'

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
				default:
					$obj->{$campo}	= isset($res[$campo]) ? $res[$campo] : null;
					break;
			}
		}

		return $obj;
	}
}