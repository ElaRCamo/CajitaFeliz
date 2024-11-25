/*const allowedDate = new Date('2024-11-26'); // Fecha permitida
const today = new Date();
const submitButton = document.getElementById('submitButton');
const message = document.getElementById('message');

if (today >= allowedDate) {
    submitButton.disabled = false;
    message.textContent = "Puede solicitar el préstamo.";
} else {
    message.textContent = `No puede solicitar un préstamo hasta el ${allowedDate.toLocaleDateString()}.`;
}*/

function validarUser(user) {
    Swal.fire({
        title: 'Autorización requerida',
        text: 'Para acceder a la Caja de Ahorro, es necesario confirmar tu identidad mediante el TAG, lo que permitirá procesar tus solicitudes.',
        input: 'password',
        inputLabel: 'TAG',
        inputPlaceholder: 'TAG',
        inputAttributes: {
            'aria-label': 'Contraseña'
        },
        showCancelButton: true,
        confirmButtonText: 'Autorizar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const password = result.value;

            if (password === "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe ingresar un TAG válido.'
                });
            } else {
                // Crea un objeto FormData para enviar los datos al servidor
                const formData = new FormData();
                formData.append('password', password);
                formData.append('user', user);

                // Enviar los datos al servidor mediante fetch
                fetch('dao/daoCompararTAG.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = 'index.php';
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'TAG incorrecto.'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Llamar a la función validarUser(user) cuando se confirme el Swal
                                    validarUser(user);
                                }
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un problema con la conexión.'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                validarUser(user);
                            }
                        });
                    });
            }
        }
    });
}


function validarMonto(montoInput) {
    const montoSinSimbolo = montoInput.replace(/[$\s]/g, '');
    const monto = parseFloat(montoSinSimbolo);

    if (isNaN(monto) || monto <= 0) {
        return null; // Devuelve null si el monto es inválido o no es positivo
    } else {
        return monto; // Devuelve el monto positivo
    }
}



function registrarPrestamo() {
    const telefono = document.getElementById("telefono").value;
    const montoSolicitado = document.getElementById('montoPrestamo').value;


    if (validarTelefono(telefono)) {

        let montoValidado = validarMonto(montoSolicitado);

        if(montoValidado !== null) {

            const data = new FormData();

            data.append('telefono', telefono.trim());
            data.append('montoSolicitado', montoValidado);

            fetch('dao/daoSolicitudPrestamo.php', {
                method: 'POST',
                body: data
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(error => {
                            throw new Error(error.message);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            title: data.message,
                            icon: "success",
                            text: "¡Solicitud realizada exitosamente!",
                            confirmButtonText: "OK"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "https://grammermx.com/RH/CajitaGrammer/misSolicitudes.php";
                            }
                        });
                    }else if (data.status === 'error') {
                        console.log(data.message);
                        Swal.fire({
                            title: "Error",
                            text: data.message,
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                }).catch(error => {
                Swal.fire({
                    title: "Error",
                    text: error.message,
                    icon: "error",
                    confirmButtonText: "OK"
                });
            });


        }else {
            Swal.fire({
                title: "Datos incorrectos",
                text: "Por favor, ingresa un monto válido.",
                icon: "error"
            });
        }

    } else {
        Swal.fire({
            title: "Datos incorrectos",
            text: "Número de teléfono inválido. Debe ingresar 10 dígitos.",
            icon: "error"
        });
    }
}





