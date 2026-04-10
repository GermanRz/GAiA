  <!-- Content Wrapper. Contains page content -->
  <!-- <div class="content-wrapper"> -->
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Fichas</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
              <li class="breadcrumb-item active">Fichas</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

            <div class="card">
              <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                  <h3 class="card-title">Lista de fichas</h3>
                  <a href="#" class="btn btn-success btn-sm">Agregar Ficha</a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="tblFichas" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Código</th>
                      <th>Descripción</th>
                      <th>Sede</th>
                      <th>Inicio</th>
                      <th>Fin</th>
                      <th>Estado</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1</td>
                      <td>3063989</td>
                      <td>Análisis y Desarrollo de Software</td>
                      <td>Sagrado</td>
                      <td>2024-10-15</td>
                      <td>2026-07-14</td>
                      <td><span class="badge badge-success">LECTIVA</span></td>
                      <td><button class="btn btn-sm btn-outline-secondary border-0"><i class="fas fa-edit"></i></button></td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td>3000606</td>
                      <td>Análisis y Desarrollo de Software</td>
                      <td>Salesianos</td>
                      <td>2024-10-15</td>
                      <td>2026-07-14</td>
                      <td><span class="badge badge-info">PRODUCTIVA</span></td>
                      <td><button class="btn btn-sm btn-outline-secondary border-0"><i class="fas fa-edit"></i></button></td>
                    </tr>
                    <tr>
                      <td>3</td>
                      <td>2005020</td>
                      <td>Análisis y Desarrollo de Software</td>
                      <td>Bicentenario</td>
                      <td>2024-10-15</td>
                      <td>2026-07-14</td>
                      <td><span class="badge badge-danger">CERRADA</span></td>
                      <td><button class="btn btn-sm btn-outline-secondary border-0"><i class="fas fa-edit"></i></button></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

    </section>
    <!-- /.content -->
  
  <!-- </div> -->
  <!-- /.content-wrapper -->