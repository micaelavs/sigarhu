<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;

$operacion = ($grado->id) ? 'modificacion' : 'alta';
$subt = ($grado->id) ? 'Modificación' : 'Alta';

$vars_vista['SUBTITULO'] = $subt .' Grado';
$vars_template = [
	'OPERACION'	=> $operacion,
	'NOMBRE'	=> $grado->nombre,
	'MOD_VINCULACION' => \FMT\Helper\Template::select_block($modalidades,$grado->tramo->id_modalidad_vinculacion),
	'CANCELAR'	=> \App\Helper\Vista::get_url('index.php/escalafon/lista_grados'),
	'BOTON'		=> ($grado->id) ? 'Modificar' : 'Guardar',
	'URL_BASE'  => \App\Helper\Vista::get_url(),
];
//if($grado->id){
	$vars_template['SIT_REVISTA'] = \FMT\Helper\Template::select_block($sit_revista,$grado->tramo->id_situacion_revista);
	$vars_template['TRAMOS'] = \FMT\Helper\Template::select_block($tramos,$grado->id_tramo);
//}
$template = (new \FMT\Template(VISTAS_PATH.'/templates/escalafon/grado.html', $vars_template,['CLEAN'=>false]));
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('script.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('ajax_grados.js');
$config       = FMT\Configuracion::instancia();

$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;
?>