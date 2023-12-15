<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class DenominacionFuncion extends Modelo {
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
			FROM denominacion_funcion
			WHERE id = :id
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
			'nombre',
			'borrado',
		]);
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM denominacion_funcion
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
			':nombre' => $this->nombre
		];
		$sql = 'INSERT INTO denominacion_funcion(nombre) VALUES (:nombre)';
		$res = $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);

		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'DenominacionFuncion';
			Logger::event('alta_denominacion_funcion', $datos);
		}
		return $res;
	}

	public function modificacion(){
		$cnx = new Conexiones();
		$sql_params	= [
			':id'		=> $this->id,
			':nombre'	=> $this->nombre,
		];
		$sql = 'UPDATE denominacion_funcion SET nombre = :nombre WHERE id = :id';
		$res = $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'ModalidadVinculacion';
			Logger::event('modificacion_denominacion_funcion', $datos);
		}
		return $res;
	}

	public function baja(){
		$cnx	= new Conexiones();
		$sql_params	= [
			':id'		=> $this->id,
		];
		$sql	= 'UPDATE denominacion_funcion SET borrado = 1 WHERE id = :id';
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
			Logger::event('baja_denominacion_funcion', $datos);
		}
		return $flag;
	}

	public static function listadoDenominacion($params=array(), $count = false) {
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
			FROM denominacion_funcion
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
			'nombre'	=> ['required', 'texto','unico(denominacion_funcion, nombre,:id)','max_length(120)'],
		];
		$nombres	= [
			'nombre'		=> 'Denominación de la Función', 	
		];
		$validator	= Validator::validate($campos, $reglas, $nombres);
		$validator->customErrors([
            'unico' => 'La Denominación de la Función ya se encuentra registrada',                         
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

	public static function obtener_por_nombre_si_borrado($nombre)
    {
        $obj = new static;
        if (is_null($nombre))
            return false;

        $sql_params = [':nombre' => $nombre,];
        $sql = <<<SQL
                    SELECT id, nombre
                    FROM denominacion_funcion
                    WHERE nombre = :nombre
                    AND borrado = 1
SQL;
        $res = (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
        if(!empty($res)){
            return static::arrayToObject($res[0]);
        }
        return false;
    }

	public function reactivar(){
        $conexion = new Conexiones;
        $params = [':id' => $this->id];
        $sql = <<<SQL
		UPDATE denominacion_funcion SET borrado = 0 WHERE id = :id
SQL;
        $res = $conexion->consulta(Conexiones::SELECT, $sql, $params);
        if ($res !== false)
        {
            $datos = (array)$this;
            $datos['modelo'] = 'Denominacion_funcion';
            Logger::event('reactivar', $datos);
            return true;
        }

        else {
            $datos['error_db'] = $conexion->errorInfo;
            Logger::event("error_reactivar", $datos);
        }
        return false;
    }
}
