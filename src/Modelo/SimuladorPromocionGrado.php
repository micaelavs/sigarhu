<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Conexiones;
use FMT\Helper\Arr;


class SimuladorPromocionGrado extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_empleado;
/** @var int */
	public $id_empleado_escalafon;
/** @var int */
	public $fecha_ultima_promocion;
/** @var int */
	public $grupo_incremental;
/** @var int */
	public $id_motivo;
/** @var int */
	public $anio;
/** @var int */
	public $anio_inicio;
/** @var int */
	public $anio_fin;
/** @var int */
	public $id_empleado_evaluacion;
/** @var int */
	public $bonificado;
/** @var int */
	public $id_calificacion;
/** @var int */
	public $grado_analisis;
/** @var int */
	public $creditos_requeridos;
/** @var int */
	public $creditos_reconocidos;
/** @var int */
	public $porcentaje_reconocido;
/** @var int */
	public $creditos_disponibles;
/** @var int */
	public $creditos_subtotal;
/** @var int */
	public $total_periodo;
/** @var int */
	public $id_situacion_revista;
/** @var int */
	public $id_nivel;
/** @var int */
	public $id_grado;
/** @var int */
	public $id_tramo;
/** @var bool */
	public $aplica_promocion;
/** @var int */
	public $borrado;

/** 2 Destacadas sin bonificar */
  const REGLA_A	= 1;
/** 1 Destacada 2 Bonificada */
  const REGLA_B	= 2;
/** 3 calificaciones superior a Bueno */
  const REGLA_C	= 3;
/** Fue Autoridad Superior */
  const REGLA_D	= 4;
/** 3 Bonificadas con 2 Destacadas y 1 superior a Bueno */
  const REGLA_E	= 5;
  
  static public $MOTIVOS_PROMOCION  = [
		self::REGLA_A	=> ['id' => self::REGLA_A, 'nombre' => '2 Destacadas sin bonificar', 'borrado' => 0],
		self::REGLA_B	=> ['id' => self::REGLA_B, 'nombre' => '1 Destacada 2 Bonificada', 'borrado' => 0],
		self::REGLA_C	=> ['id' => self::REGLA_C, 'nombre' => '3 calificaciones superior a Bueno', 'borrado' => 0],
		self::REGLA_D	=> ['id' => self::REGLA_D, 'nombre' => 'Fue Autoridad Superior', 'borrado' => 0],
		self::REGLA_E	=> ['id' => self::REGLA_E, 'nombre' => '3 Bonificadas con 2 Destacadas y 1 superior a Bueno', 'borrado' => 0],
	];
  
	public function validar(){ return true; }
	public function alta(){ return false; }
	public function baja(){ return false; }
	public function modificacion(){ return false; }

	static public function obtener($id_empleado=null, $id_grupo=null){
		if(empty($id_empleado)){
			return static::arrayToObject();
		}
		$sql_params	= [
			':id_empleado'	=> $id_empleado,
			':id_grupo'		=> $id_grupo,
		];
		$sql		= <<<SQL
			SELECT *, 
			(SELECT anio FROM empleado_simulacion_promocion_grado_tmp WHERE id_empleado = :id_empleado AND grupo_incremental = :id_grupo ORDER BY anio ASC LIMIT 1) AS anio_inicio,
			(SELECT anio FROM empleado_simulacion_promocion_grado_tmp WHERE id_empleado = :id_empleado AND grupo_incremental = :id_grupo ORDER BY anio DESC LIMIT 1) AS anio_fin
			FROM empleado_simulacion_promocion_grado_tmp 
			WHERE id_empleado = :id_empleado AND grupo_incremental = :id_grupo
			ORDER BY id DESC
			LIMIT 1
SQL;
		$cnx		= new Conexiones();
		$resp		= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(empty($resp[0]['id'])){
			return static::arrayToObject();
		}
		return static::arrayToObject($resp[0]);
	}

	static public function listar($id_empleado=null){
		if(empty($id_empleado)){
			return [];
		}
		$sql_params	= [
			':id_empleado'	=> $id_empleado,
		];
		$sql		= <<<SQL
			SELECT
			'' as nivel,
			'' as grado,
			'' as tramo,
			espgt.fecha_ultima_promocion,
			espgt.grado_analisis, 
			espgt.anio,
			IF(ISNULL(espgt.id_calificacion),'S/D',espgt.id_calificacion) as id_calificacion,
			IF(espgt.bonificado = 1,'Si','No') as bonificado,
			'' as situacion_revista,
			'' as ultimo_tramo,
			espgt.creditos_requeridos,
			espgt.creditos_disponibles as creditos_acumulados,
			espgt.porcentaje_reconocido as porcentaje_acumulado_titulos,
			IF(espgt.aplica_promocion = 1,'true','false') as promociona,
			espgt.id_motivo as motivo,
			espgt.grupo_incremental,
			espgt.id_empleado,
			espgt.id_nivel,
			espgt.id_grado,
			espgt.id_tramo,
			espgt.id_situacion_revista
			FROM empleado_simulacion_promocion_grado_tmp espgt
			WHERE espgt.borrado = 0 AND espgt.id_empleado = :id_empleado
SQL;
		$cnx		= new Conexiones();
		$resp		= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);

		$convenios_pp	= Contrato::obtenerConvenio(Contrato::SINEP, Contrato::PLANTA_PERMANENTE);
		$convenios_as	= Contrato::obtenerConvenio(Contrato::AUTORIDAD_SUPERIOR, Contrato::AUTORIDAD_SUPERIOR_SR);
		if(empty($resp)){
			return [];
		}
		foreach ($resp as &$val) {
			if($val['id_situacion_revista'] == Contrato::AUTORIDAD_SUPERIOR_SR){
				$convenios	= &$convenios_as;
			} else {
				$convenios	= &$convenios_pp;
			}
			$val['nivel']				= \FMT\Helper\Arr::path($convenios, "agrupamientos.*.niveles.{$val['id_nivel']}.nombre", '--');
			$val['grado']				= \FMT\Helper\Arr::path($convenios, "tramos.*.grados.{$val['id_grado']}.nombre", '--');
			$val['tramo']				= \FMT\Helper\Arr::path($convenios, "tramos.{$val['id_tramo']}.nombre", '--');
			$val['ultimo_tramo']		= $val['tramo'];
			$val['situacion_revista']	= \FMT\Helper\Arr::path(Contrato::getParam('SITUACION_REVISTA'), "{$val['id_situacion_revista']}.nombre", 'SIN DATA');
		}
		return $resp;
	}

	static public function listarAgentesPromocionables(){
		$cnx	= new Conexiones();
		$sql_params	= [
			':id_tipo_cambio'	=> EmpleadoUltimosCambios::GRADO,
			':estado'			=> Empleado::EMPLEADO_ACTIVO,
		];
		$sql	= <<<SQL
			SELECT
				e.id,
				e.cuit,
				p.nombre,
				p.apellido,
				cg.nombre AS nombre_grado,
				ct.nombre AS nombre_tramo,
				cn.nombre AS nombre_nivel,
				ca.nombre AS nombre_agrupamiento,
				IF(euc.fecha_desde IS NULL, '2000-01-01', euc.fecha_desde) AS fecha_ultima_promocion_grado
			FROM empleados e
					INNER JOIN personas p ON (e.id_persona = p.id)
					LEFT JOIN empleado_ultimos_cambios euc ON (e.id = euc.id_empleado AND euc.fecha_hasta IS NULL AND euc.id_tipo = :id_tipo_cambio)
					INNER JOIN empleado_escalafon ee on (e.id = ee.id_empleado AND ee.fecha_fin IS NULL )
					INNER JOIN convenio_grados cg ON (cg.id = ee.id_grado AND cg.borrado = 0)
					INNER JOIN convenio_tramos ct ON (ct.id = ee.id_tramo AND ct.borrado = 0)
					INNER JOIN convenio_niveles cn ON (cn.id = ee.id_nivel AND cn.borrado = 0)
					INNER JOIN convenio_agrupamientos ca ON (ca.id = ee.id_agrupamiento AND ca.borrado = 0)
			WHERE e.estado = :estado AND e.borrado = 0 AND e.id IN (
				SELECT distinct id_empleado
				FROM empleado_simulacion_promocion_grado_tmp
				GROUP BY id_empleado
				ORDER BY id_empleado DESC
			)
SQL;

		$lista	= $cnx->consulta(Conexiones::SELECT,  $sql, $sql_params);
		if(empty($lista[0]['id'])){
			return [];
		}
        return $lista;

	}

	public static function arrayToObject($resp=null){
		$campos = [
			'id'					=> 'int',
			'id_empleado'			=> 'int',
			'id_empleado_escalafon'	=> 'int',
			'fecha_ultima_promocion'=> 'int',
			'grupo_incremental'		=> 'int',
			'id_motivo'				=> 'int',
			'anio'					=> 'int',
			'anio_inicio'			=> 'int',
			'anio_fin'				=> 'int',
			'id_empleado_evaluacion'=> 'int',
			'bonificado'			=> 'int',
			'id_calificacion'		=> 'int',
			'grado_analisis'		=> 'int',
			'creditos_requeridos'	=> 'int',
			'creditos_reconocidos'	=> 'int',
			'porcentaje_reconocido'	=> 'int',
			'creditos_disponibles'	=> 'int',
			'creditos_subtotal'		=> 'int',
			'total_periodo'			=> 'int',
			'id_situacion_revista'	=> 'int',
			'id_nivel'				=> 'int',
			'id_grado'				=> 'int',
			'id_tramo'				=> 'int',
			'aplica_promocion'		=> 'int',
			'borrado'				=> 'int',
		];
		return parent::arrayToObject($resp, $campos);
	}

/**
 * Obtiene un listado completo de todos los agentes Activos, con situacion de revista SINEP - Planta Permanente.
 * Los valores  referidos a la situacion escalafonaria son los actuales en vigencia.
 * La fecha de ultima promocion de grado, se utiliza como referencia para realizar la posterior simulacion.
 * 
 * Si el ID de empleado es nulo, devuelve la lista de empleados, caso contrario devuelve la misma informacion  pero de un empleado en particular.
 * 
 * @param int $id_empleado - Opcional. Defualt = null
 * @return array
 */
	static public function getAgentesVigentes($id_empleado=null){
		$cnx		= new Conexiones();
		$sql_params	= [
			':id_situacion_revista'	=> [
				Contrato::PLANTA_PERMANENTE,
				Contrato::AUTORIDAD_SUPERIOR_SR
			],
			':id_tipo_cambio'	=> EmpleadoUltimosCambios::GRADO,
		];
		$where	= '';
		$limit	= '';

		if($id_empleado !== null && is_numeric($id_empleado)){
			$sql_params[':id_empleado']	= $id_empleado;
			$where	= 'AND e.id = :id_empleado ';
			$limit	= 'LIMIT 1';
		}
		$sql		= <<<SQL
			SELECT
				e.id,
				e.cuit,
				ee.id AS id_empleado_escalafon,
				ee.id_situacion_revista,
				ee.id_nivel,
				ee.id_agrupamiento,
				ee.id_tramo,
				ee.id_grado,
				cg.nombre AS nombre_grado,
				ct.nombre AS nombre_tramo,
				cn.nombre AS nombre_nivel,
				ca.nombre AS nombre_agrupamiento,
				IF(euc.fecha_desde IS NULL, '2000-01-01', euc.fecha_desde) AS fecha_ultima_promocion_grado
			FROM empleado_escalafon ee
				INNER JOIN empleados e ON (ee.id_empleado = e.id AND e.borrado = 0 AND ee.fecha_fin IS NULL)
				LEFT JOIN empleado_ultimos_cambios euc ON (euc.id_empleado = ee.id_empleado AND euc.fecha_hasta IS NULL AND euc.id_tipo = :id_tipo_cambio)
				INNER JOIN convenio_grados cg ON (cg.id = ee.id_grado AND cg.borrado = 0)
				INNER JOIN convenio_tramos ct ON (ct.id = ee.id_tramo AND ct.borrado = 0)
				INNER JOIN convenio_niveles cn ON (cn.id = ee.id_nivel AND cn.borrado = 0)
				INNER JOIN convenio_agrupamientos ca ON (ca.id = ee.id_agrupamiento AND ca.borrado = 0)
			WHERE ee.id_situacion_revista IN (:id_situacion_revista) AND ee.fecha_fin IS NULL {$where} {$limit}
SQL;
		$resp	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(empty($resp[0]['id'])){
			return [];
		}
		return $resp;
	}


/**
 * Obtiene todas las posibles evaluaciones de un empleado dada una fecha de inicio hasta la fecha actual.
 *
 * @param int $id_empleado
 * @param DateTime|string $fecha_inicio
 * @return array
 */
	static private function getEvaluacionesByEmpleadoId($id_empleado=null,$fecha_inicio=null){
		$cnx		= new Conexiones();
		$fecha_inicio	= ($fecha_inicio instanceof \DateTime)
			? $fecha_inicio->format('Y')
			: (($tmp = \DateTime::createFromFormat('Y-m-d', $fecha_inicio)) && is_object($tmp) && $tmp->format('Y') !== '-0001')
				? $tmp->format('Y')
				: null;
		if(empty($fecha_inicio)){
			return [];
		}

		$sql_params	= [
			':id_empleado'	=> $id_empleado,
			':anio_inicio'	=> $fecha_inicio+1,
			':anio_fin'		=> date('Y')-1,
		];

		$sql		= <<<SQL
		SELECT 
			ev.id,
			ev.id_empleado,
			ee.id AS id_empleado_escalafon,
			ev.anio, 
			ev.evaluacion AS id_calificacion, 
			ev.bonificado,
			ee.id_modalidad_vinculacion,
			ee.id_situacion_revista,
			ee.id_grado,
			ee.id_nivel,
			ee.id_tramo,
			ee.id_agrupamiento,
			ee.month_diff
			
		FROM
			empleado_evaluaciones  AS ev
			INNER JOIN (SELECT id, @ev_anio := anio FROM empleado_evaluaciones) AS tmp ON tmp.id = ev.id
		LEFT JOIN (
			SELECT 
				id,
				id_empleado,
				id_modalidad_vinculacion,
				id_situacion_revista,
				id_tramo,
				id_nivel,
				id_grado,
				id_agrupamiento,
				fecha_inicio,
				fecha_fin,
				TIMESTAMPDIFF(MONTH, fecha_inicio, fecha_fin) AS month_diff
			FROM empleado_escalafon
			WHERE id_empleado = :id_empleado  AND (
				(DATE_FORMAT(fecha_inicio, '%Y') <= @ev_anio  AND DATE_FORMAT(fecha_fin, '%Y') >= @ev_anio AND TIMESTAMPDIFF(MONTH, fecha_inicio, fecha_fin) >= 6 )
				OR (fecha_fin IS NULL)
			)
			ORDER BY id ASC LIMIT 1
		) AS ee ON (
			ee.id_empleado = :id_empleado )
		WHERE ev.id_empleado = :id_empleado and (ev.anio BETWEEN :anio_inicio AND :anio_fin) AND ev.borrado = 0
		ORDER BY ev.anio DESC;
SQL;

		$resp	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(empty($resp[0]['id'])){
			return [];
		}
		$aux	= [];
		foreach ($resp as $value) {
			$aux[$value['anio']]	= $value;
		}
		ksort($aux, SORT_NUMERIC);
		return $aux;
	}

/**
 * Rellena los años de evaluacion faltantes en la base de datos.
 * Los indices del array son los mismos pero con valor en NULL.
 * Corrije la situacion escalafonaria si hay discrepancias
 *
 * @param array &$evaluaciones
 * @param int $id_empleado
 * @param DateTime|string $fecha_inicio
 *
 * @return array
 */
	static private function rellenarEvaluacionesInexistentes(&$evaluaciones=null,$id_empleado=null,$fecha_inicio=null){
		$fecha_inicio	= ($fecha_inicio instanceof \DateTime)
			? $fecha_inicio->format('Y')
			: (($tmp = \DateTime::createFromFormat('Y-m-d', $fecha_inicio)) && is_object($tmp) && $tmp->format('Y') !== '-0001')
				? $tmp->format('Y')
				: null;
		if(empty($fecha_inicio) || empty($evaluaciones)){
			return $evaluaciones;
		}

		$cnx	= new Conexiones();
		$sql	= <<<SQL
			SELECT 
				id,
				id_empleado,
				id_modalidad_vinculacion,
				id_situacion_revista,
				id_tramo,
				id_nivel,
				id_grado,
				id_agrupamiento,
				fecha_inicio,
				fecha_fin,
				TIMESTAMPDIFF(MONTH, fecha_inicio, fecha_fin) AS month_diff
			FROM empleado_escalafon
			WHERE
				id_empleado = :id_empleado AND (
					(DATE_FORMAT(fecha_inicio, '%Y') <= :anio  AND DATE_FORMAT(fecha_fin, '%Y') >= :anio  AND TIMESTAMPDIFF(MONTH, fecha_inicio, fecha_fin) >= 6) 
					OR (fecha_fin IS NULL)
				)				
			ORDER BY id ASC
			LIMIT 1
SQL;
		
		
		$aux_inicio	= (int)$fecha_inicio+1;
		$aux_fin	= (int)date('Y')-1;
		$cache	= [
			'id_empleado_escalafon'		=> null,
			'id_modalidad_vinculacion'	=> null,
			'id_situacion_revista'		=> null,
			'id_nivel'					=> null,
			'id_grado'					=> null,
			'id_tramo'					=> null,
			'id_agrupamiento'			=> null,
		];
		while ($aux_inicio <= $aux_fin) {
			$resp	= $cnx->consulta(Conexiones::SELECT, $sql, [
				':anio'			=> $aux_inicio,
				':id_empleado'	=> $id_empleado,
			]);
			if(!empty($evaluaciones[$aux_inicio])){
				if(!empty($resp[0]['id']) && $evaluaciones[$aux_inicio]['id_empleado_escalafon'] != $resp[0]['id']){
					$evaluaciones[$aux_inicio]['id_empleado_escalafon']		= $resp[0]['id'];
					$evaluaciones[$aux_inicio]['id_modalidad_vinculacion']	= $resp[0]['id_modalidad_vinculacion'];
					$evaluaciones[$aux_inicio]['id_situacion_revista']		= $resp[0]['id_situacion_revista'];
					$evaluaciones[$aux_inicio]['id_nivel']					= $resp[0]['id_nivel'];
					$evaluaciones[$aux_inicio]['id_grado']					= $resp[0]['id_grado'];
					$evaluaciones[$aux_inicio]['id_tramo']					= $resp[0]['id_tramo'];
					$evaluaciones[$aux_inicio]['id_agrupamiento']			= $resp[0]['id_agrupamiento'];
				}
				$cache	= [
					'id_empleado_escalafon'		=> $evaluaciones[$aux_inicio]['id_empleado_escalafon'],
					'id_modalidad_vinculacion'	=> $evaluaciones[$aux_inicio]['id_modalidad_vinculacion'],
					'id_situacion_revista'		=> $evaluaciones[$aux_inicio]['id_situacion_revista'],
					'id_nivel'					=> $evaluaciones[$aux_inicio]['id_nivel'],
					'id_grado'					=> $evaluaciones[$aux_inicio]['id_grado'],
					'id_tramo'					=> $evaluaciones[$aux_inicio]['id_tramo'],
					'id_agrupamiento'			=> $evaluaciones[$aux_inicio]['id_agrupamiento'],
				];
				$aux_inicio	= $aux_inicio+1;
				continue;
			}
			if(!empty($resp[0]['id'])){
				$cache	= [
					'id_empleado_escalafon'		=> \FMT\Helper\Arr::get($resp[0], 'id'),
					'id_modalidad_vinculacion'	=> \FMT\Helper\Arr::get($resp[0], 'id_modalidad_vinculacion'),
					'id_situacion_revista'		=> \FMT\Helper\Arr::get($resp[0], 'id_situacion_revista'),
					'id_nivel'					=> \FMT\Helper\Arr::get($resp[0], 'id_nivel'),
					'id_grado'					=> \FMT\Helper\Arr::get($resp[0], 'id_grado'),
					'id_tramo'					=> \FMT\Helper\Arr::get($resp[0], 'id_tramo'),
					'id_agrupamiento'			=> \FMT\Helper\Arr::get($resp[0], 'id_agrupamiento'),
				];
			}
			$evaluaciones[$aux_inicio]	= [
				'id'						=> null,
				'id_empleado'				=> $id_empleado,
				'id_empleado_escalafon'		=> $cache['id_empleado_escalafon'],
				'anio'						=> $aux_inicio,
				'id_calificacion'			=> Evaluacion::NO_EVALUADO,
				'bonificado'				=> 0,
				'id_modalidad_vinculacion'	=> $cache['id_modalidad_vinculacion'],
				'id_situacion_revista'		=> $cache['id_situacion_revista'],
				'id_grado'					=> $cache['id_grado'],
				'id_nivel'					=> $cache['id_nivel'],
				'id_tramo'					=> $cache['id_tramo'],
				'id_agrupamiento'			=> $cache['id_agrupamiento'],
				'month_diff'				=> null,
			];
			$aux_inicio	= $aux_inicio+1;
		}

		ksort($evaluaciones, SORT_NUMERIC);
		return $evaluaciones;
	}

/**
 * Recibe el grupo por referencia y le agrega los indices con los datos de la evaluacion que se debe agregar.
 * Se debe comprender a este metodo como una interfaz.
 *
 * @param int $grupo_incremental
 * @param int $regla_motivo
 * @param array &$grupo		- Por referencia porque lo modifica
 * @param array &$evaluacion- Por referencia para ocupar menos memoria
 * @param int $grado_analisis
 * @return void
 * @throws Exception
 */
	static private function addToGroup($grupo_incremental=null, $regla_motivo=null, &$grupo=null, &$evaluacion=null, $grado_analisis=null){
		if(!is_array($grupo) || empty($evaluacion)){
			throw new Exception("Faltan datos para agregar en el grupo.", 1);
		}
		$grupo[]	= [
			'id_empleado_escalafon'		=> null,
			'fecha_ultima_promocion'	=> null,
			'aplica_promocion'			=> null,
			'grupo_incremental'			=> $grupo_incremental,
			'grado_analisis'			=> $grado_analisis,
			'id_motivo'					=> $regla_motivo,
			'id_empleado'				=> $evaluacion['id_empleado'],
			'anio'						=> $evaluacion['anio'],
			'id_empleado_evaluacion'	=> $evaluacion['id'],
			'bonificado'				=> $evaluacion['bonificado'],
			'id_calificacion'			=> $evaluacion['id_calificacion'],
			'creditos_requeridos'		=> $evaluacion['creditos_requeridos'],
			'creditos_reconocidos'		=> $evaluacion['creditos_reconocidos'],
			'porcentaje_reconocido'		=> $evaluacion['porcentaje_reconocido'],
			'creditos_subtotal'			=> $evaluacion['creditos_subtotal'],
			'creditos_disponibles'		=> $evaluacion['creditos_disponibles'],
			'total_periodo'				=> $evaluacion['total_periodo'],
			'id_situacion_revista'		=> $evaluacion['id_situacion_revista'],
			'id_nivel'					=> $evaluacion['id_nivel'],
			'id_grado'					=> $evaluacion['id_grado'],
			'id_tramo'					=> $evaluacion['id_tramo'],
		];
	}

/**
 * Recibe las evaluaciones, y les aplica las reglas de agrupamiento, mientras arma los grupos.
 * Cuando se le aplica Creditos Requeridos = 0 a una evaluacion, es porque se considera ignorada, se coloca dentro del grupo pero no afecta al requerimiento, sin embargo los datos que posee (porcentajes, creditos disponibles, etc) si son respetados.
 *
 * @param array &$evaluaciones
 * @param integer &$grado_analisis
 * @return array
 */
	static private function evaluarRegla(&$evaluaciones=null, &$grado_analisis=0){
		$fn_reset_calificaciones	= function(){
			$calificaciones	= [
				Evaluacion::BUENO			=> 0,
				Evaluacion::DESTACADO		=> 0,
				Evaluacion::MUY_DESTACADO	=> 0,
				Evaluacion::NO_EVALUADO		=> 0,
				'SIN_DATA'					=> 0,
				'OTRO'						=> 0,
				'BONIFICADO'				=> 0,
				'AUTORIDAD_SUPERIOR_SR'		=> 0,
			];
			return $calificaciones;
		};

		$grupos				= [];
		$grupo_incremental	= 0;
		$calificaciones		= $fn_reset_calificaciones();

		$cache_evaluaciones	= [];
		foreach ($evaluaciones as $anio => $ev) {
			$calificaciones_anterior	= $calificaciones;
			switch ($ev['id_calificacion']) {
				case Evaluacion::BUENO:
					$calificaciones[Evaluacion::BUENO]++;
					break;
				case Evaluacion::DESTACADO:
					$calificaciones[Evaluacion::DESTACADO]++;
					break;
				case Evaluacion::MUY_DESTACADO:
					$calificaciones[Evaluacion::MUY_DESTACADO]++;
					break;
				case Evaluacion::NO_EVALUADO:
					$calificaciones[Evaluacion::NO_EVALUADO]++;
					break;
				case null:
					$calificaciones['SIN_DATA']++;
					break;
				default:
					$calificaciones['OTRO']++;
					break;
			}
			// Las calificaciones aqui sitadas no deben tener Creditos Requeridos
			if($ev['id_calificacion'] == Evaluacion::REGULAR || $ev['id_calificacion'] == Evaluacion::DEFICIENTE || $ev['id_calificacion'] == Evaluacion::NO_EVALUADO){
				$ev['creditos_requeridos']	= 0;
			}
			if(!empty($ev['bonificado'])){
				$calificaciones['BONIFICADO']++;
			}
			if($ev['id_situacion_revista'] == Contrato::AUTORIDAD_SUPERIOR_SR){
				$calificaciones['AUTORIDAD_SUPERIOR_SR']++;
				$ev['creditos_requeridos']						= 0;
				$cache_evaluaciones[]							= $ev;
				continue;
			} else if ($calificaciones['AUTORIDAD_SUPERIOR_SR'] > 0){
				$ev['creditos_requeridos']						= 0;
				$cache_evaluaciones[]	= $ev;
			} else {
				$cache_evaluaciones[]	= $ev;
			}


			if($calificaciones['AUTORIDAD_SUPERIOR_SR'] > 0){
				$grupos[$grupo_incremental]						= $cache_evaluaciones;
				$grupos[$grupo_incremental]['id_motivo']		= static::REGLA_D;
				$grupos[$grupo_incremental]['grado_analisis']	= $grado_analisis;

				$cache_evaluaciones								= [];
				$calificaciones									= $fn_reset_calificaciones();
				$grupo_incremental++;
				$grado_analisis++;
			} else if($calificaciones[Evaluacion::DESTACADO] == 3 && $calificaciones['BONIFICADO'] == 2){
				$grupos[$grupo_incremental]						= $cache_evaluaciones;
				$grupos[$grupo_incremental]['id_motivo']		= static::REGLA_B;
				$grupos[$grupo_incremental]['grado_analisis']	= $grado_analisis;

				$cache_evaluaciones								= [];
				$calificaciones									= $fn_reset_calificaciones();
				$grupo_incremental++;
				$grado_analisis++;
			} else if($calificaciones['BONIFICADO'] == 3 && (
				$calificaciones_anterior[Evaluacion::DESTACADO] == 2 
				&& ($calificaciones[Evaluacion::DESTACADO] == 3 
					|| $calificaciones[Evaluacion::BUENO] == 1
					|| $calificaciones[Evaluacion::MUY_DESTACADO] == 1
				))
			){
				// Dentro del grupo conformado, evaluar que evaluacion es residuo y asignarle "creditos_requeridos = 0"
				array_walk($cache_evaluaciones, function(&$cache_ev){
					if(empty($cache_ev['bonificado'])){
						$cache_ev['creditos_requeridos']	= 0;
					}
				});

				$grupos[$grupo_incremental]						= $cache_evaluaciones;
				$grupos[$grupo_incremental]['id_motivo']		= static::REGLA_E;
				$grupos[$grupo_incremental]['grado_analisis']	= $grado_analisis;

				$cache_evaluaciones								= [];
				$calificaciones									= $fn_reset_calificaciones();
				$grupo_incremental++;
				$grado_analisis++;
			} else if (
				(($calificaciones[Evaluacion::DESTACADO] + $calificaciones[Evaluacion::BUENO] + $calificaciones[Evaluacion::MUY_DESTACADO]) == 3)
				&& $calificaciones['BONIFICADO'] == 0
				&& ($calificaciones[Evaluacion::DESTACADO] <= 2 && ($calificaciones[Evaluacion::BUENO] + $calificaciones[Evaluacion::MUY_DESTACADO]) >= 1)
			){
				$grupos[$grupo_incremental]						= $cache_evaluaciones;
				$grupos[$grupo_incremental]['id_motivo']		= static::REGLA_C;
				$grupos[$grupo_incremental]['grado_analisis']	= $grado_analisis;

				$cache_evaluaciones								= [];
				$calificaciones									= $fn_reset_calificaciones();
				$grupo_incremental++;
				$grado_analisis++;
			} else if($calificaciones[Evaluacion::DESTACADO] == 2 && $calificaciones['BONIFICADO'] == 0){
				$grupos[$grupo_incremental]						= $cache_evaluaciones;
				$grupos[$grupo_incremental]['id_motivo']		= static::REGLA_A;
				$grupos[$grupo_incremental]['grado_analisis']	= $grado_analisis;

				$cache_evaluaciones								= [];
				$calificaciones									= $fn_reset_calificaciones();
				$grupo_incremental++;
				$grado_analisis++;
			} else if($calificaciones['BONIFICADO'] > 0 && $calificaciones[Evaluacion::DESTACADO] == 0){
				// Si tiene bonificado y no es DESTACADO ignora el caso
				$cache_evaluaciones[count($cache_evaluaciones)-1]['creditos_requeridos']	= 0;
				$calificaciones									= $calificaciones_anterior;
			}
		}

		$aux_grupos	= [];
		foreach ($grupos as $indice => $ev) {
			$aux_grupo			= [];
			$id_motivo			= $ev['id_motivo'];
			$aux_grado_analisis	= $ev['grado_analisis'];
			unset($ev['grado_analisis']);
			unset($ev['id_motivo']);
			foreach ($ev as $_ev) {
				static::addToGroup($indice, $id_motivo, $aux_grupo, $_ev, $aux_grado_analisis);
			}
			$aux_grupos[$aux_grado_analisis]	= $aux_grupo;
		}

		return $aux_grupos;
	}

/**
 * Ejecuta la simulacion de promocion de grado para todos los agentes que sean SINEP - PLANTA PERMANENTE
 * Busca los agentes disponibles para promocionar, busca las evaluaciones de esos empleados, rellena las inexistentes partiendo desde la fecha de ultima promocion hasta el año actual menos uno, sigue completando posible informacion faltante, cuantifica creditos, arma los grupos segun las reglas de validacion, y los guarda en la base de datos.
 *
 * @return void
 */
	static public function runSimulacionGrados(){
		$ejecutar_constructor	= new static; // Si, es absurdo pero obligatorio.
		static $cache			= [];
		$agentes_analizables	= static::getAgentesVigentes();
		static::truncateGroupsToDB();

		foreach ($agentes_analizables as $agente) {
			$evaluaciones	= static::getEvaluacionesByEmpleadoId($agente['id'], $agente['fecha_ultima_promocion_grado']);
			if(empty($evaluaciones)){
				continue;
			}
			static::rellenarEvaluacionesInexistentes($evaluaciones, $agente['id'], $agente['fecha_ultima_promocion_grado']);
			/**
			 * $aux_grupos	= [
			 * 	(int)'id_grupo'	=> [
			 * 		0 => [
			 * 			'id_motivo'			=> static::REGLA_A,
			 * 			'grupo_incremental'	=> (int)'id_grupo',
			 * 			'anio'
			 * 		]
			 * 	]
			 * ];
			 */
			$aux_grupos			= [];
			$grado_analisis		= (int)$agente['nombre_grado'] + 1;

			foreach ($evaluaciones as $anio => &$evaluacion) {
				static::contarCreditos($evaluacion);
			}

			// Arma los grupos
			$aux_grupos			= static::evaluarRegla($evaluaciones,$grado_analisis);

			if(!empty($aux_grupos)){
				static::altaGroupsToDB($aux_grupos, $agente);
			}
		}
	}

/**
 * Ejecuta los Store Procedure que recuentan los creditos y porcentajes de cada empleado. Por ultimo Trunca la tabla de la simulacion.
 * @return void
 * @throws Exception
 */
	static private function truncateGroupsToDB(){
		$cnx	= new Conexiones();	
		$sql	= 'CALL UpdateCreditoCursosHistorico()';
		$resp	= $cnx->consulta(Conexiones::SELECT, $sql, []);
		if($cnx->errorCode	!== null){
			throw new Exception("Error al ejecutar Store Procedure UpdateCreditoCursosHistorico()", 1);
		}

		$sql	= 'CALL UpdatePorcentajeTituloCreditosHistorico()';
		$resp	= $cnx->consulta(Conexiones::SELECT, $sql, []);
		if($cnx->errorCode	!== null){
			throw new Exception("Error al ejecutar Store Procedure UpdatePorcentajeTituloCreditosHistorico()", 1);
		}

		static::calcularHistoricoCreditos();

		$sql	= 'CALL truncate_empleado_simulacion_promocion_grado_tmp()';
		$resp	= $cnx->consulta(Conexiones::SELECT, $sql, []);
		if($cnx->errorCode	!== null){
			throw new Exception("Error al truncar la tabla de simulacion", 1);
		}
	}

/**
 * Recibe los grupos de evaluaciones de un empleado en especifico, realiza el analicis de cada grupo y lo guarda en la DB.
 *
 * @param array $grupos
 * @param array $agente
 * @return bool
 * @throws Exception
 */
	static private function altaGroupsToDB($grupos=null, $agente=null){
		if(empty($grupos) || empty($agente)){
			return false;
		}
		$cnx	= new Conexiones();
		$aux_grupo	= null;
		foreach ($grupos as $grupo) {
			static::analizarAplicaGrupo($grupo, $aux_grupo);
			$aux_grupo	= $grupo;
			foreach ($grupo as $ev) {
				$campos		= [
					'id_empleado'				=> $agente['id'],
					'id_empleado_escalafon'		=> $agente['id_empleado_escalafon'],
					'fecha_ultima_promocion'	=> $agente['fecha_ultima_promocion_grado'],
					'grupo_incremental'			=> $ev['grupo_incremental'],
					'id_motivo'					=> $ev['id_motivo'],
					'anio'						=> $ev['anio'],
					'id_empleado_evaluacion'	=> $ev['id_empleado_evaluacion'],
					'bonificado'				=> $ev['bonificado'],
					'id_calificacion'			=> $ev['id_calificacion'],
					'grado_analisis'			=> $ev['grado_analisis'],
					'creditos_requeridos'		=> $ev['creditos_requeridos'],
					'creditos_reconocidos'		=> $ev['creditos_reconocidos'],
					'porcentaje_reconocido'		=> $ev['porcentaje_reconocido'],
					'creditos_disponibles'		=> $ev['creditos_disponibles'],
					'creditos_subtotal'			=> $ev['creditos_subtotal'],
					'total_periodo'				=> $ev['total_periodo'],
					'id_situacion_revista'		=> $ev['id_situacion_revista'],
					'id_nivel'					=> $ev['id_nivel'],
					'id_grado'					=> $ev['id_grado'],
					'id_tramo'					=> $ev['id_tramo'],
					// 'id_agrupamiento'			=> $ev['id_agrupamiento'],
					'aplica_promocion'			=> $ev['aplica_promocion'],
				];
				$sql_params	= [];
				foreach ($campos as $campo => $valor) {
					$sql_params[':'.$campo]	= $valor;
				}

				$sql			= 'INSERT INTO empleado_simulacion_promocion_grado_tmp('.implode(',',array_keys($campos)).') VALUES ('.implode(',',array_keys($sql_params)).')';
				$resp			= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
				if($resp === false){
					throw new \Exception("Error al guardar los datos en la tabla 'empleado_simulacion_promocion_grado_tmp'", 1);
				}
			}
		}
		return true;
	}

/**
 * Busca en DB y agrega: cantidad de creditos disponibles hasta el año evaluado, cantidad de creditos del año evaluado, porcentaje de creditos reconocidos del año evaluado, y cantidad de creditos reconocidos para el tramo y nivel del año evaluado.
 * Recibe la `$evaluacion` por referencia antes de ser agrupada, segun las reglas de agrupamiento, dentro de `static::runSimulacionGrados()`, agrega todos los indices necesarios para realizar el calculo de creditos y posibilidad de aplicar promocion.
 *
 * @param array &$evaluacion
 * @return void
 */
	static private function contarCreditos(&$evaluacion=null){
		static $cache	= [];
		$cnx			= new Conexiones();
		$evaluacion['creditos_restados']		= // Cantidad de creditos restados
		$evaluacion['creditos_subtotal']		= // Creditos restantes, luego de sumar y restar
		$evaluacion['porcentaje_reconocido']	= // Porcentaje acumulado de creditos reconocidos
		$evaluacion['creditos_disponibles']		= // Total de creditos hasta el periodo consultado
		$evaluacion['creditos_requeridos']		= // Total creditos requeridos en funcion del Nivel y Tramo
		$evaluacion['total_periodo']			= // Creditos obtenidos dentro del periodo
		$evaluacion['creditos_reconocidos']		= 0; // Creditos requeridos x ([porcentaje<=100]/100)

		$sql_params	= [
			':id_empleado'	=> $evaluacion['id_empleado'],
			':anio'			=> $evaluacion['anio'],
		];

		$sql	= <<<SQL
			SELECT creditos_disponibles AS total_hasta_periodo
			FROM empleado_historial_creditos ehc
			WHERE 
				id_empleado = :id_empleado 
				AND borrado = 0
				AND DATE_FORMAT(fecha_considerada, '%Y') <=:anio
			ORDER BY fecha_considerada DESC, id DESC
			LIMIT 1
SQL;
		$resp	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($resp[0]['total_hasta_periodo'])){
			$evaluacion['creditos_disponibles']	= (int)$resp[0]['total_hasta_periodo']; // Subtotal de creditos hasta el año evaluado
		}


		$sql	= <<<SQL
			SELECT (SUM(creditos_agregados)-SUM(creditos_descontados)) AS total_periodo, SUM(porcentaje) AS total_porcentaje_periodo
			FROM empleado_historial_creditos ehc
			WHERE 
				id_empleado = :id_empleado
				AND borrado = 0
				AND :anio =  DATE_FORMAT(fecha_considerada, '%Y')
			GROUP BY DATE_FORMAT(fecha_considerada, '%Y')
			ORDER BY fecha_considerada DESC, id DESC
			LIMIT 1
SQL;
		$resp	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);

		if(!empty($resp[0])){
			$evaluacion['porcentaje_reconocido']	= (int)$resp[0]['total_porcentaje_periodo'];	// Porcentaje reconocido dentro del año evaluado
			$evaluacion['total_periodo']			= (int)$resp[0]['total_periodo'];				// Total de creditos dentro del año evaluado
		}

		if($evaluacion['id_situacion_revista'] == Contrato::AUTORIDAD_SUPERIOR_SR){
			$evaluacion['creditos_requeridos']	= 0;
		} else if(empty($cache[$evaluacion['id_tramo']]) || empty($cache[$evaluacion['id_tramo']][$evaluacion['id_nivel']])){
			$sql_params	= [
				':id_tramo'	=> $evaluacion['id_tramo'],
				':id_nivel'	=> $evaluacion['id_nivel'],
				':anio'		=> $evaluacion['anio'],
			];
			$sql		= 'SELECT creditos FROM promocion_creditos WHERE id_nivel = :id_nivel AND id_tramo = :id_tramo AND borrado = 0 AND (:anio BETWEEN DATE_FORMAT(fecha_desde, \'%Y\') AND DATE_FORMAT(fecha_hasta, \'%Y\') OR ( fecha_hasta IS NULL) )ORDER BY id ASC LIMIT 1';
			$creditos_requeridos	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
			if(empty($creditos_requeridos[0]['creditos'])){
				$creditos_requeridos	= [
					0 => ['creditos'	=> 0]
				];
			}

			if(empty($cache[$evaluacion['id_tramo']])){
				$cache[$evaluacion['id_tramo']]	= [
					$evaluacion['id_nivel']	=> (int)$creditos_requeridos[0]['creditos'],
				];
			} else {
				$cache[$evaluacion['id_tramo']][$evaluacion['id_nivel']]	= (int)$creditos_requeridos[0]['creditos'];
			}
		}

		if($evaluacion['id_situacion_revista'] != Contrato::AUTORIDAD_SUPERIOR_SR){
			$evaluacion['creditos_requeridos']		= empty($cache[$evaluacion['id_tramo']][$evaluacion['id_nivel']])
				? 0
				: $cache[$evaluacion['id_tramo']][$evaluacion['id_nivel']];
		}

		return;
	}

/**
 * Analiza cada grupo e identifica si aplica la promocion en base a los creditos requeridos, total por periodo, reconocidos, y subtotales.
 * Tambien realiza las sumatorias y planifica cual es el total de creditos a descontar por cada grupo.
 * Recibe el grupo por referencia, antes de ser guardado en la tabla dentro del metodo `static::altaGroupsToDB()`.
 *
 * @param array &$grupo			- Grupo en analicis, modifica por referencia.
 * @param array $grupo_anterior - El grupo anterior al analizado en curso, usado para sumar los creditos sobrantes
 * @return void
 */
	static private function analizarAplicaGrupo(&$grupo=null, $grupo_anterior=null){
		$creditos_requeridos		=
		$total_periodo				=
		$porcentaje_total			= 
		$total_periodo_sobrante		= 
		$total_porcentaje_sobrante	= 
		$porcentaje_reconocido		=
		$ultimo_creditos_subtotal	= 0;
		
		if(is_array($grupo_anterior)){
			$ultima_ev				= array_pop($grupo_anterior);

			if($ultima_ev['aplica_promocion'] == true){
				$total_periodo_sobrante		= (int)round(((int)$ultima_ev['total_periodo']/2),0, PHP_ROUND_HALF_UP);
				$total_porcentaje_sobrante	= ($ultima_ev['porcentaje_reconocido'] > 100)
					? (int)$ultima_ev['porcentaje_reconocido'] - 100 
					: (int)$ultima_ev['porcentaje_reconocido'];
			} else {
				$total_periodo_sobrante		= (int)$ultima_ev['total_periodo'];
				$total_porcentaje_sobrante	= (int)$ultima_ev['porcentaje_reconocido'];
			}
		}

		foreach ($grupo as &$ev) {
			if($ev['id_motivo'] == static::REGLA_D){
				$ev['creditos_requeridos']	= 0;
			}
			$ev['creditos_requeridos']	= (int)$ev['creditos_requeridos'] + $creditos_requeridos;
			$creditos_requeridos		= (int)$ev['creditos_requeridos'];

			$ev['total_periodo']		= (int)$ev['total_periodo'] + $total_periodo;
			$total_periodo				= (int)$ev['total_periodo'];
			
			$ev['porcentaje_reconocido']= (int)$ev['porcentaje_reconocido'] + $porcentaje_reconocido;
			$porcentaje_reconocido		= (int)$ev['porcentaje_reconocido'];

			$ev['aplica_promocion']		= false;
		}

		foreach ($grupo as &$ev) {
			$porcentaje_total				= (int)$ev['porcentaje_reconocido'] + $total_porcentaje_sobrante;
			$porcentaje_total				= ($porcentaje_total > 100) ? 100 : $porcentaje_total;
			$ev['creditos_reconocidos']		= $ev['creditos_requeridos']*($porcentaje_total/100); // USADO EN EL CALCULO
			$ev['porcentaje_reconocido']	= (int)$ev['porcentaje_reconocido'] + $total_porcentaje_sobrante; // MOSTRADO EN LISTADO, USADO EN PROXIMO PERIODO

			$usables						= ((int)$ev['creditos_disponibles']-(int)round(((int)$ev['total_periodo']/2),0, PHP_ROUND_HALF_UP))+$total_periodo_sobrante;
			$quitables						= (int)$ev['creditos_requeridos']-(int)$ev['creditos_reconocidos'];

			$ultimo_creditos_restados		=
			$ev['creditos_restados']		= $quitables;

			$ultimo_resto_aplicable			= ($usables)-($quitables); // USADO PARA VALIDAR SI APLICA O NO
			$ev['creditos_subtotal']		= $ev['creditos_disponibles']-$quitables; // MOSTRADO EN PANTALLA, REFERECIAL PARA EL USUARIO
		}

		if($ultimo_resto_aplicable > 0 || $ultimo_creditos_restados == 0){
			foreach ($grupo as &$ev) {
				$ev['aplica_promocion']		= true;
			}
		}
		
	}

/**
 * Genera el calculo de creditos disponibles para la tabla `empleado_historial_creditos`.
 * Para cuando todo falla, este es el algoritmo que funciona.
 * @return void
 */
	static public function calcularHistoricoCreditos(){
		EmpleadoHistorialCreditos::calcularHistoricoCreditos(null);
	}
}