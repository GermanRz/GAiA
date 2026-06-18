$(document).ready(function() {

    // Configuración inicial de Toastr si existe
    if (typeof toastr !== 'undefined') {
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };
    }

    // Comprobar si estamos en la vista de verificación
    if ($("#tblDatos").length === 0) {
        return;
    }

    let activeConvocatoriaVerif = null;

    // --- CARGA INICIAL: CARGAR LA PRIMERA CONVOCATORIA ACTIVA ---
    const $primerBoton = $(".btn-convocatoria-verif").first();
    if ($primerBoton.length > 0) {
        const idConv = $primerBoton.data("id-convocatoria");
        $primerBoton.addClass("active");
        cargarTablaVerificacion(idConv);
    }

    // --- ACCIÓN: CLIC EN BOTÓN DE CONVOCATORIA ---
    $(document).on("click", ".btn-convocatoria-verif", function() {
        $(".btn-convocatoria-verif").removeClass("active");
        $(this).addClass("active");

        const idConvocatoria = $(this).data("id-convocatoria");
        cargarTablaVerificacion(idConvocatoria);
    });

    // --- FUNCIÓN CENTRAL: CARGAR POSTULACIONES Y CONSTRUIR TABLA DINÁMICA ---
    function cargarTablaVerificacion(idConvocatoria) {
        activeConvocatoriaVerif = idConvocatoria;

        const $headerRow = $("#headers-tblVerificacion");
        const $tbody = $("#body-tblVerificacion");

        // Mostrar cargando
        $headerRow.empty().append('<th colspan="100" class="text-center py-3"><i class="fas fa-spinner fa-spin fa-lg text-success mr-2"></i> Cargando postulaciones de aprendices...</th>');
        $tbody.empty();

        const datos = new FormData();
        datos.append("action", "cargarPostulaciones");
        datos.append("idConvocatoria", idConvocatoria);

        $.ajax({
            url: "ajax/verificacion.ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(respuesta) {
                if (respuesta.status === "success") {
                    const requisitos = respuesta.requisitos;
                    const postulaciones = respuesta.postulaciones;

                    // 1. Reconstruir cabeceras de la tabla
                    $headerRow.empty();
                    $headerRow.append('<th style="width: 12%">T.I/C.C</th>');
                    $headerRow.append('<th style="width: 25%">Nombres</th>');

                    if (requisitos.length === 0) {
                        $headerRow.empty().append('<th colspan="100" class="text-center py-4 text-warning"><i class="fas fa-exclamation-circle mr-2"></i> Esta convocatoria no tiene requisitos en el baremo.</th>');
                        return;
                    }

                    requisitos.forEach(req => {
                        // Crear abreviación para coincidir con la vista del mockup
                        let abrev = req.nombre_item;
                        const lowerName = req.nombre_item.toLowerCase();
                        
                        if (lowerName === 'cedula' || lowerName === 'cédula') {
                            abrev = 'C';
                        } else if (lowerName === 'desplazados' || lowerName === 'desplazado') {
                            abrev = 'D';
                        } else if (lowerName === 'negritudes' || lowerName === 'negritud') {
                            abrev = 'C. E';
                        } else if (lowerName === 'juventud vulnerable') {
                            abrev = 'A. M';
                        } else {
                            abrev = req.nombre_item.split(" ").map(w => w[0].toUpperCase()).join(".");
                        }

                        $headerRow.append(`<th class="text-center" title="${req.nombre_item}" style="cursor:help;">${abrev} <i class="fas fa-sort text-muted" style="font-size:0.7rem; margin-left:3px;"></i></th>`);
                    });
                    $headerRow.append('<th class="text-center" style="width: 8%">P <i class="fas fa-sort text-muted" style="font-size:0.7rem;"></i></th>');

                    // 2. Reconstruir cuerpo de la tabla
                    $tbody.empty();

                    if (postulaciones.length === 0) {
                        $tbody.append(`
                            <tr>
                                <td colspan="100" class="text-center py-4 text-muted">
                                    No hay aprendices inscritos en esta convocatoria aún.
                                </td>
                            </tr>
                        `);
                        return;
                    }

                    postulaciones.forEach(post => {
                        const $tr = $("<tr></tr>");
                        $tr.append(`<td>${post.documento_id}</td>`);
                        $tr.append(`<td class="font-weight-bold text-uppercase">${post.apellidos} ${post.nombres}</td>`);

                        // Añadir cada columna de requisito para este aprendiz
                        post.documentos.forEach(doc => {
                            let btnClass = "btn-secondary"; // Gris por defecto (PENDIENTE)
                            let btnIcon = "fas fa-exclamation-triangle text-muted";
                            let disabled = "disabled"; // Por defecto deshabilitado si no se ha subido

                            if (doc.documento_id_db) {
                                disabled = ""; // Habilitado para evaluar

                                if (doc.estado === "APROBADO") {
                                    btnClass = "btn-success"; // Verde (✔)
                                    btnIcon = "fas fa-check";
                                } else if (doc.estado === "PARA_CORREGIR") {
                                    btnClass = "btn-danger"; // Rojo (✘)
                                    btnIcon = "fas fa-times";
                                } else if (doc.estado === "RECHAZADO") {
                                    btnClass = "btn-danger bg-dark border-danger"; // Rojo oscuro (✘ permanente)
                                    btnIcon = "fas fa-times-circle";
                                } else { // PENDIENTE DE REVISIÓN
                                    btnClass = "btn-warning text-dark"; // Naranja (!)
                                    btnIcon = "fas fa-exclamation";
                                }
                            }

                            // Convertir historial a string JSON sanitizado para transferirlo al botón
                            const historialStr = JSON.stringify(doc.historial).replace(/"/g, '&quot;');

                            $tr.append(`
                                <td class="text-center">
                                    <button type="button" 
                                            class="btn btn-sm ${btnClass} btn-eval-tabla shadow-sm"
                                            ${disabled}
                                            data-documento-id="${doc.documento_id_db}"
                                            data-nombre-doc="${doc.nombre_doc}"
                                            data-es-critico="${doc.es_critico}"
                                            data-puntaje="${doc.puntaje_valor}"
                                            data-estado="${doc.estado}"
                                            data-url-copia="${doc.url_copia}"
                                            data-observacion="${doc.observacion_gestora || ''}"
                                            data-intentos="${doc.intentos_fallidos}"
                                            data-historial="${historialStr}"
                                            data-aprendiz-nombre="${post.apellidos} ${post.nombres}"
                                            style="border-radius: 4px; width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;"
                                            title="${disabled ? 'Sin archivo cargado' : 'Revisar Requisito'}"
                                    >
                                        <i class="${btnIcon}"></i>
                                    </button>
                                </td>
                            `);
                        });

                        // Puntaje Total P
                        $tr.append(`<td class="text-center font-weight-bold text-light" style="background-color: #24292e; font-size:1.05rem;">${post.puntaje_total.toFixed(0)}</td>`);
                        $tbody.append($tr);
                    });

                } else {
                    toastr.error("Error al cargar las postulaciones.");
                }
            },
            error: function() {
                toastr.error("No se pudo conectar con el servidor.");
            }
        });
    }

    // --- ACCIÓN: ABRIR MODAL DE EVALUACIÓN ---
    $(document).on("click", ".btn-eval-tabla", function() {
        const docId = $(this).data("documento-id");
        const nombreDoc = $(this).data("nombre-doc");
        const esCritico = $(this).data("es-critico");
        const puntaje = $(this).data("puntaje");
        const estado = $(this).data("estado");
        const urlCopia = $(this).data("url-copia");
        const obs = $(this).data("observacion");
        const intentos = $(this).data("intentos");
        const aprendiz = $(this).data("aprendiz-nombre");
        const historial = $(this).data("historial");

        // Cargar campos ocultos
        $("#eval-documento-id").val(docId);
        $("#eval-nombre-doc").val(nombreDoc);

        // Nombres en textos
        $("#eval-txt-aprendiz").text(aprendiz);
        $("#eval-txt-requisito").text(nombreDoc);
        $("#eval-badge-puntaje").text(`Asigna: ${puntaje} pts`);

        // Obligatoriedad
        if (esCritico == 1) {
            $("#eval-badge-obligatorio").removeClass("badge-secondary").addClass("badge-danger").text("CRÍTICO");
        } else {
            $("#eval-badge-obligatorio").removeClass("badge-danger").addClass("badge-secondary").text("OPCIONAL");
        }

        // Configurar botón de ver PDF
        if (urlCopia) {
            $("#eval-btn-ver-pdf").prop("disabled", false).data("url", urlCopia);
        } else {
            $("#eval-btn-ver-pdf").prop("disabled", true).removeData("url");
        }



        // Configurar Estado actual
        const $alertEstado = $("#eval-alert-estado");
        $alertEstado.removeClass("alert-secondary alert-success alert-info alert-danger");

        if (estado === "APROBADO") {
            $alertEstado.addClass("alert-success border-success text-success bg-transparent")
                        .html('<i class="fas fa-check-double mr-2"></i> Aprobado - Otorga puntaje');
        } 
        else if (estado === "PARA_CORREGIR") {
            $alertEstado.addClass("alert-warning border-warning text-warning bg-transparent")
                        .html('<i class="fas fa-exclamation-circle mr-2"></i> Devuelto para Corrección');
        } 
        else if (estado === "RECHAZADO") {
            $alertEstado.addClass("alert-danger border-danger text-danger bg-transparent")
                        .html('<i class="fas fa-times-circle mr-2"></i> Rechazado Permanentemente (0 pts)');
        } 
        else {
            $alertEstado.addClass("alert-info border-info text-info bg-transparent")
                        .html('<i class="fas fa-spinner fa-spin mr-2"></i> Pendiente de Revisión');
        }

        // --- LÓGICA DE REGRESIÓN E INTENTOS PREVIOS ---
        const $cardHistorial = $("#eval-card-historial");
        const $contenedorHistorial = $("#eval-contenedor-historial");
        $contenedorHistorial.empty();

        if (intentos >= 1) {
            $cardHistorial.removeClass("d-none");
            
            // Inyectar el historial en la caja
            historial.forEach((h, index) => {
                const fecha = new Date(h.fecha_revision).toLocaleDateString('es-ES', {day: '2-digit', month: 'short', hour: '2-digit', minute:'2-digit'});
                $contenedorHistorial.append(`
                    <div class="border-bottom border-secondary pb-2 mb-2">
                        <div class="d-flex justify-content-between font-weight-bold text-muted mb-1" style="font-size:0.75rem;">
                            <span>Revisión #${intentos - index} - ${fecha}</span>
                            <span>Gestor: ${h.gestora_nombres}</span>
                        </div>
                        <p class="mb-1 text-light"><strong>Observación:</strong> ${h.motivo_rechazo}</p>
                        ${h.url_archivo_rechazado ? `<a href="${h.url_archivo_rechazado}" target="_blank" class="btn btn-xs btn-outline-warning font-weight-bold"><i class="fas fa-file-pdf"></i> Ver Archivo Rechazado</a>` : ''}
                    </div>
                `);
            });

            // Regla de un solo intento: Deshabilitar el botón "Devolver para corrección"
            $("#eval-btn-corregir").prop("disabled", true).addClass("opacity-50").attr("title", "El aprendiz ya gastó su oportunidad de corrección.");
            // Habilitar la opción de rechazar permanentemente
            $("#eval-btn-rechazar").prop("disabled", false).removeClass("opacity-50").attr("title", "Rechazar permanentemente el documento.");
        } else {
            $cardHistorial.addClass("d-none");
            
            // Permitir devolver para corrección
            $("#eval-btn-corregir").prop("disabled", false).removeClass("opacity-50").removeAttr("title");
            // Deshabilitar rechazo permanente directo si no ha tenido oportunidad previa de corrección
            // (La regla de negocio indica que el aprendiz siempre tendrá un intento adicional antes de ser rechazado definitivo)
            $("#eval-btn-rechazar").prop("disabled", true).addClass("opacity-50").attr("title", "Se debe dar primero 1 oportunidad de corrección antes de rechazar.");
        }

        // Habilitar todas las acciones si el estado actual es Pendiente o no aprobado definitivamente
        // Si ya está aprobado, se pueden seguir viendo pero no es común cambiarlo
        $(".btn-accion-eval").removeClass("active");

        $("#modal-evaluarDocumento").modal("show");
    });

    // --- ACCIÓN: ABRIR PDF EN OTRA PESTAÑA ---
    $("#eval-btn-ver-pdf").on("click", function() {
        const url = $(this).data("url");
        if (url) {
            window.open(url, '_blank');
        } else {
            toastr.error("No se pudo cargar la dirección del PDF.");
        }
    });

    // --- ACCIÓN: DAR CLIC EN APROBAR, DEVOLVER O RECHAZAR ---
    $(".btn-accion-eval").on("click", function() {
        const targetEval = $(this).data("eval");
        
        $(".btn-accion-eval").removeClass("active");
        $(this).addClass("active");

        if (targetEval === "APROBADO") {
            // Confirmar y procesar aprobación inmediatamente
            procesarEvaluacion("APROBADO", "");
        } 
        else if (targetEval === "PARA_CORREGIR") {
            // Requerir descripción y procesar desde el SweetAlert
            confirmarAccionRechazo("PARA_CORREGIR", "Devolver Documento", "¿Deseas devolver este documento al aprendiz para que lo suba nuevamente?");
        } 
        else if (targetEval === "RECHAZADO") {
            // Requerir descripción y procesar desde el SweetAlert
            confirmarAccionRechazo("RECHAZADO", "Rechazar Definitivamente", "¡Advertencia! Esta acción es definitiva y asignará 0 puntos al requisito en la postulación.");
        }
    });

    function confirmarAccionRechazo(estado, titulo, texto) {
        Swal.fire({
            target: '#modal-evaluarDocumento',
            title: titulo,
            text: texto,
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'Escribe aquí la razón del rechazo o instrucciones claras para el aprendiz...',
            inputAttributes: {
                'aria-label': 'Escribe la razón del rechazo'
            },
            showCancelButton: true,
            confirmButtonColor: estado === 'RECHAZADO' ? '#dc3545' : '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: estado === 'RECHAZADO' ? 'Sí, Rechazar Permanente' : 'Sí, Devolver a Corrección',
            cancelButtonText: 'Cancelar',
            background: '#343a40',
            preConfirm: (value) => {
                if (!value || value.trim() === "") {
                    Swal.showValidationMessage('¡Debes especificar el motivo obligatoriamente para informar al aprendiz!');
                }
                return value;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                procesarEvaluacion(estado, result.value);
            }
        });
    }

    // --- FUNCIÓN: ENVIAR PETICIÓN DE EVALUACIÓN AJAX ---
    function procesarEvaluacion(estado, observacion) {
        const docId = $("#eval-documento-id").val();

        if (!docId) {
            toastr.error("Id de documento inválido.");
            return;
        }

        const datos = new FormData();
        datos.append("action", "evaluarDocumento");
        datos.append("documentoId", docId);
        datos.append("estado", estado);
        datos.append("observacion", observacion);

        $.ajax({
            url: "ajax/verificacion.ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(respuesta) {
                if (respuesta.status === "success") {
                    
                    toastr.success(respuesta.message);
                    
                    // Cerrar modal
                    $("#modal-evaluarDocumento").modal("hide");

                    // Recargar tabla de verificación
                    cargarTablaVerificacion(activeConvocatoriaVerif);

                } else {
                    Swal.fire({
                        target: '#modal-evaluarDocumento',
                        icon: 'error',
                        title: 'Error de Evaluación',
                        text: respuesta.message,
                        background: '#343a40',
                        confirmButtonColor: '#dc3545'
                    });
                }
            },
            error: function() {
                toastr.error("Error al procesar la calificación en el servidor.");
            }
        });
    }

});
