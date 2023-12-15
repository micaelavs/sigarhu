<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use \App\Helper\Vista;

$vars_template = [];
$vars_vista['SUBTITULO'] = 'Lista de Niveles Educativos';
$vars_template['TITULOS'] = [
    ['TITULO' => 'Id'],
    ['TITULO' => 'Nivel Educativo'],
    ['TITULO' => 'Acciones'],
  ];
foreach ($nivel_educativo as $ne) {
    $vars_template['ROW'][] =
        ['COL' => [
        ['CONT' => $ne->id],
        ['CONT' => $ne->nombre],
        ['CONT' => '<span class="acciones">
					<a href="'.\App\Helper\Vista::get_url("index.php/nivel_educativo/modificacion/{$ne->id}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Modificar" data-toggle="modal"><i class="fa fa-pencil"></i></a> 
					<a href="'.\App\Helper\Vista::get_url("index.php/nivel_educativo/baja/{$ne->id}").'" class="borrar" data-user="" data-toggle="tooltip" data-placement="top" title="Eliminar" target="_self"><i class="fa fa-trash"></i></a>
					</span>']				
        ], 
      ];
 }
$config	= FMT\Configuracion::instancia(); 
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('nivel_educativo.js');
$vars_vista['CSS_FILES'][]['CSS_FILE'] = $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.css";
$vars_vista['JS_FILES'][]['JS_FILE']  = $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js";	
$vars_vista['JS_FILES'][]['JS_FILE']  = $config['app']['endpoint_cdn']."/datatables/defaults.js";
$vars_template['URL_BASE'] = \App\Helper\Vista::get_url();
$vars_template['LINK'] = \App\Helper\Vista::get_url('index.php/nivel_educativo/alta');
$vars_template['DATOS_TABLA'][]=  new \FMT\Template(TEMPLATE_PATH.'/tabla.html', $vars_template,['CLEAN'=>false]) ;
$nivel_educativo = new \FMT\Template(TEMPLATE_PATH.'/nivel_educativo/nivelEducativo.html',$vars_template,['CLEAN'=>false]);
$vars_vista['CONTENT'] = "{$nivel_educativo}";

$vars_vista['JS'][]['JS_CODE']  = <<<JS
var \$endpoint_cdn = '{$config['app']['endpoint_cdn']}';
JS;

$vista->add_to_var('vars',$vars_vista);
return true;
