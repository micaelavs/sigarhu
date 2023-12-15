<?php
namespace App\Controlador;
use FMT\Controlador;
use App\Modelo;
use App\Helper;
use FMT\Configuracion;
use App\Helper\Vista;
use App\Helper\Util;

class Ubicaciones_edificios extends Base {
	
	protected function accion_index() {	
		$provincias	= json_decode(json_encode(\FMT\Ubicaciones::get_regiones('AR')), JSON_UNESCAPED_UNICODE);
		$ubicaciones_edificios = Modelo\UbicacionEdificio::listar();
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'ubicaciones_edificios', 'provincias')))->pre_render();
	}
	

	public function accion_alta(){
    	$ubicaciones_edificios = Modelo\UbicacionEdificio::obtener();
    	$provincias	= json_decode(json_encode(\FMT\Ubicaciones::get_regiones('AR')), JSON_UNESCAPED_UNICODE);
      	$localidades = [];
      	if($this->request->post('boton_ubicacion_edificio') == 'alta') {
			$ubicaciones_edificios->nombre		 = !empty($temp = $this->request->post('nombre')) ?  $temp : null;
			$ubicaciones_edificios->calle		 = !empty($temp = $this->request->post('calle')) ?  $temp : null;
			$ubicaciones_edificios->numero 		 = !empty($temp = $this->request->post('numero')) ?  $temp : null;
			$ubicaciones_edificios->id_provincia = !empty($temp = $this->request->post('id_provincia')) ?  $temp : null;
			$ubicaciones_edificios->id_localidad = !empty($temp = $this->request->post('id_localidad')) ?  $temp : null;
			$ubicaciones_edificios->cod_postal   = !empty($temp = $this->request->post('cod_postal')) ?  $temp : null;
			if($ubicaciones_edificios->validar()){	
				$ubicaciones_edificios->alta();
				$this->mensajeria->agregar(
				"La nueva Ubicación en el Edificio fue dada de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/ubicaciones_edificios/index");
				$this->redirect($redirect);	
			} else {
					$err	= $ubicaciones_edificios->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
    		$localidades = !empty($ubicaciones_edificios->id_provincia) ? json_decode(json_encode(\FMT\Ubicaciones::get_localidades($ubicaciones_edificios->id_provincia)), JSON_UNESCAPED_UNICODE) : [];
		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'ubicaciones_edificios','provincias', 'localidades')))->pre_render();


	}
	
	 public function accion_modificacion(){
	 	$ubicaciones_edificios = Modelo\UbicacionEdificio::obtener($this->request->query('id'));
    	$provincias	= json_decode(json_encode(\FMT\Ubicaciones::get_regiones('AR')), JSON_UNESCAPED_UNICODE);
		if($this->request->post('boton_ubicacion_edificio') == 'modificacion') {
			$ubicaciones_edificios->nombre 		  =  ($temp = $this->request->post('nombre')) ?  $temp : $ubicaciones_edificios->nombre;
			$ubicaciones_edificios->calle 		  =  ($temp = $this->request->post('calle')) ?  $temp : $ubicaciones_edificios->calle;
			$ubicaciones_edificios->numero 		  =  ($temp = $this->request->post('numero')) ?  $temp : $ubicaciones_edificios->numero;
			$ubicaciones_edificios->id_provincia  =  ($temp = $this->request->post('id_provincia')) ?  $temp : $ubicaciones_edificios->id_provincia;
			$ubicaciones_edificios->id_localidad  =  ($temp = $this->request->post('id_localidad')) ?  $temp : $ubicaciones_edificios->id_localidad;
			$ubicaciones_edificios->cod_postal 	  =  ($temp = $this->request->post('cod_postal')) ?  $temp : $ubicaciones_edificios->cod_postal;
			if($ubicaciones_edificios->validar()){	
				$ubicaciones_edificios->modificacion();
				$this->mensajeria->agregar(
				"La Ubicación en el Edificio fué modificada exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/ubicaciones_edificios/index");
				$this->redirect($redirect);	
			} else {
					$err	= $ubicaciones_edificios->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}
		$localidades = !empty($ubicaciones_edificios->id_provincia) ? json_decode(json_encode(\FMT\Ubicaciones::get_localidades($ubicaciones_edificios->id_provincia)), JSON_UNESCAPED_UNICODE) : [];
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'ubicaciones_edificios', 'provincias', 'localidades')))->pre_render();


	}
	
	protected function accion_baja() {
	 	$ubicaciones_edificios = Modelo\UbicacionEdificio::obtener($this->request->query('id'));
	 	if($ubicaciones_edificios->id){
			if ($this->request->post('confirmar')) {
				$ubicaciones_edificios->baja();
				$this->mensajeria->agregar('AVISO: La Ubicación en el Edificio se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase,'index');
				$redirect = Helper\Vista::get_url('index.php/ubicaciones_edificios/index');
				$this->redirect($redirect);
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/ubicaciones_edificios/index');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('ubicaciones_edificios', 'vista')))->pre_render();
	}
}