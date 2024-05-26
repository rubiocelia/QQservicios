document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('contenidoTable');
    const rows = table.querySelectorAll('tbody tr');

    let draggingElement;
    let placeholder;

    rows.forEach(row => {
        row.draggable = true;

        row.addEventListener('dragstart', function (e) {
            draggingElement = row;
            placeholder = document.createElement('tr');
            placeholder.className = 'placeholder';
            e.dataTransfer.effectAllowed = 'move';
        });

        row.addEventListener('dragover', function (e) {
            e.preventDefault();
            if (e.target.parentNode !== draggingElement) {
                table.tBodies[0].insertBefore(placeholder, e.target.parentNode.nextSibling);
            }
        });

        row.addEventListener('drop', function (e) {
            e.preventDefault();
            if (placeholder.parentNode) {
                table.tBodies[0].insertBefore(draggingElement, placeholder);
                placeholder.parentNode.removeChild(placeholder);
            }
        });

        row.addEventListener('dragend', function () {
            if (placeholder.parentNode) {
                placeholder.parentNode.removeChild(placeholder);
            }
            actualizarOrden();
        });
    });
});

function actualizarOrden() {
    const rows = document.querySelectorAll('#contenidoTable tbody tr');
    let orden = [];
    rows.forEach((row, index) => {
        const id = row.id.split('_')[1];
        orden.push({ id, orden: index + 1 });
    });

    fetch('./server/actualizar_orden.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ orden })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Orden actualizado correctamente');
        } else {
            alert('Error al actualizar el orden');
        }
    });
}
