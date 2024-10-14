// DataTables
let dataTable;
let dataTableIsInitialized = false;

const dataTableOptions = {
    lengthMenu: [10, 20, 50, 100],
    columnDefs:[
        {className: "centered", targets: [0,1,2,3,4,5,6]},
        {orderable: false, targets: [0,2,5]},
        {width: "8%", targets: [0]},
        {width: "28%", targets: [6]},
        {searchable: true, targets: [0,1,2,3,4,5,6] }
    ],
    pageLength:10,
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

    await TablaPruebasSolicitante();

    dataTable = $("#tablaSolicitudes").DataTable(dataTableOptions);

    dataTableIsInitialized = true;

    var filtroListadoPruebas = document.getElementById("tablaSolicitudes_filter");
    var contenedor = filtroListadoPruebas.parentNode;
    contenedor.style.padding = "0";

    var filtroListadoPruebas2 = document.getElementById("tablaSolicitudes_length");
    var contenedor2 = filtroListadoPruebas2.parentNode;
    contenedor2.style.padding = "0";
};

const TablaPruebasSolicitante = async () => {
    try {
        const response = await fetch(`https://grammermx.com/RH/CajitaGrammer/dao/daoMisSolicitudes.php`);

        if (!response.ok) {
            throw new Error(`Error en la solicitud: ${response.status} ${response.statusText}`);
        }

        const result = await response.json();

        let content = '';
        result.data.forEach((item) => {

            // Formatea las fechas de solicitud y compromiso
            let fechaSolicitudFormateada = formatearFecha(item.fechaSolicitud);

            content += `
                    <tr>
                        <td>${item.idSolicitud}</td>
                        <td>${item.nominaSolicitante}</td>
                        <td>${fechaSolicitudFormateada}</td>
                        <td>${item.montoSolicitado}</td>
                        <td>${item.estatusVisual}</td>
                        <td>
                            <button class="btn btn-success" onclick="mostrarRespuesta('${item.idSolicitud}')">
                                <i class="las la-eye"></i><span>Ver respuesta</span>
                            </button>`;

            // Si el estatus esta aprobado, mostrar opcion para agregar avales
            if (item.idEstatus === '3') {
                content += `
                            <button class="btn btn-secondary" onclick="agregarAvales('${item.idSolicitud}')">
                                <i class="las la-file-pdf"></i><span>Ver avales</span>
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


function mostrarRespuesta(idSolicitud){

}

function agregarAvales(idSolicitud){

}