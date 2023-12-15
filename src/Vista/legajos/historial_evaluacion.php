<?php
use App\Helper\Vista;
use App\Modelo\Documento;
$vars_template = [];
$vars_vista['SUBTITULO']		= 'Historial de Evaluaciones';
$vars_template['CLASS']			= 'historial_evaluaciones';

$vars_vista['JS'][]['JS_CODE']	= <<<JS
	\$data_table_init	= '{$vars_template['CLASS']}';
JS;

$vars_template['TITULOS'] = [
    ['TITULO' => 'ID'],
    ['TITULO' => 'Resultado', 
		'DATA'	=> 'data-target="evaluaciones_resultado" data-width="15%"'],
    ['TITULO' => 'Año', 
		'DATA'	=> 'data-target="evaluaciones_anio" data-width="7%" data-orderable="true"'],
    ['TITULO' => 'Formulario', 
		'DATA'	=> 'data-target="evaluaciones_formulario" data-width="15%"'],
    ['TITULO' => 'Acto Administrativo', 
		'DATA'	=> 'data-target="evaluaciones_acto_administrativo" data-width="20%"'],
    ['TITULO' => 'Bonifica', 
		'DATA'	=> 'data-target="evaluaciones_bonifica" data-width="7%"'],
    ['TITULO' => 'Archivo', 
		'DATA'	=> 'data-target="evaluaciones_archivo"'],
    ['TITULO' => 'Acciones', 
		'DATA'	=> 'data-target="evaluaciones_acciones" data-width="5%" data-orderable="false"'],
  ];

foreach ($evaluaciones as $key => $pres) {
    $ver		= '';
    $modifica	= '';  
    if($key == '0') {
	  $modifica	= '<a href="'
		  .\App\Helper\Vista::get_url("index.php/legajos/update_evaluacion/{$pres->id}")
		  .'" data-toggle="tooltip" data-placement="top" data-id="" title="Modificar" data-toggle="modal"><i class="fa fa-pencil"></i></a>';
    }
    if($pres->archivo){
	  $ver	= '<a href="'
		  .\App\Helper\Vista::get_url("index.php/legajos/mostrar_evaluacion/{$pres->id}")
		  .'" data-toggle="tooltip" data-placement="top" data-id="" title="Ver archivo" data-toggle="modal"  target="_blank"><i class="fa fa-eye"></i></a>';
    }

    $vars_template['ROW'][]	= ['COL'	=> [
        ['CONT' => $pres->id],
        ['CONT' => \FMT\Helper\Arr::get($resultados,$pres->evaluacion) ? $resultados[$pres->evaluacion]['nombre'] :''],
        ['CONT' => $pres->anio],
        ['CONT' => \FMT\Helper\Arr::get($formularios,$pres->formulario) ? $formularios[$pres->formulario]['nombre'] :''],
        ['CONT' => $pres->acto_administrativo],
        ['CONT' => empty($pres->bonificado) ? 'NO' : 'SI'],
        ['CONT' => preg_replace("/\d{14}_/", "", $pres->archivo)],
        ['CONT' => '
          <span class="acciones">'.$ver.$modifica.'
          </span> 
          ']
        ],  
      ];  
  }

$endpoint_cdn	= $vista->getSystemConfig()['app']['endpoint_cdn'];  
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']  = Vista::get_url('script.js');
$vars_vista['CSS_FILES'][]  = ['CSS_FILE' => $endpoint_cdn.'/datatables/1.10.12/datatables.min.css'];
$vars_vista['JS_FILES'][] = ['JS_FILE'  => $endpoint_cdn."/datatables/1.10.12/datatables.min.js"];  
$vars_vista['JS_FILES'][] = ['JS_FILE'  => $endpoint_cdn."/datatables/defaults.js"];
$vars_vista['JS_FILES'][] = ['JS_FILE' => $endpoint_cdn."/datatables/1.10.12/plugins/sorting/datetime-moment.js"];
$vars_template= [
  'DATOS_TABLA' => new \FMT\Template(TEMPLATE_PATH.'/tabla.html', $vars_template,['CLEAN'=>false])
];
$evaluaciones = new \FMT\Template(TEMPLATE_PATH.'/legajos/lista_evaluaciones.html', $vars_template, ['CLEAN'=>false]);
$bloque =\App\Helper\Bloques::PERFILES_PUESTO;
$ref_v = Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");
$vars['CLASS_COL'] = 'col-md-12';
$vars['BOTON_VOLVER'][] = ['CLASS'=>'volver_legajo btn-default','NOMBRE' => 'VOLVER','ID' => 'volver_legajo', 'EXTRAS' => " data-ref='{$ref_v}' data-bloque='{$bloque}'", 'HTTP' => '#']; 

$botones = (new \FMT\Template(VISTAS_PATH.'/widgets/botonera.html',$vars));
$vars_vista['CONTENT'] = "{$evaluaciones}{$botones}";

$vista->add_to_var('vars',$vars_vista);
return true;
