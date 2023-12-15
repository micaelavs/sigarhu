<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
if($permisos['bloque_embargo']) {
  $vars_template = [];
  $vars_template['TITULOS'] = [
      ['TITULO' => 'Tipo Embargo'],
      ['TITULO' => 'Autos'],
      ['TITULO' => 'Fecha Alta'],
      ['TITULO' => 'Fecha Cancelación'],
      ['TITULO' => 'Monto'],

    ];

  foreach ($lista_embargo as $key => $em) {
      ($em['tipo_embargo'] == 'Ejecutivo') ? $signo = ' $' : $signo = ' %';
      $vars_template['ROW'][$key] =
          ['COL' => [
          ['CONT' => $em['tipo_embargo']],
          ['CONT' => $em['autos']],
          ['CONT' => $em['fecha_alta']],
          ['CONT' => $em['fecha_cancelacion']],
          ['CONT' => $em['monto'].$signo],
          ],
        ];
      if($permisos['embargo']) {
         $acciones = '
            <span class="acciones">
            <a href="'.\App\Helper\Vista::get_url("index.php/legajos/modificar_embargo/{$em['id']}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Modificar" data-toggle="modal"><i class="fa fa-pencil"></i></a>
            <a href="'.\App\Helper\Vista::get_url("index.php/legajos/baja_embargo/{$em['id']}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Eliminar" data-toggle="modal"><i class="fa fa-trash"></i></a>
            </span>
            ';
            $vars_template['ROW'][$key]['COL'][]['CONT'] = $acciones;
      }
   }

  if(!empty($lista_historial_embargo)) {
     $vars_template['HISTORIAL'][] = ['URL_HISTORIAL' => \App\Helper\Vista::get_url("index.php/legajos/historial_embargo/{$empleado->cuit}")];
    }
  if($permisos['embargo']) {
    $vars_template['TITULOS'][]['TITULO'] ='Acciones';
    $vars_template['BOTON_NUEVO'][] = ['LINK' => \App\Helper\Vista::get_url("index.php/legajos/alta_embargo/{$empleado->cuit}"), ];
  }
  $vars_template['BOTON_VOLVER'][] = ['VOLVER' => \App\Helper\Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}"), 'CLASS' => "btn btn-default"];
  $vars_template['TABLA'][]=  new \FMT\Template(TEMPLATE_PATH.'/tabla.html', $vars_template,['CLEAN'=>false]) ;
  $embargos = new \FMT\Template(TEMPLATE_PATH.'/legajos/embargo.html', $vars_template, ['CLEAN'=>false]);
}