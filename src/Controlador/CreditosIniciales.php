<?php
namespace App\Controlador;
use App\Modelo;
use App\Helper\Vista;

class CreditosIniciales extends Base {
    public function accion_listar(){
        $vista = $this->vista;
        $listado    = Modelo\EmpleadoCreditoInicial::listar();
        $empleado       = Modelo\Empleado::obtener($this->request->query('id'));
        (new Vista($this->vista_default, compact('vista', 'listado', 'empleado')))->pre_render();
    }

    public function accion_alta(){
        $vista                                  = $this->vista;
        $creditos                               = Modelo\EmpleadoCreditoInicial::obtener(null);
        if($this->request->post('boton_creditos') == 'alta') {
            Modelo\Empleado::contiene(['persona' => []]);
            $cuit_emple                             = $this->request->post('cuit');
            $creditos->empleado->cuit               = $cuit_emple;
            $empleado                               = Modelo\Empleado::obtener($cuit_emple);
            $creditos->empleado->nombre_apellido    = $empleado->persona->nombre." ". $empleado->persona->apellido;      
            $creditos->id_empleado                  =  $empleado->id; 
            $creditos->fecha_considerada            = ($temp = $this->request->post('fecha_desde')) ?  \DateTime::createFromFormat('d/m/Y', $temp) : null;
            $creditos->creditos                     = ($temp = $this->request->post('creditos')) ?  $temp : null;
            $creditos->descripcion                  = ($temp = $this->request->post('descripcion')) ?  $temp : null;

            if($creditos->validar()){
                    $alta = $creditos->alta();
                    if($alta){
                            $this->mensajeria->agregar(
                        "El crédito inicial del empleado <strong>{$empleado->persona->nombre}</strong> {$empleado->persona->apellido}</strong> fue cargado exitosamente.",
                        \FMT\Mensajeria::TIPO_AVISO,
                        $this->clase
                        );
                    $redirect =Vista::get_url("index.php/CreditosIniciales/listar");
                    $this->redirect($redirect);
                    }else{
                        $this->mensajeria->agregar(
                        "Ha ocurrido un error al cargar el crédito.",
                        \FMT\Mensajeria::TIPO_ERROR,
                        $this->clase);
                    }
                }else {
                    foreach ((array)$creditos->errores as $text) {
                        $this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
                    }
                }
        }    

        (new Vista($this->vista_default, compact('vista','creditos')))->pre_render();
    }

    protected function accion_buscarAgente(){
        if($this->request->is_ajax()){
            $data = $this->_get_agente($this->request->post('cuit'));
            $this->json->setData($data);
            $this->json->render();
          
        }
    }   

    private function _get_agente($cuit = null) {
        if (!is_null($cuit)) {
            $empleado  =  Modelo\Empleado::obtener($cuit);
            }       
        return $empleado;
    }
  
    public function accion_modificacion(){
        $vista = $this->vista;
        $creditos                               = Modelo\EmpleadoCreditoInicial::obtener($this->request->query('id'));
        $cuit_emple                             = $creditos->empleado->cuit;
        Modelo\Empleado::contiene(['persona' => []]);
        $empleado                               = Modelo\Empleado::obtener($cuit_emple);

        if($this->request->post('boton_creditos') == 'modificacion') {
            $creditos->empleado->cuit               = $cuit_emple;
            $creditos->empleado->nombre_apellido    = $empleado->persona->nombre." ". $empleado->persona->apellido;      
            $creditos->id_empleado                  =  $empleado->id; 
            $creditos->fecha_considerada            = ($temp = $this->request->post('fecha_desde')) ?  \DateTime::createFromFormat('d/m/Y', $temp) : $creditos->fecha_considerada;
            $creditos->creditos                     = ($temp = $this->request->post('creditos')) ?  $temp : $creditos->creditos;
            $creditos->descripcion                  =  ($temp = $this->request->post('descripcion')) ?  $temp : $creditos->descripcion;

            if($creditos->validar()){
                if($creditos->modificacion()){
                        $this->mensajeria->agregar(
                            "El crédito del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> ha sido modificado correctamente",
                            \FMT\Mensajeria::TIPO_AVISO,
                        $this->clase);
                        $redirect =Vista::get_url("index.php/CreditosIniciales/listar");
                        $this->redirect($redirect); 
                }else{
                    $this->mensajeria->agregar(
                        "No se pudo modificar el crédito del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong>",
                        \FMT\Mensajeria::TIPO_ERROR,
                    $this->clase);
                }
            } else {
                foreach ((array)$creditos->errores as $text) {
                    $this->mensajeria->agregar($text, \FMT\Mensajeria::TIPO_ERROR, $this->clase);
                }
            }
        }
         
        (new Vista($this->vista_default, compact('vista', 'creditos')))->pre_render();
    }

    public function accion_baja(){
        $vista = $this->vista;
        $creditos = Modelo\EmpleadoCreditoInicial::obtener($this->request->query('id'));
        $cuit_emple = $creditos->empleado->cuit;
        Modelo\Empleado::contiene(['persona' => []]);
        $empleado = Modelo\Empleado::obtener($cuit_emple);

        if (!empty($creditos->id)){
            if($this->request->post('confirmar')){
                if($creditos->id){
                    if ($creditos->baja()){
                            $this->mensajeria->agregar(
                            "El crédito del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong> ha sido eliminado correctamente",
                            \FMT\Mensajeria::TIPO_AVISO,
                            $this->clase);
                    } else {
                        $this->mensajeria->agregar(
                        "No se ha podido eliminar el crédito del agente <strong>{$empleado->persona->nombre} {$empleado->persona->apellido}</strong>",
                        \FMT\Mensajeria::TIPO_ERROR,
                        $this->clase);
                    }
                    $redirect =Vista::get_url("index.php/CreditosIniciales/listar");
                    $this->redirect($redirect);
                }
            } 
        }

        (new Vista($this->vista_default, compact('vista','creditos', 'empleado')))->pre_render();
    }
}