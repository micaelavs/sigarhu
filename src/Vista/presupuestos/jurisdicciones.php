<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;

$operacion = ($jurisdiccion->id) ? 'modificacion' : 'alta';
$subt = ($jurisdiccion->id) ? 'Modificación' : 'Alta';

$vars_vista['SUBTITULO']		= $subt .' de Código de Jurisdicción';
$vars_template = [
	'OPERACION'	=> $operacion,
	'CODIGO'	=> $jurisdiccion->codigo,
	'NOMBRE'	=> $jurisdiccion->nombre,
	'CANCELAR'	=> \App\Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_jurisdicciones'),
	'BOTON'		=> ($jurisdiccion->id) ? 'Modificar' : 'Guardar', 
];
$template = (new \FMT\Template(VISTAS_PATH.'/templates/presupuestos/jurisdicciones.html', $vars_template,['CLEAN'=>false]));
$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;
?>