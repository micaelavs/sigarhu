<?php
use App\Helper\Vista;
$vars_template = [];
$vars_vista['SUBTITULO'] = ' Horas Extras';
$temp= $empleado->horas_extras->mes.'/'.$empleado->horas_extras->anio;
$vars_template['OPERACION'] = 'alta' ;
$vars_template['ANIO_MES'] = !empty($empleado->horas_extras->anio) &&  !empty($empleado->horas_extras->mes) ? $temp: '';
$vars_template['ACTO_ADMINISTRATIVO' ] = !empty($empleado->horas_extras->acto_administrativo) ? $empleado->horas_extras->acto_administrativo: '';
$vars_template['VOLVER'] = Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");
$vars_template['BLOQUE'] = \App\Helper\Bloques::ADMINISTRACION;
$vars_vista['CSS_FILES'][]  = ['CSS_FILE' => \App\Helper\Vista::get_url('legajos.css')];
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']   = Vista::get_url('extras.js');
$formulario_horas_extras = new \FMT\Template(TEMPLATE_PATH.'/legajos/formulario_horas_extras.html', $vars_template, ['CLEAN'=>false]);
$vars_vista['CONTENT'] = "{$formulario_horas_extras}";

$vista->add_to_var('vars',$vars_vista);
return true;