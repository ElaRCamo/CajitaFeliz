// DataTables
let dataTableAdminPrestamos;
let dataTableInitPrestamosAdmin = false;

const dataTableOptPresAdmin = {
    lengthMenu: [5, 10, 15, 20],
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

const initDataTablePresAdmin = async () => {
    alert("initDataTablePresAdmin: "+ initDataTablePresAdmin);
    if (dataTableInitPrestamosAdmin) {
        dataTableAdminPrestamos.destroy();
    }
    alert("initDataTablePresAdmin: "+ initDataTablePresAdmin);
    await dataTablePrestamosAdmin();

    dataTableAdminPrestamos = $("#tablaPrestamosAdmin").DataTable(dataTableOptPresAdmin);

    dataTableInitPrestamosAdmin = true;


};

const dataTablePrestamosAdmin = async () => {
    try {
        const response = await fetch(`https://grammermx.com/RH/CajitaGrammer/dao/daoSolicitudesPrestamos.php`);

        if (!response.ok) {
            throw new Error(`Error en la solicitud: ${response.status} ${response.statusText}`);
        }

        const result = await response.json();
        alert("response: "+ response);
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
                        <button class="btn btn-success" onclick="responderPrestamo('${item.idSolicitud}')">
                            <i class="las la-eye"></i><span>Responder</span>
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

function responderPrestamo(){

}


function exportTableToExcel(tableID, filename = '') {
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

    // Especifica el nombre del archivo
    filename = filename ? filename + '.xls' : 'excel_data.xls';

    // Crea el enlace de descarga
    downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);

    if (navigator.msSaveOrOpenBlob) {
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob(blob, filename);
    } else {
        // Crea un enlace temporal para la descarga
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

        // Configura el nombre del archivo
        downloadLink.download = filename;

        // Dispara la descarga
        downloadLink.click();
    }
}

function cargarAnio() {
    $.getJSON('https://grammermx.com/Metrologia/MetroTickets/dao/daoConsultaAdmin.php?idTipoConsulta=' + id("selectTipoConsulta").value, function (data) {
        let selectS = id("selectAnio");
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