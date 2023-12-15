<?php
namespace App\Controlador;
use FMT\Controlador;
use App\Modelo;
use App\Helper;
use FMT\Configuracion;
use App\Helper\Vista;
use App\Helper\Util;

class Obras_sociales extends Base {
	
	protected function accion_index() {	
		$obra_social = Modelo\Obra_social::listar();
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'obra_social')))->pre_render();
	}

	public function accion_alta(){
		
    	$obra_social = Modelo\Obra_social::obtener();
      	if($this->request->post('boton_obra_social') == 'alta') {
      		$obra_social->codigo = !empty($temp = $this->request->post('codigo')) ?  $temp : null;
			$obra_social->nombre = !empty($temp = $this->request->post('nombre')) ?  $temp : null;
			if($obra_social->validar()){	
				$obra_social->alta();
				$this->mensajeria->agregar(
				"La nueva Obra Social fue dada de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/obras_sociales/index");
				$this->redirect($redirect);	
			}else {
					$err	= $obra_social->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'obra_social')))->pre_render();


	}
	
	 public function accion_modificacion(){
		$obra_social = Modelo\Obra_social::obtener($this->request->query('id'));
		if($this->request->post('boton_obra_social') == 'modificacion') {
			$obra_social->codigo = !empty($temp = $this->request->post('codigo')) ?  $temp : $obra_social->codigo;
			$obra_social->nombre = !empty($temp = $this->request->post('nombre')) ?  $temp : $obra_social->nombre;
			if($obra_social->validar()){	
				$obra_social->modificacion();
				$this->mensajeria->agregar(
				"La Obra social fué modificada exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/obras_sociales/index");
				$this->redirect($redirect);	
			}else {
					$err	= $obra_social->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'obra_social')))->pre_render();


	}
	
	protected function accion_baja() {
	 	$obra_social = Modelo\Obra_social::obtener($this->request->query('id'));
	 	if($obra_social->id){
			if ($this->request->post('confirmar')) {
					if($obra_social->validar()){
						$obra_social->baja();
						$this->mensajeria->agregar('AVISO: La Obra social se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase,'index');
						$redirect = Helper\Vista::get_url('index.php/obras_sociales/index');
						$this->redirect($redirect);
					}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/obras_sociales/index');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('obra_social', 'vista')))->pre_render();
	}


}