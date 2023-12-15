<?php
namespace App\Modelo;

use App\Modelo\Modelo;
use App\Helper\Validator;
use App\Helper\Conexiones;
use FMT\Logger;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Reader\Exception\ReaderNotOpenedException;

class ImportadorExcel extends Modelo {

/** 
 * Tipos de archivo soportados
 * @var array */
    public static $FILE_TIPE_PERMITIDOS = [
        // 'application/vnd.ms-excel',
        // 'text/xls',
        'text/xlsx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];
/**
 * El contenido de $_FILES
 * @var array */
    public $archivo         = null;
/**
 * El contenido de $_FILES
 * @var array */
    public $readerFactory    = null;
    
    const FILE_PATH = '/uploads/importador/';

/**
 * Procesa el archivo excel para insertarlo en los cursos.
 *
 * @param array $row    - [string]
 * @return void
 */
	public function procesarCursos($row=[]){
		$res            = true;
		$curso          = Curso::obtener();
		$empleado_curso = EmpleadoCursos::obtener();
		if (!empty($row[2]) && is_numeric($row[2])){
			$curso  = Curso::obtener_x_codigo($row[0]);

			if (empty($curso->id)) {
				$curso->codigo          = $row[0];
				$curso->nombre_curso    = $row[1]; 
				$curso->creditos        = $row[2];	
				$res    = $curso->alta();
			}
			if($res === false) {
				$this->errores = $curso->errores;	
			}else{
				Empleado::contiene();
				$empleado   = Empleado::obtener($row[4]);
				$empleado_curso->id_empleado    = $empleado->id;
				$empleado_curso->id_curso       = (int)$curso->id;
				$empleado_curso->fecha          = !empty($temp = $row[3]) 
					? (($temp instanceof \DateTime) ? $temp : \DateTime::createFromFormat('d/m/Y', $temp)) 
					: null;
					$res    = $empleado_curso->alta();
				if($res === false){
					$this->errores = $empleado_curso->errores;  
				} else {					 
					return true;
				}
			} 			
		}else{
			return false;
		}
	}

	static public function obtener($id=null){
		define('MAX_LINEAS_ERROR', 100);

		$obj = new static();
		return $obj;
	}

    public function subirArchivo($archivo=null){
		if(!empty($archivo) && empty($this->archivo)){
			$this->archivo	= $archivo;
		}
        $this->errores  = [];
        if((empty($this->archivo) && !is_array($this->archivo)) || $this->archivo['error'] == UPLOAD_ERR_NO_FILE){
            $this->errores[]    = 'ERROR: Sin archivo a procesar.';
		}
        if(!in_array($this->archivo['type'], static::$FILE_TIPE_PERMITIDOS)){
            $this->errores[]    = 'ERROR: Tipo de archivo Invalido.';
        }
        if(!empty($this->errores)){
            return false;
        }

        if (!is_dir(BASE_PATH.static::FILE_PATH)) {
            mkdir(BASE_PATH.static::FILE_PATH, 0777);
        }
        $ruta_archivo   = BASE_PATH.static::FILE_PATH.$this->archivo['name'];
        if(!move_uploaded_file($this->archivo['tmp_name'], $ruta_archivo)){
            $this->errores[]    = 'ERROR: Falla en el sistema.';
            return false;
		}

		$this->readerFactory = ReaderFactory::create(Type::XLSX);
		try{
			$this->readerFactory->open($ruta_archivo);
		} catch(ReaderNotOpenedException $e){
			$this->errores[]    = 'ERROR: Tipo de archivo Invalido.';
			$e;
			return false;
		} catch(IOException $e){
			$this->errores[]    = 'ERROR: Falla en el sistema.';
			$e;
			return false;
		}
		return true;
    }
	static public function listar() {return [];}
	public function alta(){ return false; }
	public function modificacion(){ return false; }
	public function baja(){ return false; }
	public function validar() { return false; }
}
