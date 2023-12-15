<?php
use \FMT\Helper\Arr;
/**
 * @var array                 	$permisos
 * @var array 					$vars_template
 */

	$perfil = $empleado->perfil_puesto;
	$campos =['familia_puestos','nombre_puesto','nivel_destreza','puesto_supervisa','nivel_complejidad','denominacion_funcion','denominacion_puesto','objetivo_general','objetivo_especificos','estandares','actividades','fecha_obtencion_result','resultados_finales','evaluacion'];

	$vars_template = $campos_perfiles_puestos =  [];
	if($permisos['perfiles_puestos']) {
		if($empleado->id) {
			$vars_template['PERFILES_PUESTOS'][0]['ADJUNTAR_DOC'] = $adjuntar_doc;
			foreach ($campos as $campo) {
				switch ($campo) {
					case 'familia_puestos':
						if($permisos['perfiles_puestos'][$campo]) {
							$vars_template['PERFILES_PUESTOS'][0]['FAMILIAS_PUESTOS'][0]['FAMILIA_PUESTOS']	= \FMT\Helper\Template::select_block($parametricos['familia_de_puesto'],$perfil->familia_de_puestos);
						} else {
							$vars_template['PERFILES_PUESTOS'][0]['SPAN_FAMILIAS_PUESTOS'][0]['VALUE']	= \FMT\Helper\Arr::get($parametricos['familia_de_puesto'],$perfil->familia_de_puestos) ? $parametricos['familia_de_puesto'][$perfil->familia_de_puestos]['nombre'] :'';
						}
						break;
					case 'nombre_puesto':
						if($permisos['perfiles_puestos'][$campo]) {
							$vars_template['PERFILES_PUESTOS'][0]['NOMBRES_PUESTOS'][0] = [];
							foreach ($parametricos['puestos'] as $key => $value) {
								$vars_template['PERFILES_PUESTOS'][0]['NOMBRES_PUESTOS'][0]['SUB_FAMILIA'][$key]['SUB']	= $value['nombre'];
								$vars_template['PERFILES_PUESTOS'][0]['NOMBRES_PUESTOS'][0]['SUB_FAMILIA'][$key]['NOMBRE_PUESTO']= \FMT\Helper\Template::select_block($value['puestos'], $perfil->nombre_puesto);
							}
						} else {
							$vars_template['PERFILES_PUESTOS'][0]['SPAN_NOMBRES_PUESTOS'][0]['VALUE']	= \FMT\Helper\Arr::get($parametricos['nombre_de_puesto'],$perfil->nombre_puesto) ? $parametricos['nombre_de_puesto'][$perfil->nombre_puesto]['nombre'] : '';
						}
						break;
					case 'nivel_destreza':
						if($permisos['perfiles_puestos'][$campo]) {
							$vars_template['PERFILES_PUESTOS'][0]['NIVELES_DESTREZA'][0]['NIVEL_DESTREZA']	= \FMT\Helper\Template::select_block($parametricos['nivel_de_destreza'], $perfil->nivel_destreza);
						} else {
							$vars_template['PERFILES_PUESTOS'][0]['SPAN_NIVELES_DESTREZA'][0]['VALUE']	= \FMT\Helper\Arr::get($parametricos['nivel_de_destreza'],$perfil->nivel_destreza) ? $parametricos['nivel_de_destreza'][$perfil->nivel_destreza]['nombre'] :'';
						}
						break;
 					case 'puesto_supervisa':
						if($permisos['perfiles_puestos'][$campo]) {
							$vars_template['PERFILES_PUESTOS'][0]['PUESTOS_SUPERVISA'][0]['PUESTO_SUPERVISA']	= \FMT\Helper\Template::select_block($parametricos['niveles_puesto_supervisa'], $perfil->puesto_supervisa);
						} else {
							$vars_template['PERFILES_PUESTOS'][0]['SPAN_PUESTOS_SUPERVISA'][0]['VALUE']	= \FMT\Helper\Arr::get($parametricos['niveles_puesto_supervisa'],$perfil->puesto_supervisa) ? $parametricos['niveles_puesto_supervisa'][$perfil->puesto_supervisa]['nombre'] : '';
						}
						break;
 					case 'nivel_complejidad':
						if($permisos['perfiles_puestos'][$campo]) {
							$vars_template['PERFILES_PUESTOS'][0]['COMPLEJIDADES_TAREAS'][0]['COMPLEJIDAD_TAREA']	= \FMT\Helper\Template::select_block($parametricos['niveles_complejidad'],$perfil->nivel_complejidad);
						} else {
							$vars_template['PERFILES_PUESTOS'][0]['SPAN_COMPLEJIDADES_TAREAS'][0]['VALUE']	= \FMT\Helper\Arr::get($parametricos['niveles_complejidad'],$perfil->nivel_complejidad) ? $parametricos['niveles_complejidad'][$perfil->nivel_complejidad]['nombre'] : '';
						}
						break;
 					case 'denominacion_funcion':
						if($permisos['perfiles_puestos'][$campo]) {
							$vars_template['PERFILES_PUESTOS'][0]['TDR_DENOMINACIONES_FUNCION'][0]['TDR_DENOMINACION_FUNCION']	= \FMT\Helper\Template::select_block($parametricos['denominacion_de_la_funcion'], $perfil->denominacion_funcion);
						} else {
							$vars_template['PERFILES_PUESTOS'][0]['SPAN_DENOMINACIONES_FUNCION'][0]['VALUE']	=	\FMT\Helper\Arr::get($parametricos['denominacion_de_la_funcion'],$perfil->denominacion_funcion) ? $parametricos['denominacion_de_la_funcion'][$perfil->denominacion_funcion]['nombre'] :'';
						}
						break;
 					case 'denominacion_puesto':
						if($permisos['perfiles_puestos'][$campo]) {
							$vars_template['PERFILES_PUESTOS'][0]['DENOMINACIONES_PUESTO'][0]['DENOMINACION_PUESTO']	= \FMT\Helper\Template::select_block($parametricos['denominacion_del_puesto'], $perfil->denominacion_puesto);
						} else {
							$vars_template['PERFILES_PUESTOS'][0]['SPAN_DENOMINACIONES_PUESTO'][0]['VALUE']	=	\FMT\Helper\Arr::get($parametricos['denominacion_del_puesto'],$perfil->denominacion_puesto) ? $parametricos['denominacion_del_puesto'][$perfil->denominacion_puesto]['nombre'] :'';
						}
						break;
 					case 'objetivo_general':
						if($permisos['perfiles_puestos'][$campo]) {
							$vars_template['PERFILES_PUESTOS'][0]['OBJETIVOS_GENERALES'][0]['OBJETIVO_GENERAL']	= $perfil->objetivo_gral;
						} else {
							$vars_template['PERFILES_PUESTOS'][0]['SPAN_OBJETIVOS_GENERALES'][0]['VALUE']	= $perfil->objetivo_gral;
						}
						break;
 					case 'objetivo_especificos':
						if($permisos['perfiles_puestos'][$campo]) {
							$vars_template['PERFILES_PUESTOS'][0]['OBJETIVOS_ESPECIFICOS'][0]['OBJETIVO_ESPECIFICO']	= $perfil->objetivo_especifico;
						} else {
							$vars_template['PERFILES_PUESTOS'][0]['SPAN_OBJETIVOS_ESPECIFICOS'][0]['VALUE']	= $perfil->objetivo_especifico;
						}
						break;
 					case 'estandares':
						if($permisos['perfiles_puestos'][$campo]) {
							$vars_template['PERFILES_PUESTOS'][0]['CUANTI'][0]['ESTANDAR']	= $perfil->estandares;
						} else {
							$vars_template['PERFILES_PUESTOS'][0]['SPAN_ESTANDARES'][0]['VALUE']	= $perfil->estandares;
						}
						break;
 					case 'actividades':
						if(!is_null(\FMT\Helper\Arr::get($perfil->actividad,0)) || isset($perfil->actividad)) {
							foreach($perfil->actividad as $index => $dato){
								if($permisos['perfiles_puestos'][$campo]) {
									$vars_template['PERFILES_PUESTOS'][0]['TODOS_ACTIVIDADES'][]['ACTIVIDADES'][] = ['TEXT' => $dato->nombre, 'VALUE' => $dato->id];
								} else {
									$vars_template['PERFILES_PUESTOS'][0]['TODOS_ACTIVIDADES'][]['SPAN_ACTIVIDADES'][]['VALUE']	= $dato->nombre;
								}
							}
						}	
						if($permisos['perfiles_puestos'][$campo]) {
							$vars_template['PERFILES_PUESTOS'][0]['NUEVAS_ACTIVIDADES'][0]['TEXT'] = '';
						}elseif(empty($perfil->actividad)) {
							$vars_template['PERFILES_PUESTOS'][0]['TODOS_ACTIVIDADES'][]['SPAN_ACTIVIDADES'][]['VALUE'] = '';
						}
						break;

 					case 'fecha_obtencion_result':
						if($permisos['perfiles_puestos'][$campo]) {
							$vars_template['PERFILES_PUESTOS'][0]['OBTENCION_RESULT'][0]['FECHA_OBTENCION_RESULT']	= $perfil->fecha_obtencion_result ? $perfil->fecha_obtencion_result->format('d/m/Y') : '';
						} else {
							$vars_template['PERFILES_PUESTOS'][0]['SPAN_OBTENCION_RESULT'][0]['VALUE']	= $perfil->fecha_obtencion_result ? $perfil->fecha_obtencion_result->format('d/m/Y') : '';
						}
						break;
 					case 'resultados_finales':
 						if(!is_null(\FMT\Helper\Arr::get($perfil->resultados_parciales_finales,0)) || isset($perfil->resultados_parciales_finales)) {
							foreach($perfil->resultados_parciales_finales as $index => $dato) {

								if($permisos['perfiles_puestos'][$campo]) {
									$vars_template['PERFILES_PUESTOS'][0]['TODOS_LOS_RESULTADOS_FP'][]['RESULTADOS'][] = ['TEXT' => $dato->nombre, 'VALUE' => $index];
								} else {
									$vars_template['PERFILES_PUESTOS'][0]['TODOS_LOS_RESULTADOS_FP'][]['SPAN_RESULTADOS'][]['VALUE']	= $dato->nombre;
								}
							}
						}	
						if($permisos['perfiles_puestos'][$campo]) {
							$vars_template['PERFILES_PUESTOS'][0]['NUEVOS_RESULTADOS'][0]['TEXT'] = '';
						} elseif(empty($perfil->actividad)) {
							$vars_template['PERFILES_PUESTOS'][0]['TODOS_LOS_RESULTADOS_FP'][]['SPAN_RESULTADOS'][]['VALUE'] = '';
						}
						break;
					case 'evaluacion':
						$dato	= [];
						if(!empty($empleado->evaluaciones)){ 
							$dato	= (array)array_shift($empleado->evaluaciones);
						}

						$vars_template['PERFILES_PUESTOS'][0]['EVALUACIONES'][0]['ANIO']	= Arr::get($dato,'anio','');
						$vars_template['PERFILES_PUESTOS'][0]['EVALUACIONES'][0]['FORMULARIO']	= \FMT\Helper\Arr::get($parametricos['formularios_evaluacion'],Arr::get($dato,'formulario','')) 
							? $parametricos['formularios_evaluacion'][Arr::get($dato,'formulario','')]['nombre'] :'';
						$vars_template['PERFILES_PUESTOS'][0]['EVALUACIONES'][0]['EVALUACION']	= \FMT\Helper\Arr::get($parametricos['resultados_evaluacion'],Arr::get($dato,'evaluacion','')) 
							? $parametricos['resultados_evaluacion'][Arr::get($dato,'evaluacion','')]['nombre'] :'';
						$vars_template['PERFILES_PUESTOS'][0]['EVALUACIONES'][0]['ACTO_ADMINISTRATIVO']	= Arr::get($dato,'acto_administrativo','');
						if(Arr::get($dato,'id','')){
							$vars_template['PERFILES_PUESTOS'][0]['EVALUACIONES'][0]['ARCHIVO'][0]	= ['URL' => \App\Helper\Vista::get_url("index.php/legajos/mostrar_evaluacion/".Arr::get($dato,'id',''))];
						}
						$vars_template['PERFILES_PUESTOS'][0]['EVALUACIONES'][0]['PUNTAJE']	= Arr::get($dato,'puntaje','');
						$vars_template['PERFILES_PUESTOS'][0]['EVALUACIONES'][0]['BONIFICADO']	= Arr::get($dato,'bonificado','')==1?'SI':'NO';

						$vars_template['PERFILES_PUESTOS'][0]['EVALUACIONES'][0]['URL_HISTORIAL'][0]['VALUE']	 = \App\Helper\Vista::get_url("index.php/legajos/historial_evaluacion/{$empleado->cuit}");		
						$vars_template['PERFILES_PUESTOS'][0]['EVALUACIONES'][0]['URL_NUEVA_EVALUACION'][0]['VALUE']  = \App\Helper\Vista::get_url("index.php/legajos/nueva_evaluacion/{$empleado->cuit}");		
						break;
					default:
						# code...
						break;
				}
			}
			
			$vars_template['PERFILES_PUESTOS'][0]['FORM']	= \App\Helper\Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");

		} else {
			$vars_template['AVISO'][]['MSJ'] = 'PARA DEFINIR EL <strong>PERFIL DE PUESTO</strong>, ES REQUISITO TENER LOS DATOS BÁSICOS DEL <strong>AGENTE</strong>.';
		}
    } else {

			 $vars_template['SPAN_PERFILES_PUESTOS'][0] = [
			 	     
			 	     'FAMILIA_PUESTOS'  => \FMT\Helper\Arr::get($parametricos['familia_de_puesto'],$perfil->familia_de_puestos) ? $parametricos['familia_de_puesto'][$perfil->familia_de_puestos]['nombre'] :'',
					 
					 'DENOMINACION_PUESTO' => \FMT\Helper\Arr::get($parametricos['denominacion_del_puesto'],$perfil->denominacion_puesto) ? $parametricos['denominacion_del_puesto'][$perfil->denominacion_puesto]['nombre'] :'',
					 
					 'NIVEL_DESTREZA' => \FMT\Helper\Arr::get($parametricos['nivel_de_destreza'],$perfil->nivel_destreza) ? $parametricos['nivel_de_destreza'][$perfil->nivel_destreza]['nombre'] :'',
					 
					 'NOMBRE_PUESTO' => \FMT\Helper\Arr::get($parametricos['nombre_de_puesto'],$perfil->nombre_puesto) ? $parametricos['nombre_de_puesto'][$perfil->nombre_puesto]['nombre'] : '',
					 
					 'TAREAS'  => 'a',
					 
					 'TDR_TAREAS' => '',
					 
					 'TDR_DENOMINACION_FUNCION' => \FMT\Helper\Arr::get($parametricos['denominacion_de_la_funcion'],$perfil->denominacion_funcion) ? $parametricos['denominacion_de_la_funcion'][$perfil->denominacion_funcion]['nombre'] :'',

					  'PUESTO_SUPERVISA' => (\FMT\Helper\Arr::get($parametricos['niveles_puesto_supervisa'],$perfil->puesto_supervisa)) ? $parametricos['niveles_puesto_supervisa'][$perfil->puesto_supervisa]['nombre'] : '',
					 
					 'COMPLEJIDAD_TAREAS' => \FMT\Helper\Arr::get($parametricos['niveles_complejidad'],$perfil->nivel_complejidad) ? $parametricos['niveles_complejidad'][$perfil->nivel_complejidad]['nombre'] : '',
					 'OBJETIVO_GENERAL' => $perfil->objetivo_gral,
					 'OBJETIVOS_ESPECIFICOS' => $perfil->objetivo_especifico,
					 'ESTANDARES' => $perfil->estandares,
					 'OBTENCION_RESULT' => $perfil->fecha_obtencion_result ? $perfil->fecha_obtencion_result->format('d/m/Y') : ''

			];

			foreach($perfil->actividad as $index => $dato){
				$vars_template['SPAN_PERFILES_PUESTOS'][0]['SPAN_ACTIVIDADES'][]['VALUE']	= $dato->nombre;
			}
			foreach($perfil->resultados_parciales_finales as $index => $dato) {
				$vars_template['SPAN_PERFILES_PUESTOS'][0]['SPAN_RESULTADOS'][]['VALUE']	= $dato->nombre;
			}
			$dato	= [];
			if(!empty($empleado->evaluaciones)){
				$dato	= (array)array_shift($empleado->evaluaciones);
			}
			$vars_template['SPAN_PERFILES_PUESTOS'][0]['EVALUACIONES'][0]['ANIO']	= Arr::get($dato, 'anio', '');
			$vars_template['SPAN_PERFILES_PUESTOS'][0]['EVALUACIONES'][0]['FORMULARIO']	= \FMT\Helper\Arr::get($parametricos['formularios_evaluacion'],Arr::get($dato, 'formulario', '')) ? $parametricos['formularios_evaluacion'][Arr::get($dato, 'formulario', '')]['nombre'] :'';
			$vars_template['SPAN_PERFILES_PUESTOS'][0]['EVALUACIONES'][0]['EVALUACION']	= \FMT\Helper\Arr::get($parametricos['resultados_evaluacion'],Arr::get($dato, 'evaluacion', '')) ? $parametricos['resultados_evaluacion'][Arr::get($dato, 'evaluacion', '')]['nombre'] :'';
			$vars_template['SPAN_PERFILES_PUESTOS'][0]['EVALUACIONES'][0]['ACTO_ADMINISTRATIVO']	= Arr::get($dato, 'acto_administrativo', '');
			if(Arr::get($dato, 'id', '')){
				$vars_template['SPAN_PERFILES_PUESTOS'][0]['EVALUACIONES'][0]['ARCHIVO'][0]	= ['URL' => \App\Helper\Vista::get_url("index.php/legajos/mostrar_evaluacion/".Arr::get($dato, 'id', ''))];
			}
			$vars_template['SPAN_PERFILES_PUESTOS'][0]['EVALUACIONES'][0]['PUNTAJE']	= Arr::get($dato, 'puntaje', '');
			$vars_template['SPAN_PERFILES_PUESTOS'][0]['EVALUACIONES'][0]['BONIFICADO']	= Arr::get($dato, 'bonificado', '')==1?'SI':'NO';
			
			$vars_template['SPAN_PERFILES_PUESTOS'][0]['ADJUNTAR_DOC'] = $adjuntar_doc;
	}

	if(empty($empleado->id) && empty($empleado->cuit)){
	 	$vars_template	= [];
    	$vars_template['AVISO'][]['MSJ'] = 'PARA DEFINIR EL <strong>PERFIL DE PUESTO</strong>, ES REQUISITO TENER LOS DATOS BÁSICOS DEL <strong>AGENTE</strong> COMPLETOS.';
    }
	$perfiles_puestos = new \FMT\Template(TEMPLATE_PATH.'/legajos/perfiles_puestos.html', $vars_template,['CLEAN'=>false]);