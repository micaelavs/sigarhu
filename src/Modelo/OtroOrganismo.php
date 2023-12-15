<?php
namespace App\Modelo;
use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class OtroOrganismo extends Modelo {

    /** @var int */
    public $id;
/** @var int */
	public $borrado;
/**@var String**/
	Public $nombre;
/**@var int**/
	Public $tipo;
/**@var int**/
	Public $jurisdiccion;

	const PUBLICO	= 1;
	const PRIVADO	= 2;
	
	const PROVINCIAL	= 1;
	const NACIONAL		= 2;
	const MUNICIPAL		= 3;
	const CABA			= 4;


	static public $TIPO_ORGANISMO 	= [
		self::PUBLICO	=> ['id'	=> self::PUBLICO, 'nombre' => 'Público', 'borrado' => '0'],
		self::PRIVADO	=> ['id'	=> self::PRIVADO, 'nombre' => 'Privado', 'borrado' => '0'],
	];
	
	static public $JURISDICCION 	= [
		self::PROVINCIAL	=> ['id'	=> self::PROVINCIAL, 'nombre' => 'Provincial', 'borrado' => '0'],
		self::NACIONAL		=> ['id'	=> self::NACIONAL, 'nombre' => 'Nacional', 'borrado' => '0'],
		self::MUNICIPAL		=> ['id'	=> self::MUNICIPAL, 'nombre' => 'Municipal', 'borrado' => '0'],
		self::CABA			=> ['id'	=> self::CABA, 'nombre' => 'CABA', 'borrado' => '0'],
	];
	
	static public function getParam($attr=null){
		if($attr === null || empty(static::${$attr})){
			return [];
		}
		return static::${$attr};
	}
	
 	public static function obtener($id = null){
    	$obj	= new static;
		if($id===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id'	=> $id,
		];
		$campos	= implode(',', [
			'id','nombre','tipo', 'jurisdiccion'
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM otros_organismos 
			WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
  }

	public static function listar(){
		$conexion = new Conexiones();
		$aux= '';
		$auxb= '';
		foreach (static::$TIPO_ORGANISMO as $value) {
			$aux .= ($aux) ? " union all select {$value['id']},'{$value['nombre']}'" 
						  : " select {$value['id']} id,'{$value['nombre']}' nombre_tipo";
		}
		
		foreach (static::$JURISDICCION as $value) {
			$auxb .= ($auxb) ? " union all select {$value['id']},'{$value['nombre']}'" 
						  : " select {$value['id']} id,'{$value['nombre']}' nombre_juris";
		}

		$sql	= <<<SQL
			SELECT o.id, o.nombre, o.tipo, o.jurisdiccion, t.nombre_tipo AS nombre_tipo, j.nombre_juris AS nombre_juris FROM otros_organismos o
			LEFT JOIN ($aux) t ON o.tipo = t.id 
			LEFT JOIN ($auxb) j ON o.jurisdiccion = j.id 
			WHERE o.borrado=0
			ORDER BY o.nombre ASC;
SQL;
		$resultado = $conexion->consulta(Conexiones::SELECT,$sql);
        if(empty($resultado)){
            return [];
        }
		return $resultado;
	}

	public function alta(){
		$campos	= [
            'nombre','tipo','jurisdiccion'
		];
		$sql_params	= [
		];
		foreach ($campos as $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
			if($campo == 'jurisdiccion' && empty($this->{$campo}))
				$sql_params[':' . $campo]	= 0;

		}

		$sql	= 'INSERT INTO otros_organismos('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'otros_organismos';
			Logger::event('alta', $datos);
		}
		return $res;
	}

	public function modificacion(){
		$conexion = new Conexiones;;
		$campos	= [
            'nombre','tipo', 'jurisdiccion'
		];
		$sql_params	= [
		':id'	=> $this->id,
		];
		foreach ($campos as $key => $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
			unset($campos[$key]);
			$campos[$campo]	= $campo .' = :'.$campo;
		}
            
		$sql	= 'UPDATE otros_organismos SET '.implode(',', $campos).' WHERE id = :id';
		$res	= $conexion->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'otros_organismos';
			Logger::event('modificacion', $datos);
		} else {
			$this->errores = $conexion->errorInfo;
			$datos['error_db'] = $this->errores;
			Logger::event("error_baja",$datos);
		}
		return $res;
	}

	public function baja(){
		$conexion = new Conexiones;
		$params = [':id' => $this->id];
		$sql = <<<SQL
		UPDATE otros_organismos SET  borrado = 1 WHERE id = :id
SQL;
		$res = $conexion->consulta(Conexiones::SELECT, $sql, $params);
		if ($res !== false) {
			$res	= true;
			$datos = (array) $this;
			$datos['modelo'] = 'otros_organismos';
			Logger::event('baja', $datos);
		} else {
			$this->errores = $conexion->errorInfo;
			$datos['error_db'] = $this->errores;
			Logger::event("error_baja",$datos);
		}
		return $res;
	}

	public function validar(){
		$rules = [
            'nombre' =>  ['required', "unico(otros_organismos, nombre,:id)"],
			'tipo' =>  ['required', 'integer'],
		];
		$nombres	= [
			'nombre' => 'Organismo',
			'tipo' 	=> 'Tipo'
		];
		$validator = Validator::validate((array)$this, $rules, $nombres);
		if ($validator->isSuccess()) {
			return true;
		}
		else {
			$this->errores = $validator->getErrors();
			return false;
		}
	}
	
	static public function getOtrosOrganismos(){
		$aux= '';
		$auxb= '';
		foreach (static::$TIPO_ORGANISMO as $value) {
			$aux .= ($aux) ? " union all select {$value['id']},'{$value['nombre']}'" 
						  : " select {$value['id']} id,'{$value['nombre']}' nombre_tipo";
		}
		foreach (static::$JURISDICCION as $value) {
			$auxb .= ($auxb) ? " union all select {$value['id']},'{$value['nombre']}'" 
						  : " select {$value['id']} id,'{$value['nombre']}' nombre_juris";
		}

		$sql	= <<<SQL
			SELECT o.id, o.nombre, o.tipo, t.nombre_tipo AS nombre_tipo, j.nombre_juris AS nombre_juris, o.borrado FROM otros_organismos o
			LEFT JOIN ($aux) t ON o.tipo = t.id 
			LEFT JOIN ($auxb) j ON o.jurisdiccion = j.id 
			WHERE o.borrado=0
			ORDER BY o.nombre ASC;
SQL;
		$resp	= (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql);
		$val=[];
		foreach ($resp as $value) {
			if(is_array($value)){
				$value['id'] = (int) $value['id'];
				$val[$value['id']] = $value;
			}
	    }
	    return $val;
	}
	
	static public function getJurisdicciones(){
		$aux= '';
		$val= [];
		foreach (static::$JURISDICCION as $value) {
			$aux .= ($aux) ? " union all select {$value['id']},'{$value['nombre']}'" 
						  : " select {$value['id']} id,'{$value['nombre']}' nombre_juris";
		}

		$sql	= <<<SQL
			SELECT o.id, o.nombre, o.tipo, o.jurisdiccion, j.nombre_juris AS nombre_juris, o.borrado FROM otros_organismos o
			LEFT JOIN ($aux) j ON o.jurisdiccion = j.id 
			WHERE o.borrado=0
			ORDER BY o.nombre ASC;
SQL;
		$resp	= (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql);
		foreach ($resp as $value) {
			$value['id'] = (int) $value['id'] ;
	    	$val[$value['id']] = $value;
	    }
	    return $val;
	}
	
  
	static public function arrayToObject($res = []) {
		$campos	= [
			'id' 			=>  'int',
			'nombre' 		=>  'string',
			'tipo' 			=>  'int',
			'jurisdiccion'  =>  'int',
		];
		$obj = new self();
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
                    $obj->{$campo}	= isset($res[$campo]) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res[$campo] . ' 0:00:00') : null;
                    break;
				default:
					$obj->{$campo}	= isset($res[$campo]) ? $res[$campo] : null;
					break;
			}
		}

		return $obj;
	}
}

