<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;

$operacion = ($obra->id) ? 'modificacion' : 'alta';
$subt = ($obra->id) ? 'Modificación' : 'Alta';

$vars_vista['SUBTITULO']		= $subt .' de Obra';
$vars_template = [
	'OPERACION'	=> $operacion,
	'CODIGO'	=> $obra->codigo,
	'NOMBRE'	=> $obra->nombre,
	'PROYECTOS' => \FMT\Helper\Template::select_block($proyectos,$obra->id_proyecto),
	'CANCELAR'	=> \App\Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_obras'),
	'BOTON'		=> ($obra->id) ? 'Modificar' : 'Guardar', 
];
$template = (new \FMT\Template(VISTAS_PATH.'/templates/presupuestos/obra.html', $vars_template,['CLEAN'=>false]));
$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;
?>