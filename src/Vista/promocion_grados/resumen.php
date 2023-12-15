<?php
use \FMT\Template;
use \FMT\Helper\Arr;

	$config		= FMT\Configuracion::instancia();
	$vars_vista	= $vars_template	= [];

	$vars_vista['SUBTITULO']		= 'Resumen Promoción de Grado';

// Datos actuales
    $vars_template['CUIT']		= !empty($temp = $promocion->empleado->cuit) ? $temp : '';
    $vars_template['NOMBRE']	= !empty($temp = $promocion->empleado->persona->nombre) ? $temp : '';
    $vars_template['APELLIDO']	= !empty($temp = $promocion->empleado->persona->apellido) ? $temp : '';
	$vars_template['FECHA_PROMOCION']	= !empty($temp = $promocion->fecha_promocion->format('d/m/Y')) ? $temp: '';
	// $vars_template['CREDITOS_DISPONIBLES']		=  '99999';

	// Datos de Promocion
	$vars_template['MOTIVO']					=  $promocion->id_motivo;
	$vars_template['EVALUACION_INICIO']			=  (string)$promocion->periodo_inicio;
	$vars_template['EVALUACION_FIN']			=  (string)$promocion->periodo_fin;
	$vars_template['NUEVO_GRADO']			    =  (string)$promocion->id_grado;
	$vars_template['ACTO_ADMINISTRATIVO']		=  (string)$promocion->acto_administrativo;
	$vars_template['NUMERO_EXPEDIENDE']			=  (string)$promocion->numero_expediente;

	// Recuento de creditos
	$vars_template['REQUERIDOS']				= (string)$promocion->creditos_requeridos;
	$vars_template['RECONOCIDOS']				= (string)$promocion->creditos_reconocidos;
	$vars_template['DESCONTADOS']				= (string)$promocion->creditos_descontados;
    $vars_template['ARCHIVO']				    = empty($promocion->archivo)
        ? [['URL'   => '']]
        : [['URL'   => \App\Helper\Vista::get_url('index.php/Promocion_grados/descargar/'.$promocion->id)]];
	
	$template					= (new Template(VISTAS_PATH.'/templates/promocion_grados/resumen.html', $vars_template,['CLEAN'=>false]));
    $base_url					= \App\Helper\Vista::get_url('index.php');
    
    
    $vars_botonera['CLASS_COL']         = 'col-md-12';
    $vars_botonera['BOTON_VOLVER'][]    = [
        'CLASS' => 'btn-default',
        'NOMBRE'=> 'VOLVER',
        'HTTP' => \App\Helper\Vista::get_url('index.php/Promocion_grados/index'),
    ]; 
    $botones                    = (new \FMT\Template(VISTAS_PATH.'/widgets/botonera.html',$vars_botonera));
	$vars_vista['CONTENT']		= "{$template}{$botones}";
	$vars_vista['JS'][]['JS_CODE']	= <<<JS
		var \$base_url = "{$base_url}";
JS;
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('script.js');
	$vista->add_to_var('vars',$vars_vista);	
	return true;
?>