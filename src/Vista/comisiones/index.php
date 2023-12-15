<?php

/**
 * $vars_template |Variable de configuracion para el template de la funcionalidad que se esta desarrollando.
 * $vars_vista  |Variable de configuracion para el template general. Llega a la vista por medio de la variable "vista"
 * propagada por la clase Vista.
**/

$config	= \FMT\Configuracion::instancia();
$vars_template = [];
$vars_vista['SUBTITULO'] = 'Lista de Organismos Origen/Destino';
$vars_template['TITULOS'] = [
    		['TITULO' => 'Nombre'],

       ['TITULO'=>'Acciones']

];

foreach ($comisiones as $td) {

    $vars_template['ROW'][] =
        ['COL' => [
           		['CONT'=>$td->nombre],

           ['CONT'=>['<span class="acciones"><a href="'.\App\Helper\Vista::get_url("index.php/comisiones/modificacion/{$td->id}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Modificar" data-toggle="modal"><i class="fa fa-pencil"></i></a><a href="'. \App\Helper\Vista::get_url("index.php/comisiones/baja/{$td->id}").'" class="borrar" data-user="" data-toggle="tooltip" 
               data-placement="top" title="Eliminar" target="_self"><i class="fa fa-trash"></i></a></span>'],]
        ]
        ];
}

$vars_vista['CSS_FILES'][]['CSS_FILE'] = $config['app']['endpoint_cdn'].'/datatables/1.10.12/datatables.min.css';
$vars_vista['JS_FILES'][]['JS_FILE'] = $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js";
$vars_vista['JS_FILES'][]['JS_FILE'] = $config['app']['endpoint_cdn']."/datatables/defaults.js";
$vars_vista['JS_FILES'][] = ['JS_FILE'    => \App\Helper\Vista::get_url("comisiones.js")];
$vars_template['URL_BASE'] = \App\Helper\Vista::get_url();
$vars_template['LINK'] = \App\Helper\Vista::get_url('index.php/comisiones/alta');
$vars_template['DATOS_TABLA'][] = new \FMT\Template(TEMPLATE_PATH . '/tabla.html', $vars_template, ['CLEAN' => false]);

$comisiones = new \FMT\Template(TEMPLATE_PATH . '/comisiones/comisiones'.'.html', $vars_template, ['CLEAN' => false]);

$vars_vista['CONTENT'] = "{$comisiones}";

//Hace la composicion del template base con el funcional.
$vista->add_to_var('vars', $vars_vista);
return true;