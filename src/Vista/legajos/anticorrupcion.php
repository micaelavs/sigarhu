<?php
use App\Helper\Vista;
use \FMT\Helper\Arr;

	$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('anticorrupcion.js');
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('fileinput.min.js');
	$vars_vista['CSS_FILES'][]	= ['CSS_FILE' => \App\Helper\Vista::get_url('fileinput.min.css')];
	$vars_template = [];
	$campos_anticorrupcion = [
		'FORM'			  	=> \App\Helper\Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}"),
		'TIPO_PRESENTACION' => Arr::path($parametricos['tipo_presentacion'],"{$anticorrupcion->tipo_presentacion}.nombre",[]),
		'CHECKED'   => ($anticorrupcion->id) ? 'checked': '',
		'DISABLED'  => ($anticorrupcion->id) ? '': 'disabled',
		'DISABLED_RENUNCIA' => empty($anticorrupcion->fecha_publicacion_designacion) ? 'disabled': '',
		'FECHA_DESIGNACION' => !empty($temp = $anticorrupcion->fecha_designacion) ? $temp->format('d/m/Y') : '',
		'FECHA_PUBLICACION_DESIGNACION' => !empty($temp = $anticorrupcion->fecha_publicacion_designacion) ? $temp->format('d/m/Y') : '',
		'FECHA_ACEPTACION_RENUNCIA' => !empty($temp = $anticorrupcion->fecha_aceptacion_renuncia) ? $temp->format('d/m/Y') : '',
		'FECHA_PRESENTACION' => !empty($temp = $anticorrupcion->fecha_presentacion) ? $temp->format('d/m/Y') : '',
		'PERIODO' => !empty($anticorrupcion->periodo) ? $anticorrupcion->periodo : '',
		'NRO_TRANSACCION' => $anticorrupcion->nro_transaccion,
		'COMPROBANTE' => ($anticorrupcion->archivo)? '': 'Comprobante',	
	];

if($anticorrupcion->archivo) {
			$campos_anticorrupcion['ARCHIVO'][0] =  ['URL' => \App\Helper\Vista::get_url("index.php/legajos/mostrar_presentacion/{$anticorrupcion->id_presentacion}")];		
		}
if($anticorrupcion->id) {
			$campos_anticorrupcion['HISTORIAL'][0] = ['URL_HISTORIAL' => \App\Helper\Vista::get_url("index.php/legajos/historial_presentacion/{$empleado->cuit}")];		
		}
if($anticorrupcion->id) {
			$campos_anticorrupcion['NUEVA_PRESENTACION'][0] = ['URL_PRESENTACION' => \App\Helper\Vista::get_url("index.php/legajos/presentacion/{$empleado->cuit}")];		
		}
if($permisos['bloque_anticorrupcion']){
	if($permisos['anticorrupcion'] && $empleado->estado != \App\Modelo\Empleado::EMPLEADO_INACTIVO){
		if (!empty($empleado->id) && !empty($empleado->cuit)){
			foreach ($campos_anticorrupcion as $key => $value) {
				$vars_template['CAMPOS_ANTICORRUPCION'][0][$key] = $value;
			}
			
		}else{
			$vars_template['AVISO_ANTICORRUPCION'][]['MSJ'] = 'PARA DEFINIR <strong>ANTICORRUPCION</strong>, ES REQUISITO TENER LOS DATOS BÁSICOS DEL <strong>AGENTE</strong>.';
		}

    } else {
		foreach ($campos_anticorrupcion as $key => $value) {

		$vars_template['SPAN_ANTICORRUPCION'][0][$key]	= $value;
		}
		if($empleado->estado == \App\Modelo\Empleado::EMPLEADO_INACTIVO && $anticorrupcion->id){
			$vars_template['SPAN_ANTICORRUPCION'][0]['SPAN_ANTICORRUPCION_ACCIONES']	= [];
			$vars_template['SPAN_ANTICORRUPCION'][0]['SPAN_ANTICORRUPCION_ACCIONES'][0]['NUEVA_PRESENTACION'][0] = [
				'URL_PRESENTACION' => \App\Helper\Vista::get_url("index.php/legajos/presentacion/{$empleado->cuit}")
			];
			$vars_template['SPAN_ANTICORRUPCION'][0]['SPAN_ANTICORRUPCION_ACCIONES'][0]['HISTORIAL'][0] = [
				'URL_HISTORIAL' => \App\Helper\Vista::get_url("index.php/legajos/historial_presentacion/{$empleado->cuit}")
			];
		}
    }
    $anticorrupciones = new \FMT\Template(TEMPLATE_PATH.'/legajos/anticorrupcion.html', $vars_template,['CLEAN'=>false]);
		$vista->add_to_var('vars',$vars_vista);

}