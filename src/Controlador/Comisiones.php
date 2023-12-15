<?php
namespace App\Controlador;
use App\Helper\Controlador;
use App\Modelo;
use App\Helper;
use App\Helper\Vista;

class Comisiones extends Base {
	
	protected function accion_index() {	
		$comisiones = Modelo\Comision::listar();
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'comisiones')))->pre_render();
	}

	public function accion_alta(){

    	$comisiones = Modelo\Comision::obtener($this->request->query('id'));

        

      	if($this->request->post('comisiones') == 'alta') {
      	    		$comisiones->nombre= !empty($temp=$this->request->post('nombre')) ?  $temp : null;

			if($comisiones->validar()){
				$comisiones->alta();
				$this->mensajeria->agregar(
				"AVISO: El registro fue ingresado al sistema exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/comisiones/index");
				$this->redirect($redirect);	
			}else {
					$err	= $comisiones->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
				  }
			}

		}


		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'comisiones' )))->pre_render();
	}
	 public function accion_modificacion(){
         $comisiones = Modelo\Comision::obtener($this->request->query('id'));

         

		if($this->request->post('comisiones') == 'modificacion') {
					$comisiones->nombre= !empty($temp=$this->request->post('nombre')) ?  $temp : null;

			if($comisiones->validar()){
				$comisiones->modificacion();
				$this->mensajeria->agregar(
				"AVISO:El registro fué modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/comisiones/index");
				$this->redirect($redirect);	
			}else {
					$err	= $comisiones->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
				    }
			      }
		}

		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'comisiones' )))->pre_render();
	}
		
	protected function accion_baja() {
	 	$comisiones = Modelo\Comision::obtener($this->request->query('id'));
	 	if($comisiones->id){
			if ($this->request->post('confirmar')) {
					if($comisiones->validar()){
						$comisiones->baja();
						$this->mensajeria->agregar('AVISO:El Registro se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase,'index');
						$redirect = Helper\Vista::get_url('index.php/comisiones/index');
						$this->redirect($redirect);
					}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/comisiones/index');
			$this->redirect($redirect);
		}
		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('comisiones', 'vista')))->pre_render();
	}
//_metodo_vista_tabla_base_
}


