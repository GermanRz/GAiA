   <section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2 align-items-center">
      <div class="col-sm-6">
        <h1 class="m-0 text-uppercase font-weight-bold">SEDES</h1>
      </div>
      <div class="col-sm-6 text-right">
        <button class="btn btn-success" data-toggle="modal" data-target="#modalAgregarSede">
          Agregar Sede
        </button>
      </div>
    </div>
  </div>
</section>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="card border-0 shadow-sm">
      <div class="card-body" style="min-height: 500px;">
        
        <!-- DataTable -->
        <div class="table-responsive shadow-sm mt-3">
          <table id="tblSedes" class="table table-bordered table-striped text-center align-middle mb-0" width="100%">
            <thead class="bg-success text-white">
              <tr>
                <th style="width: 60px">ID</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Sagrado</td>
                <td>Carrera 26 - 25</td>
                <td>Horario de formación en todas las jornadas</td>
                <td class="text-danger font-weight-bold">ACTIVA</td>
                <td>
                  <button class="btn btn-sm btn-outline-secondary border-0"><i class="far fa-edit"></i></button>
                </td>
              </tr>
              <tr>
                <td>1</td>
                <td>Granja CLEM</td>
                <td>Vía Tuluá - San Pedro</td>
                <td>Horario de formación en todas las jornadas</td>
                <td>
                  <button class="btn btn-danger btn-sm text-bold rounded-0 px-3">CERRADA</button>
                </td>
                <td>
                  <button class="btn btn-sm btn-outline-secondary border-0"><i class="far fa-edit"></i></button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</section>