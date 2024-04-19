<?php
require_once("conecta.php");

$conexion = getConexion();

// Función para crear las tablas y la base de datos
function crearTablas($conexion) {
    
    // Verificar si la base de datos existe
    $verificarBD = $conexion->query("SHOW DATABASES LIKE 'QQservicios'");
    if ($verificarBD->num_rows <= 0) {
        // Crear la base de datos si no existe
        if (!$conexion->query("CREATE DATABASE QQservicios")) {
            die("Error al crear la base de datos: " . $conexion->error);
        }
        $conexion->select_db("QQservicios");
    } else {
        $conexion->query("DROP DATABASE QQservicios");
        $conexion->query("CREATE DATABASE QQservicios");
        $conexion->select_db("QQservicios");
    }

    // Crear las tablas en un orden que garantice que las llaves foráneas puedan ser definidas correctamente
    $sql_usuarios = "CREATE TABLE IF NOT EXISTS Usuarios (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        Nombre VARCHAR(255),
        Apellidos VARCHAR(255),
        Correo_electronico VARCHAR(255),
        Numero_telefono VARCHAR(20),
        Foto VARCHAR(255),
        Fecha_Registro DATE
    )";

    $sql_coaches = "CREATE TABLE IF NOT EXISTS Coaches (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        Nombre VARCHAR(255),
        Apellidos VARCHAR(255),
        Formacion VARCHAR(255),
        Experiencia VARCHAR(255),
        Foto VARCHAR(255)
    )";

    $sql_atributos = "CREATE TABLE IF NOT EXISTS Atributos (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        Nombre VARCHAR(255)
    )";

    $sql_productos = "CREATE TABLE IF NOT EXISTS Productos (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        Nombre VARCHAR(255),
        Descripcion TEXT,
        Categorias VARCHAR(255),
        Foto VARCHAR(255),
        Videos TEXT,
        Precio DECIMAL(10,2),
        Adquirible BOOLEAN,
        ID_coaches INT,
        Id_atributo INT,
        FOREIGN KEY (ID_coaches) REFERENCES Coaches(ID),
        FOREIGN KEY (Id_atributo) REFERENCES Atributos(ID)
    )";

    $sql_datos_acceso = "CREATE TABLE IF NOT EXISTS DatosAcceso (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        Usuario VARCHAR(255),
        Contrasena VARCHAR(255),
        Administrador BOOLEAN,
        FechaLogin DATETIME,
        ID_usuario INT,
        FOREIGN KEY (ID_usuario) REFERENCES Usuarios(ID)
    )";

    $sql_contenidos = "CREATE TABLE IF NOT EXISTS Contenidos (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        Titulo VARCHAR(255),
        Descripcion TEXT,
        ID_Producto INT,
        FOREIGN KEY (ID_Producto) REFERENCES Productos(ID)
    )";

    $sql_compra = "CREATE TABLE IF NOT EXISTS Compra (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        FechaHora DATETIME,
        Confirmacion BOOLEAN,
        ID_usuario INT,
        ID_Producto INT,
        FOREIGN KEY (ID_usuario) REFERENCES Usuarios(ID),
        FOREIGN KEY (ID_Producto) REFERENCES Productos(ID)
    )";

    $sql_testimonios = "CREATE TABLE IF NOT EXISTS Testimonios (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        Nombre VARCHAR(255),
        Descripcion TEXT,
        Foto VARCHAR(255),
        ID_Producto INT,
        FOREIGN KEY (ID_Producto) REFERENCES Productos(ID)
    )";

    $sql_archivos_usuarios = "CREATE TABLE IF NOT EXISTS ArchivosUsuarios (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        Ruta VARCHAR(255),
        Descripcion TEXT,
        ID_usuario INT,
        FOREIGN KEY (ID_usuario) REFERENCES Usuarios(ID)
    )";

    // Ejecutar las consultas para la creación de tablas
    $tables = [$sql_usuarios, $sql_coaches, $sql_atributos, $sql_productos, $sql_datos_acceso, $sql_contenidos, $sql_compra, $sql_testimonios, $sql_archivos_usuarios];
    foreach ($tables as $sql) {
        if ($conexion->query($sql) === TRUE) {
            echo "Tabla creada con éxito: " . $conexion->info . "<br>";
        } else {
            echo "Error al crear tabla: " . $conexion->error . "<br>";
        }
    }
}

crearTablas($conexion);

// Cerramos conexión
mysqli_close($conexion);
?>