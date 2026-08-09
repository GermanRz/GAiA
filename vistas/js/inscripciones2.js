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

    // Comprobar si estamos en la vista de inscripciones2
    if ($("#tblInscripciones2").length === 0) {
        return;
    }

    let activeConvocatoriaId2 = null;

    // --- CARGA INICIAL: CARGAR LA PRIMERA CONVOCATORIA ACTIVA ---
    const $primerBoton = $(".btn-convocatoria-sel").first();
    if ($primerBoton.length > 0) {
        const idConv = $primerBoton.data("id-convocatoria");
        $primerBoton.addClass("active");
        cargarTablaInscripciones2(idConv);
    }

    // --- ACCIÓN: CLIC EN BOTÓN DE CONVOCATORIA ---
    $(document).on("click", ".btn-convocatoria-sel", function() {
        $(".btn-convocatoria-sel").removeClass("active");
        $(this).addClass("active");

        const idConvocatoria = $(this).data("id-convocatoria");
        cargarTablaInscripciones2(idConvocatoria);
    });

    // --- FUNCIÓN CENTRAL: CARGAR REQUISITOS Y DIBUJAR LA TABLA DINÁMICAMENTE ---
    function cargarTablaInscripciones2(idConvocatoria) {
        activeConvocatoriaId2 = idConvocatoria;

        // Limpiar filas y columnas, mostrar spinner de carga
        const $headerRow = $("#headers-tblInscripciones2");
        const $row = $("#row-tblInscripciones2");

        $headerRow.empty().append('<th colspan="100" class="text-center py-3"><i class="fas fa-spinner fa-spin fa-lg text-success mr-2"></i> Cargando tabla dinámicamente...</th>');
        $row.empty();

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
                if (respuesta.status === "success") {
                    const baremo = respuesta.baremo;
                    const documentosCargados = respuesta.documentos;
                    const inscripcion = respuesta.inscripcion;

                    // 1. Reconstruir cabeceras de la tabla
                    $headerRow.empty();
                    $headerRow.append('<th style="width: 15%">T.I/C.C</th>');
                    $headerRow.append('<th style="width: 25%">Nombres</th>');

                    if (baremo.length === 0) {
                        $headerRow.empty().append('<th colspan="100" class="text-center py-4 text-warning"><i class="fas fa-exclamation-circle mr-2"></i> Convocatoria sin requisitos configurados.</th>');
                        return;
                    }

                    baremo.forEach(req => {
                        // Crear abreviación para coincidir con la vista del mockup
                        let abrev = req.nombre_item;
                        const lowerName = req.nombre_item.toLowerCase();
                        
                        if (lowerName === 'cedula' || lowerName === 'cédula') {
                            abrev = 'C';
                        } else if (lowerName === 'desplazados' || lowerName === 'desplazado') {
                            abrev = 'D';
                        } else if (lowerName === 'negritudes' || lowerName === 'negritud') {
                            abrev = 'C. E'; // Abreviado al diseño de referencia
                        } else if (lowerName === 'juventud vulnerable') {
                            abrev = 'A. M'; // Abreviado al diseño de referencia
                        } else {
                            // Crear abreviación genérica por siglas
                            abrev = req.nombre_item.split(" ").map(w => w[0].toUpperCase()).join(".");
                        }

                        $headerRow.append(`<th class="text-center font-weight-bold" title="${req.nombre_item}" style="cursor:help;">${abrev} <i class="fas fa-sort text-muted" style="font-size:0.7rem; margin-left:3px;"></i></th>`);
                    });
                    $headerRow.append('<th class="text-center" style="width: 8%">P <i class="fas fa-sort text-muted" style="font-size:0.7rem;"></i></th>');

                    // 2. Reconstruir fila del aprendiz
                    $row.empty();
                    const documentoId = $("#tblInscripciones2").data("documento");
                    const nombreCompleto = $("#tblInscripciones2").data("nombre");

                    $row.append(`<td>${documentoId}</td>`);
                    $row.append(`<td class="font-weight-bold text-uppercase">${nombreCompleto}</td>`);

                    // Rellenar las celdas para cada requisito del baremo
                    baremo.forEach(req => {
                        const archivoCargado = documentosCargados.find(doc => doc.nombre_doc == req.nombre_item);
                        
                        let btnClass = "btn-secondary"; // Gris por defecto (⚠)
                        let btnIcon = "fas fa-exclamation-triangle text-muted";
                        let estado = "pendiente";
                        let idDoc = "";
                        let rutaDoc = "";
                        let obs = "";

                        if (archivoCargado && archivoCargado.url_copia != null && archivoCargado.url_copia != "") {
                            idDoc = archivoCargado.id;
                            rutaDoc = archivoCargado.url_copia;
                            obs = archivoCargado.observacion_gestora || "";

                            if (archivoCargado.estado === 'PARA_CORREGIR') {
                                btnClass = "btn-danger"; // Rojo (✘)
                                btnIcon = "fas fa-times text-white";
                                estado = "rechazado";
                            } else if (archivoCargado.estado === 'APROBADO') {
                                btnClass = "btn-success"; // Verde (✔)
                                btnIcon = "fas fa-check text-white";
                                estado = "aprobado";
                            } else {
                                btnClass = "btn-warning text-dark"; // Naranja (!)
                                btnIcon = "fas fa-exclamation text-white";
                                estado = "pendiente_revision";
                            }
                        }

                        $row.append(`
                            <td class="text-center">
                                <button type="button" 
                                        class="btn btn-sm ${btnClass} btn-requisito-modal shadow-sm"
                                        data-nombre-doc="${req.nombre_item}"
                                        data-es-critico="${req.es_critico}"
                                        data-puntaje="${req.puntaje_valor}"
                                        data-estado="${estado}"
                                        data-id-doc="${idDoc}"
                                        data-ruta-doc="${rutaDoc}"
                                        data-obs="${obs}"
                                        style="border-radius: 4px; width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;"
                                >
                                    <i class="${btnIcon}" style="font-size:0.9rem;"></i>
                                </button>
                            </td>
                        `);
                    });

                    // Puntaje Total
                    const puntajeTotal = (inscripcion && inscripcion.puntaje_total) ? parseFloat(inscripcion.puntaje_total).toFixed(0) : "0";
                    $row.append(`<td class="text-center font-weight-bold text-light" style="font-size: 1rem; background-color: #24292e;">${puntajeTotal}</td>`);

                } else {
                    toastr.error("Error al cargar la postulación.");
                }
            },
            error: function() {
                toastr.error("Fallo al conectar con el servidor.");
            }
        });
    }

    // --- ACCIÓN: ABRIR MODAL AL HACER CLIC EN CUALQUIER BOTÓN REQUISITO ---
    $(document).on("click", ".btn-requisito-modal", function() {
        const nombreDoc = $(this).data("nombre-doc");
        const esCritico = $(this).data("es-critico");
        const puntaje = $(this).data("puntaje");
        const estado = $(this).data("estado");
        const idDoc = $(this).data("id-doc");
        const rutaDoc = $(this).data("ruta-doc");
        const obs = $(this).data("obs");

        // Rellenar campos ocultos del modal
        $("#modal-nombre-doc").val(nombreDoc);
        $("#modal-id-doc").val(idDoc);
        $("#modal-ruta-doc").val(rutaDoc);

        // Textos del modal
        $("#modal-txt-nombre-doc").text(nombreDoc);
        $("#modal-badge-puntaje").text(`Puntaje Máximo: ${puntaje} pts`);

        // Configurar obligatoriedad
        if (esCritico == 1) {
            $("#modal-badge-obligatorio").removeClass("badge-secondary").addClass("badge-danger").text("CRÍTICO");
        } else {
            $("#modal-badge-obligatorio").removeClass("badge-danger").addClass("badge-secondary").text("OPCIONAL");
        }

        // Configurar alerta de observaciones/rechazo
        if (estado === "rechazado") {
            $("#modal-alert-rechazo").removeClass("d-none");
            $("#modal-txt-obs").text(`"${obs}"`);
        } else {
            $("#modal-alert-rechazo").addClass("d-none");
        }

        // Limpiar barra de progreso
        $("#modal-progress-container").addClass("d-none");
        $("#modal-progress-bar").css("width", "0%").attr("aria-valuenow", 0);
        $("#file-uploader2").prop("disabled", false).val("");

        // Configurar Estado visual y Zonas de Carga
        const $alertEstado = $("#modal-alert-estado");
        $alertEstado.removeClass("alert-secondary alert-success alert-info alert-danger");

        if (estado === "aprobado") {
            $alertEstado.addClass("alert-success border-success text-success bg-transparent")
                        .html('<i class="fas fa-check-double mr-2"></i> Documento Aprobado por Bienestar');
            
            $("#modal-div-subida").addClass("d-none");
            $("#modal-div-acciones").removeClass("d-none");
            // No permitir eliminar si ya fue aprobado
            $("#modal-btn-eliminar").prop("disabled", true).addClass("d-none");
        } 
        else if (estado === "pendiente_revision") {
            $alertEstado.addClass("alert-info border-info text-info bg-transparent")
                        .html('<i class="fas fa-spinner fa-spin mr-2"></i> Pendiente de Revisión por Bienestar');
            
            $("#modal-div-subida").addClass("d-none");
            $("#modal-div-acciones").removeClass("d-none");
            $("#modal-btn-eliminar").prop("disabled", false).removeClass("d-none");
        } 
        else if (estado === "rechazado") {
            $alertEstado.addClass("alert-danger border-danger text-danger bg-transparent")
                        .html('<i class="fas fa-exclamation-triangle mr-2"></i> Corrección Requerida');
            
            $("#modal-div-subida").removeClass("d-none");
            $("#modal-div-acciones").removeClass("d-none");
            $("#modal-btn-eliminar").prop("disabled", false).removeClass("d-none");
        } 
        else { // Pendiente de carga
            $alertEstado.addClass("alert-secondary border-secondary text-muted bg-transparent")
                        .html('<i class="fas fa-cloud-upload-alt mr-2"></i> Pendiente de Cargar');
            
            $("#modal-div-subida").removeClass("d-none");
            $("#modal-div-acciones").addClass("d-none");
        }

        // Mostrar el modal
        $("#modal-subirDocumento2").modal("show");
    });

    // --- ACCIÓN: SUBIR ARCHIVO PDF ---
    $(document).on("change", "#file-uploader2", function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Validar formato PDF
        const ext = file.name.split('.').pop().toLowerCase();
        if (ext !== 'pdf' || file.type !== 'application/pdf') {
            Swal.fire({
                icon: 'error',
                title: 'Formato inválido',
                text: 'Únicamente se permiten archivos en formato PDF (.pdf).',
                background: '#343a40',
                confirmButtonColor: '#dc3545'
            });
            $(this).val("");
            return;
        }

        // Validar tamaño (máximo 5 MB)
        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'Archivo excedido',
                text: 'El documento PDF no debe pesar más de 5 MB.',
                background: '#343a40',
                confirmButtonColor: '#dc3545'
            });
            $(this).val("");
            return;
        }

        const nombreDoc = $("#modal-nombre-doc").val();
        const $uploader = $(this);
        const $progressContainer = $("#modal-progress-container");
        const $progressBar = $("#modal-progress-bar");

        $uploader.prop("disabled", true);
        $progressContainer.removeClass("d-none");
        $progressBar.css("width", "0%").attr("aria-valuenow", 0);

        const formData = new FormData();
        formData.append("action", "subirDocumento");
        formData.append("file", file);
        formData.append("convocatoriaId", activeConvocatoriaId2);
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
                if (respuesta.status === "success") {
                    toastr.success("Documento subido e integrado correctamente.");
                    
                    // Cerrar modal
                    $("#modal-subirDocumento2").modal("hide");

                    // Recargar tabla de inscripciones 2
                    cargarTablaInscripciones2(activeConvocatoriaId2);
                } else {
                    $uploader.prop("disabled", false).val("");
                    $progressContainer.addClass("d-none");
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Carga',
                        text: respuesta.message,
                        background: '#343a40',
                        confirmButtonColor: '#dc3545'
                    });
                }
            },
            error: function() {
                $uploader.prop("disabled", false).val("");
                $progressContainer.addClass("d-none");
                toastr.error("Error en la conexión con el servidor.");
            }
        });
    });

    // --- ACCIÓN: ELIMINAR DOCUMENTO ---
    $("#modal-btn-eliminar").on("click", function() {
        const idDoc = $("#modal-id-doc").val();
        const rutaDoc = $("#modal-ruta-doc").val();

        if (!idDoc || !rutaDoc) {
            toastr.error("No se encontraron metadatos para proceder con la eliminación.");
            return;
        }

        Swal.fire({
            title: '¿Remover archivo?',
            text: "El archivo físico se eliminará del servidor y su estado volverá a pendiente.",
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
                        if (respuesta.status === "ok") {
                            toastr.info("El documento ha sido removido.");
                            
                            // Cerrar modal
                            $("#modal-subirDocumento2").modal("hide");

                            // Recargar tabla de inscripciones 2
                            cargarTablaInscripciones2(activeConvocatoriaId2);
                        } else {
                            toastr.error("Error al remover el registro.");
                        }
                    },
                    error: function() {
                        toastr.error("Fallo al conectar con el servidor.");
                    }
                });

            }
        });
    });

    // --- ACCIÓN: VISUALIZAR PDF ---
    $("#modal-btn-ver").on("click", function() {
        const rutaDoc = $("#modal-ruta-doc").val();
        if (rutaDoc) {
            window.open(rutaDoc, '_blank');
        } else {
            toastr.error("La ruta del archivo no es válida.");
        }
    });

});
