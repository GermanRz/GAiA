document.addEventListener('DOMContentLoaded', function() {
  const btnAbrirSubirArchivo = document.getElementById('btnAbrirSubirArchivo');
  const btnPdSiguiente = document.getElementById('btnPdSiguiente');
  const btnPdAnterior = document.getElementById('btnPdAnterior');
  const pdCircles = document.querySelectorAll('.pd-circle');
  const pdModalTitle = document.getElementById('pdModalTitle');
  let pdCurrentStep = 1;
  const pdTotalSteps = 8;

  const stepTitles = {
    1: 'DATOS PERSONALES DEL APRENDIZ',
    2: 'DATOS DE FORMACIÓN',
    3: 'DATOS DEL REPRESENTANTE LEGAL',
    4: 'VIVIENDA Y SERVICIO',
    5: 'INFORMACION SOCIOECONOMICA',
    6: 'DOCUMENTO DE SOPORTE DEL APRENDIZ Y REPRESENTANTE',
    7: 'CONDICIONES DEL APRENDIZ',
    8: 'CONDICIONES DEL APRENDIZ PAG 2'
  };

  function updatePdStep(step) {
    for (let i = 1; i <= pdTotalSteps; i++) {
      const el = document.getElementById('pd-step-' + i);
      if (el) el.style.display = 'none';
    }

    const currentEl = document.getElementById('pd-step-' + step);
    if (currentEl) currentEl.style.display = 'block';

    pdCircles.forEach(circle => {
      circle.classList.remove('active');
      if (parseInt(circle.getAttribute('data-step')) === step) {
        circle.classList.add('active');
      }
    });

    if (btnPdSiguiente) {
      btnPdSiguiente.textContent = (step === pdTotalSteps) ? 'siguiente' : 'Siguiente';
    }

    if (btnPdAnterior) {
      btnPdAnterior.textContent = (step > 1) ? ((step === 8) ? 'Atras' : 'Anterior') : 'Cancelar';
    }

    if (pdModalTitle && stepTitles[step]) {
      pdModalTitle.textContent = stepTitles[step];
    }
  }

  if (btnAbrirSubirArchivo) {
    btnAbrirSubirArchivo.addEventListener('click', function() {
      $('#modalInfoBancaria').modal('hide');
      setTimeout(function() {
        $('#modalSubirArchivo').modal('show');
      }, 400);
    });
  }

  if (btnPdSiguiente) {
    btnPdSiguiente.addEventListener('click', function() {
      if (pdCurrentStep < pdTotalSteps) {
        pdCurrentStep++;
        updatePdStep(pdCurrentStep);
      } else {
        $('#modalConfirmacion').modal('show');
      }
    });
  }

  if (btnPdAnterior) {
    btnPdAnterior.addEventListener('click', function() {
      if (pdCurrentStep > 1) {
        pdCurrentStep--;
        updatePdStep(pdCurrentStep);
      } else {
        $('#modalDatosPersonales').modal('hide');
      }
    });
  }

  pdCircles.forEach(circle => {
    circle.addEventListener('click', function() {
      pdCurrentStep = parseInt(this.getAttribute('data-step'));
      updatePdStep(pdCurrentStep);
    });
  });

  $('#btnContinuarConfirmacion').on('click', function() {
    setTimeout(function() {
      $('#modalExitoInscripcion').modal('show');
    }, 400);
  });

  $('#btnFinalizarTodo').on('click', function() {
    $('#modalDatosPersonales').modal('hide');
  });

  $('#modalSubirArchivo').on('show.bs.modal', function() {
    setTimeout(function() {
      $('.modal-backdrop').last().css('z-index', 1059);
    }, 10);
  });

  $('#modalSubirArchivo, #modalConfirmacion, #modalExitoInscripcion').on('hidden.bs.modal', function() {
    if ($('.modal.show').length > 0) {
      $('body').addClass('modal-open');
    }
  });

  updatePdStep(pdCurrentStep);
});