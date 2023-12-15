<?php
use App\Helper\Vista;
$vars_template = [];
$bloque =\App\Helper\Bloques::GRUPO_FAMILIAR;
$input = 'CAMPOS_FAMILIAR';
if (!empty($familiar)){
    if($permisos['bloque_grupo_familiar']){

    	foreach ($permisos['bloque_grupo_familiar'] as $campo => $permiso) {
			switch ($campo) {
				case 'parentesco':
					if($permiso) {
						$vars_template[$input][0]['C_PARENTESCO'][0]['PARENTESCO'] = \FMT\Helper\Template::select_block($parametricos['parentesco'], $familiar->parentesco);
					} else {
						$vars_template[$input][0]['SPAN_PARENTESCO'][0]['VALUE'] = $parametricos['parentesco'][$familiar->parentesco]['nombre'];
						$vars_template[$input][0]['SPAN_PARENTESCO'][0]['ID']	 = $familiar->parentesco;
					}
					break;

				case 'nombre':
					if($permiso) {
						$vars_template[$input][0]['C_NOMBRE'][0]['NOMBRE'] = $familiar->nombre;
					} else {
						$vars_template[$input][0]['SPAN_NOMBRE'][0]['VALUE'] = $familiar->nombre;
					}
					break;

				case 'apellido':
					if($permiso) {
						$vars_template[$input][0]['C_APELLIDO'][0]['APELLIDO'] = $familiar->apellido;
					} else {
						$vars_template[$input][0]['SPAN_APELLIDO'][0]['VALUE'] = $familiar->apellido;
					}
					break;

				case 'fecha_nacimiento':
					if($permiso) {
						$vars_template[$input][0]['C_FECHA_NAC'][0]['FECHA_NACIMIENTO'] = is_object($familiar->fecha_nacimiento) ? $familiar->fecha_nacimiento->format('d/m/Y'):'';
					} else {
						$vars_template[$input][0]['SPAN_FECHA_NAC'][0]['VALUE'] = is_object($familiar->fecha_nacimiento) ? $familiar->fecha_nacimiento->format('d/m/Y'):'';
					}
					break;

				case 'tipo_documento':
					if($permiso) {
						$vars_template[$input][0]['C_TDOCUMENTO'][0]['TIPO_DOCUMENTO'] = \FMT\Helper\Template::select_block($parametricos['tipo_documento'], $familiar->tipo_documento);
					} else {
						$vars_template[$input][0]['SPAN_TDOCUMENTO'][0]['VALUE'] = $parametricos['tipo_documento'][$familiar->tipo_documento]['nombre'];
						$vars_template[$input][0]['SPAN_TDOCUMENTO'][0]['ID'] 	 = $familiar->parentesco;
					}
					break;

				case 'documento':
					if($permiso) {
						$vars_template[$input][0]['C_DOCUMENTO'][0]['DOCUMENTO'] = $familiar->documento;
					} else {
						$vars_template[$input][0]['SPAN_DOCUMENTO'][0]['VALUE'] = $familiar->documento;
					}
					break;

				case 'nacionalidad':
					if($permiso) {
						$vars_template[$input][0]['C_NACIONALIDAD'][0]['NACIONALIDAD'] = \FMT\Helper\Template::select_block($parametricos['nacionalidad'], $familiar->nacionalidad);
					} else {
						$vars_template[$input][0]['SPAN_NACIONALIDAD'][0]['VALUE'] = $parametricos['nacionalidad'][$familiar->nacionalidad]['nombre'];
						$vars_template[$input][0]['SPAN_NACIONALIDAD'][0]['ID']    = $familiar->nacionalidad;
					}
					break;

				case 'nivel_educativo':
					if($permiso) {
						$vars_template[$input][0]['C_NIVEL_EDUCATIVO'][0]['NIVEL_EDUCATIVO'] = \FMT\Helper\Template::select_block($parametricos['nivel_educativo'], $familiar->nivel_educativo);
					} else {
						$vars_template[$input][0]['SPAN_NIVEL_EDUCATIVO'][0]['VALUE'] = $parametricos['nivel_educativo'][$familiar->nivel_educativo]['nombre'];
						$vars_template[$input][0]['SPAN_NIVEL_EDUCATIVO'][0]['ID']	  = $familiar->nivel_educativo;
					}
					break;

				case 'desgrava_afip':
					if($permiso) {
						$vars_template[$input][0]['C_DESGRAVA_AFIP'][0]['DESGRAVA_AFIP'] = \FMT\Helper\Template::select_block($parametricos['porcentaje_desgrava'],  $familiar->desgrava_afip);
					} else {
						$vars_template[$input][0]['SPAN_DESGRAVA_AFIP'][0]['VALUE'] = ($familiar->desgrava_afip) ? $parametricos['porcentaje_desgrava'][$familiar->desgrava_afip]['nombre'] : 0;
						$vars_template[$input][0]['SPAN_DESGRAVA_AFIP'][0]['ID']	= $familiar->desgrava_afip;

					}
					break;

				case 'fecha_desde':
					if($permiso) {
						$vars_template[$input][0]['C_FECHA_DESDE'][0]['FECHA_DESDE'] = is_object($familiar->fecha_desde) ? $familiar->fecha_desde->format('d/m/Y'):'';
					} else {
						$vars_template[$input][0]['SPAN_FECHA_DESDE'][0]['VALUE'] = is_object($familiar->fecha_desde) ? $familiar->fecha_desde->format('d/m/Y'):'';
					}
					break;

				case 'fecha_hasta':
					if($permiso) {
						$vars_template[$input][0]['BLOCK_FECHA_HASTA'][0]['C_FECHA_HASTA'][0]['FECHA_HASTA'] = is_object($familiar->fecha_hasta) ? $familiar->fecha_hasta->format('d/m/Y'):'';
					} else {
						$vars_template[$input][0]['BLOCK_FECHA_HASTA'][0]['SPAN_FECHA_HASTA'][0]['VALUE'] = is_object($familiar->fecha_hasta) ? $familiar->fecha_hasta->format('d/m/Y'):'';
					}
					break;

				case 'reintegro_guarderia':
					if($permiso) {
						$vars_template[$input][0]['C_GUARDERIA'][0]['REINTEGRO_GUARDERIA'] = ($familiar->reintegro_guarderia == 1) ? "checked" : "";
					} else {
						$vars_template[$input][0]['SPAN_GUARDERIA'][0]['VALUE'] = ($familiar->reintegro_guarderia == 1) ? "SÍ" : "NO";
						$vars_template[$input][0]['SPAN_GUARDERIA'][0]['ID'] 	= $familiar->reintegro_guarderia;
					}
					break;

				case 'discapacidad':
					if($permiso) {
						$vars_template[$input][0]['C_DISCAPACIDAD'][0]['DISCAPACIDAD'] = ($familiar->discapacidad == 1) ? "checked" : "";
					} else {
						$vars_template[$input][0]['SPAN_DISCAPACIDAD'][0]['VALUE'] = ($familiar->discapacidad == 1) ? "SÍ" : "NO";
						$vars_template[$input][0]['SPAN_DISCAPACIDAD'][0]['ID']    = $familiar->discapacidad;
					}
					break;

				case 'tipo_discapacidad':
					if($permiso) {
						$vars_template[$input][0]['C_TDISCAPACIDAD'][0]['TIPO_DISCAPACIDAD'] = \FMT\Helper\Template::select_block($parametricos['tipo_discapacidad'], $familiar->fam_discapacidad->id_tipo_discapacidad);
					} else {
						$vars_template[$input][0]['SPAN_TDISCAPACIDAD'][0]['VALUE'] = \FMT\Helper\Arr::path($parametricos,"tipo_discapacidad{$familiar->fam_discapacidad->id_tipo_discapacidad}.nombre",'-');
						$vars_template[$input][0]['SPAN_TDISCAPACIDAD'][0]['ID'] 	= $familiar->fam_discapacidad->id_tipo_discapacidad;
					}
					break;

				case 'cud':
					if($permiso) {
						$vars_template[$input][0]['C_CUD'][0]['CUD'] = $familiar->fam_discapacidad->cud;
					} else {
						$vars_template[$input][0]['SPAN_CUD'][0]['VALUE'] = $familiar->fam_discapacidad->cud;
					}
					break;

				case 'fecha_alta_discapacidad':
					if($permiso) {
						$vars_template[$input][0]['C_FECHA_ALTA_DIS'][0]['FECHA_ALTA_DIS'] = is_object($familiar->fam_discapacidad->fecha_alta) ? $familiar->fam_discapacidad->fecha_alta->format('d/m/Y'):'';
					} else {
						$vars_template[$input][0]['SPAN_C_FECHA_ALTA_DIS'][0]['VALUE'] = is_object($familiar->fam_discapacidad->fecha_alta) ? $familiar->fam_discapacidad->fecha_alta->format('d/m/Y'):'';
					}
					break;

				case 'fecha_vencimiento':
					if($permiso) {
						$vars_template[$input][0]['C_FECHA_VEN_DIS'][0]['FECHA_VEN_DIS'] = is_object($familiar->fam_discapacidad->fecha_vencimiento) ? $familiar->fam_discapacidad->fecha_vencimiento->format('d/m/Y'):'';
					} else {
						$vars_template[$input][0]['SPAN_C_FECHA_VEN_DIS'][0]['VALUE'] = is_object($familiar->fam_discapacidad->fecha_vencimiento) ? $familiar->fam_discapacidad->fecha_vencimiento->format('d/m/Y'):'';
					}
					break;		
				default:
					# code...
					break;
			}
		}
		
		$vars_template['CAMPOS_FAMILIAR'][0]	+= [
			'FORM'			=> \App\Helper\Vista::get_url("index.php/legajos/modificar_familiar/{$familiar->id}"),
			'BOTON_SUBMIT' 	=> 'MODIFICAR',
			'AGENTE'		=> $empleado->persona->nombre.' '.$empleado->persona->apellido,
			'CUIT'			=> $empleado->cuit,
			'VOLVER'		=> \App\Helper\Vista::get_url("index.php/legajos/gestionar/{$empleado->cuit}"),
			'BLOQUE' 		=> $bloque,
		];

		$vars_template['CAMPOS_FAMILIAR'][0]['TIPO_FORM'] = 'modificar_familiar';
		$rol =\App\Modelo\AppRoles::obtener_rol();
		$permiso_div_disca =  (\App\Modelo\AppRoles::ROL_LIQUIDACIONES == $rol) ? 'false' : 'true';
		$familiar_discapacidad = (!empty($familiar->fam_discapacidad->id)?'true':'false');
		$vars_vista['JS'][]['JS_CODE']	= <<<JS
		var \$familiar_discapacidad = {$familiar_discapacidad};
		var \$permiso_div_disca = {$permiso_div_disca};
JS;

		$vars_vista['CSS_FILES'][]	= ['CSS_FILE' => \App\Helper\Vista::get_url('legajos.css')];
		$vars_vista['CSS_FILES'][]	= ['CSS_FILE' => \App\Helper\Vista::get_url('funkyradio.css')];
		$vars_vista['JS_FOOTER'][]['JS_SCRIPT']  = Vista::get_url('grupo_familiar.js');
		$familiar = new \FMT\Template(TEMPLATE_PATH.'/legajos/familiar.html', $vars_template,['CLEAN'=>false]);
		$vars_vista['SUBTITULO'] = 'Modificación de Familiar';
		$vars_vista['CONTENT'] = "{$familiar}";
		$vista->add_to_var('vars',$vars_vista);
		return true;
	}
}