<?php
namespace App\Controlador;
use FMT\Controlador;
use App\Modelo;
use App\Helper;
use FMT\Configuracion;
use App\Helper\Vista;
use App\Helper\Util;

class Seguros_vida extends Base {
	
	protected function accion_index() {	
		$seguro_vida = Modelo\Seguro_vida::listar();
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'seguro_vida')))->pre_render();
	}

	public function accion_alta(){
		
    	$seguro_vida = Modelo\Seguro_vida::obtener();
      	if($this->request->post('boton_seguro_vida') == 'alta') {
			$seguro_vida->nombre = !empty($temp = $this->request->post('nombre')) ?  $temp : null;
			if($seguro_vida->validar()){	
				$seguro_vida->alta();
				$this->mensajeria->agregar(
				"El Seguro de vida fué dado de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/seguros_vida/index");
				$this->redirect($redirect);	
			}else {
					$err	= $seguro_vida->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'seguro_vida')))->pre_render();


	}
	
	 public function accion_modificacion(){
		$seguro_vida = Modelo\Seguro_vida::obtener($this->request->query('id'));
		if($this->request->post('boton_seguro_vida') == 'modificacion') {
			$seguro_vida->nombre = !empty($temp = $this->request->post('nombre')) ?  $temp : null;
			if($seguro_vida->validar()){	
				$seguro_vida->modificacion();
				$this->mensajeria->agregar(
				"El Seguro de vida fué modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/seguros_vida/index");
				$this->redirect($redirect);	
			}else {
					$err	= $seguro_vida->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'seguro_vida')))->pre_render();


	}
	
	protected function accion_baja() {
	 	$seguro_vida = Modelo\Seguro_vida::obtener($this->request->query('id'));
	 	if($seguro_vida->id){
			if ($this->request->post('confirmar')) {
					if($seguro_vida->validar()){
						$seguro_vida->baja();
						$this->mensajeria->agregar('AVISO: El Seguro de vida se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase,'index');
						$redirect = Helper\Vista::get_url('index.php/seguros_vida/index');
						$this->redirect($redirect);
					}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/seguros_vida/index');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('seguro_vida', 'vista')))->pre_render();
	}


}