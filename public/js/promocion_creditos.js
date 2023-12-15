$(document).ready(function () {
	if($('.tabla_credito_promocion').length){
		$('.tabla_credito_promocion').DataTable({
			autoWidth: false,
			info: false,
			bFilter: true,
			columnDefs: [
				{ targets: 0},
				{ targets: 1},
				{ targets: 2, width: '10%' },
				{ targets: 3, width: '10%' },
				{ targets: 4, width: '3%', ordenable: false },
			],
			order: [[3,'desc'],[0,'desc']]
		});
	} else {
		$(".fecha").datetimepicker({
			format: 'DD/MM/YYYY'
		});
	
		if($('#divFechaHasta').data("operacion") == 'alta') {
			$('#divFechaHasta').remove()
		}

		$("select#agrupamiento").on('change', cargoNiveles);
		if($("#id_nivel").val() != ''){
			cargoNiveles();
			$("select#nivel").val($("#id_nivel").val());
		}
	}
});

function cargoNiveles(){
	$niveles = $("select#agrupamiento").children('option:selected').data('niveles');
	addOptions($niveles, '#nivel', false);
}