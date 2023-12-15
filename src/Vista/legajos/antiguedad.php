<?php
use App\Helper\Vista;
use \FMT\Helper\Template;
	$vars_template = [];
	$vars_template = [
		'SELECT_ID_ENTIDAD'		=> Template::select_block($parametricos['entidades'], null), 
		'TIPO_ENTIDAD'			=> Template::select_block($parametricos['tipo_entidades'], null),
		'JURISDICCION'			=> Template::select_block($parametricos['jurisdicciones'], null),
	];
	$campos_antiguedad = [
		'FECHA_INGRESO_MTR' 	 => !empty($temp = $empleado->antiguedad->fecha_ingreso) ? $temp->format('d/m/Y') : '',
		'CONTADOR_ANTIGUEDAD'   => \FMT\Helper\Arr::get($parametricos,'sum_antiguedad') ? $parametricos['sum_antiguedad'] : '',
		'CONTADOR_GRADO'    	=> \FMT\Helper\Arr::get($parametricos,'sum_grado') ? $parametricos['sum_grado'] : '',
		'ANIO' => $cantidad['anios'],
		'MES' => $cantidad['meses'],
		'TOTAL_ANIO' => $cantidad_total['anios'],
		'TOTAL_MES' => $cantidad_total['meses'],
	];

$nuevos= 0;
$fech_grado = !empty($temp = $empleado->antiguedad->fecha_grado) ? $temp->format('d/m/Y') : '';
$ucgrados = App\Modelo\EmpleadoUltimosCambios::obtener_grado($empleado->id);//consulta el grado del empleado en la tabla empleado_ultimos_cambios
$grad = (int)$empleado->situacion_escalafonaria->id_grado;

if (!empty($empleado->id) && !empty($empleado->cuit)){
    if($permisos['antiguedad']  && $empleado->dependencia->id != null){
    	$vars_template['CAMPOS_ANTIGUEDAD'][0]	= [
    		'FORM'		=> \App\Helper\Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}"),
    	];
		if(\FMT\Helper\Arr::get($empleado->persona->experiencia_laboral,0) && $empleado->persona->experiencia_laboral[0]->id) {
			$i= 0;
			foreach ($empleado->persona->experiencia_laboral as $ex) {
				$vars_template['CAMPOS_ANTIGUEDAD'][0]['ENTIDADES'][]	= [
					'ENTIDAD_ID'		=> $ex->id,
					'ENTIDAD'			=> Template::select_block($parametricos['entidades'], (string)$ex->id_entidad),
					'TIPO_ENTIDAD'		=> \FMT\Helper\Arr::path($parametricos, "tipo_entidades.{$ex->tipo_entidad}.nombre", ''),
					'JURISDICCION'		=> \FMT\Helper\Arr::path($parametricos, "jurisdicciones.{$ex->jurisdiccion}.nombre", ''),
					'FECHA_DESDE'		=> ($ex->fecha_desde instanceof \DateTime) ? $ex->fecha_desde->format('d/m/Y') : '',
					'FECHA_HASTA'		=> ($ex->fecha_hasta instanceof \DateTime) ? $ex->fecha_hasta->format('d/m/Y') : '',
				];
				$nuevos =  ($ex->id > $nuevos) ? $ex->id: $nuevos;
			}
			$vars_template['NUEVOS'] =  $nuevos +100;

		} else {
			$vars_template['NUEVOS'] = 100;
		}

		foreach ($campos_antiguedad as $key => $value) {
			$vars_template['CAMPOS_ANTIGUEDAD'][0][$key] = $value;
		}
		$vars_template['CAMPOS_ANTIGUEDAD'][0]['CONTADOR_ANTIGUEDAD'] = $parametricos['sum_antiguedad'];
		$vars_template['CAMPOS_ANTIGUEDAD'][0]['CONTADOR_GRADO'] = $parametricos['sum_grado'];
		$vars_template['CAMPOS_ANTIGUEDAD'][0]['ADJUNTAR_DOC'] = $adjuntar_doc;
		$vars_template['CAMPOS_ANTIGUEDAD'][0]['ANIO'] = $cantidad['anios'];
		$vars_template['CAMPOS_ANTIGUEDAD'][0]['MES'] = $cantidad['meses'];
		$vars_template['CAMPOS_ANTIGUEDAD'][0]['TOTAL_ANIO'] = $cantidad_total['anios'];
		$vars_template['CAMPOS_ANTIGUEDAD'][0]['TOTAL_MES'] = $cantidad_total['meses'];

		//Agregado para funciones super rol
		if($permisos['antiguedad_grado']){
			$vars_template ['CAMPOS_ANTIGUEDAD'][0]['INPUT_GRADO'][0]['FECHA_GRADO'] = $fech_grado;
			$vars_template ['BOTON_GRADO'][0]['BOTON'] = 1;
			if ($grad != $ucgrados->id_convenios) {
				$vars_template['CAMPOS_ANTIGUEDAD'][0]['AVISO_GRADO'][0]['MSJ_GRADO'] = 'ES NECESARIO ACTUALIZAR LA FECHA DE OTORGAMIENTO DE GRADO. LA QUE SE MUESTRA PERTENECE A UN GRADO ANTERIOR.';
			}			 
		} else {
			$vars_template ['CAMPOS_ANTIGUEDAD'][0]['SPAN_GRADO'][0]['FECHA_GRADO'] = $fech_grado;
		}
	}elseif($permisos['antiguedad'] && $empleado->dependencia->id == null) {
	 $vars_template['AVISO'][]['MSJ'] = 'PARA DEFINIR LA <strong>ANTIGÜEDAD</strong>, ES REQUISITO TENER <strong>UBICACIÓN EN LA ESTRUCTURA.</strong>.';
    } else {
		if(!empty($empleado->persona->experiencia_laboral)){
		foreach ($empleado->persona->experiencia_laboral as $i => $ex) {
			$vars_template['SPAN_ANTIGUEDAD'][0]['ENTIDADES'][$i]	= [
				'ENTIDAD'			=> $parametricos['entidades'][$ex->id_entidad]['nombre'],
				'TIPO_ENTIDAD'		=> $parametricos['entidades'][$ex->id_entidad]['nombre_tipo'],
				'JURISDICCION'		=> $parametricos['entidades'][$ex->id_entidad]['nombre_juris'],
				'FECHA_DESDE'		=> ($ex->fecha_desde instanceof \DateTime) ? $ex->fecha_desde->format('d/m/Y') : '',
				'FECHA_HASTA'		=> ($ex->fecha_hasta instanceof \DateTime) ? $ex->fecha_hasta->format('d/m/Y') : '',
			];
		}	
		}else{
			$vars_template['SPAN_ANTIGUEDAD'][0]['SIN_EXPERIENCIA'][]['MSJ'] = "No tiene experiencia laboral cargada.";
		}
    	foreach ($campos_antiguedad as $key => $value) {
			$vars_template['SPAN_ANTIGUEDAD'][0][$key]	= $value;
		}
		$vars_template ['SPAN_ANTIGUEDAD'][0]['CONTADOR_ANTIGUEDAD'] = $parametricos['sum_antiguedad'];

		if($permisos['antiguedad_grado']){
	    	$vars_template['SPAN_ANTIGUEDAD'][0]['FORM'] = \App\Helper\Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}");
			$vars_template ['SPAN_ANTIGUEDAD'][0]['INPUT_GRADO'][0]['FECHA_GRADO'] = $fech_grado;
			$vars_template ['BOTON_GRADO'][0]['BOTON'] = 1;
			$vars_template['SPAN_ANTIGUEDAD'][0]['CONTADOR_GRADO'] = $parametricos['sum_grado'];
			foreach ($campos_antiguedad as $key => $value) {
				$vars_template['SPAN_ANTIGUEDAD'][0][$key]	= $value;
			}
		} else {
			$vars_template ['SPAN_ANTIGUEDAD'][0]['SPAN_GRADO'][0]['FECHA_GRADO'] = $fech_grado;
			$vars_template['SPAN_ANTIGUEDAD'][0]['CONTADOR_GRADO'] = $parametricos['sum_grado'];
		}
		$vars_template['SPAN_ANTIGUEDAD'][0]['ADJUNTAR_DOC'] = $adjuntar_doc;
		if ($grad != $ucgrados->id_convenios) {
		$vars_template['SPAN_ANTIGUEDAD'][0]['AVISO_GRADO'][0]['MSJ_GRADO'] = 'ES NECESARIO ACTUALIZAR LA FECHA DE OTORGAMIENTO DE GRADO. LA QUE SE MUESTRA PERTENECE A UN GRADO ANTERIOR.';
		}
    }
}else{
	$vars_template['AVISO'][]['MSJ'] = 'PARA DEFINIR LA <strong>ANTIGÜEDAD</strong>, ES REQUISITO TENER LOS DATOS BÁSICOS DEL <strong>AGENTE</strong> COMPLETOS, <strong>SITUACIÓN ESCALAFONARIA</strong> y <strong>UBICACIÓN EN LA ESTRUCTURA.</strong>.';
}	
$antiguedad = new \FMT\Template(TEMPLATE_PATH.'/legajos/antiguedad.html', $vars_template,['CLEAN'=>false]);