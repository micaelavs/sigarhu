<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
    $config	= \FMT\Configuracion::instancia();
	$vars_vista['SUBTITULO']	= 'Modificar de Edificio';
	$vars_vista['CSS_FILES'][]	= ['CSS_FILE'   => $config['app']['endpoint_cdn']."/js/select2/css/select2.min.css"];
    $vars_vista['JS_FILES'][]	= ['JS_FILE'    => $config['app']['endpoint_cdn']."/js/select2/js/select2.full.min.js"];
    $vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('script.js');
    $vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('edificios.js');
    $vars_template['OPERACION'] = 'modificacion';
	$vars_template['NOMBRE']		=  !empty($ubicaciones_edificios->nombre) ? $ubicaciones_edificios->nombre: '';
    $vars_template['CALLE']			=  !empty($ubicaciones_edificios->calle) ? $ubicaciones_edificios->calle: '';
    $vars_template['NUMERO']		=  !empty($ubicaciones_edificios->numero) ? $ubicaciones_edificios->numero: '';
    $vars_template['PROVINCIA']		=  Template::select_block($provincias, $ubicaciones_edificios->id_provincia);
    $vars_template['LOCALIDAD']		=  Template::select_block($localidades, $ubicaciones_edificios->id_localidad);
    $vars_template['COD_POSTAL']	=  !empty($ubicaciones_edificios->cod_postal) ? $ubicaciones_edificios->cod_postal: '';
    $vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/ubicaciones_edificios/index'); 
	$template = (new \FMT\Template(VISTAS_PATH.'/templates/ubicaciones_edificios/alta.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	$base_url = \App\Helper\Vista::get_url('index.php');
	$vars_vista['JS'][]['JS_CODE']	= <<<JS
    var \$base_url = "{$base_url}";
JS;
	$vista->add_to_var('vars',$vars_vista);
	return true;
?>