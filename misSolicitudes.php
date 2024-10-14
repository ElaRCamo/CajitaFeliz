<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/icons/Grammer_Logo.ico" type="image/x-icon">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Mis solicitudes</title>

    <!-- CSS FILES -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/bootstrap-icons.css" rel="stylesheet">

    <link href="css/templatemo-kind-heart-charity.css" rel="stylesheet">

    <link href="css/misSolicitudes.css" rel="stylesheet">

    <?php
    session_start();
    $nombreUser = $_SESSION['nombreUsuario'];
    $esAdmin = $_SESSION['admin'];

    if ($nombreUser == null){
        header("Location: https://grammermx.com/RH/CajitaGrammer/login.php");
    }

    ?>

</head>
<body>
<nav class="navbar navbar-expand-lg bg-light shadow-lg">
    <div class="container" id="top">
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
                    <a class="nav-link click-scroll" href="#top">Inicio</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link click-scroll" href="#section_1">Préstamo</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link click-scroll" href="#section_2">Caja de Ahorro</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link click-scroll" href="#section_4">Preguntas Frecuentes</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link click-scroll" onclick="estatutosAhorro()">Estatutos</a>
                </li>

                <?php if($esAdmin == 0 ){ ?>
                    <li class="nav-item ms-3">
                        <a class="nav-link custom-btn custom-border-btn btn" href="misSolicitudes.php">Mis Solicitudes</a>
                    </li>
                <?php }?>

                <?php if($esAdmin == 1 ){ ?>
                    <li class="nav-item ms-3">
                        <a class="nav-link custom-btn custom-border-btn btn" href="administrar.php">Administrar</a>
                    </li>
                <?php }?>

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

<main>
    <section class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-12 p-0">
                    <div class="page-content">
                        <div class="records table-responsive">
                            <div class="table-Conteiner table-responsive" id="divTablaSolicitudes">
                                <h3 class="mb-4">Solicitudes de Préstamo</h3>
                                <div id="contenedorAzul"></div>
                                <table class="dataTable tableSearch table" id="tablaSolicitudes" >
                                    <thead>
                                    <tr>
                                        <th class="centered" id="folio">FOLIO</th>
                                        <th class="centered">MI NÓMINA</th>
                                        <th class="centered">FECHA DE SOLICITUD</th>
                                        <th class="centered">MONTO SOLICITADO </th>
                                        <th class="centered">ESTATUS </th>
                                        <th class="centered">ACCIONES</th>

                                        <!--
                                        acciones : ver respuesta, Agregar avales
                                        <th class="centered">FECHA RESPUESTA</th>
                                        <th class="centered">MONTO APROBADO </th>
                                        <th class="centered">FECHA DEPÓSITO</th>
                                        <th class="centered">COMENTARIOS</th>
                                        -->
                                    </tr>
                                    </thead>
                                    <tbody id="misSolicitudesBody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-12 p-0">
                    <div class="page-content">
                        <div class="records table-responsive">
                            <div class="table-Conteiner table-responsive" id="divTablaSolicitudes">
                                <h3 class="mb-4">Solicitudes de Caja de Ahorro</h3>
                                <div id="contenedorAzul"></div>
                                <h4 class="mb-4">Caja de Ahorro</h4>
                                <table class="dataTable tableSearch table" id="tablaCajaAhorro" >
                                    <thead>
                                    <tr>
                                        <th class="centered" id="folioCA">FOLIO</th>
                                        <th class="centered">MI NÓMINA</th>
                                        <th class="centered">FECHA DE SOLICITUD</th>
                                        <th class="centered">MONTO AHORRO </th>
                                    </tr>
                                    </thead>
                                    <tbody id="cajaAhorroBody"></tbody>
                                </table>

                                <h4 class="mb-4"><br>Solicitudes de retiro</h4>
                                <table class="dataTable tableSearch table" id="tablaRetiros" >
                                    <thead>
                                    <tr>
                                        <th class="centered" id="folioRetiro">FOLIO</th>
                                        <th class="centered">MI NÓMINA</th>
                                        <th class="centered">FECHA DE SOLICITUD</th>
                                    </tr>
                                    </thead>
                                    <tbody id="retirosBody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>




<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.sticky.js"></script>
<script src="js/counter.js"></script>
<script src="js/custom.js"></script>
<script src="js/misSolicitudes.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- DataTable -->
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
<script>
    window.addEventListener("load",async () => {
        await initDataTable();
        await initDataTableCaja();
    })
</script>
</body>
</html>
