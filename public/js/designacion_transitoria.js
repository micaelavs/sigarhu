$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    var $url_base = $('#url-base').attr('data_url');


    var tabla = $('#designacion_transitoria').DataTable({
        language: {
            url: $endpoint_cdn+'/datatables/1.10.12/Spanish_sym.json',
            decimal: ',',
            thousands: '.'
        },
        processing: true,
        serverSide: true,
        responsive: true,
        searchDelay: 1200,

        ajax: {
            url:$url_base + '/index.php/escalafon/ajax_designacion_transitoria',
            contentType: "application/json",
            data: function (d) {
            }
        },
        info: true,
        bFilter: true,
        columnDefs: [
            { targets: 0, width: '10%',responsivePriority:1},
            { targets: 1, width: '25%',responsivePriority:2},
            { targets: 2, width: '10%',responsivePriority:3},
            { targets: 3, width: '10%',responsivePriority:4},
            { targets: 4, width: '5%',responsivePriority:5},
            { targets: 5, width: '5%',responsivePriority:6},
            { targets: 6, width: '10%',responsivePriority:6},
        ],
        order: [[0, 'desc']],
        columns: [
            {
                title: 'CUIT',
                className: 'text-left',
                name: 'cuit',
                data: 'cuit',

            },
            {
                title: 'Nombre',
                className: 'text-left',
                name: 'nombre',
                data: 'nombre',
            },
            {
                title: 'Apellido',
                className: 'text-left',
                name: 'apellido',
                data: 'apellido',
            },
            {
                title: 'Fecha publicación',
                className: 'text-left',
                name: 'fecha_desde',
                data: 'fecha_desde',

            },
            {
                title: 'Fecha vencimiento',
                className: 'text-left',
                name: 'fecha_hasta',
                data: 'fecha_hasta',

            },
            {
                title: 'Tipo',
                className: 'text-left',
                name: 'nombre_tipo',
                data: 'nombre_tipo',

            },
            {
                title: 'Estado',
                className: 'text-center',
                orderable: true,
                name: 'estado',
                data: 'estado',
                render: function (data, type, row) {

                    var $html;
                    switch(data) {
                      case 'amarillo':
                        $html = '<span class="fa fa-circle color-warning" title="Próximo a vencer"></span>';
                        break;
                      case 'rojo':
                        $html = '<span class="fa fa-circle color-danger" title="Vencido"></span>';
                        break;
                      case 'verde':
                        $html = '<span class="fa fa-circle color-success" title="Vigente"></span>';
                        break;
                      default:
                        $html = '';
                        break;
                    }
                   return $html;
                }
            },
            {
                title: 'Acciones',
                className: 'text-center',
                name: 'accion',
                orderable: false,
                data: 'estado',
                render: function (data, type, row) {
                	//alert(estado);
                  if(row.id_usuario == row.id_logueado){
                      var  	$html = '<div class="btn-group btn-group-sm">';
                      switch(data){
						case 'amarillo':
						case 'verde':
							$html += '<a href="'+$url_base+'/index.php/escalafon/editar_prorroga/' +row.id + '"   data-toggle="tooltip" data-id="'+row.id+'" title="Editar prorroga" id= "editar_prorroga"><i class="fa fa-pencil"></i></a>';
							$html += '</div>';
							$html += '&nbsp; ';
							$html += '<a href="'+$url_base+'/index.php/escalafon/historial_designacion/' +row.cuit + '"  data-toggle="tooltip" data-id="'+row.cuit+'" title="Historial" id= "Historial"><i class="fa fa-history"></i></a>';
							$html += '&nbsp; ';
							$html += '</div>';
						break;
						case 'rojo':
							$html += '<a href="'+$url_base+'/index.php/escalafon/agregar_prorroga/' +row.cuit + '"   data-toggle="tooltip" data-id="'+row.cuit+'" title="Agregar prorroga" id= "agregar_prorroga"><i class="fa fa-plus"></i></a> ';
							$html += '</div>';
							$html += '&nbsp; ';
							$html += '<a href="'+$url_base+'/index.php/escalafon/historial_designacion/' +row.cuit + '"  data-toggle="tooltip" data-id="'+row.cuit+'" title="Historial" id= "Historial"><i class="fa fa-history"></i></a>';
							$html += '&nbsp; ';
							$html += '</div>';
							$html += '<a href="'+$url_base+'/index.php/escalafon/baja_prorroga/' +row.id_designacion + '"  data-toggle="tooltip" data-id="'+row.id_designacion+'" title="Eliminar prorroga" id= "eliminar_prorroga"><i class="fa fa-trash"></i></a>';
							$html += '</div>';
							$html += '&nbsp; ';
	                        break;
	                    default:
	                        $html = '';
	                        break;
                      }
                       return $html;
                    }
                }

            }
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

});

$(document).delegate('.volver_legajo', 'click', function(){
    var volver = $(this);
    var form = $('<form/>', {id:'form_ln' , action : volver.data('ref'), method : 'POST'});
    var input = $('<input />', {id : 'id_bloque', name: 'id_bloque', type: 'hidden', value: '' });
    volver.append(form);
    form.append(input);
    $('#id_bloque').val(volver.data('bloque'));
    form.submit();
});