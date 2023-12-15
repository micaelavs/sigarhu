<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
$vars_vista['SUBTITULO'] = 'Baja Licencias Especiales';
$vars_template['CONTROL'] = 'Licencia Especial';
$vars_template['ARTICULO'] = 'La';
$vars_template['TEXTO_AVISO'] = 'Dará de baja ';			
$vars_template['NOMBRE'] = $licencias_especiales->nombre;
$vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/licencias_especiales/index');
$template = (new \FMT\Template(VISTAS_PATH.'/widgets/confirmacion.html', $vars_template,['CLEAN'=>false]));
$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;