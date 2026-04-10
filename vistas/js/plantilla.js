$(function () {
    $("#tblApoyos").DataTable(
        {
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["excel", "pdf"]
        }).buttons().container().appendTo('#tblApoyos_wrapper .col-md-6:eq(0)');
        
    $("#tblUsuarios").DataTable(
        {
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "language": {
            "search": "",
            "searchPlaceholder": "Buscar Usuario",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        "buttons": [
            {
                extend: 'excel',
                text: 'EXCEL',
                className: 'btn btn-success btn-sm'
            },
            {
                extend: 'pdf',
                text: 'PDF',
                className: 'btn btn-success btn-sm'
            }
        ]
        }).buttons().container().appendTo('#tblUsuarios_wrapper .col-md-6:eq(0)');

    $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        }
    );
});