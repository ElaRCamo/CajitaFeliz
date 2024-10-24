<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Inicio Sesión</title>

    <!-- CSS FILES -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/bootstrap-icons.css" rel="stylesheet">

    <link href="css/styles.css" rel="stylesheet">

    <link rel="stylesheet" href="css/login.css">

</head>
<body>
    <main>
        <div class="container container-center">
            <div class="rounded-div shadow">
                <div class="left-side">
                    <small><strong>Cajita Feliz Grammer</strong></small>
                    <h2 id="iniciarSesion"><br>¡Hola,</h2>
                    <h2 ><strong>Bienvenido!</strong></h2>
                    <form id="formInicioSesion" action="dao/daoLogin.php" method="post"  >
                        <div class="input-box form-group" id="userDiv">
                            <label for="numNomina">Nómina</label>
                            <input type="text" class="form-control" name="numNomina" id="numNomina" placeholder="No. de nómina" required data-error="Ingrese un número de nómina válido.">
                            <i class="las la-user"></i>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="input-box form-group" id="tagDiv">
                            <label for="password">TAG</label>
                            <input type="password" class="form-control" name="password"  id="password" placeholder="TAG" required data-error="Ingrese un TAG válido.">
                            <i class="las la-lock"></i>
                            <div class="invalid-feedback"></div>
                        </div>
                        <button type="button" class="btn login" name="iniciarSesionBtn">Iniciar Sesión</button>
                    </form>
                    <div>
                        <label>Siguenos</label>
                        <a href="">facebook</a>
                    </div>
                </div>
                <div class="right-side">
                    <!-- La parte derecha tendrá la imagen de fondo -->
                </div>
            </div>
        </div>
    </main>
</body>

<!-- JAVASCRIPT FILES -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.sticky.js"></script>
<script src="js/counter.js"></script>
<script src="js/custom.js"></script>

</html>
