<?php
namespace App\Controlador;
use FMT\Controlador;
use App\Modelo;
use App\Helper;
use FMT\Configuracion;
use App\Helper\Vista;
use App\Helper\Util;

class Horarios extends Base {
	
	public $dias = [['id' => '00', 'dia' => 'Domingo'],['id' => '1', 'dia' => 'Lunes'], ['id' => '2', 'dia' => 'Martes'], ['id' => '3', 'dia' => 'Miércoles'], ['id' => '4', 'dia' => 'Jueves'], ['id' => '5', 'dia' => 'Viernes'],['id' => '6', 'dia' => 'Sábado']];
	
	protected function accion_index() {	
		$horarios = Modelo\Horario::listar();
		$dias = $this->dias;
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'horarios', 'dias')))->pre_render();
	}
	

	public function accion_alta(){
    	$horarios = Modelo\Horario::obtener();
    	$dias = $this->dias;
      	if($this->request->post('boton_horario') == 'alta') {
			$horarios->nombre		 = !empty($temp = $this->request->post('nombre')) ?  $temp : null;
			$horarios->dia_desde	 = !empty($temp = $this->request->post('dia_desde')) ?  $temp : null;
			$horarios->dia_hasta	 = !empty($temp = $this->request->post('dia_hasta')) ?  $temp : null;
			$horarios->hora_desde	 = !empty($temp = $this->request->post('hora_desde')) ?  $temp : null;
			$horarios->hora_hasta	 = !empty($temp = $this->request->post('hora_hasta')) ?  $temp : null;
			if ($horarios->validar()) {	
				$horarios->alta();
				$this->mensajeria->agregar(
				"La nueva Plantilla Horaria fue dada de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/horarios/index");
				$this->redirect($redirect);	
			} else {
					$err	= $horarios->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'horarios','dias')))->pre_render();


	}
	
	public function accion_modificacion(){
	 	$horarios = Modelo\Horario::obtener($this->request->query('id'));
	 	$dias = $this->dias;
		if($this->request->post('boton_horario') == 'modificacion') {
			$horarios->nombre 		=  ($temp = $this->request->post('nombre')) ?  $temp : $horarios->nombre;
			$horarios->dia_desde 	=  ($temp = $this->request->post('dia_desde')) ?  $temp : $horarios->dia_desde;
			$horarios->dia_hasta 	=  ($temp = $this->request->post('dia_hasta')) ?  $temp : $horarios->dia_hasta;
			$horarios->hora_desde  	=  ($temp = $this->request->post('hora_desde')) ?  $temp : $horarios->hora_desde;
			$horarios->hora_hasta  	=  ($temp = $this->request->post('hora_hasta')) ?  $temp : $horarios->hora_hasta;
			if($horarios->validar()){	
				$horarios->modificacion();
				$this->mensajeria->agregar(
				"La Plantilla Horaria fué modificada exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/horarios/index");
				$this->redirect($redirect);	
			}else {
					$err	= $horarios->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'horarios', 'dias')))->pre_render();
	}
	
	protected function accion_baja() {
	 	$horarios = Modelo\Horario::obtener($this->request->query('id'));
	 	if($horarios->id){
			if ($this->request->post('confirmar')) {
					if ($horarios->validar()) {
						$horarios->baja();
						$this->mensajeria->agregar('AVISO: La Plantilla Horaria se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase,'index');
						$redirect = Helper\Vista::get_url('index.php/horarios/index');
						$this->redirect($redirect);
					} else {
						$err	= $horarios->errores;
						foreach ($err as $text) {
							$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
						}
					}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/horarios/index');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('horarios', 'vista')))->pre_render();
	}
}