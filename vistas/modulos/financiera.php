<!-- Content Wrapper. Contains page content -->
<!-- <div class="content-wrapper"> -->
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Apoyos</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
          <li class="breadcrumb-item active">Financiera</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">


  <div class="container-fluid" style="padding: 20px;">
    <!-- Botones Superiores y Buscador sin fondo blanco para heredar el gris -->
    <div class="container-header-financiera w-100">
      <div class="seleccion-apoyos">
        <!-- Iconos: fa-lock, fa-phone-alt, fa-utensils, fa-car-side -->
        <div class="row w-100">
          <div class="col-auto mb-2" style="width: 220px;">
            <div class="info-box bg-success btn-apoyo active" data-target="#tab-sostenimiento"
              style="cursor:pointer; min-height: 50px; padding: 5px; margin-bottom: 0;">
              <span class="info-box-icon" style="width: 50px; font-size: 1.5rem;"><i class="fas fa-lock"></i></span>
              <div class="info-box-content" style="padding: 5px 10px;">
                <span class="info-box-text text-wrap" style="font-size: 0.85rem; line-height: 1.1;">Apoyo de
                  sostenimiento</span>
              </div>
            </div>
          </div>
          <div class="col-auto mb-2" style="width: 220px;">
            <div class="info-box bg-success btn-apoyo" data-target="#tab-datos"
              style="cursor:pointer; min-height: 50px; padding: 5px; margin-bottom: 0;">
              <span class="info-box-icon" style="width: 50px; font-size: 1.5rem;"><i
                  class="fas fa-phone-alt"></i></span>
              <div class="info-box-content" style="padding: 5px 10px;">
                <span class="info-box-text text-wrap" style="font-size: 0.85rem; line-height: 1.1;">apoyo de
                  datos</span>
              </div>
            </div>
          </div>
          <div class="col-auto mb-2" style="width: 220px;">
            <div class="info-box bg-success btn-apoyo" data-target="#tab-alimentacion"
              style="cursor:pointer; min-height: 50px; padding: 5px; margin-bottom: 0;">
              <span class="info-box-icon" style="width: 50px; font-size: 1.5rem;"><i class="fas fa-utensils"></i></span>
              <div class="info-box-content" style="padding: 5px 10px;">
                <span class="info-box-text text-wrap" style="font-size: 0.85rem; line-height: 1.1;">apoyo de
                  alimentación</span>
              </div>
            </div>
          </div>
          <div class="col-auto mb-2" style="width: 220px;">
            <div class="info-box bg-success btn-apoyo" data-target="#tab-transporte"
              style="cursor:pointer; min-height: 50px; padding: 5px; margin-bottom: 0;">
              <span class="info-box-icon" style="width: 50px; font-size: 1.5rem;"><i class="fas fa-car-side"></i></span>
              <div class="info-box-content" style="padding: 5px 10px;">
                <span class="info-box-text text-wrap" style="font-size: 0.85rem; line-height: 1.1;">apoyo de
                  transporte</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Gran contenedor con borde (como en la imagen) -->
    <div class="main-border-container" style="border: 1px solid #585858ff; padding-bottom: 10px;">
      <!-- Tablas por apoyo -->
      <div class="tab-content w-100" id="tab-content-apoyos">

        <!-- Tab Sostenimiento -->
        <div class="tab-pane fade show active" id="tab-sostenimiento" role="tabpanel">
          <table id="tblSostenimiento" class="table table-bordered table-striped tbl-GAiA w-100">
            <thead>
              <tr>
                <th>Identificacion</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Ficha</th>
                <th>Estado</th>
                <th>última modificación</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $aprendicesSostenimiento = [
                ["1", "Arnulfo slamandra maecha gil", "arnulfo.salamandra@soy.sena.edu.co", "3063989", "06/11/2025"],
                ["2", "Carlos Eduardo Perez Diaz", "carlos.perez@soy.sena.edu.co", "3063990", "07/11/2025"],
                ["3", "Maria Fernanda Gomez Lopez", "maria.gomez@soy.sena.edu.co", "3063991", "08/11/2025"],
                ["4", "Andres Felipe Santos Castro", "andres.santos@soy.sena.edu.co", "3063992", "09/11/2025"],
                ["5", "Laura Valentina Ramirez Ruiz", "laura.ramirez@soy.sena.edu.co", "3063993", "10/11/2025"],
                ["6", "Juan Camilo Herrera Blanco", "juan.herrera@soy.sena.edu.co", "3063994", "11/11/2025"],
                ["7", "Pedro Pablo Leon Jaramillo", "pedro.leon@soy.sena.edu.co", "3063995", "12/11/2025"],
                ["8", "Ana Milena Orozco Tovar", "ana.orozco@soy.sena.edu.co", "3063996", "13/11/2025"],
                ["9", "Santiago de la Cruz", "santiago.cruz@soy.sena.edu.co", "3063997", "14/11/2025"],
                ["10", "Valeria Victoria Mejia", "valeria.mejia@soy.sena.edu.co", "3063998", "15/11/2025"],
                ["11", "Francisco Javier Mora", "francisco.mora@soy.sena.edu.co", "3063999", "16/11/2025"],
                ["12", "Natalia Rocio Cardenas", "natalia.cardenas@soy.sena.edu.co", "3064000", "17/11/2025"],
                ["13", "Hugo Alejandro Caceres", "hugo.caceres@soy.sena.edu.co", "3064001", "18/11/2025"],
                ["14", "Diana Marcela Ospina", "diana.ospina@soy.sena.edu.co", "3064002", "19/11/2025"],
                ["15", "Luis Eduardo Beltran", "luis.beltran@soy.sena.edu.co", "3064003", "20/11/2025"]
              ];
              foreach ($aprendicesSostenimiento as $aprendiz):
              ?>
                <tr>
                  <td><?php echo $aprendiz[0]; ?></td>
                  <td><?php echo $aprendiz[1]; ?></td>
                  <td><?php echo $aprendiz[2]; ?></td>
                  <td><?php echo $aprendiz[3]; ?></td>
                  <td><span class="btn bg-success btn-sm"><i class="fas fa-check"></i> aprobado</span></td>
                  <td><?php echo $aprendiz[4]; ?></td>
                  <td class="acciones-btns">
                    <button class="btn btn-sm btn-outline-info border-0"><i class="far fa-file-pdf"></i></button>
                    <button class="btn btn-sm btn-outline-info border-0"><i class="far fa-file-alt"></i></button>
                    <button class="btn btn-sm btn-outline-info border-0"><i class="far fa-eye"></i></button>
                    <button class="btn btn-sm btn-outline-info border-0"><i class="fas fa-clipboard-list"></i></button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Tab Datos -->
        <div class="tab-pane fade" id="tab-datos" role="tabpanel">
          <table id="tblDatos" class="table table-bordered table-striped tbl-GAiA w-100">
            <thead>
              <tr>
                <th>Identificacion</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Ficha</th>
                <th>Estado</th>
                <th>última modificación</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $aprendicesDatos = [
                ["11", "Maria del Carmen Fernandez", "maria.fernandez@soy.sena.edu.co", "3063989", "10/12/2025"],
                ["12", "Jorge Eliecer Romero", "jorge.romero@soy.sena.edu.co", "3063989", "11/12/2025"]
              ];
              foreach ($aprendicesDatos as $aprendiz):
              ?>
                <tr>
                  <td><?php echo $aprendiz[0]; ?></td>
                  <td><?php echo $aprendiz[1]; ?></td>
                  <td><?php echo $aprendiz[2]; ?></td>
                  <td><?php echo $aprendiz[3]; ?></td>
                  <td><span class="btn-aprobacion"><i class="fas fa-check"></i> aprobado</span></td>
                  <td><?php echo $aprendiz[4]; ?></td>
                  <td class="acciones-btns">
                    <button class="btn btn-sm btn-outline-info border-0"><i class="far fa-file-pdf"></i></button>
                    <button class="btn btn-sm btn-outline-info border-0""><i class=" far fa-file-alt"></i></button>
                    <button class="btn btn-sm btn-outline-info border-0""><i class=" far fa-eye"></i></button>
                    <button class="btn btn-sm btn-outline-info border-0""><i class=" fas fa-clipboard-list"></i></button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Tab Alimentacion -->
        <div class="tab-pane fade" id="tab-alimentacion" role="tabpanel">
          <table id="tblAlimentacion" class="table table-bordered table-striped tbl-GAiA w-100">
            <thead>
              <tr>
                <th>Identificacion</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Ficha</th>
                <th>Estado</th>
                <th>última modificación</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <!-- Vacío por ahora -->
            </tbody>
          </table>
        </div>

        <!-- Tab Transporte -->
        <div class="tab-pane fade" id="tab-transporte" role="tabpanel">
          <table id="tblTransporte" class="table table-bordered table-striped tbl-GAiA w-100">
            <thead>
              <tr>
                <th>Identificacion</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Ficha</th>
                <th>Estado</th>
                <th>última modificación</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <!-- Vacío por ahora -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Botones inferiores y paginación (dentro del borde como en Figma) -->
      <div class="footer-row px-3 mt-3">
        <div class="info-buttons">
          <button class="btn btn-sm btn-outline-success bg-success border-1">Informacion adicional</button>
          <button class="btn btn-sm btn-outline-success bg-success border-1">informacion bancaria</button>          
        </div>
        
      </div>
    </div>
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->

<!-- </div> -->
<!-- /.content-wrapper -->