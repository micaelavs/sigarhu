<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
	$vars_vista['SUBTITULO']		= 'Alta Motivo de Baja';
    $vars_template['OPERACION']		= 'alta';
    $vars_template['NOMBRE']		=  !empty($motivo_baja->nombre) ? $motivo_baja->nombre: '';
    $vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/motivos_baja/index'); 
	$template = (new \FMT\Template(VISTAS_PATH.'/templates/motivos_baja/alta.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);

	return true;
?>