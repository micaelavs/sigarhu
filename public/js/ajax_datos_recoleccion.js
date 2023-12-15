$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();
    //var $url_base = $('#url-base').attr('data_url');    

    var tabla = $('#perso_uni').DataTable({
        language: {
            url: $endpoint_cdn+'/datatables/1.10.12/Spanish_sym.json',
            decimal: ',',
            thousands: '.'
        },
        processing: true,
        serverSide: true,
        responsive: false,
        searchDelay: 1200,

        ajax: {
            url:$url_base + '/index.php/legajos/ajax_datos_recoleccion',
            contentType: "application/json",
            data: function (d) {

            }
        },
        info: true,
        bFilter: true,
        columnDefs: [
                { targets: 0, width: '20%'},
                { targets: 1, width: '30%'},
                { targets: 2, width: '30%'},
                { targets: 3, width: '10%'}
        ],
        order: [[0, 'asc']],
        columns: [
            {
                title: 'Cuit',
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
                title: 'Acciones',
                className: 'text-center',
                name: 'accion',
                orderable: false,
                data: 'acciones',
                render: function (data, type, row) {
                  if(row.id_usuario == row.id_logueado){
                      var  $html = '<span class="acciones">';
                      $html += '<a href="#" data-cuit="' + row.cuit + '" data-bloque="3" class="btn btn-link btn-sm" data-toggle="tooltip" title="Gestionar Legajo"><i class="fa fa-edit"></i></a>';
                      return $html;
                    }
                }

                }
        ]
    });
    // $.fn.dataTable.moment('DD/MM/YYYY');
    /**
     * Consulta al servidor los datos y redibuja la tabla
     * @return {Void}
    */

    var tabla = $('#vinculacion').DataTable({
        language: {
            url: $endpoint_cdn+'/datatables/1.10.12/Spanish_sym.json',
            decimal: ',',
            thousands: '.'
        },
        processing: true,
        serverSide: true,
        responsive: false,
        searchDelay: 1200,

        ajax: {
            url:$url_base + '/index.php/legajos/ajax_datos_vinculacion',
            contentType: "application/json",
            data: function (d) {

            }
        },
        info: true,
        bFilter: true,
        columnDefs: [
                { targets: 0, width: '20%'},
                { targets: 1, width: '30%'},
                { targets: 2, width: '30%'},
                { targets: 3, width: '10%'}
        ],
        order: [[0, 'asc']],
        columns: [
            {
                title: 'Cuit',
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
                title: 'Acciones',
                className: 'text-center',
                name: 'accion',
                orderable: false,
                data: 'acciones',
                render: function (data, type, row) {
                  if(row.id_usuario == row.id_logueado){
                      var  $html = '<span class="acciones">';
                        $html += '<a href="#" data-cuit="'+row.cuit+'" data-bloque="2" class="btn btn-link btn-sm" data-toggle="tooltip" data-id="'+row.id+'" title="Gestionar Legajo"><i class="fa fa-edit"></i></a>';                    
                        return $html;
                    }
                }

                }
        ]
    });
    // $.fn.dataTable.moment('DD/MM/YYYY');
    /**
     * Consulta al servidor los datos y redibuja la tabla
     * @return {Void}
    */


var tabla = $('#formacion').DataTable({
        language: {
            url: $endpoint_cdn+'/datatables/1.10.12/Spanish_sym.json',
            decimal: ',',
            thousands: '.'
        },
        processing: true,
        serverSide: true,
        responsive: false,
        searchDelay: 1200,

        ajax: {
            url:$url_base + '/index.php/legajos/ajax_datos_formacion',
            contentType: "application/json",
            data: function (d) {

            }
        },
        info: true,
        bFilter: true,
        columnDefs: [
                { targets: 0, width: '20%'},
                { targets: 1, width: '30%'},
                { targets: 2, width: '30%'},
                { targets: 3, width: '10%'}
        ],
        order: [[0, 'asc']],
        columns: [
            {
                title: 'Cuit',
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
                title: 'Acciones',
                className: 'text-center',
                name: 'accion',
                orderable: false,
                data: 'acciones',
                render: function (data, type, row) {
                  if(row.id_usuario == row.id_logueado){
                      var  $html = '<span class="acciones">';
                      $html += '<a href="#" data-cuit="' + row.cuit + '" data-bloque="5" class="btn btn-link btn-sm" data-toggle="tooltip" title="Gestionar Legajo"><i class="fa fa-edit"></i></a>';
                      return $html;
                    }
                }

                }
        ]
    });

    function update() {
        tabla.draw();
    }

    $(document).delegate('.btn.btn-link.btn-sm', 'click', function () {
        var volver = $('#volver');
        var form = $('<form/>', {
            id: 'form_ln', action: $url_base +'/index.php/legajos/gestionar/'+ $(this).data('cuit'), method: 'POST' });
        var input = $('<input />', { id: 'id_bloque', name: 'id_bloque', type: 'hidden' });
        volver.append(form);
        form.append(input);
        $('#id_bloque').val($(this).data('bloque'));
        form.submit();
    });
    
}); 