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
    <div class="page-content">
        <div class="records table-responsive">
            <div class="table-Conteiner table-responsive" id="divTablaSolicitudes">
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

    <script>
        window.addEventListener("load",async () => {
            await initDataTable();
        })
    </script>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.sticky.js"></script>
<script src="js/click-scroll.js"></script>
<script src="js/counter.js"></script>
<script src="js/custom.js"></script>
<script src="js/prestamos.js"></script>
<script src="js/ahorros.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
