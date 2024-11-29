// DataTables
let dataTable;
let dataTableIsInitialized = false;

const dataTableOptions = {
    lengthMenu: [5, 15, 50, 100],
    columnDefs:[
        {className: "centered", targets: [0,1,2,3,4]},
        {orderable: false, targets: [0,1,2]},
        {width: "8%", targets: [0]},
        {width: "28%", targets: [4]},
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
                    <td>${fechaSolicitudFormateada}</td>
                    <td>${montoSolFormateado}</td>
                    <td>${item.estatusVisual}</td>
                    <td>
                        <button class="btn btn-primary" onclick="mostrarRespuestaPrestamo('${item.idSolicitud}')" data-bs-toggle="modal" data-bs-target="#modalRespPresSol">
                            <span>Detalles</span>
                        </button>`;

            // Agrega el botón de avales si el estatus es 3
            if (item.idEstatus === '3') {
                content += `
                    <button class="btn btn-secondary btnAvales" onclick="consultarAvales('${item.idSolicitud}')" data-bs-toggle="modal" data-bs-target="#modalAgregarAvales">
                        </i><span>Avales</span>
                    </button>`;
            }else if(item.idEstatus === '1'){
                content += `
                    <button class="btn btn-warning" onclick="editarPrestamo('${item.idSolicitud}')"  data-bs-toggle="modal" data-bs-target="#editarPrestamoModal">
                        </i><span>Editar</span>
                    </button>`;
            }

            content += `
                    </td>
                </tr>`;
        });
        misSolicitudesBody.innerHTML = content;

        $('#tablaSolicitudes thead th').css('background-color', '#005195')
        $('#tablaSolicitudes thead th').css('color', '#ffffff')
        $('#tablaSolicitudes').find('tbody td, thead th').css('text-align', 'center');

    } catch (error) {
        console.error('Error:', error);
    }
};

function editarPrestamo(idSolicitud) {
    // Actualiza el título del modal
    let titulo = "Editar solicitud de Préstamo Folio " + idSolicitud;
    actualizarTitulo("#editarPrestamoModalLabel", titulo);

    // Inicializa variables
    let telefono = "";
    let monto = "";

    // Obtén los datos del servidor
    $.getJSON('https://grammermx.com/RH/CajitaGrammer/dao/daoSolicitudPrestamoPorId.php?id_solicitud=' + idSolicitud, function (response) {
        if (response && response.data && response.data.length > 0) {
            let data = response.data[0];

            // Formatea los datos recibidos
            monto = formatearMonto(data.montoSolicitado);
            telefono = data.telefono;

            alert("telefono: "+telefono+" montoSolicitado: "+monto);

            // Establece los valores en los campos del formulario
            $("#telefonoE").val(telefono);
            $("#montoSolicitadoE").val(monto);
        } else {
            console.error("No se encontraron datos para la solicitud: " + idSolicitud);
        }
    }).fail(function () {
        console.error("Error al obtener los datos de la solicitud.");
    });
}

let dataTableCaja;
let dataTableCajaInit = false;

const dataTableOptionsCaja = {
    lengthMenu: [5, 15, 50, 100],
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

        /*<td>
                        <button class="btn btn-primary" onclick="consultarAhorro('${item.idCaja}')" data-bs-toggle="modal" data-bs-target="#modalConsultaAhorro">
                            </i><span>Detalles</span>
                        </button>
                    </td>*/

        cajaAhorroBody.innerHTML = content; // Asegúrate de que misSolicitudesBody esté definido

        $('#tablaCajaAhorro thead th').css('background-color', '#005195')
        $('#tablaCajaAhorro thead th').css('color', '#ffffff')
        $('#tablaCajaAhorro').find('tbody td, thead th').css('text-align', 'center');
    } catch (error) {
        console.error('Error:', error);
    }
};

function consultarAhorro(idCaja){
    const titulo = "Mi Caja de Ahorro Folio " + idCaja;
    actualizarTitulo('#titModalMiAhorro', titulo);
    let data = "";
    $.getJSON('https://grammermx.com/RH/CajitaGrammer/dao/daoRetiroPorId.php?ret='+idCaja, function (response) {

        data = response.data[0];

        let fechaSolicitudFormateada = formatearFecha(data.fechaSol);
        let fechaDepFormateada = formatearFecha(data.fechaDep);
        let montoDep = formatearMonto(data.montoDep);

        $("#folioCaja").text(data.folioRetiro);

        $("#montoAhorro").text(montoDep);

        $("#fechaAhorro").text(data.folioCaja);

        $("#nominaSolAho").text(fechaSolicitudFormateada);

        $("#nombreAho").text(fechaDepFormateada);

        $('#beneficiarioUno').text(data.usuario);

        $("#beneficiarioDos").text(data.estatusVisual);

    }).then(function(){
        fCargarSolicitanteMS(data.usuario, '#nombreSolRetiro');
    });
}


let dataTableRetiro;
let dataTableRetiroInit = false;

const dataTableOptionsRetiro = {
    lengthMenu: [5, 15, 50, 100],
    columnDefs:[
        {className: "centered", targets: [0,1,2,3]},
        {width: "8%", targets: [0]},
        {orderable: false, targets: [0,1,2]},
        {searchable: true, targets: [0,1] }
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

    // Asegúrate de que los datos estén cargados primero
    await TablaRetiroAhorro();

    // Verifica que haya filas en la tabla antes de inicializar DataTables
    if ($("#tablaRetiros tbody tr").length > 0) {
        dataTableRetiro = $("#tablaRetiros").DataTable(dataTableOptionsRetiro);
        dataTableRetiroInit = true;
    } else {
        console.warn("No se encontraron datos para inicializar DataTables en tablaRetiros.");
    }
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
                    <td>${fechaSolicitudFormateada}</td>
                    <td>`;

            if (item.estatusRetiro === '0') {
                content += `<label class="badge bg-warning text-dark bgEstatusRet">En proceso</label>`;
            } else if (item.estatusRetiro === '1') {
                content += `<label class="badge bg-primary bgEstatusRet">Completado</label>`;
            }

            content += `</td>
                    <td>`; // Nueva celda para acciones

            // Agrega el botón consultarRetiro si el estatus es 1
            if (item.estatusRetiro === '1') {
                content += `
                    <button class="btn btn-primary" onclick="consultarRetiro('${item.idRetiro}')" data-bs-toggle="modal" data-bs-target="#modalConsultaRetiro">
                        </i><span>Detalles</span>
                    </button>`;
            }

            content += `</td></tr>`;
        });

        retirosBody.innerHTML = content;

        $('#tablaRetiros thead th').css('background-color', '#005195')
        $('#tablaRetiros thead th').css('color', '#ffffff')
        $('#tablaRetiros').find('tbody td, thead th').css('text-align', 'center');
    } catch (error) {
        console.error('Error:', error);
    }
};

function consultarRetiro(idRetiro){
    const titulo = "Solicitud de Retiro de Caja de Ahorro " + idRetiro;
    actualizarTitulo('#titModalRetiro', titulo);
    let data = "";
    $.getJSON('https://grammermx.com/RH/CajitaGrammer/dao/daoRetiroPorId.php?ret='+idRetiro, function (response) {

        data = response.data[0];

        let fechaSolicitudFormateada = formatearFecha(data.fechaSol);
        let fechaDepFormateada = formatearFecha(data.fechaDep);
        let montoDep = formatearMonto(data.montoDep);

        $("#folioRetiroSol").text(data.folioRetiro);

        $("#folioRetiroCaja").text(data.folioCaja);

        $("#fechaSolRetiro").text(fechaSolicitudFormateada);

        $("#fechaDepRetiro").text(fechaDepFormateada);

        $("#montoRetiroSol").text(montoDep);

        $('#nominaSolRetiro').text(data.usuario);

        $("#estatusRetiroSol").text(data.estatusVisual);

    }).then(function(){
        fCargarSolicitanteMS(data.usuario, '#nombreSolRetiro');
    });

}

function mostrarRespuestaPrestamo(idSolicitud){
    const titulo = "Solicitud de Préstamo Folio " + idSolicitud;
    actualizarTitulo('#respModalTitSol', titulo);
    let data = "";
    $.getJSON('https://grammermx.com/RH/CajitaGrammer/dao/daoSolicitudPrestamoPorId.php?id_solicitud='+idSolicitud, function (response) {

        data = response.data[0];

        let fechaSolicitudFormateada = formatearFecha(data.fechaSolicitud);
        let fechaDepFormateada = formatearFecha(data.fechaDeposito);
        let montoForSol = formatearMonto(data.montoSolicitado);
        let montoForAut = formatearMonto(data.montoAprobado);
        let montoForDep = formatearMonto(data.montoDepositado);

        $("#folioSolicitudMS").text(data.idSolicitud);

        $("#fechaSolicitudMS").text(fechaSolicitudFormateada);

        $("#montoSolicitadoMS").text(montoForSol);

        $("#nominaSolMS").text(data.nominaSolicitante);

        $('#telefonoSolMS').text(data.telefono);

        $("#comentariosMS").text(data.comentariosAdmin);

        $("#montoAprobadoMS").text(montoForAut);

        $("#montoDepP").text(montoForDep);

        $("#FechaDepP").text(fechaDepFormateada);

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
        fCargarSolicitanteMS(data.nominaSolicitante, '#nombreSolMS');
    }).then(function(){
        fCargarEstatusMS(data.idEstatus);
    }).then(function(){
        deshabilitarInputsMS();
    });
}

function fCargarSolicitanteMS(nomina, elemento){

    $.getJSON('https://grammermx.com/RH/CajitaGrammer/dao/daoConsultarSolicitante.php?sol='+nomina, function (response) {
        $(elemento).text(response.data[0].NomUser);
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

    let data, aval1, aval2, tel1, tel2 = "";
    $.getJSON('https://grammermx.com/RH/CajitaGrammer/dao/daoSolicitudPrestamoPorId.php?id_solicitud='+idSolicitud, function (response) {

        data = response.data[0];
        //si data no esta vacio:
        aval1 = data.nominaAval1;
        aval2 = data.nominaAval2;
        tel1 = data.telAval1;
        tel2 = data.telAval2;

    }).then(function(){
        fCargarAvales(aval1,tel1,aval2,tel2,idSolicitud);
    });
}

function fCargarAvales(aval1,tel1,aval2,tel2,idSolicitud) {

    if(aval1 === '00000000' && aval2 === '00000000'){
        return;
    }

    const titulo = "Avales para la Solicitud " + idSolicitud;
    actualizarTitulo('#modalTitAvales', titulo);

    let telAval1 = document.getElementById("telAval1");
    let telAval2 = document.getElementById("telAval2");
    telAval1.value = tel1;
    telAval2.value = tel2;

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
    let tel1 = document.getElementById("telAval1").value;
    let tel2 = document.getElementById("telAval2").value;

    if(validarTelefono(tel1) && validarTelefono(tel2)){
        //alert("solicitud: "+solicitud)

        let formData = new FormData();

        formData.append('idSolicitud', solicitud.trim());
        formData.append('nom1', nomina1.trim());
        formData.append('nom2', nomina2.trim());
        formData.append('tel1', tel1.trim());
        formData.append('tel2', tel2.trim());

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
    }else{
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Número de teléfono no válido, verifique e intente nuevamente.'
        });
    }
}
function generarNomina(nomina) {

    if (nomina.length === 1){return nomina = "0000000"+nomina;}
    if (nomina.length === 2){return nomina = "000000"+nomina;}
    if (nomina.length === 3){return nomina = "00000"+nomina;}
    if (nomina.length === 4){return nomina = "0000"+nomina;}
    if (nomina.length === 5){return nomina = "000"+nomina;}
    if (nomina.length === 6){return nomina = "00"+nomina;}
    if (nomina.length === 7){return nomina = "0"+nomina;}
    if (nomina.length === 8){return nomina = nomina;}
}

function consultarNombreAval(nomina, campoNombre, idNominaInput) {
    const nominaGenerada = generarNomina(nomina);
    document.getElementById(idNominaInput).value = nominaGenerada;
    $.getJSON('https://grammermx.com/RH/CajitaGrammer/dao/daoConsultarSolicitante.php?sol=' + nominaGenerada, function(data) {
        if (data.data.length > 0) {
            $(campoNombre).val(data.data[0].NomUser);
        } else {
            $(campoNombre).val('Ingresa un número de nómina válido.');
        }
    }).fail(function() {
        console.log('Error al consultar el nombre del aval.');
    });
}

