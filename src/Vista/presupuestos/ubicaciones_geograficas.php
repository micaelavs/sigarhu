<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;

$operacion = ($geografica->id) ? 'modificacion' : 'alta';
$subt = ($geografica->id) ? 'Modificación' : 'Alta';

$vars_vista['SUBTITULO'] = $subt .' de Códigos de Ubicación Geográfica';
$vars_template = [
	'OPERACION'	=> $operacion,
	'CODIGO'	=> $geografica->codigo,
	'NOMBRE'	=> $geografica->nombre,
	'CANCELAR'	=> \App\Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_ub_geograficas'),
	'BOTON'		=> ($geografica->id) ? 'Modificar' : 'Guardar', 
];
$template = (new \FMT\Template(VISTAS_PATH.'/templates/presupuestos/ubicaciones_geograficas.html', $vars_template,['CLEAN'=>false]));
$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;
?>