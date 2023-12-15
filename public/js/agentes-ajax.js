var filtros_dataTable = null;
$(document).ready(function ()  {

    var tabla = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        searchDelay: 1200,
        ajax: {
            url: $base_url + '/legajos/ajax_lista_agentes',
            contentType: "application/json",
            data: function (d) {
                filtros_dataTable = $.extend({}, d, {
                    dependencia             :   $('#id_dependencia').val(),
                    directos                :   $('#directos').prop("checked"),
                    situacion_revista       :   $('#situacion_revista').val(),
                    modalidad_contratacion  :   $('#modalidad_contratacion').val(),
                    estado                  :   $('#estado').val()

                });
                return filtros_dataTable;
            }
        },
        order: [[3, 'desc'],[4, 'desc'],[2, 'asc'],[1, 'asc']],
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
                width: '250',
                name: 'nombre',
                data: 'nombre',
            },
            {
                title: 'Apellido',
                className: 'text-left',
                width: '250',
                name: 'apellido',
                data: 'apellido',
            },            
            {
                title: 'Modalidad Vinculación',
                className: 'text-left',
                name: 'modalidad_vinculacion',
                data: 'modalidad_vinculacion',
            },
            {
                title: 'Situación Revista',
                className: 'text-center',
                name: 'situacion_revista',
                data: 'situacion_revista',
            },
            {
                title: 'Estado',
                className: 'text-center',
                name: 'estado',
                data: 'estado',render:function(data, type, row){
                    if(data == '1'){
                        return '<span class="label label-success" title="Activo"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></span>';
                    } else {
                        return '<span class="label label-danger" title="Inactivo"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i></span>';
                    }
                }
            },
            // {
            //     title: 'Fecha Fin',
            //     className: 'text-center',
            //     name: 'fecha_fin',
            //     data: 'fecha_fin',
            // },
            {
                data: 'acciones',
                title: 'Acciones',
                name: 'accion',
                width: '80',
                className: 'text-center',
                orderable: false,
                render: function (data, type, row, obj) {
                    var cuit = row.cuit;
                    $html = '<div class="btn-group btn-group-sm">'
                    $html += '<a href="'+$base_url+'/legajos/gestionar/' +cuit + '" class="btn btn-link btn-sm" data-toggle="tooltip" title="Gestionar Legajo" name="visita_baja"><i class="fa fa-edit"></i></a>';                    
                    if ($iri) {
                        $html += '<a href="' + $base_url + '/recibos/index/' + cuit + '" class="btn btn-link btn-sm" data-toggle="tooltip" title="Remuneración" name=""><i class="fa fa-money"></i></a>';
                    }    
                    $html += '</div>';
 
                    return $html;
                }
            }
        ]
    });

    /**
     * Consulta al servidor los datos y redibuja la tabla
     * @return {Void}
    */
    function update() {
        tabla.draw();
    }

    /**
     * Acciones para los filtros, colapsar/mostrar, actualizar vista
    */
    $('#id_dependencia').on('change', update);
    $('#directos').on('change', update);
    $('#situacion_revista').on('change', update);
    $('#modalidad_contratacion').on('change', update);
    $('#modalidad_contratacion').on('change', function(){ 
         $.ajax({
            url: $base_url+"/Legajos/ajax_convenios_parametricos",
            data: {
                id_modalidad_vinculacion:   $('select#modalidad_contratacion').val(),
            },
            method: "GET"
        })
        .done(function (data) {
            if(data.situacion_revista !== undefined) {
                addOptions(data.situacion_revista, 'select#situacion_revista');
            }
        });
    });    
    $('#estado').on('change', update);    

    $('#id_dependencia, #situacion_revista, #modalidad_contratacion').select2();

    var $collapseFiltros = $('#collapseFiltros');
    var $collapseFiltrosCaret = $("#collapseFiltros_caret");
    $collapseFiltros.on('hide.bs.collapse', function () {
        $collapseFiltrosCaret.removeClass('fa-caret-down').addClass('fa-caret-right')
    });

    $collapseFiltros.on('show.bs.collapse', function () {
        $collapseFiltrosCaret.removeClass('fa-caret-right').addClass('fa-caret-down')
    });

    $("#accion_exportador").click(function () { 
        var form = $('<form/>', {id:'form_ln' , action : $(this).val(), method : 'POST'});
        $(this).append(form);
        form.append($('<input/>', {name: 'dependencia', type: 'hidden', value: $("#id_dependencia").val() }))
            .append($('<input/>', {name: 'estado', type: 'hidden', value: $("#estado").val() }))
            .append($('<input/>', {name: 'modalidad_contratacion', type: 'hidden', value: $("#modalidad_contratacion").val() }))
            .append($('<input/>', {name: 'situacion_revista', type: 'hidden', value: $("#situacion_revista").val() }))
        form.submit();
    });

    $("#directos").click(function () {
        var label = 'Directos'; 
        if($(this).prop("checked") == false) {
            label = 'Todos'; 
        }
        $("#label_directos").text(label);
    });

}),$iri;