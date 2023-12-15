<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

/**
 * Descripcion de datos: Secretarias, Subsecretarias, etc
*/
class UbicacionEdificio extends Modelo {
	/** @var int */
	public $id;
	/** @var string */
	public $nombre;
	/** @var string */
	public $calle;
		/** @var int */
	public $numero;
		/** @var int */
	public $id_localidad;
		/** @var int */
	public $id_provincia;
	/** @var string */
	public $cod_postal;
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
			'nombre',
			'calle',
			'numero',
			'id_localidad',
			'id_provincia',
			'cod_postal',
			'borrado',
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM ubicacion_edificios
			WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}

	
	static public function listar($array=false) {
		$campos	= implode(',', [
			'id',
			'nombre',
			'calle',
			'numero',
			'id_localidad',
			'id_provincia',
			'cod_postal',
			'borrado',
		]);
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM ubicacion_edificios
			WHERE borrado = 0
			ORDER BY id ASC
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
			'nombre',
			'calle',
			'numero',
			'id_localidad',
			'id_provincia',
			'cod_postal',
			
		];
		$sql_params	= [
		];
		foreach ($campos as $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
		}

		$sql	= 'INSERT INTO ubicacion_edificios('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, $sql_params);

		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'UbicacionEdificio';
			Logger::event('alta', $datos);
		}
		return $res;
	}

	public function baja(){
		$mbd = new Conexiones;
		$params = [':id' => $this->id];
		$sql = <<<SQL
		UPDATE ubicacion_edificios SET  borrado = 1 WHERE id = :id
SQL;
		$res = $mbd->consulta(Conexiones::SELECT, $sql, $params);
		if ($res !== false) {
			$datos = (array) $this;
			$datos['modelo'] = 'UbicacionEdificio';
			Logger::event('baja', $datos);
		} else {
			$datos['error_db'] = $conexion->errorInfo;
			Logger::event("error_baja",$datos);
		}
		return $res;
	}


	public function modificacion(){
		if(!$this->validar() || empty($this->id)){
			return false;
		}

		$campos	= [
			'nombre',
			'calle',
			'numero',
			'id_localidad',
			'id_provincia',
			'cod_postal',
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

		$sql	= 'UPDATE ubicacion_edificios SET '.implode(',', $campos).' WHERE id = :id';
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'UbicacionEdificio';
			Logger::event('modificacion', $datos);
		}
		return $res;
	}

	public function validar(){
		$rules = [
			'id' 			=>  ['numeric', ],
			'nombre' 		=>  ['required', 'texto', 'min_length(4)', 'max_length(100)', ],
			'calle'			=>  ['required', 'texto', 'min_length(4)', 'max_length(100)', 
								 'edificio_unico(:numero,:id,:id_localidad,:id_provincia)' => function($input, $numero, $id, $id_localidad, $id_provincia){
				$params = [':calle' => $input, ':numero' => $numero, ':id_localidad' => $id_localidad, ':id_provincia' => $id_provincia];
				$where_id = '';
				if($id){
					$where_id = " AND id != :id";
					$params[':id'] = $id;
				}

				$sql = <<<SQL
							SELECT count(*) count FROM ubicacion_edificios WHERE calle = :calle AND numero = :numero AND id_localidad = :id_localidad AND id_provincia = :id_provincia  $where_id
SQL;
							$con = new Conexiones();
							$res = $con->consulta(Conexiones::SELECT, $sql, $params);
							if (is_array($res) && isset($res[0]) && isset($res[0]['count'])) {
								return !($res[0]['count'] > 0);
							}
							return false;
			} ],
			'numero'		=>  ['alpha_numeric'],
			'id_localidad'	=>  ['required','integer'],
			'id_provincia'	=>  ['required','integer'],
			'cod_postal' 	=> 	['numeric'],
			'borrado'	    =>  ['numeric']
		];
		$nombres	= [
			'nombre'		=> 'Ubicación en el Edificio',
			'calle'			=> 'Calle',
			'numero'		=> 'Número',
			'id_localidad'	=> 'Localidad',
			'id_provincia'  => 'Provincia'
		];
	
		$validator = Validator::validate((array)$this, $rules, $nombres);
		$validator->customErrors([
            'edificio_unico'      => 'Ya existe un edificio cargado en la misma dirección.',
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
			'nombre'		=> 'string',
			'calle'			=> 'string',
			'numero'		=> 'int',
			'id_localidad'	=> 'int',
			'id_provincia'	=> 'int',
			'cod_postal' 	=> 'string',
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
				u_edificios.id			 AS e_id,
				u_edificios.borrado		 AS e_borrado,
				u_edificios.nombre		 AS e_nombre,
				u_edificios.calle		 AS e_calle,
				u_edificios.numero		 AS e_numero,
				u_edificios.id_localidad AS e_id_localidad,
				u_edificios.id_provincia AS e_id_provincia,
				u_edificios.cod_postal	 AS e_cod_postal
			FROM ubicacion_edificios AS u_edificios
			WHERE u_edificios.borrado = 0
			ORDER BY u_edificios.id DESC
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
		}
		unset($resp);
		return $cache = $aux;
	}

	static public function buscarLocacionApi($id_locacion=null){
    
        if($id_locacion){
	        $Conexiones = new Conexiones();
	        $resultado = $Conexiones->consulta(Conexiones::SELECT,
	<<<SQL

	            SELECT  id_ubicacion_api, id_ubicacion
	            FROM ubicaciones_api
	            WHERE id_ubicacion_api= :id_locacion
	            LIMIT 1
	SQL
	        ,[':id_locacion'=>$id_locacion]);
	        return !empty($resultado) ? $resultado[0] : null;
        }

    }

    static public function insertarLocacion($locacion = null){
        $cnx = new Conexiones();
        $sql_params = [
            ':nombre' => $locacion['locacion'],
            ':calle'	=> $locacion['calle'],
            ':numero'	=> $locacion['numero'],
            ':id_region' => $locacion['id_region'],
            ':id_localidad' => $locacion['id_localidad']

        ];
        $sql = 'INSERT INTO ubicacion_edificios (nombre, calle, numero, id_localidad, id_provincia) VALUES (:nombre, :calle, :numero, :id_localidad, :id_region)';
        $res = $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);

        if($res){
          $sql = 'SET @id_usuario = 99999';
          $cnx->consulta(Conexiones::SELECT, $sql, []);
          
          $sql = 'UPDATE ubicaciones_api SET id_ubicacion = :id_ubicacion WHERE id_ubicacion_api = :id_ubicacion_api';
          $respUpdate = $cnx->consulta(Conexiones::UPDATE, $sql, [ ':id_ubicacion' => $res,':id_ubicacion_api' => $locacion['id_locacion'] ]);
          if(!$respUpdate){
            $sql_params = [
              ':id_ubicacion_api' => $locacion['id_locacion'],
              ':id_ubicacion' => $res,
              ':nombre_api' => $locacion['locacion'],
              ':calle' => $locacion['calle'],
              ':id_localidad' => $locacion['id_localidad'],
              ':id_region' => $locacion['id_region']
            ];
            $sql = 'INSERT INTO ubicaciones_api (id_ubicacion_api, id_ubicacion, nombre_api, calle, id_localidad, id_region, fecha, borrado) VALUES (:id_ubicacion_api, :id_ubicacion, :nombre_api, :calle, :id_localidad, :id_region, NOW(), 0)';
            $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
          }
          
        }
        return $res;
    }

    static public function borradoInicialDeLocaciones(){
      $cnx = new Conexiones();
      $sql = 'UPDATE ubicacion_edificios SET borrado = 1';
      $res = $cnx->consulta(Conexiones::UPDATE, $sql, []);
      return $res;
    }

    static public function actualizarLocacion($locacion = null, $id_ubicacion_sigarhu = null){
        $cnx = new Conexiones();
        $sql_params = [
            ':nombre' => $locacion['locacion'],
            ':calle'	=> $locacion['calle'],
            ':numero'	=> $locacion['numero'],
            ':id_region' => $locacion['id_region'],
            ':id_localidad' => $locacion['id_localidad'],
            ':id_ubicacion_sigarhu' => $id_ubicacion_sigarhu

        ];

        $sql = 'UPDATE ubicacion_edificios SET nombre = :nombre, calle = :calle, numero = :numero, id_localidad = :id_localidad, id_provincia = :id_region, borrado = 0 
        		WHERE id = :id_ubicacion_sigarhu';

        $res = $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);
        
        return $res;
    }


}