<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;

$operacion = ($saf->id) ? 'modificacion' : 'alta';
$subt = ($saf->id) ? 'Modificación' : 'Alta';

$vars_vista['SUBTITULO']		= $subt .' de Código Saf';
$vars_template = [
	'OPERACION'	=> $operacion,
	'CODIGO'	=> $saf->codigo,
	'NOMBRE'	=> $saf->nombre,
	'CANCELAR'	=> \App\Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_saf'),
	'BOTON'		=> ($saf->id) ? 'Modificar' : 'Guardar', 
];
$template = (new \FMT\Template(VISTAS_PATH.'/templates/presupuestos/saf.html', $vars_template,['CLEAN'=>false]));
$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;
?>