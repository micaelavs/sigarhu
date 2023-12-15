<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use \App\Helper\Vista;
$vars_template = [];
$vars_vista['SUBTITULO'] = 'Lista de Edificios';
$vars_template['TITULOS'] = [
    ['TITULO' => 'Ubicaciones Edificios'],
    ['TITULO' => 'Calle'],
    ['TITULO' => 'Número'],
    ['TITULO' => 'Localidad'],
    ['TITULO' => 'Provincia'],
    ['TITULO' => 'Código Postal'],
    ['TITULO' => 'Acciones'],
  ];
foreach ($ubicaciones_edificios as $ue) {
    $vars_template['ROW'][] =
        ['COL' => [
        ['EXTRA'=> '', 'CONT' => $ue->nombre],
        ['EXTRA'=> '', 'CONT' => $ue->calle],
        ['EXTRA'=> '', 'CONT' => $ue->numero],
        ['EXTRA'=> '', 'CONT' => json_decode(json_encode(\FMT\Ubicaciones::get_localidad($ue->id_localidad)), JSON_UNESCAPED_UNICODE)['nombre']],
        ['EXTRA'=> '', 'CONT' => ($ue->id_provincia) ? $provincias[$ue->id_provincia]['nombre'] : ''],
        ['EXTRA'=> '', 'CONT' => $ue->cod_postal],
        ['EXTRA'=> '', 'CONT' => '<span class="acciones">
					<a href="'.\App\Helper\Vista::get_url("index.php/ubicaciones_edificios/modificacion/{$ue->id}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Modificar" data-toggle="modal"><i class="fa fa-pencil"></i></a> 
					<a href="'.\App\Helper\Vista::get_url("index.php/ubicaciones_edificios/baja/{$ue->id}").'" class="borrar" data-user="" data-toggle="tooltip" data-placement="top" title="Eliminar" target="_self"><i class="fa fa-trash"></i></a>
					</span>']
				
        ], 
      ];
 }
$config = $vista->getSystemConfig();
$base_url = \App\Helper\Vista::get_url('index.php');
$vars_vista['JS'][]['JS_CODE']	= <<<JS
  var \$base_url = "{$base_url}";
JS;
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('script.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('lista_edificios.js');
$vars_vista['CSS_FILES'][]['CSS_FILE'] = $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.css";
$vars_vista['JS_FILES'][]['JS_FILE']  = $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js";	
$vars_vista['JS_FILES'][]['JS_FILE']  = $config['app']['endpoint_cdn']."/datatables/defaults.js";
$vars_template['URL_BASE'] = \App\Helper\Vista::get_url();
$vars_template['LINK'] = \App\Helper\Vista::get_url('index.php/ubicaciones_edificios/alta');
$vars_template['DATOS_TABLA'][]=  new \FMT\Template(TEMPLATE_PATH.'/tabla.html', $vars_template,['CLEAN'=>false]) ;
$ubicaciones_edificios = new \FMT\Template(TEMPLATE_PATH.'/ubicaciones_edificios/ubicacionesEdificios.html',$vars_template,['CLEAN'=>false]);
$vars_vista['CONTENT'] = "{$ubicaciones_edificios}";
$vista->add_to_var('vars',$vars_vista);
return true;