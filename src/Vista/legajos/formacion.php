<?php
use \FMT\Helper\Arr;
use \FMT\Helper\Template;

	$vars_template = [
		'SELECT_TIPO_TITULO'		=> Template::select_block($parametricos['formacion_tipo_titulo'], null), 
		'SELECT_ESTADO_TITULO'		=> Template::select_block($parametricos['formacion_estado_titulo'], null),
		'SELECT_DESCRIPCION'		=> Template::select_block($parametricos['titulo'], null),
		'SELECT_NOMBRE_CURSO'		=> Template::select_block($parametricos['formacion_cursos'], null),
		'TITULOS'					=> [],
		'OTROS_ESTUDIOS'			=> [],
		'OTROS_CONOCIMIENTOS'		=> [],
		'CURSOS'					=> [],
	];

	if($permisos['formacion']){  
		$vars_template['ADJUNTAR_DOC'] = $adjuntar_doc;
		if(\FMT\Helper\Arr::get($empleado->persona->titulos,0) && $empleado->persona->titulos[0]->id) {
			foreach ($empleado->persona->titulos as $t) {
				$vars_template['TITULOS'][]	= [
					'TITULO_ID'			=> $t->id,
					'TIPO_TITULO'		=> Template::select_block($parametricos['formacion_tipo_titulo'], (string)$t->id_tipo_titulo),
					'ESTADO_TITULO'		=> Template::select_block($parametricos['formacion_estado_titulo'], (string)$t->id_estado_titulo),
					'DESCRIPCION'		=> Template::select_block($parametricos['titulo'][$t->id_tipo_titulo], $t->id_titulo),
					'FECHA'				=> ($t->fecha instanceof \DateTime) ? $t->fecha->format('d/m/Y') : '',
					'PRINCIPAL'			=> !empty($t->principal) ? 'checked' : '',
					'CREDITOS' 			=> \App\Helper\Vista::get_url("index.php/legajos/historial_titulo_creditos/{$t->id}"),
					'PORC'				=> (string)$t->acum_creditos,
				];
			}
		}

		foreach ($empleado->persona->otros_conocimientos as $oc) {
			if($oc->id_tipo	== \App\Modelo\PersonaOtroConocimiento::ESTUDIO){
				$vars_template['OTROS_ESTUDIOS'][]	= [
					'OTRO_ESTUDIO_ID'	=> $oc->id,
					'ID_TIPO'			=> $oc->id_tipo,
					'DESCRIPCION'		=> $oc->descripcion,
					'FECHA'				=> ($oc->fecha instanceof \DateTime) ? $oc->fecha->format('d/m/Y') : '',
				];
			}
			if($oc->id_tipo	== \App\Modelo\PersonaOtroConocimiento::CONOCIMIENTO){
				$vars_template['OTROS_CONOCIMIENTOS'][]	= [
					'OTRO_CONOCIMIENTO_ID'	=> $oc->id,
					'ID_TIPO'				=> $oc->id_tipo,
					'DESCRIPCION'			=> $oc->descripcion,
				];
			}
		}

	if (\FMT\Helper\Arr::get($empleado->empleado_cursos,0) && $empleado->empleado_cursos[0]->id) {
		foreach ($empleado->empleado_cursos as $c) {
				$vars_template['CURSOS'][]	= [
					'CURSOS_ID'			=> $c->id,
					'TITULO_CURSO'		=> Template::select_block($parametricos['formacion_cursos'], $c->id_curso),
					'CREDITOS'			=> Arr::path($parametricos['formacion_cursos'], $c->id_curso.'.creditos', ''),
					'FECHA'				=> ($c->fecha instanceof \DateTime) ? $c->fecha->format('d/m/Y') : '',
					'TIPO_PROMOCION'	=> ($c->tipo_promocion) ? $c->tipo_promocion : '',
					'CHECKED'			=> ($c->tipo_promocion == \App\Modelo\Curso::PROMOCION_TRAMO) 
						? 'checked' : '',
				];
			}

		$vars_template['CANTIDAD_CURSOS'] = count(App\Modelo\EmpleadoCursos::listar($empleado->id));	
		$vars_template['HISTORIAL_CURSOS'] 	= \App\Helper\Vista::get_url("index.php/legajos/historial_cursos/{$empleado->cuit}");
	}
		
		$vars_template	= [
			'FORMACION'	=> [0	=> $vars_template],
		];
	} else {

		foreach ($empleado->persona->titulos as $i => $t) {
			$vars_template['TITULOS'][$i]	= [
				'TIPO_TITULO'		=> Arr::path($parametricos, "formacion_tipo_titulo.{$t->id_tipo_titulo}.nombre", ''),
				'ESTADO_TITULO'		=> Arr::path($parametricos, "formacion_estado_titulo.{$t->id_estado_titulo}.nombre", ''),
				'DESCRIPCION'		=> Arr::path($parametricos, "titulo.{$t->id_tipo_titulo}.{$t->id_titulo}.nombre",''),
				'FECHA'				=> ($t->fecha instanceof \DateTime) ? $t->fecha->format('d/m/Y') : '',
			];
			if(!empty($t->principal)){
				$vars_template['TITULOS'][$i]['PRINCIPAL'] = [['CHECKED' => 'true']];
			}
		}
		if(empty($vars_template['TITULOS'])) {
			$vars_template['SIN_TITULOS'][]	= [
					'MSJ'	=> "No se cargaron titulos",
				];
		}
		foreach ($empleado->persona->otros_conocimientos as $oc) {
			if($oc->id_tipo	== \App\Modelo\PersonaOtroConocimiento::ESTUDIO){
				$vars_template['OTROS_ESTUDIOS'][$i]	= [
					'DESCRIPCION'		=> $oc->descripcion,
					'FECHA'				=> ($oc->fecha instanceof \DateTime) ? $oc->fecha->format('d/m/Y') : '',
				];
			}
			if($oc->id_tipo	== \App\Modelo\PersonaOtroConocimiento::CONOCIMIENTO){
				$vars_template['OTROS_CONOCIMIENTOS'][$i]	= [
					'DESCRIPCION'			=> $oc->descripcion,
				];
			}
		}

		if(empty($vars_template['OTROS_ESTUDIOS'])) {
			$vars_template['SIN_ESTUDIOS'][]	= [
					'MSJ'	=> "No se cargaron otros estudios",
				];
		}

		if(empty($vars_template['OTROS_CONOCIMIENTOS'])) {
			$vars_template['SIN_CONOCIMIENTOS'][]	= [
					'MSJ'	=> "No se cargaron conocimientos",
				];
		}

		foreach ($empleado->empleado_cursos as $i => $c) {
			$vars_template['CURSOS'][$i]	= [
				'TITULO_CURSO'		=> Arr::path($parametricos, "formacion_cursos.{$c->id_curso}.nombre", ''),
				'CREDITOS'			=> Arr::path($parametricos['formacion_cursos'], $c->id_curso.'.creditos', ''),
				'FECHA'				=> ($c->fecha instanceof \DateTime) ? $c->fecha->format('d/m/Y') : '',

			];
			if($c->tipo_promocion == \App\Modelo\Curso::PROMOCION_TRAMO){
				$vars_template['CURSOS'][$i]['TIPO_PROMOCION'] = [['CHECKED' => 'true']];
			}
		}

		if(empty($vars_template['CURSOS'])) {
			$vars_template['SIN_CURSOS'][]	= [
					'MSJ'	=> "No se cargaron cursos",
				];
		}

		$vars_template	= [
			'SPAN_FORMACION'	=> [0	=> $vars_template]
		];
	}
	$vars_template['ADJUNTAR_DOC'] = $adjuntar_doc;

	if(empty($empleado->id) || empty($empleado->cuit)){
	 	$vars_template	= [];
    	$vars_template['AVISO'][]['MSJ'] = 'PARA DEFINIR LA <strong>FORMACIÓN</strong>, ES REQUISITO TENER LOS DATOS BÁSICOS DEL <strong>AGENTE</strong> COMPLETOS.';
    }
    $formacion = new \FMT\Template(TEMPLATE_PATH.'/legajos/formacion.html', $vars_template,['CLEAN'=>false]);