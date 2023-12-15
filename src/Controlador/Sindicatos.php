<?php
namespace App\Controlador;
use FMT\Controlador;
use App\Modelo;
use App\Helper;
use FMT\Configuracion;
use App\Helper\Vista;
use App\Helper\Util;

class Sindicatos extends Base {
	
	protected function accion_index() {	
		$sindicato = Modelo\Sindicato::listar();
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'sindicato')))->pre_render();
	}

	public function accion_alta(){
		
    	$sindicato = Modelo\Sindicato::obtener();
      	if($this->request->post('boton_sindicato') == 'alta') {
      		$sindicato->codigo = !empty($temp = $this->request->post('codigo')) ?  $temp : null;
			$sindicato->nombre = !empty($temp = $this->request->post('nombre')) ?  $temp : null;
			if($sindicato->validar()){	
				$sindicato->alta();
				$this->mensajeria->agregar(
				"El nuevo Sindicato fue dado de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/sindicatos/index");
				$this->redirect($redirect);	
			}else {
					$err	= $sindicato->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'sindicato')))->pre_render();


	}
	
	 public function accion_modificacion(){
		$sindicato = Modelo\Sindicato::obtener($this->request->query('id'));
		if($this->request->post('boton_sindicato') == 'modificacion') {
			$sindicato->codigo = !empty($temp = $this->request->post('codigo')) ?  $temp : $sindicato->codigo;
			$sindicato->nombre = !empty($temp = $this->request->post('nombre')) ?  $temp : $sindicato->nombre;
			if($sindicato->validar()){	
				$sindicato->modificacion();
				$this->mensajeria->agregar(
				"El Sindicato fué modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/sindicatos/index");
				$this->redirect($redirect);	
			}else {
					$err	= $sindicato->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'sindicato')))->pre_render();


	}
	
	protected function accion_baja() {
	 	$sindicato = Modelo\Sindicato::obtener($this->request->query('id'));
	 	if($sindicato->id){
			if ($this->request->post('confirmar')) {
					if($sindicato->validar()){
						$sindicato->baja();
						$this->mensajeria->agregar('AVISO: el Sindicato se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase,'index');
						$redirect = Helper\Vista::get_url('index.php/sindicatos/index');
						$this->redirect($redirect);
					}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/sindicatos/index');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('sindicato', 'vista')))->pre_render();
	}


}