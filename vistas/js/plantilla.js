$(function () {
    $("#tblFichas").DataTable(
        {
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["excel", "pdf"]
        }).buttons().container().appendTo('#tblApoyos_wrapper .col-md-6:eq(0)');

    $("#tblFichas").DataTable(
        {
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["excel", "pdf"]
        }).buttons().container().appendTo('#tblFichas_wrapper .col-md-6:eq(0)');

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