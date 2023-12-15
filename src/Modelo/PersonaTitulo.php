<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class PersonaTitulo extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_persona;
/** @var int */
	public $id_tipo_titulo;
/** @var int */
	public $id_estado_titulo;
/** @var int */
	public $id_titulo;
/** @var Date */
	public $fecha;
/** @var int */
	public $principal;
/** @var int */
	public $borrado;
/** @var int */
	public $acum_creditos;

	const S_D			= 1;
	const COMPLETO 		= 2;
	const INCOMPLETO	= 3;
	
	protected static $ESTADO_TITULO	= [
		self::COMPLETO	=> ['id' => self::COMPLETO, 'nombre' => 'Completo', 'borrado' => '0'],
		self::INCOMPLETO=> ['id' => self::INCOMPLETO, 'nombre' => 'Incompleto', 'borrado' => '0'],
		self::S_D		=> ['id' => self::S_D, 'nombre' => 'S/D', 'borrado' => '0'],
	];

/**
 * Obtiene los valores de los array parametricos.
 * E.J.: Dependencia::getParam('TIPO_TITULO');
*/
	static public function getParam($attr=null){
		if($attr === null || empty(static::${$attr})){
			return [];
		}
		return static::${$attr};
	}

	static public function obtener($id=null){
		if($id===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id'	=> $id,
		];
		$campos	= implode(',', [
			'id',
			'id_persona',
			'id_tipo_titulo',
			'id_estado_titulo',
			'id_titulo',
			'fecha',
			'principal',
			'borrado',
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM persona_titulo
			WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}

	static public function listar($persona_id=null) {
		if(!is_numeric($persona_id)) {
			return [];
		}
		$campos	= implode(',', [
			'pt.id_persona',
			'pt.id_tipo_titulo',
			'pt.id_estado_titulo',
			'pt.id_titulo',
			'pt.fecha',
			'pt.principal',
			'pt.borrado',
		]);
		$sql_params	= [
			':id_persona'	=> $persona_id
		];
		$sql	= <<<SQL
			SELECT pt.id, {$campos}
			FROM persona_titulo pt
			WHERE pt.borrado = 0 AND pt.id_persona = :id_persona
			ORDER BY pt.principal DESC,pt.id_tipo_titulo DESC,pt.id_estado_titulo ASC
SQL;
		$resp	= (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(empty($resp)) { return []; }
		foreach ($resp as &$value) {
			$value	= static::arrayToObject($value);
		}
		return $resp;
	}

	public function alta(){
		if($this->principal == 1) {
			$this->reset_principales();
		}
		$campos	= [
			'id_persona',
			'id_tipo_titulo',
			'id_estado_titulo',
			'id_titulo',
			'fecha',
			'principal',
		];
		$sql_params	= [
		];
		foreach ($campos as $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
		}

		if($this->fecha instanceof \DateTime){
			$sql_params[':fecha']	= $this->fecha->format('Y-m-d');
		}
		$conexion = new Conexiones(); 
		$sql	= 'INSERT INTO persona_titulo('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= $conexion->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$this->id	= $res;
			$datos = (array) $this;
			$datos['modelo'] = 'PersonaTitulo';
			Logger::event('alta', $datos);
		}

		return $res;
	}

	public function baja(){
		if(empty($this->id)) {
			return false;
		}
		$sql	= <<<SQL
			UPDATE persona_titulo SET borrado = 1 WHERE id = :id
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, [':id' => $this->id]);
		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'PersonaTitulo';
			if (is_numeric($res) && $res > 0) {
				$flag = true;
			} else {
				$datos['error_db'] = $cnx->errorInfo;
			}
			Logger::event('baja', $datos);
		}
		return $flag;
	}

	public function modificacion(){
		if(empty($this->id)){
			return false;
		}
		if($this->principal == 1) {
			$this->reset_principales();
		}
		$campos	= [
			'id_persona',
			'id_tipo_titulo',
			'id_estado_titulo',
			'id_titulo',
			'fecha',
			'principal',
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

		$sql	= 'UPDATE persona_titulo SET '.implode(',', $campos).' WHERE id = :id';
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'PersonaTitulo';
			Logger::event('modificacion', $datos);
			return true;
		}
		return $res;
	}

	public function reset_principales(){

		$sql_params	= [
			':id_persona'	=> $this->id_persona,
		];

		$sql	= 'UPDATE persona_titulo SET principal = 0 WHERE  id_persona = :id_persona';
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);

		return $res;
	}

	public function validar() {
		$reglas		= [
			'id_tipo_titulo'	=> ['required', 'numeric'],
			'id_estado_titulo'	=> ['required', 'numeric'],
			'id_titulo'		=> ['required', 'numeric','titulo_unico(:id,:id_persona,:id_tipo_titulo, id_estado_titulo)' =>function($input, $id, $id_persona, $id_tipo_titulo,$id_estado_titulo){
				if (is_numeric($input)) {
					$sql = "SELECT count(*) as titulos
						FROM persona_titulo
						WHERE id_persona = :id_persona
						AND id_titulo = :id_titulo
						AND id_tipo_titulo	= :id_tipo_titulo
						AND (id_estado_titulo!=:id_estado_titulo OR id_estado_titulo=:id_estado_titulo)
						AND borrado = 0";
						$params	= [':id_persona'	=> $id_persona,
									':id_titulo' 	=> $input,
									':id_tipo_titulo' => $id_tipo_titulo,
									':id_estado_titulo' =>$id_estado_titulo
								];
					if ($id) {
						$sql .= " AND id != :id";
						$params[':id'] = $id;
					}
						$con = new Conexiones();
						$res = $con->consulta(Conexiones::SELECT, $sql, $params);
						if (is_array($res) && isset($res[0]) && isset($res[0]['titulos'])) {
				 			return !($res[0]['titulos'] > 0);
						}
						return true;
					}
					return true;
				}],
			'fecha'				=> ['fecha','fecha_vacia()'=>function($input) {
				if (empty($input)) {
					$rta = true;
				}else{
					$rta = ($input instanceof \DateTime);
					if ($rta && $input->format('Y-m-d') == '0000-00-00') {
						$rta = false;
					}
				}
				return $rta;
			}],
		];

		if ($this->id_estado_titulo == static::COMPLETO) {
				$reglas		= [
					'fecha' => ['required']
				];
		}

		$nombres	= [
			'id_tipo_titulo'	=> 'Nivel Educativo',
			'id_estado_titulo'	=> 'Estado',
			'id_titulo'		=> 'Titulo',
			'fecha'				=> 'Fecha',
		];
		$validator	= Validator::validate((array)$this, $reglas, $nombres);
		$validator->customErrors([
            'fecha_vacia'       => 'Debe cargar una fecha valida para los títulos obtenidos.',
            'titulo_unico'		=> 'El título ya existe para para esta persona.'
        ]);

		if ($validator->isSuccess()) {
			return true;
		}
		$this->errores = $validator->getErrors();
		return false;
	}

	static public function arrayToObject($res = []) {
		$campos	= [
			'id'				=> 'int',
			'id_persona'		=> 'int',
			'id_tipo_titulo'	=> 'int',
			'id_estado_titulo'	=> 'int',
			'id_titulo'			=> 'string',
			'fecha'				=> 'date',
			'principal'			=> 'int',
			'borrado'			=> 'int',
		];
		$obj = parent::arrayToObject($res, $campos);
		$obj->acum_creditos = TituloCredito::acum_creditos_de_titulo($obj->id);
		return $obj;
	}

	static public function obtenerTitulo($id_nivel_educativo=null){
		$sin_definir = '';
		if ($id_nivel_educativo == 1) {
		  	$sin_definir	= "or ISNULL(t.id_tipo_titulo)";
		 }
		$cnx	= new Conexiones();
		$sql_params	= [
			':id_nivel_educativo'	=> $id_nivel_educativo,
		];
		$sql = <<<SQL
		SELECT t.id, t.nombre,t.borrado
					from titulo as t
					left join nivel_educativo as ne ON (t.id_tipo_titulo = ne.id and t.borrado = 0)
					where 
					t.id_tipo_titulo = :id_nivel_educativo
					$sin_definir
					order by t.nombre
SQL;
		$res	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		$return = [];
		array_walk($res, function($val) USE (&$return){
			$return[$val['id']] = $val;
		});
		return $return;
	

	}

}