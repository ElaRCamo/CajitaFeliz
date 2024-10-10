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

    <link href="css/templatemo-kind-heart-charity.css" rel="stylesheet">

    <link rel="stylesheet" href="css/login.css">

</head>
<body>
    <main>

        <div class="container" >
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="row">
                        <div class="col-6" id="form-container">
                            <div class="col-lg-6 col-12 " id="login-container">
                                <small><strong>Cajita Feliz Grammer</strong></small>
                                <div class="wrapper">
                                    <form id="formInicioSesion" action="dao/login.php" method="post"  >
                                        <h2 id="iniciarSesion"><br>¡Hola,</h2>
                                        <h2 ><strong>Bienvenido!</strong></h2>
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
                                        <button type="submit" class="btn login" name="iniciarSesionBtn">Iniciar Sesión</button>
                                    </form>
                                    <div>
                                        <label>Siguenos</label>
                                        <a href="">facebook</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6" id="conteinerIMG">
                                <img src="images/slide/pigCajita.png" id="imgLogin" class="img-fluid" alt="CajitaFuerte">
                        </div>
                    </div>
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
