<?php
use App\Helper\Vista;
use \FMT\Helper\Arr;

	$vars_vista['SUBTITULO'] = 'Alta Cursos';
	$vars_template = [];
	$vars_template['OPERACION'] = 'alta' ;
	$vars_template = [
			'OPERACION' => 'alta',
			'NOMBRE_CURSO' =>  \FMT\Helper\Template::select_block($cursos,$curso->id),
			'CREDITOS' =>  !empty($curso->creditos) ? $curso->creditos : '',
			'TIPO_PROMOCION'	=> ($curso->tipo_promocion) ? $curso->tipo_promocion : '',
			'CHECKED'			=> ($curso->tipo_promocion == \App\Modelo\Curso::PROMOCION_TRAMO) 
			? 'checked' : '',
		];
	$formacion_cursos_tipo_promocion	= json_encode([
		'tipo_grado' => \App\Modelo\Curso::PROMOCION_GRADO,
		'tipo_tramo' => \App\Modelo\Curso::PROMOCION_TRAMO,
	], JSON_UNESCAPED_UNICODE);
		
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT']  =  \App\Helper\Vista::get_url('script.js');
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT']  =  \App\Helper\Vista::get_url('legajos_alta_curso.js');
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT']   = Vista::get_url('ajax_alta_curso.js');
	$vars_template['INFO_A'][0] = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit];

	$vars_template['BOTON_VOLVER'][] = ['VOLVER' => \App\Helper\Vista::get_url("index.php/legajos/historial_cursos/{$empleado->cuit}") , 'BLOQUE' =>\App\Helper\Bloques::FORMACION, 'ID' => "volver_legajo", 'CLASS' => "volver_legajo btn btn-default", 'HREF' => "#"]; 
	$vars_vista['CSS_FILES'][]	= ['CSS_FILE' => \App\Helper\Vista::get_url('funkyradio.css')];
	$base_url = \App\Helper\Vista::get_url('index.php');
	$vars_vista['JS'][]['JS_CODE']	= <<<JS
	var \$base_url = "{$base_url}";
	var \$formacion_cursos_tipo_promocion = {$formacion_cursos_tipo_promocion};

JS;
	$form_curso = new \FMT\Template(TEMPLATE_PATH.'/legajos/formulario_curso.html', $vars_template,['CLEAN'=>false]);
	$vars_vista['CONTENT'] = "{$form_curso}";
	$vista->add_to_var('vars',$vars_vista);
return true;
