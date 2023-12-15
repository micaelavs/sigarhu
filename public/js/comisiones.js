$(document).ready(function () {

    var _table = $('#tabla').DataTable({
        language: {
            search: '_INPUT_',
            searchPlaceholder: 'Ingrese búsqueda'
        },
        autoWidth: false,
        bFilter: true,
        info: true,

        columnDefs: [
            { targets: 0, className: 'text-left' },
             { targets: 1, width: '5%', orderable: false },
        ],
        order: [0, 'asc']
    });

}); 