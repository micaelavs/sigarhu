<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;

   $unidad_retri = json_encode($unidad_retributiva, JSON_UNESCAPED_UNICODE);

	$excepcion_FORMACION	= (!empty($excepcion_articulo = json_decode($empleado->situacion_escalafonaria->exc_art_14, 1))) 
							? array_column($excepcion_articulo ,'excepcion') : [];
	$select_FORMACION = '';
	foreach($parametricos['exc_art_14']	as $key => &$value){
		if (is_array($excepcion_FORMACION)) {
			if(in_array($key, $excepcion_FORMACION)){
				$select_FORMACION .= ($select_FORMACION)  ? ' | '.$value['nombre'] : $value['nombre'];
			}
		}
	}

	$temp = $vars_template	= [
		'SPANE'	=> [0	=> [
			'UNIDAD_RETRIBUTIVA' 		=> $empleado->situacion_escalafonaria->unidad_retributiva,
			'NIVEL' 					=> Arr::path($parametricos,
				"convenios_parametricos.agrupamientos.{$empleado->situacion_escalafonaria->id_agrupamiento}.niveles.{$empleado->situacion_escalafonaria->id_nivel}.nombre", ''),
			'GRADO' 					=> Arr::path($parametricos,
				"convenios_parametricos.tramos.{$empleado->situacion_escalafonaria->id_tramo}.grados.{$empleado->situacion_escalafonaria->id_grado}.nombre", ''),
			'AGRUPAMIENTO' 				=> Arr::path($parametricos,
				"convenios_parametricos.agrupamientos.{$empleado->situacion_escalafonaria->id_agrupamiento}.nombre", ''),
			'TRAMO' 					=> Arr::path($parametricos,
				"convenios_parametricos.tramos.{$empleado->situacion_escalafonaria->id_tramo}.nombre", ''),
			'MODALIDAD_VINCULACION' 	=> Arr::path($parametricos,
				"modalidad_vinculacion.{$empleado->situacion_escalafonaria->id_modalidad_vinculacion}.nombre", ''),
			'SITUACION_REVISTA' 		=> Arr::path($parametricos,
				"situacion_revista.{$empleado->situacion_escalafonaria->id_modalidad_vinculacion}.{$empleado->situacion_escalafonaria->id_situacion_revista}.nombre", ''),
			'COMPENSACION_TRANSITORIA'	=> $empleado->situacion_escalafonaria->compensacion_transitoria,
			'COMPENSACION_GEOGRAFICA' 	=> $empleado->situacion_escalafonaria->compensacion_geografica,
			'FUNCION_EJECUTIVA' 		=> Arr::path($parametricos,
				"convenios_parametricos.funciones_ejecutivas.{$empleado->situacion_escalafonaria->id_funcion_ejecutiva}.nombre", ''),
			'ID_SINDICATO' 				=>  Arr::path($parametricos['id_sindicato'],"{$empleado->id_sindicato}.nombre",[]),
			'ULTIMO_CAMBIO_NIVEL'		=> is_object($empleado->situacion_escalafonaria->ultimo_cambio_nivel) ? $empleado->situacion_escalafonaria->ultimo_cambio_nivel->format('d/m/Y') : '',
			'ULTIMO_CAMBIO_GRADO'		=> is_object($empleado->situacion_escalafonaria->ultimo_cambio_grado) ? $empleado->situacion_escalafonaria->ultimo_cambio_grado->format('d/m/Y') : '',
			'FECHA_VIGENCIA_MANDATO'	=> is_object($empleado->fecha_vigencia_mandato) ? $empleado->fecha_vigencia_mandato->format('d/m/Y') : '',
			'EXC_ART_14'				=> is_null($empleado->situacion_escalafonaria->exc_art_14) ?   "NO." :  "SI.",
			'FORMACION'					=> $select_FORMACION,
			'DELEGADO_GREMIAL'			=> is_null($empleado->id_sindicato) ?   "NO." :  "SI.",
			'VOLVER'					=> $parametricos['boton_volver']

		]],
	];

	if($permisos['escalafonario']){
		$vars_template	= [
			'INPUT'	=> [0	=> []],
		];
		$vars_template['INPUT'][0]['ADJUNTAR_DOC'] = $adjuntar_doc;
		
		foreach ($permisos['escalafonario'] as $campo => $permiso) {
			$vars_template['INPUT'][0]['FORM'] = \App\Helper\Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");
			if (
				$empleado->situacion_escalafonaria->id_modalidad_vinculacion == App\Modelo\Contrato::PRESTACION_SERVICIOS ||
				(is_null($empleado->situacion_escalafonaria->id_modalidad_vinculacion) && in_array(App\Modelo\Contrato::PRESTACION_SERVICIOS, \App\Modelo\AppRoles::obtener_modalidades_vinculacion_autorizadas()))
			) {
				$vars_template['INPUT'][0]['SITUACION_ESCALAFONARIA'] = new \FMT\Template(TEMPLATE_PATH.'/legajos/situacion_escalafonaria_convenios.html', [],['CLEAN'=>false]);
				switch ($campo) {
					case 'unidad_retributiva':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['VALUE']				= $empleado->situacion_escalafonaria->unidad_retributiva;
						}else{
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']			= $empleado->situacion_escalafonaria->unidad_retributiva;
						}
					break;
					case 'modalidad_vinculacion':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['MODALIDADES_VINCULACION']= Template::select_block($parametricos['modalidad_vinculacion'], $empleado->situacion_escalafonaria->id_modalidad_vinculacion);
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']			= $temp['SPANE'][0][strtoupper($campo)];
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['ID']				= $empleado->situacion_escalafonaria->id_modalidad_vinculacion;
						}
						break;
					case 'situacion_revista':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['SITUACIONES_REVISTA']	= !empty($empleado->situacion_escalafonaria->id_modalidad_vinculacion)
									? Template::select_block($parametricos['situacion_revista'][$empleado->situacion_escalafonaria->id_modalidad_vinculacion], $empleado->situacion_escalafonaria->id_situacion_revista)
									: [];
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']			= $temp['SPANE'][0][strtoupper($campo)];
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['ID']				= $empleado->situacion_escalafonaria->id_situacion_revista;
						}
						break;
					case 'nivel':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['NIVELES'] 				= !empty($empleado->situacion_escalafonaria->id_agrupamiento)
									?  Template::select_block($parametricos['convenios_parametricos']['agrupamientos'][$empleado->situacion_escalafonaria->id_agrupamiento]['niveles'], $empleado->situacion_escalafonaria->id_nivel)
									: [];
							$vars_template['INPUT'][0]['AGRUPAMIENTO'] = $empleado->situacion_escalafonaria->id_agrupamiento;
							$vars_template['INPUT'][0]['TRAMO']	= $empleado->situacion_escalafonaria->id_tramo;
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']			= $temp['SPANE'][0][strtoupper($campo)];
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['ID']				= $empleado->situacion_escalafonaria->id_nivel;
						}
						break;
					case 'grado':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['GRADOS'] 				= !empty($empleado->situacion_escalafonaria->id_tramo)
									?  Template::select_block(\FMT\Helper\Arr::path($parametricos,"convenios_parametricos.tramos.{$empleado->situacion_escalafonaria->id_tramo}.grados",[]), $empleado->situacion_escalafonaria->id_grado)
									: [];
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']			= $temp['SPANE'][0][strtoupper($campo)];
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['ID']				= $empleado->situacion_escalafonaria->id_grado;
						}
						break;
					case 'fecha_vigencia_mandato':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['FECHA_VIGENCIA']	= is_object($empleado->fecha_vigencia_mandato) ? $empleado->fecha_vigencia_mandato->format('d/m/Y') : '';
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']	= is_object($empleado->fecha_vigencia_mandato) ? $empleado->fecha_vigencia_mandato->format('d/m/Y') : '';
						}
						break;
					case 'id_sindicato':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['SINDICATOS']	= !empty($empleado->id_sindicato)
									?  Template::select_block($parametricos['id_sindicato'], $empleado->id_sindicato)
									: [];
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']			= $temp['SPANE'][0][strtoupper($campo)];
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['ID']				= $empleado->id_sindicato;
						}
						break;
					case 'delegado_gremial':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['CHECKED']	=  ($empleado->id_sindicato) ? "checked" :  "";
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']	=  is_null($empleado->id_sindicato) ?   "NO." :  "SI.";
							
						}
						break;
					case 'exc_art_14':
					  	if ($empleado->situacion_escalafonaria->id_modalidad_vinculacion == App\Modelo\Contrato::SINEP && $empleado->situacion_escalafonaria->id_situacion_revista == App\Modelo\Contrato::LEY_MARCO) {
							if($permiso) {
								$vars_template['INPUT'][0]['SUB_TITULO_EXC'][0][strtoupper($campo)][0]['CHECKED'] = ($empleado->situacion_escalafonaria->exc_art_14) ?  "checked" : "";
							} else {
								$vars_template['INPUT'][0]['SUB_TITULO_EXC'][0][strtoupper('span_'.$campo)][0]['VALUE']	=  empty($empleado->situacion_escalafonaria->exc_art_14) ?   "NO." :  "SI.";						
							}
						}
						break;
					case 'formacion':
					  	if ($empleado->situacion_escalafonaria->id_modalidad_vinculacion == App\Modelo\Contrato::SINEP && $empleado->situacion_escalafonaria->id_situacion_revista == App\Modelo\Contrato::LEY_MARCO) {
							$excepcion_articulo = json_decode($empleado->situacion_escalafonaria->exc_art_14, 1);
							$excepcion = (!empty($excepcion_articulo)) ? array_column($excepcion_articulo ,'excepcion') : [];
							if($permiso) {
								$vars_template['INPUT'][0]['SUB_TITULO_EXC'][0][strtoupper($campo)][0]['FORMACIONES'] =  \FMT\Helper\Template::select_block($parametricos['exc_art_14'], $excepcion);
							} else {
								$select = '';
								foreach ($parametricos['exc_art_14'] as $key => $value) {
									if (is_array($excepcion)) {
										if(in_array($key, $excepcion)){
											$select .= ($select)  ? ' | '.$value['nombre'] : $value['nombre'];
										}
									}
								}
								$vars_template['INPUT'][0]['SUB_TITULO_EXC'][0][strtoupper('span_'.$campo)][0]['VALUE'] = $select;
							}
						}
						break;	
				}

				if (Arr::get($unidad_retributiva,$empleado->situacion_escalafonaria->id_nivel) !== null) {
					$vars_template['INPUT'][0]['MIN']= ['VALUE'		=> $unidad_retributiva[$empleado->situacion_escalafonaria->id_nivel][$empleado->situacion_escalafonaria->id_grado]['min']];
					$vars_template['INPUT'][0]['MAX']= ['VALUE'		=>$unidad_retributiva[$empleado->situacion_escalafonaria->id_nivel][$empleado->situacion_escalafonaria->id_grado]['max']];
				}else{
					$vars_template['INPUT'][0]['MIN']= ['VALUE'		=> 'S/D'];
					$vars_template['INPUT'][0]['MAX']= ['VALUE'		=> 'S/D'];
				}

			}else{
				$vars_template['INPUT'][0]['SITUACION_ESCALAFONARIA'] = new \FMT\Template(TEMPLATE_PATH.'/legajos/situacion_escalafonaria_sinep.html', [],['CLEAN'=>false]);
				switch ($campo) {
					case 'modalidad_vinculacion':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['MODALIDADES_VINCULACION']= Template::select_block($parametricos['modalidad_vinculacion'], $empleado->situacion_escalafonaria->id_modalidad_vinculacion);
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']			= Arr::path($parametricos, "modalidad_vinculacion.{$empleado->situacion_escalafonaria->id_modalidad_vinculacion}.nombre", '');
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['ID']				= $empleado->situacion_escalafonaria->id_modalidad_vinculacion;
						}
						break;
					case 'situacion_revista':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['SITUACIONES_REVISTA']	= !empty($empleado->situacion_escalafonaria->id_modalidad_vinculacion)
									? Template::select_block($parametricos['situacion_revista'][$empleado->situacion_escalafonaria->id_modalidad_vinculacion], $empleado->situacion_escalafonaria->id_situacion_revista)
									: [];
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']			= Arr::path($parametricos,"situacion_revista.{$empleado->situacion_escalafonaria->id_modalidad_vinculacion}.{$empleado->situacion_escalafonaria->id_situacion_revista}.nombre",'');
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['ID']				= $empleado->situacion_escalafonaria->id_situacion_revista;
						}
						break;
					case 'nivel':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['NIVELES'] 				= Template::select_block(Arr::path($parametricos['convenios_parametricos']['agrupamientos'], $empleado->situacion_escalafonaria->id_agrupamiento.'.niveles', []), $empleado->situacion_escalafonaria->id_nivel);
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']			= Arr::path($parametricos,"convenios_parametricos.agrupamientos.{$empleado->situacion_escalafonaria->id_agrupamiento}.niveles.{$empleado->situacion_escalafonaria->id_nivel}.nombre",'');
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['ID']				= $empleado->situacion_escalafonaria->id_nivel;
						}
						break;
					case 'grado':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['GRADOS'] 				= !empty($empleado->situacion_escalafonaria->id_tramo)
									?  Template::select_block(\FMT\Helper\Arr::path($parametricos,"convenios_parametricos.tramos.{$empleado->situacion_escalafonaria->id_tramo}.grados",[]), $empleado->situacion_escalafonaria->id_grado)
									: [];
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']			= Arr::path($parametricos,"convenios_parametricos.tramos.{$empleado->situacion_escalafonaria->id_tramo}.grados.{$empleado->situacion_escalafonaria->id_grado}.nombre",'');
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['ID']				= $empleado->situacion_escalafonaria->id_grado;
						}
						break;
					case 'grado_liquidacion':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['GRADOS_LIQUIDACION'] 				= !empty($empleado->situacion_escalafonaria->id_tramo)
									?  Template::select_block(\FMT\Helper\Arr::path($parametricos,"convenios_parametricos.tramos.{$empleado->situacion_escalafonaria->id_tramo}.grados",[]), $empleado->situacion_escalafonaria->id_grado_liquidacion)
									: [];
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']			= Arr::path($parametricos,"convenios_parametricos.tramos.{$empleado->situacion_escalafonaria->id_tramo}.grados.{$empleado->situacion_escalafonaria->id_grado_liquidacion}.nombre",'');
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['ID']				= $empleado->situacion_escalafonaria->id_grado_liquidacion;
						}
						break;
					case 'compensacion_transitoria':
						$campo	= ($permiso) ? strtoupper($campo) : strtoupper('span_'.$campo);
						$vars_template['INPUT'][0][$campo][0]['VALUE']									= $empleado->situacion_escalafonaria->compensacion_transitoria;
						break;
					case 'compensacion_geografica':
						$campo	= ($permiso) ? strtoupper($campo) : strtoupper('span_'.$campo);
						$vars_template['INPUT'][0][$campo][0]['VALUE']									= $empleado->situacion_escalafonaria->compensacion_geografica;
						break;
					case 'agrupamiento':
						if($permiso) {
							$temp = $parametricos['convenios_parametricos']['agrupamientos'];
							$vars_template['INPUT'][0][strtoupper($campo)][0]['AGRUPAMIENTOS']			= !empty($temp)
									?  Template::select_block($temp, $empleado->situacion_escalafonaria->id_agrupamiento)
									: [];
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']			= Arr::path($parametricos,"convenios_parametricos.agrupamientos.{$empleado->situacion_escalafonaria->id_agrupamiento}.nombre",'');
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['ID']				= $empleado->situacion_escalafonaria->id_agrupamiento;
						}
						break;
					case 'tramo':
						if($permiso) {
							$temp = $parametricos['convenios_parametricos']['tramos'];
							$vars_template['INPUT'][0][strtoupper($campo)][0]['TRAMOS']					= !empty($temp)
									?  Template::select_block($temp, $empleado->situacion_escalafonaria->id_tramo)
									: [];
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']			= Arr::path($parametricos,"convenios_parametricos.tramos.{$empleado->situacion_escalafonaria->id_tramo}.nombre",'');
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['ID']				= $empleado->situacion_escalafonaria->id_tramo;
						}
						break;
					case 'funcion_ejecutiva':
						if($permiso) {
							$temp = $parametricos['convenios_parametricos']['funciones_ejecutivas'];
							$vars_template['INPUT'][0][strtoupper($campo)][0]['FUNCIONES_EJECUTIVAS']	= !empty($temp)
									?  Template::select_block($temp, $empleado->situacion_escalafonaria->id_funcion_ejecutiva)
									: [];
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']			= Arr::path($parametricos,"convenios_parametricos.funciones_ejecutivas.{$empleado->situacion_escalafonaria->id_funcion_ejecutiva}.nombre",'');
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['ID']				= $empleado->situacion_escalafonaria->id_funcion_ejecutiva;
						}
						break;
				
					case 'ultimo_cambio_nivel':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['CAMBIO_NIVEL']	= !empty($empleado->situacion_escalafonaria->ultimo_cambio_nivel) ? $empleado->situacion_escalafonaria->ultimo_cambio_nivel->format('d/m/Y') : '';

							// $vars_template['INPUT'][0]['ULTIMO_CAMBIO_GRADO'][0]['CAMBIO_GRADO']	= !empty($empleado->situacion_escalafonaria->ultimo_cambio_grado) ? $empleado->situacion_escalafonaria->ultimo_cambio_grado->format('d/m/Y') : '';
							$vars_template['INPUT'][0]['SPAN_ULTIMO_CAMBIO_GRADO'][0]['VALUE']	= is_object($empleado->situacion_escalafonaria->ultimo_cambio_grado) ? $empleado->situacion_escalafonaria->ultimo_cambio_grado->format('d/m/Y') : '';
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']	= is_object($empleado->situacion_escalafonaria->ultimo_cambio_nivel) ? $empleado->situacion_escalafonaria->ultimo_cambio_nivel->format('d/m/Y') : '';

							$vars_template['INPUT'][0]['SPAN_ULTIMO_CAMBIO_GRADO'][0]['VALUE']	= is_object($empleado->situacion_escalafonaria->ultimo_cambio_grado) ? $empleado->situacion_escalafonaria->ultimo_cambio_grado->format('d/m/Y') : '';
						}
						break;
					case 'fecha_vigencia_mandato':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['FECHA_VIGENCIA']	= is_object($empleado->fecha_vigencia_mandato) ? $empleado->fecha_vigencia_mandato->format('d/m/Y') : '';
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']	= is_object($empleado->fecha_vigencia_mandato) ? $empleado->fecha_vigencia_mandato->format('d/m/Y') : '';
						}
						break;
					case 'id_sindicato':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['SINDICATOS']	= !empty($empleado->id_sindicato)
									?  Template::select_block($parametricos['id_sindicato'], $empleado->id_sindicato)
									: [];
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']			= Arr::path($parametricos['id_sindicato'], "{$empleado->id_sindicato}.nombre", []);
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['ID']				= $empleado->id_sindicato;
						}
						break;
					case 'delegado_gremial':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['CHECKED']	=  ($empleado->id_sindicato) ? "checked" :  "";
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']	=  is_null($empleado->id_sindicato) ?   "NO." :  "SI.";
							
						}
						break;
					case 'exc_art_14':
					  	if ($empleado->situacion_escalafonaria->id_modalidad_vinculacion == App\Modelo\Contrato::SINEP && $empleado->situacion_escalafonaria->id_situacion_revista == App\Modelo\Contrato::LEY_MARCO) {
							if($permiso) {
								$vars_template['INPUT'][0]['SUB_TITULO_EXC'][0][strtoupper($campo)][0]['CHECKED'] = ($empleado->situacion_escalafonaria->exc_art_14) ?  "checked" : "";
							} else {
								$vars_template['INPUT'][0]['SUB_TITULO_EXC'][0][strtoupper('span_'.$campo)][0]['VALUE']	=  empty($empleado->situacion_escalafonaria->exc_art_14) ?   "NO." :  "SI.";						
							}
						}
						break;
					case 'formacion':
					  	if ($empleado->situacion_escalafonaria->id_modalidad_vinculacion == App\Modelo\Contrato::SINEP && $empleado->situacion_escalafonaria->id_situacion_revista == App\Modelo\Contrato::LEY_MARCO) {
							$excepcion_articulo = json_decode($empleado->situacion_escalafonaria->exc_art_14, 1);
							$excepcion = (!empty($excepcion_articulo)) ? array_column($excepcion_articulo ,'excepcion') : [];
							if($permiso) {
								$vars_template['INPUT'][0]['SUB_TITULO_EXC'][0][strtoupper($campo)][0]['FORMACIONES'] =  \FMT\Helper\Template::select_block($parametricos['exc_art_14'], $excepcion);
							} else {
								$select = '';
								foreach ($parametricos['exc_art_14'] as $key => $value) {
									if (is_array($excepcion)) {
										if(in_array($key, $excepcion)){
											$select .= ($select)  ? ' | '.$value['nombre'] : $value['nombre'];
										}
									}
								}
								$vars_template['INPUT'][0]['SUB_TITULO_EXC'][0][strtoupper('span_'.$campo)][0]['VALUE'] = $select;
							}
						}
						break;			
				}
			}
			$vars_template['INPUT'][0]['VOLVER'] = $parametricos['boton_volver'];
		}
		unset($vars_template['SPANE']);
    }else{
    	if($empleado->situacion_escalafonaria->id_modalidad_vinculacion == App\Modelo\Contrato::PRESTACION_SERVICIOS) {
				$vars_template['SPAN'] = new \FMT\Template(TEMPLATE_PATH.'/legajos/span_convenios.html', [],['CLEAN'=>false]);
	    }else{
			$vars_template['SPAN'] = new \FMT\Template(TEMPLATE_PATH.'/legajos/span_sinep.html', [],['CLEAN'=>false]);
	    }
    }


    if(empty($empleado->id) || empty($empleado->cuit)){
    	unset($vars_template['INPUT']);
    	unset($vars_template['SPANE']);
    	$vars_template['AVISO'][]['MSJ'] = 'PARA DEFINIR LA <strong>SITUACION ESCALAFONARIA</strong>, ES REQUISITO TENER LOS DATOS BÁSICOS DEL <strong>AGENTE</strong> COMPLETOS.';
    }

    if(isset($vars_template['INPUT'])) {
		$vars_template['INPUT'][0]['ADJUNTAR_DOC'] = $adjuntar_doc;
    } else {
		$vars_template['SPANE'][0]['ADJUNTAR_DOC'] = $adjuntar_doc;
    }
$rol = App\Modelo\AppRoles::obtener_rol();
if ($rol == App\Modelo\AppRoles::ROL_CONVENIOS) {
	$vars_vista['JS'][]['JS_CODE']	= <<<JS
	var \$unidad_retributiva = {$unidad_retri};
JS;
}
	$escalafonaria = new \FMT\Template(TEMPLATE_PATH.'/legajos/situacion_escalafonaria.html', $vars_template,['CLEAN'=>true]);
