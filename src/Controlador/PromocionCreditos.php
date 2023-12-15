<?php
namespace App\Controlador;
use FMT\Controlador;
use App\Modelo;
use App\Helper;
use FMT\Configuracion;
use App\Helper\Vista;
use App\Helper\Util;

class PromocionCreditos extends Base {
	
	protected function accion_index() {	
		$creditos = Modelo\PromocionCredito::listar();
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'creditos')))->pre_render();
	}

	public function accion_alta(){		
		$creditos = Modelo\PromocionCredito::obtener();
      	if($this->request->post('boton_creditos') == 'alta') {
      		$creditos->fecha_desde = !empty($temp = $this->request->post('fecha_desde')) ?  \DateTime::createFromFormat('d/m/Y', $temp) : null;
			$creditos->fecha_hasta = null;
			$creditos->id_nivel = !empty($temp = $this->request->post('nivel')) ?  $temp : null;
			$creditos->id_tramo = !empty($temp = $this->request->post('tramo')) ?  $temp : null;
			$creditos->creditos = !empty($temp = $this->request->post('creditos')) ?  $temp : null;

			if($creditos->validar()){
				$creditos->alta();
				$this->mensajeria->agregar(
				"El nuevo registro de Créditos para Promoción fue dado de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/PromocionCreditos/index");
				$this->redirect($redirect);	
			}else {
					$err	= $creditos->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}
		$convenio = Modelo\Contrato::obtenerConvenio(Modelo\Contrato::SINEP,Modelo\Contrato::PLANTA_PERMANENTE);
		$agrupamientos = $convenio['agrupamientos'];
		$idAgrupamiento = Modelo\Nivel::obtener($creditos->id_nivel)->id_agrupamiento;
		$tramos = $convenio['tramos'];
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'creditos','agrupamientos','idAgrupamiento','tramos')))->pre_render();


	}
	
	 public function accion_modificacion(){
		$creditos = Modelo\PromocionCredito::obtener($this->request->query('id'));
		if($this->request->post('boton_creditos') == 'modificacion') {
			$creditos->fecha_desde = !empty($temp = $this->request->post('fecha_desde')) ?  \DateTime::createFromFormat('d/m/Y', $temp) : null;
			$creditos->fecha_hasta = !empty($temp = $this->request->post('fecha_hasta')) ?  \DateTime::createFromFormat('d/m/Y', $temp) : null;
			$creditos->id_nivel = !empty($temp = $this->request->post('nivel')) ?  $temp : null;
			$creditos->id_tramo = !empty($temp = $this->request->post('tramo')) ?  $temp : null;
			$creditos->creditos = !empty($temp = $this->request->post('creditos')) ?  $temp : null;
			if($creditos->validar()){
				$creditos->modificacion();
				$this->mensajeria->agregar(
				"El registro de Créditos para Promoción fué modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/PromocionCreditos/index");
				$this->redirect($redirect);	
			}else {
					$err	= $creditos->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}
		$convenio = Modelo\Contrato::obtenerConvenio(Modelo\Contrato::SINEP,Modelo\Contrato::PLANTA_PERMANENTE);
		$agrupamientos = $convenio['agrupamientos'];
		$idAgrupamiento = Modelo\Nivel::obtener($creditos->id_nivel)->id_agrupamiento;
		$tramos = $convenio['tramos'];
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'creditos','agrupamientos','idAgrupamiento','tramos')))->pre_render();
	}
}