<?php
use \FMT\Helper\Template;
$vars_template = [];
$vars_vista['SUBTITULO'] = 'Historial de de Cursos';
$vars_template['CLASS'] = 'tabla_historial_cursos';

$vars_vista['JS'][]['JS_CODE']	= <<<JS
	\$data_table_init	= '{$vars_template['CLASS']}';
JS;

$vars_template['TITULOS'] = [
    ['TITULO' => 'Codigo', 'DATA' => 'data-target="curso_codigo" data-width="10%"'], 
    ['TITULO' => 'Título de Curso', 'DATA' => 'data-target="curso_titulo"'],
    ['TITULO' => 'Créditos', 'DATA' => 'data-target="curso_creditos" data-width="10%"'],
    ['TITULO' => 'Fecha', 'DATA' => 'data-target="curso_fecha" data-width="10%" data-orderable="true"'],
    ['TITULO' => 'Aplica para Tramo', 'DATA' => 'data-target="curso_tramo" data-width="8%"'],
    ['TITULO' => 'Acciones', 'DATA' => 'data-target="curso_acciones" data-orderable="false" data-width="5%"'],
  ];
  
foreach ($cursos_emple as $key => $curso_e) {
  $modifica ='';  
  $modifica = '<a href="'.\App\Helper\Vista::get_url("index.php/legajos/modificar_curso/{$curso_e->id}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Modificar" data-toggle="modal"><i class="fa fa-pencil"></i></a>';
  $elimina = '<a href="'.\App\Helper\Vista::get_url("index.php/legajos/baja_curso/{$curso_e->id}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Baja" data-toggle="modal"><i class="fa fa-trash"></i></a>';
  $aplica = '<span data-toggle="tooltip" data-placement="top" data-id="" title="Aplica para Tramo" data-toggle="modal"><i class="fa fa-check"></i></span>';
   $no_aplica = '<span data-toggle="tooltip" data-placement="top" data-id="" title="No aplica para Tramo" data-toggle="modal"><i class="fa fa-times"></i></span>';
  $vars_template['ROW'][] =
      ['COL' => [
        ['EXTRAS' => '','CONT' => \FMT\Helper\Arr::get($cursos,$curso_e->id_curso) ? $cursos[$curso_e->id_curso]->codigo :''],
        ['EXTRAS' => '','CONT' => \FMT\Helper\Arr::get($cursos,$curso_e->id_curso) ? $cursos[$curso_e->id_curso]->nombre_curso :''],
        ['EXTRAS' => '','CONT' => \FMT\Helper\Arr::get($cursos,$curso_e->id_curso) ? $cursos[$curso_e->id_curso]->creditos :''],
        ['EXTRAS' => '','CONT' => $curso_e->fecha->format('d/m/Y')],
        ['EXTRAS' => '','CONT' => ($curso_e->tipo_promocion== \App\Modelo\Curso::PROMOCION_TRAMO) ? '<span class="acciones">'.$aplica.'</span> ' : '<span class="acciones">'.$no_aplica.'</span> '],
        ['EXTRAS' => '','CONT' => '<span class="acciones">'.$modifica.$elimina.'</span> ']
        ],  
      ];  

}

$endpoint_cdn	= $vista->getSystemConfig()['app']['endpoint_cdn'];
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']  =  \App\Helper\Vista::get_url('script.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']   = \App\Helper\Vista::get_url('legajos_historial_cursos.js');
$vars_vista['CSS_FILES'][]  = ['CSS_FILE' => $endpoint_cdn.'/datatables/1.10.12/datatables.min.css'];
$vars_vista['JS_FILES'][] = ['JS_FILE'  => $endpoint_cdn."/datatables/1.10.12/datatables.min.js"];  
$vars_vista['JS_FILES'][] = ['JS_FILE'  => $endpoint_cdn."/datatables/defaults.js"];
$vars_vista['JS_FILES'][] = ['JS_FILE' => $endpoint_cdn."/datatables/1.10.12/plugins/sorting/datetime-moment.js"];

$vars_template['INFO_A'][0] = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit];
$vars_template['BOTON_VOLVER'][] = ['VOLVER' => \App\Helper\Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}") , 'BLOQUE' =>\App\Helper\Bloques::FORMACION, 'ID' => "volver_legajo", 'CLASS' => "volver_legajo btn btn-default", 'HREF' => "#"]; 

$vars_template['BOTON_NUEVO'][] = ['LINK' => \App\Helper\Vista::get_url("index.php/legajos/alta_curso/{$empleado->cuit}")];  
 
$vars_vista['CSS_FILES'][]  = ['CSS_FILE' => \App\Helper\Vista::get_url('legajos.css')];
$tabla_vars_template  = $vars_template;
$vars_template['TABLA'][]=  new \FMT\Template(TEMPLATE_PATH.'/tabla.html', $tabla_vars_template,['CLEAN'=>false]) ;
$historial_cursos = new \FMT\Template(TEMPLATE_PATH.'/legajos/historial_cursos.html', $vars_template, ['CLEAN'=>false]);
$vars_vista['CONTENT'] = "{$historial_cursos}";
$vista->add_to_var('vars',$vars_vista);

return true;