<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class Ubicacion extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $borrado;
/** @var string */
	public $nombre;
/** @var int */
	public $id_organismo;
/** @var int */
	public $id_edificio;
/** @var int */
	public $id_localidad;
/** @var int */
	public $id_provincia;
/** @var string */
	public $calle;
/** @var int */
	public $numero;
/** @var string */
	public $piso;
/** @var string */
	public $oficina;
/** @var string */
	public $cod_postal;

	static public function obtener($id=null){
		$obj	= new static;
		if($id===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id'	=> $id,
		];
		$sql	= <<<SQL
			SELECT
				u.id,
				u.borrado,
				u.id_organismo,
				u.piso,
				u.oficina,
				u_edificios.id AS id_edificio,
				u_edificios.nombre,
				u_edificios.calle,
				u_edificios.numero,
				u_edificios.id_localidad,
				u_edificios.id_provincia,
				u_edificios.cod_postal
			FROM ubicaciones AS u
			INNER JOIN ubicacion_edificios AS u_edificios ON (u_edificios.id = u.id_edificio)
			WHERE u.id = :id
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}

	static public function listar($array=false) {
		$sql	= <<<SQL
			SELECT
				u.id,
				u.borrado,
				u.piso,
				u.oficina,
				u.id_organismo,
				u_edificios.id AS id_edificio,
				u_edificios.nombre,
				u_edificios.calle,
				u_edificios.numero,
				u_edificios.id_localidad,
				u_edificios.id_provincia,
				u_edificios.cod_postal
			FROM ubicaciones AS u
			INNER JOIN ubicacion_edificios AS u_edificios ON (u_edificios.id = u.id_edificio)
			WHERE u.borrado = 0
			ORDER BY u.id DESC
SQL;
		$cnx	= new Conexiones();
		$resp	= (array)$cnx->consulta(Conexiones::SELECT, $sql);
		if(empty($resp)) { return []; }
		$aux	= [];
		foreach ($resp as $key => &$value) {
			if($array){
				$aux[$value['id']]	= $value;
				unset($resp[$key]);
			} else {
				$resp[$key]	= static::arrayToObject($value);
			}
		}
		return !empty($aux) ? $aux : $resp;
	}

	public function alta(){
		$campos	= [
			'id_edificio',
			'id_organismo',
			'piso',
			'oficina'
			
		];
		$sql_params	= [
		];
		foreach ($campos as $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
		}

		$sql	= 'INSERT INTO ubicaciones('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, $sql_params);

		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Ubicaciones';
			Logger::event('alta', $datos);
		}
		return $res;
	}


	public function baja(){
		$mbd = new Conexiones;
		$params = [':id' => $this->id];
		$sql = <<<SQL
		UPDATE ubicaciones SET  borrado = 1 WHERE id = :id
SQL;
		$res = $mbd->consulta(Conexiones::SELECT, $sql, $params);
		if ($res !== false) {
			$datos = (array) $this;
			$datos['modelo'] = 'Ubicaciones';
			Logger::event('baja', $datos);
		} else {
			$datos['error_db'] = $mbd->errorInfo;
			Logger::event("error_baja",$datos);
		}
		return $res;
	}


	public function modificacion(){
		if(!$this->validar() || empty($this->id)){
			return false;
		}

		$campos	= [
			'id_edificio',
			'id_organismo',
			'piso',
			'oficina'
		];
		$sql_params	= [
			':id'	=> $this->id,
		];
		foreach ($campos as $key => $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
			unset($campos[$key]);
			$campos[$campo]	= $campo .' = :'.$campo;
		}

		$sql	= 'UPDATE ubicaciones SET '.implode(',', $campos).' WHERE id = :id';
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Ubicaciones';
			Logger::event('modificacion', $datos);
		}
		return $res;
	}



	public function validar(){
		$rules = [
			'id' 			=>  ['numeric'],
			'id_edificio' 	=>  ['required', 'numeric' , 'ubicacion_unica(:id,:piso,:oficina)' => function($input, $id, $piso, $oficina){
                $params = [':id_edificio' => $input, ':piso' => $piso, ':oficina' => $oficina];
                $where_id = '';
                if($id){
                    $where_id = " AND id != :id";
                    $params[':id'] = $id;
                }
            $sql = <<<SQL
                        SELECT count(*) count FROM ubicaciones WHERE piso = :piso AND oficina = :oficina AND id_edificio = :id_edificio  $where_id

SQL;
                            $con = new Conexiones();
                            $res = $con->consulta(Conexiones::SELECT, $sql, $params);
                            if (is_array($res) && isset($res[0]) && isset($res[0]['count'])) {
                                return !($res[0]['count'] > 0);
                            }
                            return false;
            } ],

			'id_organismo' 	=>  [ 'numeric'],
			'piso'			=>  ['alpha_numeric', 'required'],
			'oficina'		=>  ['alpha_numeric', 'required'],
			'borrado'	    =>  ['numeric']
		];
		$nombres	= [
			'id_edificio'	=> 'Edificio',
			'piso'			=> 'Piso',
			'oficina'		=> 'Oficina',
		];

		$validator = Validator::validate((array)$this, $rules, $nombres);
		$validator->customErrors([
			'ubicacion_unica' => 'Ya existe una Ubicación definida para el Piso y Oficina.'
		]);
		if ($validator->isSuccess()) {
			return true;
		}
		else {
			$this->errores = $validator->getErrors();
			return false;
		}
	}


	static public function arrayToObject($res = []) {
		$campos	= [
			'id'			=> 'int',
			'borrado'		=> 'int',
			'nombre'		=> 'string',
			'id_edificio'	=> 'int',
			'id_organismo'	=> 'int',
			'calle'			=> 'string',
			'numero'		=> 'int',
			'piso'			=> 'string',
			'oficina'		=> 'string',
			'id_localidad'	=> 'int',
			'id_provincia'	=> 'int',
			'cod_postal'	=> 'string',
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
 * Este metodo devuelve un array con la estructura requerida para interacturar con campos Select y JS
 * @return array
*/
	static public function getEdificios(){
		static $cache = null;
		if(is_array($cache)) {
			return $cache;
		}
		$sql	= <<<SQL
			SELECT
				u.id					 AS u_id,
				u.borrado				 AS u_borrado,
				u.id_organismo			 AS u_id_organismo,
				u.piso					 AS u_piso,
				u.oficina				 AS u_oficina,
				u_edificios.id			 AS e_id,
				u_edificios.borrado		 AS e_borrado,
				u_edificios.nombre		 AS e_nombre,
				u_edificios.calle		 AS e_calle,
				u_edificios.numero		 AS e_numero,
				u_edificios.id_localidad AS e_id_localidad,
				u_edificios.id_provincia AS e_id_provincia,
				u_edificios.cod_postal	 AS e_cod_postal
			FROM ubicaciones AS u
			INNER JOIN ubicacion_edificios AS u_edificios ON (u_edificios.id = u.id_edificio)
			WHERE u.borrado = 0
			ORDER BY u.id DESC
SQL;
		$cnx	= new Conexiones();
		$resp	= (array)$cnx->consulta(Conexiones::SELECT, $sql);
		if(empty($resp)) { return []; }

		$aux	= [];

		foreach ($resp as &$val) {
			if(!isset($aux[$val['e_id']])) {
				$aux[$val['e_id']] = [
					'id'			=> $val['e_id'],
					'borrado'		=> $val['e_borrado'],
					'nombre'		=> $val['e_nombre'],
					'calle'			=> $val['e_calle'],
					'numero'		=> $val['e_numero'],
					'id_localidad'	=> $val['e_id_localidad'],
					'id_provincia'	=> $val['e_id_provincia'],
					'cod_postal'	=> $val['e_cod_postal'],
				];
			}
			$pisos = (is_null($val['u_piso'])) ? 'S/D' : $val['u_piso'];
			if(!isset($aux[$val['e_id']]['pisos'][$pisos])){ 
			$aux[$val['e_id']]['pisos'][$pisos]	= [
				'id'			=> $pisos,
				'borrado'		=> $val['u_borrado'],
				'nombre'		=> $pisos,
			];
			}
			$of = (is_null($val['u_oficina'])) ? 'S/D' : $val['u_oficina'] ;
			$aux[$val['e_id']]['pisos'][$pisos]['oficinas'][$of]	= [
				'id'			=> $of,
				'borrado'		=> $val['u_borrado'],
				'nombre'		=> $of,
				'id_ubicacion'	=> $val['u_id'],
			];
		}
		unset($resp);
		return $cache = $aux;
	}



	static public function getUbicaciones(){
		$campos	= implode(',', [
			'nombre',
			'calle',
			'numero',
			'borrado',
		]);
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM ubicaciones
			ORDER BY id ASC
SQL;
		$resp	= (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql);
		foreach ($resp as $value) {
	    	$aux[$value['id']] = $value;
	    }
	    return $aux;
	}
	
	public static function listadoUbicaciones($params=array(), $count = false) {
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

		$aux ='';

		$sql= <<<SQL
			SELECT
		    u.id,
		    u.id_edificio,
			u.piso,
			u.oficina,
			u_edificios.nombre as nombre
SQL;

		$from = <<<SQL
			FROM ubicaciones u
			INNER JOIN ubicacion_edificios AS u_edificios ON (u_edificios.id = u.id_edificio)
SQL;

		$order =
 		<<<SQL
 			ORDER BY 
SQL;
 		$condicion =
 		<<<SQL
 			WHERE u.borrado = 0
SQL;

    $counter_query	= "SELECT COUNT(DISTINCT u.id) AS total {$from}";

	$recordsTotal	=  $cnx->consulta(Conexiones::SELECT, $counter_query . $condicion, $sql_params)[0]['total'];

	if(!empty($params['search'])){

		$indice = 0;

		$search[]	= <<<SQL
		(u_edificios.nombre LIKE :search{$indice} OR
		u.piso LIKE :search{$indice} OR
		u.oficina LIKE :search{$indice})
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
}