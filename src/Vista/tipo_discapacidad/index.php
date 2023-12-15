<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use \App\Helper\Vista;

$vars_template = [];
$vars_vista['SUBTITULO'] = 'Lista de Tipos de Discapacidad';
$vars_template['TITULOS'] = [
    ['TITULO' => 'Tipo de Discapacidad'],
    ['TITULO' => 'Descripción'],
    ['TITULO' => 'Acciones'],
  ];

foreach ($tipo_discapacidad as $td) {
	
    $vars_template['ROW'][] =
        ['COL' => [
        ['CONT' => $td['nombre']],
        ['CONT' => $td['descripcion']],
        ['CONT' => '<span class="acciones">
					<a href="'.\App\Helper\Vista::get_url("index.php/tipo_discapacidad/modificacion/{$td['id']}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Modificar" data-toggle="modal"><i class="fa fa-pencil"></i></a> 
					<a href="'.\App\Helper\Vista::get_url("index.php/tipo_discapacidad/baja/{$td['id']}").'" class="borrar" data-user="" data-toggle="tooltip" data-placement="top" title="Eliminar" target="_self"><i class="fa fa-trash"></i></a>
					</span>']
				
        ], 
      ];
 }
 $config	= FMT\Configuracion::instancia(); 
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('tipo_discapacidad.js');
$vars_vista['CSS_FILES'][]['CSS_FILE'] = $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.css";
$vars_vista['JS_FILES'][]['JS_FILE']  = $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js";	
$vars_vista['JS_FILES'][]['JS_FILE']  = $config['app']['endpoint_cdn']."/datatables/defaults.js";
$vars_template['URL_BASE'] = \App\Helper\Vista::get_url();
$vars_template['LINK'] = \App\Helper\Vista::get_url('index.php/tipo_discapacidad/alta');
$vars_template['DATOS_TABLA'][]=  new \FMT\Template(TEMPLATE_PATH.'/tabla.html', $vars_template,['CLEAN'=>false]) ;
$tipo_discapacidad = new \FMT\Template(TEMPLATE_PATH.'/tipo_discapacidad/tipoDiscapacidad.html',$vars_template,['CLEAN'=>false]);
$vars_vista['CONTENT'] = "{$tipo_discapacidad}";

$vista->add_to_var('vars',$vars_vista);
return true;
