//===========================================
// ACTIVAR / DESACTIVAR SEDE
//===========================================
$(document).on("click", ".btnActivarSede", function(){
    let boton = $(this);
    let estadoActual = boton.attr("data-estadoSede");
    let idSede = boton.attr("data-idSede");

    $.ajax({
        url: "ajax/sedes.ajax.php",
        method: "POST",
        data: {
            idSedeEstado: idSede,
            estado: estadoActual
        },
        success: function (respuesta) {
            if (respuesta.trim()==="ok"){
                if (estadoActual === "activo") {
                    boton.removeClass("btn-danger");
                    boton.addClass("btn-success");
                    boton.html("activo");
                    boton.attr("data-estadoSede","inactivo");
                } else {
                    boton.removeClass("btn-success");
                    boton.addClass("btn-danger");
                    boton.html("inactivo");
                    boton.attr("data-estadoSede","activo");
                }
            }
        }
    });
});

//===========================================
// EDITAR SEDE
//===========================================
$(document).on("click", ".btnEditarSede", function() {
    let idSede = $(this).attr("data-idSede");
    let datos = new FormData();
    datos.append("idSede", idSede);

    $.ajax({
        url: "ajax/sedes.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
            $("#idSedeEditar").val(respuesta["id_sede"]);
            $("#editarNombreSede").val(respuesta["descripcion_sede"]);
            $("#editarDireccionSede").val(respuesta["direccion_sede"]);
        }
    });
});

//===========================================
// CONSULTAR SEDE
//===========================================
$(document).on("click", ".btnConsultarSede", function() {
    let idSede = $(this).attr("data-idSede");
    let datos = new FormData();
    datos.append("idSede", idSede);

    $.ajax({
        url: "ajax/sedes.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
            $("#consultarNombreSede").val(respuesta["descripcion_sede"]);
            $("#consultarDireccionSede").val(respuesta["direccion_sede"]);
        }
    });
});

//===========================================
// VALIDAR NOMBRE DE LA SEDE (NUEVA O EDITADA)
//===========================================
$('[name="nuevoNombreSede"], [name="editarNombreSede"]').change(function() {
    let element = $(this);
    let nombreSede = element.val();
    let isEditing = element.attr("id") === "editarNombreSede";
    let idSedeActual = isEditing ? $("#idSedeEditar").val() : null; 
    
    let datos = new FormData();
    datos.append("validarNombre", nombreSede);

    $.ajax({
        url: "ajax/sedes.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
            if (respuesta) {
                if (!isEditing || respuesta["id_sede"] != idSedeActual) {
                    element.val("");
                    Swal.fire({
                        icon: 'error',
                        title: '¡El nombre de la sede ya existe!',
                        text: 'Por favor, ingrese un nombre de sede diferente.',
                    });
                }
            }
        }
    });
});

//===========================================
// VALIDAR DIRECCION DE LA SEDE (NUEVA O EDITADA)
//===========================================
$('[name="nuevaDireccionSede"], [name="editarDireccionSede"]').change(function() {
    let element = $(this);
    let direccionSede = element.val();
    let isEditing = element.attr("id") === "editarDireccionSede";
    let idSedeActual = isEditing ? $("#idSedeEditar").val() : null; 
    
    let datos = new FormData();
    datos.append("validarDireccion", direccionSede);

    $.ajax({
        url: "ajax/sedes.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
            if (respuesta) {
                if (!isEditing || respuesta["id_sede"] != idSedeActual) {
                    element.val("");
                    Swal.fire({
                        icon: 'error',
                        title: '¡La dirección de la sede ya existe!',
                        text: 'Por favor, ingrese una dirección diferente.',
                    });
                }
            }
        }
    });
});

//===========================================
// INACTIVACIÓN RECURSIVA: SEDE → FICHAS → APRENDICES
//===========================================
$(document).on("click", ".btnInactivarSedeRecursiva", function(){
    let boton = $(this);
    let idSede = boton.attr("data-idSede");
    
    // 1. Calcular impacto antes de confirmar
    $.ajax({
        url: "ajax/sedes.ajax.php",
        method: "POST",
        data: { idSedeImpacto: idSede },
        dataType: "json",
        success: function(impacto) {
            // 2. Mostrar SweetAlert2 con detalle del impacto
            Swal.fire({
                title: '¿Inactivar sede recursivamente?',
                html: `Esta acción inactivará en cascada:<br>
                       <b>${impacto.fichas}</b> ficha(s)<br>
                       <b>${impacto.aprendices}</b> aprendiz(es)<br><br>
                       <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Los aprendices afectados NO podrán iniciar sesión.</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, inactivar todo',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc3545',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // 3. Ejecutar inactivación recursiva
                    $.ajax({
                        url: "ajax/sedes.ajax.php",
                        method: "POST",
                        data: { idSedeInactivar: idSede },
                        success: function(respuesta) {
                            if (respuesta.trim() === "ok") {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Inactivación completada',
                                    text: 'Sede, fichas y aprendices inactivados correctamente',
                                    showConfirmButton: true,
                                    confirmButtonText: 'Aceptar'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error en la inactivación',
                                    text: respuesta,
                                    showConfirmButton: true,
                                    confirmButtonText: 'Cerrar'
                                });
                            }
                        }
                    });
                }
            });
        }
    });
});
