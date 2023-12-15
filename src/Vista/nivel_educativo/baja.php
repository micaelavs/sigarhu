<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
$vars_vista['SUBTITULO'] = 'Baja Nivel Educativo';
$vars_template['CONTROL'] = 'Nivel Educativo';
$vars_template['ARTICULO'] = 'El';
$vars_template['TEXTO_AVISO'] = 'Dará de baja ';			
$vars_template['NOMBRE'] = $nivel_educativo->nombre;
$vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/nivel_educativo/index');
$template = (new \FMT\Template(VISTAS_PATH.'/widgets/confirmacion.html', $vars_template,['CLEAN'=>false]));
$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;