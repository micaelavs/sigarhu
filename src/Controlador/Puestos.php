<?php
namespace App\Controlador;
use FMT\Controlador;
use App\Modelo;
use App\Helper;
use FMT\Configuracion;
use App\Helper\Vista;
use App\Helper\Util;

class Puestos extends Base {
	
	protected function accion_index() {	
//		$puesto = Modelo\Puesto::listar_puesto();
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista')))->pre_render();
	}

	protected function accion_index_subfamilia() {
		$subfamilia = Modelo\Puesto::listar_subfamilia();
		$lista_familia = Modelo\Puesto::listar_familia_puesto();
		$vista = $this->vista;
		(new Vista(VISTAS_PATH.'/subfamilias/index.php',compact('vista', 'subfamilia', 'lista_familia')))->pre_render();
	}

	protected function accion_index_familia_puesto() {
		$familia_puesto = Modelo\Puesto::listar_familia_puesto();
		$vista = $this->vista;
		(new Vista(VISTAS_PATH.'/familia_puestos/index.php',compact('vista', 'familia_puesto')))->pre_render();
	}

	protected function accion_ajax_puesto(){
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

		$data = Modelo\Puesto::listado_puestos_ajax($params);

		$datos['draw']	= (int) $this->request->query('draw');


		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
		
	}

	public function accion_alta(){
    	$puesto = Modelo\Puesto::obtener();
		$lista_subfamilia = Modelo\Puesto::listar_subfamilia();
      	if($this->request->post('boton_puesto') == 'alta') {
      		$puesto->id_subfamilia = !empty($temp = $this->request->post('subfamilia')) ?  $temp : null;
			$puesto->nombre = !empty($temp = $this->request->post('nombre')) ?  $temp : null;
			if($puesto->validar()){	
				$puesto->alta();
				$this->mensajeria->agregar(
				"El nuevo Puesto fué dado de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/puestos/index");
				$this->redirect($redirect);	
			}else {
					$err	= $puesto->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'puesto', 'lista_subfamilia')))->pre_render();

	}
	
	public function accion_alta_subfamilia(){
    	$subfamilia = Modelo\Puesto::obtener_subfamilia();
    	$lista_familia = Modelo\Puesto::listar_familia_puesto();
      	if($this->request->post('boton_subfamilia') == 'alta') {
      		$subfamilia->id_familia = !empty($temp = $this->request->post('familia_puesto')) ?  $temp : null;
			$subfamilia->subfamilia = !empty($temp = $this->request->post('nombre')) ?  $temp : null;
			if($subfamilia->validar()){	
				$subfamilia->alta_subfamilia();
				$this->mensajeria->agregar(
				"La Subfamilia fué dada de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/puestos/index_subfamilia");
				$this->redirect($redirect);	
			}else {
					$err	= $subfamilia->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}
		 $vista = $this->vista;
		(new Vista(VISTAS_PATH.'/subfamilias/alta.php',compact('vista', 'subfamilia', 'lista_familia')))->pre_render();


	}
	
	public function accion_alta_familia_puesto(){
    	$familia_puesto = Modelo\Puesto::obtener_familia_puesto();
      	if($this->request->post('boton_familia_puesto') == 'alta') {
			$familia_puesto->familia = !empty($temp = $this->request->post('nombre')) ?  $temp : null;
			if($familia_puesto->validar()){	
				$familia_puesto->alta_familia_puesto();
				$this->mensajeria->agregar(
				"La Familia de Puesto fué dada de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/puestos/index_familia_puesto");
				$this->redirect($redirect);	
			}else {
				$err	= $familia_puesto->errores;
				foreach ($err as $text) {
					$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
				}
			}
		}
		 $vista = $this->vista;
		(new Vista(VISTAS_PATH.'/familia_puestos/alta.php',compact('vista', 'familia_puesto')))->pre_render();


	}
	
	 public function accion_modificacion(){
		$puesto = Modelo\Puesto::obtener($this->request->query('id'));
		$lista_subfamilia = Modelo\Puesto::listar_subfamilia();
		if($this->request->post('boton_puesto') == 'modificacion') {
			$puesto->id_subfamilia = ($temp = $this->request->post('subfamilia')) ?  $temp : $puesto->id_subfamilia;
			$puesto->nombre = ($temp = $this->request->post('nombre')) ?  $temp : $puesto->nombre;
			if($puesto->validar()){	
				$puesto->modificacion();
				$this->mensajeria->agregar(
				"El Puesto fué modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/puestos/index");
				$this->redirect($redirect);	
			}else {
				$err	= $puesto->errores;
				foreach ($err as $text) {
					$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
				}
			}
		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'puesto', 'lista_subfamilia')))->pre_render();

	}
	
	public function accion_modificacion_subfamilia(){
		$subfamilia = Modelo\Puesto::obtener_subfamilia($this->request->query('id'));
		$lista_familia = Modelo\Puesto::listar_familia_puesto();
		if($this->request->post('boton_subfamilia') == 'modificacion') {
			$subfamilia->id_familia = ($temp = $this->request->post('familia_puesto')) ?  $temp : $subfamilia->id_familia;
			$subfamilia->subfamilia = ($temp = $this->request->post('nombre')) ?  $temp : $subfamilia->nombre;
			if($subfamilia->validar()){	
				$subfamilia->modificacion_subfamilia();
				$this->mensajeria->agregar(
				"La Subfamilia fué modificada exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/puestos/index_subfamilia");
				$this->redirect($redirect);	
			}else {
				$err	= $subfamilia->errores;
				foreach ($err as $text) {
					$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
				}
			}
		}
		 $vista = $this->vista;
		(new Vista(VISTAS_PATH.'/subfamilias/modificacion.php',compact('vista', 'subfamilia', 'lista_familia')))->pre_render();

	}
	
	public function accion_modificacion_familia_puesto(){
		$familia_puesto = Modelo\Puesto::obtener_familia_puesto($this->request->query('id'));
		if($this->request->post('boton_familia_puesto') == 'modificacion') {
			$familia_puesto->familia = !empty($temp = $this->request->post('nombre')) ?  $temp : $familia_puesto->familia;
			if($familia_puesto->validar()){	
				$familia_puesto->modificacion_familia_puesto();
				$this->mensajeria->agregar(
				"La Familia de Puesto fué modificada exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/Puestos/index_familia_puesto");
				$this->redirect($redirect);	
			}else {
				$err	= $familia_puesto->errores;
				foreach ($err as $text) {
					$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
				}
			}
		}
		 $vista = $this->vista;
		(new Vista(VISTAS_PATH.'/familia_puestos/modificacion.php',compact('vista', 'familia_puesto')))->pre_render();

	}
	
	protected function accion_baja() {
	 	$puesto = Modelo\Puesto::obtener($this->request->query('id'));
	 	if($puesto->id){
			if ($this->request->post('confirmar')) {
				if($puesto->validar()){
					$puesto->baja();
					$this->mensajeria->agregar('AVISO: El Puesto se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase,'index');
					$redirect = Helper\Vista::get_url('index.php/puestos/index');
					$this->redirect($redirect);
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/puestos/index');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('puesto', 'vista')))->pre_render();
	}
	
	protected function accion_baja_subfamilia() {
	 	$subfamilia = Modelo\Puesto::obtener_subfamilia($this->request->query('id'));
	 	if($subfamilia->id_subfamilia){
			if ($this->request->post('confirmar')) {
				if($subfamilia->validar()){
					$subfamilia->baja_subfamilia();
					$this->mensajeria->agregar('AVISO: La Subfamilia se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase,'index_subfamilia');
					$redirect = Helper\Vista::get_url('index.php/puestos/index_subfamilia');
					$this->redirect($redirect);
				}else {
					$err	= $subfamilia->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/puestos/index_subfamilia');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista(VISTAS_PATH.'/subfamilias/baja.php',compact('subfamilia', 'vista')))->pre_render();
	}
	
	protected function accion_baja_familia_puesto() {
	 	$familia_puesto = Modelo\Puesto::obtener_familia_puesto($this->request->query('id')); 
	 	if($familia_puesto->id_familia){
			if ($this->request->post('confirmar')) {
				if($familia_puesto->validar()){
					$familia_puesto->baja_familia_puesto();
					$this->mensajeria->agregar('AVISO: La Familia de Puesto se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase,'index_familia_puesto');
					$redirect = Helper\Vista::get_url('index.php/Puestos/index_familia_puesto');
					$this->redirect($redirect);
				}else {
					$err	= $familia_puesto->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/Puestos/index_familia_puesto');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista(VISTAS_PATH.'/familia_puestos/baja.php',compact('familia_puesto', 'vista')))->pre_render();
	}
	
	protected function accion_ajax_get_puesto(){
		$data	= [
			'subfamilias'	=>	\App\Modelo\Puesto::getPuesto($this->request->query('familia_puestos'))
		];
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}
}