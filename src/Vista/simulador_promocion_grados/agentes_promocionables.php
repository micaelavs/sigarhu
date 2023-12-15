<?php
use \App\Helper\Vista;
$config	= FMT\Configuracion::instancia();

$vars_template = [];
$vars_vista['SUBTITULO']	= 'Lista de Simulación de Promoción de Grado';
$vars_template['CLASS']		= 'tabla_credito_promocion';
$vars_template['TITULOS']	= [
	['TITULO' => 'Cuit'],
	['TITULO' => 'Nombre y Apellido'],
	['TITULO' => 'Nivel'],
	['TITULO' => 'Grado'],
	['TITULO' =>'Agrupamiento'],
	['TITULO'=>'Tramo'],
	['TITULO'=>'Fecha ultima promoción'],
    ['TITULO' => 'Acciones']
];

foreach ($simulacion_agente as $td) {
    $accion = '<div class="btn-group btn-group-sm"><a href="'.Vista::get_url('index.php/SimuladorPromocionGrados/listado_simulacion_promocion_grado/'.$td['id'])
    .'" class="btn btn-link btn-sm" data-toggle="tooltip" title="Ver Simulación" data-original-title="Ver Simulación"><i class="fa fa-eye"></i></a></div>';

    $vars_template['ROW'][] =
        ['COL' => [
            ['CONT' => $td['cuit']],
            ['CONT' => $td['nombre'].' '.$td['apellido']],
            ['CONT' => $td['nombre_nivel']],
            ['CONT' => $td['nombre_grado']],
            ['CONT' => $td['nombre_agrupamiento']],
            ['CONT' => $td['nombre_tramo']],
            ['CONT' => empty($td['fecha_ultima_promocion_grado'])
                ? 'Sin Datos' : date('d/m/Y',strtotime($td['fecha_ultima_promocion_grado']))],
            ['CONT' => $accion],
        ],
    ];
}

$vars_vista['JS_FOOTER'][]['JS_SCRIPT']	= \App\Helper\Vista::get_url('script.js');
$vars_vista['CSS_FILES'][]['CSS_FILE']	= $config['app']['endpoint_cdn'].'/datatables/1.10.12/datatables.min.css';
$vars_vista['JS_FILES'][]['JS_FILE']	= $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js";
$vars_vista['JS_FILES'][]['JS_FILE']	= $config['app']['endpoint_cdn']."/datatables/defaults.js";
$vars_template['URL_BASE']				= \App\Helper\Vista::get_url();
$vars_template['LINK']					= \App\Helper\Vista::get_url('index.php/SimuladorPromocionGrados/ejecutar_simulador');

$vars_template['DATOS_TABLA'][] 		= new \FMT\Template(TEMPLATE_PATH.'/tabla.html', $vars_template,['CLEAN'=>false]) ;
$html                           		= new \FMT\Template(TEMPLATE_PATH.'/simulador_promocion_grados/agentes_promocionables.html',$vars_template,['CLEAN'=>false]);
$vars_vista['CONTENT']          		= "{$html}";
$vista->add_to_var('vars',$vars_vista);