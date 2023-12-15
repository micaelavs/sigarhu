<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
	$vars_vista['SUBTITULO']		= 'Alta Subfamilia';
    $vars_template['OPERACION']		= 'alta';
    $vars_template['FAMILIA_PUESTO'] = \FMT\Helper\Template::select_block($lista_familia, $subfamilia->id_familia);
    $vars_template['NOMBRE']		=  !empty($subfamilia->nombre) ? $subfamilia->nombre: '';
    $vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/puestos/index_subfamilia'); 
    $vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('subfamilias.js');
	$template = (new \FMT\Template(VISTAS_PATH.'/templates/subfamilias/alta.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);

	return true;
?>