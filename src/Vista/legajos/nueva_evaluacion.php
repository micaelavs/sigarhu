<?php
use App\Helper\Vista;
$vars_template = [];
$vars_vista['SUBTITULO'] = ' Evaluaciones';

	$campos_evaluacion = [
		'OPERACION' => 'alta',
		'FORMULARIOS' =>  \FMT\Helper\Template::select_block($formularios,$evaluacion->formulario ),
		'RESULTADOS' =>  \FMT\Helper\Template::select_block($resultados,$evaluacion->evaluacion ),
		'VOLVER' =>	Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}")	,
		'ANIO' => !empty($evaluacion->anio) ? $evaluacion->anio : '',
		'ACTO' => !empty($evaluacion->acto_administrativo) ? $evaluacion->acto_administrativo : '',
		'PUNTAJE' => !empty($evaluacion->puntaje) ? $evaluacion->puntaje : '',
	];

	if($empleado->situacion_escalafonaria->id_situacion_revista == App\Modelo\Contrato::PLANTA_PERMANENTE){
		$campos_evaluacion['BONIFICADO'][0] = ['CHECKED' => !empty($evaluacion->bonificado) ? 'checked' : ''];
	}
	
	if (!empty($empleado->id) && !empty($empleado->cuit)){
		foreach ($campos_evaluacion as $key => $value) {
			$vars_template['CAMPOS_EVALUACION'][0][$key] = $value;
		}
	}else{
		$vars_template['AVISO_EVALUACION'][]['MSJ'] = 'PARA DEFINIR <strong>EVALUACIÓN</strong>, ES REQUISITO TENER LOS DATOS BÁSICOS DEL <strong>AGENTE</strong>.';
	}
$vars_template['BOTON_VOLVER'][] = ['VOLVER' => \App\Helper\Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}") , 'BLOQUE' =>\App\Helper\Bloques::PERFILES_PUESTO, 'ID' => "volver_legajo", 'CLASS' => "btn btn-default", 'HREF' => "#"];

$vars_vista['CSS_FILES'][]	= ['CSS_FILE' => \App\Helper\Vista::get_url('funkyradio.css')];
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']   = Vista::get_url('form_evaluacion.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('fileinput.min.js');
$vars_vista['CSS_FILES'][]	= ['CSS_FILE' => \App\Helper\Vista::get_url('fileinput.min.css')];
$formulario_evaluacion = new \FMT\Template(TEMPLATE_PATH.'/legajos/formulario_evaluacion.html', $vars_template, ['CLEAN'=>false]);
$bloque = \App\Helper\Bloques::PERFILES_PUESTO;

$vars_vista['CONTENT'] = "{$formulario_evaluacion}";
$vista->add_to_var('vars',$vars_vista);
return true;