<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Conexiones;
use \FMT\Helper\Arr;
/**
 * Este modelo queda como recuerdo de una vieja funcionalidad, por motivos de mantenimiento se conserva como repositorio de funciones y variables.
 * Aqui podras encontrar todo lo relacionado con **Modalidades de Contratacion** y **Situaciones de Revista**
*/
class Contrato extends Modelo {
/** Modalidades de vinculacion exclusivas para mostrar
 * @var Array */
	static public $EXCLUSIVAS	= ['1', '2', '3'];

	const SINEP						= 1;
	const PRESTACION_SERVICIOS		= 2;
	const PERSONAL_EMBARCADO		= 3;
	const OTRA 						= 4;
	const EXTRAESCALAFONARIO		= 5;
	const AUTORIDAD_SUPERIOR 		= 6;
	
	static public $MODALIDAD_VINCULACION	= [
		self::SINEP						=> ['id'	=> self::SINEP, 'nombre'	=> 'SINEP', 'borrado' => '0'],
		self::PRESTACION_SERVICIOS		=> ['id'	=> self::PRESTACION_SERVICIOS, 'nombre'	=> 'Prestacion de Servicios', 'borrado' => '0'],
		self::PERSONAL_EMBARCADO		=> ['id'	=> self::PERSONAL_EMBARCADO, 'nombre'	=> 'Personal Embarcado', 'borrado' => '0'],
		self::OTRA						=> ['id'	=> self::OTRA, 'nombre'	=> 'Otra', 'borrado' => '0'],
		self::EXTRAESCALAFONARIO		=> ['id'	=> self::EXTRAESCALAFONARIO, 'nombre'	=> 'Extraescalafonario', 'borrado' => '0'],
		self::AUTORIDAD_SUPERIOR		=> ['id'	=> self::AUTORIDAD_SUPERIOR, 'nombre'	=> 'Autoridad Superior', 'borrado' => '0'],
	];

	const PLANTA_PERMANENTE 			 = 1;
	const LEY_MARCO						 = 2;
	const DESIGNACION_TRANSITORIA_PP_FE  = 3;
	const PP_MTR_DESIGNACION_TRANSITORIA = 4;
	const ONCE_CERO_NUEVE				 = 5;
	const ONCE_CERO_NUEVE_FE			 = 6;
	const ASISTENCIA_TECNICA 			 = 7;
	const CLM 							 = 8;
	const PLANTA_PERMANENTE_PE 			 = 9;
	const COMISION_SERVICIOS 			 = 10;
	const PP_DESIGNACION_TRANSITORIA 	 = 11;
	const GABINETE_ASESORES 			 = 12;
	const AUTORIDAD_SUPERIOR_SR			 = 13;
	const EXTRAESCALAFONARIO_SR			 = 14;
	const PP_MTR_DTFE 					 = 15;
	const ADSCRIPCION 					 = 16;
	const NO_APLICA_EX 					 = 17;
	const NO_APLICA_AS 					 = 18;

	static public $SITUACION_REVISTA	= [
		self::PLANTA_PERMANENTE					=> ['id'	=> self::PLANTA_PERMANENTE, 'nombre' => 'Planta Permanente', 'borrado' => '0'],
		self::LEY_MARCO							=> ['id'	=> self::LEY_MARCO, 'nombre' => 'Ley Marco', 'borrado' => '0'],
		self::DESIGNACION_TRANSITORIA_PP_FE		=> ['id'	=> self::DESIGNACION_TRANSITORIA_PP_FE, 'nombre' => 'Designacion Transitoria en Cargo de Planta Permanente con Funcion Ejecutiva', 'borrado' => '0'],
		self::PP_MTR_DESIGNACION_TRANSITORIA	=> ['id'	=> self::PP_MTR_DESIGNACION_TRANSITORIA, 'nombre' => 'Planta Permanente MTR con Designacion Transitoria', 'borrado' => '0'],
		self::ONCE_CERO_NUEVE					=> ['id'	=> self::ONCE_CERO_NUEVE, 'nombre' => '1109/17', 'borrado' => '0'],
		self::ONCE_CERO_NUEVE_FE				=> ['id'	=> self::ONCE_CERO_NUEVE_FE, 'nombre' => '1109/17 con Financiamiento Externo', 'borrado' => '0'],
		self::ASISTENCIA_TECNICA				=> ['id'	=> self::ASISTENCIA_TECNICA, 'nombre' => 'Asistencia Tecnica', 'borrado' => '0'],
		self::CLM								=> ['id'	=> self::CLM, 'nombre' => 'CLM', 'borrado' => '0'],
		self::PLANTA_PERMANENTE_PE				=> ['id'	=> self::PLANTA_PERMANENTE_PE, 'nombre' => 'Planta Permanente', 'borrado' => '0'],
		self::COMISION_SERVICIOS				=> ['id'	=> self::COMISION_SERVICIOS, 'nombre' => 'Comision de servicios', 'borrado' => '0'],
		self::PP_DESIGNACION_TRANSITORIA		=> ['id'	=> self::PP_DESIGNACION_TRANSITORIA, 'nombre' => 'Planta Permanente MTR con designacion Transitoria con funcion Ejecutiva', 'borrado' => '0'],
		self::GABINETE_ASESORES					=> ['id'	=> self::GABINETE_ASESORES, 'nombre' => 'Gabinete de Asesores', 'borrado' => '0'],
		self::AUTORIDAD_SUPERIOR_SR				=> ['id'	=> self::AUTORIDAD_SUPERIOR_SR, 'nombre' => 'Autoridad Superior', 'borrado' => '0'],
		self::EXTRAESCALAFONARIO_SR				=> ['id'	=> self::EXTRAESCALAFONARIO_SR, 'nombre' => 'Extraescalafonario', 'borrado' => '0'],
		self::PP_MTR_DTFE						=> ['id'	=> self::PP_MTR_DTFE, 'nombre' => 'Planta Permanente MTR con designacion Transitoria con funcion ejecutiva', 'borrado' => '0'],
		self::ADSCRIPCION						=> ['id'	=> self::ADSCRIPCION, 'nombre' => 'Adscripcion', 'borrado' => '0'],
		self::NO_APLICA_EX						=> ['id'	=> self::NO_APLICA_AS, 'nombre' => 'No Aplica', 'borrado' => '0'],
		self::NO_APLICA_AS						=> ['id'	=> self::NO_APLICA_EX, 'nombre' => 'No Aplica', 'borrado' => '0'],
	];

/**
 * Obtiene los valores de los array parametricos.
 * E.J.: Dependencia::getParam('MODALIDAD_VINCULACION');
*/
	static public function getParam($attr=null){
		if($attr === null || empty(static::${$attr})){
			return [];
		}
		return static::${$attr};
	}

/**
 * Busca los parametros relacionados con la modalidad de vinculacion y la situacion de revista.
 *
 * @param int $id_modalidad_vinculacion	- Alguno de los IDs en $MODALIDAD_VINCULACION
 * @param int $id_situacion_revista		- Alguno de los IDs en $SITUACION_REVISTA
 * @return array
	$return	= [
		'agrupamientos'	=> [
			'id'		=> '',
			'nombre'	=> '',
			'borrado'	=> '',
			'niveles'	=> [
				[
					'id'				=> '',
					'id_agrupamiento'	=> '',
					'nombre'			=> '',
					'borrado'			=> '',
				],
			],
		],
		'funciones_ejecutivas' => [
			'id'		=> '',
			'nombre'	=> '',
			'borrado'	=> '',
		],
		'tramos'	=> [
			'id'	=> '',
			'nombre'	=> '',
			'borrado'	=> '',
			'grados'	=> [
				[
					'id'	=> '',
					'id_tramo'	=> '',
					'nombre'	=> '',
					'borrado'	=> '',
				]
			]
		],
	];
 */
	static public function obtenerConvenio($id_modalidad_vinculacion=null,$id_situacion_revista=null){
		if(!(is_numeric($id_modalidad_vinculacion) && is_numeric($id_situacion_revista))) {
			return [];
		}
		$sql_params = [
			':id_modalidad_vinculacion'	=> $id_modalidad_vinculacion,
			':id_situacion_revista'		=> $id_situacion_revista,
		];

		$campos	= 'agr.' . implode(', agr.', [
			'id			AS agr_id',
			'nombre		AS agr_nombre',
			'borrado	AS agr_borrado',
		]);
		$campos	= $campos .', niv.' . implode(', niv.', [
			'id					AS niv_id',
			'id_agrupamiento	AS niv_id_agrupamiento',
			'nombre				AS niv_nombre',
			'borrado			AS niv_borrado',
		]);
		$campos	= $campos . ', fun.' . implode(', fun.', [
			'id			AS fun_id',
			'nombre		AS fun_nombre',
			'borrado	AS fun_borrado',
		]);
		$campos	= $campos . ', tra.' . implode(', tra.', [
			'id			AS tra_id',
			'nombre		AS tra_nombre',
			'borrado	AS tra_borrado',
		]);
		$campos	= $campos . ', gra.' . implode(', gra.', [
			'id			AS gra_id',
			'id_tramo	AS gra_id_tramo',
			'nombre		AS gra_nombre',
			'borrado	AS gra_borrado',
		]);

		$sql	= <<<SQL
			SELECT {$campos}
			FROM convenio_agrupamientos AS agr
				LEFT JOIN convenio_niveles AS niv ON (agr.id = niv.id_agrupamiento)
				LEFT JOIN convenio_tramos AS tra ON (tra.id_modalidad_vinculacion = :id_modalidad_vinculacion AND tra.id_situacion_revista = :id_situacion_revista)
				LEFT JOIN convenio_grados AS gra ON (tra.id = gra.id_tramo)
				LEFT JOIN convenio_funciones_ejecutivas AS fun ON (fun.id_modalidad_vinculacion = :id_modalidad_vinculacion AND fun.id_situacion_revista = :id_situacion_revista)
			WHERE
				agr.id_modalidad_vinculacion = :id_modalidad_vinculacion
				AND agr.id_situacion_revista = :id_situacion_revista
			ORDER BY gra.nombre ASC, fun.nombre ASC, tra.nombre ASC
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(empty($res)){
			return [
				'agrupamientos' 		=> [],
				'funciones_ejecutivas'	=> [],
				'tramos'				=> [],
			];
		}

		$return = [
			'agrupamientos' 		=> [],
			'funciones_ejecutivas'	=> [],
			'tramos'				=> [],
		];
		foreach ($res as $k => $val) {
// ------------------------------------------------------------------------- //
			if(!empty($val['agr_id']) || !empty($val['niv_id'])) {
				if(empty($return['agrupamientos'][ $val['agr_id'] ])) {
					$return['agrupamientos'][ $val['agr_id'] ] = [
						'id'		=> $val['agr_id'],
						'nombre'	=> $val['agr_nombre'],
						'borrado'	=> $val['agr_borrado'],
						'niveles'	=> [],
					];
				}

				$return['agrupamientos'][ $val['agr_id'] ]['niveles'][ $val['niv_id'] ]	= [
					'id'				=> $val['niv_id'],
					'id_agrupamiento'	=> $val['niv_id_agrupamiento'],
					'nombre'			=> $val['niv_nombre'],
					'borrado'			=> $val['niv_borrado'],
				];
			}
// ------------------------------------------------------------------------- //
			if(!empty($val['fun_id'])) {
				$return['funciones_ejecutivas'][$val['fun_id']] = [
					'id'		=> $val['fun_id'],
					'nombre'	=> $val['fun_nombre'],
					'borrado'	=> $val['fun_borrado'],
				];
			}
// ------------------------------------------------------------------------- //
			if(!empty($val['tra_id']) || !empty($val['gra_id'])) {
				if(empty($return['tramos'][$val['tra_id']])) {
					$return['tramos'][$val['tra_id']] = [
						'id'		=> $val['tra_id'],
						'nombre'	=> $val['tra_nombre'],
						'borrado'	=> $val['tra_borrado'],
					];
				}

				$return['tramos'][$val['tra_id']]['grados'][$val['gra_id']]	= [
					'id'		=> $val['gra_id'],
					'id_tramo'	=> $val['gra_id_tramo'],
					'nombre'	=> $val['gra_nombre'],
					'borrado'	=> $val['gra_borrado'],
				];
			}
// ------------------------------------------------------------------------- //
			unset($res[$k]);
		}
		return $return;
	}

/** @deprecated v8.0 */
	static public function obtener($id=null){return new static;}
/** @deprecated v8.0 */
	static public function listar() {return [new static];}
/** @deprecated v8.0 */
	public function alta(){return false;}
/** @deprecated v8.0 */
	public function baja(){return false;}
/** @deprecated v8.0 */
	public function modificacion(){return false;}
/** @deprecated v8.0 */
	public function validar() {return true;}
/** @deprecated v8.0 */
	static public function arrayToObject($resp=[]) {}

/**
 * Obterner Modalidades de Vinculacion y/o Situaciones de Revista.   
 * Si la modalidad de vinculacion es dada se devuelven la "Situaciones de Revista", caso contrario solo se devuelve la "Modalidad de Vinculacion"
 *
 * --- Respuesta si `$id_modalidad_vinculacion !== null` ---
```php
 	$return		= [
			'modalidad_vinculacion'	=> [
				int => ['id' => int, 'nombre' => string, 'borrado' => int]
			],
			'situacion_revista'		=> [
				int => ['id' => int, 'nombre' => string, 'borrado' => int]
			],
	];
```
 * --- Respuesta si `$id_modalidad_vinculacion === null` ---
```php
 	$return		= [
			'modalidad_vinculacion'	=> [
				int => ['id' => int, 'nombre' => string, 'borrado' => int]
			],
			'situacion_revista'		=> [
				(int)id_modalidad_vinculacion	=> [
					int => ['id' => int, 'nombre' => string, 'borrado' => int]
				]
			],
	];
```
 * @param	int|null	$id_modalidad_vinculacion	- ID Modalidad de Vinculacion
 * @param	bool 		$auth						- Default: false. Permite filtrar las modalidades de vinculacion por las permitidas para el ROL.
 * @param	bool		$no_auth_disable			- Default: false. Combinado con `$auth` en `true` permite marcar las modalidades de vinculacion no autorizadas con `borrado` **'1'**
 *
 * @return	array `['modalidad_vinculacion' => [], 'situacion_revista' => []]`
 */
	static public function obtenerVinculacionRevista($id_modalidad_vinculacion=null, $auth=false, $no_auth_disable=false){
		$cnx		= new Conexiones();
		$sql_params	= [];
		$return		= [
			'modalidad_vinculacion'	=> [],
			'situacion_revista'		=> [],
		];
		$where = '';
		if($auth) {
			$where = <<<SQL
					WHERE vinculacion.id IN (:mod_auth) 
SQL;
			$sql_params[':mod_auth'] = \App\Modelo\AppRoles::obtener_modalidades_vinculacion_autorizadas(); 
		}


		$sql		= <<<SQL
			SELECT id, nombre, borrado
			FROM
				convenio_modalidad_vinculacion AS vinculacion
			{$where}
			ORDER BY id ASC
SQL;
		$res		= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){
			foreach ($res as $value) {
				$borrado = (!$auth && $no_auth_disable && !in_array($value['id'], \App\Modelo\AppRoles::obtener_modalidades_vinculacion_autorizadas())) ? '1' : $value['borrado'];
				$return['modalidad_vinculacion'][$value['id']]	= ['id' => $value['id'], 'nombre' => $value['nombre'], 'borrado' => $borrado];
			}
		}

		$condicion	= '';
		if($id_modalidad_vinculacion !== null) {
			$sql_params	= [
				':id_modalidad_vinculacion'	=> $id_modalidad_vinculacion
			];
			$condicion	= "vinculacion.id = :id_modalidad_vinculacion";
		} else {
			$sql_params	= [];
		}

		if($auth) {
			$sql_params[':id_modalidad_vinculacion_mod_auth']	= \App\Modelo\AppRoles::obtener_modalidades_vinculacion_autorizadas();
			$sql_params[':id_situacion_revista_mod_auth']		= \App\Modelo\AppRoles::obtener_situaciones_revista_autorizadas();
			$condicion	= !empty($condicion) ? $condicion.' AND ' : '';
			$condicion	= $condicion.' revista.id_modalidad_vinculacion IN (:id_modalidad_vinculacion_mod_auth) AND revista.id IN (:id_situacion_revista_mod_auth) ';
		}

		$where	= !empty($condicion) ? ' WHERE '.$condicion : $condicion;
		$condicion	= !empty($condicion) ? ' AND '.$condicion : '';
		$sql	= <<<SQL
			SELECT
				revista.id,
				revista.id_modalidad_vinculacion,
				revista.nombre,
				revista.borrado
			FROM
				convenio_modalidad_vinculacion AS vinculacion
				INNER JOIN convenio_situacion_revista AS revista ON (revista.id_modalidad_vinculacion = vinculacion.id {$condicion} )
			{$where}
			ORDER BY revista.id ASC
SQL;
		$res	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(!empty($res)){

			foreach ($res as $value) {
				$borrado	= (!$auth && $no_auth_disable && !in_array($value['id'], \App\Modelo\AppRoles::obtener_situaciones_revista_autorizadas()))
					? '1' : $value['borrado'];

				if($id_modalidad_vinculacion !== null){
					$return['situacion_revista'][$value['id']]	= ['id' => $value['id'], 'nombre' => $value['nombre'], 'borrado' => $borrado];
				} else {
					$return['situacion_revista'][$value['id_modalidad_vinculacion']][$value['id']]	= ['id' => $value['id'], 'nombre' => $value['nombre'], 'borrado' => $borrado];
				}
			}
		}
		return $return;
	}

/**
 * Lista las situaciones de revista (todas)
 *
 * @return array
 */
	public static function listadoSituacionRevista(){
		$cnx = new Conexiones();
		$aux = [];

		$sql =
<<<SQL
		SELECT id, nombre, borrado FROM convenio_situacion_revista
		ORDER BY nombre ASC
SQL;

		$resultado = $cnx->consulta(Conexiones::SELECT, $sql);
		foreach($resultado as $value) {
			$aux[$value['id']] = ['id' => $value['id'], 'nombre' => $value['nombre'], 'borrado' => $value['borrado']];
		}
		return $aux;

	}

/**
 * Lista las modalidades de vinculacion (todas)
 *
 * @param boolean $auth	- Default: true. Permite filtrar las modalidades de vinculacion por las permitidas para el ROL.
 * @return array
 */
	public static function listadoModalidadVinculacion($auth=true){
		$cnx = new Conexiones();
		$aux = [];
		$sql_params = [];
		$where = '';
		if($auth) {
			$where = "WHERE id IN (:mod_auth) ";
			$sql_params[':mod_auth'] = \App\Modelo\AppRoles::obtener_modalidades_vinculacion_autorizadas(); 
		}
		$sql =
<<<SQL
		SELECT id, nombre, borrado FROM convenio_modalidad_vinculacion
		$where
		ORDER BY nombre ASC
SQL;

		$resultado = $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		foreach($resultado as $value) {
			$aux[$value['id']] = ['id' => $value['id'], 'nombre' => $value['nombre'], 'borrado' => $value['borrado']];
		}
		return $aux;
	}
}
