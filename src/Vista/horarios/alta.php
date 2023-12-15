
<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
$dia =[];
foreach ($dias as  $value) {
	$dia1[$value['id']] = $value['dia'];
	$dia2[$value['id']] = $value['dia'];
}
unset($dia2['00']);
$dia_desde = (isset($horarios->dia_desde)) ? $horarios->dia_desde : '';
$dia_hasta = (isset($horarios->dia_hasta)) ? $horarios->dia_hasta : '';

	$vars_vista['SUBTITULO']		= 'Alta Plantilla Horaria';
    $vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('horarios.js');
    $vars_template['OPERACION']		= 'alta';
    $vars_template['NOMBRE']		=  $horarios->nombre;
    $vars_template['DIA_DESDE']		=  \FMT\Helper\Template::select_block($dia1, $dia_desde);
    $vars_template['DIA_HASTA']		=  \FMT\Helper\Template::select_block($dia2, $dia_hasta);
    $vars_template['HORA_DESDE']	=  $horarios->hora_desde;
    $vars_template['HORA_HASTA']	=  $horarios->hora_hasta;
    $vars_template['CANCELAR'] 		= \App\Helper\Vista::get_url('index.php/horarios/index'); 
	$template = (new \FMT\Template(VISTAS_PATH.'/templates/horarios/alta.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);

	return true;
?>