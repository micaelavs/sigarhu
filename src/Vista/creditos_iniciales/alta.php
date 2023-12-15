<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
$config	= FMT\Configuracion::instancia();

	$vars_vista['SUBTITULO'] = 'Alta Creditos Iniciales';
	$vars_template = [];
	$vars_template['OPERACION'] = 'alta' ;
	$vars_template = [
			'OPERACION' => 'alta',
			'CUIT' =>  !empty($creditos->empleado->cuit) ? $creditos->empleado->cuit : '',
			'NOMBRE_APELLIDO' => !empty($creditos->empleado->nombre_apellido) ? $creditos->empleado->nombre_apellido : '',
			'FECHA_CONSIDERADA'	=> !empty($creditos->fecha_considerada) ? $creditos->fecha_considerada->format('d/m/Y') : '',
			'CREDITOS'	=> !empty($creditos->creditos) ? $creditos->creditos : '',
			'DESCRIPCION'	=> !empty($creditos->descripcion) ? $creditos->descripcion : '',
			'DISABLED'		=> '',
		];

	// $vars_vista['CSS_FILES'][]		= ['CSS_FILE'   => $config['app']['endpoint_cdn']."/js/select2/css/select2.min.css"];
	// $vars_vista['JS_FILES'][]		= ['JS_FILE'    => $config['app']['endpoint_cdn']."/js/select2/js/select2.full.min.js"];
   
  
  	$vars_template['BOTON_VOLVER'][] = ['VOLVER' => \App\Helper\Vista::get_url("index.php/CreditosIniciales/listar") , 'BLOQUE' =>\App\Helper\Bloques::FORMACION, 'ID' => "volver_legajo", 'CLASS' => "volver_legajo btn btn-default", 'HREF' => "#"]; 
	$template = (new \FMT\Template(VISTAS_PATH.'/templates/creditos_iniciales/alta.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('script.js');
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('bootstrap-typeahead.min.js');
    $vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('/creditos_iniciales/creditos_iniciales.js');

	$base_url = \App\Helper\Vista::get_url('index.php');
	 	$vars_vista['JS'][]['JS_CODE']	= <<<JS
	 	var \$base_url = "{$base_url}";
	JS;
	$vista->add_to_var('vars',$vars_vista);	
	return true;
?>