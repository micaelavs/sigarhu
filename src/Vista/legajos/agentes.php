<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;

$config	= FMT\Configuracion::instancia();
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('script.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('agentes-ajax.js');

$vars_vista['CSS_FILES'][]	= ['CSS_FILE' =>    $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.css"];
$vars_vista['CSS_FILES'][]  = ['CSS_FILE' => \App\Helper\Vista::get_url('funkyradio.css')];
$vars_vista['JS_FILES'][]	= ['JS_FILE' =>     $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js"];	
$vars_vista['JS_FILES'][]	= ['JS_FILE' =>     $config['app']['endpoint_cdn']."/datatables/defaults.js"];


if($parametros){    
    $vars_template = [
        'DEPENDENCIAS'              =>  Template::select_block($parametros['select_dependencia']),
        'SITUACION_REVISTA'         =>  Template::select_block($parametros['situacion_revista']),
        'MODALIDAD_CONTRATACION'    =>  Template::select_block($parametros['modalidad_contratacion']),        
        'ESTADO'                    =>  Template::select_block($parametros['estado']),        
    ];
}


$vars_template['URL_BASE'] = \App\Helper\Vista::get_url();

$vars_vista['SUBTITULO'] = 'Listado de Agentes';
$nuevo['CLASS_COL'] = 'col-md-4';
$nuevo['BOTON_ACCION'][0] = [	'HTTP'		=> \App\Helper\Vista::get_url("index.php"),
								'CONTROL'	=> '/legajos',
                                'ACCION'	=> '/gestionar',
                                'CLASS'=>'btn-primary',
								'NOMBRE'	=> 'NUEVO'
                            ];

if($permisos['exportar']){   
                            $vars_template['BOTON_EXCEL'][0] = [    'FORM'      => \App\Helper\Vista::get_url("index.php/legajos/exportacion"),  
                                                                    'CLASS'     => 'btn-default',
                                                                    'NOMBRE'    => 'Exportación de Datos '
                                   ];
                         }
$agentes = new \FMT\Template(TEMPLATE_PATH.'/legajos/lista_agentes.html', $vars_template, ['CLEAN'=>false]);

$botonera = ($permisos['nuevos']) ? new \FMT\Template(VISTAS_PATH.'/widgets/botonera.html', $nuevo, ['CLEAN'=>true]) : '';

$base_url	= \App\Helper\Vista::get_url('index.php');
$is_iri        = ($permisos['recibos']) ? 'true' : 'false';
$vars_vista['JS'][]['JS_CODE']	= <<<JS
    var \$base_url			= "{$base_url}";
    var \$iri = {$is_iri};
JS;
$vars_vista['CONTENT'] = "$agentes"."$botonera";
$vista->add_to_var('vars',$vars_vista);

return true;