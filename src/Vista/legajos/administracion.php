<?php
	use \FMT\Helper\Arr;
	use \FMT\Helper\Template;
	$vars_template = [];
	$dias = ['DOMINGO','LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO'];
	$horarios = $empleado->horario->horarios;
	if(!is_array($horarios)){
		$horarios = json_decode($horarios);
	}

	if ($permisos['administracion']){
		if (!empty($empleado->id) && !empty($empleado->cuit)){
			$vars_template['ADMINISTRACION'][0] = [
				'FORM'					=> \App\Helper\Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}"),
				'TURNO'					=> Template::select_block($parametricos['turno'], $empleado->horario->id_turno),
				'MOTIVO_BAJA'			=> Template::select_block($parametricos['motivo_baja'], $empleado->id_motivo),
				'ESTADOS'				=> Template::select_block($parametricos['estado_administracion'], $empleado->estado),
				'FECHA_BAJA'			=> ($temp = $empleado->fecha_baja) ? $temp->format('d/m/Y') : '--',
				'LICENCIAS_ESPECIALES'	=> Template::select_block($parametricos['licencias_especiales'], $empleado->licencia->id_licencia),
				'ID_UBICACION'			=> $empleado->ubicacion->id_ubicacion,
				'HORA_EXTRA'			=>	($empleado->horas_extras->id) ? "checked" :  "",
				'ANIO_EXTRA' 			=> ($temp = $empleado->horas_extras->anio) ? $temp : '--',
				'MES_EXTRA'				=> ($temp = $empleado->horas_extras->mes) ? $temp : '--',
				'ACTO_ADMINISTRATIVO'	=> ($temp = $empleado->horas_extras->acto_administrativo) ? $temp : '--',
				'EDIFICIOS'				=> Template::select_block($parametricos['ubicaciones'], $empleado->ubicacion->id_edificio),
				'CALLE_NUMERO'			=> $empleado->ubicacion->calle . ' ' . $empleado->ubicacion->numero,
				'PISOS'					=> Template::select_block(Arr::path($parametricos['ubicaciones'], "{$empleado->ubicacion->id_edificio}.pisos",[]),
					$empleado->ubicacion->piso
				),
				'OFICINAS'				=> Template::select_block(
					Arr::path($parametricos['ubicaciones'], "{$empleado->ubicacion->id_edificio}.pisos.{$empleado->ubicacion->piso}.oficinas",[]),
					$empleado->ubicacion->oficina
				),
				'UBICACION_PROVINCIA'	=> Arr::path($parametricos['ubicacion_regiones'], "{$empleado->ubicacion->id_provincia}.nombre", '--'),
				'UBICACION_LOCALIDAD'	=> Arr::path($parametricos['ubicacion_localidades_edificio'], "{$empleado->ubicacion->id_localidad}.nombre", '--'),
				'CHECKED'				=> ($empleado->planilla_reloj == 1) ? "checked" : "",
				'MSJ_GRILLA'			=> 'Si requiere modificar la carga horaria, seleccione la hora en la grilla y proceda a realizar el cambio',
				'FECHA_DESDE'		   => is_object($empleado->licencia->fecha_desde) ? $empleado->licencia->fecha_desde->format('d/m/Y') : '',
				'FECHA_HASTA'		   => is_object($empleado->licencia->fecha_hasta) ? $empleado->licencia->fecha_hasta->format('d/m/Y'):'',
				'COMISION'				=> Template::select_block($parametricos['estado_comision'], $empleado->en_comision->activo),
				'ORGANISMO_ORIGEN'		=> Template::select_block($parametricos['comisiones'], $empleado->en_comision->id_origen),
				'ORGANISMO_DESTINO'		=> Template::select_block($parametricos['comisiones'], $empleado->en_comision->id_destino),
			];

			$vars_template['NUEVA_HORA_EXTRA'][0] = ['URL_HORA_EXTRA' => \App\Helper\Vista::get_url("index.php/legajos/horas_extras/{$empleado->cuit}")];
			if ($empleado->tiene_historial_extras()) {
				$vars_template['HISTORIAL_HORA_EXTRA'][0] = ['URL_HISTORIAL' => \App\Helper\Vista::get_url("index.php/legajos/historial_horas_extras/{$empleado->cuit}")];
			}

			if (is_null($empleado->horario->id)) {
				$vars_template['ADMINISTRACION'][0]['SHOW_PLANTILLA_HORARIO'][0]['PLANTILLA_HORARIO'] = \FMT\Helper\Template::select_block($parametricos['plantilla_horario'], $id_plantilla_horario);
				$vars_template['ADMINISTRACION'][0]['MSJ_PLANTILLA'] = 'Seleccione una plantilla para realizar la carga de horario laboral.';
			}
			foreach ($dias as $key => $value) {
				$vars_template['ADMINISTRACION'][0]['HORA_DESDE_'.$value] = \FMT\Helper\Arr::path($horarios,"{$key}.0",null);
				$vars_template['ADMINISTRACION'][0]['HORA_HASTA_'.$value] = \FMT\Helper\Arr::path($horarios,"{$key}.1",null);
			}
			$comision = ($empleado->en_comision->id)  ? 'SI_COM' : 'NO_COM';
			$vars_template['ADMINISTRACION'][0][$comision] = 'selected';
			$vars_template['ADMINISTRACION'][0]['ADJUNTAR_DOC'] = $adjuntar_doc;
		}else{
			$vars_template['AVISO'][]['MSJ'] = 'PARA DEFINIR LA <strong>ADMINISTRACIÓN</strong>, ES REQUISITO TENER LOS DATOS BÁSICOS DEL <strong>AGENTE</strong> COMPLETOS.';
		}
	}else{
		$vars_template['SPAN_ADMINISTRACION'][0] = [
			'ADJUNTAR_DOC' => $adjuntar_doc,
			'TURNO' => Arr::path($parametricos, "turno.{$empleado->horario->id_turno}.nombre", '--'),
			'PLANILLA_RELOJ' => ($empleado->planilla_reloj == 1) ? 'Activo' : '--',
			'EDIFICIO'		=> $empleado->ubicacion->nombre,
			'CALLE_NUMERO'		=> $empleado->ubicacion->calle . ' ' . $empleado->ubicacion->numero,
			'OFICINA'		=> $empleado->ubicacion->oficina,
			'PISO'			=> $empleado->ubicacion->piso,
			'LICENCIAS_ESPECIALES' => $empleado->licencia->nombre,
			'HORA_EXTRA'			=>	($empleado->horas_extras->id) ? "SI" :  "NO",
			'ANIO_EXTRA' 			=> ($temp = $empleado->horas_extras->anio) ? $temp : '--',
			'MES_EXTRA'				=> ($temp = $empleado->horas_extras->mes) ? $temp : '--',
			'ACTO_ADMINISTRATIVO'	=> ($temp = $empleado->horas_extras->acto_administrativo) ? $temp : '--',
			'FIRMA_PRESENTISMO'  => 'a',
			'ACTIVO'		=> Arr::path($parametricos['estado_administracion'], "{$empleado->estado}.nombre", '--'),
			'MOTIVO_BAJA' => Arr::path($parametricos['motivo_baja'], "{$empleado->id_motivo}.nombre", '--'),
			'FECHA_BAJA' => ($temp = $empleado->fecha_baja) ? $temp->format('d/m/Y') : '--',
			'COMISION'				=> Arr::path($parametricos['estado_comision'], "{$empleado->en_comision->activo}.nombre", '--'),
			'ORGANISMO_ORIGEN'		=> Arr::path($parametricos['comisiones'], "{$empleado->en_comision->id_origen}.nombre", '--'),
			'ORGANISMO_DESTINO'		=> Arr::path($parametricos['comisiones'], "{$empleado->en_comision->id_destino}.nombre", '--'),
			'FECHA_DESDE'		   	=> is_object($empleado->licencia->fecha_desde) ? $empleado->licencia->fecha_desde->format('d/m/Y') : '--',
			'FECHA_HASTA'		   	 => is_object($empleado->licencia->fecha_hasta) ? $empleado->licencia->fecha_hasta->format('d/m/Y'):'--',
			'UBICACION_PROVINCIA'	=> Arr::path($parametricos['ubicacion_regiones'], "{$empleado->ubicacion->id_provincia}.nombre", '--'),
			'UBICACION_LOCALIDAD'   => Arr::path($parametricos['ubicacion_localidades_edificio'], "{$empleado->ubicacion->id_localidad}.nombre", '--'),
		];
		foreach ($dias as $key => $value) {
			$vars_template['SPAN_ADMINISTRACION'][0]['HORA_DESDE_'.$value] = \FMT\Helper\Arr::path($horarios, "{$key}.0",null);
			$vars_template['SPAN_ADMINISTRACION'][0]['HORA_HASTA_'.$value] = \FMT\Helper\Arr::path($horarios, "{$key}.1",null);
		}
	}
	$administracion = new \FMT\Template(TEMPLATE_PATH.'/legajos/administracion.html', $vars_template,['CLEAN'=>false]);
