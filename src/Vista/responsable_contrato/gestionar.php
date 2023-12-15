<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
	$config	= FMT\Configuracion::instancia();
	$vars_vista['SUBTITULO']		= 'Alta Contratantes';
    $vars_template['OPERACION']		= 'post';
    $vars_vista['CSS_FILES'][]    = ['CSS_FILE'   => $config['app']['endpoint_cdn']."/js/select2/css/select2.min.css"];
    $vars_vista['JS_FILES'][]     = ['JS_FILE'    => $config['app']['endpoint_cdn']."/js/select2/js/select2.full.min.js"];
    $vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('script.js');
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('responsable_contrato.js');
	$vars_template['DEPENDENCIA'] = \FMT\Helper\Template::select_block($lista_dependencias, $responsable_contrato->id_dependencia);
	
	if ($temp = Arr::get($data,'personas')) {
		$vars_template['CONTRATANTE'] =  \FMT\Helper\Template::select_block($temp,$data['contratante']);
  		$vars_template['FIRMANTE']	=  \FMT\Helper\Template::select_block($temp, $data['firmante']);
	}

    $vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php'); 

	$template = (new \FMT\Template(VISTAS_PATH.'/templates/responsable_contrato/gestionar.html', $vars_template,['CLEAN'=>false]));
	$base_url = \App\Helper\Vista::get_url('index.php');
	$vars_vista['JS'][]['JS_CODE']	= <<<JS
	var \$base_url			= "{$base_url}";
JS;
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);

	return true;
?>