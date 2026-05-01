  <!-- Content Header (Page header) -->
  <section class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h1>Usuarios</h1>
              </div>
              <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
                      <li class="breadcrumb-item active">Usuarios</li>
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
                  <h3 class="card-title font-weight-bold mb-0" style="font-size: 1.5rem; line-height: 2;">USUARIOS</h3>
                  <div class="card-tools ml-auto">
                      <button type="button" class="btn btn-success mr-2">Importar Usuarios</button>
                      <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-agregarUsuario">Agregar Usuario</button>
                  </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                  <table id="tblUsuarios" class="table table-dark table-bordered table-striped dt-responsive nowrap" style="width:100%">
                      <thead style="background-color: #198754; color: white;">
                          <tr>
                              <th>ID</th>
                              <th>Tipo de Documento</th>
                              <th>Nº de Documento</th>
                              <th>Nombre</th>
                              <th>Apellidos</th>
                              <th>Correo</th>
                              <th>Ficha</th>
                              <th>Rol</th>
                              <th>Estado</th>
                              <th>Acciones</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php
                            $respuesta = ControladorUsuarios::ctrListarUsuarios();
                            // var_dump($respuesta);
                            foreach ($respuesta as $usuario) {
                                echo "<tr>";
                                echo "<td>" . $usuario['id'] . "</td>";
                                echo "<td>" . $usuario['tipo_documento'] . "</td>";
                                echo "<td>" . $usuario['documento_id'] . "</td>";
                                echo "<td>" . $usuario['nombres'] . "</td>";
                                echo "<td>" . $usuario['apellidos'] . "</td>";
                                echo "<td>" . $usuario['correo'] . "</td>";
                                echo "<td>" . $usuario['ficha'] . "</td>";
                                echo "<td>" . $usuario['rol'] . "</td>";
                                echo "<td>";
                                if ($usuario['estado'] == 'activo') {
                                    echo "<button class='btn btn-xs btn-success'>activo</button>";
                                } else {
                                    echo "<button class='btn btn-xs btn-danger'>inactivo</button>";
                                };
                                echo "</td>";
                                echo "<td>";
                                echo '<div class="btn-group">
                            <button class="btn btn-sm btn-outline-light" data-toggle="modal" data-target="#modal-editarUsuario"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-light" data-toggle="modal" data-target="#modal-consultarUsuario"><i class="fas fa-eye"></i></button>
                          </div>
                        </td>';

                                echo "</tr>";
                            };

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
AGREGAR USUARIO   -->

  <div class="modal fade" id="modal-agregarUsuario">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title">Agregar usuario</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form action="" method="post">

                      <div class="form-group">
                          <div class="row">
                              <div class="col-md-5">
                                  <!-- <label for="">Tipo</label> -->
                                  <select class="form-control select2bs4" name="nuevoTipoDocumento" id="" required>
                                      <option value="">Tipo...</option>
                                      <option value="">TI</option>
                                      <option value="">CC</option>
                                      <option value="">CE</option>
                                      <option value="">PPT</option>
                                  </select>
                              </div>
                              <div class="input-group col-md-7">
                                  <div class="input-group-prepend">
                                      <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                  </div>
                                  <input type="text" class="form-control" name="nuevoDocumento" placeholder="Numero identificación" required>
                              </div>

                          </div>
                      </div>

                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-user"></i></span>
                          </div>
                          <input type="text" class="form-control" name="nuevoNombre" placeholder="Nombre" required>
                      </div>

                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-user"></i></span>
                          </div>
                          <input type="text" class="form-control" name="nuevoApellido" placeholder="Apellidos" required>
                      </div>

                      <label for="">Fecha de nacimiento</label>
                      <div class="input-group mb-3 date">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            </div>
                          <input type="date" class="form-control" name="nuevoFechaNacimiento" placeholder="Fecha de nacimiento" required>
                      </div>

                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                          </div>
                          <input type="email" class="form-control" name="nuevoCorreo" placeholder="Correo" required>
                      </div>

                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                          </div>
                          <select class="form-control" name="nuevoRol" required>
                              <option value="">Seleccionar rol...</option>
                              <option value="Bienestar">Bienestar</option>
                              <option value="Financiera">Financiera</option>
                              <option value="Aprendiz">Aprendiz</option>
                          </select>
                      </div>

                    <?php
                        $agregarUsuario = new ControladorUsuarios();
                        $agregarUsuario->ctrAgregarUsuario();
                    ?>
              </div>
              <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                  <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <!-- ********************************************************************************************************
EDITAR USUARIO   -->

  <div class="modal fade" id="modal-editarUsuario">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title">Editar usuario</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <p>Contenido</p>
              </div>
              <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                  <button type="button" class="btn btn-primary">Guardar</button>
              </div>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->


  <!-- ********************************************************************************************************
CONSULTAR USUARIO   -->

  <div class="modal fade" id="modal-consultarUsuario">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title">Consultar usuario</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <p>Contenido</p>
              </div>
              <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                  <button type="button" class="btn btn-primary">Guardar</button>
              </div>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
  <!-- ********************************************************************************************************
******************************************************************************************************** -->