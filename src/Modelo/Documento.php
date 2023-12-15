<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class Documento extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_empleado;
/** @var int */
	public $id_tipo;
/** @var int */
	public $id_usuario;
/** @var string */
	public $archivo;
/** @var datetime */
	public $fecha_reg;
/** @var int */
	public $cuit;
/** @var string */
	public $persona;

/** @var string */
	public $usuario;

/**
 * Devuelve el directorio temporal para guardar archivos temporales.
 * @return string
 */
	static public function getDirectorioTMP(){
		$return	= BASE_PATH.'/uploads/temporal_consola';
		if(!is_dir($return)){
			mkdir($return, 0777, true);
		}
		static::cleanTempDirectory($return);
		return $return;
	}

/**
 * Elimina los archivos usados en un directorio.
 * @param string $dir
 * @return void
 */
	static private function cleanTempDirectory($dir=null){
		$archivos	= scandir($dir);
		
		$now_date	= new \DateTime('now');
		foreach ($archivos as $value) {
			if($value == '.' || $value == '..'){
				continue;
			}
			$file_date	= \DateTime::createFromFormat('U', filemtime($dir.'/'.$value));
			$diff_date	= ($now_date->diff($file_date))->format('%a');
			if((int)$diff_date > 14){
				unlink($dir.'/'.$value);
			}
		}
	}

	static public function obtener($id = null){
		if($id) {
			$sql_params	= [
				':id'	  => $id
			];
			$campos	= 'doc.' . implode(', doc.', [
				'id',
				'id_empleado',
				'id_tipo',
				'id_usuario',
				'nombre_archivo',
				'fecha_reg'
			]);

			$sql	= <<<SQL
				SELECT {$campos}
				FROM empleado_documentos AS doc
				WHERE doc.id = :id
				AND doc.borrado = 0
SQL;
			$cnx	= new Conexiones();
			$res	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);

			if(!empty($res)){
				return static::arrayToObject($res[0]);
			}
		}
		return static::arrayToObject();
	}
	static public function listar() {}

	static public function listar_tipo() {
		$sql	= <<<SQL
			SELECT id,nombre, borrado
			FROM tipo_documento AS doc
			WHERE borrado = 0
			ORDER BY nombre
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::SELECT, $sql);

		$aux = [];
		if ($res) {
			foreach ($res as $value) {
				if($value['id'] == 1){
					$fin[$value['id']] = $value;
				}else{ 
					$aux[$value['id']] = $value;
				}
			}
			$aux += $fin;
		}
		return $aux;
	}

	public function obtener_doc_tipo() {
		$sql_params	= [
			':id_empleado'	  => $this->id_empleado,
		];
		$sql	= <<<SQL
			SELECT ed.id, ed.id_tipo, td.nombre
			FROM empleado_documentos AS ed
			INNER JOIN tipo_documento as td ON ed.id_tipo = td.id
			WHERE ed.id_empleado = :id_empleado AND ed.borrado = 0
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::SELECT, $sql,$sql_params);
		$aux = [];
		if ($res) {
			foreach ($res as $value) {
				$aux[$value['id']] = $value;
			}
		}
		return $aux;
	}

	public function listar_documentos() {

		$sql_params	= [
			':cuit'	  => $this->cuit,
			':tipo' => $this->id_tipo
		];
		$campos	= 'doc.' . implode(', doc.', [
			'id',
			'id_empleado',
			'id_tipo',
			'id_usuario',
			'nombre_archivo',
			'fecha_reg'
		]);
		$campos	= $campos . ', emp.' . implode(', emp.', [
			'cuit',
			'id_persona'
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM empleado_documentos AS doc
			JOIN empleados AS emp ON (emp.id = doc.id_empleado and emp.cuit = :cuit)
			WHERE doc.id_tipo = :tipo
			AND doc.borrado = 0

SQL;
		$cnx	= new Conexiones();
		$res	= (array)$cnx->consulta(Conexiones::SELECT, $sql, $sql_params);

		foreach ($res as &$value) {
			$value	= static::arrayToObject($value);
		}
		return $res;
	}

	public function alta(){
		if($this->upload_archivo()) {
			$cnx	= new Conexiones();
			$campos	= [
				'id_empleado',
				'id_tipo',
				'id_usuario',
				'nombre_archivo',
				'fecha_reg'
			];
			$sql_params	= [
				':id_empleado'		=> $this->id_empleado,
				':id_tipo'			=> $this->id_tipo,
				':id_usuario'		=> $this->id_usuario,
				':nombre_archivo'	=> $this->archivo,
			];

			if($this->fecha_reg instanceof \DateTime){
				$sql_params[':fecha_reg']	= $this->fecha_reg->format('Y-m-d');
			}

			$sql	= 'INSERT INTO empleado_documentos('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
			$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);

			if($res !== false){
				$this->id	= $res;
				$datos = (array) $this;
				$datos['modelo'] = 'Documento';
				Logger::event('alta', $datos);
				return true;
			} else {
				$this->errores['documentos_empleados']	= $cnx->errorInfo[2];
				return false;
			}
		}
	}


	protected function upload_archivo() {
		$error_file = true;

		if (isset($this->archivo['error']) && ($this->archivo['error'] == UPLOAD_ERR_OK)) {
			$file_name = explode(".", $this->archivo['name']);
			$file_ext = strtolower(end($file_name));
			$extensiones = array('pdf', 'docx', 'xlsx','xls','csv');
			if (!in_array($file_ext, $extensiones)) {
				$this->errores[] = 'Sólo se permiten archivos en formatos "pdf", "docx", "xlsx", "xls" y "csv"';		
				$error_file = false; 
			}
		} else {
			$error_file = false; 
			$this->errores[] = 'Ha ocurrido un error, por favor intente nuevamente';
		}
			
		if ($error_file) {
			$date_time = gmdate('YmdHis');
			$nombre_archivo = $date_time.'-'.$file_name[0].'.'.$file_ext;
			$directorio = BASE_PATH.'/uploads/'.$this->cuit.'/';
			if(!is_dir($directorio)){
				mkdir($directorio, 0777, true);
			}
			if(move_uploaded_file($this->archivo['tmp_name'], $directorio.'/'.$nombre_archivo)){
				$this->archivo = $nombre_archivo;
			}else{
				$this->errores[] = 'Ha ocurrido un error en el sistema al guardar el archivo';		
				$error_file	= false;
			}
		}
		return $error_file;
	}


	public function baja(){
		$sql	= <<<SQL
			UPDATE empleado_documentos SET borrado = 1 WHERE id = :id
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, [':id' => $this->id]);
		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'Documento';
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
		$rta = (isset($this->archivo['error']) && $this->archivo['error'] != UPLOAD_ERR_NO_FILE)  ? $this->upload_archivo() : true;
		
		if($rta) {		
			$cnx	= new Conexiones();
			$campos	= [
				'id_usuario'		=> 'id_usuario  = :id_usuario',
				'nombre_archivo'	=> 'nombre_archivo = :nombre_archivo',
				'id_tipo'			=> 'id_tipo = :id_tipo'
			];
			$sql_params	= [
				':id_usuario'		=> $this->id_usuario,
				':nombre_archivo'	=> $this->archivo,
				':id_tipo'			=> $this->id_tipo,
				':id'				=> $this->id,				
			];

			if($this->fecha_reg instanceof \DateTime){
				$campos['fecha_reg'] = 'fecha_reg  = :fecha_reg';
				$sql_params[':fecha_reg']	= $this->fecha_reg->format('Y-m-d');
			}

			$sql	= 'UPDATE empleado_documentos SET '.implode(',', $campos).' WHERE id = :id';
			$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);
			if($res !== false){
				$this->id	= $res;
				$datos = (array) $this;
				$datos['modelo'] = 'Documento';
				Logger::event('modificacion', $datos);
				return true;
			} else {
				$this->errores['documentos_empleados']	= $cnx->errorInfo[2];
				return false;
			}
		}
	}

	public function validar() {
		return true;
	}

	static public function get_nombre_tipo($tipo){
		$cnx = new Conexiones();
		$sql_params	= [
			':tipo'		=> $tipo,
		];
		$sql = 'SELECT nombre
				FROM tipo_documento
				WHERE id = :tipo';
		$res = $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		if (!empty($res)) {
			return$res[0]['nombre'];
		}
	}

	static public function arrayToObject($res = []) {
		$campos	= [
			'id'				=> 'int',
			'id_empleado'		=> 'int',
			'id_tipo'			=> 'int',
			'id_usuario'		=> 'int',
			'fecha_reg'			=> 'date',
			'cuit' 				=> 'int',
		];
		$obj = new static;
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
		$obj->archivo = ($aux =\FMT\Helper\Arr::get($res,'nombre_archivo')) ? $aux : ''; 
		
		$per = \App\Modelo\Persona::obtener(\FMT\Helper\Arr::get($res,'id_persona'));
		$obj->persona 			= new \StdClass();
		$obj->persona->nombre   = $per->nombre;
		$obj->persona->apellido = $per->apellido;

		$usu = \App\Modelo\Usuario::obtener(\FMT\Helper\Arr::get($res,'id_usuario'));
		$obj->usuario 			= new \StdClass();
		$obj->usuario->full_name   = $usu->nombre.' '.$usu->apellido;
		return $obj;
	}
}