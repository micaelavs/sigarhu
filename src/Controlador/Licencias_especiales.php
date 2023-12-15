<?php
namespace App\Controlador;
use FMT\Controlador;
use App\Modelo;
use App\Helper;
use FMT\Configuracion;
use App\Helper\Vista;
use App\Helper\Util;

class Licencias_especiales extends Base {
	
	protected function accion_index() {	
		$licencias_especiales = Modelo\LicenciaEspecial::listar();
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'licencias_especiales')))->pre_render();
	}

	public function accion_alta(){
		
    	$licencias_especiales = Modelo\LicenciaEspecial::obtener();

      	if($this->request->post('boton_licencia_especial') == 'alta') {
      		$licencias_especiales->nombre = !empty($temp = $this->request->post('nombre')) ?  $temp : null;
			if($licencias_especiales->validar()){	
				$licencias_especiales->alta();
				$this->mensajeria->agregar(
				"La nueva Licencia Especial fue dada de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/licencias_especiales/index");
				$this->redirect($redirect);	
			}else {
					$err	= $licencias_especiales->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'licencias_especiales')))->pre_render();


	}
	
	 public function accion_modificacion(){
		$licencias_especiales = Modelo\LicenciaEspecial::obtener($this->request->query('id'));

		if($this->request->post('boton_licencia_especial') == 'modificacion') {
			$licencias_especiales->nombre 	=  ($temp = $this->request->post('nombre')) ?  $temp : null;
			if($licencias_especiales->validar()){	
				$licencias_especiales->modificacion();
				$this->mensajeria->agregar(
				"La licencia Especial fué modificada exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/licencias_especiales/index");
				$this->redirect($redirect);	
			}else {
					$err	= $licencias_especiales->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'licencias_especiales')))->pre_render();


	}
	
	protected function accion_baja() {
	 	$licencias_especiales = Modelo\LicenciaEspecial::obtener($this->request->query('id'));
	 	if($licencias_especiales->id){
			if ($this->request->post('confirmar')) {
					if($licencias_especiales->validar()){
						$licencias_especiales->baja();
						$this->mensajeria->agregar('AVISO: La Licencia Especial se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase,'index');
						$redirect = Helper\Vista::get_url('index.php/licencias_especiales/index');
						$this->redirect($redirect);
					}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/licencias_especiales/index');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('licencias_especiales', 'vista')))->pre_render();
	}


}