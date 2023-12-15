<?php
use \FMT\Helper\Arr;
use \App\Helper\Vista;
$config	= \FMT\Configuracion::instancia();
$vars_template = [];
$vars_vista['SUBTITULO']	= 'Pesquisa';

$vars_vista['CSS_FILES'][]['CSS_FILE']	= $config['app']['endpoint_cdn'].'/datatables/1.10.12/datatables.min.css';
$vars_vista['JS_FILES'][]['JS_FILE']	= $config['app']['endpoint_cdn'].'/datatables/1.10.12/datatables.min.js';
$vars_vista['JS_FILES'][]['JS_FILE']	= $config['app']['endpoint_cdn'].'/datatables/defaults.js';
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']	= Vista::get_url('script.js');
$vars_vista['JS_FOOTER'][]['JS_SCRIPT']	= Vista::get_url('auditorias_index.js');
$vars_vista['CSS_FILES'][]['CSS_FILE']	= Vista::get_url('auditoria.css');
$pesquisa								= new \FMT\Template(TEMPLATE_PATH.'/auditorias/index.html',[
	'URL_BASE'	=> Vista::get_url(),
	'USUARIOS'	=> \FMT\Helper\Template::select_block($usuarios, null),
],['CLEAN'=>true]);
$vars_vista['CONTENT']					= "{$pesquisa}";

$base_url								= Vista::get_url('index.php');
$solapas								= json_encode($solapas);
$parametricos							= json_encode($parametricos);
$vars_vista['JS'][]['JS_CODE']			= <<<JS
	var \$base_url			= "{$base_url}";
	var \$solapas			= {$solapas};
	var \$parametricos		= {$parametricos};
JS;
$vista->add_to_var('vars',$vars_vista);
return true;