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
        // Si es un número en formato Excel, convertirlo a fecha en JS
        const jsDate = new Date((excelDate - 25569) * 86400 * 1000);
        return `${jsDate.getFullYear()}/${(jsDate.getMonth() + 1).toString().padStart(2, '0')}/${jsDate.getDate().toString().padStart(2, '0')}`;
    } else if (typeof excelDate === 'string') {
        // Si es una cadena, intentar analizar varios formatos comunes
        const parsedDate = new Date(excelDate);

        if (!isNaN(parsedDate)) {
            // Si la fecha es válida, devolverla en formato YYYY/MM/DD
            return `${parsedDate.getFullYear()}/${(parsedDate.getMonth() + 1).toString().padStart(2, '0')}/${parsedDate.getDate().toString().padStart(2, '0')}`;
        } else {
            // Si la fecha no es válida, devolver un mensaje de error
            return "Error: Formato de fecha no válido";
        }
    } else {
        // Si no es ni número ni cadena, devolver un mensaje de error
        return "Error: Tipo de entrada no válido";
    }
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