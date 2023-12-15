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
        { targets: 0, visible: false },
        { targets: 1, width: '5%' },
        { targets: 2, width: '10%' },
        { targets: 3, width: '5%' },
        { targets: 4, width: '10%' },
        { targets: 5, width: '20%' },
        { targets: 6, width: '5%', orderable: false },

        ],
        order: [[3,'desc'],[0,'desc']]
    });

    $.fn.dataTable.moment('DD/MM/YYYY');


});