<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
	$vars_vista['SUBTITULO']		= 'Alta Título';
    $vars_template['OPERACION']				= 'alta';
    $vars_template['NOMBRE']				=  !empty($titulo->nombre) ? $titulo->nombre: '';
    $vars_template['TIPO_TITULO']			= \FMT\Helper\Template::select_block($tipo_titulo,'');
    $vars_template['ABREV']					=  !empty($titulo->abreviatura) ? $titulo->abreviatura: '';
    $vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/titulos/index'); 
	$template = (new \FMT\Template(VISTAS_PATH.'/templates/titulos/alta.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);

	return true;
?>