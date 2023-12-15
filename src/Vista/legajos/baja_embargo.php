<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
	$vars_vista['SUBTITULO'] = 'Baja de Embargos';
	$vars_template['CONTROL'] = 'Embargo';
	$vars_template['ARTICULO'] = 'El';
	$vars_template['TEXTO_AVISO'] = 'Dará de baja ';			
	$vars_template['NOMBRE'] = $embargo->autos;
	$vars_template['TEXTO_EXTRA'] = '.<br/>Al eliminarlo, no se mostrará en el listado de Historial';
	$vars_template['CANCELAR'] = \App\Helper\Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");
	$template = (new \FMT\Template(VISTAS_PATH.'/widgets/confirmacion.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT']   = \App\Helper\Vista::get_url('embargo.js');
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);

	return true;