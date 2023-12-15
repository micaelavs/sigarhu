<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;

$operacion = ($situacion_revista->id) ? 'modificacion' : 'alta';
$subt = ($situacion_revista->id) ? 'Modificación' : 'Alta';

if($situacion_revista->id){
	$vars_template['BOTON'] = 'Modificar';
}else{
	$vars_template['BOTON'] = 'Guardar';
}

$vars_vista['SUBTITULO']		= $subt .' Situación de Revista';
$vars_template['OPERACION']		= $operacion;
$vars_template['NOMBRE']		= $situacion_revista->nombre;
$vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/escalafon/lista_situacion_revista'); 

$vars_template['MODALIDAD_VINCULACION'] = \FMT\Helper\Template::select_block($mod_vinculacion,$situacion_revista->id_modalidad_vinculacion);

$template = (new \FMT\Template(VISTAS_PATH.'/templates/escalafon/situacion_revista.html', $vars_template,['CLEAN'=>false]));
$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;
?>