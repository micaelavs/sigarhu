<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
	$vars_vista['SUBTITULO']	= 'Modificar Familia de Puestos';
    $vars_template['OPERACION'] = 'modificacion';
	$vars_template['NOMBRE'] =  !empty($familia_puesto->familia) ? $familia_puesto->familia : '';
    $vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/puestos/index_familia_puesto'); 
	$template = (new \FMT\Template(VISTAS_PATH.'/templates/familia_puestos/alta.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);
	return true;
?>