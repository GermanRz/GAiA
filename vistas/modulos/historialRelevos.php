<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Historial de relevos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
                    <li class="breadcrumb-item active">Historial de Relevos</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="card bg-dark text-white">
            <div class="card-header border-0 d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold mb-0" style="font-size: 1.5rem; line-height: 2;">RELEVOS</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="tblHistorialRelevos"
                    class="table table-dark table-bordered table-striped dt-responsive nowrap" style="width:100%">
                    <thead style="background-color: #198754; color: white;">
                        <tr>
                            <th style="width: 10px;">ID</th>
                            <th>Aprendiz Saliente</th>
                            <th>Aprendiz Entrante</th>
                            <th>Fecha</th>
                            <th>Motivo</th>
                            <th>Tiempo Asignado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $historial = ControladorRelevos::ctrMostrarHistorialRelevos();

                        foreach ($historial as $key => $value) {
                            echo '<tr>
                                    <td>'.$value["id"].'</td>
                                    <td>'.$value["aprendiz_saliente"].'</td>
                                    <td>'.$value["aprendiz_entrante"].'</td>
                                    <td>'.date("d-m-Y", strtotime($value["fecha_relevo"])).'</td>
                                    <td>'.$value["motivo_salida"].'</td>
                                    <td>'.$value["meses_restantes"].' meses</td>
                                  </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</section>
<!-- /.content -->