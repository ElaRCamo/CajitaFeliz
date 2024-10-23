// DataTables
let dataTable;
let dataTableIsInitialized = false;

const dataTableOptions = {
    lengthMenu: [5, 10, 15, 20],
    columnDefs:[
        {className: "centered", targets: [0,1,2,3,4,5]},
        {orderable: false, targets: [0,2,4]},
        {width: "8%", targets: [0]},
        {width: "28%", targets: [5]},
        {searchable: true, targets: [0,1,2,3,4,5] }
    ],
    pageLength:5,
    destroy: true,
    order: [[0, 'desc']], // Ordenar por la columna 0
    language:{
        lengthMenu: "Mostrar _MENU_ registros pór página",
        sZeroRecords: "Ninguna solicitud encontrada",
        info: "Mostrando de _START_ a _END_ de un total de _TOTAL_ registros",
        infoEmpty: "Ninguna solicitud encontrada",
        infoFiltered: "(filtrados desde _MAX_ registros totales)",
        search: "Buscar: ",
        loadingRecords: "Cargando...",
        paginate:{
            first:"Primero",
            last: "Último",
            next: "Siguiente",
            previous: "Anterior"
        }
    }
};

const initDataTable = async () => {
    if (dataTableIsInitialized) {
        dataTable.destroy();
    }

    await TablaSolicitudesPrestamos();

    dataTable = $("#tablaSolicitudes").DataTable(dataTableOptions);

    dataTableIsInitialized = true;


};

const TablaSolicitudesPrestamos = async () => {
    try {
        const response = await fetch(`https://grammermx.com/RH/CajitaGrammer/dao/daoMisSolicitudes.php`);

        if (!response.ok) {
            throw new Error(`Error en la solicitud: ${response.status} ${response.statusText}`);
        }

        const result = await response.json();

        let content = '';
        result.data.forEach((item) => {
            const fechaSolicitudFormateada = formatearFecha(item.fechaSolicitud);
            const montoSolFormateado = formatearMonto(item.montoSolicitado);

            content += `
                <tr>
                    <td>${item.idSolicitud}</td>
                    <td>${item.nominaSolicitante}</td>
                    <td>${fechaSolicitudFormateada}</td>
                    <td>${montoSolFormateado}</td>
                    <td>${item.estatusVisual}</td>
                    <td>
                        <button class="btn btn-success" onclick="mostrarRespuestaPrestamo('${item.idSolicitud}')" data-bs-toggle="modal" data-bs-target="#modalRespPresSol">
                            <i class="las la-eye"></i><span>Ver respuesta</span>
                        </button>`;

            // Agrega el botón de avales si el estatus es 3
            if (item.idEstatus === '3') {
                content += `
                    <button class="btn btn-secondary" onclick="consultarAvales('${item.idSolicitud}')" data-bs-toggle="modal" data-bs-target="#modalAgregarAvales">
                        <i class="las la-file-pdf"></i><span>Avales</span>
                    </button>`;
            }

            content += `
                    </td>
                </tr>`;
        });
        misSolicitudesBody.innerHTML = content;
    } catch (error) {
        console.error('Error:', error);
    }
};

let dataTableCaja;
let dataTableCajaInit = false;


const dataTableOptionsCaja = {
    lengthMenu: [5, 10, 15, 20],
    columnDefs:[
        {className: "centered", targets: [0,1,2,3]},
        {orderable: false, targets: [2]},
        {width: "8%", targets: [0]},
        {width: "28%", targets: [3]},
        {searchable: true, targets: [2,3] }
    ],
    pageLength:5,
    destroy: true,
    order: [[0, 'desc']], // Ordenar por la columna 0
    language:{
        lengthMenu: "Mostrar _MENU_ registros pór página",
        sZeroRecords: "Ningún registro encontrado",
        info: "Mostrando de _START_ a _END_ de un total de _TOTAL_ registros",
        infoEmpty: "Ningún registro encontrado",
        infoFiltered: "(filtrados desde _MAX_ registros totales)",
        search: "Buscar: ",
        loadingRecords: "Cargando...",
        paginate:{
            first:"Primero",
            last: "Último",
            next: "Siguiente",
            previous: "Anterior"
        }
    }
};

const initDataTableCaja = async () => {
    if (dataTableCajaInit) {
        dataTableCaja.destroy();
    }

    await TablaCajaAhorro();

    dataTableCaja = $("#tablaCajaAhorro").DataTable(dataTableOptionsCaja);

    dataTableCajaInit = true;
};

const TablaCajaAhorro= async () => {
    try {
        const response = await fetch(`https://grammermx.com/RH/CajitaGrammer/dao/daoMiCajaDeAhorro.php`);

        // Verifica si la respuesta es exitosa
        if (!response.ok) {
            throw new Error(`Error en la solicitud: ${response.status} ${response.statusText}`);
        }

        const result = await response.json();

        let content = '';
        result.data.forEach((item) => {
            const fechaSolicitudFormateada = formatearFecha(item.fechaSolicitud);
            const montoSolFormateado = formatearMonto(item.montoAhorro);

            content += `
                <tr>
                    <td>${item.idCaja}</td>
                    <td>${item.nomina}</td>
                    <td>${fechaSolicitudFormateada}</td>
                    <td>${montoSolFormateado}</td>
                </tr>`;
        });

        cajaAhorroBody.innerHTML = content; // Asegúrate de que misSolicitudesBody esté definido
    } catch (error) {
        console.error('Error:', error);
    }
};


let dataTableRetiro;
let dataTableRetiroInit = false;


const dataTableOptionsRetiro = {
    lengthMenu: [5, 10, 15, 20],
    columnDefs:[
        {className: "centered", targets: [0,1,2,3,4,5]},
        {orderable: false, targets: [0,1,3]},
        {width: "8%", targets: [0]},
        {width: "28%", targets: [5]},
        {searchable: true, targets: [2,3] }
    ],
    pageLength:5,
    destroy: true,
    order: [[0, 'desc']], // Ordenar por la columna 0
    language:{
        lengthMenu: "Mostrar _MENU_ registros pór página",
        sZeroRecords: "Ningún registro encontrado",
        info: "Mostrando de _START_ a _END_ de un total de _TOTAL_ registros",
        infoEmpty: "Ningún registro encontrado",
        infoFiltered: "(filtrados desde _MAX_ registros totales)",
        search: "Buscar: ",
        loadingRecords: "Cargando...",
        paginate:{
            first:"Primero",
            last: "Último",
            next: "Siguiente",
            previous: "Anterior"
        }
    }
};

const initDataTableRetiro = async () => {
    if (dataTableRetiroInit) {
        dataTableRetiro.destroy();
    }

    await TablaRetiroAhorro();

    dataTableRetiro = $("#tablaRetiros").DataTable(dataTableOptionsRetiro);

    dataTableRetiroInit = true;
};

const TablaRetiroAhorro = async () => {
    try {
        const response = await fetch(`https://grammermx.com/RH/CajitaGrammer/dao/daoMisRetirosAhorro.php`);

        if (!response.ok) {
            throw new Error(`Error en la solicitud: ${response.status} ${response.statusText}`);
        }

        const result = await response.json();

        let content = '';
        result.data.forEach((item) => {
            const fechaSolicitudFormateada = formatearFecha(item.fechaSolicitud);

            content += `
                <tr>
                    <td>${item.idRetiro}</td>
                    <td>${item.idCaja}</td>
                    <td>${item.nomina}</td>
                    <td>${fechaSolicitudFormateada}</td>
                    <td>`;

            if (item.estatusRetiro === '0') {
                content += `
                        <label class="badge bg-warning text-dark">En proceso</label>`;
            } else if (item.estatusRetiro === '1') {
                content += `
                        <label class="badge bg-success">Concluido</label>`;
            }

            content += `
                    </td>
                </tr>`;

            // Agrega el botón consultarRetiro si el estatus es 1
            if (item.estatusRetiro === '1') {
                content += `
                    <button class="btn btn-secondary" onclick="consultarRetiro('${item.idRetiro}')">
                        <i class="las la-file-pdf"></i><span>Ver detalles</span>
                    </button>`;
            }

            content += `
                    </td>
                </tr>`;
        });

        retirosBody.innerHTML = content; // Asegúrate de que retirosBody esté definido
    } catch (error) {
        console.error('Error:', error);
    }
};

function consultarRetiro(idRetiro){

}

function mostrarRespuestaPrestamo(idSolicitud){
    const titulo = "Solicitud de Préstamo Folio " + idSolicitud;
    actualizarTitulo('#respModalTitSol', titulo);
    let data = "";
    $.getJSON('https://grammermx.com/RH/CajitaGrammer/dao/daoSolicitudPrestamoPorId.php?id_solicitud='+idSolicitud, function (response) {

        data = response.data[0];

        let fechaSolicitudFormateada = formatearFecha(data.fechaSolicitud);
        let montoForSol = formatearMonto(data.montoSolicitado);
        let montoForAut = formatearMonto(data.montoAprobado);

        $("#folioSolicitudMS").text(data.idSolicitud);

        $("#fechaSolicitudMS").text(fechaSolicitudFormateada);

        $("#montoSolicitadoMS").text(montoForSol);

        $("#nominaSolMS").text(data.nominaSolicitante);

        $('#telefonoSolMS').text(data.telefono);

        $("#comentariosMS").text(data.comentariosAdmin);

        $("#montoAprobadoMS").text(montoForAut);

        /*alert(
            "Folio Solicitud: " + $('#folioSolicitud').val() + "\n" +
            "Fecha Solicitud: " + $('#fechaSolicitud').val() + "\n" +
            "Monto Solicitado: " + $('#montoSolicitado').val() + "\n" +
            "Nómina: " + $('#nominaSol').val() + "\n" +
            "Teléfono: " +data.telefono + "\n" +
            "Comentarios Admin: " + $('#textareaComentarios').val() + "\n" +
            "Monto Aprobado: " + montoForAut + "\n" + data.montoAprobado
        );*/
    }).then(function(){
        fCargarSolicitanteMS(data.nominaSolicitante);
    }).then(function(){
        fCargarEstatusMS(data.idEstatus);
    }).then(function(){
        deshabilitarInputsMS();
    });
}

function fCargarSolicitanteMS(nomina){

    $.getJSON('https://grammermx.com/RH/CajitaGrammer/dao/daoConsultarSolicitante.php?sol='+nomina, function (response) {
        $('#nombreSolMS').text(response.data[0].NomUser);
    });
}

function fCargarEstatusMS(idSeleccionado){
    $.getJSON('https://grammermx.com/RH/CajitaGrammer/dao/daoEstatusSol.php', function (data){
        let selectS = document.getElementById("estatusMS");
        selectS.innerHTML = ""; //limpiar contenido

        for (var j = 0; j < data.data.length; j++) {
            var createOption = document.createElement("option");
            if (data.data[j].idEstatus === idSeleccionado) {
                createOption.value = data.data[j].idEstatus;
                createOption.text = data.data[j].descripcion;
                selectS.appendChild(createOption);
                createOption.selected = true;
            }
        }
    });
}

function deshabilitarInputsMS() {
    document.getElementById('folioSolicitudMS').disabled = true;
    document.getElementById('fechaSolicitudMS').disabled = true;
    document.getElementById('montoSolicitadoMS').disabled = true;
    document.getElementById('nominaSolMS').disabled = true;
    document.getElementById('nombreSolMS').disabled = true;
    document.getElementById('telefonoSolMS').disabled = true;
    document.getElementById('montoAprobadoMS').disabled = true;
    document.getElementById('estatusMS').disabled = true;
    document.getElementById('comentariosMS').disabled = true;
}

function consultarAvales(idSolicitud){
    const folioSpan = document.getElementById("folioSolPres");
    if (folioSpan) {
        folioSpan.textContent = idSolicitud;
    } else {
        console.error("Elemento con ID 'folioSolPres' no encontrado.");
    }

    let data = "";
    let aval1 ="";
    let aval2 = "";
    $.getJSON('https://grammermx.com/RH/CajitaGrammer/dao/daoSolicitudPrestamoPorId.php?id_solicitud='+idSolicitud, function (response) {

        data = response.data[0];
        //si data no esta vacio:
        aval1 = data.nominaAval1;
        aval2 = data.nominaAval2;

    }).then(function(){
        fCargarAvales(aval1,aval2,idSolicitud);
    });
}

function fCargarAvales(aval1, aval2, idSolicitud) {

    if(aval1 === '00000000' && aval2 === '00000000'){
        return;
    }

    const titulo = "Avales para la Solicitud " + idSolicitud;
    actualizarTitulo('#modalTitAvales', titulo);

    let formData = new FormData();

    formData.append('nom1', aval1);
    formData.append('nom2', aval2);

    // Enviar los datos al servidor
    fetch('https://grammermx.com/RH/CajitaGrammer/dao/daoNombresAvales.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data.data)) {
                let nomina1 = document.getElementById("nominaAval1");
                let nomina2 = document.getElementById("nominaAval2");

                let nombre1 = document.getElementById("nombreAval1");
                let nombre2 = document.getElementById("nombreAval2");

                let valNomina1 = data.data[0]?.IdUser || '';
                let valNomina2 = data.data[1]?.IdUser || '';

                let valNombre1 = data.data[0]?.NomUser || '';
                let valNombre2 = data.data[1]?.NomUser || '';

                nomina1.value = valNomina1;
                nomina2.value = valNomina2;

                nombre1.value = valNombre1;
                nombre2.value = valNombre2;

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
                text: 'Ocurrió un error al consultar la información. Intente nuevamente.'
            });
        });
}

function guardarAvales(){
    let solicitud = document.getElementById("folioSolPres").textContent;
    let nomina1 = document.getElementById("nominaAval1").value;
    let nomina2 = document.getElementById("nominaAval2").value;

    alert("solicitud: "+solicitud)

    let formData = new FormData();

    formData.append('idSolicitud', solicitud.trim());
    formData.append('nom1', nomina1.trim());
    formData.append('nom2', nomina2.trim());

    fetch('https://grammermx.com/RH/CajitaGrammer/dao/daoActualizarAvales.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                Swal.fire({
                    icon: 'success',
                    title: 'Actualización exitosa',
                    text: data.message
                });

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al consultar la información. Intente nuevamente.'
            });
        });

}
