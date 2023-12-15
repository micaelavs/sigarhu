<?php
use \FMT\Helper\Template;
use \App\Helper\Vista;
use App\Modelo\AppRoles;

$vars_template = [];
$vars_template['IMAGEN'] = \App\Helper\Vista::get_url();
$vars_template['URL_SITIO'] =\App\Helper\Vista::get_url('../');
$vars_template['SITIO'] = str_replace(['http:','https:','/'],'', $vars_template['URL_SITIO']);
$vars_vista['CSS_FILES'][] = ['CSS_FILE' => \App\Helper\Vista::get_url('manuales.css')];
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('manuales.js');

$volver = false;
if(in_array($usuario, [AppRoles::ROL_ADMINISTRACION, AppRoles::ROL_IRI ])  && $rol_b){
	$usuario = $rol_b;	
	$volver  = true;
}

switch ($usuario) {
	case AppRoles::ROL_ADMINISTRACION_RRHH:
		$vars_vista['SUBTITULO'] = 'Manual de Usuario Administrador';
		$template = TEMPLATE_PATH.'/manuales/template_3.html';
		break;
	// case AppRoles::ROL_DESARROLLO_RRHH:
	// 	$vars_vista['SUBTITULO'] = 'Manual de Usuario de Administrador de Desarrollo';
	// 	$template = TEMPLATE_PATH.'/manuales/template_4.html';
	// 	break;
	case AppRoles::ROL_DESARROLLO_RRHH:
	case AppRoles::ROL_CONTROL_RRHH:
		$vars_vista['SUBTITULO'] = 'Manual de Usuario de Administrador de Control';
		$template = TEMPLATE_PATH.'/manuales/template_5.html';
		break;
	case AppRoles::ROL_CONVENIOS:
		$vars_vista['SUBTITULO'] = 'Manual de Usuario de Administrador de Convenios';
		$template = TEMPLATE_PATH.'/manuales/template_6.html';
		break;
	case AppRoles::ROL_LIQUIDACIONES:
		$vars_vista['SUBTITULO'] = 'Manual de Usuario de Administrador de Liquidaciones';
		$template = TEMPLATE_PATH.'/manuales/template_7.html';
		break;
	case (AppRoles::ROL_ADMINISTRACION || AppRoles::ROL_IRI):
		$vars_vista['SUBTITULO'] = 'Manuales de Usuarios';
		$vars_template['ADM_RRHH'] = \App\Helper\Vista::get_url('index.php/Manuales/index/'.AppRoles::ROL_ADMINISTRACION_RRHH);
		// $vars_template['DESARROLLO_RRHH'] = \App\Helper\Vista::get_url('index.php/Manuales/index/'.AppRoles::ROL_DESARROLLO_RRHH);
		$vars_template['CONTROL_RRHH'] = \App\Helper\Vista::get_url('index.php/Manuales/index/'.AppRoles::ROL_CONTROL_RRHH);
		$vars_template['CONVENIO'] = \App\Helper\Vista::get_url('index.php/Manuales/index/'.AppRoles::ROL_CONVENIOS);
		$vars_template['LIQUIDACONES'] = \App\Helper\Vista::get_url('index.php/Manuales/index/'.AppRoles::ROL_LIQUIDACIONES);
		$template = TEMPLATE_PATH.'/manuales/varios.html';
		break;
	default:
		# code...
		break;
}
if($volver) {
	$vars_template['BOTON_VOLVER'][0]['VOLVER'] = \App\Helper\Vista::get_url('index.php/Manuales/index');
}
$manual = new \FMT\Template($template,$vars_template,['CLEAN'=>false]);
$vars_vista['CONTENT'] = "{$manual}";
$vista->add_to_var('vars',$vars_vista);
return true;