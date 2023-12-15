<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class Horario extends Modelo {
	/* Atributos */
	/** @var int */
	public $id = 0;
	/** @var string */
	public $nombre;
	public $dia_desde;
	public $dia_hasta;
	public $hora_desde;
	public $hora_hasta;
	public $horario ;
	public $borrado;

	/**
	 * @param int $id
	 * @return Ubicacion
	 */

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
			'horario',
			'borrado',
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM plantilla_horarios
			WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);

		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}

	/**
	 * @return array
	 */

	static public function listar() {
		$str = "SELECT * FROM plantilla_horarios WHERE borrado = 0";
		$res = (new Conexiones)->consulta(Conexiones::SELECT, $str);
		$lista = [];
		if (!empty($res) && is_array($res)) {
			foreach ($res as $re) {
				$lista[] = (array)static::arrayToObject($re);
			}
		}	
		return $lista;
	}

	static public function getPlantillaHorario(){
		$campos	= implode(',', [
			'nombre',
			'horario',
			'borrado',
		]);
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM plantilla_horarios
			ORDER BY id DESC
SQL;
		$resp	= (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql);
		foreach ($resp as $value) {
	    	$aux[$value['id']] = $value;
	    }
	    return $aux;
	}

	/**
	 * @param array $res
	 * @return Ubicacion
	 */
	public static function arrayToObject($res = []) {
		$obj = new self();
		$obj->id = isset($res['id']) ? (int)$res['id'] : 0;
		$obj->nombre = isset($res['nombre']) ? $res['nombre'] : null;
		$obj->horario = isset($res['horario']) ? json_decode($res['horario'],1) : null;
		$i = 0;
		$j = 6;
		while ($i<= 6) {
			if(!empty($obj->horario[$i]) && is_null($obj->dia_desde)) {
				$obj->dia_desde = $i;
				$obj->hora_desde = 	$obj->horario[$i][0];
				$obj->hora_hasta = 	$obj->horario[$i][1];
			}			
			$obj->dia_hasta = (!empty($obj->horario[$j])  && is_null($obj->dia_hasta)) ? $j : $obj->dia_hasta;

			$i++;
			$j--;
		}
		return $obj;
	}

	public function alta() {
		if ($this->validar()) {
			$this->generar_horario();
			$mbd = new Conexiones;
			$sql = "INSERT INTO plantilla_horarios (nombre, horario) VALUES (:nombre, :horario)";
			$params = [
				':nombre' => $this->nombre,
				':horario'  => $this->horario
			];
			$resultado = $mbd->consulta(Conexiones::INSERT, $sql, $params);
			if (is_numeric($resultado) && $resultado > 0) {
				$this->id = $resultado;
				//Log
				$datos = (array)$this;
				$datos['modelo'] = 'horario';
				Logger::event('alta', $datos);

				return true;
			}
		}

		return false;
	}

	public function validar() {
		$reglas = [
			'nombre'	 => ['required', 'min_length(2)', 'unico(plantilla_horarios,nombre' .
				($this->id ? ',' . $this->id : '') . ')'],
			'dia_desde'  => ['required'],
			'dia_hasta'  => ['required','mayorA(:dia_desde)'],
			'hora_desde' => ['required', 'antesDe(:hora_hasta)'],
			'hora_hasta' => ['required']

		];
		$nombres = [
			'nombre' 		=> 'Nombre',
			'dia_desde'	    => 'Día Desde',
			'dia_hasta' 	=> 'Día Hasta',
			'hora_desde' 	=> 'Hora Desde',
			'hora_hasta' 	=> 'Hora Hasta'

		];
		$campos = (array)$this;
		$campos['dia_desde'] = "{$campos['dia_desde']}";
		$campos['dia_hasta'] = "{$campos['dia_hasta']}";
 		$validator = Validator::validate($campos, $reglas, $nombres);
		if ($validator->isSuccess()) {
			return true;
		}
		$this->errores = $validator->getErrors();

		return false;
	}

	/**
	 * @return bool
	 */
	public function modificacion() {
		if ($this->validar()) {
		$this->generar_horario();
		$sql = "UPDATE
					plantilla_horarios
				SET
					nombre		= :nombre,
					horario		= :horario
				WHERE 
					id = :id";
		if (!empty($this->id)) {
			$params = [
				':id'     => $this->id,
				':nombre' => $this->nombre,
				':horario'  => $this->horario
	
			];
			$res = (new Conexiones)->consulta(Conexiones::UPDATE, $sql, $params);
			if (!empty($res) && $res > 0) {
				//Log
				$datos = (array)$this;
				$datos['modelo'] = 'horario';
				Logger::event('modificacion', $datos);

				return true;
			}
		}
	}
		return false;
	}

	/**
	 * @return bool
	 */
	public function baja() {
		$params = [':id' => $this->id];
		$conex = new Conexiones;
		$sql = "UPDATE plantilla_horarios SET borrado = 1 WHERE id = :id";
		$res = $conex->consulta(Conexiones::UPDATE, $sql, $params);
		if (!empty($res) && is_numeric($res) && $res > 0) {
			//Log
			$datos = (array)$this;
			$datos['modelo'] = 'horario';
			Logger::event('baja', $datos);

			return true;
		}

		return false;
	}
	
	protected function generar_horario() {
		$horario = [];
		for ($i=0; $i <= 6 ; $i++) {
			$horario[$i] = []; 
			if($i >= $this->dia_desde && $i <= $this->dia_hasta) {
				$horario[$i][] = $this->hora_desde;
				$horario[$i][] = $this->hora_hasta;				
			}
		}
		$this->horario = json_encode($horario);
	}
}