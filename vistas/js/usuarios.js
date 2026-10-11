//************************************************************
//  SERVERSIDE USUARIOS
//************************************************************
if ($("#tblUsuarios").length) {
    if ($.fn.DataTable.isDataTable("#tblUsuarios")) {
        $("#tblUsuarios").DataTable().destroy();
    }
    let tablaUsuarios = $("#tblUsuarios").DataTable({
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "sAjaxSource": "ajax/serverside/serverside.usuarios.php",
        "columns": [
            { "data": 0, "width": "4%", "responsivePriority": 1 },
            { "data": 1, "width": "6%", "className": "text-center" },
            { "data": 2, "width": "11%", "responsivePriority": 2 },
            { "data": 3, "width": "15%" },
            { "data": 4, "width": "15%" },
            { "data": 5, "width": "20%" },
            { "data": 6, "width": "8%", "className": "text-center" },
            { "data": 7, "width": "8%", "className": "text-center" },
            { "data": 8, "width": "6%", "className": "text-center", "responsivePriority": 2 },
            { "data": null, "width": "7%", "className": "text-center", "orderable": false, "searchable": false, "responsivePriority": 1 }
        ],
        "columnDefs": [
            {
                "targets": [8],
                "render": function(data, type, row) {
                    let idUsuario = row[0];
                    if (data === "activo") {
                        return "<button class='btn btn-xs btn-success btnActivarUsuario' data-estadoUsuario='inactivo' data-idUsuario='" + idUsuario + "'>activo</button>";
                    } else {
                        return "<button class='btn btn-xs btn-danger btnActivarUsuario' data-estadoUsuario='activo' data-idUsuario='" + idUsuario + "'>inactivo</button>";
                    }
                }
            },
            {
                "targets": [-1],
                "render": function(data, type, row) {
                    let idUsuario = row[0];
                    return '<div class="btn-group">' +
                        '<button class="btn btn-sm btn-outline-light btnEditarUsuario" data-idUsuario="' + idUsuario + '" data-toggle="modal" data-target="#modal-editarUsuario" title="Editar usuario"><i class="fas fa-edit"></i></button>' +
                        '<button class="btn btn-sm btn-outline-light btnConsultarUsuario" data-idUsuario="' + idUsuario + '" data-toggle="modal" data-target="#modal-consultarUsuario" title="Consultar detalles"><i class="fas fa-eye"></i></button>' +
                    '</div>';
                }
            }
        ],
        "drawCallback": function() {
            this.api().columns.adjust();
        },
        "responsive": true,
        "autoWidth": false,
        "lengthChange": true,
        "lengthMenu": [10, 25, 50, 100],
        "language": {
            "processing": "Procesando...",
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "emptyTable": "No hay datos disponibles en la tabla",
            "info": "Mostrando página _PAGE_ de _PAGES_ (_TOTAL_ registros)",
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "search": "Buscar:",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        "buttons": ["excel", "pdf"],
        "dom": "<'row'<'col-sm-12 col-md-6'lB><'col-sm-12 col-md-6'f>>" +
               "<'row'<'col-sm-12'tr>>" +
               "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
    });
}

$('#nuevoDocumento').change(function() {

    let nuevoDocumento = $(this).val();
    console.log("este es el documento a ingresar: " +nuevoDocumento);
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
        success: function(respuesta) {
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

$(document).on("click", ".btnActivarUsuario", function(){
    let boton = $(this);
    let estadoActual = boton.attr("data-estadoUsuario");
    let idUsuario = boton.attr("data-idUsuario");

    $.ajax({
        url: "ajax/usuarios.ajax.php",
        method: "POST",
        data: {
            idUsuarioEstado: idUsuario,
            estado: estadoActual
        },
        success: function (respuesta) {
            let res = (respuesta || "").trim();

            if (res === "ok") {
                if (estadoActual === "activo") {
                    boton.removeClass("btn-danger");
                    boton.addClass("btn-success");
                    boton.html("activo");
                    boton.attr("data-estadoUsuario","inactivo");
                    if (typeof toastr !== "undefined") {
                        toastr.success("El usuario ha sido activado correctamente.");
                    }
                } else {
                    boton.removeClass("btn-success");
                    boton.addClass("btn-danger");
                    boton.html("inactivo");
                    boton.attr("data-estadoUsuario","activo");
                    if (typeof toastr !== "undefined") {
                        toastr.warning("El usuario ha sido desactivado.");
                    }
                }
            } else if (res === "self") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Acción no permitida',
                    text: 'No puede desactivar su propia cuenta mientras está en sesión.',
                    confirmButtonText: 'Entendido'
                });
            } else if (res === "forbidden") {
                Swal.fire({
                    icon: 'error',
                    title: 'Permiso insuficiente',
                    text: 'No tiene permisos para cambiar el estado de este usuario.',
                    confirmButtonText: 'Cerrar'
                });
            } else {
                if (typeof toastr !== "undefined") {
                    toastr.error("No se pudo actualizar el estado del usuario.");
                }
            }
        }
    });

});

// Mostrar ocultar ficha según rol
$('#nuevoRol').change(function() {
    let rol = $(this).val();
    if (rol === "Aprendiz" || rol === "APRENDIZ") {
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
$('#inputFicha').on('input', function() {
    let val = $(this).val();
    let option = $('#listaFichas option').filter(function() {
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

// Validar que se seleccione una ficha existente de la lista al enviar formularios de Aprendiz
$(document).on("submit", "#formAgregarUsuario, #formRegistroAprendiz", function(e) {
    let rol = $(this).find("select[name='nuevoRol']").val();
    let esRegistroLogin = ($(this).attr("id") === "formRegistroAprendiz");
    if (esRegistroLogin || rol === "APRENDIZ" || rol === "Aprendiz") {
        let fichaId = $(this).find("#nuevaFicha").val();
        if (!fichaId) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Ficha no válida',
                text: 'Por favor seleccione una ficha de formación válida de la lista desplegable.',
                confirmButtonText: 'Entendido'
            });
            return false;
        }
    }
});

$(document).on("submit", "#formEditarUsuario", function(e) {
    let rol = $("#editarRol").val();
    if (rol === "APRENDIZ" || rol === "Aprendiz") {
        let fichaId = $("#editarFicha").val();
        if (!fichaId) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Ficha no válida',
                text: 'Por favor seleccione una ficha de formación válida de la lista desplegable.',
                confirmButtonText: 'Entendido'
            });
            return false;
        }
    }
});

//===========================================
// EDITAR USUARIO
//===========================================
$(document).on("click", ".btnEditarUsuario", function() {
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
        success: function(respuesta) {
            if (!respuesta) return;

            $("#idUsuarioEditar").val(respuesta["id"]);
            $("#editarTipoDocumento").val(respuesta["tipo_documento"]);
            $("#editarDocumento").val(respuesta["documento_id"]);
            $("#editarNombre").val(respuesta["nombres"]);
            $("#editarApellido").val(respuesta["apellidos"]);
            $("#editarFechaNacimiento").val(respuesta["fecha_nacimiento"]);
            $("#editarCorreo").val(respuesta["correo"]);
            
            // Seleccionar el rol (ignorando mayúsculas/minúsculas)
            let rolDB = (respuesta["rol"] || "").toUpperCase();
            $("#editarRol option").each(function() {
                if($(this).val().toUpperCase() === rolDB) {
                    $(this).prop("selected", true);
                }
            });

            $("#passwordActual").val(respuesta["password"]);
            $("#fotoActualEditar").val(respuesta["foto"]);

            if(respuesta["foto"] != "" && respuesta["foto"] != null){
                $(".previsualizarEditar").attr("src", respuesta["foto"]);
            } else {
                $(".previsualizarEditar").attr("src", "documentos/anonimo/anonimo.png");
            }

            if(respuesta["contacto"]) {
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
            if(respuesta["foto"] != "" && respuesta["foto"] != "documentos/anonimo/anonimo.png" && respuesta["foto"] != null){
                $("#divEliminarFoto").show();
                $("#eliminarFotoUsuario").prop("checked", false);
            } else {
                $("#divEliminarFoto").hide();
                $("#eliminarFotoUsuario").prop("checked", false);
            }

            // Lógica para Aprendiz
            if(rolDB === "APRENDIZ"){
                $("#divEditarFicha").show();
                $("#inputEditarFicha").attr('required', true);
                
                // Si trae ficha guardada, pre-cargar el input (con respaldo directo del JOIN si la ficha está inactiva)
                if(respuesta["ficha_id"]){
                    let fichaOption = $('#listaFichas option[data-id="'+respuesta["ficha_id"]+'"]');
                    $("#editarFicha").val(respuesta["ficha_id"]);
                    if(fichaOption.length > 0){
                        $("#inputEditarFicha").val(fichaOption.attr('value'));
                        $("#descripcionEditarFicha").val(fichaOption.attr('data-programa'));
                        $("#divDescripcionEditarFicha").show();
                    } else if (respuesta["codigo_ficha"]) {
                        $("#inputEditarFicha").val(respuesta["codigo_ficha"]);
                        $("#descripcionEditarFicha").val(respuesta["programa_ficha"] || "");
                        $("#divDescripcionEditarFicha").show();
                    }
                } else {
                    $("#inputEditarFicha").val('');
                    $("#editarFicha").val('');
                    $("#divDescripcionEditarFicha").hide();
                    $("#descripcionEditarFicha").val('');
                }
            }else{
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
$('#editarRol').change(function() {
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
$('#inputEditarFicha').on('input', function() {
    let val = $(this).val();
    let option = $('#listaFichas option').filter(function() {
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
$(document).on("click", ".btnConsultarUsuario", function() {
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
        success: function(respuesta) {
            if (!respuesta) return;

            // Foto del usuario
            if (respuesta["foto"] && respuesta["foto"] !== "") {
                $("#consultarFoto").attr("src", respuesta["foto"]);
            } else {
                $("#consultarFoto").attr("src", "documentos/anonimo/anonimo.png");
            }

            $("#consultarTipoDocumento").val(respuesta["tipo_documento"] || "");
            $("#consultarDocumento").val(respuesta["documento_id"] || "");
            $("#consultarNombre").val(respuesta["nombres"] || "");
            $("#consultarApellido").val(respuesta["apellidos"] || "");
            $("#consultarFechaNacimiento").val(respuesta["fecha_nacimiento"] || "");
            $("#consultarCorreo").val(respuesta["correo"] || "");
            $("#consultarRol").val(respuesta["rol"] || "");

            // Datos de contacto
            if (respuesta["contacto"]) {
                $("#consultarDireccion").val(respuesta["contacto"]["direccion"] || "No registrada");
                $("#consultarTelefono").val(respuesta["contacto"]["telefono"] || "No registrado");
                $("#consultarDepartamento").val(respuesta["contacto"]["nombre_departamento"] || respuesta["contacto"]["codigo_dep"] || "No registrado");
                $("#consultarCiudad").val(respuesta["contacto"]["nombre_ciudad"] || respuesta["contacto"]["codigo_ciu"] || "No registrado");
            } else {
                $("#consultarDireccion").val("No registrada");
                $("#consultarTelefono").val("No registrado");
                $("#consultarDepartamento").val("No registrado");
                $("#consultarCiudad").val("No registrado");
            }

            // Documento de identidad maestro (PDF)
            let rolDB = (respuesta["rol"] || "").toUpperCase();
            if (rolDB === "APRENDIZ" || respuesta["doc_identidad_maestro_url"]) {
                $("#divConsultarDocIdentidad").show();
                if (respuesta["doc_identidad_maestro_url"] && respuesta["doc_identidad_maestro_url"] !== "") {
                    $("#btnVerDocIdentidadMaestro").attr("href", respuesta["doc_identidad_maestro_url"]).show();
                    $("#sinDocIdentidadMaestro").hide();
                } else {
                    $("#btnVerDocIdentidadMaestro").attr("href", "#").hide();
                    $("#sinDocIdentidadMaestro").show();
                }
            } else {
                $("#divConsultarDocIdentidad").hide();
            }

            // Lógica para Aprendiz (Ficha)
            if (rolDB === "APRENDIZ") {
                $("#divConsultarFicha").show();
                
                if (respuesta["ficha_id"]) {
                    let fichaOption = $('#listaFichas option[data-id="'+respuesta["ficha_id"]+'"]');
                    if (fichaOption.length > 0) {
                        $("#inputConsultarFicha").val(fichaOption.attr('value'));
                        $("#descripcionConsultarFicha").val(fichaOption.attr('data-programa'));
                        $("#divDescripcionConsultarFicha").show();
                    } else if (respuesta["codigo_ficha"]) {
                        $("#inputConsultarFicha").val(respuesta["codigo_ficha"]);
                        $("#descripcionConsultarFicha").val(respuesta["programa_ficha"] || "");
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
$(document).on("change", ".nuevaFoto", function() {
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
        $(datosImagen).on("load", function(event) {
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
        success: function(respuesta) {
            $(selectorCiudad).html('<option value="">Seleccionar Municipio/Ciudad</option>');
            if(respuesta && respuesta.length > 0) {
                respuesta.forEach(function(ciudad) {
                    let selected = (ciudadSeleccionada == ciudad.codigo_ciu) ? "selected" : "";
                    $(selectorCiudad).append('<option value="'+ciudad.codigo_ciu+'" '+selected+'>'+ciudad.nombre+'</option>');
                });
            }
        }
    });
}

$("#nuevoDepartamentoLogin").change(function(){
    cargarCiudades($(this).val(), "#nuevaCiudadLogin");
});

$("#nuevoDepartamento").change(function(){
    cargarCiudades($(this).val(), "#nuevaCiudad");
});

$("#editarDepartamento").change(function(){
    cargarCiudades($(this).val(), "#editarCiudad");
});

$("#editarDepartamentoPerfil").change(function(){
    cargarCiudades($(this).val(), "#editarCiudadPerfil");
});

/*=============================================
CARGAR DATOS DE CONTACTO AL ABRIR PERFIL
=============================================*/
$('[data-target="#modal-miPerfil"]').click(function() {
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
        success: function(respuesta) {
            if(respuesta && respuesta["contacto"]) {
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

/*=============================================
IMPORTACIÓN MASIVA DE APRENDICES (VALIDACIÓN DE ARCHIVO Y DESCARGA DE PLANTILLA)
=============================================*/
$(document).on("change", "#archivoImportarUsuarios", function() {
    let archivo = this.files[0];
    if (!archivo) {
        $(this).next(".custom-file-label").html("Seleccionar archivo CSV o Excel (.xlsx)");
        return;
    }

    let nombreArchivo = archivo.name;
    let extension = nombreArchivo.split('.').pop().toLowerCase();

    if (extension !== "csv" && extension !== "xlsx") {
        $(this).val("");
        $(this).next(".custom-file-label").html("Seleccionar archivo CSV o Excel (.xlsx)");
        Swal.fire({
            icon: 'error',
            title: 'Formato no válido',
            text: 'Por favor seleccione un archivo en formato .CSV o Excel (.XLSX).',
            confirmButtonText: 'Cerrar'
        });
        return;
    }

    if (archivo.size > 5242880) {
        $(this).val("");
        $(this).next(".custom-file-label").html("Seleccionar archivo CSV o Excel (.xlsx)");
        Swal.fire({
            icon: 'error',
            title: 'Archivo demasiado grande',
            text: 'El archivo no debe pesar más de 5 MB.',
            confirmButtonText: 'Cerrar'
        });
        return;
    }

    $(this).next(".custom-file-label").html(nombreArchivo);
});

$(document).on("click", "#btnDescargarPlantillaUsuarios", function(e) {
    e.preventDefault();
    let encabezados = "tipo_documento;documento_id;nombres;apellidos;fecha_nacimiento;correo;codigo_ficha;direccion;telefono;codigo_dep;codigo_ciu\n";
    let ejemplo1 = "CC;1005558899;Juan Camilo;Perez Gomez;2003-05-14;jcperez@soy.sena.edu.co;3063989;Calle 25 # 14-30;3158889900;76;76834\n";
    let ejemplo2 = "TI;1114567890;Laura Sofia;Ramirez Lopez;2006-11-20;lsramirez@soy.sena.edu.co;2827300;Carrera 10 # 5-12;3104445566;76;76834\n";
    let contenidoCsv = "\uFEFF" + encabezados + ejemplo1 + ejemplo2;

    let blob = new Blob([contenidoCsv], { type: "text/csv;charset=utf-8;" });
    let url = URL.createObjectURL(blob);
    let link = document.createElement("a");
    link.setAttribute("href", url);
    link.setAttribute("download", "plantilla_importar_aprendices_GAiA.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
});