<?php
namespace App\Controlador;
use App\Modelo;
use App\Helper\Vista;

class Importador extends Base {

/**
 * Formato de columnas del documento
 * 0 => Código de Comisión: (string)
 * 1 => Actividad (string)
 * 2 => Créditos (numeric)
 * 3 => Fecha Hasta (date d/m/Y) (opcional)
 * 4 => Cuit/Cuil (numeric) (opcional)
 *
 * @return void
 */
	protected function accion_procesar_cursos(){
		$vista = $this->vista;
		$importador = Modelo\ImportadorExcel::obtener();
		if($this->request->post('boton_procesar') == 'procesar_cursos') {
			$subir_archivo	= $importador->subirArchivo($_FILES['archivo']);
			if($subir_archivo === false){
				foreach ((array)$importador->errores as $value) {
					$this->mensajeria->agregar($value, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
				}
			} else {
				$registro = 1;
				foreach ($importador->readerFactory->getSheetIterator() as $sheetIndex => $sheet) {
					if($sheetIndex == 2 ){
						foreach ($sheet->getRowIterator()  as $row) {
							$encoding = [];
							$flag = false;
							foreach ($row as $key => $value) {
								if($key >= 5) continue;
								$flag		= !empty($value); 
								$encoding[]	= ($key <= 1) ? mb_convert_encoding($value, 'UTF-8', 'UTF-8') : $value;
							}
							if($flag){
								$proceso	= $importador->procesarCursos($encoding);
								if (!$proceso) {
									$text = 'ERROR: Registro omitidos por errores: fila '. $registro;
									$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
									if (!empty($importador->errores)) {
										foreach ((array)$importador->errores as $value) {
											$this->mensajeria->agregar($value, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
										}
									}
								} else {
									$text	= 'EXITO: Registros cargados exitosamente: fila '. $registro;
									$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_AVISO, $this->clase);
								}
							}
							$registro++;
						}
					}
				}
				$importador->readerFactory->close();
			}
		}
	
		(new Vista($this->vista_default,compact('vista','importador')))->pre_render();
	}
}