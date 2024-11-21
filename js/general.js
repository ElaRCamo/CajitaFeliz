const formatearFecha = (fecha) => {
    if (fecha !== '0000-00-00') {
        // Crear la fecha en UTC
        let date = new Date(Date.UTC(...fecha.split('-').map((v, i) => i === 1 ? v - 1 : v)));
        let meses = ["ene", "feb", "mar", "abr", "may", "jun", "jul", "ago", "sep", "oct", "nov", "dic"];
        let dia = date.getUTCDate(); // Usamos getUTCDate para evitar desfases de zona horaria
        let mes = meses[date.getUTCMonth()]; // Usamos getUTCMonth
        let anio = date.getUTCFullYear(); // Usamos getUTCFullYear
        return `${dia}/${mes}/${anio}`;
    } else {
        return 'Sin registro';
    }
};

// Función para convertir la fecha de Excel a formato 'YYYY-MM-DD'
function excelDateToJSDate(excelDate) {
    // Helper para formatear fechas en YYYY/MM/DD
    function formatDateToYMD(date) {
        return `${date.getUTCFullYear()}/${(date.getUTCMonth() + 1).toString().padStart(2, '0')}/${date.getUTCDate().toString().padStart(2, '0')}`;
    }

    // Verificar si es una fecha en formato numérico de Excel
    if (typeof excelDate === 'number') {
        const jsDate = new Date((excelDate - 25569) * 86400 * 1000);
        return formatDateToYMD(jsDate);
    }

    // Verificar si es una cadena
    else if (typeof excelDate === 'string') {
        // Intentar varios formatos conocidos
        const formats = [/^\d{2}\/\d{2}\/\d{4}$/, /^\d{4}-\d{2}-\d{2}$/, /^\d{2}-\d{2}-\d{4}$/];

        for (let format of formats) {
            if (format.test(excelDate)) {
                // Si el formato es DD/MM/YYYY
                if (format === formats[0]) {
                    const [day, month, year] = excelDate.split('/');
                    const parsedDate = new Date(Date.UTC(year, month - 1, day));
                    return formatDateToYMD(parsedDate);
                }
                // Si el formato es YYYY-MM-DD o DD-MM-YYYY
                else if (format === formats[1] || format === formats[2]) {
                    const parts = excelDate.split(/[-\/]/);
                    const year = parts[0].length === 4 ? parts[0] : parts[2];
                    const month = parts[1] - 1;
                    const day = parts[0].length === 4 ? parts[2] : parts[0];
                    const parsedDate = new Date(Date.UTC(year, month, day));
                    return formatDateToYMD(parsedDate);
                }
            }
        }
        return "Error: Formato de fecha no válido";
    }
    return "Error: Tipo de entrada no válido";
}


function formatearMonto(numero) {
    if (isNaN(numero)) {
        throw new Error("El valor proporcionado no es un número válido");
    }

    // Convertir el número a formato de pesos mexicanos
    return `$${numero.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}`;
    //return `$${numero.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}

function actualizarTitulo(idTitulo, titulo) {
    let titulo5 = document.querySelector(idTitulo);
    if (titulo5) {
        titulo5.textContent = titulo;
    }
}


function fCargarPrestamo() {
    let sectionSolPrestamo = document.getElementById("section_1");

    if (sectionSolPrestamo.style.display === "block") {
        sectionSolPrestamo.style.display = "none"; // Oculta la sección si está visible
    } else {
        sectionSolPrestamo.style.display = "block"; // Muestra la sección si está oculta

        fExistePrestamo();
    }
}

function fExistePrestamo(){
    let data = "";

    $.getJSON('https://grammermx.com/RH/CajitaGrammer/dao/daoCargarUltimoPrestamo.php', function (response) {

        data = response.data[0];

        let montoSolicitado = formatearMonto(data.montoSolicitado);

        $("#montoPrestamo").val(montoSolicitado);
        $('#telefono').val(data.telefono);

    });
}

function fCrearAhorro(){
    let sectionSolAhorro = document.getElementById("section_2");

    if(sectionSolAhorro.style.display === "block"){
        sectionSolAhorro.style.display = "none";
    }else{
        sectionSolAhorro.style.display = "block";

        fExisteAhorro();
    }
}

function fExisteAhorro(){

    $.getJSON('https://grammermx.com/RH/CajitaGrammer/dao/daoCargarUltimoAhorro.php', function (response) {

        let data = response.data[0];
        let data2 = response.data[2];

        let montoSolicitado = formatearMonto(data.montoAhorro);

        $("#montoAhorro").val(montoSolicitado);

        //Beneficiario 1
        $("#nombreBen1").val(data.nombre);
        $("#porcentajeBen1").val(data.porcentaje);
        $("#telefonoBen1").val(data.telefono);
        $("#domicilioBen1").val(data.direccion);

        if(data2) {
            let divBen2 = document.getElementById("divBeneficiario2");
            divBen2.style.display = "block";
            //Beneficiario 2
            $("#nombreBen2").val(data2.nombre);
            $("#porcentajeBen2").val(data2.porcentaje);
            $("#telefonoBen2").val(data2.telefono);
            $("#domicilioBen2").val(data2.direccion);
        }

    });
}


function fSolicitarRetiro(){
    let sectionSolRetiro = document.getElementById("section_3");

    if(sectionSolRetiro.style.display === "block"){
        sectionSolRetiro.style.display = "none";
    }else{
        sectionSolRetiro.style.display = "block";
    }
}

function fCargarPreguntas(){
    let sectionPreguntas = document.getElementById("section_4");

    if (sectionPreguntas.style.display === "block"){
        sectionPreguntas.style.display = "none";
    }else{
        sectionPreguntas.style.display = "block";
    }
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