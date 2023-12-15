<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
$config	= FMT\Configuracion::instancia();
$vars_template['URL_BASE'] = \App\Helper\Vista::get_url();
$vars_template['LINK'] = \App\Helper\Vista::get_url('index.php/presupuestos/subprograma');
$pre_subp = new \FMT\Template(TEMPLATE_PATH.'/presupuestos/lista_presupuesto_subprogramas.html',$vars_template,['CLEAN'=>false]);
$vars_vista['SUBTITULO'] = "Presupuestos Subprogramas";
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('ajax_pre_subprogramas.js');
$vars_vista['CSS_FILES'][]	= ['CSS_FILE' => $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.css"];
$vars_vista['JS_FILES'][]	= ['JS_FILE' => $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js"];	
$vars_vista['JS_FILES'][]	= ['JS_FILE' => $config['app']['endpoint_cdn']."/datatables/defaults.js"];
$vars_vista['JS_FILES'][]	= ['JS_FILE' => $config['app']['endpoint_cdn']."/datatables/1.10.12/plugins/sorting/datetime-moment.js"];

$vars_vista['CONTENT'] = "$pre_subp";
$vista->add_to_var('vars',$vars_vista);