<?php
namespace App\Modelo;

use App\Modelo\Usuario;
use App\Helper\Conexiones;
use FMT\Configuracion;

abstract class Modelo extends \FMT\Modelo {
/**
 * Sirve para mapear el tipo de dato para cada atribtuto. Con el objetivo de usar `parent::arrayToObject();`
 * @var array
*/
    protected $campos	= null;
	protected static $ASOCIACIONES	= array();
	public static $FLAG = false;
/**
 * Al hace "new CualquierModelo" se llama a "self::getConexion()"
*/
	final protected function __construct(){
		parent::__construct();
		static::setVarsConexion();
	}

	static public function init(){
		new static();
	}

/**
 * Inicia conexion y setea variables para MySQL.
 * Invoca a Conexiones:: para mantener la sesion viva con los parametros necesarios.
 *
 * @param string $database	- nombre alternativo DB
 * @return Conexiones::
*/
	final static protected function setVarsConexion($database=''){
		if(!static::$FLAG ) {
			$config	= Configuracion::instancia();
			$cnx	= new Conexiones($database);

			$sql	= <<<SQL
SET @@sql_mode = 'NO_ENGINE_SUBSTITUTION';
SET @id_usuario := :id_usuario
SQL;
			$cnx->consulta(Conexiones::SELECT, $sql, [
			':id_usuario'	=> Usuario::obtenerUsuarioLogueado()->id,
			]);
			$sql	= <<<SQL
SET @@sql_mode = 'NO_ENGINE_SUBSTITUTION';
SET @db_historial := :db_historial
SQL;
			$cnx->consulta(Conexiones::SELECT, $sql, [
			':db_historial'	=> $config['database']['db_historial']['database']
			]);	
			static::$FLAG = true;	
		}
	}

/**
 * Sirve para controlar la respuesta de metodos ::obtener o ::listar que implenten ::arrayToObject().
 * La idea es poder controlar las vinculaciones devueltas por un modelo.
 * USO:
 * - Invocar `::contiene()` para limitar el objeto a los atributos propios, antes de invocar un `obtener()` o `::listar()`.
 * - Invocar `::contiene(['attr1', 'attr2'])` para limitar el objeto a los atributos propios y los especificados en el array, antes de invocar un `::obtener()" o `::listar()`.
 * - Invocar `::contiene(['attr1', 'attr2', 'modelo' => ['attr1', 'attr2']])` para limitar el objeto a los atributos propios y los especificados en el array, antes de invocar un `::obtener()" o `::listar()`.
 *
 * NUNCA se debe facilitar este comportamiento dentro de un modelo sin aplicar el respectivo destructor "::borrarContiene();"
 *
 * @param bool|array $asociaciones - Recibe true o un array de objectos asociados
 * @return void
*/
	final public static function contiene($asociaciones=true){
		$modelo	= get_called_class();
		if($asociaciones === true || (is_array($asociaciones) && count($asociaciones) == 0)){
			static::$ASOCIACIONES[$modelo]	= true;
		}
		if(is_array($asociaciones)){
			static::$ASOCIACIONES[$modelo]	= [];
			foreach ($asociaciones as $indice => $attr) {
				if(is_string($indice) && !is_numeric($indice)){
					$modelo_alt	= 'App\\Modelo\\' . ucfirst($indice);
					if(class_exists('\\'.$modelo_alt) && method_exists('\\'.$modelo_alt, 'contiene')){
						$modelo_alt::contiene($attr);
					}
					$attr = $indice;
				}
				if(!array_key_exists($attr, get_class_vars($modelo))) {
					throw new \Exception("El atributo seteado para {$modelo}::contiene() no existe en la clase.", 1);
				} else {
					static::$ASOCIACIONES[$modelo][]	= $attr;
				}
			}
		}
	}

/**
 * Resetea la propiedad "::$ASOCIACIONES" a "false". Se debe llamar dentro de los metodos staticos "::obtener()" o "::listar()" antes de cualquier "return".
 * Este metodo se puede usar directamente en un "return" pasandole como argumento lo que se espera devolver. 
 *
 * @param null|mixed	$return - Devuelve lo que recibe.
 * @return null|mixed
*/
	final protected static function borrarContiene(&$return=null){
		if(isset(static::$ASOCIACIONES[get_called_class()])){
			static::$ASOCIACIONES[get_called_class()]	= false;
		}

		return $return;
	}
/**
 * Obtiene las asociaciones.
 *
 * Cuando las asociaciones al modelo son un array y "$attribute" es false; devuelve false
 * Cuando las asociaciones al modelo son un array y "$attribute" es string; devuelve false|true
 * Cuando la asociacion al modelo es bool devuelve false|true
 *
 * @param bool|string	$attribute - Attributo a comprobar en el array de asociaciones
 * @return boolean
*/
	final protected static function getContiene($attribute=false){
		if(!isset(static::$ASOCIACIONES[get_called_class()]) && $attribute===false){
			return false;
		}
		if(!isset(static::$ASOCIACIONES[get_called_class()]) && $attribute!==false){
			return true;
		}
		$assoc	= static::$ASOCIACIONES[get_called_class()];

		if(is_bool($assoc)){
			return is_string($attribute) ? !$assoc : $assoc;
		}
		if(is_array($assoc) && $attribute===false){
			return false;
		}

		if(is_array($assoc) && is_string($attribute)){
			if(in_array($attribute, $assoc)){
				return true;
			}
			return false;
		}
		return $assoc;
    }

/**
 * Convierte un array a objeto usando los atributos del modelo.
 * Uso:
 * - Este metodo esta diseñado para ser reimplementado en cada Modelo extendido usando `parent::arrayToObject($respSQL,$campos);`
 * - Se le pasan por lo menos 2 argumentos, pero en la firma aqui escrita se especifica solo una por razones de retrocompatibilidad.
 * - Si no recibe ningun argumento devuelve la instancia del objeto vacio.
 * - Si recibe un solo argumento y no es un array, devuelve lo que recibe.
 * - El primer argumento normalmente es un array asociativo devuelto por `Conexiones::->consulta(Conexiones::SELECT,...)`, el segundo es un array asociativo cuyo indice es el atributo del objeto y el valor un tipo de dato: (`int`, `json`, `datetime`, `date`, `float`).
 * - Por razones de firma, compatibilidad o mera estetica, se le puede pasar todos los parametros que guste, siempre y cuendo el primero sea resultado de la consulta a la base de datos y el ultimo, el mapeo de campos.
 * - Solo los campos mapeados, seran cargados en los argumentos, el resto quedaran en vacios.
 *
 *
 * --- Ejemplo: `$respSQL` ---
 * ```php
 * [
 *  'id'        => '2',
 *  'fecha'     => '01-01-2020 08:00:00'
 *  'descricion'=> 'Texto'
 * ]
 * ```
 * --- Ejemplo: `$campos` ---
 *
 * ```php
 * [
 *  'id'        => 'int',
 *  'fecha'     => 'datetime'
 *  'descricion'=> 'string'
 * ]
 * ```
 * --- Ejemplo de implementacion ---
 *
 * ```php
 * static public function arrayToObject($res=[]) {
 * 		$campos	= [
 * 			'id'			=> 'int',
 * 			'fecha'			=> 'date',
 * 			'descripcion'	=> 'string',
 * 		];
 * 		return parent::arrayToObject($res, $campos);
 * }
 * ```
 *
 * @param array $respSQL - Normalmente la respuesta de una consulta a la base de datos.
 * @param array $campos - Se omite en la firma pero es requerido para el correcto funcionamiento.
 * @return static::
 */
    static public function arrayToObject($respSQL=null) {
        $child_obj    = new static;

        if($respSQL === null || (is_array($respSQL) && count($respSQL) == 0)){
            return $child_obj;
        }

        $argumentos = func_get_args();
        $campos     = count($argumentos)==null ? [] : $argumentos[count($argumentos)-1];
        $campos		= (!empty($child_obj->campos) && empty($campos))
            ? $child_obj->campos
            : $campos;

        // Si recibe un solo argumento y no es un array, devuelve lo que recibe.
        if(!is_array($respSQL) || $respSQL === $campos){
            return $respSQL;
        }
        if($respSQL !== $campos && is_array($respSQL) && count($respSQL) > 0 && (empty($campos) || !is_array($campos))){
            throw new \Exception('Esta intentando usar ::arrayToObject() sin el argumento $campos que mapea los campos con respecto a su tipo.', 1);
        }

        foreach ($campos as $campo => $type) {
            switch ($type) {
                case 'int':
                    $child_obj->{$campo}	= isset($respSQL[$campo]) ? (int)$respSQL[$campo] : null;
                    break;
                case 'float':
                    $child_obj->{$campo}	= isset($respSQL[$campo]) ? (float)$respSQL[$campo] : null;
                    break;
                case 'json':
                    $child_obj->{$campo}	= isset($respSQL[$campo]) ? json_decode($respSQL[$campo], true) : null;
                    break;
                case 'datetime':
                    $child_obj->{$campo}	= isset($respSQL[$campo]) ? \DateTime::createFromFormat('Y-m-d H:i:s.u', $respSQL[$campo].'.000000') : null;
                    break;
                case 'date':
					$child_obj->{$campo}	= isset($respSQL[$campo])
						? (($respSQL[$campo] == '0000-00-00') ? null : \DateTime::createFromFormat('Y-m-d H:i:s.u', $respSQL[$campo].' 0:00:00.000000')) 
						: null;
                    break;
                default:
                    $child_obj->{$campo}	= isset($respSQL[$campo]) ? $respSQL[$campo] : null;
                    break;
            }
        }
        return $child_obj;
	}

/**
 * Obtiene los valores de los array parametricos.
 * Si el paremetro no existe o esta vacio, devuelve array vacio.
 * E.J.: ModeloCualquiera::getParam('TIPO_TITULO');
 *
 * @param string $attr
 * @return mixed
*/
	static public function getParam($attr=null){
		if($attr === null || empty(static::${$attr})){
			return [];
		}
		return static::${$attr};
	}
}