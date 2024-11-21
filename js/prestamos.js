function validarUser(user) {
    Swal.fire({
        title: 'Autorización requerida',
        input: 'password',
        inputLabel: 'Ingresa tu TAG',
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
                formData.append('user', user);  // Pasar el `user` a la solicitud también

                // Enviar los datos al servidor mediante fetch
                fetch('dao/daoValidarTAG.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())  // Se espera una respuesta en formato JSON
                    .then(data => {
                        if (data.success) {
                            // Acción después de una validación exitosa
                            window.location.href = 'index.php'; // Redirige o actualiza la página
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'TAG incorrecto.'
                            });
                            validarUser(user);
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un problema con la conexión.'
                        });
                        validarUser(user);
                    });
            }
        }
    });
}

function validarTelefono(telefono) {
    // Expresión regular para validar un número de teléfono de 10 dígitos
    const regex = /^\d{10}$/; // Formato: 5551234567

    if (regex.test(telefono)) {
        return true; // Teléfono válido
    } else {
        return false; // Teléfono inválido
    }
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





