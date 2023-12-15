<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;

$operacion = ($proyecto->id) ? 'modificacion' : 'alta';
$subt = ($proyecto->id) ? 'Modificación' : 'Alta';

$vars_vista['SUBTITULO']		= $subt .' Proyecto';
$vars_template = [
	'OPERACION'	=> $operacion,
	'NOMBRE'	=> $proyecto->nombre,
	'CODIGO'	=> $proyecto->codigo,
	'CANCELAR'	=> \App\Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_proyectos'),
	'PROGRAMAS' => \FMT\Helper\Template::select_block($programas,$proyecto->id_programa),
	'BOTON'		=> ($proyecto->id) ? 'Modificar' : 'Guardar', 
	'URL_BASE'  => \App\Helper\Vista::get_url(),
];
if($proyecto->id_programa){
	$vars_template['SUBPROGRAMAS'] = \FMT\Helper\Template::select_block($subprogramas,$proyecto->id_subprograma);
}
$template = (new \FMT\Template(VISTAS_PATH.'/templates/presupuestos/proyecto.html', $vars_template,['CLEAN'=>false]));
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('script.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('ajax_pre_proyectos.js');
$config = FMT\Configuracion::instancia();

$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;
?>