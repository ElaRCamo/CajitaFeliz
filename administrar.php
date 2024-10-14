<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="">
    <meta name="author" content="">

    <title>Kind Heart Charity - Donation</title>

    <!-- CSS FILES -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/bootstrap-icons.css" rel="stylesheet">

    <link href="css/templatemo-kind-heart-charity.css" rel="stylesheet">

</head>

<body>
<main>

    <nav class="navbar navbar-expand-lg bg-light shadow-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="images/logo.png" class="logo img-fluid" alt="Kind Heart Charity">
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
                        <a class="nav-link click-scroll" href="index.php#solicitarPrestamoSeccion">Préstamo</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="index.php#crearAhorroSeccion">Caja de Ahorro</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="index.php#section_preguntas">Preguntas Frecuentes</a>
                    </li>

                    <li class="nav-item ms-3">
                        <a class="nav-link custom-btn custom-border-btn btn" onclick="">Mis Solicitudes</a>
                    </li>

                    <li class="nav-item ms-3">
                        <a class="nav-link custom-btn custom-border-btn btn" href="administrar.php">Administrar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="section-padding">
        <div class="container">
            <div class="row">

                <div class="col-lg-10 col-12 text-center mx-auto">
                    <h2 class="mb-5">Cajita Feliz Grammer</h2>
                </div>

                <div class="col-lg-6 col-md-6 col-12 mb-4 mb-lg-0">
                    <div class="featured-block d-flex justify-content-center align-items-center">
                        <a href="#solicitarPrestamoSeccion" class="d-block">
                            <img src="images/icons/prestamo.png" class="featured-block-image img-fluid" alt="">

                            <p class="featured-block-text"> <strong>Préstamo</strong></p>
                        </a>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-12 mb-4 mb-lg-0 mb-md-4">
                    <div class="featured-block d-flex justify-content-center align-items-center">
                        <a href="donate.html" class="d-block">
                            <img src="images/icons/ahorro.png" class="featured-block-image img-fluid" alt="">

                            <p class="featured-block-text"><strong>Caja de Ahorro </strong></p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="tabla-section" id="solicitarPrestamoSeccion">
        <div class="section-overlay"></div>
        <div class="container">
            <div class="row">

                <div class="container mt-5">
                    <h2 class="text-center">Solicitudes de Préstamos</h2>
                    <button class="btn btn-success text-right"  onclick="exportTableToExcel('solicitudes', 'SolicitudesDePrestamos')">
                        Exportar a Excel
                    </button>
                    <table class="table table-striped table-bordered mt-3">
                        <thead class="table-dark">
                        <tr>
                            <th>Folio</th>
                            <th>Fecha Solicitud</th>
                            <th>Nómina</th>
                            <th>Nombre</th>
                            <th>Monto</th>
                            <th>Teléfono</th>
                            <th>Avales (Nómina, Nombre)</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>001</td>
                            <td>2024-10-08</td>
                            <td>12345</td>
                            <td>Juan Pérez</td>
                            <td>$10,000.00</td>
                            <td>555-1234</td>
                            <td>54321, María López</td>
                            <td>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#responderModal">
                                    Responder
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>002</td>
                            <td>2024-10-09</td>
                            <td>67890</td>
                            <td>Ana García</td>
                            <td>$15,000.00</td>
                            <td>555-5678</td>
                            <td>09876, Carlos Gómez</td>
                            <td>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#responderModal">
                                    Responder
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>001</td>
                            <td>2024-10-08</td>
                            <td>12345</td>
                            <td>Juan Pérez</td>
                            <td>$10,000.00</td>
                            <td>555-1234</td>
                            <td>54321, María López</td>
                            <td>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#responderModal">
                                    Responder
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>002</td>
                            <td>2024-10-09</td>
                            <td>67890</td>
                            <td>Ana García</td>
                            <td>$15,000.00</td>
                            <td>555-5678</td>
                            <td>09876, Carlos Gómez</td>
                            <td>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#responderModal">
                                    Responder
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>001</td>
                            <td>2024-10-08</td>
                            <td>12345</td>
                            <td>Juan Pérez</td>
                            <td>$10,000.00</td>
                            <td>555-1234</td>
                            <td>54321, María López</td>
                            <td>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#responderModal">
                                    Responder
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>002</td>
                            <td>2024-10-09</td>
                            <td>67890</td>
                            <td>Ana García</td>
                            <td>$15,000.00</td>
                            <td>555-5678</td>
                            <td>09876, Carlos Gómez</td>
                            <td>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#responderModal">
                                    Responder
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>001</td>
                            <td>2024-10-08</td>
                            <td>12345</td>
                            <td>Juan Pérez</td>
                            <td>$10,000.00</td>
                            <td>555-1234</td>
                            <td>54321, María López</td>
                            <td>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#responderModal">
                                    Responder
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>002</td>
                            <td>2024-10-09</td>
                            <td>67890</td>
                            <td>Ana García</td>
                            <td>$15,000.00</td>
                            <td>555-5678</td>
                            <td>09876, Carlos Gómez</td>
                            <td>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#responderModal">
                                    Responder
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>001</td>
                            <td>2024-10-08</td>
                            <td>12345</td>
                            <td>Juan Pérez</td>
                            <td>$10,000.00</td>
                            <td>555-1234</td>
                            <td>54321, María López</td>
                            <td>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#responderModal">
                                    Responder
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>002</td>
                            <td>2024-10-09</td>
                            <td>67890</td>
                            <td>Ana García</td>
                            <td>$15,000.00</td>
                            <td>555-5678</td>
                            <td>09876, Carlos Gómez</td>
                            <td>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#responderModal">
                                    Responder
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </section>

    <!-- Modal para responder solicitud -->
    <div class="modal fade" id="responderModal" tabindex="-1" aria-labelledby="responderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="responderModalLabel">Responder Solicitud</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="respuesta" class="form-label">Escribe tu respuesta</label>
                            <textarea class="form-control" id="respuesta" rows="3" placeholder="Escribe tu respuesta aquí..."></textarea>
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
                <img src="images/logo.png" class="logo img-fluid" alt="">
            </div>

            <div class="col-lg-4 col-md-6 col-12 mb-4">
                <h5 class="site-footer-title mb-3">Enlaces rápidos</h5>

                <ul class="footer-menu">
                    <li class="footer-menu-item"><a href="index.php" class="footer-menu-link">Inicio</a></li>

                    <li class="footer-menu-item"><a href="administrar.php" class="footer-menu-link">Préstamo</a></li>

                    <li class="footer-menu-item"><a href="cajaAhorro.php" class="footer-menu-link">Caja de Ahorro</a></li>

                    <li class="footer-menu-item"><a href="#section_preguntas" class="footer-menu-link">Preguntas Frecuentes</a></li>
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
                    Av. de la Luz 24, Benito Juárez, 76120 Santiago de Querétaro, Qro.
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- JavaScript para exportar la tabla a Excel -->
<script>
    function exportTableToExcel(tableID, filename = '') {
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

        // Especifica el nombre del archivo
        filename = filename ? filename + '.xls' : 'excel_data.xls';

        // Crea el enlace de descarga
        downloadLink = document.createElement("a");
        document.body.appendChild(downloadLink);

        if (navigator.msSaveOrOpenBlob) {
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob(blob, filename);
        } else {
            // Crea un enlace temporal para la descarga
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

            // Configura el nombre del archivo
            downloadLink.download = filename;

            // Dispara la descarga
            downloadLink.click();
        }
    }
</script>

<!-- JAVASCRIPT FILES -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.sticky.js"></script>
<script src="js/counter.js"></script>
<script src="js/custom.js"></script>

</body>

</html>
