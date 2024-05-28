<?php
// Iniciar sesión
session_start();

// Verificar si el usuario está registrado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

// Obtener los datos del usuario
require_once("./bbdd/conecta.php");
$conexion = getConexion();

// Obtener el ID del producto desde la URL
$idProducto = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($idProducto == 0) {
    echo "ID de producto no válido.";
    $conexion->close();
    exit();
}

// Obtener la información del producto con el ID proporcionado
$productoQuery = "SELECT * FROM Productos WHERE ID = ?";
$productoStmt = $conexion->prepare($productoQuery);
$productoStmt->bind_param("i", $idProducto);
$productoStmt->execute();
$productoResult = $productoStmt->get_result();

if ($productoResult->num_rows == 0) {
    echo "No se encontró información para el producto con el ID proporcionado.";
    $conexion->close();
    exit();
}

$producto = $productoResult->fetch_assoc();

// Manejar la actualización del producto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcionCorta = $_POST['descripcionCorta'];
    $descripcion = $_POST['descripcion'];
    $categorias = $_POST['categorias'];
    $precio = $_POST['precio'];
    $adquirible = isset($_POST['adquirible']) ? 1 : 0;
    $duracion = $_POST['duracion'];
    $modalidad = $_POST['modalidad'];
    $txtLibre = $_POST['txtLibre'];
    $id_galeria = $_POST['id_galeria'];

    // Subir archivos si hay nuevos
    if ($_FILES['foto']['name']) {
        $foto = "./archivos/productos/" . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
    } else {
        $foto = $producto['Foto'];
    }

    if ($_FILES['fotoFondo']['name']) {
        $fotoFondo = "./archivos/productos/" . basename($_FILES['fotoFondo']['name']);
        move_uploaded_file($_FILES['fotoFondo']['tmp_name'], $fotoFondo);
    } else {
        $fotoFondo = $producto['FotoFondo'];
    }

    $updateQuery = "UPDATE Productos SET Nombre=?, DescripcionCorta=?, Descripcion=?, Categorias=?, Foto=?, FotoFondo=?, Precio=?, Adquirible=?, Duracion=?, Modalidad=?, txtLibre=?, Id_galeria=? WHERE ID=?";
    $updateStmt = $conexion->prepare($updateQuery);
    $updateStmt->bind_param("sssssssisssii", $nombre, $descripcionCorta, $descripcion, $categorias, $foto, $fotoFondo, $precio, $adquirible, $duracion, $modalidad, $txtLibre, $id_galeria, $idProducto);

    if ($updateStmt->execute()) {
        // Actualizar coaches asociados
        $conexion->query("DELETE FROM ProductoCoaches WHERE ID_Producto = $idProducto");
        if (isset($_POST['coaches'])) {
            foreach ($_POST['coaches'] as $id_coach) {
                $conexion->query("INSERT INTO ProductoCoaches (ID_Producto, ID_Coach) VALUES ($idProducto, $id_coach)");
            }
        }

        // Actualizar atributos asociados
        $conexion->query("DELETE FROM ProductoAtributos WHERE ID_Producto = $idProducto");
        if (isset($_POST['atributos'])) {
            foreach ($_POST['atributos'] as $id_atributo) {
                $conexion->query("INSERT INTO ProductoAtributos (ID_Producto, ID_Atributo) VALUES ($idProducto, $id_atributo)");
            }
        }

        // Redirigir a la misma página con el parámetro success
        header("Location: editar_servicio.php?id=$idProducto&success=1");
        exit();
    } else {
        echo "Error al actualizar el producto: " . $updateStmt->error;
    }
}

// Obtener los datos de coaches y atributos
$coachesQuery = "SELECT * FROM Coaches";
$coachesResult = $conexion->query($coachesQuery);

$atributosQuery = "SELECT * FROM Atributos";
$atributosResult = $conexion->query($atributosQuery);

$galeriasQuery = "SELECT * FROM Galerias ORDER BY ID DESC";
$galeriasResult = $conexion->query($galeriasQuery);

// Obtener los IDs de los coaches asociados al producto
$productoCoachesQuery = "SELECT ID_Coach FROM ProductoCoaches WHERE ID_Producto = ?";
$productoCoachesStmt = $conexion->prepare($productoCoachesQuery);
$productoCoachesStmt->bind_param("i", $idProducto);
$productoCoachesStmt->execute();
$productoCoachesResult = $productoCoachesStmt->get_result();
$productoCoaches = [];
while ($row = $productoCoachesResult->fetch_assoc()) {
    $productoCoaches[] = $row['ID_Coach'];
}

// Obtener los IDs de los atributos asociados al producto
$productoAtributosQuery = "SELECT ID_Atributo FROM ProductoAtributos WHERE ID_Producto = ?";
$productoAtributosStmt = $conexion->prepare($productoAtributosQuery);
$productoAtributosStmt->bind_param("i", $idProducto);
$productoAtributosStmt->execute();
$productoAtributosResult = $productoAtributosStmt->get_result();
$productoAtributos = [];
while ($row = $productoAtributosResult->fetch_assoc()) {
    $productoAtributos[] = $row['ID_Atributo'];
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Servicio</title>
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/index.css">
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/editar_servicio.css">
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php include('menu_sesion_iniciada.php'); ?>
    <main>
        <h1>Editar Servicio</h1>
        <?php if (isset($_GET['success']) && $_GET['success'] == 1) : ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Éxito',
                        text: 'Producto actualizado correctamente.',
                        icon: 'success'
                    }).then(function() {
                        window.location.href = window.location.pathname + "?id=<?php echo $idProducto; ?>";
                    });
                });
            </script>
        <?php endif; ?>
        <form action="editar_servicio.php?id=<?php echo $idProducto; ?>" method="post" enctype="multipart/form-data" class="form-editar-servicio">
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-input" value="<?php echo htmlspecialchars($producto['Nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="descripcionCorta" class="form-label">Descripción Corta:</label>
                <textarea id="descripcionCorta" name="descripcionCorta" class="form-input" required><?php echo htmlspecialchars($producto['DescripcionCorta']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea id="descripcion" name="descripcion" class="form-input" required><?php echo htmlspecialchars($producto['Descripcion']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="categorias" class="form-label">Categorías:</label>
                <input type="text" id="categorias" name="categorias" class="form-input" value="<?php echo htmlspecialchars($producto['Categorias']); ?>" required>
            </div>
            <div class="form-group">
                <label for="foto" class="form-label">Foto:</label>
                <input type="file" id="foto" name="foto" class="form-input">
                <?php if ($producto['Foto']) {
                    echo '<img src="' . htmlspecialchars($producto['Foto']) . '" alt="Foto" width="100">';
                } ?>
            </div>
            <div class="form-group">
                <label for="fotoFondo" class="form-label">Foto Fondo:</label>
                <input type="file" id="fotoFondo" name="fotoFondo" class="form-input">
                <?php if ($producto['FotoFondo']) {
                    echo '<img src="' . htmlspecialchars($producto['FotoFondo']) . '" alt="Foto Fondo" width="100">';
                } ?>
            </div>
            <div class="form-group">
                <label for="precio" class="form-label">Precio:</label>
                <input type="text" id="precio" name="precio" class="form-input" value="<?php echo htmlspecialchars($producto['Precio']); ?>" required>
            </div>
            <div class="form-group">
                <label for="adquirible" class="form-label">Adquirible:</label>
                <select id="adquirible" name="adquirible" class="form-input" required>
                    <option value="1" <?php echo $producto['Adquirible'] ? 'selected' : ''; ?>>Sí</option>
                    <option value="0" <?php echo !$producto['Adquirible'] ? 'selected' : ''; ?>>No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="duracion" class="form-label">Duración:</label>
                <input type="text" id="duracion" name="duracion" class="form-input" value="<?php echo htmlspecialchars($producto['Duracion']); ?>" >
            </div>
            <div class="form-group">
                <label for="modalidad" class="form-label">Modalidad:</label>
                <input type="text" id="modalidad" name="modalidad" class="form-input" value="<?php echo htmlspecialchars($producto['Modalidad']); ?>" >
            </div>
            <div class="form-group">
                <label for="txtLibre" class="form-label">Texto Libre:</label>
                <input type="text" id="txtLibre" name="txtLibre" class="form-input" value="<?php echo htmlspecialchars($producto['txtLibre']); ?>" >
            </div>
            <div class="form-group">
                <label for="id_galeria" class="form-label">Galería:</label>
                <select id="id_galeria" name="id_galeria" class="form-input" required>
                    <?php
                    while ($galeria = $galeriasResult->fetch_assoc()) {
                        echo "<option value='" . $galeria['ID'] . "' " . ($producto['Id_galeria'] == $galeria['ID'] ? 'selected' : '') . ">" . $galeria['Nombre_galeria'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="coaches" class="form-label">Coaches:</label>
                <select id="coaches" name="coaches[]" class="form-input" multiple required>
                    <?php
                    while ($coach = $coachesResult->fetch_assoc()) {
                        echo "<option value='" . $coach['ID'] . "' " . (in_array($coach['ID'], $productoCoaches) ? 'selected' : '') . ">" . $coach['Nombre'] . " " . $coach['Apellidos'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="atributos" class="form-label">Atributos:</label>
                <select id="atributos" name="atributos[]" class="form-input" multiple required>
                    <?php
                    while ($atributo = $atributosResult->fetch_assoc()) {
                        echo "<option value='" . $atributo['ID'] . "' " . (in_array($atributo['ID'], $productoAtributos) ? 'selected' : '') . ">" . $atributo['Nombre'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="form-button">Guardar Cambios</button>
        </form>
        <button class="form-button-cancel" onclick="eliminarProducto(<?php echo $idProducto; ?>)">Eliminar Servicio</button>
    </main>
    <script>
        function eliminarProducto(id) {
            if (confirm("¿Seguro que deseas eliminar este servicio?")) {
                fetch("eliminar_servicio.php?id=" + id, {
                        method: "GET",
                    })
                    .then(response => response.text())
                    .then(data => {
                        alert(data);
                        window.location.href = "pagina_admin.php"; // Redirigir a la página de administración
                    })
                    .catch(error => {
                        console.error("Error:", error);
                    });
            }
        }
    </script>
    <?php include('footer.php'); ?>
</body>

</html>