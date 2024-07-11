// Función para formatear la fecha
export function formatDate(fechaISO) {
    // Crear un objeto Date a partir de la cadena de fecha ISO
    var fecha = new Date(fechaISO);

    // Obtener las partes de la fecha y hora
    var year = fecha.getFullYear();
    var month = ('0' + (fecha.getMonth() + 1)).slice(-2);
    var day = ('0' + fecha.getDate()).slice(-2);
    var hours = ('0' + fecha.getHours()).slice(-2);
    var minutes = ('0' + fecha.getMinutes()).slice(-2);
    var seconds = ('0' + fecha.getSeconds()).slice(-2);

    // Formatear la fecha y hora en el formato deseado
    return year + "-" + month + "-" + day + " " + hours + ":" + minutes + ":" + seconds;
}
