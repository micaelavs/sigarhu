<?php
use App\Helper\Vista;
use FMT\Helper\Arr;
$vars_template = [];
$vars_vista['SUBTITULO'] = 'Listado de Familiares';
$vars_template['TITULOS'] = [
    ['TITULO' => 'Parentesco'], 
	['TITULO' => 'Nombre Completo'],
	['TITULO' => 'Fecha Nacimiento'],
	['TITULO' => 'Tipo Doc.'],
	['TITULO' => 'Documento'],
	['TITULO' => 'Nivel Estudio'],
	['TITULO' => 'Reintegro Guardería'],
	['TITULO' => 'Discapacidad'],
	['TITULO' => 'Acciones'],
  ];
foreach ($lista_familiares as $familiar) {

	$vars_template['ROW'][] =
	    ['COL' => [
			['CONT' => $parametricos['parentesco'][$familiar->parentesco]['nombre']],
			['CONT' => "{$familiar->nombre} {$familiar->apellido}"],
			['CONT' => $familiar->fecha_nacimiento->format('d/m/Y')],
			['CONT' => Arr::path($parametricos['tipo_documento'], $familiar->tipo_documento.'.nombre')],
			['CONT' => $familiar->documento],
			['CONT' => Arr::path($parametricos['formacion_tipo_titulo'], $familiar->nivel_educativo.'.nombre')],
			['CONT' => ($familiar->reintegro_guarderia) ? 'SÍ' : 'NO'],
			['CONT' => ($familiar->discapacidad) ? 'SÍ' : 'NO'],
			['CONT' => 
				'<span class="acciones">'.
				(($permisos['grupo_familiar']['modificacion']) ?
				'<a href="'.\App\Helper\Vista::get_url("index.php/legajos/modificar_familiar/{$familiar->id}").'" data-toggle="tooltip" data-placement="top" title="Editar Familiar"><i class="fa fa-edit"></i></a>':'').				
				(($permisos['grupo_familiar']['baja']) ?
				'<a href="'.\App\Helper\Vista::get_url("index.php/legajos/baja_familiar/{$familiar->id}").'" class="borrar" data-toggle="tooltip" data-placement="top" title="Eliminar" target="_self"><i class="fa fa-trash"></i></a>':'').
				'</span>'
			],
				]	
		];
}
$config	= FMT\Configuracion::instancia();
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']  = Vista::get_url('grupo_familiar.js');
$vars_vista['CSS_FILES'][]  = ['CSS_FILE' => $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.css"];
$vars_vista['JS_FILES'][] = ['JS_FILE'  => $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js"];  
$vars_vista['JS_FILES'][] = ['JS_FILE'  => $config['app']['endpoint_cdn']."/datatables/defaults.js"];
$vars_vista['JS_FILES'][] = ['JS_FILE' => $config['app']['endpoint_cdn']."/datatables/1.10.12/plugins/sorting/datetime-moment.js"];


$ref_v = Vista::get_url("index.php/legajos/agentes");
$vars['CLASS_COL'] = 'col-md-12';
$vars['BOTON_VOLVER'][] = ['HTTP'=> \App\Helper\Vista::get_url('index.php'),'CONTROL_VOLVER' => '/legajos', 'ACCION_VOLVER' => '/agentes', 'CLASS'=>'btn-default','NOMBRE' => 'VOLVER','ID' => 'volver_agentes'];
if($permisos['grupo_familiar']['alta']){
	$vars['BOTON_ACCION'][] = [ 'HTTP'=> \App\Helper\Vista::get_url('index.php'),
								'CONTROL' => '/legajos',
								'ACCION'  => "/alta_familiar/{$empleado->cuit}",
								'CLASS'	  => 'btn-primary',
								'NOMBRE'  => 'NUEVO'
							  ];
	
}

$vars_template= [
  'TABLA' => new \FMT\Template(TEMPLATE_PATH.'/tabla.html', $vars_template,['CLEAN'=>false]),
  'BOTONERA' => new \FMT\Template(VISTAS_PATH.'/widgets/botonera.html',$vars)
];

$grupo_familiar = new \FMT\Template(TEMPLATE_PATH.'/legajos/listado_familiares.html', $vars_template, ['CLEAN'=>false]);
return true;