<?php
return [
	'app' => [
		'dev'						=> false, // Estado del desarrollo
		'modulo'					=> 0, // Numero del modulo
		'title'						=> 'Sistemas de Gestión y Administración de RRHH - Ministerio de Transporte', // Nombre del Modulo,
		'titulo_pantalla'			=> 'SIGARHU',
		'endpoint_informacion_fecha'=> 'https://qa-informacionfecha.dev.transporte.gob.ar/index.php/consulta/',
		'endpoint_ubicaciones'		=> 'https://qa-ubicaciones.dev.transporte.gob.ar/index.php/',
		'endpoint_panel'			=> 'https://qa-panel.dev.transporte.gob.ar',
		'endpoint_cdn'				=> 'https://qa-cdn.dev.transporte.gob.ar',
		'endpoint_locaciones'           => '',
    'locaciones_access_token'       => '',
		'ssl_verifypeer'			=> true,
		'id_usuario_sistema'		=>	'9', //En caso de operaciones automaticas, se establece un id de usuario que identifique al sistema
		'php_interprete'            => '/usr/bin/php74',
	]
];
