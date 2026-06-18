$(document).ready(function() {

    // =======================================================
    // APROBAR DOCUMENTO BANCARIO (FINANCIERA)
    // =======================================================
    $(document).on("click", ".btn-aprobar-banco", function() {
        const idInscripcion = $(this).data("id-inscripcion");

        Swal.fire({
            title: 'Aprobar Documento Bancario',
            html: `
                <div class="text-left">
                    <p class="text-sm">Se creará un registro de asignación activa para este aprendiz.</p>
                    <div class="form-group">
                        <label for="swal-meses">Meses otorgados:</label>
                        <input type="number" id="swal-meses" class="form-control" value="6" min="1" max="24">
                    </div>
                    <div class="form-group">
                        <label for="swal-fecha">Fecha de inicio de pago:</label>
                        <input type="date" id="swal-fecha" class="form-control" value="${new Date().toISOString().split('T')[0]}">
                    </div>
                </div>
            `,
            icon: 'info',
            background: '#343a40',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, aprobar y asignar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                const meses = document.getElementById('swal-meses').value;
                const fecha = document.getElementById('swal-fecha').value;
                if (!meses || !fecha) {
                    Swal.showValidationMessage('Ambos campos son críticos');
                }
                return { meses: meses, fecha: fecha }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const datos = new FormData();
                datos.append("action", "aprobarDocumentoBancario");
                datos.append("id_inscripcion", idInscripcion);
                datos.append("meses_otorgados", result.value.meses);
                datos.append("fecha_inicio", result.value.fecha);

                $.ajax({
                    url: "ajax/financiera.ajax.php",
                    method: "POST",
                    data: datos,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function(respuesta) {
                        if (respuesta.status === "ok") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Aprobado',
                                text: 'El aprendiz ha sido asignado correctamente.',
                                background: '#343a40',
                                confirmButtonColor: '#28a745'
                            }).then(() => {
                                window.location = "financiera";
                            });
                        } else {
                            Swal.fire({icon: 'error', title: 'Error', text: 'No se pudo aprobar la asignación.', background: '#343a40'});
                        }
                    }
                });
            }
        });
    });

    // =======================================================
    // RECHAZAR DOCUMENTO BANCARIO (FINANCIERA)
    // =======================================================
    $(document).on("click", ".btn-rechazar-banco", function() {
        const idInscripcion = $(this).data("id-inscripcion");

        Swal.fire({
            title: 'Devolver Documento',
            text: 'Ingrese el motivo por el cual el documento es rechazado:',
            input: 'textarea',
            inputPlaceholder: 'Ej. El certificado no es legible o la cuenta está a nombre de un tercero...',
            icon: 'warning',
            background: '#343a40',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, rechazar',
            cancelButtonText: 'Cancelar',
            preConfirm: (observacion) => {
                if (!observacion) {
                    Swal.showValidationMessage('Debe ingresar un motivo de rechazo');
                }
                return observacion;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const datos = new FormData();
                datos.append("action", "rechazarDocumentoBancario");
                datos.append("id_inscripcion", idInscripcion);
                datos.append("observacion", result.value);

                $.ajax({
                    url: "ajax/financiera.ajax.php",
                    method: "POST",
                    data: datos,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function(respuesta) {
                        if (respuesta.status === "ok") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Documento Rechazado',
                                text: 'Se ha notificado al aprendiz para que corrija la información.',
                                background: '#343a40',
                                confirmButtonColor: '#28a745'
                            }).then(() => {
                                window.location = "financiera";
                            });
                        } else {
                            Swal.fire({icon: 'error', title: 'Error', text: 'No se pudo rechazar el documento.', background: '#343a40'});
                        }
                    }
                });
            }
        });
    });

});
