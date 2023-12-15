<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Conexiones;
use App\Helper\Bloques;

class EmpleadoHistorialCreditos extends Modelo {
/** @var int */
	public $id;
/** @var int */
	public $id_empleado;
/** @var int */
	public $id_tabla;
/** @var string */
	public $tabla_nombre;
/** @var int */
	public $creditos_agregados;
/** @var int */
	public $creditos_descontados;
/** @var int */
	public $creditos_disponibles;
/** @var int */
	public $porcentaje;		
/** @var DateTime */
	public $fecha_considerada;
/** @var DateTime */
	public $fecha_operacion;
/** @var int */
	public $tipo_promocion;
/** @var int */
	public $borrado;

	const TABLA_EMPLEADO_CURSOS 		= 'empleado_cursos';
	const TABLA_PERSONA_TITULO_CREDITOS = 'persona_titulo_creditos';
	const TABLA_EMPLEADO_PROMOCIONES	= 'empleado_promociones';
	const TABLA_EMPLEADO_CREDITOS_INICIALES	= 'empleado_creditos_iniciales';

	static private $TIPO_PROMOCION 		= null;

/**
 * @param int $id
 * @return object EmpleadoHistorialCreditos::
 */
	static public function obtener($id=null){
		return static::arrayToObject();
	}

	static public function listar(){return [];}
	public function validar(){return true; }
	public function modificacion(){return false;}
	public function baja(){return false; }
	public function alta(){return false; }

/**
 * Setea el tipo de promocion que aplica a los creditos.
 * Se obtiene su valor con `static::getTipoPromocion()` que reinicia el valor a su default luego de devolver el valor seteado.
 *
 * Se esperan valores de las constantes `Modelo\Curso::PROMOCION_GRADO`, `Modelo\Curso::PROMOCION_TRAMO` o similares.
 *
 * @param int $val
 * @return void
 */
	static public function setTipoPromocion($val=null){
		$default_value	= Curso::PROMOCION_GRADO;
		static::$TIPO_PROMOCION	= ($val === null) ? $default_value : (int)$val;
	}

/**
 * Devuelve el valor seteado por `static::setTipoPromocion()` y lo resetea a un valor por defecto.
 *
 * @return int
 */
	static public function getTipoPromocion(){
		$default_value	= Curso::PROMOCION_GRADO;
		$return			= static::$TIPO_PROMOCION;
		static::setTipoPromocion($default_value);

		return ($return !== null) 
			? $return 
			: $default_value;
	}

/**
 * Carga los porcentajes de creditos reconocidos
 *
 * @param int $id_empleado
 * @param int $id_tabla
 * @param string $tabla_nombre
 * @param int $porcentaje
 * @param DateTime|string $fecha_considerada
 *
 * @return bool
 */
	static public function agregarPorcentaje(
		$id_empleado=null,
		$id_tabla=null,
		$tabla_nombre=null,
		$porcentaje=null,
		$fecha_considerada=null
	){
		return static::cargarCreditos($id_empleado,$id_tabla, $tabla_nombre, null, null, $porcentaje, $fecha_considerada);
	}

/**
 * Agregar creditos a un empleado.
 *
 * @param int $id_empleado
 * @param int $id_tabla
 * @param int $tabla_nombre
 * @param string $creditos_agregados
 * @param DateTime|string $fecha_considerada
 * @return bool
 */
	static public function agregarCreditos(
		$id_empleado=null,
		$id_tabla=null,
		$tabla_nombre=null,
		$creditos_agregados=null,
		$fecha_considerada=null
	){
		return static::cargarCreditos($id_empleado,$id_tabla, $tabla_nombre, $creditos_agregados, null, null, $fecha_considerada);
	}

/**
 * Quita creditos a un empleado
 *
 * @param int $id_empleado
 * @param int $id_tabla
 * @param string $tabla_nombre
 * @param int $creditos_descontados
 * @param DateTime|string $fecha_considerada
 * @return bool
 */
	static public function quitarCreditos(
		$id_empleado=null,
		$id_tabla=null,
		$tabla_nombre=null,
		$creditos_descontados=null,
		$fecha_considerada=null
	){
		return static::cargarCreditos($id_empleado,$id_tabla, $tabla_nombre, null, $creditos_descontados, null, $fecha_considerada);
	}

/**
 * Permite sumar o quitar creditos a un empleado (por ID), tambien insertar porcentaje reconocido.
 * Solo para uso interno de la clase.
 * 
 * @param int $id_empleado
 * @param int $id_tabla
 * @param string $tabla_nombre
 * @param int $creditos_agregados
 * @param int $creditos_descontados
 * @param int $creditos_disponibles
 * @param int $porcentaje
 * @param DateTime|string $fecha_considerada
 *
 * @return bool
 */
	static protected function cargarCreditos(
		$id_empleado=null,
		$id_tabla=null,
		$tabla_nombre=null,
		$creditos_agregados=null,
		$creditos_descontados=null,
		$porcentaje=null,
		$fecha_considerada=null
	){
		if(empty($id_empleado) || empty($id_tabla) || empty($tabla_nombre) || empty($fecha_considerada)){
			return false;
		}

		if($fecha_considerada instanceof \DateTime){
			$fecha_considerada	= $fecha_considerada->format('Y-m-d');
		}
		$cnx = new Conexiones();

		$get_ultimo_balance	= static::getUltimoBalance($id_empleado, $fecha_considerada);

		$get_ultimo_balance	= !empty($get_ultimo_balance[0]['creditos_disponibles']) 
			? (int)$get_ultimo_balance[0]['creditos_disponibles'] 
			: 0;

		$balance_total	= ($get_ultimo_balance + (int)$creditos_agregados) - (int)$creditos_descontados;
		$sql_params	= [
			':id_empleado' 			=> $id_empleado,
			':id_tabla'				=> $id_tabla,
			':tabla_nombre' 		=> $tabla_nombre,
			':creditos_agregados' 	=> (int)$creditos_agregados,
			':creditos_descontados' => (int)$creditos_descontados,
			':creditos_disponibles' => (int)$balance_total,
			':porcentaje' 			=> (int)$porcentaje,
			':fecha_considerada' 	=> $fecha_considerada,
			':tipo_promocion' 		=> static::getTipoPromocion(),
		];

		$campos	= [
			'id_empleado',
			'id_tabla',
			'tabla_nombre',
			'creditos_agregados',
			'creditos_descontados',
			'creditos_disponibles',
			'porcentaje',
			'fecha_considerada',
			'tipo_promocion',
		];
		$sql = 'INSERT INTO empleado_historial_creditos (fecha_operacion,'.implode(',', $campos).') VALUES (NOW(), :'.implode(',:', $campos).')';
		$res = $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
	
		return (bool)$res;

	}

/**
 * Realiza una baja logica de creditos, normalmente se utiliza para porcentajes.
 *
 * @param int $id_empleado
 * @param int $id_tabla
 * @param string $tabla_nombre
 * @return bool
 */
	static public function bajaCredito($id_empleado=null, $id_tabla=null, $tabla_nombre=null){
		if(empty($id_tabla) || empty($tabla_nombre) || empty($id_empleado)){
			return false;
		}

		$cnx		= new Conexiones();
		$sql_params	= [
			':id_tabla' 	=> $id_tabla,
			':tabla_nombre' => $tabla_nombre,
			':id_empleado'	=> $id_empleado,
		];
		$sql = 'UPDATE empleado_historial_creditos
				SET borrado = 1
				WHERE id_tabla=:id_tabla AND borrado = 0 AND tabla_nombre =:tabla_nombre AND id_empleado = :id_empleado';
		$res = $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);
		return (bool)$res;

	}
/**
 * Convierte un array en objetos.
 *
 * @param array $res
 * @return object
 */
	static public function arrayToObject($res = []) {
		$campos	= [
			'id'					=> 'int',
			'id_empleado'			=> 'int',
			'id_tabla'				=> 'int',
			'tabla_nombre'			=> 'string',
			'creditos_agregados'	=> 'int',
			'creditos_descontados'	=> 'int',
			'creditos_disponibles' 	=> 'int',
			'porcentaje'			=> 'int',
			'fecha_considerada'		=> 'datetime',
			'fecha_operacion'		=> 'datetime',
			'borrado'				=> 'int'
		];

		$obj = new static;
		foreach ($campos as $campo => $type) {
			switch ($type) {
				case 'int':
					$obj->{$campo}	= isset($res[$campo]) ? (int)$res[$campo] : null;
					break;
				case 'json':
					$obj->{$campo}	= isset($res[$campo]) ? json_decode($res[$campo], true) : null;
					break;
				case 'datetime':
					$obj->{$campo}	= isset($res[$campo]) ? \DateTime::createFromFormat('Y-m-d H:i:s.u', $res[$campo].'.000000') : null;
					break;
				case 'date':
					$obj->{$campo}	= isset($res[$campo]) ? \DateTime::createFromFormat('Y-m-d H:i:s.u', $res[$campo].' 0:00:00.000000') : null;
					break;
				default:
					$obj->{$campo}	= isset($res[$campo]) ? $res[$campo] : null;
					break;
			}
		}
		return $obj;
	}

/**
 * Devuelve el ultimo balance de creditos de un empleado, si se pasa la `fecha_considerada`, lo restringe hasta esa fecha.
 * Si se requiere un grado de precicion alto se debe ejecutar primero `static::calcularHistoricoCreditos()`.
 *
 * @param int $id_empleado
 * @param DateTime|string $fecha_considerada - Opcional
 * @return int
 */
	static public function getUltimoBalance($id_empleado=null, $fecha_considerada=null){
		$fecha_considerada	= ($fecha_considerada instanceof \DateTime) ? $fecha_considerada->format('Y-m-d') : $fecha_considerada;
		if(empty($id_empleado)){
			return 0;
		}
		$where_fecha	= '';
		$sql_params		= [
			':id_empleado'	=> $id_empleado,
		];
		if(!empty($fecha_considerada)){
			$sql_params[':fecha_considerada']	= $fecha_considerada;
			$where_fecha						= ' AND fecha_considerada <= :fecha_considerada ';
		}

		$cnx	= new Conexiones();
		$get_ultimo_balance	= $cnx->consulta(Conexiones::SELECT, 'SELECT id, creditos_disponibles FROM empleado_historial_creditos WHERE borrado = 0 AND id_empleado = :id_empleado '.$where_fecha.' ORDER BY fecha_considerada DESC, id DESC LIMIT 1', $sql_params);

		$get_ultimo_balance	= !empty($get_ultimo_balance[0]['creditos_disponibles']) 
			? (int)$get_ultimo_balance[0]['creditos_disponibles'] 
			: 0;
		return $get_ultimo_balance;
	}

/**
 * Genera el calculo de creditos disponibles para la tabla `empleado_historial_creditos`.
 * Para cuando todo falla, este es el algoritmo que funciona.
 *
 * Si se pasa un `$id_empleado`, lo calcula solo para ese empleado. Si se le pasa `$hasta_fecha_considerada` calcula hasta esa fecha.
 * @param int $id_empleado - Opcional
 * @param DateTime|string $fecha_considerada - Opcional
 * @return void
 */
	static public function calcularHistoricoCreditos($id_empleado=null, $hasta_fecha_considerada=null){
		$cnx		= new Conexiones();
		if($id_empleado == null){
			$sql		= 'SELECT DISTINCT id_empleado FROM empleado_historial_creditos WHERE borrado = 0 GROUP BY id_empleado';
			$agentes_con_creditos	= $cnx->consulta(Conexiones::SELECT, $sql,[]);
			if(empty($agentes_con_creditos[0]['id_empleado'])){
				return;
			}
		} else {
			$agentes_con_creditos	= [0=>[
				'id_empleado'	=> (int)$id_empleado,
			]];
		}

		$hasta_fecha_considerada	= ($hasta_fecha_considerada instanceof \DateTime) ? $hasta_fecha_considerada->format('Y-m-d') : $hasta_fecha_considerada;
		
		foreach ($agentes_con_creditos as $val) {
			$flag				= true;
			$control_id			= null;
			$total_disponible	= 0;

			$paginado_cantidad	= 50;
			$paginado_start		= 0;
			$sql_params	= [
				':id_empleado'		=> $val['id_empleado'],
			];
			$where_fecha	= '';
			if(!empty($hasta_fecha_considerada)){
				$where_fecha						= ' AND fecha_considerada <= :fecha_considerada ';
				$sql_params[':fecha_considerada']	= $hasta_fecha_considerada;
			}
			do{
				$sql		= <<<SQL
					SELECT 
						id, 
						id_empleado, 
						id_tabla, 
						tabla_nombre, 
						creditos_agregados, 
						creditos_descontados, 
						creditos_disponibles, 
						porcentaje, 
						fecha_considerada, 
						fecha_operacion, 
						tipo_promocion, 
						borrado
					FROM empleado_historial_creditos 
					WHERE id_empleado = :id_empleado AND borrado = 0 {$where_fecha}
					ORDER BY fecha_considerada ASC, id ASC 
					LIMIT {$paginado_start}, {$paginado_cantidad}
SQL;
				$aux_agentes_con_creditos	= $cnx->consulta(Conexiones::SELECT, $sql,$sql_params);
				if(empty($aux_agentes_con_creditos[0]['id'])){
					$flag	= false;
				} else{
					// Evitar repeticion en paginado
					if($control_id	== $aux_agentes_con_creditos[0]['id']){
						$flag	= false;
						continue;
					}
					$control_id	= $aux_agentes_con_creditos[0]['id'];

					foreach ($aux_agentes_con_creditos as $hist) {
						if(!empty($hist['creditos_agregados']) || !empty($hist['creditos_descontados'])){
							$total_disponible	= ($total_disponible + (int)$hist['creditos_agregados']) - (int)$hist['creditos_descontados'];
						}
						if((int)$hist['creditos_disponibles'] !== $total_disponible){
							$sql_params_update	= [
								':creditos_disponibles'	=> $total_disponible,
								':id'					=> $hist['id'],
							];
							$update	= 'UPDATE empleado_historial_creditos SET creditos_disponibles = :creditos_disponibles WHERE id = :id';
							$update	= $cnx->consulta(Conexiones::UPDATE, $update, $sql_params_update);
						}
					}
					$paginado_start	= $paginado_start + $paginado_cantidad;
				}
			}while ($flag==true);
		}
	}
}

