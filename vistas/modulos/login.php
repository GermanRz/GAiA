<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>GAiA</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Iniciar sesión</p>

      <form method="post" autocomplete="off">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Documento" name="ingDocumento" inputmode="numeric" pattern="[0-9]+" required autocomplete="username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-id-card"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Contraseña" name="ingPassword" required autocomplete="current-password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
 
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
          </div>
          <!-- /.col -->
        </div>
        <?php

            $login = new ControladorUsuarios();
            $login->ctrIngresarUsuario();

        
        
        ?>


      </form>

      <!-- /.social-auth-links -->

      <p class="mb-1">
        <a href="#">Olvidé mi contraseña</a>
      </p>
      <p class="mb-0">
        <a href="#" class="text-center" data-toggle="modal" data-target="#modal-registroAprendiz">Registrarse</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- MODAL REGISTRO APRENDIZ -->
<div class="modal fade" id="modal-registroAprendiz">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-user-graduate mr-2"></i>Registrarse como Aprendiz</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formRegistroAprendiz" action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="registroAprendizPublico" value="1">

                    <!-- FILA 1: Tipo Doc, Número Identificación y Fecha de Nacimiento -->
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="small text-muted mb-1">Tipo Documento</label>
                            <select class="form-control" name="nuevoTipoDocumento" required>
                                <option value="">Tipo...</option>
                                <option value="TI">TI</option>
                                <option value="CC">CC</option>
                                <option value="CE">CE</option>
                                <option value="PEP">PEP</option>
                                <option value="PPT">PPT</option>
                            </select>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="small text-muted mb-1">Número de Identificación</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                </div>
                                <input type="text" class="form-control" name="nuevoDocumento" id="nuevoDocumento" placeholder="Número identificación" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small text-muted mb-1">Fecha de Nacimiento</label>
                            <div class="input-group date">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                </div>
                                <input type="date" class="form-control" name="nuevoFechaNacimiento" placeholder="Fecha de nacimiento" required>
                            </div>
                        </div>
                    </div>

                    <!-- FILA 2: Nombres y Apellidos -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted mb-1">Nombres</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" class="form-control" name="nuevoNombre" placeholder="Nombre" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted mb-1">Apellidos</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" class="form-control" name="nuevoApellido" placeholder="Apellidos" required>
                            </div>
                        </div>
                    </div>

                    <!-- FILA 3: Correo y Teléfono -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted mb-1">Correo Electrónico</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" class="form-control" name="nuevoCorreo" placeholder="Correo" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted mb-1">Teléfono de Contacto</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                <input type="text" class="form-control" id="nuevoTelefonoLogin" name="nuevoTelefono" placeholder="Teléfono de contacto" required>
                            </div>
                        </div>
                    </div>

                    <!-- FILA 4: Ficha y Programa de Formación -->
                    <div class="row">
                        <div class="col-md-5 mb-3" id="divFicha">
                            <label class="small text-muted mb-1">Código de Ficha</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                </div>
                                <input type="text" class="form-control" list="listaFichas" id="inputFicha" placeholder="Escriba o seleccione una ficha..." required>
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
                        </div>
                        <div class="col-md-7 mb-3" id="divDescripcionFicha" style="display: none;">
                            <label class="small text-muted mb-1">Programa de Formación</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                </div>
                                <input type="text" class="form-control" id="descripcionFicha" placeholder="Programa de formación" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- FILA 5: Dirección, Departamento y Municipio -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="small text-muted mb-1">Dirección de Residencia</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                </div>
                                <input type="text" class="form-control" id="nuevaDireccionLogin" name="nuevaDireccion" placeholder="Dirección de residencia" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small text-muted mb-1">Departamento</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-map"></i></span>
                                </div>
                                <select class="form-control" name="nuevoDepartamento" id="nuevoDepartamentoLogin" required>
                                    <option value="">Departamento...</option>
                                    <?php
                                    $departamentos = ModeloUsuarios::mdlObtenerDepartamentos();
                                    foreach ($departamentos as $dep) {
                                        echo '<option value="' . $dep["codigo_dep"] . '">' . $dep["nombre"] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small text-muted mb-1">Municipio / Ciudad</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-city"></i></span>
                                </div>
                                <select class="form-control" name="nuevaCiudad" id="nuevaCiudadLogin" required>
                                    <option value="">Municipio/Ciudad...</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- FILA 6: Foto de Perfil -->
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <label class="small text-muted mb-1 font-weight-bold">Fotografía de Perfil (Opcional - Máx. 4MB JPG/PNG)</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input nuevaFoto" id="nuevaFotoLogin" name="nuevaFoto" accept="image/jpeg, image/png">
                                <label class="custom-file-label" for="nuevaFotoLogin" data-browse="Elegir">Seleccionar imagen</label>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mt-2 mt-md-0">
                            <img src="documentos/anonimo/anonimo.png" class="img-thumbnail previsualizar" width="75px">
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Registrarse</button>
                </div>
                <?php
                $registroUsuario = new ControladorUsuarios();
                $registroUsuario->ctrRegistroAprendiz();
                ?>
            </form>
        </div>
    </div>
</div>
<!-- /.modal -->


