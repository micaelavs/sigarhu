<?php
use App\Helper\Vista;
	$vars_template = [];
if($permisos['bloque_presupuesto']) {
    if($permisos['presupuesto']){
		if (!empty($empleado->id) && !empty($empleado->cuit)){
			$vars_template['CAMPOS_PRESUPUESTO'][0]['ADJUNTAR_DOC'] = $adjuntar_doc;
			$vars_template['CAMPOS_PRESUPUESTO'][0]['SAF'] 					= \FMT\Helper\Template::select_block($parametricos['saf'], $empleado->presupuesto->id_saf);
			$vars_template['CAMPOS_PRESUPUESTO'][0]['JURISDICCION'] 		= \FMT\Helper\Template::select_block($parametricos['jurisdicciones'], $empleado->presupuesto->id_jurisdiccion);
			$vars_template['CAMPOS_PRESUPUESTO'][0]['UBICACION_GEOGRAFICA'] = \FMT\Helper\Template::select_block($parametricos['ubicaciones_geograficas'], $empleado->presupuesto->id_ubicacion_geografica);
			$vars_template['CAMPOS_PRESUPUESTO'][0]['PROGRAMA'] 			= \FMT\Helper\Template::select_block($parametricos['programas'], $empleado->presupuesto->id_programa);
			$vars_template['CAMPOS_PRESUPUESTO'][0]['SUBPROGRAMA'] 			= \FMT\Helper\Template::select_block($parametricos['subprogramas'], $empleado->presupuesto->id_subprograma);
			$vars_template['CAMPOS_PRESUPUESTO'][0]['PROYECTO'] 			= \FMT\Helper\Template::select_block($parametricos['proyectos'], $empleado->presupuesto->id_proyecto);
			$vars_template['CAMPOS_PRESUPUESTO'][0]['ACTIVIDAD'] 			= \FMT\Helper\Template::select_block($parametricos['actividades'], $empleado->presupuesto->id_actividad);
			$vars_template['CAMPOS_PRESUPUESTO'][0]['OBRA'] 				= \FMT\Helper\Template::select_block($parametricos['obras'], $empleado->presupuesto->id_obra);
			$vars_template['CAMPOS_PRESUPUESTO'][0]['FORM']					= \App\Helper\Vista::get_url('index.php/legajos/gestionar/'.$empleado->cuit);
		}else{
			$vars_template['AVISO_PRESUPUESTO'][]['MSJ'] = 'PARA DEFINIR EL <strong>PRESUPUESTO</strong>, ES REQUISITO TENER LOS DATOS BÁSICOS DEL <strong>AGENTE</strong>.';
		}

    } else {
		$vars_template['SPAN_PRESUPUESTO'][0]['SAF'] 				  = ($empleado->presupuesto->id_saf) ? $parametricos['saf'][$empleado->presupuesto->id_saf]['nombre'] : '___';
		$vars_template['SPAN_PRESUPUESTO'][0]['JURISDICCION'] 	      = ($empleado->presupuesto->id_jurisdiccion) ? $parametricos['jurisdicciones'][$empleado->presupuesto->id_jurisdiccion]['nombre']: '___';
		$vars_template['SPAN_PRESUPUESTO'][0]['UBICACION_GEOGRAFICA'] = ($empleado->presupuesto->id_ubicacion_geografica)? $parametricos['ubicaciones_geograficas'][$empleado->presupuesto->id_ubicacion_geografica]['nombre']: '___';
		$vars_template['SPAN_PRESUPUESTO'][0]['PROGRAMA'] 			  = ($empleado->presupuesto->id_programa) ? $parametricos['programas'][$empleado->presupuesto->id_programa]['nombre']: '___';
		$vars_template['SPAN_PRESUPUESTO'][0]['SUBPROGRAMA'] 		  = ($empleado->presupuesto->id_subprograma) ? $parametricos['subprogramas'][$empleado->presupuesto->id_subprograma]['nombre']: '___';
		$vars_template['SPAN_PRESUPUESTO'][0]['PROYECTO'] 		      = ($empleado->presupuesto->id_proyecto) ? $parametricos['proyectos'][$empleado->presupuesto->id_proyecto]['nombre']: '___';
		$vars_template['SPAN_PRESUPUESTO'][0]['ACTIVIDAD'] 			  = ($empleado->presupuesto->id_actividad) ? $parametricos['actividades'][$empleado->presupuesto->id_actividad]['nombre']: '___';
		$vars_template['SPAN_PRESUPUESTO'][0]['OBRA'] 				  = ($empleado->presupuesto->id_obra) ? $parametricos['obras'][$empleado->presupuesto->id_obra]['nombre']: '___';
    }

    if(empty($empleado->id) && empty($empleado->cuit)){
	 	$vars_template	= [];
    	$vars_template['AVISO'][]['MSJ'] = 'PARA DEFINIR EL <strong>PRESUPUESTO</strong>, ES REQUISITO TENER LOS DATOS BÁSICOS DEL <strong>AGENTE</strong> COMPLETOS.';
    }
    $presupuesto = new \FMT\Template(TEMPLATE_PATH.'/legajos/presupuesto.html', $vars_template,['CLEAN'=>true]);
}
