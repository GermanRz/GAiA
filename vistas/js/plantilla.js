$(document).ready(function () {
  $(function () {

    $("#tblUsuarios")
      .DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        buttons: ["excel", "pdf"],
      })
      .buttons()
      .container()
      .appendTo("#tblUsuarios_wrapper .col-md-6:eq(0)");    

    $("#tblApoyos")
      .DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        buttons: ["excel", "pdf"],
      })
      .buttons()
      .container()
      .appendTo("#tblApoyos_wrapper .col-md-6:eq(0)");

    $("#tblSostenimiento")
      .DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        buttons: ["excel", "pdf"],
      })
      .buttons()
      .container()
      .appendTo("#tblSostenimiento_wrapper .col-md-6:eq(0)");

    $("#tblTransporte")
      .DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        buttons: ["excel", "pdf"],
      })
      .buttons()
      .container()
      .appendTo("#tblTransporte_wrapper .col-md-6:eq(0)");

    $("#tblAlimentacion")
      .DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        buttons: ["excel", "pdf"],
      })
      .buttons()
      .container()
      .appendTo("#tblAlimentacion_wrapper .col-md-6:eq(0)");

    $("#tblDatos")
      .DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        buttons: ["excel", "pdf"],
      })
      .buttons()
      .container()
      .appendTo("#tblDatos_wrapper .col-md-6:eq(0)");
  });
});
