<?php
require_once("conecta.php");

// Obtener conexión a la base de datos desde el archivo conecta.php
$conexion = getConexion();

// Función para crear las tablas y la base de datos
function crearTablas($conexion)
{
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
            Descripcion VARCHAR(255),
            LinkedIn VARCHAR(255),
            Video VARCHAR(255),
            General VARCHAR(255),
            Foto VARCHAR(255)
        )",
        "atributos" => "CREATE TABLE IF NOT EXISTS Atributos (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            Nombre VARCHAR(255)
        )",
        "contenido_multimedia" => "CREATE TABLE IF NOT EXISTS ContenidoMultimedia (
                    ID INT AUTO_INCREMENT PRIMARY KEY,
                    Tipo ENUM('video_local', 'foto', 'video_youtube') NOT NULL,
                    URL_Local VARCHAR(255),
                    URL_Youtube VARCHAR(255),
                    Descripcion TEXT
        )",

        "galerias" => "CREATE TABLE IF NOT EXISTS Galerias (
                    ID INT AUTO_INCREMENT PRIMARY KEY,
                    Nombre_galeria VARCHAR(255) NOT NULL
                )",

        "galeria_contenido" => "CREATE TABLE IF NOT EXISTS GaleriaContenido (
                    ID INT AUTO_INCREMENT PRIMARY KEY,
                    ID_Galeria INT,
                    ID_Contenido INT,
                    Orden INT,
                    FOREIGN KEY (ID_Galeria) REFERENCES Galerias(ID),
                    FOREIGN KEY (ID_Contenido) REFERENCES ContenidoMultimedia(ID)
                )",
        "productos" => "CREATE TABLE IF NOT EXISTS Productos (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            Nombre VARCHAR(255),
            DescripcionCorta TEXT,
            Descripcion TEXT,
            Categorias VARCHAR(255),
            Foto VARCHAR(255),
            FotoFondo VARCHAR(255),
            Precio VARCHAR(255),
            Adquirible BOOLEAN,
            Duracion VARCHAR(255),
            Modalidad VARCHAR(255),
            txtLibre VARCHAR(255),
            Id_galeria INT,
            FOREIGN KEY (Id_galeria) REFERENCES Galerias(ID)
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
            Subtitulo VARCHAR(255),
            Descripcion TEXT,
            Foto VARCHAR(255),
            ID_Producto INT,
            FOREIGN KEY (ID_Producto) REFERENCES Productos(ID)
        )",
        "archivos_usuarios" => "CREATE TABLE IF NOT EXISTS ArchivosUsuarios (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            Ruta VARCHAR(255),
            Descripcion TEXT,
            Fecha DATE,
            Deshabilitado INT,
            ID_Producto INT,
            ID_usuario INT,
            FOREIGN KEY (ID_Producto) REFERENCES Productos(ID),
            FOREIGN KEY (ID_usuario) REFERENCES Usuarios(ID)
        )",

        "faqs" => "CREATE TABLE IF NOT EXISTS faqs (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            Pregunta VARCHAR(255),
            Respuesta VARCHAR(255),
            ID_Producto INT,
            FOREIGN KEY (ID_Producto) REFERENCES Productos(ID)
            )",

        "ProductoCoaches" => "CREATE TABLE IF NOT EXISTS ProductoCoaches (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            ID_Producto INT,
            ID_Coach INT,
            FOREIGN KEY (ID_Producto) REFERENCES Productos(ID),
            FOREIGN KEY (ID_Coach) REFERENCES Coaches(ID)
        )",

        "ProductoAtributos" => "CREATE TABLE IF NOT EXISTS ProductoAtributos (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            ID_Producto INT,
            ID_Atributo INT,
            FOREIGN KEY (ID_Producto) REFERENCES Productos(ID),
            FOREIGN KEY (ID_Atributo) REFERENCES Atributos(ID)
        )",

        "Sesiones" =>"CREATE TABLE IF NOT EXISTS Sesiones (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            ID_usuario INT,
            FechaInicio DATETIME,
            FechaFin DATETIME,
            UltimoLatido DATETIME,
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

    // Insertar datos en Usuarios. La función CURDATE() en sql devuelve la fecha actual 
    $conexion->query("INSERT INTO Usuarios (Nombre, Apellidos, Correo_electronico, Numero_telefono, Organizacion, Foto, Fecha_Registro) VALUES
                      ('Nombre1', 'Apellido1', 'email1@example.com', '1234567890', 'Organización 1', '../src/archivos/perfil/fotosPerfil/foto1.jpg', CURDATE()),
                      ('Nombre2', 'Apellido2', 'admin@admin.com', '1234567891', 'Organización 1', 'foto2Usuario.jpg', CURDATE()),
                      ('Nombre3', 'Apellido3', 'email3@example.com', '1234567892', 'Organización 1', 'foto3Usuario.jpg', CURDATE()),
                      ('Nombre4', 'Apellido4', 'email4@example.com', '1234567893', 'Organización 1', 'foto4Usuario.jpg', CURDATE()),
                      ('Nombre5', 'Apellido5', 'email5@example.com', '1234567894','Organización 1', 'foto5Usuario.jpg', CURDATE()),
                      ('Nombre6', 'Apellido6', 'email6@example.com', '1234567895', 'Organización 1', 'foto6Usuario.jpg', CURDATE()),
                      ('Nombre7', 'Apellido7', 'email7@example.com', '1234567896', 'Organización 1', 'foto7Usuario.jpg', CURDATE()),
                      ('Nombre8', 'Apellido8', 'email8@example.com', '1234567897', 'Organización 1', 'foto8Usuario.jpg', CURDATE()),
                      ('Nombre9', 'Apellido9', 'email9@example.com', '1234567898', 'Organización 1', 'foto9Usuario.jpg', CURDATE()),
                      ('Nombre10', 'Apellido10', 'email10@example.com', '1234567899', 'Organización 1', 'foto10Usuario.jpg', CURDATE())");

    // Insertar datos en Coaches
    $conexion->query("INSERT INTO Coaches (Nombre, Apellidos, Titulacion, Descripcion, LinkedIn, Video, General, Foto) VALUES
                        ('Javier', 'Ontiveros', 'Fundador y CEO de Quid Qualitas', 'Con más de 20 años en puestos directivos en TI y consultoría, ha trabajado en Xerox, Borland y Lotus. Es director y profesor en programas ejecutivos y másteres en varias universidades. Diseñó programas de liderazgo femenino y es coach ejecutivo para directivos.', 'https://www.linkedin.com/', 'https://www.youtube.com/', 'https://www.google.es/', './archivos/coaches/FotoCoach4.png'),
                        ('Coach2', 'Apellido2', 'Titulacion2',  'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'https://www.linkedin.com/', 'https://www.youtube.com/', 'https://www.google.es/', './archivos/coaches/FotoCoach2.png'),
                        ('Coach3', 'Apellido3', 'Titulacion3',  'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'https://www.linkedin.com/', 'https://www.youtube.com/', 'https://www.google.es/', './archivos/coaches/FotoCoach3.png'),
                        ('Coach4', 'Apellido4', 'Titulacion4', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'https://www.linkedin.com/', 'https://www.youtube.com/', 'https://www.google.es/', './archivos/coaches/FotoCoach4.png'),
                        ('Coach5', 'Apellido5', 'Titulacion5',  'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'https://www.linkedin.com/', 'https://www.youtube.com/', 'https://www.google.es/', './archivos/coaches/FotoCoach1.png'),
                        ('Coach6', 'Apellido6', 'Titulacion6','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'https://www.linkedin.com/', 'https://www.youtube.com/', 'https://www.google.es/', './archivos/coaches/FotoCoach2.png'),
                        ('Coach7', 'Apellido7', 'Titulacion7', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'https://www.linkedin.com/', 'https://www.youtube.com/', 'https://www.google.es/', './archivos/coaches/FotoCoach3.png'),
                        ('Coach8', 'Apellido8', 'Titulacion8', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'https://www.linkedin.com/', 'https://www.youtube.com/', 'https://www.google.es/', './archivos/coaches/FotoCoach4.png'),
                        ('Coach9', 'Apellido9', 'Titulacion9', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'https://www.linkedin.com/', 'https://www.youtube.com/', 'https://www.google.es/', './archivos/coaches/FotoCoach1.png'),
                        ('Coach10', 'Apellido10', 'Titulacion10',  'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'https://www.linkedin.com/', 'https://www.youtube.com/', 'https://www.google.es/', './archivos/coaches/FotoCoach2.png')");

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
    // Insertar datos en carruselMultimedia
    $conexion->query("INSERT INTO ContenidoMultimedia (Tipo, URL_Local, URL_Youtube, Descripcion) VALUES
        ('foto', './archivos/galerias/Foto1.jpg', NULL, 'Foto de Julia y Javier en el sofá'),
        ('foto', './archivos/galerias/Foto2.jpg', NULL, 'Foto de Javier Ontiveros'),
        ('video_youtube', NULL, 'https://www.youtube.com/watch?v=9cAUjEHHhxs&pp=ygUMcXVpZHF1YWxpdGFz', 'Video de YouTube'),
        ('foto', './archivos/galerias/Foto3.jpg', NULL, 'Foto de Ejemplo 3'),
        ('foto', './archivos/galerias/Foto4.jpg', NULL, 'Foto de EJemplo 4'),
        ('video_local', './archivos/galerias/Video1.mp4', NULL, 'Video de ejemplo 1')
    ");

    // Insertar contenido en la tabla Galerias
    $conexion->query("INSERT INTO Galerias (Nombre_galeria) VALUES
        ('Galería 1'),
        ('Galería 2')
    ");

    // Insertar contenido en la tabla GaleriaContenido
    $conexion->query("INSERT INTO GaleriaContenido (ID_Galeria, ID_Contenido, Orden) VALUES
        (1, 1, 1),
        (1, 2, 2),
        (1, 3, 3),
        (1, 4, 4),
        (1, 5, 5),
        (2, 6, 1),
        (2, 1, 2),
        (2, 2, 3),
        (2, 3, 4)
    ");

$conexion->query("INSERT INTO Productos (Nombre, DescripcionCorta, Descripcion, Categorias, Foto, FotoFondo, Precio, Adquirible, Duracion, Modalidad, txtLibre, Id_galeria) VALUES
('Leadership Experiences - Golf', 'Participa en una experiencia de liderazgo y trabajo en equipo en el golf. Juega en la modalidad scrambel con equipos de 4, enfrentando situaciones similares al entorno laboral.', 'Experimenta situaciones similares al entorno profesional en el campo de golf. Participa en una modalidad modificada (scrambel) donde equipos de 4 integrantes vivirán una experiencia única para comprender las claves del liderazgo eficaz y el trabajo en equipo.', 'Categoria2', './archivos/servicios/fotoGolf.jpg','./archivos/servicios/fondoGolf.jpg', '(200-500€)', 0, '1 o 2 días', 'Presencial', 'Equipos de 25 a 50 personas', 1),
('Leadership Experiences - Orquesta', 'Únete a una orquesta en una convención y refuerza el liderazgo, la cohesión y el sentido de pertenencia. Una oportunidad única para conectar y alinear a tu equipo.', 'Conviértete en parte de una orquesta en una reunión o convención y vive una experiencia inolvidable que refuerza los conceptos de liderazgo eficaz, cohesión y sentido de pertenencia a la empresa. Una oportunidad única para conectar y alinear a tu equipo.', 'Categoria3', './archivos/servicios/fotoOrquesta.jpg', './archivos/servicios/fondoOrquesta.jpg', '(200-500€)', 0, '1 a 3 horas', 'Presencial', 'Grupos de 20 a 1200 personas', 1),
('Leadership Experiences - Mural', 'Crea un mural colectivo para fortalecer la cohesión y colaboración del equipo. Sin habilidades artísticas, cada aporte simboliza la importancia del éxito grupal.', 'Participa en la creación de un mural colectivo que fortalece la cohesión y colaboración del equipo. Sin necesidad de habilidades artísticas individuales, juntos se crea una impresionante obra de arte común, simbolizando la importancia de cada aportación en el éxito del grupo.', 'Categoria3', './archivos/servicios/fotoMural.jpg', './archivos/servicios/fondoMural.jpg', '(200-500€)', 0, '3 a 8 horas', 'Presencial', 'Grupos de mínimo 50 personas', 1),
('Leadership Experiences - Vela', 'Disfruta una aventura en la Ría de Sada a bordo del BAVARIA 50 Cruiser. Sin experiencia previa en navegación, esta actividad fortalece la cohesión y habilidades de liderazgo de tu equipo.', 'Experimenta una aventura única en la Ría de Sada (A Coruña) a bordo del impresionante barco crucero BAVARIA 50 Cruiser. Esta experiencia de liderazgo y trabajo en equipo, accesible para todos sin necesidad de experiencia previa en navegación, fortalecerá la cohesión y las habilidades de tu equipo.', 'Categoria4', './archivos/servicios/fotoVela.jpg', './archivos/servicios/fondoVela.jpg', '(200-500€)', 0, '1 o 2 días', 'Presencial', 'Grupos de hasta 12 personas', 1),
('Conecta con tu Comunidad con QQVoice','Con QQVoice, conoce las emociones de tus clientes y empleados. Usa un dashboard personalizado para impulsar tu marca y diferenciarte de la competencia.','Toma decisiones acertadas al conocer las emociones de tus clientes y empleados. Mantén siempre a mano tu dashboard personalizado de resultados. Impulsa la experiencia de tu marca y diferénciate de la competencia con QQVoice. Guarda en tu bolsillo las emociones y opiniones que importan para tu negocio.', 'Categoria5', './archivos/servicios/fotoVoice.jpg','./archivos/servicios/fondoVoice.jpg','1500€', 1,'', '','Incluye: Diseño único y personalizado de Cuestionario y Dashboard. Feedback.',1 ),
('El Viaje a la Omnicanalidad','Transforma la experiencia de tus clientes. Nuestro taller en omnicanalidad mejora la coherencia en atención al cliente e integra todos tus canales eficazmente.','¿Estás listo para transformar la experiencia de tus clientes en todos los canales? Nuestro taller de diagnóstico y asesoramiento en omnicanalidad te ayudará a evaluar y mejorar la coherencia y consistencia de la atención al cliente en tu empresa. Descubre en qué fase de la transformación digital te encuentras y aprende a integrar todos tus canales de manera efectiva.', 'Categoria6','./archivos/servicios/fotoViaje.jpg','./archivos/servicios/fondoViaje.jpg','(200-500€)', 0, '2 horas', 'Presencial u online', 'Individuos o equipos (ideal para equipos transversales de hasta 10 personas dentro de la organización)',1),
('Optimiza el Camino del Cliente con nuestro Taller de Customer Journey','Comprende y mejora cada etapa del camino del cliente. Adapta expectativas a las tendencias digitales, aumentando satisfacción y lealtad. Diferénciate de la competencia.','Comprender profundamente cada etapa del camino del cliente, mejorar su experiencia en cada punto de contacto. En el contexto actual de digitalización, es crucial adaptar las expectativas y comportamientos de los usuarios a las nuevas tendencias. Aumenta la satisfacción y lealtad del cliente, diferenciándote de la competencia.','Categoria6','./archivos/servicios/fotoCoustomer.jpg','./archivos/servicios/fondoCoustomer.jpg','(200-500€)',0,'2 a 4 horas','Presnecial u online',' Individuos o equipos (ideal para equipos transversales de al menos 4-5 personas).',1),
('Impulsa tus Competencias como líder. Neuroliderazgo',' Potencia tu liderazgo y gestión emocional con nuestro programa de Neuroliderazgo. Mejora tu desempeño, equilibrio personal, creatividad, colaboración y gestión de la diversidad en tu equipo.','¿Estás listo para potenciar tus habilidades de liderazgo y gestión emocional? Nuestro programa completo de Neuroliderazgo está diseñado para ayudarte a alcanzar un desempeño óptimo en tus funciones y mejorar tu equilibrio personal. Descubre cómo impulsar tu creatividad, fomentar la colaboración y gestionar la diversidad en tu equipo.','Categoria7','./archivos/servicios/fotoNeurolider.jpg','./archivos/servicios/fondoNeurolider.jpg','(200-500€)',0,'6 sesiones 1 to 1 incluidas. Opción de ampliación a demanda.','Presnecial u online','',1)
");



    $usuarios = [
        ['adminJavier', 'ejemplo1', 0, 1],
        ['usuario2', 'ejemplo1', 1, 2],
        ['usuario3', 'ejemplo1', 0, 3],
        ['usuario4', 'ejemplo1', 0, 4],
        ['usuario5', 'ejemplo1', 0, 5],
        ['usuario6', 'ejemplo1', 0, 6],
        ['usuario7', 'ejemplo1', 0, 7],
        ['usuario8', 'ejemplo1', 0, 8],
        ['usuario9', 'ejemplo1', 0, 9],
        ['usuario10', 'ejemplo1', 0, 10],
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
    
    ('Formación de Equipos Diversos', 
        '<ul>
            <li>Equipos de 4 personas con y sin experiencia en golf, asegurando diversidad y oportunidades de aprendizaje.</li>
        </ul>', 1),
    ('Modalidad Scramble', 
        '<ul>
            <li>Una dinámica de juego donde la cooperación y la estrategia en equipo son fundamentales para el éxito.</li>
        </ul>', 1),
    ('Desarrollo de Habilidades', 
        '<ul>
            <li>Enfrenta desafíos que mejorarán tus capacidades de liderazgo y trabajo en equipo, reflejando situaciones reales del entorno laboral.</li>
        </ul>', 1),
    ('Duración del Programa', 
        '<ul>
            <li>Opciones de uno o dos días completos de actividades intensivas y enriquecedoras.</li>
        </ul>', 1),
    ('Reflexión y Feedback', 
        '<ul>
            <li>Sesiones de retroalimentación para analizar el desempeño y extraer aprendizajes aplicables al entorno profesional.</li>
        </ul>', 1),

    ('Formación de la Orquesta', 
        '<ul>
            <li>Todos los participantes, sin importar su experiencia musical, se integran en una orquesta, promoviendo la inclusión y el trabajo en equipo.</li>
        </ul>', 2),
    ('Ensayos Guiados', 
        '<ul>
            <li>Bajo la dirección del renombrado director del Conservatorio Profesional de Arturo Soria de Madrid, los participantes ensayarán con diferentes instrumentos, creando una sinfonía única.</li>
        </ul>', 2),
    ('Descubrimiento de Instrumentos', 
        '<ul>
            <li>Los instrumentos serán descubiertos de manera inesperada, fomentando la adaptabilidad y la creatividad entre los miembros del equipo.</li>
        </ul>', 2),
    ('Duración del Programa', 
        '<ul>
            <li>Opciones flexibles de una a tres horas, adaptadas a las necesidades de tu evento o convención.</li>
        </ul>', 2),
    ('Presentación Final', 
        '<ul>
            <li>Culmina con una presentación en la que todos los participantes tocan juntos, simbolizando la armonía y la colaboración en el entorno profesional.</li>
        </ul>', 2),

    ('Introducción a la Actividad', 
        '<ul>
            <li>Presentación del proyecto y objetivos del mural colectivo, destacando la importancia de la colaboración.</li>
        </ul>', 3),
    ('Asignación de Roles y Materiales', 
        '<ul>
            <li>Distribución de materiales y roles dentro del equipo, asegurando que todos participen activamente.</li>
        </ul>', 3),
    ('Creación del Mural', 
        '<ul>
            <li>Proceso guiado donde cada participante contribuye con su trazo, reflejando la diversidad y unidad del equipo.</li>
        </ul>', 3),
    ('Reflexión y Feedback', 
        '<ul>
            <li>Sesión de retroalimentación para discutir el proceso, los retos superados y las lecciones aprendidas sobre trabajo en equipo y liderazgo.</li>
        </ul>', 3),
    ('Exposición del Mural', 
        '<ul>
            <li>Exhibición de la obra final, celebrando el esfuerzo colectivo y fortaleciendo el sentido de pertenencia al equipo.</li>
        </ul>', 3),

    ('Bienvenida y Briefing Inicial', 
        '<ul>
            <li>Introducción a la actividad, objetivos del taller y normas de seguridad a bordo.</li>
        </ul>', 4),
    ('Formación de Equipos', 
        '<ul>
            <li>Distribución de roles y responsabilidades entre los participantes para fomentar la colaboración.</li>
        </ul>', 4),
    ('Navegación y Maniobras', 
        '<ul>
            <li>Práctica de técnicas de navegación y maniobras bajo la guía de un capitán experimentado, promoviendo el liderazgo y la toma de decisiones.</li>
        </ul>', 4),
    ('Desafíos en el Mar', 
        '<ul>
            <li>Superación de retos y situaciones imprevistas que simulan escenarios reales del entorno profesional, fomentando la adaptabilidad y el pensamiento crítico.</li>
        </ul>', 4),
    ('Reflexión y Feedback', 
        '<ul>
            <li>Sesiones de retroalimentación para discutir las lecciones aprendidas y cómo aplicarlas en el trabajo diario.</li>
        </ul>', 4),
    ('Duración del Programa', 
        '<ul>
            <li>Opciones flexibles de uno o dos días, permitiendo una inmersión completa en la experiencia náutica.</li>
        </ul>', 4),

    ('Vista de Pájaro de la Situación', 
        '<ul>
            <li>Consultoría Inicial: Análisis del programa de voz actual (si lo hay).</li>
            <li>Identificación de Necesidades: Determinación de las necesidades de clientes y empleados.</li>
        </ul>', 5),
    ('Decisión del Plan de Voz', 
        '<ul>
            <li>Co-creación de Voz de Marca: Desarrollo de la voz de la marca en colaboración.</li>
            <li>Co-creación de Preguntas: Diseño de preguntas alineadas con el estilo y valores de la marca.</li>
        </ul>', 5),
    ('Diseño de Producto Digital', 
        '<ul>
            <li>Imagen de Marca Destacada: Diseño que resalta la imagen de tu marca y te diferencia de herramientas estándar como Google Forms.</li>
            <li>Dashboard Personalizado: Todos los KPIs a la vista, con posibilidad de integración con otros KPIs de negocio.</li>
            <li>Alertas de Resultados: Notificaciones en tiempo real sobre los resultados obtenidos.</li>
        </ul>', 5),
    ('Feedback y Cierre del Ciclo', 
        '<ul>
            <li>Reporte Periódico: Análisis de datos y recomendaciones para la mejora continua mediante decisiones informadas.</li>
        </ul>', 5),

    ('Entendiendo el Customer Journey Digital', 
        '<ul>
            <li>Mapeo del recorrido del cliente a través de los canales digitales.</li>
            <li>Identificación de puntos de contacto clave y áreas de mejora.</li>
        </ul>', 6),
    ('El Viaje hacia la Omnicanalidad', 
        '<ul>
            <li>Diferencias entre multicanalidad y omnicanalidad.</li>
            <li>Estrategias para una integración efectiva de todos los canales.</li>
        </ul>', 6),
    ('Descubriendo Canales de Atención al Cliente', 
        '<ul>
            <li>Análisis de los canales de atención al cliente en tu empresa y sector.</li>
            <li>Evaluación de la efectividad y coherencia de cada canal.</li>
        </ul>', 6),
    ('Diagnóstico de Integración de Canales', 
        '<ul>
            <li>Evaluación personalizada de la integración de canales en tu empresa.</li>
            <li>Identificación de brechas y oportunidades para mejorar la experiencia del cliente.</li>
        </ul>', 6),
    ('La Meta es la Omnicanalidad', 
        '<ul>
            <li>Desarrollo de un plan de acción para alcanzar la omnicanalidad.</li>
            <li>Implementación de mejores prácticas y seguimiento de resultados.</li>
        </ul>', 6),

    ('Definición del Público Objetivo', 
        '<ul>
            <li>Mapa de Empatía: Identificación detallada de las necesidades, deseos y comportamientos de tus clientes.</li>
        </ul>', 7),
    ('Identificación de las Etapas del Ciclo de Vida del Cliente', 
        '<ul>
            <li>Antes, Durante y Después: Análisis exhaustivo de cada fase del customer journey.</li>
        </ul>', 7),
    ('Descripción y Detalle de Cada Etapa', 
        '<ul>
            <li>Facilidad y Fluidez: Evaluación de la experiencia del cliente en cada punto de contacto.</li>
        </ul>', 7),
    ('Detección de los “Momentos de Dolor”', 
        '<ul>
            <li>Identificación de Problemas: Localización de las fricciones y problemas que enfrentan los clientes.</li>
        </ul>', 7),
    ('Detección de Oportunidades y Acciones de Mejora', 
        '<ul>
            <li>Mejoras y Soluciones: Desarrollo de estrategias para optimizar la experiencia del cliente y mejorar su satisfacción.</li>
        </ul>', 7),
        
        ('Sesión Previa Programa Coaching', 
        '<ul>
            <li>Definición clara de lo que es y no es coaching.</li>
            <li>Evaluación del momento actual del líder/coachee y sus objetivos de cambio.</li>
            <li>Planteamiento de un camino evolutivo hacia la autonomía, despliegue de talento y desarrollo directivo.</li>
        </ul>', 8),
    ('Situación Actual Líder. Evaluación 360º', 
        '<ul>
            <li>Evaluación de competencias según el modelo i4 de neuroliderazgo.</li>
            <li>Comprensión de la visión personal del presente y definición clara del rol profesional actual.</li>
            <li>Identificación de necesidades de cambio, áreas de desarrollo y fortalezas.</li>
        </ul>', 8),
    ('Toma de Conciencia y Definición del Cambio', 
        '<ul>
            <li>Análisis detallado de competencias (propias y externas).</li>
            <li>Identificación de fortalezas, debilidades y puntos ciegos.</li>
            <li>Definición de la visión deseada, metas y objetivos.</li>
            <li>Elaboración de un Plan de Mejora Individual (PMI) y generación de nuevos compromisos de cambio.</li>
        </ul>', 8),
    ('Proceso de Desarrollo Individual', 
        '<ul>
            <li>Evaluación conjunta del impacto y resultados del programa.</li>
            <li>Revisión de la situación profesional actual y alcance de los objetivos definidos.</li>
            <li>Provisión de herramientas necesarias para el cambio.</li>
            <li>Identificación de avances, barreras y apoyos necesarios.</li>
        </ul>', 8),
    ('Sesión Final de Evaluación de Impacto', 
        '<ul>
            <li>Coach y Coachee evalúan el impacto y resultados del Programa de Coaching.</li>
            <li>Situación actual del entorno profesional y el alcance de los objetivos definidos.</li>
            <li>Planteamiento de los pasos a seguir después del coaching.</li>
        </ul>', 8)
    ");



    // Insertar datos en Compra
    $conexion->query("INSERT INTO Compra (FechaHora, Confirmacion, ID_usuario, ID_Producto) VALUES
                        (NOW(), 1, 1, 1),
                        (NOW(),1,1,2),
                        (NOW(),1,1,3),
                        (NOW(),1,1,3)");

    // Insertar datos en Testimonios
    $conexion->query("INSERT INTO Testimonios (Nombre, Subtitulo, Descripcion, Foto, ID_Producto) VALUES
        ('Mª Rosa León Mateo', 'Socia Fundadora', 'Mi proceso como coachee contigo fue una de las mejores decisiones de mi carrera profesional. Este aprendizaje sigue vivo en mí, ayudándome a sacar mi mejor versión diariamente. Lo único que siento es no haberlo hecho antes... Si la forma de ejercer el liderazgo es siempre la base para obtener los mejores resultados, tener al equipo motivado, en definitiva tener el mejor retorno de nuestras acciones profesionales, en el periodo que estamos viviendo es fundamental. Mi proceso de aprendizaje como tu cochee sigue vivo y presente en mi, todos los días y procuro ejercerlo a diario, sacando mi mejor versión en este periodo tan complicado que estamos viviendo, con la satisfacción que eso supone.', './archivos/foto1.jpg', 8),
        ('Beatriz Achaques', 'CEO & Founder', 'Javier tiene un don para ayudar a los demás. Me ha brindado herramientas que me permiten crecer de manera independiente. Solo tengo palabras de agradecimiento. Es una bellísima persona y un profesional HUMANO. y una sabiduría infinita. Es una de esas personas a las que acudir en momentos claves de tu vida. Sabe escuchar, leer a las personas y sembrar la semilla de crecimiento en el coachee para que una vez acabado el proceso sea uno mismo el que con las herramientas conseguidas en el proceso, pueda hacerla crecer de forma independiente. Me ha ayudado a alzar el vuelo. Solo tengo palabras de agradecimiento y gratitud hacia Javier.', './archivos/foto1.jpg', 1),
        ('Ramón Fco. Pérez Ruiz', 'Senior National Manager', 'Javier fue una influencia muy positiva en nuestro desarrollo profesional. GRACIAS', './archivos/foto1.jpg', 8),
        ('María González', 'Directora de Recursos Humanos', 'Esta experiencia en el campo de golf fue reveladora. No solo aprendimos sobre liderazgo y trabajo en equipo, sino que también nos divertimos y fortalecimos nuestras relaciones profesionales.', './archivos/foto1.jpg', 1),
        ('Carlos Pérez', 'Gerente de Ventas', 'El taller de golf nos enseñó a trabajar juntos de manera más efectiva. Fue una experiencia única que transformó la forma en que nos comunicamos y colaboramos en la oficina.', './archivos/foto1.jpg', 1),
        ('Ana López', 'Directora de Recursos Humanos', 'Formar parte de la orquesta fue una experiencia transformadora para nuestro equipo. Nos ayudó a entender mejor la importancia de la cohesión y el liderazgo en nuestras actividades diarias.', './archivos/foto1.jpg', 2),
        ('Miguel Torres', 'Gerente de Proyectos', 'La actividad de la orquesta nos permitió ver cómo cada uno de nosotros juega un papel crucial en el éxito del equipo. Fue una experiencia emocionante y muy enriquecedora.', './archivos/foto1.jpg', 2),
        ('Laura Sánchez', 'Gerente de Recursos Humanos', 'La creación del mural fue una experiencia increíble. Ver cómo cada pequeño trazo contribuía a una obra maestra colectiva nos enseñó el verdadero poder del trabajo en equipo.', './archivos/foto1.jpg', 3),
        ('Fernando Gómez', 'Director de Proyectos', 'Esta actividad nos permitió ver el valor de cada individuo en el equipo. Fue una experiencia transformadora que reforzó nuestra cohesión y colaboración.', './archivos/foto1.jpg', 3),
        ('Carlos Martínez', 'CEO de Tech Solutions', 'La experiencia en el BAVARIA 50 Cruiser fue increíble. Aprendimos a trabajar juntos en situaciones desafiantes y mejoramos nuestra comunicación y liderazgo.', './archivos/foto1.jpg', 4),
        ('Laura Fernández', 'Directora de Marketing', 'Este taller de vela nos enseñó el valor de cada miembro del equipo. Fue una actividad intensa y muy enriquecedora que nos unió más como grupo.', './archivos/foto1.jpg', 4),
        ('María Auro', 'Cliente', 'QQVoice ha transformado la manera en que entendemos a nuestros clientes. La información detallada y las alertas en tiempo real nos permiten tomar decisiones rápidas y efectivas.', './archivos/foto1.jpg', 5),
        ('Gerardo Auro', 'Cliente', 'El equipo de QQ nos ha ayudado a crear una voz de marca auténtica y resonante. El dashboard personalizado es una herramienta imprescindible para nuestro equipo.', './archivos/foto1.jpg', 5),
        ('Jorge Loza', 'Cliente', 'Gracias a QQVoice, hemos podido mejorar significativamente la experiencia de nuestros empleados y clientes. Los resultados hablan por sí solos.', './archivos/foto1.jpg', 5),
        ('María', 'Librería El Buen Libro', 'El taller de omnicanalidad nos abrió los ojos sobre cómo mejorar la coherencia en la atención al cliente. Ahora nuestros clientes tienen una experiencia uniforme en todos los canales.', './archivos/foto1.jpg', 6),
        ('Juan Carlos', 'Consultoría Web', 'Gracias a QQ Digital, hemos integrado todos nuestros canales de atención, lo que ha mejorado significativamente la satisfacción de nuestros clientes.', './archivos/foto1.jpg', 6),
        ('Gabriela', 'Asociación Cultural', 'La asesoría en omnicanalidad nos permitió identificar y solucionar inconsistencias en nuestros canales de comunicación, logrando una atención más efectiva.', './archivos/foto1.jpg', 6),
        ('María', 'Librería El Buen Libro', 'El taller de Customer Journey nos permitió entender mejor a nuestros clientes y mejorar significativamente cada punto de contacto. Ahora ofrecemos una experiencia mucho más fluida y satisfactoria.', './archivos/foto1.jpg', 7),
        ('Juan Carlos', 'Consultoría Web', 'Gracias a QQ Digital, hemos identificado y solucionado los puntos de dolor de nuestros clientes, lo que ha mejorado nuestra retención y satisfacción del cliente.', './archivos/foto1.jpg', 7),
        ('Gabriela', 'Asociación Cultural', 'La asesoría en Customer Journey nos ayudó a detectar oportunidades de mejora en nuestra comunicación y servicio, aumentando la satisfacción de nuestros miembros.', './archivos/foto1.jpg', 7)
    ");


    // Insertar datos en ArchivosUsuarios
    $conexion->query("INSERT INTO ArchivosUsuarios (Ruta, Descripcion, Fecha, Deshabilitado, ID_Producto, ID_usuario) VALUES
                        ('./archivos/archivosClientes/ejemplo1.pdf', 'Manual del usuario 1',NOW(), 0, 1, 1),
                        ('./archivos/archivosClientes/ejemplo1.pdf', 'Estadisticas Excel',NOW(),0,1, 1),
                        ('./archivos/archivosClientes/ejemplo1.pdf', 'Manual del usuario 2',NOW(),0,1, 2),
                        ('./archivos/archivosClientes/ejemplo1.pdf', 'Manual del usuario 3',NOW(),0,2, 3),
                        ('./archivos/archivosClientes/ejemplo1.pdf', 'Manual del usuario 4',NOW(),0,2, 4),
                        ('./archivos/archivosClientes/ejemplo1.pdf', 'Manual del usuario 5',NOW(),0,3, 5),
                        ('./archivos/archivosClientes/ejemplo1.pdf', 'Manual del usuario 6',NOW(),0,4, 6),
                        ('./archivos/archivosClientes/ejemplo1.pdf', 'Manual del usuario 7',NOW(),0,5, 7),
                        ('./archivos/archivosClientes/ejemplo1.pdf', 'Manual del usuario 8',NOW(),0,6, 8),
                        ('./archivos/archivosClientes/ejemplo1.pdf', 'Manual del usuario 9',NOW(),0,7, 9),
                        ('./archivos/archivosClientes/ejemplo1.pdf', 'Manual del usuario 10',NOW(),0,8, 10)");

    $conexion->query("INSERT INTO ProductoCoaches (ID_Producto, ID_Coach) VALUES
                        (1, 1),
                        (1, 2),
                        (1, 3),
                        (2, 1),
                        (3, 1),
                        (4, 1),
                        (5, 1),
                        (6, 1),
                        (7, 1),
                        (8, 1)");


    $conexion->query("INSERT INTO ProductoAtributos (ID_Producto, ID_Atributo) VALUES
                        (1, 1),
                        (1, 2),
                        (1, 3),
                        (2, 1),
                        (3, 1),
                        (4, 1),
                        (5, 1),
                        (6, 1),
                        (7, 1),
                        (8, 1)");





    // Insertar datos en faqs
    $conexion->query("INSERT INTO faqs (Pregunta, Respuesta, ID_Producto) VALUES
                        ('¿Qué es el coaching y cómo se diferencia de la terapia o la consultoría?', 'El coaching se centra en ayudarte a alcanzar objetivos específicos y desarrollar habilidades a través de un proceso de colaboración y guía. A diferencia de la terapia, que trata problemas emocionales profundos y del pasado, el coaching se enfoca en el presente y el futuro. La consultoría implica ofrecer soluciones específicas y consejos expertos, mientras que el coaching te ayuda a descubrir tus propias respuestas y estrategias.', 8),
                        ('¿Cómo se realiza la evaluación 360º en el programa de Neuroliderazgo?', 'La evaluación 360º en nuestro programa se basa en el modelo i4 de neuroliderazgo. Incluye retroalimentación de compañeros, superiores y subordinados para proporcionar una visión completa de tus competencias de liderazgo. Esto ayuda a identificar tus fortalezas, áreas de mejora y puntos ciegos.', 8),
                        ('¿Qué incluye el Plan de Mejora Individual (PMI)?', 'El PMI es un plan detallado que se elabora durante el programa y que incluye metas claras, objetivos específicos y estrategias personalizadas para mejorar tus competencias de liderazgo. Este plan es fundamental para guiar tu desarrollo y seguimiento continuo.', 8),
                        ('¿Cuánto tiempo tarda en ver resultados con el programa de coaching?', 'Los resultados varían según el individuo y sus objetivos específicos, pero generalmente los participantes empiezan a notar mejoras significativas en sus habilidades de liderazgo y gestión emocional dentro de los primeros 3 a 6 meses del programa.', 8),
                        ('¿Qué tipo de apoyo continuo está disponible durante y después del programa?', 'Durante el programa, tendrás acceso continuo a tu coach para orientación y seguimiento. Después de la finalización del programa, puedes optar por sesiones adicionales para mantener y seguir desarrollando tus habilidades.', 8),
                        ('¿Cómo se mide el impacto del programa de coaching?', 'El impacto se mide a través de una sesión final de evaluación donde el coach y el coachee revisan los resultados logrados en comparación con los objetivos establecidos al inicio del programa. También se realiza una reevaluación del entorno profesional y del alcance de los cambios implementados.', 8),
                        ('¿Qué es el modelo i4 de neuroliderazgo?', 'El modelo i4 de neuroliderazgo es un marco avanzado que evalúa y desarrolla competencias de liderazgo basándose en principios de neurociencia. Este modelo ayuda a entender y mejorar cómo piensas, te comportas y lideras, utilizando evaluaciones 360º y planes personalizados para fomentar el crecimiento y la efectividad.', 8),
                        ('¿El programa incluye formación en habilidades técnicas específicas?', 'El enfoque principal del programa es el desarrollo de competencias de liderazgo y gestión emocional. Sin embargo, también se proporcionan herramientas y recursos específicos según las necesidades individuales identificadas durante el proceso de coaching.', 8)");
}


//Llamamos a la función para insetar los datos
insertarDatos($conexion);


// Cerrar conexión
$conexion->close();