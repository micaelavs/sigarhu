<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;

$vars_template['URL_BASE'] = \App\Helper\Vista::get_url();
$vars_vista['SUBTITULO'] = 'Informe Datos Globales';
$total =$dotacion_total[0]['total'];
$estilo = 'dog';
$vars_template['DPR'] = \App\Helper\Vista::get_url('index.php/legajos/datos_recoleccion');

	##########################################################################################
	#		ENVIO DE DATOS DE EMPLEADOS TOTALES A BLOQUE DOTACION_TOTAL						##
	##########################################################################################

	$vars_template['TOTALES'] = $dotacion_total[0]['total'];
	$vars_template['TOTAL_H'] = $dotacion_total[1]['porcentaje'];
	$vars_template['TOTAL_M'] = $dotacion_total[0]['porcentaje'];
	$vars_template['HOMBRES'] = $dotacion_total[1]['cantidad'];
	$vars_template['MUJERES'] = $dotacion_total[0]['cantidad'];


	##########################################################################################
	#		ENVIO DE DATOS DE EMPLEADOS POR UNIDAD A BLOQUE UNIDAD							##
	##########################################################################################

	foreach ($personal_unidad as $key => $pers) {
		$vars_template['UNIDAD'][$key]['FONDO'] = $estilo;
		$vars_template['UNIDAD'][$key]['NOMBRE_UNIDAD'] = $pers['nombre'];
		$vars_template['UNIDAD'][$key]['TOTAL'] = $pers['cant'];
		$vars_template['UNIDAD'][$key]['PORCE'] = $pers['porcentaje'];
		$estilo = ($estilo == 'dog')? 'cat':'dog';
	}

	##########################################################################################
	#		ENVIO DE DATOS DE EMPLEADOS POR SITUACION DE REVISTA Y MODALIDAD DE VINCULACION ##
	# 			A BLOQUE VINCULACION														##
	##########################################################################################
	foreach ($vinculacion as $key => $vin) {
		$modalidad = Arr::path($modalidad_revista, 'modalidad_vinculacion.'.$vin['id_modalidad_vinculacion'].'.nombre','');
		$modalidad = ($modalidad == '') ? str_replace(['adp','dpr'],['Alta Dirección Publica','Datos en proceso de recolección (*)'], $vin['id_modalidad_vinculacion']): $modalidad;
		$revista = Arr::path($modalidad_revista, 'situacion_revista.'.$vin['id_modalidad_vinculacion'].'.'.$vin['id_situacion_revista'].'.nombre','');
		$modalidad_revista['modalidad_vinculacion'][1]['nombre'];
		$vars_template['VINCULACION'][$key]['FONDO'] = $estilo;
		$vars_template['VINCULACION'][$key]['NOMBRE_VINCULACION'] = "$modalidad $revista";
		$vars_template['VINCULACION'][$key]['TOTAL'] = $vin['cantidad'];
		$vars_template['VINCULACION'][$key]['PORCE'] = $vin['porcentaje'];
		$estilo = ($estilo == 'dog')? 'cat':'dog';
		$json_vinculacion[] = ['vinculacion' => "$modalidad $revista", 'cant' => (string)$vin['cantidad']];
	}
	$json_vinculacion = json_encode($json_vinculacion);

	##########################################################################################
	#		ENVIO DE DATOS DE EMPLEADOS DE GENERO FEMENINO A BLOQUE GENERO					##
	##########################################################################################
		$vars_template['TOTAL_AS'] = $situacion_genero[0]['cantidad'];
		$vars_template['PORCE_AS'] = $situacion_genero[0]['porcentaje'];
		$vars_template['TOTAL_DIR'] = $situacion_genero[1]['cantidad'];
		$vars_template['PORCE_DIR'] = $situacion_genero[1]['porcentaje'];

		$situacion_genero['total'] = $dotacion_total[0]['cantidad'] - $situacion_genero[0]['cantidad']- $situacion_genero[1]['cantidad'];
	##########################################################################################
	#		ENVIO DE DATOS DE NIVEL EDUCATIVO DE EMPLEADOS  A BLOQUE FORMACION				##
	##########################################################################################
	$i = 0;
	foreach ($resultado as $key => $value) {
	 	if ($key != 'reco') {
	 		foreach ($value as $est => $val) {
	 			$p_m = round($val['porc_genero_m'], 2).' %';
	 			$p_h = round($val['porc_genero_h'],2).' %';
			 	$m_p = (isset($val['m'])) ? $val['m'].'  -  '.$p_m : '0 - 0%';
			 	$h_p = (isset($val['h'])) ? $val['h'].'  -  '.$p_h : '0 - 0%';

			 	$vars_template['FORMACION'][$i]['FONDO'] 		= $estilo;
				$vars_template['FORMACION'][$i]['TITULO']		= $nivel_e[$key]['nombre'].'  -  '.$estado_titulo[$est]['nombre'];
				$vars_template['FORMACION'][$i]['TOTAL_F'] 		= $val['total'];
				$vars_template['FORMACION'][$i]['PORCE'] 		= round($val['porc_titulo'],2).' %';
				$vars_template['FORMACION'][$i]['PORCE_H'] 		= $h_p;
				$vars_template['FORMACION'][$i]['PORCE_M'] 		= $m_p;
				$i++;
				$aux[] = $val['total'];
	 		}
	 	} else {
	 		 	$p_m = round($value['porc_genero_m'], 4).'%';
	 			$p_h = round($value['porc_genero_h'],4).'%';
			 	$m_p = (isset($value['m'])) ? $value['m'].' - '.$p_m : '0 - 0%';
			 	$h_p = (isset($value['h'])) ? $value['h'].' - '.$p_h : '0 - 0%';

			 	$vars_template['FORMACION'][$i]['FONDO'] 		= $estilo;
				$vars_template['FORMACION'][$i]['TITULO']		= "Datos Proc. Recolección (*)";
				$vars_template['FORMACION'][$i]['TOTAL_F'] 		= $value['total'];
				$vars_template['FORMACION'][$i]['PORCE'] 		= round($value['porc_titulo'],2).' %';
				$vars_template['FORMACION'][$i]['PORCE_H'] 		= $h_p;
				$vars_template['FORMACION'][$i]['PORCE_M'] 		= $m_p;
	 	}
		$estilo = ($estilo == 'dog')? 'cat':'dog';
		$i++;
		$json_formacion[] = ['nivel' => isset($nivel_e[$key]['nombre']) ? $nivel_e[$key]['nombre'] : 'Datos en Recolección' , 'completo' => isset($aux[0]) ? $aux[0] : $value['total'],'incompleto' => isset($aux[1]) ? $aux[1] : null];
		unset($aux);
	 } 
	 $json_formacion = json_encode($json_formacion);


$template = new \FMT\Template(TEMPLATE_PATH.'/legajos/datos_globales.html',$vars_template,['CLEAN'=>false]);

	##########################################################################################
	#		ENVIO DE DATOS A TODOS LOS GRAFICOS								 				##
	##########################################################################################

$vars_vista['JS'][]['JS_CODE'] = <<<JS
var data_torta  = [
  {
    genero: "Hombre",
    cantidad: {$dotacion_total[1]['cantidad']}
  },
  {
    genero: "Mujer",
    cantidad: {$dotacion_total[0]['cantidad']}
  }
];

var data_dona  = [
  {
    dependencia: "Unidad Ministro",
    cantidad: {$personal_unidad[0]['cant']}
  },
  {
    dependencia: "SCA",
    cantidad: {$personal_unidad[1]['cant']}
  },
  {
    dependencia: "SGT",
    cantidad: {$personal_unidad[2]['cant']}
  },
  {
    dependencia: "SPT",
    cantidad: {$personal_unidad[3]['cant']}
  },
  {
    dependencia: "SOT",
    cantidad: {$personal_unidad[4]['cant']}
  },
  {
    dependencia: "Datos",
    cantidad: {$personal_unidad[5]['cant']}
  }
];

var vinculacion = {$json_vinculacion};

var formacion = {$json_formacion};

var genero = [
{ 		
  name: "Autoridades Mujeres",
  children: [
    { 	name: "Autoridades Superior", 
    	value:  {$situacion_genero[0]['cantidad']}
    },
    { 	name: "Directoras / Coordinadoras",
    	value: {$situacion_genero[1]['cantidad']}
     }
  ]

},
{
  name: "Mujeres", value:  {$situacion_genero['total']}
}
];
JS;
$config	= FMT\Configuracion::instancia();
$vars_vista['CSS_FILES'][]	= ['CSS_FILE' => \App\Helper\Vista::get_url('datos_globales.css')];
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'][]	= $config['app']['endpoint_cdn']."/js/amcharts/4.7.9/core.js";
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'][]	= $config['app']['endpoint_cdn']."/js/amcharts/4.7.9/charts.js";
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'][]	= $config['app']['endpoint_cdn']."/js/amcharts/4.7.9/themes/animated.js";
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'][]	= $config['app']['endpoint_cdn']."/js/amcharts/4.7.9/plugins/sunburst.js";
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']		= \App\Helper\Vista::get_url('amchart.js');

$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);
return true;
