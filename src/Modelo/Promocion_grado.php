<?php
namespace App\Modelo;

use App\Helper\Conexiones;
use FMT\Logger;
use App\Helper\Validator;
use FMT\Helper\Arr;

class Promocion_grado extends  Modelo {

/** @var int **/
    public $id;
/** @var int **/
	public $id_empleado;
/** @var int **/
	public $periodo_inicio;
/** @var int **/
	public $periodo_fin;
/** @var int **/
    public $id_tipo_promocion;
/** @var int **/
    public $id_empleado_escalafon;
/** @var int **/
    public $creditos_descontados;
/** @var int **/
    public $creditos_reconocidos;
/** @var int **/
    public $creditos_requeridos;
/** @var int **/
    public $id_grado;
/** @var date **/
    public $fecha_promocion;
/** @var varchar **/
    public $numero_expediente;
/** @var varchar **/
    public $acto_administrativo;
/** @var int **/
    public $id_motivo;
/** @var varchar */
    public  $archivo;
/** @var Empleado:: */
	public $empleado;

	static private $CONVERTIR_IDS	= false;

    public static function obtener($id=null){
        if($id===null){
			return static::resetConvertirIds(static::arrayToObject());
        }
        $sql_params	= [
            ':id'	=> $id,
        ];

        $sql	= <<<SQL
			SELECT
				id,
				id_empleado,
				periodo_inicio,
				periodo_fin,
				id_empleado_escalafon,
				id_grado,
				id_tipo_promocion,
				creditos_descontados,
				creditos_reconocidos,
				creditos_requeridos,
				numero_expediente,
				acto_administrativo,
				fecha_promocion,
				id_motivo,
				archivo,
				borrado
			FROM empleado_promociones
			WHERE id = :id
SQL;
        $res	= (new Conexiones())->consulta(Conexiones::SELECT, $sql, $sql_params);
        if(!empty($res)){
			return static::resetConvertirIds(static::arrayToObject($res[0]));
		}
		return static::resetConvertirIds(static::arrayToObject());
    }

	public static function listar(){
		$cnx	= new Conexiones();
		$sql	= <<<SQL
			SELECT 
				id,
				id_empleado,
				periodo_inicio,
				periodo_fin,
				id_tipo_promocion,
				id_empleado_escalafon,
				creditos_descontados
				creditos_reconocidos,
				creditos_requeridos,
				id_grado,
				fecha_promocion,
				numero_expediente,
				acto_administrativo,
				id_motivo,
				archivo
			FROM empleado_promociones WHERE borrado = 0
SQL;
		$res	= $cnx->consulta(Conexiones::SELECT, $sql, []);
		if(empty($res[0]['id'])){
			return static::resetConvertirIds([]);
		}
		$res	= array_reduce($res,function($acumulador, $v){
			$acumulador[$v['id']]	= static::arrayToObject($v);
			return $acumulador;
		},[]);

		return static::resetConvertirIds($res);
	}

	public function validar(){ 
		$reglas		= [
			'id_empleado'			=> ['required'],
			'id_grado'				=> ['required'],
			'id_tipo_promocion'		=> ['required'],
			'creditos_descontados'	=> ['required'],
			'id_empleado_escalafon'	=> ['required'],
			'fecha_promocion'		=> ['required', 'fecha'],
			'numero_expediente'		=> ['required'],
			'acto_administrativo'	=> ['required'],
			'id_motivo'				=> ['required'],
		];
		$nombres	= [
			'id_empleado'			=> "Empleado",
			'id_grado'				=> "Grado",
			'id_tipo_promocion'		=> "Tipo Promocion",
			'creditos_descontados'	=> "Creditos Descontados",
			'id_empleado_escalafon'	=> "Escalafon",
			'fecha_promocion'		=> "Fecha de Promoción",
			'numero_expediente'		=> "Número de Expediente",
			'acto_administrativo'	=> "Acto Administrativo",
			'id_motivo'				=> "Motivo",
		];
		$validator	= Validator::validate((array)$this, $reglas, $nombres);
			if ($validator->isSuccess()) {
				return true;
		}
		$this->errores = $validator->getErrors();
		return false;
	}

    public function alta() {
    	$this->upload_archivo();
        $cnx		= new Conexiones();
        $sql_params	= [
			':id_empleado'					=> $this->id_empleado,
			':periodo_inicio'				=> $this->periodo_inicio,
			':periodo_fin'					=> $this->periodo_fin,
            ':id_tipo_promocion'			=> $this->id_tipo_promocion,
            ':id_empleado_escalafon'		=> $this->id_empleado_escalafon,
            ':creditos_descontados'			=> $this->creditos_descontados,
            ':creditos_reconocidos'			=> $this->creditos_reconocidos,
            ':creditos_requeridos'			=> $this->creditos_requeridos,
            ':id_grado'						=> $this->id_grado,
            ':fecha_promocion'				=> $this->fecha_promocion,
            ':numero_expediente'			=> $this->numero_expediente,
            ':acto_administrativo'			=> $this->acto_administrativo,
            ':id_motivo'					=> $this->id_motivo,
            ':archivo'						=> $this->archivo
		];
		$campos		= [
			'id_empleado',
			'periodo_inicio',
			'periodo_fin',
			'id_grado',
			'id_tipo_promocion',
			'creditos_descontados',
			'creditos_reconocidos',
			'creditos_requeridos',
			'id_empleado_escalafon',
			'fecha_promocion',
			'numero_expediente',
			'acto_administrativo',
			'id_motivo',
			'archivo',
		];

        if($this->fecha_promocion instanceof \DateTime){
            $sql_params[':fecha_promocion']	= $this->fecha_promocion->format('Y-m-d');
        }

        $sql = 'INSERT INTO empleado_promociones ('.implode(',',$campos).') VALUES (:'.implode(',:',$campos).')';
        $res = $cnx->consulta(Conexiones::INSERT, $sql, $sql_params);
        if($res !== false){
            $this->id	= $res;
            $datos		= (array) $this;
            $datos['modelo']	= 'Promocion_grado';
			Logger::event('alta', $datos);

			Empleado::contiene(['situacion_escalafonaria']);
			$empleado			= Empleado::obtener($this->id_empleado, true);
			$empleado->baja_situacion_escalafonaria();
			$empleado->situacion_escalafonaria->fecha_fin	= null;
			$empleado->situacion_escalafonaria->id_grado	= $this->id_grado;
			$empleado->situacion_escalafonaria->fecha_inicio= $this->fecha_promocion;
			$empleado->alta_situacion_escalafonaria();
			
			$cambio_escalafon	= EmpleadoUltimosCambios::obtener_grado($this->id_empleado);
			$cambio_escalafon->fecha_desde	= $this->fecha_promocion;
			$cambio_escalafon->id_convenios	= $empleado->situacion_escalafonaria->id;
			$cambio_escalafon->guardar_grado();

			EmpleadoHistorialCreditos::setTipoPromocion($this->id_tipo_promocion);
			EmpleadoHistorialCreditos::quitarCreditos(
				$this->id_empleado,
				$this->id,
				EmpleadoHistorialCreditos::TABLA_EMPLEADO_PROMOCIONES,
				$this->creditos_descontados,
				$this->fecha_promocion
			);
        }
        return $res;
    }

	public function baja() { return false; }
	public function modificacion() { return false; }

    protected function upload_archivo(){
        if(isset($this->archivo["error"])){
            $error_file = false;
            if (!$error_file) {
                $rta = false;
                $date_time = gmdate('YmdHis');
                $directorio = BASE_PATH.'/uploads/promocion_grado';

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

/**
 * Obliga a las respuestas de `::listar` y `::obtener` a converir los IDs de atributo a su valor (string).
 * @return void
 */
	static public function convertirIds(){
		static::$CONVERTIR_IDS	= true;
	}

/**
 * Setea la constante `$CONVERTIR_IDS` a `false` y devuelve lo que se le pase por argumento.
 * @param mixed $pasaje
 * @return mixed
 */
	static private function resetConvertirIds($pasaje=null){
		static::$CONVERTIR_IDS	= false;
		return $pasaje;
	}
    static public function arrayToObject($res = []) {
        $campos	= [
            'id'					=> 'int',
			'id_empleado'			=> 'int',
			'periodo_inicio'		=> 'int',
			'periodo_fin'			=> 'int',
            'id_tipo_promocion'		=> 'int',
            'id_empleado_escalafon'	=> 'int',
            'creditos_descontados'	=> 'int',
            'creditos_reconocidos'	=> 'int',
            'creditos_requeridos'	=> 'int',
            'id_grado'				=> 'int',
            'fecha_promocion'		=> 'date',
            'numero_expediente'		=> 'string',
            'acto_administrativo'	=> 'string',
            'id_motivo'				=> 'int',
            'archivo'				=> 'string'
        ];
        $obj = parent::arrayToObject($res, $campos);

		if(static::$CONVERTIR_IDS === true){
			static $cache_grados	= null;
			if($cache_grados	== null){
				$cache_grados	= Grado::listar();
				$cache_grados	= array_reduce($cache_grados,function($acumulador, $v){
					if(!is_object($v)){
						return $acumulador	= [];
					}
					$acumulador[$v->id]	= (array)$v;
					return $acumulador;
				},[]);
			}
			$obj->id_tipo_promocion	= Arr::path(Curso::getParam('TIPO_PROMOCIONES'), $obj->id_tipo_promocion.'.nombre', '');
			$obj->id_grado			= Arr::path($cache_grados, $obj->id_grado.'.nombre', '');
			$obj->id_motivo			= Arr::path(SimuladorPromocionGrado::getParam('MOTIVOS_PROMOCION'), $obj->id_motivo.'.nombre', '');
		}

        if(static::getContiene()){
            return $obj;
        }

        if(static::getContiene('empleado')){
			static $cache_empleado	= [];
			if(empty($cache_empleado[$obj->id_empleado])){
				Empleado::contiene(['persona'=>[]]);
				$cache_empleado[$obj->id_empleado]	= Empleado::obtener($obj->id_empleado, true);
			}
            $obj->empleado	= $cache_empleado[$obj->id_empleado];
        }

        return $obj;
    }
}