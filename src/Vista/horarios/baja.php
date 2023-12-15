<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
$vars_vista['SUBTITULO'] = 'Baja Plantillas Horarias';
$vars_template['CONTROL'] = 'Horarios';
$vars_template['ARTICULO'] = 'Los';
$vars_template['TEXTO_AVISO'] = 'Dará de baja ';			
$vars_template['NOMBRE'] =  $horarios->nombre;
$vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/horarios/index');
$template = (new \FMT\Template(VISTAS_PATH.'/widgets/confirmacion.html', $vars_template,['CLEAN'=>false]));
$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;