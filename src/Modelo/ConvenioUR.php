<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

class ConvenioUR extends Modelo {
/** @var int */
	public $id_nivel;
/** @var int */
	public $id_grado;
/** @var stdClass:: */
	public $monto;
/** @var stdClass:: */
	public $unidad;
/** @var ConvenioUR:: */
	private $clone;

/**
 * Obtener los montos y unidades retributivas para la funcion y nivel de modalidades 1109
 *
 * @param int $id_nivel - Funcion visto como 1109
 * @param int $id_grado - Nivel visto como 1109
 * @return object
 */
	static public function obtener($id_nivel=null, $id_grado=null){
		if(empty($id_nivel) || empty($id_grado)){
			return static::arrayToObject();
		}
		$sql_params = [
			':id_nivel'	=> $id_nivel,
			':id_grado'	=> $id_grado,
		];

		$filtros	= static::setFiltro();
		if(!empty($filtros['fecha'])){
			$sql_params[':filtro_fecha']	= ($filtros['fecha'] instanceof \DateTime) ? $filtros['fecha']->format('Y-m-d') : $filtros['fecha'];
			$where_monto	= '((monto.fecha_inicio <= :filtro_fecha AND monto.fecha_fin IS NULL) OR (monto.fecha_inicio <= :filtro_fecha AND :filtro_fecha < monto.fecha_fin))';
			$where_unidad	= '((unidad.fecha_inicio <= :filtro_fecha AND unidad.fecha_fin IS NULL) OR (unidad.fecha_inicio <= :filtro_fecha AND :filtro_fecha < unidad.fecha_fin))';
		} else {
			$where_monto	= 'monto.fecha_fin IS NULL';
			$where_unidad	= 'unidad.fecha_fin IS NULL';
		}

		$campos	= 'unidad.' . implode(', unidad.', [
			'id_nivel		AS id_nivel',
			'id_grado		AS id_grado',
			'id				AS unidad_id',
			'maximo			AS unidad_maximo',
			'minimo			AS unidad_minimo',
			'fecha_inicio	AS unidad_fecha_inicio',
			'fecha_fin		AS unidad_fecha_fin',
			'borrado		AS unidad_borrado',
		]);
		$campos	= $campos . ', monto.' . implode(', monto.', [
			'id				AS monto_id',
			'monto			AS monto_monto',
			'fecha_inicio	AS monto_fecha_inicio',
			'fecha_fin		AS monto_fecha_fin',
			'borrado		AS monto_borrado',
		]);

		$sql    = <<<SQL
			SELECT
				{$campos}
			FROM 
				convenio_unidades_retributivas AS unidad
				LEFT JOIN convenio_ur_montos AS monto ON (monto.id_nivel = :id_nivel AND monto.id_grado = :id_grado AND monto.borrado = 0 AND {$where_monto})
			WHERE
				unidad.id_nivel = :id_nivel AND unidad.id_grado = :id_grado AND unidad.borrado = 0 AND {$where_unidad}
			ORDER BY unidad.id DESC
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::SELECT, $sql, $sql_params);

		if(!empty($res)){
			return static::arrayToObject($res[0]);
		}
		return static::arrayToObject();
	}

/**
 * Al clonar ConvenioUR:: se clonan tambien los elementos hijos.
 * Los clones se usan para comparar diferencias al momento de hacer modificaciones.
 * @return void
 */
	final public function __clone(){
		$this->monto	= clone $this->monto;
		$this->unidad	= clone $this->unidad;
	}

/**
 * Listado de objetos ConvenioUR::
 * @return array
 */
	static public function listar(){
		$campos	= 'unidad.' . implode(', unidad.', [
			'id_nivel		AS id_nivel',
			'id_grado		AS id_grado',
			'id				AS unidad_id',
			'maximo			AS unidad_maximo',
			'minimo			AS unidad_minimo',
			'fecha_inicio	AS unidad_fecha_inicio',
			'fecha_fin		AS unidad_fecha_fin',
			'borrado		AS unidad_borrado',
		]);
		$campos	= $campos . ', monto.' . implode(', monto.', [
			'id				AS monto_id',
			'monto			AS monto_monto',
			'fecha_inicio	AS monto_fecha_inicio',
			'fecha_fin		AS monto_fecha_fin',
			'borrado		AS monto_borrado',
		]);

		$filtros	= static::setFiltro();
		if(!empty($filtros['fecha'])){
			$sql_params[':filtro_fecha']	= ($filtros['fecha'] instanceof \DateTime) ? $filtros['fecha']->format('Y-m-d') : $filtros['fecha'];
			$where_monto	= '((monto.fecha_inicio <= :filtro_fecha AND monto.fecha_fin IS NULL) OR (monto.fecha_inicio <= :filtro_fecha AND :filtro_fecha < monto.fecha_fin))';
			$where_unidad	= '((unidad.fecha_inicio <= :filtro_fecha AND unidad.fecha_fin IS NULL) OR (unidad.fecha_inicio <= :filtro_fecha AND :filtro_fecha < unidad.fecha_fin))';
		} else {
			$where_monto	= 'monto.fecha_fin IS NULL';
			$where_unidad	= 'unidad.fecha_fin IS NULL';
		}

		$sql    = <<<SQL
			SELECT
				{$campos}
			FROM 
				convenio_unidades_retributivas AS unidad
				LEFT JOIN convenio_ur_montos AS monto ON (unidad.id_nivel = monto.id_nivel AND unidad.id_grado = monto.id_grado AND monto.borrado = 0 AND {$where_monto})
			WHERE
				unidad.borrado = 0 AND {$where_unidad}
			ORDER BY unidad.id DESC
SQL;
		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::SELECT, $sql, []);
		if(empty($res)){
			return [];
		}
		foreach ($res as &$value) {
			$value	= static::arrayToObject($value);
		}
		return $res;
	}
	public function validar(){
		$required_bis	= function ($input) {
			return !empty($input);
		};
		$campos	= [
			'monto_monto'			=> $this->monto->monto,
			'monto_fecha_inicio'	=> $this->monto->fecha_inicio,
			'unidad_maximo'			=> $this->unidad->maximo,
			'unidad_minimo'			=> $this->unidad->minimo,
			'unidad_fecha_inicio'	=> $this->unidad->fecha_inicio,
		];
		$nombres	= [
			'monto_monto'			=> 'Monto',
			'monto_fecha_inicio'	=> 'Fecha Inicio Monto',
			'unidad_maximo'			=> 'Máximo Unidad Retributiva',
			'unidad_minimo'			=> 'Mínimo Unidad Retributiva',
			'unidad_fecha_inicio'	=> 'Fecha Inicio Unidad Retributiva',
		];
		$reglas		= [
			'monto_monto'			=> ['required_bis()' => $required_bis, 'numeric()' => function ($input) {
				if(is_numeric($input) && !empty($input)){
					return true;
				}
				return false;
			}],
			'monto_fecha_inicio'	=> ['required_bis()' => $required_bis, 'fecha'],
			'unidad_maximo'			=> ['required_bis()' => $required_bis, 'integer', '_maximo(:unidad_minimo)' => function($input, $unidad_minimo){
				if($input > $unidad_minimo){
					return true;
				}
				return false;
			}],
			'unidad_minimo'			=> ['required_bis()' => $required_bis, 'integer', '_minimo(:unidad_maximo)' => function($input, $unidad_maximo){
				if($input < $unidad_maximo){
					return true;
				}
				return false;
			}],
			'unidad_fecha_inicio'	=> ['required_bis()' => $required_bis, 'fecha'],
		];

		if($this->monto->fecha_inicio != $this->clone->monto->fecha_inicio){
			$that	= $this;
			$reglas['monto_fecha_inicio']['mayor_que_anterior()']	= function($input) use ($that){
				if($that->monto->fecha_inicio > $that->clone->monto->fecha_inicio){
					return true;
				}
				return false;
			};
		}
		if($this->unidad->fecha_inicio != $this->clone->unidad->fecha_inicio){
			$that	= $this;
			$reglas['unidad_fecha_inicio']['mayor_que_anterior()']	= function($input) use ($that){
				if($that->unidad->fecha_inicio > $that->clone->unidad->fecha_inicio){
					return true;
				}
				return false;
			};
		}

		$validator	= Validator::validate($campos, $reglas, $nombres);
		$validator->customErrors([
			'numeric()'      => 'El campo <strong> :attribute </strong> debe ser numerico.',
			'required_bis()' => 'El campo <strong>:attribute</strong> es obligatorio.',
			'mayor_que_anterior()'	=> 'El campo <strong>:attribute</strong> debe ser mayor que su valor anterior.',
			'_minimo'				=> 'El campo <strong>:attribute</strong> debe ser menor que el maximo.',
			'_maximo'				=> 'El campo <strong>:attribute</strong> debe ser mayor que el minimo.',
		]);

		if ($validator->isSuccess()) {
			return true;
		}
		$this->errores = $validator->getErrors();
		return false;
	}

	public function alta_monto(){
		if(empty($this->id_grado) || empty($this->id_nivel)){
			return false;
		}
		$error	= false;
		$cnx	= new Conexiones();
		if(!empty($this->clone->monto->id)){
			$this->baja_modificacion('monto');
		}
		if(empty($this->monto->id)){
			$sql	= 'INSERT INTO convenio_ur_montos (`id_nivel`,`id_grado`, `monto`, `fecha_inicio`) VALUES (:id_nivel, :id_grado, :monto, :fecha_inicio)';
			$sql_params	= [
				':id_nivel'		=> $this->id_nivel,
				':id_grado'		=> $this->id_grado,
				':monto'		=> $this->monto->monto,
				':fecha_inicio'	=> ($this->monto->fecha_inicio instanceof \DateTime) ? $this->monto->fecha_inicio->format('Y-m-d') : $this->monto->fecha_inicio,
			];
			$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
			if($res !== false){
				$this->monto->id	= $res;
			} else {
				$error	= true;
			}
		}
		if($error === false){
			$this->clone->monto	= null;
			$datos			= (array)$this;
			$datos['modelo']= 'ConvenioUR';
			Logger::event('alta', $datos);

			$this->clone->monto	= clone $this->monto;
			return true;
		}
		return false;
	}

	public function alta_unidad(){
		if(empty($this->id_grado) || empty($this->id_nivel)){
			return false;
		}
		$error	= false;
		$cnx	= new Conexiones();
		if(!empty($this->clone->unidad->id)){
			$this->baja_modificacion('unidad');
		}
		if(empty($this->unidad->id)){
			$sql	= 'INSERT INTO convenio_unidades_retributivas (`id_nivel`,`id_grado`, `minimo`, `maximo`,`fecha_inicio`) VALUES (:id_nivel,:id_grado, :minimo, :maximo,:fecha_inicio)';
			$sql_params	= [
				':id_nivel'		=> $this->id_nivel,
				':id_grado'		=> $this->id_grado,
				':maximo'		=> $this->unidad->maximo,
				':minimo'		=> $this->unidad->minimo,
				':fecha_inicio'	=> ($this->unidad->fecha_inicio instanceof \DateTime) ? $this->unidad->fecha_inicio->format('Y-m-d') : $this->unidad->fecha_inicio,
			];
			$res	= $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
			if($res !== false){
				$this->unidad->id	= $res;
			} else {
				$error	= true;
			}
		}
		if($error === false){
			$this->clone->unidad	= null;
			$datos			= (array)$this;
			$datos['modelo']= 'ConvenioUR';
			Logger::event('alta', $datos);

			$this->clone->unidad	= clone $this->unidad;
			return true;
		}
		return false;
	}
/**
 * Verifica si `monto` o `unidad` tienen `id` y guarda segun corresponda.
 * @return bool
 */
	public function alta(){
		return false;
	}

	public function baja(){
		return false;
	}

	private function baja_modificacion($tabla=null){
		if(!is_string($tabla)){
			return false;
		}
		switch($tabla){
			case 'monto':
				if(!(isset($this->clone->monto) && !empty($this->clone->monto->fecha_inicio))){
					return false;
				}
				$sql_params	= [
					':id'			=> $this->clone->monto->id,
					':fecha_fin'	=> $this->monto->fecha_inicio->format('Y-m-d'),
				];
				$sql	= 'UPDATE convenio_ur_montos SET fecha_fin = :fecha_fin WHERE id = :id';
				break;
			case 'unidad':
				if (!(isset($this->clone->unidad) && !empty($this->clone->unidad->fecha_inicio))) {
					return false;
				}
				$sql_params	= [
					':id'			=> $this->clone->unidad->id,
					':fecha_fin'	=> $this->unidad->fecha_inicio->format('Y-m-d'),
				];
				$sql	= 'UPDATE convenio_unidades_retributivas SET fecha_fin = :fecha_fin WHERE id = :id';
				break;
			default: return false; break;
		}

		$cnx	= new Conexiones();
		$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);
		if($res) {
			$datos['modelo']= 'ConvenioUR';
			$datos['data']	= $sql_params;
			Logger::event('baja', $datos);
			return true;
		}
		return false;
	}

	public function modificacion_monto(){
		$modifica	= false;
		$cnx	= new Conexiones();
		if(!empty($this->monto->id)){
			$sql_params	= [
				':id'		=> $this->monto->id,
				':monto'	=> $this->monto->monto,
			];
			$sql	= 'UPDATE convenio_ur_montos SET monto = :monto WHERE id = :id';
			$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);
			if($res) {
				$datos['modelo']= 'ConvenioUR';
				$datos['data']	= $sql_params;
				Logger::event('modificacion', $datos);
				$modifica			= true;
			}
		}
		return $modifica;
	}

	public function modificacion_unidad(){
		$modifica	= false;
		$cnx	= new Conexiones();
		if(!empty($this->unidad->id)){
			$sql_params	= [
				':id'		=> $this->unidad->id,
				':maximo'	=> $this->unidad->maximo,
				':minimo'	=> $this->unidad->minimo,
			];
			$sql	= 'UPDATE convenio_unidades_retributivas SET maximo = :maximo, minimo = :minimo WHERE id = :id';
			$res	= $cnx->consulta(Conexiones::UPDATE, $sql, $sql_params);
			if($res) {
				$datos['modelo']= 'ConvenioUR';
				$datos['data']	= $sql_params;
				Logger::event('modificacion', $datos);
				$modifica			= true;
			}
		}
		return $modifica;
	}

	public function modificacion(){
		return false;
	}

	static public function arrayToObject($res = []) {
		$campos	= [
			'id_nivel'	=> 'int',
			'id_grado'	=> 'int',
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

		$obj->unidad                = new \stdClass();
		$obj->unidad->id	        = !empty($tmp = \FMT\Helper\Arr::get($res,'unidad_id')) ? (int)$tmp : null;
		$obj->unidad->maximo        = !empty($tmp = \FMT\Helper\Arr::get($res,'unidad_maximo')) ? (int)$tmp : null;
		$obj->unidad->minimo        = !empty($tmp = \FMT\Helper\Arr::get($res,'unidad_minimo')) ? (int)$tmp : null;
		$obj->unidad->fecha_inicio	= !empty($res['unidad_fecha_inicio']) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res['unidad_fecha_inicio'] . ' 0:00:00') : null;
		$obj->unidad->fecha_fin		= !empty($res['unidad_fecha_fin']) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res['unidad_fecha_fin'] . ' 0:00:00') : null;
		$obj->unidad->borrado       = (bool)\FMT\Helper\Arr::get($res,'unidad_borrado');

		$obj->monto                 = new \stdClass();
		$obj->monto->id	            = !empty($tmp = \FMT\Helper\Arr::get($res,'monto_id')) ? (int)$tmp : null;
		$obj->monto->monto          = !empty($tmp = \FMT\Helper\Arr::get($res,'monto_monto')) ? (float)$tmp : null;
		$obj->monto->fecha_inicio   = !empty($res['monto_fecha_inicio']) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res['monto_fecha_inicio'] . ' 0:00:00') : null;
		$obj->monto->fecha_fin      = !empty($res['monto_fecha_fin']) ? \DateTime::createFromFormat('Y-m-d H:i:s', $res['monto_fecha_fin'] . ' 0:00:00') : null;
		$obj->monto->borrado        = (bool)\FMT\Helper\Arr::get($res,'monto_borrado');

		$obj->clone	= clone $obj;
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
}