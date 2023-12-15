<?php
	namespace App\Vista;
	$vars_vista['SUBTITULO']	= 'Alta Documento';

	$vars = [
		'CANCELAR'	=> \App\Helper\Vista::get_url("index.php/documentos/listado/{$empleado->cuit}"),
		'FORM'		=> \App\Helper\Vista::get_url("index.php/documentos/{$formulario}/{$empleado->cuit}"),
		'ID_TIPO'	=> \FMT\Helper\Template::select_block($tipos, $doc_empleado->id_tipo),
		'ID_EMP'    => $doc_empleado->id_empleado,
		'NOMBRE_BOTON' => ($formulario == 'alta') ? 'GUARDAR' : 'MODIFICAR',
		'ACCION'	   => ($formulario == 'alta') ? 'alta' : 'modificacion',
		'AGENTE'		=> "{$empleado->persona->nombre}	{$empleado->persona->apellido}",
		'CUIT'			=> $empleado->cuit
	];
	if($formulario != 'alta') {
		$vars['FORM']      = \App\Helper\Vista::get_url("index.php/documentos/{$formulario}/{$doc_empleado->id}");
		$vars['ACTUAL'][0] = [	'FECHA'		=> $doc_empleado->fecha_reg->format('d/m/Y'),
								'NOMBRE'	=> preg_replace('/\d{14}-/', '', $doc_empleado->archivo),
								'LINK'		=> \App\Helper\Vista::get_url("index.php/documentos/descargar_documento/{$doc_empleado->id}")
							];		
	}
	$config	= \FMT\Configuracion::instancia();
	$template = (new \FMT\Template(VISTAS_PATH."/templates/documentos/alta.html", $vars));
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT']  = \App\Helper\Vista::get_url('doc-adjuntos.js');
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT']  = \App\Helper\Vista::get_url('fileinput.min.js');
	$vars_vista['CSS_FILES'][] = ['CSS_FILE'   => $config['app']['endpoint_cdn']."/js/select2/css/select2.min.css"];
    	$vars_vista['JS_FILES'][] = ['JS_FILE' => $config['app']['endpoint_cdn']."/js/select2/js/select2.full.min.js"];
	$vars_vista['CSS_FILES'][]['CSS_FILE'] = \App\Helper\Vista::get_url('documentos.css');
	$vars_vista['CSS_FILES'][]['CSS_FILE'] = \App\Helper\Vista::get_url('fileinput.min.css');
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);
	return true;