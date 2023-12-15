<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use \App\Helper\Vista;
$vars_template = [];
$vars_vista['SUBTITULO']	= 'Importar Cursos';
$vars_template['COMPROBANTE'] = $importador->archivo;
$vars_template['FORM'] = \App\Helper\Vista::get_url("index.php/importador/procesar_cursos");
$vars_template['ARCHIVO_EJEMPLO'] = \App\Helper\Vista::get_url("assets/Ejemplo_-_Importacion_de_Cursos.xlsx");
$vars_template['OPERACION']				= 'procesar_cursos';
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('fileinput.min.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('importador.js');
$vars_vista['CSS_FILES'][]	= ['CSS_FILE' => \App\Helper\Vista::get_url('fileinput.min.css')];
$vars_vista['CSS_FILES'][]	= ['CSS_FILE' => \App\Helper\Vista::get_url("importador.css")];

$vars_template['URL_BASE'] = \App\Helper\Vista::get_url();
$vars_template['BOTON_VOLVER'][] = ['VOLVER' => \App\Helper\Vista::get_url("index.php") , 'BLOQUE' =>\App\Helper\Bloques::FORMACION, 'ID' => "volver_legajo", 'CLASS' => "volver_legajo btn btn-default", 'HREF' => "#"]; 
$importador = new \FMT\Template(TEMPLATE_PATH.'/importador/procesar_cursos.html',$vars_template,['CLEAN'=>false]);
$vars_vista['CONTENT'] = "{$importador}";
$vista->add_to_var('vars',$vars_vista);
return true;