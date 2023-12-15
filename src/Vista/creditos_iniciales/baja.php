<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;

	$vars_vista['SUBTITULO'] = 'Baja de Crédito';
	$vars_template['CONTROL'] = 'Crédito';
	$vars_template['ARTICULO'] = 'El';
	$vars_template['TEXTO_AVISO'] = 'Dará de baja ';			
	$vars_template['NOMBRE'] = $creditos->creditos.' del agente '.$empleado->persona->nombre. ' '.$empleado->persona->apellido ;
	$vars_template['TEXTO_EXTRA'] = '.<br/>Al eliminarlo, no se mostrará en el listado de Créditos';
	$vars_template['CANCELAR'] = \App\Helper\Vista::get_url("index.php/CreditosIniciales/listar");
	$template = (new \FMT\Template(VISTAS_PATH.'/widgets/confirmacion.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);

	return true;
