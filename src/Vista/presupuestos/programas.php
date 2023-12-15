<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;

$operacion = ($programa->id) ? 'modificacion' : 'alta';
$subt = ($programa->id) ? 'Modificación' : 'Alta';

$vars_vista['SUBTITULO']		= $subt .' de Código de Programas';
$vars_template = [
	'OPERACION'	=> $operacion,
	'CODIGO'	=> $programa->codigo,
	'NOMBRE'	=> $programa->nombre,
	'CANCELAR'	=> \App\Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_programas'),
	'BOTON'		=> ($programa->id) ? 'Modificar' : 'Guardar', 
];
$template = (new \FMT\Template(VISTAS_PATH.'/templates/presupuestos/programas.html', $vars_template,['CLEAN'=>false]));
$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;
?>