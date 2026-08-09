<?php

// Consultar convocatorias activas para el filtrado superior
$convocatoriasActivas = ControladorConvocatorias::ctrListarConvocatoriasActivas();

?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="font-weight-bold text-light"><i class="fas fa-handshake mr-2 text-success"></i>Verificación de Requisitos</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="inicio" class="text-success"><i class="fas fa-home"></i> Inicio</a></li>
          <li class="breadcrumb-item active text-muted">Verificación</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<!-- Main content -->
<section class="content animate__animated animate__fadeIn">
  <div class="container-fluid">

    <!-- ========================================================================================================
    GUÍA DE USO PARA GESTORES
    ========================================================================================================= -->
    <div class="alert alert-dismissible bg-dark border border-secondary text-light mb-4 shadow-sm" style="border-left: 5px solid #198754 !important;">
        <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5 class="font-weight-bold text-success"><i class="icon fas fa-gavel mr-2"></i>Consola del Gestor de Bienestar</h5>
        <p class="mb-0" style="font-size: 0.95rem;">
            Selecciona una de las convocatorias para revisar las postulaciones. Haz clic sobre los botones <strong>naranjas ( <i class="fas fa-exclamation"></i> )</strong> para revisar el PDF del aprendiz y calificarlo. 
            El sistema restringe automáticamente a **un solo intento de corrección** antes de habilitar el rechazo definitivo.
        </p>
    </div>

    <!-- ========================================================================================================
    FILTRO POR CONVOCATORIA ACTIVA
    ========================================================================================================= -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-dark text-white border border-secondary shadow-sm">
                <div class="card-body p-3">
                    <label class="text-muted text-uppercase font-weight-bold mb-3 d-block" style="font-size: 0.8rem; letter-spacing: 1px;">Seleccione Convocatoria a Evaluar:</label>
                    <div class="d-flex flex-wrap" id="grupo-botores-convocatoria-verif">
                        <?php if (count($convocatoriasActivas) === 0): ?>
                            <p class="text-muted font-italic mb-0">No hay convocatorias activas para evaluar.</p>
                        <?php else: ?>
                            <?php foreach ($convocatoriasActivas as $key => $conv): ?>
                                <button type="button" 
                                        class="btn btn-outline-success font-weight-bold btn-convocatoria-verif m-1 <?php echo $key === 0 ? 'active' : ''; ?>"
                                        data-id-convocatoria="<?php echo $conv["id"]; ?>"
                                        data-apoyo="<?php echo $conv["descripcion_apoyo"]; ?>"
                                        style="border-radius: 4px; padding: 10px 16px; transition: all 0.3s ease;">
                                    <i class="<?php echo $conv["apoyo_icono"] ? $conv["apoyo_icono"] : "fas fa-hand-holding-heart"; ?> mr-2"></i>
                                    <?php echo $conv["descripcion_apoyo"]; ?>
                                </button>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================================================================
    TABLA PRINCIPAL DATATABLE
    ========================================================================================================= -->
    <div class="card bg-dark text-white border border-secondary shadow-lg">
      <div class="card-body p-4">
        
        <div class="table-responsive">
          <table id="tblDatos" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%; border-color: #4f5962 !important;">
            <thead style="background-color: #198754; color: white;">
              <tr id="headers-tblVerificacion">
                <th>T.I/C.C</th>
                <th>Nombres</th>
                <!-- Encabezados inyectados por JQuery -->
                <th>P</th>
              </tr>
            </thead>
            <tbody id="body-tblVerificacion">
              <!-- Filas inyectadas por JQuery -->
            </tbody>
          </table>
        </div>

      </div>
    </div>

  </div>
</section>

<!-- ========================================================================================================
VENTANA MODAL - EVALUACIÓN / REVISIÓN DEL DOCUMENTO POR GESTORA
========================================================================================================= -->
<div class="modal fade animate__animated animate__fadeIn" id="modal-evaluarDocumento" role="dialog" aria-labelledby="modalEvalLabel" aria-hidden="true" style="backdrop-filter: blur(4px);">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content bg-dark text-white border border-secondary shadow-lg">
      
      <div class="modal-header border-bottom border-secondary" style="background-color: #2b3035;">
        <h5 class="modal-title font-weight-bold" id="modalEvalLabel">
          <i class="fas fa-gavel text-success mr-2"></i>Panel de Calificación
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body p-4" style="background-color: #202427;">
        <form id="form-evaluarDoc">
          
          <!-- Metadatos ocultos -->
          <input type="hidden" id="eval-documento-id">
          <input type="hidden" id="eval-nombre-doc">
          
          <div class="row mb-3">
              <div class="col-12">
                  <span class="text-muted text-uppercase font-weight-bold d-block mb-1" style="font-size: 0.75rem;">Aprendiz Postulado:</span>
                  <h6 id="eval-txt-aprendiz" class="text-white font-weight-bold mb-0">Juan Camilo Castañeda Díaz</h6>
              </div>
          </div>

          <div class="row mb-3">
              <div class="col-12">
                  <span class="text-muted text-uppercase font-weight-bold d-block mb-1" style="font-size: 0.75rem;">Documento Requisito:</span>
                  <h6 id="eval-txt-requisito" class="text-white font-weight-bold mb-0">Sisbén o Puntaje Equivalente</h6>
              </div>
          </div>

          <div class="d-flex justify-content-between mb-4">
              <span class="badge badge-danger font-weight-bold px-3 py-2" id="eval-badge-obligatorio" style="border-radius: 4px;">CRÍTICO</span>
              <span class="badge badge-dark border border-secondary text-success font-weight-bold px-3 py-2" id="eval-badge-puntaje" style="border-radius: 4px;">Asigna: 15 pts</span>
          </div>

          <!-- ESTADO ACTUAL DEL DOCUMENTO -->
          <div class="alert p-3 mb-4 text-center border font-weight-bold shadow-sm" id="eval-alert-estado" style="font-size: 0.95rem; border-radius: 6px;">
              Estado del archivo
          </div>

          <!-- HISTORIAL DE INTENTOS DE CORRECCIÓN (SI TIENE) -->
          <div class="card border border-secondary bg-dark mb-4 d-none" id="eval-card-historial">
              <div class="card-header border-bottom border-secondary py-2" style="background-color: #2b3035;">
                  <h6 class="font-weight-bold mb-0 text-warning"><i class="fas fa-history mr-2"></i>Historial de Devoluciones Previas</h6>
              </div>
              <div class="card-body p-3" id="eval-contenedor-historial" style="max-height: 120px; overflow-y: auto; font-size: 0.85rem;">
                  <!-- Se inyectan las revisiones previas -->
              </div>
          </div>

          <!-- BOTÓN PARA VER ARCHIVO PDF ACTUAL -->
          <div class="form-group mb-4">
              <button type="button" class="btn btn-info btn-block font-weight-bold py-2 shadow-sm" id="eval-btn-ver-pdf">
                  <i class="fas fa-external-link-alt mr-2"></i> Abrir y Revisar Documento PDF
              </button>
          </div>


          <!-- GRUPO DE BOTONES DE ACCIÓN DE EVALUACIÓN -->
          <div class="form-group mb-0">
              <label class="font-weight-bold text-muted text-uppercase d-block mb-3" style="font-size: 0.75rem;">Acciones de Calificación:</label>
              <div class="row text-center">
                  <!-- APROBAR -->
                  <div class="col-4 px-1">
                      <button type="button" class="btn btn-success btn-block font-weight-bold py-3 shadow-sm btn-accion-eval" data-eval="APROBADO">
                          <i class="fas fa-check-circle fa-lg d-block mb-2"></i> Aprobar
                      </button>
                  </div>
                  <!-- PARA CORREGIR (1er Intento) -->
                  <div class="col-4 px-1">
                      <button type="button" class="btn btn-warning text-dark btn-block font-weight-bold py-3 shadow-sm btn-accion-eval" id="eval-btn-corregir" data-eval="PARA_CORREGIR">
                          <i class="fas fa-exclamation-circle fa-lg d-block mb-2"></i> Devolver
                      </button>
                  </div>
                  <!-- RECHAZAR DEFINITIVO (2do Intento) -->
                  <div class="col-4 px-1">
                      <button type="button" class="btn btn-danger btn-block font-weight-bold py-3 shadow-sm btn-accion-eval" id="eval-btn-rechazar" data-eval="RECHAZADO">
                          <i class="fas fa-times-circle fa-lg d-block mb-2"></i> Rechazar
                      </button>
                  </div>
              </div>
          </div>

        </form>
      </div>
      
      <div class="modal-footer border-top border-secondary" style="background-color: #2b3035;">
        <button type="button" class="btn btn-outline-light font-weight-bold px-4" data-dismiss="modal">Cerrar</button>
      </div>
      
    </div>
  </div>
</div>

<!-- ========================================================================================================
ESTILOS ADICIONALES PREMIUM
========================================================================================================= -->
<style>
    .btn-convocatoria-verif {
        border-color: #4f5962;
        color: #adb5bd;
        background-color: #2b3035;
    }
    .btn-convocatoria-verif:hover {
        border-color: #198754;
        color: #fff;
        background-color: #1f2326;
    }
    .btn-convocatoria-verif.active {
        border-color: #198754;
        background-color: #198754 !important;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(25, 135, 84, 0.3) !important;
    }

    /* Bordes e integración de la tabla */
    #tblDatos {
        border-collapse: collapse !important;
    }
    #tblDatos th {
        border: 1px solid #4f5962 !important;
        vertical-align: middle;
        text-align: center;
    }
    #tblDatos td {
        border: 1px solid #4f5962 !important;
        vertical-align: middle;
    }

    .btn-accion-eval {
        transition: all 0.2s ease;
        border-radius: 6px;
    }
    .btn-accion-eval:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4) !important;
    }
    
    .btn-eval-tabla {
        transition: all 0.2s ease;
    }
    .btn-eval-tabla:hover:not(:disabled) {
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(0,0,0,0.3) !important;
    }
</style>