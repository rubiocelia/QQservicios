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
    actualizarProducto($conexion, $idProducto, $producto);
}

// Obtener los datos necesarios para el formulario
$coachesResult = $conexion->query("SELECT * FROM Coaches");
$atributosResult = $conexion->query("SELECT * FROM Atributos");
$galeriasResult = $conexion->query("SELECT * FROM Galerias ORDER BY ID DESC");
$contenidosResult = obtenerContenidos($conexion, $idProducto);
$productoCoaches = obtenerIds($conexion, "ProductoCoaches", "ID_Coach", $idProducto);
$productoAtributos = obtenerIds($conexion, "ProductoAtributos", "ID_Atributo", $idProducto);

$conexion->close();

function actualizarProducto($conexion, $idProducto, $producto)
{
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

    $foto = subirArchivo('foto', './archivos/productos/', $producto['Foto']);
    $fotoFondo = subirArchivo('fotoFondo', './archivos/productos/', $producto['FotoFondo']);

    $updateQuery = "UPDATE Productos SET Nombre=?, DescripcionCorta=?, Descripcion=?, Categorias=?, Foto=?, FotoFondo=?, Precio=?, Adquirible=?, Duracion=?, Modalidad=?, txtLibre=?, Id_galeria=? WHERE ID=?";
    $updateStmt = $conexion->prepare($updateQuery);
    $updateStmt->bind_param("sssssssisssii", $nombre, $descripcionCorta, $descripcion, $categorias, $foto, $fotoFondo, $precio, $adquirible, $duracion, $modalidad, $txtLibre, $id_galeria, $idProducto);

    if ($updateStmt->execute()) {
        actualizarAsociaciones($conexion, $idProducto, 'ProductoCoaches', 'ID_Coach', $_POST['coaches']);
        actualizarAsociaciones($conexion, $idProducto, 'ProductoAtributos', 'ID_Atributo', $_POST['atributos']);
        actualizarContenidos($conexion, $idProducto, $_POST['contenidos_titulo'], $_POST['contenidos_descripcion']);

        header("Location: editar_servicio.php?id=$idProducto&success=1");
        exit();
    } else {
        echo "Error al actualizar el producto: " . $updateStmt->error;
    }
}

function subirArchivo($inputName, $targetDir, $currentFile)
{
    if ($_FILES[$inputName]['name']) {
        $filePath = $targetDir . basename($_FILES[$inputName]['name']);
        move_uploaded_file($_FILES[$inputName]['tmp_name'], $filePath);
        return $filePath;
    }
    return $currentFile;
}

function actualizarAsociaciones($conexion, $idProducto, $tabla, $columna, $nuevasAsociaciones)
{
    $conexion->query("DELETE FROM $tabla WHERE ID_Producto = $idProducto");
    if (!empty($nuevasAsociaciones)) {
        foreach ($nuevasAsociaciones as $id) {
            $conexion->query("INSERT INTO $tabla (ID_Producto, $columna) VALUES ($idProducto, $id)");
        }
    }
}

function actualizarContenidos($conexion, $idProducto, $titulos, $descripciones)
{
    if (!empty($titulos) && !empty($descripciones)) {
        foreach ($titulos as $idContenido => $titulo) {
            $descripcionContenido = $descripciones[$idContenido];
            if ($idContenido > 0) {
                $updateContenidoQuery = "UPDATE Contenidos SET Titulo = ?, Descripcion = ? WHERE ID = ?";
                $updateContenidoStmt = $conexion->prepare($updateContenidoQuery);
                $updateContenidoStmt->bind_param("ssi", $titulo, $descripcionContenido, $idContenido);
                $updateContenidoStmt->execute();
            } else {
                $insertContenidoQuery = "INSERT INTO Contenidos (Titulo, Descripcion, ID_Producto) VALUES (?, ?, ?)";
                $insertContenidoStmt = $conexion->prepare($insertContenidoQuery);
                $insertContenidoStmt->bind_param("ssi", $titulo, $descripcionContenido, $idProducto);
                $insertContenidoStmt->execute();
            }
        }
    }
}

function obtenerContenidos($conexion, $idProducto)
{
    $contenidosQuery = "SELECT * FROM Contenidos WHERE ID_Producto = ?";
    $contenidosStmt = $conexion->prepare($contenidosQuery);
    $contenidosStmt->bind_param("i", $idProducto);
    $contenidosStmt->execute();
    return $contenidosStmt->get_result();
}

function obtenerIds($conexion, $tabla, $columna, $idProducto)
{
    $query = "SELECT $columna FROM $tabla WHERE ID_Producto = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $idProducto);
    $stmt->execute();
    $result = $stmt->get_result();
    $ids = [];
    while ($row = $result->fetch_assoc()) {
        $ids[] = $row[$columna];
    }
    return $ids;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Servicio</title>
    <link rel="stylesheet" type="text/css" href="./estilos/css/index.css">
    <link rel="stylesheet" type="text/css" href="./estilos/css/editar_servicio.css">
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    .contenido-item {
        margin-bottom: 10px;
        padding: 10px;
        border: 1px solid #ccc;
    }

    .contenido-header {
        cursor: pointer;
        background-color: #f7f7f7;
        padding: 10px;
        border-bottom: 1px solid #ccc;
    }

    .contenido-body {
        display: none;
        padding: 10px;
    }
    </style>
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
        <form action="editar_servicio.php?id=<?php echo $idProducto; ?>" method="post" enctype="multipart/form-data"
            class="form-editar-servicio">
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-input"
                    value="<?php echo htmlspecialchars($producto['Nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="descripcionCorta" class="form-label">Descripción Corta:</label>
                <textarea id="descripcionCorta" name="descripcionCorta" class="form-input"
                    required><?php echo htmlspecialchars($producto['DescripcionCorta']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea id="descripcion" name="descripcion" class="form-input"
                    required><?php echo htmlspecialchars($producto['Descripcion']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="categorias" class="form-label">Categorías:</label>
                <input type="text" id="categorias" name="categorias" class="form-input"
                    value="<?php echo htmlspecialchars($producto['Categorias']); ?>" required>
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
                <input type="text" id="precio" name="precio" class="form-input"
                    value="<?php echo htmlspecialchars($producto['Precio']); ?>" required>
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
                <input type="text" id="duracion" name="duracion" class="form-input"
                    value="<?php echo htmlspecialchars($producto['Duracion']); ?>">
            </div>
            <div class="form-group">
                <label for="modalidad" class="form-label">Modalidad:</label>
                <input type="text" id="modalidad" name="modalidad" class="form-input"
                    value="<?php echo htmlspecialchars($producto['Modalidad']); ?>">
            </div>
            <div class="form-group">
                <label for="txtLibre" class="form-label">Texto Libre:</label>
                <input type="text" id="txtLibre" name="txtLibre" class="form-input"
                    value="<?php echo htmlspecialchars($producto['txtLibre']); ?>">
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
            <div id="contenidos">
                <?php while ($contenido = $contenidosResult->fetch_assoc()) { ?>
                <div class="contenido-item">
                    <div class="contenido-header">
                        <span>Contenido: <?php echo htmlspecialchars($contenido['Titulo']); ?></span>
                    </div>
                    <div class="contenido-body">
                        <div class="form-group">
                            <label for="contenido_titulo_<?php echo $contenido['ID']; ?>" class="form-label">Título de
                                Contenido:</label>
                            <input type="text" id="contenido_titulo_<?php echo $contenido['ID']; ?>"
                                name="contenidos_titulo[<?php echo $contenido['ID']; ?>]" class="form-input"
                                value="<?php echo htmlspecialchars($contenido['Titulo']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="contenido_descripcion_<?php echo $contenido['ID']; ?>"
                                class="form-label">Descripción de Contenido:</label>
                            <textarea id="contenido_descripcion_<?php echo $contenido['ID']; ?>"
                                name="contenidos_descripcion[<?php echo $contenido['ID']; ?>]" class="form-input"
                                required><?php echo htmlspecialchars($contenido['Descripcion']); ?></textarea>
                        </div>
                        <button type="button" class="form-button eliminar-contenido"
                            data-id="<?php echo $contenido['ID']; ?>">Eliminar Contenido</button>
                    </div>
                </div>
                <?php } ?>
            </div>
            <!-- Botón para agregar nuevo contenido -->
            <button id="agregarContenido" type="button" class="form-button">Agregar Nuevo Contenido</button>
            <button type="submit" class="form-button">Guardar Cambios</button>
        </form>
        <button class="form-button-cancel" onclick="eliminarProducto(<?php echo $idProducto; ?>)">Eliminar
            Servicio</button>
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

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('contenido-header')) {
            const contenidoBody = event.target.nextElementSibling;
            contenidoBody.style.display = contenidoBody.style.display === 'none' ? 'block' : 'none';
        }

        if (event.target.classList.contains('eliminar-contenido')) {
            const contenidoId = event.target.getAttribute('data-id');
            if (contenidoId > 0) {
                fetch('eliminar_contenido.php?id=' + contenidoId, {
                        method: 'GET',
                    })
                    .then(response => response.text())
                    .then(data => {
                        alert(data);
                        event.target.closest('.contenido-item').remove();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                event.target.closest('.contenido-item').remove();
            }
        }
    });

    document.getElementById('agregarContenido').addEventListener('click', function() {
        const nuevoContenido = document.createElement('div');
        nuevoContenido.classList.add('contenido-item');
        nuevoContenido.innerHTML = `
                <div class="contenido-header">
                    <span>Nuevo Contenido</span>
                </div>
                <div class="contenido-body">
                    <div class="form-group">
                        <label for="nuevo_contenido_titulo" class="form-label">Título de Contenido:</label>
                        <input type="text" name="contenidos_titulo[0]" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="nuevo_contenido_descripcion" class="form-label">Descripción de Contenido:</label>
                        <textarea name="contenidos_descripcion[0]" class="form-input" required></textarea>
                    </div>
                    <button type="button" class="form-button eliminar-contenido" data-id="0">Eliminar Contenido</button>
                </div>
            `;
        document.getElementById('contenidos').appendChild(nuevoContenido);
    });

    // Inicializar los acordeones
    document.querySelectorAll('.contenido-body').forEach(function(body) {
        body.style.display = 'none';
    });
    </script>
    <?php include('footer.php'); ?>
</body>

</html>