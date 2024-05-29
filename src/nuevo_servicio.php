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

// Manejar la inserción de un nuevo producto
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
        $foto = '';
    }

    if ($_FILES['fotoFondo']['name']) {
        $fotoFondo = "./archivos/productos/" . basename($_FILES['fotoFondo']['name']);
        move_uploaded_file($_FILES['fotoFondo']['tmp_name'], $fotoFondo);
    } else {
        $fotoFondo = '';
    }

    $insertQuery = "INSERT INTO Productos (Nombre, DescripcionCorta, Descripcion, Categorias, Foto, FotoFondo, Precio, Adquirible, Duracion, Modalidad, txtLibre, Id_galeria) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insertStmt = $conexion->prepare($insertQuery);
    $insertStmt->bind_param("sssssssisssi", $nombre, $descripcionCorta, $descripcion, $categorias, $foto, $fotoFondo, $precio, $adquirible, $duracion, $modalidad, $txtLibre, $id_galeria);

    if ($insertStmt->execute()) {
        // Obtener el ID del producto recién insertado
        $idProducto = $conexion->insert_id;

        // Insertar coaches asociados
        if (isset($_POST['coaches'])) {
            foreach ($_POST['coaches'] as $id_coach) {
                $conexion->query("INSERT INTO ProductoCoaches (ID_Producto, ID_Coach) VALUES ($idProducto, $id_coach)");
            }
        }

        // Insertar atributos asociados
        if (isset($_POST['atributos'])) {
            foreach ($_POST['atributos'] as $id_atributo) {
                $conexion->query("INSERT INTO ProductoAtributos (ID_Producto, ID_Atributo) VALUES ($idProducto, $id_atributo)");
            }
        }

        // Insertar contenidos asociados
        if (isset($_POST['contenidos_titulo']) && isset($_POST['contenidos_descripcion'])) {
            foreach ($_POST['contenidos_titulo'] as $index => $titulo) {
                $descripcionContenido = $_POST['contenidos_descripcion'][$index];
                $conexion->query("INSERT INTO Contenidos (Titulo, Descripcion, ID_Producto) VALUES ('$titulo', '$descripcionContenido', $idProducto)");
            }
        }

        // Redirigir a la misma página con el parámetro success
        header("Location: nuevo_servicio.php?success=1");
        exit();
    } else {
        echo "Error al insertar el producto: " . $insertStmt->error;
    }
}

// Obtener los datos de coaches y atributos
$coachesQuery = "SELECT * FROM Coaches";
$coachesResult = $conexion->query($coachesQuery);

$atributosQuery = "SELECT * FROM Atributos";
$atributosResult = $conexion->query($atributosQuery);

$galeriasQuery = "SELECT * FROM Galerias ORDER BY ID DESC";
$galeriasResult = $conexion->query($galeriasQuery);

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nuevo Servicio</title>
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/index.css">
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/editar_servicio.css">
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php include('menu_sesion_iniciada.php'); ?>
    <main>
        <h1>Nuevo Servicio</h1>
        <?php if (isset($_GET['success']) && $_GET['success'] == 1) : ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Éxito',
                        text: 'Producto añadido correctamente.',
                        icon: 'success'
                    }).then(function() {
                        window.location.href = window.location.pathname;
                    });
                });
            </script>
        <?php endif; ?>
        <form id="formNuevoServicio" action="nuevo_servicio.php" method="post" enctype="multipart/form-data" class="form-editar-servicio">
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="descripcionCorta" class="form-label">Descripción Corta:</label>
                <textarea id="descripcionCorta" name="descripcionCorta" class="form-input" required></textarea>
            </div>
            <div class="form-group">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea id="descripcion" name="descripcion" class="form-input" required></textarea>
            </div>
            <div class="form-group">
                <label for="categorias" class="form-label">Categorías:</label>
                <input type="text" id="categorias" name="categorias" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="foto" class="form-label">Foto:</label>
                <input type="file" id="foto" name="foto" class="form-input">
            </div>
            <div class="form-group">
                <label for="fotoFondo" class="form-label">Foto Fondo:</label>
                <input type="file" id="fotoFondo" name="fotoFondo" class="form-input">
            </div>
            <div class="form-group">
                <label for="precio" class="form-label">Precio:</label>
                <input type="text" id="precio" name="precio" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="adquirible" class="form-label">Adquirible:</label>
                <select id="adquirible" name="adquirible" class="form-input" required>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="duracion" class="form-label">Duración:</label>
                <input type="text" id="duracion" name="duracion" class="form-input">
            </div>
            <div class="form-group">
                <label for="modalidad" class="form-label">Modalidad:</label>
                <input type="text" id="modalidad" name="modalidad" class="form-input">
            </div>
            <div class="form-group">
                <label for="txtLibre" class="form-label">Texto Libre:</label>
                <input type="text" id="txtLibre" name="txtLibre" class="form-input">
            </div>
            <div class="form-group">
                <label for="id_galeria" class="form-label">Galería:</label>
                <select id="id_galeria" name="id_galeria" class="form-input" required>
                    <?php
                    while ($galeria = $galeriasResult->fetch_assoc()) {
                        echo "<option value='" . $galeria['ID'] . "'>" . $galeria['Nombre_galeria'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="coaches" class="form-label">Coaches:</label>
                <select id="coaches" name="coaches[]" class="form-input" multiple required>
                    <?php
                    while ($coach = $coachesResult->fetch_assoc()) {
                        echo "<option value='" . $coach['ID'] . "'>" . $coach['Nombre'] . " " . $coach['Apellidos'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="atributos" class="form-label">Atributos:</label>
                <select id="atributos" name="atributos[]" class="form-input" multiple required>
                    <?php
                    while ($atributo = $atributosResult->fetch_assoc()) {
                        echo "<option value='" . $atributo['ID'] . "'>" . $atributo['Nombre'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="contenidos_titulo" class="form-label">Título de Contenido:</label>
                <input type="text" id="contenidos_titulo" name="contenidos_titulo" class="form-input">
            </div>
            <div class="form-group">
                <label for="contenidos_descripcion" class="form-label">Descripción de Contenido:</label>
                <textarea id="contenidos_descripcion" name="contenidos_descripcion" class="form-input"></textarea>
            </div>
            <button type="button" onclick="añadirContenido()">Añadir Contenido</button>
            <ul id="listaContenidos"></ul>
            <button type="submit" class="form-button">Añadir Servicio</button>
        </form>
    </main>
    <script>
        function añadirContenido() {
            var titulo = document.getElementById("contenidos_titulo").value;
            var descripcion = document.getElementById("contenidos_descripcion").value;

            if (titulo && descripcion) {
                var li = document.createElement("li");
                li.innerHTML = "Título: " + titulo + ", Descripción: " + descripcion;
                li.dataset.titulo = titulo;
                li.dataset.descripcion = descripcion;
                document.getElementById("listaContenidos").appendChild(li);

                document.getElementById("contenidos_titulo").value = "";
                document.getElementById("contenidos_descripcion").value = "";
            } else {
                alert("Por favor, rellena tanto el título como la descripción del contenido.");
            }
        }

        document.getElementById("formNuevoServicio").addEventListener("submit", function(event) {
            var listaContenidos = document.getElementById("listaContenidos").children;
            for (var i = 0; i < listaContenidos.length; i++) {
                var li = listaContenidos[i];
                var inputTitulo = document.createElement("input");
                inputTitulo.type = "hidden";
                inputTitulo.name = "contenidos_titulo[]";
                inputTitulo.value = li.dataset.titulo;

                var inputDescripcion = document.createElement("input");
                inputDescripcion.type = "hidden";
                inputDescripcion.name = "contenidos_descripcion[]";
                inputDescripcion.value = li.dataset.descripcion;

                this.appendChild(inputTitulo);
                this.appendChild(inputDescripcion);
            }
        });
    </script>
    <?php include('footer.php'); ?>
</body>

</html>