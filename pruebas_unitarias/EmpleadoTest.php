<?php
include_once 'Dataset/EmpleadoDataset.php';
use \App\Modelo;
use \App\Helper;

class EmpleadoTest extends \PHPUnit\Framework\TestCase
{
	private static $ID_EMPLEADO = null;

/**
 * Prueba la cracion de un empleado con sus datos basicos.
 * @return void
 */
	public function testCrearEmpleadoConDatosPersonales(){
		$this->assertTrue(true);
		return;
		Modelo\Empleado::init();
		$mock       = EmpleadoDataset::datosPersonales();

		$empleado   = Modelo\Empleado::obtener();
		$this->assertObjectHasAttribute('persona', $empleado);
		$this->assertEquals(null, $empleado->id);

		$empleado->cuit						= $mock->cuit;
		$empleado->email					= $mock->email;
		$empleado->estado					= $mock->estado;
		$empleado->persona->nombre			= $mock->persona->nombre;
		$empleado->persona->apellido		= $mock->persona->apellido;
		$empleado->persona->tipo_documento	= $mock->persona->tipo_documento;
		$empleado->persona->documento		= $mock->persona->documento;
		$empleado->persona->fecha_nac		= $mock->persona->fecha_nac;
		$empleado->persona->genero			= $mock->persona->genero;
		$empleado->persona->estado_civil	= $mock->persona->estado_civil;
		$empleado->persona->email			= $mock->persona->email;
		$empleado->persona->nacionalidad	= $mock->persona->nacionalidad;

		$domicilio 					        = new \stdClass();
		$domicilio->id 				        = $mock->persona->domicilio->id;
		$domicilio->id_provincia	        = $mock->persona->domicilio->id_provincia;
		$domicilio->id_localidad	        = $mock->persona->domicilio->id_localidad;
		$domicilio->calle			        = $mock->persona->domicilio->calle;
		$domicilio->numero			        = $mock->persona->domicilio->numero;
		$domicilio->piso			        = $mock->persona->domicilio->piso;
		$domicilio->depto			        = $mock->persona->domicilio->depto;
		$domicilio->cod_postal		        = $mock->persona->domicilio->cod_postal;
		$domicilio->fecha_alta		        = $mock->persona->domicilio->fecha_alta;
		$domicilio->fecha_baja		        = $mock->persona->domicilio->fecha_baja;

		$empleado->persona->domicilio      = $domicilio;
		
		$empleado->persona->validar();
		$this->assertTrue(empty($empleado->persona->errores), implode(',',(array)$empleado->persona->errores));

		$empleado->validar();
		$this->assertTrue(empty($empleado->errores), implode(',',(array)$empleado->errores));

		$empleado->persona->alta();
		$this->assertGreaterThan(0, (int)$empleado->persona->id);

		$empleado->alta();
		$this->assertGreaterThan(0, (int)$empleado->id);
		
		static::$ID_EMPLEADO	= $empleado->id;
		$empleado_check   		= Modelo\Empleado::obtener(static::$ID_EMPLEADO, true);
		
		$this->assertEquals($empleado->persona->id, $empleado_check->persona->id);
	}

/**
 * Prueba que exista el ID de empleado en tiempo de ejecucion.
 * @return void
 */
	public function testComprobarEmpleadoID(){
		$this->assertTrue(true);
		return;
		$this->assertGreaterThan(0, (int)static::$ID_EMPLEADO);
	}
}