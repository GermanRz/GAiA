<!-- Content Wrapper. Contains page content -->
<!-- <div class="content-wrapper"> -->
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Usuarios</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item active">Usuarios</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <style>
    .apoyo-tabs .nav-link {
      background-color: #198750;
      color: #ffffff !important;
      border-radius: 8px !important;
      text-align: left;
      padding: 8px 16px;
      display: flex;
      align-items: center;
      gap: 10px;
      line-height: 1.2;
      font-weight: 500;
      opacity: 0.8;
      border: 1px solid transparent;
      cursor: pointer;
    }

    .apoyo-tabs .nav-item {
      margin-right: 10px;
      margin-bottom: 10px;
    }

    .apoyo-tabs .nav-link.active,
    .apoyo-tabs .nav-link:hover {
      opacity: 1;
      background-color: #198754;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    }

    .apoyo-tabs .nav-link i {
      font-size: 1.5rem;
    }

    .table-apoyo {
      background-color: #343a40 !important;
    }

    .table-apoyo thead th {
      background-color: #198754 !important;
      color: white;
      border: 1px solid #454d55;
      text-align: center;
      font-weight: normal;
    }

    .table-apoyo tbody td {
      color: white;
      border: 1px solid #454d55;
      text-align: center;
      vertical-align: middle;
    }

    .table-apoyo tbody tr:hover {
      background-color: #3e444a;
    }

    .search-apoyo {
      border-color: #6c757d;
      color: white;
    }

    .search-apoyo:focus {
      background-color: transparent;
      color: white;
      border-color: #198754;
      box-shadow: none;
    }

    .badge-inscrito {
      background-color: #198754;
      color: white;
      font-size: 0.95rem;
      padding: 8px 15px;
      border-radius: 4px;
      font-weight: normal;
    }

    .badge-proceso {
      background-color: #ffc107;
      color: white;
      font-size: 0.95rem;
      padding: 8px 15px;
      border-radius: 4px;
      font-weight: normal;
    }
  </style>

  <div class="container-fluid pb-4">
    <!-- Contenedor Principal Oscuro -->
    <div class="card" style="background-color: #2b3035; border: none; min-height: 500px;">
      <div class="card-header border-0 pt-4 pb-2">
        <div class="d-flex justify-content-between align-items-start flex-wrap">

          <!-- Pestañas de Apoyos -->
          <ul class="nav nav-pills apoyo-tabs" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
              <a class="nav-link active" id="pills-sostenimiento-tab" data-toggle="pill" href="#pills-sostenimiento"
                role="tab" aria-controls="pills-sostenimiento" aria-selected="true">
                <i class="fas fa-wallet"></i>
                <span>Apoyo de<br>sostenimiento</span>
              </a>
            </li>
            <li class="nav-item" role="presentation">
              <a class="nav-link" id="pills-datos-tab" data-toggle="pill" href="#pills-datos" role="tab"
                aria-controls="pills-datos" aria-selected="false">
                <i class="fas fa-phone-alt"></i>
                <span>apoyo<br>de datos</span>
              </a>
            </li>
            <li class="nav-item" role="presentation">
              <a class="nav-link" id="pills-alimentacion-tab" data-toggle="pill" href="#pills-alimentacion" role="tab"
                aria-controls="pills-alimentacion" aria-selected="false">
                <i class="fas fa-utensils"></i>
                <span>apoyo de<br>alimentación</span>
              </a>
            </li>
            <li class="nav-item" role="presentation">
              <a class="nav-link" id="pills-transporte-tab" data-toggle="pill" href="#pills-transporte" role="tab"
                aria-controls="pills-transporte" aria-selected="false">
                <i class="fas fa-car"></i>
                <span>apoyo de<br>transporte</span>
              </a>
            </li>
          </ul>

          <!-- Buscador -->
          <div class="input-group mt-2 mt-md-0" style="width: 250px;">
            <div class="input-group-prepend">
              <span class="input-group-text bg-transparent text-white search-apoyo"><i class="far fa-user"></i></span>
            </div>
            <input type="text" class="form-control bg-transparent search-apoyo" placeholder="Buscar aprendiz">
          </div>

        </div>
      </div>

      <div class="card-body p-0">
        <div class="tab-content" id="pills-tabContent">

          <!-- TAB: Sostenimiento -->
          <div class="tab-pane fade show active" id="pills-sostenimiento" role="tabpanel"
            aria-labelledby="pills-sostenimiento-tab">
            <div class="table-responsive">
              <table class="table table-bordered table-apoyo mb-0">
                <thead>
                  <tr>
                    <th>Identificacion</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Ficha</th>
                    <th>Estado</th>
                    <th>Fecha De Inscripcion</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td><td><strong>Ken</strong></td><td><strong>Ken@Gmail.Com</strong></td><td>3063989</td><td><span class="badge badge-inscrito"><i class="fas fa-check"></i> Inscrito</span></td><td>06/11/2025</td>
                  </tr>
                  <tr>
                    <td>1</td><td><strong>Ken</strong></td><td><strong>Ken@Gmail.Com</strong></td><td>3063989</td><td><span class="badge badge-proceso"><i class="fas fa-check"></i> En Proceso</span></td><td>06/11/2025</td>
                  </tr>
                  <tr>
                    <td>1</td><td><strong>Ken</strong></td><td><strong>Ken@Gmail.Com</strong></td><td>3063989</td><td><span class="badge badge-inscrito"><i class="fas fa-check"></i> Inscrito</span></td><td>06/11/2025</td>
                  </tr>
                  <tr>
                    <td>1</td><td><strong>Ken</strong></td><td><strong>Ken@Gmail.Com</strong></td><td>3063989</td><td><span class="badge badge-proceso"><i class="fas fa-check"></i> En Proceso</span></td><td>06/11/2025</td>
                  </tr>
                  <tr>
                    <td>1</td><td><strong>Ken</strong></td><td><strong>Ken@Gmail.Com</strong></td><td>3063989</td><td><span class="badge badge-inscrito"><i class="fas fa-check"></i> Inscrito</span></td><td>06/11/2025</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- TAB: Datos -->
          <div class="tab-pane fade" id="pills-datos" role="tabpanel" aria-labelledby="pills-datos-tab">
            <div class="table-responsive">
              <table class="table table-bordered table-apoyo mb-0">
                <thead>
                  <tr>
                    <th>Identificacion</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Ficha</th>
                    <th>Estado</th>
                    <th>Fecha De Inscripcion</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>2</td><td><strong>María (Datos)</strong></td><td><strong>Maria@Gmail.Com</strong></td><td>3063990</td><td><span class="badge badge-inscrito"><i class="fas fa-check"></i> Inscrito</span></td><td>07/11/2025</td>
                  </tr>
                  <tr>
                    <td>2</td><td><strong>María (Datos)</strong></td><td><strong>Maria@Gmail.Com</strong></td><td>3063990</td><td><span class="badge badge-proceso"><i class="fas fa-check"></i> En Proceso</span></td><td>07/11/2025</td>
                  </tr>
                  <tr>
                    <td>2</td><td><strong>María (Datos)</strong></td><td><strong>Maria@Gmail.Com</strong></td><td>3063990</td><td><span class="badge badge-inscrito"><i class="fas fa-check"></i> Inscrito</span></td><td>07/11/2025</td>
                  </tr>
                  <tr>
                    <td>2</td><td><strong>María (Datos)</strong></td><td><strong>Maria@Gmail.Com</strong></td><td>3063990</td><td><span class="badge badge-proceso"><i class="fas fa-check"></i> En Proceso</span></td><td>07/11/2025</td>
                  </tr>
                  <tr>
                    <td>2</td><td><strong>María (Datos)</strong></td><td><strong>Maria@Gmail.Com</strong></td><td>3063990</td><td><span class="badge badge-inscrito"><i class="fas fa-check"></i> Inscrito</span></td><td>07/11/2025</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- TAB: Alimentación -->
          <div class="tab-pane fade" id="pills-alimentacion" role="tabpanel" aria-labelledby="pills-alimentacion-tab">
            <div class="table-responsive">
              <table class="table table-bordered table-apoyo mb-0">
                <thead>
                  <tr>
                    <th>Identificacion</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Ficha</th>
                    <th>Estado</th>
                    <th>Fecha De Inscripcion</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>3</td><td><strong>Carlos (Alim)</strong></td><td><strong>Carlos@Gmail.Com</strong></td><td>3063991</td><td><span class="badge badge-inscrito"><i class="fas fa-check"></i> Inscrito</span></td><td>08/11/2025</td>
                  </tr>
                  <tr>
                    <td>3</td><td><strong>Carlos (Alim)</strong></td><td><strong>Carlos@Gmail.Com</strong></td><td>3063991</td><td><span class="badge badge-proceso"><i class="fas fa-check"></i> En Proceso</span></td><td>08/11/2025</td>
                  </tr>
                  <tr>
                    <td>3</td><td><strong>Carlos (Alim)</strong></td><td><strong>Carlos@Gmail.Com</strong></td><td>3063991</td><td><span class="badge badge-inscrito"><i class="fas fa-check"></i> Inscrito</span></td><td>08/11/2025</td>
                  </tr>
                  <tr>
                    <td>3</td><td><strong>Carlos (Alim)</strong></td><td><strong>Carlos@Gmail.Com</strong></td><td>3063991</td><td><span class="badge badge-proceso"><i class="fas fa-check"></i> En Proceso</span></td><td>08/11/2025</td>
                  </tr>
                  <tr>
                    <td>3</td><td><strong>Carlos (Alim)</strong></td><td><strong>Carlos@Gmail.Com</strong></td><td>3063991</td><td><span class="badge badge-inscrito"><i class="fas fa-check"></i> Inscrito</span></td><td>08/11/2025</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- TAB: Transporte -->
          <div class="tab-pane fade" id="pills-transporte" role="tabpanel" aria-labelledby="pills-transporte-tab">
            <div class="table-responsive">
              <table class="table table-bordered table-apoyo mb-0">
                <thead>
                  <tr>
                    <th>Identificacion</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Ficha</th>
                    <th>Estado</th>
                    <th>Fecha De Inscripcion</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>4</td><td><strong>Laura (Transp)</strong></td><td><strong>Laura@Gmail.Com</strong></td><td>3063992</td><td><span class="badge badge-inscrito"><i class="fas fa-check"></i> Inscrito</span></td><td>09/11/2025</td>
                  </tr>
                  <tr>
                    <td>4</td><td><strong>Laura (Transp)</strong></td><td><strong>Laura@Gmail.Com</strong></td><td>3063992</td><td><span class="badge badge-proceso"><i class="fas fa-check"></i> En Proceso</span></td><td>09/11/2025</td>
                  </tr>
                  <tr>
                    <td>4</td><td><strong>Laura (Transp)</strong></td><td><strong>Laura@Gmail.Com</strong></td><td>3063992</td><td><span class="badge badge-inscrito"><i class="fas fa-check"></i> Inscrito</span></td><td>09/11/2025</td>
                  </tr>
                  <tr>
                    <td>4</td><td><strong>Laura (Transp)</strong></td><td><strong>Laura@Gmail.Com</strong></td><td>3063992</td><td><span class="badge badge-proceso"><i class="fas fa-check"></i> En Proceso</span></td><td>09/11/2025</td>
                  </tr>
                  <tr>
                    <td>4</td><td><strong>Laura (Transp)</strong></td><td><strong>Laura@Gmail.Com</strong></td><td>3063992</td><td><span class="badge badge-inscrito"><i class="fas fa-check"></i> Inscrito</span></td><td>09/11/2025</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

        </div>

        <!-- Paginación estilo imagen -->
        <div class="d-flex justify-content-end mt-4 mb-3 pr-3">
          <div class="btn-group" role="group">
            <button type="button" class="btn btn-apagado text-secondary bg-transparent btn-anterior" style="border: 1px solid #4a5568;">Anterior</button>
            <button type="button" class="btn btn-primary indicador-tabla" style="background-color: #3b71ca; border-color: #3b71ca; cursor: default;">1</button>
            <button type="button" class="btn btn-apagado text-secondary bg-transparent btn-siguiente" style="border: 1px solid #4a5568;">Siguiente</button>
          </div>
        </div>

      </div>
    </div>
  </div>
  
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const tabs = [
        '#pills-sostenimiento-tab',
        '#pills-datos-tab',
        '#pills-alimentacion-tab',
        '#pills-transporte-tab'
      ];
      
      let currentTabIndex = 0;
      const indicadorTabla = document.querySelector('.indicador-tabla');

      function updatePagination() {
        const tabElements = document.querySelectorAll('.apoyo-tabs .nav-link');
        tabElements.forEach((tab, index) => {
          if (tab.classList.contains('active')) {
            currentTabIndex = index;
          }
        });
        
        // Actualizar el número del botón central
        if (indicadorTabla) {
          indicadorTabla.textContent = currentTabIndex + 1;
        }
      }

      // Evento de Bootstrap o Click genérico
      document.querySelectorAll('.apoyo-tabs .nav-link').forEach(t => {
        t.addEventListener('click', function(e) {
          // Remover activo de todos
          document.querySelectorAll('.apoyo-tabs .nav-link').forEach(nav => nav.classList.remove('active'));
          // Añadir activo al clickeado
          this.classList.add('active');
          setTimeout(updatePagination, 100);
        });
      });

      document.querySelector('.btn-anterior').addEventListener('click', function() {
        if (currentTabIndex > 0) {
          // Simulamos el click en la tab anterior usando jQuery (AdminLTE suele requerir jQuery para tabs)
          const prevTab = tabs[currentTabIndex - 1];
          if (typeof $ !== 'undefined') {
            $(prevTab).tab('show');
          } else {
            document.querySelector(prevTab).click();
          }
        }
      });

      document.querySelector('.btn-siguiente').addEventListener('click', function() {
        if (currentTabIndex < tabs.length - 1) {
          // Simulamos el click en la tab siguiente
          const nextTab = tabs[currentTabIndex + 1];
          if (typeof $ !== 'undefined') {
            $(nextTab).tab('show');
          } else {
            document.querySelector(nextTab).click();
          }
        }
      });
      
      // Chequear inicial
      updatePagination();
    });
  </script>
</section>
<!-- /.content -->

<!-- </div> -->
<!-- /.content-wrapper -->