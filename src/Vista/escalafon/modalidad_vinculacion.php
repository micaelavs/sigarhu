<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;

$operacion = ($mod_vinculacion->id) ? 'modificacion' : 'alta';
$subt = ($mod_vinculacion->id) ? 'Modificación' : 'Alta';

if($mod_vinculacion->id){
	$vars_template['BOTON'] = 'Modificar';
}else{
	$vars_template['BOTON'] = 'Guardar';
}

$vars_vista['SUBTITULO']		= $subt .' Modalidad de Vinculación';
$vars_template['OPERACION']		= $operacion;
$vars_template['NOMBRE']		= $mod_vinculacion->nombre;
$vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/escalafon/lista_modalidad_vinculacion'); 
$template = (new \FMT\Template(VISTAS_PATH.'/templates/escalafon/modalidad_vinculacion.html', $vars_template,['CLEAN'=>false]));
$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;
?>