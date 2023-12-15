<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
$config	= \FMT\Configuracion::instancia();
$vars_template['URL_BASE'] = \App\Helper\Vista::get_url();
$vars_template['LINK'] = \App\Helper\Vista::get_url('index.php/escalafon/agrupamiento');
$agrup = new \FMT\Template(TEMPLATE_PATH.'/escalafon/lista_agrupamientos.html',$vars_template,['CLEAN'=>false]);
$vars_vista['SUBTITULO'] = "Agrupamientos";
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('ajax_agrupamientos.js');
$vars_vista['CSS_FILES'][]	= ['CSS_FILE' => $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.css"];
$vars_vista['JS_FILES'][]	= ['JS_FILE' => $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js"];	
$vars_vista['JS_FILES'][]	= ['JS_FILE' => $config['app']['endpoint_cdn']."/datatables/defaults.js"];
$vars_vista['JS_FILES'][]	= ['JS_FILE' => $config['app']['endpoint_cdn']."/datatables/1.10.12/plugins/sorting/datetime-moment.js"];

//ELIMINAR CUANDO FUNCIONE default_js

$vars_vista['JS'][]['JS_CODE']	= <<<JS
var \$endpoint_cdn = '{$config['app']['endpoint_cdn']}';
JS;
$vars_vista['CONTENT'] = "$agrup";
$vista->add_to_var('vars',$vars_vista);