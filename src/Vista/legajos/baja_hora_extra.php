<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
	$vars_vista['SUBTITULO'] = 'Baja de Horas Extras';
	$vars_template['CONTROL'] = 'Horas extras según acto administrativo';
	$vars_template['ARTICULO'] = 'Las';
	$vars_template['TEXTO_AVISO'] = 'Dará de baja ';
	$vars_template['NOMBRE'] = $control->acto_administrativo;			
	$vars_template['CANCELAR'] = \App\Helper\Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");
	$template = (new \FMT\Template(VISTAS_PATH.'/widgets/confirmacion.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);

	return true;