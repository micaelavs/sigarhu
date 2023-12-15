<?php
namespace App\Controlador;
use FMT\Controlador;
use App\Modelo;
use App\Helper;
use FMT\Configuracion;
use App\Helper\Vista;
use App\Helper\Util;

class Titulos extends Base {

	protected function accion_index() {	 
		(new Helper\Vista($this->vista_default,['vista' => $this->vista ]))
			->pre_render();
	}
	
	protected function accion_alta(){
		$titulo = Modelo\Titulo::obtener($this->request->query('id'));
		$tipo_titulo	= [];
		$aux			= \App\Modelo\NivelEducativo::listar();
		array_walk($aux, function($value) use (&$tipo_titulo){
			$tipo_titulo[$value->id] = (array)$value;
		});
		
		$titulo->id_tipo_titulo	= !empty($temp = $this->request->post('id_tipo_titulo')) ?  $temp : null;
		$titulo->nombre			= !empty($temp = $this->request->post('nombre')) ?  $temp : null;
		$titulo->abreviatura 	=  !empty($temp = $this->request->post('abreviatura')) ?  $temp : null;
		if($this->request->post('boton_titulo') == 'alta') {
			if($titulo->validar()){	
				$titulo->alta();
				$this->mensajeria->agregar(
				"El nuevo Título fue dado de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/titulos/index");
				$this->redirect($redirect);	
			}else {
				$err	= $titulo->errores;
				foreach ($err as $text) {
					$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
				}
			}
		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'tipo_titulo','titulo')))->pre_render();
	}

	protected function accion_modificacion() {
		$titulo = Modelo\Titulo::obtener($this->request->query('id'));
		$aux			= \App\Modelo\NivelEducativo::listar();
		array_walk($aux, function($value) use (&$tipo_titulo){
			$tipo_titulo[$value->id] = (array)$value;
		});
		if($this->request->post('boton_titulo') == 'modificacion') {
			$titulo->id_tipo_titulo		= ($temp = $this->request->post('id_tipo_titulo')) ?  $temp : null;
	 		$titulo->nombre				= ($temp = $this->request->post('nombre')) ?  $temp : null;
			$titulo->abreviatura 	=  ($temp = $this->request->post('abreviatura')) ?  $temp : null;
			if($titulo->validar()){	
				$titulo->modificacion();
				$this->mensajeria->agregar(
				"El Título fue modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
				$redirect =Vista::get_url("index.php/titulos/index");
				$this->redirect($redirect);	
			}else {
					$err	= $titulo->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
			}
		}
		 $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista', 'tipo_titulo','titulo')))->pre_render();

	 }
	 	
	protected function accion_baja() {
	 	$titulo = Modelo\Titulo::obtener($this->request->query('id'));
	 	if($titulo->id){
			if ($this->request->post('confirmar')) {
					if($titulo->validar()){
						$titulo->baja();
						$this->mensajeria->agregar('AVISO: El Título se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase,'index');
						$redirect = Helper\Vista::get_url('index.php/titulos/index');
						$this->redirect($redirect);
					}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/titulos/index');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('titulo', 'vista')))->pre_render();
	}

	protected function accion_ajax_lista_titulos() {
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

		$data =  Modelo\Titulo::listadoTitulos($params);
		if(empty($data['data'])){
			$data = [
				'recordsTotal'		=> '0',
				'recordsFiltered'	=> '0',
				'data'				=> [],
			];
		}
		$datos['draw']	= (int) $this->request->query('draw');
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

	protected function accion_ajax_titulos(){
		$data 	= Modelo\PersonaTitulo::obtenerTitulo($this->request->post('id_tipo_titulo'));
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

}