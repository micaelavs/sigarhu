<?php
namespace App\Modelo;

use App\Helper\Conexiones;
use FMT\Logger;
use FMT\Usuarios;
use App\Helper\Validator;
use App\Modelo\AppRoles;

/**
 * Class Usuario
 */
class Usuario extends \FMT\Modelo {
	/** @var int */
	public $id = 0;
	/** @var string */
	public $username = null;
	/** @var string */
	public $nombre = null;
	/** @var string */
	public $apellido = null;
	/** @var string */
	public $email = null;
	/** @var array[id, nombre] */
	public $area = [];
	/** @var integer */
	public $rol_id;
	public $rol_nombre;
	public $metadata_nombre;
	public $metadata = [];
	protected static $cache = [];
	/**
	 * Regresa lista de usuarios
	 * @return array
	 */
	public static function listar() {
		$config = \FMT\Configuracion::instancia();
		$lista 	= Usuarios::getUsuarios();
		$roles_permitidos = \FMT\Helper\Arr::get(AppRoles::$rol,'roles_permitidos',[]);
		foreach( $lista as &$usuario) {
			$usuario = (object) $usuario;
			$usuario->rol_id = $usuario->permiso;
			if (empty($roles_permitidos) || in_array($usuario->rol_id , $roles_permitidos)) {
				$usuario->rol_nombre = AppRoles::$permisos[$usuario->rol_id]['nombre'];
				$meta = Usuarios::getMetadata($usuario->idUsuario);
				$usuario->metadata = (!is_null($meta['metadata'])) ? json_decode($meta['metadata']) : [];
				$usuario->metadata_nombre = $config['metadata_nombre'];
			} else {
				unset($usuario);
			}
		}
		return $lista;
	}

	/**
	 * @param int|string $user_id
	 * @return Usuario
	 */
	public static function obtener($user_id = 0) {
		$usuario = new static();
		if (!empty($user_id)) {
			if (isset(static::$cache[$user_id])) {
				$usuario = static::$cache[$user_id];
			} else {
				$user = Usuarios::getUsuario($user_id);
				if (isset($user['idUsuario'])) {
					$usuario = new static();
					$usuario->id = (int)$user['idUsuario'];
					$usuario->rol_id = Usuarios::getPermiso($usuario->id)['permiso'];
					if (empty($usuario->rol_id)) {
						$usuario->rol_id = 0;
					}
					$usuario->rol_nombre = AppRoles::$permisos[$usuario->rol_id]['nombre'];
					$usuario->username = isset($user['user']) ? (string)$user['user'] : null;
					$usuario->nombre = isset($user['nombre']) ? (string)$user['nombre'] : null;
					$usuario->apellido = isset($user['apellido']) ? (string)$user['apellido'] : null;
					$usuario->email = isset($user['email']) ? (string)$user['email'] : null;
					$usuario->area =(object)[
						'id'     => isset($user['area']) ? (int)$user['area'] : null,
						'nombre' => isset($user['nombreArea']) ? (string)$user['nombreArea'] : null,
					];
					$meta = Usuarios::getMetadata($user_id);
					$usuario->metadata = (!is_null($meta['metadata'])) ? json_decode($meta['metadata'], 1) : [];
					static::$cache[$user_id] = $usuario;
				}
			}
		}

		return $usuario;
	}

	/**
	 * @return Usuario
	 */
	
	public static function obtenerUsuarioLogueado() {
		if (isset($_SESSION['iu']) && is_numeric($_SESSION['iu'])) {
			return static::obtener($_SESSION['iu']);
		} else {
			return null;
		}
	}

	public function fullName() {
		return trim("{$this->nombre} {$this->apellido}");
	}

	public function modificacion() {
		$rta = false;
		if($this->id) {
			if (Usuarios::getPermiso($this->id) != $this->rol_id) {
				Usuarios::eliminarMetadata($this->id);
			}
			Usuarios::setPermiso($this->id, $this->rol_id);
			if (!empty($this->metadata)) {
	        	foreach ($this->metadata as $data) {
	        		$metadata[] = ['dependencia'=> $data]; 
	        	}
				Usuarios::setMetadata($this->id, json_encode($metadata));
			}
			$rta = true;
		}	
		return $rta;
	}

  public function validar(){  
    $rules = [
              'username'   => ['required'],
              'rol_id'     => ['required'],
//DEPREDO
/*
              'metadata'   => ['requerido(:rol_id)' => function($input,$rol_id){
              	$rta = true;
              	if($rol_id == \App\Modelo\AppRoles::ROL_LOTE){
              		$rta = (empty($input)) ? false : true;
              	}
              	return $rta;
			  }]
*/

             ];
	$nombres	= ['metadata'		=> 'Dependencia']; 	
    $validacion = Validator::validate((array)$this, $rules, $nombres);
    $validacion->customErrors([
                               'required'      => 'Campo :attribute es requerido',
                               'requerido'     => 'Campo :attribute es requerido'
                               ,                               
                             ]);
    if ($validacion->isSuccess() == true) {
      return true;
    } 
    else {
       $this->errores = $validacion->getErrors();
       return false;
    }  
    
  }

	/**
	 * Elimina el permiso del Usuario en la API y elimina la relación entre el usuario y el empleado.
	 * @return bool
	 */
	public function baja() {
		$rta = false;
		if (!empty($this->id)) {
			Usuarios::eliminarPermiso($this->id);
			Usuarios::eliminarMetadata($this->id);
			$rta = true;
		}
		return $rta;
	}

	public function alta() {
		trigger_error("No implementado", E_USER_ERROR);
		exit();
	}
}