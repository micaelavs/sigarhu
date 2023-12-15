<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
	$vars_vista['SUBTITULO']		= 'Alta Seguro de Vida';
    $vars_template['OPERACION']		= 'alta';
    $vars_template['NOMBRE']		=  !empty($seguro_vida->nombre) ? $seguro_vida->nombre: '';
    $vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/seguros_vida/index'); 
	$template = (new \FMT\Template(VISTAS_PATH.'/templates/seguros_vida/alta.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);

	return true;
?>