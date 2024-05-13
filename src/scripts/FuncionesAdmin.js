
function redireccionarAVistaCliente(idUsuario) {
    // Construir la URL con el ID del usuario como parámetro
    var url = 'vistaClienteAdmin.php?id=' + idUsuario;
    // Redireccionar a vistaClienteAdmin.php con el ID del usuario en la URL
    window.location.href = url;
}
