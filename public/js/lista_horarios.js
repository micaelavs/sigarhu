 $(document).ready(function () {  
    var _table  = $('#tabla').DataTable({
        language: {
            search: '_INPUT_',
            searchPlaceholder: 'Ingrese búsqueda'
        },
        autoWidth: false,
        bFilter: true,
        info: true,
        
        columnDefs: [
        { targets: 0, width: '25%' },
        { targets: 1, width: '15%' },
        { targets: 2, width: '15%' },
        { targets: 3, width: '15%' },
        { targets: 4, width: '15%' },
        { targets: 5, width: '15%' },
        ],
        order: [0,'desc']
    });
});  