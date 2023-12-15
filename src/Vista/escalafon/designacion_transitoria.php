<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;

$endpoint_cdn	= $vista->getSystemConfig()['app']['endpoint_cdn'];
$vars_template['URL_BASE'] = \App\Helper\Vista::get_url();
$designacion = new \FMT\Template(TEMPLATE_PATH.'/escalafon/designacion_transitoria.html',$vars_template,['CLEAN'=>false]);
$vars_vista['SUBTITULO'] = 'Informe Designación Transitoria';
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('designacion_transitoria.js');
$vars_vista['CSS_FILES'][]	= ['CSS_FILE' => $endpoint_cdn.'/datatables/1.10.12/datatables.min.css'];
$vars_vista['JS_FILES'][]	= ['JS_FILE' => $endpoint_cdn."/datatables/1.10.12/datatables.min.js"];	
$vars_vista['JS_FILES'][]	= ['JS_FILE' => $endpoint_cdn."/datatables/defaults.js"];
$vars_vista['JS_FILES'][]	= ['JS_FILE' => $endpoint_cdn."/datatables/1.10.12/plugins/sorting/datetime-moment.js"];
$vars_vista['CONTENT'] = "$designacion";
$vista->add_to_var('vars',$vars_vista);