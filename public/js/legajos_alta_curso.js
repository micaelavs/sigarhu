$(document).ready(function () {
	var tipo_tramo = $formacion_cursos_tipo_promocion['tipo_tramo'];
	var tipo_grado = $formacion_cursos_tipo_promocion['tipo_grado'];

    $("#tipo_promocion").click(function(){
  		if($("#tipo_promocion").prop('checked')){
	        	$(this).val(tipo_tramo);
		    }else{
		        $(this).val(tipo_grado);    
		    }
	});
});	