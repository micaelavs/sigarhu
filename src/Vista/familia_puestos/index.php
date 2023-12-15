<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use \App\Helper\Vista;
$config	= \FMT\Configuracion::instancia();
$vars_template = [];
$vars_vista['SUBTITULO'] = 'Lista de Familias de Puestos';
$vars_template['TITULOS'] = [
    ['TITULO' => 'Nombre'],
    ['TITULO' => 'Acciones'],
  ];

foreach ($familia_puesto as $fp) {
	
    $vars_template['ROW'][] =
        ['COL' => [
        ['CONT' => $fp['nombre']],
        ['CONT' => '<span class="acciones">
					<a href="'.\App\Helper\Vista::get_url("index.php/puestos/modificacion_familia_puesto/{$fp['id']}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Modificar" data-toggle="modal"><i class="fa fa-pencil"></i></a> 
					<a href="'.\App\Helper\Vista::get_url("index.php/puestos/baja_familia_puesto/{$fp['id']}").'" class="borrar" data-user="" data-toggle="tooltip" data-placement="top" title="Eliminar" target="_self"><i class="fa fa-trash"></i></a>
					</span>']
				
        ], 
      ];
 }
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('familia_puestos.js');
$vars_vista['CSS_FILES'][]['CSS_FILE'] = $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.css";
$vars_vista['JS_FILES'][]['JS_FILE']  = $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js";	
$vars_vista['JS_FILES'][]['JS_FILE']  = $config['app']['endpoint_cdn']."/datatables/defaults.js";
$vars_template['URL_BASE'] = \App\Helper\Vista::get_url();
$vars_template['LINK'] = \App\Helper\Vista::get_url('index.php/puestos/alta_familia_puesto');
$vars_template['DATOS_TABLA'][]=  new \FMT\Template(TEMPLATE_PATH.'/tabla.html', $vars_template,['CLEAN'=>false]) ;
$familia_puestos = new \FMT\Template(TEMPLATE_PATH.'/familia_puestos/familia_puestos.html',$vars_template,['CLEAN'=>false]);
$vars_vista['CONTENT'] = "{$familia_puestos}";

$vista->add_to_var('vars',$vars_vista);
return true;
