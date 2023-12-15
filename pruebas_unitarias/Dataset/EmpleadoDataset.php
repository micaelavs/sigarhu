<?php

use \App\Modelo;

class EmpleadoDataset {
	static private $CUIT	= null;
	static private $DNI		= null;

	static private function genenarCuit(){
		if(!empty(static::$CUIT) && !empty(static::$DNI)){
			return;
		}

		$dni	= (string)rand(10000000,99999999);
		$cuit	= '23'.$dni;

		$acumulado	= 0;
		$digitos	= str_split($cuit);

		for ($i = 0; $i < count($digitos); $i++) {
			$acumulado += $digitos[9 - $i] * (2 + ($i % 6));
		}
		$verif = 11 - ($acumulado % 11);
		$verif = $verif == 11 ? 0 : $verif;

		static::$CUIT	= $cuit.(string)$verif;
		static::$DNI	= (string)$dni;;
	}

    static public function datosPersonales(){
		static::genenarCuit();

        $empleado   = (object)[
            'cuit'		=> static::$CUIT,
            'email'		=> 'rickzanchestest@grr.la',
            'estado'	=> MOdelo\Empleado::EMPLEADO_ACTIVO,
            'persona'	=> new \stdClass,
            'domicilio'	=> new \stdClass,
		];
		$empleado->persona	= (object)[
			'nombre'			=> 'Rick T',
			'apellido'			=> 'Zanches T',
			'tipo_documento'	=> Modelo\Persona::DNI,
			'documento'			=> static::$DNI,
			'fecha_nac'			=> \DateTime::createFromFormat('d/m/Y H:i:s', '02/01/1970'. ' 0:00:00'),
			'genero'			=> Modelo\Persona::FEMENINA,
			'estado_civil'		=> Modelo\Persona::SOLTERO,
			'email'				=> 'rickzanchestest@grr.la',
			'nacionalidad'		=> 'AR',
		];
		$empleado->persona->domicilio	= (object)[
			'id'				=> null,
			'id_provincia'		=> 2,
			'id_localidad'		=> 40,
			'calle'				=> 'S/N',
			'numero'			=> '001',
			'piso'				=> '',
			'depto'				=> '',
			'cod_postal'		=> '',
			'fecha_alta'		=> \DateTime::createFromFormat('U', strtotime('now')),
			'fecha_baja'		=> null,
		];

		return $empleado;
    }
}