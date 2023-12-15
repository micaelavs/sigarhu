    <?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
$vars_template = [];

$signo = '';

$vars_vista['SUBTITULO'] = 'Historial de Embargos';
$vars_template['TITULOS'] = [
     ['TITULO' => 'Tipo Embargo'],
    ['TITULO' => 'Autos'],
    ['TITULO' => 'Fecha Alta'],
    ['TITULO' => 'Fecha Cancelación'],
    ['TITULO' => 'Monto'],
  ];

foreach ($historial as $em) {
  ($em['tipo_embargo'] == 'Ejecutivo') ? $signo = ' $' : $signo = ' %';
    $vars_template['ROW'][] =
        ['COL' => [
        ['CONT' => $em['tipo_embargo']],
        ['CONT' => $em['autos']],
        ['CONT' => $em['fecha_alta']],
        ['CONT' => $em['fecha_cancelacion']],
        ['CONT' => $em['monto'].$signo],
        ],
      ];
 }
$vars_template['INFO_A'][0] = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit,'DENOMINACION' =>  $puesto];
$vars_template['BOTON_VOLVER'][] = ['VOLVER' => \App\Helper\Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}") , 'BLOQUE' =>\App\Helper\Bloques::EMBARGO, 'ID' => "volver_legajo", 'CLASS' => "volver_legajo btn btn-default", 'HREF' => "//"];

$vars_vista['CSS_FILES'][]  = ['CSS_FILE' => \App\Helper\Vista::get_url('legajos.css')];
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']   = \App\Helper\Vista::get_url('embargo.js');
$vars_template['TABLA'][]=  new \FMT\Template(TEMPLATE_PATH.'/tabla.html', $vars_template,['CLEAN'=>false]) ;
$embargos = new \FMT\Template(TEMPLATE_PATH.'/legajos/embargo.html', $vars_template, ['CLEAN'=>false]);
$vars_vista['CONTENT'] = "{$embargos}";
$vista->add_to_var('vars',$vars_vista);

return true;