function autorizarSolicitudAhorro(){
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

                            registrarAhorro();

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

function validarPorcentaje(valor) {
    valor = valor.trim();

    // Si el valor termina con '%', lo eliminamos
    if (valor.endsWith('%')) {
        valor = valor.slice(0, -1);
    }

    // Convertir el valor a número
    const porcentaje = parseFloat(valor);

    // Verificar que el valor sea un número válido y esté entre 0 y 100
    if (isNaN(porcentaje) || porcentaje < 0 || porcentaje > 100) {
        return false; // No es un porcentaje válido
    }

    return true; // Es un porcentaje válido
}


function registrarAhorro() {
    const montoAhorro = document.getElementById('montoAhorro');
    const nombreBen1 = document.getElementById('nombreBen1');
    const porcentajeBen1 = document.getElementById('porcentajeBen1');
    const telefonoBen1 = document.getElementById('telefonoBen1');
    const domicilioBen1 = document.getElementById('domicilioBen1');

    //Validar inputs
    const montoValido = validarInput('montoAhorro');
    const nombreBen1Valido = validarInput('nombreBen1');
    const porcentajeBen1Valido = validarInput('porcentajeBen1');
    const telefonoBen1Valido = validarInput('telefonoBen1');
    const domicilioBen1Valido = validarInput('domicilioBen1');

    let nombres = [];
    let porcentajes = [];
    let telefonos = [];
    let domicilios = [];

    // Validación para asegurarse de que todos los campos del Beneficiario 1 estén llenos
    if (montoValido && nombreBen1Valido && porcentajeBen1Valido && telefonoBen1Valido && domicilioBen1Valido) {
        let valporcentajeBen1 = validarPorcentaje(porcentajeBen1.value);
        let valtelefonoBen1 = validarTelefono(telefonoBen1.value);

        if(valporcentajeBen1 && valtelefonoBen1){
            if (!existeBen2){porcentajeBen1.value = 100}
            nombres.push(nombreBen1.value.trim());
            porcentajes.push(porcentajeBen1.value.trim());
            telefonos.push(telefonoBen1.value.trim());
            domicilios.push(domicilioBen1.value.trim());

            // Verificar si los campos del Beneficiario 2 no están vacíos antes de añadirlos
            if (existeBen2) {

                const nombreBen2 = document.getElementById('nombreBen2');
                const porcentajeBen2 = document.getElementById('porcentajeBen2');
                const telefonoBen2 = document.getElementById('telefonoBen2');
                const domicilioBen2 = document.getElementById('domicilioBen2');

                const nombreBen2Valido = validarInput('nombreBen2');
                const porcentajeBen2Valido = validarInput('porcentajeBen2');
                const telefonoBen2Valido = validarInput('telefonoBen2');
                const domicilioBen2Valido = validarInput('domicilioBen2');

                if (nombreBen2Valido && porcentajeBen2Valido && telefonoBen2Valido && domicilioBen2Valido) {

                    let valporcentajeBen2 = validarPorcentaje(porcentajeBen2.value);
                    let valtelefonoBen2 = validarTelefono(telefonoBen2.value);

                    if (valporcentajeBen2.value && valtelefonoBen2.value) {
                        console.log("%uno= " + porcentajeBen1.value + " %dos= " + porcentajeBen1.value + " suma= " + (porcentajeBen1.value + porcentajeBen2.value));
                        if ((porcentajeBen1.value + porcentajeBen2.value) === 100) {

                            nombres.push(nombreBen2.value.trim());
                            porcentajes.push(porcentajeBen2.value.trim());
                            telefonos.push(telefonoBen2.value.trim());
                            domicilios.push(domicilioBen2.value.trim());

                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: "Ambos porcentajes deben sumar 100%"
                            });
                            return;
                        }
                    }
                }
            }

            const formData = new FormData();
            formData.append('montoAhorro', montoAhorro.value.trim());
            formData.append('nombres', nombres.join(', '));
            formData.append('porcentajes', porcentajes.join(', '));
            formData.append('telefonos', telefonos.join(', '));
            formData.append('domicilios', domicilios.join(', '));

            // Enviar los datos utilizando fetch
            fetch('dao/daoGuardarAhorro.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success"){
                        Swal.fire({
                            title: "¡Solicitud realizada exitosamente!",
                            icon: "success",
                            text: data.message,
                            confirmButtonText: "OK"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "https://grammermx.com/RH/CajitaGrammer/misSolicitudes.php";
                            }
                        });
                    } else {
                        alert('Ocurrió un error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al procesar la solicitud.');
                });

        }else{
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Datos incorrectos, revise su información.'
            });
        }
    }
}

function validarInput(idInput) {
    const inputElement = document.getElementById(idInput);
    if (inputElement) {
        const inputValue = inputElement.value.trim();
        const feedbackElement = inputElement.parentElement.querySelector('.invalid-feedback');

        if (!inputValue) {
            inputElement.classList.add('is-invalid');
            inputElement.parentElement.classList.add('has-error');
            if (feedbackElement) {
                feedbackElement.textContent = inputElement.getAttribute('data-error');
                feedbackElement.style.display = 'block';
            }
            return false;
        } else {
            inputElement.classList.remove('is-invalid');
            inputElement.parentElement.classList.remove('has-error');
            if (feedbackElement) {
                feedbackElement.style.display = 'none';
            }
            return true;
        }
    } else {
        console.log(`Elemento con id ${idInput} no encontrado.`);
        return false;
    }
}

