var filtros_dataTable = null;
$(document).ready(function () {
 	$("#periodo").select2();
	$("#tipo_dj").select2();
	$('#histo_anticorrupcion').ready(function () {
		var $url_base = $('#url-base').attr('data_url');
		var $cuit = $('#div_agente .ct').eq(1).text();
		$("#alert_observacion").hide();
		$('[data-toggle="tooltip"]').tooltip();

		if (!$.fn.DataTable.isDataTable('#histo_anticorrupcion')){
			var tabla = $('#histo_anticorrupcion').DataTable({
			processing: true,
			serverSide: true,
			responsive: true,
			searchDelay: 1200,
			language: {
				url: $endpoint_cdn+'/datatables/1.10.12/Spanish_sym.json',
				decimal: ',',
				thousands: '.'
			},
			ajax: {
				url:$url_base + '/index.php/legajos/ajax_lista_historico_anticorrupcion/',
				contentType: "application/json",
				data: function (d) {
					filtros_dataTable = $.extend({}, d, {
	                    periodo       :   $('#periodo').val(),
	                    tipo_dj       :   $('#tipo_dj').val(),
	                });
	                return filtros_dataTable;
				}
			},
			info: true,
			bFilter: true,
			columnDefs: [
				{ targets: 0, width: '15%',responsivePriority:1},
				{ targets: 1, width: '5%',responsivePriority: 2},
				{ targets: 2, width: '5%',responsivePriority: 3},
				{ targets: 3, width: '5%',responsivePriority: 4},
				{ targets: 4, width: '5%',responsivePriority: 5},
				{ targets: 5, width: '5%',responsivePriority: 6},
				{ targets: 6, width: '5%',responsivePriority: 7},
				{ targets: 7, width: '5%',responsivePriority: 9},
				{ targets: 8, width: '10%',responsivePriority: 10},
				{ targets: 9, width: '5%',responsivePriority: 11},
				{ targets: 10, width: '3%',responsivePriority: 8},
			],
			order: [[0, 'desc'], [3, 'desc'], [2, 'desc']],
			columns: [
				{
					title: 'Nombre',
					className: 'text-left',
					name: 'nombre',
					data: 'nombre',
				},
				{
					title: 'Cuit',
					className: 'text-left',
					name: 'cuit',
					data: 'cuit',
				},
				{
					title: 'Periodo',
					className: 'text-left',
					name: 'periodo',
					data: 'periodo',
				},

				{
					title: 'Fecha designación',
					className: 'text-center',
					name: 'fecha_designacion',
					data: 'fecha_designacion',
				},
				{
					title: 'Fecha Publicación Designación',
					className: 'text-left',
					name: 'fecha_publicacion_designacion',
					data: 'fecha_publicacion_designacion',
				},
				{
					title: 'Tipo Declaracón Jurada',
					className: 'text-left',
					name: 'dj',
					data: 'dj',
				},
				{
					title: 'Fecha presentación',
					className: 'text-left',
					name: 'fecha_presentacion',
					data: 'fecha_presentacion',
				},
				{
					title: 'Fecha aceptacion_renuncia',
					className: 'text-left',
					name: 'fecha_aceptacion_renuncia',
					data: 'fecha_aceptacion_renuncia',
				},
				{
					title: 'Cargo',
					className: 'text-left',
					name: 'nombre_puesto',
					data: 'nombre_puesto',
				},
				{
					title: 'Número Transacción',
					className: 'text-left',
					name: 'nro_transaccion',
					data: 'nro_transaccion',
				},
				{
					title: 'Acciones',
					className: 'text-center',
					name: 'accion',
					orderable: false,
					data: 'acciones',
					render: function (data, type, row) {
						$html = '';
						if(row.archivo){
							var  	$html = '<div class="btn-group btn-group-sm">';
							$html += '<a  href="'+$url_base+'/index.php/legajos/mostrar_presentacion/' +row.id_presentacion+ '" data-toggle="tooltip" data-placement="top" data-id="" title="Ver comprobante" data-toggle="modal"  target="_blank"><i class="fa fa-eye"></i></a>';
							$html += '</div>';
						}
						return $html;
					}
				},
			]
			});
			$.fn.dataTable.moment('DD/MM/YYYY');

			/**
			* Consulta al servidor los datos y redibuja la tabla
			* @return {Void}
			*/
			function update() {
			tabla.draw();
			}

			$('#periodo').on('change', update);
			$('#tipo_dj').on('change', update);


			/**
			* Movimiento de las flechas del filtro colapsable
			*/
			var $collapseFiltros = $('#collapseFiltros');
			var $collapseFiltrosCaret = $("#collapseFiltros_caret");
			$collapseFiltros.on('hide.bs.collapse', function () {
				$collapseFiltrosCaret.removeClass('fa-caret-down').addClass('fa-caret-right')
			});
			$collapseFiltros.on('show.bs.collapse', function () {
				$collapseFiltrosCaret.removeClass('fa-caret-right').addClass('fa-caret-down')
			});

			$(".accion_exportador").click(function () {
				var form = $('<form/>', {id:'form_ln' , action : $(this).val(), method : 'POST'});
				$(this).append(form);
				form.append($('<input/>', {name: 'periodo', type: 'hidden', value: $("#periodo").val() }))
				.append($('<input/>', {name: 'tipo_dj', type: 'hidden', value: $("#tipo_dj").val() }))
				.append($('<input/>', {name: 'search', type: 'hidden', value: $('div.dataTables_filter input').val() }))
				.append($('<input/>', {name: 'campo', type: 'hidden', value: $('#histo_anticorrupcion').dataTable().fnSettings().aoColumns[$('#histo_anticorrupcion').dataTable().fnSettings().aaSorting[0][0]].name }))
				.append($('<input/>', {name: 'dir', type: 'hidden', value: $('#histo_anticorrupcion').dataTable().fnSettings().aaSorting[0][1] }))
				.append($('<input/>', {name: 'rows', type: 'hidden', value: $('#histo_anticorrupcion').dataTable().fnSettings().fnRecordsDisplay() }));
				form.submit();
			});
		}
	});
});
