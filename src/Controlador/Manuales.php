<?php
namespace App\Controlador;

use App\Helper\Vista;

class Manuales extends Base {
	
	public function accion_index() {
		$rol_b = $this->request->query('id');
		(new Vista($this->vista_default,['vista' => $this->vista, 'usuario' => $this->_user->rol_id, 'rol_b' => $rol_b]))
			->pre_render();
 	}
}