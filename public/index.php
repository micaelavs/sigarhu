<?php
session_start();
require_once __DIR__.'/../bootstrap.php';

if(!isset($_SESSION['iu'])) {
	header("Location: {$config['app']['endpoint_panel']}");
	exit;
}

function crear_constantes_tmp(){
	if(file_exists(BASE_PATH . '/uploads/constantes_tmp.php')){
		return;
	}
	$code	= <<<TXT
<?php
define('REQUEST_SCHEME', '{$_SERVER['HTTP_X_PROTO']}');
define('HTTP_HOST', '{$_SERVER['HTTP_HOST']}');
define('SCRIPT_NAME', '{$_SERVER['SCRIPT_NAME']}');
TXT;
	$resource	= fopen(BASE_PATH . '/uploads/constantes_tmp.php', 'w+');
	fwrite($resource, $code);
	fclose($resource);
};crear_constantes_tmp();


FMT\FMT::init([
	'roles'	=> '\\App\\Modelo\\AppRoles',
	'id_modulo' => $config['app']['modulo']
]);
if (!isset($_SESSION['contador_login'])) {
	$datos						= [];
	$datos['session_data']		= $_SESSION['datos_usuario_logueado'];
	FMT\Logger::event('login', $datos);
	$_SESSION['contador_login']	= true;
}

$roles = \FMT\FMT::$roles;
if($roles && method_exists($roles, "obtener_inicio")) {
	$inicio_rol	= $roles::obtener_inicio();
	$control	= $inicio_rol['control'];
	$accion		= $inicio_rol['accion'];
} else {
	$control	= '';
	$accion		= '';
}
switch (true) {
	case (isset($_SERVER['PATH_INFO'])):
		$path_info = explode('/',ltrim($_SERVER['PATH_INFO'],'/'));
		if(isset($path_info[0])){
			$control	= $path_info[0];
		}
		if(isset($path_info[1])){
			$accion		= $path_info[1];
		}
		if(isset($path_info[2])){
			$_id		= $path_info[2];
		}
		if(isset($path_info[3])){
			$_id2		= $path_info[3];
		}
		break;
	case (isset($_GET['c']) || isset($_GET['a'])):
		$control = (isset($_GET['c'])) ? $_GET['c'] : $control;
		$accion  = (isset($_GET['a'])) ? $_GET['a'] : $accion;	
		break;
	default:
		# code...
		break;
}

$class		= 'App\\Controlador\\' . ucwords($control);
if (!class_exists($class, 1)) {
	$class	= 'App\\Controlador\\Error';
	$accion = 'index';
}
/** @var \App\Controlador\Base $control */
$control	= new $class($accion);
if (isset($_id)) {
	$control->set_query('id',$_id);
}
if (isset($_id2)) {
	$control->set_query('id2',$_id2);
}
$control->procesar();