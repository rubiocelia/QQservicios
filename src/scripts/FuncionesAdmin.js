
function redireccionarAVistaCliente(idUsuario) {
    // Construir la URL con el ID del usuario como parámetro
    var url = 'vistaClienteAdmin.php?id=' + idUsuario;
    // Redireccionar a vistaClienteAdmin.php con el ID del usuario en la URL
    window.location.href = url;
}

function redireccionarAVistaCoach(id) {
    window.location.href = 'detalleCoach.php?id=' + id;
}

function mostrarFormulario() {
    document.getElementById('formularioNuevoCoach').style.display = 'block';
}

function cancelarCoach(){
    document.getElementById('formularioNuevoCoach').style.display = 'none';
}

//Insercción asincrona de los coaches
function insertarCoach() {
    var formData = new FormData(document.getElementById('formNuevoCoach'));
    
    fetch('./server/insertar_coach.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire(
                'Éxito',
                'El coach ha sido añadido exitosamente.',
                'success'
            );
            // Opcionalmente, recargar la página o actualizar la tabla
            location.reload();
        } else {
            Swal.fire(
                'Error',
                data.message,
                'error'
            );
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire(
            'Error',
            'Hubo un problema al añadir el coach.',
            'error'
        );
    });
}

