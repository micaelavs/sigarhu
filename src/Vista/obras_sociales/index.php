<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use \App\Helper\Vista;

$vars_template = [];
$vars_vista['SUBTITULO'] = 'Lista de Obras Sociales';
$vars_template['TITULOS'] = [
    ['TITULO' => 'Código'],
    ['TITULO' => 'Nombre'],
    ['TITULO' => 'Acciones'],
  ];

foreach ($obra_social as $os) {
	
    $vars_template['ROW'][] =
        ['COL' => [
        ['CONT' => $os['codigo']],
        ['CONT' => $os['nombre']],
        ['CONT' => '<span class="acciones">
					<a href="'.\App\Helper\Vista::get_url("index.php/obras_sociales/modificacion/{$os['id']}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Modificar" data-toggle="modal"><i class="fa fa-pencil"></i></a> 
					<a href="'.\App\Helper\Vista::get_url("index.php/obras_sociales/baja/{$os['id']}").'" class="borrar" data-user="" data-toggle="tooltip" data-placement="top" title="Eliminar" target="_self"><i class="fa fa-trash"></i></a>
					</span>']
				
        ], 
      ];
 }

$endpoint_cdn	= $vista->getSystemConfig()['app']['endpoint_cdn'];
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('obras_sociales.js');
$vars_vista['CSS_FILES'][]['CSS_FILE'] = $endpoint_cdn."/datatables/1.10.12/datatables.min.css";
$vars_vista['JS_FILES'][]['JS_FILE']  = $endpoint_cdn."/datatables/1.10.12/datatables.min.js";	
$vars_vista['JS_FILES'][]['JS_FILE']  = $endpoint_cdn."/datatables/defaults.js";
$vars_template['URL_BASE'] = \App\Helper\Vista::get_url();
$vars_template['LINK'] = \App\Helper\Vista::get_url('index.php/obras_sociales/alta');
$vars_template['DATOS_TABLA'][]=  new \FMT\Template(TEMPLATE_PATH.'/tabla.html', $vars_template,['CLEAN'=>false]) ;
$obras_sociales = new \FMT\Template(TEMPLATE_PATH.'/obras_sociales/obras_sociales.html',$vars_template,['CLEAN'=>false]);
$vars_vista['CONTENT'] = "{$obras_sociales}";

$vars_vista['JS'][]['JS_CODE']  = <<<JS
var \$endpoint_cdn = '{$endpoint_cdn}';
JS;

$vista->add_to_var('vars',$vars_vista);
return true;
