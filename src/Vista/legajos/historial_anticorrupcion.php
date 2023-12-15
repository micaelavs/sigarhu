<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
$config	= FMT\Configuracion::instancia();
$anios =[];
for ($i=2016; $i <= date('Y') ; $i++){
	$anios[$i] =$i;
}
if($parametros){
    $vars_template = [
        'TIPO_DJ'               =>  Template::select_block($parametros['tipo_dj']),
        'PERIODO'      		    =>  Template::select_block($anios),
        'BOTON_EXCEL'			=> \App\Helper\Vista::get_url("index.php/legajos/exportar_anticorrupcion"),
        'BOTON_PDF'				=>  \App\Helper\Vista::get_url("index.php/legajos/exportar_anticorrupcion_pdf"),
    ];
}
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('historial_anticorrupcion_ajax.js');
$vars_template['URL_BASE'] = \App\Helper\Vista::get_url();
$historial_anticorrupcion = new \FMT\Template(TEMPLATE_PATH.'/legajos/historial_anticorrupcion.html',$vars_template,['CLEAN'=>false]);

$vars_vista['SUBTITULO'] = 'Informe Histórico de Anticorrupción';
$vars_vista['CSS_FILES'][]	= ['CSS_FILE' => $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.css"];
$vars_vista['JS_FILES'][]	= ['JS_FILE' => $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js"];	
$vars_vista['JS_FILES'][]	= ['JS_FILE' => $config['app']['endpoint_cdn']."/datatables/defaults.js"];
$vars_vista['JS_FILES'][]	= ['JS_FILE' => $config['app']['endpoint_cdn']."/datatables/1.10.12/plugins/sorting/datetime-moment.js"];

$vars_vista['CONTENT'] = "$historial_anticorrupcion";
$vista->add_to_var('vars',$vars_vista);



