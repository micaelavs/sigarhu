<?php
use App\Helper\Vista;
$vars_template = [];
$vars_vista['SUBTITULO'] = ' Presentaciones';

	$campos_presentacion = [
		'OPERACION' => 'alta',
		'TIPO_PRESENTACION' =>  \FMT\Helper\Template::select_block($tipo_presentacion,$anticorrupcion->tipo_presentacion ),
		'FECHA_PRESENTACION' => !empty($temp = $anticorrupcion->fecha_presentacion) ? $temp->format('d/m/Y') : '',
		'PERIODO' => !empty($anticorrupcion->periodo) ? $anticorrupcion->periodo : '',
		'NRO_TRANSACCION' => $anticorrupcion->nro_transaccion,
		'COMPROBANTE' => ($anticorrupcion->archivo)? 'Reemplazar Comprobante': 'Comprobante',
		'VOLVER' =>	Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}")	
	];

	if (!empty($empleado->id) && !empty($empleado->cuit)){
		foreach ($campos_presentacion as $key => $value) {
			$vars_template['CAMPOS_PRESENTACION'][0][$key] = $value;
		}
	}else{
		$vars_template['AVISO_PRESENTACION'][]['MSJ'] = 'PARA DEFINIR <strong>ANTICORRUPCION</strong>, ES REQUISITO TENER LOS DATOS BÁSICOS DEL <strong>AGENTE</strong>.';
	}

$vars_vista['JS_FOOTER'][]['JS_SCRIPT']   = Vista::get_url('form_presentacion.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('fileinput.min.js');
$vars_vista['CSS_FILES'][]	= ['CSS_FILE' => \App\Helper\Vista::get_url('fileinput.min.css')];
$formulario_presentaciones = new \FMT\Template(TEMPLATE_PATH.'/legajos/formulario_presentaciones.html', $vars_template, ['CLEAN'=>false]);
$bloque = \App\Helper\Bloques::ANTICORRUPCION;


$vars_vista['CONTENT'] = "{$formulario_presentaciones}";
$vista->add_to_var('vars',$vars_vista);
return true;