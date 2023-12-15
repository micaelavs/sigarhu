<?php
$vars_template        = [];
$vars_vista           = [];
$tabla_vars_template  = [];
$vars_vista['SUBTITULO'] = 'Listado simulación de promoción de grado';

$tabla_vars_template['TITULOS'] = [
    ['DATA'=>'', 'TITULO' => 'grupo_incremental'], 
    ['DATA'=>'', 'TITULO' => 'Cuit'], 
    ['DATA'=>'', 'TITULO' => 'Nombre y Apellido'], 
    ['DATA'=>'', 'TITULO' => 'Nivel'],
    ['DATA'=>'', 'TITULO' => 'Grado'],
    ['DATA'=>'', 'TITULO' => 'Agrupamiento'],
    ['DATA'=>'', 'TITULO' => 'Tramo'],
    ['DATA'=>'', 'TITULO' => 'Última promoción de Grado'],
    ['DATA'=>'', 'TITULO' => 'Grado en Análisis'],
    ['DATA'=>'', 'TITULO' => 'Periodo'],
    ['DATA'=>'', 'TITULO' => 'Evaluación'],
    ['DATA'=>'', 'TITULO' => 'Bonificado'],
    ['DATA'=>'', 'TITULO' => 'Última situación de revista'],
    ['DATA'=>'', 'TITULO' => 'Último Tramo'],
    ['DATA'=>'', 'TITULO' => 'Créditos requeridos'],
    ['DATA'=>'', 'TITULO' => 'Creditos Acumulados'],
    ['DATA'=>'', 'TITULO' => '% Acumulado de títulos'],
    ['DATA'=>'', 'TITULO' => 'Promociona'],
    ['DATA'=>'', 'TITULO' => 'Motivo'],
    ['DATA'=>'', 'TITULO' => 'id_empleado'],
  ];

foreach ($listadoSimulacion as &$item) { 
  $tabla_vars_template['ROW'][] =
      ['COL' => [
        ['EXTRAS' => '', 'CONT' => $item['grupo_incremental']],
        ['EXTRAS' => '', 'CONT' => $empleado->cuit],
        ['EXTRAS' => '', 'CONT' => $empleado->persona->apellido.' '.$empleado->persona->nombre],
        ['EXTRAS' => '', 'CONT' => $item['nivel']],
        ['EXTRAS' => '', 'CONT' => $item['grado']],
        ['EXTRAS' => '', 'CONT' => $item['agrupamiento']],
        ['EXTRAS' => '', 'CONT' => $item['tramo']],
        ['EXTRAS' => '', 'CONT' => date("d/m/Y", strtotime($item['fecha_ultima_promocion']))],
        ['EXTRAS' => '', 'CONT' => $item['grado_analisis']],
        ['EXTRAS' => '', 'CONT' => $item['anio']],
        ['EXTRAS' => '', 'CONT' => \FMT\Helper\Arr::get($resultados,$item['id_calificacion']) ? $resultados[$item['id_calificacion']]['nombre'] :'SIN DATA'],
        ['EXTRAS' => '', 'CONT' => $item['bonificado']],
        ['EXTRAS' => '', 'CONT' => $item['situacion_revista']],
        ['EXTRAS' => '', 'CONT' => $item['ultimo_tramo']],
        ['EXTRAS' => '', 'CONT' => $item['creditos_requeridos']],
        ['EXTRAS' => '', 'CONT' => $item['creditos_acumulados']],
        ['EXTRAS' => '', 'CONT' => $item['porcentaje_acumulado_titulos']],
        ['EXTRAS' => '', 'CONT' => $item['promociona']],
        ['EXTRAS' => '', 'CONT' => \FMT\Helper\Arr::get($motivos,$item['motivo']) ? $motivos[$item['motivo']]['nombre'] :'SIN DATA'],
        ['EXTRAS' => '', 'CONT' => $item['id_empleado']],
        ],  
      ];  
} 

$endpoint_cdn = $vista->getSystemConfig()['app']['endpoint_cdn'];
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']	= \App\Helper\Vista::get_url('script.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']	= \App\Helper\Vista::get_url('listado_simulacion_promocion_grado.js');
$vars_vista['CSS_FILES'][] 				= ['CSS_FILE' => $endpoint_cdn.'/datatables/1.10.12/datatables.min.css'];
$vars_vista['JS_FILES'][]				= ['JS_FILE' => $endpoint_cdn."/datatables/1.10.12/datatables.min.js"];  
$vars_vista['JS_FILES'][]				= ['JS_FILE' => $endpoint_cdn."/datatables/1.10.12/dataTables.rowGroup.min.js"]; 
$vars_vista['JS_FILES'][]				= ['JS_FILE' => $endpoint_cdn."/datatables/defaults.js"];
$vars_vista['JS_FILES'][]				= ['JS_FILE' => $endpoint_cdn."/datatables/1.10.12/plugins/sorting/datetime-moment.js"];

$vars_template['INFO_A'][0]				= ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit];
$vars_template['BOTON_VOLVER'][]		= [
	'VOLVER' => \App\Helper\Vista::get_url("index.php/SimuladorPromocionGrados/agentes_promocionables"),
	'BLOQUE' =>\App\Helper\Bloques::FORMACION, 
	'ID' => "volver_legajo", 
	'CLASS' => "volver_legajo btn btn-default", 
	'HREF' => \App\Helper\Vista::get_url("index.php/SimuladorPromocionGrados/agentes_promocionables"),
]; 
 
$base_url						= \App\Helper\Vista::get_url('index.php');
$vars_vista['JS'][]['JS_CODE']	= <<<JS
    var \$base_url = "{$base_url}";
JS;
$tabla_vars_template			+= ['CLASS' => 'tabla_listado_simulacion'];
$tabla		                = new \FMT\Template(TEMPLATE_PATH.'/tabla.html', $tabla_vars_template,['CLEAN'=>true]);
$vars_template['TABLA']		= "{$tabla}";

$listado_simulacion				= new \FMT\Template(TEMPLATE_PATH.'/simuladorPromocionGrados/listado_simulacion_promocion_grado.html', $vars_template, ['CLEAN'=>false]);
$vars_vista['CONTENT']			= "{$listado_simulacion}";
$vista->add_to_var('vars',$vars_vista);

// return true;