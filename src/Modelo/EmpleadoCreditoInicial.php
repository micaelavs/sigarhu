<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use FMT\Logger;
use App\Helper\Conexiones;
use App\Helper\Validator;
use FMT\Helper\Arr;


class EmpleadoCreditoInicial extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_empleado;
/** @var \DateTime */
	public $fecha_considerada;
/** @var int */
	public $creditos;
/** @var string */
	public $descripcion;
/** @var int */
	public $borrado;
/** @var \StdClass */
	public $empleado;
	
	
	static public function obtener($id=null){
		if(empty($id)){
			return static::arrayToObject();
		}
		$cnx		= new Conexiones();
		$sql_params	= [
			':id'	=> $id,
		];
		$sql		= 'SELECT * FROM empleado_creditos_iniciales WHERE id = :id LIMIT 1';
		$res		= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(empty($res[0]['id'])){
			return static::arrayToObject();
		}
		return static::arrayToObject($res[0]);
	}
	static public function listar(){
		$cnx		= new Conexiones();
		$sql		= 'SELECT * FROM empleado_creditos_iniciales WHERE borrado = 0 ORDER BY id_empleado ASC';
		$res		= (array)$cnx->consulta(Conexiones::SELECT, $sql);
		
		if(empty($res[0]['id'])){
			return [static::arrayToObject()];
		}
		$aux	= [];
		foreach ($res as $val) { 
			$aux[$val['id']]	= static::arrayToObject($val);
		}
		return $aux;
	}

	public function alta(){
		$cnx		= new Conexiones();
		$campos		= [
			'id_empleado'		=> $this->id_empleado,
			'fecha_considerada'	=> $this->fecha_considerada,
			'creditos'			=> $this->creditos,
			'descripcion'		=> $this->descripcion,
			//'borrado'			=> '0',
		];
		$sql_params	= [];
		foreach ($campos as $field => $val) {
			if($val instanceof \DateTime){
				$val	= $val->format('Y-m-d');
			}
			$sql_params[':'.$field]	= $val;
		}
		
		$sql	= 'INSERT INTO empleado_creditos_iniciales('.implode(',', array_keys($campos)).') VALUES ('.implode(',', array_keys($sql_params)).')';
		$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$this->id	=	$res;
			$datos = (array) $this;
			$datos['modelo'] = 'EmpleadoCreditoInicial';
			Logger::event('alta', $datos);

			EmpleadoHistorialCreditos::calcularHistoricoCreditos($this->id_empleado, $this->fecha_considerada);			
			EmpleadoHistorialCreditos::quitarCreditos(
				$this->id_empleado,
				$this->id,
				EmpleadoHistorialCreditos::TABLA_EMPLEADO_CREDITOS_INICIALES,
				EmpleadoHistorialCreditos::getUltimoBalance($this->id_empleado, $this->fecha_considerada),
				$this->fecha_considerada
			);
			EmpleadoHistorialCreditos::agregarCreditos(
				$this->id_empleado,
				$this->id,
				EmpleadoHistorialCreditos::TABLA_EMPLEADO_CREDITOS_INICIALES,
				$this->creditos,
				$this->fecha_considerada
			);
			return true;
		}
		return false;
	}
	public function modificacion(){
		if(!$this->validar()){
			return false;
		}

		$cnx = new Conexiones();

		$campos	= [
			'fecha_considerada'	=> 'fecha_considerada = :fecha_considerada',
			'creditos'	=> 'creditos = :creditos',
			'descripcion'	=> 'descripcion = :descripcion',
		];
		$sql_params	= [
			':id_empleado' => $this->id_empleado,
			':id'		=> $this->id,
			':creditos'	=> $this->creditos,
			':descripcion'	=> $this->descripcion,
			
		];
		if($this->fecha_considerada	 instanceof \DateTime){
			$sql_params[':fecha_considerada']	= $this->fecha_considerada->format('Y-m-d');
		}
		$sql = 'UPDATE empleado_creditos_iniciales SET '.implode(',', $campos).' WHERE id = :id AND id_empleado = :id_empleado';
		$res = $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'EmpleadoCreditoInicial';
			Logger::event('modificacion', $datos);

			EmpleadoHistorialCreditos::bajaCredito(
				$this->id_empleado,
				$this->id,
				EmpleadoHistorialCreditos::TABLA_EMPLEADO_CREDITOS_INICIALES
			);
			
			EmpleadoHistorialCreditos::calcularHistoricoCreditos($this->id_empleado, $this->fecha_considerada);
			EmpleadoHistorialCreditos::quitarCreditos(
				$this->id_empleado,
				$this->id,
				EmpleadoHistorialCreditos::TABLA_EMPLEADO_CREDITOS_INICIALES,
				EmpleadoHistorialCreditos::getUltimoBalance($this->id_empleado, $this->fecha_considerada),
				$this->fecha_considerada
			);
			EmpleadoHistorialCreditos::agregarCreditos(
				$this->id_empleado,
				$this->id,
				EmpleadoHistorialCreditos::TABLA_EMPLEADO_CREDITOS_INICIALES,
				$this->creditos,
				$this->fecha_considerada
			);
			return true;
		}
		return false;
	}
	public function baja(){
		if(empty($this->id)){
			return false;
		}

		$cnx	= new Conexiones();
		$sql	= 'UPDATE empleado_creditos_iniciales SET borrado = 1 WHERE id = :id';
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, [
			':id'	=> $this->id
		]);
		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'empleado_creditos_iniciales';
			if (is_numeric($res) && $res > 0) {
				$flag = true;
				
				EmpleadoHistorialCreditos::bajaCredito(
					$this->id_empleado,
					$this->id,
					EmpleadoHistorialCreditos::TABLA_EMPLEADO_CREDITOS_INICIALES
				);
			} else {
				$datos['error_db'] = $cnx->errorInfo;
			}
			Logger::event('baja', $datos);
		}
		return $flag;
	}

	public function validar(){
		$campos = [
			'id' => $this->id,
			'id_empleado' => $this->id_empleado,
			'fecha_considerada' => $this->fecha_considerada,
			'creditos' => $this->creditos,
			'cuit' => $this->empleado->cuit,
			'nombre_apellido' => $this->empleado->nombre_apellido
		];	

		$reglas		= [
			'id' 		=> ['numeric'],
			'id_empleado' => ['required', 'numeric', 'creditoUnico(:id)' => function($id_empleado, $id){
				$params = [':id_empleado'=> $id_empleado];
				$where_id = '';
				if($id){
					$where_id = " AND id != :id";
					$params[':id'] = $id;
				}
				$sql = <<<SQL
							SELECT count(*) count FROM empleado_creditos_iniciales WHERE id_empleado = :id_empleado $where_id
								AND borrado = 0
								LIMIT 1;
SQL;
				$con = new Conexiones();
				$res = $con->consulta(Conexiones::SELECT, $sql, $params);
				if (!empty($res[0]['count'])) {
					return !($res[0]['count'] > 0);
				}
				return true;
			}],
			'fecha_considerada'	=> ['required','fecha','fecha_vacia'=>function($input) {
				$rta = true;
				if (is_null($input)) {
					$rta = true;
				}else{
					$rta = ($input instanceof \DateTime);
					if ($rta && $input->format('Y-m-d') == '0000-00-00') {
						$rta = false;
					}
				}
				return $rta;
			}],
			'creditos'	=> ['required', 'integer']
		];
		$nombre = [
			'id_empleado' => 'Empleado',	
			'creditos'	=> 'Créditos', 
			'fecha_considerada' => 'Fecha de vigencia',

		];
		
		$validator	= Validator::validate($campos, $reglas, $nombre);
		$validator->customErrors([
            'fecha_vacia'      	=> 'La fecha no puede estar vacía.',
            'creditoUnico'		=> 'Ya existe un crédito inicial para el Agente seleccionado.'
        ]);

		if ($validator->isSuccess()) {
			return true;
		}
		$this->errores = $validator->getErrors();
		return false;	
	}

	static public function arrayToObject($res=[]){
		$campos = [
			'id'				=> 'int',
			'id_empleado'		=> 'int',
			'fecha_considerada'	=> 'date',
			'creditos'			=> 'int',
			'descripcion'		=> 'string',
			'borrado'			=> 'int',
		];
		$obj	= parent::arrayToObject($res, $campos);
		Empleado::contiene(['persona'=>[]]);
		$empleado						= Empleado::obtener($obj->id_empleado, true);
		$obj->empleado					= new \stdClass();

		$obj->empleado->cuit			= $empleado->cuit;
		$obj->empleado->nombre_apellido	= $empleado->persona->nombre.' '.$empleado->persona->apellido;

		return $obj;
	}
}