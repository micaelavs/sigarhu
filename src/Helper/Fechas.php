<?php
namespace App\Helper;

Class Fechas{
	
	protected  $acum;
	protected  $cantidad = [];

	public function __construct(){
		$this->acum = true;
		$this->cantidad['anios'] = 0;
		$this->cantidad['meses'] = 0;
	}

	public  function sum_cantidad_dias($fecha_desde, $fecha_fin) {
		if ($fecha_desde instanceof \DateTime && $fecha_fin instanceof \DateTime) {
			$dif = date_diff($fecha_desde, $fecha_fin); 
			if ($this->acum) {
				$this->cantidad['anios'] = $this->cantidad['anios'] + $dif->y;
				$this->cantidad['meses'] = $this->cantidad['meses'] + $dif->m;
				while ($this->cantidad['meses'] >= 12){
					$this->cantidad['meses'] -=12;
					$this->cantidad['anios'] += 1;
				}
			}
		}
	}
	
	public static  function sum_cantidades($cantidadA, $cantidadB) {
			$cantidad['anios'] = $cantidadA['anios'] + $cantidadB['anios'];
			$cantidad['meses'] = $cantidadA['meses'] + $cantidadB['meses'];
				while ($cantidad['meses'] >= 12){
					$cantidad['meses'] -=12;
					$cantidad['anios'] += 1;
				}
			return $cantidad;	
		
	}

	/**
	 * @return array - ['anios'=>(int), 'meses'=>(int)]
	 */
	public function get_cantidad(){
		return $this->cantidad;		
	}		
	
	public function reset_cantidad(){
		$this->cantidad['anios'] = 0;
		$this->cantidad['meses'] = 0;
	}	
			
	public  static function cantidad_dias($fecha_desde, $fecha_fin) {
		if ($fecha_desde instanceof \DateTime && $fecha_fin instanceof \DateTime) {
			$dif = date_diff($fecha_desde, $fecha_fin);
		}
		return $dif;
	}

}