<?php
use App\Helper\Vista;
use App\Modelo\Documento;
$vars_template = [];
$vars_vista['SUBTITULO'] = 'Historial de Presentaciones';
$vars_template['TITULOS'] = [
    ['TITULO' => ''], 
    ['TITULO' => 'Tipo Presentación'], 
    ['TITULO' => 'Fecha'],
    ['TITULO' => 'Periodo'],
    ['TITULO' => 'Nº Transacción'],
    ['TITULO' => 'Archivo'],
    ['TITULO' => 'Acciones'],
  ];
  
foreach ($presentacion as $key => $pres) {
    $ver = '';
    $modifica ='';  
    if($key == '0') {
      $modifica = '<a href="'.\App\Helper\Vista::get_url("index.php/legajos/update_presentacion/{$empleado->cuit}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Modificar" data-toggle="modal"><i class="fa fa-pencil"></i></a>';
    }
    if($pres['archivo']){
      $ver ='
          <a href="'.\App\Helper\Vista::get_url("index.php/legajos/mostrar_presentacion/{$pres['id_presentacion']}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Ver archivo" data-toggle="modal"  target="_blank"><i class="fa fa-eye"></i></a>';
    }

    $vars_template['ROW'][] =
        ['COL' => [
        ['CONT' => $pres['id_tipo_presentacion']],
        ['CONT' => $pres['tipo_presentacion']],
        ['CONT' => $pres['fecha_presentacion']],
        ['CONT' => $pres['periodo']],
        ['CONT' => $pres['nro_transaccion']],
        ['CONT' => preg_replace("/\d{14}_/", "", $pres['archivo'])],
        ['CONT' => '
          <span class="acciones">'.$ver.$modifica.'
          </span> 
          ']
        ],  
      ];  
  }
$config	= FMT\Configuracion::instancia();  
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']  = Vista::get_url('presentaciones.js');
$vars_vista['CSS_FILES'][]  = ['CSS_FILE' => $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.css"];
$vars_vista['JS_FILES'][] = ['JS_FILE'  => $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js"];  
$vars_vista['JS_FILES'][] = ['JS_FILE'  => $config['app']['endpoint_cdn']."/datatables/defaults.js"];
$vars_vista['JS_FILES'][] = ['JS_FILE' => $config['app']['endpoint_cdn']."/datatables/1.10.12/plugins/sorting/datetime-moment.js"];
$vars_template= [
  'DATOS_TABLA' => new \FMT\Template(TEMPLATE_PATH.'/tabla.html', $vars_template,['CLEAN'=>false])
];
     							
$presentaciones = new \FMT\Template(TEMPLATE_PATH.'/legajos/lista_presentaciones.html', $vars_template, ['CLEAN'=>false]);
$bloque =\App\Helper\Bloques::ANTICORRUPCION;
$ref_v = Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");
$vars['CLASS_COL'] = 'col-md-12';
$vars['BOTON_VOLVER'][] = ['CLASS'=>'volver_legajo btn-default','NOMBRE' => 'VOLVER','ID' => 'volver_legajo', 'EXTRAS' => " data-ref='{$ref_v}' data-bloque='{$bloque}'", 'HTTP' => '#']; 

$botones = (new \FMT\Template(VISTAS_PATH.'/widgets/botonera.html',$vars));
$vars_vista['CONTENT'] = "{$presentaciones}{$botones}";

$vista->add_to_var('vars',$vars_vista);
return true;