<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Sedes</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item active">Sedes</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="card bg-dark text-white">
      <div class="card-header border-0 d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold mb-0" style="font-size: 1.5rem; line-height: 2;">SEDES</h3>
        <div class="card-tools ml-auto">
          <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-agregarSede">Agregar
            Sede</button>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="tblSedes" class="table table-dark table-bordered table-striped dt-responsive nowrap"
          style="width:100%">
          <thead style="background-color: #198754; color: white;">
            <tr>
              <th>ID</th>
              <th>Descripción</th>
              <th>Dirección</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $respuesta = ControladorSedes::ctrListarSedes();
            // var_dump($respuesta);
            foreach ($respuesta as $sede) {
              echo "<tr>";
              echo "<td>" . $sede['id_sede'] . "</td>";
              echo "<td>" . $sede['descripcion_sede'] . "</td>";
              echo "<td>" . $sede['direccion_sede'] . "</td>";
              echo "<td>";
              echo '<div class="btn-group">
                            <button class="btn btn-sm btn-outline-light" data-toggle="modal" data-target="#modal-editarSede"><i class="fas fa-edit"></i></button>
                          </div>
                        </td>';

              echo "</tr>";
            }
            ;

            ?>

          </tbody>
        </table>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>


</section>
<!-- /.content -->

<!-- ********************************************************************************************************
AGREGAR SEDE   -->

<div class="modal fade" id="modal-agregarSede">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Agregar sede</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post">

          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-align-left"></i></span>
            </div>
            <input type="text" class="form-control" name="nuevaDescripcionSede" id="nuevaDescripcion" placeholder="Descripcion" required>
          </div>

          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
            </div>
            <input type="text" class="form-control" name="nuevaDireccionSede" placeholder="Direccion" required>
          </div>

      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
      <?php
      $agregarSede = new ControladorSedes();
      $agregarSede->ctrAgregarSede();
      ?>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->