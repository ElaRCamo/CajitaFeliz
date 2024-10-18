// DataTables
let dataTableAdminPrestamos;
let dataTableInitPrestamosAdmin = false;

const dataTableOptPresAdmin = {
    lengthMenu: [5, 10, 15, 20],
    columnDefs:[
        {className: "centered", targets: [0,1,2,3,4]},
        {orderable: false, targets: [0,1,2]},
        {width: "8%", targets: [0]},
        {searchable: true, targets: [0,1,2,3] }
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

const initDataTablePresAdmin = async (anio) => {
    if (dataTableInitPrestamosAdmin) {
        dataTableAdminPrestamos.destroy();
    }

    await dataTablePrestamosAdmin(anio);

    dataTableAdminPrestamos = $("#tablaPrestamosAdmin").DataTable(dataTableOptPresAdmin);

    dataTableInitPrestamosAdmin = true;
};

const dataTablePrestamosAdmin = async (anio) => {
    try {
        const response = await fetch(`https://grammermx.com/RH/CajitaGrammer/dao/daoSolicitudesPrestamos.php?anio=` + anio);

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
                    <td>${fechaSolicitudFormateada}</td>
                    <td>${item.nominaSolicitante}</td>
                    <td>${montoSolFormateado}</td>
                    <td>${item.telefono}</td>
                    <td>
                        <button class="btn btn-success" onclick="responderPrestamo('${item.idSolicitud}')" data-bs-toggle="modal" data-bs-target="#modalRespPrestamo">
                            <span>Responder</span>
                        </button>`;
            content += `
                    </td>
                </tr>`;
        });
        bodyPrestamosAdmin.innerHTML = content;
    } catch (error) {
        console.error('Error:', error);
    }
};

function cargarAnio() {
    $.getJSON('https://grammermx.com/RH/CajitaGrammer/dao/daoAnio.php', function (data) {
        let selectS = document.getElementById("selectAnio");
        selectS.innerHTML = ""; //limpiar contenido

        let createOptionDef = document.createElement("option");
        createOptionDef.text = "Seleccione el año*";
        createOptionDef.value = "";
        selectS.appendChild(createOptionDef);

        for (var i = 0; i < data.data.length; i++) {
            var createOption = document.createElement("option");
            createOption.value = data.data[i].anio;
            createOption.text = data.data[i].anio;
            selectS.appendChild(createOption);
        }
    });
}

async function cargarSolicitudes() {
    const seccionPrestamo = document.getElementById("solicitarPrestamoSeccion");
    const seccionAhorro = document.getElementById("solicitarAhorroSeccion");
    let tipoConsulta = document.getElementById("selectTipoConsulta").value;
    let anio = document.getElementById("selectAnio").value;

    seccionPrestamo.style.display = "none";
    seccionAhorro.style.display = "none";

    if (tipoConsulta === "1") { // Préstamos
        seccionPrestamo.style.display = "block";
        await initDataTablePresAdmin(anio);

    } else if (tipoConsulta === "2") { // Caja de Ahorro
        seccionAhorro.style.display = "block";
        await initDataTableAhorroAdmin(anio);
    }
}

// DataTables
let dataTableAdminAhorro;
let dataTableInitAhorroAdmin = false;

const dataTableOptAhorroAdmin = {
    lengthMenu: [5, 10, 15, 20],
    columnDefs:[
        {className: "centered", targets: [0,1,2,3]},
        {orderable: false, targets: [0,1,2]},
        {width: "8%", targets: [0]},
        {searchable: true, targets: [0,1,2] }
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
const initDataTableAhorroAdmin = async (anio) => {
    if (dataTableInitAhorroAdmin) {
        dataTableAdminAhorro.destroy();
    }
    await dataTableAhorroAdmin(anio);

    dataTableAdminAhorro = $("#tablaAhorroAdmin").DataTable(dataTableOptAhorroAdmin);

    dataTableInitAhorroAdmin = true;
};


const dataTableAhorroAdmin = async (anio) => {
    try {
        const response = await fetch(`https://grammermx.com/RH/CajitaGrammer/dao/daoSolicitudesAhorro.php?anio=` + anio);

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
                    <td>${fechaSolicitudFormateada}</td>
                    <td>${item.nomina}</td>
                    <td>${montoSolFormateado}</td>
                </tr>`;
        });
        bodyAhorroAdmin.innerHTML = content;
    } catch (error) {
        console.error('Error:', error);
    }
};


// DataTables
let dataTableAdminRetiro;
let dataTableInitRetiroAdmin = false;

const dataTableOptRetiroAdmin = {
    lengthMenu: [5, 10, 15, 20],
    columnDefs:[
        {className: "centered", targets: [0,1,2,3]},
        {orderable: false, targets: [0,1,2,3,4]},
        {width: "8%", targets: [0]},
        {searchable: true, targets: [0,1,2,3,4] }
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
const initDataTableRetiroAdmin = async (anio) => {
    if (dataTableInitRetiroAdmin) {
        dataTableAdminRetiro.destroy();
    }
    await dataTableRetiroAdmin(anio);

    dataTableAdminRetiro = $("#tablaRetirosAdmin").DataTable(dataTableOptRetiroAdmin);

    dataTableInitRetiroAdmin = true;
};


const dataTableRetiroAdmin = async (anio) => {
    try {
        const response = await fetch(`https://grammermx.com/RH/CajitaGrammer/dao/daoSolicitudesRetiro.php?anio=` + anio);

        if (!response.ok) {
            throw new Error(`Error en la solicitud: ${response.status} ${response.statusText}`);
        }

        const result = await response.json();
        let content = '';
        result.data.forEach((item) => {
            const fechaSolicitudFormateada = formatearFecha(item.fechaSolicitud);
            const fechaDepositoFormateada = formatearFecha(item.fechaDeposito);
            const montoDepFormateado = formatearMonto(item.montoDepositado);

            content += `
                <tr>
                    <td>${item.idRetiro}</td>
                    <td>${fechaSolicitudFormateada}</td>
                    <td>${item.idCaja}</td>
                    <td>${item.nomina}</td>
                    <td>${fechaDepositoFormateada}</td>
                    <td>${montoDepFormateado}</t>
                </tr>`;
        });
        bodyRetirosAdmin.innerHTML = content;
    } catch (error) {
        console.error('Error:', error);
    }
};


function responderPrestamo(idSolicitud){
    const titulo = "Responder Solicitud de Préstamo Folio " + idSolicitud;
    actualizarTitulo('#respModalTit', titulo);
    let data = "";

    $.getJSON('https://grammermx.com/RH/CajitaGrammer/dao/daoSolicitudPrestamoPorId.php?id_solicitud='+idSolicitud, function (response) {
        //codigo para actualizar campos
        data = response.data[0];

        let fechaSolicitudFormateada = formatearFecha(data.fechaSolicitud);
        let montoFormateado = formatearMonto(data.montoSolicitado);

        $('#telefonoSol').text(data.telefono);

        $('#folioSolicitud').val(data.idSolicitud);

        $('#fechaSolicitud').val(fechaSolicitudFormateada);

        $('#montoSolicitado').val(montoFormateado);

        $('#nominaSol').val(data.nominaSolicitante);
        //$('#nombreSol').val(data.nombreSolic);
        $('#telefonoSol').val(data.telefono);
    }).then(function(){
        fCargarSolicitante(data.nominaSolicitante);
    }).then(function(){
        fCargarEstatus(data.idEstatus);
    });
}
function fCargarSolicitante(nomina){

    $.getJSON('https://grammermx.com/RH/CajitaGrammer/dao/daoConsultarSolicitante.php?sol='+nomina, function (response) {
        $('#nombreSol').val(response.data[0].NomUser);
    });
}

function fCargarEstatus(idEstatus){
    $.getJSON('https://grammermx.com/RH/CajitaGrammer/dao/daoEstatusSol.php', function (data){
        var selectS = id("solEstatus");
        selectS.innerHTML = ""; //limpiar contenido

        for (var j = 0; j < data.data.length; j++) {
            var createOption = document.createElement("option");
            createOption.value = data.data[j].id_estatusPrueba;
            createOption.text = data.data[j].descripcionEstatus;
            selectS.appendChild(createOption);
            // Si el valor actual coincide con id_estatusSol, se selecciona por defecto
            if (data.data[j].id_estatusPrueba === idEstatus) {
                createOption.selected = true;
            }
        }
    });
}

function exportTableToExcel() {

}
