<?php
use \FMT\Helper\Template;

use App\Helper\Vista;
use \FMT\Helper\Arr;

$vars_vista['SUBTITULO'] = 'Exportación de Datos Anticorrupción';


$anios =[];
for ($i=2016; $i <= date('Y') ; $i++){
	$anios[$i] =$i;
}

    $vars_template = [
        'TIPO_DJ'	=>  $tipo_dj,
        'PERIODO'	=>  $periodo,
		'SEARCH'	=>  $search,
		'CAMPO'		=>  $campo,
		'DIR'		=>  $dir,
		'TOTAL_ROWS' =>  $total_rows,
		'URL_BASE'	=> Vista::get_url("index.php"),
		'FORM'      => \App\Helper\Vista::get_url("index.php/legajos/anticorrupcion_pdf")

    ];

$vars_template['VOLVER'][0] = [ 'HREF'      => \App\Helper\Vista::get_url("index.php/legajos/historial_anticorrupcion")];
$config	= FMT\Configuracion::instancia();
$exportacion = new \FMT\Template(TEMPLATE_PATH.'/legajos/exportar_anticorrupcion_pdf.html', $vars_template,['CLEAN'=>false]);
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('historial_anticorrupcion_ajax.js');
$vars_vista['CSS_FILES'][]	= ['CSS_FILE' =>    $config['app']['endpoint_cdn'].'/datatables/1.10.12/estilos.min.css'];
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('script.js');

$vars_vista['CONTENT'] = "$exportacion";

$vista->add_to_var('vars',$vars_vista);
return true;
