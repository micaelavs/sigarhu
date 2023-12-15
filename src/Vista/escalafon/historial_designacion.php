<?php
use App\Helper\Vista;
use App\Modelo\Documento;
$vars_template = [];
$vars_vista['SUBTITULO'] = 'Historial de Designaciones';
$vars_template['TITULOS'] = [
    ['TITULO' => 'Tipo Designacion'],
    ['TITULO' => 'Situacion de Revista'],
    ['TITULO' => 'Fecha_desde'],
    ['TITULO' => 'Fecha_hasta'],
    ['TITULO' => 'Archivo'],
    ['TITULO' => 'Acciones'],
  ];
  
foreach ($designaciones as $key => $desig) {
    $ver = '';
    $modifica ='';  
    if($desig['archivo']){
      $ver ='
          <a href="'.\App\Helper\Vista::get_url("index.php/escalafon/mostrar_designacion/{$desig['id']}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Ver archivo" data-toggle="modal"  target="_blank"><i class="fa fa-eye"></i></a>';
    }

    $vars_template['ROW'][] =
        ['COL' => [
        ['CONT' => $desig['tipo']],
        ['CONT' => $desig['situacion_revista']],
        ['CONT' => $desig['fecha_desde']],
        ['CONT' => $desig['fecha_hasta']],
        ['CONT' => preg_replace("/\d{14}_/", "", $desig['archivo'])],
        ['CONT' => '
          <span class="acciones">'.$ver.$modifica.'
          </span> 
          ']
        ],  
      ];  
  }
$endpoint_cdn	= $vista->getSystemConfig()['app']['endpoint_cdn'];
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']  = Vista::get_url('designacion.js');
$vars_vista['CSS_FILES'][]  = ['CSS_FILE' => $endpoint_cdn.'/datatables/1.10.12/datatables.min.css'];
$vars_vista['JS_FILES'][] = ['JS_FILE'  => $endpoint_cdn."/datatables/1.10.12/datatables.min.js"];  
$vars_vista['JS_FILES'][] = ['JS_FILE'  => $endpoint_cdn."/datatables/defaults.js"];
$vars_vista['JS_FILES'][] = ['JS_FILE' => $endpoint_cdn."/datatables/1.10.12/plugins/sorting/datetime-moment.js"];
$vars_template= [
  'DATOS_TABLA' => new \FMT\Template(TEMPLATE_PATH.'/tabla.html', $vars_template,['CLEAN'=>false])
];
     							
$designacion = new \FMT\Template(TEMPLATE_PATH.'/escalafon/lista_designaciones.html', $vars_template, ['CLEAN'=>false]);
$ref_v = Vista::get_url("index.php/escalafon/designacion_transitoria");
$vars['CLASS_COL'] = 'col-md-12';
$vars['BOTON_VOLVER'][] = ['CLASS'=>'volver_legajo btn-default','NOMBRE' => 'VOLVER','ID' => 'volver_legajo', 'EXTRAS' => " data-ref='{$ref_v}' data-bloque='{$bloque}'", 'HTTP' => '#']; 

$botones = (new \FMT\Template(VISTAS_PATH.'/widgets/botonera.html',$vars));
$vars_vista['CONTENT'] = "{$designacion}{$botones}";

$vista->add_to_var('vars',$vars_vista);
return true;