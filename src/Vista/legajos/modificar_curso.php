<?php
use App\Helper\Vista;
use \FMT\Helper\Arr;

	$vars_vista['SUBTITULO'] = 'Modificar Curso';
	$vars_template = [];
	$vars_template['OPERACION'] = 'modificacion' ;
	$vars_template = [
			'OPERACION' 	=> 'modificacion',
			'NOMBRE_CURSO' 	=>  \FMT\Helper\Template::select_block($cursos,$empleado_cursos->id_curso),
			'CREDITOS' 		=>  !empty($curso->creditos) ? $curso->creditos : '',
			'FECHA' 	 	=>  !empty($empleado_cursos->fecha) ? $empleado_cursos->fecha->format('d/m/Y') : '',
			'TIPO_PROMOCION'	=> ($empleado_cursos->tipo_promocion) ? $empleado_cursos->tipo_promocion : '',
			'CHECKED'			=> ($empleado_cursos->tipo_promocion == \App\Modelo\Curso::PROMOCION_TRAMO) 
			? 'checked' : '',
		];
	$formacion_cursos_tipo_promocion	= json_encode([
		'tipo_grado' => \App\Modelo\Curso::PROMOCION_GRADO,
		'tipo_tramo' => \App\Modelo\Curso::PROMOCION_TRAMO,
	], JSON_UNESCAPED_UNICODE);

	$vars_vista['JS_FOOTER'][]['JS_SCRIPT']  =  \App\Helper\Vista::get_url('script.js'); //aca se dubuja el form para el boton volver
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
