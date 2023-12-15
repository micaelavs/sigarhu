<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
$vars_template = [];
$vars_vista['SUBTITULO'] = 'Creditos';
$vars_template['TITULOS'] = [
    ['TITULO' => 'Fecha'], 
    ['TITULO' => 'Creditos'],
    ['TITULO' => 'Acto Administrativo'],
    ['TITULO' => 'Archivo'],

  ];

foreach ($historial as $key => $cred) {
	$ver = '';
    $modifica ='';  
    if($key == '0') {
      $modifica = '<a href="'.\App\Helper\Vista::get_url("index.php/legajos/modificar_titulo_creditos/{$cred['id']}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Modificar" data-toggle="modal"><i class="fa fa-pencil"></i></a>';
    }
    if($cred['archivo']){
      $ver ='<a href="'.\App\Helper\Vista::get_url("index.php/legajos/mostrar_titulo_credito/{$cred['id']}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Eliminar" data-toggle="modal"><i class="fa fa-eye"></i></a>';
    }
	$vars_template['TITULO'] = $cred['nombre_titulo'];
    $vars_template['ROW'][$key] =
        ['COL' => [
        ['CONT' => $cred['fecha']->format('d/m/Y')],
        ['CONT' => $cred['creditos'].' %'],
        ['CONT' => $cred['acto_administrativo']],
        ['CONT' => $cred['archivo']],
        ['CONT' => '
          <span class="acciones">'.$ver.$modifica.'
          </span> 
          ']
        ], 
      ];   
 }
$vars_template['INFO_A'][0] = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit,'DENOMINACION' =>  $puesto];
$vars_template['BOTON_VOLVER'][] = ['VOLVER' => \App\Helper\Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}") , 'BLOQUE' =>\App\Helper\Bloques::FORMACION, 'ID' => "volver_legajo", 'CLASS' => "volver_legajo btn btn-default", 'HREF' => "#"]; 
$vars_template['TITULOS'][]['TITULO'] ='Acciones';
if (!$titulo_completo) {
	$vars_template['BOTON_NUEVO'][] = ['LINK' => \App\Helper\Vista::get_url("index.php/legajos/alta_titulo_creditos/{$persona_titulo->id}")];  
}
 
$vars_vista['CSS_FILES'][]  = ['CSS_FILE' => \App\Helper\Vista::get_url('legajos.css')];
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']   = \App\Helper\Vista::get_url('creditos.js');
$vars_template['TABLA'][]=  new \FMT\Template(TEMPLATE_PATH.'/tabla.html', $vars_template,['CLEAN'=>false]) ;
$historial_creditos = new \FMT\Template(TEMPLATE_PATH.'/legajos/historial_creditos.html', $vars_template, ['CLEAN'=>false]);
$vars_vista['CONTENT'] = "{$historial_creditos}";
$vista->add_to_var('vars',$vars_vista);

return true;