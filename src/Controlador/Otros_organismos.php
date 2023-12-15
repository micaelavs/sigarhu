<?php
namespace App\Controlador;
use FMT\Controlador;
use App\Modelo;
use App\Helper;
use FMT\Configuracion;
use App\Helper\Vista;
use App\Helper\Util;

class Otros_organismos extends Base {
	
	protected function accion_index() {	
		$otros_organismos = Modelo\OtroOrganismo::listar();
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'otros_organismos')))->pre_render();
	}

	public function accion_alta(){
		$hidden = null;

		if ($this->request->post("cuit")) {
			$hidden = [
				$this->request->post("cuit"),
				$this->request->post("urlr"),
				$this->request->post("id_bloque"),
				$this->request->post("select_tab")
			];
		}
    	$otros_organismos = Modelo\OtroOrganismo::obtener($this->request->query('id'));
    	$tipos =  Modelo\OtroOrganismo::getParam('TIPO_ORGANISMO');
    	$jurisdicciones =  Modelo\OtroOrganismo::getParam('JURISDICCION');
		if($this->request->post('otros_organismos') == 'alta') {
		    $otros_organismos->nombre= !empty($temp=$this->request->post('nombre')) ?  $temp : null;
			$otros_organismos->tipo = !empty($temp=$this->request->post('tipo')) ?  $temp : null;
			$otros_organismos->jurisdiccion = !empty($temp=$this->request->post('jurisdiccion')) ?  $temp : null;
			if($otros_organismos->validar()) {
				if($otros_organismos->alta()) {
					$this->mensajeria->agregar(
					"AVISO: El registro fue ingresado al sistema exitosamente.",\FMT\Mensajeria::TIPO_AVISO);
					$redirect =Vista::get_url("index.php/otros_organismos/index");
					if ($this->request->post('return')) {
						$tmp = json_decode(urldecode($this->request->post('return')));
						$redirect = $tmp[1].'/'.$tmp[0];
						$this->setGetVarSession('data_legajo', ['select_tab' => $tmp[3]]);
					}
					$this->redirect($redirect);	
				} else {
					$err	= $otros_organismos->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR);
					}
				}
			} else {
				$err	= $otros_organismos->errores;
				foreach ($err as $text) {
					$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR);
				 }
			}

		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'otros_organismos', 'tipos','hidden', 'jurisdicciones')))->pre_render();
	}
	
	
	public function accion_modificacion(){
		$otros_organismos = Modelo\OtroOrganismo::obtener($this->request->query('id'));
        $tipos =  Modelo\OtroOrganismo::getParam('TIPO_ORGANISMO');
        $jurisdicciones =  Modelo\OtroOrganismo::getParam('JURISDICCION');
		if($this->request->post('otros_organismos') == 'modificacion') {
			$otros_organismos->nombre= !empty($temp=$this->request->post('nombre')) ?  $temp : null;
			$otros_organismos->tipo= !empty($temp=$this->request->post('tipo')) ?  $temp : null;
			$otros_organismos->jurisdiccion = !empty($temp=$this->request->post('jurisdiccion')) ?  $temp : null;
			if ($otros_organismos->tipo == 2) {
				$otros_organismos->jurisdiccion = null;
			}
			if($otros_organismos->validar()){
				if($otros_organismos->modificacion()) {
					$this->mensajeria->agregar(
					"AVISO:El registro fué modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/otros_organismos/index");
					$this->redirect($redirect);
				} else {
					$err	= $otros_organismos->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}					
				}
			} else {
				$err	= $otros_organismos->errores;
				foreach ($err as $text) {
					$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
			    }
		    }
		}

		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'otros_organismos' , 'tipos', 'jurisdicciones')))->pre_render();
	}
		
	protected function accion_baja() {
	 	$otros_organismos = Modelo\OtroOrganismo::obtener($this->request->query('id'));
	 	if($otros_organismos->id){
			if ($this->request->post('confirmar')) {
				if(!empty($otros_organismos->validar())) {
					if($otros_organismos->baja()) {
						$this->mensajeria->agregar('AVISO:El Registro se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase,'index');
						$redirect = Helper\Vista::get_url('index.php/otros_organismos/index');
						$this->redirect($redirect);
					} else {
						$err	= $otros_organismos->errores;
						foreach ((array)$err as $text) {
							$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
						}
					}
				} else {
					$err	= $otros_organismos->errores;
					foreach ((array)$err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/otros_organismos/index');
			$this->redirect($redirect);
		}
		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('otros_organismos', 'vista')))->pre_render();
	}

}