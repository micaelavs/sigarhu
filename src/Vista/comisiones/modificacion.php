<?php

$vars_vista['SUBTITULO']	= 'Modificar Organismo Origen/Destino';
$vars_template['OPERACION'] = 'modificacion';
        $vars_template['NOMBRE']= !empty($comisiones->nombre) ? $comisiones->nombre:'';

$vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/comisiones/index');
$template = (new \FMT\Template(VISTAS_PATH.'/templates/comisiones/modificacion.html', $vars_template,['CLEAN'=>false]));
$vars_vista['CONTENT'] = "$template";

$vista->add_to_var('vars',$vars_vista);
return true;