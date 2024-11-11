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
    if (typeof excelDate === 'number') {
        // Fecha en formato numérico de Excel, convertirla a fecha JS
        const jsDate = new Date((excelDate - 25569) * 86400 * 1000); // Ajuste por el "serial" de Excel
        return moment(jsDate).format('YYYY-MM-DD'); // Usamos moment.js para dar formato
    } else if (typeof excelDate === 'string') {
        // Si la fecha está en un formato de cadena, intentar analizarla usando moment.js
        let date = moment(excelDate, ['DD/MM/YYYY', 'YYYY-MM-DD', 'MM/DD/YYYY'], true);

        // Verificar si moment pudo analizar la fecha correctamente
        if (date.isValid()) {
            return date.format('YYYY-MM-DD');
        } else {
            return ''; // Retornar cadena vacía si no se puede interpretar la fecha
        }
    }
    return ''; // Retornar cadena vacía si no es un número o string válido
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