<?php
ini_set('memory_limit', '1024M');
use \Dompdf\Dompdf;

$vars['IMG_PATH'] = BASE_PATH.'/public/img/logo_ministerio_mediano_blanco.png';
$vars['TITULO']   = $titulo;
$vars['USUARIO'] = $usuario;
$vars['FECHA']  = $fecha;
$fila = 1;
foreach ($resultado as $key => $lista) {
	$vars['ROW'][$key] =[
		'ORDEN' => $fila,
		'NOMBRE' => $lista['nombre'],
		'CUIT' => $lista['cuit'],
		'F_DES' => $lista['fecha_designacion'],
		'F_PUB' => $lista['fecha_publicacion_designacion'],
		'F_RENUN' => $lista['fecha_aceptacion_renuncia'],
		'TIPO' => $lista['dj'],
		'F_PRES' => $lista['fecha_presentacion'],
		'PERIODO' => $lista['periodo'],
		'TRANS' => $lista['nro_transaccion'],
		'PUESTO'	 => $lista['nombre_puesto']			
	];
	$fila++;
}
$content = new \FMT\Template(TEMPLATE_PATH.'/legajos/generar_pdf.html',$vars);

$dompdf = new Dompdf();
$dompdf->load_html($content);
$dompdf->setPaper('A4');
$dompdf->render();

// --------------------------------------------------
// -- INICIO  - Insertar paginado en pie de pagina --
// --------------------------------------------------
$canvas	= $dompdf->get_canvas();
$cpdf	= $canvas->get_cpdf();
$font	= $dompdf->getFontMetrics()->get_font("helvetica", "bold");
$objects= $cpdf->objects;
$pages	= array_filter($objects, function($v) {
    return $v['t'] == 'page';
});
$number = 1;
foreach($pages as $pageId => $page) {
	$canvas->reopen_object($pageId + 1);
	$canvas->text(525, 783, "Pag.: $number", $font, 10, array(0,0,0));
	$canvas->close_object();
	$number++;
}
// -----------------------------------------------
// -- FIN  - Insertar paginado en pie de pagina --
// -----------------------------------------------

if($modo_asincrono === true){
	// Salida por el servidor
	$PATH		= \App\Modelo\Documento::getDirectorioTMP();
	$stream		= $dompdf->output(); // Obtener el PDF generado
	file_put_contents("{$PATH}/{$file_nombre}", $stream, LOCK_EX);
	return;
} else {
	// Salida por el browser
	$dompdf->stream($file_nombre);
	exit;
}
