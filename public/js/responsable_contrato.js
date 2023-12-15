$(document).ready(function () {
	$("#dependencia").select2();
	$("#firmante").select2();
	$("#contratante").select2();
	
function cleanSelect($dom_select){
	if($($dom_select)[0].nodeName	!= 'SELECT') return $($dom_select);

	$($dom_select).html('');
	$($dom_select).append($('<option>', {
		value: '',
		text : 'Seleccione'
	}));
	return $($dom_select);
}

	
$('select#dependencia').on('change', function($e){
	$.ajax({
		url: $base_url+"/Responsable_contrato/ajax_get_contratante_firmantes",
		data: {
			dependencia:	$('select#dependencia').val(),
			
		},
		method: "POST"
	})
	.done(function (data) {
		addOptionsMulti(data.personas, '#contratante', data.contratante);
		addOptionsMulti(data.personas, 'select#firmante',data.firmante);			
	});
});

	function addOptionsMulti($options, $dom_select, $selected ){
		$obj				= $($dom_select);
		if($obj[0].nodeName	!= 'SELECT') return $obj;

// Limpiar etiquetas <Select> antes de llenarlas
		$obj.html('');
		$obj.append($('<option>', {
			value: '',
			text : 'Seleccione'
		}));
// Llenar etiquetas <Select>
		$.each($options, function (i, item) {
			$_options	= {
				value: i,
				text : item.nombre,
			};
			if(item.borrado != '0'){
				$_options.disabled	= 'disabled';
			}
			
			if($.inArray( i, $selected) != -1){
				$_options.selected	= 'selected';
			}
			$obj.append($('<option>', $_options));
		});
		return $obj;
	}
});
