<?php
use FMT\Vista;
$vars_vista['SUBTITULO']		= 'Alta Otros Organismos';
$vars_template['OPERACION']		= 'alta';
$vars_template['NOMBRE']= !empty($otros_organismos->nombre) ? $otros_organismos->nombre:'';
$vars_template['TIPO']	= \FMT\Helper\Template::select_block($tipos,'');
$vars_template['JURISDICCION']	= \FMT\Helper\Template::select_block($jurisdicciones,'');
$vars_template['CANCELAR'] = \App\Helper\Vista::get_url('index.php/otros_organismos/index');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']   = \App\Helper\Vista::get_url('otros_organismos.js');
if($hidden){
	$vars_template['INPUT_HIDDEN'][]['VALUE'] = urlencode(json_encode($hidden, JSON_UNESCAPED_UNICODE));
	$vars_template['CANCELAR'] = "#";
	$vars_vista['JS'][]['JS_CODE']	= <<<JS
\$(document).ready(function () {
	var \$btn = \$('#volver');
	\$btn.click(function(){
    	var f = \$('<form/>', {id:'form_l' , action : '{$hidden[1]}/{$hidden[0]}/', method : 'POST'});
    	var input = \$('<input />', {id : 'id_bloque', name: 'id_bloque', type: 'hidden', value: '{$hidden[2]}'});
    	f.append(input);
    	\$btn.after(f);
	    \$('#form_l').submit();
    });
}); 


JS;
}

$template = (new \FMT\Template(VISTAS_PATH.'/templates/otros_organismos/otros_organismos_alta.html', $vars_template,['CLEAN'=>false]));
$vars_vista['CONTENT'] = "$template";
$vista->add_to_var('vars',$vars_vista);

return true;
?>
