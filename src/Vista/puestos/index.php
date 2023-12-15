<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use \App\Helper\Vista;
$config	= FMT\Configuracion::instancia();
$vars_template = [];
$vars_vista['SUBTITULO'] = 'Lista de Puestos';
$vars_template['TITULOS'] = [
    ['TITULO' => 'Puesto'],
    ['TITULO' => 'Subfamilia'],    
    ['TITULO' => 'Acciones'],
  ];
$vars_template['LINK'] = \App\Helper\Vista::get_url('index.php/puestos/alta');
$url_base = \App\Helper\Vista::get_url();
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('listado_puestos.js');
$vars_vista['CSS_FILES'][]['CSS_FILE'] = $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.css";
$vars_vista['JS_FILES'][]['JS_FILE']  = $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js";	
$vars_vista['JS_FILES'][]['JS_FILE']  = $config['app']['endpoint_cdn']."/datatables/defaults.js";
$vars_vista['JS'][]['JS_CODE'] = <<<JS
    var \$url_base ='{$url_base}';
JS;

$vars_template['LINK'] = \App\Helper\Vista::get_url('index.php/puestos/alta');
$vars_template['DATOS_TABLA'][]=  new \FMT\Template(TEMPLATE_PATH.'/tabla.html', $vars_template,['CLEAN'=>false]) ;
$puesto = new \FMT\Template(TEMPLATE_PATH.'/puestos/puestos.html',$vars_template,['CLEAN'=>false]);
$vars_vista['CONTENT'] = "{$puesto}";

$vista->add_to_var('vars',$vars_vista);
return true;
