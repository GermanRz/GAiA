document.addEventListener('DOMContentLoaded', function() {
                // Controlar opacidad de botones tipo tab
                $('#adminTabs .nav-link').on('click', function(){
                  $('#adminTabs .nav-link').css('opacity', '0.6').removeClass('active');
                  $(this).css('opacity', '1').addClass('active');

                  // Actualizar indicador central (1 a 4) de la paginacion
                  var index = $(this).parent().index();
                  $('.admin-indicador').text(index + 1);
                });

                // Avance de tabs con Siguiente
                $('.btn-admin-sig').on('click', function(){
                  var $active = $('#adminTabs .nav-link.active');
                  var $next = $active.parent().next().children('.nav-link');
                  if($next.length > 0) {
                    $next.tab('show');
                    $next.click();
                  }
                });

                // Retroceso con Anterior
                $('.btn-admin-ant').on('click', function(){
                  var $active = $('#adminTabs .nav-link.active');
                  var $prev = $active.parent().prev().children('.nav-link');
                  if($prev.length > 0) {
                    $prev.tab('show');
                    $prev.click();
                  }
                });
              });

document.addEventListener('DOMContentLoaded', function() {
          var switchBtn = document.getElementById('switchVistaAdmin');
          var vistaUsuario = document.getElementById('vistaUsuario');
          var vistaAdmin = document.getElementById('vistaAdmin');
          var btnDoc = document.getElementById('btnSubirDocumento');

          if(switchBtn) {
            switchBtn.addEventListener('change', function() {
              if(this.checked) {
                vistaUsuario.style.display = 'none';
                vistaAdmin.style.display = 'block';
                if(btnDoc) btnDoc.style.display = 'none';
              } else {
                vistaUsuario.style.display = 'block';
                vistaAdmin.style.display = 'none';
                if(btnDoc) btnDoc.style.display = 'block';
              }
            });
          }

          var btnAbrirSubirArchivo = document.getElementById('btnAbrirSubirArchivo');
          if(btnAbrirSubirArchivo) {
            btnAbrirSubirArchivo.addEventListener('click', function() {
              $('#modalInfoBancaria').modal('hide');
              
              // Se escucha cuando se oculte esta modal para lanzar la siguiente, o se lanza de inmediato
              setTimeout(function(){
                $('#modalSubirArchivo').modal('show');
              }, 400); // Dar un breve lapso para la animación en bootstrap
            });
          }
        });

document.addEventListener('DOMContentLoaded', function() {
        let pdCurrentStep = 1;
        const pdTotalSteps = 8;
        const btnPdSiguiente = document.getElementById('btnPdSiguiente');
        const btnPdAnterior = document.getElementById('btnPdAnterior');
        const pdCircles = document.querySelectorAll('.pd-circle');
        const pdModalTitle = document.getElementById('pdModalTitle');
        
        const stepTitles = {
          1: "DATOS PERSONALES DEL APRENDIZ",
          2: "DATOS DE FORMACIÓN",
          3: "DATOS DEL REPRESENTANTE LEGAL",
          4: "VIVIENDA Y SERVICIO",
          5: "INFORMACION SOCIOECONOMICA",
          6: "DOCUMENTO DE SOPORTE DEL APRENDIZ Y REPRESENTANTE",
          7: "CONDICIONES DEL APRENDIZ",
          8: "CONDICIONES DEL APRENDIZ PAG 2"
        };
        
        function updatePdStep(step) {
          for(let i=1; i<=pdTotalSteps; i++) {
            let el = document.getElementById('pd-step-' + i);
            if(el) el.style.display = 'none';
          }
          let currentEl = document.getElementById('pd-step-' + step);
          if(currentEl) currentEl.style.display = 'block';

          pdCircles.forEach(c => {
            c.classList.remove('active');
            if(parseInt(c.getAttribute('data-step')) === step) {
              c.classList.add('active');
            }
          });

          if(step === pdTotalSteps) {
            if(btnPdSiguiente) btnPdSiguiente.textContent = 'siguiente'; // En la captura dice siguiente minúscula
          } else {
            if(btnPdSiguiente) btnPdSiguiente.textContent = 'Siguiente';
          }

          if(step > 1) {
            if(btnPdAnterior) {
              // En la captura de paso 8 el botón dice "Atras", para otros es Anterior
              btnPdAnterior.textContent = (step === 8) ? 'Atras' : 'Anterior';
            }
          } else {
            if(btnPdAnterior) {
              btnPdAnterior.textContent = 'Cancelar';
            }
          }

          if(pdModalTitle && stepTitles[step]) {
            pdModalTitle.textContent = stepTitles[step];
          }
        }

        if (btnPdSiguiente) {
          btnPdSiguiente.addEventListener('click', function() {
            if (pdCurrentStep < pdTotalSteps) {
              pdCurrentStep++;
              updatePdStep(pdCurrentStep);
            } else {
              // En lugar de cerrar, abrimos la modal de confirmacion
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
        
        // Lógica de los modales finales
        $('#btnContinuarConfirmacion').on('click', function() {
          // Un pequeño delay para que al cerrar la de confirmacion, la de exito se abra bien
          setTimeout(function() {
            $('#modalExitoInscripcion').modal('show');
          }, 400);
        });

        $('#btnFinalizarTodo').on('click', function() {
          // Cerramos todo el flujo
          $('#modalDatosPersonales').modal('hide');
        });

        // Aseguramos que la opacidad del fondo de modalSubirArchivo quede superior a todo
        $('#modalSubirArchivo').on('show.bs.modal', function () {
          setTimeout(function() {
            $('.modal-backdrop').last().css('z-index', 1059);
          }, 10);
        });
        // Si tienes problemas visuales en Bootstrap 4.6 con múltiples backdrops:
        $('#modalSubirArchivo, #modalConfirmacion, #modalExitoInscripcion').on('hidden.bs.modal', function() {
           if ($('.modal.show').length > 0) {
             $('body').addClass('modal-open');
           }
        });

      });