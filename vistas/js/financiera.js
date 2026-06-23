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
                    Swal.showValidationMessage('Ambos campos son obligatorios');
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

    // =======================================================
    // DESCARGAR REPORTE PDF DE APRENDIZ (FINANCIERA)
    // =======================================================
    $(document).on("click", ".btnDescargarPdf", function() {
        const datos = $(this).data();

        const docDefinition = {
            content: [
                // Encabezado
                {
                    columns: [
                        {
                            text: "SENA - GAIA",
                            fontSize: 16,
                            bold: true,
                            color: "#198754"
                        },
                        {
                            text: new Date().toLocaleDateString(),
                            alignment: "right",
                            fontSize: 10,
                            color: "#6c757d",
                            margin: [0, 5, 0, 0]
                        }
                    ]
                },
                {
                    canvas: [{ type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 1.5, lineColor: '#198754' }]
                },
                { text: '\n' },
                {
                    text: 'REPORTE INDIVIDUAL DE ASIGNACIÓN',
                    style: 'header',
                    alignment: 'center',
                    margin: [0, 10, 0, 20]
                },
                // Tabla de datos
                {
                    style: 'tablaDatos',
                    table: {
                        widths: ['35%', '65%'],
                        body: [
                            [
                                { text: 'Identificación', style: 'tablaHeader' },
                                { text: String(datos.identificacion), style: 'tablaCelda' }
                            ],
                            [
                                { text: 'Aprendiz', style: 'tablaHeader' },
                                { text: String(datos.aprendiz), style: 'tablaCelda' }
                            ],
                            [
                                { text: 'Ficha', style: 'tablaHeader' },
                                { text: String(datos.ficha), style: 'tablaCelda' }
                            ],
                            [
                                { text: 'Programa de Formación', style: 'tablaHeader' },
                                { text: String(datos.programa), style: 'tablaCelda' }
                            ],
                            [
                                { text: 'Tipo de Apoyo', style: 'tablaHeader' },
                                { text: String(datos.apoyo), style: 'tablaCelda' }
                            ],
                            [
                                { text: 'Meses de Beneficio', style: 'tablaHeader' },
                                { text: String(datos.meses) + " meses", style: 'tablaCelda' }
                            ],
                            [
                                { text: 'Fecha Inicio de Pago', style: 'tablaHeader' },
                                { text: String(datos.inicio), style: 'tablaCelda' }
                            ],
                            [
                                { text: 'Fecha Fin de Pago', style: 'tablaHeader' },
                                { text: String(datos.fin), style: 'tablaCelda' }
                            ],
                            [
                                { text: 'Estado de Asignación', style: 'tablaHeader' },
                                { text: String(datos.estado).toUpperCase(), style: 'tablaCelda', bold: true, color: String(datos.estado).toLowerCase() === 'activo' ? '#198754' : '#dc3545' }
                            ]
                        ]
                    },
                    layout: {
                        fillColor: function (rowIndex, node, columnIndex) {
                            return (rowIndex % 2 === 0) ? '#f8f9fa' : null;
                        },
                        hLineColor: function (i, node) {
                            return '#e9ecef';
                        },
                        vLineColor: function (i, node) {
                            return '#e9ecef';
                        }
                    }
                }
            ],
            styles: {
                header: {
                    fontSize: 14,
                    bold: true,
                    color: '#343a40'
                },
                tablaHeader: {
                    bold: true,
                    fontSize: 10,
                    color: '#495057',
                    margin: [8, 6, 8, 6]
                },
                tablaCelda: {
                    fontSize: 10,
                    color: '#212529',
                    margin: [8, 6, 8, 6]
                }
            }
        };

        pdfMake.createPdf(docDefinition).download(`Reporte_Asignacion_${datos.identificacion}.pdf`);
    });
    // =======================================================
    // MOSTRAR DETALLE FORMATO DE TERCEROS EN MODAL
    // =======================================================
    $(document).on("click", ".btnFormatoTerceros", function() {
        let idInscripcion = $(this).attr("data-idInscripcion");
        let datos = new FormData();
        datos.append("idInscripcion", idInscripcion);

        $.ajax({
            url: "ajax/financiera.ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(respuesta) {
                $("#terNombres").val(respuesta["nombres"] || "");
                $("#terApellidos").val(respuesta["apellidos"] || "");
                $("#terTipoDocumento").val(respuesta["tipo_documento"] || "");
                $("#terNumeroDocumento").val(respuesta["identificacion"] || "");
                $("#terCorreo").val(respuesta["correo"] || "");
                $("#terBanco").val(respuesta["banco"] || "");
                $("#terNumeroCuenta").val(respuesta["numero_cuenta"] || "");

                // Campos no disponibles en BD (vacíos por defecto)
                $("#terTelefono").val("");
                $("#terDireccion").val("");
                $("#terCiudad").val("");
                $("#terCodigoCiudad").val("");
                $("#terDepartamento").val("");
                $("#terCodigoDepartamento").val("");
            }
        });
    });

    // =======================================================
    // DESCARGAR EXCEL DE FORMATO DE TERCEROS
    // =======================================================
    $(document).on("click", "#btnDescargarExcelTercero", function() {
        let nombres = $("#terNombres").val() || "";
        let apellidos = $("#terApellidos").val() || "";
        let tipoDocumento = $("#terTipoDocumento").val() || "";
        let numeroDocumento = $("#terNumeroDocumento").val() || "";
        let correo = $("#terCorreo").val() || "";
        let telefono = $("#terTelefono").val() || "";
        let direccion = $("#terDireccion").val() || "";
        let ciudad = $("#terCiudad").val() || "";
        let codigoCiudad = $("#terCodigoCiudad").val() || "";
        let departamento = $("#terDepartamento").val() || "";
        let codigoDepartamento = $("#terCodigoDepartamento").val() || "";
        let banco = $("#terBanco").val() || "";
        let numeroCuenta = $("#terNumeroCuenta").val() || "";

        let excelTemplate = `
        <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
        <head>
        <meta charset="UTF-8">
        <!--[if gte mso 9]>
        <xml>
         <x:ExcelWorkbook>
          <x:ExcelWorksheets>
           <x:ExcelWorksheet>
            <x:Name>Formato de Tercero</x:Name>
            <x:WorksheetOptions>
             <x:DisplayGridlines/>
            </x:WorksheetOptions>
           </x:ExcelWorksheet>
          </x:ExcelWorksheets>
         </x:ExcelWorkbook>
        </xml>
        <![endif]-->
        </head>
        <body>
        <table border="1">
          <tr style="background-color: #198754; color: white; font-weight: bold;">
            <th colspan="2" style="font-size: 14pt; padding: 10px; text-align: center;">FORMATO DE TERCERO</th>
          </tr>
          <tr>
            <td style="font-weight: bold; background-color: #f2f2f2; width: 200px;">Nombres</td>
            <td style="width: 300px;">${nombres}</td>
          </tr>
          <tr>
            <td style="font-weight: bold; background-color: #f2f2f2;">Apellidos</td>
            <td>${apellidos}</td>
          </tr>
          <tr>
            <td style="font-weight: bold; background-color: #f2f2f2;">Tipo de Documento</td>
            <td>${tipoDocumento}</td>
          </tr>
          <tr>
            <td style="font-weight: bold; background-color: #f2f2f2;">Número de Documento</td>
            <td style="text-align: left; mso-number-format: '\\@';">${numeroDocumento}</td>
          </tr>
          <tr>
            <td style="font-weight: bold; background-color: #f2f2f2;">Correo Electrónico</td>
            <td>${correo}</td>
          </tr>
          <tr>
            <td style="font-weight: bold; background-color: #f2f2f2;">Teléfono Celular</td>
            <td style="text-align: left; mso-number-format: '\\@';">${telefono}</td>
          </tr>
          <tr>
            <td style="font-weight: bold; background-color: #f2f2f2;">Dirección</td>
            <td>${direccion}</td>
          </tr>
          <tr>
            <td style="font-weight: bold; background-color: #f2f2f2;">Ciudad</td>
            <td>${ciudad}</td>
          </tr>
          <tr>
            <td style="font-weight: bold; background-color: #f2f2f2;">Código de Ciudad</td>
            <td style="text-align: left; mso-number-format: '\\@';">${codigoCiudad}</td>
          </tr>
          <tr>
            <td style="font-weight: bold; background-color: #f2f2f2;">Departamento</td>
            <td>${departamento}</td>
          </tr>
          <tr>
            <td style="font-weight: bold; background-color: #f2f2f2;">Código de Departamento</td>
            <td style="text-align: left; mso-number-format: '\\@';">${codigoDepartamento}</td>
          </tr>
          <tr>
            <td style="font-weight: bold; background-color: #f2f2f2;">Banco</td>
            <td>${banco}</td>
          </tr>
          <tr>
            <td style="font-weight: bold; background-color: #f2f2f2;">No. Cuenta</td>
            <td style="text-align: left; mso-number-format: '\\@';">${numeroCuenta}</td>
          </tr>
        </table>
        </body>
        </html>
        `;

        let blob = new Blob([excelTemplate], { type: "application/vnd.ms-excel;charset=utf-8;" });
        let link = document.createElement("a");
        let url = URL.createObjectURL(blob);
        link.href = url;
        link.download = `Formato_Tercero_${numeroDocumento}.xls`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    // =======================================================
    // MOSTRAR VALORES A COMPROMETER EN MODAL
    // =======================================================
    $(document).on("click", ".btnValoresComprometer", function() {
        let idInscripcion = $(this).attr("data-idInscripcion");
        let datos = new FormData();
        datos.append("idInscripcion", idInscripcion);

        $.ajax({
            url: "ajax/financiera.ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(respuesta) {
                $("#compNumeroDocumento").val(respuesta["identificacion"] || "");
                $("#compNombreAprendiz").val(respuesta["aprendiz"] || "");
                $("#compTiempo").val(respuesta["meses_beneficio"] ? respuesta["meses_beneficio"] + " meses" : "");
                $("#compBanco").val(respuesta["banco"] || "");
                $("#compNumeroCuenta").val(respuesta["numero_cuenta"] || "");

                // Campo no disponible en BD (vacío por defecto)
                $("#compValorRp").val("");
            }
        });
    });

    // =======================================================
    // DESCARGAR EXCEL DE VALORES A COMPROMETER
    // =======================================================
    $(document).on("click", "#btnDescargarExcelValores", function() {
        let numeroDocumento = $("#compNumeroDocumento").val() || "";
        let nombreAprendiz = $("#compNombreAprendiz").val() || "";
        let valorRp = $("#compValorRp").val() || "";
        let tiempo = $("#compTiempo").val() || "";
        let banco = $("#compBanco").val() || "";
        let numeroCuenta = $("#compNumeroCuenta").val() || "";

        let excelTemplate = `
        <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
        <head>
        <meta charset="UTF-8">
        <!--[if gte mso 9]>
        <xml>
         <x:ExcelWorkbook>
          <x:ExcelWorksheets>
           <x:ExcelWorksheet>
            <x:Name>Valores a Comprometer</x:Name>
            <x:WorksheetOptions>
             <x:DisplayGridlines/>
            </x:WorksheetOptions>
           </x:ExcelWorksheet>
          </x:ExcelWorksheets>
         </x:ExcelWorkbook>
        </xml>
        <![endif]-->
        </head>
        <body>
        <table border="1">
          <tr style="background-color: #198754; color: white; font-weight: bold;">
            <th colspan="2" style="font-size: 14pt; padding: 10px; text-align: center;">VALORES A COMPROMETER</th>
          </tr>
          <tr>
            <td style="font-weight: bold; background-color: #f2f2f2; width: 200px;">Número Documento</td>
            <td style="text-align: left; mso-number-format: '\\@';">${numeroDocumento}</td>
          </tr>
          <tr>
            <td style="font-weight: bold; background-color: #f2f2f2;">Nombre del Aprendiz</td>
            <td style="width: 300px;">${nombreAprendiz}</td>
          </tr>
          <tr>
            <td style="font-weight: bold; background-color: #f2f2f2;">Valor RP</td>
            <td style="text-align: left;">${valorRp}</td>
          </tr>
          <tr>
            <td style="font-weight: bold; background-color: #f2f2f2;">Tiempo</td>
            <td>${tiempo}</td>
          </tr>
          <tr>
            <td style="font-weight: bold; background-color: #f2f2f2;">Banco</td>
            <td>${banco}</td>
          </tr>
          <tr>
            <td style="font-weight: bold; background-color: #f2f2f2;">Número de Cuenta</td>
            <td style="text-align: left; mso-number-format: '\\@';">${numeroCuenta}</td>
          </tr>
        </table>
        </body>
        </html>
        `;

        let blob = new Blob([excelTemplate], { type: "application/vnd.ms-excel;charset=utf-8;" });
        let link = document.createElement("a");
        let url = URL.createObjectURL(blob);
        link.href = url;
        link.download = `Valores_Comprometer_${numeroDocumento}.xls`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
});
