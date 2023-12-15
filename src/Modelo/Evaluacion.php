<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class Evaluacion extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_perfil;
/** @var int */
	public $id_empleado;
/** @var string */
	public $acto_administrativo;
/** @var int */
	public $evaluacion;
/** @var int */
	public $anio;
/** @var string */
	public $archivo;
/** @var \DateTime */
	public $fecha_evaluacion;
/** @var int */
	public $formulario;
/** @var int */
	public $puntaje;
/** @var int */
	public $bonificado;


	
/**  TIPO DE EVALUCION */
	const MUY_DESTACADO = 1;
	const DESTACADO = 2;
	const BUENO = 3;
	const REGULAR = 4;
	const DEFICIENTE = 5;
	const NO_EVALUADO = 6;

	static protected $resultados	= [
		self::MUY_DESTACADO	=> ['id' => self::MUY_DESTACADO, 	'nombre' => 'MUY DESTACADO', 'borrado' => '0'],
		self::DESTACADO		=> ['id' => self::DESTACADO,		'nombre' => 'DESTACADO', 'borrado' => '0'],
		self::BUENO			=> ['id' => self::BUENO,			'nombre' => 'BUENO', 'borrado' => '0'],
		self::REGULAR		=> ['id' => self::REGULAR,			'nombre' => 'REGULAR', 'borrado' => '0'],
		self::DEFICIENTE	=> ['id' => self::DEFICIENTE,		'nombre' => 'DEFICIENTE', 'borrado' => '0'],
		self::NO_EVALUADO	=> ['id' => self::NO_EVALUADO,		'nombre' => 'NO EVALUADO', 'borrado' => '0'],
	];

	/**  FORMULARIOS DE EVALUCION */
	const FORMULARIO1 = 1;
	const FORMULARIO2 = 2;
	const FORMULARIO3 = 3;
	const FORMULARIO4 = 4;
	const FORMULARIO5 = 5;
	const FORMULARIO6 = 6;

	static protected $formularios	= [
		self::FORMULARIO1	=> ['id' => self::FORMULARIO1, 	'nombre' => 'FORMULARIO 1', 'borrado' => '0'],
		self::FORMULARIO2	=> ['id' => self::FORMULARIO2,	'nombre' => 'FORMULARIO 2', 'borrado' => '0'],
		self::FORMULARIO3	=> ['id' => self::FORMULARIO3,	'nombre' => 'FORMULARIO 3', 'borrado' => '0'],
		self::FORMULARIO4	=> ['id' => self::FORMULARIO4,	'nombre' => 'FORMULARIO 4', 'borrado' => '0'],
		self::FORMULARIO5	=> ['id' => self::FORMULARIO5,	'nombre' => 'FORMULARIO 5', 'borrado' => '0'],
		self::FORMULARIO6	=> ['id' => self::FORMULARIO6,	'nombre' => 'FORMULARIO 6', 'borrado' => '0'],
	];

	static protected $formularios_valores	= [
		self::FORMULARIO1	=> ['min' => 1, 'max' => 56],
		self::FORMULARIO2	=> ['min' => 1,	'max' => 48],
		self::FORMULARIO3	=> ['min' => 1,	'max' => 48],
		self::FORMULARIO4	=> ['min' => 1,	'max' => 40],
		self::FORMULARIO5	=> ['min' => 1,	'max' => 32],
		self::FORMULARIO6	=> ['min' => 1,	'max' => 24],
	];

	static public function obtener($id=null){
		if($id===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id'	=> $id,
		];
		$sql	= <<<SQL
		SELECT  pe.id,
				pe.id_perfil,
				pe.id_empleado,
				pe.acto_administrativo,
				pe.evaluacion,
				pe.anio,
				pe.archivo,
				pe.fecha_evaluacion,
				pe.formulario,
				pe.puntaje,
				pe.bonificado
			FROM empleado_evaluaciones pe
			INNER JOIN empleados ep ON ep.id = pe.id_empleado AND pe.borrado = 0 
			WHERE pe.id = :id  
			ORDER BY pe.fecha_evaluacion DESC
			LIMIT 1
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res[0])){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}

	static public function listar($id_empleado=null) { //id de empleado
		if($id_empleado===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id_empleado'	=> $id_empleado,
		];
		$sql	= <<<SQL
			SELECT  pe.id,
				pe.id_perfil,
				pe.id_empleado,
				pe.acto_administrativo,
				pe.evaluacion,
				pe.anio,
				pe.archivo,
				pe.fecha_evaluacion,
				pe.formulario,
				pe.puntaje,
				pe.bonificado
			FROM empleado_evaluaciones pe
			INNER JOIN empleados em ON em.id = pe.id_empleado AND pe.borrado = 0 
			INNER JOIN empleado_perfil ep ON ep.id_empleado = em.id
			WHERE pe.id_empleado = :id_empleado
			order by pe.anio DESC
SQL;
		$resp	= (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql,$sql_params);
		if(empty($resp)) { return []; }
		foreach ($resp as &$value) {
			$value	= static::arrayToObject($value);
		}
		return $resp;
	}

	
	public function alta(){
		if(!$this->validar()){
			return false;
		}
		$this->upload_archivo();
		$campos	= [
			'id_perfil',
			'id_empleado',
			'acto_administrativo',
			'evaluacion',
			'anio',
			'archivo',
			'fecha_evaluacion',
			'formulario',
			'puntaje',
			'bonificado'
		];
		$sql_params	= [
			':id_perfil' => $this->id_perfil,
			':id_empleado' => $this->id_empleado, 
			':acto_administrativo' => $this->acto_administrativo,
			':evaluacion' => $this->evaluacion,
			':anio' => $this->anio,
			':fecha_evaluacion'=>$this->fecha_evaluacion,
			':archivo' => $this->archivo, 
			':formulario' => $this->formulario,
			':puntaje' => $this->puntaje,
			':bonificado' => $this->bonificado
		];
		if($this->fecha_evaluacion instanceof \DateTime){
			$sql_params[':fecha_evaluacion']	= $this->fecha_evaluacion->format('Y-m-d');
		}
		$sql	= 'INSERT INTO empleado_evaluaciones('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);

		if($res !== false){
			$this->id	= $res;
			$datos = (array) $this;
			$datos['modelo'] = 'Evaluacion';
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
				$directorio = BASE_PATH.'/uploads/evaluacion';

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
		return false;
	}


	public function baja(){
		$sql_params= [':id' => $this->id];
		$sql = <<<SQL
		update empleado_evaluaciones set borrado = 1 where id = :id;
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Evaluacion';
			Logger::event('baja', $datos);
			$aux = self::obtener($this->id);
			foreach (get_object_vars ($this) as $key => $value) {
				$this->{$key} = $aux->{$key};
			}			
		}
		return $res;
	}

	
	public function modificacion(){
		if(!$this->validar() || empty($this->id)){
			return false;
		}
		$campos	= [
			'id_perfil'  => 'id_perfil = :id_perfil',
			'id_empleado'  => 'id_empleado = :id_empleado',
			'acto_administrativo' => 'acto_administrativo = :acto_administrativo',
			'evaluacion' => 'evaluacion = :evaluacion',
			'anio' => 'anio = :anio' ,
			'fecha_evaluacion' => 'fecha_evaluacion = :fecha_evaluacion' ,
			'formulario' => 'formulario = :formulario',
			'puntaje' => 'puntaje = :puntaje',
			'bonificado' => 'bonificado = :bonificado'
		];
		$sql_params	= [
			':id'	=> $this->id,
			':id_perfil' => $this->id_perfil,
			':id_empleado' => $this->id_empleado,
			':acto_administrativo' => $this->acto_administrativo,
			':evaluacion' => $this->evaluacion,
			':anio' => $this->anio,
			':fecha_evaluacion'=>$this->fecha_evaluacion,
			':formulario' => $this->formulario,
			':puntaje' => $this->puntaje,
			':bonificado' => $this->bonificado
		];
		if (isset($this->archivo)){
			$this->upload_archivo();
			$campos['archivo'] = 'archivo = :archivo';
			$sql_params[':archivo'] = $this->archivo;
		}
		$sql	= 'UPDATE empleado_evaluaciones SET '.implode(',', $campos).' WHERE id = :id';
		
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Evaluacion';
			Logger::event('modificacion', $datos);
		}
		return $res;
	 }


	 
	public function validar() {
		$array_evaluacion = (array)$this;
		$min = '';
		$max = '';
		
		if(!empty($array_evaluacion['formulario'])){
			$min = self::$formularios_valores[$array_evaluacion['formulario']]['min'];
			$max = self::$formularios_valores[$array_evaluacion['formulario']]['max'];
		}
		$array_evaluacion['archivo'] = is_array($this->archivo) ? $this->archivo["name"] : $this->archivo;
		$reglas		= [
			'id_perfil'				=> ['required'],
			'acto_administrativo'  	=> ['required'],
			'evaluacion'  			=> ['required','integer'],
			'formulario' 	 		=> ['required','integer' ],
			'puntaje' 				=> ['required','integer',
			'enRango(:formulario)'				=> function($input,$formulario){
				// SELF es bueno en este contexto =D
				if(!empty($input) && !empty(self::$formularios_valores[$formulario]['min']) && !empty(self::$formularios_valores[$formulario]['max'])){
					return ($input >= self::$formularios_valores[$formulario]['min'] && $input <= self::$formularios_valores[$formulario]['max']);
				}
				return true;	
			}					
			],
			'anio' 	 				=> ['required','integer', 
			'menorA' => function($input){
				$anioactual = (int)date('Y');
				if(!empty($input)){
					return ($input <= $anioactual);
				}
				return false;	
			},
			'validar_anio(:id,:id_empleado)' => function($input,$id, $id_empleado){	
					$params = [];
					if(!isset($id)){$id=0;}
					$sql = <<<SQL
					SELECT count(*) count FROM empleado_evaluaciones WHERE id_empleado = :id_empleado AND id <> :id AND anio = :anio 
SQL;
					$params += [':anio' => $input, ':id_empleado' => $id_empleado, ':id' => $id ];
					$con = new Conexiones();
					$res = $con->consulta(Conexiones::SELECT, $sql, $params);
					if (is_array($res) && isset($res[0]) && isset($res[0]['count'])) {
						return ($res[0]['count'] < 1);
					}
					return false;
			},
		]
			
		];
		if(!empty($this->archivo)){
			$reglas	+= [
				'archivo'  	 		 => ['extension' => function($input){
		              $ext = pathinfo($input, PATHINFO_EXTENSION);
		              if (!empty($input)){
		                  return  ($ext == 'pdf');
		              }
		              return true;
		          }],
			];
		}
		$nombres	= [
			'id_perfil'				=> 'Perfil de Puesto',
			'acto_administrativo'	=> 'Acto Administrativo',
			'evaluacion' 			=> 'Resultado de la Evaluación',
			'anio'					=> 'Año de Evaluación',
			'archivo'				=> 'Archivo Adjunto',
			'fecha_evaluacion' 		=> 'Fecha Evaluación',
			'formulario'			=> 'Formulario',
			'puntaje'   			=> 'Puntaje'
		];
		$validator	= Validator::validate($array_evaluacion, $reglas, $nombres);
		$validator->customErrors([
			'validar_anio'	=> 'Ya existe una evaluación para el <b> :attribute </b> seleccionado',
			'menorA' 		=> 'El <b> :attribute </b> debe ser igual o menor al actual',
			'enRango'	 	=> 'El <b> :attribute </b> para el formulario seleccionado debe estar comprendido entre '. $min. ' y ' .$max,
			'extension'		=> 'El formato del archivo adjunto no es el permitido, debe adjuntarlo en formato PDF'


        ]);
	    if ($validator->isSuccess() == true) {
	      return true;
	    } 
		if ($validator->isSuccess()) {
			return true;
		}
		$this->errores = $validator->getErrors();
		return false;
	}


	static public function obtener_archivo($id_evaluacion=null){
		//$aux='';
		if($id_evaluacion){
	    $Conexiones = new Conexiones();
	    $resultado = $Conexiones->consulta(Conexiones::SELECT,
<<<SQL

			SELECT  archivo
			FROM empleado_evaluaciones
			WHERE id = :id_evaluacion
			LIMIT 1
SQL
,[':id_evaluacion'=>$id_evaluacion]);
	
	    return $resultado[0]['archivo'];
		}

	}

	static public function arrayToObject($res = []) {
		$campos	= [
            'id'                        => 'int',
			'id_perfil'                 => 'int',
			'id_empleado'               => 'int',
			'acto_administrativo'       => 'string',
			'evaluacion'    		 	=> 'int',
			'anio'         				=> 'int',
			'archivo'					=> 'string',
		    'fecha_evaluacion'			=> 'date',
			'formulario' 				=> 'int',
			'puntaje'					=> 'int',
			'bonificado'				=> 'int'
		];
		return parent::arrayToObject($res, $campos);
	}
}