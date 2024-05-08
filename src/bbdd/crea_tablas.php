<?php
require_once("conecta.php");

// Obtener conexión a la base de datos desde el archivo conecta.php
$conexion = getConexion();

// Función para crear las tablas y la base de datos
function crearTablas($conexion) {
     // Eliminar la base de datos si existe
     $conexion->query("DROP DATABASE IF EXISTS QQservicios");

     // Crear la base de datos
     if (!$conexion->query("CREATE DATABASE QQservicios")) {
         die("Error al crear la base de datos: " . $conexion->error);
     }
    
    $conexion->select_db("QQservicios");

    // Array para la creación de las tablas de la base de datos
    $tables = [
        "usuarios" => "CREATE TABLE IF NOT EXISTS Usuarios (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            Nombre VARCHAR(255),
            Apellidos VARCHAR(255),
            Correo_electronico VARCHAR(255) UNIQUE,
            Numero_telefono VARCHAR(20),
            Organizacion VARCHAR(255),
            Foto VARCHAR(255),
            Fecha_Registro DATE
        )",
        "coaches" => "CREATE TABLE IF NOT EXISTS Coaches (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            Nombre VARCHAR(255),
            Apellidos VARCHAR(255),
            Titulacion VARCHAR(255),
            Formacion VARCHAR(255),
            Experiencia VARCHAR(255),
            Foto VARCHAR(255)
        )",
        "atributos" => "CREATE TABLE IF NOT EXISTS Atributos (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            Nombre VARCHAR(255)
        )",
        "productos" => "CREATE TABLE IF NOT EXISTS Productos (
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
        )",
        "datos_acceso" => "CREATE TABLE IF NOT EXISTS DatosAcceso (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            ID_usuario INT,
            Contrasena VARCHAR(255),
            Administrador BOOLEAN,
            FechaLogin DATETIME,
            FOREIGN KEY (ID_usuario) REFERENCES Usuarios(ID)
        )",
        "contenidos" => "CREATE TABLE IF NOT EXISTS Contenidos (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            Titulo VARCHAR(255),
            Descripcion TEXT,
            ID_Producto INT,
            FOREIGN KEY (ID_Producto) REFERENCES Productos(ID)
        )",
        "compra" => "CREATE TABLE IF NOT EXISTS Compra (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            FechaHora DATETIME,
            Confirmacion BOOLEAN,
            ID_usuario INT,
            ID_Producto INT,
            FOREIGN KEY (ID_usuario) REFERENCES Usuarios(ID),
            FOREIGN KEY (ID_Producto) REFERENCES Productos(ID)
        )",
        "testimonios" => "CREATE TABLE IF NOT EXISTS Testimonios (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            Nombre VARCHAR(255),
            Descripcion TEXT,
            Foto VARCHAR(255),
            ID_Producto INT,
            FOREIGN KEY (ID_Producto) REFERENCES Productos(ID)
        )",
        "archivos_usuarios" => "CREATE TABLE IF NOT EXISTS ArchivosUsuarios (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            Ruta VARCHAR(255),
            Descripcion TEXT,
            ID_usuario INT,
            FOREIGN KEY (ID_usuario) REFERENCES Usuarios(ID)
        )"
    ];

    // Crear cada tabla en la base de datos
    foreach ($tables as $table_name => $sql) {
        if (!$conexion->query($sql)) {
            die("Error al crear la tabla $table_name: " . $conexion->error);
        } else {
            echo "Tabla $table_name creada con éxito.<br>";
        }
    }
}
    

// Llama a la función para crear las tablas
crearTablas($conexion);

/*
*****************************************
*****************************************
*****************************************
LA INSERCCIÓN DE DATOS A CONTINUACIÓN SON DATOS DE EJEMPLO NO REALES NI VÁLIDOS
*****************************************
*****************************************
*****************************************
*/
//Función para insertar datos en las tablas 
function insertarDatos($conexion)
{
    // Insertar datos en Coaches
    $conexion->query("INSERT INTO Coaches (Nombre, Apellidos, Titulacion, Formacion, Experiencia, Foto) VALUES
                      ('Coach1', 'Apellido1', 'Titulacion1', 'Formacion1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', './archivos/coaches/FotoCoach1.png'),
                      ('Coach2', 'Apellido2', 'Titulacion2', 'Formacion2', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', './archivos/coaches/FotoCoach2.png'),
                      ('Coach3', 'Apellido3', 'Titulacion3', 'Formacion3', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', './archivos/coaches/FotoCoach3.png'),
                      ('Coach4', 'Apellido4', 'Titulacion4', 'Formacion4', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', './archivos/coaches/FotoCoach4.png'),
                      ('Coach5', 'Apellido5', 'Titulacion5', 'Formacion5', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', './archivos/coaches/FotoCoach1.png'),
                      ('Coach6', 'Apellido6', 'Titulacion6', 'Formacion6', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', './archivos/coaches/FotoCoach2.png'),
                      ('Coach7', 'Apellido7', 'Titulacion7', 'Formacion7', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', './archivos/coaches/FotoCoach3.png'),
                      ('Coach8', 'Apellido8', 'Titulacion8', 'Formacion8', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', './archivos/coaches/FotoCoach4.png'),
                      ('Coach9', 'Apellido9', 'Titulacion9', 'Formacion9', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', './archivos/coaches/FotoCoach1.png'),
                      ('Coach10', 'Apellido10', 'Titulacion10', 'Formacion10', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', './archivos/coaches/FotoCoach2.png')");

    // Insertar datos en Atributos
    $conexion->query("INSERT INTO Atributos (Nombre) VALUES
                      ('Individual'),
                      ('Grupal'),
                      ('Coaching'),
                      ('Atributo4'),
                      ('Atributo5'),
                      ('Atributo6'),
                      ('Atributo7'),
                      ('Atributo8'),
                      ('Atributo9'),
                      ('Atributo10')");

    // Insertar datos en Usuarios. La función CURDATE() en sql devuelve la fecha actual 
    $conexion->query("INSERT INTO Usuarios (Nombre, Apellidos, Correo_electronico, Numero_telefono, Organizacion, Foto, Fecha_Registro) VALUES
                      ('Nombre1', 'Apellido1', 'email1@example.com', '1234567890', 'Organización 1', '../src/archivos/perfil/fotosPerfil/foto1.jpg', CURDATE()),
                      ('Nombre2', 'Apellido2', 'email2@example.com', '1234567891', 'Organización 1', 'foto2Usuario.jpg', CURDATE()),
                      ('Nombre3', 'Apellido3', 'email3@example.com', '1234567892', 'Organización 1', 'foto3Usuario.jpg', CURDATE()),
                      ('Nombre4', 'Apellido4', 'email4@example.com', '1234567893', 'Organización 1', 'foto4Usuario.jpg', CURDATE()),
                      ('Nombre5', 'Apellido5', 'email5@example.com', '1234567894','Organización 1', 'foto5Usuario.jpg', CURDATE()),
                      ('Nombre6', 'Apellido6', 'email6@example.com', '1234567895', 'Organización 1', 'foto6Usuario.jpg', CURDATE()),
                      ('Nombre7', 'Apellido7', 'email7@example.com', '1234567896', 'Organización 1', 'foto7Usuario.jpg', CURDATE()),
                      ('Nombre8', 'Apellido8', 'email8@example.com', '1234567897', 'Organización 1', 'foto8Usuario.jpg', CURDATE()),
                      ('Nombre9', 'Apellido9', 'email9@example.com', '1234567898', 'Organización 1', 'foto9Usuario.jpg', CURDATE()),
                      ('Nombre10', 'Apellido10', 'email10@example.com', '1234567899', 'Organización 1', 'foto10Usuario.jpg', CURDATE())");

    // Insertar datos en Productos
    $conexion->query("INSERT INTO Productos (Nombre, Descripcion, Categorias, Foto, Videos, Precio, Adquirible, ID_coaches, Id_atributo) VALUES
                      ('Producto1', 'Descripcion1', 'Categoria1', 'producto1.jpg', 'video1producto.mp4', 100, 1, 1, 1),
                      ('Producto2', 'Descripcion2', 'Categoria2', 'producto2.jpg', 'video2producto.mp4', 200, 0, 2, 2),
                      ('Producto3', 'Descripcion3', 'Categoria3', 'producto3.jpg', 'video3producto.mp4', 300, 1, 3, 3),
                      ('Producto4', 'Descripcion4', 'Categoria4', 'producto4.jpg', 'video4producto.mp4', 400, 0, 4, 4),
                      ('Producto5', 'Descripcion5', 'Categoria5', 'producto5.jpg', 'video5producto.mp4', 500, 1, 5, 5),
                      ('Producto6', 'Descripcion6', 'Categoria6', 'producto6.jpg', 'video6producto.mp4', 600, 0, 6, 6),
                      ('Producto7', 'Descripcion7', 'Categoria7', 'producto7.jpg', 'video7producto.mp4', 700, 1, 7, 7),
                      ('Producto8', 'Descripcion8', 'Categoria8', 'producto8.jpg', 'video8producto.mp4', 800, 0, 8, 8),
                      ('Producto9', 'Descripcion9', 'Categoria9', 'producto9.jpg', 'video9producto.mp4', 900, 1, 9, 9),
                      ('Producto10', 'Descripcion10', 'Categoria10', 'producto10.jpg', 'video10producto.mp4', 1000, 0, 10, 10)");

$usuarios = [
    ['adminJavier','ejemplo1', 1, 1],
    ['usuario2', 'ejemplo1',0, 2],
    ['usuario3', 'ejemplo1', 0, 3],
    ['usuario4', 'ejemplo1', 0, 4],
    ['usuario5', 'ejemplo1', 0, 5],
    ['usuario6', 'ejemplo1', 0, 6],
    ['usuario7','ejemplo1', 0, 7],
    ['usuario8','ejemplo1', 0, 8],
    ['usuario9', 'ejemplo1',0, 9],
    ['usuario10', 'ejemplo1',0, 10],
];

foreach ($usuarios as $usuario) {
    $username = $usuario[0];
    $password = password_hash($usuario[1], PASSWORD_DEFAULT);  // Cifra la contraseña
    $admin = $usuario[2];
    $userID = $usuario[3];  // Obtener ID del usuario en lugar del correo electrónico

    // Prepara la consulta SQL para insertar los datos en la tabla DatosAcceso
    $sql = "INSERT INTO DatosAcceso (ID_usuario, Contrasena, Administrador, FechaLogin) VALUES
    ('$userID', '$password', $admin, NOW())";

    // Ejecuta la consulta y maneja errores
    $conexion->query($sql) or die("Error al insertar en DatosAcceso: " . $conexion->error);
}

    // Insertar datos en Contenidos
    $conexion->query("INSERT INTO Contenidos (Titulo, Descripcion, ID_Producto) VALUES
                        ('Titulo1', 'Descripcion del contenido 1', 1),
                        ('Titulo2', 'Descripcion del contenido 2', 2),
                        ('Titulo3', 'Descripcion del contenido 3', 3),
                        ('Titulo4', 'Descripcion del contenido 4', 4),
                        ('Titulo5', 'Descripcion del contenido 5', 5),
                        ('Titulo6', 'Descripcion del contenido 6', 6),
                        ('Titulo7', 'Descripcion del contenido 7', 7),
                        ('Titulo8', 'Descripcion del contenido 8', 8),
                        ('Titulo9', 'Descripcion del contenido 9', 9),
                        ('Titulo10', 'Descripcion del contenido 10', 10)");

    // Insertar datos en Compra
    $conexion->query("INSERT INTO Compra (FechaHora, Confirmacion, ID_usuario, ID_Producto) VALUES
                        (NOW(), 1, 1, 1),
                        (NOW(), 0, 2, 2),
                        (NOW(), 1, 3, 3),
                        (NOW(), 0, 4, 4),
                        (NOW(), 1, 5, 5),
                        (NOW(), 0, 6, 6),
                        (NOW(), 1, 7, 7),
                        (NOW(), 0, 8, 8),
                        (NOW(), 1, 9, 9),
                        (NOW(), 0, 10, 10)");

    // Insertar datos en Testimonios
    $conexion->query("INSERT INTO Testimonios (Nombre, Descripcion, Foto, ID_Producto) VALUES
                        ('Testigo1', 'Gran producto', 'testimonio1.jpg', 1),
                        ('Testigo2', 'Excelente calidad', 'testimonio2.jpg', 2),
                        ('Testigo3', 'Muy recomendable', 'testimonio3.jpg', 3),
                        ('Testigo4', 'Satisfecho con la compra', 'testimonio4.jpg', 4),
                        ('Testigo5', 'Buen servicio', 'testimonio5.jpg', 5),
                        ('Testigo6', 'Me encantó', 'testimonio6.jpg', 6),
                        ('Testigo7', 'No cumplió mis expectativas', 'testimonio7.jpg', 7),
                        ('Testigo8', 'Volvería a comprar', 'testimonio8.jpg', 8),
                        ('Testigo9', 'El mejor del mercado', 'testimonio9.jpg', 9),
                        ('Testigo10', 'Pudo ser mejor', 'testimonio10.jpg', 10)");

    // Insertar datos en ArchivosUsuarios
    $conexion->query("INSERT INTO ArchivosUsuarios (Ruta, Descripcion, ID_usuario) VALUES
                        ('ruta/archivo1.pdf', 'Manual del usuario 1', 1),
                        ('ruta/EstadisticasExcel.csv', 'Estadisticas Excel', 1),
                        ('ruta/archivo2.pdf', 'Manual del usuario 2', 2),
                        ('ruta/archivo3.pdf', 'Manual del usuario 3', 3),
                        ('ruta/archivo4.pdf', 'Manual del usuario 4', 4),
                        ('ruta/archivo5.pdf', 'Manual del usuario 5', 5),
                        ('ruta/archivo6.pdf', 'Manual del usuario 6', 6),
                        ('ruta/archivo7.pdf', 'Manual del usuario 7', 7),
                        ('ruta/archivo8.pdf', 'Manual del usuario 8', 8),
                        ('ruta/archivo9.pdf', 'Manual del usuario 9', 9),
                        ('ruta/archivo10.pdf', 'Manual del usuario 10', 10)");
}
//Llamamos a la función para insetar los datos
insertarDatos($conexion);

// Cerrar conexión
$conexion->close();