$(document).ready(function () {

	$('select#nombre_curso').on('change', function($e){
			var codigo_curso;
			if($('select#nombre_curso').val()==""){
				codigo_curso = 0;
			}else{
				codigo_curso = $('select#nombre_curso').val();
			}
			$.ajax({
				url: $base_url+"/Legajos/buscar_curso",
				data: {
					codigo_curso: codigo_curso,
					
				},
				method: "POST"
			})
			.done(function (data) {
				$("#creditos").val(data.data.creditos);
				$("#creditoshidden").val(data.data.creditos);
			})
			.fail(function(data){
				$("#creditos").val("");
				$("#creditoshidden").val("");
			});

	});	
});	