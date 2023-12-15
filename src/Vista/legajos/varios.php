<?php
	use App\Helper\Vista;
	$vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('varios.js');
	$sindicatos = json_encode($parametricos['sindicatos']);
	$sindi =  json_encode($empleado->sindicato);

	$obras_sociales = $parametricos['obras_sociales'];
	$vars_template = [];
	$ids =  (array)$empleado->sindicato;
	array_walk($ids,function(&$value){$value = (array) $value;});
	$ids = array_column($ids, 'id_sindicato');
	$seguros = $empleado->empleado_seguro;	
	array_walk($seguros,function(&$value){$value = (array) $value;
});
	array_walk($obras_sociales, function (&$value) { $value['nombre'] = $value['codigo']. ' - ['.$value['nombre'].']';});

	$seguros = array_column($seguros, 'seguros');

	$campos_varios = [
		'TIPO_DISCAPACIDAD' 	=>  \FMT\Helper\Template::select_block($parametricos['tipo_discapacidad'], $empleado->persona->discapacidad->id_tipo_discapacidad),
		'CUD' 					=> $empleado->persona->discapacidad->cud,
		'OBSERVACIONES'    		=> $empleado->persona->discapacidad->observaciones,
		'FECHA_VENCIMIENTO'		=> ($empleado->fecha_vencimiento instanceof \DateTime) 
								? $empleado->fecha_vencimiento->format('d/m/Y') : '',
		'FECHA_VENCIMIENTO_CUD'	=> ($empleado->persona->discapacidad->fecha_vencimiento instanceof \DateTime) 
								? $empleado->persona->discapacidad->fecha_vencimiento->format('d/m/Y') : '',
		'OBRA_SOCIAL'		 	=>  \FMT\Helper\Template::select_block($obras_sociales, $empleado->empleado_salud->id_obra_social),
		'SEGUROS_VIDA'		 	=>  \FMT\Helper\Template::select_block($parametricos['seguros_vida'], $seguros),
		'VETERANO'				=> ($empleado->veterano_guerra == 1) ? "checked" : "",
		'SINDICATO'				=> \FMT\Helper\Template::select_block($parametricos['sindicatos'], $ids),
	];

   if (!empty($empleado->id) && !empty($empleado->cuit)){
	    if($permisos['varios']){
			$campos_varios['CAMPO_CREDENCIAL'][0]['CHECKED'] = ($empleado->credencial == 1) ? "checked" : "";

			$vars_template['CAMPOS_VARIOS'][0]	= [
				'FORM'		=> \App\Helper\Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}"),
				'ADJUNTAR_DOC' =>  $adjuntar_doc,
			];
		    foreach ($campos_varios as $key => $value) {
				$vars_template['CAMPOS_VARIOS'][0][$key] = $value;
			}
	    } else {

	    	$campos_varios['ADJUNTAR_DOC'] = $adjuntar_doc;
	    	$campos_varios['CAMPO_CREDENCIAL'][0]['CREDENCIAL'] = ($empleado->credencial == 1) ?   "SI" :  "NO";
	    	$campos_varios['FECHA_VENCIMIENTO'] = is_object($empleado->fecha_vencimiento) ? $empleado->fecha_vencimiento->format('d/m/Y'):'';
	    	$campos_varios['TIPO_DISCAPACIDAD'] = \FMT\Helper\Arr::path($parametricos,"tipo_discapacidad.{$empleado->persona->discapacidad->id_tipo_discapacidad}.nombre",'');
	    	$campos_varios['FECHA_VENCIMIENTO_CUD']	= is_object($empleado->persona->discapacidad->fecha_vencimiento) ? $empleado->persona->discapacidad->fecha_vencimiento->format('d/m/Y'):'';
	    	$campos_varios['CUD'] = $empleado->persona->discapacidad->cud;
	    	$campos_varios['OBRA_SOCIAL'] = \FMT\Helper\Arr::path($parametricos,"obras_sociales.{$empleado->empleado_salud->id_obra_social}.nombre",'');

	    	$campos_varios['VETERANO'] = ($empleado->veterano_guerra == 1) ?   "SI" :  "NO";

			$select = '';

	    	foreach ($empleado->empleado_seguro as $val) {
	    		$select .= (($select == '') ? '[ ' : ' ] , [ '). \FMT\Helper\Arr::path($parametricos,"seguros_vida.{$val->seguros}.nombre",'');	
	    	}

			$campos_varios['SEGUROS_VIDA'] = (($select == '') ? '' : "$select ]");

	    	foreach ($campos_varios as $key => $value) {
				$vars_template['SPAN_VARIOS'][0][$key]	= $value;
			}

	    	$select = '';
	    	foreach ($empleado->sindicato as $val) {
				foreach (\FMT\Helper\Template::select_block($parametricos['sindicatos'], $val->id_sindicato) as  $value) {
					if ($value['SELECTED']){
						$select .= (($select == '') ? '[ ' : ' ] , [ ').$value['TEXT'];
					}
				}
	    	}
			$campos_varios['SINDICATOS'] = (($select == '') ? '' : "$select ]");

	    	foreach ($campos_varios as $key => $value) {
				$vars_template['SPAN_VARIOS'][0][$key]	= $value;
			}
	    }
 	}else{
		$vars_template['AVISO'][]['MSJ'] = 'PARA DEFINIR LA <strong>VARIOS</strong>, ES REQUISITO TENER LOS DATOS BÁSICOS DEL <strong>AGENTE</strong> COMPLETOS.';
	}

	$varios = new \FMT\Template(TEMPLATE_PATH.'/legajos/varios.html', $vars_template,['CLEAN'=>false]);
	$vista->add_to_var('vars',$vars_vista);