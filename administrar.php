<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/icons/Grammer_Logo.ico" type="image/x-icon">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Administrar</title>

    <!-- CSS FILES -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/bootstrap-icons.css" rel="stylesheet">

    <link href="css/styles.css" rel="stylesheet">

    <link href="css/admin.css" rel="stylesheet">

    <!-- ?php
    session_start();
    $nombreUser = $_SESSION['nombreUsuario'];
    $esAdmin = $_SESSION['admin'];

    if ($nombreUser == null || $esAdmin == 0){
        header("Location: https://grammermx.com/RH/CajitaGrammer/login.php");
    }

    ?>-->
</head>

<body>
<main>

    <nav class="navbar navbar-expand-lg bg-light shadow-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="images/icons/croc_logo.png" class="logo img-fluid" alt="Logo CROC">
                <img src="images/logo.png" class="logo img-fluid" alt="Logo Grammer">
                <span>
                    Cajita Feliz
                    <small>Grammer Automotive Puebla S.A. de C.V</small>
                </span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#adminPrestamosSeccion">Préstamos</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#adminAhorroSeccion">Caja de Ahorro</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#adminFiltrarSolicitudes">Filtrar Solicitudes</a>
                    </li>

                    <li class="nav-item ms-3">
                        <form id="logoutForm" action="dao/daoLogin.php" method="POST" style="display: none;">
                            <input type="hidden" name="cerrarSesion" value="true">
                        </form>
                        <a class="nav-link" id="cerrarSesion" href="#">Salir</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="tabla-section" id="adminPrestamosSeccion">
        <div class=""></div>
        <div class="container">
            <div class="row">
                <div class="container mt-5">
                    <h2 class="text-center">Solicitudes de Préstamos</h2>
                    <button class="btn btn-success text-right" onclick="exportTableToExcel('solicitudes', 'SolicitudesDePrestamos')">
                        Exportar a Excel
                    </button>
                    <table class="table table-striped table-bordered mt-3" id="tablaPrestamosAdmin">
                        <thead class="table-dark">
                        <tr>
                            <th>Folio</th>
                            <th>Fecha Solicitud</th>
                            <th>Nómina</th>
                            <th>Monto</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody id="bodyPrestamosAdmin"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <section class="tabla-section" id="adminAhorroSeccion">
        <div class="section-overlay"></div>
        <div class="container">
            <div class="row">

                <div class="container mt-5">
                    <h2 class="text-center">Solicitudes de Caja de Ahorro</h2>
                    <h3 class="text-center"><br>Iniciar ahorro</br></h3>
                    <button class="btn btn-success text-right"  onclick="exportTableToExcel()">
                        Exportar a Excel
                    </button>
                    <table class="table table-striped table-bordered mt-3" id="tablaAhorroAdmin">
                        <thead class="table-dark">
                        <tr>
                            <th>Folio</th>
                            <th>Fecha Solicitud</th>
                            <th>Nómina</th>
                            <th>Monto</th>
                        </tr>
                        </thead>
                        <tbody id="bodyAhorroAdmin"></tbody>
                    </table>

                    <h3 class="text-center"><br>Retiros</br></h3>
                    <button class="btn btn-success text-right"  onclick="exportTableToExcel()">
                        Exportar a Excel
                    </button>
                    <table class="table table-striped table-bordered mt-3" id="tablaRetirosAdmin">
                        <thead class="table-dark">
                        <tr>
                            <th>Folio</th>
                            <th>Fecha Solicitud</th>
                            <th>Id caja</th>
                            <th>Nómina</th>
                            <th>Fecha de depósito</th>
                            <th>Monto depositado</th>
                        </tr>
                        </thead>
                        <tbody id="bodyRetirosAdmin"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding" id="adminFiltrarSolicitudes">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-12 text-center mx-auto">
                    <h2 class="mb-5">Filtrar solicitudes</h2>
                </div>
                <div class="col-lg-6 col-md-6 col-12 mb-4 mb-lg-0 text-center mx-auto">
                    <div id="divForm" class="featured-block d-flex justify-content-center align-items-center p-4 border rounded">
                        <form class="w-100">
                            <div class="form-group mb-3">
                                <label for="selectTipoConsulta" class="form-label">Tipo de consulta</label>
                                <select id="selectTipoConsulta" name="selectTipoConsulta" class="form-control" onchange="cargarAnio()" required data-error="Por favor seleccione un tipo de consulta.">
                                    <option value="">Seleccione el tipo de consulta*</option>
                                    <option value="1">Préstamos</option>
                                    <option value="2">Caja de ahorro</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="selectAnio" class="form-label">Año</label>
                                <select id="selectAnio" name="selectAnio" class="form-control" required data-error="Por favor seleccione un año válido.">
                                    <option value="">Seleccione el año*</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100" onclick="cargarSolicitudes()">Ver solicitudes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Modal para responder solicitud -->
    <div class="modal fade" id="modalRespPrestamo" tabindex="-1" aria-labelledby="responderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="respModalTit">Responder Solicitud de Préstamo Folio <span id="numSolPres"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <table class="table">
                        <tr>
                            <td>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="folioSolicitud" placeholder="Folio de solicitud">
                                    <label for="folioSolicitud">Folio de solicitud</label>
                                </div>
                            </td>
                            <td>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="fechaSolicitud" placeholder="Fecha solicitud">
                                    <label for="fechaSolicitud">Fecha solicitud</label>
                                </div>
                            </td>
                            <td>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="montoSolicitado" placeholder="Monto solicitado">
                                    <label for="montoSolicitado">Monto solicitado</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="nomina" placeholder="Nómina">
                                    <label for="nomina">Nómina</label>
                                </div>
                            </td>
                            <td>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="nombre" placeholder="Nombre">
                                    <label for="nombre">Nombre</label>
                                </div>
                            </td>
                            <td>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="telefono" placeholder="Teléfono">
                                    <label for="telefono">Teléfono</label>
                                </div>
                            </td>
                        </tr>
                    </table>



                    <!--
                    <table class="table">
                        <tr>
                            <td>
                                <label for="folioSolicitud" class="form-label">Folio de solicitud</label>
                            </td>
                            <td>
                                <input type="text" class="form-control" id="folioSolicitud">
                            </td>
                            <td>
                                <label for="fechaSolicitud" class="form-label">Fecha solicitud</label>
                            </td>
                            <td>
                                <input type="text" class="form-control" id="fechaSolicitud">
                            </td>
                            <td>
                                <label for="montoSolicitado" class="form-label">Monto Solicitado</label>
                            </td>
                            <td>
                                <input type="text" class="form-control" id="montoSolicitado">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="nomina" class="form-label">Nómina</label>
                            </td>
                            <td>
                                <input type="text" class="form-control" id="nomina">
                            </td>
                            <td>
                                <label for="nombre" class="form-label">Nombre</label>
                            </td>
                            <td>
                                <input type="text" class="form-control" id="nombre">
                            </td>
                            <td>
                                <label for="telefono" class="form-label">Teléfono</label>
                            </td>
                            <td>
                                <input type="text" class="form-control" id="telefono">
                            </td>
                        </tr>
                    </table>
                    -->

                    <form>
                        <div class="container col-md-12">
                            <div class="row">
                                <!-- Primer div: Monto Aprobado -->
                                <div class="col-sm-5 col-md-6 mb-3">
                                    <label for="inMontoAprobado" class="form-label">Monto Aprobado</label>
                                    <input type="number" class="form-control" id="inMontoAprobado" placeholder="$5,000">
                                </div>

                                <!-- Segundo div: Estatus del préstamo -->
                                <div class="col-sm-5 col-md-6 mb-3">
                                    <label for="selEstatus" class="form-label">Estatus del préstamo</label>
                                    <select class="form-control" id="selEstatus">
                                        <option value="">Seleccione un estatus*</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="textareaComentarios" class="form-label">Comentarios</label>
                            <textarea class="form-control" id="textareaComentarios" rows="3" placeholder="Escribe tus observaciones aquí..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary">Enviar Respuesta</button>
                </div>
            </div>
        </div>
    </div>
</main>


<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-12 mb-4">
                <img src="images/logo.png" class="logo img-fluid" alt="Logo">
            </div>

            <div class="col-lg-4 col-md-6 col-12 mb-4">
                <h5 class="site-footer-title mb-3">Enlaces rápidos</h5>

                <ul class="footer-menu">
                    <li class="footer-menu-item"><a href="index.php" class="footer-menu-link">Inicio</a></li>

                    <li class="footer-menu-item"><a href="index.php#section_1" class="footer-menu-link">Préstamo</a></li>

                    <li class="footer-menu-item"><a class="footer-menu-link" href="index.php#section_2">Caja de Ahorro</a></li>

                    <li class="footer-menu-item"><a class="footer-menu-link" href="index.php#section_4">Preguntas Frecuentes</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-6 col-12 mx-auto">
                <h5 class="site-footer-title mb-3">Información de contacto</h5>

                <p class="text-white d-flex mb-2">
                    <i class="bi-telephone me-2"></i>

                    <a href="tel: 442-296-7310" class="site-footer-link">
                        442 296 7310
                    </a>
                </p>

                <p class="text-white d-flex">
                    <i class="bi-envelope me-2"></i>

                    <a href="juanroberto.arreola@grammer.com" class="site-footer-link">
                        juanroberto.arreola@grammer.com
                    </a>
                </p>

                <p class="text-white d-flex mt-3">
                    <i class="bi-geo-alt me-2"></i>
                    Av. de la Luz 24, Benito Juárez, 76120
                    <br>Santiago de Querétaro, Qro.
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- -Archivos de jQuery-->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<!-- JAVASCRIPT FILES -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.sticky.js"></script>
<script src="js/counter.js"></script>
<script src="js/custom.js"></script>
<script src="js/general.js"></script>
<script src="js/administrador.js"></script>

<!-- DataTable -->
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const anioActual = new Date().getFullYear();
        initDataTablePresAdmin(anioActual);
        initDataTableAhorroAdmin(anioActual);
        initDataTableRetiroAdmin(anioActual);
    });

    $("#montoAprobado").on({
        "focus": function (event) {
            $(event.target).select();
        },
        "keyup": function (event) {
            $(event.target).val(function (index, value ) {
                return value.replace(/\D/g, "")
                    .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
            });
        }
    });
</script>

</body>

</html>
