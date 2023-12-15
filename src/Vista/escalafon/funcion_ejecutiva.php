<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;

$operacion = ($f_ejecutiva->id) ? 'modificacion' : 'alta';
$subt = ($f_ejecutiva->id) ? 'Modificación' : 'Alta';

$vars_vista['SUBTITULO']		= $subt .' de Función Ejecutiva';
$vars_template = [
	'OPERACION'	=> $operacion,
	'NOMBRE'	=> $f_ejecutiva->nombre,
	'CANCELAR'	=> \App\Helper\Vista::get_url('index.php/escalafon/lista_funciones_ejecutivas'),
	'MOD_VINCULACION' => \FMT\Helper\Template::select_block($modalidades,$f_ejecutiva->id_modalidad_vinculacion),
	'BOTON'		=> ($f_ejecutiva->id) ? 'Modificar' : 'Guardar', 
	'URL_BASE'  => \App\Helper\Vista::get_url(),
];

if($f_ejecutiva->id){
	$vars_template['SIT_REVISTA'] = \FMT\Helper\Template::select_block($sit_revista,$f_ejecutiva->id_situacion_revista);
}

$template = (new \FMT\Template(VISTAS_PATH.'/templates/escalafon/funcion_ejecutiva.html', $vars_template,['CLEAN'=>false]));
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('script.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('ajax_funcion_ejecutiva.js');
$config       = FMT\Configuracion::instancia();

$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;
?>