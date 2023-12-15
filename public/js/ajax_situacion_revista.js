$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    var $url_base = $('#url-base').attr('data_url');    

    var tabla = $('#sit_revista').DataTable({
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
            url:$url_base + '/index.php/escalafon/ajax_lista_situacion_revista',
            contentType: "application/json",
            data: function (d) {

            }
        },
        info: true,
        bFilter: true,
        columnDefs: [
            { targets: 0, width: '30%' },
            { targets: 1, width: '60%'},
            { targets: 2, width: '10%'}
        ],
        order: [[0, 'asc']],
        columns: [
            {
                title: 'Modalidad de Viculación',
                className: 'text-left',
                name: 'mod_vinculacion',
                data: 'mod_vinculacion',

            },
            {
                title: 'Situación de Revista',
                className: 'text-left',
                name: 'situacion_revista',
                data: 'situacion_revista',
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
                        $html += '<a href="'+$url_base+'/index.php/escalafon/situacion_revista/' +row.id + '" data-toggle="tooltip" data-id="'+row.id+'" title="Modificar Situacion Revista" id= "modificar_revista"><i class="fa fa-pencil"></i></a>';                    
                        $html += ' ';
                        $html += '<a href="'+$url_base+'/index.php/escalafon/baja_situacion_revista/' +row.id + '" data-toggle="tooltip" data-id="'+row.id+'" title="Eliminar Situacion Revista" id= "eliminar_revista"><i class="fa fa-trash"></i></a>';                    
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