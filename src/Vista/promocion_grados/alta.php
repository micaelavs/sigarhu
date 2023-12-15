<?php
use \FMT\Template;
use \FMT\Helper\Arr;

	$config		= FMT\Configuracion::instancia();
	$vars_vista	= $vars_template	= [];

	$vars_vista['SUBTITULO']		= 'Aplicar Promocion - Alta de registro';
    $vars_template['OPERACION']		= 'alta';

// Datos actuales
	$nivel_nombre				= Arr::path($convenios, 'agrupamientos.'.$empleado->situacion_escalafonaria->id_agrupamiento.'.niveles.'.$empleado->situacion_escalafonaria->id_nivel.'.nombre', '');
	$tramo_nombre				= Arr::path($convenios, 'tramos.'.$empleado->situacion_escalafonaria->id_tramo.'.nombre', '');
	$grado_nombre				= Arr::path($convenios, 'tramos.'.$empleado->situacion_escalafonaria->id_tramo.'.grados.'.$empleado->situacion_escalafonaria->id_grado.'.nombre', '');

    $vars_template['CUIT']		= !empty($temp = $empleado->cuit) ? $temp : '';
    $vars_template['NOMBRE']	= !empty($temp = $empleado->persona->nombre) ? $temp : '';
    $vars_template['APELLIDO']	= !empty($temp = $empleado->persona->apellido) ? $temp : '';
	$vars_template['TRAMO']		= (string)$tramo_nombre;
	$vars_template['NIVEL']		= (string)$nivel_nombre;
	$vars_template['GRADO']		= (string)$grado_nombre;
	$vars_template['FECHA_ULTIMA_PROMOCION']	= !empty($empleado->situacion_escalafonaria->ultimo_cambio_grado instanceof \DateTime) ? $empleado->situacion_escalafonaria->ultimo_cambio_grado->format('d/m/Y') : '';
	$vars_template['CREDITOS_DISPONIBLES']		=  $simulacion->creditos_disponibles;
	
	// Datos de Simulacion
	$motivos									= \App\Modelo\SimuladorPromocionGrado::getParam('MOTIVOS_PROMOCION');
	$vars_template['MOTIVO']					=  Arr::path($motivos, $simulacion->id_motivo.'.nombre', '');
	$vars_template['EVALUACION_INICIO']			=  (string)$simulacion->anio_inicio;
	$vars_template['EVALUACION_FIN']			=  (string)$simulacion->anio_fin;
	$vars_template['GRADO_PROMOCIONAR']			=  (string)$simulacion->grado_analisis;
	$vars_template['TEXTO_APLICA']				=  empty($simulacion->aplica_promocion)
		? 'No Aplica'
		: 'Aplica';
	$vars_template['CLASS_TEXTO_APLICA']				=  empty($simulacion->aplica_promocion)
		? 'label-warning'
		: 'label-success';
	if(!empty($simulacion->aplica_promocion)){
		$vars_template['FORMULARIO_APLICA']			= [[]];
		$vars_template['BOTON_FORMULARIO_APLICA']	= [[]];
	}
	
	// Recuento de creditos
	$vars_template['REQUERIDOS']				= (string)$simulacion->creditos_requeridos;
	$vars_template['RECONOCIDOS']				= (string)$simulacion->creditos_reconocidos;
	$vars_template['SUB_TOTAL']					= (string)$simulacion->creditos_subtotal;
	$vars_template['DESCONTADOS']				= (string)((int)$simulacion->creditos_requeridos - (int)$simulacion->creditos_reconocidos);
	$vars_template['TOTAL_PERIODO']				= (string)(int)$simulacion->total_periodo;
	$vars_template['UTILIZABLES']				= $simulacion->creditos_disponibles-(int)round(((int)$simulacion->total_periodo/2),0, PHP_ROUND_HALF_UP);
	
	$vars_template['CANCELAR']	= \App\Helper\Vista::get_url('index.php/SimuladorPromocionGrados/listado_simulacion_promocion_grado/'.$empleado->id);
	$template					= (new Template(VISTAS_PATH.'/templates/promocion_grados/alta.html', $vars_template,['CLEAN'=>false]));
	$base_url					= \App\Helper\Vista::get_url('index.php');
	$vars_vista['CONTENT']		= "$template";
	$vars_vista['JS'][]['JS_CODE']	= <<<JS
		var \$base_url = "{$base_url}";
JS;
	$vars_vista['CSS_FILES'][]		= ['CSS_FILE'   => $config['app']['endpoint_cdn']."/js/select2/css/select2.min.css"];
	$vars_vista['CSS_FILES'][]		= ['CSS_FILE' => \App\Helper\Vista::get_url('fileinput.min.css')];
	$vars_vista['JS_FILES'][]		= ['JS_FILE'    => $config['app']['endpoint_cdn']."/js/select2/js/select2.full.min.js"];
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('script.js');
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('fileinput.min.js');
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('promocion_creditos.js');
	$vista->add_to_var('vars',$vars_vista);	
	return true;
?>