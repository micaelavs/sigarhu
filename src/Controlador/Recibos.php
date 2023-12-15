<?php

namespace App\Controlador;

use App\Modelo\Empleado;
use FMT\Configuracion;
use App\Helper\Vista;

class Recibos extends Base
{

    public function  search_files($dir, &$files, &$mes, &$anio, $documento)
    {

        if (is_dir($dir)) {
            if ($gd = opendir($dir)) {
                while (($file = readdir($gd)) !== false) {

                    if ($file != '.' and $file != '..') {

                        if (is_dir($dir . '/' . $file)) {
                            $mes = $file;
                            $this->search_files($dir . '/' . $file, $files, $mes, $anio, $documento);
                        } else {

                            if (is_file($dir . '/' . $file)) {

                                $config = Configuracion::instancia();

                                $documentoArchivo = substr($file, 0, 8);


                                if ($documento == $documentoArchivo) {
                                    $tipoRec = substr($file, 12, 1);
                                    $nombreRecibo = "";

                                    if ($tipoRec == 1) {
                                        $nombreRecibo = "Recibo de Haberes Mensual";
                                    } elseif ($tipoRec == 9) {
                                        $nombreRecibo = "Aguinaldo";
                                    } else {
                                        $nombreRecibo = "Otros Haberes";
                                    }
                                    $files[] = ['documento' => $documentoArchivo, 'nombreArchivo' => $file, 'mes' => $mes, 'anio' => $anio, 'tipoRec' => $nombreRecibo, 'tipoRecId' => $tipoRec];
                                }
                            }
                        }
                    }
                }
            }
            closedir($gd);
        }
    }


    public function accion_index()
    {
        $contiene    = ['persona', 'situacion_escalafonaria'];
        Empleado::contiene($contiene);
        $cuit = $this->request->query('id'); 
        $empleado    = Empleado::obtener($cuit);
        if ($empleado->situacion_escalafonaria->id_modalidad_vinculacion == 2) {
            $convenio_ur = (array) \App\Modelo\ConvenioUR::listar();
            $unidad_retributiva = '';
            foreach ($convenio_ur as $key => $value) {
                if (
                    $value->id_nivel == $empleado->situacion_escalafonaria->id_nivel
                    && $value->id_grado == $empleado->situacion_escalafonaria->id_grado
                ) {
                    $unidad_retributiva = ['min' => $value->unidad->minimo, 'max' => $value->unidad->maximo, 'monto' => $value->monto->monto];
                }
            }
            $unidad_retributiva = (empty($unidad_retributiva)) ? ['min' => 'S/D', 'max' => 'S/D'] : $unidad_retributiva;
            $ur = $empleado->situacion_escalafonaria->unidad_retributiva;
            $ur = (empty($ur)) ? 'S/D' : $ur;
            $this->vista_default = VISTAS_PATH . '/recibos/remuneracion_ur.php';
            $vista = $this->vista;
            $volver    = Vista::get_url('index.php/legajos/agentes');
            (new Vista($this->vista_default, compact('empleado', 'vista', 'unidad_retributiva', 'ur', 'volver')))->pre_render();

        } else {
            $recibosEncontrados = [];
            $mes = "";
            $anio = ($this->request->post('anio')) ? $this->request->post('anio') : date('Y');
            $documento = $empleado->persona->documento;
            if (isset($documento) && !empty($documento)) {
                //if ($this->request->post('boton_consultar') == 'consultar') {
                    if ($anio) {
                        $config = Configuracion::instancia();
                        $dir = $config['recibos']['path_2'] . "/" . $anio;
                        if (is_dir($dir)) {
                            $this->search_files($dir, $recibosEncontrados, $mes, $anio, $documento);
                            if (empty($recibosEncontrados)) {
                                $this->mensajeria->agregar(
                                    "No hay recibos procesados para el año seleccionado",
                                    \FMT\Mensajeria::TIPO_ERROR,
                                    $this->clase
                                );
                            }
                        } else {
                            $this->mensajeria->agregar(
                                "No hay recibos procesados para el año seleccionado",
                                \FMT\Mensajeria::TIPO_ERROR,
                                $this->clase
                            );
                        }
                    } else {
                        $this->mensajeria->agregar(
                            "Escriba el año para consultar recibos de sueldo",
                            \FMT\Mensajeria::TIPO_ERROR,
                            $this->clase
                        );
                    }
               // }
            } else {
                $this->mensajeria->agregar(
                    "No hay datos del usuario, comuniquese con RRHH",
                    \FMT\Mensajeria::TIPO_ERROR,
                    $this->clase
                );
            }
            $vista = $this->vista;
            $usuario = $this->_user->id;
            $meses = $this->meses('id');
            uasort($recibosEncontrados, function($a,$b) use ($meses){
                if ($meses[$a['mes']] == $meses[$b['mes']]) {
                    return 0;
                }
                return ($meses[$a['mes']] > $meses[$b['mes']]) ? -1 : 1;
            });
            $meses = $this->meses();
            (new Vista($this->vista_default, compact('usuario','cuit','vista', 'recibosEncontrados', 'config','meses')))->pre_render();
        }    
    }

    protected function meses($tipo=false)
    {
        if(!$tipo){
            $meses = [
                '1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio', '8' => 'Agosto', '9' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
            ];
        } else {
            $meses = [
                'Enero' => 1, 'Febrero' => 2, 'Marzo' => 3, 'Abril' => 4, 'Mayo' => 5, 'Junio' => 6, 'Julio' => 7, 'Agosto' => 8,'Septiembre' => 9, 'Octubre' => 10, 'Noviembre' => 11, 'Diciembre' => 12            
            ];
        }
        return $meses;
    }

    protected function accion_descarga()
    {
        $config = Configuracion::instancia();
        $meses = $this->meses();

        $documento = $this->request->query('id');
        if (!empty($documento)) {
            $datos = base64_decode($documento);
            $datosDecryp = openssl_decrypt($datos, $config['recibos']['methodEncrypted'], $config['recibos']['passEncrypted']);
            $datosDecode = json_decode($datosDecryp, true);
            if ($datosDecode['usuario'] == $this->_user->id) {
                $mes2dig = array_search($datosDecode['mes'], $meses);
                if ($mes2dig < 10) {
                    $mes2dig = "0" . $mes2dig;
                }

                $archivoEncontrado = glob($config['recibos']['path_2'] . "/" . $datosDecode['anio'] . "/" . $datosDecode['mes'] . "/" . $datosDecode['documento'] . substr($datosDecode['anio'], 2, 2) . $mes2dig . $datosDecode['tipoRecId'] . "*.pdf");

                if (!empty($archivoEncontrado) && is_file($archivoEncontrado[0])) {
                    $nombre = $datosDecode['documento'] . "_" . $datosDecode['mes'] . "_" . $datosDecode['anio'] . ".pdf";
                    header("Content-Disposition: inline; filename='" . $nombre . "'");
                    header("Content-type: application/pdf");
                    readfile($archivoEncontrado[0]);
                }
            } else {
                $this->mensajeria->agregar("No esta autorizado a visualizar el archivo", \FMT\Mensajeria::TIPO_ERROR, $this->clase);
            }
        }
    }
}