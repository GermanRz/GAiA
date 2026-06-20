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
                      <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-agregarSede">Agregar Sede</button>
                  </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                  <table id="tblSedes" class="table table-dark table-bordered table-striped dt-responsive nowrap" style="width:100%">
                      <thead style="background-color: #198754; color: white;">
                          <tr>
                              <th>ID</th>
                              <th>Nombre</th>
                              <th>Dirección</th>
                              <th>Estado</th>
                              <th>Acciones</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php
                            $respuesta = ControladorSedes::ctrListarSedes();
                            if ($respuesta) {
                                foreach ($respuesta as $sede) {
                                    echo "<tr>";
                                    echo "<td>" . $sede['id_sede'] . "</td>";
                                    echo "<td>" . $sede['descripcion_sede'] . "</td>";
                                    echo "<td>" . $sede['direccion_sede'] . "</td>";
echo "<td>";
                                     if ($sede['estado'] == 'activo') {
                                         echo "<button class='btn btn-xs btn-success btnInactivarSedeRecursiva' data-idSede='" . $sede['id_sede'] . "' title='Inactivar sede, fichas y aprendices'>activo</button>";
                                     } else {
                                         echo "<button class='btn btn-xs btn-danger btnActivarSede' data-estadoSede='activo' data-idSede='" . $sede['id_sede'] . "'>inactivo</button>";
                                     };
                                     echo "</td>";
                                    echo "<td>";
                                    echo '<div class="btn-group">
                                <button class="btn btn-sm btn-outline-light btnEditarSede" data-idSede="' . $sede["id_sede"] . '" data-toggle="modal" data-target="#modal-editarSede"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-light btnConsultarSede" data-idSede="' . $sede["id_sede"] . '" data-toggle="modal" data-target="#modal-consultarSede"><i class="fas fa-eye"></i></button>
                              </div>
                            </td>';
    
                                    echo "</tr>";
                                }
                            }
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
                              <span class="input-group-text"><i class="fas fa-building"></i></span>
                          </div>
                          <input type="text" class="form-control" name="nuevoNombreSede" placeholder="Nombre de la sede" required>
                      </div>

                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                          </div>
                          <input type="text" class="form-control" name="nuevaDireccionSede" placeholder="Dirección" required>
                      </div>

              </div>
              <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                  <button type="submit" class="btn btn-primary">Guardar</button>
              </div>
              <?php
                if(class_exists('ControladorSedes')){
                    $agregarSede = new ControladorSedes();
                    $agregarSede->ctrAgregarSede();
                }
                ?>
              </form>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <!-- ********************************************************************************************************
EDITAR SEDE   -->

  <div class="modal fade" id="modal-editarSede">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title">Editar sede</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form action="" method="post">
                      <input type="hidden" name="idSedeEditar" id="idSedeEditar">

                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-building"></i></span>
                          </div>
                          <input type="text" class="form-control" name="editarNombreSede" id="editarNombreSede" placeholder="Nombre de la sede" required>
                      </div>

                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                          </div>
                          <input type="text" class="form-control" name="editarDireccionSede" id="editarDireccionSede" placeholder="Dirección" required>
                      </div>

              </div>
              <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                  <button type="submit" class="btn btn-primary">Guardar cambios</button>
              </div>
              <?php
                if(class_exists('ControladorSedes')){
                    $editarSede = new ControladorSedes();
                    $editarSede->ctrEditarSede();
                }
                ?>
              </form>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->


  <!-- ********************************************************************************************************
CONSULTAR SEDE   -->

  <div class="modal fade" id="modal-consultarSede">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title">Consultar sede</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  
                  <div class="input-group mb-3">
                      <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-building"></i></span>
                      </div>
                      <input type="text" class="form-control" id="consultarNombreSede" readonly>
                  </div>

                  <div class="input-group mb-3">
                      <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                      </div>
                      <input type="text" class="form-control" id="consultarDireccionSede" readonly>
                  </div>

              </div>
              <div class="modal-footer justify-content-end">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              </div>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->