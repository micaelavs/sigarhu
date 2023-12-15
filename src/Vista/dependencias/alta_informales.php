<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
$config	= \FMT\Configuracion::instancia();
	$vars_vista['SUBTITULO']		= 'Alta Dependencia Informal';
	$vars_vista['CSS_FILES'][]		= ['CSS_FILE'   => $config['app']['endpoint_cdn']."/js/select2/css/select2.min.css"];
    $vars_vista['JS_FILES'][]		= ['JS_FILE'    => $config['app']['endpoint_cdn']."/js/select2/js/select2.full.min.js"];
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('script.js');
    $vars_template['OPERACION']				= 'alta';
    $vars_template['NOMBRE']				= !empty($dep_informal->nombre) ? $dep_informal->nombre: '';
	$vars_template['FECHA_DESDE']			= !empty($temp = $dep_informal->fecha_desde) ? $temp->format('d/m/Y') : '';
	$vars_template['DEPENDENCIA'] = \FMT\Helper\Template::select_block($lista_dependencias, $dependencia->id);
    $vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/dependencias/index_informales'); 
	$template = (new \FMT\Template(VISTAS_PATH.'/templates/dependencias/alta_informales.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);

	return true;
?>