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
    const montoAhorro = document.getElementById('montoAhorro').value;
    const nombreBen1 = document.getElementById('nombreBen1').value;
    const porcentajeBen1 = document.getElementById('porcentajeBen1').value;
    const telefonoBen1 = document.getElementById('telefonoBen1').value;
    const domicilioBen1 = document.getElementById('domicilioBen1').value;

    const nombreBen2 = document.getElementById('nombreBen2').value;
    const porcentajeBen2 = document.getElementById('porcentajeBen2').value;
    const telefonoBen2 = document.getElementById('telefonoBen2').value;
    const domicilioBen2 = document.getElementById('domicilioBen2').value;

    // Validación para asegurarse de que todos los campos del Beneficiario 1 estén llenos
    if (nombreBen1 === '' || porcentajeBen1 === '' || telefonoBen1 === '' || domicilioBen1 === '') {

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Todos los campos del Beneficiario 1 deben estar completos.'
        });
        return;
    }else{
        let valporcentajeBen1 = validarPorcentaje(porcentajeBen1);
        let valtelefonoBen1 = validarTelefono(telefonoBen1);

        if(valporcentajeBen1 && valtelefonoBen1){

            const formData = new FormData();
            formData.append('montoAhorro', montoAhorro);
            formData.append('nombreBen1', nombreBen1);
            formData.append('porcentajeBen1', porcentajeBen1);
            formData.append('telefonoBen1', telefonoBen1);
            formData.append('domicilioBen1', domicilioBen1);

            // Verificar si los campos del Beneficiario 2 no están vacíos antes de añadirlos
            if (nombreBen2 && porcentajeBen2 && telefonoBen2 && domicilioBen2) {
                let valporcentajeBen2 = validarPorcentaje(porcentajeBen2);
                let valtelefonoBen2 = validarTelefono(telefonoBen2);

                if(valporcentajeBen2 && valtelefonoBen2) {
                        console.log("%uno= "+porcentajeBen1 + " %dos= " +porcentajeBen1 +" suma= "+(porcentajeBen1 + porcentajeBen2));
                    if((porcentajeBen1 + porcentajeBen2) === 100){
                        formData.append('nombreBen2', nombreBen2);
                        formData.append('porcentajeBen2', porcentajeBen2);
                        formData.append('telefonoBen2', telefonoBen2);
                        formData.append('domicilioBen2', domicilioBen2);
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: "Ambos porcentajes deben sumar 100%"
                        });
                        return;
                    }

                }
            }

            // Enviar los datos utilizando fetch
            fetch('dao/daoGuardarAhorro.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
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
