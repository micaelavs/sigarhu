$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    var $url_base = $('#url-base').attr('data_url');    

    if ($('#grados').length) {
        var tabla = $('#grados').DataTable({
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
                url:$url_base + '/index.php/escalafon/ajax_lista_grados',
                contentType: "application/json",
                data: function (d) {

                }
            },
            info: true,
            bFilter: true,
            columnDefs: [
                { targets: 0, width: '22%' },
                { targets: 1, width: '22%' },
                { targets: 2, width: '22%' },
                { targets: 3, width: '24%' },
                { targets: 4, width: '10%' }
            ],
            order: [[0, 'asc'],[1, 'asc'],[2, 'asc'],[3, 'asc']],
            columns: [
                {
                    title: 'Modalidad Vinculación',
                    className: 'text-left',
                    name: 'modalidad',
                    data: 'modalidad',

                },
                {
                    title: 'Situación Revista',
                    className: 'text-left',
                    name: 'revista',
                    data: 'revista',

                },
                {
                    title: 'Tramo',
                    className: 'text-left',
                    name: 'tramo',
                    data: 'tramo',

                },
                {
                    title: 'Grado',
                    className: 'text-left',
                    name: 'grado',
                    data: 'grado',
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
                            $html += '<a href="'+$url_base+'/index.php/escalafon/grado/' +row.id + '" data-toggle="tooltip" data-id="'+row.id+'" title="Modificar Grado" id= "modificar_grado"><i class="fa fa-pencil"></i></a>';                    
                            $html += ' ';
                            $html += '<a href="'+$url_base+'/index.php/escalafon/baja_grado/' +row.id + '" data-toggle="tooltip" data-id="'+row.id+'" title="Eliminar Grado" id= "eliminar_grado"><i class="fa fa-trash"></i></a>';                    
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
    }

    $('#id_modalidad_vinculacion').on('change', function(){
        $.ajax({
            url: $url_base + '/index.php/escalafon/ajax_get_revista',
            data: {id_modalidad: $(this).val()},
            method: "POST"
        })
        .done(function (data) {
            if(data.revista !== undefined) {
                addOptions(data.revista, 'select#id_situacion_revista', true);
            }
        });
    });
    $('#id_situacion_revista').on('change', function(){
        $.ajax({
            url: $url_base + '/index.php/escalafon/ajax_get_tramos',
            data: {
                id_modalidad: $("#id_modalidad_vinculacion").val(),
                id_revista: $(this).val()
            },
            method: "POST"
        })
        .done(function (data) {
            if(data.tramos !== undefined) {
                addOptions(data.tramos, 'select#id_tramo', true);
            }
        });
    });

}); 