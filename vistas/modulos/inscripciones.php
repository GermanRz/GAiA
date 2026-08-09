<?php

// 1. CONSULTAR INFORMACIÓN DE CONVOCATORIAS Y POSTULACIONES DESDE EL CONTROLADOR
$convocatoriasActivas = ControladorConvocatorias::ctrListarConvocatoriasActivas();
$misPostulaciones = ControladorInscripciones::ctrListarPostulacionesUsuario();

// 2. PRECALCULAR INDICADORES PARA LOS KPI (Key Performance Indicators) CARDS
$totalConvocatoriasAbiertas = count($convocatoriasActivas);
$totalMisPostulaciones = count($misPostulaciones);
$totalCorreccionesRequeridas = 0;

foreach ($misPostulaciones as $post) {
    // Verificar si tiene algún documento en estado "PARA_CORREGIR"
    $documentosPostulacion = ModeloInscripciones::mdlListarDocumentosInscripcion("inscripcion_documentos", $post["id"]);
    foreach ($documentosPostulacion as $doc) {
        if ($doc["estado"] == 'PARA_CORREGIR') {
            $totalCorreccionesRequeridas++;
            break; // Siguiente postulación
        }
    }
}

// Obtener estado real de cuenta del aprendiz
$datosUsuarioLogueado = ModeloUsuarios::mdlMostrarUsuarios("usuarios", "id", $_SESSION["id"]);
$estadoUsuarioStr = ($datosUsuarioLogueado && isset($datosUsuarioLogueado["estado"])) ? strtoupper($datosUsuarioLogueado["estado"]) : "ACTIVO";

?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="font-weight-bold text-light"><i class="fas fa-pencil-alt mr-2 text-success"></i>Inscripciones a Convocatorias</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="inicio" class="text-success"><i class="fas fa-home"></i> Inicio</a></li>
                    <li class="breadcrumb-item active text-muted">Inscripciones</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <!-- ========================================================================================================
        KPI WIDGETS / TARJETAS DE INDICADORES REALES
        ========================================================================================================= -->
        <div class="row">
            <!-- Convocatorias Abiertas -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box bg-dark shadow-sm border border-secondary">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-bullhorn"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-muted font-weight-bold text-uppercase" style="font-size: 0.75rem;">Convocatorias Abiertas</span>
                        <span class="info-box-number h4 font-weight-bold mb-0 text-white" id="kpi-abiertas"><?php echo $totalConvocatoriasAbiertas; ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Mis Postulaciones -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box bg-dark shadow-sm border border-secondary">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-file-signature"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-muted font-weight-bold text-uppercase" style="font-size: 0.75rem;">Mis Postulaciones</span>
                        <span class="info-box-number h4 font-weight-bold mb-0 text-white" id="kpi-postulaciones"><?php echo $totalMisPostulaciones; ?></span>
                    </div>
                </div>
            </div>

            <!-- Correcciones Requeridas -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box bg-dark shadow-sm border border-secondary">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-exclamation-triangle text-dark"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-muted font-weight-bold text-uppercase" style="font-size: 0.75rem;">Acciones Requeridas</span>
                        <span class="info-box-number h4 font-weight-bold mb-0 <?php echo $totalCorreccionesRequeridas > 0 ? 'text-warning' : 'text-white'; ?>" id="kpi-correcciones"><?php echo $totalCorreccionesRequeridas; ?></span>
                    </div>
                </div>
            </div>

            <!-- Estado de Aprendiz -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box bg-dark shadow-sm border border-secondary">
                    <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-user-graduate"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-muted font-weight-bold text-uppercase" style="font-size: 0.75rem;">Estado de Aprendiz</span>
                        <span class="info-box-number h4 font-weight-bold mb-0 <?php echo $estadoUsuarioStr == 'ACTIVO' ? 'text-success' : 'text-danger'; ?>" id="kpi-estado"><?php echo $estadoUsuarioStr; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================================================================
        PANEL PRINCIPAL CON TABS (LISTADO / HISTORIAL)
        ========================================================================================================= -->
        <div id="panel-listados" class="card card-dark card-tabs bg-dark border border-secondary shadow">
            <div class="card-header p-0 pt-1 border-bottom-0" style="background-color: #343a40;">
                <ul class="nav nav-tabs" id="tabInscripciones" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold text-uppercase" id="tab-convocatorias-tab" data-toggle="pill" href="#tab-convocatorias" role="tab" aria-controls="tab-convocatorias" aria-selected="true" style="padding: 12px 20px;">
                            <i class="fas fa-bullhorn mr-2 text-success"></i> Convocatorias Disponibles
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold text-uppercase" id="tab-postulaciones-tab" data-toggle="pill" href="#tab-postulaciones" role="tab" aria-controls="tab-postulaciones" aria-selected="false" style="padding: 12px 20px;">
                            <i class="fas fa-history mr-2 text-info"></i> Mis Postulaciones 
                            <span class="badge badge-warning text-dark ml-2 px-2 py-1 font-weight-bold" id="badge-contador-postulaciones" style="border-radius: 10px;"><?php echo $totalMisPostulaciones; ?></span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="card-body" style="background-color: #2b3035;">
                <div class="tab-content" id="tabInscripcionesContent">
                    
                    <!-- TAB 1: CONVOCATORIAS DISPONIBLES (RENDERIZADAS DESDE BD) -->
                    <div class="tab-pane fade show active" id="tab-convocatorias" role="tabpanel" aria-labelledby="tab-convocatorias-tab">
                        
                        <!-- Mensaje de bienvenida / guía rápida -->
                        <div class="alert alert-dismissible bg-dark border border-secondary text-light mb-4 shadow-sm" style="border-left: 5px solid #198754 !important;">
                            <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5 class="font-weight-bold text-success"><i class="icon fas fa-info-circle mr-2"></i>Bienvenido, <?php echo $_SESSION["nombres"] . " " . $_SESSION["apellidos"]; ?></h5>
                            <p class="mb-0" style="font-size: 0.95rem;">
                                Selecciona una de las convocatorias abiertas de tu centro de formación. Presiona <strong>"Iniciar Postulación"</strong> para cargar tus documentos. Recuerda que todos los archivos deben estar en formato <strong>PDF</strong> y pesar como máximo <strong>2 MB</strong>.
                            </p>
                        </div>

                        <!-- Grid de Convocatorias -->
                        <div class="row" id="contenedor-grid-convocatorias">
                            
                            <?php if (count($convocatoriasActivas) === 0): ?>
                                <div class="col-12 text-center py-5 text-muted">
                                    <i class="fas fa-folder-open fa-4x mb-3 text-secondary"></i>
                                    <h5>No hay convocatorias abiertas en este momento.</h5>
                                    <p>Por favor revise más tarde o consulte con bienestar de su sede.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($convocatoriasActivas as $conv): 
                                    
                                    // Formatear fechas
                                    $fechaInicio = date('d/M/Y', strtotime($conv["fecha_inicio"]));
                                    $fechaFin = date('d/M/Y', strtotime($conv["fecha_fin"]));
                                    $rangoFechas = $fechaInicio . " - " . $fechaFin;

                                    // Contar requisitos del baremo
                                    $criteriosBaremo = ControladorConvocatorias::ctrMostrarBaremo($conv["id"]);
                                    $totalRequisitos = count($criteriosBaremo);

                                    // Verificar si tiene postulación en base de datos
                                    $postulacion = ModeloInscripciones::mdlMostrarInscripcionUsuario("inscripciones", $_SESSION["id"], $conv["id"]);
                                    
                                    // Determinar estados y estilo de botones
                                    $btnClass = "btn-success";
                                    $btnText = "Iniciar Postulación";
                                    $btnIcon = "fas fa-file-signature";
                                    $isNuevo = "true";

                                    if ($postulacion) {
                                        $isNuevo = "false";
                                        // Buscar si requiere corrección
                                        $docsCargados = ModeloInscripciones::mdlListarDocumentosInscripcion("inscripcion_documentos", $postulacion["id"]);
                                        $tieneCorreccion = false;
                                        foreach ($docsCargados as $d) {
                                            if ($d["estado"] == 'PARA_CORREGIR') {
                                                $tieneCorreccion = true;
                                                break;
                                            }
                                        }

                                        if ($tieneCorreccion) {
                                            $btnClass = "btn-warning text-dark";
                                            $btnText = "Corregir Postulación";
                                            $btnIcon = "fas fa-exclamation-triangle";
                                        } else {
                                            $btnClass = "btn-outline-info";
                                            $btnText = "Ver Postulación";
                                            $btnIcon = "fas fa-eye";
                                        }
                                    }

                                    // Determinar color de borde izquierdo por tipo
                                    $borderColor = "#28a745"; // Verde por defecto
                                    if (stripos($conv["descripcion_apoyo"], "transporte") !== false) {
                                        $borderColor = "#007bff"; // Azul
                                    } else if (stripos($conv["descripcion_apoyo"], "sostenimiento") !== false || $conv["apoyo_id"] == 1) {
                                        $borderColor = "#17a2b8"; // Celeste
                                    }
                                ?>
                                    <div class="col-md-6 col-lg-4 mb-4 animate__animated animate__fadeIn">
                                        <div class="card bg-dark text-white border border-secondary h-100 shadow-sm card-convocatoria" style="border-left: 5px solid <?php echo $borderColor; ?> !important; transition: transform 0.2s ease; cursor: pointer;">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <span class="p-3 rounded mr-3 shadow-sm text-white" style="background-color: <?php echo $borderColor; ?>;"><i class="<?php echo $conv["apoyo_icono"] ? $conv["apoyo_icono"] : "fas fa-hand-holding-heart"; ?> fa-lg"></i></span>
                                                        <div>
                                                            <h5 class="card-title font-weight-bold mb-0 text-white" style="line-height: 1.2;"><?php echo $conv["descripcion_apoyo"]; ?></h5>
                                                            <small class="font-weight-bold" style="color: <?php echo $borderColor; ?>;">CONVOCATORIA ABIERTA</small>
                                                        </div>
                                                    </div>
                                                    <span class="badge px-2 py-1 shadow-sm font-weight-bold text-uppercase text-white" style="background-color: <?php echo $borderColor; ?>; font-size: 0.75rem;">Abierta</span>
                                                </div>
                                                
                                                <p class="card-text text-muted mb-4" style="font-size: 0.9rem; line-height: 1.5; height: 55px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                                    <?php echo $conv["informacion_apoyo"] ? $conv["informacion_apoyo"] : "Apoyo institucional regulado por el baremo y requisitos establecidos."; ?>
                                                </p>

                                                <div class="border-top border-secondary pt-3 mt-3" style="font-size: 0.85rem;">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-muted"><i class="far fa-calendar-alt mr-2"></i>Período:</span>
                                                        <span class="font-weight-bold text-white"><?php echo $rangoFechas; ?></span>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-muted"><i class="fas fa-users mr-2"></i>Cupos Disponibles:</span>
                                                        <span class="font-weight-bold text-white"><?php echo $conv["cupos_personas"]; ?> personas</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-muted"><i class="fas fa-clipboard-check mr-2"></i>Requisitos Baremo:</span>
                                                        <span class="badge badge-warning text-dark font-weight-bold px-2"><?php echo $totalRequisitos; ?> Criterios</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="card-footer bg-transparent border-top border-secondary p-3">
                                                <button class="btn <?php echo $btnClass; ?> btn-block font-weight-bold btn-iniciar-inscripcion" 
                                                        data-id-convocatoria="<?php echo $conv["id"]; ?>"
                                                        data-apoyo="<?php echo $conv["descripcion_apoyo"]; ?>" 
                                                        data-fechas="<?php echo $rangoFechas; ?>"
                                                        data-is-nuevo="<?php echo $isNuevo; ?>">
                                                    <i class="<?php echo $btnIcon; ?> mr-2"></i> <?php echo $btnText; ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </div>
                    </div>

                    <!-- TAB 2: MIS POSTULACIONES (TRAÍDAS DESDE LA BASE DE DATOS) -->
                    <div class="tab-pane fade" id="tab-postulaciones" role="tabpanel" aria-labelledby="tab-postulaciones-tab">
                        
                        <div class="table-responsive">
                            <table id="tblMisPostulaciones" class="table table-dark table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                <thead style="background-color: #198754; color: white;">
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 25%">Apoyo Solicitado</th>
                                        <th style="width: 15%">Fecha Registro</th>
                                        <th style="width: 10%">Puntaje</th>
                                        <th style="width: 15%">Estado Actual</th>
                                        <th style="width: 20%">Observaciones</th>
                                        <th style="width: 10%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($misPostulaciones) === 0): ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                No tienes postulaciones registradas en este período.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($misPostulaciones as $key => $post): 
                                            
                                            $fechaPost = date('d/M/Y', strtotime($post["fecha_postulacion"]));
                                            
                                            // Recopilar documentos cargados para ver si hay errores de validación en curso
                                            $docs = ModeloInscripciones::mdlListarDocumentosInscripcion("inscripcion_documentos", $post["id"]);
                                            $estadoVisualBadge = "";
                                            $obsTexto = "En espera de validación de documentos.";
                                            $tieneCorrecciones = false;
                                            
                                            foreach ($docs as $d) {
                                                if ($d["estado"] == 'PARA_CORREGIR') {
                                                    $tieneCorrecciones = true;
                                                    $obsTexto = "Corrección requerida: \"" . $d["observacion_gestora"] . "\"";
                                                    break;
                                                }
                                            }

                                            // Definir Badge visual
                                            if ($tieneCorrecciones) {
                                                $estadoVisualBadge = '<span class="badge badge-warning text-dark font-weight-bold px-2 py-1" style="border-radius:4px;"><i class="fas fa-exclamation-triangle mr-1"></i> Corrección Requerida</span>';
                                            } else {
                                                switch ($post["estado"]) {
                                                    case 'PENDIENTE':
                                                        $estadoVisualBadge = '<span class="badge badge-info font-weight-bold px-2 py-1" style="border-radius:4px;"><i class="fas fa-spinner fa-spin mr-1"></i> En Verificación</span>';
                                                        $obsTexto = "Documentos cargados. El gestor asignado está revisando tu baremo.";
                                                        break;
                                                    case 'SELECCIONADO':
                                                        $estadoVisualBadge = '<span class="badge badge-primary font-weight-bold px-2 py-1" style="border-radius:4px;"><i class="fas fa-user-check mr-1"></i> Seleccionado</span>';
                                                        $obsTexto = "¡Felicidades! Has sido seleccionado en el baremo preliminar.";
                                                        break;
                                                    case 'BENEFICIADO':
                                                        $estadoVisualBadge = '<span class="badge badge-success font-weight-bold px-2 py-1" style="border-radius:4px;"><i class="fas fa-check-double mr-1"></i> Beneficiado</span>';
                                                        $obsTexto = "¡Asignado! El beneficio ha sido aprobado de manera oficial.";
                                                        break;
                                                    case 'BENEFICIADO_PENDIENTE_DOC':
                                                        $estadoVisualBadge = '<span class="badge badge-warning text-dark font-weight-bold px-2 py-1" style="border-radius:4px;"><i class="fas fa-file-invoice-dollar mr-1"></i> Falta Doc Bancario</span>';
                                                        // Si hay observación de rechazo, mostrarla
                                                        if (!empty($post["observacion_rechazo_financiera"])) {
                                                            $obsTexto = "<strong>Rechazado por Financiera:</strong> " . $post["observacion_rechazo_financiera"];
                                                        } else {
                                                            $obsTexto = "Por favor, carga tu certificación bancaria para programar los desembolsos.";
                                                        }
                                                        break;
                                                    case 'DOCUMENTO_BANCARIO_CARGADO':
                                                        $estadoVisualBadge = '<span class="badge badge-info font-weight-bold px-2 py-1" style="border-radius:4px;"><i class="fas fa-search-dollar mr-1"></i> En Revisión</span>';
                                                        $obsTexto = "Tu documento bancario está siendo validado por Financiera.";
                                                        break;
                                                    case 'APROBADO_FINANCIERA':
                                                        $estadoVisualBadge = '<span class="badge badge-success font-weight-bold px-2 py-1" style="border-radius:4px;"><i class="fas fa-hand-holding-usd mr-1"></i> Aprobado Financiera</span>';
                                                        $obsTexto = "Tus desembolsos están oficialmente programados.";
                                                        break;
                                                    case 'RETIRADO':
                                                        $estadoVisualBadge = '<span class="badge badge-danger font-weight-bold px-2 py-1" style="border-radius:4px;"><i class="fas fa-user-times mr-1"></i> Retirado</span>';
                                                        $obsTexto = "Solicitud retirada del sistema.";
                                                        break;
                                                    default:
                                                        $estadoVisualBadge = '<span class="badge badge-secondary font-weight-bold px-2 py-1" style="border-radius:4px;">' . $post["estado"] . '</span>';
                                                        break;
                                                }
                                            }

                                            // Formatear fechas de convocatoria asociada para el JS
                                            $rangoConvocatoria = date('d/M/Y', strtotime($post["fecha_inicio"])) . " - " . date('d/M/Y', strtotime($post["fecha_fin"]));
                                        ?>
                                            <tr>
                                                <td><?php echo ($key + 1); ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="mr-2 text-info" style="font-size: 1.1rem;"><i class="<?php echo $post["apoyo_icono"] ? $post["apoyo_icono"] : "fas fa-hand-holding-heart"; ?>"></i></span>
                                                        <span class="font-weight-bold"><?php echo $post["descripcion_apoyo"]; ?></span>
                                                    </div>
                                                </td>
                                                <td><?php echo $fechaPost; ?></td>
                                                <td class="font-weight-bold text-success"><?php echo $post["puntaje_total"]; ?> pts</td>
                                                <td><?php echo $estadoVisualBadge; ?></td>
                                                <td class="font-italic text-muted" style="font-size: 0.88rem; max-width: 250px; overflow: hidden; text-truncate: ellipsis;">
                                                    <?php echo $obsTexto; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($tieneCorrecciones || $post["estado"] == 'PENDIENTE'): ?>
                                                        <button class="btn btn-sm <?php echo $tieneCorrecciones ? 'btn-outline-warning text-warning' : 'btn-outline-info text-info'; ?> btn-editar-postulacion-tabla" 
                                                                data-id-convocatoria="<?php echo $post["convocatoria_id"]; ?>"
                                                                data-apoyo="<?php echo $post["descripcion_apoyo"]; ?>" 
                                                                data-fechas="<?php echo $rangoConvocatoria; ?>"
                                                                title="Subir / Corregir Archivos">
                                                            <i class="fas fa-upload mr-1"></i> <?php echo $tieneCorrecciones ? 'Corregir' : 'Ver / Cargar'; ?>
                                                        </button>
                                                    <?php elseif ($post["estado"] == 'BENEFICIADO_PENDIENTE_DOC'): ?>
                                                        <button class="btn btn-sm btn-outline-warning text-warning btn-cargar-banco" 
                                                                data-id-inscripcion="<?php echo $post["id"]; ?>"
                                                                data-apoyo="<?php echo $post["descripcion_apoyo"]; ?>"
                                                                title="Cargar Certificación Bancaria"
                                                                data-toggle="modal" data-target="#modalCargarBanco">
                                                            <i class="fas fa-upload mr-1"></i> Cargar Doc
                                                        </button>
                                                    <?php else: ?>
                                                        <span class="text-muted" style="font-size: 0.85rem;"><i class="fas fa-lock mr-1"></i> Bloqueado</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- ========================================================================================================
        PANEL DE CARGA DE DOCUMENTOS (DETALLES + WIZARD DE ARCHIVOS)
        ========================================================================================================= -->
        <div id="panel-carga-documentos" class="card bg-dark border border-secondary shadow mb-4 d-none">
            <div class="card-header border-bottom border-secondary d-flex align-items-center py-3" style="background-color: #343a40;">
                <button type="button" class="btn btn-outline-light btn-sm mr-3 btn-regresar-listado">
                    <i class="fas fa-chevron-left mr-1"></i> Volver a Convocatorias
                </button>
                <h4 class="modal-title font-weight-bold text-white mb-0" id="titulo-panel-apoyo">Carga de Documentos</h4>
            </div>

            <div class="card-body bg-dark p-4">
                
                <!-- Resumen de Convocatoria Seleccionada -->
                <div class="bg-secondary rounded p-3 mb-4 border border-secondary shadow-sm" style="background-color: #2b3035 !important;">
                    <div class="row align-items-center">
                        <div class="col-md-9 mb-2 mb-md-0">
                            <h6 class="font-weight-bold text-success mb-1"><i class="fas fa-info-circle mr-1"></i> Instrucciones de Postulación e Integridad</h6>
                            <p class="text-light mb-0" style="font-size: 0.9rem;">
                                Sube documentos en formato <strong>PDF</strong> de manera individual por cada fila. El sistema creará tu carpeta personal <strong>documentos/<?php echo $_SESSION["documento"]; ?>/</strong> y renombrará los archivos para su control de manera automática. Peso límite: <strong>2 MB</strong>.
                            </p>
                        </div>
                        <div class="col-md-3 text-md-right">
                            <span class="text-muted d-block" style="font-size: 0.8rem;">Fecha Límite</span>
                            <span class="badge badge-danger font-weight-bold px-3 py-2" id="badge-fecha-limite" style="font-size: 0.9rem;">--/--/----</span>
                        </div>
                    </div>
                </div>

                <!-- Contenedor Dinámico de Requisitos (cargados por AJAX en inscripciones.js) -->
                <div id="contenedor-requisitos-carga">
                    <!-- Las filas se inyectan dinámicamente mediante jQuery -->
                </div>

            </div>

            <!-- Botones de Acción de Envío -->
            <div class="card-footer border-top border-secondary justify-content-between d-flex p-3" style="background-color: #343a40;">
                <button type="button" class="btn btn-outline-light font-weight-bold btn-regresar-listado">Cancelar</button>
                <div>
                    <button type="button" class="btn btn-secondary mr-2 font-weight-bold" id="btn-guardar-borrador-sim">
                        <i class="fas fa-save mr-1"></i> Guardar Borrador
                    </button>
                    <button type="button" class="btn btn-success font-weight-bold" id="btn-enviar-postulacion-sim">
                        <i class="fas fa-rocket mr-1"></i> Enviar Postulación
                    </button>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ========================================================================================================
MODAL CARGA CERTIFICACIÓN BANCARIA
========================================================================================================= -->
<div class="modal fade" id="modalCargarBanco" tabindex="-1" role="dialog" aria-labelledby="modalCargarBancoLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content bg-dark border-secondary">
            <form role="form" id="frmCargarBanco" enctype="multipart/form-data">
                <div class="modal-header border-bottom border-secondary" style="background-color: #343a40;">
                    <h5 class="modal-title font-weight-bold text-white" id="modalCargarBancoLabel">
                        <i class="fas fa-file-invoice-dollar mr-2 text-success"></i>Cargar Certificación Bancaria
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4" style="background-color: #2b3035;">
                    <input type="hidden" id="id_inscripcion_banco" name="id_inscripcion_banco">
                    
                    <div class="alert alert-info border border-info p-2 mb-4" style="font-size: 0.85rem; border-radius: 8px;">
                        <i class="fas fa-info-circle mr-1"></i> Recuerde que la cuenta bancaria debe estar a su nombre y el archivo PDF no puede pesar más de 2MB.
                    </div>

                    <div class="form-group mb-3">
                        <label class="text-light font-weight-bold"><i class="fas fa-university mr-1"></i> Entidad Bancaria <span class="text-danger">*</span></label>
                        <select class="form-control bg-dark text-white border-secondary select2" id="entidad_bancaria" name="entidad_bancaria" required>
                            <option value="">Seleccione una entidad...</option>
                            <option value="Bancolombia">Bancolombia</option>
                            <option value="Nequi">Nequi</option>
                            <option value="Davivienda">Davivienda</option>
                            <option value="Daviplata">Daviplata</option>
                            <option value="Banco de Bogotá">Banco de Bogotá</option>
                            <option value="BBVA">BBVA</option>
                            <option value="Banco Popular">Banco Popular</option>
                            <option value="Banco de Occidente">Banco de Occidente</option>
                            <option value="Banco AV Villas">Banco AV Villas</option>
                            <option value="Banco Agrario">Banco Agrario</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="text-light font-weight-bold"><i class="fas fa-hashtag mr-1"></i> Número de Cuenta <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-dark text-white border-secondary input-numero-cuenta" id="numero_cuenta" name="numero_cuenta" placeholder="Ingrese sin guiones ni espacios" required autocomplete="off">
                    </div>

                    <div class="form-group mb-4">
                        <label class="text-light font-weight-bold"><i class="fas fa-check-double mr-1"></i> Confirmar Número de Cuenta <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-dark text-white border-secondary input-numero-cuenta" id="confirmar_cuenta" name="confirmar_cuenta" placeholder="Vuelva a ingresar el número" required autocomplete="off">
                        <small class="text-muted">No se permite copiar y pegar en este campo.</small>
                    </div>

                    <div class="form-group mb-2">
                        <label class="text-light font-weight-bold"><i class="fas fa-file-pdf mr-1"></i> Certificación (PDF) <span class="text-danger">*</span></label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input cursor-pointer" id="certificacion_pdf" name="certificacion_pdf" accept="application/pdf">
                            <label class="custom-file-label bg-dark text-white border-secondary" for="certificacion_pdf" data-browse="Buscar">Seleccionar Archivo</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary" style="background-color: #343a40;">
                    <button type="button" class="btn btn-outline-light font-weight-bold" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success font-weight-bold btn-guardar-banco">
                        <i class="fas fa-save mr-1"></i> Guardar y Enviar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================================================
TEMPLATES HTML
========================================================================================================= -->

<!-- Fila de Requisito para Documento -->
<template id="template-requisito-fila">
    <div class="card border border-secondary bg-dark mb-3 card-requisito" data-estado="pendiente" style="border-radius: 6px; transition: border 0.3s ease;">
        <div class="card-body p-3">
            <div class="row align-items-center">
                
                <!-- Info del Requisito -->
                <div class="col-md-5 mb-3 mb-md-0">
                    <div class="d-flex align-items-start">
                        <span class="indicator-icon bg-secondary text-white p-2 rounded mr-3 shadow-sm" style="width: 40px; text-align: center;">
                            <i class="fas fa-file-pdf"></i>
                        </span>
                        <div>
                            <h6 class="font-weight-bold text-white mb-1 doc-nombre">Nombre del Documento</h6>
                            <p class="text-muted mb-0 doc-desc" style="font-size: 0.8rem; line-height: 1.3;">
                                Requisito documental crítico según el baremo de la convocatoria seleccionada.
                            </p>
                            <div class="mt-2">
                                <span class="badge badge-danger font-weight-bold mr-1 badge-obligatoriedad" style="font-size: 0.7rem; padding: 4px 6px;">CRÍTICO</span>
                                <span class="badge badge-dark text-muted border border-secondary font-weight-bold badge-puntaje" style="font-size: 0.7rem; padding: 3px 5px;">Puntaje</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estado Visual e Indicador del archivo -->
                <div class="col-md-4 mb-3 mb-md-0 text-md-center container-estado">
                    <!-- Estado 1: Pendiente de Subida -->
                    <div class="estado-pendiente">
                        <span class="badge badge-secondary px-3 py-2 font-weight-bold text-muted shadow-sm border border-secondary" style="border-radius: 12px; font-size: 0.8rem;">
                            <i class="fas fa-cloud-upload-alt mr-1"></i> Pendiente de Cargar
                        </span>
                    </div>

                    <!-- Estado 2: Archivo Cargado -->
                    <div class="estado-cargado d-none">
                        <div class="alert alert-success border border-success p-2 mb-0 d-inline-flex align-items-center shadow-sm text-left" style="border-radius: 8px; font-size: 0.85rem; max-width: 100%;">
                            <i class="fas fa-check-circle fa-lg mr-2 text-success"></i>
                            <div class="text-truncate" style="max-width: 250px;">
                                <strong class="text-white d-block text-truncate nombre-archivo-cargado">mi_documento.pdf</strong>
                                <small class="text-muted d-block size-archivo-cargado">Subido con éxito</small>
                            </div>
                        </div>
                    </div>

                    <!-- Estado 3: Observación de Rechazo -->
                    <div class="estado-rechazado d-none">
                        <div class="alert alert-danger border border-danger p-2 mb-0 text-left shadow-sm" style="border-radius: 8px; font-size: 0.85rem;">
                            <div class="d-flex align-items-center mb-1">
                                <i class="fas fa-times-circle fa-lg mr-2 text-danger"></i>
                                <strong class="text-white">Documento Rechazado</strong>
                            </div>
                            <small class="text-white font-italic text-obs-rechazo">"El archivo no es legible."</small>
                        </div>
                    </div>
                </div>

                <!-- Widget de Subida / Acciones -->
                <div class="col-md-3 text-right">
                    
                    <!-- Formulario de carga de archivo -->
                    <div class="zona-subida-archivo">
                        <div class="dropzone-mock">
                            <i class="fas fa-cloud-upload-alt fa-lg text-success mb-1"></i>
                            <span class="d-block text-muted font-weight-bold" style="font-size: 0.75rem;">Buscar PDF</span>
                            <small class="text-muted" style="font-size: 0.65rem;">PDF menor a 5MB</small>
                            <input type="file" class="file-uploader-input" accept="application/pdf">
                        </div>
                        
                        <!-- Barra de progreso (Funciona con XHR real en ajax) -->
                        <div class="progress progress-xxs mt-2 d-none progress-simulada" style="height: 6px; border-radius: 3px;">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Acciones cuando está cargado -->
                    <div class="acciones-archivo-cargado d-none">
                        <div class="btn-group shadow-sm">
                            <button type="button" class="btn btn-sm btn-info btn-ver-pdf-sim" title="Visualizar PDF">
                                <i class="fas fa-eye mr-1"></i> Ver PDF
                            </button>
                            <button type="button" class="btn btn-sm btn-danger btn-eliminar-pdf-sim" title="Eliminar archivo del servidor">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>
</template>

<!-- ========================================================================================================
ESTILOS ADICIONALES LOCALES PARA LA MAQUETACIÓN PREMIUM
========================================================================================================= -->
<style>
    .dropzone-mock {
        border: 2px dashed #6c757d;
        border-radius: 6px;
        background-color: #343a40;
        padding: 12px 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .dropzone-mock:hover {
        border-color: #28a745;
        background-color: #3d454d;
        box-shadow: 0 0 8px rgba(40, 167, 69, 0.2);
    }
    
    .dropzone-mock input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 10;
    }

    .card-convocatoria:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4) !important;
        border-color: #6c757d !important;
    }

    .card-requisito {
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card-requisito:hover {
        border-color: #4f5962 !important;
    }

    .card-dark.card-tabs .nav-tabs .nav-link.active {
        background-color: #2b3035 !important;
        border-color: #6c757d #6c757d transparent !important;
        color: #fff !important;
    }
    
    .card-dark.card-tabs .nav-tabs .nav-link {
        color: #adb5bd;
        border-top: 3px solid transparent;
    }

    .card-dark.card-tabs .nav-tabs .nav-link:hover {
        color: #fff;
        border-top-color: #6c757d;
        background-color: #343a40;
    }
    
    #tblMisPostulaciones_wrapper .dataTables_length, 
    #tblMisPostulaciones_wrapper .dataTables_filter, 
    #tblMisPostulaciones_wrapper .dataTables_info,
    #tblMisPostulaciones_wrapper .dataTables_paginate {
        color: #adb5bd !important;
        font-size: 0.9rem;
        margin-top: 10px;
    }
</style>
