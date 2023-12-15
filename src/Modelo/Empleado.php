<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use App\Modelo\Presupuesto;
use App\Modelo\Dependencia;
use App\Modelo\AppRoles;
use FMT\Logger;

class Empleado extends Modelo {
/** @var int */
	public $id;
/** @var decimal(11,0) */
	public $cuit;
/** @var string */
	public $email;
/** @var int */
	public $planilla_reloj;
/** @var string */
	public $antiguedad;
/** @var int */
	public $veterano_guerra;
/** @var StdClass */
	public $en_comision;
/** @var int */
	public $credencial;
/** @var object - empleado_horario*/
	public $horario;
/** @var object - empleado_horas_extras */
	public $horas_extras;
/**@var array telefonos*/
	public $telefonos;
/** @var Persona:: */
	public $persona;
/** @var Dependencia:: */
	public $dependencia;
/** @var Ubicacion:: */
	public $ubicacion;
/** @var Situacion Escalafonaria:: */
	public $situacion_escalafonaria;
/** @var Presupuesto:: */
	public $presupuesto;
/** @var Perfil de puesto:: */
	public $perfil_puesto;	
/** @var licencias_especiales*/
	public $licencia;
/** @var estado*/
	public $estado;	
/** @var Date  Fecha de vencimiento de credencial de acceso.*/
	public $fecha_vencimiento;
/** @var Date */
	public $fecha_baja;
/** @var string */
	public $id_motivo;
/** @var int */
	public $id_sindicato;
/** @var Date */
	public $fecha_vigencia_mandato;
/** @var StdClass */
	public $empleado_salud;
/** @var StdClass */
	public $empleado_seguro;	
/** @var StdClass */
	public $sindicato = [];
/** @var int */
	public $id_empleado;
/** @var int */
	public $id_seguro;
	/** @var datetime */
	public $fecha_desde;
/** @var datetime */
	public $fecha_hasta;
/** @var StdClass */
	public $empleado_ultimos_cambios;
/** @var StdClass */
	public $empleado_cursos = [];
/** @var Evaluacion:: */
	public $evaluaciones = [];


	const TURNO_MANIANA	= 1;
	const TURNO_TARDE	= 2;
	protected static $TURNO	= [
		self::TURNO_MANIANA	=> ['id'	=> self::TURNO_MANIANA, 'nombre'	=> 'Mañana', 'borrado' => '0'],
		self::TURNO_TARDE	=> ['id'	=> self::TURNO_TARDE, 'nombre'	=> 'Tarde', 'borrado' => '0']
	];

	const PART_TIME			= 1;
	const FULL_TIME 		= 2;
	const EN_CICLO			= 3;
	const JORNADA_REDUCIDA	= 4;
	protected static $JORNADA_LABORAL = [
		self::PART_TIME			=> ['id'=> self::PART_TIME, 'nombre'=>'Part Time'],
		self::FULL_TIME			=> ['id'=> self::FULL_TIME, 'nombre'=>'Full Time'],
		self::EN_CICLO			=> ['id'=> self::EN_CICLO, 'nombre'=>'En Ciclo'],
		self::JORNADA_REDUCIDA	=> ['id'=> self::JORNADA_REDUCIDA, 'nombre'=>'Jornada Reducida'],
	];

	const EMPLEADO_ACTIVO 	= 1;
	const EMPLEADO_INACTIVO = 2;
	const EMPLEADO_POSTULANTE = 3;
	const EMPLEADO_RECHAZADO= 4;

	public static $TIPO_ESTADOS_API	= [
		'EMPLEADO_ACTIVO'			=> self::EMPLEADO_ACTIVO,
		'EMPLEADO_INACTIVO'			=> self::EMPLEADO_INACTIVO,
		'EMPLEADO_POSTULANTE'		=> self::EMPLEADO_POSTULANTE,
		'EMPLEADO_RECHAZADO'		=> self::EMPLEADO_RECHAZADO,
	];

	public static $TIPO_ESTADOS_EMPLEADOS = [
		self::EMPLEADO_ACTIVO			=> ['id'=> self::EMPLEADO_ACTIVO, 	'nombre'=>'Activo', 	'borrado' => 0],
		self::EMPLEADO_INACTIVO			=> ['id'=> self::EMPLEADO_INACTIVO, 'nombre'=>'Inactivo', 'borrado' => 0] ,
	];

	const SECUNDARIO				= 1;
	const UNIVERSITARIO				= 2;
	const TERCIARIO					= 3;
	const ESPECIALIZACION_AVANZADA	= 4;
	const ESPECIALIZACION			= 5;
	const EXPERIENCIA_6				= 6;
	const EXPERIENCIA_3				= 7;
	const EXPERIENCIA_3A6			= 8;
	const TERCIARIO_EXPERIENCIA		= 9;
	const EXPERIENCIA_10 			= 10;
	const SECUNDARIO_EXPERIENCIA	= 11;
	const EXPERIENCIA_LABORAL		= 12;
	const CONOCIMIENTOS				= 13;
	const MAYOR_16					= 14;
	

	static protected $EXCEPCION_ART_14 	= [
		self::SECUNDARIO				=> ['id'	=> self::SECUNDARIO, 	'nombre' => 'Título Secundario', 'borrado' => '0'],
		self::UNIVERSITARIO				=> ['id'	=> self::UNIVERSITARIO, 	'nombre' => 'Título Universitario de Grado', 'borrado' => '0'],
		self::TERCIARIO					=> ['id'	=> self::TERCIARIO, 'nombre' => 'Título Terciario', 'borrado' => '0'],
		self::ESPECIALIZACION_AVANZADA	=> ['id'	=> self::ESPECIALIZACION_AVANZADA, 'nombre' => 'Especialización avanzada acreditable mediante Postgrado', 'borrado' => '0'],
		self::ESPECIALIZACION			=> ['id'	=> self::ESPECIALIZACION, 'nombre' => 'Especialización en los campos profesionales acreditable mediante estudios o cursos', 'borrado' => '0'],
		self::EXPERIENCIA_6				=> ['id'	=> self::EXPERIENCIA_6, 'nombre'	=> 'Experiencia laboral no inferior a 6 años post-titulación', 'borrado' => '0'],
		self::EXPERIENCIA_3				=> ['id'	=> self::EXPERIENCIA_3, 'nombre'	=> 'Experiencia laboral no inferior a 3 años post-titulación', 'borrado' => '0'],
		self::EXPERIENCIA_3A6			=> ['id'	=> self::EXPERIENCIA_3A6, 'nombre' => 'Experiencia laboral no inferior a 3 años post-titulación, o, 6 años en total', 'borrado' => '0'],
		self::TERCIARIO_EXPERIENCIA		=> ['id'	=> self::TERCIARIO_EXPERIENCIA, 'nombre' => 'Título terciario + experiencia laboral no inferior a 3 años post-titulación, o, 6 años en total', 'borrado' => '0'],
		self::EXPERIENCIA_10 			=> ['id'	=> self::EXPERIENCIA_10, 'nombre' => 'Experiencia laboral no inferior a 10', 'borrado' => '0'],
		self::SECUNDARIO_EXPERIENCIA	=> ['id'	=> self::SECUNDARIO_EXPERIENCIA, 'nombre' => 'Titulo Secundario Técnico + experiencia laboral no inferior a 1 año y medio post-titulación', 'borrado' => '0'],
		self::EXPERIENCIA_LABORAL		=> ['id'	=> self::EXPERIENCIA_LABORAL, 'nombre' => 'Experiencia laboral afín de al menos 6 meses', 'borrado' => '0'],
		self::CONOCIMIENTOS				=> ['id'	=> self::CONOCIMIENTOS, 'nombre' => 'Conocimientos y capacitaciones específicas afin a las tareas a desarrollar', 'borrado' => '0'],
		self::MAYOR_16					=> ['id'	=> self::MAYOR_16, 'nombre' => 'Mayor de 16 años', 'borrado' => '0'],

	];

	const ESTADO_ACTIVO		= 1;
	const ESTADO_INACTIVO	= 2;
	static protected $ESTADOS	= [
		self::EMPLEADO_ACTIVO	=> ['id' => self::EMPLEADO_ACTIVO, 		'nombre' => 'SI', 'borrado' => '0'],
		self::EMPLEADO_INACTIVO	=> ['id' => self::EMPLEADO_INACTIVO,	'nombre' => 'NO', 'borrado' => '0'],
	];

	

	static public function getParam($attr=null){
		if($attr === null || empty(static::${$attr})){
			return [];
		}
		return static::${$attr};
	}

	static public function obtener($cuit=null, $is_id=false){

		$obj	= new static;
		if($cuit===null){
			$return	= static::arrayToObject();
			return static::borrarContiene($return);
		}
		$sql_params	= [
			!$is_id ? ':cuit' : ':id'	=> $cuit,
		];
		$campos	= 'emp.' . implode(', emp.', [
			'id',
			'borrado',
			'id_persona',
			'cuit',
			'email',
			'planilla_reloj',
			'antiguedad_adm_publica',
			'en_comision',
			'credencial',
			'fecha_vencimiento',
			'estado',
			'fecha_baja',
			'id_motivo',
			'id_sindicato',
			'fecha_vigencia_mandato',
			'veterano_guerra'
		]);
		$campos	= $campos . ', e_comision.' . implode(', e_comision.', [
			'id 					AS com_id',
			'id_comision_origen 	AS com_id_comision_origen',
			'id_comision_destino	AS com_id_comision_destino',
			'fecha_inicio 			AS com_fecha_inicio',
			'fecha_fin 				AS com_fecha_fin',
		]);
		$campos	= $campos . ', e_dependencia.' . implode(', e_dependencia.', [
			'id					AS dep_id',
			'id_dependencia		AS dep_id_dependencia',
			'fecha_desde		AS dep_fecha_desde',
			'fecha_hasta		AS dep_fecha_hasta',
		]);
		$campos	= $campos . ', e_dep_informal.' . implode(', e_dep_informal.', [
			'id					AS dep_id_informal',
			'id_dep_informal   AS dep_id_dep_informal'
		]);
		$campos	= $campos . ', e_horarios.' . implode(', e_horarios.', [
			'id				AS hor_id',
			'horarios		AS hor_horarios',
			'id_turno		AS hor_id_turno',
			'fecha_inicio	AS hor_fecha_inicio',
			'fecha_fin		AS hor_fecha_fin',
		]);
		$campos	= $campos . ', e_horas_extras.' . implode(', e_horas_extras.', [
			'id				AS hor_ex_id',
			'anio			AS hor_ex_anio',
			'mes			AS hor_ex_mes',
			'acto_administrativo	AS hor_ex_acto_adm',
		]);
		$campos	= $campos . ', e_ubicacion.' . implode(', e_ubicacion.', [
			'id				AS ubi_id',
			'id_ubicacion	AS ubi_id_ubicacion',
			'fecha_desde	AS ubi_fecha_desde',
			'fecha_hasta	AS ubi_fecha_hasta',
		]);
		$campos	= $campos . ', e_escalafon.' . implode(', e_escalafon.', [
			'id							AS esc_id',
			'id_modalidad_vinculacion	AS esc_id_modalidad_vinculacion',
			'id_situacion_revista		AS esc_id_situacion_revista',
			'id_nivel					AS esc_id_nivel',
			'id_grado					AS esc_id_grado',
			'id_grado_liquidacion		AS esc_id_grado_liquidacion',
			'id_tramo					AS esc_id_tramo',
			'id_agrupamiento			AS esc_id_agrupamiento',
			'id_funcion_ejecutiva		AS esc_id_funcion_ejecutiva',
			'compensacion_geografica	AS esc_compensacion_geografica',
			'compensacion_transitoria	AS esc_compensacion_transitoria',
			'fecha_inicio				AS esc_fecha_inicio',
			'fecha_fin					AS esc_fecha_fin',
			//'ultimo_cambio_nivel		AS esc_ultimo_cambio_nivel',
			'exc_art_14					AS esc_exc_art_14',
			'unidad_retributiva 		AS esc_unidad_retributiva',
		]);

		$campos	= $campos . ', e_presupuesto.' . implode(', e_presupuesto.', [
			'id					AS pre_id',
			'id_presupuesto		AS pre_id_presupuesto',
			'fecha_desde		AS pre_fecha_desde',
			'fecha_hasta		AS pre_fecha_hasta',

		]);

		$campos	= $campos . ', e_licencias.' . implode(', e_licencias.', [
			'id				AS lic_id',
			'id_licencia	AS lic_id_licencia',
			'fecha_desde	AS lic_fecha_desde',
			'fecha_hasta	AS lic_fecha_hasta',
		]);


		 $campos	= $campos . ', e_salud.' . implode(', e_salud.', [
		 	'id				AS sal_id',
		 	'id_obra_social	AS sal_id_obra_social',
		 ]);

		 $campos	= $campos . ', e_seg.' . implode(', e_seg.', [
		 	'id_registros	AS seg_id_registro',
		 	'seguros		AS seg_id_seguro',
		 ]);

		$campos	= $campos . ', e_cursos.' . implode(', e_cursos.', [
			'id				AS cur_id',
			'id_curso		AS cur_id_curso',
			'fecha			AS cur_fecha',
		]);

		 $campos	= $campos . ", '' e_cambios";

		if($is_id) {
			$where	= "emp.id = :id";
		} else {
			$where	= "emp.cuit = :cuit";
		}
		$mes = date('m');
		$anio = date('Y'); 
		$sql	= <<<SQL
			SELECT {$campos}
			FROM empleados AS emp
				LEFT JOIN empleado_dependencia AS e_dependencia ON (e_dependencia.id_empleado = emp.id AND ISNULL( e_dependencia.fecha_hasta) AND e_dependencia.borrado = 0)
				LEFT JOIN empleado_dep_informales AS e_dep_informal ON (e_dep_informal.id_empleado = emp.id AND ISNULL( e_dep_informal.fecha_hasta) AND e_dep_informal.borrado = 0)
				LEFT JOIN empleado_comision AS e_comision ON (e_comision.id_empleado = emp.id AND emp.en_comision = 1 AND e_comision.fecha_fin IS NULL)
				LEFT JOIN empleado_horarios AS e_horarios ON (e_horarios.id_empleado = emp.id AND ISNULL( e_horarios.fecha_fin) AND e_horarios.borrado = 0)
				LEFT JOIN empleado_horas_extras AS e_horas_extras ON (e_horas_extras.id_empleado = emp.id AND  e_horas_extras.borrado = 0 AND e_horas_extras.mes = '$mes' AND e_horas_extras.anio = '$anio')
				LEFT JOIN empleados_x_ubicacion AS e_ubicacion ON (e_ubicacion.id_empleado = emp.id AND ISNULL(e_ubicacion.fecha_hasta))
				LEFT JOIN empleado_escalafon AS e_escalafon ON (e_escalafon.id_empleado = emp.id AND ISNULL( e_escalafon.fecha_fin))
				LEFT JOIN empleado_presupuesto AS e_presupuesto ON (e_presupuesto.id_empleado = emp.id AND ISNULL(e_presupuesto.fecha_hasta))
				LEFT JOIN empleados_lic_especiales AS e_licencias ON (e_licencias.id_empleado = emp.id AND (e_licencias.fecha_hasta >= now() OR e_licencias.fecha_hasta IS NULL) AND e_licencias.borrado = 0 )
				LEFT JOIN (SELECT id_empleado id, group_concat(id_seguro) as seguros, group_concat(id) as id_registros FROM empleado_seguros WHERE ISNULL(fecha_hasta) GROUP BY id_empleado) e_seg ON e_seg.id = emp.id  
				LEFT JOIN empleado_salud AS e_salud ON (e_salud.id_empleado = emp.id  AND ISNULL (e_salud.fecha_hasta) )
				LEFT JOIN empleado_cursos AS e_cursos ON (e_cursos.id_empleado = emp.id  AND e_cursos.borrado = 0 ) 

			WHERE emp.borrado = 0 AND {$where}
			LIMIT 1
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);

		if(!empty($res)){
			$return	= static::arrayToObject($res[0]);
			return static::borrarContiene($return);
		}

		$return	= static::arrayToObject();

		return static::borrarContiene($return);
	}

	static public function listar() {
		$campos	= 'emp.' . implode(', emp.', [
			'id',
			'borrado',
			'id_persona',
			'cuit',
			'email',
			'planilla_reloj',
			'antiguedad_adm_publica',
			'en_comision',
			'credencial',
			'fecha_vencimiento',
			'fecha_baja',
			'id_motivo',
		]);
		$campos	= $campos . ', e_comision.' . implode(', e_comision.', [
			'id_dependencia_destino	AS com_id_dependencia_destino',
			'fecha_inicio 			AS com_fecha_inicio',
			'fecha_fin 				AS com_fecha_fin',
		]);
		$campos	= $campos . ', e_dependencia.' . implode(', e_dependencia.', [
			'id_dependencia		AS dep_id_dependencia',
			'fecha_desde		AS dep_fecha_desde',
			'fecha_hasta		AS dep_fecha_hasta',
		]);
		$campos	= $campos . ', e_horarios.' . implode(', e_horarios.', [
			'id				AS hor_id',
			'horarios		AS hor_horarios',
			'id_turno		AS hor_id_turno',
			'fecha_inicio	AS hor_fecha_inicio',
			'fecha_fin		AS hor_fecha_fin',
		]);
		$campos	= $campos . ', e_horas_extras.' . implode(', e_horas_extras.', [
			'id				AS hor_ex_id',
			'anio			AS hor_ex_anio',
			'mes			AS hor_ex_mes',
			'acto_administrativo		AS hor_ex_acto_adm',
		]);
		$campos	= $campos . ', e_ubicacion.' . implode(', e_ubicacion.', [
			'id				AS ubi_id',
			'id_ubicacion	AS ubi_id_ubicacion',
			'fecha_desde	AS ubi_fecha_desde',
			'fecha_hasta	AS ubi_fecha_hasta',
		]);
		$campos	= $campos . ', e_licencias.' . implode(', e_licencias.', [
			'id				AS lic_id',
			'id_licencia	AS lic_id_licencia',
			'fecha_desde	AS lic_fecha_desde',
			'fecha_hasta	AS lic_fecha_hasta',
		]);
		$campos	= $campos . ', e_salud.' . implode(', e_salud.', [
			'id				AS sal_id',
			'id_obra_social	AS sal_id_obra_social',
		]);
		$campos	= $campos . ', e_seguros.' . implode(', e_seguros.', [
			'id				AS seg_id',
			'id_seguro		AS seg_id_seguro',
		]);

		$where	= 'emp.borrado = 0';
		$where	.= ' AND ISNULL(fecha_baja) ';

		$sql	= <<<SQL
			SELECT {$campos}
			FROM empleados AS emp
				LEFT JOIN empleado_dependencia AS e_dependencia ON (e_dependencia.id_empleado = emp.id AND e_dependencia.borrado = 0 AND emp.borrado = 0)
				LEFT JOIN empleado_dep_informales AS e_dep_informales ON (e_dep_informales.id_empleado = emp.id AND e_dep_informales.borrado = 0 AND emp.borrado = 0)
				LEFT JOIN empleado_comision AS e_comision ON (e_comision.id_empleado = emp.id AND emp.en_comision = 1 AND emp.borrado = 0)
				LEFT JOIN empleado_horarios AS e_horarios ON (e_horarios.id_empleado = emp.id AND e_horarios.borrado = 0 AND emp.borrado = 0)
				LEFT JOIN empleado_horas_extras AS e_horas_extras ON (e_horas_extras.id_empleado = emp.id AND  e_horas_extras.borrado = 0)
				LEFT JOIN empleados_x_ubicacion AS e_ubicacion ON (e_ubicacion.id_empleado = emp.id)
				LEFT JOIN empleados_lic_especiales AS e_licencias ON (e_licencias.id_empleado = emp.id AND e_licencias.borrado = 0)
			WHERE {$where}
			ORDER BY emp.id DESC
SQL;
		$resp	= (array)(new Conexiones())->consulta(Conexiones::SELECT, $sql);
		if(empty($resp)) { 
			static::borrarContiene();
			return [];
		}
		foreach ($resp as &$value) {
			$value	= static::arrayToObject($value);
		}
		return static::borrarContiene($resp);
	}

	public function alta(){
		if(!$this->validar()){
			return false;
		}
		$cnx	= new Conexiones();
		$this->errores	= [];
// empleados
		if(empty($this->id)){
			$campos	= [
				'id_persona',
				'cuit',
				'email',
				'planilla_reloj',
				'en_comision',
				'credencial',
				'id_motivo',
				'fecha_baja',
				'id_sindicato',
				'fecha_vigencia_mandato',
			];
			$sql_params	= [
				':id_persona'		=> $this->persona->id,
				':cuit'				=> $this->cuit,
				':email'			=> $this->email,
				':planilla_reloj'	=> $this->planilla_reloj,
				':en_comision'		=> (int)!empty($this->en_comision->id),
				':credencial'		=> $this->credencial,
				':id_motivo'		=> $this->id_motivo,
				':fecha_baja'		=> $this->fecha_baja,
				':id_sindicato'		=> $this->id_sindicato,
				':fecha_vigencia_mandato'	=> $this->fecha_vigencia_mandato,
			];

			if(!empty($this->estado)){
				$campos[]				= 'estado';
				$sql_params[':estado']	= $this->estado;
			}
			if($this->fecha_vencimiento instanceof \DateTime) {
				$campos[] = 'fecha_vencimiento = :fecha_vencimiento';
				$sql_params[':fecha_vencimiento'] = $this->fecha_vencimiento->format('Y-m-d');
			}

			if($this->fecha_baja instanceof \DateTime){
				$sql_params[':fecha_baja']	= $this->fecha_baja->format('Y-m-d');
			}
			$sql	= 'INSERT INTO empleados('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
			$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
			if($res !== false){
				$this->id	= $res;
				$datos = (array) $this;
				$datos['modelo'] = 'Empleado';
				Logger::event('alta', $datos);
			} else {
				$this->errores['empleados']	= $cnx->errorInfo[2];
				return false;
			}
		}

		if(empty($this->dependencia->id)) {
			$if_null	= true;
			$campos	= [
				'id_dependencia',
				'fecha_desde',
				'fecha_hasta',
			];
			$sql_params	= [
				':id_empleado'		=> $this->id,
				':id_dependencia'	=> $this->dependencia->id_dependencia,
				':fecha_desde'		=> $this->dependencia->fecha_desde,
				':fecha_hasta'		=> $this->dependencia->fecha_hasta,
			];
			foreach ($campos as $campo) {
				$sql_params[':'.$campo]	= $this->dependencia->{$campo};
				if(!empty($this->dependencia->{$campo}) && $if_null) {
					$if_null = false;
				}
			}

			$campos[]	= 'id_empleado';

			if($this->dependencia->fecha_desde instanceof \DateTime){
				$sql_params[':fecha_desde']	= $this->dependencia->fecha_desde->format('Y-m-d');
			}
			if($this->dependencia->fecha_hasta instanceof \DateTime){
				$sql_params[':fecha_hasta']	= $this->dependencia->fecha_hasta->format('Y-m-d');
			}

			if(!$if_null) {
        $sql	= 'UPDATE empleado_dependencia SET fecha_hasta = NOW(), borrado = 1 WHERE id_empleado = '.$this->id.' AND borrado = 0';
        $cnx->consulta(Conexiones::UPDATE, $sql, []);

        $sql	= 'INSERT INTO empleado_dependencia('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
        $res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
        if($res !== false){
          $this->dependencia->id	= $res;
          $this->alta_dependencia_informal();
        } else {
          $this->errores['empleado_dependencia']	= $cnx->errorInfo[2];
        }
        
			}
		}


		if(empty($this->licencia->id) && !empty($this->licencia->id_licencia)) {
			$campos	= [
				'id_empleado',
				'id_licencia',
				'fecha_desde',
				'fecha_hasta',
			];
			$sql_params	= [
				':id_empleado'		=> $this->id,
				':id_licencia'	    => $this->licencia->id_licencia,
				':fecha_desde'		=> $this->licencia->fecha_desde,
				':fecha_hasta'		=> $this->licencia->fecha_hasta,
			];

			if($this->licencia->fecha_desde instanceof \DateTime){
				$sql_params[':fecha_desde']	= $this->licencia->fecha_desde->format('Y-m-d');
			}
			if($this->licencia->fecha_hasta instanceof \DateTime){
				$sql_params[':fecha_hasta']	= $this->licencia->fecha_hasta->format('Y-m-d');
			}

			$sql	= 'INSERT INTO empleados_lic_especiales('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
			$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
			return str_replace(array_keys($sql_params), array_values($sql_params), $sql);


			if($res !== false){
				$this->licencia->id	= $res;
			} else {
				$this->errores['empleados_lic_especiales']	= $cnx->errorInfo[2];
			}
		}
		return true;
	}


	public function alta_situacion_escalafonaria(){
			$cnx	= new Conexiones();
			$campos	= [
				'id_empleado',
				'id_modalidad_vinculacion',
				'id_situacion_revista',
				'id_nivel',
				'id_grado',
				'id_tramo',
				'id_agrupamiento',
				'id_funcion_ejecutiva',
				'compensacion_geografica',
				'compensacion_transitoria',
				'fecha_inicio',
				'fecha_fin',
				//'ultimo_cambio_nivel',
				'exc_art_14',
				'unidad_retributiva',
				'id_grado_liquidacion',
			];

			$sql_params	= [
				':id_empleado'	=> $this->id,
				':id_modalidad_vinculacion'	=> $this->situacion_escalafonaria->id_modalidad_vinculacion,
				':id_situacion_revista'	=> $this->situacion_escalafonaria->id_situacion_revista,
				':id_nivel'	=> !empty($tmp = $this->situacion_escalafonaria->id_nivel) ? $tmp : null,
				':id_grado'	=> !empty($tmp = $this->situacion_escalafonaria->id_grado) ? $tmp : null,
				':id_tramo'	=> !empty($tmp = $this->situacion_escalafonaria->id_tramo) ? $tmp : null,
				':id_agrupamiento'	=> !empty($tmp = $this->situacion_escalafonaria->id_agrupamiento) ? $tmp : null,
				':id_funcion_ejecutiva'	=> !empty($tmp = $this->situacion_escalafonaria->id_funcion_ejecutiva) ? $tmp : null,
				':compensacion_geografica'	=> !empty($tmp = $this->situacion_escalafonaria->compensacion_geografica) ? $tmp : null,
				':compensacion_transitoria'	=> !empty($tmp = $this->situacion_escalafonaria->compensacion_transitoria) ? $tmp : null,
				':fecha_inicio'	=> !empty($tmp = $this->situacion_escalafonaria->fecha_inicio) ? $tmp : null,
				':fecha_fin'	=> !empty($tmp = $this->situacion_escalafonaria->fecha_fin) ? $tmp : null,
				//':ultimo_cambio_nivel'	=> $this->situacion_escalafonaria->ultimo_cambio_nivel,
				':exc_art_14' => $this->situacion_escalafonaria->exc_art_14,
				':unidad_retributiva' => !empty($tmp = $this->situacion_escalafonaria->unidad_retributiva) ? $tmp : null,
				':id_grado_liquidacion'	=> !empty($tmp = $this->situacion_escalafonaria->id_grado_liquidacion) ? $tmp : null,
			];

			if($this->situacion_escalafonaria->fecha_inicio instanceof \DateTime){
				$sql_params[':fecha_inicio'] = $this->situacion_escalafonaria->fecha_inicio->format('Y-m-d');
			}
			if($this->situacion_escalafonaria->fecha_fin instanceof \DateTime){
				$sql_params[':fecha_fin']	= $this->situacion_escalafonaria->fecha_fin->format('Y-m-d');
			}
			// if($this->situacion_escalafonaria->ultimo_cambio_nivel instanceof \DateTime){
			// 	$sql_params[':ultimo_cambio_nivel']	= $this->situacion_escalafonaria->ultimo_cambio_nivel->format('Y-m-d');
			// }

			$sql	= 'INSERT INTO empleado_escalafon('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';

			$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
			$datos = [];
			if($res !== false){
				$this->situacion_escalafonaria->id	= $res;
				$datos				= (array)$this;
				$datos['modelo']	= 'Empleado';
			} else {
				$this->errores['empleado_escalafon'] = $cnx->errorInfo[2];
			}
			Logger::event('alta_situacion_escalafonaria', $datos);
		
	}

	public function modificacion_situacion_escalafonaria(){
		$cnx	= new Conexiones();
		if(!empty($this->situacion_escalafonaria->id)) {
			if($this->situacion_escalafonaria->id_modalidad_vinculacion != \App\Modelo\Contrato::PRESTACION_SERVICIOS && !empty($this->situacion_escalafonaria->unidad_retributiva)){
				$this->situacion_escalafonaria->unidad_retributiva = null;
			}
			$campos	= [
				'id_empleado =:id_empleado',
				'id_modalidad_vinculacion =:id_modalidad_vinculacion',
				'id_situacion_revista =:id_situacion_revista',
				'id_nivel =:id_nivel',
				'id_grado =:id_grado',
				'id_tramo =:id_tramo',
				'id_agrupamiento =:id_agrupamiento',
				'id_funcion_ejecutiva =:id_funcion_ejecutiva',
				'compensacion_geografica =:compensacion_geografica',
				'compensacion_transitoria =:compensacion_transitoria',
				'fecha_inicio =:fecha_inicio',
				'fecha_fin =:fecha_fin',
				//'ultimo_cambio_nivel = :ultimo_cambio_nivel',
				'exc_art_14= :exc_art_14',
				'unidad_retributiva = :unidad_retributiva',
				'id_grado_liquidacion =:id_grado_liquidacion'
			];
			$sql_params	= [
				':id_empleado'	=> $this->id,
				':id_modalidad_vinculacion'	=> $this->situacion_escalafonaria->id_modalidad_vinculacion,
				':id_situacion_revista'		=> $this->situacion_escalafonaria->id_situacion_revista,
				':id_nivel'	=> !empty($tmp = $this->situacion_escalafonaria->id_nivel) ? $tmp : null,
				':id_grado'	=> !empty($tmp = $this->situacion_escalafonaria->id_grado) ? $tmp : null,
				':id_tramo'	=> !empty($tmp = $this->situacion_escalafonaria->id_tramo) ? $tmp : null,
				':id_agrupamiento'		=> !empty($tmp = $this->situacion_escalafonaria->id_agrupamiento) ? $tmp : null,
				':id_funcion_ejecutiva'	=> !empty($tmp = $this->situacion_escalafonaria->id_funcion_ejecutiva) ? $tmp : null,
				':compensacion_geografica'	=> !empty($tmp = $this->situacion_escalafonaria->compensacion_geografica) ? $tmp : null,
				':compensacion_transitoria'	=> !empty($tmp = $this->situacion_escalafonaria->compensacion_transitoria) ? $tmp : null,
				':fecha_inicio'	=> !empty($tmp = $this->situacion_escalafonaria->fecha_inicio) ? $tmp : null,
				':fecha_fin'	=> !empty($tmp = $this->situacion_escalafonaria->fecha_fin) ? $tmp : null,
				':id'			=> $this->situacion_escalafonaria->id,
				//':ultimo_cambio_nivel' => $this->situacion_escalafonaria->ultimo_cambio_nivel,
				':exc_art_14' => $this->situacion_escalafonaria->exc_art_14,
				':unidad_retributiva' => !empty($tmp = $this->situacion_escalafonaria->unidad_retributiva) ? $tmp : null,
				':id_grado_liquidacion'	=> !empty($tmp = $this->situacion_escalafonaria->id_grado_liquidacion) ? $tmp : null
			];

			if($this->situacion_escalafonaria->fecha_inicio instanceof \DateTime){
				$sql_params[':fecha_inicio'] = $this->situacion_escalafonaria->fecha_inicio->format('Y-m-d');
			}
			if($this->situacion_escalafonaria->fecha_fin instanceof \DateTime){
				$sql_params[':fecha_fin']	= $this->situacion_escalafonaria->fecha_fin->format('Y-m-d');
			}
			// if($this->situacion_escalafonaria->ultimo_cambio_nivel instanceof \DateTime){
			// 	$sql_params[':ultimo_cambio_nivel']	= $this->situacion_escalafonaria->ultimo_cambio_nivel->format('Y-m-d');
			// }
			$sql	= 'UPDATE empleado_escalafon SET '.implode(',', $campos).'  WHERE id = :id';
			$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);

			if($res !== false){
				$this->situacion_escalafonaria->id	= $res;
			} else {
				$this->errores['empleado_escalafon'] = $cnx->errorInfo[2];
			}
		}
	}


	public function baja_situacion_escalafonaria(){
		if(empty($this->situacion_escalafonaria->id)) {
			return false;
		}
		$sql_params	= [
				':fecha_fin'	 => \DateTime::createFromFormat('d/m/Y H:i:s', gmdate('d/m/Y').'0:00:00')->format('Y-m-d'),
				':id_empleado' 	 => $this->id,
				':id' 	 => $this->situacion_escalafonaria->id
				
		];
		$sql	= <<<SQL
			UPDATE empleado_escalafon SET fecha_fin = :fecha_fin WHERE id_empleado= :id_empleado AND id = :id
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);

		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'Empleado';
			if (is_numeric($res) && $res > 0) {
				$flag = true;
			} else {
				$datos['error_db'] = $cnx->errorInfo;
			}
			Logger::event('baja_situacion_escalafonaria', $datos);
		}
		return $flag;
	}

	public function alta_dependencia_informal(){
		$flag	= false;
		if(!empty($this->dependencia->id_dep_informal)) {
			$cnx	= new Conexiones();
			$campos	= [
				'id_empleado',
				'id_dep_informal',
				'fecha_desde',
				'fecha_hasta',
			];
			$sql_params	= [
				':id_empleado'			=> $this->id,
				':id_dep_informal'		=> $this->dependencia->id_dep_informal,
				':fecha_desde'			=> date('Y-m-d'),
				 ':fecha_hasta'			=> null,
			];

			$sql	= 'INSERT INTO empleado_dep_informales('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
			$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);

			if($res !== false){
				$this->dependencia->id_informal	= $res;
				$datos				= (array)$this;
				$datos['modelo']	= 'Empleado';
				$flag = true;
				Logger::event('alta_dependencia_informal', $datos);
			} else {
				$this->errores['empleado_dep_informales']	= $cnx->errorInfo[2];
			}
		}
		return $flag;

	}


	public function modificacion_dependencia_informal(){
		if(!empty($this->dependencia->id_informal)) {
			if(!empty($this->dependencia->id_dep_informal)) {
				$cnx	= new Conexiones();
				$sql_params	= [
					':fecha_hasta' => date('Y-m-d'),
					':id' 	 => $this->dependencia->id_informal,
					
				];
				$sql	= 'UPDATE empleado_dep_informales SET fecha_hasta = :fecha_hasta WHERE id = :id';

				$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
				$flag	= false;
				if($res !== false){
					$this->dependencia->id_informal	= null;
					$this->alta_dependencia_informal();
					$datos				= (array)$this;
					$datos['modelo']	= 'Empleado';
					$flag = true;
				} else {
					$this->errores['empleado_dep_informales']	= $cnx->errorInfo[2];
				}
				return $flag;
				Logger::event('modificacion_dependencia_informal', $datos);
			} else {
				$result = $this->baja_dependencia_informal();
				return $result;
			}
		} else {
			$result = $this->alta_dependencia_informal();
			return $result;
		}
		return false;
	}


	public function baja_dependencia_informal(){
		$cnx	= new Conexiones();
			$sql_params	= [
				':fecha_hasta'	 => date('Y-m-d'),
				':id_empleado' 	 => $this->id
				
			];
			$sql	= 'UPDATE empleado_dep_informales SET fecha_hasta = :fecha_hasta WHERE id_empleado= :id_empleado';

			$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
			$flag	= false;
			if (!empty($res) && $res > 0) {
				$datos				= (array)$this;
				$datos['modelo']	= 'Empleado';
				if (is_numeric($res) && $res > 0) {
					$flag = true;
				} else {
					$datos['error_db'] = $cnx->errorInfo;
				}
				Logger::event('baja_dependencia_informal', $datos);
			}
			return $flag;
	}



	public function baja_dependencia(){
		if(empty($this->dependencia->id)) {
			return false;
		}
		$sql	= <<<SQL
			UPDATE empleado_dependencia SET borrado = 1 WHERE id = :id
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, [':id' => $this->dependencia->id]);
		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'Empleado';
			if (is_numeric($res) && $res > 0) {
				$flag = true;
			} else {
				$datos['error_db'] = $cnx->errorInfo;
			}
			Logger::event('baja_dependencia', $datos);
		}
		return $flag;
	}

	public function baja_horarios(){
		if(empty($this->horario->id)) {
			return false;
		}
		$sql	= <<<SQL
			UPDATE empleado_horarios SET borrado = 1 WHERE id = :id
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, [':id' => $this->horario->id]);
		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'Empleado';
			if (is_numeric($res) && $res > 0) {
				$flag = true;
			} else {
				$datos['error_db'] = $cnx->errorInfo;
			}
			Logger::event('baja_horarios', $datos);
		}
		return $flag;
	}

	public function baja(){
		if(empty($this->id)) {
			return false;
		}
		$sql	= <<<SQL
			UPDATE empleados SET borrado = 1 WHERE id = :id
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, [':id' => $this->id]);
		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'Empleado';
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
		$cnx	= new Conexiones();

// empleado_dependencia
		if($this->dependencia->id) {
			$campos	= [
				'id_dependencia'	=> 'id_dependencia = :id_dependencia',
				'fecha_desde'		=> 'fecha_desde = :fecha_desde',
				'fecha_hasta'		=> 'fecha_hasta = :fecha_hasta',
			];
			$sql_params	= [
				':id'			=> $this->dependencia->id,
			];
			$sql_params[':id_dependencia'] = $this->dependencia->id_dependencia;

			$sql_params[':fecha_desde']	= ($this->dependencia->fecha_desde instanceof \DateTime) ? $this->dependencia->fecha_desde->format('Y-m-d') : null;
			$sql_params[':fecha_hasta']	= ($this->dependencia->fecha_hasta instanceof \DateTime) ? $this->dependencia->fecha_hasta->format('Y-m-d') : null;			
			$sql	= 'UPDATE empleado_dependencia SET '.implode(',', $campos).' WHERE id = :id';
			$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);
			if($res === false){
				$this->errores['empleado_dependencia']	= $cnx->errorInfo[2];
			}
			$this->modificacion_dependencia_informal();
		}

// empleado_licencias_especiales
		if($this->licencia->id) {
			$campos	= [
				'id_licencia'	=> 'id_licencia = :id_licencia',
				'fecha_desde'	=> 'fecha_desde = :fecha_desde',
				'fecha_hasta'	=> 'fecha_hasta = :fecha_hasta',
			];
			$sql_params	= [
				':id'			=> $this->id,
				':id_licencia'	=> $this->licencia->id_licencia,
				':fecha_desde'	=> $this->licencia->fecha_desde,
				':fecha_hasta'	=> $this->licencia->fecha_hasta,
			];

			if($this->licencia->fecha_desde instanceof \DateTime){
				$sql_params[':fecha_desde']	= $this->licencia->fecha_desde->format('Y-m-d');
			}
			if($this->licencia->fecha_hasta instanceof \DateTime){
				$sql_params[':fecha_hasta']	= $this->licencia->fecha_hasta->format('Y-m-d');
			}
			$sql	= 'UPDATE empleados_lic_especiales SET '.implode(',', $campos).' WHERE id = :id';
			$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);


			if($res === false){
				$this->errores['empleados_lic_especiales']	= $cnx->errorInfo[2];
			}
		}

// Alta de datos en caso de estar pre-existentes
		if(
			empty($this->horario->id)
			|| empty($this->dependencia->id)
			|| empty($this->situacion_escalafonaria->id)
			|| empty($this->licencia->id)
		){
			$this->alta();
		}
// empleados
		$campos	= [
			'cuit = :cuit',
			'email = :email',
			'planilla_reloj = :planilla_reloj',
			'antiguedad_adm_publica = :antiguedad_adm_publica',
			'id_persona		= :id_persona',
			'en_comision	= :en_comision',
			'credencial 	= :credencial',
			'estado			= :estado',
			'id_motivo		= :id_motivo',
			'fecha_baja		= :fecha_baja',
			'id_sindicato 				= :id_sindicato',
			'fecha_vigencia_mandato = :fecha_vigencia_mandato',
			'veterano_guerra = :veterano_guerra',

		];
		$sql_params	= [
			':cuit'						=> $this->cuit,
			':email'					=> $this->email,
			':planilla_reloj'			=> $this->planilla_reloj,
			':en_comision'				=> (int)!empty($this->en_comision->id),
			':antiguedad_adm_publica'	=> json_encode( $this->antiguedad->antiguedad_adm_publica, JSON_UNESCAPED_SLASHES),
			':id_persona'				=> $this->persona->id,
			':id'						=> $this->id,
			':credencial'				=> $this->credencial,
			':estado'					=> $this->estado,
			':id_motivo'				=> $this->id_motivo,
			':fecha_baja'				=> $this->fecha_baja,
			':id_sindicato'				=> $this->id_sindicato,
			':fecha_vigencia_mandato'	=> $this->fecha_vigencia_mandato,
			':veterano_guerra'			=> $this->veterano_guerra,
		];

		if($this->fecha_vencimiento instanceof \DateTime) {
			$campos[] = 'fecha_vencimiento = :fecha_vencimiento';
			$sql_params[':fecha_vencimiento'] = $this->fecha_vencimiento->format('Y-m-d');
		}

		if($this->fecha_baja instanceof \DateTime){
			$sql_params[':fecha_baja']	= $this->fecha_baja->format('Y-m-d');
		}

		if($this->fecha_vigencia_mandato instanceof \DateTime){
			$sql_params[':fecha_vigencia_mandato']	= $this->fecha_vigencia_mandato->format('Y-m-d');
		}

		$sql	= 'UPDATE empleados SET '.implode(',', $campos).' WHERE id = :id';
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);

		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Empleado';
			Logger::event('modificacion', $datos);
		} else {
			$this->errores['empleados']	= $cnx->errorInfo[2];
		}
		return $res;
	}

	public function validar() {
		if($this->situacion_escalafonaria->id_modalidad_vinculacion != \App\Modelo\Contrato::PRESTACION_SERVICIOS && !empty($this->situacion_escalafonaria->unidad_retributiva)){
			$this->situacion_escalafonaria->unidad_retributiva = null;
		}

		$campos = (array)$this;
		$campos['dni'] = $this->persona->documento;
		$campos	+= [
			'esc_id_modalidad_vinculacion'	=> $this->situacion_escalafonaria->id_modalidad_vinculacion,
			'esc_situacion_revista'			=> $this->situacion_escalafonaria->id_situacion_revista,
			'esc_nivel'						=> $this->situacion_escalafonaria->id_nivel,
			'esc_grado'						=> $this->situacion_escalafonaria->id_grado,
			'esc_tramo'						=> $this->situacion_escalafonaria->id_tramo,
			'esc_agrupamiento'				=> $this->situacion_escalafonaria->id_agrupamiento,
			'esc_funcion_ejecutiva'			=> $this->situacion_escalafonaria->id_funcion_ejecutiva,
			'esc_compensacion_geografica'	=> $this->situacion_escalafonaria->compensacion_geografica,
			'esc_compensacion_transitoria'	=> $this->situacion_escalafonaria->compensacion_transitoria,
			'esc_fecha_inicio'				=> $this->situacion_escalafonaria->fecha_inicio,
			'esc_fecha_fin'					=> $this->situacion_escalafonaria->fecha_fin,
			//'esc_ultimo_cambio_nivel'		=> $this->situacion_escalafonaria->ultimo_cambio_nivel,
			'esc_exc_art_14'				=> $this->situacion_escalafonaria->exc_art_14,
			'esc_unidad_retributiva'		=> $this->situacion_escalafonaria->unidad_retributiva,
		];
		$nombres	= [
			'esc_id_modalidad_vinculacion'	=> 'Modalidad de Vinculación',
			'esc_situacion_revista'			=> 'Situación de Revista',
			'esc_nivel'						=> 'Nivel',
			'esc_grado'						=> 'Grado',
			'esc_tramo'						=> 'Tramo',
			'esc_agrupamiento'				=> 'Agrupamiento',
			'esc_funcion_ejecutiva'			=> 'Función Ejecutiva',
			'esc_compensacion_geografica'	=> 'Compensación Geografica',
			'esc_compensacion_transitoria'	=> 'Compensación Transitoria',
			'esc_fecha_inicio'				=> 'Fecha de inicio',
			'esc_fecha_fin'					=> 'Fecha de Fin',
			'esc_unidad_retributiva'		=> 'Unidad Retributiva',
		];
		$reglas	= [
			'cuit'	=> ['required', 'cuit','unico(empleados, cuit'.(($this->id) ? ','.$this->id : '').')', 'correpondencia(:dni)' => function($input,$dni) {
				if(!empty($input)) {
					$cuit_dni = preg_replace(['/^\d{2}/','/\d{1}$/'],'',$input); 
					if($cuit_dni == $dni) {
						return true;						
					}
					return false;
				}
				return true;
			} ],
			'esc_exc_art_14'				=> ['array_no_vacio' =>function($input){
				$aux = true;
				if(is_array($input)){
					$aux = (!empty($input)) ? true : false ; 
				}
				return $aux;
			}
		],
			'credencial'					=> ['integer'],
			'fecha_vencimiento'				=> ['fecha'],
			'id_motivo'						=> ['integer'],
			'fecha_baja'					=> ['fecha'],
			'id_sindicato'					=> ['integer'],
			'fecha_vigencia_mandato'		=> ['fecha'],
		];

		if (
			!is_null($this->situacion_escalafonaria->id_modalidad_vinculacion)
			|| !is_null($this->situacion_escalafonaria->id_situacion_revista)
			|| !is_null($this->situacion_escalafonaria->id_nivel)
			|| !is_null($this->situacion_escalafonaria->id_grado)
			|| !is_null($this->situacion_escalafonaria->id_tramo)
			|| !is_null($this->situacion_escalafonaria->id_agrupamiento)
			|| !is_null($this->situacion_escalafonaria->id_funcion_ejecutiva)
			|| !is_null($this->situacion_escalafonaria->compensacion_geografica)
			|| !is_null($this->situacion_escalafonaria->compensacion_transitoria)
			|| !is_null($this->situacion_escalafonaria->unidad_retributiva)
		) {
			$reglas	= [
				'esc_id_modalidad_vinculacion'	=> ['required','integer'],
				'esc_situacion_revista' 		=> ['required','integer'],
				'esc_nivel' 					=> ['integer'],
				'esc_grado' 					=> ['integer'],
				'esc_tramo' 					=> ['integer'],
				'esc_agrupamiento'				=> ['integer'],
				'esc_funcion_ejecutiva' 		=> ['integer'],
				'esc_fecha_inicio'				=> ['fecha'],
				'esc_fecha_fin'					=> ['fecha'],
				'esc_unidad_retributiva' 		=> ['integer', 'unidades_retributivas(:esc_nivel,:esc_grado)' =>function($input,$nivel, $grado){
				if (!empty($input)) {
					$sql = "SELECT count(*) as valido
						FROM convenio_unidades_retributivas
						WHERE id_nivel = :id_nivel
						AND id_grado = :id_grado
						AND :input BETWEEN minimo AND maximo";
						$params	= [':id_nivel'	=> $nivel,
									':id_grado'	=> $grado,
									':input' => $input,
								];
						$con = new Conexiones();
						$res = $con->consulta(Conexiones::SELECT, $sql, $params);
						if (is_array($res) && isset($res[0]) && isset($res[0]['valido'])) {
							return  (bool) $res[0]['valido'];
						}
						return false;
					}
					return true;
				}],
			];
	}

		$nombres	+= [
			'cuit'			=> 'CUIT',
			'credencial'	=> 'Credencial',
			'id_motivo'		=> 'Motivo de Baja',
			'fecha_baja'	=> 'Fecha de Baja',
			'fecha_vencimiento' => 'Fecha de Vencimiento de credencial',
			'id_sindicato'		=> 'Sindicato',
			'fecha_vigencia_mandato'	=>  'Fecha de Vigencia de Mandato Gremial'	
		];

		if($this->antiguedad->id) {
			$campos['fecha_ingreso']     = $this->antiguedad->fecha_ingreso;  
			$campos['ant_adm_pub_anio']  = $this->antiguedad->antiguedad_adm_publica->anio;  
			$campos['ant_adm_pub_mes']   = $this->antiguedad->antiguedad_adm_publica->mes;  
			$reglas['fecha_ingreso']     = ['required', 'fecha'];
			$reglas['ant_adm_pub_anio']  = ['integer'];
			$reglas['ant_adm_pub_mes']   = ['integer'];
			$nombres['fecha_ingreso']	 = 'Fecha de Ingreso al MTR';
			$nombres['ant_adm_pub_anio'] = 'Años de antigüedad en la Administración Pública';
			$nombres['ant_adm_pub_mes']  = 'Meses de antigüedad en la Administración Pública';
		}

		if($this->dependencia->nivel) {
			$campos['id_dependencia']  = $this->dependencia->id_dependencia;
			$reglas['id_dependencia']  = ['required','integer'];
			$nombres['id_dependencia'] = 'Dependencia';
		}

		if($this->dependencia->id_dependencia) {
			$campos['id_dependencia']  = $this->dependencia->id_dependencia;
			$campos['fecha_desde'] 	   = $this->dependencia->fecha_desde;
			$reglas['id_dependencia']  = ['required','integer'];
			$reglas['fecha_desde']     = ['required','fecha'];
			$nombres['id_dependencia'] = 'Dependencia';
			$nombres['fecha_desde']    = 'Fecha Desde';
		}

		if(isset($this->horario->horarios)){
			$campos['horarios'] 	= $this->horario->horarios;
			$campos['turno'] 	 	= $this->horario->id_turno;
			$reglas['horarios']		= ['isJson'];
			$reglas['turno']		= ['integer'];
			$nombres['horarios'] 	= 'Grilla Horaria';
			$nombres['turno'] 		= 'Turno';
		}
		if(isset($this->horas_extras->anio)){
			$campos['id_extra'] 			= $this->horas_extras->id;
			$campos['anio'] 				= $this->horas_extras->anio;
			$campos['mes'] 	 				= $this->horas_extras->mes;
			$campos['acto_administrativo'] 	= $this->horas_extras->acto_administrativo;
			$reglas['anio']					= ['required', 'integer'];
			$reglas['mes']					= ['required', 'integer', 'unico_periodo(:id,:id_extra,:anio)' => function($input,$id,$id_extra,$anio){
						if (!is_null($input)) {
							$where_id = '';
							$params = [':id' => $id,':anio' => $anio, ':mes' => $input];
							if (!empty($id_extra)) {
								$where_id = "AND id != :id_extra";
								$params[':id_extra'] = $id_extra; 
							}
							$sql = <<<SQL
							SELECT count(*) count FROM empleado_horas_extras WHERE id_empleado = :id AND anio = :anio AND mes = :mes  $where_id
SQL;
							$con = new Conexiones();
							$res = $con->consulta(Conexiones::SELECT, $sql, $params);
							if (is_array($res) && isset($res[0]) && isset($res[0]['count'])) {
								return !($res[0]['count'] > 0);
							}
							return false;
						}
						return true;
				}];

			$reglas['acto_administrativo']	= ['required', 'texto'];
			$nombres['anio'] 	= 'Año';
			$nombres['mes'] 	= 'Mes';
			$nombres['acto_administrativo'] 	= 'Acto Administrativo';
		}
		if(isset($this->ubicacion->id_ubicacion)){
			$campos['ubicacion'] 	= $this->ubicacion->id_ubicacion;
			$reglas['ubicacion']	= ['required', 'integer'];
			$nombres['ubicacion'] 	= 'Datos de Ubicación';
		}
		if(!empty($this->licencia->id_licencia)){
			$campos['licencia'] 	= $this->licencia->id_licencia;
			$campos['licencia_fecha_desde']	= $this->licencia->fecha_desde;
			$campos['licencia_fecha_hasta']  = $this->licencia->fecha_hasta;
			$reglas['licencia']		= ['integer'];
			$reglas['licencia_fecha_desde']	= ['required','fecha', 'antesDe(:licencia_fecha_hasta)'];
			$reglas['licencia_fecha_hasta']  = ['required','fecha', 'despuesDe(:licencia_fecha_desde)'];
			$nombres['licencia'] 	= 'Licencia';
			$nombres['licencia_fecha_hasta'] = 'Fecha Fin Licencia';
			$nombres['licencia_fecha_desde'] = 'Fecha Inicio Licencia';
		}

		if($this->presupuesto->id_presupuesto) {
			$campos['id_presupuesto']  = $this->presupuesto->id_presupuesto;
			$campos['fecha_desde'] 	   = $this->presupuesto->fecha_desde;
			$reglas['id_presupuesto']  = ['required','integer'];
			$reglas['fecha_desde']     = ['required','fecha'];
			$nombres['id_presupuesto'] = 'Presupuesto';
			$nombres['fecha_desde']    = 'Fecha Desde';
		}

		if(isset($this->empleado_salud->id_obra_social)){
			$campos['obra_social'] 		= $this->empleado_salud->id_obra_social;
			$reglas['obra_social']		= ['integer'];
			$nombres['obra_social'] 	= 'Obra Social';
		}

		if(isset($this->empleado_salud->id_seguro_vida)){
			$campos['seguro_vida'] 		= $this->empleado_salud->id_seguro_vida;
			$reglas['seguro_vida']		= ['integer'];
			$nombres['seguro_vida'] 	= 'Seguro de Vida';
		}

		$validator	= Validator::validate($campos, $reglas, $nombres);
		$validator->customErrors([
            'unico_periodo'      => 'Ya existen Horas Extras asociada a éste Periodo',                             
            'array_no_vacio'	 => 'Si corresponde la "Excepción Art.14" debe elegirse alguna/s opciones/s.',
            'unidades_retributivas' => 'Las unidades retributivas estan fueras del rango autorizado para la función y nivel'
        ]);		

		if ($validator->isSuccess()) {
			return true;
		}
		$this->errores = $validator->getErrors();
		return false;
	}

	public function modificacion_ingreso(){
		if(!is_null($this->antiguedad->id)) {
			$cnx	= new Conexiones();
			$sql = <<<SQL
				UPDATE empleado_dependencia SET fecha_desde = :fecha_desde
				WHERE id = :id
SQL;
			$res = $cnx->consulta(Conexiones::UPDATE, $sql,[':id'=> $this->antiguedad->id, ':fecha_desde' => $this->antiguedad->fecha_ingreso->format('Y-m-d H:i:s')]);
			if($res !== false){				
				$datos = (array) $this;
				$datos['modelo'] = 'Empleado';
				Logger::event('modificacion_ingreso', $datos);
				$this->dependencia->fecha_desde = $this->antiguedad;

			} else {
				$this->antiguedad->fecha_ingreso = '';
				$this->errores['empleados']	= $cnx->errorInfo[2];
			}
		}else{
			$res = false;

			$this->errores['empleados']	= 'Para poder guardar la antigüedad en el Ministerio se debe definir primero una "Ubicación en la Estructura".';			
		}
		return $res;
	}

	public function ingreso_mtr(){
		$cnx	= new Conexiones();
		$sql = <<<SQL
			SELECT * FROM empleado_dependencia 
			WHERE id_empleado = :id_empleado 
			ORDER BY fecha_desde ASC LIMIT 1
SQL;
		$res = $cnx->consulta(Conexiones::SELECT, $sql,[':id_empleado'=> $this->id]);
		return ($res) ? (object) $res[0] : null;
	}

	public function alta_horario(){
		$cnx	= new Conexiones();
		$campos	= [
			'id_empleado',
			'horarios',
			'id_turno',
			'fecha_inicio',
			'fecha_fin',
		];
		$sql_params	= [
			':id_empleado'	=> $this->id,
			':horarios'		=> $this->horario->horarios,
			':id_turno'		=> $this->horario->id_turno,
			':fecha_inicio'	=> $this->horario->fecha_inicio,
			':fecha_fin'	=> $this->horario->fecha_fin,
		];

		if($this->horario->fecha_inicio instanceof \DateTime){
			$sql_params[':fecha_inicio']	= $this->horario->fecha_inicio->format('Y-m-d');
		}
		if($this->horario->fecha_fin instanceof \DateTime){
			$sql_params[':fecha_fin']	= $this->horario->fecha_fin->format('Y-m-d');
		}

		$sql	= 'INSERT INTO empleado_horarios('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);

		if($res !== false){
			$this->horario->id	= $res;
			$datos = (array) $this;
			$datos['modelo'] = 'Empleado';
			Logger::event('alta', $datos);
			return true;
		} else {
			$this->errores['empleado_horarios']	= $cnx->errorInfo[2];
			return false;
		}
	}

	public function modificacion_horario(){
		$cnx	= new Conexiones();
		$campos	= [
			'horarios'		=> 'horarios = :horarios',
			'id_turno'		=> 'id_turno = :id_turno',
			'fecha_inicio'	=> 'fecha_inicio = :fecha_inicio',
			'fecha_fin'		=> 'fecha_fin = :fecha_fin',
		];
		$sql_params	= [
			':id'			=> $this->horario->id,
			':horarios'		=> $this->horario->horarios,
			':id_turno'		=> $this->horario->id_turno,
			':fecha_inicio'	=> $this->horario->fecha_inicio,
			':fecha_fin'	=> $this->horario->fecha_fin,
		];

		if($this->horario->fecha_inicio instanceof \DateTime){
			$sql_params[':fecha_inicio']	= $this->horario->fecha_inicio->format('Y-m-d');
		}
		if($this->horario->fecha_fin instanceof \DateTime){
			$sql_params[':fecha_fin']	= $this->horario->fecha_fin->format('Y-m-d');
		}
		$sql	= 'UPDATE empleado_horarios SET '.implode(',', $campos).' WHERE id = :id';
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);
		
		if($res !== false){
			$this->horario->id	= $res;
			$datos = (array) $this;
			$datos['modelo'] = 'Empleado';
			Logger::event('modificacion', $datos);
			return true;
		} else {
			$this->errores['empleado_horarios']	= $cnx->errorInfo[2];
			return false;
		}
	}

	public function alta_hora_extra(){
			$cnx	= new Conexiones();
			$campos	= [
				'id_empleado',
				'anio',
				'mes',
				'acto_administrativo'
			];
			$sql_params	= [
				':id_empleado'			=> $this->id,
				':anio'					=> $this->horas_extras->anio,
				':mes'					=> $this->horas_extras->mes,
				':acto_administrativo'	=> $this->horas_extras->acto_administrativo,
			];

			$sql	= 'INSERT INTO empleado_horas_extras('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
			$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
			
			if($res !== false){
				$this->horas_extras->id	= $res;
		$datos['modelo'] = 'Empleado';
			Logger::event('alta', $datos);
			return true;
			} else {
				$this->errores['empleado_horas_extras']	= $cnx->errorInfo[2];
			return false;
			}
		}



	public function modificacion_hora_extra(){
		$cnx	= new Conexiones();
		$campos	= [
			'anio'			=> 'anio = :anio',
			'mes'			=> 'mes = :mes',
			'acto_administrativo' 	=> 'acto_administrativo = :acto_administrativo'

		];
		$sql_params	= [
			':anio'					=> $this->horas_extras->anio,
			':mes'					=> $this->horas_extras->mes,
			':acto_administrativo'	=> $this->horas_extras->acto_administrativo,
			':id'					=> $this->horas_extras->id,
		];

	
		$sql	= 'UPDATE empleado_horas_extras SET '.implode(',', $campos).' WHERE id = :id';
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$this->horario->id	= $res;
			$datos = (array) $this;
			$datos['modelo'] = 'Empleado';
			Logger::event('modificacion', $datos);
			return true;
		} else {
			$this->errores['empleado_horarios']	= $cnx->errorInfo[2];
			return false;
		}
	}


	public function alta_ubicacion(){
		$this->baja_ubicacion();
		$cnx	= new Conexiones();
		$campos	= [
			'id_empleado',
			'id_ubicacion',
			'fecha_desde',
			'fecha_hasta',
		];
		$sql_params	= [
			':id_empleado'	=> $this->id,
			':id_ubicacion'	=> $this->ubicacion->id_ubicacion,
			':fecha_desde'	=> $this->ubicacion->fecha_desde,
			':fecha_hasta'	=> $this->ubicacion->fecha_hasta,
		];

		if($this->ubicacion->fecha_desde instanceof \DateTime){
			$sql_params[':fecha_desde']	= $this->ubicacion->fecha_desde->format('Y-m-d');
		}
		if($this->ubicacion->fecha_hasta instanceof \DateTime){
			$sql_params[':fecha_hasta']	= $this->ubicacion->fecha_hasta->format('Y-m-d');
		}

		$sql	= 'INSERT INTO empleados_x_ubicacion('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
		if($res !== false){
			$this->ubicacion->id	= $res;
			$datos = (array) $this;
			$datos['modelo'] = 'Empleado';
			Logger::event('alta', $datos);
			return true;
		} else {
			$this->errores['empleados_x_ubicacion']	= $cnx->errorInfo[2];
			return false;
		}
	}

	public function modificacion_ubicacion(){
		$cnx	= new Conexiones();
		$campos	= [
			'id_ubicacion'	=> 'id_ubicacion = :id_ubicacion',
			'fecha_desde'	=> 'fecha_desde = :fecha_desde',
			'fecha_hasta'	=> 'fecha_hasta = :fecha_hasta',
		];
		$sql_params	= [
			':id'			=> $this->ubicacion->id,
			':id_ubicacion'	=> $this->ubicacion->id_ubicacion,
			':fecha_desde'	=> $this->ubicacion->fecha_desde,
			':fecha_hasta'	=> $this->ubicacion->fecha_hasta,
		];

		if($this->ubicacion->fecha_desde instanceof \DateTime){
			$sql_params[':fecha_desde']	= $this->ubicacion->fecha_desde->format('Y-m-d');
		}
		if($this->ubicacion->fecha_hasta instanceof \DateTime){
			$sql_params[':fecha_hasta']	= $this->ubicacion->fecha_hasta->format('Y-m-d');
		}
		$sql	= 'UPDATE empleados_x_ubicacion SET '.implode(',', $campos).' WHERE id = :id';
		$res	=  $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);

		if($res !== false){
			$this->ubicacion->id	= $res;
			$datos = (array) $this;
			$datos['modelo'] = 'Empleado';
			Logger::event('modificacion', $datos);
			return true;
		} else {
			$this->errores['empleados_x_ubicacion']	= $cnx->errorInfo[2];
			return false;
		}
	}

	public function baja_ubicacion(){
		$cnx	= new Conexiones();
		$sql_params = [
			':id_empleado'	=> $this->id,
			':fecha_hasta'  => (date('Y-m-d'))
		];

		$sql	= <<<SQL
		UPDATE empleados_x_ubicacion SET fecha_hasta = :fecha_hasta WHERE id_empleado = :id_empleado AND fecha_hasta IS NULL;
SQL;
		$res	=  $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);

		if ($res !== false) {
			$datos = (array)$this;
			$datos['modelo'] = 'Empleado';
			Logger::event('modificacion', $datos);
			return true;
		} else {
			$this->errores['empleados_x_ubicacion']	= $cnx->errorInfo[2];
			return false;
		}
	}


	public function alta_licencias_especiales(){
		$cnx	= new Conexiones();
		$campos	= [
			'id_empleado',
			'id_licencia',
			'fecha_desde',
			'fecha_hasta'
		];
		$sql_params	= [
			':id_empleado'		=> $this->id,
			':id_licencia'	    => $this->licencia->id_licencia,
			':fecha_desde'		=> $this->licencia->fecha_desde,
			':fecha_hasta'		=> $this->licencia->fecha_hasta,
		];

		if($this->licencia->fecha_desde instanceof \DateTime){
			$sql_params[':fecha_desde']	= $this->licencia->fecha_desde->format('Y-m-d');
		}
		if($this->licencia->fecha_hasta instanceof \DateTime){
			$sql_params[':fecha_hasta']	= $this->licencia->fecha_hasta->format('Y-m-d');
		}

		$sql	= 'INSERT INTO empleados_lic_especiales('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
		$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);

		if($res !== false){
			$this->licencia->id	= $res;
			$datos = (array) $this;
			$datos['modelo'] = 'Empleado';
			Logger::event('alta', $datos);
			return true;
		} else {
			$this->errores['empleados_lic_especiales']	= $cnx->errorInfo[2];
			return false;
		}
	}

	public function modificacion_licencias_especiales(){
		$cnx	= new Conexiones();
		$campos	= [
			'id_licencia'	=> 'id_licencia = :id_licencia',
			'fecha_desde'	=> 'fecha_desde = :fecha_desde',
			'fecha_hasta'	=> 'fecha_hasta = :fecha_hasta',
		];
		$sql_params	= [
			':id'				=> $this->licencia->id,
			':id_licencia'	    => $this->licencia->id_licencia,
			':fecha_desde'		=> $this->licencia->fecha_desde,
			':fecha_hasta'		=> $this->licencia->fecha_hasta,
		];

		if($this->licencia->fecha_desde instanceof \DateTime){
			$sql_params[':fecha_desde']	= $this->licencia->fecha_desde->format('Y-m-d');
		}
		if($this->licencia->fecha_hasta instanceof \DateTime){
			$sql_params[':fecha_hasta']	= $this->licencia->fecha_hasta->format('Y-m-d');
		}

		$sql	= 'UPDATE empleados_lic_especiales SET '.implode(',', $campos).' WHERE id = :id';
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);

		if($res !== false){
			$this->licencia->id	= $res;
			$datos = (array) $this;
			$datos['modelo'] = 'Empleado';
			Logger::event('modificacion', $datos);
			return true;
		} else {
			$this->errores['empleados_lic_especiales']	= $cnx->errorInfo[2];
			return false;
		}
	}

		public function baja_licencias_especiales(){
		if(empty($this->licencia->id)) {
			return false;
		}
		$sql	= <<<SQL
			UPDATE empleados_lic_especiales SET borrado = 1 WHERE id = :id
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, [':id' => $this->licencia->id]);
		$flag	= false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'Empleado';
			if (is_numeric($res) && $res > 0) {
				$flag = true;
			} else {
				$datos['error_db'] = $cnx->errorInfo;
			}
			Logger::event('baja_horarios', $datos);
		}
		return $flag;
	}

	public function modificacion_empleado_planilla_reloj(){
		if(!$this->validar() || empty($this->id)){
			return false;
		}
		$cnx	= new Conexiones();
		$campos	= [
			'planilla_reloj'			=> 'planilla_reloj = :planilla_reloj',
		];
		$sql_params	= [
			':id'						=> $this->id,
			':planilla_reloj'			=> $this->planilla_reloj,
		];

		$sql	= 'UPDATE empleados SET '.implode(',', $campos).' WHERE id = :id';
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);

		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Empleado';
			Logger::event('modificacion', $datos);
			return true;
		} else {
			$this->errores['empleados']	= $cnx->errorInfo[2];
			return false;
		}		
	}

	public function modificacion_estado(){
		if(!$this->validar() || empty($this->id)){
			return false;
		}
		$cnx	= new Conexiones();
		$sql_params	= [
			':id'				=> $this->id,
			':estado'			=> $this->estado,
			':fecha_baja'		=> $this->fecha_baja,
			':motivo_id'		=> $this->id_motivo,
		];

		if($this->fecha_baja instanceof \DateTime){
			$sql_params[':fecha_baja']	= $this->fecha_baja->format('Y-m-d');
		}

		$sql	= 'UPDATE empleados SET estado =:estado, fecha_baja =:fecha_baja, id_motivo =:motivo_id WHERE id = :id';
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);

		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Empleado';
			Logger::event('modificacion', $datos);
			return true;
		} else {
			$this->errores['empleados']	= $cnx->errorInfo[2];
			return false;
		}		
	}	

public function tiene_historial_extras() {
		$sql_params	= [
			':id_empleado' => $this->id,
		];
		$lista = [];
		$sql	= <<<SQL
			SELECT  count(*) count
			FROM empleado_horas_extras 
			WHERE  id_empleado = :id_empleado AND borrado = 0
			ORDER BY id DESC

SQL;
		$resp	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		return ($resp[0]['count'] > 0);

	}

	public function listar_horas_extras() {
		$sql_params	= [
			':id_empleado' => $this->id,
		];
		$lista = [];
		$sql	= <<<SQL
			SELECT  id,
			    id_empleado,
			    anio,
			    mes,
			    acto_administrativo
			FROM empleado_horas_extras 
			WHERE  id_empleado = :id_empleado AND borrado = 0
			ORDER BY id DESC

SQL;
		$resp	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		if(empty($resp)) { return []; }
		return $resp;

	}

	public function baja_hora_extra($id=null){
		$sql_params= [':id' => $id];
		$sql = <<<SQL
		update empleado_horas_extras set borrado = 1 where id = :id;
SQL;
		$res	= (new Conexiones())->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false){
			$datos = (array) $this;
			$datos['modelo'] = 'Empleado';
			Logger::event('baja_horas_extras', $datos);		
		}
		return $res;
	}


	static public function obtener_hora_extra($id=null){

		$sql_params	= [
			':id'	=> $id,
		];
		
		$sql	= <<<SQL
		SELECT  id,
			   	id_empleado,
			    anio,
			    mes,
			    acto_administrativo
			FROM empleado_horas_extras 
			WHERE id = :id and borrado = 0
SQL;
		$resp	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
		return ($resp) ? (object) $resp[0] : null;
	}

	static public function getSindicato(){ 
		static $aux	= [];
		if(!empty($aux)){
			return $aux;
		}
		$campos = implode(',', [ 'id', 'codigo', 'nombre', 'borrado' ]); 
		$sql = <<<SQL
		SELECT {$campos} FROM sindicatos ORDER BY id ASC 
SQL;
		$cnx = new Conexiones(); 
		$res = $cnx->consulta(Conexiones::SELECT, $sql); 
		foreach ((array)$res as $value) { 
			$aux[$value['id']] = $value; 
		} 
		return $aux; 
	}
	
	static public function getMotivoBaja(){
		static $aux	= [];
		if(!empty($aux)){
			return $aux;
		}
		$campos = implode(',', [ 'id', 'nombre', 'borrado' ]); 
		$sql = <<<SQL
		SELECT {$campos} FROM motivo_baja ORDER BY id ASC 
SQL;
		$cnx = new Conexiones(); 
		$res = $cnx->consulta(Conexiones::SELECT, $sql); 
		foreach ((array)$res as $value) { 
			$aux[$value['id']] = $value; 
		} 
		return $aux;
	}


	static public function arrayToObject($res = []) {
		$campos	= [
			'id'						=> 'int',
			'cuit'						=> 'int',
			'email'						=> 'string',
			'planilla_reloj'			=> 'int',
			'credencial'				=> 'int',
			'fecha_vencimiento'			=> 'date',
			'estado'					=> 'int',
			'id_motivo'					=> 'int',
			'fecha_baja'				=> 'date',
			'id_sindicato'				=> 'int',
			'fecha_vigencia_mandato'	=> 'date',
			'veterano_guerra'			=> 'int',
		];
		$obj = parent::arrayToObject($res, $campos);

		if(static::getContiene()){
			return $obj;
		}

		if(static::getContiene('persona')){
			$obj->persona = \App\Modelo\Persona::obtener(\FMT\Helper\Arr::get($res,'id_persona'));
		}

		if(static::getContiene('dependencia')){
			$aux	= \App\Modelo\Dependencia::obtener(\FMT\Helper\Arr::get($res,'dep_id_dependencia'));
			$obj->dependencia 					= new \StdClass();
			$obj->dependencia->id				= \FMT\Helper\Arr::get($res,'dep_id');
			$obj->dependencia->id_dependencia	= \FMT\Helper\Arr::get($res,'dep_id_dependencia');
			$obj->dependencia->nombre			= $aux->nombre;
			$obj->dependencia->nivel			= $aux->nivel;
			$obj->dependencia->fecha_desde		= isset($res['dep_fecha_desde']) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res['dep_fecha_desde'] . ' 0:00:00') : null;
			$obj->dependencia->fecha_hasta		= isset($res['dep_fecha_hasta']) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res['dep_fecha_hasta'] . ' 0:00:00') : null;
			$obj->dependencia->id_informal		= \FMT\Helper\Arr::get($res,'dep_id_informal');
			$obj->dependencia->id_dep_informal  = \FMT\Helper\Arr::get($res,'dep_id_dep_informal'); 
		}

		if(static::getContiene('en_comision')){
			$obj->en_comision					= new \StdClass();
			$obj->en_comision->activo			= \FMT\Helper\Arr::get($res,'en_comision', self::ESTADO_INACTIVO);
			$obj->en_comision->id 				= \FMT\Helper\Arr::get($res,'com_id');
			$obj->en_comision->id_origen		= \FMT\Helper\Arr::get($res,'com_id_comision_origen');
			$obj->en_comision->id_destino		= \FMT\Helper\Arr::get($res,'com_id_comision_destino');
			$obj->en_comision->fecha_inicio		=  !empty($res['com_fecha_inicio']) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res['com_fecha_inicio'] . ' 0:00:00') : null;
			$obj->en_comision->fecha_fin		= !empty($res['com_fecha_fin']) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res['com_fecha_fin'] . ' 0:00:00') : null;
		}

		if(static::getContiene('ubicacion')){
			$aux	= \App\Modelo\Ubicacion::obtener(\FMT\Helper\Arr::get($res,'ubi_id_ubicacion'));
			$obj->ubicacion						= new \StdClass();
			$obj->ubicacion->id					= \FMT\Helper\Arr::get($res,'ubi_id'); 
			$obj->ubicacion->id_ubicacion		= \FMT\Helper\Arr::get($res,'ubi_id_ubicacion');
			$obj->ubicacion->id_edificio		= $aux->id_edificio;
			$obj->ubicacion->nombre				= $aux->nombre;
			$obj->ubicacion->calle				= $aux->calle;
			$obj->ubicacion->numero				= $aux->numero;
			$obj->ubicacion->piso				= $aux->piso;
			$obj->ubicacion->oficina			= $aux->oficina;
			$obj->ubicacion->id_localidad		= $aux->id_localidad;
			$obj->ubicacion->id_provincia		= $aux->id_provincia;
			$obj->ubicacion->fecha_desde		= isset($res['ubi_fecha_desde']) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res['ubi_fecha_desde'] . ' 0:00:00') : null;
			$obj->ubicacion->fecha_hasta		= isset($res['com_fecha_fin']) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res['com_fecha_fin'] . ' 0:00:00') : null;
		}

		if(static::getContiene('antiguedad')){
			$aux  = $obj->ingreso_mtr();
			$id_tipo = EmpleadoUltimosCambios::GRADO; //id_tipo de grado
			$auxb = $obj->empleados_ultimos_cambios($id_tipo);

			$obj->antiguedad						= new \StdClass(); 
			$obj->antiguedad->antiguedad_adm_publica= \FMT\Helper\Arr::get($res,'antiguedad_adm_publica') ? json_decode($res['antiguedad_adm_publica']) : (object) ['anio' => null,'mes' => null];  
			$obj->antiguedad->id					= !empty($aux) ? $aux->id : null; 
			$obj->antiguedad->fecha_ingreso			= !empty($aux) ? \DateTime::createFromFormat('Y-m-d H:i:s', $aux->fecha_desde . ' 0:00:00') : null;
			$obj->antiguedad->fecha_grado			= !empty($auxb) ? \DateTime::createFromFormat('Y-m-d H:i:s', $auxb['fecha_desde'] . ' 00:00:00') : null;
		}

		if(static::getContiene('horario')){
			$obj->horario							= new \StdClass();
			$obj->horario->id						= \FMT\Helper\Arr::get($res,'hor_id');
			$obj->horario->horarios                 = \FMT\Helper\Arr::get($res,'hor_horarios'); 
			$obj->horario->id_turno					= \FMT\Helper\Arr::get($res,'hor_id_turno');
			$obj->horario->fecha_inicio				= \FMT\Helper\Arr::get($res,'hor_fecha_inicio');
			$obj->horario->fecha_fin				= \FMT\Helper\Arr::get($res,'hor_fecha_fin');
		}

		if(static::getContiene('horas_extras')){
			$obj->horas_extras							= new \StdClass();
			$obj->horas_extras->id						= \FMT\Helper\Arr::get($res,'hor_ex_id');
			$obj->horas_extras->anio                 	= \FMT\Helper\Arr::get($res,'hor_ex_anio'); 
			$obj->horas_extras->mes						= \FMT\Helper\Arr::get($res,'hor_ex_mes');
			$obj->horas_extras->acto_administrativo		= \FMT\Helper\Arr::get($res,'hor_ex_acto_adm');
		}

		if(static::getContiene('situacion_escalafonaria')){
			$aux = $obj->empleados_ultimos_cambios(EmpleadoUltimosCambios::NIVEL);
			$auxb = $obj->empleados_ultimos_cambios(EmpleadoUltimosCambios::GRADO);

			$obj->situacion_escalafonaria	= new \StdClass();
			$obj->situacion_escalafonaria->id = \FMT\Helper\Arr::get($res,'esc_id');
			$obj->situacion_escalafonaria->id_modalidad_vinculacion = \FMT\Helper\Arr::get($res,'esc_id_modalidad_vinculacion');
			$obj->situacion_escalafonaria->id_situacion_revista = \FMT\Helper\Arr::get($res,'esc_id_situacion_revista');
			$obj->situacion_escalafonaria->id_nivel = \FMT\Helper\Arr::get($res,'esc_id_nivel');
			$obj->situacion_escalafonaria->id_grado = \FMT\Helper\Arr::get($res,'esc_id_grado');
			$obj->situacion_escalafonaria->id_grado_liquidacion = \FMT\Helper\Arr::get($res,'esc_id_grado_liquidacion');
			$obj->situacion_escalafonaria->id_tramo = \FMT\Helper\Arr::get($res,'esc_id_tramo');
			$obj->situacion_escalafonaria->id_agrupamiento = \FMT\Helper\Arr::get($res,'esc_id_agrupamiento');
			$obj->situacion_escalafonaria->id_funcion_ejecutiva = \FMT\Helper\Arr::get($res,'esc_id_funcion_ejecutiva');
			$obj->situacion_escalafonaria->compensacion_geografica = \FMT\Helper\Arr::get($res,'esc_compensacion_geografica');
			$obj->situacion_escalafonaria->compensacion_transitoria = \FMT\Helper\Arr::get($res,'esc_compensacion_transitoria');
			$obj->situacion_escalafonaria->fecha_inicio = (\FMT\Helper\Arr::get($res,'esc_fecha_inicio')) ? \DateTime::createFromFormat('Y-m-d H:i:s', \FMT\Helper\Arr::get($res,'esc_fecha_inicio') . ' 0:00:00') : null;
			$obj->situacion_escalafonaria->fecha_fin =  (\FMT\Helper\Arr::get($res,'esc_fecha_desde')) ? \DateTime::createFromFormat('Y-m-d H:i:s', \FMT\Helper\Arr::get($res,'esc_fecha_desde') . ' 0:00:00') : null;
			$obj->situacion_escalafonaria->ultimo_cambio_nivel =  !empty($aux) ? \DateTime::createFromFormat('Y-m-d H:i:s.u', $aux['fecha_desde'] . ' 00:00:00.000000') : null;
			$obj->situacion_escalafonaria->ultimo_cambio_grado =  !empty($auxb) ? \DateTime::createFromFormat('Y-m-d H:i:s.u', $auxb['fecha_desde'] . ' 00:00:00.000000') : null;
			$obj->situacion_escalafonaria->exc_art_14 =  \FMT\Helper\Arr::get($res,'esc_exc_art_14', null);
			$obj->situacion_escalafonaria->unidad_retributiva = \FMT\Helper\Arr::get($res,'esc_unidad_retributiva');
		}

		if(static::getContiene('presupuesto')){
			$aux= \App\Modelo\Presupuesto::obtener(\FMT\Helper\Arr::get($res,'pre_id_presupuesto'));
			$obj->presupuesto							= new \StdClass();
			$obj->presupuesto->id						= \FMT\Helper\Arr::get($res,'pre_id');
			$obj->presupuesto->id_presupuesto			= \FMT\Helper\Arr::get($res,'pre_id_presupuesto');
			$obj->presupuesto->id_saf  					= $aux->id_saf;
			$obj->presupuesto->id_jurisdiccion 			= $aux->id_jurisdiccion;
			$obj->presupuesto->id_ubicacion_geografica  = $aux->id_ubicacion_geografica;
			$obj->presupuesto->id_programa  			= $aux->id_programa;
			$obj->presupuesto->id_subprograma 			= $aux->id_subprograma;
			$obj->presupuesto->id_proyecto 				= $aux->id_proyecto;
			$obj->presupuesto->id_actividad  			= $aux->id_actividad;
			$obj->presupuesto->id_obra 					= $aux->id_obra;

			$obj->presupuesto->fecha_desde				= isset($res['pre_fecha_desde']) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res['pre_fecha_desde'] . ' 0:00:00') : null;
			$obj->presupuesto->fecha_hasta				= isset($res['pre_fecha_hasta']) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res['pre_fecha_hasta'] . ' 0:00:00') : null;
		}

		if(static::getContiene('perfil_puesto')){
			$obj->perfil_puesto	= \App\Modelo\Perfil::obtener($obj->id);
		}
		if(static::getContiene('evaluaciones')){
			$obj->evaluaciones	= \App\Modelo\Evaluacion::listar($obj->id);
		}

		if(static::getContiene('licencia')){
			$aux = \App\Modelo\LicenciaEspecial::obtener(\FMT\Helper\Arr::get($res,'lic_id_licencia'));
			$obj->licencia 				= new \StdClass();
			$obj->licencia->id 			= \FMT\Helper\Arr::get($res,'lic_id');
			$obj->licencia->id_licencia = !empty($aux) ? $aux->id : null;
			$obj->licencia->nombre 		= !empty($aux) ? $aux->nombre : null;
			$obj->licencia->fecha_desde = isset($res['lic_fecha_desde']) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res['lic_fecha_desde'] . ' 0:00:00') : null;
			$obj->licencia->fecha_hasta = isset($res['lic_fecha_hasta']) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res['lic_fecha_hasta'] . ' 0:00:00') : null;
		}

		if(static::getContiene('empleado_salud')){
			$obj->empleado_salud					= new \StdClass();
			$obj->empleado_salud->id 				= \FMT\Helper\Arr::get($res,'sal_id');
			$obj->empleado_salud->id_obra_social	= \FMT\Helper\Arr::get($res,'sal_id_obra_social');
		}	

		if(static::getContiene('empleado_seguro')){
			$obj->empleado_seguro					= [];//new \StdClass();

			if($temp =\FMT\Helper\Arr::get($res,'seg_id_seguro')){
				$seg_id_seguro = explode(',', $temp);
				$seg_id_registro = explode(',', \FMT\Helper\Arr::get($res,'seg_id_registro')); 
				foreach ($seg_id_seguro as $key => $value) {
					$aux = new \StdClass();
					$aux->id = $seg_id_registro[$key];
					$aux->seguros = $value ;
					$obj->empleado_seguro[] = $aux; 					 
				}
			} else {
				$obj->empleado_seguro = [];	
			}

		}

		if(static::getContiene('sindicato')){
			$obj->sindicato	= \App\Modelo\EmpleadoSindicato::listar($obj->id);
		}

		if(static::getContiene('empleado_cursos')){
			 $obj->empleado_cursos                = \App\Modelo\EmpleadoCursos::listar($obj->id, 10);
		}

		return $obj;
	}



	public function alta_presupuesto(){
		if(!$this->validar()){
			return false;
		}
		$cnx	= new Conexiones();
		$this->errores	= [];


		if(empty($this->presupuesto->id)) {
			$campos	= [
				'id_empleado',
				'id_presupuesto',
				'fecha_desde',
				'fecha_hasta'
			];
			$sql_params	= [
				':id_empleado'	=> $this->id,
				'id_presupuesto'=> $this->presupuesto->id_presupuesto,
				':fecha_desde'	=> $this->presupuesto->fecha_desde,
				':fecha_hasta'	=> $this->presupuesto->fecha_hasta,
			];

			if($this->presupuesto->fecha_desde instanceof \DateTime){
				$sql_params[':fecha_desde']	= $this->presupuesto->fecha_desde->format('Y-m-d');
			}
			if($this->presupuesto->fecha_hasta instanceof \DateTime){
				$sql_params[':fecha_hasta']	= $this->presupuesto->fecha_hasta->format('Y-m-d');
			}
			$sql	= 'INSERT INTO empleado_presupuesto('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
			$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
			if($res !== false){
				$this->presupuesto->id	= $res;

			} else {
				$this->errores['empleado_presupuesto']	= $cnx->errorInfo[2];
			}
		}
	}

	public static function listadoAgentes($params=array(), $count = false) {
	
		$cnx	= new Conexiones();
		$where = [];
		$sql_params = [
			':estado_rechazado'	=> static::EMPLEADO_RECHAZADO,
			':estado_postulante'=> static::EMPLEADO_POSTULANTE,
		];
		$condicion = '';


		$default_params	= [
			'order'		=> [
				[
					'campo'	=> 'emp.id',
					'dir'	=> 'desc',
				],
			],
			'start'		=> 0,
			'lenght'	=> 10,
			'search'	=> '',
			'filtros'	=> [
				'dependencia'				=> null,
				'directos'					=> null,
				'modalidad_contratacion'	=> null,
				'situacion_revista'			=> null,
				'estado' 					=> null
			],
			'count'		=> false,
		];

		$params['filtros']	= array_merge($default_params['filtros'], $params['filtros']);
		$params	= array_merge($default_params, $params);
		$sql =

<<<SQL
		SELECT
			emp.id,
			emp.cuit, 
			p.nombre AS nombre,
			p.apellido AS apellido,			
			con_mod_vinc.nombre modalidad_vinculacion,
			revista.nombre situacion_revista,
			emp.estado AS estado,
			IF((emp.estado = :estado_rechazado OR emp.estado = :estado_postulante), 0, 1) AS agente_activo

SQL;

		switch ($params['filtros']['estado']) {
			case self::EMPLEADO_ACTIVO:
				$sql_params[':estado']	= static::EMPLEADO_ACTIVO;
				$join	= 'LEFT JOIN';
				break;
			case self::EMPLEADO_INACTIVO:
				$sql_params[':estado']	= static::EMPLEADO_INACTIVO;
				$join	= 'LEFT JOIN';
				break;
			case self::EMPLEADO_RECHAZADO:
				$sql_params[':estado']	= static::EMPLEADO_RECHAZADO;
				$join	= 'LEFT JOIN';
				break;
			case self::EMPLEADO_POSTULANTE:
				$sql_params[':estado']	= static::EMPLEADO_POSTULANTE;
				$join	= 'LEFT JOIN';
				break;
			default:
				$sql_params[':estado']	= static::EMPLEADO_ACTIVO;
				$join	= 'LEFT JOIN';
				break;
		}
		$where[]	= 'emp.estado = :estado AND !ISNULL(con_mod_vinc.id)';

		$from =

<<<SQL
		FROM empleados emp
		JOIN personas p 
			ON p.id = emp.id_persona
		LEFT JOIN empleado_dependencia AS e_dependencia 
			ON (e_dependencia.id_empleado = emp.id AND ISNULL(e_dependencia.fecha_hasta) AND e_dependencia.borrado = 0 AND emp.borrado = 0) 
		LEFT JOIN empleados_x_ubicacion AS e_ubicacion 
			ON (e_ubicacion.id_empleado = emp.id AND ISNULL( e_ubicacion.fecha_hasta) AND emp.borrado = 0) 
		LEFT JOIN empleado_escalafon AS e_escalafon 
			ON (e_escalafon.id_empleado = emp.id AND ISNULL( e_escalafon.fecha_fin) AND emp.borrado = 0)
		{$join} convenio_modalidad_vinculacion con_mod_vinc
			ON (con_mod_vinc.id = e_escalafon.id_modalidad_vinculacion)
		{$join} convenio_situacion_revista revista
			ON (e_escalafon.id_situacion_revista = revista.id  AND con_mod_vinc.id = revista.id_modalidad_vinculacion)		
SQL;

	$order = 
	<<<SQL
	ORDER BY emp.id DESC, 
		e_dependencia.id DESC, 
		e_ubicacion.id 
SQL;


	/**Filtros */
	if(!empty($params['filtros']['dependencia'])){
		$hijos = [];
		if($params['filtros']['directos'] == 'false') {
			$hijos = Dependencia::obtener_cadena_dependencias_hijas($params['filtros']['dependencia']);
            $hijos = array_column($hijos, 'id');
            if(is_array($params['filtros']['dependencia'])){
                $params['filtros']['dependencia'] += $hijos;
            } else {
                $params['filtros']['dependencia'] = $hijos;
            }
    	}

    	if(is_array($params['filtros']['dependencia'])) {
    		if(!empty(($params['filtros']['dependencia']))){
    			$where[] = "e_dependencia.id_dependencia  IN (:dependencia)";
    		}
			$sql_params[':dependencia']	= $params['filtros']['dependencia'];
		}else{
			$where[] = "e_dependencia.id_dependencia = :dependencia";
			$sql_params[':dependencia']	= $params['filtros']['dependencia'];
		}
	}

	if(!empty($params['filtros']['situacion_revista'])){
		$where[] = "revista.id = :revista";
		$sql_params[':revista']	= $params['filtros']['situacion_revista'];
	}

	if(!empty($params['filtros']['modalidad_contratacion'])) {
		if(is_array($params['filtros']['modalidad_contratacion'])) {

				$where[] = "con_mod_vinc.id IN (:modalidad_vinculacion)";
				$sql_params[':modalidad_vinculacion'] = $params['filtros']['modalidad_contratacion'];
		} else {
			$where[] = "con_mod_vinc.id = :modalidad_vinculacion";
			$sql_params[':modalidad_vinculacion'] = $params['filtros']['modalidad_contratacion'];
		}

 	} 
 	elseif ($sql_params[':estado']	!= static::EMPLEADO_INACTIVO) {
			$where[] = " (con_mod_vinc.id IN (:modalidad_vinculacion) OR ISNULL(con_mod_vinc.id))";
			$sql_params[':modalidad_vinculacion']	= \App\Modelo\AppRoles::obtener_modalidades_vinculacion_autorizadas();
	}

	$condicion = !empty($where) ? ' WHERE ' . \implode(' AND ',$where) : '';	
	
	/** Total de registros de Empleados */
	$counter_query	= "SELECT COUNT(DISTINCT emp.id) AS total {$from}";
	$recordsTotal	=  $cnx->consulta(Conexiones::SELECT, $counter_query , [])[0]['total'];
		
	/** Buscar Elementos */

	if(!empty($params['search'])){
		$search	= [];
		foreach (explode(' ', (string)$params['search']) as $indice => $texto) {
			$search[]	= <<<SQL
			
				(emp.cuit LIKE :search{$indice} OR
				CONCAT(p.nombre, ' ' , p.apellido) LIKE :search{$indice} OR
				con_mod_vinc.nombre LIKE :search{$indice} OR
				revista.nombre  LIKE :search{$indice})
			
SQL;
			$sql_params[":search{$indice}"]	= "%{$texto}%";
		}
		$buscar =  implode(' AND ', $search);
		$condicion .= empty($condicion) ? " WHERE {$buscar}" : " AND {$buscar} ";
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
		$order 	=  ' ORDER BY '.implode(',', $orderna);		
	}

	/**Limit: funcionalidad: desde-hasta donde se pagina */
	$limit	= (isset($params['lenght']) && isset($params['start']))
			? " LIMIT  {$params['start']}, {$params['lenght']}"	:	' ';

	$recordsFiltered= $cnx->consulta(Conexiones::SELECT, $counter_query . $condicion, array_diff_key($sql_params, [
		':estado_rechazado' => 1, 
		':estado_postulante'=> 1
	]))[0]['total'];

	$lista			= $cnx->consulta(Conexiones::SELECT, $sql .$from. $condicion . $order . $limit, $sql_params);

	if($lista){
		foreach ($lista as $key => &$value) {
			if(isset($value['fecha_fin'])){
				$value['fecha_fin'] = \DateTime::createFromFormat('Y-m-d',$value['fecha_fin'])->format('d/m/Y');
			}
		}
	}

	return [
		'recordsTotal'    => !empty($recordsTotal) ? $recordsTotal : 0,
		'recordsFiltered' => !empty($recordsFiltered) ? $recordsFiltered : 0,
		'data'            => $lista ? $lista : [],
	];
	}

	
	public function modificacion_presupuesto() {
		$cnx	= new Conexiones();
		if(!$this->validar() || empty($this->id)) {
			return false;
		}
		$campos	= [
			'id_presupuesto'  	=> 'id_presupuesto = :id_presupuesto',
			'fecha_desde'		=> 'fecha_desde = :fecha_desde',
			'fecha_hasta'		=> 'fecha_hasta = :fecha_hasta',
		];
		$sql_params	= [
			':id'				=> $this->presupuesto->id,

		];

		$sql_params[':id_presupuesto'] = $this->presupuesto->id_presupuesto;
		$sql_params[':fecha_desde']	= ($this->presupuesto->fecha_desde instanceof \DateTime) ? $this->presupuesto->fecha_desde->format('Y-m-d') : null;
		$sql_params[':fecha_hasta']	= ($this->presupuesto->fecha_hasta instanceof \DateTime) ? $this->presupuesto->fecha_hasta->format('Y-m-d') : null;

		$sql	= 'UPDATE empleado_presupuesto SET '.implode(',', $campos).' WHERE id = :id';
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res !== false) {
			$datos = (array) $this;
			$datos['modelo'] = 'empleado_presupuesto';
			Logger::event('modificacion', $datos);
		}
		return $res;
	}

	public function modificacion_comision(){

		if(!$this->validar() || empty($this->id)){
			return false;
		}
		$cnx	= new Conexiones();

		if($this->en_comision->activo == self::ESTADO_ACTIVO){
			$this->en_comision->fecha_inicio	= \DateTime::createFromFormat('U', strtotime('now'));
			$campos	= [
				'id_empleado',
				'id_comision_origen',
				'id_comision_destino',
				'fecha_inicio',
				'fecha_fin',
			];
			$sql_params	= [
				':empleado_id'			=> $this->id,
				':comision_origen_id'	=> $this->en_comision->id_origen,
				':comision_destino_id'	=> $this->en_comision->id_destino,
				':fecha_inicio'			=> $this->en_comision->fecha_inicio,
				':fecha_fin'			=> $this->en_comision->fecha_fin,
			];
			$sql	= <<<SQL
				id_empleado			= :empleado_id,
				id_comision_origen	= :comision_origen_id,
				id_comision_destino	= :comision_destino_id,
				fecha_inicio		= :fecha_inicio,
				fecha_fin			= :fecha_fin
SQL;
			if($this->en_comision->fecha_inicio instanceof \DateTime){
				$sql_params[':fecha_inicio']	= $this->en_comision->fecha_inicio->format('Y-m-d');
			}
			if($this->en_comision->fecha_fin instanceof \DateTime){
				$sql_params[':fecha_fin']	= $this->en_comision->fecha_fin->format('Y-m-d');
			}
			$sql	= 'INSERT INTO empleado_comision('.implode(',', $campos).') VALUES ('.implode(',', array_keys($sql_params)).')';
			$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
			if($res !== false){				
				$this->en_comision->id	= $res;
				$this->baja_comision_anterior();
				$sql	= 'UPDATE empleados SET en_comision =:activo WHERE id = :id';
				$res	= $cnx->consulta(Conexiones::UPDATE, $sql, [
					':id'		=> $this->id,
					':activo'	=> self::ESTADO_ACTIVO,
				]);
				if($res !== false){
					$datos = (array) $this;
					$datos['modelo'] = 'Empleado';
					Logger::event('modificacion', $datos);
				}
				return true;
			} else {
				$this->errores['empleado_comision']	= $cnx->errorInfo[2];
			}
		} else {
			$sql_params	= [
				':id'	=> $this->en_comision->id
			];
			$this->en_comision->fecha_fin	= \DateTime::createFromFormat('U', strtotime('now'));
			if($this->en_comision->fecha_fin instanceof \DateTime){
				$sql_params[':fecha_fin']	= $this->en_comision->fecha_fin->format('Y-m-d');
			}

			$sql	= 'UPDATE empleado_comision SET fecha_fin =:fecha_fin WHERE id = :id';
			$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);
			if($res !== false){
				$sql	= 'UPDATE empleados SET en_comision =:activo WHERE id = :id';
				$res	= $cnx->consulta(Conexiones::UPDATE, $sql, [
					':id'		=> $this->id,
					':activo'	=> self::ESTADO_INACTIVO,
				]);

				if($res !== false){
					$datos = (array) $this;
					$datos['modelo'] = 'Empleado';
					Logger::event('modificacion', $datos);
				}
				return true;
			}
		}
		return false;
	}

	public static function exportarExcel($campos = [], $extras = []){
//\App\Helper\Conexiones::activarDebug();
		if(!empty($campos)){
			$comisiones = Comision::listar(true);
			$select_default = [
				/** Campos bloque datos personas */
				'id' 						=> "emp.id",
				'id_persona' 				=> "p.id id_persona",
				'id_perfil'					=> "e_perfil.id id_perfil",	
				'cuit' 						=> "emp.cuit",
				'nombre' 					=> "p.nombre",
				'apellido' 					=> "p.apellido",
				'email'						=> "emp.email email",
				'tipo_documento'			=> "p.tipo_documento",
				'documento'					=> "p.documento documento",
				'nacionalidad'				=> "IF(p.nacionalidad!='0',p.nacionalidad,'S/D') nacionalidad",
				'fecha_nac'					=> "p.fecha_nac",
				'genero'					=> "p.genero",
				'estado_civil'				=> "p.estado_civil",
				'telefono'					=> "'' AS telefono",
				'cod_postal'				=> "IF(pd.cod_postal IS NULL,'S/D',pd.cod_postal) cod_postal",
				'calle'						=> "IF(pd.calle IS NULL,'S/D', pd.calle) calle",
				'numero'					=> "IF(pd.numero IS NULL,'S/D',pd.numero) numero",
				'piso'						=> "IF(pd.piso IS NULL,'S/D',pd.piso) piso",
				'depto'						=> "IF(pd.depto IS NULL,'S/D',pd.depto) depto",
				'localidad'					=> "pd.id_localidad as localidad",
				'provincia'					=> "pd.id_provincia as provincia",

				/** Campos bloque situación escalafonaria */
				'modalidad_vinculacion' 	=> "con_mod_vinc.nombre modalidad_vinculacion",
				'situacion_revista' 		=> "revista.nombre situacion_revista",
				'funcion_ejecutiva'			=> "con_fe.nombre funcion_ejecutiva",
				'agrupamiento' 				=> "con_ag.nombre agrupamiento",
				'nivel_funcion'				=> "con_ni.nombre nivel_funcion",
				'compensacion_geografica'	=> "e_escalafon.compensacion_geografica",
				'tramo'						=> "con_tra.nombre tramo",
				'grado' 					=> "con_gra.nombre grado",
				'grado_liquidacion' 		=> "con_gra_liq.nombre grado_liquidacion",
				'compensacion_transitoria'	=> "e_escalafon.compensacion_transitoria",
				'exc_art_14'				=> "IF((e_escalafon.exc_art_14 IS NULL OR e_escalafon.exc_art_14 = ''), 'NO.', 'SI.') AS exc_art_14",
				'opc_art_14'				=> 'e_escalafon.exc_art_14 AS opc_art_14',
				'delegado_gremial'			=> "IF(emp.id_sindicato IS NULL, 'NO.', 'SI.') AS delegado_gremial",
				'fecha_vigencia_mandato'	=> "emp.fecha_vigencia_mandato AS fecha_vigencia_mandato",
				'sindicato'					=> "sindicato.nombre AS sindicato",

				/** Campos bloque ubicacion estructura */
				'nivel_organigrama'			=> "'' AS nivel_organigrama",
				'dependencia'				=> "e_dependencia.id_dependencia AS dependencia",
				'ministro'					=> "'--' ministro",
				'secretaria'				=> "'--' secretaria",
				'subsecretaria'				=> "'--' subsecretaria",
				'direccion_general'			=> "'--' direccion_general",  
				'direccion_simple'			=> "'--' direccion_simple",
				'coordinacion'				=> "'--' coordinacion",
				'unidad_o_area'				=> "'--' unidad_o_area",
				'dependencia_informal'		=> "dep_in.nombre as dependencia_informal",

				/** Campos bloque perfiles de puestos */
				'familia_puestos'			=> "fdp.nombre as familia_puestos",
				'nombre_puesto'				=> "pues.nombre nombre_puesto",
				'nivel_destreza'			=> "e_perfil.nivel_destreza",
				'puesto_supervisa'			=> "e_perfil.puesto_supervisa",
				'denominacion_funcion'		=> "df.nombre denominacion_funcion",
				'denominacion_puesto'		=> "dp.nombre denominacion_puesto",
				'objetivo_general'			=> "e_perfil.objetivo_gral AS objetivo_general",
				'objetivo_especificos'		=> "e_perfil.objetivo_especifico AS objetivo_especificos",
				'estandares'				=> "e_perfil.estandares",
				'nivel_complejidad'			=> "e_perfil.nivel_complejidad",
				'actividades'				=> "'' AS actividades",
				'fecha_obtencion_result'	=> "e_perfil.fecha_obtencion_result",
				'resultados_finales'		=> "'' AS resultados_finales",	
				'evaluacion_anio'			=> "pev.anio AS evaluacion_anio",
				'evaluacion_formulario'		=> "pev.formulario AS evaluacion_formulario",
				'evaluacion_resultado'		=> "pev.evaluacion AS evaluacion_resultado",
				'evaluacion_puntaje'		=> "pev.puntaje AS evaluacion_puntaje",
				'evaluacion_bonificado'		=> "IF(pev.bonificado = 1, 'SI', 'NO')  AS evaluacion_bonificado",
				'evaluacion_acto'			=> "pev.acto_administrativo AS evaluacion_acto",		

				/** Campos bloque formacion */
				'nivel_educativo'			=> "'' AS nivel_educativo",
				'estado_titulo'				=> "'' AS estado_titulo",
				'nombre_titulo'				=> "'' AS nombre_titulo",
				'fecha_otorgamiento'		=> "'' AS fecha_otorgamiento",
				'titulos_adicionales'				=> "'' AS titulos_adicionales",
				'otros_estudios'			=> "'' AS otros_estudios",
				'otros_conocimientos'		=> "'' AS otros_conocimientos",
				'cursos'				=> "'' AS cursos",

				/** Campos bloque antiguedad */
				'antiguedad_adm_publica'	=> "emp.antiguedad_adm_publica antiguedad_adm_publica",
				'antiguedad_otros_organismos' => "'' AS antiguedad_otros_organismos",
				'experiencia_laboral'          => "'' AS experiencia_laboral",
				'fecha_ingreso_mtr'			=> "'' AS fecha_ingreso_mtr",
				'fecha_otorgamiento_grado'  => "euc.fecha_desde AS fecha_otorgamiento_grado" ,

				/** Campos bloque administracion */
				'horarios'					=> "e_horarios.horarios horarios",
				'turno'						=> "e_horarios.id_turno turno",
				'piso_oficina'				=> "u.piso AS piso_oficina",
				'num_oficina'				=> "u.oficina AS num_oficina",
				'calle_edificio'			=> "u_edificios.calle AS calle_edificio",
				'numero_edificio'			=> "u_edificios.numero AS numero_edificio",
				'localidad_edificio'		=> "u_edificios.id_localidad AS localidad_edificio",
				'provincia_edificio'		=> "u_edificios.id_provincia AS provincia_edificio",
				'licencias_especiales'		=> "l_especiales.nombre AS licencias_especiales",
				'fecha_inicio_lic_esp'		=> "e_lic_especiales.fecha_desde AS fecha_inicio_lic_esp",
				'fecha_fin_lic_especiales'  => "e_lic_especiales.fecha_hasta AS fecha_fin_lic_especiales",
				'estado' 					=> "emp.estado",
				'fecha_baja'				=> "IF(emp.fecha_baja IS NULL, '', emp.fecha_baja) fecha_baja",
				'motivo_baja' 				=> "emp.id_motivo AS motivo_baja",
				'en_comision'				=> "IF(emp.en_comision = 1, 'SI','NO') en_comision",	
				'destino_comision'			=> "e_comision.id_comision_destino destino_comision",	
				'origen_comision'			=> "e_comision.id_comision_origen origen_comision",
				'horas_extras'				=> "CONCAT('[\"ANIO\":\"',e_horas_extras.anio,'\",\"MES\":\"',e_horas_extras.mes,'\",\"ACTO_ADMINISTRATIVO\":\"',e_horas_extras.acto_administrativo,'\"]') AS horas_extras",

				/** Campos bloque varios */
				'credencial_acceso'			=> "IF(emp.credencial = 1, 'SI','NO') AS credencial_acceso",
				'fecha_ven_credencial'		=> "emp.fecha_vencimiento AS fecha_ven_credencial",
				'tipo_discapacidad'			=> "tdis.nombre AS tipo_discapacidad",
				'cud'						=> "pdis.cud",
				'fecha_ven_cud'				=> "pdis.fecha_vencimiento AS fecha_ven_cud",
				'obs_cud'					=> "pdis.observaciones AS obs_cud",	
				'sindicatos_varios'			=> "'' AS sindicatos_varios",
				'obra_social'				=> 'es.id_obra_social AS obra_social',
				'seguro_vida'				=> "'' AS seguro_vida",
				'veterano_guerra'			=> 'IF(emp.veterano_guerra = 1, "SI", "NO") AS veterano_guerra',

				/* Campos bloque presupuesto */
				'saf'						=> "IF(p_saf.id IS NULL,'--',CONCAT(p_saf.id,'-',p_saf.nombre)) saf",
				'jurisdiccion'				=> "IF(p_jur.id IS NULL,'--',CONCAT(p_jur.id,'-',p_jur.nombre)) jurisdiccion",
				'ubicacion_geografica'		=> "IF(p_ubg.id IS NULL,'--',CONCAT(p_ubg.id,'-',p_ubg.nombre)) ubicacion_geografica",
				'programa'					=> "IF(p_pro.id IS NULL,'--',CONCAT(p_pro.id,'-',p_pro.nombre)) programa",
				'subprograma'				=> "IF(p_subp.id IS NULL,'--',CONCAT(p_subp.id,'-',p_subp.nombre)) subprograma",
				'proyecto'					=> "IF(p_proy.id IS NULL,'--',CONCAT(p_proy.id,'-',p_proy.nombre)) proyecto",
				'actividad'					=> "IF(p_act.id IS NULL,'--',CONCAT(p_act.id,'-',p_act.nombre)) actividad",
				'obra'						=> "IF(p_obra.id IS NULL,'--',CONCAT(p_obra.id,'-',p_obra.nombre)) obra",
				/* Campos bloque Anticorrupcion*/
				'obligado_prensentar_declaracion'	=> "'' AS obligado_prensentar_declaracion",
				'fecha_designacion'					=> "'' AS fecha_designacion",
				'fecha_publicacion_designacion'		=> "'' AS fecha_publicacion_designacion",
				'fecha_aceptacion_renuncia'			=> "'' AS fecha_aceptacion_renuncia",
				'tipo_presentacion'					=> "'' AS tipo_presentacion",
				'fecha_presentacion'				=> "'' AS fecha_presentacion",
				'periodo'							=> "'' AS periodo",
				'nro_transaccion'					=> "'' AS nro_transaccion",
				
				/* Campos designacion transitoria*/
				'fecha_desde'					=> "'' AS fecha_desde",
				'fecha_hasta'					=> "'' AS fecha_hasta",
				'tipo'		 					=> "'' AS tipo",
			
				/* Campos bloque  Familiar*/
				'familiares'				=> "'' AS familiares",
				/* Campos bloque Embargos*/
				'embargos'					=> "'' AS embargos",
			];
			$campos_extra = ['id' => 'id', 'id_persona' => 'id_persona', 'id_perfil' => 'id_perfil','fecha_baja' => 'fecha_baja'];
			
			$campos = array_merge($campos_extra,$campos); 

			$select_default =  array_intersect_key($select_default, $campos);
			$campos_query	= implode(', ',$select_default);
			$where			= [];
			$sql_params		= [];
			$condicion		= '';

			//Define los alias de los campos proyectados para incluir en los joins a las tablas asociadas 
			$filtro_joins = array_values($select_default);
			array_walk($filtro_joins,function(&$val){
				$val = \preg_replace([ '/\'\'|\(|IF|IS|NULL|NO|SI|AS|S\/D|\,/','/\'--\'.*$/','/CONCAT\'\[\"ANIO\":\"\'/', '/\..*/', '/  /'],'',$val);
			});
			$filtro_joins = array_filter (array_unique($filtro_joins),'strlen');
			if( !empty($extras['dependencia'])) {
				if( count($extras['dependencia']) > 1) {
					$where[] = "e_dependencia.id_dependencia  IN (:dependencia)";
					$sql_params[':dependencia']	= $extras['dependencia'];
				} else {
					$where[] = "e_dependencia.id_dependencia = :dependencia";
					$sql_params[':dependencia']	= $extras['dependencia'];
				}
			}
			if(!empty($extras['estado']) && ($extras['estado'] == self::EMPLEADO_ACTIVO)){
				$where[] = "emp.estado =".self::EMPLEADO_ACTIVO;
			} elseif (!empty($extras['estado']) && ($extras['estado'] == self::EMPLEADO_INACTIVO)){
				$where[] = "emp.estado = ".self::EMPLEADO_INACTIVO;
			} else {
				$where[] = "emp.estado =".self::EMPLEADO_ACTIVO;
			}
			$where[] = "emp.borrado = 0";

			if(!empty($extras['situacion_revista'])){
				if(!is_array($extras['situacion_revista'])) {	
					$where[] = "e_escalafon.id_situacion_revista = :revista";
				}else{
					$where[] = "e_escalafon.id_situacion_revista IN (:revista)";
				}	
				$sql_params[':revista']	= $extras['situacion_revista'];
			}

			if(!empty($extras['modalidad_contratacion'])){
				if(!is_array($extras['modalidad_contratacion'])) {
					
					$where[] = "e_escalafon.id_modalidad_vinculacion = :modalidad_vinculacion";
				}else{
					$where[] = "e_escalafon.id_modalidad_vinculacion IN (:modalidad_vinculacion)";
				}	
				$sql_params[':modalidad_vinculacion'] = $extras['modalidad_contratacion'];

			}

			$condicion = !empty($where) ? ' WHERE ' . \implode(' AND ',$where) : '';

			$mes	= date('m');
			$anio	= date('Y'); 


$v_pres = (function($filtro_joins){
	foreach ($$filtro_joins as $value) {
		if(in_array($value,['p_saf','p_jur','p_ubg', 'p_pro', 'p_subp', 'p_proy', 'p_act', 'p_obra'])){
			return true;
		}		
	}
	return false;
});
			$sql_presupuestos ='';
		if ($v_pres) {
			$sql_presupuestos .= <<<SQL
				LEFT JOIN empleado_presupuesto emp_p
					ON emp_p.id_empleado = emp.id AND emp_p.borrado = 0
SQL;
		}
		if ($v_pres) {			
			$sql_presupuestos .= <<<SQL
				LEFT JOIN presupuestos presu
					ON presu.id = emp_p.id_presupuesto
SQL;
		}
		if (in_array('p_saf', $filtro_joins)) {
			$sql_presupuestos .= <<<SQL
				LEFT JOIN presupuesto_saf p_saf
					ON p_saf.id = presu.id_saf AND p_saf.borrado = 0
SQL;
		}
		if (in_array('p_jur', $filtro_joins)) {
			$sql_presupuestos .= <<<SQL
				LEFT JOIN presupuesto_jurisdicciones p_jur
					ON p_jur.id = presu.id_jurisdiccion AND p_jur.borrado = 0
SQL;
		}
		if (in_array('p_ubg', $filtro_joins)) {			
			$sql_presupuestos .= <<<SQL
				LEFT JOIN presupuesto_ubicaciones_geograficas p_ubg
					ON p_ubg.id = presu.id_ubicacion_geografica AND p_ubg.borrado = 0
SQL;
		}
		if (in_array('p_pro', $filtro_joins)) {			
			$sql_presupuestos .= <<<SQL
				LEFT JOIN presupuesto_programas p_pro
					ON p_pro.id = presu.id_programa AND p_pro.borrado = 0
SQL;
		}
		if (in_array('p_subp', $filtro_joins)) {
			$sql_presupuestos .= <<<SQL
				LEFT JOIN presupuesto_subprogramas p_subp
					ON p_subp.id = presu.id_subprograma AND p_subp.borrado = 0
SQL;
		}
		if (in_array('p_proy', $filtro_joins)) {			
			$sql_presupuestos .= <<<SQL
				LEFT JOIN presupuesto_proyectos p_proy
					ON p_proy.id = presu.id_proyecto AND p_proy.borrado = 0
SQL;
		}
		if (in_array('p_act', $filtro_joins)) {			
			$sql_presupuestos .= <<<SQL
				LEFT JOIN presupuesto_actividades p_act
					ON p_act.id = presu.id_actividad AND p_act.borrado = 0
SQL;
		}
		if (in_array('p_obra', $filtro_joins)) {			
			$sql_presupuestos .= <<<SQL
				LEFT JOIN presupuesto_obras p_obra
					ON p_obra.id = presu.id_obra AND p_obra.borrado = 0			
SQL;
		}

		$sql_perfiles_puestos ='';
		if (in_array('e_perfil', $filtro_joins)) {
			$sql_perfiles_puestos .= <<<SQL
				LEFT JOIN empleado_perfil e_perfil
					ON emp.id = e_perfil.id_empleado AND ISNULL(e_perfil.fecha_hasta)
SQL;
		}

		if (in_array('fdp', $filtro_joins)) {
			$sql_perfiles_puestos .= <<<SQL
				LEFT JOIN familia_puestos fdp
					ON fdp.id = e_perfil.familia_de_puestos
SQL;
		}
		if (in_array('pev',$filtro_joins)){
			$sql_perfiles_puestos .= <<<SQL
				LEFT JOIN empleado_evaluaciones pev
					ON pev.id_empleado = emp.id and pev.anio=YEAR(NOW())
SQL;
		}
		if (in_array('df', $filtro_joins)) {
			$sql_perfiles_puestos .= <<<SQL
				LEFT JOIN denominacion_funcion df
					ON df.id = e_perfil.denominacion_funcion
SQL;
		}
		if (in_array('dp', $filtro_joins)) {
			$sql_perfiles_puestos .= <<<SQL
				LEFT JOIN denominacion_puesto dp
					ON dp.id = e_perfil.denominacion_puesto
SQL;
		}
		if (in_array('pues', $filtro_joins)) {
			$sql_perfiles_puestos .= <<<SQL
				LEFT JOIN puestos pues
					ON pues.id = e_perfil.nombre_puesto
SQL;
		}

			$sql_administracion = '';
			if (in_array('u', $filtro_joins)) {
				$sql_administracion .= <<<SQL
				LEFT JOIN ubicaciones u
					ON u.id =  e_ubicacion.id_ubicacion
SQL;
			}
			if (in_array('u_edificios', $filtro_joins)) {
				$sql_administracion .= <<<SQL
				LEFT JOIN ubicacion_edificios u_edificios
					ON u_edificios.id = u.id_edificio
SQL;
			}
			if (in_array('e_lic_especiales', $filtro_joins)) {
				$sql_administracion .= <<<SQL
				LEFT JOIN (SELECT id_empleado, MAX(fecha_desde) AS max_fecha_desde FROM empleados_lic_especiales WHERE borrado = 0 GROUP BY id_empleado) AS __e_lic_especiales ON (__e_lic_especiales.id_empleado = emp.id)
				LEFT JOIN empleados_lic_especiales e_lic_especiales
					ON e_lic_especiales.id_empleado = emp.id AND __e_lic_especiales.max_fecha_desde = e_lic_especiales.fecha_desde
SQL;
			}
			if (in_array('l_especiales', $filtro_joins)) {
				$sql_administracion .= <<<SQL
				LEFT JOIN licencias_especiales l_especiales
					ON l_especiales.id = e_lic_especiales.id_licencia
SQL;
			}
			if (in_array('e_horas_extras', $filtro_joins)) {
				$sql_administracion .= <<<SQL
				LEFT JOIN empleado_horas_extras AS e_horas_extras ON (e_horas_extras.id_empleado = emp.id AND  e_horas_extras.borrado = 0 AND e_horas_extras.mes = '{$mes}' AND e_horas_extras.anio = '{$anio}')
SQL;
			}	

			$sql_rol_administrador ='';
			$sql_varios = '';
			if (in_array('pdis', $filtro_joins)) {			
				$sql_varios .= 
<<<SQL
				LEFT JOIN persona_discapacidad pdis
					ON pdis.id_persona = p.id 
SQL;
			}

			if (in_array('tdis', $filtro_joins)) {
				$sql_varios .=
<<<SQL
				LEFT JOIN tipo_discapacidad tdis
					ON tdis.id = pdis.id_tipo_discapacidad
SQL;
			}

			if (in_array('es', $filtro_joins)) {
				$sql_varios .=
<<<SQL
				LEFT JOIN empleado_salud es ON es.id_empleado = emp.id AND es.fecha_hasta IS NULL
SQL;
			}

			$sql =
<<<SQL
		SELECT
			{$campos_query}
		FROM 
		empleados emp
		JOIN personas p 
			ON p.id = emp.id_persona
SQL;
if(in_array('pd',$filtro_joins)) {
$sql .=
<<<SQL
		LEFT JOIN persona_domicilio pd
			ON pd.id_persona = p.id AND ISNULL(pd.fecha_baja)
SQL;
}


if(in_array('e_horarios',$filtro_joins)) {
$sql .=
<<<SQL
		LEFT JOIN empleado_horarios AS e_horarios 
			ON (e_horarios.id_empleado = emp.id AND e_horarios.fecha_fin IS NULL AND e_horarios.borrado = 0) 
SQL;
}

$sql .=
<<<SQL
		LEFT JOIN empleados_x_ubicacion AS e_ubicacion 
			ON (e_ubicacion.id_empleado = emp.id AND e_ubicacion.fecha_hasta IS NULL ) 
SQL;

//if(in_array('e_dependencia',$filtro_joins)) {
$sql .=
<<<SQL
		LEFT JOIN empleado_dependencia AS e_dependencia 
			ON (e_dependencia.id_empleado = emp.id AND e_dependencia.fecha_hasta IS NULL AND e_dependencia.borrado = 0) 
SQL;
//}
if(in_array('e_comision',$filtro_joins)) {
$sql .= 
<<<SQL
		LEFT JOIN empleado_comision AS e_comision 
			ON (e_comision.id_empleado = emp.id AND emp.en_comision = 1 AND e_comision.fecha_fin IS NULL) 
SQL;
}

$sql .= <<<SQL
		LEFT JOIN empleado_escalafon AS e_escalafon 
			ON (e_escalafon.id_empleado = emp.id AND e_escalafon.fecha_fin IS NULL)
SQL;

//Podria Extraerse
$sql .=
<<<SQL
		JOIN convenio_modalidad_vinculacion con_mod_vinc
			ON con_mod_vinc.id = e_escalafon.id_modalidad_vinculacion
SQL;

if(in_array('revista',$filtro_joins)) {
//Podria Extraerse
	$sql .=
<<<SQL
		LEFT JOIN convenio_situacion_revista revista
			ON e_escalafon.id_situacion_revista = revista.id AND revista.borrado = 0
SQL;
}
if(in_array('con_tra',$filtro_joins)) {
	//Podria Extraerse
$sql .= 
<<<SQL
		LEFT JOIN convenio_tramos con_tra
			ON con_tra.id = e_escalafon.id_tramo AND con_tra.borrado = 0
SQL;
}
//Podria Extraerse
if(in_array('con_ni',$filtro_joins)) {
$sql .=
<<<SQL
		LEFT JOIN convenio_niveles con_ni
			ON con_ni.id = e_escalafon.id_nivel AND con_ni.borrado = 0
SQL;
}
//Podria Extraerse
if(in_array('con_ag',$filtro_joins)) {
$sql .=
<<<SQL
		LEFT JOIN convenio_agrupamientos con_ag
			ON con_ag.id = e_escalafon.id_agrupamiento AND con_ag.borrado = 0
SQL;
}
//Podria Extraerse
if(in_array('con_gra',$filtro_joins)) {
$sql .=
<<<SQL
		LEFT JOIN convenio_grados con_gra
			ON con_gra.id = e_escalafon.id_grado AND con_gra.borrado = 0
SQL;
}
//Podria Extraerse
if(in_array('con_gra_liq',$filtro_joins)) {
	$sql .=
	<<<SQL
			LEFT JOIN convenio_grados con_gra_liq
				ON con_gra_liq.id = e_escalafon.id_grado_liquidacion AND con_gra_liq.borrado = 0
	SQL;
	}
//Podria Extraerse
if(in_array('con_fe',$filtro_joins)) {
$sql .=
<<<SQL
		LEFT JOIN convenio_funciones_ejecutivas con_fe
			ON con_fe.id = e_escalafon.id_funcion_ejecutiva AND con_fe.borrado = 0
SQL;
}

if(in_array('sindicato',$filtro_joins)) {
$sql .=
<<<SQL
		LEFT JOIN  sindicatos AS sindicato ON sindicato.id = emp.id_sindicato 
SQL;
}

$sql .=
<<<SQL
		LEFT JOIN empleado_dep_informales edi
			ON edi.id_empleado = emp.id AND edi.fecha_hasta IS NULL
SQL;
//Podria Extraerse
$sql .=
<<<SQL
		LEFT JOIN dependencias_informales dep_in
			ON dep_in.id = edi.id_dep_informal
SQL;
//Obtiene datos de grado del empleado
$sql .=
<<<SQL
		LEFT JOIN empleado_ultimos_cambios AS euc
			ON (euc.id_empleado = emp.id AND euc.id_tipo = 2 AND euc.id_convenios = e_escalafon.id_grado AND euc.fecha_hasta IS NULL)
SQL;
if(in_array('pel',$filtro_joins)) {
$sql .=
<<<SQL
		LEFT JOIN persona_experiencia_laboral pel
			ON pel.id_persona = emp.id_persona AND pel.borrado = 0
SQL;
}

$sql .=
<<<SQL
		{$sql_rol_administrador}
		{$sql_administracion}
		{$sql_perfiles_puestos}
		{$sql_varios}
		{$sql_presupuestos}
		{$condicion}
		ORDER BY emp.id DESC
SQL;
	if (in_array('e_dependencia', $filtro_joins)) {
		$sql .= <<<SQL
			,e_dependencia.id_dependencia DESC 
SQL;
	}
	if (in_array('e_comision', $filtro_joins)) {
				$sql .= <<<SQL
			,e_comision.id DESC
SQL;
	}
	if (in_array('e_horarios', $filtro_joins)) {
		$sql .= <<<SQL
			,e_horarios.id DESC
SQL;
	}
	if (in_array('e_ubicacion', $filtro_joins)) {
		$sql .= <<<SQL
			,e_ubicacion.id
SQL;
	}
		$cnx =  new Conexiones(); 
		$resultado = $cnx->consulta(Conexiones::SELECT,$sql, $sql_params);

		//Sub querys extraidas. #################################################################
		$formacion_default = [
				/** Campos bloque formacion */
				'nivel_educativo'			=> "pt.id_tipo_titulo as nivel_educativo",
				'estado_titulo'				=> "pt.id_estado_titulo as estado_titulo",
				'nombre_titulo'				=> "pt.nombre as nombre_titulo",
				'fecha_otorgamiento'		=> "pt.fecha as fecha_otorgamiento",
			];
		$query_formacion = array_intersect_key($formacion_default, $campos);		
		$persona_titulo = [];	
		if (!empty($query_formacion)) {
			$campos_form = implode(',', $query_formacion);
			$sql = <<<SQL
				SELECT pt.id_persona, $campos_form FROM tv_persona_titulos pt order by pt.id_persona, pt.principal desc;
SQL;
			$aux = $cnx->consulta(Conexiones::SELECT, $sql); 
			$auxIdPersona = 0;
			foreach ($aux as  $val) {
				$id_persona = $val['id_persona'];
				if($auxIdPersona != $id_persona){
					$persona_titulo[$id_persona] = $val; 
					$auxIdPersona = $id_persona;
				}else{					
					unset($val['id_persona']);
					$persona_titulo[$id_persona]['titulos_adicionales'][] = $val;
				}
			}
		}

			$persona_otros_es_con = [];
			if(array_key_exists('otros_estudios',$campos) || array_key_exists('otros_conocimientos',$campos)){
				$sql = <<<SQL
				SELECT * FROM tv_persona_otros_estudios;
SQL;
				$aux = $cnx->consulta(Conexiones::SELECT, $sql);
				if(!empty($aux)){
					foreach ($aux as  $val) {
						$persona_otros_es_con[$val['id_tipo']][$val['id_persona']] = $val['otros_e_c']; 
					}
				}
			}

			
			$empleado_curso = [];
			if (in_array('cursos', $campos)) {
				$sql = <<<SQL
					SELECT * FROM tv_empleado_cursos;
SQL;
				$aux = $cnx->consulta(Conexiones::SELECT, $sql); 
				if(!empty($aux)){
					foreach ($aux as  $val) {
						$empleado_curso[$val['id_empleado']] = $val['cursos']; 
					}
				}
			}

			$familiar_default = [
				/* Campos bloque  Familiar*/
				'familiares'				=> "fam.familiares",
			];
			$query_familiar = array_intersect_key($familiar_default, $campos);
			$grupo_familiar = [];
			if (!empty($query_familiar)) {
				$sql = <<<SQL
				SELECT * FROM tv_grupo_familiar;
SQL;
				$aux = $cnx->consulta(Conexiones::SELECT, $sql);
				foreach ($aux as  $val) {
					$grupo_familiar[$val['id_empleado']] = $val['familiares'];
				}
			}

			$embargos_default = [
				/* Campos bloque Embargos*/
				'embargos'					=> "emb.embargos",
			];
			$query_embargos = array_intersect_key($embargos_default, $campos);
			$embargos = [];
			if (!empty($query_embargos)) {
				$sql = <<<SQL
				SELECT * FROM tv_empleado_embargos;
SQL;
				$aux = $cnx->consulta(Conexiones::SELECT, $sql);
				foreach ($aux as  $val) {
					$embargos[$val['id_empleado']] = $val['embargos'];
				}
			}

			$sindicatos_default = [
				/* Campos bloque Embargos*/
				'sindicatos_varios' => 'sindicato_varios.nombres AS sindicatos_varios',
			];
			$query_sindicatos = array_intersect_key($sindicatos_default, $campos);
			$sindicatos = [];
			if (!empty($query_sindicatos)) {
				$sql = <<<SQL
				SELECT * FROM tv_empleado_sindicatos;
SQL;
				$aux = $cnx->consulta(Conexiones::SELECT, $sql);
				foreach ($aux as  $val) {
					$sindicatos[$val['id_empleado']] = $val['nombres'];
				}
			}

			$anticorrupcion_default = [
				/* Campos bloque Anticorrupcion*/
				'obligado_prensentar_declaracion'	=> 'IF(id IS NOT NULL, "SI","NO") obligado_prensentar_declaracion',
				'fecha_designacion'					=> 'fecha_designacion',
				'fecha_publicacion_designacion'		=> 'fecha_publicacion_designacion',
				'fecha_aceptacion_renuncia'			=> 'fecha_aceptacion_renuncia',
				'tipo_presentacion'					=> 'tipo_presentacion',
				'fecha_presentacion'				=> 'fecha_presentacion',
				'periodo'							=> 'periodo',
				'nro_transaccion'					=> 'nro_transaccion',
			];
			$query_anticorrupcion = array_intersect_key($anticorrupcion_default, $campos);
			$anticorrupcion = [];
			if (!empty($query_anticorrupcion)) {
				$campos_anti = implode(',',$query_anticorrupcion);
				$sql = <<<SQL
				SELECT id_empleado, {$campos_anti} FROM tv_empleado_anticorrupcion;
SQL;
				$aux = $cnx->consulta(Conexiones::SELECT, $sql);
				foreach ($aux as  $val) {
					if(!isset($anticorrupcion[$val['id_empleado']]))
						$anticorrupcion[$val['id_empleado']] = $val;
				}
			}
			$designacion_transitoria_default = [
				/* Campos Designacion Transitoria*/
				'fecha_desde'					=> 'fecha_desde',
				'fecha_hasta'					=> 'fecha_hasta',
				'tipo'							=> 'tipo'
			];
			$query_designacion_transitoria = array_intersect_key($designacion_transitoria_default, $campos);
			$designacion_transitoria = [];
			if (!empty($query_designacion_transitoria)) {
				$campos_designacion = implode(',',$query_designacion_transitoria);
				$sql = <<<SQL
				SELECT id_empleado, {$campos_designacion} FROM tv_designacion_transitoria;
SQL;
				$aux = $cnx->consulta(Conexiones::SELECT, $sql);
				foreach ($aux as  $val) {
					if(!isset($designacion_transitoria[$val['id_empleado']]))
						$designacion_transitoria[$val['id_empleado']] = $val;
				}
			}
			if (in_array('actividades', $filtro_joins)) {
				$perfil_actividades = [];
				$sql_perfiles_puestos_act = <<<SQL
				SELECT id_perfil, actividades FROM tv_empleado_actividades;
SQL;
				$aux = $cnx->consulta(Conexiones::SELECT, $sql_perfiles_puestos_act);
				foreach ($aux as  $val) {
					$perfil_actividades[$val['id_perfil']] = $val;
				}
			}

			if (in_array('resultados_finales', $filtro_joins)) {
				$perfil_resultado_parc_final = [];
				$sql_perfiles_puestos_parc = <<<SQL
				SELECT id_perfil, resultados FROM tv_empleado_resultados;
SQL;
				$aux = $cnx->consulta(Conexiones::SELECT, $sql_perfiles_puestos_parc);
				foreach ($aux as  $val) {
					$perfil_resultado_parc_final[$val['id_perfil']] = $val;
				}
			}
			
			$seguros_vida = [];
			if (in_array('seguro_vida', $filtro_joins)) {
				$sql_seguros = <<<SQL
				SELECT id_empleado, seguro_vida FROM tv_empleado_seguros_vida;
SQL;
				$aux = $cnx->consulta(Conexiones::SELECT, $sql_seguros);
				foreach ($aux as  $val) {
					$seguros_vida[$val['id_empleado']] = $val;
				}
			}

			if (in_array('telefono', $filtro_joins)) {
				$persona_telefono = [];
				$sql_telefonos = <<<SQL
				SELECT id_persona, telefonos FROM tv_persona_telefonos;
SQL;
				$aux = $cnx->consulta(Conexiones::SELECT, $sql_telefonos);
				foreach ($aux as  $val) {
					$persona_telefono[$val['id_persona']] = $val;
				}
			}
			if (in_array('fecha_ingreso_mtr', $filtro_joins)) {
				$emp_ant_mtr	= [];
				$sql_ant_mtr =<<<SQL
		SELECT id_empleado, MIN(fecha_desde) AS fecha_desde FROM empleado_dependencia WHERE borrado = 0 GROUP BY id_empleado; 
SQL;
				$aux = $cnx->consulta(Conexiones::SELECT, $sql_ant_mtr);
				foreach ($aux as  $val) {
					$emp_ant_mtr[$val['id_empleado']] = $val;
				}
			}

			$experiencia_laboral = [];
			if (in_array('antiguedad_otros_organismos', $campos)) {
				$sql =<<<SQL
				SELECT id_persona,experiencia FROM tv_persona_experiencia_laboral; 
SQL;
				$aux = $cnx->consulta(Conexiones::SELECT, $sql);
				foreach ($aux as  $val) {
					$experiencia_laboral[$val['id_persona']] = $val['experiencia'];
				}
			}

		##########################################################################################	
        if (!empty($resultado) && is_array($resultado)) {
			$nivel_educativo	= \App\Modelo\NivelEducativo::getNivelEducativo();
			$o_sociales			= \App\Modelo\Empleado::getObraSociales();
			$niveles = [];
            foreach ($resultado as &$value) {
				if(isset($value['provincia'])){
	                $prov = \FMT\Ubicaciones::get_region($value['provincia']);
                   	$value['provincia'] = ($prov) ? $prov->nombre : 'S/D';
                }
				if(isset($value['provincia_edificio'])){
	                $prov = json_decode(json_encode(\FMT\Ubicaciones::get_region($value['provincia_edificio'])));
                   	$value['provincia_edificio'] = ($prov) ? $prov->nombre : 'S/D';
                }
				if(isset($value['localidad'])){
	                $loca = json_decode(json_encode(\FMT\Ubicaciones::get_localidad($value['localidad'])));
                   	$value['localidad'] = ($loca) ? $loca->nombre : 'S/D';
                }
				if(isset($value['localidad_edificio'])){
	                $loca = json_decode(json_encode(\FMT\Ubicaciones::get_localidad($value['localidad_edificio'])));
                   	$value['localidad_edificio'] = ($loca) ? $loca->nombre : 'S/D';
                }
				if(isset($value['tipo_documento'])){
                    $value['tipo_documento'] = \FMT\Helper\Arr::path(\App\Modelo\Persona::getParam('TIPO_DOCUMENTO'),"{$value['tipo_documento']}.nombre",'S/D');
                }
                if(isset($value['estado_civil'])){
                    $value['estado_civil'] =  \FMT\Helper\Arr::path(\App\Modelo\Persona::getParam('ESTADO_CIVIL'), "{$value['estado_civil']}.nombre",'S/D');
                }
                if(isset($value['genero'])){
                    $value['genero'] =  \FMT\Helper\Arr::path(\App\Modelo\Persona::getParam('GENERO'),"{$value['genero']}.nombre",'S/D');
                }
                if(isset($value['nivel_destreza'])){          
                    $value['nivel_destreza'] = \FMT\Helper\Arr::path(\App\Modelo\Perfil::getParam('NIVELES_DESTREZA'),"{$value['nivel_destreza']}.nombre",'S/D');
                }
                if(isset($value['nivel_complejidad'])){
                    $value['nivel_complejidad'] =  \FMT\Helper\Arr::path(\App\Modelo\Perfil::getParam('NIVELES_COMPLEJIDAD'),"{$value['nivel_complejidad']}.nombre",'S/D');
				}
				if(isset($value['evaluacion_formulario'])){
                    $value['evaluacion_formulario'] =  \FMT\Helper\Arr::path(\App\Modelo\Evaluacion::getParam('formularios'),"{$value['evaluacion_formulario']}.nombre",'S/D');
				}
				if(isset($value['evaluacion_resultado'])){
                    $value['evaluacion_resultado'] =  \FMT\Helper\Arr::path(\App\Modelo\Evaluacion::getParam('resultados'),"{$value['evaluacion_resultado']}.nombre",'S/D');
				}
                if(isset($value['puesto_supervisa'])){          
                    $value['puesto_supervisa'] =   \FMT\Helper\Arr::path(\App\Modelo\Perfil::getParam('NIVELES_PUESTO_SUPERVISA'),"{$value['puesto_supervisa']}.nombre",'S/D');
				}

				//Formacion/Titulo.
				if ($query_formacion) {
					foreach ($query_formacion as $key => $val) {
						$value[$key] = (isset($persona_titulo[$value['id_persona']][$key])) ? $persona_titulo[$value['id_persona']][$key] : '';
					}
					if (isset($value['nivel_educativo'])) {
							$value['nivel_educativo'] =  \FMT\Helper\Arr::path($nivel_educativo, "{$value['nivel_educativo']}.nombre",'S/D');
					}
					if (isset($value['estado_titulo'])) {
							$value['estado_titulo'] = ($value['estado_titulo'] == 0) ? 1 : $value['estado_titulo'];
							$value['estado_titulo'] =  \FMT\Helper\Arr::get(\App\Modelo\PersonaTitulo::getParam('ESTADO_TITULO')[$value['estado_titulo']], 'nombre', 'S/D');
					}
					if (isset($persona_titulo[$value['id_persona']]['titulos_adicionales'])){
						$titulos_adicionales = $persona_titulo[$value['id_persona']]['titulos_adicionales'];
						foreach($titulos_adicionales as $key => $adicional){
							if (isset($adicional['nivel_educativo'])) {
								$adicional['nivel_educativo'] =  \FMT\Helper\Arr::path($nivel_educativo, "{$adicional['nivel_educativo']}.nombre",'S/D');
							}
							if (isset($adicional['estado_titulo'])) {
								$adicional['estado_titulo'] = ($adicional['estado_titulo'] == 0) ? 1 : $adicional['estado_titulo'];
								$adicional['estado_titulo'] =  \FMT\Helper\Arr::get(\App\Modelo\PersonaTitulo::getParam('ESTADO_TITULO')[$adicional['estado_titulo']], 'nombre', 'S/D');
							}
							$titulos_adicionales[$key] = '["' . implode('", "',$adicional) . '"]'; 		
						}
						$value['titulos_adicionales'] = implode(', ', array_values($titulos_adicionales));
					}
				}
				if(array_key_exists('otros_estudios', $campos)){
					$value['otros_estudios'] 		= \FMT\Helper\Arr::path($persona_otros_es_con, PersonaOtroConocimiento::ESTUDIO.".{$value['id_persona']}", '');
				}
				if(array_key_exists('otros_conocimientos', $campos)){
					$value['otros_conocimientos'] 	= \FMT\Helper\Arr::path($persona_otros_es_con, PersonaOtroConocimiento::CONOCIMIENTO.".{$value['id_persona']}", '');
				}
				if (!empty($empleado_curso)) {
					$value['cursos']	=\FMT\Helper\Arr::path($empleado_curso, "{$value['id']}", '');
				}
				if($query_familiar) {
					$value['familiares']	= \FMT\Helper\Arr::get($grupo_familiar, "{$value['id']}", '');
				}
				if ($query_embargos) {
					$value['embargos']	= \FMT\Helper\Arr::get($embargos, "{$value['id']}", '');
				}
				if ($query_sindicatos) {
					$value['sindicatos_varios']	= \FMT\Helper\Arr::get($sindicatos, "{$value['id']}", '');
				}
			
				if ($query_anticorrupcion) {
					$keys_anticorrupcion = array_keys($query_anticorrupcion); 
					$value['obligado_prensentar_declaracion'] = 'NO';
					foreach ($keys_anticorrupcion as $key) {
						$def = ($key == 'obligado_prensentar_declaracion') ? 'NO' : '';
						$value["$key"] = \FMT\Helper\Arr::path($anticorrupcion, "{$value['id']}.{$key}",$def);
					}
				}
				if ($query_designacion_transitoria) {
					$keys_designacion_transitoria = array_keys($query_designacion_transitoria);
					foreach ($keys_designacion_transitoria as $key ) {
						$value["$key"] = \FMT\Helper\Arr::path($designacion_transitoria, "{$value['id']}.{$key}",'');
					}
				}
				if (isset($sql_telefonos)) {
					$value['telefono'] = \FMT\Helper\Arr::path($persona_telefono, "{$value['id_persona']}.telefonos");

				}
				if (isset($emp_ant_mtr)) {
					$value['fecha_ingreso_mtr'] =  \FMT\Helper\Arr::path($emp_ant_mtr, "{$value['id']}.fecha_desde");
				}
				if((array_key_exists('antiguedad_adm_publica',$campos)) && isset($value['fecha_ingreso_mtr']) && $value['id_persona']){
					$fecha_ingreso_mtr = \DateTime::createFromFormat('Y-m-d H:i:s', $value['fecha_ingreso_mtr'] . ' 0:00:00');
					$antiguedad_adm_publica = static::total_antiguedad_adm_publica($value['id_persona'],$fecha_ingreso_mtr);
					$value['antiguedad_adm_publica'] = $antiguedad_adm_publica['anios'] . ' años ' .  $antiguedad_adm_publica['meses'] . ' meses';
				}
				if(array_key_exists('antiguedad_adm_publica',$campos)){
					$aux	= json_decode($value['antiguedad_adm_publica'], false);
					if(!empty($aux) && $aux->anio == null){
						$value['antiguedad_adm_publica']	= 'S/D';
					}
					if(empty($value['antiguedad_adm_publica'])){
						$value['antiguedad_adm_publica']	= 'S/D';
					}
				}
				if(array_key_exists('antiguedad_otros_organismos',$campos)){
					$antiguedad_otros_organismos =  PersonaExperienciaLaboral::total_antiguedad($value['id_persona']);
					$value['antiguedad_otros_organismos'] = $antiguedad_otros_organismos['anios'] . ' años ' .  $antiguedad_otros_organismos['meses'] . ' meses';
					$value['experiencia_laboral'] = \FMT\Helper\Arr::path($experiencia_laboral, $value['id_persona'], '');
				}
				if(isset($value['estado'])){
                    $value['estado'] =  \FMT\Helper\Arr::get(\App\Modelo\Empleado::getParam('TIPO_ESTADOS_EMPLEADOS')[$value['estado']],'nombre','S/D');
                }                
                if(isset($value['nivel_organigrama'])){
                    $value['nivel_organigrama'] =  \App\Modelo\Dependencia::getParam('NIVEL_ORGANIGRAMA')[$value['nivel_organigrama']]['nombre'];
                }
                if(isset($value['turno'])){
                    $value['turno'] =  \FMT\Helper\Arr::path(\App\Modelo\Empleado::getParam('TURNO'),"{$value['turno']}.nombre", 'S/D');
                }
                if(isset($value['motivo_baja'])){
                    $value['motivo_baja'] =  \FMT\Helper\Arr::path(\App\Modelo\Empleado::getParam('MOTIVO_BAJA'),"{$value['motivo_baja']}.nombre", 'S/D');
                }
                if(isset($value['destino_comision'])){
                    $value['destino_comision'] =  \FMT\Helper\Arr::path($comisiones,"{$value['destino_comision']}.nombre", 'S/D');
                }
                if(isset($value['origen_comision'])){
                    $value['origen_comision'] =  \FMT\Helper\Arr::path($comisiones,"{$value['origen_comision']}.nombre", 'S/D');
				}
				if (isset($value['actividades'])) {
					$value['actividades'] = \FMT\Helper\Arr::path($perfil_actividades, "{$value['id_perfil']}.actividades", '');
				}
				if (isset($value['resultados_finales'])) {
					$value['resultados_finales'] = \FMT\Helper\Arr::path($perfil_resultado_parc_final, "{$value['id_perfil']}.resultados", '');
				}
				if (in_array('es', $filtro_joins)) {
						$value['obra_social'] = \FMT\Helper\Arr::path($o_sociales, "{$value['obra_social']}.nombre");
				}					
				if (isset($value['seguro_vida'])) {
					$value['seguro_vida'] = \FMT\Helper\Arr::path($seguros_vida, "{$value['id']}.seguro_vida");
				}

				if(isset($value['opc_art_14'])){
					$excepcion_FORMACION	= (!empty($excepcion_articulo = json_decode($value['opc_art_14'], 1))) 
											? array_column($excepcion_articulo ,'excepcion') : [];
					$select_FORMACION 		= '';

					foreach(self::$EXCEPCION_ART_14	as $__key => &$__value){
						if (is_array($excepcion_FORMACION)) {
							if(in_array($__key, $excepcion_FORMACION)){
								$select_FORMACION .= ($select_FORMACION)  ? ' | '.$__value['nombre'] : $__value['nombre'];
							}
						}
					}
					$value['opc_art_14'] =  $select_FORMACION;
				}
                if(isset($value['dependencia'])){
					if(!isset($niveles[$value['dependencia']])) {
						$niveles[$value['dependencia']] = Dependencia::obtener_cadena_dependencias($value['dependencia'], $value['fecha_baja']);
					}
					$value['ministro'] = $value['secretaria'] = 
                	$value['subsecretaria'] = $value['direccion_general'] = 
                	$value['direccion_simple'] = $value['coordinacion'] = 
                	$value['unidad_o_area'] = '--';  
                	
                	foreach ($niveles[$value['dependencia']] as $nivel) {
                		switch ($nivel['nivel']) {
                			case Dependencia::MINISTRO:
                				$value['ministro'] = $nivel['ubicacion'];
                				break;
                			case Dependencia::SECRETARIA:
                				$value['secretaria'] = $nivel['ubicacion'];
                				break;
                			case Dependencia::SUBSECRETARIA:
                				$value['subsecretaria']= $nivel['ubicacion'];
                				break;
                			case Dependencia::DIRECCION_GENERAL:
                				$value['direccion_general'] = $nivel['ubicacion'];
                				break;
                			case Dependencia::DIRECCION_SIMPLE:
                				$value['direccion_simple'] = $nivel['ubicacion'];
                				break;
                			case Dependencia::COORDINACION:
                				$value['coordinacion'] = $nivel['ubicacion'];
                				break;
                			case Dependencia::UNIDAD_O_AREA:
                				$value['unidad_o_area'] = $nivel['ubicacion'];
                				break;
                			default:
                				# code...
                				break;
                		}
                	}
				}
				// Filtrar los campos resultantes segun permisos especificados
				$value	= array_intersect_key($value,$campos);
				unset($value['id'], $value['id_persona'], $value['id_perfil'], $value['dependencia']);
            } // fin - foreach
        }	else	{
            $resultado = [];
		}
        return $resultado;
		}
	}

	static public function getObraSociales(){
		$aux = [];
		$campos	= "id,codigo,nombre,borrado";
		
		$sql	= <<<SQL
			SELECT {$campos}
			FROM obras_sociales
			ORDER BY id ASC
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::SELECT, $sql);
		if($res){
			foreach ((array)$res as $value) 
			{ 
				$aux[$value['id']] = $value; 
			}
			return $aux;
		}else{
			return [];
		}
	}

	static public function getSegurosVida(){
		$aux = [];
		$campos	= implode(',', [
			'id',
			'nombre',
			'borrado'
		]);
		$sql	= <<<SQL
			SELECT {$campos}
			FROM seguro_vida
			ORDER BY id ASC
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::SELECT, $sql);
		if($res){
			foreach ((array)$res as $value) 
			{ 
				$aux[$value['id']] = $value; 
			}
			return $aux;
		}else{
			return [];
		}
	}

	public function alta_empleado_salud(){
		if(!empty($this->empleado_salud->id_obra_social)) {
			$cnx	= new Conexiones();
			$campos	= [
				'id_empleado',
				'id_obra_social',
				'fecha_desde',
				'fecha_hasta'
			];
			$sql_params	= [
				':id_empleado'		=> $this->id,
				':id_obra_social'	=> $this->empleado_salud->id_obra_social,
				':fecha_desde'		=> date('Y-m-d'),	
				':fecha_hasta'		=> null,
			];

			$sql	= 'INSERT INTO empleado_salud('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
			$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);

			if($res !== false){
				$this->empleado_salud->id	= $res;
				$datos				= (array)$this;
				$datos['modelo']	= 'Empleado';
				$flag = true;
			} else {
				$this->errores['empleado_salud']	= $cnx->errorInfo[2];
			}
			Logger::event('alta_empleado_salud', $datos);
		}
	}

	public function baja_empleado_salud(){	
		if(empty($this->empleado_salud->id_obra_social)) {
			return false;
		}	
			$cnx	= new Conexiones();
			$sql_params	= [
				':id'				=> $this->empleado_salud->id,
				':fecha_hasta'		=> date('Y-m-d'),
			];
			$sql	= 'UPDATE empleado_salud SET fecha_hasta =:fecha_hasta WHERE id = :id';
			$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);

			if($res !== false){
				$datos = (array) $this;
				$datos['modelo'] = 'Empleado';
				Logger::event('modificacion_empleado_salud', $datos);
				return true;
			} else {
				$this->errores['empleados']	= $cnx->errorInfo[2];
				return false;
			}
		
	}

	public function alta_empleado_seguro($id_seguro){
		if(!empty($id_seguro)) {
			$cnx	= new Conexiones();
			$campos	= [
				'id_empleado',
				'id_seguro',
				'fecha_desde',
				'fecha_hasta'
			];
			$sql_params	= [
				':id_empleado'		=> $this->id,
				':id_seguro'		=> $id_seguro,
				':fecha_desde'		=> date('Y-m-d'),	
				':fecha_hasta'		=> null,
			];	


	
			$sql	= 'INSERT INTO empleado_seguros('.implode(',', $campos).') VALUES (:'.implode(',:', $campos).')';
			$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params); 

			if($res !== false){
				$aux = new \StdClass();
				$aux->id = $res;
				$aux->seguros = $id_seguro;
				$this->empleado_seguro[]= $aux;
				$datos				= (array)$this;
				$datos['modelo']	= 'Empleado';
			} else {
				$datos				= (array)$this;
				$datos['modelo']	= 'Empleado';
				$this->errores['empleado_seguro']	= $cnx->errorInfo[2];
			}

			Logger::event('alta_empleado_seguro', $datos);
		}
	}


	public function baja_empleado_seguro($id_registro){
		if(empty($id_registro)) {
			return false;
		}
		$sql	= <<<SQL
			UPDATE empleado_seguros SET fecha_hasta = :fecha_hasta WHERE id = :id
SQL;
		$mbd = new Conexiones();
		$res	= $mbd->consulta(Conexiones::UPDATE, $sql, [
			':id'			=> $id_registro,
			':fecha_hasta'	=> date('Y-m-d'),
		]);

		$flag = false;
		if (!empty($res) && $res > 0) {
			$datos				= (array)$this;
			$datos['modelo']	= 'Empleado';
			if (is_numeric($res) && $res > 0) {
				$flag =true;
			} else {
				$datos['error_db'] = $mbd->errorInfo;
			}
			Logger::event('baja', $datos);
		}
		return $flag;
	}
	


/**
 * Busca segun "cuit" o "nombre_apellido" un agente. Query optimizada para CUIT.
 * 
 * Si $params['limit_1'] es true, devuelve un objeto en vez de array.
 * 
 * array 	$params
 * @return array|object
*/
	static public function ajaxBuscarAgentes(Array $params=[]){
		$paramsDefault	= [
			'cuit'				=> null,
			'nombre_apellido'	=> null,
			'estado' 			=> null,
			'agente_activo'		=> null,
			'limit_1'			=> null,
		];
		$params	= array_merge($paramsDefault, $params);
		if(empty($params['cuit']) && empty($params['nombre_apellido'])){
			return [];
		}

		$where		= '';
		$sql_params = [
			':estado_activo'	=> static::EMPLEADO_ACTIVO,
		];
		$sql	= <<<SQL
			SELECT
				e.id	AS id_empleado,
				e.cuit	AS cuit,
				e.estado AS estado,
				CONCAT(p.nombre, ' ', p.apellido)	AS nombre,
				IF((e.estado = :estado_activo), 1, 0) AS agente_activo
SQL;

		if(isset($params['agente_activo']) && $params['agente_activo'] != null){
			if(empty($params['agente_activo'])) {
				$where[]	= 'emp.estado != :estado_activo';
			} else {
				$where[]	= 'emp.estado = :estado_activo';
			}
		}

		if(!empty($params['filtros']['estado'])) {
			switch ($params['filtros']['estado']) {
				case self::EMPLEADO_ACTIVO:
					$where[]	= "emp.estado =".self::EMPLEADO_ACTIVO;
					break;
				case self::EMPLEADO_INACTIVO:
					$where[]	= "emp.estado =".self::EMPLEADO_INACTIVO;
					break;
				case self::EMPLEADO_RECHAZADO:
					$where[]	= "emp.estado =".self::EMPLEADO_RECHAZADO;
					break;
				case self::EMPLEADO_POSTULANTE:
					$where[]	= "emp.estado =".self::EMPLEADO_POSTULANTE;
					break;
				default:
					$where[]	= "emp.estado =".self::EMPLEADO_ACTIVO;
					break;
			}
		}

		if(!empty($params['nombre_apellido']) && empty($params['cuit'])){
			$sql_params[':nombre_apellido']	= '%'.implode('%', explode(' ', $params['nombre_apellido'])).'%';
			$sql	.= <<<SQL
				FROM personas AS p
					INNER JOIN empleados AS e ON (p.id = e.id_persona AND e.borrado = 0 AND p.borrado = 0)
				WHERE
					e.borrado = 0 AND p.nombre LIKE :nombre_apellido OR p.apellido LIKE :nombre_apellido {$where}
SQL;
		} else {
			$sql_params[':cuit']	= (string)('%%'.$params['cuit'].'%');
			$sql	.= <<<SQL
				FROM empleados AS e
					INNER JOIN personas AS p ON (e.id_persona = p.id AND e.borrado = 0 AND p.borrado = 0)
				WHERE
					e.borrado = 0 AND e.cuit LIKE :cuit {$where}
SQL;
		}
		if(empty($params['limit_1'])){
			$sql	.= ' LIMIT 4 ';
		} else {
			$sql	.= ' LIMIT 1 ';
		}

		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);

		if(!empty($res[0])){
			foreach ($res as &$r) {
				if(isset($r['agente_activo'])){
					$r['agente_activo']	= (bool)$r['agente_activo'];
				}
			}
		}
		if(!empty($params['limit_1'])){
			!empty($res[0]) ? (object)$res[0] : (object)[
				'id_empleado'			=> null,
				'cuit'					=> null,
				'estado'				=> null,
				'nombre'				=> null,
				'agente_activo'			=> null,
			];
		}

		return !empty($res) ? $res : [];
	}

	public static function obtener_mail($email) {
		$cuit = null;

		$sql = <<<SQL
			SELECT cuit FROM empleados WHERE email = :email AND estado = :estado AND borrado =0;
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::SELECT, $sql, [':email' => $email,':estado' => self::EMPLEADO_ACTIVO]);
		if(!empty($res)){
			$cuit =$res[0]['cuit'];
		}
		$obj = static::obtener($cuit);
		return $obj;
	}

	public static function dotacion_total(){
		$cnx	= new Conexiones();
		$sql = <<<SQL
			SELECT @total:= count(*) FROM empleados WHERE estado = :estado;
SQL;
		$cnx->consulta(Conexiones::SELECT, $sql,[':estado' => self::EMPLEADO_ACTIVO]);	
		$sql = <<<SQL
			SELECT @total total,count(*) AS cantidad, p.genero, count(*) * 100/@total porcentaje
			FROM empleados AS e
			LEFT JOIN personas as p ON  (e.id_persona = p.id)
			WHERE e.estado =:estado
			AND e.borrado = 0
			GROUP BY e.estado,p.genero;
SQL;
		$res	= $cnx->consulta(Conexiones::SELECT, $sql,[':estado' => self::EMPLEADO_ACTIVO]);
		return $res;
	}

	public static function personal_por_unidad($dependencias){
		$return = [];
		$cnx	= new Conexiones();
		$sql = <<<SQL
		SELECT  @total,cc.id_dependencia,d.nombre,sum(cc.cant) cant,concat(sum(cc.cant)*100 / @total)  porcentaje FROM (SELECT 1 uni,id_dependencia,'' nombre,count(*) cant FROM empleados e INNER JOIN empleado_dependencia ed ON e.id = ed.id_empleado AND ed.fecha_hasta IS NULL AND ed.borrado = 0 AND e.estado = :estado AND e.borrado=0 			WHERE e.estado = :estado AND e.borrado = 0 AND (id_dependencia IS NULL OR id_dependencia IN(:hijos)) GROUP BY id_dependencia
		) cc 
		INNER JOIN dependencias d ON d.id = cc.id_dependencia
		GROUP BY uni
SQL;
		foreach ($dependencias as $id_dep) {
				$hijos = [$id_dep];
			if($id_dep == 1) { //id de la unidad de ministros
				$auditoria = 120;
				$uni_coord = 5;
				$programas = 134;
				$hijos[] = $auditoria;
				$hijos[] = $uni_coord;
				$hijos[] = $programas;
				foreach ([$auditoria,$uni_coord,$programas] as $id) {
					$aux = Dependencia::obtener_cadena_dependencias_hijas($id);
					foreach ($aux as $value) {
						if($value['borrado'] == 0) {
							$hijos[] = $value['id'];
						}
					}
				}
			} else {
				$aux = Dependencia::obtener_cadena_dependencias_hijas($id_dep);
				foreach ($aux as $value) {
					if($value['borrado'] == 0) {
						$hijos[] = $value['id'];
					}
				}				
			}
			$res	= $cnx->consulta(Conexiones::SELECT, $sql,[':estado' => self::EMPLEADO_ACTIVO,':hijos' => $hijos]); 
			$return[] = $res[0];
		}
			$sql = <<<SQL
			SELECT @total, '999' id, 'Datos en Proceso de Recolección (*)' nombre, count(*) AS cant,count(*) * 100/@total porcentaje FROM empleados e LEFT JOIN empleado_dependencia ed 
			ON e.id = ed.id_empleado 
			WHERE e.estado = :estado AND e.borrado = 0 AND ed.id IS NULL;		
SQL;
			$res	= $cnx->consulta(Conexiones::SELECT, $sql,[':estado' => self::EMPLEADO_ACTIVO]);
			$return[] = $res[0];
		return $return;
	}


	public static function vinculacion(){
		$cnx	= new Conexiones();
		$sql = <<<SQL
			SELECT @total:= count(*) FROM empleados WHERE estado = :estado;
SQL;
		$cnx->consulta(Conexiones::SELECT, $sql,[':estado' => self::EMPLEADO_ACTIVO]);

		$sql = <<<SQL
		SELECT @total total,count(*) AS cantidad, count(*) * 100/@total porcentaje, ee.id_modalidad_vinculacion, ee.id_situacion_revista
		FROM empleados AS e
		LEFT JOIN personas as p ON  (e.id_persona = p.id)
		LEFT JOIN empleado_escalafon as ee ON  (e.id = ee.id_empleado AND ee.fecha_fin is null )
		WHERE e.estado = :estado
		AND e.borrado = 0 
		GROUP BY ee.id_modalidad_vinculacion,ee.id_situacion_revista;
SQL;
		$res	= $cnx->consulta(Conexiones::SELECT, $sql,[':estado' => self::EMPLEADO_ACTIVO]);
		return $res;
	}

	public static function formacion(){
		$return = [];
		$cnx	= new Conexiones();
	##########################################################################################
	#	Se buscan los primeros titulos que esten declarados como principal y se 			##
	#   excluyen para la siguiente busqueda													##
	##########################################################################################																				
		$sql= <<<SQL
		SELECT e.id, pt.id_tipo_titulo, pt.id_estado_titulo,p.genero
 		FROM empleados AS e
 		LEFT JOIN personas as p ON  (e.id_persona = p.id)
 		LEFT JOIN persona_titulo as pt ON (pt.id_persona = p.id)
 		WHERE e.estado = :estado
 		AND e.borrado = 0
 		AND pt.principal = 1
 		AND pt.id_estado_titulo = :completo
SQL;
 		$res= $cnx->consulta(Conexiones::SELECT, $sql,[':estado' => self::EMPLEADO_ACTIVO,':completo' => PersonaTitulo::COMPLETO]);

 	#############################################################################################
	#SE GUARDAN IDS DEL RESULTADO Y SE AGREGAN A $exclu PARA EXCLUIRLOS EN LA PROXIMA BUSQUEDA ##
	#############################################################################################

		foreach ($res as $key => $value) {
			$return[$value['id_tipo_titulo']][$value['id_estado_titulo']][$value['id']] = ['id'=>$value['id'],'genero' => $value['genero']];
			$exclu[$value['id']] = $value['id'];
		}

 	#################################################################################################
	## SE DEFINEN VARIABLES DE TIPOS DE TITULOS DE LA TABLA NIVEL_EDUCATIVO PARA ITERAR EN LA QUERY ##
	#################################################################################################
	
	$postgrado = 6;
	$universitario = 5;
	$terciario = 4;
	$secundario = 3;
	$primario = 2;
	$sindefinir = 1;
	$titulos[] = $postgrado;
	$titulos[] = $universitario;
	$titulos[] = $terciario;
	$titulos[] = $secundario;
	$titulos[] = $primario;
	foreach ($titulos as $formacion){
 		$sql= <<<SQL
 		SELECT cc.* FROM(
    	SELECT e.id,pt.id_tipo_titulo,pt.id_estado_titulo,p.genero 
 		FROM empleados AS e
 		LEFT JOIN personas as p ON  (e.id_persona = p.id)
 		LEFT JOIN persona_titulo as pt ON (pt.id_persona = p.id)
 		WHERE e.id NOT IN(:esclu)  
        AND e.estado = :estado
 		AND e.borrado = 0
 		AND pt.id_tipo_titulo = :formacion
 		AND (pt.principal != 1 OR ISNULL(pt.principal))
 		AND pt.id_estado_titulo IN (:completo,:incompleto)
        ORDER BY id_estado_titulo asc
        ) cc
        GROUP BY cc.id
SQL;
 		$res= $cnx->consulta(Conexiones::SELECT, $sql,[':estado' => self::EMPLEADO_ACTIVO,':completo' => PersonaTitulo::COMPLETO, ':incompleto' => PersonaTitulo::INCOMPLETO, ':esclu' => $exclu,':formacion' => $formacion]);
 		foreach ($res as $key => $value) {
			$return[$value['id_tipo_titulo']][$value['id_estado_titulo']][$value['id']]= ['id'=>$value['id'],'genero' => $value['genero']];
			$exclu[$value['id']] = $value['id'];
 		}
 	}

 	 ##################################################################################
	# SE REALIZA BUSQUEDA DE EMPLEADOS CON TIPO_TITULO S/D O ESTADO_TITULO MENOR A 2 ##
	###################################################################################

 	$sql = <<<SQL
 	    SELECT e.id,pt.id_tipo_titulo,pt.id_estado_titulo,p.genero
 		FROM empleados AS e
 		LEFT JOIN personas as p ON  (e.id_persona = p.id)
 		LEFT JOIN persona_titulo as pt ON (pt.id_persona = p.id)
 		WHERE (id_tipo_titulo = :sd OR pt.id_estado_titulo < :completo)
        AND e.estado = :estado 
		AND e.borrado = 0
		ORDER BY id_estado_titulo asc
SQL;
	$res= $cnx->consulta(Conexiones::SELECT, $sql,[':estado' => self::EMPLEADO_ACTIVO,':completo' => PersonaTitulo::COMPLETO,':sd' => NivelEducativo::SD]);
	foreach ($res as $key => $value) {

			if(!in_array($value['id_estado_titulo'], ['0','1','']) && ($value['id_tipo_titulo'] != $sindefinir)){
				$return[$value['id_tipo_titulo']][$value['id_estado_titulo']][$value['id']] = ['id'=>$value['id'],'genero' => $value['genero']];
			} else {
				$return['reco'][$value['id']] = ['id'=>$value['id'],'genero' => $value['genero']];
			}			
			$exclu[$value['id']] = $value['id']; 
	}

 	###################################################################################
	# 			SE REALIZA BUSQUEDA DE EMPLEADOS SIN ID EN TABLA PERSONA_TITULO 	 ##
	###################################################################################

	$sql = <<<SQL
    SELECT e.id,pt.id_tipo_titulo,pt.id_estado_titulo,p.genero
 		FROM empleados AS e
 		LEFT JOIN personas as p ON  (e.id_persona = p.id)
 		LEFT JOIN persona_titulo as pt ON (pt.id_persona = p.id)
 		WHERE pt.id IS NULL
        AND e.estado = :estado 
		AND e.borrado = 0
SQL;
	$res= $cnx->consulta(Conexiones::SELECT, $sql,[':estado' => self::EMPLEADO_ACTIVO]);
	foreach ($res as $key => $value) {
			if(!in_array($value['id_estado_titulo'], ['0','1','']) && ($value['id_tipo_titulo'] != $sindefinir)){
				$return[$value['id_tipo_titulo']][$value['id_estado_titulo']][$value['id']] = ['id'=>$value['id'],'genero' => $value['genero']];
			} else {
				$return['reco'][$value['id']] = ['id'=>$value['id'],'genero' => $value['genero']];
			}
			$exclu[$value['id']] = $value['id'];
	}
	krsort($return);
	return $return;
}


	public static function situacion_genero(){
		$return = [];
		$cnx	= new Conexiones();
		$sql = <<<SQL
		SELECT @total:= count(*) FROM empleados e
		LEFT JOIN personas as p ON  (e.id_persona = p.id AND p.genero = :femenina) 
		WHERE e.estado = :estado;
SQL;
		$cnx->consulta(Conexiones::SELECT, $sql,[':estado' => self::EMPLEADO_ACTIVO, ':femenina' => Persona::FEMENINA]);
		$sql = <<<SQL
		SELECT @total total,count(*) AS cantidad, count(*) * 100/@total porcentaje
		FROM empleados AS e
		LEFT JOIN personas as p ON  (e.id_persona = p.id)
		LEFT JOIN empleado_escalafon as ee ON  (e.id = ee.id_empleado)
		WHERE e.estado = :estado
		AND e.borrado = 0
		AND p.genero = :femenina
		AND ee.id_modalidad_vinculacion = :autoridad_superior 
		And ee.id_situacion_revista = :autoridad_superior_sr
SQL;
		$res= $cnx->consulta(Conexiones::SELECT, $sql,[
			':estado' => self::EMPLEADO_ACTIVO,
			':femenina' => Persona::FEMENINA,
			':autoridad_superior' => Contrato::AUTORIDAD_SUPERIOR,
			':autoridad_superior_sr' => Contrato::AUTORIDAD_SUPERIOR_SR]);

		$return[] = $res[0]; 
$sql = <<<SQL
			SELECT @total:= count(*) FROM empleados WHERE estado = :estado;
SQL;
		$cnx->consulta(Conexiones::SELECT, $sql,[':estado' => self::EMPLEADO_ACTIVO]);

		$sql = <<<SQL
		SELECT @total total,count(*) AS cantidad, count(*) * 100/@total porcentaje, ee.id_modalidad_vinculacion, ee.id_situacion_revista
		FROM empleados AS e
		LEFT JOIN personas as p ON  (e.id_persona = p.id)
		LEFT JOIN empleado_escalafon as ee ON  (e.id = ee.id_empleado AND ee.fecha_fin is null )
		WHERE e.estado = :estado
		AND e.borrado = 0 
		AND p.genero = :femenina
		AND id_modalidad_vinculacion IN (:sinep,:otra) 
		AND id_situacion_revista IN (:designacion_transitoria_pp_fe,:pp_mtr_designacion_transitoria,:pp_designacion_transitoria,:pp_mtr_dtfe)
		GROUP BY ee.id_modalidad_vinculacion,ee.id_situacion_revista;
SQL;
		$res= $cnx->consulta(Conexiones::SELECT, $sql,[
			':estado'   => self::EMPLEADO_ACTIVO,
			':femenina' => Persona::FEMENINA,
			':sinep' 	=> Contrato::SINEP,
			':otra'		=> Contrato::OTRA,
			':designacion_transitoria_pp_fe' => Contrato::DESIGNACION_TRANSITORIA_PP_FE,
			':pp_mtr_designacion_transitoria'=> Contrato::PP_MTR_DESIGNACION_TRANSITORIA,
			':pp_designacion_transitoria' => Contrato::PP_DESIGNACION_TRANSITORIA,
			':pp_mtr_dtfe' => Contrato::PP_MTR_DTFE]);
		$return[] = $res[0];

		return $return;
	}

	/*
	* Da de baja todas las comisones anteriores a la actual
	*/
	protected function baja_comision_anterior() {
		if($this->en_comision->id) {
			$cnx =  new Conexiones();
			$sql = <<<SQL
				UPDATE empleado_comision SET fecha_fin = :fecha_fin WHERE id_empleado = :id_empleado AND id != :id;
SQL;
			$res	= $cnx->consulta(Conexiones::UPDATE, $sql, [
				':id_empleado'	=> $this->id,
				':fecha_fin'	=> date('Y-m-d'),
				':id'			=> $this->en_comision->id
			]);
			if ($res) {
				$datos = (array) $this;
				$datos['modelo'] = 'Empleado_comision';
				Logger::event('baja', $datos);
			}
		}
		return true;				
	}

	public function empleados_ultimos_cambios($id_tipo){	
		$cnx	= new Conexiones();
		$sql_params = [ ':id_empleado' => $this->id,
		 				':id_tipo'		=> $id_tipo,
		];
		$sql = <<<SQL
	    SELECT euc.*
	    FROM empleado_ultimos_cambios AS euc
	    LEFT JOIN empleados as e ON  (e.id = euc.id_empleado AND e.borrado = 0)
	    WHERE ISNULL(euc.fecha_hasta)
	    AND euc.id_tipo = :id_tipo 
	    AND e.id = :id_empleado
SQL;
	 	$return = [];
		$res= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);
		if (empty($res)) {
			$res=[];
		}else{
			$res = $res[0];
		}
		return $res;
	}

###########################################################################################################################
## OBTIENE DATOS PERSONALES DE LOS AGENTES QUE ESTAN EN EL INFORME DE DATOS GLOBALES EN DATOS EN PROCESO DE RECOLECCIÓN ###
###########################################################################################################################
	public static function datos_recoleccion_por_unidad($params=array(), $count = false){
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
		  e.id,
		  p.nombre,
		  p.apellido,
		  e.cuit
SQL;
		$from = <<<SQL
		FROM empleados e
		LEFT JOIN empleado_dependencia ed ON (e.id = ed.id_empleado)
		LEFT JOIN personas p ON (e.id_persona = p.id)
SQL;
		$order =
 		<<<SQL
			ORDER BY 
SQL;
		$condicion =
		<<<SQL
		WHERE e.estado = 1
		AND e.borrado = 0
		AND ed.id IS NULL
SQL;

    $counter_query	= "SELECT COUNT(e.id) AS total {$from}";

	$recordsTotal	=  $cnx->consulta(Conexiones::SELECT, $counter_query . $condicion, $sql_params )[0]['total'];

	if(!empty($params['search'])){
		$indice = 0;
		$search[]	= <<<SQL
		(e.cuit LIKE :search{$indice} OR
		 p.nombre LIKE :search{$indice} OR
		 p.apellido LIKE :search{$indice}  )
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
						? "  LIMIT  {$params['start']}, {$params['lenght']}"	:	' ';
	$recordsFiltered= $cnx->consulta(Conexiones::SELECT, $counter_query.$condicion,  $sql_params)[0]['total'];
	$order .= (($order =='') ? '' : ', ').'e.id , p.nombre ASC, p.apellido ASC';
	//$order = ' ORDER BY '.$order;

	$lista	= $cnx->consulta(Conexiones::SELECT,  $sql .$from.$condicion.$order.$limit,$sql_params);

		return [
			'recordsTotal'    => !empty($recordsTotal) ? $recordsTotal : 0,
			'recordsFiltered' => !empty($recordsFiltered) ? $recordsFiltered : 0,
			'data'            => $lista ? $lista : [],
		];
	}

	public static function datos_recoleccion_por_vinculacion($params=array(), $count = false){
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
		  e.id,
		  p.nombre,
		  p.apellido,
		  e.cuit
SQL;
		$from = <<<SQL
		FROM empleados e
		LEFT JOIN personas p ON (e.id_persona = p.id)
		LEFT JOIN empleado_escalafon AS ee ON (e.id = ee.id_empleado AND ee.fecha_fin IS NULL)
SQL;
		$order =
 		<<<SQL
 		ORDER BY 
SQL;
		$condicion =
		<<<SQL
		WHERE e.estado = 1
		AND e.borrado = 0
		AND ISNULL(ee.id_modalidad_vinculacion)
		AND ISNULL(ee.id_situacion_revista)
SQL;

    $counter_query	= "SELECT COUNT(e.id) AS total {$from}";

	$recordsTotal	=  $cnx->consulta(Conexiones::SELECT, $counter_query . $condicion, $sql_params )[0]['total'];

	if(!empty($params['search'])){
		$indice = 0;
		$search[]	= <<<SQL
		(e.cuit LIKE :search{$indice} OR
		 p.nombre LIKE :search{$indice} OR
		 p.apellido LIKE :search{$indice}  )
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
	$recordsFiltered= $cnx->consulta(Conexiones::SELECT, $counter_query.$condicion,  $sql_params)[0]['total'];
	$order .= (($order =='') ? '' : ', ').'e.id , p.nombre ASC, p.apellido ASC';
	//$order = ' ORDER BY '.$order;
	$lista	= $cnx->consulta(Conexiones::SELECT,  $sql .$from.$condicion.$order.$limit,$sql_params);

		return [
			'recordsTotal'    => !empty($recordsTotal) ? $recordsTotal : 0,
			'recordsFiltered' => !empty($recordsFiltered) ? $recordsFiltered : 0,
			'data'            => $lista ? $lista : [],
		];
	}

	public static function datos_recoleccion_por_formacion($params=array(), $count = false){
		$return = [];
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
		$sql = <<< SQL
		SELECT
		f.id, f.nombre, f.apellido, f.cuit
SQL;
		$from = <<<SQL
		FROM
		((SELECT e.id, p.nombre, p.apellido, e.cuit
		FROM empleados AS e
        LEFT JOIN personas AS p ON (e.id_persona = p.id)
        LEFT JOIN persona_titulo AS pt ON (pt.id_persona = p.id)
		WHERE (id_tipo_titulo = 1 OR pt.id_estado_titulo < 2)
        AND e.estado = 1
        AND e.borrado = 0)
		UNION
		(SELECT e.id, p.nombre, p.apellido, e.cuit
			FROM empleados AS e
			LEFT JOIN personas as p ON  (e.id_persona = p.id)
			LEFT JOIN persona_titulo as pt ON (pt.id_persona = p.id)
			WHERE pt.id IS NULL
			AND e.estado = 1
			AND e.borrado = 0)) AS f
SQL;
		$params	= array_merge($default_params, $params);
		$order =
 		<<<SQL
 			ORDER BY 
SQL;
 		$condicion ='';
//  		<<<SQL
//  		WHERE 
// SQL;

 //	$condicion = !empty($where) ? ' WHERE ' . \implode(' AND ',$where) : '';

    $counter_query	= "SELECT COUNT(f.id) AS total {$from}";
	$recordsTotal	=  $cnx->consulta(Conexiones::SELECT, $counter_query , $sql_params )[0]['total'];

	if(!empty($params['search'])){
		$indice = 0;
		$search[]	= <<<SQL
		(f.cuit LIKE :search{$indice} OR
		 f.nombre LIKE :search{$indice} OR
		 f.apellido LIKE :search{$indice}  )
SQL;
		$texto = $params['search'];
		$sql_params[":search{$indice}"]	= "%{$texto}%";

		$buscar =  implode(' AND ', $search);
		$condicion .= empty($condicion) ? " WHERE {$buscar}" : " AND {$buscar} ";
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
	$recordsFiltered= $cnx->consulta(Conexiones::SELECT, $counter_query.$condicion,  $sql_params)[0]['total'];
	$order .= (($order =='') ? '' : ', ').'f.id , f.nombre ASC, f.apellido ASC';
	//$order = ' ORDER BY '.$order;
	$lista	= $cnx->consulta(Conexiones::SELECT,  $sql .$from.$condicion.$order.$limit,$sql_params);

		return [
			'recordsTotal'    => !empty($recordsTotal) ? $recordsTotal : 0,
			'recordsFiltered' => !empty($recordsFiltered) ? $recordsFiltered : 0,
			'data'            => $lista ? $lista : [],
		];
	}

	/**
	 * Suma la antiguedad laboral de una persona en distintos sectores (publico, privado) teniendo en cuenta el solapamiento de fechas e incorpora la fecha de ingreso al MTR
	 *
	 * @param int $id_persona
	 * @param DateTime $fecha_ingreso
	 * @return array - ['anios'=>int, 'meses'=>int]
	 */
	static public function total_antiguedad_adm_publica($id_persona=null,$fecha_ingreso=null){
		if(empty($id_persona)){
			return ['anios'=>0, 'meses'=>0];
		}
		if(!empty($fecha_ingreso)){
			$rango_extra	= [[
				'fecha_desde'	=> $fecha_ingreso,
				'fecha_hasta'	=> \DateTime::createFromFormat('d/m/Y H:i:s.u', gmdate('d/m/Y').' 0:00:00.000000')
			]];
		} else {
			$rango_extra	= null;
		}
		$antiguedad_experiencia_laboral	= PersonaExperienciaLaboral::total_antiguedad($id_persona, $rango_extra);
		return $antiguedad_experiencia_laboral;
	}

}
