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
                      <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#modal-importarUsuarios">
                          <i class="fas fa-file-import mr-1"></i> Importar Aprendices
                      </button>
                      <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-agregarUsuario">Agregar Usuario</button>
                  </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                  <table id="tblUsuarios" class="table table-dark table-bordered table-striped dt-responsive" style="width:100%">
                      <thead style="background-color: #198754; color: white;">
                          <tr>
                              <th style="width: 4%;">ID</th>
                              <th style="width: 6%;">Tipo Doc.</th>
                              <th style="width: 11%;">Nº Documento</th>
                              <th style="width: 15%;">Nombre</th>
                              <th style="width: 15%;">Apellidos</th>
                              <th style="width: 20%;">Correo</th>
                              <th style="width: 8%;">Ficha</th>
                              <th style="width: 8%;">Rol</th>
                              <th style="width: 6%;">Estado</th>
                              <th style="width: 7%;">Acciones</th>
                          </tr>
                      </thead>
                      <tbody>
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
                  <form id="formAgregarUsuario" action="" method="post" enctype="multipart/form-data">
                      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                      <div class="form-group">
                          <div class="row">
                              <div class="col-md-5">
                                  <!-- <label for="">Tipo</label> -->
                                  <select class="form-control" name="nuevoTipoDocumento" required>
                                      <option value="">Tipo...</option>
                                      <option value="TI">TI</option>
                                      <option value="CC">CC</option>
                                      <option value="CE">CE</option>
                                      <option value="PEP">PEP</option>
                                      <option value="PPT">PPT</option>
                                  </select>
                              </div>
                              <div class="input-group col-md-7">
                                  <div class="input-group-prepend">
                                      <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                  </div>
                                  <input type="text" class="form-control" name="nuevoDocumento" id="nuevoDocumento" placeholder="Numero identificación" required>
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
                          <select class="form-control" name="nuevoRol" id="nuevoRol" required>
                              <option value="">Seleccionar rol...</option>
                              <option value="APRENDIZ">Aprendiz</option>
                              <option value="GESTORA">Gestora</option>
                              <option value="ADMIN">ADMIN</option>
                          </select>
                      </div>

                      <!-- FOTO -->
                      <div class="form-group">
                          <div class="panel font-weight-bold">SUBIR FOTO</div>
                          <div class="custom-file mb-2">
                              <input type="file" class="custom-file-input nuevaFoto" id="nuevaFoto" name="nuevaFoto" accept="image/jpeg, image/png">
                              <label class="custom-file-label" for="nuevaFoto" data-browse="Elegir">Seleccionar imagen</label>
                          </div>
                          <p class="help-block">Peso máximo de la foto 4MB (Formatos: JPG o PNG)</p>
                          <img src="documentos/anonimo/anonimo.png" class="img-thumbnail previsualizar" width="100px">
                      </div>

                      <div class="input-group mb-3" id="divFicha" style="display: none;">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                          </div>
                          <input type="text" class="form-control" list="listaFichas" id="inputFicha" placeholder="Escriba o seleccione una ficha...">
                          <datalist id="listaFichas">
                              <?php
                                $fichas = ControladorUsuarios::ctrListarFichas();
                                foreach ($fichas as $ficha) {
                                    echo '<option data-id="' . $ficha["id_ficha"] . '" data-programa="' . $ficha["programa_ficha"] . '" value="' . $ficha["codigo"] . '"></option>';
                                }
                                ?>
                          </datalist>
                          <input type="hidden" name="nuevaFicha" id="nuevaFicha">
                      </div>

                      <div class="input-group mb-3" id="divDescripcionFicha" style="display: none;">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                          </div>
                          <input type="text" class="form-control" id="descripcionFicha" placeholder="Programa de formación" readonly>
                      </div>

                      <!-- CONTACTO: DIRECCION -->
                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                          </div>
                          <input type="text" class="form-control" id="nuevaDireccion" name="nuevaDireccion" placeholder="Dirección de residencia (Obligatorio para Aprendiz)">
                      </div>

                      <!-- CONTACTO: TELEFONO -->
                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-phone"></i></span>
                          </div>
                          <input type="text" class="form-control" id="nuevoTelefono" name="nuevoTelefono" placeholder="Teléfono de contacto (Obligatorio para Aprendiz)">
                      </div>

                      <!-- CONTACTO: DEPARTAMENTO -->
                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-map"></i></span>
                          </div>
                          <select class="form-control" name="nuevoDepartamento" id="nuevoDepartamento">
                              <option value="">Seleccionar Departamento</option>
                              <?php
                              $departamentos = ModeloUsuarios::mdlObtenerDepartamentos();
                              foreach ($departamentos as $dep) {
                                  echo '<option value="' . $dep["codigo_dep"] . '">' . $dep["nombre"] . '</option>';
                              }
                              ?>
                          </select>
                      </div>

                      <!-- CONTACTO: CIUDAD -->
                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-city"></i></span>
                          </div>
                          <select class="form-control" name="nuevaCiudad" id="nuevaCiudad">
                              <option value="">Seleccionar Municipio/Ciudad</option>
                          </select>
                      </div>

              </div>
              <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                  <button type="submit" class="btn btn-primary">Guardar</button>
              </div>
              <?php
                $agregarUsuario = new ControladorUsuarios();
                $agregarUsuario->ctrAgregarUsuario();
                ?>
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
                  <form id="formEditarUsuario" action="" method="post" enctype="multipart/form-data">
                      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                      <input type="hidden" name="idUsuarioEditar" id="idUsuarioEditar">
                      <input type="hidden" name="fotoActualEditar" id="fotoActualEditar">

                      <!-- ELIMINAR FOTO -->
                      <div class="form-group" id="divEliminarFoto" style="display: none;">
                          <div class="custom-control custom-checkbox">
                              <input class="custom-control-input" type="checkbox" id="eliminarFotoUsuario" name="eliminarFotoUsuario" value="si">
                              <label for="eliminarFotoUsuario" class="custom-control-label">Eliminar foto del usuario (volver a anónima)</label>
                          </div>
                      </div>

                      <!-- FOTO -->
                      <div class="form-group">
                          <div class="panel font-weight-bold">CAMBIAR FOTO</div>
                          <div class="custom-file mb-2">
                              <input type="file" class="custom-file-input nuevaFoto" id="editarFoto" name="editarFoto" accept="image/jpeg, image/png">
                              <label class="custom-file-label" for="editarFoto" data-browse="Elegir">Seleccionar imagen</label>
                          </div>
                          <p class="help-block">Peso máximo de la foto 4MB (Formatos: JPG o PNG)</p>
                          <img src="documentos/anonimo/anonimo.png" class="img-thumbnail previsualizar previsualizarEditar" width="100px">
                      </div>

                      <div class="form-group">
                          <div class="row">
                              <div class="col-md-5">
                                  <select class="form-control" name="editarTipoDocumento" id="editarTipoDocumento" required>
                                      <option value="">Tipo...</option>
                                      <option value="TI">TI</option>
                                      <option value="CC">CC</option>
                                      <option value="CE">CE</option>
                                      <option value="PEP">PEP</option>
                                      <option value="PPT">PPT</option>
                                  </select>
                              </div>
                              <div class="input-group col-md-7">
                                  <div class="input-group-prepend">
                                      <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                  </div>
                                  <input type="text" class="form-control" name="editarDocumento" id="editarDocumento" placeholder="Numero identificación" readonly required>
                              </div>
                          </div>
                      </div>

                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-user"></i></span>
                          </div>
                          <input type="text" class="form-control" name="editarNombre" id="editarNombre" placeholder="Nombre" required>
                      </div>

                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-user"></i></span>
                          </div>
                          <input type="text" class="form-control" name="editarApellido" id="editarApellido" placeholder="Apellidos" required>
                      </div>

                      <label for="">Fecha de nacimiento</label>
                      <div class="input-group mb-3 date">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                          </div>
                          <input type="date" class="form-control" name="editarFechaNacimiento" id="editarFechaNacimiento" placeholder="Fecha de nacimiento" required>
                      </div>

                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                          </div>
                          <input type="email" class="form-control" name="editarCorreo" id="editarCorreo" placeholder="Correo" required>
                      </div>

                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                          </div>
                          <select class="form-control" name="editarRol" id="editarRol" required>
                              <option value="">Seleccionar rol...</option>
                              <option value="APRENDIZ">Aprendiz</option>
                              <option value="GESTORA">Gestora</option>
                              <option value="ADMIN">ADMIN</option>
                          </select>
                      </div>

                      <div class="input-group mb-3" id="divEditarFicha" style="display: none;">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                          </div>
                          <input type="text" class="form-control" list="listaFichas" id="inputEditarFicha" placeholder="Escriba o seleccione una ficha...">
                          <input type="hidden" name="editarFicha" id="editarFicha">
                      </div>

                      <div class="input-group mb-3" id="divDescripcionEditarFicha" style="display: none;">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                          </div>
                          <input type="text" class="form-control" id="descripcionEditarFicha" placeholder="Programa de formación" readonly>
                      </div>

                      <!-- CONTACTO: DIRECCION -->
                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                          </div>
                          <input type="text" class="form-control" id="editarDireccion" name="editarDireccion" placeholder="Dirección de residencia (Obligatorio para Aprendiz)">
                      </div>

                      <!-- CONTACTO: TELEFONO -->
                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-phone"></i></span>
                          </div>
                          <input type="text" class="form-control" id="editarTelefono" name="editarTelefono" placeholder="Teléfono de contacto (Obligatorio para Aprendiz)">
                      </div>

                      <!-- CONTACTO: DEPARTAMENTO -->
                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-map"></i></span>
                          </div>
                          <select class="form-control" name="editarDepartamento" id="editarDepartamento">
                              <option value="">Seleccionar Departamento</option>
                              <?php
                              $departamentos = ModeloUsuarios::mdlObtenerDepartamentos();
                              foreach ($departamentos as $dep) {
                                  echo '<option value="' . $dep["codigo_dep"] . '">' . $dep["nombre"] . '</option>';
                              }
                              ?>
                          </select>
                      </div>

                      <!-- CONTACTO: CIUDAD -->
                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-city"></i></span>
                          </div>
                          <select class="form-control" name="editarCiudad" id="editarCiudad">
                              <option value="">Seleccionar Municipio/Ciudad</option>
                          </select>
                      </div>

                      <div class="form-group">
                          <label for="">Para cambiar la contraseña escriba una nueva, de lo contrario déjelo en blanco</label>
                          <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                              </div>
                              <input type="password" class="form-control" name="editarPassword" placeholder="Nueva contraseña">
                              <input type="hidden" id="passwordActual" name="passwordActual">
                          </div>
                      </div>

              </div>
              <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                  <button type="submit" class="btn btn-primary">Guardar cambios</button>
              </div>
              <?php
                $editarUsuario = new ControladorUsuarios();
                $editarUsuario->ctrEditarUsuario();
                ?>
              </form>
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
                  <!-- FOTO DE PERFIL -->
                  <div class="form-group text-center mb-3">
                      <img id="consultarFoto" src="documentos/anonimo/anonimo.png" class="img-thumbnail rounded-circle" width="110px" height="110px" style="object-fit: cover;" alt="Foto Usuario">
                  </div>

                  <div class="form-group">
                      <div class="row">
                          <div class="col-md-5">
                              <input type="text" class="form-control" id="consultarTipoDocumento" readonly>
                          </div>
                          <div class="input-group col-md-7">
                              <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                              </div>
                              <input type="text" class="form-control" id="consultarDocumento" readonly>
                          </div>
                      </div>
                  </div>

                  <div class="input-group mb-3">
                      <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-user"></i></span>
                      </div>
                      <input type="text" class="form-control" id="consultarNombre" readonly>
                  </div>

                  <div class="input-group mb-3">
                      <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-user"></i></span>
                      </div>
                      <input type="text" class="form-control" id="consultarApellido" readonly>
                  </div>

                  <label for="">Fecha de nacimiento</label>
                  <div class="input-group mb-3 date">
                      <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                      </div>
                      <input type="date" class="form-control" id="consultarFechaNacimiento" readonly>
                  </div>

                  <div class="input-group mb-3">
                      <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                      </div>
                      <input type="email" class="form-control" id="consultarCorreo" readonly>
                  </div>

                  <div class="input-group mb-3">
                      <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                      </div>
                      <input type="text" class="form-control" id="consultarRol" readonly>
                  </div>

                  <div class="input-group mb-3" id="divConsultarFicha" style="display: none;">
                      <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                      </div>
                      <input type="text" class="form-control" id="inputConsultarFicha" readonly>
                  </div>

                  <div class="input-group mb-3" id="divDescripcionConsultarFicha" style="display: none;">
                      <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                      </div>
                      <input type="text" class="form-control" id="descripcionConsultarFicha" readonly>
                  </div>

                  <hr class="border-secondary">
                  <label class="mb-2"><i class="fas fa-address-book mr-1"></i> Información de Contacto</label>

                  <!-- CONTACTO: DIRECCIÓN -->
                  <div class="input-group mb-3">
                      <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                      </div>
                      <input type="text" class="form-control" id="consultarDireccion" placeholder="Dirección" readonly>
                  </div>

                  <!-- CONTACTO: TELÉFONO -->
                  <div class="input-group mb-3">
                      <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-phone"></i></span>
                      </div>
                      <input type="text" class="form-control" id="consultarTelefono" placeholder="Teléfono" readonly>
                  </div>

                  <!-- CONTACTO: DEPARTAMENTO Y CIUDAD -->
                  <div class="row">
                      <div class="col-md-6">
                          <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fas fa-map"></i></span>
                              </div>
                              <input type="text" class="form-control" id="consultarDepartamento" placeholder="Departamento" readonly>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fas fa-city"></i></span>
                              </div>
                              <input type="text" class="form-control" id="consultarCiudad" placeholder="Ciudad/Municipio" readonly>
                          </div>
                      </div>
                  </div>

                  <!-- DOCUMENTO DE IDENTIDAD MAESTRO -->
                  <div class="form-group mt-2" id="divConsultarDocIdentidad" style="display: none;">
                      <label class="d-block"><i class="fas fa-file-pdf mr-1"></i> Documento de Identidad Maestro</label>
                      <a href="#" id="btnVerDocIdentidadMaestro" target="_blank" class="btn btn-sm btn-info" style="display: none;">
                          <i class="fas fa-external-link-alt mr-1"></i> Ver PDF Documento de Identidad
                      </a>
                      <span id="sinDocIdentidadMaestro" class="badge badge-secondary p-2" style="display: none;">
                          Sin soporte de identidad cargado
                      </span>
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

  <!-- ********************************************************************************************************
IMPORTAR APRENDICES (CSV / EXCEL)   -->

  <div class="modal fade" id="modal-importarUsuarios">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title"><i class="fas fa-file-import mr-2"></i>Importar Aprendices desde CSV / Excel</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <form id="formImportarUsuarios" action="" method="post" enctype="multipart/form-data">
                  <div class="modal-body">
                      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                      <input type="hidden" name="importarAprendices" value="1">

                      <div class="alert alert-warning text-dark">
                          <h5><i class="icon fas fa-exclamation-triangle"></i> ¡Atención! Estructura obligatoria del archivo</h5>
                          <p class="mb-2">
                              El archivo debe estar en formato <strong>.CSV</strong> (separado por punto y coma <code>;</code> o coma <code>,</code>) o <strong>Excel (.XLSX)</strong> y respetar exactamente el siguiente orden de columnas:
                          </p>
                          <ol class="mb-2 pl-3 small">
                              <li><strong>tipo_documento</strong> <em>(Obligatorio: <code>TI</code>, <code>CC</code>, <code>CE</code>, <code>PEP</code> o <code>PPT</code>)</em></li>
                              <li><strong>documento_id</strong> <em>(Obligatorio: Número de identificación entre 5 y 20 dígitos, será su contraseña inicial)</em></li>
                              <li><strong>nombres</strong> <em>(Obligatorio: Solo letras y espacios)</em></li>
                              <li><strong>apellidos</strong> <em>(Obligatorio: Solo letras y espacios)</em></li>
                              <li><strong>fecha_nacimiento</strong> <em>(Obligatorio: Formato <code>AAAA-MM-DD</code> o <code>DD/MM/AAAA</code>)</em></li>
                              <li><strong>correo</strong> <em>(Obligatorio: Correo electrónico válido y único)</em></li>
                              <li><strong>codigo_ficha</strong> <em>(Obligatorio: Código exacto de una ficha activa en GAiA, ej. <code>3063989</code>)</em></li>
                              <li><strong>direccion</strong> <em>(Opcional: Dirección de residencia)</em></li>
                              <li><strong>telefono</strong> <em>(Opcional: Teléfono de contacto)</em></li>
                              <li><strong>codigo_dep</strong> <em>(Opcional: Código DANE de departamento de 2 dígitos; por defecto <code>76</code> - Valle)</em></li>
                              <li><strong>codigo_ciu</strong> <em>(Opcional: Código DANE de municipio de 5 dígitos; por defecto <code>76834</code> - Tuluá)</em></li>
                          </ol>
                          <p class="mb-2 small">
                              * Los registros con documentos/correos ya existentes, fichas inactivas/inexistentes o formatos inválidos serán <strong>ignorados automáticamente</strong> y reportados al finalizar.
                          </p>
                          <button type="button" id="btnDescargarPlantillaUsuarios" class="btn btn-sm btn-dark">
                              <i class="fas fa-download mr-1"></i> Descargar Plantilla CSV de Ejemplo
                          </button>
                      </div>

                      <div class="form-group mt-3">
                          <label for="archivoImportarUsuarios" class="font-weight-bold">Seleccionar archivo (.CSV o .XLSX - Máx. 5 MB)</label>
                          <div class="custom-file">
                              <input type="file" class="custom-file-input" id="archivoImportarUsuarios" name="archivoImportarUsuarios" accept=".csv, .xlsx, text/csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
                              <label class="custom-file-label" for="archivoImportarUsuarios" data-browse="Elegir">Seleccionar archivo CSV o Excel (.xlsx)</label>
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                      <button type="submit" class="btn btn-success"><i class="fas fa-upload mr-1"></i> Procesar Importación</button>
                  </div>
                  <?php
                  $importarAprendices = new ControladorUsuarios();
                  $importarAprendices->ctrImportarAprendices();
                  ?>
              </form>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
  <!-- ********************************************************************************************************
******************************************************************************************************** -->