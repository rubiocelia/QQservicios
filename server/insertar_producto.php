<?php
require_once("./bbdd/conecta.php");
$conexion = getConexion();

$nombre = $_POST['nombre'];
$descripcionCorta = $_POST['descripcionCorta'];
$descripcion = $_POST['descripcion'];
$categorias = $_POST['categorias'];
$foto = $_FILES['foto']['name'];
$videos = $_POST['videos'];
$precio = $_POST['precio'];
$adquirible = $_POST['adquirible'];
$duracion = $_POST['duracion'];
$modalidad = $_POST['modalidad'];
$txtLibre = $_POST['txtLibre'];
$id_galeria = $_POST['id_galeria'];
$coaches = $_POST['coaches'];
$atributos = $_POST['atributos'];

$target_dir = "./archivos/productos/";
$target_file = $target_dir . basename($_FILES["foto"]["name"]);
move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);

$sql = "INSERT INTO Productos (Nombre, DescripcionCorta, Descripcion, Categorias, Foto, Videos, Precio, Adquirible, Duracion, Modalidad, txtLibre, Id_galeria) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssssssdsissi", $nombre, $descripcionCorta, $descripcion, $categorias, $target_file, $videos, $precio, $adquirible, $duracion, $modalidad, $txtLibre, $id_galeria);

if ($stmt->execute()) {
    $id_producto = $stmt->insert_id;

    foreach ($coaches as $id_coach) {
        $sql_coach = "INSERT INTO ProductoCoaches (ID_Producto, ID_Coach) VALUES (?, ?)";
        $stmt_coach = $conexion->prepare($sql_coach);
        $stmt_coach->bind_param("ii", $id_producto, $id_coach);
        $stmt_coach->execute();
    }

    foreach ($atributos as $id_atributo) {
        $sql_atributo = "INSERT INTO ProductoAtributos (ID_Producto, ID_Atributo) VALUES (?, ?)";
        $stmt_atributo = $conexion->prepare($sql_atributo);
        $stmt_atributo->bind_param("ii", $id_producto, $id_atributo);
        $stmt_atributo->execute();
    }

    echo "Producto insertado correctamente";
} else {
    echo "Error al insertar el producto";
}

$conexion->close();
?>
