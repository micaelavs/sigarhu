<?php

$vars_vista['SUBTITULO']		= 'Alta Organismo Origen/Destino';
$vars_template['OPERACION']		= 'alta';
        $vars_template['NOMBRE']= !empty($comisiones->nombre) ? $comisiones->nombre:'';


$vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/comisiones/index');
$template = (new \FMT\Template(VISTAS_PATH.'/templates/comisiones/alta.html', $vars_template,['CLEAN'=>false]));
$vars_vista['CONTENT'] = "$template";


$vars_vista['JS'][]['JS_CODE'] = <<<JS
        
JS;
$vista->add_to_var('vars',$vars_vista);

return true;