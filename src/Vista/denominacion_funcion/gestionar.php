<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
$operacion = ($denom_funcion->id) ? 'modificacion' : 'alta';
$subt = ($denom_funcion->id) ? 'Modificación' : 'Alta';

if($denom_funcion->id){
	$vars_template['BOTON'] = 'Modificar';
}else{
	$vars_template['BOTON'] = 'Guardar';
}

	$vars_vista['SUBTITULO']		= $subt .' Denominación de la Función';
	$vars_template['OPERACION']		= $operacion;
	$vars_template['NOMBRE']		= $denom_funcion->nombre;
    $vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/denominacion_funcion/index'); 
	$template = (new \FMT\Template(VISTAS_PATH.'/templates/denominacion_funcion/gestion.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);

	return true;
?>