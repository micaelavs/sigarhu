<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use \App\Helper\Vista;
$config	= FMT\Configuracion::instancia();

$vars_vista['SUBTITULO'] = 'Listado Promociones';
$vars_template = [];
$vars_template['CLASS'] = 'tabla_credito_promocion';
$vars_template['TITULOS'] = [
    ['TITULO' => 'Cuit',
    'DATA'	=> 'data-target="evaluaciones_anio" data-width="10%" data-orderable="true"'],
    ['TITULO' => 'Nombre y Apellido',
    'DATA'	=> 'data-target="evaluaciones_anio" data-width="15%" data-orderable="true"'],
    ['TITULO' => 'Fecha Promocion',
    'DATA'	=> 'data-target="evaluaciones_anio" data-type="date" data-orderable="true"'],
    ['TITULO' => 'Periodo Inicio ',
    'DATA'	=> 'data-target="evaluaciones_anio" data-orderable="true"'],
    ['TITULO' => 'Periodo Fin',
    'DATA'	=> 'data-target="evaluaciones_anio" data-orderable="true"'],
    ['TITULO' => 'Motivo',
    'DATA'	=> 'data-target="evaluaciones_anio" data-orderable="true"'],
    ['TITULO' => 'Nuevo Grado',
    'DATA'	=> 'data-target="evaluaciones_anio" data-orderable="false"'],
    ['TITULO' => 'Acto Administrativo',
    'DATA'	=> 'data-target="evaluaciones_anio" data-orderable="false"'],
    ['TITULO' => 'Numero de Expediente',
    'DATA'	=> 'data-target="evaluaciones_anio" data-orderable="false"'],
    ['TITULO' => 'Acción',
    'DATA'	=> 'data-target="evaluaciones_anio" data-width="5%" data-orderable="false"'],
  ];

foreach ($listado_promociones as $td) {
    $link   = \App\Helper\Vista::get_url('index.php/Promocion_grados/resumen/'.$td->id);
    $accion = '<div class="btn-group btn-group-sm"><a href="'.$link.'" class="btn btn-link btn-sm" data-toggle="tooltip" title="Ver Resumen" data-original-title="Ver Resumen"><i class="fa fa-eye"></i></a></div>';
    $vars_template['ROW'][] =
        ['COL' => [
            ['EXTRAS'=>[],'CONT' => $td->empleado->cuit],
            ['EXTRAS'=>[],'CONT' => $td->empleado->persona->nombre.' '.$td->empleado->persona->apellido],
            ['EXTRAS'=>[],'CONT' => $td->fecha_promocion->format('d/m/Y')],
            ['EXTRAS'=>[],'CONT' => $td->periodo_inicio],
            ['EXTRAS'=>[],'CONT' => $td->periodo_fin],
            ['EXTRAS'=>[],'CONT' => $td->id_motivo],
            ['EXTRAS'=>[],'CONT' => $td->id_grado],
            ['EXTRAS'=>[],'CONT' => $td->acto_administrativo],
            ['EXTRAS'=>[],'CONT' => $td->numero_expediente],
            ['EXTRAS'=>[],'CONT' => $accion],
        ]
    ];
 }
 $vars_template['URL_BASE']              = \App\Helper\Vista::get_url();
 $vars_template['DATOS_TABLA'][]         = new \FMT\Template(TEMPLATE_PATH.'/tabla.html', $vars_template,['CLEAN'=>false]) ;
 $content                                = new \FMT\Template(TEMPLATE_PATH.'/promocion_grados/index.html',$vars_template,['CLEAN'=>false]);
 
 $vars_vista['JS'][]['JS_CODE']	= <<<JS
    \$data_table_init   = '{$vars_template['CLASS']}';
JS;
 $vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('script.js');
 $vars_vista['CSS_FILES'][]['CSS_FILE']  = $config['app']['endpoint_cdn'].'/datatables/1.10.12/datatables.min.css';
 $vars_vista['JS_FILES'][]['JS_FILE']    = $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js"; 
 $vars_vista['JS_FILES'][]['JS_FILE']    = $config['app']['endpoint_cdn']."/datatables/1.10.12/plugins/sorting/datetime-moment.js";
 $vars_vista['JS_FILES'][]['JS_FILE']    = $config['app']['endpoint_cdn']."/datatables/defaults.js";
$vars_vista['CONTENT'] = "{$content}";
$vista->add_to_var('vars',$vars_vista);
return true;