<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
	$vars_vista['SUBTITULO']	= 'Modificar Licencias Especiales';
    $vars_template['OPERACION'] = 'modificacion';
	$vars_template['NOMBRE'] =  !empty($licencias_especiales->nombre) ? $licencias_especiales->nombre: '';
    $vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/licencias_especiales/index'); 
	$template = (new \FMT\Template(VISTAS_PATH.'/templates/licencias_especiales/alta.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);
	return true;
?>