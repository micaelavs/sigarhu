<?php
use \FMT\Template;
use \App\Helper\Vista;
$config	= FMT\Configuracion::instancia();
$vars_template['URL_BASE'] = Vista::get_url();
$vars_template['LINK'] = Vista::get_url('index.php/ubicaciones/alta');
$ubicaciones = new Template(TEMPLATE_PATH.'/ubicaciones/ubicaciones.html',$vars_template,['CLEAN'=>false]);
$vars_vista['SUBTITULO'] = "Lista de Ubicaciones";
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = Vista::get_url('ajax_ubicaciones.js');
$vars_vista['CSS_FILES'][]	= ['CSS_FILE' => $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.css"];
$vars_vista['JS_FILES'][]	= ['JS_FILE' => $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js"];
$vars_vista['JS_FILES'][]	= ['JS_FILE' => $config['app']['endpoint_cdn']."/datatables/defaults.js"];
$vars_vista['JS_FILES'][]	= ['JS_FILE' => $config['app']['endpoint_cdn']."/datatables/1.10.12/plugins/sorting/datetime-moment.js"];

$vars_vista['CONTENT'] = "{$ubicaciones}";
$vista->add_to_var('vars',$vars_vista);
