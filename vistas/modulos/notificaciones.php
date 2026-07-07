<?php
// =============================================
// MÓDULO NOTIFICACIONES — MAQUETACIÓN UI
// Los datos aquí son ESTÁTICOS (mockup).
// La lógica de negocio, consultas y servicios
// se implementarán en una fase posterior.
// =============================================
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-bell mr-2 text-info"></i>Notificaciones</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php?ruta=inicio">Inicio</a></li>
                    <li class="breadcrumb-item active">Notificaciones</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content" id="vista-notificaciones">

    <!-- ================================================================
         TARJETAS DE RESUMEN (MÉTRICAS RÁPIDAS)
         ================================================================ -->
    <div class="row mb-3">
        <div class="col-12 col-sm-6 col-md-3 mb-2">
            <div class="notif-stat-card notif-stat-card--total">
                <div class="notif-stat-card__icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <div class="notif-stat-card__info">
                    <span class="notif-stat-card__number">10</span>
                    <span class="notif-stat-card__label">Total</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3 mb-2">
            <div class="notif-stat-card notif-stat-card--unread">
                <div class="notif-stat-card__icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="notif-stat-card__info">
                    <span class="notif-stat-card__number">4</span>
                    <span class="notif-stat-card__label">No leídas</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3 mb-2">
            <!--<div class="notif-stat-card notif-stat-card--high">
                <div class="notif-stat-card__icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="notif-stat-card__info">
                    <span class="notif-stat-card__number">2</span>
                    <span class="notif-stat-card__label">Alta Prioridad</span>
                </div>
            </div>-->
        </div>
        <div class="col-12 col-sm-6 col-md-3 mb-2">
            <!--<div class="notif-stat-card notif-stat-card--email">
                <div class="notif-stat-card__icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="notif-stat-card__info">
                    <span class="notif-stat-card__number">3</span>
                    <span class="notif-stat-card__label">Correos enviados</span>
                </div>
            </div>-->
        </div>
    </div>

    <!-- ================================================================
         PANEL PRINCIPAL CON PESTAÑAS
         ================================================================ -->
    <div id="panel-notificaciones" class="card card-dark card-tabs bg-dark border border-secondary shadow">

        <!-- CABECERA CON PESTAÑAS -->
        <div class="card-header p-0 pt-1 border-bottom-0" style="background-color: #343a40;">
            <ul class="nav nav-tabs" id="tabNotificaciones" role="tablist">

                <!-- PESTAÑA: TODAS -->
                <li class="nav-item">
                    <a class="nav-link active font-weight-bold text-uppercase" id="tab-todas-tab" data-toggle="pill"
                        href="#tab-todas" role="tab" aria-controls="tab-todas" aria-selected="true"
                        style="padding: 12px 20px;">
                        <i class="fas fa-inbox mr-2 text-info"></i>
                        Bandeja de Entrada
                        <span class="badge badge-info ml-1">10</span>
                    </a>
                </li>

                <!-- PESTAÑA: NO LEÍDAS -->
                <li class="nav-item">
                    <a class="nav-link font-weight-bold text-uppercase" id="tab-no-leidas-tab" data-toggle="pill"
                        href="#tab-no-leidas" role="tab" aria-controls="tab-no-leidas" aria-selected="false"
                        style="padding: 12px 20px;">
                        <i class="fas fa-bell mr-2 text-warning"></i>
                        No Leídas
                        <span class="badge badge-warning ml-1">4</span>
                    </a>
                </li>

                <!-- PESTAÑA: ALTA PRIORIDAD -->
                <!--<li class="nav-item">
                    <a class="nav-link font-weight-bold text-uppercase"
                       id="tab-alta-prioridad-tab"
                       data-toggle="pill"
                       href="#tab-alta-prioridad"
                       role="tab"
                       aria-controls="tab-alta-prioridad"
                       aria-selected="false"
                       style="padding: 12px 20px;">
                        <i class="fas fa-exclamation-triangle mr-2 text-danger"></i>
                        Alta Prioridad
                        <span class="badge badge-danger ml-1">2</span>
                    </a>
                </li>-->

            </ul>
        </div><!-- /.card-header -->

        <!-- CUERPO DEL PANEL -->
        <div class="card-body" style="background-color: #2b3035;">
            <div class="tab-content" id="tabNotificacionesContent">

                <!-- ============================================================
                     PESTAÑA 1: BANDEJA DE ENTRADA (TODAS)
                     ============================================================ -->
                <div class="tab-pane fade show active" id="tab-todas" role="tabpanel" aria-labelledby="tab-todas-tab">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0 text-white">
                            <i class="fas fa-inbox mr-2 text-info"></i>
                            Todas las Notificaciones
                        </h3>
                        <div>
                            <button type="button" class="btn btn-outline-info btn-sm mr-2" id="btn-marcar-todas-leidas"
                                title="Marcar todas como leídas">
                                <i class="fas fa-check-double mr-1"></i> Marcar todas como leídas
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="tblNotificaciones"
                            class="table table-dark table-striped table-bordered dt-responsive nowrap tabla-notificaciones"
                            style="width:100%">
                            <thead style="background-color: rgb(25, 135, 84); color: white;">
                                <tr>
                                    <th style="width: 48px;" class="text-center">Acciones</th>
                                    <th>Título</th>
                                    <th>Descripción</th>
                                    <th style="width: 140px;">Fecha y Hora</th>
                                    <!-- <th style="width: 95px;" class="text-center">Prioridad</th> -->
                                    <th style="width: 100px;" class="text-center">Estado</th>
                                    <th style="width: 60px;" class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>

                                <!-- ── FILA 1: Apertura de convocatoria | Alta | No leída ── -->
                                <tr class="notif-row--unread">
                                    <td class="text-center">
                                        <span class="notif-type-icon notif-type-icon--convocatoria"
                                            title="Apertura de convocatoria">
                                            <!--<i class="fas fa-folder-open text-success"></i>-->
                                        </span>
                                    </td>
                                    <td class="font-weight-bold">Nueva convocatoria abierta</td>
                                    <td>Se ha abierto la convocatoria <strong>Apoyo de Sostenimiento 2025-I</strong>.
                                        Los aprendices pueden iniciar su inscripción.</td>
                                    <td>25/06/2025 08:00</td>
                                    <!-- <td class="text-center">
                                    <span class="badge badge-danger">ALTA</span>
                                    </td>-->
                                    <td class="text-center">
                                        <span class="badge notif-badge-unread">
                                            <i class="fas fa-circle mr-1" style="font-size: 0.55rem;"></i>No leída
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-info btn-sm btn-ver-notificacion" data-id="1"
                                            data-titulo="Nueva convocatoria abierta"
                                            data-descripcion="Se ha abierto la convocatoria Apoyo de Sostenimiento 2025-I. Los aprendices pueden iniciar su inscripción."
                                            data-fecha="25/06/2025 08:00" data-tipo="Apertura de convocatoria"
                                            data-estado="No leída" title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- ── FILA 2: Cierre de inscripciones | Alta | No leída ── -->
                                <tr class="notif-row--unread">
                                    <td class="text-center">
                                        <span class="notif-type-icon notif-type-icon--cierre-inscripciones"
                                            title="Cierre de inscripciones">
                                            <!--<i class="fas fa-clock text-warning"></i>-->
                                        </span>
                                    </td>
                                    <td class="font-weight-bold">Cierre próximo de inscripciones</td>
                                    <td>Las inscripciones para <strong>Apoyo de Transporte 2025-I</strong> cierran en 24
                                        horas. Verifique los formularios pendientes.</td>
                                    <td>24/06/2025 16:30</td>
                                    <!-- <td class="text-center">
                                        <span class="badge badge-danger">ALTA</span>
                                    </td> -->
                                    <td class="text-center">
                                        <span class="badge notif-badge-unread">
                                            <i class="fas fa-circle mr-1" style="font-size: 0.55rem;"></i>No leída
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-info btn-sm btn-ver-notificacion" data-id="2"
                                            data-titulo="Cierre próximo de inscripciones"
                                            data-descripcion="Las inscripciones para Apoyo de Transporte 2025-I cierran en 24 horas. Verifique los formularios pendientes."
                                            data-fecha="24/06/2025 16:30" data-tipo="Cierre de inscripciones"
                                            data-estado="No leída" title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- ── FILA 3: Envío de postulación | Normal | No leída ── -->
                                <tr class="notif-row--unread">
                                    <td class="text-center">
                                        <span class="notif-type-icon notif-type-icon--postulacion"
                                            title="Envío de postulación">
                                            <!--<i class="fas fa-paper-plane text-info"></i>-->
                                        </span>
                                    </td>
                                    <td class="font-weight-bold">Nueva postulación recibida</td>
                                    <td>El aprendiz <strong>Carlos Andrés Ruiz López</strong> ha enviado su postulación
                                        para el apoyo <em>Alimentación Especial</em>.</td>
                                    <td>24/06/2025 11:15</td>
                                    <!-- <td class="text-center">
                                        <span class="badge badge-primary">NORMAL</span>
                                    </td> -->
                                    <td class="text-center">
                                        <span class="badge notif-badge-unread">
                                            <i class="fas fa-circle mr-1" style="font-size: 0.55rem;"></i>No leída
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-info btn-sm btn-ver-notificacion" data-id="3"
                                            data-titulo="Nueva postulación recibida"
                                            data-descripcion="El aprendiz Carlos Andrés Ruiz López ha enviado su postulación para el apoyo Alimentación Especial."
                                            data-fecha="24/06/2025 11:15" data-tipo="Envío de postulación"
                                            data-estado="No leída" title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- ── FILA 4: Documentos rechazados | Media | No leída ── -->
                                <tr class="notif-row--unread">
                                    <td class="text-center">
                                        <span class="notif-type-icon notif-type-icon--doc-rechazado"
                                            title="Documento rechazado">
                                            <!--<i class="fas fa-file-excel text-danger"></i>-->
                                        </span>
                                    </td>
                                    <td class="font-weight-bold">Documento bancario devuelto</td>
                                    <td>El certificado bancario de <strong>Laura Milena Torres Gómez</strong> fue
                                        devuelto para corrección. Motivo: cuenta a nombre de tercero.</td>
                                    <td>23/06/2025 14:45</td>
                                    <!-- <td class="text-center">
                                        <span class="badge badge-warning text-dark">MEDIA</span>
                                    </td> -->
                                    <td class="text-center">
                                        <span class="badge notif-badge-unread">
                                            <i class="fas fa-circle mr-1" style="font-size: 0.55rem;"></i>No leída
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-info btn-sm btn-ver-notificacion" data-id="4"
                                            data-titulo="Documento bancario devuelto"
                                            data-descripcion="El certificado bancario de Laura Milena Torres Gómez fue devuelto para corrección. Motivo: cuenta a nombre de tercero."
                                            data-fecha="23/06/2025 14:45" data-tipo="Documento devuelto/rechazado"
                                            data-estado="No leída" title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- ── FILA 5: Usuario seleccionado | Normal | Leída ── -->
                                <tr>
                                    <td class="text-center">
                                        <span class="notif-type-icon notif-type-icon--seleccionado"
                                            title="Usuario seleccionado">
                                            <!--<i class="fas fa-user-check text-success"></i>-->
                                        </span>
                                    </td>
                                    <td>Aprendiz seleccionado</td>
                                    <td>El aprendiz <strong>Julián Esteban Mora Peña</strong> ha sido seleccionado para
                                        el apoyo <em>Sostenimiento 2025-I</em>.</td>
                                    <td>22/06/2025 09:00</td>
                                    <!-- <td class="text-center">
                                        <span class="badge badge-primary">NORMAL</span>
                                    </td> -->
                                    <td class="text-center">
                                        <span class="badge notif-badge-read">
                                            <i class="fas fa-check mr-1" style="font-size: 0.55rem;"></i>Leída
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-secondary btn-sm btn-ver-notificacion" data-id="5"
                                            data-titulo="Aprendiz seleccionado"
                                            data-descripcion="El aprendiz Julián Esteban Mora Peña ha sido seleccionado para el apoyo Sostenimiento 2025-I."
                                            data-fecha="22/06/2025 09:00" data-tipo="Usuario seleccionado"
                                            data-estado="Leída" title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- ── FILA 6: Usuario beneficiado | Normal | Leída ── -->
                                <tr>
                                    <td class="text-center">
                                        <span class="notif-type-icon notif-type-icon--beneficiado"
                                            title="Usuario beneficiado">
                                            <!--<i class="fas fa-hand-holding-heart text-success"></i>-->
                                        </span>
                                    </td>
                                    <td>Aprendiz beneficiado</td>
                                    <td>El aprendiz <strong>Sandra Patricia Ríos Castro</strong> ha sido marcado como
                                        beneficiado del apoyo <em>Transporte 2024-II</em>.</td>
                                    <td>20/06/2025 10:30</td>
                                    <!-- <td class="text-center">
                                        <span class="badge badge-primary">NORMAL</span>
                                    </td> -->
                                    <td class="text-center">
                                        <span class="badge notif-badge-read">
                                            <i class="fas fa-check mr-1" style="font-size: 0.55rem;"></i>Leída
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-secondary btn-sm btn-ver-notificacion" data-id="6"
                                            data-titulo="Aprendiz beneficiado"
                                            data-descripcion="El aprendiz Sandra Patricia Ríos Castro ha sido marcado como beneficiado del apoyo Transporte 2024-II."
                                            data-fecha="20/06/2025 10:30" data-tipo="Usuario beneficiado"
                                            data-estado="Leída" title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- ── FILA 7: Cambio seleccionado→beneficiado | Media | Leída ── -->
                                <tr>
                                    <td class="text-center">
                                        <span class="notif-type-icon notif-type-icon--cambio-estado"
                                            title="Cambio de estado">
                                            <!--<i class="fas fa-exchange-alt text-info"></i>-->
                                        </span>
                                    </td>
                                    <td>Cambio de estado: Seleccionado → Beneficiado</td>
                                    <td><strong>Miguel Ángel Cárdenas Vega</strong> ha pasado de estado
                                        <em>Seleccionado</em> a <em>Beneficiado</em> en el apoyo Sostenimiento 2024-II.
                                    </td>
                                    <td>18/06/2025 13:00</td>
                                    <!-- <td class="text-center">
                                        <span class="badge badge-warning text-dark">MEDIA</span>
                                    </td> -->
                                    <td class="text-center">
                                        <span class="badge notif-badge-read">
                                            <i class="fas fa-check mr-1" style="font-size: 0.55rem;"></i>Leída
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-secondary btn-sm btn-ver-notificacion" data-id="7"
                                            data-titulo="Cambio de estado: Seleccionado → Beneficiado"
                                            data-descripcion="Miguel Ángel Cárdenas Vega ha pasado de estado Seleccionado a Beneficiado en el apoyo Sostenimiento 2024-II."
                                            data-fecha="18/06/2025 13:00" data-tipo="Cambio seleccionado a beneficiado"
                                            data-estado="Leída" title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- ── FILA 8: Usuario no seleccionado | Normal | Leída ── -->
                                <tr>
                                    <td class="text-center">
                                        <span class="notif-type-icon notif-type-icon--no-seleccionado"
                                            title="Usuario no seleccionado">
                                            <!--<i class="fas fa-user-times text-danger"></i>-->
                                        </span>
                                    </td>
                                    <td>Aprendiz no seleccionado</td>
                                    <td>El aprendiz <strong>Diana Carolina Ospina Herrera</strong> no fue seleccionada
                                        en la convocatoria <em>Alimentación 2025-I</em> por puntaje insuficiente.</td>
                                    <td>16/06/2025 08:45</td>
                                    <!-- <td class="text-center">
                                        <span class="badge badge-primary">NORMAL</span>
                                    </td> -->
                                    <td class="text-center">
                                        <span class="badge notif-badge-read">
                                            <i class="fas fa-check mr-1" style="font-size: 0.55rem;"></i>Leída
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-secondary btn-sm btn-ver-notificacion" data-id="8"
                                            data-titulo="Aprendiz no seleccionado"
                                            data-descripcion="El aprendiz Diana Carolina Ospina Herrera no fue seleccionada en la convocatoria Alimentación 2025-I por puntaje insuficiente."
                                            data-fecha="16/06/2025 08:45" data-tipo="Usuario no seleccionado"
                                            data-estado="Leída" title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- ── FILA 9: Cierre de convocatoria | Media | Leída ── -->
                                <tr>
                                    <td class="text-center">
                                        <span class="notif-type-icon notif-type-icon--cierre-conv"
                                            title="Cierre de convocatoria">
                                            <!--<i class="fas fa-folder-minus text-danger"></i>-->
                                        </span>
                                    </td>
                                    <td>Convocatoria cerrada</td>
                                    <td>La convocatoria <strong>Apoyo de Alimentación 2024-II</strong> ha sido cerrada
                                        oficialmente. No se admiten más postulaciones.</td>
                                    <td>10/06/2025 17:00</td>
                                    <!-- <td class="text-center">
                                        <span class="badge badge-warning text-dark">MEDIA</span>
                                    </td> -->
                                    <td class="text-center">
                                        <span class="badge notif-badge-read">
                                            <i class="fas fa-check mr-1" style="font-size: 0.55rem;"></i>Leída
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-secondary btn-sm btn-ver-notificacion" data-id="9"
                                            data-titulo="Convocatoria cerrada"
                                            data-descripcion="La convocatoria Apoyo de Alimentación 2024-II ha sido cerrada oficialmente. No se admiten más postulaciones."
                                            data-fecha="10/06/2025 17:00" data-tipo="Cierre de convocatoria"
                                            data-estado="Leída" title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- ── FILA 10: Fin de período de beneficio | Normal | Leída ── -->
                                <tr>
                                    <td class="text-center">
                                        <span class="notif-type-icon notif-type-icon--fin-beneficio"
                                            title="Finalización período de beneficio">
                                            <!--<i class="fas fa-calendar-times text-warning"></i>-->
                                        </span>
                                    </td>
                                    <td>Período de beneficio finalizado</td>
                                    <td>El período de beneficio de <strong>Andrés Felipe Vargas Quintero</strong> en el
                                        apoyo <em>Sostenimiento 2024-I</em> ha concluido.</td>
                                    <td>05/06/2025 00:00</td>
                                    <!-- <td class="text-center">
                                        <span class="badge badge-primary">NORMAL</span>
                                    </td> -->
                                    <td class="text-center">
                                        <span class="badge notif-badge-read">
                                            <i class="fas fa-check mr-1" style="font-size: 0.55rem;"></i>Leída
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-secondary btn-sm btn-ver-notificacion" data-id="10"
                                            data-titulo="Período de beneficio finalizado"
                                            data-descripcion="El período de beneficio de Andrés Felipe Vargas Quintero en el apoyo Sostenimiento 2024-I ha concluido."
                                            data-fecha="05/06/2025 00:00" data-tipo="Finalización período de beneficio"
                                            data-estado="Leída" title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div><!-- /.table-responsive -->

                </div><!-- /#tab-todas -->

                <!-- ============================================================
                     PESTAÑA 2: NO LEÍDAS
                     ============================================================ -->
                <div class="tab-pane fade" id="tab-no-leidas" role="tabpanel" aria-labelledby="tab-no-leidas-tab">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0 text-white">
                            <i class="fas fa-bell mr-2 text-warning"></i>
                            Notificaciones No Leídas
                        </h3>
                        <button type="button" class="btn btn-outline-warning btn-sm" id="btn-marcar-leidas-tab"
                            title="Marcar todas como leídas">
                            <i class="fas fa-check-double mr-1"></i> Marcar como leídas
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table id="tblNotificacionesNoLeidas"
                            class="table table-dark table-striped table-bordered dt-responsive nowrap tabla-notificaciones"
                            style="width:100%">
                            <thead style="background-color: #ffc107; color: black;">
                                <tr>
                                    <th style="width: 48px;" class="text-center">Tipo</th>
                                    <th>Título</th>
                                    <th>Descripción</th>
                                    <th style="width: 140px;">Fecha y Hora</th>
                                    <!-- <th style="width: 95px;" class="text-center">Prioridad</th> -->
                                    <th style="width: 60px;" class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr class="notif-row--unread">
                                    <td class="text-center"><i class="fas fa-folder-open text-success fa-lg"></i></td>
                                    <td class="font-weight-bold">Nueva convocatoria abierta</td>
                                    <td>Se ha abierto la convocatoria <strong>Apoyo de Sostenimiento 2025-I</strong>.
                                    </td>
                                    <td>25/06/2025 08:00</td>
                                    <td class="text-center">
                                        <button class="btn btn-info btn-sm btn-ver-notificacion" data-id="1"
                                            data-titulo="Nueva convocatoria abierta"
                                            data-descripcion="Se ha abierto la convocatoria Apoyo de Sostenimiento 2025-I."
                                            data-fecha="25/06/2025 08:00" data-tipo="Apertura de convocatoria"
                                            data-estado="No leída" title="Ver detalle"><i
                                                class="fas fa-eye"></i></button>
                                    </td>
                                </tr>

                                <tr class="notif-row--unread">
                                    <td class="text-center"><i class="fas fa-clock text-warning fa-lg"></i></td>
                                    <td class="font-weight-bold">Cierre próximo de inscripciones</td>
                                    <td>Las inscripciones para <strong>Apoyo de Transporte 2025-I</strong> cierran en 24
                                        horas.</td>
                                    <td>24/06/2025 16:30</td>
                                    <td class="text-center">
                                        <button class="btn btn-info btn-sm btn-ver-notificacion" data-id="2"
                                            data-titulo="Cierre próximo de inscripciones"
                                            data-descripcion="Las inscripciones para Apoyo de Transporte 2025-I cierran en 24 horas."
                                            data-fecha="24/06/2025 16:30" data-tipo="Cierre de inscripciones"
                                            data-estado="No leída" title="Ver detalle"><i
                                                class="fas fa-eye"></i></button>
                                    </td>
                                </tr>

                                <tr class="notif-row--unread">
                                    <td class="text-center"><i class="fas fa-paper-plane text-info fa-lg"></i></td>
                                    <td class="font-weight-bold">Nueva postulación recibida</td>
                                    <td><strong>Carlos Andrés Ruiz López</strong> envió su postulación para
                                        <em>Alimentación Especial</em>.
                                    </td>
                                    <td>24/06/2025 11:15</td>
                                    <td class="text-center">
                                        <button class="btn btn-info btn-sm btn-ver-notificacion" data-id="3"
                                            data-titulo="Nueva postulación recibida"
                                            data-descripcion="Carlos Andrés Ruiz López envió su postulación para Alimentación Especial."
                                            data-fecha="24/06/2025 11:15" data-tipo="Envío de postulación"
                                            data-estado="No leída" title="Ver detalle"><i
                                                class="fas fa-eye"></i></button>
                                    </td>
                                </tr>

                                <tr class="notif-row--unread">
                                    <td class="text-center"><!--<i class="fas fa-file-excel text-danger fa-lg"></i>-->
                                    </td>
                                    <td class="font-weight-bold">Documento bancario devuelto</td>
                                    <td>El certificado de <strong>Laura Milena Torres Gómez</strong> fue devuelto para
                                        corrección.</td>
                                    <td>23/06/2025 14:45</td>
                                    <td class="text-center">
                                        <button class="btn btn-info btn-sm btn-ver-notificacion" data-id="4"
                                            data-titulo="Documento bancario devuelto"
                                            data-descripcion="El certificado de Laura Milena Torres Gómez fue devuelto para corrección."
                                            data-fecha="23/06/2025 14:45" data-tipo="Documento devuelto/rechazado"
                                            data-estado="No leída" title="Ver detalle"><i
                                                class="fas fa-eye"></i></button>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div><!-- /.table-responsive -->

                </div><!-- /#tab-no-leidas -->

                <!-- ============================================================
                     PESTAÑA 3: ALTA PRIORIDAD
                     ============================================================ -->
                <!--<div class="tab-pane fade"
                     id="tab-alta-prioridad"
                     role="tabpanel"
                     aria-labelledby="tab-alta-prioridad-tab">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0 text-white">
                            <i class="fas fa-exclamation-triangle mr-2 text-danger"></i>
                            Notificaciones de Alta Prioridad
                        </h3>
                    </div>

                    <div class="table-responsive">
                        <table id="tblNotificacionesAlta" class="table table-dark table-striped table-bordered dt-responsive nowrap tabla-notificaciones" style="width:100%">
                            <thead style="background-color: #dc3545; color: white;">
                                <tr>
                                    <th style="width: 48px;" class="text-center">Tipo</th>
                                    <th>Título</th>
                                    <th>Descripción</th>
                                    <th style="width: 140px;">Fecha y Hora</th>
                                    <th style="width: 100px;" class="text-center">Estado</th>
                                    <th style="width: 60px;" class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr class="notif-row--unread">
                                    <td class="text-center"><i class="fas fa-folder-open text-success fa-lg"></i></td>
                                    <td class="font-weight-bold">Nueva convocatoria abierta</td>
                                    <td>Se ha abierto la convocatoria <strong>Apoyo de Sostenimiento 2025-I</strong>. Los aprendices pueden iniciar su inscripción.</td>
                                    <td>25/06/2025 08:00</td>
                                    <td class="text-center">
                                        <span class="badge notif-badge-unread">
                                            <i class="fas fa-circle mr-1" style="font-size: 0.55rem;"></i>No leída
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-info btn-sm btn-ver-notificacion"
                                                data-id="1"
                                                data-titulo="Nueva convocatoria abierta"
                                                data-descripcion="Se ha abierto la convocatoria Apoyo de Sostenimiento 2025-I."
                                                data-fecha="25/06/2025 08:00"
                                                data-tipo="Apertura de convocatoria"
                                                data-prioridad="ALTA"
                                                data-estado="No leída"
                                                title="Ver detalle"><i class="fas fa-eye"></i></button>
                                    </td>
                                </tr>

                                <tr class="notif-row--unread">
                                    <td class="text-center"><i class="fas fa-clock text-warning fa-lg"></i></td>
                                    <td class="font-weight-bold">Cierre próximo de inscripciones</td>
                                    <td>Las inscripciones para <strong>Apoyo de Transporte 2025-I</strong> cierran en 24 horas. Verifique los formularios pendientes.</td>
                                    <td>24/06/2025 16:30</td>
                                    <td class="text-center">
                                        <span class="badge notif-badge-unread">
                                            <i class="fas fa-circle mr-1" style="font-size: 0.55rem;"></i>No leída
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-info btn-sm btn-ver-notificacion"
                                                data-id="2"
                                                data-titulo="Cierre próximo de inscripciones"
                                                data-descripcion="Las inscripciones para Apoyo de Transporte 2025-I cierran en 24 horas."
                                                data-fecha="24/06/2025 16:30"
                                                data-tipo="Cierre de inscripciones"
                                                data-prioridad="ALTA"
                                                data-estado="No leída"
                                                title="Ver detalle"><i class="fas fa-eye"></i></button>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
                /#tab-alta-prioridad -->

            </div><!-- /.tab-content -->
        </div><!-- /.card-body -->
    </div><!-- /#panel-notificaciones /.card -->

</section><!-- /.content -->


<!-- ================================================================
     MODAL: DETALLE DE NOTIFICACIÓN
     ================================================================ -->
<div class="modal fade" id="modal-detalle-notificacion" tabindex="-1" role="dialog"
    aria-labelledby="modalDetalleNotifLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="background-color: #343a40; color: #fff; border: 1px solid #6c757d;">
            <div class="modal-header" style="background-color: #343a40; border-bottom: 1px solid #495057;">
                <h5 class="modal-title" id="modalDetalleNotifLabel">
                    <i class="fas fa-bell mr-2 text-info"></i>
                    Detalle de Notificación
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background-color: #2b3035;">

                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <p class="mb-1 text-muted small text-uppercase">Título</p>
                        <p class="mb-0 font-weight-bold" id="modal-notif-titulo">—</p>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <p class="mb-1 text-muted small text-uppercase">Tipo de evento</p>
                        <p class="mb-0" id="modal-notif-tipo">—</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <p class="mb-1 text-muted small text-uppercase">Fecha y Hora</p>
                        <p class="mb-0" id="modal-notif-fecha">—</p>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <p class="mb-1 text-muted small text-uppercase">Estado</p>
                        <p class="mb-0" id="modal-notif-estado">—</p>
                    </div>
                </div>

                <hr style="border-color: #495057;">

                <div>
                    <p class="mb-1 text-muted small text-uppercase">Descripción completa</p>
                    <p class="mb-0" id="modal-notif-descripcion">—</p>
                </div>

            </div>
            <div class="modal-footer" style="background-color: #343a40; border-top: 1px solid #495057;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cerrar
                </button>
                <!-- Botón placeholder para futura funcionalidad -->
                <button type="button" class="btn btn-info" id="btn-modal-marcar-leida" disabled>
                    <i class="fas fa-check mr-1"></i> Marcar como leída
                </button>
            </div>
        </div>
    </div>
</div><!-- /#modal-detalle-notificacion -->


<!-- ================================================================
     ESTILOS ESPECÍFICOS DEL MÓDULO NOTIFICACIONES
     ================================================================ -->
<style>
    /* ── Tabs oscuras (mismo patrón que financiera.php) ── */
    #panel-notificaciones.card-dark.card-tabs .nav-tabs .nav-link.active {
        background-color: #2b3035 !important;
        border-color: #6c757d #6c757d transparent !important;
        color: #fff !important;
    }

    #panel-notificaciones.card-dark.card-tabs .nav-tabs .nav-link {
        color: #adb5bd;
        border-top: 3px solid transparent;
    }

    #panel-notificaciones.card-dark.card-tabs .nav-tabs .nav-link:hover {
        color: #fff;
        border-top-color: #6c757d;
        background-color: #343a40;
    }

    /* ── Fila no leída: resaltado sutil ── */
    #vista-notificaciones .notif-row--unread {
        border-left: 3px solid #17a2b8;
    }

    #vista-notificaciones .notif-row--unread td:nth-child(2) {
        font-weight: 700;
    }

    /* ── Badges de estado personalizados ── */
    #vista-notificaciones .notif-badge-unread {
        background-color: #17a2b8;
        color: #fff;
        font-size: 0.72rem;
        white-space: nowrap;
    }

    #vista-notificaciones .notif-badge-read {
        background-color: #6c757d;
        color: #fff;
        font-size: 0.72rem;
        white-space: nowrap;
    }

    /* ── Icono de tipo en tabla ── */
    #vista-notificaciones .notif-type-icon {
        font-size: 1.15rem;
    }

    /* ── TARJETAS DE RESUMEN RÁPIDO ── */
    #vista-notificaciones .notif-stat-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        background-color: #343a40;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    #vista-notificaciones .notif-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.35);
    }

    #vista-notificaciones .notif-stat-card__icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    #vista-notificaciones .notif-stat-card__info {
        display: flex;
        flex-direction: column;
    }

    #vista-notificaciones .notif-stat-card__number {
        font-size: 1.6rem;
        font-weight: 700;
        color: #fff;
        line-height: 1;
    }

    #vista-notificaciones .notif-stat-card__label {
        font-size: 0.8rem;
        color: #adb5bd;
        margin-top: 2px;
    }

    /* Variantes de color por tarjeta */
    #vista-notificaciones .notif-stat-card--total .notif-stat-card__icon {
        background-color: rgba(23, 162, 184, 0.18);
        color: #17a2b8;
    }

    #vista-notificaciones .notif-stat-card--unread .notif-stat-card__icon {
        background-color: rgba(255, 193, 7, 0.18);
        color: #ffc107;
    }

    #vista-notificaciones .notif-stat-card--high .notif-stat-card__icon {
        background-color: rgba(220, 53, 69, 0.18);
        color: #dc3545;
    }

    #vista-notificaciones .notif-stat-card--email .notif-stat-card__icon {
        background-color: rgba(0, 123, 255, 0.18);
        color: #007bff;
    }
</style>


<!-- ================================================================
     INICIALIZACIÓN DATATABLES (español) + LÓGICA UI BÁSICA
     ================================================================ -->
<script>
    $(document).ready(function () {

        // ── Configuración de idioma DataTables (compartida) ──
        var idiomaES = {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        };

        // ── Inicializar todas las tablas del módulo ──
        $(".tabla-notificaciones").each(function () {
            var tablaId = $(this).attr('id');
            var table = $(this).DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                buttons: ["excel", "pdf"],
                language: idiomaES,
                order: [[3, "desc"]]  // Ordenar por fecha descendente por defecto
            });
            table.buttons().container()
                .appendTo('#' + tablaId + '_wrapper .col-md-6:eq(0)');
        });

        // ── Ver detalle de notificación (abre el modal) ──
        $(document).on("click", ".btn-ver-notificacion", function () {
            var id = $(this).data("id");
            var titulo = $(this).data("titulo");
            var descripcion = $(this).data("descripcion");
            var fecha = $(this).data("fecha");
            var tipo = $(this).data("tipo");
            // var prioridad   = $(this).data("prioridad");
            var estado = $(this).data("estado");

            // Construir badge de prioridad
            // var prioridadClass = "badge-primary";
            // if (prioridad === "ALTA")  prioridadClass = "badge-danger";
            // if (prioridad === "MEDIA") prioridadClass = "badge-warning text-dark";

            // Construir badge de estado
            var estadoBadge = estado === "No leída"
                ? '<span class="badge notif-badge-unread"><i class="fas fa-circle mr-1" style="font-size:0.55rem;"></i>No leída</span>'
                : '<span class="badge notif-badge-read"><i class="fas fa-check mr-1" style="font-size:0.55rem;"></i>Leída</span>';

            // Poblar el modal
            $("#modal-notif-titulo").text(titulo);
            $("#modal-notif-tipo").text(tipo);
            $("#modal-notif-fecha").text(fecha);
            // $("#modal-notif-prioridad").html('<span class="badge ' + prioridadClass + '">' + prioridad + '</span>');
            $("#modal-notif-estado").html(estadoBadge);
            $("#modal-notif-descripcion").html(descripcion);

            // Habilitar botón marcar leída solo si no está leída (mockup UI)
            if (estado === "No leída") {
                $("#btn-modal-marcar-leida").prop("disabled", false);
            } else {
                $("#btn-modal-marcar-leida").prop("disabled", true);
            }

            // Mostrar modal
            $("#modal-detalle-notificacion").modal("show");
        });

        // ── Botón "Marcar todas como leídas" (placeholder UI) ──
        $(document).on("click", "#btn-marcar-todas-leidas, #btn-marcar-leidas-tab", function () {
            Swal.fire({
                title: 'Marcar todas como leídas',
                text: 'Esta acción marcará todas las notificaciones como leídas.',
                icon: 'question',
                background: '#343a40',
                showCancelButton: true,
                confirmButtonColor: '#17a2b8',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, marcar todas',
                cancelButtonText: 'Cancelar'
            }).then(function (result) {
                if (result.isConfirmed) {
                    // TODO: implementar llamada AJAX en fase de backend
                    Swal.fire({
                        icon: 'info',
                        title: 'Función en desarrollo',
                        text: 'Esta funcionalidad estará disponible en la próxima fase.',
                        background: '#343a40',
                        confirmButtonColor: '#17a2b8'
                    });
                }
            });
        });

        // ── Botón "Marcar como leída" en modal (placeholder UI) ──
        $(document).on("click", "#btn-modal-marcar-leida", function () {
            // TODO: implementar llamada AJAX en fase de backend
            Swal.fire({
                icon: 'info',
                title: 'Función en desarrollo',
                text: 'El marcado de notificaciones estará disponible en la próxima fase.',
                background: '#343a40',
                confirmButtonColor: '#17a2b8'
            });
        });

    });
</script>