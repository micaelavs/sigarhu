<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class Puesto extends Modelo {

/**@var int*/
	public $id;
/**@var string*/
	public $nombre;
/**@var int*/
	public $id_familia;
/**@var string*/
	public $familia;
/**@var int*/
	public $id_subfamilia;
/**@var string*/
	public $subfamilia;
/** @var int */
	public $borrado;


 	public static function obtener($id = null){
    	$obj	= new static;
		if($id===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id'	=> $id,
		];
		$campos	= implode(',', [
			'id',
			'id_subfamilia',
			'nombre',
			'borrado'
		
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM puestos
			WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
  }

	public static function obtener_subfamilia($id = null){
		$obj	= new static;
		if($id===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id'	=> $id,
		];

		$sql	= <<<SQL
			SELECT id AS id_subfamilia,id_familia,nombre AS subfamilia
			FROM subfamilia_puestos
			WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
  }

	public static function obtener_familia_puesto($id = null){
		$obj	= new static;
		if($id===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id'	=> $id,
		];

		$sql	= <<<SQL
			SELECT id AS id_familia, nombre AS familia
			FROM familia_puestos
			WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
  }


	public static function listar_puesto(){
		$conexion = new Conexiones();
		$resultado = $conexion->consulta(Conexiones::SELECT,
		'SELECT
		p.id,
		p.id_subfamilia,
		sf.nombre subfamilia,
		p.nombre,
		p.borrado
		FROM puestos p
		LEFT JOIN subfamilia_puestos sf ON sf.id = p.id_subfamilia
		WHERE p.borrado = 0');
		$aux = [];
		foreach ($resultado as $value) {
			$aux[$value['id']] = $value;
		}
		return $aux;
	}

	public static function listar_subfamilia(){
		$conexion = new Conexiones();
		$resultado = $conexion->consulta(Conexiones::SELECT,
		'SELECT
		id,
		id_familia,
		nombre,
		borrado
		FROM subfamilia_puestos
		WHERE borrado = 0');
		$aux = [];
		foreach ($resultado as $value) {
			$aux[$value['id']] = $value;
		}
		return $aux;
	}

	public static function listar_familia_puesto(){
		$conexion = new Conexiones();
		$resultado = $conexion->consulta(Conexiones::SELECT,
		'SELECT
		id,
		nombre,
		borrado
		FROM familia_puestos
		WHERE borrado = 0');
		$aux = [];
		foreach ($resultado as $value) {
			$aux[$value['id']] = $value;
		}
		return $aux;
	}

	public static function listado_puestos_ajax($params=array(), $count = false){
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
		    p.id_subfamilia,
		    sf.nombre as subfamilia,
			p.nombre,
			p.borrado
SQL;

		$from = <<<SQL
			FROM puestos p
			INNER JOIN subfamilia_puestos sf  ON sf.id =  p.id_subfamilia
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
		((p.nombre LIKE :search{$indice}) OR 
		(sf.nombre LIKE :search{$indice}))  

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


	

	public function alta(){
		$campos	= [
		'id_subfamilia',
		'nombre'
		];
		$sql_params	= [
		];
		foreach ($campos as $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
		}
		$camp	= implode(',', $campos);
		$val 	= implode(',:', $campos);
		$sql	= <<<SQL
		INSERT INTO puestos($camp) VALUES (:$val)
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'puestos';
			Logger::event('alta', $datos);
		}
		return $res;
	}

	public function alta_subfamilia(){

		$sql	= 'INSERT INTO subfamilia_puestos(id_familia, nombre) VALUES (:id_familia, :nombre)';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, [':id_familia' => $this->id_familia,
		':nombre' => $this->subfamilia]);

		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'subfamilia_puestos';
			Logger::event('alta', $datos);
		}
		return $res;
	}

	public function alta_familia_puesto(){
		$sql	= 'INSERT INTO familia_puestos(nombre) VALUES (:nombre)';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, [':nombre' => $this->familia]);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'familia_puestos';
			Logger::event('alta', $datos);
		}
		return $res;
	}

	public function modificacion(){
		$campos	= [
		'id_subfamilia',
		'nombre'
		];
		$sql_params	= [
		':id'	=> $this->id,
		];
		foreach ($campos as $key => $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
			unset($campos[$key]);
			$campos[$campo]	= $campo .' = :'.$campo;
		}
		$aux = implode(',', $campos);
		$sql	= <<<SQL
		UPDATE puestos SET $aux WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'puestos';
			Logger::event('modificacion', $datos);
		}
		return $res;
	}

	public function modificacion_subfamilia(){

		$campos	= [
		'id_familia',
		'nombre'
		];

		foreach ($campos as $key => $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
			unset($campos[$key]);
			$campos[$campo]	= $campo .' = :'.$campo;
		}
		$aux = implode(',', $campos);

		$sql	= <<<SQL
		UPDATE subfamilia_puestos SET $aux WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, [':id'	=> $this->id_subfamilia,':id_familia' => $this->id_familia,':nombre' => $this->subfamilia]);

		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'subfamilia_puestos';
			Logger::event('modificacion', $datos);
		}
		return $res;
	}

	public function modificacion_familia_puesto(){
		$campos	= [
		'nombre',
		];
		foreach ($campos as $key => $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
			unset($campos[$key]);
			$campos[$campo]	= $campo .' = :'.$campo;
		}
		$aux = implode(',', $campos);

		$sql	= <<<SQL
		UPDATE familia_puestos SET $aux WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql,[':id'	=> $this->id_familia,':nombre' => $this->familia]);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'familia_puestos';
			Logger::event('modificacion', $datos);
		}
		return $res;
	}

	public function baja(){
		$mbd = new Conexiones;
		$params = [':id' => $this->id];
		$sql = <<<SQL
		UPDATE puestos SET  borrado = 1 WHERE id = :id
SQL;
		$res = $mbd->consulta(Conexiones::SELECT, $sql, $params);
		if ($res !== false) {
			$datos = (array) $this;
			$datos['modelo'] = 'puestos';
			Logger::event('baja', $datos);
		} else {
			$datos['error_db'] = $conexion->errorInfo;
			Logger::event("error_baja",$datos);
		}
		return $res;
	}


	public function baja_subfamilia(){
		$mbd = new Conexiones;
		$params = [':id' => $this->id_subfamilia];
		$sql = <<<SQL
		UPDATE subfamilia_puestos SET  borrado = 1 WHERE id = :id
SQL;
		$res = $mbd->consulta(Conexiones::SELECT, $sql, $params);
		if ($res !== false) {
			$datos = (array) $this;
			$datos['modelo'] = 'subfamilia_puestos';
			Logger::event('baja', $datos);
		} else {
			$datos['error_db'] = $conexion->errorInfo;
			Logger::event("error_baja",$datos);
		}
		return $res;
	}

	public function baja_familia_puesto(){
		$mbd = new Conexiones;
		$params = [':id' => $this->id_familia];
		$sql = <<<SQL
		UPDATE familia_puestos SET  borrado = 1 WHERE id = :id
SQL;
		$res = $mbd->consulta(Conexiones::SELECT, $sql, $params);
		if ($res !== false) {
			$datos = (array) $this;
			$datos['modelo'] = 'familia_puestos';
			Logger::event('baja', $datos);
		} else {
			$datos['error_db'] = $conexion->errorInfo;
			Logger::event("error_baja",$datos);
		}
		return $res;
	}

	public function validar(){

		switch (true) {
			case ($this->id_subfamilia && $this->nombre):
				//validacion de puesto
				$rules =  [
					'id_subfamilia' => [ 'required','numeric' ],
					'nombre' 	=> [ 'required','texto', 'min_length(3)',
					'puesto_unico(:id_subfamilia,:id)' => function($input, $id_subfamilia, $id){
					$params = [':nombre' => $input, ':id_subfamilia' => $id_subfamilia];
					$where_id = '';
					if($id){
						$where_id = " AND id != :id";
						$params[':id'] = $id;
					}

					$sql = <<<SQL

						SELECT count(*) count FROM puestos WHERE nombre = :nombre AND id_subfamilia = :id_subfamilia AND borrado = 0 $where_id
SQL;
					$con = new Conexiones();
					$res = $con->consulta(Conexiones::SELECT, $sql, $params);
					if (is_array($res) && isset($res[0]) && isset($res[0]['count'])) {
						return !($res[0]['count'] > 0);
					}
					return false;
					}],
				];
				break;

			case ($this->id_familia && $this->subfamilia):
				//validacion de subfamilia
				$rules =  [
					'id_familia' => [ 'required','numeric' ],
					'subfamilia' => [ 'required','texto', 'min_length(3)',
							'subfamilia_unica(:id_familia,:id_subfamilia)' => function($input, $id_familia, $id_subfamilia){
						$params = [':subfamilia' => $input, ':id_familia' => $id_familia];
						$where_id = '';
						if($id_subfamilia){
							$where_id = " AND id != :id";
							$params[':id'] = $id_subfamilia;
						}

						$sql = <<<SQL

							SELECT count(*) count FROM subfamilia_puestos WHERE nombre = :subfamilia AND id_familia = :id_familia AND borrado=0 $where_id

SQL;
						$con = new Conexiones();
						$res = $con->consulta(Conexiones::SELECT, $sql, $params);
						if (is_array($res) && isset($res[0]) && isset($res[0]['count'])) {
							return !($res[0]['count'] > 0);
						}
						return false;
					}]
				];
				break;
			case ($this->familia):
				//validacion de familia
				$rules = [
					'id_familia' => ['numeric'],
					'familia' => [ 'required','texto', 'min_length(3)', 'max_length(200)', 'unico(familia_puestos, nombre,:id_familia,:id, 0)' ],
					'borrado' 	 => ['numeric']
				];
				break;
			default:
				$rules	= [
					'nombre' 	=> [ 'required','texto', 'min_length(3)'],
				];
				break;
		}

		$nombres	= [
			'id_subfamilia'	 => 'Subfamilia',
			'nombre'		 => 'Puesto',
			'id_familia'	 => 'Familia de Puesto',
			'familia'		 => 'Nombre de Familia de Puesto',
			'subfamilia'	 => 'Nombre de Subfamilia de Puesto'
		 ];

		$validator = Validator::validate((array)$this, $rules, $nombres);
		$validator->customErrors([
            'puesto_unico'     		 => 'Ya existe un Puesto con la misma Subfamilia y nombre.',
            'subfamilia_unica'       => 'Ya existe una Subfamilia con la misma Familia de puestos y nombre.',

        ]);

		if ($validator->isSuccess()) {
			return true;
		}
		else {
			$this->errores = $validator->getErrors();
			return false;
		}
	}

	static public function getPuesto($id_familia){
		$aux= [];
		$sql	= <<<SQL
			SELECT sp.id AS id_subfamilia, sp.nombre AS subfamilia_nombre,p.id AS id_puesto, p.nombre AS puesto_nombre
			FROM subfamilia_puestos sp
			INNER JOIN puestos p ON sp.id = p.id_subfamilia
			WHERE sp.id_familia = :id_familia
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::SELECT, $sql, [':id_familia'=> $id_familia]);
		foreach ($res as $value) {
			 $aux[$value['id_subfamilia']]['puestos'][$value['id_puesto']] = ['id'=>$value['id_puesto'], 'nombre' => $value['puesto_nombre'], 'borrado'=>'0'];
			 $aux[$value['id_subfamilia']]['nombre'] =  $value['subfamilia_nombre'];
		}
		return $aux;
	}

	
	static public function arrayToObject($res = []) {
		$campos	= [
		'id'						=> 'int',
		'id_familia'				=> 'int',
		'id_subfamilia'				=> 'int',
		'nombre'					=> 'string',
		'familia'					=> 'string',
		'subfamilia'				=> 'string'
		];
		$obj = new self();
		foreach ($campos as $campo => $type) {
			switch ($type) {
				case 'int':
					$obj->{$campo}	= isset($res[$campo]) ? (int)$res[$campo] : null;
					break;
				default:
					$obj->{$campo}	= isset($res[$campo]) ? $res[$campo] : null;
					break;
			}
		}

		return $obj;
	}
}
