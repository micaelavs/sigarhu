$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    var $url_base = $('#url-base').attr('data_url');    

    var tabla = $('#mod_vinculacion').DataTable({
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
            url:$url_base + '/index.php/escalafon/ajax_lista_modalidad_vinculacion',
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
                title: 'Modalidad',
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
                      var  $html = '<span class="acciones">';
                        $html += '<a href="'+$url_base+'/index.php/escalafon/modalidad_vinculacion/' +row.id + '" data-toggle="tooltip" data-id="'+row.id+'" title="Modificar Modalidad" id= "modificar_modalidad"><i class="fa fa-pencil"></i></a>';                    
                        $html += ' ';
                        $html += '<a href="'+$url_base+'/index.php/escalafon/baja_modalidad_vinculacion/' +row.id + '" data-toggle="tooltip" data-id="'+row.id+'" title="Eliminar Modalidad" id= "eliminar_modalidad"><i class="fa fa-trash"></i></a>';                    
                        $html += '</span>';
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