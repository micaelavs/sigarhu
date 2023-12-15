<?php
namespace App\Controlador;
use FMT\Controlador;
use App\Modelo;
use App\Helper;
use FMT\Configuracion;
use App\Helper\Vista;
use App\Helper\Util;

class Responsable_contrato extends Base {
	


	public function accion_gestionar(){
		$sin_cambios = true;
		$niveles = [Modelo\Dependencia::SUBSECRETARIA, Modelo\Dependencia::SECRETARIA, Modelo\Dependencia::MINISTRO, Modelo\Dependencia::DIRECCION_GENERAL, Modelo\Dependencia::DIRECCION_SIMPLE, Modelo\Dependencia::UNIDAD_O_AREA];
		$lista_dependencias = [];
		foreach ($niveles as $value) {
			/* array_mergen no mantiene los indices originales, por lo tanto, se uso la simple suma de los arrays */
			$lista_dependencias = $lista_dependencias + Modelo\Dependencia::obtener_dependencias($value);
		}
		$responsable_contrato = Modelo\ResponsableContrato::obtener($this->request->post('dependencia'));
		$control = Clone $responsable_contrato;
      	
      	if ($this->request->post('boton_contratante') == 'post') {
      		
      		if (empty($responsable_contrato->getContratante()) && empty($responsable_contrato->getFirmante())){
      			$responsable_contrato->id_dependencia = !empty($temp = $this->request->post('dependencia')) ?  $temp : null;
      			$responsable_contrato->setContratante($this->request->post('contratante'));
      			$responsable_contrato->setFirmante($this->request->post('firmante')); 
	      		
	      		if ($responsable_contrato->validar()) {	
	      				$resp = $responsable_contrato->alta_contratante();
						
						foreach ($responsable_contrato->getFirmante() as $value) {
							$responsable_contrato->alta_firmante($value);
						}
						$this->mensajeria->agregar(
						"Los Responsables fueron dados de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
						$sin_cambios = false;					
				} else {
						$err	= $responsable_contrato->errores;
						foreach ($err as $text) {
							$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
						}
						$sin_cambios = false;
						
				}
      		} else {
      			$responsable_contrato->id_dependencia = !empty($temp = $this->request->post('dependencia')) ?  $temp : null;	
      			$responsable_contrato->setContratante($this->request->post('contratante'));
      			$responsable_contrato->setFirmante($this->request->post('firmante'));

      			// Modificación de Contratante
      			if (current($control->getContratante()) != $responsable_contrato->getContratante()) {
      				
      				if($responsable_contrato->validar()) {
      					$res = $control->baja_contratante();
      					
      					if ($res) {
      						$responsable_contrato->alta_contratante();  					
      					}

      					if ($res) {
      						$this->mensajeria->agregar(
							"El Contratante fué modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
							$sin_cambios = false;							
      					} else {
      						$err	= $responsable_contrato->errores;
							foreach ($err as $text) {
							$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
      						}
							$sin_cambios = false;
      				}
					
					}else {
							$err	= $responsable_contrato->errores;
							foreach ($err as $text) {
								$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
							}
							$sin_cambios = false;		
					}
      			}
      			// Modificación de Firmante
      			if ($control->getFirmante() != $responsable_contrato->getFirmante()) {
      				if($responsable_contrato->validar()){
      					$comparacion_firmante_nuevos = array_diff($responsable_contrato->getFirmante(), $control->getFirmante());
	      				foreach ($comparacion_firmante_nuevos as $value) {
							$responsable_contrato->alta_firmante($value);
						}
      					$comparacion_firmante_viejos = array_diff($control->getFirmante(), $responsable_contrato->getFirmante());
		     			foreach ($comparacion_firmante_viejos as $value) {
								$control->baja_firmante($value);
						}
						$this->mensajeria->agregar(
						"Los Firmantes fueron modificados exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
						$sin_cambios = false;						
      				}else {
						$err	= $responsable_contrato->errores;
						foreach ($err as $text) {
							$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
						}
						$sin_cambios = false;						
					}			
				}
			}

			if($sin_cambios) 
				$this->mensajeria->agregar('No se realizaron cambios', \FMT\Mensajeria::TIPO_ERROR, $this->clase);
		}
		

		
		$vista = $this->vista;
		$data  = $this->_get_contratante_firmantes($responsable_contrato->id_dependencia); 
		(new Vista($this->vista_default,compact('vista', 'lista_dependencias','agentes','responsable_contrato','data')))->pre_render();


	}
	
	protected function accion_ajax_get_contratante_firmantes() {
		
		$data = $this->_get_contratante_firmantes( $this->request->post('dependencia') );
		
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();

	}
	
	protected function _get_contratante_firmantes($id_dependencia) {
		$data = [];
		if (!is_null($id_dependencia)) {
			$dependencias	=  Modelo\Dependencia::obtener_cadena_dependencias_hijas($id_dependencia);
            $dependencias = array_column($dependencias, 'id');
            if(empty($dependencias)){
                $dependencias   = [$id_dependencia];
            }
			$niveles = [Modelo\Dependencia::SUBSECRETARIA, Modelo\Dependencia::SECRETARIA, Modelo\Dependencia::MINISTRO, Modelo\Dependencia::DIRECCION_GENERAL, Modelo\Dependencia::DIRECCION_SIMPLE, Modelo\Dependencia::UNIDAD_O_AREA];
            $adicionales    = Modelo\Dependencia::obtener_dependencias($niveles);
            $dependencias   = array_merge($dependencias, array_keys($adicionales), ['76']); //AGREGO la dependencia 76 para que pueda estar siempre la responsable de Control de RRHH

			$params	= [
				'start' =>'0', 
				'lenght'=>'20000',  
				'filtros'=> [
                    'dependencia' => $dependencias,
					'modalidad_contratacion' => ['1','3','4','5','6']
				],
				'order' => [
					['campo' =>'p.nombre' ,'dir' => 'asc'],
					['campo' =>'p.apellido' ,'dir' => 'asc']
				]
			];	
			
			$rta = Modelo\Empleado::listadoAgentes($params)['data'];
			$personas = [];

			foreach ($rta as $value) {
				$personas[$value['id']]['nombre'] = $value['nombre'].' '. $value['apellido'].'-- CUIT --'.$value['cuit'];
				$personas[$value['id']]['borrado'] = 0;
			}
			$data['personas'] = $personas;
			$responsable_contrato = Modelo\ResponsableContrato::obtener($id_dependencia);
			$data['contratante'] = is_array($temp = $responsable_contrato->getContratante()) ? $temp: []; 
			$data['firmante'] = is_array($temp = $responsable_contrato->getFirmante()) ? $temp: [];	
		}		
		return $data;
	}
	

}