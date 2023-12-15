<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class TituloCredito extends Modelo {

/**@var int*/
	public $id;
/**@var int*/
	public $id_persona_titulo;
/**@var int*/
	public $id_persona;
/**@var date*/
	public $fecha;
/**@var string*/
	public $acto_administrativo;
/**@var string*/
	public $creditos;
/**@var string*/
	public $archivo;
/** @var int */
	public $estado_titulo;
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
			'id_persona_titulo',
			'id_persona',
			'fecha',
			'acto_administrativo',
			'creditos',
			'archivo',
			'estado_titulo',
			'borrado'
		
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM persona_titulo_creditos
			WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
  }

	static public function listar_historial_creditos($id_persona_titulo=null) {
		if(!is_numeric($id_persona_titulo)) {
			return [];
		}
		$sql_params	= [
			':id_persona_titulo'	=> $id_persona_titulo
		];
		$sql	= <<<SQL
			SELECT ptc.id, ptc.fecha, ptc.acto_administrativo,  ptc.creditos, ptc.archivo, ptc.estado_titulo, t.nombre AS nombre_titulo, e.id as id_empleado
			FROM persona_titulo_creditos ptc
			INNER JOIN persona_titulo pt ON pt.id = ptc.id_persona_titulo
			INNER JOIN titulo t ON t.id = pt.id_titulo
			INNER JOIN empleados e ON e.id_persona = pt.id_persona
			WHERE pt.borrado = 0 AND pt.id = :id_persona_titulo
			ORDER BY ptc.id DESC
SQL;
		$resp	= (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(empty($resp)) { return []; }
		foreach ($resp as &$value) {
			$value['fecha']	= \DateTime::createFromFormat('Y-m-d', $value['fecha']);
		}
		return $resp;
	}

/**
 * Obtiene la cantidad de creditos acumulados para el titulo de una persona
 *
 * @param int $titulo_persona_id
 * @return int
 */
	static public function acum_creditos_de_titulo($titulo_persona_id=null) {
		if(!is_numeric($titulo_persona_id)) {
			return (int)'0';
		}
		
		$sql_params	= [
			':id_titulo'	=> $titulo_persona_id
		];
		$sql	= <<<SQL
		SELECT SUM(creditos) as acum_creditos
			FROM persona_titulo_creditos AS ptc
			WHERE ptc.id_persona_titulo =:id_titulo AND ptc.borrado =0
            GROUP BY ptc.id_persona_titulo;
SQL;

		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return (int)$res[0]['acum_creditos'];
		}
		return (int)'0';
	}

	public function alta(){
		$this->upload_archivo();
		$campos	= [
			'id_persona_titulo',
			'id_persona',
			'fecha',
			'acto_administrativo',
			'creditos',
			'archivo',
			'estado_titulo'
		];
		$sql_params	= [
			':id_persona_titulo' => $this->id_persona_titulo,
			':id_persona' => $this->id_persona,
			':fecha' => $this->fecha,
			':acto_administrativo' => $this->acto_administrativo,
			':creditos' => $this->creditos,
			':archivo'=>$this->archivo,
			':estado_titulo'=>$this->estado_titulo,

		];
		foreach ($campos as $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
		}

		if($this->fecha instanceof \DateTime){
			$sql_params[':fecha']	= $this->fecha->format('Y-m-d');
		}

		$sql	= 'INSERT INTO persona_titulo_creditos('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$this->id 			= $res;

			$id_empleado	= Persona::getEmpleado($this->id_persona);
			EmpleadoHistorialCreditos::agregarPorcentaje(
				$id_empleado,
				$this->id,
				EmpleadoHistorialCreditos::TABLA_PERSONA_TITULO_CREDITOS,
				$this->creditos,
				$this->fecha
			);
			
			$datos = (array) $this;
			$datos['modelo'] = 'TituloCredito';
			Logger::event('alta', $datos);
		}
		return $res;
	}

	protected function upload_archivo(){
		if(isset($this->archivo["error"])){
			$error_file = false;
			if (!$error_file) {
				$rta = false;
				$date_time = gmdate('YmdHis');
				$directorio = BASE_PATH.'/uploads/tituloCreditos';

				$name =   $this->archivo["name"];
				$nombre_archivo = $date_time.'_'.$name;
				
				if(!is_dir($directorio)){
					mkdir($directorio, 0777, true);
				}
				if(move_uploaded_file($this->archivo['tmp_name'], $directorio."/".$nombre_archivo)){
			        $this->archivo = $nombre_archivo;
			         $rta = true; 
			    }
			    return $rta;
				
			}
		}
	}

	public function modificacion(){
		$this->upload_archivo();
		$campos	= [
			'id_persona_titulo',
			'id_persona',
			'fecha',
			'acto_administrativo',
			'creditos',
			'archivo',
			'estado_titulo',
			'borrado'
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

		$sql	= 'UPDATE persona_titulo_creditos SET '.implode(',', $campos).' WHERE id = :id';
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			//Cargo EmpleadoHistorialCreditos 
			//Edito el registro y coloco borrado = 1
			//Inserto un nuevo registro de la modiciación
			$id_empleado	= Persona::getEmpleado($this->id_persona);
			EmpleadoHistorialCreditos::bajaCredito(
				$id_empleado,
				$this->id,
				EmpleadoHistorialCreditos::TABLA_PERSONA_TITULO_CREDITOS
			);
			EmpleadoHistorialCreditos::agregarPorcentaje(
				$id_empleado,
				$this->id,
				EmpleadoHistorialCreditos::TABLA_PERSONA_TITULO_CREDITOS,
				$this->creditos,
				$this->fecha
			);

			$datos = (array) $this;
			$datos['modelo'] = 'TituloCredito';
			Logger::event('modificacion', $datos);
		}
		return $res;
	}

	public function baja(){
		$cnx = new Conexiones;
		$params = [':id' => $this->id];
		$sql = <<<SQL
		UPDATE persona_titulo_creditos SET  borrado = 1 WHERE id = :id
SQL;
		$res = $cnx->consulta(Conexiones::SELECT, $sql, $params);
		if ($res !== false) {

			$id_empleado	= Persona::getEmpleado($this->id_persona);
			EmpleadoHistorialCreditos::bajaCredito(
				$id_empleado,
				$this->id,
				EmpleadoHistorialCreditos::TABLA_PERSONA_TITULO_CREDITOS
			);

			$datos = (array) $this;
			$datos['modelo'] = 'TituloCredito';
			Logger::event('baja', $datos);
		} else {
			$datos['error_db'] = $cnx->errorInfo;
			Logger::event("error_baja",$datos);
		}
		return $res;
	}
	
	static public function obtener_archivo($id){
		//$aux='';
		if($id){
	    $Conexiones = new Conexiones();
	    $resultado = $Conexiones->consulta(Conexiones::SELECT,
<<<SQL

			SELECT  archivo
			FROM persona_titulo_creditos
			WHERE id = :id
			LIMIT 1
SQL
,[':id'=>$id]);
	
	    return $resultado[0]['archivo'];
		}

	}


	public function validar(){
		$campos['hoy'] = new \DateTime();
		$campos['estado'] = \App\Modelo\PersonaTitulo::COMPLETO;
		$rules = [
			'fecha' => ['required', 'fecha'],
			'acto_administrativo' => ['required'],
			'creditos' => ['required', 'numeric',
			'rango_valido' => function($input) {
				return ((int)$input > 1);
				}
			],
			'archivo'   => ['extension' => function($input){
				if (!empty($input)){
					if (is_array($input)) {
						$ext = pathinfo($input['name'], PATHINFO_EXTENSION);
					}else{
						$ext = pathinfo($input, PATHINFO_EXTENSION);
					}
					return  ($ext == 'pdf');
				}
				return true;

				}],
		    'id_persona_titulo' => ['tituloCompleto(:estado)' => function($input, $estado){
						if ($input) {
							$sql = <<<SQL
							SELECT count(*) count FROM persona_titulo_creditos WHERE estado_titulo = :estado
							AND id_persona_titulo = :id_persona_titulo;
SQL;
							$params = [':id_persona_titulo' => $input, ':estado' => $estado];
							$con = new Conexiones();
							$res = $con->consulta(Conexiones::SELECT, $sql, $params);
							if ($res[0]['count'] <= 0) {
								return true;
							}
							return false;
						}
						return true;
					}
				],
		   
		];
		$nombres	= [
			'fecha'		=> 'Fecha',
			'acto_administrativo'	=> 'Acto Administrativo',
			'creditos'	=> 'Créditos',
			'archivo'				=> 'Archivo Comprobante',
		];

		$validator = Validator::validate((array)$this, $rules, $nombres);
		$validator->customErrors([
            'extension'         => 'Extensión inválida. Se permite sólo archivos pdf',
            'tituloCompleto'    => 'No es posible cargar nuevos créditos cuando el estado de titulo del empleado es completo.',
            'rango_valido'		=> 'El campo  <b> :attribute </b> debe estar comprendido entre 1 y 100.',
       ]);
		if ($validator->isSuccess()) {
			return true;
		}
		else {
			$this->errores = $validator->getErrors();
			return false;
		}
	}
	
/**
 * Verifica si un titulo en particular tiene estado completo.
 *
 * @param int $id_persona_titulo
 * @return bool
 */
	static public function tituloCompleto($id_persona_titulo=null){
		$sql_params	= [
			':estado_titulo'	=> \App\Modelo\PersonaTitulo::COMPLETO,
			':id_persona_titulo'	=> $id_persona_titulo,
		];
		$sql =  <<<SQL
			SELECT count(*) count FROM persona_titulo_creditos WHERE estado_titulo = :estado_titulo
			AND id_persona_titulo = :id_persona_titulo;
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		if ($res[0]['count'] == '0') {
			return false;
		}
		return true;
	}
	
	
	
	static public function arrayToObject($res = []) {
		$campos	= [
			'id'					=> 'int',
			'id_persona_titulo'		=> 'int',
			'id_persona'			=> 'int',
			'fecha'		    		=> 'date',
			'acto_administrativo'	=> 'string',
			'creditos'				=> 'string',
			'archivo'				=> 'string',
			'estado_titulo'			=> 'int',
			'borrado'				=> 'int',
		];
		return parent::arrayToObject($res, $campos);
	}
}