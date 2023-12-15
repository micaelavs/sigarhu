<?php
use App\Helper\Vista;
$vars_template = [];
$vars_vista['SUBTITULO'] = 'Modificar Prorroga';

if (!empty($empleado->id) && !empty($empleado->cuit)){
	
	$vars_template['CAMPOS_DESIGNACION'][0]['OPERACION'] = 'modificacion';
	$vars_template['CAMPOS_DESIGNACION'][0]['COMPROBANTE'] = ($designacion_transitoria->archivo)? 'Reemplazar Comprobante': 'Comprobante';
	$vars_template['CAMPOS_DESIGNACION'][0]['TIPO_MODIFICACION'][0]['TIPO'] = \FMT\Helper\Template::select_block($tipo_designacion, $designacion_transitoria->tipo);
	$vars_template['CAMPOS_DESIGNACION'][0]['FECHA_MODIFICACION'][0]['FECHA_DESDE'] =  !empty($temp = $designacion_transitoria->fecha_desde) ? $temp->format('d/m/Y') : '';
	$vars_template['CAMPOS_DESIGNACION'][0]['VOLVER'] = Vista::get_url("index.php/escalafon/designacion_transitoria");
	
	if($designacion_transitoria->archivo) {
		$campos_designacion['ARCHIVO'][0] =  ['URL' => \App\Helper\Vista::get_url("index.php/escalafon/mostrar_designacion/{$designacion_transitoria->id}")];		
	}
}


$vars_vista['JS_FOOTER'][]['JS_SCRIPT']   = Vista::get_url('form_designacion_transitoria.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('fileinput.min.js');
$vars_vista['CSS_FILES'][]	= ['CSS_FILE' => \App\Helper\Vista::get_url('fileinput.min.css')];
$formulario_designacion = new \FMT\Template(TEMPLATE_PATH.'/escalafon/formulario_prorroga.html', $vars_template, ['CLEAN'=>false]);

$vars_vista['CONTENT'] = "{$formulario_designacion}";
$vista->add_to_var('vars',$vars_vista);
return true;