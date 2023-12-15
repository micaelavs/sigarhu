<?php

/**
 * $vars_template |Variable de configuracion para el template de la funcionalidad que se esta desarrollando.
 * $vars_vista  |Variable de configuracion para el template general. Llega a la vista por medio de la variable "vista"
 * propagada por la clase Vista.
**/

$vars_template = [];
$vars_vista['SUBTITULO'] = 'Lista de Otros Organismos';
$vars_template['TITULOS'] = [
    		['TITULO' => 'Nombre'],
			['TITULO' => 'Tipo'],
			['TITULO' => 'Jurisdicción'],
			['TITULO' => 'Acciones']

];
foreach ($otros_organismos as $td) {
	$vars_template['ROW'][] =
	    ['COL' => [
	    ['EXTRA'=> '', 'CONT'=>$td['nombre']],
		['EXTRA'=> '', 'CONT'=>$td['nombre_tipo']],
		['EXTRA'=> '', 'CONT'=>$td['nombre_juris']],

	       ['EXTRA'=> '', 'CONT'=>['<span class="acciones"><a href="'.\App\Helper\Vista::get_url("index.php/otros_organismos/modificacion/{$td['id']}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Modificar" data-toggle="modal"><i class="fa fa-pencil"></i></a><a href="'. \App\Helper\Vista::get_url("index.php/otros_organismos/baja/{$td['id']}").'" class="borrar" data-user="" data-toggle="tooltip" 
	           data-placement="top" title="Eliminar" target="_self"><i class="fa fa-trash"></i></a></span>'],]
	    ]
	    ];
}
$config	= FMT\Configuracion::instancia();
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('listado_otros_organismos.js');
$vars_vista['CSS_FILES'][]['CSS_FILE'] = $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.css";
$vars_vista['JS_FILES'][]['JS_FILE'] = $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js";
$vars_vista['JS_FILES'][]['JS_FILE'] = $config['app']['endpoint_cdn']."/datatables/defaults.js";

$vars_template['URL_BASE'] = \App\Helper\Vista::get_url();
$vars_template['LINK'] = \App\Helper\Vista::get_url('index.php/otros_organismos/alta');
$vars_template['DATOS_TABLA'][] = new \FMT\Template(TEMPLATE_PATH . '/tabla.html', $vars_template, ['CLEAN' => false]);

$otros_organismos = new \FMT\Template(TEMPLATE_PATH . '/otros_organismos/otros_organismos.html', $vars_template, ['CLEAN' => false]);

$vars_vista['CONTENT'] = "{$otros_organismos}";

//Hace la composicion del template base con el funcional.
$vista->add_to_var('vars', $vars_vista);
return true;
