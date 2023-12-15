<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;

$config = FMT\Configuracion::instancia();
$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('observaciones-ajax.js');

$vars_template['URL_BASE'] = \App\Helper\Vista::get_url();
$htmla = (new \FMT\Template(VISTAS_PATH.'/widgets/alert_observacion.html',$vars));
$vars_template['ALERT_MSJ'] = "{$htmla}";
$observaciones = new \FMT\Template(TEMPLATE_PATH.'/legajos/observaciones.html', $vars_template,['CLEAN'=>false]);