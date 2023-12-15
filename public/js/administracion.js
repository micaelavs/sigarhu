$(document).ready(function(){
  calcularHoras();
});
/**
 * Limpia el contenido html de las etiquetas <select>
 *
 * @param string	$dom_select	- valor usado para seleccionar el elemento dom. E.j.: 'select#id_situacion_revista'
*/
function cleanSelect($dom_select){
	if($($dom_select)[0].nodeName	!= 'SELECT') return $($dom_select);

	$($dom_select).html('');
	$($dom_select).append($('<option>', {
		value: '',
		text : 'Seleccione'
	}));
	return $($dom_select);
}

$(".hora").datetimepicker({
  format: "HH:00"
});

  $('#anio_mes').datetimepicker({
            // viewMode: 'years',
            format: 'MM/YYYY',
     });



$(".fecha_licencia").datetimepicker({
  format: 'DD/MM/YYYY'
});

$("#select_horario").on('change',function(event) {
  $(this).closest('form').submit();
});

if($("#activo").val() != "1"){
  $("#id_motivo").attr('disabled', false);
  $("#fecha_baja").attr('disabled', false);
}

$("#activo").on('change',function(event) {
  if($(this).val() != "1"){
    $("#id_motivo").attr('disabled', false);
    $("#fecha_baja").attr('disabled', false);
  }else{
    $("#id_motivo").val('').attr('disabled', true);
    $("#fecha_baja").val('').attr('disabled', true);
  }
});

if($("#comision").val() == "1"){
  $("#organismo_destino").attr('disabled', false);
  $("#organismo_origen").attr('disabled', false);
}

$("#comision").on('change',function(event) {
  if($(this).val() == "1"){
    $("#organismo_destino").attr('disabled', false);
    $("#organismo_origen").attr('disabled', false);
  }else{
    $("#organismo_destino").val('').attr('disabled', true);
    $("#organismo_origen").val('').attr('disabled', true);
  }
});


   $(".filestyle").fileinput({
      language: 'es',
      browseLabel: '',
      showRemove: false,
      showUpload: false,
      previewFileIcon: '<i class="glyphicon glyphicon-eye"></i>',
      previewFileIconClass: 'file-icon-4x'
    });
      if ($('#horas_extras').is(':checked')) {
          $("#boton_hora_extra").attr('disabled', false);
        } else {
          $("#boton_hora_extra").attr('disabled', true);
        }
      $('#horas_extras').on('click',function () {
        if ($('#horas_extras').is(':checked')) {
          $("#boton_hora_extra").attr('disabled', false);
        } else {
          $("#boton_hora_extra").attr('disabled', true);
          $("#anio_extra").text('');
          $("#mes_extra").text('');
          $("#acto_administrativo").text('');
		}
	});


$(".calcular_horas").on('blur', function(){
  calcularHoras();
});

function calcularHoras(){
  var suma_horas = 0;
  var dias = ['domingo','lunes','martes','miercoles','jueves','viernes','sabado'];
  $.each( dias, function( key, dia ){
    if($("#hora_desde_"+dia).val() && $("#hora_hasta_"+dia).val()){
      var h_desde = parseInt($("#hora_desde_"+dia).val());
      var h_hasta = parseInt($("#hora_hasta_"+dia).val());
      var diff = h_hasta - h_desde;

      diff < 0 ? dia = (diff * -1) :  dia = diff;
      suma_horas += dia;
    }
  });
  $("#horas_semanales").val(suma_horas+" Hrs.");
  $("#span_horas_semanales").html(suma_horas+" Hrs.");
}


(function() {
$('#div_adm_ubicacion').ready(function(){
	var $rollback_ubi = $('#id_ubicacion').val();

	var campos_ubicacion	= {
		'id_edificio'				: $('#id_edificio').val(),
		'ubicacion_piso'			: $('#ubicacion_piso').val(),
		'ubicacion_oficina'			: $('#ubicacion_oficina').val(),
	};
	var span_ubicacion	= {
		'ubicacion_calle_numero'	 : $('#ubicacion_calle_numero').text(),
		'ubicacion_provincia'	 	 : $('#ubicacion_provincia').text(),
		'ubicacion_localidad'	 	 : $('#ubicacion_localidad').text(),
	};

  $('#div_adm_ubicacion *').attr('disabled', true);

	  if(!$empleado_ubicacion){
	    $('#div_adm_ubicacion *').attr('disabled', false);
	    $('#ubicacion_accion_alta').hide();
	    $('#ubicacion_accion_modificar').hide();
	    $('#adm_ubicacion_accion').val('alta');
	  }

  $('#ubicacion_accion_alta').on('click', function(){
    $('#adm_ubicacion_accion').val('alta');
    $('#div_adm_ubicacion *').find("span").empty();
	$.each(campos_ubicacion, function(campo, valor){
		$('#'+campo).val('');
	});
    $('#div_adm_ubicacion *').attr('disabled', false);
    $('#id_ubicacion').val('');
    $('#div_adm_ubicacion #ubicacion_calle_numero').attr('disabled', true);
    $('#div_adm_ubicacion #ubicacion_provincia').attr('disabled', true);
    $('#div_adm_ubicacion #ubicacion_localidad').attr('disabled', true);
  });

  $('#ubicacion_accion_modificar').on('click', function(){
    $('#adm_ubicacion_accion').val('modificacion');

    $('#div_adm_ubicacion *').attr('disabled', false);
    $('#id_ubicacion').val($rollback_ubi);
		$.each(campos_ubicacion, function(campo, valor){
	$('#'+campo).val(valor);
	});
	$.each(span_ubicacion, function(campo, valor){
		$('#'+campo).text(valor);
	});
    $('#div_adm_ubicacion #ubicacion_calle_numero').attr('disabled', true);
    $('#div_adm_ubicacion #ubicacion_provincia').attr('disabled', true);
    $('#div_adm_ubicacion #ubicacion_localidad').attr('disabled', true);
  });


  	if($ubicaciones){
  		$('#div_adm_ubicacion select#id_edificio').on('change', function(){
  			var id_edificio	= $(this).val();
  			if(isNaN(parseInt(id_edificio))){
  				return;
  			}
  			var edificio	= $ubicaciones[id_edificio];

			var localidad_nombre	= '';
			var provincia_nombre	= $ubicacion_provincia[$ubicaciones[id_edificio].id_provincia].nombre;
			new ApiUbicaciones($ubicaciones[id_edificio].id_provincia)
			.done(function (data) {
				if(data.ubicacion_localidades !== undefined) {
					localidad_nombre	= data.ubicacion_localidades[$ubicaciones[id_edificio].id_localidad].nombre;
					$('span#ubicacion_localidad').html(localidad_nombre);
				} 
			});
			
  			$('#div_adm_ubicacion input#id_ubicacion').val('');
  			$('#div_adm_ubicacion select#ubicacion_piso').val('');
  			$('#div_adm_ubicacion select#ubicacion_oficina').val('');

  			$('#div_adm_ubicacion span#ubicacion_calle_numero').html(edificio.calle+' - '+edificio.numero);
  			$('span#ubicacion_provincia').html(provincia_nombre);
  			cleanSelect('#div_adm_ubicacion select#ubicacion_piso');
			cleanSelect('#div_adm_ubicacion select#ubicacion_oficina');
  			addOptions(edificio.pisos, '#div_adm_ubicacion select#ubicacion_piso');
// Arreglo para cuando solo existe un piso y una oficina
			var pisos			= Object.values($ubicaciones[id_edificio].pisos);
  			if(pisos.length == 1){
  				var oficinas		= pisos[0].oficinas;
  				var oficinas_array	= Object.values(oficinas);
  				$('#div_adm_ubicacion select#ubicacion_piso').val(pisos[0].id);
  				if(oficinas_array.length == 1){
  					var id_ubicacion = oficinas_array[0].id_ubicacion;
  					addOptions(oficinas, '#div_adm_ubicacion select#ubicacion_oficina');
  					$('#div_adm_ubicacion select#ubicacion_oficina').val(oficinas_array[0].id)

  					$('#div_adm_ubicacion input#id_ubicacion').val(id_ubicacion);
  				}
  			}
  		});

  		$('#div_adm_ubicacion select#ubicacion_piso').on('change', function(){
  			var edificio = $ubicaciones[$('select#id_edificio').val()];

  			cleanSelect('select#ubicacion_oficina');
  			addOptions(edificio.pisos[$(this).val()].oficinas, 'select#ubicacion_oficina');
			if (edificio.pisos[$(this).val()].oficinas.length == 1) {
				$('#div_adm_ubicacion input#id_ubicacion').val(edificio.pisos[$(this).val()].oficinas[0].id_ubicacion);
			}

  		});
  		$('#div_adm_ubicacion select#ubicacion_oficina').on('change', function(){
  			var id_edificio		= $('#div_adm_ubicacion select#id_edificio').val();
  			var piso			= $('#div_adm_ubicacion select#ubicacion_piso').val();
  			piso				= $ubicaciones[id_edificio].pisos[piso];
			var oficina			= piso.oficinas[$(this).val()];
  			$('#div_adm_ubicacion input#id_ubicacion').val(oficina.id_ubicacion);
  		})
  	}
 });


$('#div_licencias_especiales').ready(function(){

	var $rollback_lic = $('#id_licencia').val();
	var $rollback_fecha_desde = $('#fecha_desde').val();
	var $rollback_fecha_hasta = $('#fecha_hasta').val();
	if(!$empleado_licencia){
		$('#div_licencias_especiales *').attr('disabled', false);
		$('#licencia_accion_alta').hide();
		$('#licencia_accion_modificar').hide();
		$('#adm_licencia_accion').val('alta');
	}else{
		$('#div_licencias_especiales *').attr('disabled', true);
	}


	$('#licencia_accion_alta').on('click', function(){
		$('#adm_licencia_accion').val('alta');
		$('#div_licencias_especiales *').attr('disabled', false);
		$('#id_licencia').val('');
		$('#fecha_desde').val('');
		$('#fecha_hasta').val('');
	});

	$('#licencia_accion_modificar').on('click', function(){
		$('#adm_licencia_accion').val('modificacion');
		$('#div_licencias_especiales *').attr('disabled', false);
		$('#id_licencia').val($rollback_lic);
		$('#fecha_desde').val($rollback_fecha_desde);
		$('#fecha_hasta').val($rollback_fecha_hasta);
	});
});

})();