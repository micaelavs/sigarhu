<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;

$operacion = ($subprograma->id) ? 'modificacion' : 'alta';
$subt = ($subprograma->id) ? 'Modificación' : 'Alta';

$vars_vista['SUBTITULO']		= $subt .' de Subprograma';
$vars_template = [
	'OPERACION'	=> $operacion,
	'NOMBRE'	=> $subprograma->nombre,
	'CODIGO'	=> $subprograma->codigo,
	'CANCELAR'	=> \App\Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_subprogramas'),
	'PROGRAMAS' => \FMT\Helper\Template::select_block($programas,$subprograma->id_programa),
	'BOTON'		=> ($subprograma->id) ? 'Modificar' : 'Guardar', 
	'URL_BASE'  => \App\Helper\Vista::get_url(),
];

$template = (new \FMT\Template(VISTAS_PATH.'/templates/presupuestos/subprograma.html', $vars_template,['CLEAN'=>false]));
$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;
?>