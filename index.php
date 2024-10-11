<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/icons/Grammer_Logo.ico" type="image/x-icon">

    <meta name="description" content="">
    <meta name="author" content="">

    <title>Cajita Feliz</title>

    <!-- CSS FILES -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/bootstrap-icons.css" rel="stylesheet">

    <link href="css/templatemo-kind-heart-charity.css" rel="stylesheet">

    <?php
        session_start();
        $nombreUser = $_SESSION['nombreUsuario'];
        $esAdmin = $_SESSION['admin'];

    if ($nombreUser == null){
        header("Location: https://grammermx.com/RH/CajitaGrammer/login.php");
    }

    ?>

</head>

<body id="section_1">

    <nav class="navbar navbar-expand-lg bg-light shadow-lg">
        <div class="container" id="top">
            <a class="navbar-brand" href="index.php">
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
                        <a class="nav-link click-scroll">Manual</a>
                    </li>

                    <?php if($esAdmin == 0 ){ ?>
                    <li class="nav-item ms-3">
                        <a class="nav-link custom-btn custom-border-btn btn" onclick="">Mis Solicitudes</a>
                    </li>
                    <?php }?>

                    <?php if($esAdmin == 1 ){ ?>
                    <li class="nav-item ms-3">
                        <a class="nav-link custom-btn custom-border-btn btn" href="administrar.php">Administrar</a>
                    </li>
                    <?php }?>

                    <li class="nav-item ms-3">
                        <a class="nav-link" id="cerrarSesion">Salir</a>
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
                                    <img src="images/slide/pigDorado.png"
                                        class="carousel-image img-fluid" alt="exito">
                                    <div class="carousel-caption d-flex flex-column justify-content-end">
                                        <h1>Impulsa <br>tus Metas</h1>
                                        <p>Cada centavo ahorrado <br>es un paso más hacia tus objetivos.</p>
                                    </div>
                                </div>

                                <div class="carousel-item">
                                    <img src="images/slide/pigCarrito.jpg"
                                        class="carousel-image img-fluid" alt="PersonaFeliz">
                                    <div class="carousel-caption d-flex flex-column justify-content-end">
                                        <h1>Fácil y Posible</h1>

                                        <p>Prestamos fáciles, sueños posibles: ¡comencemos!</p>
                                    </div>
                                </div>

                                <div class="carousel-item">
                                    <img src="images/slide/pigTarget.jpg"
                                        class="carousel-image img-fluid" alt="Celebrando">
                                    <div class="carousel-caption d-flex flex-column justify-content-end">
                                        <h1>Hazlo Realidad</h1>

                                        <p>Cada peso cuenta: ahorra y celebra cada logro.</p>
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
                            <a href="#section_1" class="d-block">
                                <img src="images/icons/prestamo.png" class="imgIcon featured-block-image img-fluid" alt="">

                                <p class="featured-block-text">Solicitar <strong>préstamo</strong></p>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0 mb-md-4">
                        <div class="featured-block d-flex justify-content-center align-items-center">
                            <a href="#section_2" class="d-block">
                                <img src="images/icons/ahorro.png" class="imgIcon featured-block-image img-fluid" alt="">

                                <p class="featured-block-text"><strong>Crear </strong>ahorro  </p>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0 mb-md-4">
                        <div class="featured-block d-flex justify-content-center align-items-center">
                            <a href="#section_3" class="d-block">
                                <img src="images/icons/salario.png" class="imgIcon featured-block-image img-fluid" alt="">

                                <p class="featured-block-text"> <strong>Retirar</strong> ahorro</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0">
                        <div class="featured-block d-flex justify-content-center align-items-center">
                            <a href="#section_2" class="d-block">
                                <img src="images/icons/contrato.png" class="imgIcon featured-block-image img-fluid" alt="">

                                <p class="featured-block-text"><strong>Modificar</strong> ahorro</p>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section class="donate-section" id="section_1"><!--solicitarPrestamoSeccion-->
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
                                    <input type="text" name="montoPrestamo" id="montoPrestamo" class="form-control"
                                           placeholder="$1,000.00" required>
                                </div>
                            </div>

                            <!--<div class="col-lg-12 col-12">
                                <h4>Avales</h4>

                                <div class="col-lg-12 col-12 row">
                                    <h5>Aval 1
                                        <i class="bi bi-exclamation-triangle" id="tooltipAvales1" data-bs-toggle="tooltip" data-bs-placement="top" title="Recuerda que tu aval debe estar activo en caja"></i>
                                    </h5>
                                    <div class="col-lg-3 col-12">
                                        <label for="nominaAval1">Nómina: </label>
                                        <input type="text" name="nominaAval1" id="nominaAval1" class="form-control" placeholder="00012345" required>
                                    </div>
                                    <div class="col-lg-9 col-12">
                                        <label for="nombreAval1">Nombre: </label>
                                        <input type="text" name="nombreAval1" id="nombreAval1" class="form-control" placeholder="Juan Perez" required>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-12 row">
                                    <h5>Aval 2
                                        <i class="bi bi-exclamation-triangle" id="tooltipAvales1" data-bs-toggle="tooltip" data-bs-placement="top" title="Recuerda que tu aval debe estar activo en caja"></i>
                                    </h5>
                                    <div class="col-lg-3 col-12">
                                        <label for="nominaAval2">Nómina: </label>
                                        <input type="text" name="nominaAval2" id="nominaAval2" class="form-control" placeholder="00023456" required>
                                    </div>
                                    <div class="col-lg-9 col-12">
                                        <label for="nombreAval2">Nombre: </label>
                                        <input type="text" name="nombreAval2" id="nombreAval2" class="form-control" placeholder="María Hernández" required>
                                    </div>
                                </div>
                            </div>-->
                            <button type="button" class="form-control" onclick="autorizarSolicitud()">Solicitar</button>
                        </form>
                    </div>
                    <div class="col-lg-6 col-12">
                        <img src="images/slide/pigAhorro.jpg"
                             class="volunteer-image img-fluid" alt="">

                        <div class="custom-block-body text-center">
                            <h4 class="text-white mt-lg-3 mb-lg-3">Recuerda:</h4>

                            <p class="text-white">
                                El monto final del préstamo autorizado dependerá de varios factores, entre ellos el límite establecido:
                            </p>
                            <ul class="text-white list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center"><strong>Sindicalizados:</strong>

                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">Menos de 3 meses de antigüedad: $5,000</li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">Menos de 6 meses de antigüedad: $10,000</li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center"> Hasta $30,000 </li>
                                    </ul>

                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center"><strong>Administrativos:</strong> Hasta 3 meses de salario</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </section>


        <section class="ahorro-section" id="section_2"><!--crearAhorroSeccion-->
            <div class="section-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <img src="images/slide/pigCajita.png"
                             class="volunteer-image img-fluid" alt="">

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
                                    <input type="text" name="montoAhorro" id="montoAhorro" class="form-control"
                                           placeholder="$1,000" required>
                                </div>
                            </div>

                            <div class="col-lg-12 col-12">
                                <div class="col-lg-12 col-12 row">
                                    <h5>Beneficiario 1 <i class="bi bi-plus-circle" id="btnAgregarBeneficiario" style="cursor: pointer;"></i></h5>
                                    <div class="col-lg-12 col-12">
                                        <label for="nombreBen1">Nombre: </label>
                                        <input type="text" name="nombreBen1" id="nombreBen1" class="form-control" placeholder="Juan Perez" required>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <label for="porcentajeBen1">Porcentaje: </label>
                                        <input type="text" name="porcentajeBen1" id="porcentajeBen1" class="form-control" placeholder="80%" required>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <label for="telefonoBen1">Teléfono: </label>
                                        <input type="text" name="telefonoBen1" id="telefonoBen1" class="form-control" placeholder="555 4422556" required>
                                    </div>
                                    <div class="col-lg-12 col-12">
                                        <label for="domicilioBen1">Domicilio: </label>
                                        <input type="text" name="domicilioBen1" id="domicilioBen1" class="form-control" placeholder="Av. de la Luz No.20" required>
                                    </div>

                                </div>
                                <div class="col-lg-12 col-12 row" id="divBeneficiario2" style="display: none;">
                                    <h5>Beneficiario 2</h5>
                                    <div class="col-lg-12 col-12">
                                        <label for="nombreBen2">Nombre: </label>
                                        <input type="text" name="nombreBen2" id="nombreBen2" class="form-control" placeholder="María Hernández" required>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <label for="porcentajeBen2">Porcentaje: </label>
                                        <input type="text" name="porcentajeBen2" id="porcentajeBen2" class="form-control" placeholder="20%" required>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <label for="telefonoBen2">Teléfono: </label>
                                        <input type="text" name="telefonoBen2" id="telefonoBen2" class="form-control" placeholder="555 4422556" required>
                                    </div>
                                    <div class="col-lg-12 col-12">
                                        <label for="domicilioBen2">Domicilio: </label>
                                        <input type="text" name="domicilioBen2" id="domicilioBen2" class="form-control" placeholder="Av. de la Luz No.20" required>
                                    </div>
                                </div>
                                <button type="button" class="form-control" onclick="autorizarSolicitudAhorro()">Solicitar</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section class="donate-section" id="section_3"><!--retirarAhorroSeccion-->
            <div class="section-overlay"></div>
            <div class="container">
                <div class="row">

                    <div class="col-lg-6 col-12 mx-auto">
                        <form class="custom-form volunteer-form mb-5 mb-lg-0" action="#" method="post" role="form">
                            <h3 class="mb-4">Retirar de mis ahorros</h3>

                            <div class="row">
                                <div class="col-lg-6 col-12">
                                    <label for="montoRetiro">Monto solicitado: </label>
                                    <input type="number" name="montoRetiro" id="montoRetiro" class="form-control"
                                           placeholder="$1,000" required>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <label for="tagRetiro">TAG: </label>
                                    <input type="number" name="tagRetiro" id="tagRetiro" class="form-control"
                                           placeholder="0012345678" required>
                                </div>
                            </div>
                            <button type="submit" class="form-control">Solicitar</button>
                        </form>
                    </div>
                    <div class="col-lg-6 col-12">
                        <img src="images/slide/pigVerde.png"
                             class="volunteer-image img-fluid" alt="">
                    </div>

                </div>
            </div>
        </section>
    </main>

    <section class="section-padding section-bg" id="section_4"><!--section_preguntas-->
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-12 text-center mx-auto">
                    <h2 class="mb-5">Preguntas frecuentes</h2>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="custom-text-box">

                        <h5 class="mb-3 ">¿Cuál es el monto mínimo que puedo ahorrar? </h5>

                        <p class="mb-0">Puedes ahorrar un mínimo de $100 pesos.</p>
                    </div>
                    <div class="custom-text-box">

                        <h5 class="mb-3">¿Cuál es el monto máximo que puedo ahorrar?  </h5>

                        <p class="mb-0"> Si eres personal sindicalizado, puedes ahorrar hasta $1,000 pesos.<br/></p>
                    </div>
                </div>

                <div class="col-lg-6 col-12">
                    <div class="custom-text-box">

                        <h5 class="mb-3">¿Qué días puedo retirar mi ahorro para que se refleje en la misma semana?  </h5>

                        <p class="mb-0"> Debes solicitar tu ahorro el lunes o martes para que el depósito se realice el viernes de esa misma semana. Si lo solicitas a partir del miércoles, se procesará hasta la siguiente semana.<br/></p>
                    </div>

                    <div class="custom-text-box">

                        <h5 class="mb-3">Si tengo un préstamo, ¿puedo retirar mi ahorro? </h5>

                        <p class="mb-0">Sí, puedes retirar tu ahorro siempre que el monto ahorrado sea mayor que la deuda restante. En este caso, se liquidará la deuda y se te entregará la diferencia.</p>
                    </div>
                </div>

                <div class="col-lg-6 col-12 mb-5 mb-lg-0">
                    <div class="custom-text-box">

                        <h5 class="mb-3">Si soy aval, ¿puedo retirar mi ahorro?</h5>

                        <p class="mb-0"> Puedes retirar tu ahorro siempre y cuando el préstamo de tu aval esté en la mitad de su plazo.</p>
                    </div>
                    <div class="custom-text-box">

                        <h5 class="mb-3">Me fui de incapacidad, ¿por qué dejaron de descontar mi caja de ahorro?</h5>

                        <p class="mb-0">Recuerda que si no tienes ingresos o tus ingresos son reducidos, es imposible realizar el descuento de tu ahorro. Es tu responsabilidad solicitar la reactivación del mismo.</p>
                    </div>
                </div>

                <div class="col-lg-6 col-12">
                    <div class="custom-text-box">

                        <h5 class="mb-3">¿Qué criterios se consideran para otorgar un préstamo?</h5>

                        <p class="mb-0"> Se evalúa el historial de los últimos seis meses, incluyendo faltas, amonestaciones, descuentos de Fonacot e Infonavit, así como pensiones y juicios mercantiles.<br/></p>
                    </div>

                    <div class="custom-text-box">

                        <h5 class="mb-3">¿Qué requisitos debo cumplir para ser aval?</h5>

                        <p class="mb-0">Debes estar ahorrando (cualquier cantidad) y tener un contrato de planta.</p>
                    </div>
                </div>

                <div class="col-lg-6 col-12">
                    <div class="custom-text-box ">

                        <h5 class="mb-3">¿A qué hora se refleja el depósito de mi caja de ahorro o préstamo?</h5>

                        <p class="mb-0"> Los depósitos se realizan el viernes después de las 2:00 p.m. directamente en la cuenta de HSBC de cada trabajador solicitante.<br/></p>
                    </div>

                    <div class="custom-text-box ">

                        <h5 class="mb-3">Quiero liquidar mi préstamo, ¿qué debo hacer?</h5>

                        <p class="mb-0"> Acércate al departamento de nóminas para que te informen el monto a pagar y el número de cuenta para realizar el depósito. Solo necesitarás entregar el comprobante para registrarlo y eliminar el descuento.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-12 mb-4">
                    <img src="images/logo.png" class="logo img-fluid" alt="">
                </div>

                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    <h5 class="site-footer-title mb-3">Enlaces rápidos</h5>

                    <ul class="footer-menu">
                        <li class="footer-menu-item"><a href="#top" class="footer-menu-link">Inicio</a></li>

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

    <script>
        document.getElementById('btnAgregarBeneficiario').addEventListener('click', function() {
            var divBeneficiario2 = document.getElementById('divBeneficiario2');
            var icono = document.getElementById('btnAgregarBeneficiario');

            if (divBeneficiario2.style.display === 'none' || divBeneficiario2.style.display === '') {
                divBeneficiario2.style.display = 'block'; // Muestra el div
                icono.classList.remove('bi-plus-circle'); // Cambia a ícono de menos
                icono.classList.add('bi-dash-circle');
            } else {
                divBeneficiario2.style.display = 'none'; // Oculta el div
                icono.classList.remove('bi-dash-circle'); // Cambia a ícono de más
                icono.classList.add('bi-plus-circle');
            }
        });
    </script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/click-scroll.js"></script>
    <script src="js/counter.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/prestamos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>