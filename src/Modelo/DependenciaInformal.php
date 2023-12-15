<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

/**
 * Dependencias Informales de una Dependencia Padre
*/
class DependenciaInformal extends Modelo {
/** @var int */
    public $id;
/** @var int */
    public $id_dependencia;
/** @var string */
    public $nombre;
/** @var DateTime */
    public $fecha_desde;
/** @var DateTime */
    public $fecha_hasta;

    static public function obtener($id=null){
        if($id===null){
            return static::arrayToObject();
        }
        $cnx    = new Conexiones();
        $resp   = $cnx->consulta(Conexiones::SELECT, 'SELECT * FROM dependencias_informales WHERE id = :id LIMIT 1',[':id' => $id]);
        if(empty($resp)){
            return static::arrayToObject();
        }
        return static::arrayToObject($resp[0]);
    }
    static public function listar($id_dependencia=null){
        if(empty($id_dependencia)){
            $where      = '';
            $sql_params = [];
		} else {
            $where      = ' WHERE id_dependencia = :id_dependencia ';
            $sql_params = [
                ':id_dependencia'   => $id_dependencia,
            ];
        }
		$campos	= implode(',', [
			'id',
			'id_dependencia',
			'nombre',
			'fecha_desde',
			'fecha_hasta'
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM dependencias_informales
			{$where}
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		$aux	= [];
		if(empty($res[0])) {
			return [];
		}
		foreach ($res as  $value){
			$aux[$value['id']]	= static::arrayToObject($value);
		}
		return $aux;
    }
    public function validar(){
        $campos     = (array)$this;
        $reglas     = [
            'id_dependencia'=> ['required', 'integer'],
            'nombre'		=> ['required', 'texto'],
            'fecha_desde'	=> ['required', 'fecha'],
            'fecha_hasta'	=> ['fecha'],            
        ];
        $nombres    = [
            'nombre'		=> 'Nombre Dependencia Informal',
            'id_dependencia'=> 'Dependencia Padre',
            'fecha_desde'	=> 'Fecha Desde',
            'fecha_hasta'	=> 'Fecha Hasta',
        ];

        $validator  = Validator::validate($campos, $reglas, $nombres);

        if ($validator->isSuccess()) {
            return true;
        }

        $this->errores = $validator->getErrors();
        return false;
    }

    public function alta(){
        $campos = [
			'id_dependencia',
			'nombre',
			'fecha_desde',
			'fecha_hasta',
		];
		$sql_params	= [
		];
		foreach ($campos as $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
		}
		if($this->fecha_desde instanceof \DateTime){
			$sql_params[':fecha_desde']	= $this->fecha_desde->format('Y-m-d');
		}
		if($this->fecha_hasta instanceof \DateTime){
			$sql_params[':fecha_hasta']	= $this->fecha_hasta->format('Y-m-d');
		}

		$sql	= 'INSERT INTO dependencias_informales('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
            $this->id   = $res;
			$datos = (array) $this;
			$datos['modelo'] = 'Dependencia Informal';
			Logger::event('alta_informales', $datos);
		}
		return $res;
    }
    public function modificacion(){
        $campos	= [
			'nombre',
			'id_dependencia',
			'fecha_desde',
			'fecha_hasta',
		];
		$sql_params = [
			':id'	=> $this->id,
		];
		foreach ($campos as $key => $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
			unset($campos[$key]);
			$campos[$campo]	= $campo .' = :'.$campo;
		}

		if($this->fecha_desde instanceof \DateTime){
			$sql_params[':fecha_desde']	= $this->fecha_desde->format('Y-m-d');
		}
		if($this->fecha_hasta instanceof \DateTime){
			$sql_params[':fecha_hasta']	= $this->fecha_hasta->format('Y-m-d');
		}

		$sql	= 'UPDATE dependencias_informales SET '.implode(',', $campos).' WHERE id = :id';
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Dependencia Informal';
			Logger::event('modificacion_informales', $datos);
		}
		return $res;
    }
    public function baja(){
        if(empty($this->fecha_hasta)){
            return false;
        }
        $sql_params = [
            ':id'   => $this->id,
            ':fecha_hasta' => ($this->fecha_hasta instanceof \DateTime) ? $this->fecha_hasta->format('Y-m-d') : $this->fecha_hasta
        ];
        $cnx    = new Conexiones;
		$sql = 'UPDATE dependencias_informales SET fecha_hasta = :fecha_hasta WHERE id = :id';
		$res    = $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if (!empty($res) ) {
            $this->id   = $res;
			$datos = (array)$this;
			$datos['modelo'] = 'Dependencia Informal';
            Logger::event('baja', $datos);
            return true;
		}
        $datos['error_db']  = $cnx->errorInfo;
        Logger::event("error_baja",$datos);
	
		return false;
    }

    static public function arrayToObject($resp=[]){
        $campos = [
            'id'             => 'int',
            'id_dependencia' => 'int',
            'nombre'         => 'string',
            'fecha_desde'    => 'date',
            'fecha_hasta'    => 'date',
        ];
        return parent::arrayToObject($resp, $campos);
    }
}