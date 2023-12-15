<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
	$vars_vista['SUBTITULO']		= 'Alta Sindicatos';
    $vars_template['OPERACION']		= 'alta';
    $vars_template['CODIGO']		=  !empty($sindicato->codigo) ? $sindicato->codigo: '';
    $vars_template['NOMBRE']		=  !empty($sindicato->nombre) ? $sindicato->nombre: '';
    $vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/sindicatos/index'); 
	$template = (new \FMT\Template(VISTAS_PATH.'/templates/sindicatos/alta.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);

	return true;
?>