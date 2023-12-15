<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
$config	= FMT\Configuracion::instancia();

	$vars_vista['SUBTITULO']		= 'Modificar Créditos para Promoción';
	$vars_vista['CSS_FILES'][]		= ['CSS_FILE'   => $config['app']['endpoint_cdn']."/js/select2/css/select2.min.css"];
	$vars_vista['JS_FILES'][]		= ['JS_FILE'    => $config['app']['endpoint_cdn']."/js/select2/js/select2.full.min.js"];
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('script.js');
    $vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('promocion_creditos.js');
 
    $vars_template['OPERACION']		= 'modificacion';
	$vars_template['FECHA_DESDE']	= !empty($temp = $creditos->fecha_desde) ? $temp->format('d/m/Y') : '';
	$vars_template['AGRUPAMIENTO'] 	= \FMT\Helper\Template::select_block($agrupamientos,$idAgrupamiento);
	$i = 0;
	foreach($agrupamientos as $arg){
		$vars_template['AGRUPAMIENTO'][$i]['NIVELES'] = json_encode($arg['niveles']);
		$i++;
	}
	$vars_template['NIVEL']         = !empty($temp = $creditos->id_nivel) ? $temp : '';
	$vars_template['TRAMO']			= \FMT\Helper\Template::select_block($tramos,$creditos->id_tramo);
	$vars_template['CREDITOS']		= !empty($creditos->creditos) ? $creditos->creditos: '';
    $vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/PromocionCreditos/index'); 
	$template = (new \FMT\Template(VISTAS_PATH.'/templates/promocion_creditos/alta.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	
	$base_url = \App\Helper\Vista::get_url('index.php');
	$vars_vista['JS'][]['JS_CODE']	= <<<JS
	var \$base_url = "{$base_url}";
JS;
	$vista->add_to_var('vars',$vars_vista);	
	return true;
?>
