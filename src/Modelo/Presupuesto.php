<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class Presupuesto extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_saf;
/** @var int */
	public $id_jurisdiccion;
/** @var int */
	public $id_ubicacion_geografica;
/** @var int */
	public $id_programa;
/** @var int */
	public $id_subprograma;
/** @var int */
	public $id_proyecto;
/** @var int */
	public $id_actividad;
/** @var int */
	public $id_obra;


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
			'id_saf',
			'id_jurisdiccion',
			'id_ubicacion_geografica',
			'id_programa',
			'id_subprograma',
			'id_proyecto',
			'id_actividad',
			'id_obra'
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM presupuestos
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
			'id_saf',
			'id_jurisdiccion',
			'id_ubicacion_geografica',
			'id_programa',
			'id_subprograma',
			'id_proyecto',
			'id_actividad',
			'id_obra'
		]);
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM presupuestos
			ORDER BY id DESC
SQL;
		$resp	= (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql);
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
		$campos	= [
			'id_saf',
			'id_jurisdiccion',
			'id_ubicacion_geografica',
			'id_programa',
			'id_subprograma',
			'id_proyecto',
			'id_actividad',
			'id_obra'
		];
		$sql_params	= [
		];
		foreach ($campos as $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
		}

		$sql	= 'INSERT INTO presupuestos('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= (new Conexiones())->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$this->id	= $res;
			$datos = (array) $this;
			$datos['modelo'] = 'Presupuesto';
			Logger::event('alta', $datos);
		}
		return $res;
	}

	public function baja(){
		$cnx	= new Conexiones();
		$sql_params	= [
			':id'		=> $this->id,
		];
		$sql	= 'UPDATE presupuestos SET borrado = 1 WHERE id = :id';
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);

		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'Presupuesto';
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
		if(!$this->validar() || empty($this->id)){
			return false;
		}

		$campos	= [
			'id_saf',
			'id_jurisdiccion',
			'id_ubicacion_geografica',
			'id_programa',
			'id_subprograma',
			'id_proyecto',
			'id_actividad',
			'id_obra'
		];
		$sql_params	= [
			':id'	=> $this->id,
		];
		foreach ($campos as $key => $campo) {
			$sql_params[':'.$campo]	= $this->{$campo};
			unset($campos[$key]);
			$campos[$campo]	= $campo .' = :'.$campo;
		}

		$sql	= 'UPDATE presupuestos SET '.implode(',', $campos).' WHERE id = :id';
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Presupuesto';
			Logger::event('modificacion', $datos);
		}
		return $res;
	}

	public function validar() {
		$campos = (array)$this;
		$reglas		= [
			'id' 						=> ['integer'],
			'id_programa'				=> ['integer'],
			'id_subprograma'			=> ['integer'],
			'id_proyecto'				=> ['integer'],
			'id_actividad'				=> ['integer'],
			'id_obra'					=> ['integer'],
			'id_ubicacion_geografica'	=> ['required', 'integer'],
			'id_jurisdiccion'			=> ['required', 'integer'],
			'id_saf'					=> ['required', 'integer', 'presupuesto_unico(:id,:id_programa,:id_subprograma,:id_proyecto,:id_actividad,:id_obra,:id_ubicacion_geografica,:id_jurisdiccion)' => function($input,$id,$id_programa,$id_subprograma,$id_proyecto,$id_actividad,$id_obra,$id_ubicacion_geografica,$id_jurisdiccion){

				$params = [
					':id_programa'				=> $id_programa,
					':id_subprograma'			=> $id_subprograma,
					':id_proyecto'				=> $id_proyecto,
					':id_actividad'				=> $id_actividad,
					':id_obra'					=> $id_obra,
					':id_ubicacion_geografica'	=> $id_ubicacion_geografica,
					':id_jurisdiccion'			=> $id_jurisdiccion,
					':id_saf'					=> $input,
					':id'						=> $id,
				];

				$where_id = '';
				if($id){
					$where_id = " AND id != :id";
					$params[':id'] = $id;
				} else {
					unset($params[':id']);
				}

				$sql = <<<SQL
							SELECT count(*) count FROM presupuestos WHERE 'id_programa' = :id_programa AND 'id_subprograma' = :id_subprograma AND 'id_proyecto' = :id_proyecto AND 'id_actividad' = :id_actividad AND 'id_obra' = :id_obra AND 'id_ubicacion_geografica' = :id_ubicacion_geografica AND 'id_jurisdiccion' = :id_jurisdiccion AND 'id_saf' = :id_saf $where_id;
SQL;
				$con = new Conexiones();
				$res = $con->consulta(Conexiones::SELECT, $sql, $params);
				if (is_array($res) && isset($res[0]) && isset($res[0]['count'])) {
					return ($res[0]['count'] == 0);
				}
				return false;
			} ],
			'borrado' 	=> ['numeric']
		];
		$nombres	= [
			'id_proyecto'				=> 'Proyecto',
			'id_subprograma'			=> 'SubPrograma',
			'id_programa'				=> 'Programa', 
			'id_actividad'				=> 'Acividad',
			'id_obra'					=> 'Obras',
			'id_ubicacion_geografica'	=> 'Ubicación Geografica',
			'id_jurisdiccion' 			=> 'Jurisdicción',
			'id_saf' 					=> 'Servicio Administrativo Financiero'
		];
		$validator	= Validator::validate($campos, $reglas, $nombres);
		$validator->customErrors([
            'presupuesto_unico'      => 'Ya existe un Presupuesto con los mismos valores configurados.',
        ]);
		if ($validator->isSuccess()) {
			return true;
		}
		$this->errores = $validator->getErrors(); 
		return false;
	}
	// 	$this->errores = ['No puede registrar un formulario vacío, por favor verifique'];
	// 	return $aux;
	// }

	static public function arrayToObject($res = []) {
		$campos	= [
			'id'					  => 'int',
			'id_saf'				  => 'int',
			'id_jurisdiccion'		  => 'int',
			'id_ubicacion_geografica' => 'int',
			'id_programa'			  => 'int',
			'id_subprograma'		  => 'int',
			'id_proyecto'			  => 'int',
			'id_actividad'			  => 'int',
			'id_obra'				  => 'int',

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
					$obj->{$campo}	= isset($res[$campo]) ? \DateTime::createFromFormat('Y-m-d', $res[$campo]) : null;
					break;
				default:
					$obj->{$campo}	= isset($res[$campo]) ? $res[$campo] : null;
					break;
			}
		}
		return $obj;
	}

	static public function getSaf(){
		$campos	= implode(',', [
			'codigo',
			'nombre',
			'borrado'
		]);
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM presupuesto_saf
			ORDER BY id ASC
SQL;

		$resp = (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql); 

		$aux	= [];
		foreach ($resp as $value) {
			$value['nombre'] = "{$value['codigo']} - {$value['nombre']}";
			$aux[$value['id']] = $value;
		}
		return $aux;
	}

	static public function getJurisdiccion(){
		$campos	= implode(',', [
			'codigo',
			'nombre',
			'borrado'
		]);
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM presupuesto_jurisdicciones
			ORDER BY id ASC
SQL;

		$resp = (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql); 

		$aux	= [];
		foreach ($resp as $value) {
			$value['nombre'] = "{$value['codigo']} - {$value['nombre']}";	
			$aux[$value['id']] = $value;
		}
		return $aux;
	}

	static public function getUbicacionesGeograficas(){
		$campos	= implode(',', [
			'codigo',
			'nombre',
			'borrado'
		]);
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM presupuesto_ubicaciones_geograficas
			ORDER BY id ASC
SQL;

		$resp = (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql); 
		$aux	= [];
		foreach ($resp as $value) {
			$value['nombre'] = "{$value['codigo']} - {$value['nombre']}";			
			$aux[$value['id']] = $value;
		}
		return $aux;
	}


	static public function getProgramas(){
		$campos	= implode(',', [
			'nombre',
			'codigo',
			'borrado'
		]);
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM presupuesto_programas
			ORDER BY id ASC
SQL;
		$resp = (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql); 
		$aux	= [];
		foreach ($resp as $value) {
			$value['nombre'] = "{$value['codigo']} - {$value['nombre']}";
			$aux[$value['id']] = $value;
		}
		return $aux;
	}


	static public function getProyectos($subprograma){
		$aux= [];
		$sql_params	= [
			':id_subprograma'=> $subprograma,

		];
		$campos	= implode(',', [
			'id',
			'codigo',
			'nombre',
			'borrado'
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM presupuesto_proyectos
			WHERE id_subprograma = :id_subprograma 
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		foreach ($res as $value) {
			$value['nombre'] = "{$value['codigo']} - {$value['nombre']}";
			$aux[$value['id']] = $value;
		}
		return $aux;
	}
	

	static public function getActividades(){
		$campos	= implode(',', [
			'codigo',
			'nombre',
			'borrado'
		]);
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM presupuesto_actividades
			ORDER BY id ASC
SQL;
		$resp = (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql); 
		$aux	= [];
		foreach ($resp as $value) {
			$value['nombre'] = "{$value['codigo']} - {$value['nombre']}";
			$aux[$value['id']] = $value;
		}
			return $aux;
	}

	static public function getObras($proyecto){
		$aux= [];
		$sql_params	= [
			':id_proyecto'=> $proyecto,

		];
		$campos	= implode(',', [
			'id',
			'codigo',
			'nombre',
			'borrado'
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM presupuesto_obras
			WHERE id_proyecto = :id_proyecto 
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		foreach ($res as $value) {
			$value['nombre'] = "{$value['codigo']} - {$value['nombre']}";
			$aux[$value['id']] = $value;
		}
		return $aux;
	}



	public function obtener_presupuesto(){
		$obj	= new static;

		$campos =
		$where  = '';
		$aux = get_object_vars($obj);
		unset($aux['errores']);
		unset($aux['campos']);
		unset($aux['id']);
		foreach ($aux as $propiedad => $value) {
			$campos	.= ($campos == '') ? $propiedad : ','.$propiedad;
			if($this->{$propiedad}) {				
				$sql_params[":{$propiedad}"] = $this->{$propiedad};
				$where  .= ($where == '') ? "{$propiedad} = :{$propiedad}" : " AND {$propiedad} = :{$propiedad}";
			}else{
				$where  .= ($where == '') ? "ISNULL({$propiedad})" : " AND ISNULL({$propiedad})";				
			}
		}
		$sql	= <<<SQL
			SELECT id, {$campos}
			FROM presupuestos
			WHERE {$where}
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){

			$this->id = $res[0]['id'];
			return true;
		}
		return false;
	}


	static public function getSubProgramas($programa){
		$aux= [];
		$sql_params	= [
			':id_programa'			   => $programa,

		];
		$campos	= implode(',', [
			'id',
			'codigo',
			'nombre',
			'borrado'
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM presupuesto_subprogramas
			WHERE id_programa = :id_programa 
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		foreach ($res as $value) {
			$value['nombre'] = "{$value['codigo']} - {$value['nombre']}";
			$aux[$value['id']] = $value;
		}
		return $aux;
	}

	public static function listadoPresupuestos($params=array(), $count = false) {
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

		$sql= <<<SQL
			SELECT 
		    p.id,
		    IF(ISNULL(ps.codigo),'  --', concat(ps.codigo,' - ',ps.nombre)) AS saf,
		    IF(ISNULL(pj.codigo),'  --', concat(pj.codigo,' - ',pj.nombre)) AS jurisdicciones,
		    IF(ISNULL(pg.codigo),'  --', concat(pg.codigo,' - ',pg.nombre)) AS ub_geograficas,
		    IF(ISNULL(pp.codigo),'  --', concat(pp.codigo,' - ',pp.nombre)) AS programas,
		    IF(ISNULL(psp.codigo),'  --', concat(psp.codigo,' - ',psp.nombre)) AS subprogramas,
		    IF(ISNULL(py.codigo),'  --', concat(py.codigo,' - ',py.nombre)) AS proyectos,
		    IF(ISNULL(pa.codigo),'  --', concat(pa.codigo,' - ',pa.nombre)) AS actividades,
		    IF(ISNULL(po.codigo),'  --', concat(po.codigo,' - ',po.nombre)) AS obras
SQL;

		$from = <<<SQL
			FROM presupuestos p
			LEFT JOIN presupuesto_saf ps ON ps.id = p.id_saf
			LEFT JOIN presupuesto_jurisdicciones pj ON pj.id = p.id_jurisdiccion
			LEFT JOIN presupuesto_ubicaciones_geograficas pg ON pg.id = p.id_ubicacion_geografica
			LEFT JOIN presupuesto_programas pp ON pp.id = p.id_programa
			LEFT JOIN presupuesto_subprogramas psp ON psp.id = p.id_subprograma
			LEFT JOIN presupuesto_proyectos py ON py.id = p.id_proyecto
			LEFT JOIN presupuesto_actividades pa ON pa.id = p.id_actividad
			LEFT JOIN presupuesto_obras po ON po.id = p.id_obra
SQL;

		$order =
 		<<<SQL
 			ORDER BY 
SQL;
 		$condicion =
 		<<<SQL
 			WHERE p.borrado = 0 
SQL;

    $counter_query	= "SELECT COUNT(DISTINCT p.id) AS total {$from}";

	$recordsTotal	=  $cnx->consulta(Conexiones::SELECT, $counter_query . $condicion, $sql_params)[0]['total'];

	if(!empty($params['search'])){
		$indice = 0;
		$search[]	= <<<SQL
		(ps.nombre LIKE :search{$indice} OR
		 pj.nombre LIKE :search{$indice} OR
		 pg.nombre LIKE :search{$indice} OR
		 pp.nombre LIKE :search{$indice} OR
		 ps.nombre LIKE :search{$indice} OR
		 py.nombre LIKE :search{$indice} OR
		 pa.nombre LIKE :search{$indice} OR
		 po.nombre LIKE :search{$indice}) 
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

	$order 	.=  implode(',', $orderna);

	/**Limit: funcionalidad: desde-hasta donde se pagina */
	$limit = (isset($params['lenght']) && isset($params['start']))
						? " LIMIT  {$params['start']}, {$params['lenght']}"	:	' ';

	$recordsFiltered= $cnx->consulta(Conexiones::SELECT, $counter_query. $condicion,  $sql_params)[0]['total'];

	$lista = $cnx->consulta(Conexiones::SELECT,  $sql .$from. $condicion . $order . $limit, $sql_params);

		if($lista){
			foreach ($lista as $key => &$value) {
				$value	= (object)$value;

			}

		}
		return [
			'recordsTotal'    => !empty($recordsTotal) ? $recordsTotal : 0,
			'recordsFiltered' => !empty($recordsFiltered) ? $recordsFiltered : 0,
			'data'            => $lista ? $lista : [],
		];
	}
}