<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use \App\Helper\Vista;

$vars_template = [];
$vars_vista['SUBTITULO'] = 'Lista de Sindicatos';
$vars_template['TITULOS'] = [
    ['TITULO' => 'Sigla'],
    ['TITULO' => 'Sindicatos'],
    ['TITULO' => 'Acciones'],
  ];

foreach ($sindicato as $td) {
	
    $vars_template['ROW'][] =
        ['COL' => [
        ['CONT' => $td['codigo']],
        ['CONT' => $td['nombre']],
        ['CONT' => '<span class="acciones">
					<a href="'.\App\Helper\Vista::get_url("index.php/sindicatos/modificacion/{$td['id']}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Modificar" data-toggle="modal"><i class="fa fa-pencil"></i></a> 
					<a href="'.\App\Helper\Vista::get_url("index.php/sindicatos/baja/{$td['id']}").'" class="borrar" data-user="" data-toggle="tooltip" data-placement="top" title="Eliminar" target="_self"><i class="fa fa-trash"></i></a>
					</span>']
				
        ], 
      ];
 }
$config	= FMT\Configuracion::instancia(); 
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('sindicatos.js');
$vars_vista['CSS_FILES'][]['CSS_FILE'] = $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.css";
$vars_vista['JS_FILES'][]['JS_FILE']  = $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js";	
$vars_vista['JS_FILES'][]['JS_FILE']  = $config['app']['endpoint_cdn']."/datatables/defaults.js";
$vars_template['URL_BASE'] = \App\Helper\Vista::get_url();
$vars_template['LINK'] = \App\Helper\Vista::get_url('index.php/sindicatos/alta');
$vars_template['DATOS_TABLA'][]=  new \FMT\Template(TEMPLATE_PATH.'/tabla.html', $vars_template,['CLEAN'=>false]) ;
$sindicatos = new \FMT\Template(TEMPLATE_PATH.'/sindicatos/sindicatos.html',$vars_template,['CLEAN'=>false]);
$vars_vista['CONTENT'] = "{$sindicatos}";

$vista->add_to_var('vars',$vars_vista);
return true;
