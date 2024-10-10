function autorizarSolicitud(){
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

                const formData = new FormData(document.getElementById('formSolicitarPrestamo'));

                formData.append('password', password);

                // Enviar los datos al servidor
                fetch('dao/daoValidarTAG.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('formSolicitarPrestamo').submit();

                            const telefono = formData.get('telefono');
                            const montoPrestamo = formData.get('montoPrestamo');

                            // Mostrar los valores en la consola (opcional)
                            console.log('Teléfono:', telefono);
                            console.log('Monto solicitado:', montoPrestamo);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message // Mostrar el mensaje de error devuelto por el servidor
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al validar la contraseña. Intente nuevamente.'
                        });
                    });
            }
        }
    });
}

function registrarUsuario() {
    var inputsValidos = validarFormulario() && validarCorreo('correo','aviso') && validarPasswords('password','password2','aviso');

    if (inputsValidos) {
        var nombreUsuario = id("nombreUsuario");
        var correo        = id("correo");
        var numNomina     = id("numNomina");
        var password      = id("password");

        const data = new FormData();

        data.append('nombreUsuario', nombreUsuario.value.trim());
        data.append('correo', correo.value.trim());
        data.append('numNomina', numNomina.value.trim());
        data.append('password', password.value.trim());

        //alert('nombreUsuario: '+nombreUsuario.value.trim()+' correo: '+correo.value.trim()+' numNomina: '+numNomina.value.trim()+' password: '+ password.value.trim());

        fetch('../../dao/userRegister.php', {
            method: 'POST',
            body: data
        })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(error => {
                        throw new Error(error.message);
                    });
                    // throw new Error('Hubo un problema al registrar el usuario. Por favor, intenta de nuevo más tarde.');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    //console.log(data.message);
                    Swal.fire({
                        title: "¡Usuario registrado exitosamente!",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            enviarCorreoNuevoUsuario(nombreUsuario.value.trim(), numNomina.value.trim(), correo.value.trim());
                            window.location.href = "../sesion/indexSesion.php";
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
            //console.error(error);
            Swal.fire({
                title: "Error",
                text: error.message,
                icon: "error",
                confirmButtonText: "OK"
            });
        });
    }else{
        Swal.fire({
            title: "Datos incorrectos",
            text: "Revise su información",
            icon: "error"

        });
    }
}