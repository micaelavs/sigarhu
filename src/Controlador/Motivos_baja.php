<?php
namespace App\Controlador;
use FMT\Controlador;
use App\Modelo;
use App\Helper;
use FMT\Configuracion;
use App\Helper\Vista;
use App\Helper\Util;

class Motivos_baja extends Base {
	
	protected function accion_index() {	
		$motivo_baja = Modelo\MotivoBaja::listar();
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'motivo_baja')))->pre_render();
	}

	public function accion_alta(){
		
    	$motivo_baja = Modelo\MotivoBaja::obtener();

      	if($this->request->post('boton_motivo_baja') == 'alta') {
			$motivo_baja->nombre = !empty($temp = $this->request->post('nombre')) ?  $temp : null;
			if($motivo_baja->validar()){	
				$motivo_baja->alta();
				$this->mensajeria->agregar(
				"El nuevo Motivo de Baja fue dado de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/motivos_baja/index");
				$this->redirect($redirect);	
			}else {
					$err	= $motivo_baja->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'motivo_baja')))->pre_render();


	}
	
	 public function accion_modificacion(){
		$motivo_baja = Modelo\MotivoBaja::obtener($this->request->query('id'));
		
		if($this->request->post('boton_motivo_baja') == 'modificacion') {
			$motivo_baja->nombre 	=  ($temp = $this->request->post('nombre')) ?  $temp : null;
			if($motivo_baja->validar()){	
				$motivo_baja->modificacion();
				$this->mensajeria->agregar(
				"El Motivo de baja fué modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/motivos_baja/index");
				$this->redirect($redirect);	
			}else {
					$err	= $motivo_baja->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'motivo_baja')))->pre_render();


	}
	
	protected function accion_baja() {
	 	$motivo_baja = Modelo\MotivoBaja::obtener($this->request->query('id'));
	 	if($motivo_baja->id){
			if ($this->request->post('confirmar')) {
					if($motivo_baja->validar()){
						$motivo_baja->baja();
						$this->mensajeria->agregar('AVISO: El Motivo de baja se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase,'index');
						$redirect = Helper\Vista::get_url('index.php/motivos_baja/index');
						$this->redirect($redirect);
					}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/motivos_baja/index');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('motivo_baja', 'vista')))->pre_render();
	}


}