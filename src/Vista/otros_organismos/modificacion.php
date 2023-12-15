<?php
use FMT\Vista;
$vars_vista['SUBTITULO']	= 'Modificar Otros Organismos';

$vars_template['OPERACION'] = 'modificacion';
$vars_template['NOMBRE']= !empty($otros_organismos->nombre) ? $otros_organismos->nombre:'';
$vars_template['TIPO']=   !empty($otros_organismos->tipo) ? \FMT\Helper\Template::select_block($tipos,  $otros_organismos->tipo) : '';
$vars_template['JURISDICCION']=   \FMT\Helper\Template::select_block($jurisdicciones,  $otros_organismos->jurisdiccion);
$vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/otros_organismos/index');
$template = (new \FMT\Template(VISTAS_PATH.'/templates/otros_organismos/otros_organismos_modificacion.html', $vars_template,['CLEAN'=>false]));
$vars_vista['CONTENT'] = "$template";
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']   = \App\Helper\Vista::get_url('otros_organismos.js');
$vista->add_to_var('vars',$vars_vista);
return true;
?>
