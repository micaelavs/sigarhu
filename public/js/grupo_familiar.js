$('#div_discapacidad').ready(function(){

	var $rollback_dis = $('#tipo_discapacidad').val();
	var $rollback_cud = $('#cud').val();
	var $rollback_fecha_alta = $('#fecha_alta_discapacidad').val();
	var $rollback_fecha_ven = $('#fecha_vencimiento').val();
	var rta;
	if(typeof($permiso_div_disca) != "undefined" && !$permiso_div_disca){
		$('#div_discapacidad').hide();
		$('#botones_disca').hide();
	}
	if(typeof($familiar_discapacidad) != "undefined" && !$familiar_discapacidad && $permiso_div_disca){

		if($('#discapacidad').prop('checked')){
			rta = false;
		}else{
			rta = true;
		}
	    $('#div_discapacidad *').attr('disabled', rta);
	    $('#dis_accion_alta').hide();
	    $('#dis_accion_modificar').hide();
	    $('#dis_familiar_accion').val('alta');
	}else{

		$('#div_discapacidad *').attr('disabled', true);
	}

	$('#dis_accion_alta').on('click', function(){
	    $('#dis_familiar_accion').val('alta');
	    $('#div_discapacidad *').attr('disabled', false);
	    $('#tipo_discapacidad').val('');
	    $('#cud').val('');
	    $('#fecha_alta_discapacidad').val('');
	    $('#fecha_vencimiento').val('');
	});

	$('#dis_accion_modificar').on('click', function(){
	    $('#dis_familiar_accion').val('modificacion');
	    $('#div_discapacidad *').attr('disabled', false);
	    $('#tipo_discapacidad').val($rollback_dis);
	    $('#cud').val($rollback_cud);
	    $('#fecha_alta_discapacidad').val($rollback_fecha_alta);
	    $('#fecha_vencimiento').val($rollback_fecha_ven);
	});
});

$('#alta_grupo_familiar').ready(function () {
	$(".fecha").datetimepicker({
		format: 'DD/MM/YYYY'
    });
	$("#fecha_nacimiento").datetimepicker({
		format: 'DD/MM/YYYY',
		maxDate: 'now',
    });

	$('#discapacidad').on('click', function(){

		if($(this).prop('checked')) {
			$('#div_discapacidad *').attr('disabled', false);
		} else {
			$('#div_discapacidad *').attr('disabled', true);
		}
	});

	if(!(typeof $('#tabla') !== 'undefined' && $('#tabla').length > 0)){
		return;
	}
	if($.fn.dataTable.isDataTable('#tabla') !== false){
		return;
	}
	
	if($('#tabla').length != 0) {
		var _table	= $('#tabla');
		_table.DataTable({
			language: {
				search: '_INPUT_',
				searchPlaceholder: 'Ingrese búsqueda'
			},
			autoWidth: false,
			lengthChange: false,
			info: false,
			bFilter: true,
	        columnDefs: [
			{ targets: 0, width: '5%' },
			{ targets: 1, width: '20%' },
			{ targets: 2, width: '5%' },
			{ targets: 3, width: '5%' },
			{ targets: 4, width: '5%' },
			{ targets: 5, width: '5%' },
			{ targets: 6, width: '5%' },
			{ targets: 7, width: '5%' },
			{ targets: 8, width: '5%', orderable: false },
	        ],
			order: [[0,'asc'],[2,'desc']]
		});

		$.fn.dataTable.moment('DD/MM/YYYY');
	}
});