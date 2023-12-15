<?php
namespace App\Modelo;

//use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class Designacion_transitoria extends \App\Modelo\Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_empleado;
/** @var Date */
	public $fecha_desde;
/** @var Date */
	public $fecha_hasta;
	/** @var int */
	public $tipo;
/** @var string */
	public $archivo; 
/** @var int */
	public $borrado;

	const TRANSITORIA =  1;
	const PRORROGA = 2;
	const NINGUNA = 3;
	
	static public $TIPO_DESIGNACION 	= [
		self::TRANSITORIA	=> ['id'	=> self::TRANSITORIA, 'nombre' => 'Transitoria', 'borrado' => '0'],
		self::PRORROGA		=> ['id'	=> self::PRORROGA, 'nombre' => 'Prorroga', 'borrado' => '0'],
		self::NINGUNA		=> ['id'	=> self::NINGUNA, 'nombre' => 'Ninguna', 'borrado' => '0'],
	];
	
	const DESIGNACION_TRANSITORIA_CARGO_PLANTA_PERMANENTE_FUNCION_EJECUTIVA =  3;
	const PLANTA_PERMANENTE_MTR_DESIGNACION_TRANSITORIA = 4;
	const PLANTA_PERMANENTE_DESIGNACION_TRANSITORIA = 11;
	const PLANTA_PERMANENTE_MTR_DESIGNACION_TRANSITORIA_FUNCION_EJECUTIVA = 15;
	const DESIGNACION_TRANSITORIA_SIN_FUNCION_EJECUTIVA = 17;
	
	static public $SR_DESIGNACION_TRANSITORIA 	= [
		self::DESIGNACION_TRANSITORIA_CARGO_PLANTA_PERMANENTE_FUNCION_EJECUTIVA	=> [
			'id'	=> self::DESIGNACION_TRANSITORIA_CARGO_PLANTA_PERMANENTE_FUNCION_EJECUTIVA, 
			'nombre' => 'Designacion Transitoria en Cargo de Planta Permanente con Funcion Ejecutiva', 
			'borrado' => '0'
		],
		self::PLANTA_PERMANENTE_MTR_DESIGNACION_TRANSITORIA		
		=> ['id'	=> self::PLANTA_PERMANENTE_MTR_DESIGNACION_TRANSITORIA, 'nombre' => 'Planta Permanente MTR con Designacion Transitoria', 'borrado' => '0'],
		self::PLANTA_PERMANENTE_DESIGNACION_TRANSITORIA		
		=> ['id'	=> self::PLANTA_PERMANENTE_DESIGNACION_TRANSITORIA, 'nombre' => 'Planta Permanente con Designación Transitoria', 'borrado' => '0'],
		self::PLANTA_PERMANENTE_MTR_DESIGNACION_TRANSITORIA_FUNCION_EJECUTIVA		
		=> ['id'	=> self::PLANTA_PERMANENTE_MTR_DESIGNACION_TRANSITORIA_FUNCION_EJECUTIVA, 'nombre' => 'Planta Permanente MTR con Designacion Transitoria con Funcion Ejecutiva', 'borrado' => '0'],
		self::DESIGNACION_TRANSITORIA_SIN_FUNCION_EJECUTIVA		
		=> ['id'	=> self::DESIGNACION_TRANSITORIA_SIN_FUNCION_EJECUTIVA, 'nombre' => 'Designación Transitoria sin Función Ejecutiva', 'borrado' => '0'],

	];

	static public function getParam($attr=null){
		if($attr === null || empty(static::${$attr})){
			return [];
		}
		return static::${$attr};
	}

	static public function obtener($id_empleado=null){
		$obj	= new static;
		if($id_empleado===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id_empleado'	=> $id_empleado,
		];
		$sql	= <<<SQL
		SELECT * FROM designacion_transitoria 
 			WHERE id_empleado = :id_empleado AND tipo != 3 AND borrado = 0
 			ORDER BY id DESC
 			LIMIT 1
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}
	
	static public function obtener_prorroga($id=null){
		$obj	= new static;
		if($id===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id'	=> $id,
		];
		
		$sql	= <<<SQL
		SELECT *
			FROM designacion_transitoria
			WHERE id = :id and borrado = 0
		
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}


	static public function listar($array=false) {
		$sql	= <<<SQL
			SELECT * FROM designacion_transitoria 
 			WHERE  borrado = 0
 			ORDER BY id DESC
SQL;
		$resp	= (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql);
		if(empty($resp)) { return []; }
		$aux	= [];
		foreach ($resp as $key => &$value) {
			if($array){
				$aux[$value['id']]	= $value;
				unset($resp[$key]);
			} else {
				$resp[$key]	= static::arrayToObject($value);
			}
		}
		return !empty($aux) ? $aux : $resp;
	}

	public function alta(){
		$this->upload_archivo();
		$campos	= [
			'id_empleado',
			'fecha_desde',
			'fecha_hasta',
			'tipo',
			'archivo',
		];
		$sql_params	= [
			':id_empleado' => $this->id_empleado,
			':fecha_desde' => $this->fecha_desde,
			':fecha_hasta' => $this->fecha_hasta,
			':tipo'=>$this->tipo,
			':archivo' => $this->archivo,

		];

		if(!($this->fecha_desde instanceof \DateTime)){
			return false;
		}
		$this->fecha_hasta = \DateTime::createFromFormat('Y-m-d H:i:s.u', \FMT\Informacion_fecha::dias_habiles_hasta_fecha($this->fecha_desde, 180).' 0:00:00.000000');

		if($this->fecha_desde instanceof \DateTime){
			$sql_params[':fecha_desde']	= $this->fecha_desde->format('Y-m-d');
		}
		if($this->fecha_hasta instanceof \DateTime){
			$sql_params[':fecha_hasta']	= $this->fecha_hasta->format('Y-m-d');
		}

		$sql	= 'INSERT INTO designacion_transitoria('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$this->id	= $res;
			$datos = (array) $this;
			$datos['modelo'] = 'Designacion_transitoria';
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
				$directorio = BASE_PATH.'/uploads/designacion_transitoria';

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
	
	public function listar_designaciones() {
		$sql_params	= [
			':id_empleado' => $this->id_empleado,
		];
		$lista = [];
		$sql	= <<<SQL
		SELECT  dt.id,
			    dt.id_empleado,
			    dt.fecha_desde,
			    dt.fecha_hasta,
			    dt.tipo,
			   	dt.archivo,
                csr.nombre as situacion_revista
			FROM designacion_transitoria dt
            INNER JOIN empleado_escalafon ee ON ee.id_empleado = dt.id_empleado
            INNER JOIN convenio_situacion_revista csr ON csr.id = ee.id_situacion_revista
			WHERE dt.id_empleado = :id_empleado AND dt.borrado = 0 
			order by dt.fecha_desde DESC, dt.tipo DESC

SQL;
		$resp	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if (!empty($resp) && is_array($resp)) {
			foreach ($resp as $key => &$value) {
						$value['tipo'] =  self::$TIPO_DESIGNACION[$value['tipo']]['nombre'];	
						$value['fecha_desde'] = \DateTime::createFromFormat('Y-m-d',$value['fecha_desde'])->format('d/m/Y');
						$value['fecha_hasta'] = \DateTime::createFromFormat('Y-m-d',$value['fecha_hasta'])->format('d/m/Y');
				}
		}else{
			$resp = [];
		}
		return $resp;

	}
	
	static public function obtener_archivo($id_designacion){

		if($id_designacion){
	    $Conexiones = new Conexiones();
	    $resultado = $Conexiones->consulta(Conexiones::SELECT,
<<<SQL

			SELECT  archivo
			FROM designacion_transitoria
			WHERE id = :id_designacion
			LIMIT 1
SQL
,[':id_designacion'=>$id_designacion]);
	
	    return $resultado[0]['archivo'];
		}

	}


	public function baja(){
		if(empty($this->id)) {
			return false;
		}
		$sql	= <<<SQL
			UPDATE designacion_transitoria SET tipo = 3 WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, [':id' => $this->id]);
		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'Designacion_transitoria';
			if (is_numeric($res) && $res > 0) {
				$flag = true;
			} else {
				$datos['error_db'] = $mbd->errorInfo;
			}
			Logger::event('baja', $datos);
		}
		return $flag;
	}



	 public function modificacion(){

		$this->upload_archivo();
		$campos	= [
			'id_empleado'   => 'id_empleado = :id_empleado',
			'fecha_desde' 	=> 'fecha_desde = :fecha_desde',
			'fecha_hasta' 	=> 'fecha_hasta = :fecha_hasta',
			'tipo' 			=> 'tipo = :tipo' ,
			'archivo' 		=> 'archivo = :archivo' ,
		];

		$sql_params	= [
			
			':id_empleado' => $this->id_empleado,
			':fecha_desde' => $this->fecha_desde,
			':fecha_hasta' => $this->fecha_hasta,
			':tipo'		   => $this->tipo,
			':archivo'     => $this->archivo,
			':id' => $this->id,
		];

		if($this->fecha_desde instanceof \DateTime){
			$sql_params[':fecha_desde']	= $this->fecha_desde->format('Y-m-d');
		}
		
		if($this->fecha_hasta instanceof \DateTime){
			$sql_params[':fecha_hasta']	= $this->fecha_hasta->format('Y-m-d');
		}

		$sql	= 'UPDATE designacion_transitoria SET  '.implode(',', $campos).' WHERE id = :id';
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);

		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Designacion_transitoria';
			Logger::event('modificacion', $datos);
		}
		return $res;
	 }

	public function validar() {
		$array_designacion_transitoria = (array)$this;
		$array_designacion_transitoria['archivo_comprobante'] = is_array($this->archivo) ? $this->archivo["name"] : $this->archivo;
		$reglas		= [
			'fecha_desde'  		 => ['fecha'],
			'tipo'  			 => ['integer'],
		];
		if(!empty($this->id)){
			$reglas	+= [
				'archivo_comprobante'  	 		 => ['extension' => function($input){
		              $ext = pathinfo($input, PATHINFO_EXTENSION);
		              if (!empty($input)){
		                  return  ($ext == 'pdf');
		              }
		              return true;
		          }],
			];
		}


		$nombres	= [
			'fecha_desde'				=> 'Fecha Desde',
			'tipo'						=> 'Tipo de designación transitoria',
			'archivo_comprobante'		=> 'Archivo Comprobante'

		];
		$validator	= Validator::validate($array_designacion_transitoria, $reglas, $nombres);
		$validator->customErrors([
            'extension'         => 'Extensión inválida. Se permite sólo archivos pdf'
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
	
	
	public static function informe_designacion_transitoria($params=array(), $count = false) {
		$sql_params	= [
			':designacion_tipo1' => static::DESIGNACION_TRANSITORIA_CARGO_PLANTA_PERMANENTE_FUNCION_EJECUTIVA, 
			':designacion_tipo2' => static::PLANTA_PERMANENTE_MTR_DESIGNACION_TRANSITORIA, 
			':designacion_tipo3' => static::PLANTA_PERMANENTE_DESIGNACION_TRANSITORIA, 
			':designacion_tipo4' => static::PLANTA_PERMANENTE_MTR_DESIGNACION_TRANSITORIA_FUNCION_EJECUTIVA,
			':designacion_tipo5' => static::DESIGNACION_TRANSITORIA_SIN_FUNCION_EJECUTIVA,
			':transitoria' 		 => static::TRANSITORIA, 
			':prorroga' 		 => static::PRORROGA
		];
		$where = [];
		$default_params	= [
			'order'		=> [
				[
					'campo'	=> 'fecha_desde',
					'dir'	=> 'asc',
				],
			],
			'search'	=> '',
			'count'		=> false
		];
		
		$aux = '';
		foreach (self::$TIPO_DESIGNACION as $value) {
			$aux .= ($aux) ? " union all select {$value['id']},'{$value['nombre']}'" 
						  : " select {$value['id']} id,'{$value['nombre']}' nombre_tipo";
		}
		$campos	= 'id, nombre, apellido, id_designacion, cuit , fecha_desde, fecha_hasta, tipo, nombre_tipo, estado' ;
		
	 	$consulta = "SELECT * FROM
	 				(
                    SELECT id, nombre, apellido, id_designacion, cuit , fecha_desde, fecha_hasta, tipo, nombre_tipo, estado FROM
                    (
                    SELECT a.id, p.nombre, p.apellido, dt.id as id_designacion, a.cuit , dt.fecha_desde as fecha_desde, dt.fecha_hasta as fecha_hasta, dt.tipo as tipo, t.nombre_tipo as nombre_tipo, datediff(dt.fecha_hasta,now()) ,if(datediff(dt.fecha_hasta,now()) > 45,'verde',if(datediff(dt.fecha_hasta,now())>0,'amarillo','rojo')) as estado FROM empleados a
					INNER JOIN empleado_escalafon ee ON a.id = ee.id_empleado
					INNER JOIN personas p ON a.id_persona = p.id
					LEFT JOIN (SELECT _dt.id_empleado, _dt.fecha_desde, _dt.fecha_hasta, _dt.archivo, _dt.tipo, _dt.borrado, MAX(_dt.fecha_desde) AS max_fecha_desde  FROM designacion_transitoria _dt WHERE _dt.borrado = 0 AND _dt.tipo IN (:prorroga, :transitoria) GROUP BY _dt.id_empleado) AS __dt ON (a.id = __dt.id_empleado)
    				LEFT JOIN designacion_transitoria dt ON (a.id = dt.id_empleado AND dt.fecha_desde = __dt.max_fecha_desde )
					LEFT JOIN ({$aux}) t ON dt.tipo = t.id
					WHERE ee.id_situacion_revista IN (:designacion_tipo1,:designacion_tipo2, :designacion_tipo3,:designacion_tipo4,:designacion_tipo5) AND a.estado = 1 AND dt.tipo IN (:prorroga, :transitoria) AND NOT ISNULL(dt.fecha_desde)
                    ORDER BY fecha_desde DESC
                   ) e
					GROUP BY e.id
				   ) t WHERE 1=1 ";
	 	$params	= array_merge($default_params, $params);
	 	$data = self::listadoAjax($campos, $consulta, $params, $sql_params);
	 	return $data;

	}
	
	public static function listadoAjax($campos, $consulta, $params=array(), $sql_params=array(), $count = false) {
        $cnx    = new Conexiones();
        $condicion = '';
        $order= '';
        if (preg_match('/where\s.$/i',$consulta, $where)) {
            $consulta = str_replace($where[0], 'WHERE ', $consulta); 
        
            $where = explode('AND', preg_replace(['/and/','/where/i'], ['AND', ''], $where[0])); 
       
            if ($params['filtros']) {
                $flag = false; 
                foreach ($where as $i => $filtro) {
                    foreach ($params['filtros'] as $key => $value) {
                        if (!is_null($value) && !empty($value) && preg_match('/' . $key . '/', $filtro)){
                            $flag= true;
                            $sql_params[":{$key}"] = $value;
                        }
                    }
                    if(preg_match("/\:/",$filtro) && !$flag){
                        unset($where[$i]);
                    }
                    $flag = false;
                }
            }
            $consulta .=  implode ('AND', $where); 
        }
        $campos_array=explode(',', $campos);
 
        $counter_query  = str_replace('*', "COUNT(DISTINCT {$campos_array[0]})  AS total",$consulta);
 
        $recordsTotal   =  $cnx->consulta(Conexiones::SELECT, $counter_query, $sql_params)[0]['total'];

	    if(!empty($params['search'])){
	        $search = [];
	        foreach (explode(' ', (string)$params['search']) as $indice => $texto) {
	            $search_campos = '';
	            foreach ($campos_array as $value) {
	                $search_campos .= ($search_campos=='') ?"$value LIKE :search{$indice}" : " OR $value LIKE :search{$indice}";

	            }
	            $search[] = $search_campos;
	            $sql_params[":search{$indice}"] = "%{$texto}%";
	        }
	        $buscar =  implode(' AND ', $search);
	        $condicion .= (!preg_match('/where/i', $consulta)) ? " WHERE {$buscar}" : " AND {$buscar} ";
	    }
	    /*Orden de las columnas */
	    $orderna = [];
	        foreach ($params['order'] as $i => $val) {
	            $orderna[]  = "{$val['campo']} {$val['dir']}";
	        }
	    $order  .=' ORDER BY '. implode(',', $orderna);

	    /**Limit: funcionalidad: desde-hasta donde se pagina */
	    $limit  = (isset($params['lenght']) && isset($params['start']))
	                        ? " LIMIT  {$params['start']}, {$params['lenght']}" :   ' ';

	    $recordsFiltered= $cnx->consulta(Conexiones::SELECT, $counter_query. $condicion,  $sql_params)[0]['total'];

	    $lista      = $cnx->consulta(Conexiones::SELECT, str_replace('*', $campos,$consulta).$condicion. $order.$limit, $sql_params);
	    if($lista){
	        foreach ($lista as $key => &$value) {
	            foreach ($value as $ke => $val) {                    
	                if (preg_match('/^\d{4}\-\d{2}\-\d{2}.*/', $val)) {  
	                	/*quito hora minuto y segundo*/
	                    $value[$ke] = \DateTime::createFromFormat('Y-m-d',$val)->format('d/m/Y');
	                   
	                }
	            }
	            $value  = (object)$value;
	        }
	    }
	    return [
	        'recordsTotal'    => !empty($recordsTotal) ? $recordsTotal : 0,
	        'recordsFiltered' => !empty($recordsFiltered) ? $recordsFiltered : 0,
	        'data'            => $lista ? $lista : [],
	    ];
	}
	
	static public function arrayToObject($res = []) {
		$campos	= [
            'id'                                => 'int',
			'id_empleado'                       => 'int',
			'fecha_desde'                 		=> 'date',
			'fecha_hasta'    					=> 'date',
			'tipo'								=> 'int',
			'archivo'							=> 'string',
			'borrado'							=> 'int'
		];
		return parent::arrayToObject($res, $campos);

		return $obj;
	}

}