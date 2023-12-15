<?php

use App\Helper\Vista;
/**
 * @var \App\Modelo\Denominacion_funcion $denom_funcion
 * @var $vista
 */

$vars_vista['SUBTITULO'] = 'Reactivar "Denominacion funcion"';
$vars_template['TEXTO_AVISO'] = '';
$vars_template['ARTICULO'] = 'La ';
$vars_template['CONTROL'] = 'Denominacion funcion con nombre:';
$vars_template['NOMBRE'] =  $denom_funcion->nombre;
$vars_template['ID'] =  $denom_funcion->id;
$vars_template['CANCELAR'] = Vista::get_url('index.php/denominacion_funcion/index');
$template = (new \FMT\Template(VISTAS_PATH.'/widgets/confirmacion_reactivar.html', $vars_template,['CLEAN'=>false]));
$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;