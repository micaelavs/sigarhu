<?php

$vars_template['HAYURS'][0]['UR']    = $ur;
$vars_template['HAYURS'][0]['MIN']   = $unidad_retributiva['min'];
$vars_template['HAYURS'][0]['MAX']   = $unidad_retributiva['max'];
$vars_template['HAYURS'][0]['MONTO'] = $unidad_retributiva['monto'];
$vars_template['HAYURS'][0]['TOTAL'] = (!empty($ur)) ? '$ '.($unidad_retributiva['monto'] * $ur) : 'S/D' ;
$vars_template['NOMBRE'] = $empleado->persona->nombre;
$vars_template['APELLIDO'] = $empleado->persona->apellido;
$vars_template['CUIT'] = $empleado->cuit;
$vars_template['HREF'] = $volver;
$remun = new \FMT\Template(TEMPLATE_PATH.'/recibos/remuneracion_ur.html',$vars_template,['CLEAN'=>false]);

$vars_vista['SUBTITULO'] = 'Remureración Personal';
$vars_vista['CSS_FILES'][]    = ['CSS_FILE' => \App\Helper\Vista::get_url('documentos.css')];
$vars_vista['CONTENT'] = "$remun";
$vista->add_to_var('vars', $vars_vista);