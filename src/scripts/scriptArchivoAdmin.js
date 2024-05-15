function mostrarFormulario() {
    document.getElementById('formularioArchivo').style.display = 'block';
}

function ocultarFormulario() {
    document.getElementById('formularioArchivo').style.display = 'none';
}



function subirArchivo() {
    var formData = new FormData(document.getElementById('formArchivo'));
    
    fetch('./server/subir_archivo.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire(
                'Subido',
                'El archivo ha sido subido exitosamente.',
                'success'
            );
            // Recargar la página o actualizar la tabla
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
            'Hubo un problema subiendo el archivo.',
            'error'
        );
    });
}
