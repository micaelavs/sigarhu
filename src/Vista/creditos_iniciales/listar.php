<?php
use \FMT\Helper\Template;
$vars_template = [];
$vars_vista['SUBTITULO'] = 'Créditos Iniciales';
$vars_template['CLASS'] = 'creditos_iniciales';

$vars_vista['JS'][]['JS_CODE']  = <<<JS
  \$data_table_init = '{$vars_template['CLASS']}';
JS;

$vars_template['TITULOS'] = [
    ['TITULO' => 'Cuit', 'DATA' => 'data-target="cuit" data-width="10%"'], 
    ['TITULO' => 'Nombre y Apellido', 'DATA' => 'data-target="nombre_apellido"  data-width="8%"'],
    ['TITULO' => 'Fecha considerada', 'DATA' => 'data-target="fecha" data-width="10%"'],
    ['TITULO' => 'Créditos utilizables', 'DATA' => 'data-target="creditos" data-width="10%" data-orderable="true"'],
    ['TITULO' => 'Descripción', 'DATA' => 'data-target="descripcion" data-width="10%"'],
    ['TITULO' => 'Acciones', 'DATA' => 'data-target="creditos_acciones" data-orderable="false" data-width="4%"'],
  ];
 
foreach ($listado as $key => $elem) {
  if(empty($elem->id)){
    continue;
  }
  $modifica ='';  
  $modifica = '<a href="'.\App\Helper\Vista::get_url("index.php/CreditosIniciales/modificacion/{$elem->id}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Modificar" data-toggle="modal"><i class="fa fa-pencil"></i></a>';
  $elimina = '<a href="'.\App\Helper\Vista::get_url("index.php/CreditosIniciales/baja/{$elem->id}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Baja" data-toggle="modal"><i class="fa fa-trash"></i></a>';
  $vars_template['ROW'][] =
      ['COL' => [
        ['CONT' =>$elem->empleado->cuit],
        ['CONT' =>$elem->empleado->nombre_apellido],
        ['CONT' =>($elem->fecha_considerada instanceof \DateTime) ? $elem->fecha_considerada->format('d/m/Y') : ''],
        ['CONT' =>$elem->creditos],
        ['CONT' =>$elem->descripcion],
        ['CONT' =>'<span class="acciones">'.$modifica.$elimina.'</span> ']
        ],  
      ]; 

}

$endpoint_cdn = $vista->getSystemConfig()['app']['endpoint_cdn'];
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']  =  \App\Helper\Vista::get_url('script.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']   = \App\Helper\Vista::get_url('/creditos_iniciales/creditos_iniciales.js');
$vars_vista['CSS_FILES'][]  = ['CSS_FILE' => $endpoint_cdn.'/datatables/1.10.12/datatables.min.css'];
$vars_vista['JS_FILES'][] = ['JS_FILE'  => $endpoint_cdn."/datatables/1.10.12/datatables.min.js"];  
$vars_vista['JS_FILES'][] = ['JS_FILE'  => $endpoint_cdn."/datatables/defaults.js"];
$vars_vista['JS_FILES'][] = ['JS_FILE' => $endpoint_cdn."/datatables/1.10.12/plugins/sorting/datetime-moment.js"];

$vars_template['BOTON_VOLVER'][] = ['VOLVER' => \App\Helper\Vista::get_url("index.php/legajos/gestionar/") , 'BLOQUE' =>\App\Helper\Bloques::FORMACION, 'ID' => "volver_legajo", 'CLASS' => "volver_legajo btn btn-default", 'HREF' => "#"]; 

$vars_template['BOTON_NUEVO'][] = ['LINK' => \App\Helper\Vista::get_url("index.php/CreditosIniciales/alta")];  
 
$vars_vista['CSS_FILES'][]  = ['CSS_FILE' => \App\Helper\Vista::get_url('legajos.css')];
$tabla_vars_template  = $vars_template;
$vars_template['TABLA'][]=  new \FMT\Template(TEMPLATE_PATH.'/tabla.html', $tabla_vars_template,['CLEAN'=>false]) ;
$creditos_listar = new \FMT\Template(TEMPLATE_PATH.'/creditos_iniciales/listar.html', $vars_template, ['CLEAN'=>false]);
$vars_vista['CONTENT'] = "{$creditos_listar}";
$vista->add_to_var('vars',$vars_vista);

return true;