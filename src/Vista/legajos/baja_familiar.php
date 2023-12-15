<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
	$vars_vista['SUBTITULO'] = 'Baja de Familiar';
	$vars_template['CONTROL'] = 'Grupo Familiar a';
	$vars_template['ARTICULO'] = 'del';
	$vars_template['TEXTO_AVISO'] = 'Dará de baja ';			
	$vars_template['NOMBRE'] = "{$familiar->nombre} {$familiar->apellido}";
	$vars_template['CANCELAR'] = \App\Helper\Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");
	$vars_template['TEXTO_EXTRA'] = '.<br/>Solo utilice esta función en caso de error';
	$vista->setGetVarSession('data_legajo', ['select_tab' => 'tab_grupo_familiar']);
	$template = (new \FMT\Template(VISTAS_PATH.'/widgets/confirmacion.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);

	return true;