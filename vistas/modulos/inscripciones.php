  <!-- Content Wrapper. Contains page content -->
  <!-- <div class="content-wrapper"> -->
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Inscripciones</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
              <li class="breadcrumb-item active">Inscripciones</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Botones superiores -->
      <div class="row mb-4">
        <div class="col-md-6 mb-2">
          <button class="btn btn-primary btn-block" style="border-radius: 5px; font-weight: 500;" data-toggle="modal" data-target="#modalDatosPersonales">Inscripción a apoyos</button>
        </div>
        <div class="col-md-6 mb-2">
          <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalPreguntas" style="border-radius: 5px; font-weight: 500;">Preguntas Frecuentes</button>
        </div>
      </div>

      <!-- Tarjeta principal -->
      <div class="card">
        <div class="card-body">

          <!-- VISTA USUARIO -->
          <div id="vistaUsuario">

          <?php 
          $categorias = [
            "SOSTENIMIENTO REGULAR",
            "ALIMENTACION TEMPORAL",
            "MEDIOS TECNOLOGICOS Y DATOS",
            "TRANSPORTE"
          ];

          foreach ($categorias as $categoria) : ?>
          <div class="mb-4">
            <h5 class="mb-2" style="font-weight: 600; font-size: 1.1rem;">
              <?php echo $categoria; ?> <span class="text-muted" style="font-size: 0.85rem; margin-left: 5px;">| 20/100</span>
            </h5>
            <div class="table-responsive">
              <table class="table table-sm table-bordered text-center m-0" style="min-width: 900px;">
                <thead>
                  <tr style="background-color: #0b8043; color: white;">
                    <th style="font-size: 0.75rem; vertical-align: middle; width: 10%;">AUTORIZACION<br>DE MENOR</th>
                    <th style="font-size: 0.75rem; vertical-align: middle; width: 10%;">DISCAPACIDAD</th>
                    <th style="font-size: 0.75rem; vertical-align: middle; width: 10%;">COMUNIDAD<br>ETNICA</th>
                    <th style="font-size: 0.75rem; vertical-align: middle; width: 10%;">CAMPESINO</th>
                    <th style="font-size: 0.75rem; vertical-align: middle; width: 10%;">CABEZA<br>DE FAMILIA</th>
                    <th style="font-size: 0.75rem; vertical-align: middle; width: 10%;">VICTIMA<br>DE CONFLICTO</th>
                    <th style="font-size: 0.75rem; vertical-align: middle; width: 10%;">VIOLENCIA<br>DE GENERO</th>
                    <th style="font-size: 0.75rem; vertical-align: middle; width: 10%;">EMBARAZADA<br>/LACTANCIA</th>
                    <th style="font-size: 0.75rem; vertical-align: middle; width: 10%;">SISBEN<br>A/B</th>
                    <th style="font-size: 0.75rem; vertical-align: middle; width: 10%;">DESPLAZADO<br>NATURAL</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="bg-danger py-1" style="font-size: 0.8rem;">Rechazado</td>
                    <td class="bg-danger py-1" style="font-size: 0.8rem;">Rechazado</td>
                    <td class="bg-warning py-1 text-dark" style="font-size: 0.8rem; font-weight: 500;">Pendiente</td>
                    <td class="bg-danger py-1" style="font-size: 0.8rem;">Rechazado</td>
                    <td class="bg-danger py-1" style="font-size: 0.8rem;">Rechazado</td>
                    <td class="bg-secondary py-1" style="font-size: 0.8rem;">No Presentado</td>
                    <td class="bg-danger py-1" style="font-size: 0.8rem;">Rechazado</td>
                    <td class="bg-success py-1" style="font-size: 0.8rem;">Aprobado</td>
                    <td class="bg-danger py-1" style="font-size: 0.8rem;">Rechazado</td>
                    <td class="bg-success py-1" style="font-size: 0.8rem;">Aprobado</td>
                  </tr>
                  <tr>
                    <td class="py-2"><i class="fas fa-upload fa-lg" style="cursor: pointer;"></i></td>
                    <td class="py-2"><i class="fas fa-upload fa-lg" style="cursor: pointer;"></i></td>
                    <td class="py-2"><i class="fas fa-ban fa-lg text-muted"></i></td>
                    <td class="py-2"><i class="fas fa-upload fa-lg" style="cursor: pointer;"></i></td>
                    <td class="py-2"><i class="fas fa-upload fa-lg" style="cursor: pointer;"></i></td>
                    <td class="py-2"><i class="fas fa-ban fa-lg text-muted"></i></td>
                    <td class="py-2"><i class="fas fa-upload fa-lg" style="cursor: pointer;"></i></td>
                    <td class="py-2"><i class="fas fa-ban fa-lg text-muted"></i></td>
                    <td class="py-2"><i class="fas fa-upload fa-lg" style="cursor: pointer;"></i></td>
                    <td class="py-2"><i class="fas fa-ban fa-lg text-muted"></i></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <?php endforeach; ?>
          </div>
          <!-- /VISTA USUARIO -->

          <!-- VISTA ADMINISTRADOR -->
          <div id="vistaAdmin" style="display: none;">
            <div class="row align-items-center mb-4">
              <div class="col-md-8 d-flex flex-wrap" style="gap: 10px;">
                <button class="btn d-flex align-items-center" style="background-color: #0b8043; color: white; border-radius: 8px; padding: 8px 15px;">
                  <i class="fas fa-wallet fa-lg mr-2"></i>
                  <div class="text-left text-sm" style="line-height: 1.1;">Apoyo de<br>sostenimiento</div>
                </button>
                <button class="btn d-flex align-items-center" style="background-color: #0b8043; color: white; border-radius: 8px; padding: 8px 15px;">
                  <i class="fas fa-phone-alt fa-lg mr-2"></i>
                  <div class="text-left text-sm" style="line-height: 1.1;">apoyo<br>de datos</div>
                </button>
                <button class="btn d-flex align-items-center" style="background-color: #0b8043; color: white; border-radius: 8px; padding: 8px 15px;">
                  <i class="fas fa-utensils fa-lg mr-2"></i>
                  <div class="text-left text-sm" style="line-height: 1.1;">apoyo de<br>alimentación</div>
                </button>
                <button class="btn d-flex align-items-center" style="background-color: #0b8043; color: white; border-radius: 8px; padding: 8px 15px;">
                  <i class="fas fa-car fa-lg mr-2"></i>
                  <div class="text-left text-sm" style="line-height: 1.1;">apoyo de<br>transporte</div>
                </button>
              </div>
              <div class="col-md-4">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" style="border: 1px solid #28a745; border-right: none; border-radius: 8px 0 0 8px; background-color: transparent;"><i class="far fa-user"></i></span>
                  </div>
                  <input type="text" class="form-control" placeholder="Buscar aprendiz" style="border: 1px solid #28a745; border-left: none; border-radius: 0 8px 8px 0; background-color: transparent;">
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-bordered text-center mb-4 text-nowrap">
                <thead>
                  <tr style="background-color: #0b8043; color: white;">
                    <th style="font-weight: 500;">Identificacion</th>
                    <th style="font-weight: 500;">Nombre</th>
                    <th style="font-weight: 500;">Correo</th>
                    <th style="font-weight: 500;">Ficha</th>
                    <th style="font-weight: 500; width: 15%;">Estado</th>
                    <th style="font-weight: 500;">Fecha De Inscripcion</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $estadosAdmin = ['Inscrito', 'En Proceso', 'Inscrito', 'En Proceso', 'Inscrito', 'Inscrito', 'En Proceso', 'En Proceso', 'Inscrito'];
                  foreach ($estadosAdmin as $estado) : ?>
                  <tr>
                    <td class="align-middle">1</td>
                    <td class="align-middle">Ken</td>
                    <td class="align-middle">Ken@Gmail.Com</td>
                    <td class="align-middle">3063989</td>
                    <td class="p-0 align-middle">
                      <?php if($estado == 'Inscrito'): ?>
                        <div class="w-100 h-100 d-flex justify-content-center align-items-center py-2 bg-success text-white" style="min-height: 40px;">
                          <i class="fas fa-check mr-2"></i> Inscrito
                        </div>
                      <?php else: ?>
                        <div class="w-100 h-100 d-flex justify-content-center align-items-center py-2 bg-warning text-dark" style="min-height: 40px;">
                          <i class="fas fa-check mr-2"></i> En Proceso
                        </div>
                      <?php endif; ?>
                    </td>
                    <td class="align-middle">06/11/2025</td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

            <div class="d-flex justify-content-end">
              <ul class="pagination mt-2">
                <li class="page-item"><a class="page-link" href="#">Anterior</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
              </ul>
            </div>
          </div>
          <!-- /VISTA ADMINISTRADOR -->

        </div>
        <div class="card-footer position-relative d-flex justify-content-center align-items-center">
          <button id="btnSubirDocumento" class="btn btn-primary px-4" style="border-radius: 5px;" data-toggle="modal" data-target="#modalInfoBancaria">
            <i class="fas fa-file-upload mr-2"></i> Subir documento de certificación bancaria
          </button>
          <div class="position-absolute" style="right: 20px;">
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" id="switchVistaAdmin">
              <label class="custom-control-label" for="switchVistaAdmin" style="cursor: pointer;" title="Cambiar a vista de administrador"></label>
            </div>
          </div>
        </div>
      </div>

      <script>
        document.addEventListener('DOMContentLoaded', function() {
          var switchBtn = document.getElementById('switchVistaAdmin');
          var vistaUsuario = document.getElementById('vistaUsuario');
          var vistaAdmin = document.getElementById('vistaAdmin');
          var btnDoc = document.getElementById('btnSubirDocumento');

          if(switchBtn) {
            switchBtn.addEventListener('change', function() {
              if(this.checked) {
                vistaUsuario.style.display = 'none';
                vistaAdmin.style.display = 'block';
                if(btnDoc) btnDoc.style.display = 'none';
              } else {
                vistaUsuario.style.display = 'block';
                vistaAdmin.style.display = 'none';
                if(btnDoc) btnDoc.style.display = 'block';
              }
            });
          }

          var btnAbrirSubirArchivo = document.getElementById('btnAbrirSubirArchivo');
          if(btnAbrirSubirArchivo) {
            btnAbrirSubirArchivo.addEventListener('click', function() {
              $('#modalInfoBancaria').modal('hide');
              
              // Se escucha cuando se oculte esta modal para lanzar la siguiente, o se lanza de inmediato
              setTimeout(function(){
                $('#modalSubirArchivo').modal('show');
              }, 400); // Dar un breve lapso para la animación en bootstrap
            });
          }
        });
      </script>

      <!-- Modal Info Bancaria -->
      <div class="modal fade" id="modalInfoBancaria" tabindex="-1" role="dialog" aria-labelledby="modalInfoBancariaTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header p-3" style="background-color: #007bff; color: white; border-bottom: none;">
              <div class="d-flex align-items-center w-100">
                <i class="fas fa-address-card fa-lg ml-2 mr-3" style="color: white !important;"></i>
                <h5 class="modal-title font-weight-bold mb-0 text-uppercase mx-auto" id="modalInfoBancariaTitle" style="font-size: 1.1rem; letter-spacing: 0.5px; color: white !important;">
                  ADICCIONAR INFORMACION BANCARIA
                </h5>
                <button type="button" class="close text-white ml-0" style="opacity: 1; text-shadow: none;" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" style="color: white !important;">&times;</span>
                </button>
              </div>
            </div>
            <div class="modal-body p-4">
              <div style="border: 1px dashed #6c757d; padding: 25px 20px; border-radius: 5px;">
                <div class="form-group mb-4">
                  <label for="numeroCuenta" style="font-weight: normal; margin-bottom: 5px;">numero de cuenta</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-transparent" style="border-right: none;"><i class="far fa-dot-circle"></i></span>
                    </div>
                    <input type="text" class="form-control bg-transparent" id="numeroCuenta" style="border-left: none; box-shadow: none;">
                  </div>
                </div>
                <div class="form-group mb-4">
                  <label for="nombreBanco" style="font-weight: normal; margin-bottom: 5px;">nombre del banco</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-transparent" style="border-right: none;"><i class="fas fa-file-invoice"></i></span>
                    </div>
                    <input type="text" class="form-control bg-transparent" id="nombreBanco" style="border-left: none; box-shadow: none;">
                  </div>
                </div>
                <button type="button" class="btn btn-primary shadow-sm" id="btnAbrirSubirArchivo" style="background-color: #007bff; border-color: #007bff; border-radius: 20px; padding: 6px 20px;">
                  <i class="fas fa-upload mr-1"></i> Subir archivo
                </button>
              </div>
            </div>
            <div class="modal-footer border-0 pt-0 pb-4 pr-4">
              <button type="button" class="btn btn-primary" data-dismiss="modal" style="background-color: #007bff; border-color: #007bff; border-radius: 10px; padding: 8px 25px;">Aceptar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Subir Archivo -->
      <div class="modal fade" id="modalSubirArchivo" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content text-center" style="border-radius: 10px; min-height: 350px;">
            <button type="button" class="close position-absolute" style="right: 15px; top: 15px; z-index: 10;" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
            </button>
            <div class="modal-body d-flex flex-column justify-content-center align-items-center" style="padding: 40px 20px;">
              <div class="mb-4">
                <i class="fas fa-upload" style="font-size: 4rem; display: block; margin-bottom: 5px;"></i>
              </div>
              <p class="mb-5" style="font-size: 1.1rem;">Arrastra aqui el archivo o buscar en el dispositivo</p>
              <div class="d-flex justify-content-center flex-wrap" style="gap: 15px;">
                <button type="button" class="btn btn-primary px-4 py-2" style="background-color: #007bff; border-color: #007bff; border-radius: 10px;">Buscar en el dispositivo</button>
                <button type="button" class="btn btn-primary px-4 py-2" style="background-color: #007bff; border-color: #007bff; border-radius: 10px;">Enviar documento</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Preguntas Frecuentes -->
      <?php
      $preguntas = [
        [
          "titulo" => "¿Qué es el apoyo de sostenimiento del SENA?",
          "respuesta" => "Es un beneficio económico otorgado a aprendices activos para apoyar gastos de transporte, materiales y permanencia durante la formación."
        ],
        [
          "titulo" => "¿Quiénes pueden inscribirse al apoyo de sostenimiento?",
          "respuesta" => "Aprendices matriculados en programas de formación titulada que cumplan con requisitos socioeconómicos definidos por el SENA."
        ],
        [
          "titulo" => "¿Cómo me inscribo para solicitar el apoyo?",
          "respuesta" => "Primero deberás estar registrado en el aplicativo luego navegar hasta el apartado de inscripciones y seleccionar inscripción a apoyos"
        ],
        [
          "titulo" => "¿Qué documentos necesito para solicitar el apoyo?",
          "respuesta" => "Documento de identidad del aprendiz y del tutor si es el caso de que el aprendiz sea menor de edad, Documento EPS, recibo de energía de la vivienda (Todo los archivos en PDF)."
        ],
        [
          "titulo" => "¿Cuándo abren las inscripciones para el apoyo?",
          "respuesta" => "Las fechas varían según centro de formación y convocatoria; el calendario se publica en canales oficiales del SENA."
        ],
        [
          "titulo" => "¿Puedo aplicar al apoyo si estoy en etapa productiva?",
          "respuesta" => "Sí, siempre que cumplas requisitos y la convocatoria esté habilitada para aprendices en etapa productiva."
        ],
        [
          "titulo" => "¿Puedo perder el apoyo de sostenimiento una vez otorgado?",
          "respuesta" => "Sí. El beneficio puede suspenderse por bajo rendimiento, faltas disciplinarias, cancelación del programa o abandono."
        ],
        [
          "titulo" => "¿Cómo saber si fui seleccionado para el apoyo?",
          "respuesta" => "Los resultados se publican a través de notificaciones de el aplicativo o por el grupo de whatsapp de tu ficha compartidos con los voceros."
        ],
        [
          "titulo" => "¿Qué pasa si pierdo la condición de estudiante activo?",
          "respuesta" => "El apoyo se suspende inmediatamente y no se seguirán generando pagos."
        ],
        [
          "titulo" => "¿Hay límite de cupos para el apoyo?",
          "respuesta" => "Sí, depende del presupuesto asignado por cada centro de formación y la demanda de aprendices inscritos."
        ],
      ];
      ?>

      <div class="modal fade" id="modalPreguntas" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
          <div class="modal-content" style="border-radius: 10px; overflow: hidden;">
            <div class="modal-header d-flex justify-content-between align-items-center" style="background-color: #007bff; color: white;">
              <div>
                <i class="fas fa-user-graduate" style="font-size: 1.5rem;"></i>
              </div>
              <h5 class="modal-title font-weight-bold" style="flex-grow: 1; text-align: center;">PREGUNTAS FRECUENTES</h5>
              <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 1; outline: none;">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" style="padding: 20px 40px; max-height: 70vh; overflow-y: auto;">
              
              <div class="accordion" id="accordionPreguntas">
                <?php foreach($preguntas as $index => $pregunta): 
                  $headingId = "headingFAQ" . $index;
                  $collapseId = "collapseFAQ" . $index;
                  $expanded = $index === 0 ? "true" : "false";
                  $collapseClass = $index === 0 ? "collapse show" : "collapse";
                ?>
                <div class="card shadow-none mb-3" style="border: 1px solid rgba(128,128,128,0.3); border-radius: 8px; background-color: transparent;">
                  <div class="card-header p-0" id="<?php echo $headingId; ?>" style="background-color: transparent; border-bottom: none; border-radius: 8px;">
                    <h2 class="mb-0">
                      <button class="btn btn-link btn-block text-left d-flex justify-content-between align-items-center text-decoration-none text-reset <?php echo $index === 0 ? '' : 'collapsed'; ?>" type="button" data-toggle="collapse" data-target="#<?php echo $collapseId; ?>" aria-expanded="<?php echo $expanded; ?>" aria-controls="<?php echo $collapseId; ?>" style="box-shadow: none;">
                        <?php echo $pregunta['titulo']; ?>
                        <i class="fas fa-chevron-down text-muted" style="font-size: 0.8rem;"></i>
                      </button>
                    </h2>
                  </div>
                  <div id="<?php echo $collapseId; ?>" class="<?php echo $collapseClass; ?>" aria-labelledby="<?php echo $headingId; ?>" data-parent="#accordionPreguntas">
                    <div class="card-body pt-0 text-muted" style="background-color: transparent;">
                      <?php echo $pregunta['respuesta']; ?>
                    </div>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>

            </div>
          </div>
        </div>
      </div>

    </section>
    
    <!-- Modal Datos Personales -->
    <div class="modal fade" id="modalDatosPersonales" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header p-3" style="background-color: #2852ff; color: white; border-bottom: none;">
            <div class="d-flex align-items-center w-100">
              <i class="fas fa-graduation-cap fa-lg ml-2 mr-3" style="color: white !important;"></i>
              <h5 class="modal-title font-weight-bold mb-0 text-uppercase mx-auto" style="font-size: 1.1rem; letter-spacing: 0.5px; color: white !important;">
                DATOS PERSONALES DEL APRENDIZ
              </h5>
              <button type="button" class="close text-white ml-0" style="opacity: 1; text-shadow: none;" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" style="color: white !important;">&times;</span>
              </button>
            </div>
          </div>
          <div class="modal-body p-4">
            
            <!-- Contenedor del paso 1 -->
            <div id="pd-step-1" class="pd-step">
              <!-- Box 1 -->
              <div style="border: 1px dashed #6c757d; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <div class="row">
                  <div class="col-md-6 form-group mb-3">
                    <label style="font-weight: normal; margin-bottom: 5px;">Nombre completo</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-transparent" style="border-right: none;"><i class="far fa-user"></i></span>
                      </div>
                      <input type="text" class="form-control bg-transparent" style="border-left: none; box-shadow: none;">
                    </div>
                  </div>
                  <div class="col-md-6 form-group mb-3">
                    <label style="font-weight: normal; margin-bottom: 5px;">Apellidos</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-transparent" style="border-right: none;"><i class="far fa-user"></i></span>
                      </div>
                      <input type="text" class="form-control bg-transparent" style="border-left: none; box-shadow: none;">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3 form-group mb-0">
                    <input type="text" class="form-control bg-transparent" placeholder="Tipo de documento" style="box-shadow: none; text-align: center; border-radius: 20px;">
                  </div>
                  <div class="col-md-4 form-group mb-0">
                    <input type="text" class="form-control bg-transparent" placeholder="Numero identificación" style="box-shadow: none; text-align: center; border-radius: 20px;">
                  </div>
                  <div class="col-md-3 form-group mb-0">
                    <input type="text" class="form-control bg-transparent" placeholder="Género" style="box-shadow: none; text-align: center; border-radius: 20px;">
                  </div>
                  <div class="col-md-2 form-group mb-0">
                    <input type="text" class="form-control bg-transparent" placeholder="Edad" style="box-shadow: none; text-align: center; border-radius: 20px;">
                  </div>
                </div>
              </div>
              
              <!-- Box 2 -->
              <div style="border: 1px dashed #6c757d; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <div class="row">
                  <div class="col-md-6 form-group mb-3">
                    <label style="font-weight: normal; margin-bottom: 5px;">Departamento residencia</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-transparent" style="border-right: none;"><i class="fas fa-home"></i></span>
                      </div>
                      <input type="text" class="form-control bg-transparent" style="border-left: none; box-shadow: none;">
                    </div>
                  </div>
                  <div class="col-md-6 form-group mb-3">
                    <label style="font-weight: normal; margin-bottom: 5px;">Municipio de residencia</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-transparent" style="border-right: none;"><i class="fas fa-home"></i></span>
                      </div>
                      <input type="text" class="form-control bg-transparent" style="border-left: none; box-shadow: none;">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 form-group mb-3">
                    <label style="font-weight: normal; margin-bottom: 5px;">Dirección</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-transparent" style="border-right: none;"><i class="fas fa-home"></i></span>
                      </div>
                      <input type="text" class="form-control bg-transparent" style="border-left: none; box-shadow: none;">
                    </div>
                  </div>
                  <div class="col-md-6 form-group mb-3">
                    <label style="font-weight: normal; margin-bottom: 5px;">Numero contacto</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-transparent" style="border-right: none;"><i class="fas fa-phone-alt"></i></span>
                      </div>
                      <input type="text" class="form-control bg-transparent" style="border-left: none; box-shadow: none;">
                    </div>
                  </div>
                </div>
                <div class="row justify-content-center">
                  <div class="col-md-6 form-group mb-0 flex-column d-flex align-items-center">
                    <label style="font-weight: normal; margin-bottom: 5px;">Correo electronico</label>
                    <div class="input-group w-100">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-transparent" style="border-right: none;"><i class="far fa-envelope"></i></span>
                      </div>
                      <input type="text" class="form-control bg-transparent" style="border-left: none; box-shadow: none;">
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Box 3 -->
              <div style="border: 1px dashed #6c757d; padding: 15px; border-radius: 5px;">
                <div class="row">
                  <div class="col-md-6 form-group mb-3">
                    <label style="font-weight: normal; margin-bottom: 5px;">Nombre contacto</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-transparent" style="border-right: none;"><i class="far fa-user"></i></span>
                      </div>
                      <input type="text" class="form-control bg-transparent" style="border-left: none; box-shadow: none;">
                    </div>
                  </div>
                  <div class="col-md-6 form-group mb-3">
                    <label style="font-weight: normal; margin-bottom: 5px;">Número contacto</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-transparent" style="border-right: none;"><i class="fas fa-phone-alt"></i></span>
                      </div>
                      <input type="text" class="form-control bg-transparent" style="border-left: none; box-shadow: none;">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 form-group mb-0">
                    <label style="font-weight: normal; margin-bottom: 5px;">Grupo SISBEN</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-transparent" style="border-right: none;"><i class="fas fa-users"></i></span>
                      </div>
                      <input type="text" class="form-control bg-transparent" style="border-left: none; box-shadow: none;">
                    </div>
                  </div>
                  <div class="col-md-6 form-group mb-0">
                    <label style="font-weight: normal; margin-bottom: 5px;">Nivel SISBEN</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-transparent" style="border-right: none;"><i class="fas fa-layer-group"></i></span>
                      </div>
                      <input type="text" class="form-control bg-transparent" style="border-left: none; box-shadow: none;">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Pasos vacíos adicionales 2 al 8 -->
            <div id="pd-step-2" class="pd-step" style="display: none;">
              <div class="text-center py-5"><h4>Paso 2</h4><p>Formulario del paso 2...</p></div>
            </div>
            <div id="pd-step-3" class="pd-step" style="display: none;">
              <div class="text-center py-5"><h4>Paso 3</h4><p>Formulario del paso 3...</p></div>
            </div>
            <div id="pd-step-4" class="pd-step" style="display: none;">
              <div class="text-center py-5"><h4>Paso 4</h4><p>Formulario del paso 4...</p></div>
            </div>
            <div id="pd-step-5" class="pd-step" style="display: none;">
              <div class="text-center py-5"><h4>Paso 5</h4><p>Formulario del paso 5...</p></div>
            </div>
            <div id="pd-step-6" class="pd-step" style="display: none;">
              <div class="text-center py-5"><h4>Paso 6</h4><p>Formulario del paso 6...</p></div>
            </div>
            <div id="pd-step-7" class="pd-step" style="display: none;">
              <div class="text-center py-5"><h4>Paso 7</h4><p>Formulario del paso 7...</p></div>
            </div>
            <div id="pd-step-8" class="pd-step" style="display: none;">
              <div class="text-center py-5"><h4>Paso 8</h4><p>Finalización del registro...</p></div>
            </div>

          </div>
          <div class="modal-footer d-flex justify-content-center border-0 pt-0 pb-4">
            <div class="w-100 d-flex justify-content-between align-items-center px-4 flex-wrap">
              <div class="d-flex mb-2" style="gap: 5px; flex-wrap: wrap;" id="pd-pagination">
                <span class="pd-circle active" data-step="1">1</span>
                <span class="pd-circle" data-step="2">2</span>
                <span class="pd-circle" data-step="3">3</span>
                <span class="pd-circle" data-step="4">4</span>
                <span class="pd-circle" data-step="5">5</span>
                <span class="pd-circle" data-step="6">6</span>
                <span class="pd-circle" data-step="7">7</span>
                <span class="pd-circle" data-step="8">8</span>
              </div>
              <div class="d-flex mb-2">
                <button type="button" class="btn btn-danger mr-2" data-dismiss="modal" style="border-radius: 8px; padding: 6px 20px;">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnPdSiguiente" style="background-color: #2852ff; border-color: #2852ff; border-radius: 8px; padding: 6px 20px;">Siguiente</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <style>
    .pd-circle {
      display: inline-flex;
      justify-content: center;
      align-items: center;
      width: 30px;
      height: 30px;
      border-radius: 50%;
      background-color: #bcbcbc;
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s;
    }
    .pd-circle.active {
      background-color: #2852ff;
    }
    </style>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        let pdCurrentStep = 1;
        const pdTotalSteps = 8;
        const btnPdSiguiente = document.getElementById('btnPdSiguiente');
        const pdCircles = document.querySelectorAll('.pd-circle');
        
        function updatePdStep(step) {
          for(let i=1; i<=pdTotalSteps; i++) {
            let el = document.getElementById('pd-step-' + i);
            if(el) el.style.display = 'none';
          }
          let currentEl = document.getElementById('pd-step-' + step);
          if(currentEl) currentEl.style.display = 'block';

          pdCircles.forEach(c => {
            c.classList.remove('active');
            if(parseInt(c.getAttribute('data-step')) === step) {
              c.classList.add('active');
            }
          });

          if(step === pdTotalSteps) {
            if(btnPdSiguiente) btnPdSiguiente.textContent = 'Finalizar';
          } else {
            if(btnPdSiguiente) btnPdSiguiente.textContent = 'Siguiente';
          }
        }

        if (btnPdSiguiente) {
          btnPdSiguiente.addEventListener('click', function() {
            if (pdCurrentStep < pdTotalSteps) {
              pdCurrentStep++;
              updatePdStep(pdCurrentStep);
            } else {
              $('#modalDatosPersonales').modal('hide');
              pdCurrentStep = 1;
              updatePdStep(pdCurrentStep);
            } 
          });
        }

        pdCircles.forEach(circle => {
          circle.addEventListener('click', function() {
            pdCurrentStep = parseInt(this.getAttribute('data-step'));
            updatePdStep(pdCurrentStep);
          });
        });
      });
    </script>
    <!-- /.content -->
  
  <!-- </div> -->
  <!-- /.content-wrapper -->