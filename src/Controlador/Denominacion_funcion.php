<?php
namespace App\Controlador;
use FMT\Controlador;
use App\Modelo;
use App\Helper;
use FMT\Configuracion;
use App\Helper\Vista;
use App\Helper\Util;
use App\Modelo\DenominacionFuncion;

class Denominacion_funcion extends Base {


	protected function accion_index() {
		(new Helper\Vista($this->vista_default,['vista' => $this->vista ]))
			->pre_render();
	}


	protected function accion_gestionar(){
		$denom_funcion = Modelo\DenominacionFuncion::obtener($this->request->query('id'));

		if($this->request->post('boton_denom_funcion')) {
			$denom_funcion->nombre = $this->request->post('nombre');

			if($this->request->post('boton_denom_funcion') == 'alta') {
				if($denominacion_funcion = DenominacionFuncion::obtener_por_nombre_si_borrado($denom_funcion->nombre)){
					$this->redirect(Vista::get_url("index.php/Denominacion_funcion/reactivar/$denominacion_funcion->id"));
				}
				if($denom_funcion->validar()){	
					$denom_funcion->alta();
					$this->mensajeria->agregar(
					"La Denominación de la Función fue dada de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/Denominacion_funcion/index");
					$this->redirect($redirect);	
				}else {
					$err = $denom_funcion->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}

			if($this->request->post('boton_denom_funcion') == 'modificacion') {
				if($denom_funcion->validar()){	
					$denom_funcion->modificacion();
					$this->mensajeria->agregar(
					"La Denominación de la Función fue modificada exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/Denominacion_funcion/index");
					$this->redirect($redirect);	
				}else {
					$err = $denom_funcion->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		}
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista','denom_funcion')))->pre_render();
	}

	protected function accion_baja() {
	 	$denom_funcion = Modelo\DenominacionFuncion::obtener($this->request->query('id'));
		if($denom_funcion->id) {
			if ($this->request->post('confirmar')) {
				$res = $denom_funcion->baja();
				if ($res) {
					$this->mensajeria->agregar('AVISO: La Denominación de la Función se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/Denominacion_funcion/index');
					$this->redirect($redirect);
				} else {
					$this->mensajeria->agregar('AVISO: No es posible eliminar la Denominación de la Función. Contácte con el Administrador',\FMT\Mensajeria::TIPO_ERROR,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/Denominacion_funcion/index');
					$this->redirect($redirect);
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/Denominacion_funcion/index');
			$this->redirect($redirect);
		}

		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('denom_funcion', 'vista')))->pre_render();
	}

	protected function accion_ajax_lista_denominacion_funcion() {
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
		$data =  Modelo\DenominacionFuncion::listadoDenominacion($params);
		$datos['draw']	= (int) $this->request->query('draw');
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

	protected function accion_reactivar() {
		$denom_funcion= DenominacionFuncion::obtener($this->request->query('id'));
        if ($this->request->is_post() && $this->request->post('confirmar')) {
           
            if($denom_funcion->reactivar()){
                $this->mensajeria->agregar('AVISO:La Denominacion funcion se reactivó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase,'index');
                $this->redirect(Vista::get_url('index.php/Denominacion_funcion/index'));
            }
        }
        if(!$denom_funcion->id) {
            $this->mensajeria->agregar('AVISO:Id de Denominacion funcion no encontrado.', \FMT\Mensajeria::TIPO_ERROR, $this->clase, 'index');
            $this->redirect(Vista::get_url('index.php/denominacion_funcion/index'));
        }
        $vista = $this->vista;
        (new Vista(VISTAS_PATH.'/denominacion_funcion/reactivar.php',compact('denom_funcion','vista')))->pre_render();
    }
}