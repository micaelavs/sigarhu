<?php
use App\Helper\Vista;
use \FMT\Helper\Arr;

$vars_vista['SUBTITULO'] = 'Modificar Créditos';
$vars_template = [];
$vars_template['OPERACION'] = 'modificacion' ;
$vars_template['FECHA' ] = !empty($temp = $titulo_creditos->fecha) ? $temp->format('d/m/Y') : '';
$vars_template['ACTO_ADMINISTRATIVO'] = !empty($titulo_creditos->acto_administrativo) ? $titulo_creditos->acto_administrativo: '';
$vars_template['CREDITOS'] = !empty($titulo_creditos->creditos) ? $titulo_creditos->creditos: '';
$vars_template['COMPROBANTE'] = !empty($titulo_creditos->archivo) ? $titulo_creditos->archivo: '';
$vars_template['VOLVER'] = Vista::get_url("index.php/legajos/historial_titulo_creditos/{$persona_titulo->id}");
$vars_template['BLOQUE'] = \App\Helper\Bloques::FORMACION;
$vars_template['INFO_A'][0] = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit,'DENOMINACION' =>  $puesto];
	if($titulo_creditos->archivo) {
			$vars_template['ARCHIVO'][0] =  ['URL' => \App\Helper\Vista::get_url("index.php/legajos/mostrar_titulo_credito/{$titulo_creditos->id}")];		
		}
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']   = Vista::get_url('form_presentacion.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('fileinput.min.js');
$vars_vista['CSS_FILES'][]	= ['CSS_FILE' => \App\Helper\Vista::get_url('fileinput.min.css')];
$form_titulo_creditos = new \FMT\Template(TEMPLATE_PATH.'/legajos/formulario_titulo_creditos.html', $vars_template,['CLEAN'=>false]);
$vars_vista['CONTENT'] = "{$form_titulo_creditos}";
$vista->add_to_var('vars',$vars_vista);
return true;
