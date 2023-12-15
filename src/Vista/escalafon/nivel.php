<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;

$operacion = ($nivel->id) ? 'modificacion' : 'alta';
$subt = ($nivel->id) ? 'Modificación' : 'Alta';
$vars_vista['SUBTITULO'] = $subt .' Nivel';
$vars_template = [
	'OPERACION'	=> $operacion,
	'NOMBRE'	=> $nivel->nombre,
	'CANCELAR'	=> \App\Helper\Vista::get_url('index.php/escalafon/lista_niveles'),
	'MOD_VINCULACION' => \FMT\Helper\Template::select_block($modalidades,$nivel->agrupamiento->id_modalidad_vinculacion),
	'BOTON'		=> ($nivel->id) ? 'Modificar' : 'Guardar', 
	'URL_BASE'  => \App\Helper\Vista::get_url(),
];


$vars_template['SIT_REVISTA'] = \FMT\Helper\Template::select_block($sit_revista,$nivel->agrupamiento->id_situacion_revista);
$vars_template['AGRUPAMIENTO'] = \FMT\Helper\Template::select_block($agrupamiento,$nivel->id_agrupamiento);

$template = (new \FMT\Template(VISTAS_PATH.'/templates/escalafon/nivel.html', $vars_template,['CLEAN'=>false]));
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('script.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('ajax_niveles.js');
$config = FMT\Configuracion::instancia();

$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;
?>