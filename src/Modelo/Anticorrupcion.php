<?php
namespace App\Modelo;

//use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class Anticorrupcion extends \App\Modelo\Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_empleado;
/** @var Date */
	public $fecha_designacion;
/** @var Date */
	public $fecha_publicacion_designacion;
/** @var Date */
	public $fecha_aceptacion_renuncia;
/** @var int */
	public $id_presentacion;
/** @var int */
	public $tipo_presentacion;
/** @var Date */
	public $fecha_presentacion;
/** @var string */
    public $periodo;
/** @var string */
	public $nro_transaccion;
/** @var string */
	public $archivo; 
/** @var string */
	public $empleado;

	const ANUAL		= 2;
	const INICIAL	= 1;
	const BAJA		= 3;

	static public $TIPO_DJ 	= [
		self::INICIAL	=> ['id'	=> self::INICIAL, 'nombre' => 'Inicial', 'borrado' => '0'],
		self::ANUAL		=> ['id'	=> self::ANUAL, 'nombre' => 'Anual', 'borrado' => '0'],
		self::BAJA		=> ['id'	=> self::BAJA, 'nombre' => 'Baja', 'borrado' => '0'],

	];
	static $FECHA_VENCIMIENTO; 
	static public function getParam($attr=null){
		if($attr === null || empty(static::${$attr})){
			return [];
		}
		return static::${$attr};
	}

	static public function obtener($id=null){
		$obj	= new static;
		if($id===null){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id_empleado'	=> $id,
		];
		$sql	= <<<SQL
		SELECT  a.id,
			    a.id_empleado,
			    a.fecha_designacion,
			    a.fecha_publicacion_designacion,
			    a.fecha_aceptacion_renuncia,
			    ap.id as id_presentacion,
			    ap.tipo_presentacion,
			    ap.fecha_presentacion,
			    ap.periodo,
			    ap.nro_transaccion,
			    ap.archivo,
				e.estado
			FROM anticorrupcion a
			LEFT JOIN anticorrupcion_presentacion ap ON a.id = ap.id_anticorrupcion AND ap.borrado = 0 
			INNER JOIN empleados e ON (a.id_empleado = e.id)
			WHERE a.id_empleado = :id_empleado  and a.borrado = 0
			ORDER BY a.id DESC, ap.tipo_presentacion DESC,ap.periodo DESC
			LIMIT 1
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			if($res[0]['estado'] == \App\Modelo\Empleado::EMPLEADO_INACTIVO){
				$res = [];
			}else{
				return static::arrayToObject($res[0]);
			}
		}
		return static::arrayToObject();
	}

	static public function listar() {
		
		$sql	= <<<SQL
			SELECT  a.id,
			    a.id_empleado,
			    a.fecha_designacion,
			    a.fecha_publicacion_designacion,
			    a.fecha_aceptacion_renuncia,
			    ap.id as id_presentacion,
			    ap.tipo_presentacion,
			    ap.fecha_presentacion,
			    ap.periodo,
			    ap.nro_transaccion,
			    ap.archivo
			FROM anticorrupcion a
			INNER JOIN anticorrupcion_presentacion ap ON a.id = ap.id_anticorrupcion
			WHERE IS NOT NULL(a.fecha_aceptacion_renuncia)
			order by a.id DESC
SQL;
		$resp	= (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql);
		if(empty($resp)) { return []; }
		foreach ($resp as &$value) {
			$value	= static::arrayToObject($value);
		}
		return $resp;
	}

	public function listar_presentacion() {
		$sql_params	= [
			':id_empleado' => $this->id_empleado,
		];
		$lista = [];
		$sql	= <<<SQL
			SELECT  a.id,
			    a.id_empleado,
			    a.fecha_designacion,
			    a.fecha_publicacion_designacion,
			    a.fecha_aceptacion_renuncia,
			    ap.id as id_presentacion,
			    ap.tipo_presentacion id_tipo_presentacion,
			    '' tipo_presentacion,
			    ap.fecha_presentacion,
			    ap.periodo,
			    ap.nro_transaccion,
			    ap.archivo
			FROM anticorrupcion a
			INNER JOIN anticorrupcion_presentacion ap ON a.id = ap.id_anticorrupcion AND ap.borrado = 0
			WHERE a.id_empleado = :id_empleado AND a.borrado = 0 
			#AND ISNULL(a.fecha_aceptacion_renuncia)
			order by ap.periodo DESC, ap.tipo_presentacion DESC

SQL;
		$resp	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if (!empty($resp) && is_array($resp)) {
			foreach ($resp as $key => &$value) {
					if(isset($value['fecha_presentacion'])){
						$value['tipo_presentacion'] =  self::getParam('TIPO_DJ')[$value['id_tipo_presentacion']]['nombre'];	
						$value['fecha_presentacion'] = \DateTime::createFromFormat('Y-m-d',$value['fecha_presentacion'])->format('d/m/Y');
					}
				}
		}else{
			$resp = [];
		}
		return $resp;

	}

	public function alta(){
		if(!$this->validar()){
			return false;
		}
		$campos	= [
			'id_empleado',
			'fecha_designacion',
			'fecha_publicacion_designacion',
			'fecha_aceptacion_renuncia',
		];
		$sql_params	= [
		];
		foreach ($campos as $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
		}

		if($this->fecha_designacion instanceof \DateTime){
			$sql_params[':fecha_designacion']	= $this->fecha_designacion->format('Y-m-d');
		}
		if($this->fecha_publicacion_designacion instanceof \DateTime){
			$sql_params[':fecha_publicacion_designacion']	= $this->fecha_publicacion_designacion->format('Y-m-d');
		}
		if($this->fecha_aceptacion_renuncia instanceof \DateTime){
			$sql_params[':fecha_aceptacion_renuncia']	= $this->fecha_aceptacion_renuncia->format('Y-m-d');
		}

		$sql	= 'INSERT INTO anticorrupcion('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$this->id	= $res;
			$datos = (array) $this;
			$datos['modelo'] = 'Anticorrupcion';
			Logger::event('alta', $datos);
			if(!is_null($this->tipo_presentacion)){
				$this->alta_presentacion();	
			}
		}
		return $res;
	}

	public function alta_presentacion(){
		$this->upload_archivo();
		$campos	= [
			'id_anticorrupcion',
			'tipo_presentacion',
			'fecha_presentacion',
			'periodo',
			'nro_transaccion',
			'archivo',
		];
		$sql_params	= [
			':id_anticorrupcion' => $this->id,
			':tipo_presentacion' => $this->tipo_presentacion,
			':fecha_presentacion' => $this->fecha_presentacion,
			':periodo' => $this->periodo,
			':nro_transaccion'=>$this->nro_transaccion,
			':archivo' => $this->archivo, 
		];


		if($this->fecha_presentacion instanceof \DateTime){
			$sql_params[':fecha_presentacion']	= $this->fecha_presentacion->format('Y-m-d');
		}

		$sql	= 'INSERT INTO anticorrupcion_presentacion('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$this->id	= $res;
			$datos = (array) $this;
			$datos['modelo'] = 'Anticorrupcion';
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
				$directorio = BASE_PATH.'/uploads/anticorrupcion';

				$name =   $this->archivo["name"];
				$nombre_archivo = $date_time.'_'.$name;
				
				if(!is_dir($directorio)){
					mkdir($directorio, 0777, true);
				}
				if(move_uploaded_file($this->archivo['tmp_name'], $directorio."/".$nombre_archivo)){
			        $this->archivo = $nombre_archivo;
			         $rta = true; 
			    } else {
					$this->archivo	= null;
				}
			    return $rta;
				
			}
		}
	}


	public function baja(){
		$sql_params= [':id' => $this->id];
		$sql = <<<SQL
		update anticorrupcion set borrado = 1 where id = :id;
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Anticorrupcion';
			Logger::event('baja', $datos);
			$this->baja_presentacion();	
			$aux = Anticorrupcion::obtener($this->id_empleado);
			foreach (get_object_vars ($this) as $key => $value) {
				$this->{$key} = $aux->{$key};
			}			
		}
		return $res;
	}

	public function baja_presentacion(){
		$sql_params= [':id' => $this->id];
		$sql = <<<SQL
		update anticorrupcion_presentacion set borrado = 1 where id_anticorrupcion = :id;
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Anticorrupcion';
			Logger::event('baja_presentacion', $datos);
			
		}
		return $res;
	}

	public function modificacion(){
		if(!$this->validar() || empty($this->id)){
			return false;
		}
		
		$campos	= [
			'id_empleado' ,
			'fecha_designacion',
			'fecha_publicacion_designacion',
			'fecha_aceptacion_renuncia'
		];
		$sql_params	= [
			':id'	=> $this->id,
		];
		foreach ($campos as $key => $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
			unset($campos[$key]);
			$campos[$campo]	= $campo .' = :'.$campo;
		}


		if($this->fecha_designacion instanceof \DateTime){
			$sql_params[':fecha_designacion']	= $this->fecha_designacion->format('Y-m-d');
		}
		if($this->fecha_publicacion_designacion instanceof \DateTime){
			$sql_params[':fecha_publicacion_designacion']	= $this->fecha_publicacion_designacion->format('Y-m-d');
		}
		if($this->fecha_aceptacion_renuncia instanceof \DateTime){
			$sql_params[':fecha_aceptacion_renuncia']	= $this->fecha_aceptacion_renuncia->format('Y-m-d');
		}

		$sql	= 'UPDATE anticorrupcion SET '.implode(',', $campos).' WHERE id = :id';

		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Anticorrupcion';
			Logger::event('modificacion', $datos);
			if(!is_null($this->tipo_presentacion)){
				$this->modificacion_presentacion();	
			}
		}
		return $res;
	 }


	 public function modificacion_presentacion(){
		$this->upload_archivo();
		$campos	= [
			'id_anticorrupcion'  => 'id_anticorrupcion = :id_anticorrupcion',
			'tipo_presentacion' => 'tipo_presentacion = :tipo_presentacion',
			'fecha_presentacion' => 'fecha_presentacion = :fecha_presentacion',
			'periodo' => 'periodo = :periodo' ,
			'nro_transaccion' => 'nro_transaccion = :nro_transaccion' ,
			'archivo' => 'archivo = :archivo' ,
		];

		$sql_params	= [
			':id_anticorrupcion' => $this->id,
			':tipo_presentacion' => $this->tipo_presentacion,
			':fecha_presentacion' => $this->fecha_presentacion,
			':periodo' => $this->periodo,
			':nro_transaccion'=>$this->nro_transaccion,
			':archivo' => $this->archivo,
			':id' =>$this->id_presentacion
		];

		if($this->fecha_presentacion instanceof \DateTime){
			$sql_params[':fecha_presentacion']	= $this->fecha_presentacion->format('Y-m-d');
		}

		$sql	= 'UPDATE anticorrupcion_presentacion SET  '.implode(',', $campos).' WHERE id = :id';
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Anticorrupcion';
			Logger::event('modificacion', $datos);
		}
		return $res;
	 }


	public function validar() {
		$array_anticorrupcion = (array)$this;
		$array_anticorrupcion['archivo_comprobante'] = is_array($this->archivo) ? $this->archivo["name"] : $this->archivo;
		$reglas		= [
			'fecha_designacion'  			 => ['fecha', 'antesDe(:fecha_publicacion_designacion)'],
			'fecha_publicacion_designacion'  => ['fecha', 'despuesDe(:fecha_designacion)'],
		];
		if(!empty($this->id)){
			$reglas	+= [
				'fecha_aceptacion_renuncia'  	 => ['fecha', 'despuesDe(:fecha_publicacion_designacion)'],
				'archivo_comprobante'  	 		 => ['extension' => function($input){
		              $ext = pathinfo($input, PATHINFO_EXTENSION);
		              if (!empty($input)){
		                  return  ($ext == 'pdf');
		              }
		              return true;
		          }],
			];

			if($this->tipo_presentacion || $this->nro_transaccion 
			|| $this->fecha_presentacion || $this->periodo) {
				$reglas	+= [
					'nro_transaccion'  	 			 => ['required','integer'],
					'tipo_presentacion'  			 => ['required','integer',
					'solo_inactivo(:fecha_aceptacion_renuncia)' => function($input,$fecha){
						$rta = true;
						if ($input == self::BAJA) {

							$rta =  (!is_null($fecha)) ? true : false;
						}
						return $rta;
					},
					'tipo_unico(:id,:id_presentacion)' => function($input,$id,$id_presentacion){						
						if ($input == self::BAJA || $input == self::INICIAL) {
							$params = [];
							$excluye_reg= '';
							if($id_presentacion){
								$excluye_reg = ' AND id !=:id_presentacion';
								$params[':id_presentacion'] = $id_presentacion;
							}
							$sql = <<<SQL
							SELECT count(*) count FROM anticorrupcion_presentacion WHERE borrado = 0 AND id_anticorrupcion = :id_anticorrupcion AND tipo_presentacion = :tipo_presentacion $excluye_reg
SQL;
							$params += [':tipo_presentacion' => $input, ':id_anticorrupcion' => $id];
							$con = new Conexiones();
							$res = $con->consulta(Conexiones::SELECT, $sql, $params);
							if (is_array($res) && isset($res[0]) && isset($res[0]['count'])) {
								return ($res[0]['count'] < 1);
							}
							return false;
						}
						return true;
					},
					'inicial(:id)' => function($input, $id){
						if ($input ==  self::ANUAL) {
								$sql = <<<SQL
							SELECT count(*) count FROM anticorrupcion_presentacion WHERE id_anticorrupcion = :id_anticorrupcion AND tipo_presentacion = :tipo_presentacion
SQL;
							$tipo_presentacion = self::INICIAL;
							$params = [':tipo_presentacion' => $tipo_presentacion , ':id_anticorrupcion' => $id];
							$con = new Conexiones();
							$res = $con->consulta(Conexiones::SELECT, $sql, $params);
							if (isset($res[0]) && isset($res[0]['count'])) {
								return ($res[0]['count'] > 0);
							}
							return false;
						}
						return true;
					}
					],
					'fecha_presentacion' 			 => ['required','fecha', 'despuesDe(:fecha_publicacion_designacion)' ],
					'periodo'  	 			 		 => ['required', 'fecha', 'periodo_fecha(:tipo_presentacion,:fecha_presentacion)' => function($input,$tipo_presentacion,$fecha_presentacion){
						if(empty($fecha_presentacion)){
							return false;
						}
							$fecha =  clone $fecha_presentacion;
							$fecha->sub(new \DateInterval('P1Y'));
							if($tipo_presentacion == Anticorrupcion::ANUAL) {								
								$rta = ($fecha->format('Y') == $input) ? true : false;
							} else {
								$rta = ($fecha->format('Y') == $input || $fecha_presentacion->format('Y') == $input) ? true : false;
							}
							return $rta;
						}, 
						'periodo_inicial(:tipo_presentacion,:id)' => function($input,$tipo_presentacion,$id){
							if (!is_null($input) && $tipo_presentacion == Anticorrupcion::ANUAL) {
								$sql = <<<SQL
								select periodo from anticorrupcion_presentacion where id_anticorrupcion = :id  and tipo_presentacion = :tipo_presentacion
SQL;
								$params = [':id' => $id, ':tipo_presentacion' => Anticorrupcion::INICIAL];
								$con = new Conexiones();
								$res = $con->consulta(Conexiones::SELECT, $sql, $params);
								if (!empty($res[0]) && $res[0]['periodo'] <= $input) {
									return true;
								}
								return false;
							}
							return true;
						},
						'unico_periodo(:id,:tipo_presentacion,:id_presentacion,:id_empleado)' => function($input,$id,$tipo_presentacion,$id_presentacion,$id_empleado){
							if (!is_null($input)) {
								$sql = <<<SQL
								select count(*) count from anticorrupcion where id_empleado = :id_empleado and isnull(fecha_aceptacion_renuncia)
SQL;
								$params = [':id_empleado' => $id_empleado];		
								$con = new Conexiones();
								$res = $con->consulta(Conexiones::SELECT, $sql, $params);
								if (is_array($res) && isset($res[0]) && isset($res[0]['count'])) {
									if($res[0]['count'] > 0){
										return true;
									}else{
										$params = [':tipo_presentacion' => $tipo_presentacion, ':id_anticorrupcion' => $id, ':periodo' => $input];
										$id_resgistro = '';
										if(!empty($id_presentacion)) {
											$id_resgistro = 'AND id != :id_presentacion';
											$params[':id_presentacion'] =  $id_presentacion;	
										}
										$sql = <<<SQL
										select count(*) count from anticorrupcion_presentacion where id_anticorrupcion = :id_anticorrupcion and tipo_presentacion = :tipo_presentacion  and periodo = :periodo $id_resgistro 
SQL;

										$con = new Conexiones();
										$res = $con->consulta(Conexiones::SELECT, $sql, $params);
										if (is_array($res) && isset($res[0]) && isset($res[0]['count'])) {
											return !($res[0]['count'] > 0);
										}
										return false;
									}
								}
							}
							return true;
					}],
				];
			}
		}


		$nombres	= [
			'fecha_designacion'				=> 'Fecha de Designación',
			'fecha_publicacion_designacion' => 'Fecha de Publicación de la Designación',
			'fecha_aceptacion_renuncia'		=> 'Fecha de Aceptación de la Renuncia',
			'tipo_presentacion'				=> 'Tipo de Presentación',
			'fecha_presentacion' 			=> 'Fecha Presentación',
			'periodo'						=> 'Periodo',
			'nro_transaccion'				=> 'Número de Transacción',
			'archivo_comprobante'			=> 'Archivo Comprobante'

		];
		$validator	= Validator::validate($array_anticorrupcion, $reglas, $nombres);
		$validator->customErrors([
            'extension'         => 'Extensión inválida. Se permite sólo archivos pdf',
            'periodo_inicial'   => 'El Periodo, debe ser mayor o igual al Periodo de la Presentación de Tipo INICIAL',
            'unico_periodo'     => 'Ya existe una Presentación asociada a éste Periodo',
            'solo_inactivo'		=>  'No puede crearse una presentacion de baja sin cargar la "Fecha de Aceptación de Renuncia."',
            'periodo_fecha'		=> 'Periodo incorrecto para tipo de presentación.',
            'tipo_unico'        => 'Verifique el tipo de declaración. Los tipos de presentación "Inicial" y "Baja" deben ser únicas.',
            'f_renuncia'		=> 'La fecha de aceptación de renuncia, no puede ser menor a la fecha de renuncia',
            'present'			=> 'Ya existe una Presentación Anual con el mismo periodo',
            'inicial'			=> 'No puede crearse una Presentación Anual si no tiene Presentación Inicial'
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


	static public function obtener_archivo($id_presentacion){
		//$aux='';
		if($id_presentacion){
	    $Conexiones = new Conexiones();
	    $resultado = $Conexiones->consulta(Conexiones::SELECT,
<<<SQL

			SELECT  archivo
			FROM anticorrupcion_presentacion
			WHERE id = :id_presentacion
			LIMIT 1
SQL
,[':id_presentacion'=>$id_presentacion]);
	
	    return $resultado[0]['archivo'];
		}

	}


	static public function arrayToObject($res = []) {
		static::$FECHA_VENCIMIENTO = new \DateTime(date('Y').'-05-31');
		$campos	= [

            'id'                                => 'int',
			'id_empleado'                       => 'int',
			'fecha_designacion'                 => 'date',
			'fecha_publicacion_designacion'     => 'date',
			'fecha_aceptacion_renuncia'         => 'date',
			'id_presentacion'					=> 'int',
			'tipo_presentacion'					=> 'int',
		    'fecha_presentacion'				=> 'date',
		    'periodo' 							=> 'string',
		    'nro_transaccion' 					=> 'string',
		    'archivo'							=> 'string'
			
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
					$obj->{$campo}	= isset($res[$campo]) ? \DateTime::createFromFormat('Y-m-d 00:00:00', $res[$campo]) : null;
					break;
				case 'date':
					$obj->{$campo}	= isset($res[$campo]) ? \DateTime::createFromFormat('Y-m-d', $res[$campo]) : null;
					break;
				default:
					$obj->{$campo}	= isset($res[$campo]) ? $res[$campo] : null;
					break;
			}
		}

		if(isset($res['cuit'])){
			$obj->empleado = new \StdClass();
			$obj->empleado->cuit = $res['cuit'];
			$obj->empleado->nombre = $res['nombre'];
			$obj->empleado->email = $res['email'];
			$obj->empleado->nombre_puesto = $res['nombre_puesto'];
		}
		return $obj;
	}


	public static function informe_anticorrupcion($params=array(), $count = false) {
		$anio = date('Y');
		$baja = self::BAJA;
		$cnx	= new Conexiones();
		$sql_params = [];
		$where = [];
		$condicion = '';
		$order = '';
		$search = [];
		$default_params	= [
			'order'		=> [
				[
					'campo'	=> 'nombre',
					'dir'	=> 'ASC',
				],
			],
			'search'	=> '',
			'count'		=> false
		];

		$params	= array_merge($default_params, $params);

		$sql= <<<SQL
			SELECT 
		    a.id,
			a.id_empleado,
			a.fecha_designacion,
			a.fecha_publicacion_designacion,
			a.fecha_aceptacion_renuncia,
			ap.tipo_presentacion,
			ap.fecha_presentacion,
			ap.periodo,
			concat(p.nombre,' ',p.apellido) AS nombre,
			p.email,
			e.cuit,
			pu.nombre AS nombre_puesto
SQL;

		$from = <<<SQL
			FROM anticorrupcion a
			LEFT JOIN (SELECT @pv:=id id FROM anticorrupcion) __a ON (__a.id = a.id)
			LEFT JOIN anticorrupcion_presentacion ap ON a.id = ap.id_anticorrupcion
			INNER JOIN empleados e ON e.id = a.id_empleado 
			INNER JOIN personas p ON p.id = e.id
			LEFT JOIN empleado_perfil ep ON ep.id_empleado = e.id
			LEFT JOIN puestos pu ON pu.id = ep.nombre_puesto
SQL;

		$order =
 		<<<SQL
 			ORDER BY 
SQL;
 		$condicion =
 		<<<SQL
 			WHERE a.borrado = 0
SQL;

    $counter_query	= "SELECT COUNT(t.cuit) AS total FROM (SELECT * FROM ( SELECT cuit,tipo_presentacion,periodo {$from}";

	$recordsTotal	=  $cnx->consulta(Conexiones::SELECT, $counter_query . $condicion." ORDER BY cuit, a.id DESC, tipo_presentacion DESC,periodo DESC,fecha_presentacion DESC) tt GROUP BY cuit HAVING NOT (tipo_presentacion = {$baja} AND periodo < '{$anio}' )) t", $sql_params )[0]['total'];

	if(!empty($params['search'])){
		$indice = 0;
		$search[]	= <<<SQL
		(e.cuit LIKE :search{$indice} OR
		 p.nombre LIKE :search{$indice} OR
		 p.apellido LIKE :search{$indice} OR
		 ap.periodo LIKE :search{$indice} OR
		 CONCAT(p.nombre,' ',p.apellido) LIKE :search{$indice})
SQL;
		$texto = $params['search'];	
		$sql_params[":search{$indice}"]	= "%{$texto}%";

		$buscar =  implode(' AND ', $search);
		$condicion .= empty($condicion) ? "{$buscar}" : " AND {$buscar} ";
	}

	/**Orden de las columnas */
	$orderna = [];
		foreach ($params['order'] as $i => $val) {
			$orderna[]	= "{$val['campo']} {$val['dir']}";
		}

	$order 	.= implode(',', $orderna);


	/**Limit: funcionalidad: desde-hasta donde se pagina */
	$limit	= (isset($params['lenght']) && isset($params['start']))
						? " LIMIT  {$params['start']}, {$params['lenght']}"	:	' ';
	$recordsFiltered= $cnx->consulta(Conexiones::SELECT, $counter_query. $condicion." ORDER BY cuit, tipo_presentacion DESC,periodo DESC,fecha_presentacion DESC )tt GROUP BY  cuit HAVING NOT (tipo_presentacion = {$baja} AND periodo < '{$anio}')) t",  $sql_params)[0]['total'];
	$order .= (strlen($order) > 13 ? ',' : '').'cuit,tipo_presentacion DESC,fecha_presentacion DESC';
	$lista	= $cnx->consulta(Conexiones::SELECT, 'SELECT * FROM ('.$sql .$from. $condicion ." ORDER BY cuit, id DESC,  tipo_presentacion DESC,periodo DESC,fecha_presentacion DESC) lac  GROUP BY cuit HAVING NOT (tipo_presentacion = {$baja} AND periodo < '{$anio}') ".$order. $limit, $sql_params);

		if($lista){
			foreach ($lista as $key => &$value) {
				$value	= (object)$value;

				if($value->fecha_presentacion){
					$value->fecha_presentacion = \DateTime::createFromFormat('Y-m-d', $value->fecha_presentacion);
				}
				if($value->fecha_designacion){
					$value->fecha_designacion = \DateTime::createFromFormat('Y-m-d', $value->fecha_designacion);
				}
				if($value->fecha_publicacion_designacion){
					$value->fecha_publicacion_designacion = \DateTime::createFromFormat('Y-m-d', $value->fecha_publicacion_designacion);
				}
				if($value->fecha_aceptacion_renuncia){
					$value->fecha_aceptacion_renuncia = \DateTime::createFromFormat('Y-m-d', $value->fecha_aceptacion_renuncia);
				}
			}
		}
		return [
			'recordsTotal'    => !empty($recordsTotal) ? $recordsTotal : 0,
			'recordsFiltered' => !empty($recordsFiltered) ? $recordsFiltered : 0,
			'data'            => $lista ? $lista : [],
		];
	}
	
	
	public static function listadoHistorialAnticorrupcion($params=array(), $count = false) {

		$cnx	= new Conexiones();
		$sql_params = [];
		$where = [];
		$condicion = '';
		$order = '';
		$search = [];
		$default_params	= [
			'order'		=> [
				[
					'campo'	=> 'nombre',
					'dir'	=> 'ASC',
				],
			],
			'start'		=> 0,
			'lenght'	=> 10,
			'search'	=> '',
			'filtros'	=> [
				'periodo'				=> null,
				'tipo_dj'				=> null
			],
			'count'		=> false
		];
		$params['filtros']	= array_merge($default_params['filtros'], $params['filtros']);
		$params	= array_merge($default_params, $params);

		$aux = '';
		foreach (self::$TIPO_DJ as $value) {
			$aux .= ($aux) ? " union all select {$value['id']},'{$value['nombre']}'" 
						  : " select {$value['id']} id,'{$value['nombre']}' nombre";
		}

		$sql= <<<SQL
			SELECT 
		    a.id,
			a.id_empleado,
			concat(p.nombre,' ',p.apellido) AS nombre,
			e.cuit,
			ap.periodo,
			a.fecha_designacion,
			a.fecha_publicacion_designacion,
			a.fecha_aceptacion_renuncia,
			ap.id id_presentacion,
			ap.tipo_presentacion,
			dj.nombre dj,
			ap.fecha_presentacion,
			ap.nro_transaccion,
			ap.archivo,
			p.email,
			pu.nombre AS nombre_puesto
SQL;

		$from = <<<SQL
			FROM anticorrupcion a
			LEFT JOIN anticorrupcion_presentacion ap ON a.id = ap.id_anticorrupcion
			INNER JOIN empleados e ON e.id = a.id_empleado 
			INNER JOIN personas p ON p.id = e.id_persona
			LEFT JOIN empleado_perfil ep ON ep.id_empleado = e.id
			LEFT JOIN puestos pu ON pu.id = ep.nombre_puesto
			LEFT JOIN ({$aux}) dj ON ap.tipo_presentacion = dj.id
SQL;

		$order = '';
 		$condicion =
 		<<<SQL
 			WHERE a.borrado = 0
SQL;

	if(!empty($params['filtros']['periodo'])){
		$condicion .= " AND ap.periodo IN (:periodo)";
		$sql_params[':periodo']	= $params['filtros']['periodo'];
	}

	if(!empty($params['filtros']['tipo_dj'])){
		$condicion .= " AND ap.tipo_presentacion IN (:tipo_dj)";
		$sql_params[':tipo_dj']	= $params['filtros']['tipo_dj'];
	}
    $counter_query	= "SELECT COUNT(a.id) AS total {$from}";

	$recordsTotal	=  $cnx->consulta(Conexiones::SELECT, $counter_query . $condicion, $sql_params )[0]['total'];

	if(!empty($params['search'])){
		$indice = 0;
		$search[]	= <<<SQL
		(e.cuit LIKE :search{$indice} OR
		 p.nombre LIKE :search{$indice} OR
		 e.cuit LIKE :search{$indice} OR
		 date_format(a.fecha_designacion,'%d/%m/%Y') like :search{$indice} OR
		 date_format(a.fecha_publicacion_designacion,'%d/%m/%Y') like :search{$indice} OR
		 date_format(a.fecha_aceptacion_renuncia,'%d/%m/%Y') like :search{$indice} OR
		 ap.tipo_presentacion LIKE :search{$indice} OR
		 pu.nombre LIKE :search{$indice} OR
		 ap.nro_transaccion LIKE :search{$indice} OR
		 p.apellido LIKE :search{$indice} OR
		 CONCAT(p.nombre,' ',p.apellido) LIKE :search{$indice} OR
		 ap.periodo LIKE :search{$indice} OR
		 dj.nombre LIKE  :search{$indice} OR
		 date_format(ap.fecha_presentacion,'%d/%m/%Y') like :search{$indice})
SQL;
		$texto = $params['search'];	
		$sql_params[":search{$indice}"]	= "%{$texto}%";

		$buscar =  implode(' AND ', $search);
		$condicion .= empty($condicion) ? "{$buscar}" : " AND {$buscar} ";
	}

	/**Orden de las columnas */
	$orderna = [];
		foreach ($params['order'] as $i => $val) {
			$orderna[]	= "{$val['campo']} {$val['dir']}";
		}

	$order 	.= implode(',', $orderna);

	/**Limit: funcionalidad: desde-hasta donde se pagina */
	$limit	= (isset($params['lenght']) && isset($params['start']) && $params['lenght'] != '')
						? " LIMIT  {$params['start']}, {$params['lenght']}"	:	' ';
	$recordsFiltered= $cnx->consulta(Conexiones::SELECT, $counter_query.$condicion,  $sql_params)[0]['total'];

	$order .= (($order =='') ? '' : ', ').'a.id_empleado , a.id, ap.tipo_presentacion DESC,  ap.periodo DESC, ap.fecha_presentacion DESC';
	$order = ' ORDER BY '.$order;

	$lista	= $cnx->consulta(Conexiones::SELECT,  $sql .$from.$condicion.$order.$limit,$sql_params);

		if($lista){
			foreach ($lista as $key => &$value) {
				$value	= (object)$value;

				if($value->fecha_presentacion){
					$value->fecha_presentacion = \DateTime::createFromFormat('Y-m-d', $value->fecha_presentacion)->format('d/m/Y');
				}
				if($value->fecha_designacion){
					$value->fecha_designacion = \DateTime::createFromFormat('Y-m-d', $value->fecha_designacion)->format('d/m/Y');
				}
				if($value->fecha_publicacion_designacion){
					$value->fecha_publicacion_designacion = \DateTime::createFromFormat('Y-m-d', $value->fecha_publicacion_designacion)->format('d/m/Y');
				}
				if($value->fecha_aceptacion_renuncia){
					$value->fecha_aceptacion_renuncia = \DateTime::createFromFormat('Y-m-d', $value->fecha_aceptacion_renuncia)->format('d/m/Y');
				}
				
			}
		}
		return [
			'recordsTotal'    => !empty($recordsTotal) ? $recordsTotal : 0,
			'recordsFiltered' => !empty($recordsFiltered) ? $recordsFiltered : 0,
			'data'            => $lista ? $lista : [],
		];
	}

	public static function exportar($params=array(), $count = false){
		$result = static::listadoHistorialAnticorrupcion($params, $count);
		$resultado = [];
		foreach ($result['data'] as $key => $value) {
			$resultado[$key] = (array)$value;

		}
		return $resultado;
	}

	/**
	* Metodo que reordena los resultados procesados en PHP  
	* reordenar y limitar el listado segun los parametros.
	*/
	public static function informe_anticorrupcion_temporal($data,$params) {

		usort($data['data'], function($a, $b) use ($params) {
			if($params[1] == 'asc') {
    			$rta = $a->{$params[0]} - $b->{$params[0]};
    		} else {
    			$rta = $b->{$params[0]} - $a->{$params[0]};
    		}
    		return $rta;
		});
		$data['data'] = array_slice($data['data'],$params[2],$params[3]);

 		return $data; 
	}

	public static function listar_notificaciones() {
//		$fecha_actual = date("Y");
//		$anio_anterior =  date("Y",strtotime($fecha_actual."- 1 year"));
	$sql	= <<<SQL
SELECT * FROM 
(SELECT  a.id,
			a.id_empleado,
			a.fecha_designacion,
			a.fecha_publicacion_designacion,
			a.fecha_aceptacion_renuncia,
			ap.tipo_presentacion,
			ap.fecha_presentacion,
			ap.periodo,
			e.cuit,
			e.estado,
			concat(p.nombre,' ',p.apellido) AS nombre,
			p.email,
			null as 'nombre_puesto',
			null as 'nro_transaccion',
			null as 'archivo',
			null as 'id_presentacion'
			FROM anticorrupcion a
			LEFT JOIN anticorrupcion_presentacion ap ON a.id = ap.id_anticorrupcion
			INNER JOIN empleados e ON e.id = a.id_empleado 
			INNER JOIN personas p ON p.id = e.id
			WHERE a.borrado = 0
            order by ap.periodo DESC
 ) p GROUP BY p.id_empleado            
SQL;
		$resp	= (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql);

		if(empty($resp)) { return []; }

		foreach ($resp as &$value) {
			$estado = $value['estado'];
 			$value	= static::arrayToObject($value);
 			$value->empleado->estado = $estado;
 		}
		return $resp;
	}


}
