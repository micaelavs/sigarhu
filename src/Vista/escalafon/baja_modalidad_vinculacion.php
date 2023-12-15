<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
$vars_vista['SUBTITULO'] = 'Baja de Modalidad de Vinculación';
$vars_template['CONTROL'] = 'Modalidad de Vinculación';
$vars_template['ARTICULO'] = 'La';
$vars_template['TEXTO_AVISO'] = 'Dará de baja ';			
$vars_template['NOMBRE'] = $mod_vinculacion->nombre;
$vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/escalafon/lista_modalidad_vinculacion');
$template = (new \FMT\Template(VISTAS_PATH.'/widgets/confirmacion.html', $vars_template,['CLEAN'=>false]));
$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;