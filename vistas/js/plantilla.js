$(function () {
    $("#tblApoyos").DataTable(
        {
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["excel", "pdf"]
        }).buttons().container().appendTo('#tblApoyos_wrapper .col-md-6:eq(0)');
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

$(function () {
    $("#tblSedes").DataTable(
        {
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["excel", "pdf"]
        }).buttons().container().appendTo('#tblSedes_wrapper .col-md-6:eq(0)');
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