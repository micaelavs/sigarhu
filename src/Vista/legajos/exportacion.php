<?php
use App\Helper\Vista;
use \FMT\Helper\Arr;
$config	= FMT\Configuracion::instancia();
$vars_vista['SUBTITULO'] = 'Exportación de Datos';

array_walk($parametros,function(&$value, $key){
		$value['DIV_ID'] = str_replace(' ', '', $value['BLOCK']);
		$value['CAMPO'] = \FMT\Helper\Template::select_block($value['CAMPO']);

});

$vars_template = [
	'DEPENDENCIAS' 		=> \FMT\Helper\Template::select_block($parametricos['select_dependencia'],$filtros['dependencia']),
	'SIT_REVISTA'		=> \FMT\Helper\Template::select_block($parametricos['situacion_revista'],$filtros['situacion_revista']),
	'MOD_CONTRATACION'  => \FMT\Helper\Template::select_block($parametricos['modalidad_contratacion'],$filtros['modalidad_contratacion']),
	'ESTADO' 			=> \FMT\Helper\Template::select_block($parametricos['estado'],$filtros['estado']),
	'CAMPOS' 			=> $parametros,
	'URL_BASE' 			=> Vista::get_url("index.php"),
	'FORM'				=> \App\Helper\Vista::get_url("index.php/legajos/exportar"),
];

$vars_template['BOTON_EXPORTAR'][0] = [ 'FORM'      => \App\Helper\Vista::get_url("index.php/legajos/exportar"),  
                                        'CLASS'     => 'btn-primary',
                                        'NOMBRE'    => 'EXPORTAR'
                                   	  ];

$vars_template['VOLVER'][0] = [ 'HREF'      => \App\Helper\Vista::get_url("index.php/legajos/agentes"),  
                                'CLASS'     => 'btn-default',
                                'NOMBRE'    => 'VOLVER'
                           	  ];

$exportacion = new \FMT\Template(TEMPLATE_PATH.'/legajos/exportacion.html', $vars_template,['CLEAN'=>false]);

$vars_vista['CSS_FILES'][]	= ['CSS_FILE' =>    $config['app']['endpoint_cdn'].'/datatables/1.10.12/estilos.min.css'];
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('exportador.js').'?'.filectime(__FILE__);
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('script.js');

$vars_vista['CONTENT'] = "$exportacion";

$vista->add_to_var('vars',$vars_vista);
return true;
