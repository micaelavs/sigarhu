<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class ResponsableContrato extends Modelo {
/** @var int */
	public $id_dependencia;
/** @var array */
	protected $contratante;
/** @var array */
	protected $firmante;
	
	
	const	FIRMANTE		= 1,
			CONTRATANTE		= 2;

	static protected $TIPO_CONTRATANTE	= [
		self::FIRMANTE		=> ['id'	=> self::FIRMANTE, 'nombre'	=> 'Firmante', 'borrado' => '0'],
		self::CONTRATANTE	=> ['id'	=> self::CONTRATANTE, 'nombre'	=> 'Contratante', 'borrado' => '0'],

	];
	
	static public function getParam($attr=null){
		if($attr === null || empty(static::${$attr})){
			return [];
		}
		return static::${$attr};
	}


	static public function obtener($dependencia=null){
		$obj	= new static;
		if($dependencia===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id_dependencia'	=> $dependencia
		];
		$campos	= implode(',', [
			'id',
			'id_empleado',
			'id_dependencia',
			'id_tipo',
			'borrado',
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM responsables_contrato
			WHERE id_dependencia = :id_dependencia  and borrado = 0
SQL;

		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res);
		}
		return static::arrayToObject();
	}


	public function alta(){

	}
	
	
	public function alta_contratante(){
		$sql_params	= [
			':id_dependencia'	=> $this->id_dependencia,
			':id_empleado'	=> $this->contratante,
			':tipo'	=> static::CONTRATANTE
		];		
		$res = $this->alta_responsable($sql_params);
		
		return $res;
	}
	
	public function alta_firmante($firmante){
		$sql_params	= [
			':id_dependencia'	=> $this->id_dependencia,
			':id_empleado'	=> $firmante,
			':tipo'	=> static::FIRMANTE
		];
		$res = $this->alta_responsable($sql_params);
		
		return $res;
	}
			
	public function alta_responsable($sql_params){

		$sql	= 'INSERT INTO responsables_contrato (id_dependencia, id_empleado, id_tipo) VALUES (:id_dependencia, :id_empleado, :tipo)';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'ResponsableContrato';
			Logger::event('alta_responsable', $datos);
		}
		return $res;
	}
	
	
	public function baja_contratante(){
		$sql_params	= [
			':id_empleado'	=> $this->contratante,
			':tipo'	=> static::CONTRATANTE
		];
		$res = $this->baja_responsable($sql_params);
		
		return $res;
	}
	
	public function baja_firmante($firmante){
		$sql_params	= [
			':id_empleado'	=> $firmante,
			':tipo'	=> static::FIRMANTE
		];
		$res = $this->baja_responsable($sql_params);
		
		return  $res;
	}
	
	
	public function baja_responsable($sql_params){
		$sql_params[':id_dependencia'] = $this->id_dependencia;	
		$sql	= <<<SQL
			UPDATE responsables_contrato SET borrado = 1 WHERE id_empleado = :id_empleado AND id_tipo = :tipo AND id_dependencia = :id_dependencia 
SQL;
        $cnx    = new Conexiones();
        $res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);
		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'ResponsableContrato';
			if (is_numeric($res) && $res > 0) {
				$flag = true;
			} else {
				$datos['error_db'] = $cnx->errorInfo;
			}
			Logger::event('baja_responsable', $datos);
		}
		return $flag;
	}
	


	public function baja(){

	}

	public function modificacion(){

	}

	public function validar() {
		$campos = (array)$this;
		$campos = [
			'id_dependencia'  => $this->id_dependencia,
			'contratante'  => static::getContratante(),
			'firmante'	   => static::getFirmante()
		];

		$reglas		= [
			'id_dependencia'	=> ['required', 'integer'],
			'contratante'		=> ['required', 'integer'],
			'firmante'			=> ['array_no_vacio' =>function($input){
				$aux = false;
				if(is_array($input)){
					$aux = (!empty($input)) ? true : false ; 
				}
				return $aux;
			}],
		];
		$nombres	= [
			'dependencia'	=> 'Dependencia',
			'contratante'	=> 'Contratante',
			'firmante'		=> 'Firmantes',
		];
		$validator	= Validator::validate($campos, $reglas, $nombres);

		$validator->customErrors([                   
            'array_no_vacio'	 => ' Debe elegirse alguna/s opcion/s de Firmantes'
        ]);	
	
		if ($validator->isSuccess()) {
			return true;
		}
		$this->errores = $validator->getErrors();
	
		return false;
	}

	static public function arrayToObject($res = []) {
		$obj = new self();

		foreach ($res as $value) {
			if($value['id_tipo'] == static::CONTRATANTE){
				$obj->id_dependencia = $value['id_dependencia'];
				$obj->contratante[] = $value['id_empleado'];
			}else{
				$obj->firmante[] = $value['id_empleado'];
			}
		}
		$obj->contratante = (is_null($obj->contratante)) ? [] : $obj->contratante;
		$obj->firmante = (is_null($obj->firmante)) ? [] : $obj->firmante;
		sort($obj->contratante);
		sort($obj->firmante);
		return $obj;
	}
	
	public function getFirmante(){
		
		return $this->firmante;
	}
		
	public function getContratante(){
		
		return $this->contratante;
	}
	
	 public function setFirmante($firmante)
    {
    	if(is_array($firmante))
    		sort($firmante);

        $this->firmante = $firmante;
    }

     public function setContratante($contratante)
    {
        $this->contratante = $contratante;
    }
	
}