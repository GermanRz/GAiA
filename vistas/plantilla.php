<!DOCTYPE html>
<html lang="es">

<head>

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GAiA</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="vistas/plugins/fontawesome-free/css/all.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="vistas/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="vistas/dist/css/adminlte.min.css">

    <!-- ************************************************************ -->

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="vistas/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="vistas/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="vistas/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="vistas/dist/js/adminlte.js"></script>

    <!-- PAGE PLUGINS -->
    <!-- jQuery Mapael -->
    <script src="vistas/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
    <script src="vistas/plugins/raphael/raphael.min.js"></script>
    <script src="vistas/plugins/jquery-mapael/jquery.mapael.min.js"></script>
    <script src="vistas/plugins/jquery-mapael/maps/usa_states.min.js"></script>
    <!-- ChartJS -->
    <script src="vistas/plugins/chart.js/Chart.min.js"></script>

    <!-- AdminLTE for demo purposes -->
    <!-- <script src="vistas/dist/js/demo.js"></script> -->
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="vistas/dist/js/pages/dashboard2.js"></script>



  </head>

<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

  <div class="wrapper">
    <?php
    include 'modulos/encabezado.php';
    include 'modulos/menu.php';
    ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">


      <?php
      if (isset($_GET["ruta"])) {
        if (
          $_GET["ruta"] == "inicio" ||
          $_GET["ruta"] == "apoyos" || 
          $_GET["ruta"] == "financiera"
        ) {
          include "modulos/" . $_GET["ruta"] . ".php";
        } //fin del enrutador
        else {
          include "modulos/error404.php";
        } // si la ruta no existe
      }

      ?>

    </div>
    <!-- /.content-wrapper -->
    <?php
    include 'modulos/footer.php';
    ?>
  </div>



</body>

</html>