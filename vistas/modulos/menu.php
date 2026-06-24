   <aside class="main-sidebar sidebar-dark-primary elevation-4">
     <!-- Brand Logo -->
     <a href="index3.html" class="brand-link">
       <!-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
       <span class="brand-text font-weight-light">GAiA</span>
     </a>

     <!-- Sidebar -->
     <div class="sidebar">
       <!-- Sidebar user panel (optional) -->
       <div class="user-panel mt-3 pb-3 mb-3 d-flex">
         <div class="image">
           <?php
            if (isset($_SESSION["foto"])) {
              echo '<img src="' . $_SESSION["foto"] . '" class="user-image rounded-circle shadow" alt="User Image">';
            }
            ?>
         </div>
         <div class="info">
           <a href="#" class="d-block" data-toggle="modal" data-target="#modal-miPerfil">
             <?php echo $_SESSION["nombres"] . " " . $_SESSION["apellidos"]; ?>
           </a>
         </div>
       </div>


       <!-- Sidebar Menu -->
       <nav class="mt-2">
         <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
           <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <?php if ($_SESSION["rol"] == "GESTORA" || $_SESSION["rol"] == "ADMIN"): ?>
           <!-- CONFIGURACION -->
           <li class="nav-item menu-open ">
             <a href="#" class="nav-link active">
               <i class="nav-icon fas fa-cog"></i>
               <p>
                 Configuracion
                 <i class="right fas fa-angle-left"></i>
               </p>
             </a>

             <ul class="nav nav-treeview">
               <li class="nav-item">
                 <a href="./sedes" class="nav-link">
                   <i class="far fa-circle nav-icon"></i>
                   <p>Sedes</p>
                  </a>
                </li>
                
                <li class="nav-item">
                  <a href="fichas" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Fichas</p>
                  </a>
                </li>
                
                <li class="nav-item">
                  <a href="apoyos" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Apoyos</p>
                  </a>
                </li>

               <li class="nav-item">
                 <a href="convocatorias" class="nav-link">
                   <i class="far fa-circle nav-icon"></i>
                   <p>Convocatorias</p>
                 </a>
               </li>

               <li class="nav-item">
                 <a href="Usuarios" class="nav-link">
                   <i class="far fa-circle nav-icon"></i>
                   <p>Usuarios</p>
                 </a>
               </li>
             </ul>
           </li>
            
           <?php endif; ?>

           <li class="nav-item">
             <a href="identificacion" class="nav-link">
               <i class="nav-icon fas fa-life-ring"></i>
               <p>
                 Identificación de apoyos
               </p>
             </a>
           </li>

          <?php if ($_SESSION["rol"] != "GESTORA"): ?>

           <li class="nav-item">
             <a href="inscripciones" class="nav-link">
               <i class="nav-icon fas fa-pencil-alt"></i>
               <p>
                 Inscripciones
               </p>
             </a>
           </li>

           <?php endif; ?>



           <?php if ($_SESSION["rol"] == "GESTORA" || $_SESSION["rol"] == "ADMIN"): ?>
            <li class="nav-item menu-open">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-handshake"></i>
                <p>
                  Gestión
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
 
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="verificacion" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Verificación</p>
                  </a>
                </li>
 
                <li class="nav-item">
                  <a href="puntuacion" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Puntuación</p>
                  </a>
                </li>
 
              </ul>
            </li>
            
            <li class="nav-item">
              <a href="financiera" class="nav-link">
                <i class="fas fa-money-bill-wave"></i>
                <p>
                  Financiera
                </p>
              </a>
            </li>
            
            <?php endif; ?>

           <li class="nav-item">
             <a href="reportes" class="nav-link">
               <i class="fas fa-chart-bar"></i>
               <p>
                 Reportes
               </p>
             </a>
           </li>

         </ul>
       </nav>
       <!-- /.sidebar-menu -->
     </div>
     <!-- /.sidebar -->
   </aside>

   <!-- ********************************************************************************************************
  MODAL MI PERFIL   -->
   <div class="modal fade" id="modal-miPerfil">
     <div class="modal-dialog">
       <div class="modal-content">
         <div class="modal-header">
           <h4 class="modal-title">Mi Perfil</h4>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>
         <div class="modal-body">           <form action="" method="post" enctype="multipart/form-data">
             <?php
             $contactoPerfil = ModeloUsuarios::mdlObtenerContactoUsuario($_SESSION["id"]);
             $dirP = $contactoPerfil ? $contactoPerfil["direccion"] : "";
             $telP = $contactoPerfil ? $contactoPerfil["telefono"] : "";
             $depP = $contactoPerfil ? $contactoPerfil["codigo_dep"] : "";
             $ciuP = $contactoPerfil ? $contactoPerfil["codigo_ciu"] : "";
             ?>

             <input type="hidden" name="idPerfil" value="<?php echo $_SESSION["id"]; ?>">
             <input type="hidden" name="fotoActual" value="<?php echo $_SESSION["foto"]; ?>">
             <input type="hidden" name="documentoPerfil" value="<?php echo $_SESSION["documento"]; ?>">


             <div class="input-group mb-3">
               <div class="input-group-prepend">
                 <span class="input-group-text"><i class="fas fa-user"></i></span>
               </div>
               <input type="text" class="form-control" name="editarNombrePerfil" value="<?php echo $_SESSION["nombres"]; ?>" required>
             </div>

             <div class="input-group mb-3">
               <div class="input-group-prepend">
                 <span class="input-group-text"><i class="fas fa-user"></i></span>
               </div>
               <input type="text" class="form-control" name="editarApellidoPerfil" value="<?php echo $_SESSION["apellidos"]; ?>" required>
             </div>

             <div class="form-group">
               <label for="">Para cambiar la contraseña escriba una nueva, de lo contrario déjelo en blanco</label>
               <div class="input-group mb-3">
                 <div class="input-group-prepend">
                   <span class="input-group-text"><i class="fas fa-lock"></i></span>
                 </div>
                 <input type="password" class="form-control" name="editarPasswordPerfil" placeholder="Nueva contraseña">
               </div>
             </div>

             <!-- CONTACTO: DIRECCION -->
             <div class="input-group mb-3">
                 <div class="input-group-prepend">
                     <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                 </div>
                 <input type="text" class="form-control" id="editarDireccionPerfil" name="editarDireccionPerfil" placeholder="Dirección de residencia" value="<?php echo $dirP; ?>">
             </div>

             <!-- CONTACTO: TELEFONO -->
             <div class="input-group mb-3">
                 <div class="input-group-prepend">
                     <span class="input-group-text"><i class="fas fa-phone"></i></span>
                 </div>
                 <input type="text" class="form-control" id="editarTelefonoPerfil" name="editarTelefonoPerfil" placeholder="Teléfono de contacto" value="<?php echo $telP; ?>">
             </div>

             <!-- CONTACTO: DEPARTAMENTO -->
             <div class="input-group mb-3">
                 <div class="input-group-prepend">
                     <span class="input-group-text"><i class="fas fa-map"></i></span>
                 </div>
                 <select class="form-control" name="editarDepartamentoPerfil" id="editarDepartamentoPerfil">
                     <option value="">Seleccionar Departamento</option>
                     <?php
                     $departamentos = ModeloUsuarios::mdlObtenerDepartamentos();
                     foreach ($departamentos as $dep) {
                         $selected = ($dep["codigo_dep"] == $depP) ? "selected" : "";
                         echo '<option value="' . $dep["codigo_dep"] . '" '.$selected.'>' . $dep["nombre"] . '</option>';
                     }
                     ?>
                 </select>
             </div>

             <!-- CONTACTO: CIUDAD -->
             <div class="input-group mb-3">
                 <div class="input-group-prepend">
                     <span class="input-group-text"><i class="fas fa-city"></i></span>
                 </div>
                 <select class="form-control" name="editarCiudadPerfil" id="editarCiudadPerfil">
                     <option value="">Seleccionar Municipio/Ciudad</option>
                     <?php
                     if($depP != ""){
                         $ciudades = ModeloUsuarios::mdlObtenerCiudades($depP);
                         foreach ($ciudades as $ciu) {
                             $selected = ($ciu["codigo_ciu"] == $ciuP) ? "selected" : "";
                             echo '<option value="' . $ciu["codigo_ciu"] . '" '.$selected.'>' . $ciu["nombre"] . '</option>';
                         }
                     }
                     ?>
                 </select>
             </div>
               <div class="form-group">
                 <div class="panel">CAMBIAR FOTO PERFIL</div>
                 <div class="custom-file mb-2">
                   <input type="file" class="custom-file-input nuevaFoto" id="editarFotoPerfil" name="editarFotoPerfil" accept="image/jpeg, image/png">
                   <label class="custom-file-label" for="editarFotoPerfil" data-browse="Elegir">Seleccionar imagen</label>
                 </div>
                 <p class="help-block">Peso máximo de la foto 4MB (Formatos: JPG o PNG)</p>
                 <?php
                if (isset($_SESSION["foto"]) && $_SESSION["foto"] != "") {
                  echo '<img src="' . $_SESSION["foto"] . '" class="img-thumbnail previsualizar" width="100px">';
                } else {
                  echo '<img src="documentos/anonimo/anonimo.png" class="img-thumbnail previsualizar" width="100px">';
                }
                ?>
             </div>

         </div>
         <div class="modal-footer justify-content-between">
           <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
           <button type="submit" class="btn btn-primary">Guardar cambios</button>
         </div>
         <?php
          $editarPerfil = new ControladorUsuarios();
          $editarPerfil->ctrEditarPerfil();
          ?>
         </form>
       </div>
       <!-- /.modal-content -->
     </div>
     <!-- /.modal-dialog -->
   </div>
   <!-- /.modal -->