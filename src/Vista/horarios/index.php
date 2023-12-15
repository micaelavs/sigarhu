<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use \App\Helper\Vista;
$config	= \FMT\Configuracion::instancia();
$vars_template = [];
$vars_vista['SUBTITULO'] = 'Lista de Plantillas Horarias';
$vars_template['TITULOS'] = [
    ['TITULO' => 'Nombre'],
    ['TITULO' => 'Día Desde'],
    ['TITULO' => 'Día Hasta'],
    ['TITULO' => 'Horario Desde'],
    ['TITULO' => 'Horario Hasta'],
    ['TITULO' => 'Acciones'],
  ];
foreach ($horarios as $h) {
    $vars_template['ROW'][] =
        ['COL' => [
        ['CONT' => $h['nombre']],
        ['CONT' => !is_null($h['dia_desde']) ? $dias[$h['dia_desde']]['dia'] : '' ],
        ['CONT' => !is_null($h['dia_hasta']) ? $dias[$h['dia_hasta']]['dia'] : '' ],
        ['CONT' => $h['hora_desde']],
        ['CONT' => $h['hora_hasta']],
        ['CONT' => '<span class="acciones">
					<a href="'.\App\Helper\Vista::get_url("index.php/horarios/modificacion/{$h['id']}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Modificar" data-toggle="modal"><i class="fa fa-pencil"></i></a> 
					<a href="'.\App\Helper\Vista::get_url("index.php/horarios/baja/{$h['id']}").'" class="borrar" data-user="" data-toggle="tooltip" data-placement="top" title="Eliminar" target="_self"><i class="fa fa-trash"></i></a>
					</span>']
				
        ], 
      ];
 }
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('lista_horarios.js');
$vars_vista['CSS_FILES'][]['CSS_FILE'] = $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.css";
$vars_vista['JS_FILES'][]['JS_FILE']  = $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js";	
$vars_vista['JS_FILES'][]['JS_FILE']  = $config['app']['endpoint_cdn']."/datatables/defaults.js";
$vars_template['URL_BASE'] = \App\Helper\Vista::get_url();
$vars_template['LINK'] = \App\Helper\Vista::get_url('index.php/horarios/alta');
$vars_template['DATOS_TABLA'][]=  new \FMT\Template(TEMPLATE_PATH.'/tabla.html', $vars_template,['CLEAN'=>false]) ;
$horarios = new \FMT\Template(TEMPLATE_PATH.'/horarios/horarios.html',$vars_template,['CLEAN'=>false]);
$vars_vista['CONTENT'] = "{$horarios}";
$vista->add_to_var('vars',$vars_vista);
return true;