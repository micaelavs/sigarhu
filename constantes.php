<?php
define('BASE_PATH', realpath(__DIR__));
define('VISTAS_PATH', BASE_PATH .'/src/Vista');
define('TEMPLATE_PATH', BASE_PATH .'/src/Vista/templates');
define('CONSOLA_FILE', 'public/cron.php');
if(!empty($_SERVER['_']) && !defined('PHP_INTERPRETE')){
	define('PHP_INTERPRETE', $_SERVER['_']);
}
if(file_exists(BASE_PATH . '/uploads/constantes_tmp.php')){
	require_once BASE_PATH . '/uploads/constantes_tmp.php';
}
