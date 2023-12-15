<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
$config    = FMT\Configuracion::instancia();
$operacion = ($presupuesto->id) ? 'modificacion' : 'alta';
$subt = ($presupuesto->id) ? 'Modificación' : 'Alta';

$vars_vista['SUBTITULO']		= $subt .' de Presupuesto';
$vars_template = [
	'OPERACION'		=> $operacion,
	'SAF'			=> \FMT\Helper\Template::select_block((array)$parametricos['saf'], $presupuesto->id_saf),
	'JURISDICCIONES'=> \FMT\Helper\Template::select_block((array)$parametricos['jurisdicciones'], $presupuesto->id_jurisdiccion),
	'UB_GEOGRAFICAS'=> \FMT\Helper\Template::select_block((array)$parametricos['ub_geograficas'], $presupuesto->id_ubicacion_geografica),
	'PROGRAMAS'		=> \FMT\Helper\Template::select_block((array)$parametricos['programas'], $presupuesto->id_programa),
	'SUBPROGRAMAS'	=> \FMT\Helper\Template::select_block((array)$parametricos['subprogramas'], $presupuesto->id_subprograma),
	'PROYECTOS'		=> \FMT\Helper\Template::select_block((array)$parametricos['proyectos'], $presupuesto->id_proyecto),
	'ACTIVIDADES'	=> \FMT\Helper\Template::select_block((array)$parametricos['actividades'], $presupuesto->id_actividad),
	'OBRAS'			=> \FMT\Helper\Template::select_block((array)$parametricos['obras'], $presupuesto->id_obra),
	'CANCELAR'	=> \App\Helper\Vista::get_url('index.php/presupuestos/index'),
	'BOTON'		=> ($presupuesto->id) ? 'Modificar' : 'Guardar',
	'URL_BASE'  => \App\Helper\Vista::get_url(),
];


$template = (new \FMT\Template(VISTAS_PATH.'/templates/presupuestos/am_presupuesto.html', $vars_template,['CLEAN'=>false]));
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('script.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('ajax_presupuestos.js');

$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;
?>
