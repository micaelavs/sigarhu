<?php
namespace App\Controlador;

use App\Helper\Vista;
use App\Modelo;
use FMT\Helper\Arr;

class Auditorias extends Base {
	protected function datos_parametricos(){
		$incluir_estados = [
			Modelo\Empleado::EMPLEADO_INACTIVO	=> Modelo\Empleado::EMPLEADO_INACTIVO,
			Modelo\Empleado::EMPLEADO_ACTIVO	=> Modelo\Empleado::EMPLEADO_ACTIVO,
		];
		return [
			'genero'				 		=> Modelo\Persona::getParam('GENERO'),
			'estado_civil'			 		=> Modelo\Persona::getParam('ESTADO_CIVIL'),
			'tipo_documento'		 		=> Modelo\Persona::getParam('TIPO_DOCUMENTO'),
			'tipo_telefono'			 		=> Modelo\PersonaTelefono::getParam('TIPO_TELEFONO'),
			'nacionalidad'			 		=> json_decode(json_encode(\FMT\Ubicaciones::get_gentilicios()), JSON_UNESCAPED_UNICODE),
			'nivel_organigrama' 	 		=> Modelo\Dependencia::getParam('NIVEL_ORGANIGRAMA'),
			'modalidad_vinculacion'	 		=> Modelo\Contrato::obtenerVinculacionRevista(null,false,true)['modalidad_vinculacion'],
			'tipo_discapacidad' 	 		=> Modelo\Persona::getDiscapacidad(),
			'formacion_tipo_titulo'		    => Modelo\NivelEducativo::getNivelEducativo(),
			'formacion_estado_titulo'	    => Modelo\PersonaTitulo::getParam('ESTADO_TITULO'),
			'familia_de_puesto' 			=> Modelo\Perfil::listarFamiliaPuestos(),
			'denominacion_del_puesto' 		=> Modelo\Perfil::listarDenominacionDelPuesto(),
			'denominacion_de_la_funcion'	=> Modelo\Perfil::listarDenominacionFuncion(),
			'motivo_baja'					=> Modelo\Empleado::getMotivoBaja(),
			'nivel_de_destreza' 			=> Modelo\Perfil::$NIVELES_DESTREZA,
			'nombre_de_puesto' 				=> Modelo\Perfil::listarNombrePuestos(),
			'niveles_complejidad' 			=> Modelo\Perfil::$NIVELES_COMPLEJIDAD,
			'niveles_puesto_supervisa' 		=> Modelo\Perfil::$NIVELES_PUESTO_SUPERVISA,
			'turno'							=> Modelo\Empleado::getParam('TURNO'),
			'estado_administracion'			=> array_intersect_key(Modelo\Empleado::$TIPO_ESTADOS_EMPLEADOS, $incluir_estados),
			'estado_comision'				=> Modelo\Empleado::getParam('ESTADOS'),
			'comisiones'					=> Modelo\Comision::listar(true),
			'licencias_especiales' 			=> Modelo\LicenciaEspecial::getLicenciasEspeciales(),
			'tipo_presentacion' 	 		=> Modelo\Anticorrupcion::getParam('TIPO_DJ'),
			'id_sindicato' 	 				=> Modelo\Empleado::getSindicato(),
			'obras_sociales'				=> Modelo\Empleado::getObraSociales(),
			'seguros_vida'					=> Modelo\Empleado::getSegurosVida(),
			'parentesco'					=> Modelo\GrupoFamiliar::getParam('PARENTESCO'),
			'opcion_sino'					=> Modelo\GrupoFamiliar::getParam('OPCION_SINO'),
			'porcentaje_desgrava'			=> Modelo\GrupoFamiliar::getParam('PORCENTAJE_DESGRAVA'),
			'titulo'						=> Modelo\Titulo::listar(),
			'cursos'						=> Modelo\EmpleadoCursos::getCursos(),
			'formularios'					=> Modelo\Evaluacion::getParam('formularios'),
			'evaluaciones'					=> Modelo\Evaluacion::getParam('resultados')
        ];
	}

	protected function accion_index(){
		if(!$this->request->is_ajax()){
			$solapas		= \App\Helper\Bloques::$SOLAPAS;
			$parametricos	= $this->datos_parametricos();

			$usuarios	= \App\Modelo\Usuario::listar();
			$aux		= [];
			foreach ($usuarios as $user) {
				$aux[$user->idUsuario]	= ['id' => $user->idUsuario, 'nombre' => $user->user.' - '.$user->nombre.' '.$user->apellido ,'borrado'=>'0'];
			}
			$usuarios	= $aux;

			$registros	= Modelo\Auditoria::listar();
			$vista		= $this->vista;

			(new Vista($this->vista_default,compact('vista', 'registros', 'solapas', 'parametricos', 'usuarios')))->pre_render();
		} else {
			$dataTable_columns	= $this->request->query('columns');
			$orders				= [];
			foreach($orden = (array)$this->request->query('order') as $i => $val){
				$orders[]	= [
					'campo'	=> (!empty($tmp = $orden[$i]) && !empty($dataTable_columns) && is_array($dataTable_columns[0]))
						? $dataTable_columns[ (int)$tmp['column'] ]['data']	: 'fecha_operacion',
					'dir'	=> !empty($tmp = $orden[$i]['dir'])
						? $tmp : 'desc',
				];
			}
			$params				= [
				'order'		=> $orders,
				'start'		=> !empty($tmp =$this->request->query('start'))
					? $tmp : 0,
				'lenght'	=> !empty($tmp = $this->request->query('length'))
					? $tmp : 10,
				'search'	=> !empty($tmp = $this->request->query('search'))
					? $tmp['value'] : '',
				'filtros'	=> [
					'fecha_operacion_desde'	=> \DateTime::createFromFormat('d/m/Y H:i:s', $this->request->query('fecha_operacion_desde')),
					'fecha_operacion_hasta'	=> \DateTime::createFromFormat('d/m/Y H:i:s', $this->request->query('fecha_operacion_hasta')),
					'id_usuario'			=> $this->request->query('id_usuario'),
				],
			];
			$data			= Modelo\Auditoria::ajaxPesquisa($params);
			foreach ($data['data'] as &$value) {
				$value['acciones']	= '';
			}
			$data['draw']	= (int) $this->request->query('draw');
			$this->json->setData($data);
			$this->json->render();
		}
	}

	protected function  accion_json_detalle_pesquisa(){
        $data	= Modelo\Auditoria::queryComparacion($this->request->query('pesquisa'), $this->request->query('id'));
		if(!empty($data['consulta'])){
			$this->mapearCampos($data['consulta']);
		}
		$tipo = ['A'=>'alta','B'=>'baja','M'=>'modificacion'];
		if(!empty($data['anterior'])){
			$user = \App\Modelo\Usuario::obtener($data['anterior'][1]['valor']);
			$data['ver_anterior']['id'] 		= $data['anterior'][0]['valor'];
			$temp = \DateTime::createFromFormat('Y-m-d H:i:s', $data['anterior'][2]['valor']);
			$data['ver_anterior']['fecha']		= ($temp) ? $temp->format('d/m/Y H:i:s') :'';
			$data['ver_anterior']['usuario']	= is_null($user->nombre) ? '' : $user->nombre;
			$data['ver_anterior']['pesquisa']	= $this->request->query('pesquisa'); 	
			$data['ver_anterior']['tipo_operacion']	= Arr::get($tipo, $data['anterior'][3]['valor'],'--');	
			$this->mapearCampos($data['anterior']);

		}
		$this->json->setData($data);
		$this->json->render();
	}
/**
 * Recibe por referencia un array de datos devuelto por Auditoria::queryComparacion().
 * Se encarga de darle un formato digno para mostrar como html.
 *
 * Los campos con flag hidden, son eliminados del array porque solo se usan para mapear los campos.
 * @param array $data
 * @return void
 */
	private function mapearCampos(&$data){
		static	$tmp	= [];
		foreach ($data as &$value) {
			if(empty($tmp['map_fields'])){
				$tmp['map_fields'] = Arr::path($data, "*.campo");
			}
			if(empty($tmp['convenios'])){
				$id_modalidad_vinculacion	= !empty($tmp['id_modalidad_vinculacion']) ? $tmp['id_modalidad_vinculacion'] : $tmp['map_fields'];
				if(is_array($id_modalidad_vinculacion)){
					$id_modalidad_vinculacion	= array_search('id_modalidad_vinculacion', $id_modalidad_vinculacion);
					$id_modalidad_vinculacion	= $tmp['id_modalidad_vinculacion']	= $data[$id_modalidad_vinculacion]['valor'];
				}
				$id_situacion_revista	= !empty($tmp['id_situacion_revista']) ? $tmp['id_situacion_revista'] : $tmp['map_fields'];
				if(is_array($id_situacion_revista)){
					$id_situacion_revista	= array_search('id_situacion_revista', $id_situacion_revista);
					$id_situacion_revista	= $tmp['id_situacion_revista']	= $data[$id_situacion_revista]['valor'];
				}
				$tmp['convenios']	= Modelo\Contrato::obtenerConvenio($id_modalidad_vinculacion, $id_situacion_revista);
			}
			if(!isset($value['map'])){
				continue;
			}
			switch ($value['map']) {
                case 'map_foto_persona': 
                break;
                case 'map_desgrava_afip': 
				break;
				case 'map_antiguedad_adm_publica':
					if(!empty($value['valor'])){
						$json	= json_decode($value['valor'], true);
						$value['valor']	= 'Año: '.$json['anio'].' Mes: '.$json['mes'];
					}

				break;
				case 'map_tipo_embargo': 
					if(!empty($value['valor']) || $value['valor'] === 0){
						$value['valor']	= Arr::path(Modelo\Embargo::getParam('TIPO_EMBARGO'), "{$value['valor']}.nombre");
					}
				break;
				case 'map_id_dependencia':
					if(!empty($value['valor']) || $value['valor'] === 0){
						$value['valor']	= Arr::path((array)Modelo\Dependencia::obtener($value['valor']), 'nombre');
					}
				break;
				case 'map_id_dep_informal':
					if(!empty($value['valor']) || $value['valor'] === 0){
						$id_dep_informal	= !empty($tmp['id_dep_informal']) ? $tmp['id_dep_informal'] : $tmp['map_fields'];
						if(is_array($id_dep_informal)){
							$id_dep_informal	= array_search('id_dep_informal', $id_dep_informal);
							$id_dep_informal	= $tmp['id_dep_informal']	= $data[$id_dep_informal]['valor'];
						}
						$dependencia	= Modelo\Dependencia::getPadre($id_dep_informal);
						$dependencia	= Arr::path($dependencia->dependencias_informales, $id_dep_informal);
						$value['valor']	= is_object($dependencia) ? $dependencia->nombre : $value['valor'];
					}
				break;
				case 'map_exc_art_14':
					if(!empty($value['valor'])){
						$json	= json_decode($value['valor'], true);
						$exc_art_14	= Modelo\Empleado::getParam('EXCEPCION_ART_14');
						$html	= '';
						foreach ($json as $exc) {
							$html	.= '<b> * </b> '.Arr::path($exc_art_14, "{$exc['excepcion']}.nombre", $exc['excepcion']).'<br />';
						}
						$value['valor']	= $html;
					}
				break;
				case 'map_situacion_revista':
					if(!empty($value['valor']) || $value['valor'] === 0){
						$id_modalidad_vinculacion	= (!empty($tmp['id_modalidad_vinculacion'])) ? $tmp['id_modalidad_vinculacion'] : $tmp['map_fields'];
						if(is_array($id_modalidad_vinculacion)){
							$id_modalidad_vinculacion	= array_search('id_modalidad_vinculacion', $id_modalidad_vinculacion);
							$id_modalidad_vinculacion	= $tmp['id_modalidad_vinculacion']	= $data[$id_modalidad_vinculacion]['valor'];
						}
						$situacion_revista	= Modelo\Contrato::obtenerVinculacionRevista($id_modalidad_vinculacion)['situacion_revista'];
						$value['valor']		= Arr::path($situacion_revista, "{$value['valor']}.nombre", $value['valor']);
					}
				break;
				case 'map_id_nivel':
					if(!empty($value['valor']) || $value['valor'] === 0){
						$value['valor']	= Arr::path($tmp['convenios'], "agrupamientos.*.niveles.{$value['valor']}.nombre");
					}
				break;
				case 'map_id_grado':
					if(!empty($value['valor']) || $value['valor'] === 0){
						$value['valor']	= Arr::path($tmp['convenios'], "tramos.*.grados.{$value['valor']}.nombre");
					}
				break;
				case 'map_id_tramo': 
					if(!empty($value['valor']) || $value['valor'] === 0){
						$value['valor']	= Arr::path($tmp['convenios'], "tramos.{$value['valor']}.nombre");
					}
				break;
				case 'map_id_agrupamiento': 
					if(!empty($value['valor']) || $value['valor'] === 0){
						$value['valor']	= Arr::path($tmp['convenios'], "agrupamientos.{$value['valor']}.nombre");
					}
				break;
				case 'map_id_funcion_ejecutiva': 
					if(!empty($value['valor']) || $value['valor'] === 0){
						$value['valor']	= Arr::path($tmp['convenios'], "funciones_ejecutivas.{$value['valor']}.nombre");
					}
				break;
                case 'map_horarios':
                    if(!empty($value['valor']) || $value['valor'] === 0){
                        $horario    = json_decode($value['valor'], true);
                        if(empty($horario)){
                            break;
                        }
                        $dias       = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
                        $html       = '<div>';
                        foreach ($horario as $k => $v) {
                            if(!empty($v[0])){
                                $html   .= "<p><strong>$dias[$k]: </strong>$v[0] a $v[1]</p>";
                            } else {
                                $html   .= "<p><strong>$dias[$k]: </strong></p>";
                            }
                        }
                        $html       .= '</div>';
                        $value['valor'] = $html;
                    }
				break;
				case 'map_id_motivo':
					if(!empty($value['valor']) || $value['valor'] === 0){
						$aux	= Modelo\Empleado::getMotivoBaja();
						$value['valor']	= Arr::path($aux, "{$value['valor']}.nombre");
					}
				break;
				case 'map_id_ubicacion':
					if(!empty($value['valor']) || $value['valor'] === 0){
						$ubicacion		= Modelo\Ubicacion::obtener($value['valor']); 
						$value['valor']	= '';
						$value['valor']	.= "<strong>Edificio:</strong> {$ubicacion->nombre} <br />";
						$value['valor']	.= "<strong>Calle:</strong> {$ubicacion->calle} - ";
						$value['valor']	.= "<strong>Número:</strong> {$ubicacion->numero} - ";
						$value['valor']	.= "<strong>Piso:</strong> {$ubicacion->piso} - ";
						$value['valor']	.= "<strong>Oficina:</strong> {$ubicacion->oficina}";
					}
				break;
				case 'map_provincia':
					if(!empty($value['valor']) || $value['valor'] === 0){
						$tmp['id_provincia']	= $value['valor'];
						$provincia		= json_decode(json_encode(\FMT\Ubicaciones::get_regiones('AR')), true);
						$value['valor']	= Arr::path($provincia, "{$value['valor']}.nombre", $value['valor']);
					}
				break;
				case 'map_localidad':
					if(!empty($value['valor']) || $value['valor'] === 0){
						$id_provincia	= isset($tmp['id_provincia']) ? $tmp['id_provincia'] : $tmp['map_fields'];
						if(is_array($id_provincia)){
							$id_provincia	= array_search('id_provincia', $id_provincia);
							$id_provincia	= $tmp['id_provincia']	= $data[$id_provincia]['valor'];
						}
						$provincia		= json_decode(json_encode(\FMT\Ubicaciones::get_localidades($id_provincia)), true);
						$value['valor']	= Arr::path($provincia, "{$value['valor']}.nombre", $value['valor']);
					}
				break;
				case 'map_id_bloque':
					if(!empty($value['valor']) || $value['valor'] === 0){
						$value['valor']	= Arr::path(\App\Helper\Bloques::$SOLAPAS, "{$value['valor']}.nombre");
					}
                break;
				case 'map_id_tipo':
					if (!empty($value['valor']) || $value['valor'] === 0) {
						$aux	= Modelo\Documento::listar_tipo();
						$value['valor']	= Arr::path($aux, "{$value['valor']}.nombre");
					}
                break;
                case 'map_id_presupuesto':
                    if(!empty($value['valor']) || $value['valor'] === 0){
						$presupuesto	= Modelo\Presupuesto::obtener($value['valor']);
						$aux	= [
							'saf' 			=> Arr::path(Modelo\Presupuesto::getSaf(), "{$presupuesto->id_saf}.nombre"),
							'jurisdicciones'=> Arr::path(Modelo\Presupuesto::getJurisdiccion(), "{$presupuesto->id_jurisdiccion}.nombre"),
							'ub_geograficas'=> Arr::path(Modelo\Presupuesto::getUbicacionesGeograficas(), "{$presupuesto->id_ubicacion_geografica}.nombre"),
							'programas' 	=> Arr::path(Modelo\Presupuesto::getProgramas(), "{$presupuesto->id_programa}.nombre"),
							'actividades' 	=> Arr::path(Modelo\Presupuesto::getActividades(), "{$presupuesto->id_actividad}.nombre"),
							'subprogramas' 	=> Arr::path(Modelo\Presupuesto::getSubProgramas($presupuesto->id_programa), "{$presupuesto->id_subprograma}.nombre"),
							'proyectos'		=> Arr::path(Modelo\Presupuesto::getProyectos($presupuesto->id_subprograma), "{$presupuesto->id_proyecto}.nombre"),
							'obras'			=> Arr::path(Modelo\Presupuesto::getObras($presupuesto->id_proyecto), "{$presupuesto->id_obra}.nombre"),
						];
                        $html	= '<div style="padding-left: 20px;">';
                        $html   .= "<p><strong>Servicio Administrativo Financiero: </strong><br/> {$aux['saf']} </p>";
                        $html   .= "<p><strong>Jurisdicción: </strong><br/> {$aux['jurisdicciones']} </p>";
                        $html   .= "<p><strong>Ubicación Geográfica: </strong><br/> {$aux['ub_geograficas']} </p>";
                        $html   .= "<p><strong>Programa: </strong><br/> {$aux['programas']} </p>";
                        $html   .= "<p><strong>Subprograma: </strong><br/> {$aux['subprogramas']} </p>";
                        $html   .= "<p><strong>Proyecto: </strong><br/> {$aux['proyectos']} </p>";
                        $html   .= "<p><strong>Actividad: </strong><br/> {$aux['actividades']} </p>";
                        $html   .= "<p><strong>Obra: </strong><br/> {$aux['obras']} </p>";
						$html   .= '</div>';
						$value['valor']	= $html;
                    }
				break;
				case 'map_designacion_transitoria': 
					if(!empty($value['valor']) || $value['valor'] === 0){
						$value['valor']	= Arr::path(Modelo\Designacion_transitoria::$TIPO_DESIGNACION, "{$value['valor']}.nombre");
					}
				break;
                case 'map_organismos':
                    if(!empty($value['valor']) || $value['valor'] === 0){
                        $organismo	= Modelo\OtroOrganismo::obtener($value['valor']);
						$value['valor']	= $organismo->nombre;
                    }
				break;
				case 'map_formacion_cursos':
                    if(!empty($value['valor']) || $value['valor'] === 0){
                   		$value['valor']	= Arr::path((array)Modelo\EmpleadoCursos::getCursos($value['valor']),"{$value['valor']}.nombre_curso"); 	
                       
                    }
				break;
				case 'map_formulario':
                    if(!empty($value['valor']) || $value['valor'] === 0){
                    	$value['valor'] =  Arr::path((array)Modelo\Evaluacion::getParam('formularios'),"{$value['valor']}.nombre");
                    }
				break;
				case 'map_evaluacion':
                    if(!empty($value['valor']) || $value['valor'] === 0){
                   		$value['valor'] =  Arr::path((array)Modelo\Evaluacion::getParam('resultados'),"{$value['valor']}.nombre"); 	
                    }
				break;
			}
		}
		foreach ($data as $key => &$value) {
			if(isset($value['flag']) && $value['flag'] == 'hidden'){
				unset($data[$key]);
				continue;
			}
		}
	}
}
