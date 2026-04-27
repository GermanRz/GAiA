  <!-- Content Wrapper. Contains page content -->
  <!-- <div class="content-wrapper"> -->
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
                      <button type="button" class="btn btn-success">Agregar Usuario</button>
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
                                if ($usuario['estado'] == 'activo'){
                                    echo "<button class='btn btn-xs btn-success'>activo</button>";
                                } else {
                                    echo "<button class='btn btn-xs btn-danger'>inactivo</button>";
                                };
                                echo "</td>";
                                echo "<td>";
                                echo '<div class="btn-group">
                            <button class="btn btn-sm btn-outline-light"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-light"><i class="fas fa-eye"></i></button>
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

  <!-- </div> -->
  <!-- /.content-wrapper -->