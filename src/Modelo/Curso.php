<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class Curso extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $codigo;
/** @var string */
	public $nombre_curso;
/** @var int */
	public $creditos;
/** @var int */
	public $borrado;
	
	const PROMOCION_TRAMO = 2;
	const PROMOCION_GRADO = 1;

	static public $TIPO_PROMOCIONES	= [
		self::PROMOCION_TRAMO	=> ['id' => self::PROMOCION_TRAMO, 'nombre' => 'Tramo', 'borrado' => '0'],
		self::PROMOCION_GRADO	=> ['id' => self::PROMOCION_GRADO, 'nombre' => 'Grado', 'borrado' => '0']
	];

	static public function obtener($id=null){
		if($id===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id'	=> $id,
		];
		$campos	= implode(',', [
			'id',
			'codigo',
			'nombre_curso',
			'creditos',
			'borrado'
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM cursos_snc
			WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}
	 
	static public function listar() {
		$campos	= implode(',', [
			'codigo',
			'nombre_curso',
			'creditos',
			'borrado'
		]);
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM cursos_snc
			ORDER BY id ASC
SQL;
		$resp	= (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql);
		if(empty($resp[0])) { return []; }
		foreach ($resp as &$value) {
			$value	= static::arrayToObject($value);
		}

		return $resp;
	}

	static public function listarParaSelect(){
		$lista 	= static::listar();
		$aux	= [];
		foreach ($lista as $item) {
			$aux[$item->id]	= $item;
		}
		return $aux;
	}

	static public function listarConcatenado(){
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

	public function alta(){
		if(!$this->validar()){
			return false;
		}
		$cnx = new Conexiones();
		$sql_params	= [
			':codigo' => $this->codigo,
			':nombre_curso' => $this->nombre_curso,
			':creditos' => $this->creditos
		];
		$sql = 'INSERT INTO cursos_snc(nombre_curso, codigo, creditos) VALUES (:nombre_curso, :codigo, :creditos)';
		$res = $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$this->id = $res;
			$datos = (array) $this;
			$datos['modelo'] = 'Curso';
			Logger::event('alta', $datos);
		}
		return $res;
	}

	public function modificacion(){
		if(!$this->validar()){
			return false;
		}
		$cnx = new Conexiones();
		$campos	= [
			'codigo'	=> 'codigo = :codigo',
			'nombre_curso'	=> 'nombre_curso = :nombre_curso',
			'creditos'	=> 'creditos = :creditos',
		];
		$sql_params	= [
			':id'		=> $this->id,
			':codigo'	=> $this->codigo,
			':nombre_curso'	=> $this->nombre_curso,
			':creditos'	=> $this->creditos,
		];
		$sql = 'UPDATE cursos_snc SET '.implode(',', $campos).' WHERE id = :id';
		$res = $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Curso';
			Logger::event('modificacion', $datos);
			return true;
		}
		return false;
	}

	public function baja(){
		$cnx	= new Conexiones();
		$sql_params	= [
			':id'		=> $this->id,
		];
		$sql	= 'UPDATE cursos_snc SET borrado = 1 WHERE id = :id';
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);

		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'Curso';
			if (is_numeric($res) && $res > 0) {
				$flag = true;
			} else {
				$datos['error_db'] = $cnx->errorInfo;
			}
			Logger::event('baja', $datos);
		}
		return $flag;
	}

	public function validar() {
		$campos = (array)$this;
		$reglas		= [
			'id' 		=> ['numeric'],
			'codigo'	=> ['required'],
			'nombre_curso'	=> ['required','texto', 'min_length(5)', 'max_length(200)', 'curso_unico(:codigo,:id)' => function($input, $codigo, $id){
				$params = [':nombre_curso' => $input, ':codigo' => $codigo];
				$where_id = '';
				if($id){
					$where_id = " AND id != :id";
					$params[':id'] = $id;
				}

				$sql = <<<SQL
							SELECT count(*) count FROM cursos_snc WHERE nombre_curso = :nombre_curso AND codigo = :codigo $where_id
SQL;
				$con = new Conexiones();
				$res = $con->consulta(Conexiones::SELECT, $sql, $params);
				if (is_array($res) && isset($res[0]) && isset($res[0]['count'])) {
					return !($res[0]['count'] > 0);
				}
				return false;
			}],
			'creditos'	=> ['required', 'integer'],
			'borrado' 	=> ['numeric']
		];
		$nombre= [
			'codigo'	=> 'Código',
			'nombre_curso'	=> 'Nombre del Curso', 	
			'creditos'	=> 'Créditos', 	
		];

		$validator	= Validator::validate($campos, $reglas, $nombre);
		$validator->customErrors([
            'curso_unico'      => 'Ya existe un curso con el código y nombre.',
        ]);
		if ($validator->isSuccess()) {
			return true;
		}
		$this->errores = $validator->getErrors();
		return false;
	}

/**
 * Obtener curso por codigo
 *
 * @param string $codigo
 * @return Curso::
 */
	static public function obtener_x_codigo($codigo=null){
		if(empty($codigo)){
			return static::arrayToObject();
		}
		$sql_params	= [
			':codigo'	=> $codigo,
		];
		$campos	= implode(',', [
			'id',
			'codigo',
			'nombre_curso',
			'creditos',
			'borrado'
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM cursos_snc
			WHERE codigo = :codigo
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}

	static public function arrayToObject($res = []) {
		$campos	= [
			'id'		=> 'int',
			'codigo'	=> 'string',
			'nombre_curso'	=> 'string',
			'creditos'	=> 'int',
		];
		return parent::arrayToObject($res, $campos);
	}
}
