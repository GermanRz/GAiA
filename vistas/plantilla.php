<?php

  session_start();

?>

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

    <!-- DataTables -->
    <link rel="stylesheet" href="vistas/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="vistas/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="vistas/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">    

    <!-- ************************************************************ -->

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="vistas/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="vistas/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="vistas/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>

    <!-- DataTables  & Plugins -->
    <script src="vistas/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="vistas/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="vistas/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="vistas/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="vistas/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="vistas/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="vistas/plugins/jszip/jszip.min.js"></script>
    <script src="vistas/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="vistas/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="vistas/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="vistas/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="vistas/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>







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

<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed login-page">

    <?php
    if (isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok"){

      echo "<script>
        document.addEventListener('DOMContentLoaded', function(){
          document.body.classList.remove('login-page');
        });
      </script>"; //Script para remover la clase de login al momento de haber iniciado sesion
      echo '<div class="wrapper">'; //WRAPPER OPENED

      include 'modulos/encabezado.php';
      include 'modulos/menu.php';
      
      //Content Wrapper. Contains page content
      echo '<div class="content-wrapper">';
      
      if (isset($_GET["ruta"])) {
        if (
          $_GET["ruta"] == "inicio" ||
          $_GET["ruta"] == "puntuacion" ||
          $_GET["ruta"] == "apoyos" ||
          $_GET["ruta"] == "sedes" ||
          $_GET["ruta"] == "fichas" ||
          $_GET["ruta"] == "identificacion"|| 
          $_GET["ruta"] == "financiera"||
          $_GET["ruta"] == "verificacion"||  
          $_GET["ruta"] == "reportes"||  
          $_GET["ruta"] == "inscripciones"||
          $_GET["ruta"] == "Usuarios"||
          $_GET["ruta"] == "salir"
          ) {
            include "modulos/" . $_GET["ruta"] . ".php";
          } //Fin del enrutador
          else {
            include "modulos/error404.php";
          } // si la ruta no existe
      }
        echo '</div>'; //Content Wrapper closed
        include 'modulos/footer.php';

        echo '</div>'; //WRAPPER CLOSED
    } else {
      include "modulos/login.php";
    }
    ?>



  <script src="vistas/js/plantilla.js"></script>  
</body>

</html>