<?php
namespace App\Controlador;
use FMT\Controlador;
use App\Modelo;
use App\Helper;
use FMT\Configuracion;
use App\Helper\Vista;
use App\Helper\Util;

class Presupuestos extends Base {

	protected function accion_index() {
		(new Helper\Vista($this->vista_default,['vista' => $this->vista ]))
			->pre_render();
	}


	protected function accion_ajax_presupuestos() {	 
		$this->ajax_presupuesto('presupuestos');
	}

	protected function accion_am_presupuesto(){
		$presupuesto = Modelo\Presupuesto::obtener($this->request->query('id'));

		if($this->request->post('boton_presupuesto')) {
			$presupuesto->id_saf 			= !empty($tmp = $this->request->post('id_saf')) ? $tmp : $presupuesto->id_saf;
			$presupuesto->id_jurisdiccion 	= !empty($tmp = $this->request->post('id_jurisdiccion')) ? $tmp : $presupuesto->id_jurisdiccion;
			$presupuesto->id_ubicacion_geografica = !empty($tmp = $this->request->post('id_ubicacion_geografica')) ? $tmp : $presupuesto->id_ubicacion_geografica;
			$presupuesto->id_programa 		= !empty($tmp = $this->request->post('id_programa')) ? $tmp : $presupuesto->id_programa;
			$presupuesto->id_subprograma 	= !empty($tmp = $this->request->post('id_subprograma')) ? $tmp : $presupuesto->id_subprograma;
			$presupuesto->id_proyecto 		= !empty($tmp = $this->request->post('id_proyecto')) ? $tmp : $presupuesto->id_proyecto;
			$presupuesto->id_actividad 		= !empty($tmp = $this->request->post('id_actividad')) ? $tmp : $presupuesto->id_actividad;
			$presupuesto->id_obra 			= !empty($tmp = $this->request->post('id_obra')) ? $tmp : $presupuesto->id_obra;
			if($this->request->post('boton_presupuesto') == 'alta') {
				if($presupuesto->validar()){	
					if($presupuesto->alta()) {
						$this->mensajeria->agregar(
						"El Presupuesto fue dado de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
						$redirect =Vista::get_url("index.php/presupuestos/index");
						$this->redirect($redirect);
					} else {
						$err = $presupuesto->errores;
						foreach ($err as $text) {
							$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
						}
					}	
				}
			}

			if($this->request->post('boton_presupuesto') == 'modificacion') {
				if($presupuesto->validar()){	
					if($presupuesto->modificacion()) {
						$this->mensajeria->agregar(
						"El Presupuesto fue modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
						$redirect =Vista::get_url("index.php/presupuestos/index");
						$this->redirect($redirect);	
					}else {
						$err = $presupuesto->errores;
						foreach ($err as $text) {
							$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
						}
					}
				}
			}
		}	
		$parametricos = [
			'saf' 			=> Modelo\Presupuesto::getSaf(),
			'jurisdicciones'=> Modelo\Presupuesto::getJurisdiccion(),
			'ub_geograficas'=> Modelo\Presupuesto::getUbicacionesGeograficas(),
			'programas' 	=> Modelo\Presupuesto::getProgramas(),
			'actividades' 	=> Modelo\Presupuesto::getActividades(),
			'subprogramas' 	=> Modelo\Presupuesto::getSubProgramas($presupuesto->id_programa),
			'proyectos'		=> Modelo\Presupuesto::getProyectos($presupuesto->id_subprograma),
			'obras'			=> Modelo\Presupuesto::getObras($presupuesto->id_proyecto),
		];
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista','presupuesto','parametricos')))->pre_render();
	}

	protected function accion_baja_presupuesto() {
		$presupuesto = Modelo\Presupuesto::obtener($this->request->query('id')); 
		if($presupuesto->id) {
			if ($this->request->post('confirmar')) {
				$res = $presupuesto->baja();
				if ($res) {
					$this->mensajeria->agregar('AVISO: El Presupuesto se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/presupuestos/index');
					$this->redirect($redirect);
				} else {
					$this->mensajeria->agregar('AVISO: No es posible eliminar el Presupuesto Contácte con el Administrador',\FMT\Mensajeria::TIPO_ERROR,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/presupuestos/index');
					$this->redirect($redirect);
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/presupuestos/index');
			$this->redirect($redirect);
		}
		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('presupuesto', 'vista')))->pre_render();
	}

	protected function accion_lista_presupuesto_saf() {
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista')))->pre_render();
	}

	protected function accion_ajax_presupuesto_saf() {
		$this->ajax_presupuesto('saf');
	}

	protected function accion_saf(){
		$saf = Modelo\PresupuestoSaf::obtener($this->request->query('id'));

		if($this->request->post('boton_saf')) {
			$saf->codigo = $this->request->post('codigo');
			$saf->nombre = $this->request->post('nombre');

			if($this->request->post('boton_saf') == 'alta') {
				if($saf->validar()){	
					$saf->alta();
					$this->mensajeria->agregar(
					"El Código SAF fue dado de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/presupuestos/lista_presupuesto_saf");
					$this->redirect($redirect);	
				}else {
					$err = $saf->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}

			if($this->request->post('boton_saf') == 'modificacion') {
				if($saf->validar()){	
					$saf->modificacion();
					$this->mensajeria->agregar(
					"El Código SAF fue modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/presupuestos/lista_presupuesto_saf");
					$this->redirect($redirect);	
				}else {
					$err = $saf->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		}
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista','saf')))->pre_render();
	}

	protected function accion_baja_saf() {
		$saf = Modelo\PresupuestoSaf::obtener($this->request->query('id'));
		if($saf->id) {
			if ($this->request->post('confirmar')) {
				$res = $saf->baja();
				if ($res) {
					$this->mensajeria->agregar('AVISO: El Código SAF se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_saf');
					$this->redirect($redirect);
				} else {
					$this->mensajeria->agregar('AVISO: No es posible eliminar el Código SAF Contácte con el Administrador',\FMT\Mensajeria::TIPO_ERROR,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_saf');
					$this->redirect($redirect);
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_saf');
			$this->redirect($redirect);
		}
		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('saf', 'vista')))->pre_render();
	}

	protected function accion_lista_presupuesto_jurisdicciones() {	 
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista')))->pre_render();
	}

	protected function accion_jurisdicciones(){
		$jurisdiccion = Modelo\PresupuestoJurisdiccion::obtener($this->request->query('id'));

		if($this->request->post('boton_jurisdiccion')) {
			$jurisdiccion->codigo = $this->request->post('codigo');
			$jurisdiccion->nombre = $this->request->post('nombre');

			if($this->request->post('boton_jurisdiccion') == 'alta') {
				if($jurisdiccion->validar()){	
					$jurisdiccion->alta();
					$this->mensajeria->agregar(
					"El Código Jurisdicción fue dado de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/presupuestos/lista_presupuesto_jurisdicciones");
					$this->redirect($redirect);	
				}else {
					$err = $jurisdiccion->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}

			if($this->request->post('boton_jurisdiccion') == 'modificacion') {
				if($jurisdiccion->validar()){	
					$jurisdiccion->modificacion();
					$this->mensajeria->agregar(
					"El Código Jurisdicción fue modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/presupuestos/lista_presupuesto_jurisdicciones");
					$this->redirect($redirect);	
				}else {
					$err = $jurisdiccion->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		}
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista','jurisdiccion')))->pre_render();
	}

	protected function accion_baja_jurisdicciones() {
		$jurisdiccion = Modelo\PresupuestoJurisdiccion::obtener($this->request->query('id'));
		if($jurisdiccion->id) {
			if ($this->request->post('confirmar')) {
				$res = $jurisdiccion->baja();
				if ($res) {
					$this->mensajeria->agregar('AVISO: El Código Jurisdicción se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_jurisdicciones');
					$this->redirect($redirect);
				} else {
					$this->mensajeria->agregar('AVISO: No es posible eliminar el Código Jurisdicción. Contácte con el Administrador',\FMT\Mensajeria::TIPO_ERROR,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_jurisdicciones');
					$this->redirect($redirect);
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_jurisdicciones');
			$this->redirect($redirect);
		}
		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('jurisdiccion', 'vista')))->pre_render();
	}

	protected function accion_ajax_presupuesto_jurisdicciones() {
		$this->ajax_presupuesto('jurisdicciones');
	}

	protected function accion_lista_presupuesto_ub_geograficas() {	 
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista')))->pre_render();
	}
	
	protected function accion_ajax_presupuesto_ub_geograficas() {
		$this->ajax_presupuesto('ub_geograficas');
	}

	protected function accion_ubicaciones_geograficas(){
		$geografica = Modelo\PresupuestoUbGeografica::obtener($this->request->query('id'));

		if($this->request->post('boton_ub_geografica')) {
			$geografica->codigo = $this->request->post('codigo');
			$geografica->nombre = $this->request->post('nombre');

			if($this->request->post('boton_ub_geografica') == 'alta') {
				if($geografica->validar()){	
					$geografica->alta();
					$this->mensajeria->agregar(
					"El Código de Ubicación Geográfica fue dado de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/presupuestos/lista_presupuesto_ub_geograficas");
					$this->redirect($redirect);	
				}else {
					$err = $geografica->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}

			if($this->request->post('boton_ub_geografica') == 'modificacion') {
				if($geografica->validar()){	
					$geografica->modificacion();
					$this->mensajeria->agregar(
					"El Código de Ubicación Geográfica fue modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/presupuestos/lista_presupuesto_ub_geograficas");
					$this->redirect($redirect);	
				}else {
					$err = $geografica->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		}
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista','geografica')))->pre_render();
	}

	protected function accion_baja_ub_geograficas() {
		$geografica = Modelo\PresupuestoUbGeografica::obtener($this->request->query('id'));
		if($geografica->id) {
			if ($this->request->post('confirmar')) {
				$res = $geografica->baja();
				if ($res) {
					$this->mensajeria->agregar('AVISO: El Código de Ubicación Geográfica se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_ub_geograficas');
					$this->redirect($redirect);
				} else {
					$this->mensajeria->agregar('AVISO: No es posible eliminar el Código de Ubicación Geográfica. Contácte con el Administrador',\FMT\Mensajeria::TIPO_ERROR,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_ub_geograficas');
					$this->redirect($redirect);
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_ub_geograficas');
			$this->redirect($redirect);
		}
		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('geografica', 'vista')))->pre_render();
	}

	protected function accion_lista_presupuesto_programas() { 
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista')))->pre_render();
	}
	
	protected function accion_ajax_presupuesto_programas() {
		$this->ajax_presupuesto('programas');
	}

	protected function accion_programas(){
		$programa = Modelo\PresupuestoPrograma::obtener($this->request->query('id'));

		if($this->request->post('boton_programa')) {
			$programa->codigo = $this->request->post('codigo');
			$programa->nombre = $this->request->post('nombre');

			if($this->request->post('boton_programa') == 'alta') {
				if($programa->validar()){	
					$programa->alta();
					$this->mensajeria->agregar(
					"El Código Programa fue dado de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/presupuestos/lista_presupuesto_programas");
					$this->redirect($redirect);	
				}else {
					$err = $programa->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}

			if($this->request->post('boton_programa') == 'modificacion') {
				if($programa->validar()){	
					$programa->modificacion();
					$this->mensajeria->agregar(
					"El Código Programa fue modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/presupuestos/lista_presupuesto_programas");
					$this->redirect($redirect);	
				}else {
					$err = $programa->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		}
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista','programa','agrupamiento')))->pre_render();
	}

	protected function accion_baja_programa() {
	 	$programa = Modelo\PresupuestoPrograma::obtener($this->request->query('id'));
		if($programa->id) {
			if ($this->request->post('confirmar')) {
				$res = $programa->baja();
				if ($res) {
					$this->mensajeria->agregar('AVISO: El Código de Programa se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_programas');
					$this->redirect($redirect);
				} else {
					$this->mensajeria->agregar('AVISO: No es posible eliminar el Código de Programa. Contácte con el Administrador',\FMT\Mensajeria::TIPO_ERROR,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_programas');
					$this->redirect($redirect);
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_programas');
			$this->redirect($redirect);
		}
		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('programa', 'vista')))->pre_render();
	}

	protected function accion_lista_presupuesto_actividades() {	 
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista')))->pre_render();
	}
	
	protected function accion_ajax_presupuesto_actividades() {
		$this->ajax_presupuesto('actividades');
	}

	protected function accion_actividades(){
		$actividad = Modelo\PresupuestoActividad::obtener($this->request->query('id'));

		if($this->request->post('boton_actividades')) {
			$actividad->codigo = $this->request->post('codigo');
			$actividad->nombre = $this->request->post('nombre');

			if($this->request->post('boton_actividades') == 'alta') {
				if($actividad->validar()){	
					$actividad->alta();
					$this->mensajeria->agregar(
					"El Código Actividades fue dado de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/presupuestos/lista_presupuesto_actividades");
					$this->redirect($redirect);	
				}else {
					$err = $actividad->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}

			if($this->request->post('boton_actividades') == 'modificacion') {
				if($actividad->validar()){	
					$actividad->modificacion();
					$this->mensajeria->agregar(
					"El Código Actividades fue modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/presupuestos/lista_presupuesto_actividades");
					$this->redirect($redirect);	
				}else {
					$err = $actividad->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		}
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista','actividad','tramos')))->pre_render();
	}

	protected function accion_baja_actividad() {
		$actividad = Modelo\PresupuestoActividad::obtener($this->request->query('id'));
		if($actividad->id) {
			if ($this->request->post('confirmar')) {
				$res = $actividad->baja();
				if ($res) {
					$this->mensajeria->agregar('AVISO: El Código de Actividad se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_actividades');
					$this->redirect($redirect);
				} else {
					$this->mensajeria->agregar('AVISO: No es posible eliminar el Código de Actividad. Contácte con el Administrador',\FMT\Mensajeria::TIPO_ERROR,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_actividades');
					$this->redirect($redirect);
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_actividades');
			$this->redirect($redirect);
		}
		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('actividad', 'vista')))->pre_render();
	}

	protected function accion_lista_presupuesto_subprogramas() {	 
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista')))->pre_render();
	}
	
	protected function accion_ajax_presupuesto_subprogramas() {
		$this->ajax_presupuesto('subprogramas');
	}

	protected function accion_subprograma(){
		$subprograma = Modelo\PresupuestoSubPrograma::obtener($this->request->query('id'));
		$programas = Modelo\PresupuestoPrograma::getProgramas();

		if($this->request->post('boton_subprograma')) {
			$subprograma->id_programa = $this->request->post('id_programa');
			$subprograma->codigo = $this->request->post('codigo');
			$subprograma->nombre = $this->request->post('nombre');

			if($this->request->post('boton_subprograma') == 'alta') {
				if($subprograma->validar()){	
					$subprograma->alta();
					$this->mensajeria->agregar(
					"El Código de Subprgrama fue dado de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/presupuestos/lista_presupuesto_subprogramas");
					$this->redirect($redirect);	
				}else {
					$err = $subprograma->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}

			if($this->request->post('boton_subprograma') == 'modificacion') {
				if($subprograma->validar()){	
					$subprograma->modificacion();
					$this->mensajeria->agregar(
					"El Código Subprograma fue modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/presupuestos/lista_presupuesto_subprogramas");
					$this->redirect($redirect);	
				}else {
					$err = $subprograma->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		}
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista','subprograma','programas')))->pre_render();
	}

	protected function accion_baja_subprograma() {
	 	$subprograma = Modelo\PresupuestoSubPrograma::obtener($this->request->query('id'));
		if($subprograma->id) {
			if ($this->request->post('confirmar')) {
				$res = $subprograma->baja();
				if ($res) {
					$this->mensajeria->agregar('AVISO: El Código de Subprograma se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_subprogramas');
					$this->redirect($redirect);
				} else {
					$this->mensajeria->agregar('AVISO: No es posible eliminar el Código de Subprograma. Contácte con el Administrador',\FMT\Mensajeria::TIPO_ERROR,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_subprogramas');
					$this->redirect($redirect);
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_subprogramas');
			$this->redirect($redirect);
		}
		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('subprograma', 'vista')))->pre_render();
	}

	protected function accion_lista_presupuesto_proyectos() {	 
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista')))->pre_render();
	}
	
	protected function accion_ajax_presupuesto_proyectos() {
		$this->ajax_presupuesto('proyectos');
	}

	protected function accion_proyecto(){
		$proyecto = Modelo\PresupuestoProyecto::obtener($this->request->query('id'));

		if($this->request->post('boton_proyecto')) {
			$proyecto->id_programa = $this->request->post('id_programa');
			$proyecto->id_subprograma = $this->request->post('id_subprograma');
			$proyecto->codigo = $this->request->post('codigo');
			$proyecto->nombre = $this->request->post('nombre');

			if($this->request->post('boton_proyecto') == 'alta') {
				if($proyecto->validar()){	
					if($proyecto->alta()) {
					$this->mensajeria->agregar(
					"El Código fue dado de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/presupuestos/lista_presupuesto_proyectos");
					$this->redirect($redirect);
					}else{

					$err = $proyecto->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}					}	
				}else {
					$err = $proyecto->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}

			if($this->request->post('boton_proyecto') == 'modificacion') {
				if($proyecto->validar()){	
					$proyecto->modificacion();
					$this->mensajeria->agregar(
					"El Código fue modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/presupuestos/lista_presupuesto_proyectos");
					$this->redirect($redirect);	
				}else {
					$err = $proyecto->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		}
		$programas = Modelo\PresupuestoPrograma::getProgramas();
		$subprogramas = Modelo\PresupuestoSubPrograma::getSubprogramas($proyecto->id_programa);

		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista','proyecto','programas','subprogramas')))->pre_render();
	}

	protected function accion_baja_proyecto() {
	 	$proyecto = Modelo\PresupuestoProyecto::obtener($this->request->query('id'));
		if($proyecto->id) {
			if ($this->request->post('confirmar')) {
				$res = $proyecto->baja();
				if ($res) {
					$this->mensajeria->agregar('AVISO: El Código de Proyecto se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_proyectos');
					$this->redirect($redirect);
				} else {
					$this->mensajeria->agregar('AVISO: No es posible eliminar el Código de Proyecto. Contácte con el Administrador',\FMT\Mensajeria::TIPO_ERROR,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_proyectos');
					$this->redirect($redirect);
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_proyectos');
			$this->redirect($redirect);
		}
		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('proyecto', 'vista')))->pre_render();
	}

	protected function accion_lista_presupuesto_obras() {	 
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista')))->pre_render();
	}
	
	protected function accion_ajax_presupuesto_obras() {
		$this->ajax_presupuesto('obras');
	}

	protected function accion_obra(){
		$obra = Modelo\PresupuestoObra::obtener($this->request->query('id'));
		$proyectos = Modelo\PresupuestoProyecto::getProyectos();

		if($this->request->post('boton_obra')) {
			$obra->id_proyecto = $this->request->post('id_proyecto');
			$obra->codigo = $this->request->post('codigo');
			$obra->nombre = $this->request->post('nombre');

			if($this->request->post('boton_obra') == 'alta') {
				if($obra->validar()){	
					$obra->alta();
					$this->mensajeria->agregar(
					"El Código fue dado de alta exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/presupuestos/lista_presupuesto_obras");
					$this->redirect($redirect);	
				}else {
					$err = $obra->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}

			if($this->request->post('boton_obra') == 'modificacion') {
				if($obra->validar()){	
					$obra->modificacion();
					$this->mensajeria->agregar(
					"El Código fue modificado exitosamente.",\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect =Vista::get_url("index.php/presupuestos/lista_presupuesto_obras");
					$this->redirect($redirect);	
				}else {
					$err = $obra->errores;
					foreach ($err as $text) {
						$this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
					}
				}
			}
		}
		$vista = $this->vista;
		(new Vista($this->vista_default,compact('vista','obra','proyectos')))->pre_render();
	}

	protected function accion_baja_obra() {
	 	$obra = Modelo\PresupuestoObra::obtener($this->request->query('id'));
		if($obra->id) {
			if ($this->request->post('confirmar')) {
				$res = $obra->baja();
				if ($res) {
					$this->mensajeria->agregar('AVISO: El Código de Obra se eliminó de forma exitosa.',\FMT\Mensajeria::TIPO_AVISO,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_obras');
					$this->redirect($redirect);
				} else {
					$this->mensajeria->agregar('AVISO: No es posible eliminar el Código de Obra. Contácte con el Administrador',\FMT\Mensajeria::TIPO_ERROR,$this->clase);
					$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_obras');
					$this->redirect($redirect);
				}
			}
		} else {
			$redirect = Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_obras');
			$this->redirect($redirect);
		}
		$vista = $this->vista;
		(new Helper\Vista($this->vista_default,compact('obra', 'vista')))->pre_render();
	}

	protected function accion_ajax_get_subprogramas(){
		$data	= [
			'subprograma'	=> Modelo\PresupuestoSubPrograma::ajaxSubprogramas($this->request->post('id_programa'))
		];
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

	protected function ajax_presupuesto($tipo_presupuesto){
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

		switch ($tipo_presupuesto) {
				case 'saf':
					$data =  Modelo\PresupuestoSaf::listadoPresupuestoSaf($params);
					break;
				case 'jurisdicciones':
					$data =  Modelo\PresupuestoJurisdiccion::listadoPresupuestoJurisdiccion($params);
					break;
				case 'ub_geograficas':
					$data =  Modelo\PresupuestoUbGeografica::listadoUbGeograficas($params);
					break;
				case 'programas':
					$data =  Modelo\PresupuestoPrograma::listadoProgramas($params);
					break;
				case 'actividades':
					$data =  Modelo\PresupuestoActividad::listadoActividades($params);
					break;
				case 'subprogramas':
					$data =  Modelo\PresupuestoSubPrograma::listadoSubProgramas($params);
					break;
				case 'proyectos':
					$data =  Modelo\PresupuestoProyecto::listadoProyectos($params);
					break;
				case 'obras':
					$data =  Modelo\PresupuestoObra::listadoObras($params);
					break;
				case 'presupuestos':
					$data =  Modelo\Presupuesto::listadoPresupuestos($params);
					break;
			}	
		
		$datos['draw']	= (int) $this->request->query('draw');
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

	protected function accion_ajax_get_proyectos(){
		$params = $this->request->post();
		$data	= [
			'proyectos'	=> Modelo\PresupuestoProyecto::ajaxGetProyectos($params)
		];
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

	protected function accion_ajax_get_obras(){
		$data	= [
			'obras'	=> Modelo\PresupuestoObra::ajaxGetObras($this->request->post('id_proyecto'))
		];
		(new Vista(VISTAS_PATH.'/json_response.php',compact('data')))->pre_render();
	}

}