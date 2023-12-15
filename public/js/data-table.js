$(document).ready(function () {
	setTimeout(function(){
		if($.fn.dataTable.isDataTable('#tabla') === false){
			$('#tabla').DataTable();
		}
	}, 100);

	var table	= $('#tabloa').DataTable({
		buttons: [{
			extend: 'excelHtml5',
			filename:'Sigarhu',
			className: 'btn-sm',
			text:'Descargar Excel',
			exportOptions: {"columns": ':not(.acciones)'}
		}],
	});

	table.table().on('init', function(){
		table.buttons().container().css('padding-right','20px').prependTo( $('.dataTables_filter', table.table().container() ) );
	});

	$.fn.dataTable.moment('DD/MM/YYYY');
});