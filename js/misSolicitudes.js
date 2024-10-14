// DataTables
let dataTable; // Asegúrate de definir dataTable antes de usarlo
let dataTableIsInitialized = false; // Inicializa la variable para verificar el estado de la tabla
const dataTableOptions = {
    // Aquí puedes definir las opciones para DataTables según lo que necesites
    // Ejemplo:
    paging: true,
    searching: true,
    ordering: true,
    // ...otras opciones
};

const initDataTable = async () => {
    // Verifica si la tabla ya está inicializada y destrúyela si es necesario
    if (dataTableIsInitialized) {
        dataTable.destroy();
    }

    // Llama a la función que carga los datos en la tabla
    await TablaPruebasSolicitante();

    // Inicializa DataTables con las opciones definidas
    dataTable = $("#tablaSolicitudes").DataTable(dataTableOptions);

    dataTableIsInitialized = true;

    // Ajusta el padding de los filtros
    const filtroListadoPruebas = document.getElementById("tablaSolicitudes_filter");
    if (filtroListadoPruebas) {
        const contenedor = filtroListadoPruebas.parentNode;
        contenedor.style.padding = "0";
    }

    const filtroListadoPruebas2 = document.getElementById("tablaSolicitudes_length");
    if (filtroListadoPruebas2) {
        const contenedor2 = filtroListadoPruebas2.parentNode;
        contenedor2.style.padding = "0";
    }
};

const TablaPruebasSolicitante = async () => {
    try {
        const response = await fetch(`https://grammermx.com/RH/CajitaGrammer/dao/daoMisSolicitudes.php`);

        // Verifica si la respuesta es exitosa
        if (!response.ok) {
            throw new Error(`Error en la solicitud: ${response.status} ${response.statusText}`);
        }

        const result = await response.json();
        console.log(result); // Verifica la respuesta del servidor

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
                        <button class="btn btn-success" onclick="mostrarRespuesta('${item.idSolicitud}')">
                            <i class="las la-eye"></i><span>Ver respuesta</span>
                        </button>`;

            // Agrega el botón de avales si el estatus es 3
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

        console.log(content); // Asegúrate de que las filas se generen correctamente
        misSolicitudesBody.innerHTML = content; // Asegúrate de que misSolicitudesBody esté definido
    } catch (error) {
        console.error('Error:', error);
    }
};



const formatearFecha = (fecha) => {
    if (fecha !== '0000-00-00'){
        let date = new Date(fecha);
        let meses = ["ene", "feb", "mar", "abr", "may", "jun", "jul", "ago", "sep", "oct", "nov", "dic"];
        let dia = date.getDate();
        let mes = meses[date.getMonth()];
        let anio = date.getFullYear();
        return `${dia}/${mes}/${anio}`;
    }else{
        //return '0000-00-00';
        return 'No asignada';
    }
};

function formatearMonto(numero) {
    if (isNaN(numero)) {
        throw new Error("El valor proporcionado no es un número válido");
    }

    // Convertir el número a formato de pesos mexicanos
    return `$${numero.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}`;
    //return `$${numero.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}



function mostrarRespuesta(idSolicitud){

}

function agregarAvales(idSolicitud){

}