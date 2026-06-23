<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>GAiA</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Iniciar sesión</p>

      <form method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Documento" name="ingDocumento">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-id-card"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Contraseña" name="ingPassword">
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Registrarse como Aprendiz</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data">
                    
                    <input type="hidden" name="nuevoRol" value="Aprendiz">

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-5">
                                <select class="form-control" name="nuevoTipoDocumento" required>
                                    <option value="">Tipo...</option>
                                    <option value="TI">TI</option>
                                    <option value="CC">CC</option>
                                    <option value="CE">CE</option>
                                    <option value="PPT">PPT</option>
                                </select>
                            </div>
                            <div class="input-group col-md-7">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                </div>
                                <input type="text" class="form-control" name="nuevoDocumento" id="nuevoDocumento" placeholder="Número identificación" required>
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

                    <!-- FOTO -->
                    <div class="form-group">
                        <div class="panel">SUBIR FOTO</div>
                        <div class="custom-file mb-2">
                            <input type="file" class="custom-file-input nuevaFoto" id="nuevaFotoLogin" name="nuevaFoto" accept="image/jpeg, image/png">
                            <label class="custom-file-label" for="nuevaFotoLogin" data-browse="Elegir">Seleccionar imagen</label>
                        </div>
                        <p class="help-block">Peso máximo de la foto 4MB (Formatos: JPG o PNG)</p>
                    </div>

                    <div class="input-group mb-3" id="divFicha">
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
                        <input type="text" class="form-control" id="nuevaDireccionLogin" name="nuevaDireccion" placeholder="Dirección de residencia (Obligatorio para Aprendiz)">
                    </div>

                    <!-- CONTACTO: TELEFONO -->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        </div>
                        <input type="text" class="form-control" id="nuevoTelefonoLogin" name="nuevoTelefono" placeholder="Teléfono de contacto (Obligatorio para Aprendiz)">
                    </div>

                    <!-- CONTACTO: DEPARTAMENTO -->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-map"></i></span>
                        </div>
                        <select class="form-control" name="nuevoDepartamento" id="nuevoDepartamentoLogin">
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
                        <select class="form-control" name="nuevaCiudad" id="nuevaCiudadLogin">
                            <option value="">Seleccionar Municipio/Ciudad</option>
                        </select>
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


