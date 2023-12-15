<?php
use App\Helper\Vista;
use \FMT\Helper\Arr;

  $vars_vista['SUBTITULO'] = 'Modificar Crédito';
  $vars_template = [];
  $vars_template['OPERACION'] = 'modificacion' ;
  $vars_template = [
      'OPERACION'   => 'modificacion',
      'CUIT'  => !empty($creditos->empleado->cuit) ? $creditos->empleado->cuit : '',
      'NOMBRE_APELLIDO'    => !empty($creditos->empleado->nombre_apellido) ? $creditos->empleado->nombre_apellido : '',
      'FECHA_CONSIDERADA'     =>   !empty($creditos->fecha_considerada) ? $creditos->fecha_considerada->format('d/m/Y') : '',
      'CREDITOS'  => !empty($creditos->creditos) ? $creditos->creditos : '',
      'DESCRIPCION'    => !empty($creditos->descripcion) ? $creditos->descripcion : '',
      'DISABLED'      => 'disabled',
    ];

  $vars_vista['JS_FOOTER'][]['JS_SCRIPT']  =  \App\Helper\Vista::get_url('script.js'); //aca se dubuja el form para el boton volver
  $vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('bootstrap-typeahead.min.js');
  $vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('/creditos_iniciales.js');
  $vars_template['INFO_A'][0] = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit];

  $vars_template['BOTON_VOLVER'][] = ['VOLVER' => \App\Helper\Vista::get_url("index.php/CreditosIniciales/listar") , 'BLOQUE' =>\App\Helper\Bloques::FORMACION, 'ID' => "volver_legajo", 'CLASS' => "volver_legajo btn btn-default", 'HREF' => "#"]; 
 
  $base_url = \App\Helper\Vista::get_url('index.php');
  
  $template = new \FMT\Template(TEMPLATE_PATH.'/creditos_iniciales/alta.html', $vars_template,['CLEAN'=>false]);
  $vars_vista['CONTENT'] = "{$template}";
  $vista->add_to_var('vars',$vars_vista);
return true;
