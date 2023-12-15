<?php
use App\Modelo\Dependencia;
	$vars_template = [];
	//Bloque Ubicacion en la Estructura

	$niveles = ['MINISTRO','SECRETARIA','SUBSECRETARIA','DIR_NACIONAL_GENERAL','DIR_SIMPLE','COORDINACION','UNIDAD_AREA'];
	if ($permisos['ubicacion_estructura']){
		if (!empty($empleado->id) && !empty($empleado->cuit)){
			$vars_template['UBICACION_ESTRUCTURA'][0]['ADJUNTAR_DOC'] = $adjuntar_doc;
			$vars_template['UBICACION_ESTRUCTURA'][0]['NIVEL_ORGANIGRAMA'] = \FMT\Helper\Template::select_block($parametricos['nivel_organigrama'],$empleado->dependencia->nivel);
			$vars_template['UBICACION_ESTRUCTURA'][0]['DEPENDENCIA'] = \FMT\Helper\Template::select_block($lista_dependencia,$empleado->dependencia->id_dependencia);
			$vars_template['UBICACION_ESTRUCTURA'][0]['DEPENDENCIA_INFORMAL'] = \FMT\Helper\Template::select_block($lista_dep_informales,$empleado->dependencia->id_dep_informal);
			if (!empty($empleado->dependencia->id_dep_informal)  && (\FMT\Helper\Arr::path($lista_dep_informales,$empleado->dependencia->id_dep_informal, null))) { 
				$vars_template['INFORMAL'][0]['UNIDAD_INFORMAL']  = $lista_dep_informales[$empleado->dependencia->id_dep_informal]['nombre']; 
			}
			$vars_template['UBICACION_ESTRUCTURA'][0]['FORM']		= \App\Helper\Vista::get_url('index.php/legajos/gestionar/'.$empleado->cuit);

			if($estructura){
				foreach ($estructura as $value) {
					$vars_template['UBICACION_ESTRUCTURA'][0][$value['nombre_nivel']] = $value['ubicacion'];
				}
				foreach ($niveles as $value) {
					if(!isset($vars_template['UBICACION_ESTRUCTURA'][0][$value])){
						$vars_template['UBICACION_ESTRUCTURA'][0][$value] = '___';
					} 
				}
			}
		}else{ 
			$vars_template['AVISO'][]['MSJ'] = 'PARA DEFINIR LA <strong>UBICACIÓN EN LA ESTRUCTURA</strong>, ES REQUISITO TENER LOS DATOS BÁSICOS DEL <strong>AGENTE</strong> COMPLETOS.';
		}
	}else{

			if($estructura){
				foreach ($estructura as $value) {
					$vars_template['SPAN_UBICACION_ESTRUCTURA'][0][$value['nombre_nivel']] = $value['ubicacion'];
				}
			}	
			foreach ($niveles as $value) {
				if(!isset($vars_template['SPAN_UBICACION_ESTRUCTURA'][0][$value])){
					$vars_template['SPAN_UBICACION_ESTRUCTURA'][0][$value] = '___';
				} 
			}
			if ((!empty($empleado->dependencia->id_dep_informal)) && (\FMT\Helper\Arr::path($lista_dep_informales,$empleado->dependencia->id_dep_informal, null))) {
				$vars_template['SPAN_UBICACION_ESTRUCTURA'][0]['DEPENDENCIA_INFORMAL']  = $lista_dep_informales[$empleado->dependencia->id_dep_informal]['nombre'];
			}else{
				$vars_template['SPAN_UBICACION_ESTRUCTURA'][0]['DEPENDENCIA_INFORMAL']  =  '___';
			}
			$vars_template['SPAN_UBICACION_ESTRUCTURA'][0]['ADJUNTAR_DOC'] = $adjuntar_doc;
			
	}
	if(empty($empleado->id) && empty($empleado->cuit)){
		$vars_template	= [];
    	$vars_template['AVISO'][]['MSJ'] = 'PARA DEFINIR LA <strong>UBICACIÓN EN LA ESTRUCTURA</strong>, ES REQUISITO TENER LOS DATOS BÁSICOS DEL <strong>AGENTE</strong> COMPLETOS.';
    }
	$ubicacion_estructura = new \FMT\Template(TEMPLATE_PATH.'/legajos/ubicacion_estructura.html', $vars_template, ['CLEAN' => false]);
