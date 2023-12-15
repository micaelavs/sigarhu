<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use FMT\Usuarios;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;


class Observacion extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_empleado;	
/** @var int */
	public $id_usuario;
/** @var int */
	public $id_bloque;
/** @var Date */
	public $fecha;
/** @var string */
	public $descripcion;
/** @var int */
	public $borrado;

// *
//  * Obtiene los valores de los array parametricos.
//  * E.J.: Dependencia::getParam('NIVEL_ORGANIGRAMA');

	static public function getParam($attr=null){
		if($attr === null || empty(static::${$attr})){
			return [];
		}
		return static::${$attr};
	}
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
			'id_empleado',
			'id_usuario',
			'id_bloque',
			'fecha',
			'descripcion',
			'borrado',
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM observaciones
			WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}

	public function alta(){

		if(!$this->validar()){
			return false;
		}
		$campos	= [
			'id_empleado',
			'id_usuario',
			'id_bloque',
			'fecha',
			'descripcion'
		];
		$sql_params	= [
		];
		foreach ($campos as $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
		}

		if($this->fecha instanceof \DateTime){
			$sql_params[':fecha']	= $this->fecha->format('Y-m-d');
		}


		$sql	= 'INSERT INTO observaciones('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Observacion';
			Logger::event('alta', $datos);
		}
		return $res;
	}

	public function baja(){
		return false;
	}

	public function modificacion(){
		if(!$this->validar() || empty($this->id)){
			return false;
		}

		$campos	= [
			'id_usuario',
			'id_bloque',
			'fecha',
			'descripcion',
			'borrado',
		];
		$sql_params	= [
			':id'	=> $this->id,
		];
		foreach ($campos as $key => $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
			unset($campos[$key]);
			$campos[$campo]	= $campo .' = :'.$campo;
		}

		if($this->fecha instanceof \DateTime){
			$sql_params[':fecha']	= $this->fecha->format('Y-m-d');
		}


		$sql	= 'UPDATE observaciones SET '.implode(',', $campos).' WHERE id = :id';
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Observacion';
			Logger::event('modificacion', $datos);
		}
		return $res;
	}

	public static function listadoObservaciones($params=array(), $count = false) {
		$cnx	= new Conexiones();
		$sql_params = [];
		$where = [];
		$condicion = '';
		$order = '';
		$search = [];

		$default_params	= [
			'order'		=> [
				[
					'campo'	=> 'fecha',
					'dir'	=> 'DESC',
				],
			],
			'start'		=> 0,
			'lenght'	=> 10,
			'search'	=> '',
			'count'		=> false
		];

		$params	= array_merge($default_params, $params);
		$sql_params[':id_empleado'] = $params['id_empleado']; 
		$usuario = Usuarios::getUsuarios();

		$aux = '';
		$auxb = '';
		foreach ($usuario as $value) {
			$aux .= ($aux) ? " union all select {$value['idUsuario']},'{$value['nombre']} {$value['apellido']}'" 
						  : " select {$value['idUsuario']} id,'{$value['nombre']} {$value['apellido']}' nombre";
		}
		foreach (\App\Helper\Bloques::$SOLAPAS as $value) {
			$auxb .= ($auxb) ? " union all select {$value['id']},'{$value['nombre']}'" 
						  : " select {$value['id']} id,'{$value['nombre']}' nombre";
		}

		$sql= <<<SQL
			SELECT o.id,
			o.id_usuario,
			u.nombre usuario,
 			o.id_bloque,
			b.nombre bloque,
		    o.fecha,
		    o.descripcion
SQL;

		$from = <<<SQL
			FROM observaciones o
			LEFT JOIN ({$aux}) u ON o.id_usuario = u.id
			LEFT JOIN ({$auxb}) b ON o.id_bloque = b.id
SQL;

		$order =
 		<<<SQL
 			ORDER BY 
SQL;
	
	$condicion = !empty($where) ? ' WHERE id_empleado = :id_empleado AND o.borrado = 0' . \implode(' AND ',$where) : ' WHERE id_empleado = :id_empleado AND o.borrado = 0';

	$counter_query	= "SELECT COUNT(DISTINCT o.id) AS total {$from}";
	$recordsTotal	=  $cnx->consulta(Conexiones::SELECT, $counter_query.' WHERE id_empleado=:id_empleado' , $sql_params)[0]['total'];
		

	if(!empty($params['search'])){
		$search	= [];
		foreach (explode(' ', (string)$params['search']) as $indice => $texto) {
			$search[]	= <<<SQL
				u.nombre LIKE :search{$indice} OR
				b.nombre LIKE :search{$indice} OR
				fecha LIKE :search{$indice} OR
				descripcion LIKE :search{$indice}
SQL;
			
			$sql_params[":search{$indice}"]	= "%{$texto}%";
		}
		$buscar =  implode(' AND ', $search);
		$condicion .= empty($condicion) ? "{$buscar}" : " AND {$buscar} ";
	}

	/**Orden de las columnas */
	$orderna			= [];
		foreach ($params['order'] as $i => $val) {
			$orderna[]	= "{$val['campo']} {$val['dir']}";
		}

	$order 	.=  implode(',', $orderna);


	/**Limit: funcionalidad: desde-hasta donde se pagina */
	$limit	= (isset($params['lenght']) && isset($params['start']))
						? " LIMIT  {$params['start']}, {$params['lenght']}"	:	' ';

	$recordsFiltered= $cnx->consulta(Conexiones::SELECT, $counter_query . $condicion, $sql_params)[0]['total'];
							
	$lista		= $cnx->consulta(Conexiones::SELECT,  $sql .$from. $condicion . $order . $limit, $sql_params);

		if($lista){
			$id  = Usuario::obtenerUsuarioLogueado();		
			foreach ($lista as $key => &$value) {
				if(isset($value['fecha'])){
					$value['fecha'] = \DateTime::createFromFormat('Y-m-d',$value['fecha'])->format('d/m/Y');
				}
				$aux = Usuario::obtener($value['id_usuario']);
				$value['usuario'] = $aux->fullName();
				$value['id_logueado'] = $id->id;
			}

		}

		return [
			'recordsTotal'    => !empty($recordsTotal) ? $recordsTotal : 0,
			'recordsFiltered' => !empty($recordsFiltered) ? $recordsFiltered : 0,
			'data'            => $lista ? $lista : [],
		];
	}


	public function validar() {
		return true;
		$reglas		= [
			'descripcion'  => ['required']];
		$nombres	= [
			' descripcion'	=> 'Descripción',
		];
		$validator	= Validator::validate((array)$this, $reglas, $nombres);
		if ($validator->isSuccess()) {
			return true;
		}
		$this->errores = $validator->getErrors();
		return false;
	}

	static public function arrayToObject($res = []) {
		$campos	= [
			'id'			=> 'int',
			'id_usuario'	=> 'int',
			'id_bloque'		=> 'int',
			'fecha'		    => 'date',
			'descripcion'	=> 'string',
			'borrado'		=> 'int',
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
}