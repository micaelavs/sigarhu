<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class EmpleadoCursos extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_empleado;
/** @var int */
	public $id_curso;
/** @var Date */
	public $fecha;
/** @var int,  1 si aplica para promoción de grado = default y 2 si aplica para promoción de tramo*/
	public $tipo_promocion;
/** @var int */
	public $borrado;

	static public function obtener($id=null){
		if($id===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id'	=> $id,
		];
		$campos	= implode(',', [
			'id',
			'id_empleado',
			'id_curso',
			'fecha',
			'tipo_promocion',
			'borrado',
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM empleado_cursos
			WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}

	static public function listar($empleado_id=null, $limit=null) {
		if(!is_numeric($empleado_id)) {
			return [];
		}
		$sql_params = [];
        $limit_sql  = '';
        if($limit !== null && is_numeric($limit)){
            $limit_sql              = ' LIMIT '.(int)$limit;
        }
		$campos	= implode(',', [
			'ec.id_empleado',
			'ec.id_curso',
			'ec.fecha',
			'ec.tipo_promocion',
			'ec.borrado',
		]);
		$sql_params	= [
			':id_empleado'	=> $empleado_id
		];
		$sql	= <<<SQL
			SELECT ec.id, {$campos}
			FROM empleado_cursos ec
			WHERE ec.borrado = 0 AND ec.id_empleado = $empleado_id
			ORDER BY ec.fecha DESC
			{$limit_sql}
			
SQL;
		$resp	= (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(empty($resp)) { return []; }
		foreach ($resp as &$value) {
			$value	= static::arrayToObject($value);
		}
		return $resp;
	}

/**
 * Listar cursos para Select de vista.
 * @return array
*/
	static public function getCursos(){
		$cnx	= new Conexiones();
		$aux = [];
		$sql= <<<SQL
		SELECT 
		   id,
		   codigo,
		   nombre_curso,
		   creditos,
		   borrado
		FROM cursos_snc
 		WHERE borrado = 0
 		ORDER BY id ASC;
SQL;
		$res	= $cnx->consulta(Conexiones::SELECT, $sql);
		if(is_array($res) && !empty($res[0])){
			foreach ($res as $value) { 
				$aux[$value['id']] = [
					'id'			=> $value['id'],
					'nombre'		=> $value['codigo'].' - '.$value['nombre_curso'],
					'borrado'		=> $value['borrado'],
					'codigo'		=> $value['codigo'],
					'nombre_curso'	=> $value['nombre_curso'],
					'creditos'		=> $value['creditos'],
				]; 
			}
		}
		return $aux;
	}

	public function alta() {
		if(!$this->validar()){
			return false;
		}
		$cnx	= new Conexiones();
		$campos	= [
			'id_empleado',
			'id_curso',
			'fecha',
			'tipo_promocion'
		];
		$sql_params	= [
			':id_empleado'		=> $this->id_empleado,
			':id_curso'			=> $this->id_curso,
			':fecha'			=> $this->fecha,
			':tipo_promocion'	=> $this->tipo_promocion,
		];
		if($this->fecha instanceof \DateTime){
			$sql_params[':fecha']	= $this->fecha->format('Y-m-d');
		}

		$sql	= 'INSERT INTO empleado_cursos('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params); 

		if($res !== false){
			$this->id 			= $res;

			$curso				= Curso::obtener($this->id_curso);

			EmpleadoHistorialCreditos::setTipoPromocion($this->tipo_promocion);
			EmpleadoHistorialCreditos::agregarCreditos(
				$this->id_empleado,
				$this->id,
				EmpleadoHistorialCreditos::TABLA_EMPLEADO_CURSOS,
				$curso->creditos,
				$this->fecha
			);

			$datos				= (array)$this;
			$datos['modelo']	= 'EmpleadoCursos';
			Logger::event('alta_empleado_cursos', $datos);
			return true;
		}
		$datos				= (array)$this;
		$datos['modelo']	= 'EmpleadoCursos';
		$this->errores['empleado_cursos']	= $cnx->errorInfo[2];
		Logger::event('alta_empleado_cursos', $datos);

		return false;
	}


	public function baja() {
		if(empty($this->id)) {
			return false;
		}
		$sql	= <<<SQL
			UPDATE empleado_cursos SET borrado = 1 WHERE id = :id
SQL;
		$mbd = new Conexiones();
		$res	= $mbd->consulta(Conexiones::UPDATE, $sql, [
			':id'			=> $this->id,
		]);

		$flag = false;
		if (!empty($res) && $res > 0) {
			EmpleadoHistorialCreditos::setTipoPromocion($this->tipo_promocion);
			EmpleadoHistorialCreditos::bajaCredito(
				$this->id_empleado,
				$this->id,
				EmpleadoHistorialCreditos::TABLA_EMPLEADO_CURSOS
			);

			$datos				= (array)$this;
			$datos['modelo']	= 'EmpleadoCursos';
			if (is_numeric($res) && $res > 0) {
				$flag =true;
			} else {
				$datos['error_db'] = $mbd->errorInfo;
			}
			Logger::event('baja_empleado_cursos', $datos);
		}
		return $flag;
	}

	public function modificacion(){
		if(empty($this->id)){
			return false;
		}

		$campos	= [
			'id_curso'				=> 'id_curso = :id_curso',
			'fecha'					=> 'fecha = :fecha',
			'tipo_promocion'		=> 'tipo_promocion = :tipo_promocion'
		];
		$sql_params	= [
			':id_curso'		=> $this->id_curso,
			':fecha'		=> $this->fecha,
			':tipo_promocion'	=> $this->tipo_promocion,	
			':id'			=> $this->id,
		];

		if($this->fecha instanceof \DateTime){
			$sql_params[':fecha']	= $this->fecha->format('Y-m-d');
		}

		$sql	= 'UPDATE empleado_cursos SET '.implode(',', $campos).' WHERE id = :id';
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){

			EmpleadoHistorialCreditos::bajaCredito(
				$this->id_empleado,
				$this->id,
				EmpleadoHistorialCreditos::TABLA_EMPLEADO_CURSOS
			);

			$curso = Curso::obtener($this->id_curso);
			EmpleadoHistorialCreditos::setTipoPromocion($this->tipo_promocion);
			EmpleadoHistorialCreditos::agregarCreditos(
				$this->id_empleado,
				$this->id,
				EmpleadoHistorialCreditos::TABLA_EMPLEADO_CURSOS,
				$curso->creditos,
				$this->fecha
			);

			$datos = (array) $this;
			$datos['modelo'] = 'EmpleadoCursos';
			Logger::event('modificacion', $datos);
			return true;
		}
		return false;
	}

	public function validar()  {
		$reglas		= [
			'id_empleado'		=> ['required'],
			'id_curso'			=> ['required',
			'curso_unico(:id,:id_empleado)' =>function($input, $id, $id_empleado){
				$con = new Conexiones();
				if (is_numeric($input)) {
					$sql	= 'SELECT codigo FROM cursos_snc WHERE id = :id_curso';
					$res	= $con->consulta(Conexiones::SELECT, $sql, [':id_curso' => $input]);
					if(preg_match('/(EXTRA)/', \FMT\Helper\Arr::path($res, '0.codigo', ''))){
						return true;
					}

					$sql = "SELECT count(*) as cursos
						FROM empleado_cursos
						WHERE id_empleado = :id_empleado
						AND id_curso = :id_curso
						AND borrado = 0";
						$params	= [':id_empleado'	=> $id_empleado,
									':id_curso' 	=> $input
								];
					if ($id) {
						$sql .= " AND id != :id";
						$params[':id'] = $id;
					}
						$res = $con->consulta(Conexiones::SELECT, $sql, $params);
						if (is_array($res) && isset($res[0]) && isset($res[0]['cursos'])) {
				 			return !($res[0]['cursos'] > 0);
						}
						return true;
				}
				return false;
				}
			],
			'fecha'				=> ['required','fecha','fecha_vacia'=>function($input) {
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
		];
		$nombres	= [
			'id_empleado'		=> 'Cuit/Cuil',
			'id_curso'			=> 'Curso',
			'fecha'				=> 'Fecha',
		];
		$validator	= Validator::validate((array)$this, $reglas, $nombres);
		$validator->customErrors([
            'fecha_vacia'       => 'Debe cargar una fecha para cursos sistema nacional de capacitacion.',
            'curso_unico'		=> 'El curso ya existe para éste empleado.'
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
			'id_empleado' 	=> 'int',
			'id_curso' 		=> 'int',
			'fecha'			=> 'date',
			'tipo_promocion'=> 'int'
		];
		return parent::arrayToObject($res, $campos);
	}
}