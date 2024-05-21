<?php
session_start();
include('./bbdd/conecta.php');
$conn = getConexion();

if (!isset($_GET['id'])) {
    die("ID de producto no proporcionado");
}

$idProducto = $_GET['id'];

// Consulta para obtener el nombre del producto
$query = "SELECT Nombre FROM Productos WHERE ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idProducto);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No se encontró el producto");
}

$producto = $result->fetch_assoc();
$nombreProducto = $producto['Nombre'];

// Consulta para obtener los elementos del carrusel asociados al producto
$queryElementos = "SELECT * FROM carruselMultimedia WHERE ID_Producto = ?";
$stmtElementos = $conn->prepare($queryElementos);
$stmtElementos->bind_param("i", $idProducto);
$stmtElementos->execute();
$resultElementos = $stmtElementos->get_result();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>QQ Servicios</title>
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/index.css">
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/miCuenta_admin.css">
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
    <!-- CDN para el popup de cerrar sesión -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php include('menu_sesion_iniciada.php'); ?>
    <h1>Gestionar Carrusel: <?php echo htmlspecialchars($nombreProducto); ?></h1>

    <form id="formNuevoElemento" enctype="multipart/form-data">
        <input type="hidden" name="idProducto" value="<?php echo htmlspecialchars($idProducto); ?>">
        <label for="archivo">Seleccionar archivo:</label>
        <input type="file" id="archivo" name="archivo" accept="image/*,video/*">

        <label for="link_video">URL de YouTube:</label>
        <input type="url" id="link_video" name="link_video">

        <button type="button" onclick="agregarElemento()">Agregar Elemento</button>
    </form>

    <h2>Elementos del Carrusel</h2>
    <div id="elementosCarrusel" class="elementos-container">
        <?php while ($elemento = $resultElementos->fetch_assoc()) : ?>
            <div class="elemento-item">
                <?php if ($elemento['RutaArchivos']) : ?>
                    <img src="<?php echo htmlspecialchars($elemento['RutaArchivos']); ?>" alt="">
                <?php elseif ($elemento['Link_Video']) : ?>
                    <?php
                    // Convertir URL de YouTube a URL de inserción
                    $videoUrl = str_replace("watch?v=", "embed/", htmlspecialchars($elemento['Link_Video']));
                    ?>
                    <iframe width="560" height="315" src="<?php echo $videoUrl; ?>" frameborder="0" allowfullscreen></iframe>
                <?php endif; ?>
                <button type="button" class="btn-eliminar" onclick="eliminarElemento(<?php echo $elemento['ID']; ?>)">Eliminar</button>
            </div>
        <?php endwhile; ?>
    </div>

    <script>
        function agregarElemento() {
            var formData = new FormData(document.getElementById('formNuevoElemento'));
            formData.append('idProducto', <?php echo $idProducto; ?>);

            fetch('./server/agregar_elemento.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Error al agregar el elemento: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function eliminarElemento(id) {
            fetch('./server/eliminar_elemento.php?id=' + id, {
                    method: 'GET'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Error al eliminar el elemento: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
    <script src="./scripts/scriptPopUp.js"></script>
    <script src="./scripts/menuLateral.js"></script>
    <script src="./scripts/FuncionesAdmin.js"></script>
    <script src="./scripts/cerrarSesion.js"></script>
    <script src="./scripts/botonesPerfil.js"></script>
    <?php include('footer.php'); ?>
</body>

</html>