<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;

    $vars_vista['SUBTITULO'] = 'Baja de Documento';
    $vars_template['CONTROL'] = 'Documento';
    $vars_template['ARTICULO'] = 'El';
    $vars_template['TEXTO_AVISO'] = 'Dará de baja ';			
    $vars_template['NOMBRE'] = $doc_empleado->archivo;
    $vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/documentos/ver_listado');
    $template = (new \FMT\Template(VISTAS_PATH.'/widgets/confirmacion.html', $vars_template,['CLEAN'=>false]));
    $vars_vista['CONTENT'] = "$template";
    $vista->add_to_var('vars',$vars_vista);

    return true;