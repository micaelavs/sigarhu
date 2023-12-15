<?php
namespace App\Controlador;
use FMT\Controlador;
use App\Modelo;
use App\Helper;
use App\Modelo\Empleado;
use App\Modelo\Designacion_transitoria;
use FMT\Configuracion;
use App\Helper\Vista;
use App\Helper\Util;

class Escalafon extends Base {


	protected function accion_index() {
		(new Helper\Vista($this->vista_default,['vista' => $this->vista ]))
			->pre_render();
	}

	protected function accion_lista_modalidad_vinculacion() {	 
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista')))->pre_render();
	}

	protected function accion_modalidad_vinculacion(){
		$mod_vinculacion = Modelo\ModalidadVinculacion::obtener($this->request->query('id'));

		if($this->request->post('boton_mod_vinculacion')) {
			$mod_vinculacion->nombre = $this->request->post('nombre');

			if($this->request->post('boton_mod_vinculacion') == 'alta') {
				if($mod_vinculacion->validar()){	
					$mod_vinculacion->alta();
					$this->mensajeria->agregar(
					"La Modalidad de Vinculación fue dada de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/escalafon/lista_modalidad_vinculacion");
					$this->redirect($redirect);	
				}else {
					$err = $mod_vinculacion->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}

			if($this->request->post('boton_mod_vinculacion') == 'modificacion') {
				if($mod_vinculacion->validar()){	
					$mod_vinculacion->modificacion();
					$this->mensajeria->agregar(
					"La Modalidad de Vinculación fue modificada exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/escalafon/lista_modalidad_vinculacion");
					$this->redirect($redirect);	
				}else {
					$err = $mod_vinculacion->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		}
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista','mod_vinculacion')))->pre_render();
	}

	protected function accion_baja_modalidad_vinculacion() {
	 	$mod_vinculacion = Modelo\ModalidadVinculacion::obtener($this->request->query('id'));
		if($mod_vinculacion->id) {
			if ($this->request->post('confirmar')) {
				$res = $mod_vinculacion->baja();
				if ($res) {
					$this->mensajeria->agregar('AVISO: La Modalidad de Vinculación se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/escalafon/lista_modalidad_vinculacion');
					$this->redirect($redirect);
				} else {
					$this->mensajeria->agregar('AVISO: No es posible eliminar la Modalidad de Vinculación. Contácte con el Administrador',\FMT\Mensajeria::TIPO_ERROR,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/escalafon/lista_modalidad_vinculacion');
					$this->redirect($redirect);
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/escalafon/lista_modalidad_vinculacion');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('mod_vinculacion', 'vista')))->pre_render();
	}

	protected function accion_ajax_lista_modalidad_vinculacion() {
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
		$data =  Modelo\ModalidadVinculacion::listadoModalidades($params);
		$datos['draw']	= (int) $this->request->query('draw');
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

	protected function accion_lista_situacion_revista() {	 
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista')))->pre_render();
	}
	
	protected function accion_ajax_lista_situacion_revista() {
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
		$data =  Modelo\SituacionRevista::listadoSituacionRevista($params);
		$datos['draw']	= (int) $this->request->query('draw');
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

	protected function accion_situacion_revista(){
		$mod_vinculacion = Modelo\ModalidadVinculacion::modalidades();
		$situacion_revista = Modelo\SituacionRevista::obtener($this->request->query('id'));

		if($this->request->post('boton_sit_revista')) {
			$situacion_revista->id_modalidad_vinculacion = $this->request->post('id_mod_vinculacion');
			$situacion_revista->nombre = $this->request->post('nombre');

			if($this->request->post('boton_sit_revista') == 'alta') {
				if($situacion_revista->validar()){	
					$situacion_revista->alta();
					$this->mensajeria->agregar(
					"La Situación de Revista fue dada de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/escalafon/lista_situacion_revista");
					$this->redirect($redirect);	
				}else {
					$err = $situacion_revista->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}

			if($this->request->post('boton_sit_revista') == 'modificacion') {
				if($situacion_revista->validar()){
					$situacion_revista->modificacion();
					$this->mensajeria->agregar(
					"La Denominación de la Función fue modificada exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/escalafon/lista_situacion_revista");
					$this->redirect($redirect);	
				}else {
					$err = $situacion_revista->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		}
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista','situacion_revista','mod_vinculacion')))->pre_render();
	}

	protected function accion_baja_situacion_revista() {
	 	$situacion_revista = Modelo\SituacionRevista::obtener($this->request->query('id'));
		if($situacion_revista->id) {
			if ($this->request->post('confirmar')) {
				$res = $situacion_revista->baja();
				if ($res) {
					$this->mensajeria->agregar('AVISO: La Situación de Revista se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/escalafon/lista_situacion_revista');
					$this->redirect($redirect);
				} else {
					$this->mensajeria->agregar('AVISO: No es posible eliminar la Situación de Revista. Contácte con el Administrador',\FMT\Mensajeria::TIPO_ERROR,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/escalafon/lista_situacion_revista');
					$this->redirect($redirect);
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/escalafon/lista_situacion_revista');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('situacion_revista', 'vista')))->pre_render();
	}

	protected function accion_lista_niveles() {	 
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista')))->pre_render();
	}
	
	protected function accion_ajax_lista_niveles() {
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
		$data =  Modelo\Nivel::listadoNiveles($params);
		$datos['draw']	= (int) $this->request->query('draw');
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

	protected function accion_nivel(){
		$nivel = Modelo\Nivel::obtener($this->request->query('id'));
		if($this->request->post('boton_nivel')) {
			$nivel->id_agrupamiento = $this->request->post('id_agrupamiento');
			$nivel->nombre = $this->request->post('nombre');
			$nivel->agrupamiento->id_modalidad_vinculacion = $this->request->post('id_modalidad_vinculacion');
			$nivel->agrupamiento->id_situacion_revista = $this->request->post('id_situacion_revista');

			if($this->request->post('boton_nivel') == 'alta') {
				if($nivel->validar()){	
					$nivel->alta();
					$this->mensajeria->agregar(
					"El Nivel fue dado de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/escalafon/lista_niveles");
					$this->redirect($redirect);	
				}else {
					$err = $nivel->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}

			if($this->request->post('boton_nivel') == 'modificacion') {
				if($nivel->validar()){	
					$nivel->modificacion();
					$this->mensajeria->agregar(
					"El Nivel fue modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/escalafon/lista_niveles");
					$this->redirect($redirect);	
				}else {
					$err = $nivel->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		}

		$param = $nivel->agrupamiento;
		$agrupamiento = Modelo\Agrupamiento::agrupamientos($param);
		$sit_revista = Modelo\SituacionRevista::getSitRevista($param->id_modalidad_vinculacion);
		$modalidades = Modelo\ModalidadVinculacion::modalidades();

		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista','nivel','modalidades','sit_revista','agrupamiento')))->pre_render();
	}

	protected function accion_baja_nivel() {
	 	$nivel = Modelo\Nivel::obtener($this->request->query('id'));
		if($nivel->id) {
			if ($this->request->post('confirmar')) {
				$res = $nivel->baja();
				if ($res) {
					$this->mensajeria->agregar('AVISO: El Nivel se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/escalafon/lista_niveles');
					$this->redirect($redirect);
				} else {
					$this->mensajeria->agregar('AVISO: No es posible eliminar el Nivel. Contácte con el Administrador',\FMT\Mensajeria::TIPO_ERROR,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/escalafon/lista_niveles');
					$this->redirect($redirect);
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/escalafon/lista_niveles');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('nivel', 'vista')))->pre_render();
	}

	protected function accion_lista_grados() {	 
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista')))->pre_render();
	}
	
	protected function accion_ajax_lista_grados() {
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
		$data =  Modelo\Grado::listadoGrados($params);
		$datos['draw']	= (int) $this->request->query('draw');
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

	protected function accion_grado(){
		$grado = Modelo\Grado::obtener($this->request->query('id'));

		if($this->request->post('boton_grado')) {
			$grado->id_tramo = $this->request->post('id_tramo');
			$grado->nombre = $this->request->post('nombre');
			$grado->tramo->id_modalidad_vinculacion = $this->request->post('id_modalidad_vinculacion');
			$grado->tramo->id_situacion_revista 	= $this->request->post('id_situacion_revista');
			if($this->request->post('boton_grado') == 'alta') {
				if($grado->validar()){	
					$grado->alta();
					$this->mensajeria->agregar(
					"El Grado fue dado de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/escalafon/lista_grados");
					$this->redirect($redirect);	
				}else {
					$err = $grado->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}

			if($this->request->post('boton_grado') == 'modificacion') {
				if($grado->validar()){	
					$grado->modificacion();
					$this->mensajeria->agregar(
					"El Grado fue modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/escalafon/lista_grados");
					$this->redirect($redirect);	
				}else {
					$err = $grado->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		}
		$param = $grado->tramo;
		$tramos = Modelo\Tramo::getTramos($param);
		$sit_revista = Modelo\SituacionRevista::getSitRevista($param->id_modalidad_vinculacion);
		$modalidades = Modelo\ModalidadVinculacion::modalidades();

		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista','grado','tramos','modalidades','sit_revista')))->pre_render();
	}

	protected function accion_baja_grado() {
	 	$grado = Modelo\Grado::obtener($this->request->query('id'));
		if($grado->id) {
			if ($this->request->post('confirmar')) {
				$res = $grado->baja();
				if ($res) {
					$this->mensajeria->agregar('AVISO: El Grado se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/escalafon/lista_grados');
					$this->redirect($redirect);
				} else {
					$this->mensajeria->agregar('AVISO: No es posible eliminar el Grado. Contácte con el Administrador',\FMT\Mensajeria::TIPO_ERROR,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/escalafon/lista_grados');
					$this->redirect($redirect);
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/escalafon/lista_grados');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('grado', 'vista')))->pre_render();
	}

	protected function accion_lista_tramos() {	 
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista')))->pre_render();
	}
	
	protected function accion_ajax_lista_tramos() {
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
		$data =  Modelo\Tramo::listadoTramos($params);
		$datos['draw']	= (int) $this->request->query('draw');
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

	protected function accion_tramo(){
		$tramo = Modelo\Tramo::obtener($this->request->query('id'));
		$modalidades = Modelo\ModalidadVinculacion::modalidades();
		$sit_revista = Modelo\SituacionRevista::getSitRevista($tramo->id_modalidad_vinculacion);

		if($this->request->post('boton_tramo')) {
			$tramo->id_modalidad_vinculacion = $this->request->post('id_modalidad_vinculacion');
			$tramo->id_situacion_revista = $this->request->post('id_situacion_revista');
			$tramo->nombre = $this->request->post('nombre');

			if($this->request->post('boton_tramo') == 'alta') {
				if($tramo->validar()){	
					$tramo->alta();
					$this->mensajeria->agregar(
					"El Tramo fue dado de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/escalafon/lista_tramos");
					$this->redirect($redirect);	
				}else {
					$err = $tramo->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}

			if($this->request->post('boton_tramo') == 'modificacion') {
				if($tramo->validar()){	
					$tramo->modificacion();
					$this->mensajeria->agregar(
					"El Tramo fue modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/escalafon/lista_tramos");
					$this->redirect($redirect);	
				}else {
					$err = $tramo->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		}
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista','tramo','modalidades','sit_revista')))->pre_render();
	}

	protected function accion_baja_tramo() {
	 	$tramo = Modelo\Tramo::obtener($this->request->query('id'));
		if($tramo->id) {
			if ($this->request->post('confirmar')) {
				$res = $tramo->baja();
				if ($res) {
					$this->mensajeria->agregar('AVISO: El Tramo se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/escalafon/lista_tramos');
					$this->redirect($redirect);
				} else {
					$this->mensajeria->agregar('AVISO: No es posible eliminar el Tramo. Contácte con el Administrador',\FMT\Mensajeria::TIPO_ERROR,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/escalafon/lista_tramos');
					$this->redirect($redirect);
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/escalafon/lista_tramos');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('tramo', 'vista')))->pre_render();
	}

	protected function accion_ajax_get_revista(){
		$data	= [
			'revista'	=> Modelo\SituacionRevista::ajaxSituacionRevista($this->request->post('id_modalidad'))
		];
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

	protected function accion_lista_agrupamientos() {	 
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista')))->pre_render();
	}
	
	protected function accion_ajax_lista_agrupamientos() {
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
		$data =  Modelo\Agrupamiento::listadoAgrupamientos($params);
		$datos['draw']	= (int) $this->request->query('draw');
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

	protected function accion_agrupamiento(){
		$agrupamiento = Modelo\Agrupamiento::obtener($this->request->query('id'));
		$modalidades = Modelo\ModalidadVinculacion::modalidades();
		$sit_revista = Modelo\SituacionRevista::getSitRevista($agrupamiento->id_modalidad_vinculacion);

		if($this->request->post('boton_agrupamiento')) {
			$agrupamiento->id_modalidad_vinculacion = $this->request->post('id_modalidad_vinculacion');
			$agrupamiento->id_situacion_revista = $this->request->post('id_situacion_revista');
			$agrupamiento->nombre = $this->request->post('nombre');

			if($this->request->post('boton_agrupamiento') == 'alta') {
				if($agrupamiento->validar()){	
					$agrupamiento->alta();
					$this->mensajeria->agregar(
					"El Agrupamiento fue dado de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/escalafon/lista_agrupamientos");
					$this->redirect($redirect);	
				}else {
					$err = $agrupamiento->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}

			if($this->request->post('boton_agrupamiento') == 'modificacion') {
				if($agrupamiento->validar()){	
					$agrupamiento->modificacion();
					$this->mensajeria->agregar(
					"El Agrupamiento fue modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/escalafon/lista_agrupamientos");
					$this->redirect($redirect);	
				}else {
					$err = $agrupamiento->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		}
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista','agrupamiento','modalidades','sit_revista')))->pre_render();
	}

	protected function accion_baja_agrupamiento() {
	 	$agrupamiento = Modelo\Agrupamiento::obtener($this->request->query('id'));
		if($agrupamiento->id) {
			if ($this->request->post('confirmar')) {
				$res = $agrupamiento->baja();
				if ($res) {
					$this->mensajeria->agregar('AVISO: El Agrupamiento se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/escalafon/lista_agrupamientos');
					$this->redirect($redirect);
				} else {
					$this->mensajeria->agregar('AVISO: No es posible eliminar el Agrupamiento. Contácte con el Administrador',\FMT\Mensajeria::TIPO_ERROR,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/escalafon/lista_agrupamientos');
					$this->redirect($redirect);
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/escalafon/lista_agrupamientos');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('agrupamiento', 'vista')))->pre_render();
	}

	protected function accion_ajax_get_agrupamientos(){
		$params = $this->request->post();
		$data	= [
			'agrupamiento'	=> Modelo\Agrupamiento::ajaxGetAgrupamientos($params)
		];
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

	protected function accion_ajax_get_tramos(){
		$params = $this->request->post();
		$data	= [
			'tramos'	=> Modelo\Tramo::ajaxGetTramos($params)
		];
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

	protected function accion_lista_funciones_ejecutivas() {	 
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista')))->pre_render();
	}
	
	protected function accion_ajax_lista_funciones_ejecutivas() {
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
		$data =  Modelo\FuncionEjecutiva::listadoFuncionesEjecutivas($params);
		$datos['draw']	= (int) $this->request->query('draw');
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

	protected function accion_funcion_ejecutiva(){
		$f_ejecutiva = Modelo\FuncionEjecutiva::obtener($this->request->query('id'));
		$modalidades = Modelo\ModalidadVinculacion::modalidades();
		$sit_revista = Modelo\SituacionRevista::getSitRevista($f_ejecutiva->id_modalidad_vinculacion);

		if($this->request->post('boton_funcion_ejecutiva')) {
			$f_ejecutiva->id_modalidad_vinculacion = $this->request->post('id_modalidad_vinculacion');
			$f_ejecutiva->id_situacion_revista = $this->request->post('id_situacion_revista');
			$f_ejecutiva->nombre = $this->request->post('nombre');

			if($this->request->post('boton_funcion_ejecutiva') == 'alta') {
				if($f_ejecutiva->validar()){	
					$f_ejecutiva->alta();
					$this->mensajeria->agregar(
					"La Función Ejecutiva fue dada de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/escalafon/lista_funciones_ejecutivas");
					$this->redirect($redirect);	
				}else {
					$err = $f_ejecutiva->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}

			if($this->request->post('boton_funcion_ejecutiva') == 'modificacion') {
				if($f_ejecutiva->validar()){	
					$f_ejecutiva->modificacion();
					$this->mensajeria->agregar(
					"La Función Ejecutiva fue modificada exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/escalafon/lista_funciones_ejecutivas");
					$this->redirect($redirect);	
				}else {
					$err = $f_ejecutiva->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		}
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista','f_ejecutiva','modalidades','sit_revista')))->pre_render();
	}

	protected function accion_baja_funcion_ejecutiva() {
	 	$f_ejecutiva = Modelo\FuncionEjecutiva::obtener($this->request->query('id'));
		if($f_ejecutiva->id) {
			if ($this->request->post('confirmar')) {
				$res = $f_ejecutiva->baja();
				if ($res) {
					$this->mensajeria->agregar('AVISO: La Función Ejecutiva se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/escalafon/lista_funciones_ejecutivas');
					$this->redirect($redirect);
				} else {
					$this->mensajeria->agregar('AVISO: No es posible eliminar la Función Ejecutiva. Contácte con el Administrador',\FMT\Mensajeria::TIPO_ERROR,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/escalafon/lista_funciones_ejecutivas');
					$this->redirect($redirect);
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/escalafon/lista_funciones_ejecutivas');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('f_ejecutiva', 'vista')))->pre_render();
	}

	protected function accion_designacion_transitoria(){
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista')))->pre_render();
	}

	protected function accion_ajax_designacion_transitoria(){

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

		$listado =  \App\Modelo\Designacion_transitoria::informe_designacion_transitoria($params);

		foreach ($listado['data'] as $key => $value) {
			$listado['data'][$key]->nombre_completo	= $value->nombre. ' ' .$value->apellido;

		}

		$data = $listado;

		$datos['draw']	= (int) $this->request->query('draw');
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

	protected function accion_historial_designacion(){
		$empleado	= Empleado::obtener($this->request->query('id'));
		$designacion_transitoria = Designacion_transitoria::obtener($empleado->id);
		$designaciones = $designacion_transitoria->listar_designaciones($empleado->id);
		$vista = $this->vista;
		$vars = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit];
		$vista->add_to_var('vars',$vars);
		(new Vista($this->vista_default,compact('vista','designaciones','empleado')))->pre_render();
	}

	protected function accion_mostrar_designacion(){
		$archivo = Modelo\Designacion_transitoria::obtener_archivo($this->request->query('id'));
		$doc_content = preg_replace("/\d{14}_/", "", $archivo);
		$doc = BASE_PATH.'/uploads/designacion_transitoria/'.$archivo ;
		header("Content-Disposition:inline;filename=".$doc_content."");
		header("Content-type: application/pdf;");
		readfile($doc);
	}

	protected function accion_editar_prorroga() {
		Modelo\Empleado::contiene(['persona']);
		$empleado	= Empleado::obtener($this->request->query('id'),1);
		$tipo_designacion = Modelo\Designacion_transitoria::getParam('TIPO_DESIGNACION');
		$designacion_transitoria = Modelo\Designacion_transitoria::obtener($empleado->id);
		$designacion_transitoria->tipo  		= !empty($this->request->post('tipo_designacion')) ?  (int)$this->request->post('tipo_designacion'): $designacion_transitoria->tipo;
		$designacion_transitoria->fecha_desde 	= !empty($temp = $this->request->post('fecha_desde')) ?  \DateTime::createFromFormat('d/m/Y', $temp) : $designacion_transitoria->fecha_desde;
		$designacion_transitoria->fecha_hasta	= \DateTime::createFromFormat('Y-m-d H:i:s.u', \FMT\Informacion_fecha::dias_habiles_hasta_fecha($designacion_transitoria->fecha_desde, 180).' 0:00:00.000000');

		if ($this->request->post('boton_designacion') == 'modificacion') {
				$designacion_transitoria->id_empleado 			= $empleado->id;
				$designacion_transitoria->archivo				= ($_FILES['designacion_file']['error'] == UPLOAD_ERR_OK) ? $_FILES['designacion_file'] : $designacion_transitoria->archivo;
				if($designacion_transitoria->validar()){
					$designacion_transitoria->modificacion();
						$this->mensajeria->agregar("la porroga del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue modificada exitosamente.",
						\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/escalafon/designacion_transitoria");
					$this->redirect($redirect);

				}else {
					$err	= $designacion_transitoria->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
		}
		$vista = $this->vista;
		$vars = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit];
			$vista->add_to_var('vars',$vars);
		(new Vista($this->vista_default,compact('vista','empleado', 'designacion_transitoria','tipo_designacion')))->pre_render();
	}

	protected function accion_baja_prorroga() {
		$designacion_transitoria = Modelo\Designacion_transitoria::obtener_prorroga($this->request->query('id'));
		$empleado	= Empleado::obtener($designacion_transitoria->id_empleado, true);

		if (!empty($empleado->id) && !empty($empleado->cuit)){
			if($designacion_transitoria->id) {
				if ($this->request->post('confirmar')) {
					if ($designacion_transitoria->baja()) {
						$this->mensajeria->agregar('AVISO: La designacion se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					}
					$redirect =Vista::get_url("index.php/escalafon/designacion_transitoria");
					$this->redirect($redirect);
			}
			$vista = $this->vista;
			$vars = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit];
				$vista->add_to_var('vars',$vars);
			(new Vista($this->vista_default,compact('vista','empleado', 'designacion_transitoria')))->pre_render();
			}
		}
	}

	protected function accion_agregar_prorroga() {
		Modelo\Empleado::contiene(['persona']);
		$empleado								= Empleado::obtener($this->request->query('id'));
		$tipo_designacion 						= Modelo\Designacion_transitoria::getParam('TIPO_DESIGNACION');
		$designacion_transitoria 				= Modelo\Designacion_transitoria::obtener();
		$control = Modelo\Designacion_transitoria::obtener($empleado->id);
		$designacion_transitoria->fecha_desde 	= $control->fecha_hasta;
		$designacion_transitoria->tipo = Modelo\Designacion_transitoria::PRORROGA;
		if ($this->request->post('boton_designacion') == 'alta') {
				$designacion_transitoria->id_empleado 	= $empleado->id;	
				$designacion_transitoria->archivo		= ($_FILES['designacion_file']['error'] == UPLOAD_ERR_OK) ? $_FILES['designacion_file'] : '';
				if($designacion_transitoria->validar()){
					if($designacion_transitoria->alta()){
						$this->mensajeria->agregar(
							"la nueva porroga del empleado <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> fue cargada exitosamente.",
							\FMT\Mensajeria::TIPO_AVISO,
							$this->clase
						);
						$redirect =Vista::get_url("index.php/escalafon/designacion_transitoria");
						$this->redirect($redirect);
					} else {
						$this->mensajeria->agregar('Fallo al aplicar prorroga', \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}

				}else {
					$err	= $designacion_transitoria->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
		}
		$vista = $this->vista;
		$vars = ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit];
			$vista->add_to_var('vars',$vars);
		(new Vista($this->vista_default,compact('vista','empleado', 'designacion_transitoria','tipo_designacion')))->pre_render();
	}
}