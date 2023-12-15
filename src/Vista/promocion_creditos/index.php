<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use \App\Helper\Vista;
$config	= FMT\Configuracion::instancia();

$vars_template = [];
$vars_vista['SUBTITULO'] = 'Lista de Créditos para Promoción';
$vars_template['CLASS'] = 'tabla_credito_promocion';
$vars_template['TITULOS'] = [
    ['TITULO' => 'Tramo'],
    ['TITULO' => 'Agrupamiento - Nivel'],
    ['TITULO' => 'Créditos'],
    ['TITULO' => 'Fecha Vigencia'],
    ['TITULO' => 'Acciones'],
  ];

foreach ($creditos as $td) {
	
    $vars_template['ROW'][] =
        ['COL' => [
        ['EXTRAS'=>'', 'CONT' => $td['tramo']],
        ['EXTRAS'=>'', 'CONT' => $td['nivel']],
        ['EXTRAS'=>'', 'CONT' => $td['creditos']],
        ['EXTRAS'=>'', 'CONT' => $td['fecha_desde']],
        ['EXTRAS'=>'', 'CONT' => '<span class="acciones">
					<a href="'.\App\Helper\Vista::get_url("index.php/PromocionCreditos/modificacion/{$td['id']}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Modificar" data-toggle="modal"><i class="fa fa-pencil"></i></a>']
				
        ], 
      ];
 }
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('script.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('promocion_creditos.js');
$vars_vista['CSS_FILES'][]['CSS_FILE']  = $config['app']['endpoint_cdn'].'/datatables/1.10.12/datatables.min.css';
$vars_vista['JS_FILES'][]['JS_FILE']    = $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js";	
$vars_vista['JS_FILES'][]['JS_FILE']    = $config['app']['endpoint_cdn']."/datatables/defaults.js";
$vars_template['URL_BASE']              = \App\Helper\Vista::get_url();
$vars_template['LINK']                  = \App\Helper\Vista::get_url('index.php/PromocionCreditos/alta');
$vars_template['DATOS_TABLA'][]         = new \FMT\Template(TEMPLATE_PATH.'/tabla.html', $vars_template,['CLEAN'=>false]) ;
$sindicatos                             = new \FMT\Template(TEMPLATE_PATH.'/promocion_creditos/promocion_creditos.html',$vars_template,['CLEAN'=>false]);
$vars_vista['CONTENT']                  = "{$sindicatos}";
$vista->add_to_var('vars',$vars_vista);
return true;