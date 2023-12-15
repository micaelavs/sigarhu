<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Conexiones;
use App\Helper\Bloques;

class Auditoria extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_usuario;
/** @var DateTime */
	public $fecha_operacion;
/** @var string */
	public $tipo_operacion;
/** @var int */
	public $id_tabla;
/** @var string */
	public $tabla_nombre;
/** @var int */
	public $id_empleado;
/** @var string */
	public $empleado_nombre;
/** @var int */
	public $empleado_cuit;
/** @var string */
	public $usuario_nombre;
/** @var string */
	public $tipo_registro;
/**
 * Obtener los montos y unidades retributivas para la funcion y nivel de modalidades 1109
 *
 * @param int $id
 * @return object Auditoria::
 */
	static public function obtener($id=null){
		return static::arrayToObject();
	}

/**
 * Listado de objetos Auditoria::
 * @return array [Auditoria::]
 */
	static public function listar(){
		$cnx		= new Conexiones('db_historial');
		$filtros	= static::setFiltro();
		$sql_params	= [];
		$limit		= '';
		$where		= '';
		if(empty($filtros) || (empty($filtros['fecha_desde']) && empty($filtros['fecha_hasta'])) ){
			$limit = ' LIMIT 10';
		} else {
			$sql_params	+= [
				':fecha_desde'	=> ($filtros['fecha_desde'] instanceof \DateTime) 
					? $filtros['fecha_desde']->format('Y-m-d') : $filtros['fecha_desde'],
				':fecha_hasta'	=> ($filtros['fecha_hasta'] instanceof \DateTime) 
					? $filtros['fecha_hasta']->format('Y-m-d') : $filtros['fecha_hasta'],
			];
			$where		= 'WHERE fecha_operacion BETWEEN :fecha_desde AND :fecha_hasta';
		}

		$sql		= <<<SQL
			SELECT id,
				id_usuario,
				fecha_operacion,
				tipo_operacion,
				id_tabla,
				tabla_nombre,
				id_empleado,
				empleado_nombre,
				empleado_cuit,
				'' AS usuario_nombre,
				tipo_registro
			FROM _vt_registros_historial {$where} {$limit}
SQL;

		$res		= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(empty($res)){
			return [];
		}
		foreach ($res as &$value) {
			$value	= static::arrayToObject($value);
		}
		return $res;
	}

/**
 * Metodo usado para consultas por API. Se puede consultar ultima fehca de modificacion de agente por CUIT u obtener los CUITs modificados en un rango de fechas.
 * @param array $params  ```[
    'cuit'			=> null|string,
    'fecha_desde'	=> null|DateTime,
    'fecha_hasta'	=> null|DateTime,
]```
 * @return array
 */
	static public function apiBuscarActualizaciones($params=array()){
		$params_default	= [
			'cuit'			=> null,
			'fecha_desde'	=> null,
			'fecha_hasta'	=> null,
		];
		$params	= array_merge($params_default, $params);

		$sql_params	= [];
		if(!empty($params['cuit'])){
			$sql_params	+= [
				':cuit'	=> $params['cuit'],
			];
			$sql	= 'SELECT fecha_operacion FROM _vt_registros_historial WHERE tipo_registro = \'legajo\' AND empleado_cuit = :cuit ORDER BY fecha_operacion DESC LIMIT 1';
		} else if(!empty($params['fecha_desde']) && empty($params['fecha_hasta'])){
			$sql_params	+= [
				':fecha_desde'	=> ($params['fecha_desde'] instanceof \DateTime)
					? $params['fecha_desde']->format('Y-m-d H:i:s') : $params['fecha_desde'],
			];
			$sql	= 'SELECT empleado_cuit ';
			$sql	.= ' FROM _vt_registros_historial WHERE tipo_registro = \'legajo\' AND fecha_operacion >= :fecha_desde GROUP BY id_empleado ORDER BY fecha_operacion DESC';
		} else if(!empty($params['fecha_desde']) && !empty($params['fecha_hasta'])){
			$sql_params	+= [
				':fecha_desde'	=> ($params['fecha_desde'] instanceof \DateTime)
					? $params['fecha_desde']->format('Y-m-d') : $params['fecha_desde'],
				':fecha_hasta'	=> ($params['fecha_hasta'] instanceof \DateTime)
					? $params['fecha_hasta']->format('Y-m-d') : $params['fecha_hasta'],
			];
			$sql	= 'SELECT empleado_cuit ';
			$sql	.= ' FROM _vt_registros_historial WHERE tipo_registro = \'legajo\' AND fecha_operacion BETWEEN :fecha_desde AND :fecha_hasta GROUP BY id_empleado ORDER BY fecha_operacion DESC';
		} else {
			return [];
		}

		$cnx		= new Conexiones('db_historial');
        $res		= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);

        $aux    = [];
		if(empty($res)){
			return [];
        }
        if(isset($res[0]) && isset($res[0]['fecha_operacion'])){
            $aux    = [
                'tipo'						=> 'modificacion',
                'agente'					=> $params['cuit'],
                'fecha_ultima_modificacion'	=> $res[0]['fecha_operacion'],
            ];
        }else if(isset($res[0]) && isset($res[0]['empleado_cuit'])){
            $aux    = [
                'tipo'				=> 'agentes',
                'fecha_desde'		=> ($params['fecha_desde'] instanceof \DateTime) ? $params['fecha_desde']->format('Y-m-d H:i:s') : null,
                'fecha_hasta'		=> ($params['fecha_hasta'] instanceof \DateTime) ? $params['fecha_hasta']->format('Y-m-d H:i:s') : null,
                'cuits'	            => [],
            ];
            foreach ($res as $_res) {
                $aux['cuits'][] = $_res['empleado_cuit'];
            }
        }

		return $aux;
	}

	public function validar(){ return true; }
	public function alta(){ return false; }
	public function modificacion(){ return false; }
	public function baja(){ return false; }

/**
 * Convierte un array en objetos.
 *
 * @param array $res
 * @return object
 */
	static public function arrayToObject($res = []) {
		$campos	= [
			'id'				=> 'int',
			'id_usuario'		=> 'int',
			'fecha_operacion'	=> 'datetime',
			'tipo_operacion'	=> 'string',
			'id_tabla'			=> 'int',
			'tabla_nombre'		=> 'string',
			'id_empleado'		=> 'int',
			'empleado_nombre'	=> 'string',
			'usuario_nombre'	=> 'string',
			'empleado_cuit'		=> 'int',
			'tipo_registro'		=> 'string',
		];
		$obj	= parent::arrayToObject($res, $campos);
		$usuario= Usuario::obtener($obj->id_usuario);
		$obj->usuario_nombre	= $usuario->nombre.' '.$usuario->apellido;
		return $obj;
	}

/**
 * Sirve para almacenar filtros adicionales que luego seran usados en las consultas realizadas por `::obtener` o `::listar`.
 * Si no se pasan parametros, funciona como Getter y limpia los filtros.
 *
 * @param array|string $campo	- Puede ser el string del filtro a usar o un array con su conjunto `clave => valor`
 * @param boolean $valor		- Valor del filtro
 * @return array|bool
 */
	static public function setFiltro($campo=false, $valor=false){
		static $FILTRO	=  [];
		if($campo === false && $valor === false){
			$tmp	= $FILTRO;
			$FILTRO	= [];
			return $tmp;
		}
		if(!is_string($campo) && !is_array($campo)){
			return false;
		}
		if(is_string($campo)){
			$FILTRO[$campo]	= $valor;
		}
		if(is_array($campo)){
			$FILTRO	= array_merge($FILTRO, $campo);
		}
	}

/**
 * Recibe parametros de un ajax realizados por DataTable
 *
 * @param array $params
 * @param boolean $count
 * @return array
 */
	public static function ajaxPesquisa($params=array(), $count = false) {
		$cnx		= new Conexiones('db_historial');

		$where		= [];
		$sql_params	= [];
		$condicion	= '';

		$default_params	= [
			'order'		=> [
				[
					'campo'	=> 'fecha_operacion',
					'dir'	=> 'desc',
				],
			],
			'start'		=> 0,
			'lenght'	=> 10,
			'search'	=> '',
			'filtros'	=> [
				'fecha_operacion_desde'	=> null,
				'fecha_operacion_hasta'	=> null,
				'id_usuario'			=> null,
			],
			'count'		=> false,
		];

		$params				= array_merge($default_params, $params);
		$params['filtros']	= array_merge($default_params['filtros'], $params['filtros']);
		$params['order']	= array_merge($default_params['order'], $params['order']);
		$sql				= <<<SQL
			SELECT
				id,
				id_usuario,
				fecha_operacion,
				tipo_operacion,
				id_tabla,
				tabla_nombre,
				id_empleado,
				empleado_nombre,
				empleado_cuit,
				'' AS usuario_nombre,
				tipo_registro
			FROM _vt_registros_historial
SQL;
		$from		= '';
		$order		= ' ORDER BY fecha_operacion DESC ';
		$condicion	= !empty($where) ? ' WHERE ' . \implode(' AND ',$where) : '';

		/** Total de registros de Empleados */
		$counter_query	= 'SELECT COUNT(id) AS total FROM _vt_registros_historial';
		$recordsTotal	=  $cnx->consulta(Conexiones::SELECT, $counter_query.' ORDER BY fecha_operacion DESC' , [])[0]['total'];

		/** Buscar Elementos */
		if(!empty($params['search'])){
			$search	= [];
			foreach (explode(' ', (string)$params['search']) as $indice => $texto) {
				$search[]	= <<<SQL
					(
					empleado_cuit LIKE :search{$indice} OR
					empleado_nombre LIKE :search{$indice}
					)
SQL;
				$sql_params[":search{$indice}"]	= "%{$texto}%";
			}
			$buscar =  implode(' AND ', $search);
			$condicion .= empty($condicion) ? " WHERE {$buscar}" : " AND {$buscar} ";
		}

		if(
			!empty($params['filtros']['fecha_operacion_desde'])
			|| !empty($params['filtros']['fecha_operacion_hasta'])
		){
			$fecha_operacion_desde = $params['filtros']['fecha_operacion_desde'];
			$fecha_operacion_hasta = $params['filtros']['fecha_operacion_hasta'];
			if(!empty($fecha_operacion_desde) && !empty($fecha_operacion_hasta)){
				$sql_params[':desde']	= ($fecha_operacion_desde instanceof \DateTime) ? $fecha_operacion_desde->format('Y-m-d H:i:s') : $fecha_operacion_desde;
				$sql_params[':hasta']	= ($fecha_operacion_hasta instanceof \DateTime) ? $fecha_operacion_hasta->format('Y-m-d H:i:s') : $fecha_operacion_hasta;
				$fecha_where			= ' fecha_operacion BETWEEN :desde AND :hasta ';
			} else {
				if($fecha_operacion_desde){
					$sql_params[':desde']	= ($fecha_operacion_desde instanceof \DateTime) ? $fecha_operacion_desde->format('Y-m-d H:i:s') : $fecha_operacion_desde;
					$fecha_where			= ' fecha_operacion > :desde ';
				} elseif($fecha_operacion_hasta){
					$sql_params[':hasta']	= ($fecha_operacion_hasta instanceof \DateTime) ? $fecha_operacion_hasta->format('Y-m-d H:i:s') : $fecha_operacion_hasta;
					$fecha_where			= ' fecha_operacion < :hasta ';
				}
			}
			$condicion .= empty($condicion) ? " WHERE {$fecha_where}" : " AND {$fecha_where} ";
		}
		if( !empty($id_usuario = $params['filtros']['id_usuario']) ){
			$sql_params[':id_usuario']	= $id_usuario;
			$usuario_where				= ' id_usuario = :id_usuario ';
			$condicion .= empty($condicion) ? " WHERE {$usuario_where}" : " AND {$usuario_where} ";
		}

		/**Orden de las columnas */
		$orderna			= [];
		foreach ($params['order'] as $i => $val) {
			if(!empty($val['campo']) && !empty($val['dir'])){
				$ordenamiento	= ($val['campo'] == 'nombre') ? 'p.' : '';
			}

			$orderna[]	= "{$ordenamiento}{$val['campo']} {$val['dir']}";
		}
		if(count($orderna)>=1) {
			$order 	=  $order. ', ' .implode(',', $orderna);
		}

		/**Limit: funcionalidad: desde-hasta donde se pagina */
		$limit			= (isset($params['lenght']) && isset($params['start']))
			? " LIMIT  {$params['start']}, {$params['lenght']}"	:	' ';

		$recordsFiltered= $cnx->consulta(Conexiones::SELECT, $counter_query . $condicion, $sql_params)[0]['total'];
		$lista			= $cnx->consulta(Conexiones::SELECT, $sql .$from. $condicion . $order . $limit, $sql_params);
		if($lista){
			foreach ($lista as $key => &$value) {
				if(isset($value['fecha_operacion'])){
					$value['fecha_operacion'] = \DateTime::createFromFormat('Y-m-d H:i:s',$value['fecha_operacion'])->format('d/m/Y H:i:s');
				}
				if(isset($value['tipo_registro'])){
					$value['tipo_registro']	= ($value['tipo_registro'] == 'abm') ? 'Parametrico' : 'Legajo';
				}
				if(isset($value['tabla_registro'])){
					explode(',',$value['tabla_registro']);
					$value['tabla_registro']	= $value['tabla_registro'];
				}
				$usuario= Usuario::obtener($value['id_usuario']);
				if(empty($usuario->nombre)){
					$value['usuario_nombre']	= 'Sistema';
				} else {
					$value['usuario_nombre']	= $usuario->nombre.' '.$usuario->apellido;;
				}
				foreach ($value as &$val2) {
					if(empty($val2) && !is_numeric($val2)){$val2='--';}
				}
			}
		}

		return [
			'recordsTotal'    => !empty($recordsTotal) ? $recordsTotal : 0,
			'recordsFiltered' => !empty($recordsFiltered) ? $recordsFiltered : 0,
			'data'            => $lista ? $lista : [],
		];
	}
/**
 * Analiza columna por columna para mapear los campos usando la estructura Auditoria::$STRUCTURE
 * Realiza las comparaciones si es que se pueden hacer.
 * ----------------------------------------------------
 * Ejemplo de respuesta
 ```
 $return   = [
    'consulta'   => [ 0 => [
        'solapa'     => int|string,
        'titulo'     => string,
        'valor'      => string,
        'flag'       => string|null,
        'map'        => string|null,
    ]],
    'anterior'   => [],
];
 ```
 * @param string	$tabla_nombre
 * @param array		$array_consulta
 * @param array		$array_anterior (Opcional)
 * @return array
 */
	static private function mapearCampos($tabla_nombre=null, $array_consulta=[], $array_anterior=null){
		$return	= [
			'consulta'	=> [],
			'anterior'	=> [],
		];
		$i	= 0;
		foreach ($array_consulta as $campo => $valor) {
			if(empty(static::$STRUCTURE[$tabla_nombre][$campo])){
				$return['consulta'][$i]	= [
					'valor'		=> ($array_consulta[$campo] === null) ? '--' : $array_consulta[$campo],
					'flag'		=> 'hidden',
					'campo'		=> $campo,
				];
				$return['anterior'][$i]	= [
					'valor'		=> ($array_anterior[$campo] === null) ? '--' : $array_anterior[$campo],
					'flag'		=> 'hidden',
					'campo'		=> $campo,
				];
				$i++;
				continue;
			}
			if(!empty($tmp	= \DateTime::createFromFormat('Y-m-d H:i:s', $valor.' 0:00:00'))){
				$array_consulta[$campo]	= $tmp->format('d/m/Y');
			} elseif(!empty($tmp = \DateTime::createFromFormat('Y-m-d H:i:s', $valor))){
				$array_consulta[$campo]	= $tmp->format('d/m/Y H:i:s');
			}
			if(is_array($array_anterior) && $array_consulta[$campo] !== $array_anterior[$campo]){
				if(!empty($tmp	= \DateTime::createFromFormat('Y-m-d H:i:s', $array_anterior[$campo].' 0:00:00'))){
					$array_anterior[$campo]	= $tmp->format('d/m/Y');
				} elseif(!empty($tmp = \DateTime::createFromFormat('Y-m-d H:i:s', $array_anterior[$campo]))){
					$array_anterior[$campo]	= $tmp->format('d/m/Y H:i:s');
				}
			}

			static::estructura_dinamica($tabla_nombre,$campo, $i, $array_consulta, $array_anterior,$return);

			$i++;
		}

		return $return;
	}

		static protected function estructura_dinamica($tabla_nombre,$campo, $i, $array_consulta, $array_anterior,&$return){

			$estructura	= static::$STRUCTURE[$tabla_nombre][$campo];

			switch ($tabla_nombre) {
							case 'empleado_ultimos_cambios':
								if($array_consulta['id_tipo'] == EmpleadoUltimosCambios::GRADO) {
									$estructura['solapa'] = Bloques::ANTIGUEDAD;
									$estructura['etiqueta'] =' Fecha Otorgamiento Grado';

								}else{
									$estructura['solapa'] = Bloques::SITUACION_ESCALAFONARIA;
									$estructura['etiqueta'] = 'Último cambio de nivel';

								}
								break;
							default:
								# code...
								break;
						}


			$return['consulta'][$i]	= [
				'solapa'	=> $estructura['solapa'],
				'titulo'	=> $estructura['etiqueta'],
				'valor'		=> ($array_consulta[$campo] === null) ? '--' : $array_consulta[$campo],
				'flag'		=> null,
				'map'		=> (isset($estructura['map']) && !empty($tmp = $estructura['map'])) ? $tmp : null,
				'campo'		=> $campo,
			];
			if(is_array($array_anterior) && $array_consulta[$campo] !== $array_anterior[$campo]){
                $return['consulta'][$i]['flag']	= 'modificado';
				$return['anterior'][$i]	= [
					'solapa'	=> $estructura['solapa'],
					'titulo'	=> $estructura['etiqueta'],
					'valor'		=> $array_anterior[$campo],
					'flag'		=> null,
					'map'		=> (isset($estructura['map']) && !empty($tmp = $estructura['map'])) ? $tmp : null,
					'campo'		=> $campo,
				];
			}
		}
/**
 * En base a los resultados de Auditoria::ajaxPesquisa() realiza dentro de la base de datos HIstorial la consulta de la tabla especificada con el id especificado.
 * Si la operacion es diferente de una "Alta", intentara obtener el registro anterior para comparar los cambios nuevos con los anteriores, correspondientes a ese registro historico.
 * Los resultado son mapeados por Auditoria::mapearCampos()
 *
 * @param string $tabla_nombre
 * @param int	 $id_tabla
 * @return array
 */
	static public function queryComparacion($tabla_nombre=null, $id_tabla=null){
		if(empty($tabla_nombre) || empty($id_tabla) || !array_key_exists($tabla_nombre, static::$STRUCTURE) ){
			return [];
		}
        $cnx		= new Conexiones('db_historial');
		$sql_params	= [
			':id_tabla'		=> $id_tabla,
		];
		$sql = <<<SQL
		SELECT * FROM {$tabla_nombre} WHERE id = :id_tabla ORDER BY id DESC LIMIT 1
SQL;
		$res_consulta	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(empty($res_consulta[0])){
			return [];
		}

		$sql_params	= [
			':id_tabla'			=> $id_tabla,
			':val_campo_where'	=> $res_consulta[0]['id_'.$tabla_nombre],
		];

		$sql = <<<SQL
		SELECT * FROM {$tabla_nombre} WHERE id_{$tabla_nombre} = :val_campo_where AND id != :id_tabla  
		AND fecha_operacion <= (SELECT fecha_operacion FROM {$tabla_nombre} WHERE id_{$tabla_nombre} = :val_campo_where AND id = :id_tabla)  
		ORDER BY fecha_operacion DESC LIMIT 1
SQL;
        $res_anterior	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);

		if(empty($res_anterior[0])){
			return static::mapearCampos($tabla_nombre, $res_consulta[0]);
		}
		return static::mapearCampos($tabla_nombre, $res_consulta[0], $res_anterior[0]);
	}

/**
 * Mapeo de los capos de las tablas de legajo en la base de historial.
 * Campo que no este, o tabla que no este, es dato que no se muestra. El metodo Auditoria::mapearCampos() se encarga de interactuar con esta estructura.
```
[
'tabla_nombre'	=> [
	'columna	=> [
		'solapa'	=> int|string,
		'etiqueta'	=> string,
		'map'		=> string, // (opcional) string con prijo 'map_' o 'string' o json
	]
]
]
```
 * Los mapeos con prefijo 'map_' son procesados por el controlador, los que no son representaciones de parametros con formato JSON, que seran usados para hacer el correspondiente reemplazo del lado cliente, al iguak que los JSON cargados en aqui.
 * @var array
 */
	static private $STRUCTURE	= [
		'anticorrupcion'	=> [
			'id_anticorrupcion'				=> ['solapa'=>Bloques::ANTICORRUPCION, 'etiqueta' => 'Id Registro', 'map'],
			'fecha_designacion'				=> ['solapa'=>Bloques::ANTICORRUPCION, 'etiqueta'=>'Fecha de Designación'],
			'fecha_publicacion_designacion'	=> ['solapa'=>Bloques::ANTICORRUPCION, 'etiqueta'=>'Fecha Publicación Designación'],
			'fecha_aceptacion_renuncia'		=> ['solapa'=>Bloques::ANTICORRUPCION, 'etiqueta'=>'Fecha Aceptación Renuncia'],
		],
		'anticorrupcion_presentacion'	=> [
			'id_anticorrupcion_presentacion' => ['solapa'=>Bloques::ANTICORRUPCION, 'etiqueta'=> 'Id Registro'],
			'tipo_presentacion'		=> ['solapa'=>Bloques::ANTICORRUPCION, 'etiqueta'=>'Tipo Declaración Jurada', 'map'=>'tipo_presentacion'],
			'fecha_presentacion'	=> ['solapa'=>Bloques::ANTICORRUPCION, 'etiqueta'=>'Fecha Presentación'],
			'periodo'				=> ['solapa'=>Bloques::ANTICORRUPCION, 'etiqueta'=>'Periodo Presentado'],
			'nro_transaccion'		=> ['solapa'=>Bloques::ANTICORRUPCION, 'etiqueta'=>'Nº Transacción'],
			'archivo'				=> ['solapa'=>Bloques::ANTICORRUPCION, 'etiqueta'=>'Comprobante'],
		],
		'embargos'	=> [
			'id_embargos'		=> ['solapa'=>Bloques::EMBARGO, 'etiqueta' => 'Id Registro'],			
			'tipo_embargo'		=> ['solapa'=>Bloques::EMBARGO, 'etiqueta'=>'Tipo Embargo', 'map'=>'map_tipo_embargo'],
			'autos'				=> ['solapa'=>Bloques::EMBARGO, 'etiqueta'=>'Autos'],
			'fecha_alta'		=> ['solapa'=>Bloques::EMBARGO, 'etiqueta'=>'Fecha Alta'],
			'fecha_cancelacion'	=> ['solapa'=>Bloques::EMBARGO, 'etiqueta'=>'Fecha Cancelación'],
			'monto'				=> ['solapa'=>Bloques::EMBARGO, 'etiqueta'=>'Monto'],
		],
		'empleado_comision'	=> [
			'id_empleado_comision'	=> ['solapa'=>Bloques::ADMINISTRACION, 'etiqueta' =>'Id Registro'],			
			'id_comision_origen'	=> ['solapa'=>Bloques::ADMINISTRACION, 'etiqueta'=>'Organismo de Origen', 'map'=>'comisiones'],
			'id_comision_destino'	=> ['solapa'=>Bloques::ADMINISTRACION, 'etiqueta'=>'Organismo de Destino', 'map'=>'comisiones'],
			'fecha_inicio'			=> ['solapa'=>Bloques::ADMINISTRACION, 'etiqueta'=>'Fecha Inicio'],
			'fecha_fin'				=> ['solapa'=>Bloques::ADMINISTRACION, 'etiqueta'=>'Fecha Fin'],
		],
		'empleado_dependencia'	=> [
			'id_empleado_dependencia'	=> ['solapa' => Bloques::UBICACION_ESTRUCTURA, 'etiqueta' => 'Id Registro'],
			'id_dependencia'	=> ['solapa'=>Bloques::UBICACION_ESTRUCTURA, 'etiqueta'=>'Dependencia', 'map'=>'map_id_dependencia'],
			'fecha_desde'		=> ['solapa'=>Bloques::ANTIGUEDAD, 'etiqueta'=>'Fecha Ingreso MTR'],
			'fecha_hasta'		=> ['solapa'=>Bloques::UBICACION_ESTRUCTURA, 'etiqueta'=>'Fecha Baja'],
		],
		'empleado_dep_informales'	=> [
			'id_empleado_dep_informales'	=> ['solapa' =>Bloques::UBICACION_ESTRUCTURA, 'etiqueta' => 'Id Registro'],
			'id_dep_informal'	=> ['solapa'=>Bloques::UBICACION_ESTRUCTURA, 'etiqueta'=>'Dependencia Informal', 'map'=> 'map_id_dep_informal'],
			'fecha_desde'		=> ['solapa'=>Bloques::UBICACION_ESTRUCTURA, 'etiqueta'=>'Fecha Alta'],
			'fecha_hasta'		=> ['solapa'=>Bloques::UBICACION_ESTRUCTURA, 'etiqueta'=>'Fecha Baja'],
		],
		'empleado_documentos'	=> [
			'id_empleado_documentos' => ['solapa'=>'Documentos', 'etiqueta'=> 'Id Registro'],
			'id_tipo'			=> ['solapa' => 'Documentos', 'etiqueta' => 'Tipo', 'map'=>'map_id_tipo'],
			'nombre_archivo'	=> ['solapa'=>'Documentos', 'etiqueta'=>'Documento'],
			'fecha_reg'			=> ['solapa'=>'Documentos', 'etiqueta'=>'Fecha de Subida'],
		],
		'empleado_escalafon'	=> [
			'id_empleado_escalafon'		=> ['solapa'=>Bloques::SITUACION_ESCALAFONARIA, 'etiqueta' => 'Id Registro'],
			'id_modalidad_vinculacion'	=> ['solapa'=>Bloques::SITUACION_ESCALAFONARIA, 'etiqueta'=>'Modalidad de Vinculación', 'map'=>'modalidad_vinculacion'],
			'id_situacion_revista'		=> ['solapa'=>Bloques::SITUACION_ESCALAFONARIA, 'etiqueta'=>'Situación de Revista','map'=>'map_situacion_revista'],
			'id_nivel'					=> ['solapa'=>Bloques::SITUACION_ESCALAFONARIA, 'etiqueta'=>'Función o Nivel', 'map'=>'map_id_nivel'],
			'id_grado'					=> ['solapa'=>Bloques::SITUACION_ESCALAFONARIA, 'etiqueta'=>'Grado', 'map'=>'map_id_grado'],
			'id_tramo'					=> ['solapa'=>Bloques::SITUACION_ESCALAFONARIA, 'etiqueta'=>'Tramo', 'map'=>'map_id_tramo'],
			'id_agrupamiento'			=> ['solapa'=>Bloques::SITUACION_ESCALAFONARIA, 'etiqueta'=>'Agrupamiento', 'map'=>'map_id_agrupamiento'],
			'id_funcion_ejecutiva'		=> ['solapa'=>Bloques::SITUACION_ESCALAFONARIA, 'etiqueta'=>'Función Ejecutiva', 'map'=>'map_id_funcion_ejecutiva'],
			'compensacion_geografica'	=> ['solapa'=>Bloques::SITUACION_ESCALAFONARIA, 'etiqueta'=>'Compensación por Zona Geográfica'],
			'compensacion_transitoria'	=> ['solapa'=>Bloques::SITUACION_ESCALAFONARIA, 'etiqueta'=>'Compensación Transitoria'],
//			'fecha_inicio'				=> ['solapa'=>Bloques::ANTIGUEDAD, 'etiqueta'=>'Fecha Otorgamiento Grado'],
			'fecha_fin'					=> ['solapa'=>Bloques::SITUACION_ESCALAFONARIA, 'etiqueta'=>'Baja Modalidad Vinculacion'],
//			'ultimo_cambio_nivel'		=> ['solapa'=>Bloques::SITUACION_ESCALAFONARIA, 'etiqueta'=>'Último cambio de nivel'],
			'exc_art_14'				=> ['solapa'=>Bloques::SITUACION_ESCALAFONARIA, 'etiqueta'=>'Excepción Articulo Nº14', 'map'=>'map_exc_art_14'],
			'unidad_retributiva'		=> ['solapa'=>Bloques::SITUACION_ESCALAFONARIA, 'etiqueta'=>'Unidad Retributiva'],
		],
		'empleado_ultimos_cambios' =>[
			'fecha_desde'		=> ['solapa'=>'', 'etiqueta'=>''],

		],
		'empleado_horarios'	=> [
			'id_empleado_horarios' => ['solapa'=>Bloques::ADMINISTRACION, 'etiqueta'=>'Id Registro'],
			'horarios'		=> ['solapa' => Bloques::ADMINISTRACION, 'etiqueta' => 'Grilla Horaria', 'map' => 'map_horarios'],
			'id_turno'		=> ['solapa'=>Bloques::ADMINISTRACION, 'etiqueta'=>'Turno', 'map'=>'turno'],
			'fecha_inicio'	=> ['solapa'=>Bloques::ADMINISTRACION, 'etiqueta'=>'Fecha Inicio'],
			'fecha_fin'		=> ['solapa'=>Bloques::ADMINISTRACION, 'etiqueta'=>'Fecha Fin'],
		],
		'empleado_horas_extras'	=> [
			'id_empleado_horas_extras'	=> ['solapa'=>Bloques::ADMINISTRACION, 'etiqueta'=> 'Id Registro'],
			'anio'					=> ['solapa' => Bloques::ADMINISTRACION, 'etiqueta' => 'Horas Extras - Año'],
			'mes'					=> ['solapa'=>Bloques::ADMINISTRACION, 'etiqueta'=>'Horas Extras - Mes'],
			'acto_administrativo'	=> ['solapa'=>Bloques::ADMINISTRACION, 'etiqueta'=>'Horas Extras - Acto Administrativo'],
		],
		'empleado_perfil'	=> [
			'id_empleado_perfil'	=> ['solapa'=>Bloques::PERFILES_PUESTO, 'etiqueta'=>'Id Registro'],
			'denominacion_funcion'	=> ['solapa'=>Bloques::PERFILES_PUESTO, 'etiqueta'=>'Denominación de la Función', 'map'=>'denominacion_de_la_funcion'],
			'denominacion_puesto'	=> ['solapa'=>Bloques::PERFILES_PUESTO, 'etiqueta'=>'Denominación del Puesto', 'map'=>'denominacion_del_puesto'],
			'objetivo_gral'			=> ['solapa'=>Bloques::PERFILES_PUESTO, 'etiqueta'=>'Objetivo General'],
			'objetivo_especifico'	=> ['solapa'=>Bloques::PERFILES_PUESTO, 'etiqueta'=>'Objetivos Específicos'],
			'estandares'			=> ['solapa'=>Bloques::PERFILES_PUESTO, 'etiqueta'=>'Estándares Cuanti/Cualitativos'],
			'fecha_obtencion_result'=> ['solapa'=>Bloques::PERFILES_PUESTO, 'etiqueta'=>'Fecha de Obtención Resultado'],
			'nivel_destreza'		=> ['solapa'=>Bloques::PERFILES_PUESTO, 'etiqueta'=>'Nivel de Destreza', 'map'=>'nivel_de_destreza'],
			'nombre_puesto'			=> ['solapa'=>Bloques::PERFILES_PUESTO, 'etiqueta'=>'Nombre del Puesto', 'map'=>'nombre_de_puesto'],
			'puesto_supervisa'		=> ['solapa'=>Bloques::PERFILES_PUESTO, 'etiqueta'=>'Puesto Supervisa', 'map'=>'niveles_puesto_supervisa'],
			'nivel_complejidad'		=> ['solapa'=>Bloques::PERFILES_PUESTO, 'etiqueta'=>'Nivel de Destreza', 'map'=>'niveles_complejidad'],
			'fecha_desde'			=> ['solapa'=>Bloques::PERFILES_PUESTO, 'etiqueta'=>'fecha Alta'],
			'fecha_hasta'			=> ['solapa'=>Bloques::PERFILES_PUESTO, 'etiqueta'=>'Fecha Baja'],
			'familia_de_puestos'	=> ['solapa'=>Bloques::PERFILES_PUESTO, 'etiqueta'=>'Familia de Puestos', 'map'=>'familia_de_puesto'],
		],
		'empleado_presupuesto'	=> [
			'id_empleado_presupuesto'	=> ['solapa' => Bloques::PRESUPUESTO, 'etiqueta' => 'Id Registro'],
			'id_presupuesto'	=> ['solapa'=>Bloques::PRESUPUESTO, 'etiqueta'=>'Presupuesto', 'map'=>'map_id_presupuesto'],
			'fecha_desde'		=> ['solapa'=>Bloques::PRESUPUESTO, 'etiqueta'=>'Fecha Inicio'],
			'fecha_hasta'		=> ['solapa'=>Bloques::PRESUPUESTO, 'etiqueta'=>'Fecha Fin'],
		],
		'empleados'	=> [
			'id_empleados'				=> ['solapa'=>Bloques::DATOS_PERSONALES, 'etiqueta'=> 'Id Registro'],
			'cuit'						=> ['solapa'=> Bloques::DATOS_PERSONALES, 'etiqueta' => 'CUIT/CUIL'],
			'email'						=> ['solapa'=>Bloques::DATOS_PERSONALES, 'etiqueta'=>'Email'],
			'planilla_reloj'			=> ['solapa'=>Bloques::ADMINISTRACION, 'etiqueta'=>'Firma Presentismo', 'map'=>'{"0":"No","1":"Si"}'],
			'en_comision'				=> ['solapa'=>Bloques::ADMINISTRACION, 'etiqueta'=>' Agente en Situación de Comisión', 'map'=>'{"2":"No","1":"Si"}'],
			'credencial'				=> ['solapa'=>Bloques::VARIOS, 'etiqueta'=>' Posee Credencial de Acceso', 'map'=>'{"0":"No","1":"Si"}'],
			'antiguedad_adm_publica'	=> ['solapa'=>Bloques::ANTIGUEDAD, 'etiqueta'=>' Antigüedad en la Administración Publica','map'=>'map_antiguedad_adm_publica'],
			'id_sindicato'				=> ['solapa'=>Bloques::SITUACION_ESCALAFONARIA, 'etiqueta'=>'Delegado Gremial del Sindicato', 'map'=>'id_sindicato'],
			'fecha_vigencia_mandato'	=> ['solapa'=>Bloques::SITUACION_ESCALAFONARIA, 'etiqueta'=>'Fecha de Vigencia de Mandato'],
			'estado'					=> ['solapa'=>Bloques::ADMINISTRACION, 'etiqueta'=>'Estado del Agente', 'map'=>'{"2":"Inactivo","1":"Activo"}'],
			'id_motivo'					=> ['solapa'=>Bloques::ADMINISTRACION, 'etiqueta'=>'Motivo de Baja', 'map'=>'motivo_baja'],
			'fecha_baja'				=> ['solapa'=>Bloques::ADMINISTRACION, 'etiqueta'=>'Fecha de Baja'],
			'fecha_vencimiento'			=> ['solapa'=>Bloques::VARIOS, 'etiqueta'=>'Fecha de Vencimiento Credencial de Acceso'],
			'veterano_guerra'			=> ['solapa'=>Bloques::VARIOS, 'etiqueta'=>'Veterano de Guerra', 'map'=>'{"0":"No","1":"Si"}'],
		],
		'empleado_salud'	=> [
			'id_empleado_salud'=> ['solapa'=> Bloques::VARIOS, 'etiqueta' => 'Id Registro'],
			'id_obra_social' => ['solapa' => Bloques::VARIOS, 'etiqueta'=>'Obra Social', 'map'=>'obras_sociales'],
			'fecha_desde'	=> ['solapa'=>Bloques::VARIOS, 'etiqueta'=>'Fecha Inicio'],
			'fecha_hasta'	=> ['solapa'=>Bloques::VARIOS, 'etiqueta'=>'Fecha Fin'],
		],
		'empleado_seguros'	=> [
			'id_empleado_seguros'	=> ['solapa'=>Bloques::VARIOS, 'etiqueta'=>'Id Registro'],
			'id_seguro'		=> ['solapa' => Bloques::VARIOS, 'etiqueta' => 'Seguro De Vida', 'map'=>'seguros_vida'],
			'fecha_desde'	=> ['solapa'=>Bloques::VARIOS, 'etiqueta'=>'Fecha Inicio'],
			'fecha_hasta'	=> ['solapa'=>Bloques::VARIOS, 'etiqueta'=>'Fecha Fin'],
		],
		'empleado_sindicatos'	=> [
			'id_empleado_sindicatos' => ['solapa' => Bloques::VARIOS, 'etiqueta' => 'Id Registro'],
			'id_sindicato'	=> ['solapa'=>Bloques::VARIOS, 'etiqueta'=>'Sindicato', 'map'=>'id_sindicato'],
			'fecha_desde'	=> ['solapa'=>Bloques::VARIOS, 'etiqueta'=>'Fecha Inicio'],
			'fecha_hasta'	=> ['solapa'=>Bloques::VARIOS, 'etiqueta'=>'Fecha Baja'],
		],
		'empleados_lic_especiales'	=> [
			'id_empleados_lic_especiales'	=> ['solapa' => Bloques::ADMINISTRACION, 'etiqueta' => 'Id Registro'],
			'id_licencia'	=> ['solapa'=>Bloques::ADMINISTRACION, 'etiqueta'=>'Licencias Especiales', 'map'=>'licencias_especiales'],
			'fecha_desde'	=> ['solapa'=>Bloques::ADMINISTRACION, 'etiqueta'=>'Licencias Fecha Inicio'],
			'fecha_hasta'	=> ['solapa'=>Bloques::ADMINISTRACION, 'etiqueta'=>'Licencias Fecha Fin'],
		],
		'empleados_x_ubicacion'	=> [
			'id_empleados_x_ubicacion'	=> ['solapa'=>Bloques::ADMINISTRACION, 'etiqueta'=>'Id Registro'],
			'id_ubicacion'	=> ['solapa' => Bloques::ADMINISTRACION, 'etiqueta' => 'Datos de Ubicación', 'map' => 'map_id_ubicacion'],
			'fecha_desde'	=> ['solapa'=>Bloques::ADMINISTRACION, 'etiqueta'=>'Fecha Desde'],
			'fecha_hasta'	=> ['solapa'=>Bloques::ADMINISTRACION, 'etiqueta'=>'Fecha Hasta'],
		],
		'familiar_discapacidad'	=> [
			'id_familiar_discapacidad'	=> ['solapa' => Bloques::GRUPO_FAMILIAR, 'etiqueta' => 'Id Registro'],
			'id_tipo_discapacidad'	=> ['solapa'=>Bloques::GRUPO_FAMILIAR, 'etiqueta'=>'Tipo de Discapacidad', 'map'=>'tipo_discapacidad'],
			'cud'					=> ['solapa'=>Bloques::GRUPO_FAMILIAR, 'etiqueta'=>'CUD'],
			'fecha_alta'			=> ['solapa'=>Bloques::GRUPO_FAMILIAR, 'etiqueta'=>'Fecha Alta Discapacidad'],
			'fecha_vencimiento'		=> ['solapa'=>Bloques::GRUPO_FAMILIAR, 'etiqueta'=>'Fecha de Vencimiento'],
		],
		'grupo_familiar'	=> [
			'id_grupo_familiar'		=> ['solapa' => '#', 'etiqueta' => 'Id Registro'],			
			'parentesco'			=> ['solapa'=>Bloques::GRUPO_FAMILIAR, 'etiqueta'=>'Parentesco', 'map'=>'parentesco'],
			'nombre'				=> ['solapa'=>Bloques::GRUPO_FAMILIAR, 'etiqueta'=>'Nombre'],
			'apellido'				=> ['solapa'=>Bloques::GRUPO_FAMILIAR, 'etiqueta'=>'Apellido'],
			'fecha_nacimiento'		=> ['solapa'=>Bloques::GRUPO_FAMILIAR, 'etiqueta'=>'Fecha de Nacimiento'],
			'nacionalidad'			=> ['solapa'=>Bloques::GRUPO_FAMILIAR, 'etiqueta'=>'Nacionalidad', 'map'=>'nacionalidad'],
			'tipo_documento'		=> ['solapa'=>Bloques::GRUPO_FAMILIAR, 'etiqueta'=>'Tipo de Documento', 'map'=>'tipo_documento'],
			'documento'				=> ['solapa'=>Bloques::GRUPO_FAMILIAR, 'etiqueta'=>'Documento'],
			'nivel_educativo'		=> ['solapa'=>Bloques::GRUPO_FAMILIAR, 'etiqueta'=>'Nivel de Estudio', 'map'=>'formacion_tipo_titulo'],
			'reintegro_guarderia'	=> ['solapa'=>Bloques::GRUPO_FAMILIAR, 'etiqueta'=>'Reintegro Guardería', 'map'=>'{"0":"No","1":"Si"}'],
			'discapacidad'			=> ['solapa'=>Bloques::GRUPO_FAMILIAR, 'etiqueta'=>'Tiene Discapacidad', 'map'=>'{"0":"No","1":"Si"}'],
			'desgrava_afip'			=> ['solapa'=>Bloques::GRUPO_FAMILIAR, 'etiqueta'=>'% que Desgrava AFIP', 'map'=>'map_desgrava_afip'],
			'fecha_desde'			=> ['solapa'=>Bloques::GRUPO_FAMILIAR, 'etiqueta'=>'Fecha Desde'],
			'fecha_hasta'			=> ['solapa'=>Bloques::GRUPO_FAMILIAR, 'etiqueta'=>'Fecha Hasta'],
		],
		'perfil_actividades'	=> [
			'id_perfil_actividades'	=> ['solapa'=>Bloques::PERFILES_PUESTO, 'etiqueta'=>'Id Registro'],
			'nombre'	=> ['solapa' => Bloques::PERFILES_PUESTO, 'etiqueta' => 'Actividades/Tareas'],
		],
		'perfil_resultado_parc_final'	=> [
			'id_perfil_resultado_parc_final'	=> ['solapa'=>Bloques::PERFILES_PUESTO, 'etiqueta'=> 'Id Registro'],
			'nombre'	=> ['solapa' => Bloques::PERFILES_PUESTO, 'etiqueta' => 'Resultados Parciales/Finales'],
		],
		'persona_discapacidad'	=> [
			'id_persona_discapacidad'	=> ['solapa'=>Bloques::VARIOS, 'etiqueta'=>'Id Registro'],
			'id_tipo_discapacidad'	=> ['solapa' => Bloques::VARIOS, 'etiqueta' => 'Tipo de Discapacidad', 'map' => 'tipo_discapacidad'],
			'cud'					=> ['solapa'=>Bloques::VARIOS, 'etiqueta'=>'CUD'],
			'fecha_vencimiento'		=> ['solapa'=>Bloques::VARIOS, 'etiqueta'=>'Fecha de Vencimiento'],
			'observaciones'			=> ['solapa'=>Bloques::VARIOS, 'etiqueta'=>'Observaciones'],
		],
		'persona_domicilio'	=> [
			'id_persona_domicilio' => ['solapa'=>Bloques::DATOS_PERSONALES, 'etiqueta'=>'Id_Registro'],
			'calle'				=> ['solapa' => Bloques::DATOS_PERSONALES, 'etiqueta' => 'Calle'],
			'numero'			=> ['solapa'=>Bloques::DATOS_PERSONALES, 'etiqueta'=>'Número'],
			'piso'				=> ['solapa'=>Bloques::DATOS_PERSONALES, 'etiqueta'=>'Piso'],
			'depto'				=> ['solapa'=>Bloques::DATOS_PERSONALES, 'etiqueta'=>'Depto'],
			'cod_postal'		=> ['solapa'=>Bloques::DATOS_PERSONALES, 'etiqueta'=>'Código Postal'],
			'id_provincia'		=> ['solapa'=>Bloques::DATOS_PERSONALES, 'etiqueta'=>'Provincia', 'map'=>'map_provincia'],
			'id_localidad'		=> ['solapa'=>Bloques::DATOS_PERSONALES, 'etiqueta'=>'Localidad', 'map'=>'map_localidad'],
			// 'fecha_alta'		=> ['solapa'=>'', 'etiqueta'=>'Fecha Alta'],
			// 'fecha_baja'		=> ['solapa'=>'', 'etiqueta'=>'Fecha Baja'],
		],
		'persona_otros_conocimientos'	=> [
			'id_persona_otros_conocimientos' => ['solapa' => Bloques::FORMACION, 'etiqueta' => 'Id Registro'],
			'id_tipo'			=> ['solapa'=>Bloques::FORMACION, 'etiqueta'=>'Tipo', 'map'=>'{"2":"Conocimiento Especfico de Sistemas\/Software","1":"Otros Estudios Realizados"}'],
			'fecha'				=> ['solapa'=>Bloques::FORMACION, 'etiqueta'=>'Fecha'],
			'descripcion'		=> ['solapa'=>Bloques::FORMACION, 'etiqueta'=>'Título/Descripcion'],
		],
		'personas'	=> [
			'id_personas'		=> ['solapa'=>Bloques::DATOS_PERSONALES, 'etiqueta' => 'Id Registro'],
			'tipo_documento'	=> ['solapa' => Bloques::DATOS_PERSONALES, 'etiqueta'=>'Tipo de Documento', 'map'=>'tipo_documento'],
			'documento'			=> ['solapa'=>Bloques::DATOS_PERSONALES, 'etiqueta'=>'Documento'],
			'nombre'			=> ['solapa'=>Bloques::DATOS_PERSONALES, 'etiqueta'=>'Nombre'],
			'apellido'			=> ['solapa'=>Bloques::DATOS_PERSONALES, 'etiqueta'=>'Apellido'],
			'fecha_nac'			=> ['solapa'=>Bloques::DATOS_PERSONALES, 'etiqueta'=>'Fecha de Nacimiento'],
			'genero'			=> ['solapa'=>Bloques::DATOS_PERSONALES, 'etiqueta'=>'Genero', 'map'=>'genero'],
			'nacionalidad'		=> ['solapa'=>Bloques::DATOS_PERSONALES, 'etiqueta'=>'Nacionalidad', 'map'=>'nacionalidad'],
			'estado_civil'		=> ['solapa'=>Bloques::DATOS_PERSONALES, 'etiqueta'=>'Estado Civil','map'=>'estado_civil'],
			'foto_persona'		=> ['solapa'=>Bloques::DATOS_PERSONALES, 'etiqueta'=>'Foto', 'map'=>'map_foto_persona'],
		],
		'persona_telefono'	=> [
			'id_persona_telefono' => ['solapa'=>Bloques::DATOS_PERSONALES, 'etiqueta'=>'Descripción'],
			'id_tipo_telefono'	=> ['solapa' => Bloques::DATOS_PERSONALES, 'etiqueta' => 'Descripción', 'map'=>'tipo_telefono'],
			'telefono'			=> ['solapa'=>Bloques::DATOS_PERSONALES, 'etiqueta'=>'Número Telefono'],
			'fecha_alta'		=> ['solapa'=>Bloques::DATOS_PERSONALES, 'etiqueta'=>'Fecha Alta'],
			'fecha_baja'		=> ['solapa'=>Bloques::DATOS_PERSONALES, 'etiqueta'=>'Fecha Baja'],
		],
		'persona_titulo'	=> [
			'id_persona_titulo'	=> ['solapa' => Bloques::FORMACION, 'etiqueta' => 'Id Registro'],
			'id_tipo_titulo'	=> ['solapa'=>Bloques::FORMACION, 'etiqueta'=>'Nivel Educativo', 'map'=>'formacion_tipo_titulo'],
			'id_estado_titulo'	=> ['solapa'=>Bloques::FORMACION, 'etiqueta'=>'Estado', 'map'=>'formacion_estado_titulo'],
			'id_titulo'			=> ['solapa'=>Bloques::FORMACION, 'etiqueta'=>'Titulo', 'map'=>'titulo'],
			// 'abreviatura'		=> ['solapa'=>Bloques::FORMACION, 'etiqueta'=>'Texto'],
			'fecha'				=> ['solapa'=>Bloques::FORMACION, 'etiqueta'=>'Otorgamiento'],
			'principal'			=> ['solapa'=>Bloques::FORMACION, 'etiqueta'=>'Titulo Considerado', 'map'=>'{"1":"SI","0":"NO"}'],
		],
		'persona_experiencia_laboral'	=> [
			'id_persona_experiencia_laboral'	=> ['solapa' => Bloques::ANTIGUEDAD, 'etiqueta' => 'Id Registro'],
			'id_entidad'			=> ['solapa'=>Bloques::ANTIGUEDAD, 'etiqueta'=>'Organismo', 'map'=>'map_organismos'],
			'fecha_desde'			=> ['solapa'=>Bloques::ANTIGUEDAD, 'etiqueta'=>'Fecha desde'],
			'fecha_hasta'			=> ['solapa'=>Bloques::ANTIGUEDAD, 'etiqueta'=>'Fecha hasta'],
		],
		'designacion_transitoria'	=> [
			'id_designacion_transitoria' 	=> ['solapa'=>'Designación Transitoria', 'etiqueta'=> 'Id Registro'],
			'fecha_desde'					=> ['solapa'=>'Designación Transitoria', 'etiqueta'=>'Fecha Publicacion de Designación'],
			'fecha_hasta'					=> ['solapa'=>'Designación Transitoria', 'etiqueta'=>'Fecha vencimiento de Designación'],
			'tipo'							=> ['solapa'=>'Designación Transitoria', 'etiqueta'=>'Tipo Designación Transitoria', 'map'=>'map_designacion_transitoria'],

		],
		'empleado_cursos'	=> [
			'id_empleado_cursos'=> ['solapa' => Bloques::FORMACION, 'etiqueta' => 'Id Registro'],
			'id_curso'	=> ['solapa'=>Bloques::FORMACION, 'etiqueta'=>'Curso', 'map'=>'map_formacion_cursos'],
			'fecha'				=> ['solapa'=>Bloques::FORMACION, 'etiqueta'=>'fecha_curso'],
		],
		'empleado_evaluaciones'	=> [
			'id_empleado_evaluaciones'	=> ['solapa'=> Bloques::PERFILES_PUESTO, 'etiqueta' => 'Id Registro'],
			'anio'						=> ['solapa'=>Bloques::PERFILES_PUESTO, 'etiqueta'	=>'Año'],
			'formulario'				=> ['solapa'=>Bloques::PERFILES_PUESTO, 'etiqueta'	=>'Formulario', 'map'=>'map_formulario'],
			'evaluacion'				=> ['solapa'=>Bloques::PERFILES_PUESTO, 'etiqueta'	=>'Evaluación', 'map'=>'map_evaluacion'],
			'puntaje'					=> ['solapa'=>Bloques::PERFILES_PUESTO, 'etiqueta'	=>'Puntaje'],
			'bonificado'        		=> ['solapa'=>Bloques::PERFILES_PUESTO, 'etiqueta'	=>'Bonificación','map'=>'{"1":"SI","0":"NO"}'],
			'acto_administrativo'       => ['solapa'=>Bloques::PERFILES_PUESTO, 'etiqueta'	=>'Acto Administrativo'],
			'archivo'					=> ['solapa'=>Bloques::PERFILES_PUESTO, 'etiqueta'	=>'Documento']
		],
		'persona_titulo_creditos'	=> [
			'id_persona_titulo_credito' 	=> ['solapa'=>'Persona Titulo Créditos', 'etiqueta'=> 'Id Registro'],
			'fecha'							=> ['solapa'=>'Persona Titulo Créditos', 'etiqueta'=>'Fecha'],
			'acto_administrativo'			=> ['solapa'=>'Persona Titulo Créditos', 'etiqueta'=>'Acto Administrativo'],
			'creditos'						=> ['solapa'=>'Persona Titulo Créditos', 'etiqueta'=>'Créditos'],
			'archivo'						=> ['solapa'=>'Persona Titulo Créditos', 'etiqueta'=>'Comprobante'],
			'estado_titulo'					=> ['solapa'=>'Persona Titulo Créditos', 'etiqueta'=>'Estado Titulo', 'map'=>'{"1":"Incompleto","2":"Completo"}'],
		],
		'empleado_creditos_iniciales'	=> [
			'id_empleado_creditos_iniciales'=> ['solapa'=>'Créditos Iniciales', 'etiqueta'=> 'Id Registro'],
			'fecha_considerada'				=> ['solapa'=>'Créditos Iniciales', 'etiqueta'=>'Fecha Considerada'],
			'creditos'						=> ['solapa'=>'Créditos Iniciales', 'etiqueta'=>'Créditos'],
			'descripcion'					=> ['solapa'=>'Créditos Iniciales', 'etiqueta'=>'Descripción'],
		],

	];
}
