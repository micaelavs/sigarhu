<?php

namespace App\Controlador;

//use FMT\Controlador;
use App\Modelo;
use App\Modelo\Documento;
use App\Helper;
//use FMT\Configuracion;
use App\Helper\Vista;
//use App\Helper\Util;

class Documentos extends Base
{

	protected function accion_listado()
	{
		$id_bloque = $this->get_bloque();
		$empleado = Modelo\Empleado::obtener($this->request->query('id'));
		$documento = Modelo\Documento::obtener();
		$documento->id_empleado = $empleado->id;
		$documentos_tipo = $documento->obtener_doc_tipo();
		$doc_tipo = [];
		foreach ($documentos_tipo as $key => $value) {
			$doc_tipo[$value['id_tipo']][] = $value['id'];
		}
		$documento->cuit = $this->request->query('id');
		$tipos = $documento->listar_tipo();
		$vista = $this->vista;
		(new Vista($this->vista_default, compact('vista', 'documento', 'empleado', 'tipos', 'doc_tipo', 'id_bloque')))->pre_render();
	}

	protected function accion_ver_listado()
	{
		$dato = explode('-', $this->request->query('id'));
		$documento = Modelo\Documento::obtener();
		$empleado = Modelo\Empleado::obtener($dato[0]);
		$documento->id_empleado = $empleado->id;
		$cuit = !empty($dato[0]) ? $dato[0] : '';
		$id_tipo =  !empty($dato[1]) ? $dato[1] : '';
		$documento->cuit = $cuit;
		$documento->id_tipo = $id_tipo;
		$nombre_tipo = Modelo\Documento::get_nombre_tipo($cuit);
		$doc_empleado = $documento->listar_documentos();
		$user_log = $this->_user->id;
		$vista = $this->vista;
		(new Vista($this->vista_default, compact('vista', 'doc_empleado', 'documento', 'empleado', 'user_log', 'nombre_tipo')))->pre_render();
	}

	protected function accion_alta()
	{
		$id_bloque = $this->get_bloque();		
		$empleado = Modelo\Empleado::obtener($this->request->query('id'));
		$doc_empleado = Documento::obtener();
		$doc_empleado->id_empleado = $empleado->id;
		$doc_empleado->cuit = $empleado->cuit;
		$doc_empleado->id_tipo   = $this->request->post('id_tipo');
		$doc_empleado->id_usuario  = Modelo\Usuario::obtenerUsuarioLogueado()->id;
		$doc_empleado->fecha_reg   = \DateTime::createFromFormat('d/m/Y H:i:s', gmdate('d/m/Y') . ' 0:00:00');
		$tipos =  Modelo\Documento::listar_tipo();
		$_SESSION['id_bloque'] = $id_bloque;
		if ($this->request->post('formulario') == 'alta') {
			if ($_FILES) {
				$doc_empleado->archivo = $_FILES['documento'];
				if ($doc_empleado->alta()) {
					$this->mensajeria->agregar("El documento fue adjuntado exitosamente.", \FMT\Mensajeria::TIPO_AVISO, $this->clase);
					$redirect = Helper\Vista::get_url('index.php/documentos/listado/' . $doc_empleado->cuit);
					$this->redirect($redirect);
				} else {
					foreach ($doc_empleado->errores as $value) {
						$this->mensajeria->agregar($value, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			} else {
				$this->mensajeria->agregar('Seleccione un archivo.', \FMT\Mensajeria::TIPO_ERROR, $this->clase);
			}
		}
		$vista = $this->vista;
		$formulario = "alta";
		(new Vista($this->vista_default, compact('vista', 'tipos', 'formulario', 'doc_empleado', 'empleado')))->pre_render();
	}

	protected function accion_modificacion()
	{
		$id_bloque = $this->get_bloque();
		$doc_empleado = Documento::obtener($this->request->query('id'));
		$empleado = Modelo\Empleado::obtener($doc_empleado->id_empleado, 1);
		$doc_empleado->id_empleado = $empleado->id;
		$doc_empleado->cuit = $empleado->cuit;
		$doc_empleado->id_usuario  = Modelo\Usuario::obtenerUsuarioLogueado()->id;
		$doc_empleado->fecha_reg   = \DateTime::createFromFormat('d/m/Y H:i:s', gmdate('d/m/Y') . ' 0:00:00');
		$tipos =  Modelo\Documento::listar_tipo();
		if ($this->request->post('formulario') == 'modificacion') {
			if ($_FILES) {
				$doc_empleado->archivo = ($_FILES['documento']['error'] == UPLOAD_ERR_OK) ? $_FILES['documento'] : $doc_empleado->archivo;
				//$doc_empleado->archivo = $_FILES['documento'];
				$doc_empleado->id_tipo =  !empty($temp = $this->request->post('id_tipo')) ?  $temp : $doc_empleado->id_tipo;
				if ($doc_empleado->modificacion()) {
					$this->mensajeria->agregar("El documento fue modificado exitosamente.", \FMT\Mensajeria::TIPO_AVISO, $this->clase);
					$_SESSION['id_bloque'] = $id_bloque;
					$redirect = Helper\Vista::get_url('index.php/documentos/listado/' . $empleado->cuit);
					$this->redirect($redirect);
				} else {
					foreach ($doc_empleado->errores as $value) {
						$this->mensajeria->agregar($value, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			} else {
				$this->mensajeria->agregar('Seleccione un archivo.', \FMT\Mensajeria::TIPO_ERROR, $this->clase);
			}
		}
		$vista = $this->vista;
		$_SESSION['id_bloque'] = $id_bloque;
		$formulario = "modificacion";
		(new Vista(VISTAS_PATH . '/documentos/alta.php', compact('vista', 'tipos', 'formulario', 'doc_empleado', 'empleado')))->pre_render();
	}

	protected function accion_baja()
	{
		$id_bloque = $this->get_bloque();
		$doc_empleado = Documento::obtener($this->request->query('id'));
		$empleado = Modelo\Empleado::obtener($doc_empleado->id_empleado, 1);
		if ($doc_empleado->id) {
			if ($this->request->post('confirmar')) {
				$res = $doc_empleado->baja();
				$_SESSION['id_bloque'] = $id_bloque;
				if ($res) {
					$this->mensajeria->agregar('AVISO: El documento se eliminó de forma exitosa.', \FMT\Mensajeria::TIPO_AVISO, $this->clase);
					$redirect = Helper\Vista::get_url('index.php/documentos/ver_listado/' . $empleado->cuit . '-' . $doc_empleado->id_tipo);
					$this->redirect($redirect);
				} else {
					$this->mensajeria->agregar('AVISO: No es posible eliminar el documento. Contácte con el Administrador', \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					$redirect = Helper\Vista::get_url('index.php/documentos/ver_listado/' . $empleado->cuit . '-' . $doc_empleado->id_tipo);
					$this->redirect($redirect);
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/documentos/ver_listado/' . $empleado->cuit . '-' . $doc_empleado->id_tipo);
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista($this->vista_default, compact('doc_empleado', 'vista')))->pre_render();
	}

	protected function accion_descargar_documento()
	{
		$doc_empleado = Documento::obtener($this->request->query('id'));
		$empleado = \App\Modelo\Empleado::obtener($doc_empleado->id_empleado, 1);
		$arch = preg_replace(['/\d{14}-/', '/\s/'], ['', '_'], $doc_empleado->archivo);
		$aux = explode('.', $arch);
		$ext = end($aux);
		$doc = BASE_PATH . '/uploads/' . $empleado->cuit . '/' . $doc_empleado->archivo;
		header("Content-Disposition: inline; filename=" . $arch . "");
		header("Content-type: application/" . $ext . ";");
		readfile($doc);
	}

	protected function get_bloque()
	{
		$id_bloque = $this->request->post('id_bloque');
		if (!$id_bloque && isset($_SESSION['id_bloque'])) {
			$id_bloque = $_SESSION['id_bloque'];
			unset($_SESSION['id_bloque']);
		}

		$id_bloque = isset($bloque) ? $bloque['id'] : $id_bloque;

		return $id_bloque;
	}

	protected function accion_descarga_pdf()
	{
		$hash_archivo = $this->request->query('id');
		$directorio	= Documento::getDirectorioTMP();

		if (!file_exists($directorio . '/' . $hash_archivo . '.pdf')) {
			$this->mensajeria->agregar('El archivo solicitado no esta disponible. Deberá generarlo nuevamente.', \FMT\Mensajeria::TIPO_ERROR);
			$redirect	= !empty($tmp = $_SERVER['HTTP_REFERER'])
				? $tmp : Vista::get_url('index.php/legajos/historial_anticorrupcion');
			$this->redirect($redirect);
		}

		$nombre_para_humanos = base64_decode($hash_archivo);
		header("Content-Disposition: inline; filename=" . $nombre_para_humanos);
		header("Content-type: application/pdf;");
		echo file_get_contents($directorio . '/' . $hash_archivo . '.pdf');
	}

	protected function accion_descarga_excel()
	{
		$hash_archivo = $this->request->query('id');
		$directorio	= Documento::getDirectorioTMP();

		if (!file_exists($directorio . '/' . $hash_archivo . '.xlsx')) {
			$this->mensajeria->agregar('El archivo solicitado no esta disponible. Deberá generarlo nuevamente.', \FMT\Mensajeria::TIPO_ERROR);
			$redirect	= !empty($tmp = $_SERVER['HTTP_REFERER'])
				? $tmp : Vista::get_url('index.php');
			$this->redirect($redirect);
		}

		$nombre_para_humanos = base64_decode($hash_archivo);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$nombre_para_humanos.'.xlsx"');
		header('Cache-Control: max-age=0');
		echo file_get_contents($directorio . '/' . $hash_archivo . '.xlsx');
		exit;
	}
}