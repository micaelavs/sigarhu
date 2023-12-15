<?php
	$vars = [];
	$tab_administracion =
	$tab_antiguedad = 
	$tab_datos_personales =
	$tab_escalafonaria  =
	$tab_formacion =
	$tab_perfiles_puestos =
	$tab_presupuesto =
	$tab_ubicacion_estructura =
	$tab_anticorrupcion =
	$tab_embargo =
	$tab_varios = 
	$tab_grupo_familiar = false;


	if($select_tab) {
		//Variable variable que setea el tab activo;
		$$select_tab = true;
	}else{
		$tab_datos_personales = true;
	}

	/** ADJUNTO DE DOCUMENTOS*/
	$adjuntar_doc = '';
	if ($empleado->id) {
		$datos_emp['DATOS_EMPLEADO'][] = [
			'ID_EMP' 		=> $empleado->id,
			'FORM_ADJUNTAR' => \App\Helper\Vista::get_url("index.php/documentos/listado/{$empleado->cuit}")
		];
		$adjuntar_doc = new \FMT\Template(TEMPLATE_PATH.'/legajos/carga_documentos.html',$datos_emp,['CLEAN'=>false]);
	}
	/**--------------------------------------*/

	/** BLOQUE DE DATOS PERSONALES */
	include 'datos_personales.php';
	$vars['TAB'][] = [
		'NOMBRE_TAB' => 'datos_personales',
		'TITULO_TAB' => 'Datos Personales',
		'SELECTED' => !empty($tab_datos_personales)? "true" : "false",
		'ACTIVE' => !empty($tab_datos_personales)? " active" : "",
		'ACTIVO_LINK' => !empty($tab_datos_personales)? " active" : "",
		'ID_BLOQUE'		=> \App\Helper\Bloques::DATOS_PERSONALES
		];

	$vars['BLOQUE_TAB'][] = [
		'NOMBRE_TAB' => 'datos_personales',
		'CONTENT_TAB' => "{$datos_personales}",
		'CONTENT_TAB_ACTIVE' => !empty($tab_datos_personales)? " in active" : ""
	];
	/**--------------------------------------*/ 

	/** BLOQUE DE SITUACION ESCALAFONARIA */
	include 'escalafonario.php';

	$vars['TAB'][] = [
		'NOMBRE_TAB' => 'escalafonaria',
		'TITULO_TAB' => 'Situación Escalafonaria',
		'SELECTED' => !empty($tab_escalafonaria)? "true" : "false",
		'ACTIVE' => !empty($tab_escalafonaria)? " active" : "",
		'ACTIVO_LINK' => !empty($tab_escalafonaria)? " active" : "",
		'ID_BLOQUE'		=> \App\Helper\Bloques::SITUACION_ESCALAFONARIA
];

	$vars['BLOQUE_TAB'][] = [
		'NOMBRE_TAB' => 'escalafonaria',
		'CONTENT_TAB' => "{$escalafonaria}",
		'CONTENT_TAB_ACTIVE' => !empty($tab_escalafonaria)? " in active" : ""

	];
	/**--------------------------------------*/ 

	/** BLOQUE DE UBICACION EN LA ESTRUCTURA */
	include 'ubicacion_estructura.php';	

	$dispara	= '';
	if($empleado->dependencia->id){
		$dispara = "$('#btn_buscar_estructura').click()";
	}

	$vars['TAB'][] = [
		'NOMBRE_TAB' => 'ubicacion_estructura',
		'TITULO_TAB' => 'Ubicación en la Estructura',
		'SELECTED' => ($tab_ubicacion_estructura)? "true" : "false",
		'ACTIVE' => ($tab_ubicacion_estructura)? " active" : "",
		'ACTIVO_LINK' => ($tab_ubicacion_estructura)? " active" : "",
		'ID_BLOQUE'		=> \App\Helper\Bloques::UBICACION_ESTRUCTURA
//		'DISPARA_ESTRUCTURA' => "{$dispara}"
	];

	$vars['BLOQUE_TAB'][] = [
		'NOMBRE_TAB' => 'ubicacion_estructura',
		'CONTENT_TAB' => "$ubicacion_estructura",
		'CONTENT_TAB_ACTIVE' => !empty($tab_ubicacion_estructura)? " in active" : ""
	];
	/**--------------------------------------*/ 
	/** BLOQUE DE PERFILES DE PUESTOS */ 
	include 'perfiles_puestos.php';

	$vars['TAB'][] = [
		'NOMBRE_TAB' => 'perfiles_puestos',
		'TITULO_TAB' => 'Perfiles de Puestos',
		'SELECTED' => !empty($tab_perfiles_puestos)? "true" : "false",
		'ACTIVE' => !empty($tab_perfiles_puestos)? " active" : "",
		'ACTIVO_LINK' => !empty($tab_perfiles_puestos)? " active" : "",
		'ID_BLOQUE'		=> \App\Helper\Bloques::PERFILES_PUESTO
	];

	$vars['BLOQUE_TAB'][] = [
		'NOMBRE_TAB' => 'perfiles_puestos',
		'CONTENT_TAB' => "$perfiles_puestos",
		'CONTENT_TAB_ACTIVE' => !empty($tab_perfiles_puestos)? " in active" : ""
	];
	/**--------------------------------------*/ 

	/** BLOQUE DE FORMACION */
	include 'formacion.php';

	$vars['TAB'][] = [
		'NOMBRE_TAB' => 'formacion_educativa',
		'TITULO_TAB' => 'Formación',
		'SELECTED' => !empty($tab_formacion)? "true" : "false",
		'ACTIVE' => !empty($tab_formacion)? " active" : "",
		'ACTIVO_LINK' => !empty($tab_formacion)? " active" : "",
		'ID_BLOQUE'		=> \App\Helper\Bloques::FORMACION
	];

	$vars['BLOQUE_TAB'][] = [
		'NOMBRE_TAB' => 'formacion_educativa',
		'CONTENT_TAB' => "$formacion",
		'CONTENT_TAB_ACTIVE' => !empty($tab_formacion)? " in active" : ""
	];

	/**--------------------------------------*/ 

	/** BLOQUE DE ANTIGüEDAD */
	include 'antiguedad.php';

	$vars['TAB'][] = [
		'NOMBRE_TAB' => 'antiguedad',
		'TITULO_TAB' => 'Antigüedad',
		'SELECTED' => !empty($tab_antiguedad)? "true" : "false",
		'ACTIVE' => !empty($tab_antiguedad)? " active" : "",
		'ACTIVO_LINK' => !empty($tab_antiguedad)? " active" : "",
		'ID_BLOQUE'		=> \App\Helper\Bloques::ANTIGUEDAD
	];

	$vars['BLOQUE_TAB'][] = [
		'NOMBRE_TAB' => 'antiguedad',
		'CONTENT_TAB' => "$antiguedad",
		'CONTENT_TAB_ACTIVE' => !empty($tab_antiguedad)? " in active" : ""
	];

	/**--------------------------------------*/ 

	/** BLOQUE DE ADMINISTRACION */
	include 'administracion.php';

	$vars['TAB'][] = [
		'NOMBRE_TAB' => 'administracion',
		'TITULO_TAB' => 'Administración',
		'SELECTED' => !empty($tab_administracion)? "true" : "false",
		'ACTIVE' => !empty($tab_administracion)? " active" : "",
		'ACTIVO_LINK' => !empty($tab_administracion)? " active" : "",
		'ID_BLOQUE'		=> \App\Helper\Bloques::ADMINISTRACION
	];

	$vars['BLOQUE_TAB'][] = [
		'NOMBRE_TAB' => 'administracion',
		'CONTENT_TAB' => "$administracion",
		'CONTENT_TAB_ACTIVE' => !empty($tab_administracion)? " in active" : ""
	];

	/**--------------------------------------*/ 

	/** BLOQUE DE VARIOS */
	include 'varios.php';

	$vars['TAB'][] = [
		'NOMBRE_TAB' => 'varios',
		'TITULO_TAB' => 'Varios',
		'SELECTED' => !empty($tab_varios)? "true" : "false",
		'ACTIVE' => !empty($tab_varios)? " active" : "",
		'ACTIVO_LINK' => !empty($tab_varios)? " active" : "",
		'ID_BLOQUE'		=> \App\Helper\Bloques::VARIOS
	];

	$vars['BLOQUE_TAB'][] = [
		'NOMBRE_TAB' => 'varios',
		'CONTENT_TAB' => "$varios",
		'CONTENT_TAB_ACTIVE' => !empty($tab_varios)? " in active" : ""
	];

	/**--------------------------------------*/ 

	/** BLOQUE DE PRESUPUESTO */
	include 'presupuesto.php';
	if(isset($presupuesto)) {
		$vars['TAB'][] = [
			'NOMBRE_TAB' => 'presupuesto',
			'TITULO_TAB' => 'Presupuesto',
			'SELECTED' => !empty($tab_presupuesto)? "true" : "false",
			'ACTIVE' => !empty($tab_presupuesto)? " active" : "",
			'ACTIVO_LINK' => !empty($tab_presupuesto)? " active" : "",
			'ID_BLOQUE'		=> \App\Helper\Bloques::PRESUPUESTO
		];

		$vars['BLOQUE_TAB'][] = [
			'NOMBRE_TAB' => 'presupuesto',
			'CONTENT_TAB' => "$presupuesto",
			'CONTENT_TAB_ACTIVE' => !empty($tab_presupuesto)? " in active" : ""
		];
	}
	/**--------------------------------------*/

	/** BLOQUE DE ANTICORRUPCION */
	include 'anticorrupcion.php';
	if(isset($anticorrupciones)) {
		$vars['TAB'][] = [
			'NOMBRE_TAB'	=> 'anticorrupcion',
			'TITULO_TAB'	=> 'Anticorrupción',
			'SELECTED'		=> !empty($tab_anticorrupcion)? "true" : "false",
			'ACTIVE'		=> !empty($tab_anticorrupcion)? " active" : "",
			'ACTIVO_LINK'	=> !empty($tab_anticorrupcion)? " active" : "",
			'ID_BLOQUE'		=> \App\Helper\Bloques::ANTICORRUPCION
		];

		$vars['BLOQUE_TAB'][] = [
			'NOMBRE_TAB' => 'anticorrupcion',
			'CONTENT_TAB' => "$anticorrupciones",
			'CONTENT_TAB_ACTIVE' => !empty($tab_anticorrupcion)? " in active" : ""
		];
	}
	/**--------------------------------------*/

	/** BLOQUE DE GRUPO FAMILIAR */
	include 'grupo_familiar.php';
	$vars['TAB'][] = [
		'NOMBRE_TAB' => 'grupo_familiar',
		'TITULO_TAB' => 'Grupo Familiar',
		'SELECTED' => !empty($tab_grupo_familiar)? "true" : "false",
		'ACTIVE' => !empty($tab_grupo_familiar)? " active" : "",
		'ACTIVO_LINK' => !empty($tab_grupo_familiar)? " active" : "",
		'ID_BLOQUE'		=> \App\Helper\Bloques::GRUPO_FAMILIAR
	];

	$vars['BLOQUE_TAB'][] = [
		'NOMBRE_TAB' => 'grupo_familiar',
		'CONTENT_TAB' => "$grupo_familiar",
		'CONTENT_TAB_ACTIVE' => !empty($tab_grupo_familiar)? " in active" : ""
	];
	/**--------------------------------------*/


	if($empleado->id) {
		include 'observaciones.php';
		$vars['CONTENT_OBS'] = "$observaciones";
	}

		/** BLOQUE DE EMBARGO */
	include 'embargo.php';
	if(isset($embargos)) {
		$vars['TAB'][] = [
			'NOMBRE_TAB'	=> 'embargo',
			'TITULO_TAB'	=> 'Embargo',
			'SELECTED'		=> !empty($tab_embargo)? "true" : "false",
			'ACTIVE'		=> !empty($tab_embargo)? " active" : "",
			'ACTIVO_LINK'	=> !empty($tab_embargo)? " active" : "",
			'ID_BLOQUE'		=> \App\Helper\Bloques::EMBARGO
		];

		$vars['BLOQUE_TAB'][] = [
			'NOMBRE_TAB' => 'embargo',
			'CONTENT_TAB' => "$embargos",
			'CONTENT_TAB_ACTIVE' => !empty($tab_embargo)? " in active" : ""
		];
	}
	/**--------------------------------------*/


	include 'observaciones.php';
	$vars['CONTENT_OBS'] = "$observaciones";
	

	$template = new \FMT\Template(TEMPLATE_PATH.'/solapas.html',$vars,['CLEAN'=>false]);

	$vars_vista['CONTENT'] = "{$template}";
	$vars_vista['SUBTITULO'] = 'Gestion de Legajos';

	$base_url = \App\Helper\Vista::get_url('index.php');
	$empleado_creado = (!empty($empleado->id) ? 'true' : 'false');
	$empleado_domicilio = (!empty($empleado->persona->domicilio->id)?'true':'false');
	$empleado_escalafonaria = (!empty($empleado->situacion_escalafonaria->id)?'true':'false');
	$empleado_ubicacion = (!empty($empleado->ubicacion->id)?'true':'false');
	$empleado_licencia = (!empty($empleado->licencia->id)?'true':'false');
	$no_bloquear	= (!empty($no_bloquear)?'true':'false');
	$ubicaciones	= json_encode($parametricos['ubicaciones'], JSON_UNESCAPED_UNICODE);
	$ubicacion_provincia	= json_encode($parametricos['ubicacion_regiones'], JSON_UNESCAPED_UNICODE);
	$entidades	= json_encode($parametricos['entidades'], JSON_UNESCAPED_UNICODE);
	$is_admin	= (in_array(\App\Modelo\AppRoles::obtener_rol(),[\App\Modelo\AppRoles::ROL_ADMINISTRACION_RRHH, \App\Modelo\AppRoles::ROL_CONVENIOS ])) ? 'true' : 'false';

	$formacion_cursos_tipo_promocion	= json_encode([
		'tipo_grado' => \App\Modelo\Curso::PROMOCION_GRADO,
		'tipo_tramo' => \App\Modelo\Curso::PROMOCION_TRAMO,
	], JSON_UNESCAPED_UNICODE);
	
	$config	= FMT\Configuracion::instancia();

	$vars_vista['JS'][]['JS_CODE']	= <<<JS
	var \$base_url = "{$base_url}";
	var \$empleado_creado = {$empleado_creado};
	var \$empleado_domicilio = {$empleado_domicilio};
	var \$empleado_escalafonaria = {$empleado_escalafonaria};
	var \$empleado_ubicacion = {$empleado_ubicacion};
	var \$empleado_licencia = {$empleado_licencia};
	var \$ubicaciones = {$ubicaciones};
	var \$ubicacion_provincia = {$ubicacion_provincia};
	var \$entidades = {$entidades};
	var \$no_bloquear = {$no_bloquear};
	var \$sindicatos = {$sindicatos};
	var \$sindi = {$sindi};
	var \$is_admin = {$is_admin};
	var \$formacion_cursos_tipo_promocion = {$formacion_cursos_tipo_promocion};

JS;
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('cropit.js');
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('script.js');
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('multiple-input-area.js');
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('legajos.js').'?'.filectime('./js/legajos.js');
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT']		= \App\Helper\Vista::get_url('formacion_ajax.js');

	$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('administracion.js');
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('tabs.js') . '?' . filectime('./js/tabs.js');
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT']   = \App\Helper\Vista::get_url('legajos_historial_cursos.js');

	$vars_vista['CSS_FILES'][]	= ['CSS_FILE' => $config['app']['endpoint_cdn'].'/datatables/1.10.12/datatables.min.css'];
	$vars_vista['CSS_FILES'][]	= ['CSS_FILE' => \App\Helper\Vista::get_url('legajos.css')];
	$vars_vista['CSS_FILES'][]	= ['CSS_FILE' => \App\Helper\Vista::get_url('funkyradio.css')];
	$vars_vista['JS_FILES'][]	= ['JS_FILE' => $config['app']['endpoint_cdn']."/datatables/1.10.12/datatables.min.js"];	
	$vars_vista['JS_FILES'][]	= ['JS_FILE' => $config['app']['endpoint_cdn']."/datatables/defaults.js"];
	$vars_vista['JS_FILES'][]	= ['JS_FILE' => $config['app']['endpoint_cdn']."/datatables/1.10.12/plugins/sorting/datetime-moment.js"];
	$vars_vista['VOLVER']		= $parametricos['boton_volver'];
	
	$vista->add_to_var('vars',$vars_vista);
	return true;