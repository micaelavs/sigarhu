<?php
$vars_template['TEXTO_AVISO'] = 'Dará de baja  ';
$vars_template['ARTICULO'] = 'el Registro';
$vars_vista['SUBTITULO'] = 'Baja Organismo Origen/Destino';
$vars_template['CONTROL'] = 'comisiones:';

$vars_template['NOMBRE'] = "con"." nombre:". $comisiones->nombre;
$vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/comisiones/index');
$template = (new \FMT\Template(VISTAS_PATH.'/widgets/confirmacion.html', $vars_template,['CLEAN'=>false]));
$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);
return true;
