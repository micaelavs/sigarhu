<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
	$vars_vista['SUBTITULO']	= 'Modificar Tipo de Discapacidad';
    $vars_template['OPERACION'] = 'modificacion';
	$vars_template['NOMBRE'] =  !empty($tipo_discapacidad->nombre) ? $tipo_discapacidad->nombre: '';
	$vars_template['DESCRIPCION'] =  !empty($tipo_discapacidad->descripcion) ? $tipo_discapacidad->descripcion: '';
    $vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/tipo_discapacidad/index'); 
	$template = (new \FMT\Template(VISTAS_PATH.'/templates/tipo_discapacidad/alta.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);
	return true;
?>