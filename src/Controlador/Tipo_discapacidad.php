<?php
namespace App\Controlador;
use FMT\Controlador;
use App\Modelo;
use App\Helper;
use FMT\Configuracion;
use App\Helper\Vista;
use App\Helper\Util;

class Tipo_discapacidad extends Base {
	
	protected function accion_index() {	
		$tipo_discapacidad = Modelo\TipoDiscapacidad::listar();
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'tipo_discapacidad')))->pre_render();
	}

	public function accion_alta(){
		
    	$tipo_discapacidad = Modelo\TipoDiscapacidad::obtener();

      	if($this->request->post('boton_tipo_discapacidad') == 'alta') {
			$tipo_discapacidad->nombre = !empty($temp = $this->request->post('nombre')) ?  $temp : null;
			$tipo_discapacidad->descripcion = !empty($temp = $this->request->post('descripcion')) ?  $temp : null;
			if($tipo_discapacidad->validar()){	
				$tipo_discapacidad->alta();
				$this->mensajeria->agregar(
				"El nuevo tipo de discapacidad fue dado de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/Tipo_discapacidad/index");
				$this->redirect($redirect);	
			}else {
					$err	= $tipo_discapacidad->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'tipo_discapacidad')))->pre_render();


	}
	
	 public function accion_modificacion(){
		$tipo_discapacidad = Modelo\TipoDiscapacidad::obtener($this->request->query('id'));
		if($this->request->post('boton_tipo_discapacidad') == 'modificacion') {
			$tipo_discapacidad->nombre		=	($temp = $this->request->post('nombre')) ?  $temp : null;
			$tipo_discapacidad->descripcion	=	($temp = $this->request->post('descripcion')) ?  $temp : null;
			if($tipo_discapacidad->validar()){	
				$tipo_discapacidad->modificacion();
				$this->mensajeria->agregar(
				"El tipo de discapacidad fué modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/Tipo_discapacidad/index");
				$this->redirect($redirect);	
			}else {
					$err	= $tipo_discapacidad->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'tipo_discapacidad')))->pre_render();


	}
	
	protected function accion_baja() {
	 	$tipo_discapacidad = Modelo\TipoDiscapacidad::obtener($this->request->query('id'));
	 	if($tipo_discapacidad->id){
			if ($this->request->post('confirmar')) {
					if($tipo_discapacidad->validar()){
						$tipo_discapacidad->baja();
						$this->mensajeria->agregar('AVISO: el tipo de discapacidad se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase,'index');
						$redirect = Helper\Vista::get_url('index.php/Tipo_discapacidad/index');
						$this->redirect($redirect);
					}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/Tipo_discapacidad/index');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('tipo_discapacidad', 'vista')))->pre_render();
	}


}