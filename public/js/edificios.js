$(document).ready(function () {
	$("#id_localidad").select2();
    $("#id_provincia").select2();
    

  function cleanSelect($dom_select){
    	if($($dom_select)[0].nodeName	!= 'SELECT') return $($dom_select);

		$($dom_select).html('');
		$($dom_select).append($('<option>', {
			value: '',
			text : 'Seleccione'
		}));
		return $($dom_select);
	}
	(function ubicaciones(){

// Carga la informacion de Provincia y Localidad para los Datos de Domicilio en funcion de la Nacionalidad

		$('select#id_provincia').on('change', function($e){
			new ApiUbicaciones($('select#id_provincia').val())
			.done(function (data) {
				if(data.ubicacion_localidades !== undefined) {
					addOptions(data.ubicacion_localidades, 'select#id_localidad', true);
				}
				$("select#id_localidad").trigger('ajax_rollback');
			});
		});
	})();
}); 