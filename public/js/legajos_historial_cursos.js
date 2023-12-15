$(document).ready(function () {


	var cant_cursos = $("#cant_cursos").val();
	  	if(cant_cursos>10){
	  		 $('#boton_historial').css("visibility", "visible");
	  	}else{
	  	$('#boton_historial').css("visibility", "hidden");
	  	}

        $("#fecha").datetimepicker({
	     format: 'DD/MM/YYYY',
	     maxDate: moment()
		});
             
    $("#formacion_cursos").delegate('.curso_check', 'change', function(){
		var tipo_tramo = $formacion_cursos_tipo_promocion['tipo_tramo'];
		var tipo_grado = $formacion_cursos_tipo_promocion['tipo_grado'];

	     if($(this).prop('checked')){
	          $(this).val(tipo_tramo);
		     }else{
		          $(this).val(tipo_grado);    
		     }
		});

    $("#editar_campos").click(function() {
  		$('.habilitar').attr("disabled", false);
  	
	});


});



