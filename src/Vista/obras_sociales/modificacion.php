<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
	$vars_vista['SUBTITULO']	= 'Modificar Obra Social';
    $vars_template['OPERACION'] = 'modificacion';
    $vars_template['CODIGO'] =  !empty($obra_social->codigo) ? $obra_social->codigo: '';
	$vars_template['NOMBRE'] =  !empty($obra_social->nombre) ? $obra_social->nombre: '';
    $vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/obras_sociales/index'); 
	$template = (new \FMT\Template(VISTAS_PATH.'/templates/obras_sociales/alta.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);
	return true;
?>