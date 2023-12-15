<?php
namespace App\Controlador;
use FMT\Controlador;
use App\Modelo;
use App\Helper;
use FMT\Configuracion;
use App\Helper\Vista;
use App\Helper\Util;

class Nivel_educativo extends Base {
	
	protected function accion_index() {	
		$nivel_educativo = Modelo\NivelEducativo::listar();
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'nivel_educativo')))->pre_render();
	}

	public function accion_alta(){
		
    	$nivel_educativo = Modelo\NivelEducativo::obtener();

      	if($this->request->post('boton_nivel_educativo') == 'alta') {
      		$nivel_educativo->nombre = !empty($temp = $this->request->post('nombre')) ? $temp : null;
			if($nivel_educativo->validar()){	
				$nivel_educativo->alta();
				$this->mensajeria->agregar(
				"El nuevo Nivel Educativo fue dado de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/nivel_educativo/index");
				$this->redirect($redirect);	
			}else {
					$err	= $nivel_educativo->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'nivel_educativo')))->pre_render();


	}
	
	 public function accion_modificacion(){
		$nivel_educativo = Modelo\NivelEducativo::obtener($this->request->query('id'));
		if($this->request->post('boton_nivel_educativo') == 'modificacion') {
			$nivel_educativo->nombre = ($temp = $this->request->post('nombre')) ? $temp : null;
			if($nivel_educativo->validar()){	
				$nivel_educativo->modificacion();
				$this->mensajeria->agregar(
				"El Nivel educativo fué modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/nivel_educativo/index");
				$this->redirect($redirect);	
			}else {
					$err	= $nivel_educativo->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'nivel_educativo')))->pre_render();


	}
	
	protected function accion_baja() {
	 	$nivel_educativo = Modelo\NivelEducativo::obtener($this->request->query('id'));
	 	if($nivel_educativo->id){
			if ($this->request->post('confirmar')) {
					if($nivel_educativo->validar()){
						$nivel_educativo->baja();
						$this->mensajeria->agregar('AVISO: El Nivel educativo se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase,'index');
						$redirect = Helper\Vista::get_url('index.php/nivel_educativo/index');
						$this->redirect($redirect);
					}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/nivel_educativo/index');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('nivel_educativo', 'vista')))->pre_render();
	}


}

