<?php

use  \App\Helper\Vista;

$vars_template = [];
$vars_vista['SUBTITULO'] = 'Documentos del Legajo';
$vars_template['TITULOS'] = [
	['TITULO' => 'Tipo Documentos'],
	['TITULO' => 'Estado'],
	['TITULO' => 'Acciones'],
];
$acciones = '';
$cant_tipos = count($tipos) - 1;
$i = 0;
foreach ($tipos as $key => $doc) {
	$check  = '<span class="fa fa-close color-danger" title ="Sin cargar"></span>';
	if (isset($doc_tipo[$key])) {
		$check = '<span class="fa fa-check color-success" title = "Cargado"></span>';
		if (count($doc_tipo[$key]) == 1 &&  $key != 1) { //Tipo de documento sin definir
			$acciones = '<a href="' . \App\Helper\Vista::get_url("index.php/documentos/descargar_documento/{$doc_tipo[$key][0]}") . '" data-toggle="tooltip" data-placement="top"  title="Ver archivo" data-toggle="modal"  target="_blank"><i class="fa fa-eye"></i></a>';
		} else {
			$acciones = '<a href="' . \App\Helper\Vista::get_url("index.php/documentos/ver_listado/{$empleado->cuit}" . '-' . "{$doc['id']}") . '" data-toggle="tooltip" data-placement="top" data-id="" title="Ver listado" data-toggle="modal"  target="_self"><i class="fa fa-eye"></i></a>';
		}
	} else {
		$acciones = '<i class="fa fa-eye-slash" title="Sin documentos para ver"></i>';
	}
	$vars_template['TIPO_DOC'][$i] = [
		'NOMBRE' => $doc['nombre'],
		'CHECK' => $check,
		'VER'  =>  $acciones,
		'BOR_BOTT' => (($cant_tipos === $i) ? 'border-bottom:1px solid #CECECE' : '')
	];
	$i++;
}

$vars_t = new \FMT\Template(TEMPLATE_PATH . '/documentos/listado.html', $vars_template, ['CLEAN' => false]);
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']   = Vista::get_url('data-table-adjuntos.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']   = Vista::get_url('extras.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']   = Vista::get_url('script.js');
$vars_vista['CSS_FILES'][]	= ['CSS_FILE' => \App\Helper\Vista::get_url('documentos.css')];
$HTTP	= Vista::get_url('index.php');
$ref 	= Vista::get_url('index.php') . "/documentos/alta/{$documento->cuit}";
$ref_v	= Vista::get_url('index.php') . "/legajos/gestionar/{$documento->cuit}";

$vars['BOTON_ACCION'][] = ['CLASS' => 'btn-primary', 'NOMBRE' => 'NUEVO', 'ID' => 'nuevo_documento', 'EXTRAS' => " data-ref='{$ref}' data-bloque='{$id_bloque}'", 'HTTP' => '#'];
$vars['BOTON_VOLVER'][] = ['CLASS' => 'volver_legajo btn-default', 'NOMBRE' => 'VOLVER', 'ID' => 'volver_legajo', 'EXTRAS' => " data-ref='{$ref_v}' data-bloque='{$id_bloque}'", 'HTTP' => '#'];

$vars['CLASS_COL'] = 'col-md-9';
$botones = (new \FMT\Template(VISTAS_PATH . '/widgets/botonera.html', $vars));
$vars_vista['CONTENT'] = "<div class='row'><div class='col-xs-12 text-right'><div id='div_agente'><i class='fa fa-puzzle-piece' aria-hidden='true'></i> Agente: <strong>{$empleado->persona->nombre}	{$empleado->persona->apellido}</strong> | Cuit:<strong>{$empleado->cuit}</strong></div></div></div>{$vars_t}<br><div class='row'>{$botones}</div>";

$vista->add_to_var('vars', $vars_vista);
return true;
