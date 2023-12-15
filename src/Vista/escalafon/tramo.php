<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
$config = FMT\Configuracion::instancia();
$operacion = ($tramo->id) ? 'modificacion' : 'alta';
$subt = ($tramo->id) ? 'Modificación' : 'Alta';

$vars_vista['SUBTITULO']		= $subt .' de Tramo';
$vars_template = [
	'OPERACION'	=> $operacion,
	'NOMBRE'	=> $tramo->nombre,
	'CANCELAR'	=> \App\Helper\Vista::get_url('index.php/escalafon/lista_tramos'),
	'MOD_VINCULACION' => \FMT\Helper\Template::select_block($modalidades,$tramo->id_modalidad_vinculacion),
	'BOTON'		=> ($tramo->id) ? 'Modificar' : 'Guardar', 
	'URL_BASE'  => \App\Helper\Vista::get_url(),
];

if($tramo->id){
	$vars_template['SIT_REVISTA'] = \FMT\Helper\Template::select_block($sit_revista,$tramo->id_situacion_revista);
}

$template = (new \FMT\Template(VISTAS_PATH.'/templates/escalafon/tramo.html', $vars_template,['CLEAN'=>false]));
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('script.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('ajax_tramos.js');

$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;
?>