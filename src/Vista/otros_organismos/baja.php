<?php
$vars_template['TEXTO_AVISO'] = 'Dará de baja  ';
$vars_template['ARTICULO'] = 'el Registro';
$vars_vista['SUBTITULO'] = 'Otros Organismos';
$vars_template['CONTROL'] = 'otrosorganismos:';

$vars_template['NOMBRE'] = "con"." nombre:". $otros_organismos->nombre;
$vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/otros_organismos/index');
$template = (new \FMT\Template(VISTAS_PATH.'/widgets/confirmacion.html', $vars_template,['CLEAN'=>false]));
$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;
