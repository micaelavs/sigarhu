<?php
use App\Helper\Vista;
use \FMT\Helper\Arr;

$vars_vista['SUBTITULO'] = 'Alta Créditos';
$vars_template = [];
$vars_template['OPERACION'] = 'alta' ;
$vars_template['FECHA' ] = !empty($temp = $titulo_creditos->fecha) ? $temp->format('d/m/Y') : '';
$vars_template['ACTO_ADMINISTRATIVO'] = $titulo_creditos->acto_administrativo;
$vars_template['CREDITOS'] = $titulo_creditos->creditos;
$vars_template['COMPROBANTE'] = ($titulo_creditos->archivo) ? 'Reemplazar Comprobante': 'Comprobante';
$vars_template['INFO_A'][0] = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit,'DENOMINACION' =>  $puesto];
$vars_template['VOLVER'] =  Vista::get_url("index.php/legajos/historial_titulo_creditos/{$persona_titulo->id}");
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']   = Vista::get_url('form_titulo_creditos.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('fileinput.min.js');
$vars_vista['CSS_FILES'][]	= ['CSS_FILE' => \App\Helper\Vista::get_url('fileinput.min.css')];
$form_titulo_creditos = new \FMT\Template(TEMPLATE_PATH.'/legajos/formulario_titulo_creditos.html', $vars_template,['CLEAN'=>false]);
$vars_vista['CONTENT'] = "{$form_titulo_creditos}";
$vista->add_to_var('vars',$vars_vista);
return true;
