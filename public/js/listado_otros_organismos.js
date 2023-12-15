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
        { targets: 0, width: '30%',className: 'text-left' },
        { targets: 1, width: '20%' },
        { targets: 2, width: '10%', orderable: false },
        ],
        order: [[1,'desc'],[0,'asc'],[2,'asc']]
    });
});  
