<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
	$config	= \FMT\Configuracion::instancia();
	$vars_vista['SUBTITULO']		= 'Alta Dependencias';
	$vars_vista['CSS_FILES'][]		= ['CSS_FILE'   => $config['app']['endpoint_cdn']."/js/select2/css/select2.min.css"];
    $vars_vista['JS_FILES'][]		= ['JS_FILE'    => $config['app']['endpoint_cdn']."/js/select2/js/select2.full.min.js"];
    $vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('dependencias.js');
    $vars_template['OPERACION']				= 'alta';
    $vars_template['NOMBRE']				=  !empty($dependencia->nombre) ? $dependencia->nombre: '';
	$vars_template['FECHA_DESDE']			= !empty($temp = $dependencia->fecha_desde) ? $temp->format('d/m/Y') : '';
    $vars_template['NIVEL']					= \FMT\Helper\Template::select_block($nivel_organigrama,$dependencia->nivel);
    $vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/dependencias/index'); 
	$template = (new \FMT\Template(VISTAS_PATH.'/templates/dependencias/alta.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";

	$base_url = \App\Helper\Vista::get_url('index.php');
	$vars_vista['JS'][]['JS_CODE']	= <<<JS
	var \$base_url = "{$base_url}";
JS;
	$vista->add_to_var('vars',$vars_vista);
	return true;
