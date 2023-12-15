<?php
use App\Helper\Vista;
use App\Modelo\Documento;
$vars_template = [];
$vars_vista['SUBTITULO'] = 'Historial de Horas Extras';
$vars_template['TITULOS'] = [
    ['TITULO' => 'Año'], 
    ['TITULO' => 'Mes'],
    ['TITULO' => 'Acto Administrativo'],
    ['TITULO' => 'Acciones'],
  ];
foreach ($horas_extras as $key => $value) {
    $extra = \DateTime::createFromFormat('d/m/Y',"1/{$value['mes']}/{$value['anio']}");
    $mes_actual = new \DateTime();
    $mes_actual->setDate(date('Y'),date('m'),'1');
    $modifica ='';
    if($extra >= $mes_actual) {
      $modifica = '<a href="'.\App\Helper\Vista::get_url("index.php/legajos/update_hora_extra/{$value['id']}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Modificar" data-toggle="modal"><i class="fa fa-pencil"></i></a>';
    }  
    $vars_template['ROW'][] =
        ['COL' => [
        ['CONT' => $value['anio']],
        ['CONT' => $value['mes']],
        ['CONT' => $value['acto_administrativo']],
        ['CONT' => '
          <span class="acciones">
          '.$modifica.'
          <a href="'.\App\Helper\Vista::get_url("index.php/legajos/baja_hora_extra/{$value['id']}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Eliminar" data-toggle="modal"><i class="fa fa-trash"></i></a>
          </span> 
          ']
        ],  
      ];  
  }
$config	= FMT\Configuracion::instancia();  
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']  = Vista::get_url('horas_extras.js');
$vars_vista['CSS_FILES'][]  = ['CSS_FILE' => \App\Helper\Vista::get_url('legajos.css')];
$vars_vista['CSS_FILES'][]  = ['CSS_FILE' => $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.css"];
$vars_vista['JS_FILES'][] = ['JS_FILE'  => $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js"];  
$vars_vista['JS_FILES'][] = ['JS_FILE'  => $config['app']['endpoint_cdn']."/datatables/defaults.js"];
$vars_vista['JS_FILES'][] = ['JS_FILE' => $config['app']['endpoint_cdn']."/datatables/1.10.12/plugins/sorting/datetime-moment.js"];


$vars_template= [
  'DATOS_TABLA' => new \FMT\Template(TEMPLATE_PATH.'/tabla.html', $vars_template,['CLEAN'=>false]),
  'VOLVER' => Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}"),
  'BLOQUE' => \App\Helper\Bloques::ADMINISTRACION
];		
$horas_extras = new \FMT\Template(TEMPLATE_PATH.'/legajos/lista_horas_extras.html', $vars_template, ['CLEAN'=>false]);
$vars_vista['CONTENT'] = "{$horas_extras}";

$vista->add_to_var('vars',$vars_vista);
return true;