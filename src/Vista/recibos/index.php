<?php

use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use \App\Helper\Vista;

//$vars_template  Variable de configuracion para el template de la funcionalidad que se esta desarrollando.

//$vars_vista  Variable de configuracion para el template general. Llega a la vista por medio de la variable "vista" propagada por la clase Vista.

$vars_template = [];
$vars_vista['SUBTITULO'] = 'Consulta de recibos';


$vars_template2['TITULOS'] = [
    ['TITULO' => 'Tipo de recibo'],
    ['TITULO' => 'Mes'],
    ['TITULO' => 'Año'],
    ['TITULO' => 'Archivo'],
];




foreach ($recibosEncontrados as $link => $archivo) {

    $datosArchivo = [
        'documento' => $archivo['documento'],
        'anio' => $archivo['anio'],
        'mes' => $archivo['mes'],
        'tipoRec' => $archivo['tipoRec'],
        'tipoRecId' => $archivo['tipoRecId'],
        'usuario' => $usuario
    ];
    $datosJson = json_encode($datosArchivo);
    $hash = base64_encode(openssl_encrypt($datosJson, $config['recibos']['methodEncrypted'], $config['recibos']['passEncrypted']));

    $vars_template2['ROW'][] =
        [
            'COL' => [
                ['CONT' => $archivo['tipoRec']],
                ['CONT' => $archivo['mes']],
                ['CONT' => $archivo['anio']],
                ['CONT' => '<span class="acciones">
                    <a href="' . Vista::get_url("index.php/recibos/descarga/{$hash}") . '" target="_blank" data-toggle="tooltip" data-placement="top" data-id="" title="Ver recibo" data-toggle="modal"><i class="fa fa-eye"></i></a> </span>']
            ]
        ];
}

$vars_template['URL_BASE'] = \App\Helper\Vista::get_url();
$vars_template['LINK'] = \App\Helper\Vista::get_url('index.php/recibos/index/' .$cuit);
$vars_template['OPERACION'] = 'consultar';

$config	= FMT\Configuracion::instancia();

$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = Vista::get_url('data-table-recibos.js');
$vars_vista['CSS_FILES'][]['CSS_FILE']  = $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.css";
$vars_vista['JS_FILES'][]['JS_FILE']    = $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js";
$vars_vista['JS_FILES'][]['JS_FILE']    = $config['app']['endpoint_cdn']."/datatables/defaults.js";
$vars_vista['JS_FILES'][]['JS_FILE']    = $config['app']['endpoint_cdn']."/datatables/1.10.12/plugins/sorting/datetime-moment.js";

$vars_template['MESES'] = \FMT\Helper\Template::select_block($meses, '');

$vars_template2['DATOS_TABLA'][] =  new \FMT\Template(TEMPLATE_PATH . '/tabla.html', $vars_template2, ['CLEAN' => false]);
$vars_template2['VOLVER'] = \App\Helper\Vista::get_url("index.php/legajos/agentes");

$recibos_encontrados = new \FMT\Template(TEMPLATE_PATH . '/recibos/reciboLista.html', $vars_template2, ['CLEAN' => false]);
$vars_template['CONTENT'] = "{$recibos_encontrados}";

$recibo = new \FMT\Template(TEMPLATE_PATH . '/recibos/recibo.html', $vars_template, ['CLEAN' => false]);
$vars_vista['CONTENT'] = "{$recibo}";

//Hace la composición del template base con el funcional.
$vista->add_to_var('vars', $vars_vista);
return true;