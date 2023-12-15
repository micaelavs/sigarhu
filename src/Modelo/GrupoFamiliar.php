<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

/**
 * Descripcion de datos: Secretarias, Subsecretarias, etc
*/
class GrupoFamiliar extends Modelo {
  /** @var int */
  public $id;
  /** @var int */
  public $id_empleado;
  /** @var int */
  public $parentesco;
  /** @var string */
  public $nombre;
  /** @var string */
  public $apellido;
  /** @var datetime */
  public $fecha_nacimiento;
  /** @var string */
  public $nacionalidad;
  /** @var int */
  public $tipo_documento;
  /** @var string */
  public $documento;
  /** @var int */
  public $nivel_educativo;
  /** @var int */
  public $reintegro_guarderia;
  /** @var int */
  public $discapacidad;
  /** @var datetime */
  public $desgrava_afip;
  /** @var datetime */
  public $fecha_desde;
  /** @var datetime */
  public $fecha_hasta;
  /** @var StdClass */
  public $fam_discapacidad;

  const SI  = 1;
  const NO  = 0;
  static protected $OPCION_SINO = [
    self::SI  => ['id'  => self::SI, 'nombre' => 'SI', 'borrado' => 0],
    self::NO  => ['id'  => self::NO, 'nombre' => 'NO', 'borrado' => 0],
  ];

  const HIJO	= 1;
  const ESPOSO	= 2;
  const MADRE	= 3;
  const PADRE	= 4;
  const CONYUGE	= 5;
  const CONVIVIENTE	= 6;
  const OTROS	= 7;

  static protected $PARENTESCO  = [
    self::HIJO			=> ['id' => self::HIJO,   'nombre' => 'Hijo/a', 'borrado' => 0],
	self::ESPOSO		=> ['id' => self::ESPOSO, 'nombre' => 'Esposo/a', 'borrado' => 0],
	self::MADRE			=> ['id' => self::MADRE,  'nombre' => 'Madre', 'borrado' => 0],
	self::PADRE			=> ['id' => self::PADRE,  'nombre' => 'Padre', 'borrado' => 0],
	self::CONYUGE		=> ['id' => self::CONYUGE,'nombre' => 'Cónyuge', 'borrado' => 0],
	self::CONVIVIENTE	=> ['id' => self::CONVIVIENTE,   'nombre' => 'Conviviente', 'borrado' => 0],
	self::OTROS			=> ['id' => self::OTROS,  'nombre' => 'Otros', 'borrado' => 0],	
  ];

  const DESGRAVA_MITAD  = 1;
  const DESGRAVA_TOTAL  = 2;
  static protected $PORCENTAJE_DESGRAVA = [
    self::DESGRAVA_MITAD  => ['id'  => self::DESGRAVA_MITAD, 'nombre' => '50', 'borrado' => 0],
    self::DESGRAVA_TOTAL  => ['id'  => self::DESGRAVA_TOTAL, 'nombre' => '100', 'borrado' => 0],
  ];

  /**
   * Obtiene los valores de los array parametricos.
   * E.J.: GrupoFamiliar::getParam('PARENTESCO');
  */
  static public function getParam($attr=null){
    if($attr === null || empty(static::${$attr})){
      return [];
    }
    return static::${$attr};
  }

  static public function obtener($id=null){
    $obj  = new static;
    if($id===null){
      return static::arrayToObject();
    }
    $sql_params = [
      ':id' => $id,
    ];

    $campos = 'fam.' . implode(', fam.', [
      'id',
      'id_empleado',
      'parentesco',
      'nombre',
      'apellido',
      'fecha_nacimiento',
      'nacionalidad',
      'tipo_documento',
      'documento',
      'nivel_educativo',
      'reintegro_guarderia',
      'discapacidad',
      'desgrava_afip',
      'fecha_desde',
      'fecha_hasta',
      'borrado',
    ]);

    $campos = $campos . ', disfam.' . implode(', disfam.', [
      'id           		AS id_dis_familiar',
      'id_familiar      	AS id_familiar',
      'id_tipo_discapacidad AS id_tipo_discapacidad',
      'cud          		AS cud',
      'fecha_alta       	AS fecha_alta_dis',
      'fecha_vencimiento    AS fecha_ven_dis',
    ]);

    $sql  = <<<SQL
      SELECT {$campos}
      FROM grupo_familiar fam
      LEFT JOIN familiar_discapacidad AS disfam ON (disfam.id_familiar = fam.id AND disfam.borrado = 0)
      WHERE fam.id = :id
      AND fam.borrado = 0
SQL;
    $cnx  = new Conexiones();
    $res  = $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
    if(!empty($res)){
      return static::arrayToObject($res[0]);
    }
    return static::arrayToObject();
  }

  static public function listar($id=null) {
    $sql_params = [
      ':id' => $id,
    ];

    $campos = 'fam.' . implode(', fam.', [
      'id',
      'id_empleado',
      'parentesco',
      'nombre',
      'apellido',
      'fecha_nacimiento',
      'nacionalidad',
      'tipo_documento',
      'documento',
      'nivel_educativo',
      'reintegro_guarderia',
      'discapacidad',
      'desgrava_afip',
      'fecha_desde',
      'fecha_hasta',
      'borrado',
    ]);

    $campos = $campos . ', disfam.' . implode(', disfam.', [
      'id           		AS id_dis_familiar',
      'id_familiar      	AS id_familiar',
      'id_tipo_discapacidad AS id_tipo_discapacidad',
      'cud          		AS cud',
      'fecha_alta       	AS fecha_alta_dis',
      'fecha_vencimiento    AS fecha_ven_dis',
    ]);

    $sql  = <<<SQL
      SELECT {$campos}
      FROM grupo_familiar fam
      LEFT JOIN familiar_discapacidad AS disfam ON (disfam.id_familiar = fam.id AND disfam.borrado = 0)
      WHERE fam.id_empleado = :id
      AND fam.borrado = 0
      ORDER BY fam.id ASC
SQL;
    $cnx  = new Conexiones();
    $res  = $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);

    if(empty($res)) { return []; }
    foreach ($res as &$value) {
      
      $value  = static::arrayToObject($value);
    }
    return $res;
  }

	public function alta(){
	    if(!$this->validar()){
	      return false;
	    }
	    $campos = [
			'id_empleado',
			'parentesco',
			'nombre',
			'apellido',
			'fecha_nacimiento',
			'nacionalidad',
			'tipo_documento',
			'documento',
			'nivel_educativo',
			'desgrava_afip',
			'reintegro_guarderia',
			'discapacidad', 
	    ];
	    $sql_params = [
	    ];
	    foreach ($campos as $campo) {
	      $sql_params[':'.$campo] = $this->{$campo};
	    }
	    if($this->fecha_nacimiento instanceof \DateTime){
	      $sql_params[':fecha_nacimiento'] = $this->fecha_nacimiento->format('Y-m-d');
	    }
	    if($this->fecha_desde instanceof \DateTime){
	    	$campos[] = 'fecha_desde';
	      $sql_params[':fecha_desde'] = $this->fecha_desde->format('Y-m-d');
	    }
	    if($this->fecha_hasta instanceof \DateTime){
		  $campos[] = 'fecha_hasta';
	      $sql_params[':fecha_hasta'] = $this->fecha_hasta->format('Y-m-d');
	    }
	    $sql  = 'INSERT INTO grupo_familiar('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
	    $cnx  = new Conexiones();
	    $res  = $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);

	    if($res !== false){
	      $this->id = $res;
	      $datos = (array) $this;
	      $datos['modelo'] = 'GrupoFamiliar';
	      Logger::event('alta', $datos);
	    }

	    return $res;
	}

	public function baja(){
		if(empty($this->id)) {
			return false;
		}
		$sql	= <<<SQL
			UPDATE grupo_familiar SET borrado = 1 WHERE id = :id
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, [':id' => $this->id]);
		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= '	GrupoFamiliar';
			if (is_numeric($res) && $res > 0) {
				if(!empty($this->fam_discapacidad->id)){
					$this->baja_discapacidad();
				}
				$flag = true;
			} else {
				$datos['error_db'] = $cnx->errorInfo;
			}
			Logger::event('baja_familiar', $datos);
		}
		return $flag;
	}

 	public function modificacion(){
		if(!$this->validar() || empty($this->id)){
		  return false;
		}

		$campos = [
			'id_empleado',
			'parentesco',
			'nombre',
			'apellido',
			'nacionalidad',
			'tipo_documento',
			'documento',
			'nivel_educativo',
			'desgrava_afip',
			'reintegro_guarderia',
			'discapacidad',
		];
		$sql_params = [];
		foreach ($campos as $key => $campo) {
		  $sql_params[':'.$campo] = $this->{$campo};
		  unset($campos[$key]);
		  $campos[$campo] = $campo .' = :'.$campo;
		}

		if($this->fecha_nacimiento instanceof \DateTime){
			$campos['fecha_nacimiento'] = 'fecha_nacimiento=:fecha_nacimiento';	
	      $sql_params[':fecha_nacimiento'] = $this->fecha_nacimiento->format('Y-m-d');

	    }
	    if($this->fecha_desde instanceof \DateTime){
	    	$campos['fecha_desde'] = 'fecha_desde = :fecha_desde';	
	    	$sql_params[':fecha_desde'] = $this->fecha_desde->format('Y-m-d');
	    }
	    if($this->fecha_hasta instanceof \DateTime){
	    	$campos['fecha_hasta'] = 'fecha_hasta=:fecha_hasta';
	    	$sql_params[':fecha_hasta'] = $this->fecha_hasta->format('Y-m-d');
	    }
		$sql_params[':id'] = $this->id;
		$sql  = 'UPDATE grupo_familiar SET '.implode(',', $campos).' WHERE id = :id';
		$cnx  = new Conexiones();
	    $res  = $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
		  $this->id = $res;
		  $datos = (array) $this;
		  $datos['modelo'] = 'GrupoFamiliar';
		  Logger::event('modificacion', $datos);
		}
		return $res;
	}

	public function alta_discapacidad(){
		$res =true;
		$campos	= [
			'id_familiar',
			'id_tipo_discapacidad',
			'cud',
		];
		$sql_params	= [];

		//$this->fam_discapacidad->id_familiar = $this->id;
		foreach ($campos as $campo) {
			$sql_params[':'.$campo]	= $this->fam_discapacidad->{$campo};
		}
		if($this->fam_discapacidad->fecha_alta instanceof \DateTime){
			$campos[]	= 'fecha_alta';		
	    	$sql_params[':fecha_alta'] = $this->fam_discapacidad->fecha_alta->format('Y-m-d');
	    }
		if($this->fam_discapacidad->fecha_vencimiento instanceof \DateTime){
			$campos[] =	'fecha_vencimiento';
			$sql_params[':fecha_vencimiento'] = $this->fam_discapacidad->fecha_vencimiento->format('Y-m-d');
		}
		if($sql_params[':id_familiar'] && $sql_params[':id_tipo_discapacidad'] && $sql_params[':cud']) {
			$sql	= 'INSERT INTO familiar_discapacidad('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
			$cnx	= new Conexiones();
			$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);

			if($res !== false){
			  $this->fam_discapacidad->id = $res;
			  $datos = (array) $this;
			  $datos['modelo'] = 'GrupoFamiliar';
			  Logger::event('alta_discapacidad', $datos);
			}
		}
		return $res;
	}


	public function modificacion_discapacidad(){
		$res = true;
		if($this->fam_discapacidad->id) {
			$campos = [
				'id_tipo_discapacidad',
				'cud',
			];
			$sql_params = [];
			foreach ($campos as $key => $campo) {
			  $sql_params[':'.$campo] = $this->fam_discapacidad->{$campo};
			  unset($campos[$key]);
			  $campos[$campo] = $campo .' = :'.$campo;
			}
			if($this->fam_discapacidad->fecha_alta instanceof \DateTime){
				$campos['fecha_alta']	= 'fecha_alta=:fecha_alta';
		    	$sql_params[':fecha_alta'] = $this->fam_discapacidad->fecha_alta->format('Y-m-d');
		    }
			if($this->fam_discapacidad->fecha_vencimiento instanceof \DateTime){
				$campos['fecha_vencimiento'] =	'fecha_vencimiento=:fecha_vencimiento';
				$sql_params[':fecha_vencimiento'] = $this->fam_discapacidad->fecha_vencimiento->format('Y-m-d');
			}
			$sql_params[':id'] = $this->fam_discapacidad->id;
			$sql  = 'UPDATE familiar_discapacidad SET '.implode(',', $campos).' WHERE id = :id';
			$res  = (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
			if($res !== false){
			  $this->id = $res;
			  $datos = (array) $this;
			  $datos['modelo'] = 'GrupoFamiliar';
			  Logger::event('modificacion_discapacidad', $datos);
			}
		}	
		return $res;
	}

	public function baja_discapacidad(){
		if(empty($this->fam_discapacidad->id)) {
			return false;
		}
		$sql	= <<<SQL
			UPDATE familiar_discapacidad SET borrado = 1 WHERE id = :id
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, [':id' => $this->fam_discapacidad->id]);
		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= '	GrupoFamiliar';
			if (is_numeric($res) && $res > 0) {
				$flag = true;
			} else {
				$datos['error_db'] = $cnx->errorInfo;
			}
			Logger::event('baja_discapacidad_familiar', $datos);
		}
		return $flag;
	}

	public function validar() {
	    $campos = (array)$this;
	    $campos['hoy'] = new \DateTime();
	    $reglas   = [
			'id_empleado'     	=> ['required', 'integer'],
			'parentesco'      	=> ['required', 'integer'],
			'nombre'        	=> ['required', 'texto'],
			'apellido'        	=> ['required', 'texto'],
			'fecha_nacimiento'  => ['required', 'fecha', 'antesDe(:hoy)'],
			'nacionalidad'      => ['texto'],
			'tipo_documento'    => ['required', 'integer'],
			'documento'       	=> ['required', 'numeric'],
			'nivel_educativo'   => ['integer'],
			'desgrava_afip'     => ['integer'],
			'fecha_desde'     	=> ['required', 'fecha'],
			'fecha_hasta'    	=> ['fecha'],
			'reintegro_guarderia' => ['integer'],
			'discapacidad'      => ['integer'],
	    ];
	    $nombres  = [
			'parentesco'    	=> 'Parentesco',
			'nombre'      		=> 'Nombre',
			'apellido'      	=> 'Apellido',
			'fecha_nacimiento'	=> 'Fecha de Nacimiento',
			'nacionalidad'    	=> 'Nacionalidad',
			'tipo_documento'  	=> 'Tipo de Documento',
			'documento'     	=> 'Documento',
			'nivel_educativo' 	=> 'Nivel Educativo',
			'desgrava_afip'   	=> '% que Desgrava AFIP',
			'fecha:_desde'    	=> 'Fecha Desde',
			'fecha_hasta'   	=> 'Fecha Hasta',
			'reintegro_guarderia'	 => 'Reintegro Guardería',
			'discapacidad'    	=> 'Discapacidad',
	    ];

	    if($this->discapacidad){
	    	$campos['id_tipo_discapacidad']     = $this->fam_discapacidad->id_tipo_discapacidad;  
			$campos['cud']  					= $this->fam_discapacidad->cud;  
			$campos['fecha_alta']   			= $this->fam_discapacidad->fecha_alta; 
			$campos['fecha_vencimiento']		= $this->fam_discapacidad->fecha_vencimiento;  
			
			$nombres['id_tipo_discapacidad']	= 'Tipo de Discapacidad';
			$nombres['cud']						= 'CUD';
			$nombres['fecha_alta']				= 'Fecha Alta Discapacidad';
			$nombres['fecha_vencimiento']		= 'Fecha Vencimiento Discapacidad';
			
			$reglas['id_tipo_discapacidad']  = ['required', 'integer'];
			$reglas['cud']      			 = ['required', 'alpha_numeric'];
			$reglas['fecha_alta']        	 = ['required', 'fecha', 'menor_que(:fecha_vencimiento)' =>
				function($input,$fecha_vencimiento){
					if(!($input instanceof \DateTime && !empty($input)) && ($fecha_vencimiento instanceof \DateTime && !empty($fecha_vencimiento))){
						return false;
					}
					if($input >= $fecha_vencimiento){
						return false;
					}
					return true;
				}];
			$reglas['fecha_vencimiento']     = ['required', 'fecha','mayor_que(:fecha_alta)' =>
				function($input,$fecha_alta){
					if(!($input instanceof \DateTime && !empty($input)) && ($fecha_alta instanceof \DateTime && !empty($fecha_alta))){
						return false;
					}
					if($input <= $fecha_alta){
						return false;
					}
					return true;
				}];
		   
	    }

	    $validator  = Validator::validate($campos, $reglas, $nombres);
	    $validator->customErrors([
            'menor_que'	=> 'La <strong>Fecha de Alta</strong> debe ser menor que la <strong>Fecha de Vencimiento</strong>.',
            'mayor_que' => 'La <strong>Fecha de Vencimiento</strong> debe ser mayor que la <strong>Fecha de Alta</strong>.',
        ]);
	    if ($validator->isSuccess()) {
	      return true;
	    }
	    $this->errores = $validator->getErrors();
	    return false;
	}

  static public function arrayToObject($res = []) {
    $campos = [
		'id'		      	 => 'int',
		'id_empleado'		 => 'int',
		'parentesco'		 => 'int',
		'nombre'		     => 'string',
		'apellido'		     => 'string',
		'fecha_nacimiento'	 => 'date',
		'nacionalidad'		 => 'string',
		'tipo_documento'	 => 'int',
		'documento'		     => 'string',
		'nivel_educativo'	 => 'int',
		'reintegro_guarderia'=> 'int',
		'discapacidad'		 => 'int',
		'desgrava_afip'		 => 'int',
		'fecha_desde'		 => 'date',
		'fecha_hasta'		 => 'date',
    ];
    $obj = new self();
    foreach ($campos as $campo => $type) {
		switch ($type) {
		case 'int':
		  $obj->{$campo}  = isset($res[$campo]) ? (int)$res[$campo] : null;
		  break;
		case 'json':
		  $obj->{$campo}  = isset($res[$campo]) ? json_decode($res[$campo], true) : null;
		  break;
		case 'datetime':
		  $obj->{$campo}  = isset($res[$campo]) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res[$campo]) : null;
		  break;
		case 'date':
		  $obj->{$campo}  = isset($res[$campo]) ? \DateTime::createFromFormat('Y-m-d', $res[$campo]) : null;
		  break;
		default:
		  $obj->{$campo}  = isset($res[$campo]) ? $res[$campo] : null;
		  break;
		}
    }

    $obj->fam_discapacidad              	= new \StdClass();
    $obj->fam_discapacidad->id            	= \FMT\Helper\Arr::get($res,'id_dis_familiar');
    $obj->fam_discapacidad->id_familiar     = \FMT\Helper\Arr::get($res,'id_familiar');
    $obj->fam_discapacidad->id_tipo_discapacidad    = \FMT\Helper\Arr::get($res,'id_tipo_discapacidad');
    $obj->fam_discapacidad->cud           	= \FMT\Helper\Arr::get($res,'cud');
    $obj->fam_discapacidad->fecha_alta  	= isset($res['fecha_alta_dis']) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res['fecha_alta_dis'] . ' 0:00:00') : null;
    $obj->fam_discapacidad->fecha_vencimiento   = isset($res['fecha_ven_dis']) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res['fecha_ven_dis'] . ' 0:00:00') : null;
    return $obj;
  }
}