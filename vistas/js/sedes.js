$("#nuevoNombre").change(function () {
  let nuevoNombre = $(this).val();
  console.log("este es el Nombre a ingresar: " + nuevoNombre);
  let datos = new FormData();
  datos.append("nuevoNombre", nuevoNombre);
  $.ajax({
    url: "ajax/sedes.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta) {
        $("#nuevoNombre").val("");
        Swal.fire({
          icon: "error",
          title: "¡La sede ya existe!",
          text: "Por favor, ingrese una sede diferente.",
        });
      }
    },
  }); //ajax
}); //fin de nuevoNombre.change
