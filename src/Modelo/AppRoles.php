<?php 
namespace App\Modelo;
use FMT\Roles;
use FMT\Usuarios;

class AppRoles extends Roles {
	const PADRE_BASE = 0;
	const ROL_DEFAULT = 1;
	const ROL_ADMINISTRACION = 2;
	const ROL_ADMINISTRACION_RRHH = 3;
	/** @deprecated v12.0.9 */
	const ROL_DESARROLLO_RRHH = 4;
	const ROL_CONTROL_RRHH = 5;
	const ROL_CONVENIOS = 6;
	const ROL_LIQUIDACIONES = 7;
	CONST ROL_SUPER_RRHH =9;
/** @deprecated v8.0 */
	const ROL_IRI = 8;

	static $rol;
	static $excepcion_permisos = true;
	static $permisos	= [
		self::PADRE_BASE => [
			'nombre'	=> 'Padre',
			'inicio'	=> ['control' => 'error','accion' => 'index'],
			'modalidades_vinculacion_autorizadas' =>['1','3','4','5','6'],
			'situaciones_revista_autorizadas' =>['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18'],
			'atributos' => [
				'tab_visible' =>[
					'presupuesto' =>	['Presupuestos'		=>	['alta' => 1]],
					'anticorrupcion' => ['Anticorrupcion'	=>	['alta' => 0]],
					'embargo' =>		['Embargos'			=>	['index' => 1]]
				 ],
				'campos' => [
					'modalidad_vinculacion' =>  [
						'escalafon' =>[
							'alta' => 0,
							'modificar' => 0
							],
					],
					'situacion_revista' =>  [
						'escalafon' =>[
							'alta' => 0,
							'modificar' => 0
						],
					],
					'unidad_retributiva' =>  [
						'escalafon' =>[
							'alta' => 0,
							'modificar' => 0
						],
					],
					'compensacion_geografica' =>  [
						'escalafon' =>[
							'alta' => 0,
							'modificar' => 0
						],
					],
					'ultimo_cambio_nivel' =>  [
						'escalafon' =>[
							'alta' => 0,
							'modificar' => 0
							],
					],
					'fecha_vigencia_mandato' =>  [
						'escalafon' =>[
							'alta' => 0,
							'modificar' => 0
							],
					],
					'id_sindicato' =>  [
						'escalafon' =>[
							'alta' => 0,
							'modificar' => 0
							],
					],
					'delegado_gremial' =>  [
						'escalafon' =>[
							'alta' => 0,
							'modificar' => 0
							],
					],
					'exc_art_14' =>  [
						'escalafon' =>[
							'alta' => 0,
							'modificar' => 0
							],
					],
					'formacion' =>  [
						'escalafon' =>[
							'alta' => 0,
							'modificar' => 0
							],
					],
					'antiguedad_adm_publica' =>  [
	                    'antiguedad' =>[
	                        'alta' => 0,
	                        'modificar' => 0
	                        ],
	                ],
	                'fecha_ingreso_mtr' =>  [
	                    'antiguedad' =>[
	                        'alta' => 0,
	                        'modificar' => 0
	                    ],
	                ],
	                'contador_antiguedad' =>  [
	                    'antiguedad' =>[
	                        'alta' => 0,
	                        'modificar' => 0
	                    ],
	                ],
					'tipo_discapacidad' => [
						'varios' => [
							 'alta' => 0, 
							 'modificar' => 0 
							] 
						], 
					'cud' => [
						'varios' => [ 
							'alta' => 0, 
							'modificar' => 0 
						] 
					], 
					'credencial' => [
					 'varios' => [
					 	'alta' => 0, 
					 	'modificar' => 0 
					 ] 
					], 
					'observaciones' => [ 
						'varios' => [ 
							'alta' => 0, 
							'modificar' => 0 
						] 
					],
					'nivel'	=>  [
						'escalafon' =>[
							'alta' => 0,
							'modificar' => 0
						]
					],
					'grado'	=> [
						'escalafon' =>[
							'alta' => 0,
							'modificar' => 0
						]
					],
					'grado_liquidacion'	=> [
						'escalafon' =>[
							'alta' => 0,
							'modificar' => 0
						]
					],
					'compensacion_transitoria'	=>   [
						'escalafon' =>[
							'alta' => 0,
							'modificar' => 0
						],
					],
					'agrupamiento'	=>   [
						'escalafon' =>[
							'alta' => 0,
							'modificar' => 0
						],
					],
					'tramo' =>  [
						'escalafon' =>[
								'alta' => 0,
								'modificar' => 0
						],
					],
					'nivel_educativo' =>  [
						'formacion' =>[
							'alta' => 0,
							'modificar' => 0
						],
					],
					'titulo' =>  [
						'formacion' =>[
							'alta' => 0,
							'modificar' => 0
						],
					],
					'otros_estudios' =>  [
						'formacion' =>[
							'alta' => 0,
							'modificar' => 0
						],
					],
					'conocimientos' =>  [
						'formacion' =>[
							'alta' => 0,
							'modificar' => 0
						],
					],
					'funcion_ejecutiva'	=>  [
						'escalafon' =>[
							'alta' => 0,
							'modificar' => 0
						],
					],
					'nivel_destreza' => [
						'perfil' => [
							'alta' 		=> 0,
							'modificar' => 0,
						]	
					],
					'puesto_supervisa' => [
						'perfil' => [
							'alta' 		=> 0,
							'modificar' => 0,
						]
					],
					'nivel_complejidad' => [
						'perfil' => [
							'alta' 		=> 0,
							'modificar' => 0,
						]	
					],
					'denominacion_funcion' => [
						'perfil' => [
							'alta' 		=> 0,
							'modificar' => 0,
						]
					],
					'denominacion_puesto' => [
						'perfil' => [
							'alta' 		=> 0,
							'modificar' => 0,
						]
					],
					'objetivo_general' => [
						'perfil' => [
							'alta' 		=> 0,
							'modificar' => 0,
						]
					],
					'objetivo_especificos' => [
						'perfil' => [
							'alta' 		=> 0,
							'modificar' => 0,
						]
					],
					'estandares' => [
						'perfil' => [
							'alta' 		=> 0,
							'modificar' => 0,
						]
					],
					'actividades' => [
						'perfil' => [
							'alta' 		=> 0,
							'modificar' => 0,
						]
					],
					'fecha_obtencion_result' => [
						'perfil' => [
							'alta' 		=> 0,
							'modificar' => 0,
						]
					],
					'resultados_finales' => [
						'perfil' => [
							'alta' 		=> 0,
							'modificar' => 0,
						]
					],
					'familia_puestos' => [
						'perfil' => [
							'alta' 		=> 0,
							'modificar' => 0,
						]
					],
					'nombre_puesto' => [
						'perfil' => [
							'alta' 		=> 0,
							'modificar' => 0,
						]
					],
					'desgrava_afip' =>  [
						'grupo_familiar' =>[
							'alta' => 0,
							'modificar' => 0
						]
					],
					'parentesco' => [ 
						'grupo_familiar' => [ 
							'alta' => 0, 
							'modificar' => 0
						] 
					],
					'nombre' => [ 
						'grupo_familiar' => [ 
							'alta' => 0, 
							'modificar' => 0 
						] 
					],
					'apellido' => [ 
						'grupo_familiar' => [ 
							'alta' => 0, 
							'modificar' => 0 
						] 
					],
					'fecha_nacimiento' => [ 
						'grupo_familiar' => [ 
							'alta' => 0, 
							'modificar' => 0 
						] 
					],
					'tipo_documento' => [ 
						'grupo_familiar' => [ 
							'alta' => 0, 
							'modificar' => 0 
						] 
					],
					'documento' => [ 
						'grupo_familiar' => [ 
							'alta' => 0, 
							'modificar' => 0 
						] 
					],
					'nacionalidad' => [ 
						'grupo_familiar' => [ 
							'alta' => 0, 
							'modificar' => 0 
						] 
					],
					'nivel_educativo' => [ 
						'grupo_familiar' => [ 
							'alta' => 0, 
							'modificar' => 0 
						] 
					],
					'fecha_desde' => [ 
						'grupo_familiar' => [ 
							'alta' => 0, 
							'modificar' => 0 
						] 
					],
					'fecha_hasta' => [ 
						'grupo_familiar' => [ 
							'alta' => 0, 
							'modificar' => 0 
						] 
					],
					'reintegro_guarderia' => [ 
						'grupo_familiar' => [ 
							'alta' => 0, 
							'modificar' => 0 
						] 
					],
					'discapacidad' => [ 
						'grupo_familiar' => [ 
							'alta' => 0,
							'modificar' => 0 
						] 
					],
					'tipo_discapacidad' => [ 
						'grupo_familiar' => [ 
							'alta' => 0, 
							'modificar' => 0 
						] 
					],
					'cud' => [ 
						'grupo_familiar' => [ 
							'alta' => 0, 
							'modificar' => 0 
						] 
					],
					'fecha_alta_discapacidad' => [ 
						'grupo_familiar' => [ 
							'alta' => 0, 
							'modificar' => 0
						] 
					],
					'fecha_vencimiento' => [ 
						'grupo_familiar' => [ 
							'alta' => 0, 
							'modificar' => 0 
						] 
					]
				]

			],	 			
			'permisos'	=> [
				'CreditosIniciales'	=> [
					'listar'		=> false,
					'alta'			=> false,
					'modificacion'	=> false,
					'baja'			=> false,
					'buscarAgente'	=> false,
				],
				'Legajos'	=> [
					//'gestionar' =>1,
					'ajax_ubicaciones'	=> 1,
					'ajax_convenios_parametricos'	=> 1,
					'agentes' => 1,
					'ajax_lista_agentes' => 1,
					'ajax_lista_observaciones' => 1,
					'observaciones' => 1,
					'alta_embargo' => 0,
					'historial_embargo' => 1,
					'modificar_embargo' => 0,
					'baja_embargo' => 0,
					'buscar_cuit' => 1,
					'datos_globales' => 0,
					'datos_recoleccion' => 1,
					'ajax_datos_recoleccion' => 1,
					'ajax_datos_vinculacion' => 1,
					'ajax_datos_formacion'	=> 1,
					'mostrar_foto_persona'	=> 1,
				],
				'Titulos'    =>[
					'ajax_titulos' => 1,
				],
				'Documentos' => [
					'listado'	=> 1,
					'ver_listado' => 1,
					'alta'	=> 1,
					'modificacion' => 1,
					'baja' => 1,
					'descargar_documento' => 1,
					'descarga_pdf'			=> true,
					'descarga_excel'		=> true,
				],
 				'grupo_familiar' => [ 
					'alta' =>0,
					'baja' =>0,
					'modificar' =>0,
				],
				'Error' => [
					'index'	=> 1
				],
				'Sigeco'	=> [
					'link'				=> 1,
				],
				'Dependencias' => [
					'ajax_nivel_hijos' => 1,
					'ajax_dependencias_nivel' => 1
				],
				'Manuales'	=> [
					'index' => 1,
				],
				'informes'	=> [
					'menu' => 1,
				],
				'SimuladorPromocionGrados'	=> [
					'listado_simulacion_promocion_grado' => false,
                    'agentes_promocionables'		=> false,
					'ejecutar_simulador'			=> false,
					'simulacion_promocion_grado'	=> false,
				],
			]	
		],
		self::ROL_DEFAULT	=> [
			'nombre'	=> 'Visita',
			'padre'		=> self::PADRE_BASE,
			'inicio'	=> ['control' => 'tests','accion' => 'index'],
			'permisos'	=> [
				'Tests' => [
					'index'	=> 1
				],
			]
		],
		self::ROL_ADMINISTRACION => [
			'nombre'	=> 'Administrador del sistema',
			'padre'		=> self::PADRE_BASE,
			'inicio'	=> ['control' => 'legajos', 'accion' => 'agentes'],
			'modalidades_vinculacion_autorizadas' =>['1','2','3','4','5','6'],
			'roles_permitidos' => [self::ROL_ADMINISTRACION, self::ROL_ADMINISTRACION_RRHH, self::ROL_DESARROLLO_RRHH, self::ROL_CONTROL_RRHH, self::ROL_CONVENIOS, self::ROL_LIQUIDACIONES, self::ROL_IRI],
			'atributos' => [ 
				'tab_visible' =>[
					'anticorrupcion'	=>	['Anticorrupcion'	=>	['alta' => 1]]
				 ],
			],	 
			'permisos'	=> [
				'Usuarios' => [
					'index'	=> 1,
					'alta'	=> 1,
					'modificar' =>1,
					'baja'	=>1
				],
				'Dependencias' => [
					'index'	=> 1,
					'alta'  => 1,
					'baja'  => 1,
					'modificacion' => 1,
					'ajax_lista_dependencias' => 1,
					'index_informales' => 1,
					'alta_informales' => 1,
					'modificacion_informales' => 1,
					'baja_informales' => 1,
					'ajax_lista_dependencias_informales' => 1,
				],
				'anticorrupcion' => [
					'alta' =>1,
				],
				'Legajos' => [
					'gestionar'	=> 1,
					'mostrar_presentacion' => 1,
					'historial_presentacion'  => 1,
					'listado_anticorrupcion' => 1,
					'historial_anticorrupcion' => 1,
					'exportar' => 1,
					'exportacion' => 1,
					'ajax_lista_historico_anticorrupcion' => 1,
					'ajax_listado_anticorrupcion' => 1,
					'nueva_evaluacion' => 1,
					'historial_evaluacion' => 1,
					'mostrar_evaluacion' => 1,
					'update_evaluacion' => 1,
					'historial_titulo_creditos' => 1,
					'alta_titulo_creditos' => 1,
					'modificar_titulo_creditos' => 1,
					'ver_titulo_creditos' => 1,
					'mostrar_titulo_credito' => 1,
					'historial_cursos' => 1,
					'alta_curso' => 1,
					'buscar_curso' => 1,
					'modificar_curso' =>1,
					'baja_curso' => 1,
				],
				'Tipo_discapacidad' => [ 
					'alta' =>1,
					'baja' =>1,
					'modificacion' =>1,
					'index' => 1,
				],
				'Ubicaciones_edificios' => [ 
					'alta' =>1,
					'baja' =>1,
					'modificacion' =>1,
					'index' => 1,
				],
				'Ubicaciones' => [ 
					'alta' =>1,
					'baja' =>1,
					'modificacion' =>1,
					'index' => 1,
					'ajax_lista_ubicaciones' => 1,
				],
				'Licencias_especiales' => [ 
					'alta' =>1,
					'baja' =>1,
					'modificacion' =>1,
					'index' => 1
				],
				'Motivos_baja' => [ 
					'alta' =>1,
					'baja' =>1,
					'modificacion' =>1,
					'index' => 1
				],
				'Nivel_educativo' => [ 
					'alta' =>1,
					'baja' =>1,
					'modificacion' =>1,
					'index' => 1
				],
				'Titulos' => [
					'index'	=> 1,
					'alta'  => 1,
					'modificacion' => 1,
					'baja' => 1,
					'ajax_lista_titulos' => 1,
				],
				'Sindicatos' => [ 
					'alta' =>1,
					'baja' =>1,
					'modificacion' =>1,
					'index' => 1
				],
				'Obras_sociales' => [ 
					'alta' =>1,
					'baja' =>1,
					'modificacion' =>1,
					'index' => 1
				],
				'Seguros_vida' => [ 
					'alta' =>1,
					'baja' =>1,
					'modificacion' =>1,
					'index' => 1
				],
				'Familia_puestos' => [
					'index'			=> 1,
					'alta'			=> 1,
					'baja'			=> 1,
					'modificacion'	=> 1
				],
				'Subfamilias' => [
					'index'			=> 1,
					'alta'			=> 1,
					'baja'			=> 1,
					'modificacion'	=> 1
				],
				'Puestos' => [
					'index'			=> 1,
					'alta'			=> 1,
					'baja'			=> 1,
					'modificacion'	=> 1
				],
				'Responsable_contrato' =>	[
					'gestionar' => 1,
					'ajax_get_contratante_firmantes' =>1
				],
				'Puestos' => [
					'index'							=> 1,
					'index_subfamilia'				=> 1,
					'index_familia_puesto'			=> 1,
					'alta'							=> 1,
					'baja'							=> 1,
					'modificacion'					=> 1,
					'alta_subfamilia'				=> 1,
					'baja_subfamilia'				=> 1,
					'modificacion_subfamilia'		=> 1,
					'alta_familia_puesto'			=> 1,
					'baja_familia_puesto'			=> 1,
					'modificacion_familia_puesto'	=> 1,
					'ajax_get_puesto' => 1,
					'ajax_puesto' =>1
				],

				'Documentos' => [
					'listado'	=> 1,
					'ver_listado' => 1,
					'alta'	=> 0,
					'modificacion' => 0,
					'baja' => 0,
					'descargar_documento' => 1,
					'descarga_pdf'			=> 1,
				],				
				'escalafon' => [
					'alta' =>1
				],
				'Manuales'	=> [
					'index' => 1,
				],
				'Otros_organismos' => [
					'index'	=> 1,
					'alta'	=> 1,
					'modificacion' =>1,
					'baja'	=>1
				],
				'Auditorias' => [
					'index'	=> true,
					'json_detalle_pesquisa'	=> true,
				],
				'Recibos' => [
					'index' => 1,
					'descarga' => 1
				],
				'Importador' => [
					'procesar_cursos' => 1
				],
				'Escalafon' => [
					'alta' =>1,
					'designacion_transitoria' => 1,
					'ajax_designacion_transitoria' => 1,
					'agregar_prorroga' =>1,
					'editar_prorroga' =>1,
					'baja_prorroga' => 1,
					'historial_designacion' =>1, 
					'mostrar_designacion' => 1
				],
				'informes' => [
					'menu' =>1
				],
				'PromocionCreditos' => [
					'alta' => 1,
					'modificacion' => 1,
					'index' => 1
				],
			]
		],
		self::ROL_ADMINISTRACION_RRHH => [
			'nombre'	=> 'Administrador de RRHH',
			'padre'		=> self::PADRE_BASE,
			'inicio'	=> ['control' => 'legajos', 'accion' => 'agentes'],
			'atributos' => [ 
				'campos' => [
					'modalidad_vinculacion' =>  [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
							],
					],
					'situacion_revista' =>  [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'compensacion_geografica' =>  [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'exc_art_14' =>  [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'antiguedad_adm_publica' =>  [
	                    'antiguedad' =>[
	                        'alta' => 1,
	                        'modificar' => 1
	                        ],
	                ],
	                'fecha_ingreso_mtr' =>  [
	                    'antiguedad' =>[
	                        'alta' => 1,
	                        'modificar' => 1
	                    ],
	                ],
	                'contador_antiguedad' =>  [
	                    'antiguedad' =>[
	                        'alta' => 1,
	                        'modificar' => 1
	                    ],
	                ],
					'tipo_discapacidad' => [
						'varios' => [
							 'alta' => 1, 
							 'modificar' => 1 
							] 
						], 
					'cud' => [
						'varios' => [ 
							'alta' => 1, 
							'modificar' => 1 
						] 
					], 
					'credencial' => [
						 'varios' => [
						 	'alta' => 1, 
						 	'modificar' => 1 
						 ] 
					], 
					'observaciones' => [ 
						'varios' => [ 
							'alta' => 1, 
							'modificar' => 1 
						] 
					],
					'parentesco' => [ 
						'grupo_familiar' => [ 
							'alta' => 1, 
							'modificar' => 1 
						] 
					],
					'nombre' => [ 
						'grupo_familiar' => [ 
							'alta' => 1, 
							'modificar' => 1 
						] 
					],
					'apellido' => [ 
						'grupo_familiar' => [ 
							'alta' => 1, 
							'modificar' => 1 
						] 
					],
					'fecha_nacimiento' => [ 
						'grupo_familiar' => [ 
							'alta' => 1, 
							'modificar' => 1 
						] 
					],
					'tipo_documento' => [ 
						'grupo_familiar' => [ 
							'alta' => 1, 
							'modificar' => 1 
						] 
					],
					'documento' => [ 
						'grupo_familiar' => [ 
							'alta' => 1, 
							'modificar' => 1 
						] 
					],
					'nacionalidad' => [ 
						'grupo_familiar' => [ 
							'alta' => 1, 
							'modificar' => 1 
						] 
					],
					'nivel_educativo' => [ 
						'grupo_familiar' => [ 
							'alta' => 1, 
							'modificar' => 1 
						] 
					],
					'fecha_desde' => [ 
						'grupo_familiar' => [ 
							'alta' => 1, 
							'modificar' => 1 
						] 
					],
					'fecha_hasta' => [ 
						'grupo_familiar' => [ 
							'alta' => 1, 
							'modificar' => 1 
						] 
					],
					'reintegro_guarderia' => [ 
						'grupo_familiar' => [ 
							'alta' => 1, 
							'modificar' => 1 
						] 
					],
					'discapacidad' => [ 
						'grupo_familiar' => [ 
							'alta' => 1, 
							'modificar' => 1 
						] 
					],
					'tipo_discapacidad' => [ 
						'grupo_familiar' => [ 
							'alta' => 1, 
							'modificar' => 1 
						] 
					],
					'cud' => [ 
						'grupo_familiar' => [ 
							'alta' => 1, 
							'modificar' => 1 
						] 
					],
					'fecha_alta_discapacidad' => [ 
						'grupo_familiar' => [ 
							'alta' => 1, 
							'modificar' => 1 
						] 
					],
					'fecha_vencimiento' => [ 
						'grupo_familiar' => [ 
							'alta' => 1, 
							'modificar' => 1 
						] 
					],
					'delegado_gremial' =>  [
						'escalafon' =>[
								'alta' => 1,
								'modificar' => 1
						],
					],
					'fecha_vigencia_mandato' =>  [
						'escalafon' =>[
								'alta' => 1,
								'modificar' => 1
						],
					],
					'id_sindicato' =>  [
						'escalafon' =>[
								'alta' => 1,
								'modificar' => 1
						],
					],
				]
			],
			'permisos'	=> [
				'Tests' => [
					'index'	=> 1
				],
				'Legajos' => [
					'gestionar'	=> 1,
					'ajax_convenios_parametricos'	=> 1,
					'ajax_lista_observaciones' => 1,
					'horas_extras' => 1,
					'historial_horas_extras' => 1,
					'update_hora_extra' =>1,
					'baja_hora_extra' => 1,
					'observaciones' => 1,
					'alta_embargo' => 1,
					'historial_embargo' => 1,
					'modificar_embargo' => 1,
					'baja_embargo' => 1,
					'alta_familiar' => 1,
					'modificar_familiar' => 1,
					'baja_familiar' => 1,
					'exportar' => 1,
					'alta_familiar' =>1,	
					'baja_familiar' =>1,	
					'modificar_familiar' =>1,
					'exportacion' => 1,	
					'ajax_art_14_parametricos' => 1,
					'ajax_sindicato'	=> 1,
				],

				'datos_personales' => [
					'alta' =>1
				],
				'escalafon' => [
					'alta' =>1
				],
				'antiguedad' => [
					'alta' =>1
				],

				'administracion' => [ 
					'alta' =>1 
				], 
				'varios' => [ 
					'alta' =>1 
				],
				'Documentos' => [
					//'carga_documentos'	=> 1,
					'listado'	=> 1,
					'ver_listado' => 1,
					'alta'	=> 1,
					'modificacion' => 1,
					'baja' => 1,
					'descargar_documento' => 1
				],
				'grupo_familiar' => [ 
					'alta' =>1,
					'baja' =>1,
					'modificar' =>1,
				],
				'Tipo_discapacidad' => [ 
					'alta' =>1,
					'baja' =>1,
					'modificacion' =>1,
					'index' => 1,
				],
				'Licencias_especiales' => [ 
					'alta' =>1,
					'baja' =>1,
					'modificacion' =>1,
					'index' => 1
				],
				'Motivos_baja' => [ 
					'alta' =>1,
					'baja' =>1,
					'modificacion' =>1,
					'index' => 1
				],
				'Horarios' => [
					'index' 		=> 1,
					'alta' 			=> 1,
					'baja' 			=> 1,
					'modificacion'	=> 1
				],
				'Escalafon' => [
					'index' => 1,
					'ajax_lista_modalidad_vinculacion' => 1,
					'lista_modalidad_vinculacion' => 1,
					'modalidad_vinculacion' => 1,
					'baja_modalidad_vinculacion' => 1,
					'ajax_lista_situacion_revista' => 1,
					'lista_situacion_revista' => 1,
					'situacion_revista' => 1,
					'baja_situacion_revista' => 1,
					'ajax_lista_niveles' => 1,
					'lista_niveles' => 1,
					'nivel' => 1,
					'baja_nivel' => 1,
					'ajax_lista_grados' => 1,
					'lista_grados' => 1,
					'grado' => 1,
					'baja_grado' => 1,
					'ajax_lista_tramos' => 1,
					'lista_tramos' => 1,
					'tramo' => 1,
					'baja_tramo' => 1,
					'ajax_get_revista' => 1,
					'ajax_lista_agrupamientos' => 1,
					'lista_agrupamientos' => 1,
					'agrupamiento' => 1,
					'baja_agrupamiento' => 1,
					'ajax_get_agrupamientos' => 1,
					'ajax_get_tramos' => 1,

					'ajax_lista_funciones_ejecutivas' => 1,
					'lista_funciones_ejecutivas' => 1,
					'funcion_ejecutiva' => 1,
					'baja_funcion_ejecutiva' => 1,
				],
				'Ubicaciones_edificios' => [ 
					'alta' =>1,
					'baja' =>1,
					'modificacion' =>1,
					'index' => 1,
				],
				'Ubicaciones' => [ 
					'alta' =>1,
					'baja' =>1,
					'modificacion' =>1,
					'index' => 1,
					'ajax_lista_ubicaciones' => 1,
				],
				'Nivel_educativo' => [ 
					'alta' 			=> 1,
					'baja' 			=> 1,
					'modificacion'  => 1,
					'index' 	    => 1
				],
				'Sindicatos' => [ 
					'alta' =>1,
					'baja' =>1,
					'modificacion' =>1,
					'index' => 1
				],
				'Obras_sociales' => [ 
					'alta' =>1,
					'baja' =>1,
					'modificacion' =>1,
					'index' => 1
				],
				'Seguros_vida' => [ 
					'alta' =>1,
					'baja' =>1,
					'modificacion' =>1,
					'index' => 1
				],
				'Denominacion_funcion' => [ 
					'gestionar' =>1,
					'baja' =>1,
					'index' => 1,
					'ajax_lista_denominacion_funcion' => 1
				],
				'Sigeco'	=> [
					'link'	=> 0,
				],
				'Otros_organismos' => [
					'index'	=> 1,
					'alta'	=> 1,
					'modificacion' =>1,
					'baja'	=>1
				],
				'Auditorias' => [
					'index'	=> true,
					'json_detalle_pesquisa'	=> true,
				],
				'Recibos' => [
					'index' => 1,
					'descarga' => 1
				],
				'Comisiones' =>[
					'alta' => 1,
					'modificacion' => 1,
					'baja' => 1,
					'index' => 1,
				],
				'Dependencias' => [
					'index'	=> 1,
					'alta'  => 1,
					'baja'  => 1,
					'modificacion' => 1,
					'ajax_lista_dependencias' => 1,
					'index_informales' => 1,
					'alta_informales' => 1,
					'modificacion_informales' => 1,
					'baja_informales' => 1,
					'ajax_lista_dependencias_informales' => 1,
				],
			]
		],
		self::ROL_DESARROLLO_RRHH => [
			'nombre'	=> 'Administrador Desarrollo RRHH (deprecado)',
			'padre'		=> self::PADRE_BASE,
			'inicio'	=> ['control' => 'legajos','accion' => 'agentes'],
			'modalidades_vinculacion_autorizadas' =>['1','2','3','4','5','6'],
			'atributos' => [ 
				'campos' => [
					'nivel'	=>  [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
						]
					],
					'grado'	=> [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
						]
					],
					'funcion_ejecutiva'	=> [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
						]
					],
					'compensacion_transitoria'	=>   [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'agrupamiento'	=>   [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'tramo' =>  [
						'escalafon' =>[
								'alta' => 1,
								'modificar' => 1
						],
					],
					'ultimo_cambio_nivel' =>  [
						'escalafon' =>[
								'alta' => 1,
								'modificar' => 1
						],
					],
					'fecha_vigencia_mandato' =>  [
						'escalafon' =>[
								'alta' => 1,
								'modificar' => 1
						],
					],
					'id_sindicato' =>  [
						'escalafon' =>[
								'alta' => 1,
								'modificar' => 1
						],
					],
					// 'delegado_gremial' =>  [
					// 	'escalafon' =>[
					// 			'alta' => 1,
					// 			'modificar' => 1
					// 	],
					// ],
					'exc_art_14' =>  [
						'escalafon' =>[
								'alta' => 1,
								'modificar' => 1
						],
					],
					'formacion' =>  [
						'escalafon' =>[
								'alta' => 1,
								'modificar' => 1
						],
					],
					'nivel_educativo' =>  [
						'formacion' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'titulo' =>  [
						'formacion' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'otros_estudios' =>  [
						'formacion' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'conocimientos' =>  [
						'formacion' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'familia_puestos' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'nombre_puesto' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'nivel_destreza' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]	
					],
					'puesto_supervisa' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'nivel_complejidad' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]	
					],
				]		

			],
			'permisos'	=> [
				'Tests' => [
					'index'	=> 1
				],
				'Legajos' => [
					'gestionar'	=> 1,
					'alta' => 1,
					'ajax_lista_observaciones' => 1,
					'observaciones' => 1,
					'ajax_art_14_parametricos' => 1,
					'ajax_sindicato' => 1,
					'exportar' => 1,
					'exportacion' => 1,
					'nueva_evaluacion' => 1,
					'historial_evaluacion' => 1,
					'mostrar_evaluacion' => 1,
					'update_evaluacion' => 1,
					'historial_titulo_creditos' => 1,
					'alta_titulo_creditos' => 1,
					'modificar_titulo_creditos' => 1,
					'ver_titulo_creditos' => 1,
					'mostrar_titulo_credito' => 1,
					'historial_cursos' => 1,
					'alta_curso' => 1,
					'buscar_curso' => 1,
					'modificar_curso' =>1,
					'baja_curso' => 1
				],
				//Controlador/aciones de ejemplo sustituir por lo que corresponda
				'escalafon' => [
					'alta' =>1
				],
				//Controlador/aciones de ejemplo sustituir por lo que corresponda

				'Formacion' => [
					'alta' =>1,
				],
				'perfil' => [
					'alta' 		=> 1,
					'modificar' => 1,
				],
				'formacion' => [
					'alta' =>1
				],
				'Titulos' => [
					'index'	=> 1,
					'alta'  => 1,
					'modificacion' => 1,
					'baja' => 1,
					'ajax_lista_titulos' => 1,
				],
				'Puestos' => [
					'index'							=> 1,
					'index_subfamilia'				=> 1,
					'index_familia_puesto'			=> 1,
					'alta'							=> 1,
					'baja'							=> 1,
					'modificacion'					=> 1,
					'alta_subfamilia'				=> 1,
					'baja_subfamilia'				=> 1,
					'modificacion_subfamilia'		=> 1,
					'alta_familia_puesto'			=> 1,
					'baja_familia_puesto'			=> 1,
					'modificacion_familia_puesto'	=> 1,
					'ajax_get_puesto' => 1,
					'ajax_puesto' =>1
				],
				'antiguedad_grado' => [
					'alta' =>1
				],
				'Auditorias' => [
					'index'	=> true,
					'json_detalle_pesquisa'	=> true,
				],
				'Importador' => [
					'procesar_cursos' => 1
				],
				'PromocionCreditos' => [
					'alta' => 1,
					'modificacion' => 1,
					'index' => 1
				],
                'Promocion_grados'=>[
                    'alta'		=> 1,
					'index'		=> 1,
					'resumen'	=> 1,
					'descargar'	=> 1,
                ],
                'SimuladorPromocionGrados'	=> [
					'listado_simulacion_promocion_grado' => false,
                    'agentes_promocionables'		=> false,
					'ejecutar_simulador'			=> false,
					'simulacion_promocion_grado'	=> false,
				],
				'CreditosIniciales'	=> [
					'listar'		=> true,
					'alta'			=> true,
					'modificacion'	=> true,
					'baja'			=> true,
					'buscarAgente'	=> true,
				],
			]
		],
		self::ROL_CONTROL_RRHH => [
			'nombre'	=> 'Administrador Control RRHH',
			'padre'		=> self::PADRE_BASE,
			'inicio'	=> ['control' => 'legajos', 'accion' => 'agentes'],
			'modalidades_vinculacion_autorizadas' =>['1','3','4','5','6'],
			'atributos' => [ 
				'tab_visible' =>[
					'anticorrupcion' => ['Anticorrupcion' => ['alta' => 1]]
				 ],
				'campos' => [
					'modalidad_vinculacion' =>  [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
							],
					],
					'situacion_revista' =>  [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'funcion_ejecutiva'	=>  [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'secretaria'	=>  [
						'ubicacion_estructura' => [
							'alta' => 1,
							'modificar' => 1
						],
					],
					'subsecretaria'	=>  [
						'ubicacion_estructura' => [
							'alta' => 1,
							'modificar' => 1
						],
					],
					'dir_nacional_general'	=>  [
						'ubicacion_estructura' => [
							'alta' => 1,
							'modificar' => 1
						],
					],
					'dir_simple'	=>  [
						'ubicacion_estructura' => [
							'alta' => 1,
							'modificar' => 1
						],
					],
					'coordinacion'	=>  [
						'ubicacion_estructura' => [
							'alta' => 1,
							'modificar' => 1
						],
					],
					'unidad'	=>  [
						'ubicacion_estructura' => [
							'alta' => 1,
							'modificar' => 1
						],
					],
					'denominacion_funcion' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'denominacion_puesto' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'objetivo_general' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'objetivo_especificos' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'estandares' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'actividades' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'fecha_obtencion_result' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'resultados_finales' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'acto_administrativo'	=> ['gestionar'	=> [
						'alta'	=> false,
					]],
					'nivel'	=>  [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
						]
					],
					'grado'	=> [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
						]
					],
					'grado_liquidacion'	=> [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
						]
					],
					'funcion_ejecutiva'	=> [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
						]
					],
					'compensacion_transitoria'	=>   [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'agrupamiento'	=>   [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'tramo' =>  [
						'escalafon' =>[
								'alta' => 1,
								'modificar' => 1
						],
					],
					'ultimo_cambio_nivel' =>  [
						'escalafon' =>[
								'alta' => 1,
								'modificar' => 1
						],
					],
					'fecha_vigencia_mandato' =>  [
						'escalafon' =>[
								'alta' => 1,
								'modificar' => 1
						],
					],
					'id_sindicato' =>  [
						'escalafon' =>[
								'alta' => 1,
								'modificar' => 1
						],
					],
					// 'delegado_gremial' =>  [
					// 	'escalafon' =>[
					// 			'alta' => 1,
					// 			'modificar' => 1
					// 	],
					// ],
					'exc_art_14' =>  [
						'escalafon' =>[
								'alta' => 1,
								'modificar' => 1
						],
					],
					'formacion' =>  [
						'escalafon' =>[
								'alta' => 1,
								'modificar' => 1
						],
					],
					'nivel_educativo' =>  [
						'formacion' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'titulo' =>  [
						'formacion' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'otros_estudios' =>  [
						'formacion' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'conocimientos' =>  [
						'formacion' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'familia_puestos' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'nombre_puesto' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'nivel_destreza' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]	
					],
					'puesto_supervisa' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'nivel_complejidad' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]	
					],
				]
			],	
			'permisos'	=> [
				'Tests' => [
					'index'	=> 1
				],
				'Dependencias' => [
					'index' => 1,
					'alta'  => 1,
					'baja'  => 1,
					'modificacion' => 1,
					'ajax_lista_dependencias' => 1,
					'index_informales' => 1,
					'alta_informales' => 1,
					'modificacion_informales' => 1,
					'baja_informales' => 1,
					'ajax_lista_dependencias_informales' => 1,
				],
				'Legajos' => [
					'gestionar' =>1,
					'alta' => 1,
					'ajax_lista_observaciones' => 1,
					'observaciones' => 1,
					'mostrar_presentacion' => 1,
					'historial_presentacion'  => 1,
					'listado_anticorrupcion' => 1,
					'historial_anticorrupcion' => 1,
					'nueva_evaluacion' => 1,
					'historial_evaluacion' => 1,
					'update_evaluacion' => 1,
					'mostrar_evaluacion' => 1,
					'exportar_anticorrupcion_pdf' => 1,
					'anticorrupcion_pdf' => 1,
					'exportar_anticorrupcion' => 1,
					'ajax_lista_historico_anticorrupcion' => 1,
					'presentacion' => 1,
					'update_presentacion' => 1,
					'exportar' => 1,
					'exportacion' => 1,
					'ajax_listado_anticorrupcion' => 1,
					'datos_globales' => 0,
					'ajax_art_14_parametricos' => 1,
					'ajax_sindicato' => 1,
					'historial_titulo_creditos' => 1,
					'alta_titulo_creditos' => 1,
					'modificar_titulo_creditos' => 1,
					'ver_titulo_creditos' => 1,
					'mostrar_titulo_credito' => 1,
					'historial_cursos' => 1,
					'alta_curso' => 1,
					'buscar_curso' => 1,
					'modificar_curso' =>1,
					'baja_curso' => 1,
				],
				'datos_personales' => [
					'alta'	=> 1
				],
				'escalafon' => [
					'alta' =>1
				],	
				//Controlador/aciones de ejemplo sustituir por lo que corresponda
				'Escalafon' => [
					'alta' =>1,
					'designacion_transitoria' => 1,
					'ajax_designacion_transitoria' => 1,
					'agregar_prorroga' =>1,
					'editar_prorroga' =>1,
					'baja_prorroga' => 1,
					'historial_designacion' =>1, 
					'mostrar_designacion' => 1
				],
				'ubicacion_estructura' => [
					'alta' =>1
				],
				'perfil' => [
					'alta' =>1,
				],
				'anticorrupcion' => [
					'alta' =>1,
				],
				'informes' => [
					'menu' =>1
				],
				'Responsable_contrato' =>	[
					'gestionar' => 1,
					'ajax_get_contratante_firmantes' => 1
				],
				'Denominacion_funcion' => [ 
					'gestionar' =>1,
					'baja' =>1,
					'index' => 1,
					'ajax_lista_denominacion_funcion' => 1,
					'reactivar' => 1
				],
				'Comisiones' =>[
					'alta' => 1,
					'modificacion' => 1,
					'baja' => 1,
					'index' => 1,
				],
				'Auditorias' => [
					'index'	=> true,
					'json_detalle_pesquisa'	=> true,
				],
				'PromocionCreditos' => [
					'alta' => 1,
					'modificacion' => 1,
					'index' => 1
				],
				'Promocion_grados'=>[
                    'alta'		=> 1,
					'index'		=> 1,
					'resumen'	=> 1,
					'descargar'	=> 1,
                ],
                'SimuladorPromocionGrados'	=> [
					'listado_simulacion_promocion_grado' => false,
                    'agentes_promocionables'		=> false,
                    'ejecutar_simulador'			=> false,
                    'simulacion_promocion_grado'	=> false,
				],
				'CreditosIniciales'	=> [
					'listar'		=> true,
					'alta'			=> true,
					'modificacion'	=> true,
					'baja'			=> true,
					'buscarAgente'	=> true,
				],
				'Formacion' => [
					'alta' =>1,
				],
				'formacion' => [
					'alta' =>1
				],
				'Titulos' => [
					'index'	=> 1,
					'alta'  => 1,
					'modificacion' => 1,
					'baja' => 1,
					'ajax_lista_titulos' => 1,
				],
				'Puestos' => [
					'index'							=> 1,
					'index_subfamilia'				=> 1,
					'index_familia_puesto'			=> 1,
					'alta'							=> 1,
					'baja'							=> 1,
					'modificacion'					=> 1,
					'alta_subfamilia'				=> 1,
					'baja_subfamilia'				=> 1,
					'modificacion_subfamilia'		=> 1,
					'alta_familia_puesto'			=> 1,
					'baja_familia_puesto'			=> 1,
					'modificacion_familia_puesto'	=> 1,
					'ajax_get_puesto' => 1,
					'ajax_puesto' =>1
				],
				'antiguedad_grado' => [
					'alta' =>1
				],
				'Auditorias' => [
					'index'	=> true,
					'json_detalle_pesquisa'	=> true,
				],
				'Importador' => [
					'procesar_cursos' => 1
				],
				'PromocionCreditos' => [
					'alta' => 1,
					'modificacion' => 1,
					'index' => 1
				],
                'Promocion_grados'=>[
                    'alta'		=> 1,
					'index'		=> 1,
					'resumen'	=> 1,
					'descargar'	=> 1,
                ],
                'SimuladorPromocionGrados'	=> [
					'listado_simulacion_promocion_grado' => false,
                    'agentes_promocionables'		=> false,
					'ejecutar_simulador'			=> false,
					'simulacion_promocion_grado'	=> false,
				],
				'CreditosIniciales'	=> [
					'listar'		=> true,
					'alta'			=> true,
					'modificacion'	=> true,
					'baja'			=> true,
					'buscarAgente'	=> true,
				],
			]
		],
		self::ROL_CONVENIOS => [
			'nombre'	=> 'Administrador de Convenios',
			'padre'		=> self::PADRE_BASE,
			'inicio'	=> ['control' => 'legajos', 'accion' => 'agentes'],
			'modalidades_vinculacion_autorizadas' =>['2'], //SOLO PRESTACION DE SERVICIOS
			'situaciones_revista_autorizadas' =>['5','6','7'],
			'atributos' => [
				'tab_visible' =>[
					'presupuesto' 	=> ['Presupuestos' 	=>	['alta' => 0]],
					'embargo'		=>	['Embargos'		=>	['index' => 0]]					
				],	
				'campos' => [
					'modalidad_vinculacion' =>  [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
							],
					],
					'situacion_revista' =>  [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'unidad_retributiva' =>  [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'antiguedad_adm_publica' =>  [
	                    'antiguedad' =>[
	                        'alta' => 1,
	                        'modificar' => 1
	                        ],
	                ],
	                'fecha_ingreso_mtr' =>  [
	                    'antiguedad' =>[
	                        'alta' => 1,
	                        'modificar' => 1
	                    ],
	                ],
	                'contador_antiguedad' =>  [
	                    'antiguedad' =>[
	                        'alta' => 1,
	                        'modificar' => 1
	                    ],
	                ],
					'tipo_discapacidad' => [
						'varios' => [
							 'alta' => 1, 
							 'modificar' => 1 
							] 
						], 
					'cud' => [
						'varios' => [ 
							'alta' => 1, 
							'modificar' => 1 
						] 
					], 
					'credencial' => [
						 'varios' => [
						 	'alta' => 1, 
						 	'modificar' => 1 
						 ] 
					], 
					'observaciones' => [ 
						'varios' => [ 
							'alta' => 1, 
							'modificar' => 1 
						] 
					],
					'nivel'	=>  [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
						]
					],
					'grado'	=> [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
						]
					],
					'compensacion_transitoria'	=>   [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'agrupamiento'	=>   [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'tramo' =>  [
						'escalafon' =>[
								'alta' => 1,
								'modificar' => 1
						],
					],
					'nivel_educativo' =>  [
						'formacion' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'titulo' =>  [
						'formacion' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'otros_estudios' =>  [
						'formacion' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'conocimientos' =>  [
						'formacion' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'familia_puestos' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'nombre_puesto' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'nivel_destreza' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]	
					],
					'puesto_supervisa' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'nivel_complejidad' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]	
					],
					'funcion_ejecutiva'	=>  [
						'escalafon' =>[
							'alta' => 1,
							'modificar' => 1
						],
					],
					'secretaria'	=>  [
						'ubicacion_estructura' => [
							'alta' => 1,
							'modificar' => 1
						],
					],
					'subsecretaria'	=>  [
						'ubicacion_estructura' => [
							'alta' => 1,
							'modificar' => 1
						],
					],
					'dir_nacional_general'	=>  [
						'ubicacion_estructura' => [
							'alta' => 1,
							'modificar' => 1
						],
					],
					'dir_simple'	=>  [
						'ubicacion_estructura' => [
							'alta' => 1,
							'modificar' => 1
						],
					],
					'coordinacion'	=>  [
						'ubicacion_estructura' => [
							'alta' => 1,
							'modificar' => 1
						],
					],
					'unidad'	=>  [
						'ubicacion_estructura' => [
							'alta' => 1,
							'modificar' => 1
						],
					],
					'denominacion_funcion' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'denominacion_puesto' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'objetivo_general' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'objetivo_especificos' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'estandares' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'actividades' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'fecha_obtencion_result' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
					'resultados_finales' => [
						'perfil' => [
							'alta' 		=> 1,
							'modificar' => 1,
						]
					],
				]
			],
			'permisos'	=> [
				'Tests' => [
					'index'	=> 1
				],
				'Legajos' => [
					'alta' => 1,
					'gestionar'	=> 1,
					'ajax_convenios_parametricos'	=> 1,
					'ajax_lista_observaciones' => 1,
					'observaciones' => 1,
					'exportar' => 1,
					'exportacion' => 1,
				],

				'datos_personales' => [
					'alta' =>1
				],
				'escalafon' => [
					'alta' =>1
				],
				'antiguedad' => [
					'alta' =>1
				],
				'administracion' => [ 
					'alta' =>1 
				], 
				'varios' => [ 
					'alta' =>1 
				],
				'Documentos' => [
					//'carga_documentos'	=> 1,
					'listado'	=> 1,
					'ver_listado' => 1,
					'alta'	=> 1,
					'modificacion' => 1,
					'baja' => 1,
					'descargar_documento' => 1
				],
				'Formacion' => [
					'alta' =>1,
				],
				'perfil' => [
					'alta' 		=> 1,
					'modificar' => 1,
				],
				'formacion' => [
					'alta' =>1
				],
				'ubicacion_estructura' => [
					'alta' =>1
				],
				'Sigeco'	=> [
					'link' => 0,
				],
				'Auditorias' => [
					'index'	=> true,
					'json_detalle_pesquisa'	=> true,
				],
				'Recibos' => [
					'index' => 1,
					'descarga' => 1
				],	
				'Puestos' =>[
					'ajax_get_puesto' => 1
				]
			]
		],
		self::ROL_LIQUIDACIONES => [
			'nombre'	=> 'Administrador de Liquidaciones',
			'padre'		=> self::PADRE_BASE,
			'modalidades_vinculacion_autorizadas' =>['1','2','3','4','5','6'],
			'inicio'	=> ['control' => 'legajos', 'accion' => 'agentes'],
			'atributos' => [ 
				'campos' => [
					'desgrava_afip' =>  [
						'grupo_familiar' =>[
							'alta' => 1,
							'modificar' => 1
						]
					],
				]
			],
			'permisos'	=> [
				'informes'	=> [
					'menu'	=> 0,
				],
				'Tests' => [
					'index'	=> 1
				],
				'Legajos' => [
					'gestionar'	=> 1,
					'ajax_lista_observaciones' => 1,
					'observaciones' => 1,
					'modificar_familiar' => 1,
					'exportar' => 1,
					'modificar_familiar' =>1,
					'exportacion' => 1,
				],
				'presupuestos' => [
					'alta' => 1
				],
				'Presupuestos' => [
					'index' => 1,
					'lista_presupuesto_saf' => 1,
					'ajax_presupuesto_saf' => 1,
					'saf' => 1,
					'baja_saf' => 1,
					'lista_presupuesto_jurisdicciones' => 1,
					'ajax_presupuesto_jurisdicciones' => 1,
					'jurisdicciones' => 1,
					'baja_jurisdicciones' => 1,
					'lista_presupuesto_ub_geograficas' => 1,
					'ajax_presupuesto_ub_geograficas' => 1,
					'ubicaciones_geograficas' => 1,
					'baja_ub_geograficas' => 1,
					'lista_presupuesto_programas' => 1,
					'ajax_presupuesto_programas' => 1,
					'programas' => 1,
					'baja_programa' => 1,
					'lista_presupuesto_actividades' => 1,
					'ajax_presupuesto_actividades' => 1,
					'actividades' => 1,
					'baja_actividad' => 1,
					'lista_presupuesto_subprogramas' => 1,
					'ajax_presupuesto_subprogramas' => 1,
					'subprograma' => 1,
					'baja_subprograma' => 1,
					'lista_presupuesto_proyectos' => 1,
					'ajax_presupuesto_proyectos' => 1,
					'proyecto' => 1,
					'baja_proyecto' => 1,
					'ajax_get_subprogramas' => 1,
					'lista_presupuesto_obras' => 1,
					'ajax_presupuesto_obras' => 1,
					'obra' => 1,
					'baja_obra' => 1,
					'ajax_presupuestos' => 1,
					'am_presupuesto' => 1,
					'ajax_get_proyectos' => 1,
					'ajax_get_obras' => 1,
					'baja_presupuesto' => 1,
				],
				'Sigeco'	=> [
					'link' => 0,
				],
				'escalafon' => [
					'alta' =>1
				],
				'Recibos' => [
					'index' => 1,
					'descarga' => 1
				],
			]
		],
		///////////////////////////////////////////////////////////////////////////////////////
		self::ROL_IRI => [
			'nombre'	=> 'Observador',
			'padre'		=> self::PADRE_BASE,
			'inicio'	=> ['control' => 'legajos', 'accion' => 'agentes'],
			'modalidades_vinculacion_autorizadas' => ['1', '2', '3', '4', '5', '6'],
			'atributos' => [
				'campos' => []
			],
			'permisos'	=> [
				'Legajos' => [
					'gestionar'	=> 1,
					'ajax_convenios_parametricos'	=> 1,
					'ajax_lista_observaciones' => 1,
					'horas_extras' => 1,
					'historial_horas_extras' => 1,
					'update_hora_extra' => 0,
					'baja_hora_extra' => 0,
					'observaciones' => 1,
					'alta_embargo' => 0,
					'historial_embargo' => 1,
					'modificar_embargo' => 0,
					'baja_embargo' => 0,
					'alta_familiar' => 0,
					'modificar_familiar' => 0,
					'baja_familiar' => 0,
					'exportar' => 0,
					'alta_familiar' => 0,
					'baja_familiar' => 0,
					'modificar_familiar' => 0,
					'exportacion' => 0,
					'mostrar_presentacion' => 1,
					'historial_presentacion'  => 1,
					'listado_anticorrupcion' => 0,
					'historial_anticorrupcion' => 1,
					'exportar_anticorrupcion_pdf' => 1,
					'anticorrupcion_pdf' => 1,
					'exportar_anticorrupcion' => 1,
					'ajax_lista_historico_anticorrupcion' => 1,
				],

				'datos_personales' => [
					'alta' => 0
				],
				'escalafon' => [
					'alta' =>0
				],
				'antiguedad' => [
					'alta' => 0
				],

				'administracion' => [
					'alta' => 0
				],
				'varios' => [
					'alta' => 0
				],
				'Documentos' => [
					//'carga_documentos'	=> 1,
					'listado'	=> 1,
					'ver_listado' => 1,
					'alta'	=> 0,
					'modificacion' => 0,
					'baja' => 0,
					'descargar_documento' => 1
				],
				'grupo_familiar' => [
					'alta' => 0,
					'baja' => 0,
					'modificar' => 0,
				],
				'Tipo_discapacidad' => [
					'alta' => 0,
					'baja' => 0,
					'modificacion' => 0,
					'index' => 1,
				],
				'Licencias_especiales' => [
					'alta' => 0,
					'baja' => 0,
					'modificacion' => 0,
					'index' => 1
				],
				'Motivos_baja' => [
					'alta' => 0,
					'baja' => 0,
					'modificacion' => 0,
					'index' => 1
				],
				'Horarios' => [
					'index' 		=> 1,
					'alta' 			=> 0,
					'baja' 			=> 0,
					'modificacion'	=> 0
				],
				'Escalafon' => [
					'index' => 1,
					'ajax_lista_modalidad_vinculacion' => 1,
					'lista_modalidad_vinculacion' => 1,
					'modalidad_vinculacion' => 0,
					'baja_modalidad_vinculacion' => 0,
					'ajax_lista_situacion_revista' => 1,
					'lista_situacion_revista' => 1,
					'situacion_revista' => 0,
					'baja_situacion_revista' => 0,
					'ajax_lista_niveles' => 1,
					'lista_niveles' => 1,
					'nivel' => 0,
					'baja_nivel' => 0,
					'ajax_lista_grados' => 1,
					'lista_grados' => 1,
					'grado' => 0,
					'baja_grado' => 0,
					'ajax_lista_tramos' => 1,
					'lista_tramos' => 1,
					'tramo' => 0,
					'baja_tramo' => 0,
					'ajax_get_revista' => 1,
					'ajax_lista_agrupamientos' => 1,
					'lista_agrupamientos' => 1,
					'agrupamiento' => 0,
					'baja_agrupamiento' => 0,
					'ajax_get_agrupamientos' => 1,
					'ajax_get_tramos' => 1,
					'ajax_lista_funciones_ejecutivas' => 1,
					'lista_funciones_ejecutivas' => 1,
					'funcion_ejecutiva' => 0,
					'baja_funcion_ejecutiva' => 0,
				],
				'Ubicaciones_edificios' => [
					'alta' => 0,
					'baja' => 0,
					'modificacion' => 0,
					'index' => 1,
				],
				'Ubicaciones' => [
					'alta' => 0,
					'baja' => 0,
					'modificacion' => 0,
					'index' => 1,
					'ajax_lista_ubicaciones' => 1,
				],
				'Nivel_educativo' => [
					'alta' 			=> 0,
					'baja' 			=> 0,
					'modificacion'  => 0,
					'index' 	    => 1
				],
				'Sindicatos' => [
					'alta' => 0,
					'baja' => 0,
					'modificacion' => 0,
					'index' => 1
				],
				'Obras_sociales' => [
					'alta' => 0,
					'baja' => 0,
					'modificacion' => 0,
					'index' => 1
				],
				'Seguros_vida' => [
					'alta' => 0,
					'baja' => 0,
					'modificacion' => 0,
					'index' => 1
				],
				'Denominacion_funcion' => [
					'gestionar' => 0,
					'baja' => 0,
					'index' => 1,
					'ajax_lista_denominacion_funcion' => 1
				],
				'Sigeco'	=> [
					'link'	=> 0,
				],
				'Otros_organismos' => [
					'index'	=> 1,
					'alta'	=> 0,
					'modificacion' => 0,
					'baja'	=> 0
				],
				'Recibos' => [
					'index' => 1,
					'descarga' => 1
				],
				'informes' => [
					'menu' => 1
				],
			]
		],
///////////////////////////////////////////////////////////////////////////////////////
self::ROL_SUPER_RRHH => [
'nombre'	=> 'Superman',
'padre'		=> self::PADRE_BASE,
'inicio'	=> ['control' => 'legajos','accion' => 'agentes'],
'roles_permitidos' => [self::ROL_ADMINISTRACION, self::ROL_ADMINISTRACION_RRHH, self::ROL_DESARROLLO_RRHH, self::ROL_CONTROL_RRHH, self::ROL_CONVENIOS, self::ROL_LIQUIDACIONES, self::ROL_IRI],
'modalidades_vinculacion_autorizadas' =>['1','2','3','4','5','6'],
	'atributos' => [
		'tab_visible' =>[
		'anticorrupcion' => ['Anticorrupcion' => ['alta' => 1]]
		],
		'campos' => [
		'unidad_retributiva' =>  [
		'escalafon' =>[
		'alta' => 1,
		'modificar' => 1
		],
		],
		'desgrava_afip' =>  [
		'grupo_familiar' =>[
		'alta' => 1,
		'modificar' => 1
		]
		],
		'modalidad_vinculacion' =>  [
		'escalafon' =>[
		'alta' => 1,
		'modificar' => 1
		],
		],
		'situacion_revista' =>  [
		'escalafon' =>[
		'alta' => 1,
		'modificar' => 1
		],
		],
		'compensacion_geografica' =>  [
		'escalafon' =>[
		'alta' => 1,
		'modificar' => 1
		],
		],
		'antiguedad_adm_publica' =>  [
		'antiguedad' =>[
		'alta' => 1,
		'modificar' => 1
		],
		],
		'fecha_ingreso_mtr' =>  [
		'antiguedad' =>[
		'alta' => 1,
		'modificar' => 1
		],
		],
		'contador_antiguedad' =>  [
		'antiguedad' =>[
		'alta' => 1,
		'modificar' => 1
		],
		],
		'tipo_discapacidad' => [
		'varios' => [
		'alta' => 1, 
		'modificar' => 1 
		],
		'grupo_familiar' => [ 
		'alta' => 1, 
		'modificar' => 1 
		]  
		],
		'cud' => [
		'varios' => [ 
		'alta' => 1, 
		'modificar' => 1 
		], 
		'grupo_familiar' => [ 
		'alta' => 1, 
		'modificar' => 1 
		] 
		],
		'credencial' => [
		'varios' => [
		'alta' => 1, 
		'modificar' => 1 
		] 
		], 
		'observaciones' => [ 
		'varios' => [ 
		'alta' => 1, 
		'modificar' => 1 
		] 
		],
		'parentesco' => [ 
		'grupo_familiar' => [ 
		'alta' => 1, 
		'modificar' => 1 
		] 
		],
		'nombre' => [ 
		'grupo_familiar' => [ 
		'alta' => 1, 
		'modificar' => 1 
		] 
		],
		'apellido' => [ 
		'grupo_familiar' => [ 
		'alta' => 1, 
		'modificar' => 1 
		] 
		],
		'fecha_nacimiento' => [ 
		'grupo_familiar' => [ 
		'alta' => 1, 
		'modificar' => 1 
		] 
		],
		'tipo_documento' => [ 
		'grupo_familiar' => [ 
		'alta' => 1, 
		'modificar' => 1 
		] 
		],
		'documento' => [ 
		'grupo_familiar' => [ 
		'alta' => 1, 
		'modificar' => 1 
		] 
		],
		'nacionalidad' => [ 
		'grupo_familiar' => [ 
		'alta' => 1, 
		'modificar' => 1 
		] 
		],
		'nivel_educativo' => [ 
		'grupo_familiar' => [ 
		'alta' => 1, 
		'modificar' => 1 
		],
		'formacion' =>[
		'alta' => 1,
		'modificar' => 1
		],
		],
		'fecha_desde' => [ 
		'grupo_familiar' => [ 
		'alta' => 1, 
		'modificar' => 1 
		] 
		],
		'fecha_hasta' => [ 
		'grupo_familiar' => [ 
		'alta' => 1, 
		'modificar' => 1 
		] 
		],
		'reintegro_guarderia' => [ 
		'grupo_familiar' => [ 
		'alta' => 1, 
		'modificar' => 1 
		] 
		],
		'discapacidad' => [ 
		'grupo_familiar' => [ 
		'alta' => 1, 
		'modificar' => 1 
		] 
		],
		'fecha_alta_discapacidad' => [ 
		'grupo_familiar' => [ 
		'alta' => 1, 
		'modificar' => 1 
		] 
		],
		'fecha_vencimiento' => [ 
		'grupo_familiar' => [ 
		'alta' => 1, 
		'modificar' => 1 
		] 
		],
		'nivel'	=>  [
		'escalafon' =>[
		'alta' => 1,
		'modificar' => 1
		]
		],
		'grado'	=> [
		'escalafon' =>[
		'alta' => 1,
		'modificar' => 1
		]
		],
		'grado_liquidacion'	=> [
		'escalafon' =>[
		'alta' => 1,
		'modificar' => 1
		]
		],
		'compensacion_transitoria'	=>   [
		'escalafon' =>[
		'alta' => 1,
		'modificar' => 1
		],
		],
		'agrupamiento'	=>   [
		'escalafon' =>[
		'alta' => 1,
		'modificar' => 1
		],
		],
		'tramo' =>  [
		'escalafon' =>[
			'alta' => 1,
			'modificar' => 1
		],
		],
		'ultimo_cambio_nivel' =>  [
		'escalafon' =>[
			'alta' => 1,
			'modificar' => 1
		],
		],
		'fecha_vigencia_mandato' =>  [
		'escalafon' =>[
			'alta' => 1,
			'modificar' => 1
		],
		],
		'id_sindicato' =>  [
		'escalafon' =>[
			'alta' => 1,
			'modificar' => 1
		],
		],
		'delegado_gremial' =>  [
		'escalafon' =>[
			'alta' => 1,
			'modificar' => 1
		],
		],
		'exc_art_14' =>  [
		'escalafon' =>[
			'alta' => 1,
			'modificar' => 1
		],
		],
		'formacion' =>  [
		'escalafon' =>[
			'alta' => 1,
			'modificar' => 1
		],
		],
		'titulo' =>  [
		'formacion' =>[
		'alta' => 1,
		'modificar' => 1
		],
		],
		'otros_estudios' =>  [
		'formacion' =>[
		'alta' => 1,
		'modificar' => 1
		],
		],
		'conocimientos' =>  [
		'formacion' =>[
		'alta' => 1,
		'modificar' => 1
		],
		],
		'familia_puestos' => [
		'perfil' => [
		'alta' 		=> 1,
		'modificar' => 1,
		]
		],
		'nombre_puesto' => [
		'perfil' => [
		'alta' 		=> 1,
		'modificar' => 1,
		]
		],
		'nivel_destreza' => [
		'perfil' => [
		'alta' 		=> 1,
		'modificar' => 1,
		]	
		],
		'puesto_supervisa' => [
		'perfil' => [
		'alta' 		=> 1,
		'modificar' => 1,
		]
		],
		'nivel_complejidad' => [
		'perfil' => [
		'alta' 		=> 1,
		'modificar' => 1,
		]	
		],
		'funcion_ejecutiva'	=>  [
		'escalafon' =>[
		'alta' => 1,
		'modificar' => 1
		],
		],
		'secretaria'	=>  [
		'ubicacion_estructura' => [
		'alta' => 1,
		'modificar' => 1
		],
		],

		'subsecretaria'	=>  [
		'ubicacion_estructura' => [
		'alta' => 1,
		'modificar' => 1
		],
		],
		'dir_nacional_general'	=>  [
		'ubicacion_estructura' => [
		'alta' => 1,
		'modificar' => 1
		],
		],
		'dir_simple'	=>  [
		'ubicacion_estructura' => [
		'alta' => 1,
		'modificar' => 1
		],
		],
		'coordinacion'	=>  [
		'ubicacion_estructura' => [
		'alta' => 1,
		'modificar' => 1
		],
		],
		'unidad'	=>  [
		'ubicacion_estructura' => [
		'alta' => 1,
		'modificar' => 1
		],
		],
		'denominacion_funcion' => [
		'perfil' => [
		'alta' 		=> 1,
		'modificar' => 1,
		]
		],
		'denominacion_puesto' => [
		'perfil' => [
		'alta' 		=> 1,
		'modificar' => 1,
		]
		],
		'objetivo_general' => [
		'perfil' => [
		'alta' 		=> 1,
		'modificar' => 1,
		]
		],
		'objetivo_especificos' => [
		'perfil' => [
		'alta' 		=> 1,
		'modificar' => 1,
		]
		],
		'estandares' => [
		'perfil' => [
		'alta' 		=> 1,
		'modificar' => 1,
		]
		],
		'actividades' => [
		'perfil' => [
		'alta' 		=> 1,
		'modificar' => 1,
		]
		],
		'fecha_obtencion_result' => [
		'perfil' => [
		'alta' 		=> 1,
		'modificar' => 1,
		]
		],
		'resultados_finales' => [
		'perfil' => [
		'alta' 		=> 1,
		'modificar' => 1,
		]
		],
		'acto_administrativo'	=> ['gestionar'	=> [
		'alta'	=> false,
		]],

	]
],
	'permisos'	=> [
		'Tests' => [
			'index'	=> 1
		],
		'Usuarios' => [
			'index'	=> 1,
			'alta'	=> 1,
			'modificar' => 1,
			'baja'	=> 1
		],
		'Legajos' => [
			'gestionar'	=> 1,
			'ajax_convenios_parametricos'	=> 1,
			'ajax_lista_observaciones' => 1,
			'horas_extras' => 1,
			'historial_horas_extras' => 1,
			'update_hora_extra' =>1,
			'baja_hora_extra' => 1,
			'observaciones' => 1,
			'alta_embargo' => 1,
			'historial_embargo' => 1,
			'modificar_embargo' => 1,
			'baja_embargo' => 1,
			'alta_familiar' => 1,
			'modificar_familiar' => 1,
			'exportar' => 1,
			'alta_familiar' =>1,	
			'baja_familiar' =>1,	
			'exportacion' => 1,	
			'alta' => 1,
			'ajax_art_14_parametricos' => 1,
			'ajax_sindicato' => 1,
			'mostrar_presentacion' => 1,
			'historial_presentacion'  => 1,
			'listado_anticorrupcion' => 1,
			'exportar_anticorrupcion' => 1,
			'exportacion_anticorrupcion_pdf' => 1,
			'anticorrupcion_pdf' => 1,
			'historial_anticorrupcion' => 1,
			'ajax_lista_historico_anticorrupcion' => 1,
			'presentacion' => 1,
			'update_presentacion' => 1,
			'ajax_listado_anticorrupcion' => 1,
			'ajax_ubicaciones'	=> 1,
			'agentes'			=> 1,
            'ajax_lista_agentes'           => 1,
            'buscar_cuit'                  => 1,
            'datos_globales'               => 1,
            'datos_recoleccion'            => 1,
            'ajax_datos_recoleccion'       => 1,
            'ajax_datos_vinculacion'       => 1,
            'ajax_datos_formacion'         => 1,
			'exportar_anticorrupcion_pdf'  => 1,
			'nueva_evaluacion'			   => 1,
			'historial_evaluacion'		   => 1,
			'mostrar_evaluacion'		   => 1,
			'update_evaluacion'			   => 1,
			'historial_titulo_creditos'	   => 1,
			'alta_titulo_creditos'		   => 1,
			'modificar_titulo_creditos'	   => 1,
			'ver_titulo_creditos'		   => 1,
			'mostrar_titulo_credito'	   => 1,
			'historial_cursos'			   => 1,
			'alta_curso'				   => 1,
			'buscar_curso'				   => 1,
			'modificar_curso'			   => 1,
			'baja_curso'				   => 1,
		],
		'datos_personales' => [
			'alta' =>1
		],
		'escalafon' => [
			'alta' =>1
		],
		'antiguedad' => [
			'alta' =>1
		],

		'administracion' => [ 
			'alta' =>1 
		], 
		'varios' => [ 
			'alta' =>1 
		],
		'Documentos' => [
			'listado'	=> 1,
			'ver_listado' => 1,
			'alta'	=> 1,
			'modificacion' => 1,
			'baja' => 1,
            'descargar_documento' => 1,
            'descarga_pdf'			=> true,
		],
		'Importador' => [
			'procesar_cursos' => 1
		],
		'grupo_familiar' => [ 
			'alta' =>1,
			'baja' =>1,
			'modificar' =>1,
		],
		'Tipo_discapacidad' => [ 
			'alta' =>1,
			'baja' =>1,
			'modificacion' =>1,
			'index' => 1,
		],
		'Licencias_especiales' => [ 
			'alta' =>1,
			'baja' =>1,
			'modificacion' =>1,
			'index' => 1
		],
		'Motivos_baja' => [ 
			'alta' =>1,
			'baja' =>1,
			'modificacion' =>1,
			'index' => 1
		],
		'Horarios' => [
			'index'		=> 1,
			'alta'		=> 1,
			'baja'		=> 1,
			'modificacion'	=> 1
		],
		'Escalafon' => [
			'index' => 1,
			'ajax_lista_modalidad_vinculacion' => 1,
			'lista_modalidad_vinculacion' => 1,
			'modalidad_vinculacion' => 1,
			'baja_modalidad_vinculacion' => 1,
			'ajax_lista_situacion_revista' => 1,
			'lista_situacion_revista' => 1,
			'situacion_revista' => 1,
			'baja_situacion_revista' => 1,
			'ajax_lista_niveles' => 1,
			'lista_niveles' => 1,
			'nivel' => 1,
			'baja_nivel' => 1,
			'ajax_lista_grados' => 1,
			'lista_grados' => 1,
			'grado' => 1,
			'baja_grado' => 1,
			'ajax_lista_tramos' => 1,
			'lista_tramos' => 1,
			'tramo' => 1,
			'baja_tramo' => 1,
			'ajax_get_revista' => 1,
			'ajax_lista_agrupamientos' => 1,
			'lista_agrupamientos' => 1,
			'agrupamiento' => 1,
			'baja_agrupamiento' => 1,
			'ajax_get_agrupamientos' => 1,
			'ajax_get_tramos' => 1,
	        'ajax_lista_funciones_ejecutivas' => 1,
	        'lista_funciones_ejecutivas' => 1,
	        'funcion_ejecutiva' => 1,
			'baja_funcion_ejecutiva' => 1,
			'alta' =>1,
			'designacion_transitoria' => 1,
			'ajax_designacion_transitoria' => 1,
			'agregar_prorroga' =>1,
			'editar_prorroga' =>1,
			'baja_prorroga' => 1,
			'historial_designacion' =>1, 
			'mostrar_designacion' => 1
],
'Ubicaciones_edificios' => [ 
	'alta' =>1,
	'baja' =>1,
	'modificacion' =>1,
	'index' => 1,
],
'Ubicaciones' => [ 
	'alta' =>1,
	'baja' =>1,
	'modificacion' =>1,
	'index' => 1,
	'ajax_lista_ubicaciones'=>1,
],
'Nivel_educativo' => [ 
	'alta' 			=> 1,
	'baja' 			=> 1,
	'modificacion'  => 1,
	'index' 	    => 1
],
'Sindicatos' => [ 
	'alta' =>1,
	'baja' =>1,
	'modificacion' =>1,
	'index' => 1
],
'Obras_sociales' => [ 
	'alta' =>1,
	'baja' =>1,
	'modificacion' =>1,
	'index' => 1
],
'Seguros_vida' => [ 
	'alta' =>1,
	'baja' =>1,
	'modificacion' =>1,
	'index' => 1
],
'Denominacion_funcion' => [ 
	'gestionar' =>1,
	'baja' =>1,
	'index' => 1,
	'ajax_lista_denominacion_funcion' => 1
],
'Sigeco'	=> [
	'link'	=> 0,
],
'formacion' => [
	'alta' =>1
],
'perfil' => [
	'alta'      => 1,
	'modificar' => 1,
],
'Titulos' => [
	'index'	=> 1,
	'alta'  => 1,
	'modificacion' => 1,
	'baja' => 1,
	'ajax_lista_titulos' => 1,
],
'Puestos' => [
	'index'			=> 1,
	'index_subfamilia'	=> 1,
	'index_familia_puesto'	=> 1,
	'alta'			=> 1,
	'baja'			=> 1,
	'modificacion'		=> 1,
	'alta_subfamilia'	=> 1,
	'baja_subfamilia'	=> 1,
	'modificacion_subfamilia'	=> 1,
	'alta_familia_puesto'		=> 1,
	'baja_familia_puesto'		=> 1,
	'modificacion_familia_puesto'	=> 1,
	'ajax_get_puesto'	=> 1,
	'ajax_puesto'		=>1
],
'antiguedad_grado' => [
	'alta' =>1
],
'ubicacion_estructura' => [
	'alta' =>1
],
'anticorrupcion' => [
	'alta' =>1,
],
'informes' => [
	'menu' =>1
],
'Responsable_contrato' =>	[
	'gestionar' => 1,
	'ajax_get_contratante_firmantes' => 1
],
'presupuestos' =>[ 'alta' => 1],
'Presupuestos' => [
	'alta' => 1,
	'index' => 1,
	'lista_presupuesto_saf' => 1,
	'ajax_presupuesto_saf' => 1,
	'saf' => 1,
	'baja_saf' => 1,
	'lista_presupuesto_jurisdicciones' => 1,
	'ajax_presupuesto_jurisdicciones' => 1,
	'jurisdicciones' => 1,
	'baja_jurisdicciones' => 1,
	'lista_presupuesto_ub_geograficas' => 1,
	'ajax_presupuesto_ub_geograficas' => 1,
	'ubicaciones_geograficas' => 1,
	'baja_ub_geograficas' => 1,
	'lista_presupuesto_programas' => 1,
	'ajax_presupuesto_programas' => 1,
	'programas' => 1,
	'baja_programa' => 1,
	'lista_presupuesto_actividades' => 1,
	'ajax_presupuesto_actividades' => 1,
	'actividades' => 1,
	'baja_actividad' => 1,
	'lista_presupuesto_subprogramas' => 1,
	'ajax_presupuesto_subprogramas' => 1,
	'subprograma' => 1,
	'baja_subprograma' => 1,
	'lista_presupuesto_proyectos' => 1,
	'ajax_presupuesto_proyectos' => 1,
	'proyecto' => 1,
	'baja_proyecto' => 1,
	'ajax_get_subprogramas' => 1,
	'lista_presupuesto_obras' => 1,
	'ajax_presupuesto_obras' => 1,
	'obra' => 1,
	'baja_obra' => 1,
	'ajax_presupuestos' => 1,
	'am_presupuesto' => 1,
	'ajax_get_proyectos' => 1,
	'ajax_get_obras' => 1,
	'baja_presupuesto' => 1,
],
'Responsable_contrato' =>	[
    'gestionar' => 1,
    'ajax_get_contratante_firmantes' =>1
],
'Auditorias'	=> [
	'index'	=> true,
	'json_detalle_pesquisa'	=> true,
],
'Comisiones' =>[
	'alta' => 1,
	'modificacion' => 1,
	'baja' => 1,
	'index' => 1,
],
'Dependencias' => [
	'index'	=> 1,
	'alta'  => 1,
	'baja'  => 1,
	'modificacion' => 1,
	'ajax_lista_dependencias' => 1,
	'index_informales' => 1,
	'alta_informales' => 1,
	'modificacion_informales' => 1,
	'baja_informales' => 1,
    'ajax_lista_dependencias_informales' => 1,
    'ajax_dependencias_nivel'   => 1,
    'ajax_nivel_hijos'  => 1,
],
'Familia_puestos' => [
    'index'			=> 1,
    'alta'			=> 1,
    'baja'			=> 1,
    'modificacion'	=> 1
],
'Subfamilias' => [
    'index'			=> 1,
    'alta'			=> 1,
    'baja'			=> 1,
    'modificacion'	=> 1
],
'Otros_organismos' => [
    'index'	=> 1,
    'alta'	=> 1,
    'modificacion' =>1,
    'baja'	=>1,
],
'Importador' => [
	'procesar_cursos' => 1
],
'Promocion_grados'=>[
	'alta'		=> 1,
	'index'		=> 1,
	'resumen'	=> 1,
	'descargar'	=> 1,
],
'SimuladorPromocionGrados'	=> [
	'listado_simulacion_promocion_grado' => 1,
	'agentes_promocionables'		=> 1,
	'ejecutar_simulador'			=> 1,
	'simulacion_promocion_grado'	=> 1,
],
'CreditosIniciales'	=> [
	'listar'		=> true,
	'alta'			=> true,
	'modificacion'	=> true,
	'baja'			=> true,
	'buscarAgente'	=> true,
],
	'Recibos' => [
		'index' => 1,
		'descarga' => 1
	]

	]
],
///////////////////////////////////////////////////////////////////////////////////////

	];

	public static function sin_permisos($accion){
		$vista = include (VISTAS_PATH.'/widgets/acceso_denegado_accion.php');
		return $vista;
	}

    public static function obtener_rol() {
    	return static::$rol;
    }

	public static function obtener_inicio() {
    	static::$rol= Usuarios::$usuarioLogueado['permiso'];
		static::$rol= (is_null(static::$rol))? self::ROL_DEFAULT : static::$rol ;
    	$inicio		= static::$permisos[static::$rol]['inicio'];
    	return $inicio;
    }

    public static function obtener_nombre_rol() {
    	$nombre	= static::$permisos[static::$rol]['nombre'];
    	return $nombre;
    }

 	public static function obtener_manual() {
    	$manual	= static::$permisos[static::$rol]['manual'];
    	return $manual;
    }

 	public static function obtener_atributos_visibles() {
		$atributo_visible	= static::$permisos[static::$rol]['atributos_visibles'];
		return $atributo_visible;
    }

    public static function obtener_atributos_select() {
		$atributos_select	= static::$permisos[static::$rol]['atributos_select'];
		return $atributos_select;
    }

 	public static function obtener_modalidades_vinculacion_autorizadas() {
		$flag = true;
		$rol = static::$rol;
		$atributos = [];
	    while ($flag) {
	    	if (isset(static::$permisos[$rol]['modalidades_vinculacion_autorizadas'])) {
				$atributos	= static::$permisos[$rol]['modalidades_vinculacion_autorizadas'];
				$flag = false;
			}
		    if ($flag && isset(static::$permisos[$rol]['padre'])) {
                $rol = static::$permisos[$rol]['padre'];
            } else {
                $flag = false;
            }
		}
        return $atributos;
    }
    public static function obtener_situaciones_revista_autorizadas() {
		$flag = true;
		$rol = static::$rol;
		$atributos = [];
	    while ($flag) {
	    	if (isset(static::$permisos[$rol]['situaciones_revista_autorizadas'])) {
				$atributos	= static::$permisos[$rol]['situaciones_revista_autorizadas'];
				$flag = false;
			}
		    if ($flag && isset(static::$permisos[$rol]['padre'])) {
                $rol = static::$permisos[$rol]['padre'];
            } else {
                $flag = false;
            }
		}
        return $atributos;
    }

    public static function puede_atributo($cont, $accion, $atributo, $id_atributo) {
		$flag = true;
		$rol = static::$rol;
	    while ($flag) {
		    if (isset(static::$permisos[$rol]['atributos'][$atributo][$id_atributo])) { 
		        if(isset(static::$permisos[$rol]['atributos'][$atributo][$id_atributo][$cont][$accion])) { 
		            $puede = static::$permisos[$rol]['atributos'][$atributo][$id_atributo][$cont][$accion];
		            $flag = false;
		        }
		    }

		    if ($flag && isset(static::$permisos[$rol]['padre'])) {
                $rol = static::$permisos[$rol]['padre'];
            } else {
                $flag = false;
            }
        }
	    if (!isset($puede)) { 
	        $puede = static::puede($cont, $accion);
	    }
	    return $puede;
	}

    public static function puede($cont, $accion) {
		$rol	=  Usuarios::$usuarioLogueado['permiso'];
		if($rol) {
			$puede	= parent::puede($cont, $accion);
		} else {
			$rol	= self::ROL_DEFAULT;
			$puede	= false;
            if (isset(static::$permisos[$rol]['permisos'][$cont][$accion])) {
                $puede	= static::$permisos[$rol]['permisos'][$cont][$accion];
			}
		}
		return $puede;
	}

/**
 * Se usa para consultar si un usuario logueado tiene permisos sobre el rol de otro.
 *
 * @param int $rol_externo El rol de un usuario distinto al logueado
 * @return boolean
*/
	public static function tiene_permiso_sobre($rol_externo=null){
		return in_array($rol_externo, (array)static::$permisos[static::$rol]['roles_permitidos']);
	}

	public static function obtener_listado() {
		$roles_permitidos	= static::$permisos[static::$rol]['roles_permitidos'];
		$permisos			= static::$permisos;
		foreach ($permisos as $key => $permiso) {
			if(!in_array( $key, $roles_permitidos )){
				unset($permisos[$key]);
			}
		}

		return $permisos;
	}

	public static function excepcion_permisos($modalidad = null) {		
		if($modalidad) {
			$mod = \FMT\Helper\Arr::path( \App\Modelo\Contrato::obtenerVinculacionRevista(), 'modalidad_vinculacion.'.$modalidad.'.nombre','');
	        switch (true) {
	            // case ($mod == 'Prestacion de Servicios' && self::$rol == self::ROL_DESARROLLO_RRHH):
	            //     static::$excepcion_permisos = false;
	            //     break;
	        default:
	            static::$excepcion_permisos = true;
	            break;
			}
		}
		return static::$excepcion_permisos;

	}
}
