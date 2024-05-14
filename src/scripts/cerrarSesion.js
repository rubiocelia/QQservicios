function confirmarCerrarSesion() {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Estás seguro de que deseas cerrar sesión?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#2a255b',
        cancelButtonColor: '#e6312f',
        confirmButtonText: 'Sí, cerrar sesión',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirigir al usuario a cerrar_sesion.php
            window.location.href = "./server/cerrar_sesion.php";
        }
    });
}