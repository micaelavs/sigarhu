$(document).ready(function () {

    var _table  = $('#tabla').DataTable({
        language: {
            search: '_INPUT_',
            searchPlaceholder: 'Ingrese búsqueda'
        },
        autoWidth: false,
        info: false,
        bFilter: true,
        columnDefs: [
        { targets: 0, width: '10%' },
        { targets: 1, width: '20%' },
        { targets: 2, width: '5%' },
        { targets: 3, width: '5%' },
        { targets: 4, width: '10%' },
        { targets: 5, width: '5%', orderable: false },

        ],
        order: [[2,'desc'],[0,'desc']]
    });

    $.fn.dataTable.moment('DD/MM/YYYY');


});