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
        { targets: 0, width: '20%',className: 'text-left' },
        { targets: 1, width: '15%',className: 'text-left' },
        { targets: 2, width: '5%' },
        { targets: 3, width: '15%',className: 'text-left' },
        { targets: 4, width: '15%',className: 'text-left' },
        { targets: 5, width: '10%' },
        { targets: 6, width: '10%', orderable: false},
        ],
        order: [0,'desc']
    });
});  