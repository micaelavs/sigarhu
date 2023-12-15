$(document).ready(function () {

    var _table  = $('#tabla').DataTable({
        language: {
            url: $endpoint_cdn+'/datatables/1.10.12/Spanish_sym.json',
            search: '_INPUT_',
            searchPlaceholder: 'Ingrese búsqueda'
        },
        autoWidth: false,
        bFilter: true,
        info: true,

        columnDefs: [
        { targets: 0, width: '40%',className: 'text-left'},
        { targets: 1, width: '20%',className: 'text-left'},
        { targets: 2, width: '333', orderable: false},
        ],
        order: [0,'desc']
    });

});

