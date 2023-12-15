<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;

$operacion = ($agrupamiento->id) ? 'modificacion' : 'alta';
$subt = ($agrupamiento->id) ? 'Modificación' : 'Alta';

$vars_vista['SUBTITULO']		= $subt .' de Agrupamiento';
$vars_template = [
	'OPERACION'	=> $operacion,
	'NOMBRE'	=> $agrupamiento->nombre,
	'CANCELAR'	=> \App\Helper\Vista::get_url('index.php/escalafon/lista_agrupamientos'),
	'MOD_VINCULACION' => \FMT\Helper\Template::select_block($modalidades,$agrupamiento->id_modalidad_vinculacion),
	'BOTON'		=> ($agrupamiento->id) ? 'Modificar' : 'Guardar', 
	'URL_BASE'  => \App\Helper\Vista::get_url(),
];

if($agrupamiento->id){
	$vars_template['SIT_REVISTA'] = \FMT\Helper\Template::select_block($sit_revista,$agrupamiento->id_situacion_revista);
}

$template = (new \FMT\Template(VISTAS_PATH.'/templates/escalafon/agrupamiento.html', $vars_template,['CLEAN'=>false]));
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('script.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('ajax_agrupamientos.js');
$config       = FMT\Configuracion::instancia();
$vars_vista['JS'][]['JS_CODE']	= <<<JS
var \$endpoint_cdn = '{$config['app']['endpoint_cdn']}';
JS;

$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;
?>