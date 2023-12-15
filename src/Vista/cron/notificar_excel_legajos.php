<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use \App\Helper\Vista;
	$vars_template	= [
		'NOMBRE_APELLIDO'	=> $usuario->nombre.' '.$usuario->apellido,
		'LINK_ARCHIVO'		=> Vista::get_url('index.php/documentos/descarga_excel/').$file_nombre,
	];

	$template		= new \FMT\Template(TEMPLATE_PATH.'/cron/notificar_excel_legajos.html',$vars_template,['CLEAN'=>false]);
	echo "$template";