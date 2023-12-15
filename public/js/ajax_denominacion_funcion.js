$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    var $url_base = $('#url-base').attr('data_url');    

    var tabla = $('#denom_funcion').DataTable({
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
            url:$url_base + '/index.php/denominacion_funcion/ajax_lista_denominacion_funcion',
            contentType: "application/json",
            data: function (d) {

            }
        },
        info: true,
        bFilter: true,
        columnDefs: [
            { targets: 0, width: '50%' },
            { targets: 1, width: '15%'}
        ],
        order: [[0, 'asc']],
        columns: [
            {
                title: 'Denominación de la Función',
                className: 'text-left',
                name: 'nombre',
                data: 'nombre',

            },
            {
                title: 'Acciones',
                className: 'text-center',
                name: 'accion',
                orderable: false,
                data: 'acciones',
                render: function (data, type, row) {
                  if(row.id_usuario == row.id_logueado){
                      var  $html = '<div class="btn-group btn-group-sm">';
                        $html += '<a href="'+$url_base+'/index.php/denominacion_funcion/gestionar/' +row.id + '"  class="btn btn-link btn-sm" data-toggle="tooltip" data-id="'+row.id+'" title="Modificar Denominación" id= "modificar_denominacion"><i class="fa fa-edit"></i></a>';
                        $html += '</div>';
                        $html += '<a href="'+$url_base+'/index.php/denominacion_funcion/baja/' +row.id + '"  class="btn btn-link btn-sm" data-toggle="tooltip" data-id="'+row.id+'" title="Eliminar Denominación" id= "eliminar_denominacion"><i class="fa fa-trash"></i></a>';
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