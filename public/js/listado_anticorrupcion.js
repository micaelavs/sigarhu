$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    var $url_base = $('#url-base').attr('data_url');


    var tabla = $('#anticorrupcion').DataTable({
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
            url:$url_base + '/index.php/legajos/ajax_listado_anticorrupcion',
            contentType: "application/json",
            data: function (d) {
            }
        },
        info: true,
        bFilter: true,
        columnDefs: [
            { targets: 0, width: '10%',responsivePriority:1},
            { targets: 1, width: '20%',responsivePriority:2},
            { targets: 2, width: '20%',responsivePriority:4},
            { targets: 3, width: '10%',responsivePriority:5},
            { targets: 4, width: '5%',responsivePriority:3},
            { targets: 5, width: '5%',responsivePriority:3},
            { targets: 6, width: '5%',responsivePriority:2},
            { targets: 7, width: '5%',responsivePriority:2},
            { targets: 8, width: '5%',responsivePriority:1},
            { targets: 9, width: '5%',responsivePriority:1}
        ],
        order: [[8, 'desc']],
        columns: [
            {
                title: 'CUIT',
                className: 'text-left',
                name: 'cuit',
                data: 'cuit',

            },
            {
                title: 'Nombre Completo',
                className: 'text-left',
                name: 'nombre',
                data: 'nombre',

            },
            {
                title: 'Denominación del Cargo',
                className: 'text-left',
                name: 'nombre_puesto',
                data: 'nombre_puesto',

            },
            {
                title: 'Email',
                className: 'text-left',
                name: 'email',
                data: 'email',

            },
            {
                title: 'Tipo',
                className: 'text-left',
                name: 'tipo_presentacion',
                data: 'tipo_presentacion',

            },
            {
                title: 'Fecha',
                className: 'text-left',
                name: 'fecha_presentacion',
                data: 'fecha_presentacion',

            },
            {
                title: 'Período',
                className: 'text-left',
                name: 'periodo',
                data: 'periodo',

            },
            {
                title: 'Mora(días)',
                className: 'text-left',
                orderable: true,
                name: 'cant_dias',
                data: 'cant_dias',
                render: function (row) {
                    if(row == 'undefined'){
                        row = 0;
                    }
                    return row;
                }
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
                        $html = '<span class="fa fa-circle color-warning"></span>';
                        break;
                      case 'rojo':
                        $html = '<span class="fa fa-circle color-danger"></span>';
                        break;
                      case 'verde':
                        $html = '<span class="fa fa-circle color-success"></span>';
                        break;
                      case 'sin_presentacion':
                        $html = '<span class="fa fa-circle-o"></span>';
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
                data: 'acciones',
                render: function (data, type, row) {
                  if(row.id_usuario == row.id_logueado){
                      var   $html = '<div class="btn-group btn-group-sm">';
                        	$html += '<a href="#" data-toggle="tooltip" data-placement="top" data-ref="'+$url_base+'/index.php/legajos/gestionar/' +row.cuit + '" title="Ver Legajo" data-toggle="modal" class="volver_legajo" data-bloque="'+row.bloque+'"><i class="fa fa-eye"></i></a>'
                        	$html += '</div>';

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

$(document).delegate('.volver_legajo', 'click', function(e){
    e.preventDefault();
    var volver = $(this);
    var form = $('<form/>', {id:'form_ln' , action : volver.data('ref'), method : 'POST'});
    var input = $('<input />', {id : 'id_bloque', name: 'id_bloque', type: 'hidden', value: '' });
    volver.append(form);
    form.append(input);
    $('#id_bloque').val(volver.data('bloque'));
    form.submit();
});