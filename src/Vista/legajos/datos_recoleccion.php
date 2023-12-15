<?php
use \App\Helper\Vista;
use \FMT\Helper\Arr;
$config	= FMT\Configuracion::instancia();
$vars_template = [];
$vars_vista['SUBTITULO'] = 'Datos en Proceso de Recolección';
$vars_template['TITULOS'] = [
	['TITULO' => 'Cuit'],
	['TITULO' => 'Nombre'],
	['TITULO' => 'Apellido'],
];

$url_base = \App\Helper\Vista::get_url();
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('ajax_datos_recoleccion.js');
$vars_vista['CSS_FILES'][]			 	= ['CSS_FILE' => \App\Helper\Vista::get_url('datos_globales.css')];
$vars_vista['CSS_FILES'][]				= ['CSS_FILE' => $config['app']['endpoint_cdn'].'/datatables/1.10.12/datatables.min.css'];
$vars_vista['CSS_FILES'][]  			= ['CSS_FILE' => \App\Helper\Vista::get_url('funkyradio.css')];
$vars_vista['JS_FILES'][]['JS_FILE']  	= $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js";	
$vars_vista['JS_FILES'][]['JS_FILE']  	= $config['app']['endpoint_cdn']."/datatables/defaults.js";
$vars_vista['JS'][]['JS_CODE']		 	= <<<JS
    var \$url_base ='{$url_base}';
JS;
$vars_template['LINK'] = \App\Helper\Vista::get_url('index.php/legajos/datos_globales');
$recoleccion = new \FMT\Template(TEMPLATE_PATH.'/legajos/datos_recoleccion.html',$vars_template,['CLEAN'=>false]);
$vars_vista['CONTENT'] = "{$recoleccion}";
$vista->add_to_var('vars',$vars_vista);
return true;