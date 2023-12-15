<?php
use App\Helper\Vista;
$vars_template = [];
$vars_vista['SUBTITULO'] = ' Nueva Prorroga';

if (!empty($empleado->id) && !empty($empleado->cuit)){
	
$vars_template['CAMPOS_DESIGNACION'][0]['OPERACION'] = 'alta';
$vars_template['CAMPOS_DESIGNACION'][0]['COMPROBANTE'] = ($designacion_transitoria->archivo)? 'Reemplazar Comprobante': 'Comprobante';
$vars_template['CAMPOS_DESIGNACION'][0]['TIPO_ALTA'][0]['TIPO'] = $tipo_designacion[$designacion_transitoria->tipo]['nombre'];
$vars_template['CAMPOS_DESIGNACION'][0]['FECHA_ALTA'][0]['FECHA_DESDE'] =  !empty($temp = $designacion_transitoria->fecha_desde) ? $temp->format('d/m/Y') : '';
$vars_template['CAMPOS_DESIGNACION'][0]['VOLVER'] = Vista::get_url("index.php/escalafon/designacion_transitoria");

}

$vars_vista['JS_FOOTER'][]['JS_SCRIPT']   = Vista::get_url('form_designacion_transitoria.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('fileinput.min.js');
$vars_vista['CSS_FILES'][]	= ['CSS_FILE' => \App\Helper\Vista::get_url('fileinput.min.css')];
$vars_template['BOTON_VOLVER'][] = ['VOLVER' => \App\Helper\Vista::get_url("index.php/legajos/escalafon/designacion_transitoria") , 'BLOQUE' =>\App\Helper\Bloques::FORMACION, 'ID' => "volver_legajo", 'CLASS' => "volver_legajo btn btn-default", 'HREF' => "#"]; 
$formulario_prorroga = new \FMT\Template(TEMPLATE_PATH.'/escalafon/formulario_prorroga.html', $vars_template, ['CLEAN'=>false]);

$vars_vista['CONTENT'] = "{$formulario_prorroga}";
$vista->add_to_var('vars',$vars_vista);
return true;