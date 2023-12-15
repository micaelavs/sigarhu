<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
	$vars_vista['SUBTITULO']	= 'Modificar Subfamilia';
    $vars_template['OPERACION'] = 'modificacion';
    $vars_template['FAMILIA_PUESTO'] = \FMT\Helper\Template::select_block($lista_familia, $subfamilia->id_familia);
	$vars_template['NOMBRE'] =  !empty($subfamilia->subfamilia) ? $subfamilia->subfamilia : '';
    $vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/Puestos/index_subfamilia'); 
	$template = (new \FMT\Template(VISTAS_PATH.'/templates/subfamilias/alta.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);
	return true;
?>