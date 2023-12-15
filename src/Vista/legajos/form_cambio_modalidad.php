<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;

	$temp = $vars_template	= [
		'SPANE'	=> [0	=> [
			'UNIDAD_RETRIBUTIVA' 		=> '',
			'NIVEL' 					=> '',
			'GRADO' 					=> '',
			'AGRUPAMIENTO' 				=> '',
			'TRAMO' 					=> '',
			'MODALIDAD_VINCULACION' 	=> '',
			'SITUACION_REVISTA' 		=> '',
			'COMPENSACION_TRANSITORIA'	=> '',
			'COMPENSACION_GEOGRAFICA' 	=> '',
			'FUNCION_EJECUTIVA' 		=> '',
			'ID_SINDICATO' 				=> '',
			'ULTIMO_CAMBIO_NIVEL'		=> '',
			'FECHA_VIGENCIA_MANDATO'	=> '',
			'EXC_ART_14'				=> '',
			'FORMACION'					=> '',
			'DELEGADO_GREMIAL'			=> '',
			'VOLVER'					=> $parametricos['boton_volver']

		]],
	];

if($permisos['escalafonario']){
		$vars_template	= [
			'INPUT'	=> [0	=> []],
		];
		$vars_template['INPUT'][0]['ADJUNTAR_DOC'] = '';
		foreach ($permisos['escalafonario'] as $campo => $permiso) {

			$vars_template['INPUT'][0]['FORM'] = '';

				switch ($campo) {
					case 'unidad_retributiva':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['VALUE']				= '';
						}else{
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']		= '';
						}
					break;
					case 'modalidad_vinculacion':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['MODALIDADES_VINCULACION']= Template::select_block($parametricos['modalidad_vinculacion'], $modalidad_vinculacion);
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']			= $temp['SPANE'][0][strtoupper($campo)];
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['ID']				= '';
						}
						break;
					case 'situacion_revista':
                        if($permiso) {
                            $parametricos['situacion_revista'][$modalidad_vinculacion] = (!empty($parametricos['situacion_revista'][$modalidad_vinculacion])) ? $parametricos['situacion_revista'][$modalidad_vinculacion] :[];
                            $vars_template['INPUT'][0][strtoupper($campo)][0]['SITUACIONES_REVISTA']    = Template::select_block($parametricos['situacion_revista'][$modalidad_vinculacion], '');
                        } else {
                            $vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']            = $temp['SPANE'][0][strtoupper($campo)];
                            $vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['ID']                = '';
                        }
                        break;
					case 'nivel':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['NIVELES'] 				= [];
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']			= $temp['SPANE'][0][strtoupper($campo)];
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['ID']				= '';
						}
						break;
					case 'grado':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['GRADOS'] 				= [];
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']			= $temp['SPANE'][0][strtoupper($campo)];
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['ID']				= '';
						}
						break;
					case 'compensacion_transitoria':
						$campo	= ($permiso) ? strtoupper($campo) : strtoupper('span_'.$campo);
						$vars_template['INPUT'][0][$campo][0]['VALUE']									= '';
						break;
					case 'compensacion_geografica':
						$campo	= ($permiso) ? strtoupper($campo) : strtoupper('span_'.$campo);
						$vars_template['INPUT'][0][$campo][0]['VALUE']									= '';
						break;
					case 'agrupamiento':
						if($permiso) {
							 $vars_template['INPUT'][0][strtoupper($campo)][0]['AGRUPAMIENTOS']			= [];
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']			= $temp['SPANE'][0][strtoupper($campo)];
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['ID']				= '';
						}
						break;
					case 'tramo':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['TRAMOS']					= [];
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']			= $temp['SPANE'][0][strtoupper($campo)];
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['ID']				= '';
						}
						break;
					case 'funcion_ejecutiva':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['FUNCIONES_EJECUTIVAS']	=  [];
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']			= $temp['SPANE'][0][strtoupper($campo)];
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['ID']				= '';
						}
						break;
				
					case 'ultimo_cambio_nivel':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['CAMBIO_NIVEL']	= '';
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']	= '';
						}
						break;
					case 'fecha_vigencia_mandato':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['FECHA_VIGENCIA']	= '';
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']	= '';
						}
						break;
					case 'id_sindicato':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['SINDICATOS']	= [];						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']			= $temp['SPANE'][0][strtoupper($campo)];
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['ID']				= '';
						}
						break;
					case 'delegado_gremial':
						if($permiso) {
							$vars_template['INPUT'][0][strtoupper($campo)][0]['CHECKED']	=  '';
						} else {
							$vars_template['INPUT'][0][strtoupper('span_'.$campo)][0]['VALUE']	= '';
							
						}
						break;
					case 'exc_art_14':
					  	if ($modalidad_vinculacion == App\Modelo\Contrato::SINEP && $situacion_revista == App\Modelo\Contrato::LEY_MARCO) {
							if($permiso) {
								$vars_template['INPUT'][0]['SUB_TITULO_EXC'][0][strtoupper($campo)][0]['CHECKED'] = ($empleado->situacion_escalafonaria->exc_art_14) ?  "checked" : "";
							} else {
								$vars_template['INPUT'][0]['SUB_TITULO_EXC'][0][strtoupper('span_'.$campo)][0]['VALUE']	=  empty($empleado->situacion_escalafonaria->exc_art_14) ?   "NO." :  "SI.";						
							}
						}
						break;
					case 'formacion':
					  	if ($modalidad_vinculacion == App\Modelo\Contrato::SINEP && $situacion_revista == App\Modelo\Contrato::LEY_MARCO) {
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
			$vars_template['INPUT'][0]['VOLVER'] = $temp['SPANE'][0]['VOLVER'];
		}
		unset($vars_template['SPANE']);
    }else{
    	if($modalidad_vinculacion == App\Modelo\Contrato::PRESTACION_SERVICIOS) {
    		$vars_template['SPANE'][0]['SPAN'] = new \FMT\Template(TEMPLATE_PATH.'/legajos/span_convenios.html', [],['CLEAN'=>false]);
	    }else{
	    	$vars_template['SPANE'][0]['SPAN'] = new \FMT\Template(TEMPLATE_PATH.'/legajos/span_sinep.html', [],['CLEAN'=>false]);
	    }
    }



		if($modalidad_vinculacion != \App\Modelo\Contrato::PRESTACION_SERVICIOS) {
			$form =  TEMPLATE_PATH.'/legajos/situacion_escalafonaria_sinep.html';
		}else{
			$form = TEMPLATE_PATH.'/legajos/situacion_escalafonaria_convenios.html';
			$vars_template['MIN'] = 'S/D';
			$vars_template['MAX'] = 'S/D';
		}
	 
$escalafonaria = new \FMT\Template($form, $vars_template,['CLEAN'=>true]);

echo $escalafonaria;