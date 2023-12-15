<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

/**
 * Descripcion de datos: Secretarias, Subsecretarias, etc
*/
class Dependencia extends Modelo {
/** @var int */
	public $id;
/** @var array */
	public $dependencias_informales;
/** @var string */
	public $nombre;
/** @var string */
	public $codep;
/** @var int */
	public $id_padre;
/** @var Date */
	public $fecha_desde;
/** @var Date */
	public $fecha_hasta;
/** @var int */
	public $nivel;
	//public $dependencia_informal;
    static private $ANULAR_FILTRO   = false;

	const MINISTRO			= 1;
	const SECRETARIA		= 2;
	const SUBSECRETARIA		= 3;
	const DIRECCION_GENERAL	= 4;
	const DIRECCION_SIMPLE	= 5;
	const COORDINACION		= 6;
	const UNIDAD_O_AREA		= 7;

	const FAKE_PADRE_ID			= 99999;
	const FAKE_NIVEL			= 999;

/** Nivel del Organigrama*/
	static protected $NIVEL_ORGANIGRAMA	= [
		self::MINISTRO				=> ['id' => self::MINISTRO, 'nombre' => 'Ministro', 'borrado' => '0'],
		self::SECRETARIA			=> ['id' => self::SECRETARIA, 'nombre' => 'Secretaria', 'borrado' => '0'],
		self::SUBSECRETARIA			=> ['id' => self::SUBSECRETARIA, 'nombre' => 'Subsecretaria', 'borrado' => '0'],
		self::DIRECCION_GENERAL		=> ['id' => self::DIRECCION_GENERAL, 'nombre' => 'Direccion general o Nacional', 'borrado' => '0'],
		self::DIRECCION_SIMPLE		=> ['id' => self::DIRECCION_SIMPLE, 'nombre' => 'Direccion Simple', 'borrado' => '0'],
		self::COORDINACION			=> ['id' => self::COORDINACION, 'nombre' => 'Coordinacion', 'borrado' => '0'],
		self::UNIDAD_O_AREA			=> ['id' => self::UNIDAD_O_AREA, 'nombre' => 'Unidad O Area', 'borrado' => '0'],
	];
/**
 * Obtiene los valores de los array parametricos.
 * E.J.: Dependencia::getParam('NIVEL_ORGANIGRAMA');
*/
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
			':id'	=> $id,
		];
		$campos	= implode(',', [
			'id',
			'nombre',
			'codep',
			'id_padre',
			'fecha_desde',
			'fecha_hasta',
			'nivel',
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM dependencias
			WHERE id = :id
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}

	static public function lista_dep_informales($id_dependencia=null){
		$dependencias	= DependenciaInformal::listar($id_dependencia);
		$aux			= [];
		if(!empty($dependencias)){
			foreach ($dependencias as $id => $object) {
				$aux[$id]	= [
					'id'				=> $object->id,
					'id_dependencia'	=> $object->id_dependencia,
					'nombre'			=> $object->nombre,
					'borrado'			=> empty($object->facha_hasta) ? 0 : 1,
				];
			}
		}
		return $aux;

	}
	
	static public function anularFiltro(){
        static::$ANULAR_FILTRO  = true;
    }
	 
	static public function listar($select = false) {
		static $return	= null;
		if($return !== null && $select){
			return $return;
		}
		$campos	= implode(',', [
		
			'd.nombre',
			'd.codep',
			'd.id_padre',
			'd.fecha_desde',
			'd.fecha_hasta',
			'd.nivel',
			'n.nombre_nivel'
		]);
		$aux ='';
		foreach (static::$NIVEL_ORGANIGRAMA	 as $value) {
			$aux .= ($aux) ? " union all select {$value['id']},'{$value['nombre']}'" 
						  : " select {$value['id']} id,'{$value['nombre']}' nombre_nivel";
		}

        $where  = '';
        if(static::$ANULAR_FILTRO === true){
            static::$ANULAR_FILTRO  = false;
        } else {
            $where  = 'WHERE isnull(d.fecha_hasta)';
        }
		$sql	= <<<SQL
			SELECT d.id, {$campos}
			FROM dependencias d
			LEFT JOIN ($aux) n  ON d.nivel =  n.id 
			{$where}
			ORDER BY d.nombre ASC;
SQL;
		$resp	= (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql);
		if(empty($resp)) { return []; }
		
		if($select){
			$aux = [];
			foreach ($resp as $value) {
				$aux[$value['id']] = [
					'id' => $value['id'],
					'nombre' => $value['nombre'],
					'borrado' => !empty($value['fecha_hasta']),
				];
			}
			return $return	= $aux;
		}

		foreach ($resp as &$value) {
			$value	= (object)$value;
			$value->fecha_desde	= \DateTime::createFromFormat('Y-m-d', $value->fecha_desde);
			$value->fecha_hasta	= \DateTime::createFromFormat('Y-m-d', $value->fecha_hasta);
		}
	
		return $resp;
	}

	static public function lista_dependencias() {
		$aux=[];
		$mbd = new Conexiones;
	    $resultado = $mbd->consulta(Conexiones::SELECT,
	    "SELECT
	    id, nombre, id_padre, nivel, 0 as  borrado 
	    FROM dependencias
	    ORDER BY nombre ASC ");
	    if(empty($resultado)) { return []; }
	    foreach ($resultado as $value) {
	    	$aux[$value['id']] = $value;
	    }
	    return $aux;

	}

	public function alta(){
		$campos	= [
			'nombre',
			'codep',
			'id_padre',
			'fecha_desde',
			'fecha_hasta',
			'nivel',
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

		$sql	= 'INSERT INTO dependencias('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$this->id = (int)$res;
			$this->alta_dep_historica();
			$datos = (array) $this;
			$datos['modelo'] = 'Dependencia';
			Logger::event('alta', $datos);
		}
		return $res;
	}

	public static function listadoDependencias($params=array(), $count = false) {
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
			'count'		=> false
		];

		$params	= array_merge($default_params, $params);

		$aux ='';
		foreach (static::$NIVEL_ORGANIGRAMA	 as $value) {
			$aux .= ($aux) ? " union all select {$value['id']},'{$value['nombre']}'" 
						  : " select {$value['id']} id,'{$value['nombre']}' nombre_nivel";
		}

		$sql= <<<SQL
			SELECT 
		    d.id,
		    d.nombre,
			d.codep,
			d.id_padre,
			d.fecha_desde,
			d.fecha_hasta,
			d.nivel,
			n.nombre_nivel
SQL;

		$from = <<<SQL
			FROM dependencias d
			LEFT JOIN ({$aux}) n  ON d.nivel =  n.id 
SQL;

		$order =
 		<<<SQL
 			ORDER BY 
SQL;
 		$condicion =
 		<<<SQL
 			WHERE (ISNULL(d.fecha_hasta) OR d.fecha_hasta = '0000-00-00')
SQL;

    $counter_query	= "SELECT COUNT(DISTINCT d.id) AS total {$from}";

	$recordsTotal	=  $cnx->consulta(Conexiones::SELECT, $counter_query . $condicion, $sql_params)[0]['total'];

	if(!empty($params['search'])){
		$indice = 0;
		//foreach ($params['search'] as $indice => $texto) {
		$search[]	= <<<SQL
		(d.nombre LIKE :search{$indice} OR
		n.nombre_nivel LIKE :search{$indice} OR
		d.fecha_desde LIKE :search{$indice})
SQL;
		$texto = $params['search'];	
		$sql_params[":search{$indice}"]	= "%{$texto}%";
		//}
		$buscar =  implode(' AND ', $search);
		$condicion .= empty($condicion) ? "{$buscar}" : " AND {$buscar} ";
	}

	/**Orden de las columnas */
	$orderna = [];
		foreach ($params['order'] as $i => $val) {
			$orderna[]	= "{$val['campo']} {$val['dir']}";
		}

	$order 	.=  implode(',', $orderna);


	/**Limit: funcionalidad: desde-hasta donde se pagina */
	$limit	= (isset($params['lenght']) && isset($params['start']))
						? " LIMIT  {$params['start']}, {$params['lenght']}"	:	' ';

	$recordsFiltered= $cnx->consulta(Conexiones::SELECT, $counter_query. $condicion,  $sql_params)[0]['total'];
					
	$lista		= $cnx->consulta(Conexiones::SELECT,  $sql .$from. $condicion . $order . $limit, $sql_params);

		if($lista){
			foreach ($lista as $key => &$value) {
				$value	= (object)$value;
				$value->fecha_desde	= \DateTime::createFromFormat('Y-m-d', $value->fecha_desde)->format('d/m/Y');

			}

		}
		return [
			'recordsTotal'    => !empty($recordsTotal) ? $recordsTotal : 0,
			'recordsFiltered' => !empty($recordsFiltered) ? $recordsFiltered : 0,
			'data'            => $lista ? $lista : [],
		];
	}


	public static function listadoDependenciasInformales($params=array(), $count = false) {
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
				'dependencia'				=> null,
			],
			'count'		=> false
		];

		$params	= array_merge($default_params, $params);
		$aux ='';
		foreach (static::lista_dependencias() as $value) {
			$aux .= ($aux) ? " union all select {$value['id']},'{$value['nombre']}'" 
						  : " select {$value['id']} id,'{$value['nombre']}' nombre_padre";
		}
		$sql= <<<SQL
			SELECT 
		   d.id, 
		   d.nombre, 
		   d.id_dependencia, 
		   d.fecha_desde, 
		   d.fecha_hasta,
		   n.nombre_padre
SQL;

		$from = <<<SQL
			FROM dependencias_informales d
			LEFT JOIN ({$aux}) n  ON d.id_dependencia =  n.id

SQL;

		$order =
 		<<<SQL
 			ORDER BY 
SQL;



 	if(!empty($params['filtros']['dependencia'])){
		$where[] = "id_dependencia= :dependencia";
		$sql_params[':dependencia']	= $params['filtros']['dependencia'];
	}

 	$condicion = !empty($where) ? ' WHERE ISNULL(d.fecha_hasta) AND ' . \implode(' AND ',$where) : 'WHERE ISNULL(d.fecha_hasta) ';

    $counter_query	= "SELECT COUNT(DISTINCT d.id) AS total {$from}";

	$recordsTotal	=  $cnx->consulta(Conexiones::SELECT, $counter_query. $condicion, $sql_params)[0]['total'];

	if(!empty($params['search'])){
		$search	= [];
		foreach (explode(' ', (string)$params['search']) as $indice => $texto) {
			if(!empty($texto)) {
				$search[]	= <<<SQL
					d.nombre LIKE :search{$indice} OR
					n.nombre_padre LIKE :search{$indice} OR
					d.fecha_desde LIKE :search{$indice}
SQL;
				$sql_params[":search{$indice}"]	= "%{$texto}%";
			}
		}
		$buscar =  implode(' AND ', $search);
		$condicion .= empty($condicion) ? "{$buscar}" : " AND {$buscar} ";
	}

	/**Orden de las columnas */
	$orderna = [];
		foreach ($params['order'] as $i => $val) {
			$orderna[]	= "{$val['campo']} {$val['dir']}";
		}

	$order 	.=  implode(',', $orderna);


	/**Limit: funcionalidad: desde-hasta donde se pagina */
	$limit	= (isset($params['lenght']) && isset($params['start']))
						? " LIMIT  {$params['start']}, {$params['lenght']}"	:	' ';

	$recordsFiltered= $cnx->consulta(Conexiones::SELECT, $counter_query.$condicion,  $sql_params)[0]['total'];
					
	$lista		= $cnx->consulta(Conexiones::SELECT,  $sql .$from. $condicion . $order . $limit, $sql_params);
		if($lista){
			foreach ($lista as $key => &$value) {
				$value	= (object)$value;
				$temp = \DateTime::createFromFormat('Y-m-d', $value->fecha_desde);
				$value->fecha_desde	= (is_object($temp)) ? $temp->format('d/m/Y') : '';

			}

		}
		return [
			'recordsTotal'    => !empty($recordsTotal) ? $recordsTotal : 0,
			'recordsFiltered' => !empty($recordsFiltered) ? $recordsFiltered : 0,
			'data'            => $lista ? $lista : [],
		];
	}

	public function baja(){
		$mbd = new Conexiones;
		$sql= <<<SQL
		SELECT count(id_dependencia) AS count FROM empleado_dependencia WHERE id_dependencia = :id_dependencia AND isnull(fecha_hasta)
SQL;
		$params = [':id_dependencia' => $this->id];
		$res = $mbd->consulta(Conexiones::SELECT, $sql, $params);

		if(isset($res[0]['count']) && $res[0]['count'] != '0'){ // count > 0
			return false;
		}

		$sql = <<<SQL
		 UPDATE dependencias
                SET 
                    fecha_hasta = :fecha_hasta
                WHERE id = :id
SQL;
		$params = [':id' => $this->id, ':fecha_hasta' => $this->fecha_hasta->format('Y-m-d')];
		$res = $mbd->consulta(Conexiones::UPDATE, $sql, $params);
		if (!empty($res) && is_numeric($res) && $res > 0) {
			$resul= true;
			$datos = (array)$this;
			$datos['modelo'] = 'Dependencia';
			Logger::event('baja', $datos);
		}else{
			$resul=false;
			$datos['error_db'] = $mbd->errorInfo;
     		Logger::event("error_baja",$datos);
		}

		return $resul;
	}

	public function modificacion_dep_historica(){
		$sql_params	= [
			':id_dependencia'	=> $this->id,
			':id_padre'	=> (int)$this->id_padre,
			':fecha_hasta'	 => \DateTime::createFromFormat('d/m/Y H:i:s', gmdate('d/m/Y').'0:00:00')->format('Y-m-d'),
		];
		$sql	= 'UPDATE dependencias_historicas SET fecha_hasta = :fecha_hasta WHERE id_dependencia = :id_dependencia AND id_padre = :id_padre';
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Dependencia';
			Logger::event('modificacion_dep_historica', $datos);
		}
		return $res;
	}

	public function alta_dep_historica(){

		$sql_params	= [
			':id_dependencia'	=> $this->id,
			':id_padre'	=> (int)$this->id_padre,
			':fecha_desde'	 => \DateTime::createFromFormat('d/m/Y H:i:s', gmdate('d/m/Y').'0:00:00')->format('Y-m-d'),
		];
		$sql	= 'INSERT INTO dependencias_historicas(id_dependencia, id_padre, fecha_desde) VALUES (:id_dependencia, :id_padre, :fecha_desde)';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Dependencia';
			Logger::event('alta_dep_historica', $datos);
		}
		return $res;
	}

	public function modificacion(){

		$campos	= [
			'nombre',
			'codep',
			'id_padre',
			'fecha_desde',
			'fecha_hasta',
			'nivel',
		];
		$sql_params	= [
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

		$sql	= 'UPDATE dependencias SET '.implode(',', $campos).' WHERE id = :id';
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Dependencia';
			Logger::event('modificacion', $datos);
		}
		return $res;
	}


	static public function getPadre($id){
		$sql_params	= [
			':id'=> $id,

		];

		$sql	= <<<SQL
			SELECT id_dependencia
			FROM dependencias_informales
			WHERE id = :id
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);

		$dependencia = static::obtener($res[0]['id_dependencia']);
		return $dependencia; 
	}

	public function validar() {
        $campos = (array)$this;
        $reglas	= [];
		$nombres= [];

			/*Validación dpendencia según nivel y padre*/
			$reglas		+= [
				'nombre'				=> ['required', 'texto', 'nombreUnico()' => function($input) use ($campos){
					$where	= '';
					$input		= trim($input);
					$sql_params	= [
						':nombre'			=> '%'.$input.'%',
						':nombre_uppercase'	=> '%'.strtoupper($input).'%',
						':nombre_lowercase'	=> '%'.strtolower($input).'%',
					];
					if(!empty($campos['id'])){
						$where				= ' AND id != :id';
						$sql_params[':id']	= $campos['id'];
					}
					$sql		= 'SELECT nombre FROM dependencias WHERE (nombre LIKE :nombre OR nombre LIKE :nombre_uppercase OR nombre LIKE :nombre_lowercase)'.$where;
					$resp	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
					return empty($resp);
				}],
				'nivel'					=> ['required', 'integer'],
				'id_padre'				=> ['required', 'integer','verificarPadres(:nivel)' => function($input, $nivel){
					if($input != 0 && $input != \App\Modelo\Dependencia::FAKE_PADRE_ID){
						$dependencias = \App\Modelo\Dependencia::obtener_dependencias_nivel_superior($nivel);
						return array_key_exists($input,$dependencias);
					}
					return true;
				}, 'verificarUnidadMinistro(:nivel)'	=> function($input, $nivel){
					if($nivel	== \App\Modelo\Dependencia::MINISTRO){
						return empty(\App\Modelo\Dependencia::obtener_nivel_ministro($input));
					}
					return true;
				}],
				'fecha_desde'	=> ['required', 'fecha'],
				'fecha_hasta'	=> ['fecha'],
			];

			if ($this->id_padre == static::FAKE_PADRE_ID && is_null($this->id)) {
				$reglas['id_padre']	= ['verificarUnidadMinistro()' => function($input){
					return empty(\App\Modelo\Dependencia::obtener_nivel_ministro($input));
				}];
			}

			$nombres	+= [
				'nombre'		=> 'Nombre Dependencia',
				'id_padre'		=> 'Dependencia Padre',
				'fecha_desde'	=> 'Fecha Desde',
				'fecha_hasta'	=> 'Fecha Hasta',
				'nivel'			=> 'Nivel',
			];

		$validator	= Validator::validate($campos, $reglas, $nombres);
		$validator->customErrors([
			'verificarPadres'	=> 'La <b> :attribute </b> no corresponde con el nivel seleccionado',
			'verificarUnidadMinistro' => 'Ya existe una depedencia con Nivel Unidad Ministro',
			'verificarUnidadMinistro()' => 'Ya existe una depedencia con Nivel Unidad Ministro',
			'nombreUnico()' => 'Ya existe una dependencia con el mismo nombre, modifique la existente.',
		]);

		if ($validator->isSuccess()) {
			return true;
		}

		$this->errores = $validator->getErrors();
		return false;
	}

	static public function arrayToObject($res = []) {
		$campos	= [
			'id'			=> 'int',
			'nombre'		=> 'string',
			'codep'			=> 'string',
			'id_padre'		=> 'int',
			'fecha_desde'	=> 'date',
			'fecha_hasta'	=> 'date',
			'nivel'		=> 'int',
		];
		$obj = parent::arrayToObject($res, $campos);
		$obj->dependencias_informales	= DependenciaInformal::listar($obj->id);
		// $obj->obtener_dep_informal(); 
		return $obj;
	}

    /********* NUEVO **********/
/**
 * Obtine las dependencias filtradas por Nivel. Devuelve un array preparado para usar en la vista SELECT. 
 *
 * @param int|array $nivel
 * @return array
 */
	static public function obtener_dependencias($nivel=null){
        $where  = '';
	    $Conexiones = new Conexiones();
        if(is_numeric($nivel)){
            $where  = 'nivel = :nivel';
        } else if(is_array($nivel)){
            $where  = 'nivel IN (:nivel)';
        } else {
            return [];
        }
        $query  = <<<SQL
            SELECT
            id, nombre, id_padre, nivel, 0 as  borrado
            FROM dependencias
            WHERE {$where} AND (fecha_hasta is null OR fecha_hasta = '0000-00-00')
            ORDER BY nombre ASC
SQL;
        $resultado = $Conexiones->consulta(Conexiones::SELECT,$query,[':nivel'=>$nivel]);
        $aux=[];
        foreach ($resultado as $value) {
            $aux[$value['id']] = $value;
		}

	    return $aux;
	}

/**
 * Obtiene todas las dependencias con nivel superior al indicado.
 *
 * @param int $nivel
 * @return array
 */
	static public function obtener_dependencias_nivel_superior($nivel=null){
		if($nivel===null){
			return [];
		}

		$query	=
<<<SQL
	    SELECT
	    id, nombre, id_padre, nivel, 0 as  borrado 
	    FROM dependencias
	    WHERE (nivel < :nivel) AND fecha_hasta is null 
	    ORDER BY nombre ASC
SQL;
		$Conexiones = new Conexiones();
		$res = $Conexiones->consulta(Conexiones::SELECT, $query, [
			':nivel'	=> $nivel
		]);

		$aux	= [];
		if(!empty($res[0])){
		    foreach ($res as $value) {
		    	$aux[$value['id']] = $value;
		    }
		}
	    return $aux;
	}

/**
 * Trae todas las dependencias con nivel Ministro, distintas al ID consultado.
 * La razon de existir es para validar que no existan Dependencias con nivel MINISTRO existentes.
 *
 * @param int $id
 * @return array
 */
	static public function obtener_nivel_ministro($id=null){
		if($id === null){
			return [];
		}

		$Conexiones = new Conexiones();
		$query	= 
<<<SQL
		SELECT
		id, nombre, id_padre, nivel, 0 as  borrado 
		FROM dependencias
		WHERE nivel = :nivel AND id_padre = 0 AND (fecha_hasta is null OR fecha_hasta = '0000-00-00') AND id != :id
		ORDER BY id DESC
SQL;
		$res = $Conexiones->consulta(Conexiones::SELECT, $query, [
			':id' => $id,
			':nivel'	=> static::MINISTRO,
		]);

		$aux	= [];
		if(!empty($res[0])){
			foreach ($res as $value) {
				$aux[$value['id']]	= $value;
			}
		}
	    return $aux;
	}

/**
 * Segun el id de la dependencia consultada obtiene el array de dependencias superiores de las cuales depende en el organigrama.
 * Este metodo es recursivo a si mismo
 * @param int $dependencia - Requerido
 * @param null|DateTime|string $fecha - Opcional. Default: null
 * @param array $merge - Usado unicamente para la recursividad.
 * @return array
 */
	static public function obtener_cadena_dependencias($dependencia=null,$fecha=null, $merge=array()){
		$cache	= [];
		$fecha		= empty($fecha)
			? date('Y-m-d') 
			: (($fecha instanceof \DateTime) ? $fecha->format('Y-m-d') : $fecha);
		$cnx		= new Conexiones();
		$sql_params	= [
			':fecha'			=> $fecha,
			':id_dependencia'	=> $dependencia,
			':ministro'			=> self::MINISTRO,
			':secretaria'		=> self::SECRETARIA,
			':subsecretaria'	=> self::SUBSECRETARIA,
			':direccion_general'=> self::DIRECCION_GENERAL,
			':direccion_simple'	=> self::DIRECCION_SIMPLE,
			':coordinacion'		=> self::COORDINACION,
			':unidad_area'		=> self::UNIDAD_O_AREA,
		];
		$sql		= <<<SQL
			SELECT 
				dh.id_dependencia AS item_id,
				dh.id_padre AS id_padre,
				d.nombre AS ubicacion,
				d.nivel,
				case
					when d.nivel = :ministro then "MINISTRO"
					when d.nivel = :secretaria then "SECRETARIA"
					when d.nivel = :subsecretaria then "SUBSECRETARIA"
					when d.nivel = :direccion_general then "DIR_NACIONAL_GENERAL"
					when d.nivel = :direccion_simple then "DIR_SIMPLE"
					when d.nivel = :coordinacion then "COORDINACION"
					when d.nivel = :unidad_area then "UNIDAD_AREA"
				end as nombre_nivel
			FROM dependencias_historicas AS dh
			INNER JOIN dependencias AS d ON (d.id = dh.id_dependencia)
			WHERE dh.fecha_desde <= :fecha AND (
				dh.fecha_hasta IS NULL
				OR (dh.fecha_hasta IS NOT NULL AND dh.fecha_hasta > :fecha)
			) AND dh.id_dependencia = :id_dependencia
			ORDER BY d.nivel DESC
			LIMIT 1
SQL;
		$resp	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($resp[0]['item_id'])){
			if($resp[0]['item_id'] == '1'){
				return $cache[$dependencia] = array_merge($merge, $resp);
			}
			return $cache[$dependencia] = array_merge($merge, static::obtener_cadena_dependencias($resp[0]['id_padre'], $fecha, $resp));
		}
		return [];
	}

/**
 * Se le pase un ID pariente, y se obtienen las decendencias.
 * Este es un metod que se llama a si mismo recursivamente para satisfacer el argumento anterior. Si la fecha no se pasa, se 
 *
 * @param integer $id_padre
 * @param array $lista_hijos
 * @param \DateTime $fecha
 * @return array
 */
	static public function obtener_cadena_dependencias_hijas($id_padre = 0, $fecha = null) {
        $return = [];
		$fecha_format = (is_null($fecha) || !($fecha instanceof \DateTime)) ? date('Y-m-d') : $fecha->format('Y-m-d');
		$sql = <<<SQL
		SELECT
		    d.id,
		    d.nombre,
		    d.id_padre,
		    d.nivel,
		    IF(d.fecha_hasta IS NULL, 0, 1) AS borrado
		FROM
		    dependencias_historicas dh
		INNER JOIN
		    dependencias d ON d.id = dh.id_dependencia
		WHERE
		    dh.id_padre = :id_padre AND (dh.fecha_desde <= :fecha) AND (
                dh.fecha_hasta IS NULL OR (dh.fecha_hasta IS NOT NULL AND dh.fecha_hasta > :fecha)
            )
SQL;
		$Conexiones    = new Conexiones();
        $res        = $Conexiones->consulta(Conexiones::SELECT, $sql,[':id_padre' => $id_padre, ':fecha' => $fecha_format]);
		if (count($res) > 0) {
			foreach ($res as $hijo) {
                $return[$hijo['id']]    = ["id" => $hijo['id'], "id_padre" => $hijo['id_padre'], "name" => $hijo['nombre'], "nivel" => $hijo['nivel'], "borrado" => $hijo['borrado']];
                $recurive_aux           = static::obtener_cadena_dependencias_hijas($hijo['id'], $fecha);
                if(!empty($recurive_aux)){
                    $return += $recurive_aux;
                }
			}
        }
		return $return;
	}

	static public function obtener_niveles_hijos($id_padre = 0) {
		$lista_hijos = self::obtener_cadena_dependencias_hijas($id_padre);
		$id_niveles = [];
		if(!empty($lista_hijos)) {
			$id_niveles = array_unique(array_column($lista_hijos,'nivel'));
		}
		$niveles = [];

		foreach (static::$NIVEL_ORGANIGRAMA as $key => $value) {
			if(in_array($key,$id_niveles)) {
				$niveles['niveles'][$key] = $value;
			}
		}
		return $niveles;
	}

	static public function obtener_dependencias_niveles($id_padre = 0, $niveles = []) {
		$lista_hijos = [];
		if(!empty($niveles)) {
			$lista = self::obtener_cadena_dependencias_hijas($id_padre);
			foreach ($lista as $key => $var) {
				$var['nombre'] = $var['name'];
				unset($var['name']);
				if (isset($var['nivel']) && in_array($var['nivel'], $niveles )) {
					$lista_hijos['dependencias'][$key] = $var;				
				}
			}
		}
		return $lista_hijos;
	}
}
