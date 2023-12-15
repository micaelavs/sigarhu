$(document).ready(function () {
	$('#nuevo_documento').click(function(e){
		e.preventDefault();
		var bloque_id	= $(this).data('bloque');
		var data_ref	= $(this).data('ref');

		var formulario			= $('<form/>', {id:'form_documentos_'+bloque_id , action : data_ref, method : 'POST'});
		var input_hidden_bloque = $('<input />', { name: 'id_bloque', type: 'hidden', value: bloque_id });
		formulario.append(input_hidden_bloque);
		$(this).after(formulario);
		$('form#form_documentos_'+bloque_id).submit();
	});

});