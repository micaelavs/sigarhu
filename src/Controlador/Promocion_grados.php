<?php
namespace App\Controlador;
use App\Modelo;
use App\Helper\Vista;

class Promocion_grados extends Base {

    protected function accion_index() {
		Modelo\Promocion_grado::convertirIds();
		$listado_promociones	= Modelo\Promocion_grado::listar();

        $vista = $this->vista;
        (new Vista($this->vista_default,compact('vista','listado_promociones')))->pre_render();
    }

    protected function accion_resumen() {
		$id_promocion			= $this->request->query('id');
		Modelo\Promocion_grado::contiene();
		Modelo\Promocion_grado::convertirIds();
		$promocion				= Modelo\Promocion_grado::obtener($id_promocion);
		Modelo\Empleado::contiene(['situacion_escalafonaria', 'persona' => []]);
		$promocion->empleado	= Modelo\Empleado::obtener($promocion->id_empleado, true);

        $vista = $this->vista;
		(new Vista($this->vista_default,compact('vista','promocion')))->pre_render();
    }

	protected function accion_descargar() {
		Modelo\Promocion_grado::contiene();
		$promocion		= Modelo\Promocion_grado::obtener($this->request->query('id'));
		if(empty($promocion->id)){
			$this->redirect(Vista::get_url("index.php/Promocion_grados/index"));
		}
		$archivo		= $promocion->archivo;

		$doc_content	= preg_replace("/[\(\)\,]/", "", $archivo);
		$doc_content	= preg_replace("/\d{14}_/", "", $doc_content);

		$doc			= realpath(BASE_PATH.'/uploads/promocion_grado/'.$archivo);
		if(!file_exists($doc)){
			$this->mensajeria->agregar(
				"El archivo solicitado no existe.",\FMT\Mensajeria::TIPO_ERROR, $this->clase);
			$this->redirect(Vista::get_url("index.php/Promocion_grados/resumen/{$promocion->id}"));
		}
		header("Content-Disposition:attachment;filename=".$doc_content."");
		header("Content-type: application/pdf;");
		header('Content-Length: ' . filesize($doc));
		readfile($doc);
		exit;
    }

    protected function accion_alta(){
        $id_empleado				= $this->request->query('id');
		$id_grupo					= $this->request->query('id2');

        Modelo\Empleado::contiene(['situacion_escalafonaria', 'persona' => []]);
        $empleado           = Modelo\Empleado::obtener($id_empleado, true);
        $simulacion			= Modelo\SimuladorPromocionGrado::obtener($id_empleado, $id_grupo);
        if(empty($simulacion->id)){
            $this->mensajeria->agregar("No existe la simulacion solicitada.",\FMT\Mensajeria::TIPO_ERROR,$this->clase);
            $this->redirect(Vista::get_url("index.php/SimuladorPromocionGrados/agentes_promocionables"));
        }

        $convenios          = Modelo\Contrato::obtenerConvenio($empleado->situacion_escalafonaria->id_modalidad_vinculacion, $empleado->situacion_escalafonaria->id_situacion_revista);
        
        if($this->request->post('boton_promocion_grados') == 'alta') {
            $grados         = \FMT\Helper\Arr::path($convenios, 'tramos.'.$empleado->situacion_escalafonaria->id_tramo.'.grados', []);
            $id_grado       = null;
            foreach ($grados as $grado) {
                if((int)$grado['nombre'] == $simulacion->grado_analisis){
                    $id_grado   = $grado['id'];
                }
            }
            if($id_grado === null){
                $this->mensajeria->agregar("El nuevo grado que quiere aplicar no existe. Debe pedir a <strong>Administracion de RRHH</strong> que lo agregue entre los disponibles.",\FMT\Mensajeria::TIPO_ERROR,$this->clase);
                $this->redirect(Vista::get_url("index.php/Promocion_grados/alta/{$id_empleado}/{$id_grupo}"));
            }

            Modelo\Promocion_grado::contiene();
            $empleado_promocion	= Modelo\Promocion_grado::obtener();
            $empleado_promocion->id_empleado					= $simulacion->id_empleado;
            $empleado_promocion->id_grado				        = $id_grado;
            $empleado_promocion->id_tipo_promocion				= Modelo\Curso::PROMOCION_GRADO;
            $empleado_promocion->id_motivo                      = $simulacion->id_motivo;
            $empleado_promocion->periodo_inicio                 = $simulacion->anio_inicio;
            $empleado_promocion->periodo_fin                    = $simulacion->anio_fin;
            $empleado_promocion->creditos_descontados			= ((int)$simulacion->creditos_requeridos - (int)$simulacion->creditos_reconocidos);
            $empleado_promocion->creditos_reconocidos			= $simulacion->creditos_reconocidos;
            $empleado_promocion->creditos_requeridos			= $simulacion->creditos_requeridos;
            $empleado_promocion->id_empleado_escalafon			= $simulacion->id_empleado_escalafon;
			$empleado_promocion->fecha_promocion				= !empty($temp = $this->request->post('fecha_promocion')) 
				?  \DateTime::createFromFormat('d/m/Y', $temp) : null;
			$empleado_promocion->numero_expediente				= !empty($temp = $this->request->post('numero_expediente')) 
				?  $temp : null;
			$empleado_promocion->acto_administrativo			= !empty($temp = $this->request->post('acto_administrativo')) 
				?  $temp : null;
            $empleado_promocion->archivo						= ($_FILES['archivo']['error'] == UPLOAD_ERR_OK) 
                ? $_FILES['archivo'] : null;

            if($empleado_promocion->validar()){
                if($empleado_promocion->alta()){
                    $this->mensajeria->agregar(
                        "La nueva promoción de grado para el agente {$empleado->persona->apellido} fue exitosa.",\FMT\Mensajeria::TIPO_AVISO, $this->clase);
                } else {
                    $this->mensajeria->agregar(
                        "La nueva promoción de grado para el agente {$empleado->persona->apellido} fallo.",\FMT\Mensajeria::TIPO_ERROR, $this->clase);
                }
                $this->redirect(Vista::get_url("index.php/SimuladorPromocionGrados/agentes_promocionables"));
            } else {
                foreach ((array)$empleado_promocion->errores as $text) {
                    $this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
                }
            }
        }

        $vista = $this->vista;
        (new Vista($this->vista_default,compact('vista','empleado','convenios', 'simulacion')))->pre_render();
    }
}