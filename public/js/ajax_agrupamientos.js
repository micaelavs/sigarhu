$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    var $url_base = $('#url-base').attr('data_url');

    if ($('#agrupamientos').length) {
        var tabla = $('#agrupamientos').DataTable({
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
                url:$url_base + '/index.php/escalafon/ajax_lista_agrupamientos',
                contentType: "application/json",
                data: function (d) {

                }
            },
            info: true,
            bFilter: true,
            columnDefs: [
                { targets: 0, width: '20%' },
                { targets: 1, width: '30%'},
                { targets: 2, width: '40%'},
                { targets: 3, width: '10%'}
            ],
            order: [[2, 'asc']],
            columns: [
                {
                    title: 'Modalidad de Vinculación',
                    className: 'text-left',
                    name: 'modalidad_vinculacion',
                    data: 'modalidad_vinculacion',

                },
                {
                    title: 'Situación de Revista',
                    className: 'text-left',
                    name: 'situacion_revista',
                    data: 'situacion_revista',
                },
                {
                    title: 'Agrupamiento',
                    className: 'text-left',
                    name: 'agrupamiento',
                    data: 'agrupamiento',
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
                            $html += '<a href="'+$url_base+'/index.php/escalafon/agrupamiento/' +row.id + '"   data-toggle="tooltip" data-id="'+row.id+'" title="Modificar Agrupamiento" id= "modificar_agrupamiento"><i class="fa fa-pencil"></i></a> ';                   
                            $html += '<a href="'+$url_base+'/index.php/escalafon/baja_agrupamiento/' +row.id + '"  data-toggle="tooltip" data-id="'+row.id+'" title="Eliminar Agrupamiento" id= "eliminar_agrupamiento"><i class="fa fa-trash"></i></a>';                    
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

});