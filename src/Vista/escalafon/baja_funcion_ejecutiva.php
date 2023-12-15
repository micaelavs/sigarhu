<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
$vars_vista['SUBTITULO'] = 'Baja de Función Ejecutiva';
$vars_template['CONTROL'] = 'Función Ejecutiva';
$vars_template['ARTICULO'] = 'La';
$vars_template['TEXTO_AVISO'] = 'Dará de baja ';			
$vars_template['NOMBRE'] = $f_ejecutiva->nombre;
$vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/escalafon/lista_funciones_ejecutivas');
$template = (new \FMT\Template(VISTAS_PATH.'/widgets/confirmacion.html', $vars_template,['CLEAN'=>false]));
$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;