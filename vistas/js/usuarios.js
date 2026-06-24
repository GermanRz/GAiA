function validarComplejidadPassword(password) {
    return {
        longitud: password.length >= 8,
        mayuscula: /[A-Z]/.test(password),
        minuscula: /[a-z]/.test(password),
        numero: /[0-9]/.test(password),
        especial: /[^A-Za-z0-9]/.test(password)
    };
}

function passwordCumplePolitica(validaciones) {
    return Object.keys(validaciones).every(function (clave) {
        return validaciones[clave];
    });
}

function pintarFeedbackPassword(input) {
    let password = input.val();
    let feedback = input.closest("form").find(".password-feedback").first();

    if (!password) {
        feedback.empty();
        input.removeClass("is-valid is-invalid");
        return null;
    }

    let validaciones = validarComplejidadPassword(password);
    let mensajes = [
        { clave: "longitud", texto: "Mínimo 8 caracteres" },
        { clave: "mayuscula", texto: "Al menos 1 mayúscula" },
        { clave: "minuscula", texto: "Al menos 1 minúscula" },
        { clave: "numero", texto: "Al menos 1 número" },
        { clave: "especial", texto: "Al menos 1 carácter especial" }
    ];
    let contenido = mensajes.map(function (mensaje) {
        let icono = validaciones[mensaje.clave] ? "fa-check" : "fa-times";
        let estilo = validaciones[mensaje.clave]
            ? 'color:#28a745;'
            : 'color:#dc3545;';
        return '<div class="d-flex align-items-center mb-1" style="font-weight:600;' + estilo + '"><i class="fas ' + icono + ' mr-2"></i><span>' + mensaje.texto + '</span></div>';
    }).join("");

    input.attr("type", "password");
    feedback.html('<div class="p-2 border rounded" style="background:#343a40;color:#ffffff;border-color:#454d55 !important;">' + contenido + '</div>');
    input.toggleClass("is-valid", password !== "" && passwordCumplePolitica(validaciones));
    input.toggleClass("is-invalid", password !== "" && !passwordCumplePolitica(validaciones));

    return passwordCumplePolitica(validaciones);
}

function pintarFeedbackConfirmacion(inputPassword, inputConfirmacion) {
    let feedback = inputConfirmacion.closest("form").find(".password-confirm-feedback").first();
    let password = inputPassword.val();
    let confirmacion = inputConfirmacion.val();

    if (!password || !confirmacion) {
        feedback.empty();
        inputConfirmacion.removeClass("is-valid is-invalid");
        return null;
    }

    let coincide = password === confirmacion;

    feedback.html(
        coincide
            ? '<div class="p-2 border rounded" style="background:#343a40;color:#28a745;border-color:#454d55 !important;font-weight:600;">Las contraseñas coinciden.</div>'
            : '<div class="p-2 border rounded" style="background:#343a40;color:#dc3545;border-color:#454d55 !important;font-weight:600;">Las contraseñas no coinciden.</div>'
    );

    inputConfirmacion.toggleClass("is-valid", coincide);
    inputConfirmacion.toggleClass("is-invalid", confirmacion !== "" && !coincide);

    return coincide;
}

$(document).on("click", ".js-toggle-password", function () {
    let targetName = $(this).attr("data-target");
    let input = $(this).closest("form").find('[name="' + targetName + '"]').first();
    let icon = $(this).find("i");

    if (!input.length) {
        return;
    }

    if (input.attr("type") === "password") {
        input.attr("type", "text");
        icon.removeClass("fa-eye").addClass("fa-eye-slash");
    } else {
        input.attr("type", "password");
        icon.removeClass("fa-eye-slash").addClass("fa-eye");
    }
});

$(document).on("input", ".js-password-segura", function () {
    let input = $(this);
    pintarFeedbackPassword(input);
    let inputConfirmacion = input.closest("form").find(".js-password-confirmacion").first();
    if (inputConfirmacion.length && inputConfirmacion.val()) {
        pintarFeedbackConfirmacion(input, inputConfirmacion);
    } else if (inputConfirmacion.length && !input.val()) {
        inputConfirmacion.closest("form").find(".password-confirm-feedback").first().empty();
        inputConfirmacion.removeClass("is-valid is-invalid");
    }
});

$(document).on("input", ".js-password-confirmacion", function () {
    let inputConfirmacion = $(this);
    let inputPassword = inputConfirmacion.closest("form").find(".js-password-segura").first();

    if (inputPassword.length) {
        pintarFeedbackConfirmacion(inputPassword, inputConfirmacion);
    }
});

$(document).on("submit", "form", function (event) {
    let passwordInput = $(this).find(".js-password-segura");
    let confirmacionInput = $(this).find(".js-password-confirmacion");
    let passwordOk = true;
    let confirmacionOk = true;

    if (passwordInput.length) {
        passwordOk = pintarFeedbackPassword(passwordInput.first());
    }

    if (confirmacionInput.length && passwordInput.length) {
        confirmacionOk = pintarFeedbackConfirmacion(passwordInput.first(), confirmacionInput.first());
    }

    if (passwordInput.length && passwordInput.val() && confirmacionInput.length && confirmacionInput.val() && (!passwordOk || !confirmacionOk)) {
        event.preventDefault();
        Swal.fire({
            icon: "error",
            title: "Contraseña insegura",
            html: '<div style="font-size:1.1rem;line-height:1.5;color:#212529;">La contraseña debe tener mínimo 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.<br><strong style="color:#dc3545;">Las contraseñas deben coincidir.</strong></div>',
            confirmButtonText: "Cerrar"
        });
    }
});

$(document).on("hidden.bs.modal", "#modal-agregarUsuario, #modal-registroAprendiz", function () {
    let form = $(this).find("form").first();
    if (!form.length) {
        return;
    }

    form.find(".js-password-segura, .js-password-confirmacion").val("").removeClass("is-valid is-invalid").attr("type", "password");
    form.find(".password-feedback, .password-confirm-feedback").empty();
    form.find(".js-toggle-password i").removeClass("fa-eye-slash").addClass("fa-eye");
});

$('#nuevoDocumento').change(function () {

    let nuevoDocumento = $(this).val();
    console.log("este es el documento a ingresar: " + nuevoDocumento);
    let datos = new FormData();
    datos.append("nuevoDocumento", nuevoDocumento);
    $.ajax({
        url: "ajax/usuarios.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {
            console.log(respuesta);
            if (respuesta) {
                $("#nuevoDocumento").val("");
                Swal.fire({
                    icon: 'error',
                    title: '¡El documento ya existe!',
                    text: 'Por favor, ingrese un documento diferente.',
                });
            }
        },
    }); //ajax



}); //fin de nuevoDocumento.change

$(document).on("click", ".btnActivarUsuario", function () {
    let boton = $(this);
    let estadoActual = boton.attr("data-estadoUsuario");
    let idUsuario = boton.attr("data-idUsuario");
    // console.log("estadoUsuario", estadoActual);
    // console.log("idUsuario", idUsuario);

    $.ajax({
        url: "ajax/usuarios.ajax.php",
        method: "POST",
        data: {
            idUsuarioEstado: idUsuario,
            estado: estadoActual
        },
        success: function (respuesta) {
            // console.log(respuesta);

            if (respuesta.trim() === "ok") {
                if (estadoActual === "activo") {
                    boton.removeClass("btn-danger");
                    boton.addClass("btn-success");
                    boton.html("activo");
                    boton.attr("data-estadoUsuario", "inactivo");
                } else {
                    boton.removeClass("btn-success");
                    boton.addClass("btn-danger");
                    boton.html("inactivo");
                    boton.attr("data-estadoUsuario", "activo");
                }
            }
        }
    });

});

// Mostrar ocultar ficha según rol
$('#nuevoRol').change(function () {
    let rol = $(this).val();
    if (rol === "Aprendiz") {
        $('#divFicha').show();
        $('#inputFicha').attr('required', true);
        $('#nuevaDireccion').attr('required', true);
        $('#nuevoTelefono').attr('required', true);
        $('#nuevoDepartamento').attr('required', true);
        $('#nuevaCiudad').attr('required', true);
    } else {
        $('#divFicha').hide();
        $('#inputFicha').val('');
        $('#nuevaFicha').val('');
        $('#inputFicha').removeAttr('required');
        $('#divDescripcionFicha').hide();
        $('#descripcionFicha').val('');
        $('#nuevaDireccion').removeAttr('required');
        $('#nuevoTelefono').removeAttr('required');
        $('#nuevoDepartamento').removeAttr('required');
        $('#nuevaCiudad').removeAttr('required');
    }
});

// Autocompletar descripcion ficha
$('#inputFicha').on('input', function () {
    let val = $(this).val();
    let option = $('#listaFichas option').filter(function () {
        return this.value === val;
    });

    if (option.length) {
        let idFicha = option.attr('data-id');
        let programa = option.attr('data-programa');
        $('#nuevaFicha').val(idFicha);
        $('#descripcionFicha').val(programa);
        $('#divDescripcionFicha').show();
    } else {
        $('#nuevaFicha').val('');
        $('#descripcionFicha').val('');
        $('#divDescripcionFicha').hide();
    }
});

//===========================================
// EDITAR USUARIO
//===========================================
$(document).on("click", ".btnEditarUsuario", function () {
    let idUsuario = $(this).attr("data-idUsuario");
    let datos = new FormData();
    datos.append("idUsuario", idUsuario);

    $.ajax({
        url: "ajax/usuarios.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {
            $("#idUsuarioEditar").val(respuesta["id"]);
            $("#editarTipoDocumento").val(respuesta["tipo_documento"]);
            $("#editarDocumento").val(respuesta["documento_id"]);
            $("#editarNombre").val(respuesta["nombres"]);
            $("#editarApellido").val(respuesta["apellidos"]);
            $("#editarFechaNacimiento").val(respuesta["fecha_nacimiento"]);
            $("#editarCorreo").val(respuesta["correo"]);

            // Seleccionar el rol (ignorando mayúsculas/minúsculas)
            let rolDB = respuesta["rol"].toUpperCase();
            $("#editarRol option").each(function () {
                if ($(this).val().toUpperCase() === rolDB) {
                    $(this).prop("selected", true);
                }
            });

            $("#passwordActual").val(respuesta["password"]);
            $("#fotoActualEditar").val(respuesta["foto"]);

            if (respuesta["foto"] != "" && respuesta["foto"] != null) {
                $(".previsualizarEditar").attr("src", respuesta["foto"]);
            } else {
                $(".previsualizarEditar").attr("src", "documentos/anonimo/anonimo.png");
            }

            if (respuesta["contacto"]) {
                $("#editarDireccion").val(respuesta["contacto"]["direccion"]);
                $("#editarTelefono").val(respuesta["contacto"]["telefono"]);
                $("#editarDepartamento").val(respuesta["contacto"]["codigo_dep"]);
                cargarCiudades(respuesta["contacto"]["codigo_dep"], "#editarCiudad", respuesta["contacto"]["codigo_ciu"]);
            } else {
                $("#editarDireccion").val("");
                $("#editarTelefono").val("");
                $("#editarDepartamento").val("");
                $("#editarCiudad").html('<option value="">Seleccionar Municipio/Ciudad</option>');
            }

            // Mostrar opcion de eliminar foto si no es la por defecto
            if (respuesta["foto"] != "" && respuesta["foto"] != "documentos/anonimo/anonimo.png" && respuesta["foto"] != null) {
                $("#divEliminarFoto").show();
                $("#eliminarFotoUsuario").prop("checked", false);
            } else {
                $("#divEliminarFoto").hide();
                $("#eliminarFotoUsuario").prop("checked", false);
            }

            // Lógica para Aprendiz
            if (respuesta["rol"] === "Aprendiz" || respuesta["rol"] === "APRENDIZ") {
                $("#divEditarFicha").show();
                $("#inputEditarFicha").attr('required', true);

                // Si trae ficha guardada, pre-cargar el input
                if (respuesta["ficha_id"]) {
                    let fichaOption = $('#listaFichas option[data-id="' + respuesta["ficha_id"] + '"]');
                    if (fichaOption.length > 0) {
                        $("#inputEditarFicha").val(fichaOption.attr('value'));
                        $("#editarFicha").val(respuesta["ficha_id"]);
                        $("#descripcionEditarFicha").val(fichaOption.attr('data-programa'));
                        $("#divDescripcionEditarFicha").show();
                    }
                }
            } else {
                $("#divEditarFicha").hide();
                $("#inputEditarFicha").removeAttr('required');
                $("#inputEditarFicha").val('');
                $("#editarFicha").val('');
                $("#divDescripcionEditarFicha").hide();
                $("#descripcionEditarFicha").val('');
            }
        }
    });
});

// Mostrar ocultar ficha según rol en EDICIÓN
$('#editarRol').change(function () {
    let rol = $(this).val();
    if (rol === "Aprendiz" || rol === "APRENDIZ") {
        $('#divEditarFicha').show();
        $('#inputEditarFicha').attr('required', true);
        $('#editarDireccion').attr('required', true);
        $('#editarTelefono').attr('required', true);
        $('#editarDepartamento').attr('required', true);
        $('#editarCiudad').attr('required', true);
    } else {
        $('#divEditarFicha').hide();
        $('#inputEditarFicha').val('');
        $('#editarFicha').val('');
        $('#inputEditarFicha').removeAttr('required');
        $('#divDescripcionEditarFicha').hide();
        $('#descripcionEditarFicha').val('');
        $('#editarDireccion').removeAttr('required');
        $('#editarTelefono').removeAttr('required');
        $('#editarDepartamento').removeAttr('required');
        $('#editarCiudad').removeAttr('required');
    }
});

// Autocompletar descripcion ficha en EDICIÓN
$('#inputEditarFicha').on('input', function () {
    let val = $(this).val();
    let option = $('#listaFichas option').filter(function () {
        return this.value === val;
    });

    if (option.length) {
        let idFicha = option.attr('data-id');
        let programa = option.attr('data-programa');
        $('#editarFicha').val(idFicha);
        $('#descripcionEditarFicha').val(programa);
        $('#divDescripcionEditarFicha').show();
    } else {
        $('#editarFicha').val('');
        $('#descripcionEditarFicha').val('');
        $('#divDescripcionEditarFicha').hide();
    }
});

//===========================================
// CONSULTAR USUARIO
//===========================================
$(document).on("click", ".btnConsultarUsuario", function () {
    let idUsuario = $(this).attr("data-idUsuario");
    let datos = new FormData();
    datos.append("idUsuario", idUsuario);

    $.ajax({
        url: "ajax/usuarios.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {
            $("#consultarTipoDocumento").val(respuesta["tipo_documento"]);
            $("#consultarDocumento").val(respuesta["documento_id"]);
            $("#consultarNombre").val(respuesta["nombres"]);
            $("#consultarApellido").val(respuesta["apellidos"]);
            $("#consultarFechaNacimiento").val(respuesta["fecha_nacimiento"]);
            $("#consultarCorreo").val(respuesta["correo"]);
            $("#consultarRol").val(respuesta["rol"]);

            // Lógica para Aprendiz (Ficha)
            if (respuesta["rol"] === "Aprendiz" || respuesta["rol"] === "APRENDIZ") {
                $("#divConsultarFicha").show();

                // Buscar ficha en el datalist para mostrar el programa
                if (respuesta["ficha_id"]) {
                    let fichaOption = $('#listaFichas option[data-id="' + respuesta["ficha_id"] + '"]');
                    if (fichaOption.length > 0) {
                        $("#inputConsultarFicha").val(fichaOption.attr('value')); // código
                        $("#descripcionConsultarFicha").val(fichaOption.attr('data-programa'));
                        $("#divDescripcionConsultarFicha").show();
                    } else {
                        $("#inputConsultarFicha").val(respuesta["ficha_id"]);
                        $("#divDescripcionConsultarFicha").hide();
                    }
                } else {
                    $("#inputConsultarFicha").val("Sin ficha asignada");
                    $("#divDescripcionConsultarFicha").hide();
                }
            } else {
                $("#divConsultarFicha").hide();
                $("#divDescripcionConsultarFicha").hide();
                $("#inputConsultarFicha").val('');
                $("#descripcionConsultarFicha").val('');
            }
        }
    });
});

/*=============================================
SUBIENDO LA FOTO DEL USUARIO (PREVISUALIZACIÓN Y VALIDACIÓN)
=============================================*/
$(document).on("change", ".nuevaFoto", function () {
    let imagen = this.files[0];
    if (!imagen) {
        $(this).next('.custom-file-label').html('Seleccionar imagen');
        return;
    }

    // Actualizar el label del custom-file-input
    let fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').html(fileName);

    /*=============================================
    VALIDAMOS EL FORMATO DE LA IMAGEN SEA JPG O PNG
    =============================================*/
    if (imagen["type"] !== "image/jpeg" && imagen["type"] !== "image/png") {
        $(this).val("");
        Swal.fire({
            icon: 'error',
            title: '¡Error al subir la imagen!',
            text: 'La imagen debe estar en formato JPG o PNG',
            confirmButtonText: 'Cerrar'
        });
    } else if (imagen["size"] > 4194304) { // 4MB MAX
        $(this).val("");
        Swal.fire({
            icon: 'error',
            title: '¡Error al subir la imagen!',
            text: 'La imagen no debe pesar más de 4MB',
            confirmButtonText: 'Cerrar'
        });
    } else {
        let datosImagen = new FileReader();
        datosImagen.readAsDataURL(imagen);

        let inputElement = $(this);
        $(datosImagen).on("load", function (event) {
            let rutaImagen = event.target.result;
            // Busca la imagen previsualizar correspondiente dentro del mismo form-group
            inputElement.closest(".form-group").find(".previsualizar").attr("src", rutaImagen);
            inputElement.closest(".form-group").find(".previsualizarEditar").attr("src", rutaImagen);
        });
    }
});

/*=============================================
CARGAR CIUDADES POR DEPARTAMENTO
=============================================*/
function cargarCiudades(codigo_dep, selectorCiudad, ciudadSeleccionada = null) {
    if (!codigo_dep) {
        $(selectorCiudad).html('<option value="">Seleccionar Municipio/Ciudad</option>');
        return;
    }
    let datos = new FormData();
    datos.append("codigo_dep", codigo_dep);

    $.ajax({
        url: "ajax/usuarios.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {
            $(selectorCiudad).html('<option value="">Seleccionar Municipio/Ciudad</option>');
            if (respuesta && respuesta.length > 0) {
                respuesta.forEach(function (ciudad) {
                    let selected = (ciudadSeleccionada == ciudad.codigo_ciu) ? "selected" : "";
                    $(selectorCiudad).append('<option value="' + ciudad.codigo_ciu + '" ' + selected + '>' + ciudad.nombre + '</option>');
                });
            }
        }
    });
}

$("#nuevoDepartamentoLogin").change(function () {
    cargarCiudades($(this).val(), "#nuevaCiudadLogin");
});

$("#nuevoDepartamento").change(function () {
    cargarCiudades($(this).val(), "#nuevaCiudad");
});

$("#editarDepartamento").change(function () {
    cargarCiudades($(this).val(), "#editarCiudad");
});

$("#editarDepartamentoPerfil").change(function () {
    cargarCiudades($(this).val(), "#editarCiudadPerfil");
});

/*=============================================
CARGAR DATOS DE CONTACTO AL ABRIR PERFIL
=============================================*/
$('[data-target="#modal-miPerfil"]').click(function () {
    let idPerfil = $("input[name='idPerfil']").val();
    let datos = new FormData();
    datos.append("idUsuario", idPerfil);

    $.ajax({
        url: "ajax/usuarios.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {
            if (respuesta && respuesta["contacto"]) {
                $("#editarDireccionPerfil").val(respuesta["contacto"]["direccion"]);
                $("#editarTelefonoPerfil").val(respuesta["contacto"]["telefono"]);
                $("#editarDepartamentoPerfil").val(respuesta["contacto"]["codigo_dep"]);
                cargarCiudades(respuesta["contacto"]["codigo_dep"], "#editarCiudadPerfil", respuesta["contacto"]["codigo_ciu"]);
            } else {
                $("#editarDireccionPerfil").val("");
                $("#editarTelefonoPerfil").val("");
                $("#editarDepartamentoPerfil").val("");
                $("#editarCiudadPerfil").html('<option value="">Seleccionar Municipio/Ciudad</option>');
            }
        }
    });
});
