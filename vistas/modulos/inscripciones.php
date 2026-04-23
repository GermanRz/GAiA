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
  <link rel="stylesheet" href="inscripciones.css/styles.css">

  <!-- Botones superiores -->
  <div class="row mb-4">
    <div class="col-md-6 mb-2">
      <button class="btn btn-primary btn-block custom-btn-rounded" data-toggle="modal"
        data-target="#modalDatosPersonales">Inscripción a apoyos</button>
    </div>
    <div class="col-md-6 mb-2">
      <button class="btn btn-primary btn-block custom-btn-rounded" data-toggle="modal" data-target="#modalPreguntas">Preguntas Frecuentes</button>
    </div>
  </div>

  <!-- Tarjeta principal -->
  <div class="card bg-dark text-light">
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

        foreach ($categorias as $categoria): ?>
          <div class="mb-4">
            <h5 class="mb-2 font-weight-bold">
              <?php echo $categoria; ?> <span class="text-muted text-sm">| 20/100</span>
            </h5>
            <div class="table-responsive">
              <table class="table table-sm table-dark table-bordered table-striped text-center m-0 inscripciones-table">
                <thead class="bg-success text-white">
                  <tr>
                    <th class="align-middle w-10">AUTORIZACION<br>DE MENOR</th>
                    <th class="align-middle w-10">DISCAPACIDAD</th>
                    <th class="align-middle w-10">COMUNIDAD<br>ETNICA</th>
                    <th class="align-middle w-10">CAMPESINO</th>
                    <th class="align-middle w-10">CABEZA<br>DE FAMILIA</th>
                    <th class="align-middle w-10">VICTIMA<br>DE CONFLICTO</th>
                    <th class="align-middle w-10">VIOLENCIA<br>DE GENERO</th>
                    <th class="align-middle w-10">EMBARAZADA<br>/LACTANCIA</th>
                    <th class="align-middle w-10">SISBEN<br>A/B</th>
                    <th class="align-middle w-10">DESPLAZADO<br>NATURAL</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="bg-danger py-1">Rechazado</td>
                    <td class="bg-danger py-1">Rechazado</td>
                    <td class="bg-warning text-light py-1">Pendiente</td>
                    <td class="bg-danger py-1">Rechazado</td>
                    <td class="bg-danger py-1">Rechazado</td>
                    <td class="bg-secondary py-1 text-white">No Presentado</td>
                    <td class="bg-danger py-1">Rechazado</td>
                    <td class="bg-success py-1 text-white">Aprobado</td>
                    <td class="bg-danger py-1">Rechazado</td>
                    <td class="bg-success py-1 text-white">Aprobado</td>
                  </tr>
                  <tr>
                    <td class="py-2"><button type="button" class="btn btn-tool" data-toggle="modal"
                        data-target="#modalSubirArchivo"><i class="fas fa-upload fa-lg"></i></button></td>
                    <td class="py-2"><button type="button" class="btn btn-tool" data-toggle="modal"
                        data-target="#modalSubirArchivo"><i class="fas fa-upload fa-lg"></i></button></td>
                    <td class="py-2"><i class="fas fa-ban fa-lg text-muted"></i></td>
                    <td class="py-2"><button type="button" class="btn btn-tool" data-toggle="modal"
                        data-target="#modalSubirArchivo"><i class="fas fa-upload fa-lg"></i></button></td>
                    <td class="py-2"><button type="button" class="btn btn-tool" data-toggle="modal"
                        data-target="#modalSubirArchivo"><i class="fas fa-upload fa-lg"></i></button></td>
                    <td class="py-2"><i class="fas fa-ban fa-lg text-muted"></i></td>
                    <td class="py-2"><button type="button" class="btn btn-tool" data-toggle="modal"
                        data-target="#modalSubirArchivo"><i class="fas fa-upload fa-lg"></i></button></td>
                    <td class="py-2"><i class="fas fa-ban fa-lg text-muted"></i></td>
                    <td class="py-2"><button type="button" class="btn btn-tool" data-toggle="modal"
                        data-target="#modalSubirArchivo"><i class="fas fa-upload fa-lg"></i></button></td>
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
      <div id="vistaAdmin" class="d-none">
        <div class="row align-items-center mb-4">
          <div class="col-md-8">
            <ul class="nav nav-pills" id="adminTabs" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active btn-admin-tab" id="tab-sost-btn" data-toggle="pill"
                  data-target="#tab-sost" type="button" role="tab">
                  <i class="fas fa-wallet fa-lg mr-2"></i> Apoyo de sostenimiento
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link btn-admin-tab" id="tab-datos-btn" data-toggle="pill" data-target="#tab-datos"
                  type="button" role="tab">
                  <i class="fas fa-phone-alt fa-lg mr-2"></i> Apoyo de datos
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link btn-admin-tab" id="tab-alim-btn" data-toggle="pill" data-target="#tab-alim"
                  type="button" role="tab">
                  <i class="fas fa-utensils fa-lg mr-2"></i> Apoyo de alimentación
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link btn-admin-tab" id="tab-trans-btn" data-toggle="pill" data-target="#tab-trans"
                  type="button" role="tab">
                  <i class="fas fa-car fa-lg mr-2"></i> Apoyo de transporte
                </button>
              </li>
            </ul>
          </div>
          <div class="col-md-4 mt-3 mt-md-0">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="far fa-user"></i></span>
              </div>
              <input type="text" class="form-control" placeholder="Buscar aprendiz">
            </div>
          </div>
        </div>

        <div class="tab-content" id="adminTabsContent">
          <!-- CONTENEDOR 1: Sostenimiento -->
          <div class="tab-pane fade show active" id="tab-sost" role="tabpanel">
            <div class="table-responsive scrollable-table">
              <table class="table table-dark table-bordered table-striped text-center mb-0 text-nowrap">
                <thead class="bg-success text-white">
                  <tr>
                    <th>Identificacion</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Ficha</th>
                    <th class="w-15">Estado</th>
                    <th>Fecha De Inscripcion</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $estadosAdmin = ['Inscrito', 'En Proceso', 'Inscrito', 'En Proceso', 'Inscrito', 'En Proceso', 'Inscrito'];
                  foreach ($estadosAdmin as $estado): ?>
                    <tr>
                      <td class="align-middle">1</td>
                      <td class="align-middle">Ken</td>
                      <td class="align-middle">Ken@Gmail.Com</td>
                      <td class="align-middle">3063989</td>
                      <td class="p-0 align-middle">
                        <?php if ($estado == 'Inscrito'): ?>
                          <button class="btn btn-success btn-block rounded-0"><i class="fas fa-check mr-2"></i>
                            Inscrito</button>
                        <?php else: ?>
                          <button class="btn btn-warning btn-block rounded-0 text-white"><i class="fas fa-check mr-2"></i>
                            En Proceso</button>
                        <?php endif; ?>
                      </td>
                      <td class="align-middle">06/11/2025</td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- CONTENEDOR 2: Datos -->
          <div class="tab-pane fade" id="tab-datos" role="tabpanel">
            <div class="table-responsive scrollable-table">
              <table class="table table-dark table-bordered table-striped text-center mb-0 text-nowrap">
                <thead class="bg-success text-white">
                  <tr>
                    <th>Identificacion</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Ficha</th>
                    <th class="w-15">Estado</th>
                    <th>Fecha De Inscripcion</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($estadosAdmin as $estado): ?>
                    <tr>
                      <td class="align-middle">2</td>
                      <td class="align-middle">María</td>
                      <td class="align-middle">Maria@Gmail.Com</td>
                      <td class="align-middle">3063990</td>
                      <td class="p-0 align-middle">
                        <?php if ($estado == 'Inscrito'): ?>
                          <button class="btn btn-success btn-block rounded-0"><i class="fas fa-check mr-2"></i>
                            Inscrito</button>
                        <?php else: ?>
                          <button class="btn btn-warning btn-block rounded-0 text-white"><i class="fas fa-check mr-2"></i>
                            En Proceso</button>
                        <?php endif; ?>
                      </td>
                      <td class="align-middle">07/11/2025</td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- CONTENEDOR 3: Alimentacion -->
          <div class="tab-pane fade" id="tab-alim" role="tabpanel">
            <div class="table-responsive scrollable-table">
              <table class="table table-dark table-bordered table-striped text-center mb-0 text-nowrap">
                <thead class="bg-success text-white">
                  <tr>
                    <th>Identificacion</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Ficha</th>
                    <th class="w-15">Estado</th>
                    <th>Fecha De Inscripcion</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($estadosAdmin as $estado): ?>
                    <tr>
                      <td class="align-middle">3</td>
                      <td class="align-middle">Carlos</td>
                      <td class="align-middle">Carlos@Gmail.Com</td>
                      <td class="align-middle">3063991</td>
                      <td class="p-0 align-middle">
                        <?php if ($estado == 'Inscrito'): ?>
                          <button class="btn btn-success btn-block rounded-0"><i class="fas fa-check mr-2"></i>
                            Inscrito</button>
                        <?php else: ?>
                          <button class="btn btn-warning btn-block rounded-0 text-white"><i class="fas fa-check mr-2"></i>
                            En Proceso</button>
                        <?php endif; ?>
                      </td>
                      <td class="align-middle">08/11/2025</td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- CONTENEDOR 4: Transporte -->
          <div class="tab-pane fade" id="tab-trans" role="tabpanel">
            <div class="table-responsive scrollable-table">
              <table class="table table-dark table-bordered table-striped text-center mb-0 text-nowrap">
                <thead class="bg-success text-white">
                  <tr>
                    <th>Identificacion</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Ficha</th>
                    <th class="w-15">Estado</th>
                    <th>Fecha De Inscripcion</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($estadosAdmin as $estado): ?>
                    <tr>
                      <td class="align-middle">4</td>
                      <td class="align-middle">Laura</td>
                      <td class="align-middle">Laura@Gmail.Com</td>
                      <td class="align-middle">3063992</td>
                      <td class="p-0 align-middle">
                        <?php if ($estado == 'Inscrito'): ?>
                          <button class="btn btn-success btn-block rounded-0"><i class="fas fa-check mr-2"></i>
                            Inscrito</button>
                        <?php else: ?>
                          <button class="btn btn-warning btn-block rounded-0 text-white"><i class="fas fa-check mr-2"></i>
                            En Proceso</button>
                        <?php endif; ?>
                      </td>
                      <td class="align-middle">09/11/2025</td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div> <!-- /.tab-content -->
      </div>
      <!-- /VISTA ADMINISTRADOR -->

    </div>
    <div class="card-footer position-relative d-flex justify-content-center align-items-center bg-dark border-top">
      <button id="btnSubirDocumento" class="btn btn-primary px-4" data-toggle="modal" data-target="#modalInfoBancaria">
        <i class="fas fa-file-upload mr-2"></i> Subir documento de certificación bancaria
      </button>
    </div>
  </div>

  <!-- Modal Info Bancaria -->
  <div class="modal fade" id="modalInfoBancaria" tabindex="-1" role="dialog" aria-labelledby="modalInfoBancariaTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content bg-dark text-light">
        <div class="modal-header bg-primary p-3 border-0">
          <div class="d-flex align-items-center w-100">
            <i class="fas fa-address-card fa-lg ml-2 mr-3 text-white-important"></i>
            <h5 class="modal-title font-weight-bold mb-0 text-uppercase mx-auto modal-title-sm text-white-important" id="modalInfoBancariaTitle">
              ADICCIONAR INFORMACION BANCARIA
            </h5>
            <button type="button" class="close text-white ml-0 close-no-shadow"
              data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-white-important">&times;</span>
            </button>
          </div>
        </div>
        <div class="modal-body p-4">
          <div class="box-dashed">
            <div class="form-group mb-4">
              <label for="numeroCuenta" class="form-label-normal">numero de cuenta</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                      class="far fa-dot-circle"></i></span>
                </div>
                <input type="text" class="form-control bg-transparent form-control-no-border-left" id="numeroCuenta">
              </div>
            </div>
            <div class="form-group mb-4">
              <label for="nombreBanco" class="form-label-normal">nombre del banco</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                      class="fas fa-file-invoice"></i></span>
                </div>
                <input type="text" class="form-control bg-transparent form-control-no-border-left" id="nombreBanco">
              </div>
            </div>
            <button type="button" class="btn btn-primary shadow-sm btn-upload" id="btnAbrirSubirArchivo">
              <i class="fas fa-upload mr-1"></i> Subir archivo
            </button>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0 pb-4 pr-4">
          <button type="button" class="btn btn-primary btn-upload-square" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Subir Archivo -->
  <div class="modal fade modal-high-z" id="modalSubirArchivo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content bg-dark text-light text-center modal-rounded modal-content-centered modal-content-dark">
        <button type="button" class="close position-absolute modal-close-top-right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="text-white modal-close-icon">&times;</span>
        </button>
        <div class="modal-body d-flex flex-column justify-content-center align-items-center modal-body-upload">
          <div class="mb-4">
            <i class="fas fa-upload icon-large"></i>
          </div>
          <p class="mb-5 upload-description">Arrastra aqui el archivo o buscar en el dispositivo</p>
          <div class="d-flex justify-content-center flex-wrap upload-action-group">
            <button type="button" class="btn btn-primary px-4 py-2 btn-upload-square">Buscar en el
              dispositivo</button>
            <button type="button" class="btn btn-primary px-4 py-2 btn-upload-square">Enviar documento</button>
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
      <div class="modal-content bg-dark text-light modal-rounded overflow-hidden">
        <div class="modal-header bg-primary d-flex justify-content-between align-items-center border-0">
          <div>
            <i class="fas fa-user-graduate icon-notification"></i>
          </div>
          <h5 class="modal-title font-weight-bold modal-title-sm text-center flex-grow-1">PREGUNTAS FRECUENTES</h5>
          <button type="button" class="close text-white close-no-shadow" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body modal-faq-body">

          <div class="accordion" id="accordionPreguntas">
            <?php foreach ($preguntas as $index => $pregunta):
              $headingId = "headingFAQ" . $index;
              $collapseId = "collapseFAQ" . $index;
              $expanded = $index === 0 ? "true" : "false";
              $collapseClass = $index === 0 ? "collapse show" : "collapse";
              ?>
              <div class="card shadow-none mb-3 faq-card">
                <div class="card-header p-0 faq-card-header" id="<?php echo $headingId; ?>">
                  <h2 class="mb-0">
                    <button
                      class="btn btn-link btn-block text-left d-flex justify-content-between align-items-center text-decoration-none text-reset faq-collapse-btn <?php echo $index === 0 ? '' : 'collapsed'; ?>"
                      type="button" data-toggle="collapse" data-target="#<?php echo $collapseId; ?>"
                      aria-expanded="<?php echo $expanded; ?>" aria-controls="<?php echo $collapseId; ?>">
                      <?php echo $pregunta['titulo']; ?>
                      <i class="fas fa-chevron-down text-muted icon-small"></i>
                    </button>
                  </h2>
                </div>
                <div id="<?php echo $collapseId; ?>" class="<?php echo $collapseClass; ?>"
                  aria-labelledby="<?php echo $headingId; ?>" data-parent="#accordionPreguntas">
                  <div class="card-body pt-0 text-muted card-body-transparent">
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
    <div class="modal-content bg-dark text-light">
      <div class="modal-header bg-primary p-3 border-0">
        <div class="d-flex align-items-center w-100">
          <i class="fas fa-graduation-cap fa-lg ml-2 mr-3 text-white-important"></i>
          <h5 class="modal-title font-weight-bold mb-0 text-uppercase mx-auto modal-title-sm text-white-important" id="pdModalTitle">
            DATOS PERSONALES DEL APRENDIZ
          </h5>
          <button type="button" class="close text-white ml-0 close-no-shadow"
            data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-white-important">&times;</span>
          </button>
        </div>
      </div>
      <div class="modal-body p-4">

        <!-- Contenedor del paso 1 -->
        <div id="pd-step-1" class="pd-step">
          <div class="box-dashed">
            <div class="row">
              <div class="col-md-6 form-group mb-3">
                <label class="form-label-normal">Nombre completo</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="far fa-user"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
              <div class="col-md-6 form-group mb-3">
                <label class="form-label-normal">Apellidos</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="far fa-user"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3 form-group mb-0">
                <input type="text" class="form-control bg-transparent form-control-round-centered" placeholder="Tipo de documento">
              </div>
              <div class="col-md-4 form-group mb-0">
                <input type="text" class="form-control bg-transparent form-control-round-centered" placeholder="Numero identificación">
              </div>
              <div class="col-md-3 form-group mb-0">
                <input type="text" class="form-control bg-transparent form-control-round-centered" placeholder="Género">
              </div>
              <div class="col-md-2 form-group mb-0">
                <input type="text" class="form-control bg-transparent form-control-round-centered" placeholder="Edad">
              </div>
            </div>
          </div>

          <div class="box-dashed">
            <div class="row">
              <div class="col-md-6 form-group mb-3">
                <label class="form-label-normal">Departamento residencia</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="fas fa-home"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
              <div class="col-md-6 form-group mb-3">
                <label class="form-label-normal">Municipio de residencia</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="fas fa-home"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 form-group mb-3">
                <label class="form-label-normal">Dirección</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="fas fa-home"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
              <div class="col-md-6 form-group mb-3">
                <label class="form-label-normal">Numero contacto</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="fas fa-phone-alt"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
            </div>
            <div class="row justify-content-center">
              <div class="col-md-6 form-group mb-0 flex-column d-flex align-items-center">
                <label class="form-label-normal">Correo electronico</label>
                <div class="input-group w-100">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="far fa-envelope"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
            </div>
          </div>

          <div class="box-dashed">
            <div class="row">
              <div class="col-md-6 form-group mb-3">
                <label class="form-label-normal">Nombre contacto</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="far fa-user"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
              <div class="col-md-6 form-group mb-3">
                <label class="form-label-normal">Número contacto</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="fas fa-phone-alt"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 form-group mb-0">
                <label class="form-label-normal">Grupo SISBEN</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="fas fa-users"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
              <div class="col-md-6 form-group mb-0">
                <label class="form-label-normal">Nivel SISBEN</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="fas fa-layer-group"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Contenedor del paso 2 -->
        <div id="pd-step-2" class="pd-step">
          <div class="box-dashed-large">
            <p class="mb-2 text-large">Centro Latinoamericano De Especies Menores</p>
            <p class="mb-4">seleccione la sede a la que pertenece</p>
            <select class="form-control mb-4 bg-transparent border-dark form-control-large">
              <option>Select option</option>
            </select>
            <div class="form-control-large">
              <label class="form-label-normal">numero de ficha</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-transparent border-dark input-group-text-no-border-right"><i
                      class="fas fa-file-alt"></i></span>
                </div>
                <input type="text" class="form-control bg-transparent border-dark form-control-no-border-left">
              </div>
            </div>
          </div>
        </div>

        <!-- Contenedor del paso 3 -->
        <div id="pd-step-3" class="pd-step">
          <div class="box-dashed">
            <div class="row">
              <div class="col-md-6 form-group mb-3">
                <label class="form-label-normal">Nombres del representante</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="far fa-user"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
              <div class="col-md-6 form-group mb-3">
                <label class="form-label-normal">Apellidos del representante</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="far fa-user"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
            </div>
            <div class="row justify-content-center">
              <div class="col-md-6 form-group mb-3">
                <label class="form-label-normal">parentezco</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="fas fa-user-friends"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
            </div>
            <div class="row justify-content-center">
              <div class="col-md-6 form-group mb-3">
                <label class="form-label-normal">numero de documento</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="fas fa-file-alt"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 form-group mb-0">
                <input type="text" class="form-control bg-transparent form-control-round-centered" placeholder="Tipo de documento">
              </div>
              <div class="col-md-4 form-group mb-0">
                <input type="text" class="form-control bg-transparent form-control-round-centered" placeholder="Género">
              </div>
              <div class="col-md-4 form-group mb-0">
                <input type="text" class="form-control bg-transparent form-control-round-centered" placeholder="Edad">
              </div>
            </div>
          </div>
          <div class="box-dashed">
            <div class="row">
              <div class="col-md-6 form-group mb-3">
                <label class="form-label-normal">Departamento residencia</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="fas fa-home"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
              <div class="col-md-6 form-group mb-3">
                <label class="form-label-normal">Municipio de residencia</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="fas fa-home"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
            </div>
            <div class="row justify-content-center">
              <div class="col-md-6 form-group mb-3 flex-column d-flex align-items-center">
                <label class="form-label-normal vertical-label-full">Correo electronico</label>
                <div class="input-group w-100">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="far fa-envelope"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
            </div>
            <div class="row justify-content-center">
              <div class="col-md-6 form-group mb-0 flex-column d-flex align-items-center">
                <label class="form-label-normal vertical-label-full">Dirección</label>
                <div class="input-group w-100">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="fas fa-home"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
            </div>
          </div>
          <div class="row justify-content-center">
            <div class="col-md-6 form-group mb-3">
              <label class="form-label-normal">Número contacto</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                      class="fas fa-phone-alt"></i></span>
                </div>
                <input type="text" class="form-control bg-transparent form-control-no-border-left">
              </div>
            </div>
          </div>
          <p class="text-center mt-2 mb-0 form-warning-text">*En Caso De Que El Aprendiz Sea
            Menor De Edad El Tutor A Cargo Deberá Llenar Estos Datos*</p>
        </div>

        <!-- Contenedor del paso 4 -->
        <div id="pd-step-4" class="pd-step">
          <div class="box-dashed-large">
            <div class="row">
              <div class="col-md-6 form-group mb-4">
                <label class="form-label-normal">ubicación de la vivienda</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="fas fa-home"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
              <div class="col-md-6 form-group mb-4">
                <label class="form-label-normal">Número de estrato</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="far fa-edit"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 form-group mb-4">
                <label class="form-label-normal">tipo de regimen</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="fas fa-hand-holding-medical"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
              <div class="col-md-6 form-group mb-4">
                <label class="form-label-normal">EPS</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="fas fa-user-md"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
            </div>
            <div class="row justify-content-center">
              <div class="col-md-6 form-group mb-0">
                <label class="form-label-normal">tipo de vinculación</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="far fa-user"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Contenedor del paso 5 (Mismo contenido del paso 3 modificado o similar para mantener la captura) -->
        <div id="pd-step-5" class="pd-step">
          <!-- El diseño del paso 5 es casi idéntico al 3 en los campos pero dice INFORMACION SOCIOECONOMICA -->
          <div class="box-dashed">
            <div class="row">
              <div class="col-md-6 form-group mb-3">
                <label class="form-label-normal">Nombres del representante</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="far fa-user"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
              <div class="col-md-6 form-group mb-3">
                <label class="form-label-normal">Apellidos del representante</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="far fa-user"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
            </div>
            <div class="row justify-content-center">
              <div class="col-md-6 form-group mb-3">
                <label class="form-label-normal">parentezco</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="fas fa-user-friends"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
            </div>
            <div class="row justify-content-center">
              <div class="col-md-6 form-group mb-3">
                <label class="form-label-normal">numero de documento</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="fas fa-file-alt"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 form-group mb-0">
                <input type="text" class="form-control bg-transparent form-control-round-centered" placeholder="Tipo de documento">
              </div>
              <div class="col-md-4 form-group mb-0">
                <input type="text" class="form-control bg-transparent form-control-round-centered" placeholder="Género">
              </div>
              <div class="col-md-4 form-group mb-0">
                <input type="text" class="form-control bg-transparent form-control-round-centered" placeholder="Edad">
              </div>
            </div>
          </div>
          <div class="box-dashed">
            <div class="row">
              <div class="col-md-6 form-group mb-3">
                <label class="form-label-normal">Departamento residencia</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="fas fa-home"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
              <div class="col-md-6 form-group mb-3">
                <label class="form-label-normal">Municipio de residencia</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="fas fa-home"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
            </div>
            <div class="row justify-content-center">
              <div class="col-md-6 form-group mb-3 flex-column d-flex align-items-center">
                <label class="form-label-normal vertical-label-full">Correo electronico</label>
                <div class="input-group w-100">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="far fa-envelope"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
            </div>
            <div class="row justify-content-center">
              <div class="col-md-6 form-group mb-0 flex-column d-flex align-items-center">
                <label class="form-label-normal vertical-label-full">Dirección</label>
                <div class="input-group w-100">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                        class="fas fa-home"></i></span>
                  </div>
                  <input type="text" class="form-control bg-transparent form-control-no-border-left">
                </div>
              </div>
            </div>
          </div>
          <div class="row justify-content-center">
            <div class="col-md-6 form-group mb-0">
              <label class="form-label-normal">Número contacto</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-transparent input-group-text-no-border-right"><i
                      class="fas fa-phone-alt"></i></span>
                </div>
                <input type="text" class="form-control bg-transparent form-control-no-border-left">
              </div>
            </div>
          </div>
        </div>

        <!-- Contenedor del paso 6 -->
        <div id="pd-step-6" class="pd-step">
          <p class="text-center mb-3 mt-4">Documentos del aprendiz</p>
          <div class="box-dashed-large">
            <div class="d-flex justify-content-center flex-wrap upload-action-group">
              <button type="button" class="btn btn-primary btn-blue-solid"
                data-toggle="modal" data-target="#modalSubirArchivo">Documento de identidad</button>
              <button type="button" class="btn btn-primary btn-blue-solid"
                data-toggle="modal" data-target="#modalSubirArchivo">Recibo de servicio publico(Energia, Agua o
                Gas)</button>
              <button type="button" class="btn btn-primary btn-blue-solid"
                data-toggle="modal" data-target="#modalSubirArchivo">Documento de su EPS</button>
            </div>
          </div>
          <p class="text-center mb-4 form-warning-text">¡Documentos del representante en caso del aprendiz ser
            menor de edad!</p>
          <div class="text-center mb-4 pb-2">
            <button type="button" class="btn btn-primary btn-blue-solid"
              data-toggle="modal" data-target="#modalSubirArchivo">Documento de identidad</button>
          </div>
        </div>

        <div id="pd-step-7" class="pd-step">
          <div class="scroll-section">
            <?php
            $preguntasPaso7 = [
              ["texto" => "aprendiz embarazada o en periodo de lactancia hasta un (1) año despues del parto y por razones de lactancia?", "color" => "text-danger"],
              ["texto" => "se reconoce como aprendiz padre o madre cabeza de familia?", "color" => ""],
              ["texto" => "se reconoce como aprendiz en cituacion de discapacidad? cual discapacidad?", "color" => "text-danger"],
              ["texto" => "se reconoce como aprendiz victima de violencia basada en genero y violencia contra la mujer", "color" => ""],
              ["texto" => "aprendiz victima de conflicto armado ley 1448 de 2011 - decreto 4800 de 2011", "color" => ""],
              ["texto" => "se reconoce como aprendiz perteneciente a comunidades narp (negritudez, afrocolombianos, raizales palanqueros) pueblo rom, poblacion indigena?", "color" => ""],
              ["texto" => "se reconoce como aprendiz en cituacion de desplazamiento por fenomenos naturales en los ultimos dos (2) años?", "color" => "text-danger"]
            ];
            foreach ($preguntasPaso7 as $index => $pregunta):
              ?>
              <div class="row align-items-center mb-4">
                <div class="col-8">
                  <p class="mb-0 <?php echo $pregunta['color']; ?>"><?php echo $pregunta['texto']; ?></p>
                </div>
                <div class="col-4 d-flex justify-content-around">
                  <div class="custom-control custom-radio">
                    <input type="radio" id="p7_<?php echo $index; ?>_si" name="p7_<?php echo $index; ?>"
                      class="custom-control-input">
                    <label class="custom-control-label font-weight-normal" for="p7_<?php echo $index; ?>_si">si</label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input type="radio" id="p7_<?php echo $index; ?>_no" name="p7_<?php echo $index; ?>"
                      class="custom-control-input">
                    <label class="custom-control-label font-weight-normal" for="p7_<?php echo $index; ?>_no">no</label>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div id="pd-step-8" class="pd-step">
          <div class="scroll-section">
            <?php
            $preguntasPaso8 = [
              ["texto" => "es aprendiz con sisben nivel grupo a 1,2,3,4,", "color" => ""],
              ["texto" => "es aprendiz con sisben nivel grupo b 1,2,3,4,5,6", "color" => ""],
              ["texto" => "se reconoce como aprendiz campesino?", "color" => "text-danger"],
              ["texto" => "es aprendiz representante elegido segun normatividad institucional?", "color" => ""],
              ["texto" => "es aprendiz vocero principal y/o suplente elegido segun normatividad institucional", "color" => ""],
              ["texto" => "¿es aperndiz que participa en semillero de navegacion o worldskills o senasoft o produccion dell centro? cual?", "color" => ""],
              ["texto" => "es aprendiz que ha tenido un cupo en el internado en la vigencia interior?", "color" => ""],
              ["texto" => "tiene certificado de nivel tecnologo o titulo profesional?", "color" => "text-danger"],
              ["texto" => "Adjunta informacion declaracion fundamentada", "color" => "text-danger"]
            ];
            foreach ($preguntasPaso8 as $index => $pregunta):
              ?>
              <div class="row align-items-center mb-4">
                <div class="col-8">
                  <p class="mb-0 <?php echo $pregunta['color']; ?>"><?php echo $pregunta['texto']; ?></p>
                </div>
                <div class="col-4 d-flex justify-content-around">
                  <div class="custom-control custom-radio">
                    <input type="radio" id="p8_<?php echo $index; ?>_si" name="p8_<?php echo $index; ?>"
                      class="custom-control-input">
                    <label class="custom-control-label font-weight-normal" for="p8_<?php echo $index; ?>_si">si</label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input type="radio" id="p8_<?php echo $index; ?>_no" name="p8_<?php echo $index; ?>"
                      class="custom-control-input">
                    <label class="custom-control-label font-weight-normal" for="p8_<?php echo $index; ?>_no">no</label>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

      </div>
      <div class="modal-footer d-flex justify-content-center border-0 pt-0 pb-4">
        <div class="w-100 d-flex justify-content-between align-items-center px-4 flex-wrap">
          <div class="d-flex gap-5-flex-wrap mb-2" id="pd-pagination">
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
            <button type="button" class="btn btn-danger mr-2 btn-pill-md" id="btnPdAnterior">Cancelar</button>
            <button type="button" class="btn btn-primary btn-blue-solid btn-pill-md" id="btnPdSiguiente">Siguiente</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Confirmacion Obligaciones -->
<div class="modal fade" id="modalConfirmacion" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content text-center modal-content-lg-rounded">
      <div class="modal-body">
        <i class="fas fa-exclamation-circle mb-3 icon-notification"></i>
        <p class="modal-notification-text mb-4">
          conoce las<br>obligaciones que<br>adquiere como<br>aprendiz<br>si es beneficiario
          del<br>apoyo<br>socioeconomico al<br>que<br>se esta postulando?
        </p>
        <div class="d-flex justify-content-center btn-group-gap">
          <button type="button" class="btn btn-danger px-4 btn-pill-md" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary px-4 btn-primary-emphasis btn-pill-md" id="btnContinuarConfirmacion" data-dismiss="modal">Continuar</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Finalizado Exito -->
<div class="modal fade" id="modalExitoInscripcion" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content text-center modal-content-lg-rounded">
      <div class="modal-body">
        <i class="fas fa-exclamation-circle mb-3 icon-notification"></i>
        <p class="modal-notification-text mb-4">
          Tu inscripción se<br>completo<br>corectamente. te<br>invitamos a estar al<br>tanto con las<br>notificaciones.
        </p>
        <button type="button" class="btn btn-primary px-5 btn-primary-emphasis" id="btnFinalizarTodo" data-dismiss="modal">Finalizar</button>
      </div>
    </div>
  </div>
</div>

<script src="vistas/js/InscripcionesTablaUsuarios.js"></script>
<script src="vistas/js/InscripcionesTablaAdministrador.js"></script>


<!-- /.content -->

<!-- </div> -->
<!-- /.content-wrapper -->