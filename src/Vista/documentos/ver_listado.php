<?php
use  \App\Helper\Vista;
$config	= \FMT\Configuracion::instancia();
$vars_template = [];
$vars_vista['SUBTITULO'] = 'Documentos <strong> '.$nombre_tipo.' </strong>';
$vars_template['TITULOS'] = [
		['TITULO' => 'Fecha'],
		['TITULO' => 'Usuario'],
		['TITULO' => 'Archivo'],
		['TITULO' => 'Acciones'],
	];

foreach ($doc_empleado as $doc) {
	if($doc->id) {
		$acciones = '';

			$acciones = '<a href="'.\App\Helper\Vista::get_url("index.php/documentos/modificacion/{$doc->id}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Modificar" data-toggle="modal"><i class="fa fa-pencil"></i></a>
					<a href="'.\App\Helper\Vista::get_url("index.php/documentos/baja/{$doc->id}").'" class="borrar" data-user="" data-toggle="tooltip" data-placement="top" title="Eliminar" target="_self"><i class="fa fa-trash"></i></a>';

		$vars_template['ROW'][] =
		    ['COL' => [
				['CONT' => (is_object($doc->fecha_reg)) ? $doc->fecha_reg->format('d/m/Y') : ''],
				['CONT' => $doc->usuario->full_name],
				['CONT' => preg_replace('/\d{14}-/', '', $doc->archivo)],
				['CONT' => '
					<span class="acciones">
					<a href="'.\App\Helper\Vista::get_url("index.php/documentos/descargar_documento/{$doc->id}").'" data-toggle="tooltip" data-placement="top" data-id="" title="Ver archivo" data-toggle="modal"  target="_blank"><i class="fa fa-eye"></i></a>'
					.$acciones.
					'</span>']
				],
			];
	}
}

$vars_vista['JS_FOOTER'][]['JS_SCRIPT']   = Vista::get_url('data-table-adjuntos.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']   = Vista::get_url('script.js');
$vars_vista['CSS_FILES'][]['CSS_FILE'] = $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.css";
$vars_vista['JS_FILES'][]['JS_FILE']  = $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js";	
$vars_vista['JS_FILES'][]['JS_FILE']  = $config['app']['endpoint_cdn']."/datatables/defaults.js";
$vars_vista['JS_FILES'][]['JS_FILE'] = $config['app']['endpoint_cdn']."/datatables/1.10.12/plugins/sorting/datetime-moment.js";
$ref_v	= Vista::get_url('index.php')."/documentos/listado/{$documento->cuit}";
$vars_t = new \FMT\Template(TEMPLATE_PATH.'/tabla.html', $vars_template,['CLEAN'=>false]);
$vars['BOTON_VOLVER'][] = ['CLASS'=>'volver_legajo btn-default','NOMBRE' => 'VOLVER','ID' => 'volver_legajo', 'HTTP' => $ref_v];

$botones = (new \FMT\Template(VISTAS_PATH.'/widgets/botonera.html',$vars));
$vars_vista['CONTENT'] = "<div class='row'><div class='col-xs-12 text-right'><div id='div_agente'><i class='fa fa-puzzle-piece' aria-hidden='true'></i> Agente: <strong>{$empleado->persona->nombre}	{$empleado->persona->apellido}</strong> | Cuit:<strong>{$empleado->cuit}</strong></div></div></div>{$vars_t}{$botones}";

$vista->add_to_var('vars',$vars_vista);
return true;
