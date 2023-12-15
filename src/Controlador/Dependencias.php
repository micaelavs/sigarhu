<?php
namespace App\Controlador;
use FMT\Controlador;
use App\Modelo;
use App\Helper;
use FMT\Configuracion;
use App\Helper\Vista;
use App\Helper\Util;

class Dependencias extends Base {

	protected function accion_index() {	 
		 
		(new Helper\Vista($this->vista_default,['vista' => $this->vista ]))
			->pre_render();
	}
	protected function accion_index_informales() {	 
		$lista_dependencias = Modelo\Dependencia::lista_dependencias();
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'lista_dependencias')))->pre_render();
	}

	protected function accion_alta(){
		if($this->request->is_ajax()){
			$data = $this->_get_dependencias($this->request->post('nivel'));
			$this->json->setData($data);
			$this->json->render();
			exit;
		}
	
		$dependencia = Modelo\Dependencia::obtener($this->request->query('id'));
		$nivel_organigrama 			= \App\Modelo\Dependencia::getParam('NIVEL_ORGANIGRAMA');
		$dependencia->nombre		= !empty($temp = $this->request->post('nombre')) ?  $temp : null;
		$dependencia->id_padre		= !empty($temp = $this->request->post('id_padre')) ?  $temp : null;
		$dependencia->fecha_desde 	= !empty($temp = $this->request->post('fecha_desde')) ?  \DateTime::createFromFormat('d/m/Y', $temp) : null;
		$dependencia->nivel			= !empty($temp = $this->request->post('nivel')) ?  $temp : null;
		$dependencia->fecha_hasta   = null;
	
		if($this->request->post('boton_dependencia') == 'alta') {
			if($dependencia->validar()){
				if($dependencia->id_padre == Modelo\Dependencia::FAKE_PADRE_ID){
					$dependencia->id_padre=0;
				}
				$dependencia->alta();
				$this->mensajeria->agregar(
				"La nueva Dependencia fue dada de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/dependencias/index");
				$this->redirect($redirect);	
			}else {
					$err	= $dependencia->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'nivel_organigrama','dependencia')))->pre_render();
	}


	private function _get_dependencias($nivel = null) {
		$data = [];
		$dependencias = [];
		if (!is_null($nivel)) {
			if($nivel==1){ //si es unidad ministro, no va a tener dependencias con nivel superior, cargo uno a mano
				$dependencias[Modelo\Dependencia::FAKE_PADRE_ID] =  ['id'=>Modelo\Dependencia::FAKE_PADRE_ID, 'nombre' =>'Sin padre', 'nivel'=>Modelo\Dependencia::FAKE_NIVEL, 'borrado' =>0];
				$data = $dependencias;
			}else{
				$dependencias	=  Modelo\Dependencia::obtener_dependencias_nivel_superior($nivel);
				$data = $dependencias;
			}
			 
		}		
		return $data;
	}

	protected function accion_alta_informales(){
		$dependencia= Modelo\Dependencia::obtener($this->request->query('id'));
		$lista_dependencias		    = Modelo\Dependencia::lista_dependencias();

		$dep_informal	= Modelo\DependenciaInformal::obtener();
		if($this->request->post('boton_dependencia_informal') == 'alta') {
			$dep_informal->nombre			= !empty($temp = $this->request->post('nombre')) ?  $temp : null;
			$dep_informal->id_dependencia	= !empty($temp = $this->request->post('id_dependencia')) ?  $temp : $dependencia->id;
			$dep_informal->fecha_desde	    = !empty($temp = $this->request->post('fecha_desde')) ?  \DateTime::createFromFormat('d/m/Y', $temp) : null;
			$dep_informal->fecha_hasta		= null;
			if($dep_informal->validar()){	
				$dep_informal->alta();
				$this->mensajeria->agregar(
					"La nueva Dependencia Informal fue dada de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/dependencias/index_informales");
				$this->redirect($redirect);	
			}else {
				$err	= $dep_informal->errores;
				foreach ((array)$err as $text) {
					$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
				}
			}
		}
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista','lista_dependencias','dependencia', 'dep_informal')))->pre_render();
	}

	protected function accion_modificacion_informales(){
		$id_informal	= $this->request->query('id');
		$informal		= Modelo\DependenciaInformal::obtener($id_informal);
		$lista_dependencias = Modelo\Dependencia::lista_dependencias();
		
		if($this->request->post('boton_dependencia_informal') == 'modificacion') {
			$informal->nombre			= $this->request->post('nombre');
			$informal->id_dependencia	= $this->request->post('id_dependencia');
			$informal->fecha_desde		= ($temp = $this->request->post('fecha_desde')) ?  \DateTime::createFromFormat('d/m/Y', $temp) : null;
			$informal->fecha_hasta		= null;

			if($informal->validar()){	
				$informal->modificacion();
				$this->mensajeria->agregar(
					"La nueva Dependencia Informal fue modificada exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/dependencias/index_informales");
				$this->redirect($redirect);	
			}else {
				$err	= $informal->errores;
				foreach ((array)$err as $text) {
					$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
				}
			}
		}
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista','informal','lista_dependencias')))->pre_render();
	}

	 protected function accion_modificacion() {
	 	if($this->request->is_ajax()){ 
			$data = $this->_get_dependencias($this->request->query('nivel'));
			$this->json->setData($data);
			$this->json->render();
			exit;
		}

		$dependencia = Modelo\Dependencia::obtener($this->request->query('id'));
		if($dependencia->id_padre == 0){
			$dependencia->id_padre = Modelo\Dependencia::FAKE_PADRE_ID;
		}
		$control = unserialize(serialize($dependencia));
		$nivel_organigrama = \App\Modelo\Dependencia::getParam('NIVEL_ORGANIGRAMA');
		$lista_dependencias = Modelo\Dependencia::lista_dependencias();
	 	$dependencia->nombre		= ($temp = $this->request->post('nombre')) ?  $temp : $dependencia->nombre;
		$dependencia->id_padre		= ($temp = $this->request->post('id_padre')) ?  $temp : $dependencia->id_padre;
		$dependencia->fecha_desde 	= ($temp = $this->request->post('fecha_desde')) ?  \DateTime::createFromFormat('d/m/Y', $temp) : $dependencia->fecha_desde;
		$dependencia->nivel			= ($temp = $this->request->post('nivel')) ?  $temp : $dependencia->nivel;
		if($this->request->post('boton_dependencia') == 'modificacion') {
			if($dependencia->validar()){
				if($dependencia->id_padre == Modelo\Dependencia::FAKE_PADRE_ID){
					$dependencia->id_padre = 0;
				}
				if ((int)$dependencia->id_padre !== $control->id_padre) {
					$aux = unserialize(serialize($dependencia));
					$dependencia= $control;
					$dependencia->modificacion_dep_historica();
					$dependencia = $aux;
					$dependencia->alta_dep_historica();
				}
				$dependencia->modificacion();
				$this->mensajeria->agregar(
				"La nueva Dependencia fue modificada exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/dependencias/index");
				$this->redirect($redirect);	
			}else {
					$err	= $dependencia->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'nivel_organigrama','lista_dependencias','dependencia')))->pre_render();

	 }

	 protected function accion_baja() {
	 	$dependencia = Modelo\Dependencia::obtener($this->request->query('id'));
		if($dependencia->id) {
			if (!empty($this->request->post('confirmar'))) {
				$dependencia->fecha_hasta = \DateTime::createFromFormat('U', strtotime('now'));
				if($dependencia->validar()){
					$res = $dependencia->baja();
					if ($res) {
						$this->mensajeria->agregar('AVISO: La Dependencia se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase,'index');
						$redirect = Helper\Vista::get_url('index.php/dependencias/index');
						$this->redirect($redirect);
					} else {
						$this->mensajeria->agregar('AVISO: No es posible eliminar una Dependencia con agentes asignados.',\FMT\Mensajeria::TIPO_ERROR,$this->clase,'index');
						$redirect = Helper\Vista::get_url('index.php/dependencias/index');
						$this->redirect($redirect);
					}
				} else {
					$err	= $dependencia->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/dependencias/index');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('dependencia', 'vista')))->pre_render();
	}


	 protected function accion_baja_informales() {
		$id_informal=$this->request->query('id');
		$informal	= Modelo\DependenciaInformal::obtener($id_informal);
		$dependencia = Modelo\Dependencia::getPadre($id_informal);
		if(!is_null($informal->id)) {
			if ($this->request->post('confirmar')) {
				$informal->fecha_hasta = \DateTime::createFromFormat('U', strtotime('now'));

				$res = $informal->baja();
				if ($res) {
					$this->mensajeria->agregar('AVISO: La Dependencia Informal se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase,'index_informales');
					$redirect = Helper\Vista::get_url('index.php/dependencias/index_informales');
					$this->redirect($redirect);
				} else {
					$this->mensajeria->agregar('AVISO: No es posible eliminar una Dependencia con agentes asignados.',\FMT\Mensajeria::TIPO_ERROR,$this->clase,'index_informales');
					$redirect = Helper\Vista::get_url('index.php/dependencias/index_informales');
					$this->redirect($redirect);
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/dependencias/index_informales');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('informal', 'vista')))->pre_render();
	}

	protected function accion_ajax_lista_dependencias() {

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

		$data =  Modelo\Dependencia::listadoDependencias($params);

		$datos['draw']	= (int) $this->request->query('draw');

		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}


	protected function accion_ajax_lista_dependencias_informales() {
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
			'filtros'	=> [			
			'dependencia' => $this->request->query('dependencia')		
			],		
		
		];		

		$data =  Modelo\Dependencia::listadoDependenciasInformales($params);

		$datos['draw']	= (int) $this->request->query('draw');


		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
		}

		/**
		* Dado un id de dependencia obtiene los niveles de sus hijos.
		*/
		protected function accion_ajax_nivel_hijos() {
			$id_dependencia	= $this->request->query('id');
			$data =  Modelo\Dependencia::obtener_niveles_hijos($id_dependencia);
			(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
		}

		/**
		* Dado un id de dependencia y niveles obtiene los hijos que se corresponden.
		*/
		protected function accion_ajax_dependencias_nivel() {
			$id_dependencia	= $this->request->post('id_dependencia');
			$niveles		= $this->request->post('nivel');
			$data =  Modelo\Dependencia::obtener_dependencias_niveles($id_dependencia, $niveles);
			(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
		}
}
