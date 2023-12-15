
<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
	$vars_vista['SUBTITULO']	= 'Modificar Nivel Educativo';
    $vars_template['OPERACION'] = 'modificacion';
	$vars_template['NOMBRE'] =  !empty($nivel_educativo->nombre) ? $nivel_educativo->nombre: '';
    $vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/nivel_educativo/index'); 
	$template = (new \FMT\Template(VISTAS_PATH.'/templates/nivel_educativo/alta.html', $vars_template,['CLEAN'=>false]));
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);
	return true;
?>