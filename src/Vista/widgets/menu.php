<?php
	use \App\Modelo\AppRoles;


		$menu		= new \FMT\Menu();
		$config = FMT\Configuracion::instancia();
		if($config['app']['dev']) {	
			$menu->activar_dev();
		}
		$opcion1	= $menu->agregar_opcion('GESTION');
		if(
			AppRoles::puede('Usuarios','index')
		||	AppRoles::puede('Dependencias','index')
		||	AppRoles::puede('Licencias_especiales','index')
		||	AppRoles::puede('Motivos_baja','index')
		||	AppRoles::puede('Tipo_discapacidad','index')
		||	AppRoles::puede('Titulos','index')
		||	AppRoles::puede('Escalafon','index')
		||	AppRoles::puede('Nivel_educativo','index')
		||	AppRoles::puede('Ubicaciones_edificios','index')
		||	AppRoles::puede('Sindicatos','index')
		||	AppRoles::puede('Ubicaciones','index')
		||	AppRoles::puede('Obras_sociales', 'index')
		||	AppRoles::puede('Seguros_vida', 'index')
		||	AppRoles::puede('Presupuestos','index')
		||	AppRoles::puede('Puestos','index_familia_puesto')
		||	AppRoles::puede('Puestos','index_subfamilia')
		||	AppRoles::puede('Puestos','index')
		||	AppRoles::puede('Responsable_contrato','gestionar')
		||	AppRoles::puede('Denominacion_funcion','index')
		||	AppRoles::puede('Otros_organismos','index')
		||	AppRoles::puede('PromocionCredios','index')
        ) {
			$opcion2	= $menu->agregar_opcion('ABMs');
		}

		if(AppRoles::puede('informes','menu')) {
			$opcion3	= $menu->agregar_opcion('INFORMES');
		}

		if(AppRoles::puede('Importador','procesar_cursos')) {
			$opcion4	= $menu->agregar_opcion('IMPORTADOR');
			$opcion4->agregar_link('Importar Cursos', \App\Helper\Vista::get_url('index.php').'/importador/procesar_cursos', \FMT\Opcion::COLUMNA1);
		}
		//-----------------------------------------------------------//
		//-----------------------------------------------------------//

		if(AppRoles::puede('Legajos','gestionar')) {
			$opcion1->agregar_titulo('Legajos', \FMT\Opcion::COLUMNA1);
			
			$opcion1->agregar_link('Por Agentes', \App\Helper\Vista::get_url('index.php').'/legajos/agentes', \FMT\Opcion::COLUMNA1);
		}

		if(AppRoles::puede('Legajos','buscar_cuit')) {
			$opcion1->agregar_link('Por Cuit', \App\Helper\Vista::get_url('index.php').'/legajos/buscar_cuit', \FMT\Opcion::COLUMNA1);
		}

		if(AppRoles::puede('CreditosIniciales','listar')) {
			$opcion1->agregar_link('Creditos Iniciales', \App\Helper\Vista::get_url('index.php').'/CreditosIniciales/listar', \FMT\Opcion::COLUMNA1);
		}
		//-----------------------------------------------------------//
		//-----------------------------------------------------------//
		

		if(AppRoles::puede('Usuarios','index')) {		
			$opcion2->agregar_titulo('Administración del sistema', \FMT\Opcion::COLUMNA1);

			$opcion2->agregar_link('Usuarios', \App\Helper\Vista::get_url('index.php/usuarios/index'), \FMT\Opcion::COLUMNA1);
		}
    //-----------------------------------------------------------//
	//-----------------------------------------------------------//
		if(isset($opcion2)) {
        	$opcion2->agregar_titulo('Administración de recursos', \FMT\Opcion::COLUMNA1);	
		}

		if(AppRoles::puede('Dependencias','index')) {
	        $opcion2->agregar_link('Dependencias', \App\Helper\Vista::get_url('index.php/dependencias/index'), \FMT\Opcion::COLUMNA1);
    	}

		if(AppRoles::puede('Dependencias','index_informales')) {
			$opcion2->agregar_link('Dependencias Informales', \App\Helper\Vista::get_url('index.php/dependencias/index_informales'), \FMT\Opcion::COLUMNA1);
		}

		if(AppRoles::puede('Titulos','index')) {
	        $opcion2->agregar_link('Títulos', \App\Helper\Vista::get_url('index.php/titulos/index'), \FMT\Opcion::COLUMNA1);
    	}


		
		if(AppRoles::puede('Escalafon','index')) {
			$opcion2->agregar_titulo(' ', \FMT\Opcion::COLUMNA1);

			$opcion2->agregar_link('Modalidad de Vinculación', \App\Helper\Vista::get_url('index.php/escalafon/lista_modalidad_vinculacion'), \FMT\Opcion::COLUMNA1);

			$opcion2->agregar_link('Situación de Revista', \App\Helper\Vista::get_url('index.php/escalafon/lista_situacion_revista'), \FMT\Opcion::COLUMNA1);

			$opcion2->agregar_link('Agrupamientos', \App\Helper\Vista::get_url('index.php/escalafon/lista_agrupamientos'), \FMT\Opcion::COLUMNA1);

			$opcion2->agregar_link('Tramos', \App\Helper\Vista::get_url('index.php/escalafon/lista_tramos'), \FMT\Opcion::COLUMNA1);

			$opcion2->agregar_link('Niveles', \App\Helper\Vista::get_url('index.php/escalafon/lista_niveles'), \FMT\Opcion::COLUMNA1);

			$opcion2->agregar_link('Grados', \App\Helper\Vista::get_url('index.php/escalafon/lista_grados'), \FMT\Opcion::COLUMNA1);

			$opcion2->agregar_link('Funciones Ejecutivas', \App\Helper\Vista::get_url('index.php/escalafon/lista_funciones_ejecutivas'), \FMT\Opcion::COLUMNA1);


			$opcion2->agregar_titulo('&nbsp;', \FMT\Opcion::COLUMNA2);

			$opcion2->agregar_titulo('&nbsp;', \FMT\Opcion::COLUMNA2);
		}

    	if(AppRoles::puede('Presupuestos','index')) {
    		$opcion2->agregar_titulo('Gestión de Presupuestos', \FMT\Opcion::COLUMNA1);

			$opcion2->agregar_link('Presupuestos', \App\Helper\Vista::get_url('index.php/presupuestos/index'), \FMT\Opcion::COLUMNA1);
	        $opcion2->agregar_link('Códigos SAF', \App\Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_saf'), \FMT\Opcion::COLUMNA1);

	        $opcion2->agregar_link('Códigos Jurisdicciones', \App\Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_jurisdicciones'), \FMT\Opcion::COLUMNA1);

	        $opcion2->agregar_link('Códigos Ubicaciones Geográficas', \App\Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_ub_geograficas'), \FMT\Opcion::COLUMNA1);

	        $opcion2->agregar_link('Códigos Programas', \App\Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_programas'), \FMT\Opcion::COLUMNA1);
			
			$opcion2->agregar_titulo('+ ', \FMT\Opcion::COLUMNA2);
			$opcion2->agregar_titulo('... ', \FMT\Opcion::COLUMNA2);

	        $opcion2->agregar_link('Códigos Subprogramas', \App\Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_subprogramas'), \FMT\Opcion::COLUMNA2);

	        $opcion2->agregar_link('Códigos Proyectos', \App\Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_proyectos'), \FMT\Opcion::COLUMNA2);

	        $opcion2->agregar_link('Códigos Actividades', \App\Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_actividades'), \FMT\Opcion::COLUMNA2);

	        $opcion2->agregar_link('Códigos Obras', \App\Helper\Vista::get_url('index.php/presupuestos/lista_presupuesto_obras'), \FMT\Opcion::COLUMNA2);

    	}



		if(AppRoles::puede('Motivos_baja','index')) {
			$opcion2->agregar_link('Motivos de Baja', \App\Helper\Vista::get_url('index.php/Motivos_baja/index'), \FMT\Opcion::COLUMNA2);
		}

		if(AppRoles::puede('Nivel_educativo','index')) {
			$opcion2->agregar_link('Nivel Educativo', \App\Helper\Vista::get_url('index.php/Nivel_educativo/index'), \FMT\Opcion::COLUMNA2);
		}

		if(AppRoles::puede('Licencias_especiales','index')) {
			$opcion2->agregar_link('Licencias Especiales', \App\Helper\Vista::get_url('index.php/Licencias_especiales/index'), \FMT\Opcion::COLUMNA2);
		}

    	if(AppRoles::puede('Tipo_discapacidad','index')) {
        $opcion2->agregar_link('Tipo Discapacidad', \App\Helper\Vista::get_url('index.php/tipo_discapacidad/index'), \FMT\Opcion::COLUMNA2);
    	}

		if(AppRoles::puede('Sindicatos','index')) {
        $opcion2->agregar_link('Sindicatos', \App\Helper\Vista::get_url('index.php/sindicatos/index'), \FMT\Opcion::COLUMNA2);
    	}

    	if(AppRoles::puede('Obras_sociales','index')) {
			$opcion2->agregar_link('Obras Sociales', \App\Helper\Vista::get_url('index.php/obras_sociales/index'), \FMT\Opcion::COLUMNA2);
		}

    	if(AppRoles::puede('Seguros_vida','index')) {
			$opcion2->agregar_link('Seguros de Vida', \App\Helper\Vista::get_url('index.php/seguros_vida/index'), \FMT\Opcion::COLUMNA2);
		}

    	if(AppRoles::puede('Horarios','index')) {
			$opcion2->agregar_link('Plantillas Horarias', \App\Helper\Vista::get_url('index.php/horarios/index'), \FMT\Opcion::COLUMNA2);
		}		


    	if(AppRoles::puede('Ubicaciones_edificios','index')) {
			$opcion2->agregar_titulo(' ', \FMT\Opcion::COLUMNA2);
			$opcion2->agregar_titulo(' ', \FMT\Opcion::COLUMNA1);

			$opcion2->agregar_link('Edificios', \App\Helper\Vista::get_url('index.php/Ubicaciones_edificios/index'), \FMT\Opcion::COLUMNA1);
		}

    	if(AppRoles::puede('Ubicaciones','index')) {
			$opcion2->agregar_link('Ubicaciones', \App\Helper\Vista::get_url('index.php/Ubicaciones/index'), \FMT\Opcion::COLUMNA1);
		}

		if(AppRoles::puede('Otros_organismos','index')) {
			$opcion2->agregar_link('Otros Organismos', \App\Helper\Vista::get_url('index.php/Otros_organismos/index'), \FMT\Opcion::COLUMNA1);
		}
		
		if(AppRoles::puede('PromocionCreditos','index')) {
			$opcion2->agregar_link('Créditos para Promoción', \App\Helper\Vista::get_url('index.php/PromocionCreditos/index'), \FMT\Opcion::COLUMNA1);
		}

		//-----------------------------------------------------------//
		//-----------------------------------------------------------//
		
		if(AppRoles::puede('informes', 'menu') && AppRoles::puede('Legajos','historial_anticorrupcion')) {
			$opcion3->agregar_link('Historico Anticorrupción', \App\Helper\Vista::get_url('index.php/legajos/historial_anticorrupcion'), \FMT\Opcion::COLUMNA1);
		}

		if (AppRoles::puede('informes', 'menu') && AppRoles::puede('Legajos', 'listado_anticorrupcion')) {
			$opcion3->agregar_link('Alertas Anticorrupción', \App\Helper\Vista::get_url('index.php/legajos/listado_anticorrupcion'), \FMT\Opcion::COLUMNA1);
		}

		if (AppRoles::puede('informes', 'menu') && AppRoles::puede('Escalafon', 'designacion_transitoria')) {
			$opcion3->agregar_link('Designación transitoria', \App\Helper\Vista::get_url('index.php/escalafon/designacion_transitoria'), \FMT\Opcion::COLUMNA1);
		}

		if(AppRoles::puede('informes', 'menu') && AppRoles::puede('Legajos','datos_globales')) {
			$opcion3->agregar_link('Datos Globales', \App\Helper\Vista::get_url('index.php/legajos/datos_globales'), \FMT\Opcion::COLUMNA1);
		}

		if (AppRoles::puede('informes', 'menu') && AppRoles::puede('Auditorias', 'index')) {
			$opcion3->agregar_link('Auditoria', \App\Helper\Vista::get_url('index.php/auditorias/index'), \FMT\Opcion::COLUMNA1);
		}

		if(AppRoles::puede('SimuladorPromocionGrados','agentes_promocionables')){

		    $opcion3->agregar_link('Simulador Promocion de Grados',\App\Helper\Vista::get_url('index.php/SimuladorPromocionGrados/agentes_promocionables'), \FMT\Opcion::COLUMNA1);
        }
            if(AppRoles::puede('Promocion_grados','index')) {
                $opcion3->agregar_link('Listado Promociones', \App\Helper\Vista::get_url('index.php/Promocion_grados/index'), \FMT\Opcion::COLUMNA1);
            }

    //-----------------------------------------------------------//
		//-----------------------------------------------------------//


		if(AppRoles::puede('Responsable_contrato','gestionar')) {
			$opcion2->agregar_link('Responsables Contratos', \App\Helper\Vista::get_url('index.php/Responsable_contrato/gestionar'), \FMT\Opcion::COLUMNA1);
		}

		if(AppRoles::puede('Denominacion_funcion','index')) {
			$opcion2->agregar_link('Denominación Función', \App\Helper\Vista::get_url('index.php/Denominacion_funcion/index'), \FMT\Opcion::COLUMNA1);
		}

		if (AppRoles::puede('Comisiones', 'index')) {
			$opcion2->agregar_link('Organismos Origen/Destino', \App\Helper\Vista::get_url('index.php/comisiones/index'), \FMT\Opcion::COLUMNA1);
		}

		if(AppRoles::puede('Puestos','index_familia_puesto')) {
			$opcion2->agregar_link('Familia de Puestos', \App\Helper\Vista::get_url('index.php/Puestos/index_familia_puesto'), \FMT\Opcion::COLUMNA1);
		}

		if(AppRoles::puede('Puestos','index_subfamilia')) {
			$opcion2->agregar_link('Subfamilias de Puestos', \App\Helper\Vista::get_url('index.php/Puestos/index_subfamilia'), \FMT\Opcion::COLUMNA1);

		}
		if(AppRoles::puede('Puestos','index')) {
			$opcion2->agregar_link('Puestos', \App\Helper\Vista::get_url('index.php/Puestos/index'), \FMT\Opcion::COLUMNA1);
		}
		if(AppRoles::puede('Sigeco','link')) {
			$menu->agregar_link('<i class="fa fa-external-link" aria-hidden="true"></i> SIGECO',\App\Helper\Vista::get_url('../sigeco'));
		}

		if(AppRoles::puede('Manuales','index')) {
			$menu->agregar_manual(\App\Helper\Vista::get_url('index.php/Manuales/index'));
		}

		$menu->agregar_salir($config['app']['endpoint_panel'].'/logout.php');

		$vars['CABECERA'] = "{$menu}";
		$vista->add_to_var('vars', $vars);
		return true;
