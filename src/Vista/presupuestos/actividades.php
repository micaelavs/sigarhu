<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;

$operacion = ($actividad->id) ? 'modificacion' : 'alta';
$subt = ($actividad->id) ? 'Modificación' : 'Alta';

$vars_vista['SUBTITULO']		= $subt .' Actividad';
$vars_template = [
	'OPERACION'	=> $operacion,
	'CODIGO'	=> $actividad->codigo,
	'NOMBRE'	=> $actividad->nombre,
	'CANCELAR'	=> \App\Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_actividades'),
	'BOTON'		=> ($actividad->id) ? 'Modificar' : 'Guardar', 
];
$template = (new \FMT\Template(VISTAS_PATH.'/templates/presupuestos/actividades.html', $vars_template,['CLEAN'=>false]));
$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;
?>