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

    let activeConvocatoriaId = null;

    // --- ACCIÓN: CLIC EN "INICIAR POSTULACIÓN" O "CORREGIR POSTULACIÓN" (GRID CARDS) ---
    $(document).on("click", ".btn-iniciar-inscripcion", function() {
        const idConvocatoria = $(this).data("id-convocatoria");
        const apoyo = $(this).data("apoyo");
        const fechas = $(this).data("fechas");
        
        cargarAsistenteDocumental(idConvocatoria, apoyo, fechas);
    });

    // --- ACCIÓN: CLIC EN "CORREGIR" (DESDE LA TABLA DE MIS POSTULACIONES) ---
    $(document).on("click", ".btn-editar-postulacion-tabla", function() {
        const idConvocatoria = $(this).data("id-convocatoria");
        const apoyo = $(this).data("apoyo");
        const fechas = $(this).data("fechas");

        cargarAsistenteDocumental(idConvocatoria, apoyo, fechas);
    });

    // --- ACCIÓN: REGRESAR AL LISTADO ---
    $(".btn-regresar-listado").on("click", function() {
        $("#panel-carga-documentos").addClass("d-none");
        $("#panel-listados").removeClass("d-none");
        $("html, body").animate({ scrollTop: 0 }, "slow");
        
        // Recargar la página para actualizar estados en caliente
        setTimeout(function() {
            window.location = "inscripciones";
        }, 100);
    });

    // --- FUNCIÓN PRINCIPAL: CARGAR EL ASISTENTE CON DATOS REALES DEL SERVIDOR ---
    function cargarAsistenteDocumental(idConvocatoria, apoyo, fechas) {
        activeConvocatoriaId = idConvocatoria;

        // Configurar cabecera
        $("#titulo-panel-apoyo").html(`<i class="fas fa-file-invoice mr-2 text-success"></i>Postulación y Carga de Archivos - <strong>${apoyo}</strong>`);
        $("#badge-fecha-limite").text(fechas.split(" - ")[1] || fechas);

        // Limpiar contenedor de requisitos
        const $contenedor = $("#contenedor-requisitos-carga");
        $contenedor.empty();

        // Mostrar spinner de carga
        $contenedor.html(`
            <div class="text-center py-5 text-muted">
                <i class="fas fa-spinner fa-spin fa-3x mb-3 text-success"></i>
                <h5>Cargando requisitos del servidor...</h5>
            </div>
        `);

        // Realizar petición AJAX para traer requisitos y postulación existente
        const datos = new FormData();
        datos.append("action", "cargarRequisitosConvocatoria");
        datos.append("idConvocatoria", idConvocatoria);

        $.ajax({
            url: "ajax/inscripciones.ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(respuesta) {
                $contenedor.empty();

                if (respuesta.status == "success") {
                    const baremo = respuesta.baremo;
                    const documentosCargados = respuesta.documentos;

                    if (baremo.length === 0) {
                        $contenedor.html(`
                            <div class="text-center py-5 text-muted border border-secondary rounded" style="border-style: dashed !important;">
                                <i class="fas fa-exclamation-circle fa-3x mb-3 text-warning"></i>
                                <h5>Esta convocatoria no tiene requisitos configurados en el baremo.</h5>
                                <p class="mb-0">Por favor contacte al administrador del sistema.</p>
                            </div>
                        `);
                        return;
                    }

                    const template = document.getElementById("template-requisito-fila");

                    // Dibujar cada requisito del baremo
                    baremo.forEach((req, idx) => {
                        const clone = document.importNode(template.content, true);
                        const $card = $(clone).find(".card-requisito");

                        // Asignar textos
                        $card.find(".doc-nombre").text(req.nombre_item);
                        $card.find(".doc-desc").text(`Documento requerido según el baremo oficial de evaluación.`);
                        $card.find(".badge-puntaje").text(`Puntaje Máx: ${req.puntaje_valor} pts`);

                        // Obligatoriedad
                        if (req.es_critico != 1) {
                            $card.find(".badge-obligatoriedad")
                                 .removeClass("badge-danger")
                                 .addClass("badge-secondary")
                                 .text("OPCIONAL");
                        }

                        // Almacenar metadatos en la card
                        $card.attr("data-nombre-doc", req.nombre_item);

                        // Buscar si el aprendiz ya subió este documento previamente
                        const archivoCargado = documentosCargados.find(doc => doc.nombre_doc == req.nombre_item);

                        if (archivoCargado && archivoCargado.url_copia != null && archivoCargado.url_copia != "") {
                            
                            // Guardar ID y ruta en el elemento para eliminar o ver
                            $card.attr("data-id-doc", archivoCargado.id);
                            $card.attr("data-ruta-doc", archivoCargado.url_copia);

                            if (archivoCargado.estado == 'PARA_CORREGIR') {
                                // --- ESTADO: PARA CORREGIR ---
                                $card.attr("data-estado", "rechazado");
                                $card.find(".estado-pendiente").addClass("d-none");
                                $card.find(".estado-rechazado").removeClass("d-none");
                                
                                const obs = archivoCargado.observacion_gestora || "El documento requiere correcciones.";
                                $card.find(".text-obs-rechazo").text(`"${obs}"`);
                                $card.css("border-left", "5px solid #dc3545");
                            } else if (archivoCargado.estado == 'APROBADO') {
                                // --- ESTADO: APROBADO ---
                                $card.attr("data-estado", "cargado");
                                $card.find(".estado-pendiente").addClass("d-none");
                                $card.find(".estado-cargado").removeClass("d-none");
                                
                                $card.find(".nombre-archivo-cargado").text(archivoCargado.url_copia.split("/").pop());
                                $card.find(".size-archivo-cargado").html(`<span class="badge badge-success"><i class="fas fa-check"></i> Aprobado</span>`);
                                
                                $card.find(".zona-subida-archivo").addClass("d-none");
                                $card.find(".acciones-archivo-cargado").removeClass("d-none");
                                $card.css("border-left", "5px solid #28a745");
                            } else {
                                // --- ESTADO: PENDIENTE DE REVISIÓN ---
                                $card.attr("data-estado", "cargado");
                                $card.find(".estado-pendiente").addClass("d-none");
                                $card.find(".estado-cargado").removeClass("d-none");
                                
                                $card.find(".nombre-archivo-cargado").text(archivoCargado.url_copia.split("/").pop());
                                $card.find(".size-archivo-cargado").html(`<span class="badge badge-info"><i class="fas fa-spinner fa-spin"></i> Pendiente de revisión</span>`);

                                $card.find(".zona-subida-archivo").addClass("d-none");
                                $card.find(".acciones-archivo-cargado").removeClass("d-none");
                                $card.css("border-left", "5px solid #17a2b8");
                            }
                        } else {
                            // --- ESTADO: SIN CARGAR ---
                            $card.attr("data-estado", "pendiente");
                            $card.css("border-left", "5px solid #6c757d");
                        }

                        $contenedor.append($card);
                    });

                    // Cambiar visibilidad de paneles
                    $("#panel-listados").addClass("d-none");
                    $("#panel-carga-documentos").removeClass("d-none");
                    $("html, body").animate({ scrollTop: 0 }, "slow");

                } else {
                    toastr.error("Error al recuperar los datos de la convocatoria.");
                }
            },
            error: function() {
                toastr.error("No se pudo conectar con el servidor.");
            }
        });
    }

    // --- ACCIÓN: SELECCIÓN Y CARGA INMEDIATA DEL ARCHIVO ---
    $(document).on("change", ".file-uploader-input", function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // 1. Validar que sea PDF
        const ext = file.name.split('.').pop().toLowerCase();
        if (ext !== 'pdf' || file.type !== 'application/pdf') {
            Swal.fire({
                icon: 'error',
                title: 'Formato no permitido',
                text: 'Únicamente se permiten documentos en formato PDF (.pdf).',
                background: '#343a40',
                confirmButtonColor: '#dc3545'
            });
            $(this).val("");
            return;
        }

        // 2. Validar tamaño (máximo 2 MB)
        const maxSize = 2 * 1024 * 1024;
        if (file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'Archivo demasiado pesado',
                text: 'El documento supera el límite permitido de 5 MB.',
                background: '#343a40',
                confirmButtonColor: '#dc3545'
            });
            $(this).val("");
            return;
        }

        const $input = $(this);
        const $card = $input.closest(".card-requisito");
        const $dropzone = $card.find(".dropzone-mock");
        const $progressBarContainer = $card.find(".progress-simulada");
        const $progressBar = $progressBarContainer.find(".progress-bar");
        const nombreDoc = $card.data("nombre-doc");

        // Deshabilitar input y mostrar barra de carga
        $input.prop("disabled", true);
        $dropzone.css("opacity", "0.5");
        $progressBarContainer.removeClass("d-none");
        $progressBar.css("width", "0%").attr("aria-valuenow", 0);

        // Armar FormData para petición AJAX
        const formData = new FormData();
        formData.append("action", "subirDocumento");
        formData.append("file", file);
        formData.append("convocatoriaId", activeConvocatoriaId);
        formData.append("nombreDoc", nombreDoc);

        $.ajax({
            url: "ajax/inscripciones.ajax.php",
            method: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            xhr: function() {
                const myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            const porcentaje = Math.round((e.loaded * 100) / e.total);
                            $progressBar.css("width", porcentaje + "%").attr("aria-valuenow", porcentaje);
                        }
                    }, false);
                }
                return myXhr;
            },
            success: function(respuesta) {
                if (respuesta.status == "success") {
                    
                    // Actualizar estado en la card
                    $card.attr("data-estado", "cargado");
                    $card.attr("data-id-doc", respuesta.inscripcion_id); // De respaldo
                    $card.attr("data-ruta-doc", respuesta.url);

                    // Refrescar UI de fila
                    $card.find(".estado-pendiente").addClass("d-none");
                    $card.find(".estado-rechazado").addClass("d-none");
                    $card.find(".estado-cargado").removeClass("d-none");

                    $card.find(".nombre-archivo-cargado").text(respuesta.nombre_archivo);
                    $card.find(".size-archivo-cargado").html(`<span class="badge badge-info"><i class="fas fa-spinner fa-spin"></i> Pendiente de revisión</span>`);

                    $card.find(".zona-subida-archivo").addClass("d-none");
                    $card.find(".acciones-archivo-cargado").removeClass("d-none");

                    $card.css("border-left", "5px solid #17a2b8"); // Color celeste de pendiente de revision

                    toastr.success("El archivo se subió e integró correctamente en la base de datos.");
                    
                    // Volver a cargar el asistente en segundo plano para obtener el ID real de la base de datos del documento
                    recargarIdDocumentoCargado($card, nombreDoc);

                } else {
                    resetearFilaCarga($card, $input, $dropzone, $progressBarContainer);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de carga',
                        text: respuesta.message,
                        background: '#343a40',
                        confirmButtonColor: '#dc3545'
                    });
                }
            },
            error: function() {
                resetearFilaCarga($card, $input, $dropzone, $progressBarContainer);
                toastr.error("Fallo de red al intentar subir el archivo.");
            }
        });
    });

    // --- FUNCIÓN DE RESPALDO: CONSULTA RÁPIDA PARA RECUPERAR ID DEL NUEVO REGISTRO ---
    function recargarIdDocumentoCargado($card, nombreDoc) {
        const datos = new FormData();
        datos.append("action", "cargarRequisitosConvocatoria");
        datos.append("idConvocatoria", activeConvocatoriaId);

        $.ajax({
            url: "ajax/inscripciones.ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(respuesta) {
                if (respuesta.status == "success" && respuesta.documentos) {
                    const cargado = respuesta.documentos.find(d => d.nombre_doc == nombreDoc);
                    if (cargado) {
                        $card.attr("data-id-doc", cargado.id);
                        $card.attr("data-ruta-doc", cargado.url_copia);
                    }
                }
            }
        });
    }

    function resetearFilaCarga($card, $input, $dropzone, $progressBarContainer) {
        $input.prop("disabled", false).val("");
        $dropzone.css("opacity", "1");
        $progressBarContainer.addClass("d-none");
        $progressBarContainer.find(".progress-bar").css("width", "0%");
    }

    // --- ACCIÓN: ELIMINAR UN DOCUMENTO GUARDADO ---
    $(document).on("click", ".btn-eliminar-pdf-sim", function() {
        const $btn = $(this);
        const $card = $btn.closest(".card-requisito");
        const idDoc = $card.attr("data-id-doc");
        const rutaDoc = $card.attr("data-ruta-doc");
        const nombreDoc = $card.data("nombre-doc");

        if (!idDoc || !rutaDoc) {
            toastr.error("No se encontraron metadatos válidos del archivo para proceder.");
            return;
        }

        Swal.fire({
            title: '¿Remover archivo?',
            text: "El archivo físico se eliminará del servidor y el estado volverá a pendiente.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            background: '#343a40'
        }).then((result) => {
            if (result.isConfirmed) {
                
                const datos = new FormData();
                datos.append("action", "eliminarDocumento");
                datos.append("idDoc", idDoc);
                datos.append("rutaArchivo", rutaDoc);

                $.ajax({
                    url: "ajax/inscripciones.ajax.php",
                    method: "POST",
                    data: datos,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function(respuesta) {
                        if (respuesta.status == "ok") {
                            
                            $card.attr("data-estado", "pendiente");
                            $card.removeAttr("data-id-doc");
                            $card.removeAttr("data-ruta-doc");

                            $card.find(".estado-cargado").addClass("d-none");
                            $card.find(".estado-rechazado").addClass("d-none");
                            $card.find(".estado-pendiente").removeClass("d-none");

                            $card.find(".file-uploader-input").prop("disabled", false).val("");
                            $card.find(".dropzone-mock").css("opacity", "1");
                            $card.find(".progress-simulada").addClass("d-none").find(".progress-bar").css("width", "0%");

                            $card.find(".zona-subida-archivo").removeClass("d-none");
                            $card.find(".acciones-archivo-cargado").addClass("d-none");

                            $card.css("border-left", "5px solid #6c757d");

                            toastr.info("El documento ha sido eliminado físicamente.");

                        } else {
                            toastr.error("No se pudo remover el archivo en la base de datos.");
                        }
                    },
                    error: function() {
                        toastr.error("Error de comunicación al eliminar el archivo.");
                    }
                });

            }
        });
    });

    // --- ACCIÓN: PREVISUALIZAR ARCHIVO REAL ---
    $(document).on("click", ".btn-ver-pdf-sim", function() {
        const $btn = $(this);
        const $card = $btn.closest(".card-requisito");
        const rutaDoc = $card.attr("data-ruta-doc");
        const nombreArchivo = $card.find(".nombre-archivo-cargado").text();
        
        if (rutaDoc) {
            // En lugar del modal simulado, podemos abrir la ruta real del PDF en otra pestaña. ¡Súper Premium!
            window.open(rutaDoc, '_blank');
        } else {
            toastr.error("La ruta del archivo no es válida.");
        }
    });

    // --- ACCIÓN: BOTÓN GUARDAR BORRADOR ---
    $("#btn-guardar-borrador-sim").on("click", function() {
        Swal.fire({
            icon: 'success',
            title: 'Borrador Almacenado',
            text: 'Tus archivos se han subido con éxito. Puedes regresar y completarlo antes de la fecha límite.',
            background: '#343a40',
            confirmButtonColor: '#28a745'
        }).then(() => {
            window.location = "inscripciones";
        });
    });

    // --- ACCIÓN: ENVIAR POSTULACIÓN COMPLETA (FINALIZAR) ---
    $("#btn-enviar-postulacion-sim").on("click", function() {
        let obligatoriosFaltantes = 0;

        $("#contenedor-requisitos-carga .card-requisito").each(function() {
            const estado = $(this).attr("data-estado");
            const esObligatorio = $(this).find(".badge-obligatoriedad").hasClass("badge-danger");

            if (esObligatorio && estado !== "cargado") {
                obligatoriosFaltantes++;
            }
        });

        if (obligatoriosFaltantes > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Inscripción Incompleta',
                text: `Faltan cargar ${obligatoriosFaltantes} documento(s) críticos requeridos por esta convocatoria.`,
                background: '#343a40',
                confirmButtonColor: '#dc3545'
            });
            return;
        }

        Swal.fire({
            title: '¿Confirmar envío de postulación?',
            text: "Tu solicitud se enviará a revisión. Se bloqueará la edición y borrado de documentos mientras dure la evaluación.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, enviar ahora',
            cancelButtonText: 'Cancelar',
            background: '#343a40'
        }).then((result) => {
            if (result.isConfirmed) {
                // Como los archivos ya se subieron en tiempo real y el registro en la BD de inscripciones ya se creó, 
                // el envío simplemente confirma el estado. Redirigimos a la tabla actualizando estados.
                Swal.fire({
                    icon: 'success',
                    title: '¡Postulación Enviada!',
                    text: 'Tu solicitud ha sido ingresada al flujo de evaluación del centro de formación.',
                    background: '#343a40',
                    confirmButtonColor: '#28a745'
                }).then(() => {
                    window.location = "inscripciones";
                });
            }
        });
    });

    // =========================================================================================
    // LÓGICA DE CARGA BANCARIA (DOBLE PERFIL FINANCIERA)
    // =========================================================================================

    // 1. Abrir Modal y setear ID
    $(document).on("click", ".btn-cargar-banco", function() {
        const idInscripcion = $(this).data("id-inscripcion");
        $("#id_inscripcion_banco").val(idInscripcion);
        $("#frmCargarBanco")[0].reset();
        $("#certificacion_pdf").next('.custom-file-label').html('Seleccionar Archivo');
    });

    // 1.5. Mostrar nombre del archivo seleccionado
    $(document).on("change", "#certificacion_pdf", function(e) {
        if(e.target.files.length > 0) {
            let fileName = e.target.files[0].name;
            $(this).next('.custom-file-label').html(fileName);
        } else {
            $(this).next('.custom-file-label').html('Seleccionar Archivo');
        }
    });

    // 2. Restricción: Solo números en campos de cuenta
    $(document).on("input", ".input-numero-cuenta", function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // 3. Bloquear copiar y pegar en confirmar cuenta
    $(document).on("paste", "#confirmar_cuenta", function(e) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Acción Bloqueada',
            text: 'Por favor, digite el número de cuenta manualmente para evitar errores.',
            background: '#343a40',
            confirmButtonColor: '#ffc107'
        });
    });

    // 4. Submit y validación de formulario
    $(document).on("submit", "#frmCargarBanco", function(e) {
        e.preventDefault();

        const cuenta1 = $("#numero_cuenta").val();
        const cuenta2 = $("#confirmar_cuenta").val();
        const archivo = $("#certificacion_pdf")[0].files[0];

        // Validar coincidencia
        if (cuenta1 !== cuenta2) {
            Swal.fire({
                icon: 'error',
                title: 'Las cuentas no coinciden',
                text: 'El número de cuenta y su confirmación deben ser exactamente iguales.',
                background: '#343a40',
                confirmButtonColor: '#dc3545'
            });
            return;
        }

        // Validar PDF y tamaño
        if (!archivo) {
            Swal.fire({icon: 'error', title: 'Archivo Requerido', text: 'Debe seleccionar un documento PDF.', background: '#343a40'});
            return;
        } else {
            const ext = archivo.name.split('.').pop().toLowerCase();
            if (ext !== 'pdf' || archivo.type !== 'application/pdf') {
                Swal.fire({icon: 'error', title: 'Solo PDF', text: 'El documento debe ser un PDF.', background: '#343a40'});
                return;
            }
            if (archivo.size > 2 * 1024 * 1024) {
                Swal.fire({icon: 'error', title: 'Archivo muy pesado', text: 'El PDF supera los 2MB permitidos.', background: '#343a40'});
                return;
            }
        }

        // AJAX
        const formData = new FormData(this);
        formData.append("action", "subirCertificacionBancaria");

        // Cambiar estado de botón
        const $btn = $(".btn-guardar-banco");
        $btn.prop("disabled", true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Procesando...');

        $.ajax({
            url: "ajax/inscripciones.ajax.php", // Usaremos el de inscripciones
            method: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(respuesta) {
                if (respuesta.status === "success") {
                    $("#modalCargarBanco").modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Documento Enviado',
                        text: 'Su certificación ha sido cargada y está en revisión por Financiera.',
                        background: '#343a40',
                        confirmButtonColor: '#28a745'
                    }).then(() => {
                        window.location = "inscripciones";
                    });
                } else {
                    Swal.fire({icon: 'error', title: 'Error', text: respuesta.message, background: '#343a40'});
                    $btn.prop("disabled", false).html('<i class="fas fa-save mr-1"></i> Guardar y Enviar');
                }
            },
            error: function() {
                Swal.fire({icon: 'error', title: 'Error de Red', text: 'Fallo al comunicarse con el servidor.', background: '#343a40'});
                $btn.prop("disabled", false).html('<i class="fas fa-save mr-1"></i> Guardar y Enviar');
            }
        });
    });

});
