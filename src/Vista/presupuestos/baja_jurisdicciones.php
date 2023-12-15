<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
$vars_vista['SUBTITULO'] = 'Baja de Código de Jurisdicción';
$vars_template['CONTROL'] = 'Código de Jurisdicción';
$vars_template['ARTICULO'] = 'el';
$vars_template['TEXTO_AVISO'] = 'Dará de baja ';			
$vars_template['NOMBRE'] = $jurisdiccion->nombre;
$vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_jurisdicciones');
$template = (new \FMT\Template(VISTAS_PATH.'/widgets/confirmacion.html', $vars_template,['CLEAN'=>false]));
$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;