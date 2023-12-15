<?php
use App\Helper\Vista;
use \FMT\Helper\Arr;

	$vars_vista['SUBTITULO'] = 'Modificar Embargo';
	$vars_template = [];

	$vars_template['OPERACION'] = 'modificacion' ;
	$vars_template['AUTOS'] = !empty($embargo->autos) ? $embargo->autos : '';
	$vars_template['TIPO_EMBARGO'] = \FMT\Helper\Template::select_block($tipo_embargo, $embargo->tipo_embargo);
	$vars_template['FECHA_ALTA' ] = !empty($temp = $embargo->fecha_alta) ? $temp->format('d/m/Y') : '';
	$vars_template['FECHA_CANCELACION'] = !empty($temp = $embargo->fecha_cancelacion) ? $temp->format('d/m/Y') : '';
	$vars_template['MONTO'] = !empty($embargo->monto) ? $embargo->monto: '';
	$vars_template['VOLVER'] = Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");
	$vars_template['BLOQUE'] = \App\Helper\Bloques::EMBARGO;
    
    $form_embargos = new \FMT\Template(TEMPLATE_PATH.'/legajos/formulario_embargo.html', $vars_template,['CLEAN'=>false]);

	$vars_vista['CSS_FILES'][]  = ['CSS_FILE' => \App\Helper\Vista::get_url('legajos.css')];
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT']   = Vista::get_url('embargo.js');
	$vars_vista['CONTENT'] = "{$form_embargos}";
	$vista->add_to_var('vars',$vars_vista);
	return true;



