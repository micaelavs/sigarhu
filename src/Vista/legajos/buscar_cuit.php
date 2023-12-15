
<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
	$vars_vista['SUBTITULO']		= 'Búsqueda por Cuit';
    $vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/legajos/agentes'); 
	$template = (new \FMT\Template(VISTAS_PATH.'/templates/legajos/formulario_cuit.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);

	return true;
?>