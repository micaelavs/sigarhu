<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
	$config	= FMT\Configuracion::instancia();
	$vars_vista['SUBTITULO']	= 'Modificar Ubicación';
	$vars_vista['CSS_FILES'][]	= ['CSS_FILE'   => $config['app']['endpoint_cdn']."/js/select2/css/select2.min.css"];
    $vars_vista['JS_FILES'][]	= ['JS_FILE'    => $config['app']['endpoint_cdn']."/js/select2/js/select2.full.min.js"];
    $vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('ubicaciones.js');
    $vars_template['OPERACION'] = 'modificacion';
	$vars_template['EDIFICIO']		=   Template::select_block($edificios, $ubicaciones->id_edificio);
    $vars_template['PISO']			=  !empty($ubicaciones->piso) ? $ubicaciones->piso: '';
    $vars_template['OFICINA']		=  !empty($ubicaciones->oficina) ? $ubicaciones->oficina: '';
    $vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/ubicaciones/index'); 
	$template = (new \FMT\Template(VISTAS_PATH.'/templates/ubicaciones/alta.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);
	return true;
?>