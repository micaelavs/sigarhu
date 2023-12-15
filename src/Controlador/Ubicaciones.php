<?php
namespace App\Controlador;
use FMT\Controlador;
use App\Modelo;
use App\Helper;
use FMT\Configuracion;
use App\Helper\Vista;
use App\Helper\Util;

class Ubicaciones extends Base {
	
	protected function accion_index() {	
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista')))->pre_render();
	}
	

	public function accion_alta(){
    	$ubicaciones = Modelo\Ubicacion::obtener();
    	$edificios =  Modelo\UbicacionEdificio::getEdificios();
      	if($this->request->post('boton_ubicacion') == 'alta') {
			$ubicaciones->id_edificio		 = !empty($temp = $this->request->post('id_edificio')) ?  $temp : null;
			$ubicaciones->id_organismo		 = 1;
			$ubicaciones->piso	 			 = !empty($temp = $this->request->post('piso')) ?  $temp : null;
			$ubicaciones->oficina	 		 = !empty($temp = $this->request->post('oficina')) ?  $temp : null;
			if($ubicaciones->validar()){	
				$ubicaciones->alta();
				$this->mensajeria->agregar(
				"La nueva Ubicación fue dada de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/ubicaciones/index");
				$this->redirect($redirect);	
			}else {
					$err	= $ubicaciones->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'ubicaciones', 'edificios')))->pre_render();


	}
	
	 public function accion_modificacion(){
	 	$ubicaciones = Modelo\Ubicacion::obtener($this->request->query('id'));
	 	$edificios =  Modelo\Ubicacion::getEdificios();
		if($this->request->post('boton_ubicacion') == 'modificacion') {
			$ubicaciones->id_edificio 	=  ($temp = $this->request->post('id_edificio')) ?  $temp : $ubicaciones->id_edificio;
			$ubicaciones->id_organismo 	=  1;
			$ubicaciones->piso 		  	=  ($temp = $this->request->post('piso')) ?  $temp : $ubicaciones->piso;
			$ubicaciones->oficina  		=  ($temp = $this->request->post('oficina')) ?  $temp : $ubicaciones->oficina;
			if($ubicaciones->validar()){	
				$ubicaciones->modificacion();
				$this->mensajeria->agregar(
				"La Ubicación fué modificada exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/ubicaciones/index");
				$this->redirect($redirect);	
			}else {
					$err	= $ubicaciones->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}

		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'ubicaciones','edificios')))->pre_render();


	}
	
	protected function accion_baja() {
	 	$ubicaciones = Modelo\Ubicacion::obtener($this->request->query('id'));
	 	if($ubicaciones->id){
			if ($this->request->post('confirmar')) {
					if($ubicaciones->validar()){
						$ubicaciones->baja();
						$this->mensajeria->agregar('AVISO: La Ubicación se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase,'index');
						$redirect = Helper\Vista::get_url('index.php/ubicaciones/index');
						$this->redirect($redirect);
					}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/ubicaciones/index');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('ubicaciones', 'vista')))->pre_render();
	}



	protected function accion_ajax_lista_ubicaciones() {

		$dataTable_columns	= $this->request->query('columns');
		$orders	= [];
		foreach($orden = (array)$this->request->query('order') as $i => $val){
			$orders[]	= [
				'campo'	=> (!empty($tmp = $orden[$i]) && !empty($dataTable_columns) && is_array($dataTable_columns[0]))
						? $dataTable_columns[ (int)$tmp['column'] ]['data']	:	'id',
				'dir'	=> !empty($tmp = $orden[$i]['dir'])
						? $tmp	:	'desc',
			];
		}
		$params	= [
			'order'		=> $orders,
			'start'		=> !empty($tmp =$this->request->query('start'))
						? $tmp : 0,
			'lenght'	=> !empty($tmp = $this->request->query('length'))
						? $tmp : 10,
			'search'	=> !empty($tmp = $this->request->query('search'))
						? $tmp['value'] : '',
		];

		$data =  Modelo\Ubicacion::listadoUbicaciones($params);

		$datos['draw']	= (int) $this->request->query('draw');

		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}


}