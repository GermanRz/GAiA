
// 1. Botón Guardar / Aceptar (genérico para varios modales)
$(document).on("click", ".btn-accion-guardar", function() {
    $("#modalNotificacionSuccess .modal-body p").html("<strong><i class='fas fa-check'></i> Informacion guardada correctamente</strong>");
    $("#modalNotificacionSuccess").modal("show");
});

// 2. Botón Enviar a Financiera (Validar/Rechazar)
$(document).on("click", ".btn-enviar-financiera", function() {
    $("#modalNotificacionSuccess .modal-body p").html("<strong><i class='fas fa-check'></i> Documento enviado</strong><br><br>Documentos:<br>• Formato_de_terceros_Pepito<br>• Valores a comprometer<br>Estado: Enviado<br><br>El documento fue enviado con exito al area financiera");
    $("#modalNotificacionSuccess").modal("show");
});

// 3. Botón Notificar Aprendiz (Info Bancaria)
$(document).on("click", ".btn-notificar", function() {
    $("#modalNotificacionSuccess .modal-body p").html("<strong><i class='fas fa-check'></i> Se notificó al aprendiz</strong>");
    $("#modalNotificacionSuccess").modal("show");
});

// 4. Botón Reporte por aprendiz (.xls)
$(document).on("click", ".btn-reporte-xls", function() {
    $('#modalDescargarReportes').modal('hide');
    $("#modalNotificacionSuccess .modal-body p").html("<strong><i class='fas fa-check'></i> reporte \"apoyo ___.xls\" descargado correctamente</strong>");
    $("#modalNotificacionSuccess").modal("show");
});

// 5. Botón Reporte en excel (.zip)
$(document).on("click", ".btn-reporte-zip", function() {
    $('#modalDescargarReportes').modal('hide');
    $("#modalNotificacionSuccess .modal-body p").html("<strong><i class='fas fa-check'></i> reporte \"aprendices ___.zip\" descargado correctamente</strong>");
    $("#modalNotificacionSuccess").modal("show");
});
$(document).ready(function () {
    // Configuración base de datatables
    // var tableOptions = {
    //     "responsive": true,
    //     "lengthChange": false,
    //     "autoWidth": false,
    //     "searching": true,
    //     "paging": true,
    //     "info": false,
    //     "dom": 'rt', // Quitamos filtros y paginación por defecto, los manejaremos custom
    //     "pageLength": 5
    // };

    // // Inicializar cada tabla
    // var tblSostenimiento = $("#tblSostenimiento").DataTable(tableOptions);
    // var tblDatos = $("#tblDatos").DataTable(tableOptions);
    // var tblAlimentacion = $("#tblAlimentacion").DataTable(tableOptions);
    // var tblTransporte = $("#tblTransporte").DataTable(tableOptions);

    var tablesMap = {
        "#tab-sostenimiento": tblSostenimiento,
        "#tab-datos": tblDatos,
        "#tab-alimentacion": tblAlimentacion,
        "#tab-transporte": tblTransporte
    };

    var activeTableId = "#tab-sostenimiento";
    var activeTable = tablesMap[activeTableId];

    // Manejo de Tabs (Apoyos)
    $(".btn-apoyo").click(function () {
        // Estilos del tab
        $(".btn-apoyo").removeClass("active");
        $(this).addClass("active");

        // Mostrar tabla
        var targetId = $(this).data("target");
        $(".tab-pane").removeClass("show active");
        $(targetId).addClass("show active");

        // Actualizar tabla activa
        activeTableId = targetId;
        activeTable = tablesMap[activeTableId];
        
        redibujarPaginacion();
    });

    // Buscador global custom
    $("#buscadorAprendiz").on("keyup", function () {
        var val = $(this).val();
        // Buscar en todas las tablas
        tblSostenimiento.search(val).draw();
        tblDatos.search(val).draw();
        tblAlimentacion.search(val).draw();
        tblTransporte.search(val).draw();
        redibujarPaginacion();
    });

    // Paginación custom
    function redibujarPaginacion() {
        if(!activeTable) return;
        var info = activeTable.page.info();
        var current = info.page + 1;

        var html = '<div class="custom-paginate-wrap">';
        html += '<div class="custom-paginate-btn btn-prev">Anterior</div>';
        html += '<div class="custom-paginate-btn active">' + current + '</div>';
        html += '<div class="custom-paginate-btn btn-next">Siguiente</div>';
        html += '</div>';

        $("#paginacionCustom").html(html);

        $(".btn-prev").off('click').on('click', function() {
            if (activeTable.page() > 0) {
                activeTable.page('previous').draw('page');
                redibujarPaginacion();
            }
        });

        $(".btn-next").off('click').on('click', function() {
            if (activeTable.page() < activeTable.page.info().pages - 1) {
                activeTable.page('next').draw('page');
                redibujarPaginacion();
            }
        });
    }

    // Inicializar paginación
    redibujarPaginacion();
});
