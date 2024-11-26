<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/icons/Grammer_Logo.ico" type="image/x-icon">

    <meta name="description" content="">
    <meta name="author" content="">

    <title>Cajita Feliz Grammer</title>

    <!-- CSS FILES -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/bootstrap-icons.css" rel="stylesheet">

   <link href="css/styles.css" rel="stylesheet">

    <script src="js/prestamos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php
    session_start();

    // Verificar si el usuario está logueado
    $nombreUser = isset($_SESSION['nombreUsuario']) ? $_SESSION['nombreUsuario'] : null;
    $esAdmin = isset($_SESSION['admin']) ? $_SESSION['admin'] : null;

    // Verificar si se pasó el parámetro `user` en la URL
    $user = isset($_GET['user']) ? $_GET['user'] : null;

    // Si el nombre de usuario no está definido (sesión no válida), redirige a login
    if ($nombreUser == null && $user == null) {
        header("Location: https://grammermx.com/RH/CajitaGrammer/login.php");
        exit;
    }
    ?>
</head>

<body onload="consultarFechaConvocatoria()">

    <?php if ($nombreUser == null && $user != null): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            validarUser('<?php echo $user; ?>');
        });
    </script>
    <?php endif; ?>

    <?php if ($nombreUser != null): ?>
    <nav class="navbar navbar-expand-lg bg-light shadow-lg">
        <div class="container" id="top">
            <a class="navbar-brand" href="index.php">
                <img src="images/icons/GrammerAzul.png" class="m-lg-3 logo img-fluid" alt="Logo Grammer">
                <img src="images/icons/croc_logo.png" class="m-lg-3 logo img-fluid" alt="Logo CROC">
                <span id="titCajita">Caja de Ahorro</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#section_1" onclick="fCargarPrestamo()">Préstamo</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#section_2" onclick="fCrearAhorro()">Caja de Ahorro</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#section_4" onclick="fCargarPreguntas()">Preguntas Frecuentes</a>
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
        <section class="hero-section hero-section-full-height">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-12 p-0">
                        <div id="hero-slide" class="carousel carousel-fade slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="images/slide/slide1.jpg"
                                        class="carousel-image img-fluid" alt="exito">
                                    <div class="carousel-caption d-flex flex-column justify-content-end">
                                        <h1>¡Aviso importante!</h1>
                                        <p class="msjVaner">Recepción de préstamos a partir del día <span id="fechaPermitida"></span> a las <span id="horaPermitida"></span> horas.</p>
                                    </div>
                                </div>

                                <div class="carousel-item">
                                    <img src="images/slide/slide2.jpg"
                                        class="carousel-image img-fluid" alt="PersonaFeliz">
                                    <div class="carousel-caption d-flex flex-column justify-content-end">
                                        <h1>Fácil y Posible</h1>
                                        <p class="msjVaner">Recuerda que puedes ahorrar a partir de $100 pesos.</p>
                                    </div>
                                </div>

                                <div class="carousel-item">
                                    <img src="images/slide/slide3.png"
                                        class="carousel-image img-fluid" alt="Celebrando">
                                    <div class="carousel-caption d-flex flex-column justify-content-end">
                                        <h1>Recuerda</h1>
                                        <p class="msjVaner">Tus avales deben estar activos en la caja de ahorro.</p>
                                    </div>
                                </div>
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#hero-slide"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>

                            <button class="carousel-control-next" type="button" data-bs-target="#hero-slide"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section class="section-padding">
            <div class="container">
                <div class="row">

                    <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0">
                        <div class="featured-block d-flex justify-content-center align-items-center">
                            <a href="#section_1" class="d-block" onclick="fCargarPrestamo()">
                                <img src="images/icons/receipt.png" class="imgIcon featured-block-image img-fluid" alt="">

                                <p class="featured-block-text"> <strong>Solicitar préstamo</strong></p>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0 mb-md-4">
                        <div class="featured-block d-flex justify-content-center align-items-center">
                            <a href="#section_2" class="d-block" onclick="fCrearAhorro()">
                                <img src="images/icons/piggy-bank.png" class="imgIcon featured-block-image img-fluid" alt="">
                                <p class="featured-block-text"><strong>Caja de ahorro</strong></p>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0 mb-md-4">
                        <div class="featured-block d-flex justify-content-center align-items-center">
                            <a href="#section_3" class="d-block" onclick="fSolicitarRetiro()">
                                <img src="images/icons/money.png" class="imgIcon featured-block-image img-fluid" alt="">
                                <p class="featured-block-text"> <strong>Retirar ahorro</strong></p>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0">
                        <div class="featured-block d-flex justify-content-center align-items-center">
                            <a href="#section_4" class="d-block" onclick="fCargarPreguntas()">
                                <img src="images/icons/search.png" class="imgIcon featured-block-image img-fluid" alt="">
                                <p class="featured-block-text"><strong>Preguntas frecuentes</strong></p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="solPrestamo-section" id="section_1">
            <div class="section-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-12 mx-auto">
                        <form class="custom-form volunteer-form mb-5 mb-lg-0" action="" method="post" role="form" id="formSolicitarPrestamo">
                            <h3 class="mb-4">Solicitar Préstamo</h3>
                            <div class="row">
                                <div class="col-lg-6 col-12">
                                    <label for="telefono">Teléfono: </label>
                                    <input type="tel" name="telefono" id="telefono" class="form-control" placeholder="555-1234567" required>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <label for="montoPrestamo">Monto solicitado: </label>
                                    <input type="text" name="montoPrestamo" id="montoPrestamo" class="form-control" placeholder="$1,000.00" required>
                                </div>
                            </div>
                            <button type="button"  class="form-control" onclick="registrarPrestamo()">Solicitar</button>
                        </form>
                    </div>
                    <div class="col-lg-6 col-12" id="divMontos">
                        <div class="custom-block-body text-center" id="section_1_div">
                            <h4 class="text-white mt-lg-3 mb-lg-3">Recuerda:</h4>
                            <p class="text-white">El monto final del préstamo autorizado dependerá de varios factores, entre ellos el límite establecido:</p>
                            <ul class="text-white list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong>Sindicalizados:</strong>
                                    <ul class="list-group" id="sindUl">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">Menos de 3 meses de antigüedad: $5,000</li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">Menos de 6 meses de antigüedad: $10,000</li>
                                    </ul>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center"><strong>Todo el personal:</strong> Hasta 3 meses de salario</li>
                            </ul>
                            <div id="avisoPrestamo">
                                <p class="text-white" ><br><strong>¡IMPORTANTE!</strong><br> Las recepción de solicitudes de préstamo será a partir del día
                                    <span id="fechaPermitidaP"></span> a las <span id="horaPermitidaP"></span> horas.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="ahorro-section" id="section_2"><!--crearAhorroSeccion-->
            <div class="section-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-12" >
                        <div id="divCrearAhorroImg" class="custom-block-body">
                            <img src="images/slide/plantita.jpg"
                                 class="volunteer-image img-fluid" alt="">
                        </div>

                        <div class="custom-block-body text-center">
                            <h4 class="text-white mt-lg-3 mb-lg-3">Recuerda:</h4>

                            <p class="text-white">
                                Es importante establecer un objetivo claro: Define una meta específica para tu ahorro, como un viaje, un fondo de emergencia o una compra importante. Tener un objetivo tangible te motivará a ahorrar más.
                            </p>
                        </div>
                    </div>


                    <div class="col-lg-6 col-12 mx-auto">
                        <form class="custom-form volunteer-form mb-5 mb-lg-0" action="#" id="formRegistrarAhorro" method="post" role="form">
                            <h3 class="mb-4">Crear Ahorro</h3>

                            <div class="row">
                                <div class="col-lg-12 col-12">
                                    <label for="montoAhorro">Monto para ahorrar: </label>
                                    <input type="text" name="montoAhorro" id="montoAhorro" class="form-control" placeholder="$1,000" required data-error="Por favor ingresa un monto válido.">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-12">
                                <div class="col-lg-12 col-12 row" id="divBeneficiario1">
                                    <h5>Beneficiario 1 <i class="bi bi-plus-circle" id="btnAgregarBeneficiario" style="cursor: pointer;"></i></h5>
                                    <div class="col-lg-12 col-12">
                                        <label for="nombreBen1">Nombre: </label>
                                        <input type="text" name="nombreBen1" id="nombreBen1" class="form-control" placeholder="Juan Perez" required data-error="Por favor ingresa el nombre del beneficiario.">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <label for="porcentajeBen1">Porcentaje: </label>
                                        <input type="text" name="porcentajeBen1" id="porcentajeBen1" class="form-control" placeholder="80%" required data-error="Por favor ingresa un porcentaje válido.">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <label for="telefonoBen1">Teléfono: </label>
                                        <input type="text" name="telefonoBen1" id="telefonoBen1" class="form-control" placeholder="4424422556" required data-error="Por favor ingresa un teléfono válido.">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-lg-12 col-12">
                                        <label for="domicilioBen1">Domicilio: </label>
                                        <input type="text" name="domicilioBen1" id="domicilioBen1" class="form-control" placeholder="Av. de la Luz No.20" required data-error="Por favor ingresa el domicilio.">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-12 row" id="divBeneficiario2" style="display: none;">
                                    <h5>Beneficiario 2</h5>
                                    <div class="col-lg-12 col-12">
                                        <label for="nombreBen2">Nombre: </label>
                                        <input type="text" name="nombreBen2" id="nombreBen2" class="form-control" placeholder="María Hernández" required data-error="Por favor ingresa el nombre del beneficiario.">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <label for="porcentajeBen2">Porcentaje: </label>
                                        <input type="text" name="porcentajeBen2" id="porcentajeBen2" class="form-control" placeholder="20%" required data-error="Por favor ingresa un porcentaje válido.">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <label for="telefonoBen2">Teléfono: </label>
                                        <input type="text" name="telefonoBen2" id="telefonoBen2" class="form-control" placeholder="4424422556" required data-error="Por favor ingresa un teléfono válido.">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-lg-12 col-12">
                                        <label for="domicilioBen2">Domicilio: </label>
                                        <input type="text" name="domicilioBen2" id="domicilioBen2" class="form-control" placeholder="Av. de la Luz No.20" required data-error="Por favor ingresa el domicilio.">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <button type="button" class="form-control" onclick="validarFormAhorro(false)" id="btnSolAhorro">Solicitar</button>
                                <!--<button type="button" class="form-control" id="btnActAhorro">Actualizar</button>-->
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section class="prestamo-section" id="section_3"><!--retirarAhorroSeccion-->
            <div class="section-overlay"></div>
            <div class="container" id="divSection_3">
                <div class="row">
                    <div class="col-lg-6 col-12 mx-auto custom-form ">
                        <form class="custom-form volunteer-form mb-5 mb-lg-0" id="formRetirarAhorro" action="#" method="post" role="form">
                            <h3 class="mb-4">Retirar mis ahorros</h3>
                            <button type="button" class="form-control" onclick="autorizarAhorros()">Solicitar</button>
                        </form>
                    </div>
                    <div class="col-lg-6 col-12 divImg">
                        <img src="images/slide/pigVerde.png"
                             class="volunteer-image img-fluid" alt="">
                    </div>

                </div>
            </div>
        </section>
    </main>

    <section class="section-padding section-bg text-gray" id="section_4">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-12 text-center mx-auto">
                    <h2 class="mb-5">Preguntas frecuentes</h2>
                </div>
                <div class="col-lg-10 col-12 mx-auto">
                    <div class="custom-text-box">
                        <h5 class="mb-3 question"><span class="toggle-icon">+</span> ¿Cuál es el monto mínimo que puedo ahorrar?</h5>
                        <p class="mb-0 answer">Puedes ahorrar un mínimo de $100 pesos.</p>
                    </div>
                    <div class="custom-text-box">
                        <h5 class="mb-3 question"><span class="toggle-icon">+</span> ¿Cuál es el monto máximo que puedo ahorrar?</h5>
                        <p class="mb-0 answer">Si eres personal sindicalizado, puedes ahorrar hasta $1,000 pesos.</p>
                    </div>
                    <div class="custom-text-box">
                        <h5 class="mb-3 question"><span class="toggle-icon">+</span> ¿Qué días puedo retirar mi ahorro para que se refleje en la misma semana?</h5>
                        <p class="mb-0 answer">Debes solicitar tu ahorro el lunes o martes para que el depósito se realice el viernes de esa misma semana. Si lo solicitas a partir del miércoles, se procesará hasta la siguiente semana.</p>
                    </div>
                    <div class="custom-text-box">
                        <h5 class="mb-3 question"><span class="toggle-icon">+</span> Si tengo un préstamo, ¿puedo retirar mi ahorro?</h5>
                        <p class="mb-0 answer">Sí, puedes retirar tu ahorro siempre que el monto ahorrado sea mayor que la deuda restante. En este caso, se liquidará la deuda y se te entregará la diferencia.</p>
                    </div>
                    <div class="custom-text-box">
                        <h5 class="mb-3 question"><span class="toggle-icon">+</span> Si soy aval, ¿puedo retirar mi ahorro?</h5>
                        <p class="mb-0 answer">Puedes retirar tu ahorro siempre y cuando el préstamo de tu aval esté en la mitad de su plazo.</p>
                    </div>
                    <div class="custom-text-box">
                        <h5 class="mb-3 question"><span class="toggle-icon">+</span> Me fui de incapacidad, ¿por qué dejaron de descontar mi caja de ahorro?</h5>
                        <p class="mb-0 answer">Recuerda que si no tienes ingresos o tus ingresos son reducidos, es imposible realizar el descuento de tu ahorro. Es tu responsabilidad solicitar la reactivación del mismo. </p>
                    </div>
                    <div class="custom-text-box">
                        <h5 class="mb-3 question"><span class="toggle-icon">+</span> ¿Qué criterios se consideran para otorgar un préstamo?</h5>
                        <p class="mb-0 answer">Se evalúa el historial de los últimos seis meses, incluyendo faltas, amonestaciones, descuentos de Fonacot e Infonavit, así como pensiones y juicios mercantiles.</p>
                    </div>
                    <div class="custom-text-box">
                        <h5 class="mb-3 question"><span class="toggle-icon">+</span> ¿Qué requisitos debo cumplir para ser aval?</h5>
                        <p class="mb-0 answer">Debes estar ahorrando (cualquier cantidad) y tener un contrato de planta</p>
                    </div>
                    <div class="custom-text-box">
                        <h5 class="mb-3 question"><span class="toggle-icon">+</span> ¿A qué hora se refleja el depósito de mi caja de ahorro o préstamo?</h5>
                        <p class="mb-0 answer">Los depósitos se realizan el viernes después de las 2:00 p.m. directamente en la cuenta de HSBC del solicitante.</p>
                    </div>
                    <div class="custom-text-box">
                        <h5 class="mb-3 question"><span class="toggle-icon">+</span>Quiero liquidar mi préstamo, ¿qué debo hacer?</h5>
                        <p class="mb-0 answer">Acércate al departamento de nóminas para que te informen el monto a pagar y el número de cuenta para realizar el depósito. Solo necesitarás entregar el comprobante para registrarlo y eliminar el descuento.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.querySelectorAll('.question').forEach(question => {
            question.addEventListener('click', () => {
                const answer = question.nextElementSibling;
                const icon = question.querySelector('.toggle-icon');

                if (answer.style.display === 'none' || answer.style.display === '') {
                    answer.style.display = 'block';
                    icon.textContent = '-';
                } else {
                    answer.style.display = 'none';
                    icon.textContent = '+';
                }
            });
        });

    </script>


    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-12 mb-4">
                    <img src="images/icons/GrammerBlanco.png" class="logo img-fluid" alt="Logo Grammer">
                    <img src="images/icons/CROCblanco.png" class="logo img-fluid m-gl-3" alt="Logo CROC">
                </div>

                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    <h5 class="site-footer-title mb-3">Enlaces rápidos</h5>
                    <ul class="footer-menu">
                        <li class="footer-menu-item"><a href="#top" class="footer-menu-link" >Inicio</a></li>

                        <li class="footer-menu-item"><a href="#section_1" class="footer-menu-link" onclick="fCargarPrestamo()">Solicitar préstamo</a></li>

                        <li class="footer-menu-item"><a href="#section_2" class="footer-menu-link" onclick="fCrearAhorro()">Caja de Ahorro</a></li>

                        <li class="footer-menu-item"><a href="#section_3" class="footer-menu-link" onclick="fSolicitarRetiro()">Retiro de Ahorro</a></li>

                        <li class="footer-menu-item"><a href="#section_4" class="footer-menu-link" onclick="fCargarPreguntas()">Preguntas Frecuentes</a></li>

                    </ul>
                </div>

                <div class="col-lg-4 col-md-6 col-12 mx-auto">
                    <h5 class="site-footer-title mb-3">Información de contacto</h5>

                    <p class="text-white d-flex mb-2">
                        <i class="bi-telephone me-2"></i>
                            442 475 2898
                    </p>

                    <p class="text-white d-flex">
                        <i class="bi-envelope me-2"></i>
                            Lic. Irma Yomara Soto Cabello<br>
                            Coordinadora Nóminas<br>
                            yomara.soto@grammer.com<br>
                    </p>

                    <p class="text-white d-flex">
                        <i class="bi-envelope me-2"></i>
                            Lic. Juan Roberto Arreola Hernandez<br>
                            Administrador y analista de nómina<br>
                            juanroberto.arreola@grammer.com
                    </p>

                    <p class="text-white d-flex mt-3">
                        <i class="bi-geo-alt me-2"></i>
                        Grammer Automotive Puebla S.A. de C.V.<br>
                        Av. de la Luz 24, Benito Juárez, 76120<br>
                        Santiago de Querétaro, Qro.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        let existeBen2 = false;
        document.getElementById('btnAgregarBeneficiario').addEventListener('click', function() {
            var divBeneficiario2 = document.getElementById('divBeneficiario2');
            var icono = document.getElementById('btnAgregarBeneficiario');

            if (divBeneficiario2.style.display === 'none' || divBeneficiario2.style.display === '') {
                divBeneficiario2.style.display = 'block'; // Muestra el div
                icono.classList.remove('bi-plus-circle'); // Cambia a ícono de menos
                icono.classList.add('bi-dash-circle');
                existeBen2 = true;
            } else {
                divBeneficiario2.style.display = 'none'; // Oculta el div
                icono.classList.remove('bi-dash-circle'); // Cambia a ícono de más
                icono.classList.add('bi-plus-circle');
                existeBen2 = false;
            }
        });

        document.getElementById('cerrarSesion').addEventListener('click', function(e) {
            e.preventDefault(); // Evita el comportamiento predeterminado del enlace
            document.getElementById('logoutForm').submit(); // Envía el formulario
        });
        /*

        document.addEventListener("DOMContentLoaded", function() {
            const section = document.getElementById('section_1');
            const numberOfCircles = 20; // Número de círculos a generar

            for (let i = 0; i < numberOfCircles; i++) {
                const circle = document.createElement('div');
                circle.classList.add('circle_1');

                // Generar un tamaño aleatorio para el círculo
                const size = Math.random() * 100 + 20; // Entre 20px y 120px
                circle.style.width = `${size}px`;
                circle.style.height = `${size}px`;

                // Posición aleatoria dentro de la sección
                const top = Math.random() * (window.innerHeight - size); // Asegura que no se salga del viewport
                const left = Math.random() * (window.innerWidth - size); // Asegura que no se salga del viewport
                circle.style.top = `${top}px`;
                circle.style.left = `${left}px`;

                section.appendChild(circle);
            }

        });


        document.addEventListener("DOMContentLoaded", function() {
            const section = document.getElementById('section_2');
            const numberOfCircles = 20; // Número de círculos a generar

            for (let i = 0; i < numberOfCircles; i++) {
                const circle = document.createElement('div');
                circle.classList.add('circle_2');

                // Generar un tamaño aleatorio para el círculo
                const size = Math.random() * 100 + 20; // Entre 20px y 120px
                circle.style.width = `${size}px`;
                circle.style.height = `${size}px`;

                // Posición aleatoria dentro de la sección
                const top = Math.random() * (window.innerHeight - size); // Asegura que no se salga del viewport
                const left = Math.random() * (window.innerWidth - size); // Asegura que no se salga del viewport
                circle.style.top = `${top}px`;
                circle.style.left = `${left}px`;

                section.appendChild(circle);
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            const section = document.getElementById('section_3');
            const numberOfCircles = 20; // Número de círculos a generar

            for (let i = 0; i < numberOfCircles; i++) {
                const circle = document.createElement('div');
                circle.classList.add('circle_3');

                // Generar un tamaño aleatorio para el círculo
                const size = Math.random() * 100 + 20; // Entre 20px y 120px
                circle.style.width = `${size}px`;
                circle.style.height = `${size}px`;

                // Posición aleatoria dentro de la sección
                const top = Math.random() * (window.innerHeight - size); // Asegura que no se salga del viewport
                const left = Math.random() * (window.innerWidth - size); // Asegura que no se salga del viewport
                circle.style.top = `${top}px`;
                circle.style.left = `${left}px`;

                section.appendChild(circle);
            }
        });

        */

    </script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/click-scroll.js"></script>
    <script src="js/counter.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/ahorros.js"></script>
    <script src="js/general.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
<?php endif; ?>
</html>