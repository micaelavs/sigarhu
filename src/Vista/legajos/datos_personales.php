<?php
use \FMT\Helper\Arr;
	$vars_template	= [
		'NOMBRE'			=> $empleado->persona->nombre,
		'APELLIDO'			=> $empleado->persona->apellido,
		'CUIT'				=> $empleado->cuit,
		'FECHA_NAC'			=> !empty($temp = $empleado->persona->fecha_nac) ? $temp->format('d/m/Y') : '--',
		'GENERO'			=> \FMT\Helper\Template::select_block($parametricos['genero'], $empleado->persona->genero),
		'ESTADO_CIVIL'		=> \FMT\Helper\Template::select_block($parametricos['estado_civil'], $empleado->persona->estado_civil),
		'EMAIL'				=> $empleado->email,
		'PROVINCIA'			=> \FMT\Helper\Template::select_block($parametricos['ubicacion_regiones'], $empleado->persona->domicilio->id_provincia),
		'LOCALIDAD'			=> \FMT\Helper\Template::select_block($parametricos['ubicacion_localidades'], $empleado->persona->domicilio->id_localidad),
		'TIPO_DOCUMENTO'	=> \FMT\Helper\Template::select_block($parametricos['tipo_documento'], $empleado->persona->tipo_documento),
		'DOCUMENTO'			=> $empleado->persona->documento,
		'CALLE'				=> $empleado->persona->domicilio->calle,
		'NUMERO'			=> $empleado->persona->domicilio->numero,
		'PISO'				=> $empleado->persona->domicilio->piso,
		'DEPTO'				=> $empleado->persona->domicilio->depto,
		'COD_POSTAL'		=> $empleado->persona->domicilio->cod_postal,
		'FECHA_ALTA'		=> !empty($temp = $empleado->persona->domicilio->fecha_alta)	? $temp->format('d/m/Y') : '',
		'NACIONALIDAD'		=> \FMT\Helper\Template::select_block($parametricos['nacionalidad'], $empleado->persona->nacionalidad),
		'TELEFONOS'			=> [],
		'DETALLE_TEL'		=> \FMT\Helper\Template::select_block($parametricos['tipo_telefono'], null),
		'FOTO_PERSONA'		=> ($empleado->persona->foto_persona) ? \App\Helper\Vista::get_url("index.php/legajos/mostrar_foto_persona/{$empleado->cuit}") : '',
	];

	if(!empty($empleado->persona->telefonos)){
		foreach ($empleado->persona->telefonos as $telefono) {
			$vars_template['TELEFONOS'][]	= [
				'ID_TEL'		=> $telefono->id ?: '0',
				'DETALLE_TEL'	=> \FMT\Helper\Template::select_block($parametricos['tipo_telefono'], $telefono->id_tipo_telefono),
				'NUMERO_TEL'	=> $telefono->telefono,
			];
		}
	}
    if($permisos['datos_personales']){
    	$cuit_form = ($empleado->id) ? $empleado->cuit : ''; 
		$vars_template['ADJUNTAR_DOC'] = $adjuntar_doc;
    	$vars_template['CAMPOS_ADMIN_RRHH'][0]	= $vars_template;
    	$vars_template['CAMPOS_ADMIN_RRHH'][0]['FORM']	= \App\Helper\Vista::get_url("index.php/legajos/gestionar/{$cuit_form}");
    } else {
    	if(!empty($empleado->persona->telefonos)){
			foreach ($empleado->persona->telefonos as $telefono) {
				$vars_template['SPAN_TELEFONOS'][]	= [
					'DETALLE_TEL'	=> Arr::path($parametricos, "tipo_telefono.{$telefono->id_tipo_telefono}.nombre", '--'),
					'NUMERO_TEL'	=> $telefono->telefono,
				];
			}
		} else {
			$vars_template['SPAN_TELEFONOS'][0]	= [
					'DETALLE_TEL'	=> '--',
					'NUMERO_TEL'	=> '--',
				];
		}
		$vars_template['ADJUNTAR_DOC'] = $adjuntar_doc;
    	$vars_template['SPAN_DATOS_PERSONALES'][0]	= $vars_template;
    	$vars_template['SPAN_DATOS_PERSONALES'][0]['GENERO']		= Arr::path($parametricos, "genero.{$empleado->persona->genero}.nombre", '--');
		$vars_template['SPAN_DATOS_PERSONALES'][0]['ESTADO_CIVIL']	= Arr::path($parametricos, "estado_civil.{$empleado->persona->estado_civil}.nombre", '--');
		$vars_template['SPAN_DATOS_PERSONALES'][0]['PROVINCIA']		= Arr::path($parametricos, "ubicacion_regiones.{$empleado->persona->domicilio->id_provincia}.nombre", '--');
		$vars_template['SPAN_DATOS_PERSONALES'][0]['LOCALIDAD']		= Arr::path($parametricos, "ubicacion_localidades.{$empleado->persona->domicilio->id_localidad}.nombre", '--');
		$vars_template['SPAN_DATOS_PERSONALES'][0]['TIPO_DOCUMENTO']= Arr::path($parametricos, "tipo_documento.{$empleado->persona->tipo_documento}.nombre", '--');
		$vars_template['SPAN_DATOS_PERSONALES'][0]['NACIONALIDAD']	= Arr::path($parametricos, "nacionalidad.{$empleado->persona->nacionalidad}.nombre", '--');
    }

	$datos_personales = new \FMT\Template(TEMPLATE_PATH.'/legajos/datos_personales.html', $vars_template,['CLEAN'=>false]);
