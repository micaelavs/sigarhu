<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
$dia =[];
foreach ($dias as  $value) {
	$dia[$value['id']] = $value['dia'];
}

$dia_desde = (isset($horarios->dia_desde)) ? $horarios->dia_desde : '';
$dia_hasta = (isset($horarios->dia_hasta)) ? $horarios->dia_hasta : '';
	$vars_vista['SUBTITULO']	= 'Modificar Plantilla Horaria';
    $vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('horarios.js');
    $vars_template['OPERACION'] = 'modificacion';
	$vars_template['NOMBRE']		=  !empty($horarios->nombre) ? $horarios->nombre: '';
    $vars_template['DIA_DESDE']		=  \FMT\Helper\Template::select_block($dia, $dia_desde);
    $vars_template['DIA_HASTA']		=  \FMT\Helper\Template::select_block($dia, $dia_hasta);
    $vars_template['HORA_DESDE']	=  !empty($horarios->hora_desde) ? $horarios->hora_desde: '';
    $vars_template['HORA_HASTA']	=  !empty($horarios->hora_hasta) ? $horarios->hora_hasta: '';
    $vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/horarios/index'); 
	$template = (new \FMT\Template(VISTAS_PATH.'/templates/horarios/alta.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);
	return true;
?>