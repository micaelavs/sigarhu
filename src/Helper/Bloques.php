<?php
namespace App\Helper;

class Bloques {
	const DATOS_PERSONALES = 1;
	const SITUACION_ESCALAFONARIA = 2;
	const UBICACION_ESTRUCTURA = 3;
	const PERFILES_PUESTO = 4;
	const FORMACION = 5;
	const ANTIGUEDAD = 6;
	const ADMINISTRACION = 7;
	const VARIOS = 8;
	const PRESUPUESTO = 9;
	const ANTICORRUPCION = 10;
	const GRUPO_FAMILIAR = 11;
	const EMBARGO = 12;

	static public $SOLAPAS	= [
		self::DATOS_PERSONALES			=> ['id' => self::DATOS_PERSONALES, 'nombre' => 'Datos Personales', 'borrado' => '0'],
		self::SITUACION_ESCALAFONARIA	=> ['id' => self::SITUACION_ESCALAFONARIA, 'nombre' => 'Situacion Escalafonaria', 'borrado' => '0'],
		self::UBICACION_ESTRUCTURA		=> ['id' => self::UBICACION_ESTRUCTURA, 'nombre' => 'Ubicación en la Estructura', 'borrado' => '0'],
		self::PERFILES_PUESTO			=> ['id' => self::PERFILES_PUESTO, 'nombre' => 'Perfiles de Puestos', 'borrado' => '0'],
		self::FORMACION					=> ['id' => self::FORMACION, 'nombre' => 'Formacion', 'borrado' => '0'],
		self::ANTIGUEDAD				=> ['id' => self::ANTIGUEDAD, 'nombre' => 'Antigüedad', 'borrado' => '0'],
		self::ADMINISTRACION			=> ['id' => self::ADMINISTRACION, 'nombre' => 'Administracion', 'borrado' => '0'],
		self::VARIOS					=> ['id' => self::VARIOS, 'nombre' => 'Varios', 'borrado' => '0'],
		self::PRESUPUESTO				=> ['id' => self::PRESUPUESTO, 'nombre' => 'Presupuesto', 'borrado' => '0'],
		self::ANTICORRUPCION			=> ['id' => self::ANTICORRUPCION, 'nombre' => 'Anticorrupcion', 'borrado' => '0'],
		self::GRUPO_FAMILIAR			=> ['id' => self::GRUPO_FAMILIAR, 'nombre' => 'Grupo Familiar', 'borrado' => '0'],
		self::EMBARGO					=> ['id' => self::EMBARGO, 'nombre' => 'Embargo', 'borrado' => '0'],
	];	

}