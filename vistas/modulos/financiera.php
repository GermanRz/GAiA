<?php
// Obtener convocatorias que tienen asignaciones
$convocatoriasActivas = ControladorFinanciera::ctrListarConvocatoriasFinanciera();

// Obtener pendientes bancarios
$pendientesBancarios = ControladorFinanciera::ctrListarPendientesBancarios();
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Financiera</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
                    <li class="breadcrumb-item active">Financiera</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">

    <!-- PESTAÑAS (TABS) AL ESTILO INSCRIPCIONES -->
    <div id="panel-financiera" class="card card-dark card-tabs bg-dark border border-secondary shadow">
        <div class="card-header p-0 pt-1 border-bottom-0" style="background-color: #343a40;">
            <ul class="nav nav-tabs" id="tabFinanciera" role="tablist">
                <!-- PESTAÑA FIJA PARA REVISIÓN BANCARIA -->
                <li class="nav-item">
                    <a class="nav-link active font-weight-bold text-uppercase" id="tab-revision-bancaria-tab"
                        data-toggle="pill" href="#tab-revision-bancaria" role="tab"
                        aria-controls="tab-revision-bancaria" aria-selected="true" style="padding: 12px 20px;">
                        <i class="fas fa-file-invoice-dollar mr-2 text-warning"></i>
                        Revisión Bancaria
                        <?php if (count($pendientesBancarios) > 0): ?>
                            <span class="badge badge-danger ml-1"><?php echo count($pendientesBancarios); ?></span>
                        <?php endif; ?>
                    </a>
                </li>

                <!-- PESTAÑAS DINÁMICAS POR CONVOCATORIA (BENEFICIARIOS) -->
                <?php foreach ($convocatoriasActivas as $key => $conv): ?>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold text-uppercase" id="tab-conv-<?php echo $conv['id']; ?>-tab"
                            data-toggle="pill" href="#tab-conv-<?php echo $conv['id']; ?>" role="tab"
                            aria-controls="tab-conv-<?php echo $conv['id']; ?>" aria-selected="false"
                            style="padding: 12px 20px;">
                            <i
                                class="<?php echo $conv["apoyo_icono"] ? $conv["apoyo_icono"] : "fas fa-hand-holding-heart"; ?> mr-2 text-success"></i>
                            <?php echo $conv["descripcion_apoyo"]; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="card-body" style="background-color: #2b3035;">
            <div class="tab-content" id="tabFinancieraContent">

                <!-- CONTENIDO PESTAÑA FIJA REVISIÓN BANCARIA -->
                <div class="tab-pane fade show active" id="tab-revision-bancaria" role="tabpanel"
                    aria-labelledby="tab-revision-bancaria-tab">

                    <h3 class="mb-4 text-white">Documentos Bancarios Pendientes de Revisión</h3>

                    <div class="table-responsive">
                        <table
                            class="table table-dark table-striped table-bordered dt-responsive nowrap tabla-beneficiarios"
                            style="width:100%">
                            <thead style="background-color: #ffc107; color: black;">
                                <tr>
                                    <th>Identificación</th>
                                    <th>Aprendiz</th>
                                    <th>Programa</th>
                                    <th>Apoyo</th>
                                    <th>Banco</th>
                                    <th>Cuenta</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($pendientesBancarios) > 0): ?>
                                    <?php foreach ($pendientesBancarios as $pend): ?>
                                        <tr>
                                            <td><?php echo $pend["identificacion"]; ?></td>
                                            <td><?php echo $pend["aprendiz"]; ?></td>
                                            <td><?php echo $pend["programa_formacion"]; ?></td>
                                            <td><?php echo $pend["descripcion_apoyo"]; ?></td>
                                            <td><?php echo $pend["banco"]; ?></td>
                                            <td><?php echo $pend["numero_cuenta"]; ?></td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="<?php echo $pend['documento_bancario_url']; ?>" target="_blank"
                                                        class="btn btn-info btn-sm" title="Ver Documento">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button class="btn btn-success btn-sm btn-aprobar-banco"
                                                        data-id-inscripcion="<?php echo $pend['inscripcion_id']; ?>"
                                                        title="Aprobar y Crear Asignación">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm btn-rechazar-banco"
                                                        data-id-inscripcion="<?php echo $pend['inscripcion_id']; ?>"
                                                        title="Devolver al Aprendiz">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php if (count($convocatoriasActivas) === 0): ?>
                    <!-- Opcional: mostrar algo si no hay convocatorias activas -->
                <?php else: ?>
                    <?php foreach ($convocatoriasActivas as $key => $conv):
                        // Obtener los beneficiarios de esta convocatoria usando el nuevo controlador
                        $beneficiarios = ControladorFinanciera::ctrMostrarBeneficiarios($conv["id"]);
                        ?>
                        <div class="tab-pane fade" id="tab-conv-<?php echo $conv['id']; ?>" role="tabpanel"
                            aria-labelledby="tab-conv-<?php echo $conv['id']; ?>-tab">

                            <h3 class="mb-4 text-white"><?php echo $conv["descripcion_apoyo"]; ?></h3>

                            <div class="table-responsive">
                                <table id="tblBeneficiarios_<?php echo $conv['id']; ?>"
                                    class="table table-dark table-striped table-bordered dt-responsive nowrap tabla-beneficiarios"
                                    style="width:100%">
                                    <thead style="background-color: #198754; color: white;">
                                        <tr>
                                            <th>Identificación</th>
                                            <th>Aprendiz</th>
                                            <th>Ficha</th>
                                            <th>Programa</th>
                                            <th>Meses</th>
                                            <th>Inicio Pago</th>
                                            <th>Fin Pago</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($beneficiarios as $key => $ben): ?>
                                            <tr>
                                                <td><?php echo $ben["identificacion"]; ?></td>
                                                <td><?php echo $ben["aprendiz"]; ?></td>
                                                <td><?php echo $ben["codigo_ficha"]; ?></td>
                                                <td><?php echo $ben["programa_formacion"]; ?></td>
                                                <td><?php echo $ben["meses_beneficio"]; ?></td>
                                                <td><?php echo $ben["fecha_inicio_pago"]; ?></td>
                                                <td><?php echo $ben["fecha_fin_pago"]; ?></td>
                                                <td class="text-center">
                                                    <?php
                                                    $estadoClass = "btn-success";
                                                    if (strtoupper($ben["estado_asignacion"]) == "PENDIENTE") {
                                                        $estadoClass = "btn-warning text-dark";
                                                    } else if (strtoupper($ben["estado_asignacion"]) == "CANCELADO" || strtoupper($ben["estado_asignacion"]) == "RECHAZADO") {
                                                        $estadoClass = "btn-danger";
                                                    }
                                                    ?>
                                                    <button
                                                        class="btn <?php echo $estadoClass; ?> btn-sm"><?php echo $ben["estado_asignacion"]; ?></button>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <button class="btn btn-sm btn-outline-light btnFormatoTerceros mr-1"
                                                            data-idInscripcion="<?php echo $ben["inscripcion_id"]; ?>"
                                                            data-toggle="modal" data-target="#modal-formatoTerceros"
                                                            title="Formato de Terceros">
                                                            <i class="fas fa-id-card"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-light btnValoresComprometer mr-1"
                                                            data-idInscripcion="<?php echo $ben["inscripcion_id"]; ?>"
                                                            data-toggle="modal" data-target="#modal-valoresComprometer"
                                                            title="Valores a Comprometer">
                                                            <i class="fas fa-dollar-sign"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-light btnDescargarPdf"
                                                            data-identificacion="<?php echo $ben["identificacion"]; ?>"
                                                            data-aprendiz="<?php echo $ben["aprendiz"]; ?>"
                                                            data-ficha="<?php echo $ben["codigo_ficha"]; ?>"
                                                            data-programa="<?php echo $ben["programa_formacion"]; ?>"
                                                            data-meses="<?php echo $ben["meses_beneficio"]; ?>"
                                                            data-inicio="<?php echo $ben["fecha_inicio_pago"]; ?>"
                                                            data-fin="<?php echo $ben["fecha_fin_pago"]; ?>"
                                                            data-estado="<?php echo $ben["estado_asignacion"]; ?>"
                                                            data-apoyo="<?php echo $conv["descripcion_apoyo"]; ?>"
                                                            title="Descargar PDF">
                                                            <i class="fas fa-file-pdf"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<!-- MODAL FORMATO DE TERCEROS -->
<div class="modal fade" id="modal-formatoTerceros" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header" style="background-color: #343a40;">
                <h4 class="modal-title font-weight-bold"><i class="fas fa-id-card mr-2 text-success"></i> Formato de
                    Terceros</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background-color: #454d55 !important;">
                <div class="row">
                    <!-- Nombres -->
                    <div class="col-md-4 form-group">
                        <label>Nombres</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" id="terNombres" class="form-control" readonly>
                        </div>
                    </div>
                    <!-- Apellidos -->
                    <div class="col-md-4 form-group">
                        <label>Apellidos</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" id="terApellidos" class="form-control" readonly>
                        </div>
                    </div>
                    <!-- Tipo de Documento -->
                    <div class="col-md-2 form-group">
                        <label>Tipo Doc.</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            </div>
                            <input type="text" id="terTipoDocumento" class="form-control" readonly>
                        </div>
                    </div>
                    <!-- Número de Documento -->
                    <div class="col-md-2 form-group">
                        <label>Nro. Documento</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            </div>
                            <input type="text" id="terNumeroDocumento" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Correo Electrónico -->
                    <div class="col-md-4 form-group">
                        <label>Correo Electrónico</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="text" id="terCorreo" class="form-control" readonly>
                        </div>
                    </div>
                    <!-- Teléfono Celular -->
                    <div class="col-md-3 form-group">
                        <label>Teléfono Celular</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            </div>
                            <input type="text" id="terTelefono" class="form-control" readonly>
                        </div>
                    </div>
                    <!-- Dirección -->
                    <div class="col-md-5 form-group">
                        <label>Dirección</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            </div>
                            <input type="text" id="terDireccion" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Ciudad -->
                    <div class="col-md-4 form-group">
                        <label>Ciudad</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-city"></i></span>
                            </div>
                            <input type="text" id="terCiudad" class="form-control" readonly>
                        </div>
                    </div>
                    <!-- Código de Ciudad -->
                    <div class="col-md-2 form-group">
                        <label>Cód. Ciudad</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                            </div>
                            <input type="text" id="terCodigoCiudad" class="form-control" readonly>
                        </div>
                    </div>
                    <!-- Departamento -->
                    <div class="col-md-4 form-group">
                        <label>Departamento</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-map"></i></span>
                            </div>
                            <input type="text" id="terDepartamento" class="form-control" readonly>
                        </div>
                    </div>
                    <!-- Código de Departamento -->
                    <div class="col-md-2 form-group">
                        <label>Cód. Depto.</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                            </div>
                            <input type="text" id="terCodigoDepartamento" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Banco -->
                    <div class="col-md-6 form-group">
                        <label>Banco</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-university"></i></span>
                            </div>
                            <input type="text" id="terBanco" class="form-control" readonly>
                        </div>
                    </div>
                    <!-- No. Cuenta -->
                    <div class="col-md-6 form-group">
                        <label>No. Cuenta</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                            </div>
                            <input type="text" id="terNumeroCuenta" class="form-control" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between" style="background-color: #343a40;">
                <button type="button" class="btn btn-success" id="btnDescargarExcelTercero">
                    <i class="fas fa-file-excel mr-2"></i> Descargar Excel
                </button>
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL VALORES A COMPROMETER -->
<div class="modal fade" id="modal-valoresComprometer" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header" style="background-color: #343a40;">
                <h4 class="modal-title font-weight-bold"><i class="fas fa-dollar-sign mr-2 text-success"></i> Valores a
                    Comprometer</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background-color: #454d55 !important;">
                <div class="row">
                    <!-- Número Documento -->
                    <div class="col-md-6 form-group">
                        <label>Número Documento</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            </div>
                            <input type="text" id="compNumeroDocumento" class="form-control" readonly>
                        </div>
                    </div>
                    <!-- Nombre del Aprendiz -->
                    <div class="col-md-6 form-group">
                        <label>Nombre del Aprendiz</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" id="compNombreAprendiz" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Valor RP -->
                    <div class="col-md-6 form-group">
                        <label>Valor RP</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                            </div>
                            <input type="text" id="compValorRp" class="form-control" readonly>
                        </div>
                    </div>
                    <!-- Tiempo -->
                    <div class="col-md-6 form-group">
                        <label>Tiempo</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" id="compTiempo" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Banco -->
                    <div class="col-md-6 form-group">
                        <label>Banco</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-university"></i></span>
                            </div>
                            <input type="text" id="compBanco" class="form-control" readonly>
                        </div>
                    </div>
                    <!-- Número de Cuenta -->
                    <div class="col-md-6 form-group">
                        <label>Número de Cuenta</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                            </div>
                            <input type="text" id="compNumeroCuenta" class="form-control" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between" style="background-color: #343a40;">
                <button type="button" class="btn btn-success" id="btnDescargarExcelValores">
                    <i class="fas fa-file-excel mr-2"></i> Descargar Excel
                </button>
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Estilos para las tabs oscuras -->
<style>
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
</style>

<!-- Inicialización dinámica de DataTables -->
<script>
    $(document).ready(function () {
        $(".tabla-beneficiarios").each(function () {
            var table = $(this).DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                buttons: ["excel", "pdf"],
                language: {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sSearch": "Buscar:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });

            table.buttons().container().appendTo('#' + $(this).attr('id') + '_wrapper .col-md-6:eq(0)');
        });
    });
</script>