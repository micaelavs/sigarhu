<?php
namespace App\Controlador;
use App\Modelo;
use App\Helper\Vista;
use \FMT\Consola;

class SimuladorPromocionGrados extends Base {

	public function accion_simulacion_promocion_grado(){
		$vista = $this->vista;

		$id_empleado  		= $this->request->query('id');
		$simulacion_agente	= Modelo\SimuladorPromocionGrado::listar($id_empleado);

		(new Vista($this->vista_default, compact('vista', 'simulacion_agente')))->pre_render(); 
	}

	public function accion_agentes_promocionables(){
		$vista = $this->vista;
		$id_empleado  		= $this->request->query('id');
		$simulacion_agente	= Modelo\SimuladorPromocionGrado::listarAgentesPromocionables();

		(new Vista($this->vista_default, compact('vista','simulacion_agente')))->pre_render();
	}

	public function accion_ejecutar_simulador(){
		if(Consola\Modelo\ColaTarea::agregar('simular_promocion_grado')){
			$this->mensajeria->agregar('El proceso esta a espera de ser procesado, en instantes podra ver los resultados actualizados.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
		} else {
			$this->mensajeria->agregar('El proceso está siendo ejecutado.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
		}
		$this->redirect(Vista::get_url('index.php/SimuladorPromocionGrados/agentes_promocionables'));
	}
	protected function accion_listado_simulacion_promocion_grado() {
		$resultados			= Modelo\Evaluacion::getParam('resultados');
		$motivos			= Modelo\SimuladorPromocionGrado::getParam('MOTIVOS_PROMOCION');
		
		Modelo\Empleado::contiene(['persona'=>[]]);
		$empleado			= Modelo\Empleado::obtener($this->request->query('id'),1);
		$listadoSimulacion	= Modelo\SimuladorPromocionGrado::listar($this->request->query('id'));
		$vista				= $this->vista;
		$vars				= ['AGENTE' => $empleado->persona->apellido.' '.$empleado->persona->nombre, 'CUIT' => $empleado->cuit];
		$vista->add_to_var('vars',$vars);
		(new Vista($this->vista_default,compact('vista','empleado','resultados','motivos','listadoSimulacion')))->pre_render();
	}
}