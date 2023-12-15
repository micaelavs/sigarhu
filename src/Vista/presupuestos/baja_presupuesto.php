<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
$pres = "SAF[{$presupuesto->id_saf}],JURIS.[$presupuesto->id_jurisdiccion],UBI GEO[$presupuesto->id_ubicacion_geografica],PROG[$presupuesto->id_programa],SUBPROG[$presupuesto->id_subprograma],PROY[$presupuesto->id_proyecto],ACT[$presupuesto->id_actividad],OBRA[$presupuesto->id_obra]";
$pres = preg_replace('/\[\]/', '[--]', $pres);
$vars_vista['SUBTITULO'] = 'Baja de Presupuesto';
$vars_template['CONTROL'] = "Presupuesto";
$vars_template['ARTICULO'] = 'el';
$vars_template['TEXTO_AVISO'] = 'Dará de baja';
$vars_template['NOMBRE'] = $pres;
$vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/presupuestos/index');
$template = (new \FMT\Template(VISTAS_PATH.'/widgets/confirmacion.html', $vars_template,['CLEAN'=>false]));
$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;