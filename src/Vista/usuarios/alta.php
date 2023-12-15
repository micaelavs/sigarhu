<?php
	namespace App\Vista;
	$usuario->area 	= (array)$usuario->area;
	$config	= \FMT\Configuracion::instancia();
	$vars_vista['SUBTITULO']	= "$operacion Usuarios";
	$vars_vista['CSS_FILES'][]    = ['CSS_FILE'   => $config['app']['endpoint_cdn']."/js/select2/css/select2.min.css"];
    $vars_vista['JS_FILES'][]     = ['JS_FILE'    => $config['app']['endpoint_cdn']."/js/select2/js/select2.full.min.js"];
    $vars_vista['JS_FOOTER'][]['JS_SCRIPT'] = \App\Helper\Vista::get_url('usuario.js');

    $vars['ROLES'] = \FMT\Helper\Template::select_block($roles, $usuario->rol_id);
    $metadata=[];
    if (!empty($usuario->metadata)){
    	foreach ($usuario->metadata   as $key =>  $value) {
    		$metadata[$key] = $value['dependencia'];
   	 	}
    }else{
    	$metadata = '';
    }
	///DEPRECADO
	//if($usuario->rol_id == \App\Modelo\AppRoles::ROL_LOTE) {
	//	$vars['DEPENDENCIA'][0]['DEPENDENCIAS'] =  \FMT\Helper\Template::select_block($dependencias, $metadata);
	//} 

    $vars['USER']  = $usuario;
    $vars['CANCELAR'] = \App\Helper\Vista::get_url('index.php/usuarios/index'); 
	$template = new \FMT\Template(VISTAS_PATH.'/templates/usuarios/alta.html', $vars, ['CLEAN'=>true]);
	$vars_vista['CONTENT'] = "$template";
	$vista->add_to_var('vars',$vars_vista);

	return true;
?>
