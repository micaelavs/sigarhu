<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
	$vars_vista['SUBTITULO']	= 'Modificar Puesto';
    $vars_template['OPERACION'] = 'modificacion';
    $vars_template['SUBFAMILIA'] = \FMT\Helper\Template::select_block($lista_subfamilia, $puesto->id_subfamilia);
	$vars_template['NOMBRE'] =  !empty($puesto->nombre) ? $puesto->nombre : '';
    $vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/puestos/index'); 
	$template = (new \FMT\Template(VISTAS_PATH.'/templates/puestos/alta.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);
	return true;
?>