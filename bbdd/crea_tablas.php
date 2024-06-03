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

    $conexion->query("INSERT INTO Coaches (Nombre, Apellidos, Titulacion, Descripcion, LinkedIn, Video, General, Foto) VALUES
                    ('Javier', 'Ontiveros', 'Fundador y CEO de Quid Qualitas', 'Con más de 20 años en puestos directivos en TI y consultoría, ha trabajado en Xerox, Borland y Lotus. Es director y profesor en programas ejecutivos y másteres en varias universidades. Diseñó programas de liderazgo femenino y es coach ejecutivo para directivos.', 'https://www.linkedin.com/in/javierontiveros/', '', '', './archivos/coaches/JavierOntiveros.jpg'),
                    ('Equipo', 'QQ', 'Experiencia de Cliente y Empleado', 'Equipo de consultores, coach, diseñadores, desarrolladores multiplataforma y analistas de datos, apasionados por impulsar programas e iniciativas de Experiencia de Cliente. Nos ponemos en tu piel y te ayudamos a transformar la cultura de tu organización, alineando valores, desarrollando las competencias clave en los profesionales, directivos y equipos de tu organización.', 'https://www.linkedin.com/company/quid-qualitas/', 'https://www.youtube.com/@QQUALITAS', 'https://quidqualitas.es/', './archivos/coaches/equipoQQ.jpg'),
                    ('QQ', 'Voice', 'Servicio de Voz de Cliente y Empleado de Quid Qualitas', 'El equipo multidisciplinar de Quid Qualitas, compuesto por consultores expertos en CX, ingeniería de diseño y análisis de datos, lanza un nuevo servicio tras más de 15 años en el mundo del Customer Experience. Este servicio permitirá a las marcas ser más humanas en su escucha, diferenciándose de la competencia con poco esfuerzo.', 'https://www.linkedin.com/', 'https://www.youtube.com/', 'https://www.google.es/', './archivos/coaches/qqvoice.jpg'),
                    ('QQ', 'Digital', 'Automatización de Procesos, Digitalización y Tecnologías de Business Intelligence', 'Nuestro equipo, con más de 15 años en Customer Experience, está compuesto por expertos en CX, diseño e ingeniería de análisis de datos. En Quid Qualitas Digital, ayudamos a las marcas a humanizar su atención al cliente y destacarse, especializándonos en integración, deep learning y big data para la transformación digital.', 'https://www.linkedin.com/', 'https://www.youtube.com/', 'https://www.google.es/', './archivos/coaches/qqdigital.jpg'),
                    ('Patricia', 'R. Cima', 'Agile Coach Innovación y Coach Ejecutiva y de Equipos Coach Certificado modelo i4 de Neuroliderazgo', 'Licenciada en Periodismo y Experta en Protocolo (Universidad Pontificia Salamanca), con Masters en Gestión Empresarial y Coaching Ejecutivo (CORAOPS). Experiencia en Editoriales y Comunicación: Marketing Manager en Mutualidad de Arquitectos, Directora de Marketing en MC Lifestyle, y Responsable de Comunicación en Grupo Sanca. Consultora en Transformación Digital/Cultural, con más de 4,500 horas de formación. Profesora y mentora en EOI y Generation Spain McKinsey.', 'https://www.linkedin.com/', 'https://www.youtube.com/', 'https://www.google.es/', './archivos/coaches/patricia.jpg')
                    ");


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
        ('foto', './archivos/galerias/golf.jpg', NULL, 'foto del golf'),
        ('foto', './archivos/galerias/golfArbol.jpg', NULL, 'Golf desde un arbol'),
        ('video_youtube', NULL, 'https://www.youtube.com/watch?v=v4jIRWPBjzg&start=45', 'Video de YouTube comenzando en el segundo 45'),
        ('foto', './archivos/galerias/orquesta.jpg', NULL, 'orquesta'),
        ('video_youtube', NULL, 'https://www.youtube.com/watch?v=Dm5vWVSnTq0&t=2s', 'Video de YouTube comenzando en el segundo2'),
        ('video_youtube', NULL, 'https://youtu.be/_2ig94wp0h4?si=IzHwqYGKOXZaXrAJ&t=154', 'Video de YouTube comenzando en el segundo 154'),
        ('foto', './archivos/galerias/muralComun.jpg', NULL, 'mural foto de todos juntos'),
        ('foto', './archivos/galerias/muralQuiron.jpg', NULL, 'mural de quiron'),
        ('foto', './archivos/galerias/qqvoice.jpg', NULL, 'foto qqvoice'),
        ('foto', './archivos/galerias/movilqqvoice.jpg', NULL, 'foto movil qq voice'),
        ('video_youtube', NULL, 'https://youtu.be/z3wNPSZowGo?si=nXa4j_7-7VmAKdm4&t=21', 'Video de YouTube coustomer digital'),
        ('video_youtube', NULL, 'https://youtu.be/UDRn-2M7pJ0?si=JdsP7sXC9z3TMv8X&t=29', 'Video de YouTube neuroliderazgo')
    ");

    // Insertar contenido en la tabla Galerias
    $conexion->query("INSERT INTO Galerias (Nombre_galeria) VALUES
        ('QQExperience-Golf'),
        ('QQExperience-Orquesta'),
        ('QQExperience-Mural'),
        ('QQExperience-Vela'),
        ('QQExperience-QQVoice'),
        ('QQExperience-omnicanalidad'),    
        ('QQExperience-coustomerJourney'),
        ('QQExperience-neuroliderazgo')
        ");

    // Insertar contenido en la tabla GaleriaContenido
    $conexion->query("INSERT INTO GaleriaContenido (ID_Galeria, ID_Contenido, Orden) VALUES
        (1, 1, 1),
        (1, 2, 3),
        (1, 3, 2),

        (2, 4, 1),
        (2, 5, 2),

        (3, 6, 1),
        (3, 7, 2),
        (3, 8, 3),

        (4, 3, 1),

        (5, 9, 1),
        (5, 10, 2),

        (7, 11, 1),
        
        (8, 12, 1)


    ");

$conexion->query("INSERT INTO Productos (Nombre, DescripcionCorta, Descripcion, Categorias, Foto, FotoFondo, Precio, Adquirible, Duracion, Modalidad, txtLibre, Id_galeria) VALUES
('Leadership Experiences - Golf', 'Participa en una experiencia de liderazgo y trabajo en equipo en el golf. Juega en la modalidad scrambel con equipos de 4, enfrentando situaciones similares al entorno laboral.', 'Experimenta situaciones similares al entorno profesional en el campo de golf. Participa en una modalidad modificada (scrambel) donde equipos de 4 integrantes vivirán una experiencia única para comprender las claves del liderazgo eficaz y el trabajo en equipo.', 'Categoria2', './archivos/servicios/fotoGolf.jpg','./archivos/servicios/fondoGolf.jpg', '(200-500€)', 0, '1 o 2 días', 'Presencial', 'Equipos de 25 a 50 personas', 1),
('Leadership Experiences - Orquesta', 'Únete a una orquesta en una convención y refuerza el liderazgo, la cohesión y el sentido de pertenencia. Una oportunidad única para conectar y alinear a tu equipo.', 'Conviértete en parte de una orquesta en una reunión o convención y vive una experiencia inolvidable que refuerza los conceptos de liderazgo eficaz, cohesión y sentido de pertenencia a la empresa. Una oportunidad única para conectar y alinear a tu equipo.', 'Categoria3', './archivos/servicios/fotoOrquesta.jpg', './archivos/servicios/fondoOrquesta.jpg', '(200-500€)', 0, '1 a 3 horas', 'Presencial', 'Grupos de 20 a 1200 personas', 1),
('Leadership Experiences - Mural', 'Crea un mural colectivo para fortalecer la cohesión y colaboración del equipo. Sin habilidades artísticas, cada aporte simboliza la importancia del éxito grupal.', 'Participa en la creación de un mural colectivo que fortalece la cohesión y colaboración del equipo. Sin necesidad de habilidades artísticas individuales, juntos se crea una impresionante obra de arte común, simbolizando la importancia de cada aportación en el éxito del grupo.', 'Categoria3', './archivos/servicios/fotoMural.jpg', './archivos/servicios/fondoMural.jpg', '(200-500€)', 0, '3 a 8 horas', 'Presencial', 'Grupos de mínimo 50 personas', 1),
('Leadership Experiences - Vela', 'Disfruta una aventura en la Ría de Sada a bordo del BAVARIA 50 Cruiser. Sin experiencia previa en navegación, esta actividad fortalece la cohesión y habilidades de liderazgo de tu equipo.', 'Experimenta una aventura única en la Ría de Sada (A Coruña) a bordo del impresionante barco crucero BAVARIA 50 Cruiser. Esta experiencia de liderazgo y trabajo en equipo, accesible para todos sin necesidad de experiencia previa en navegación, fortalecerá la cohesión y las habilidades de tu equipo.', 'Categoria4', './archivos/servicios/fotoVela.jpg', './archivos/servicios/fondoVela.jpg', '(200-500€)', 0, '1 o 2 días', 'Presencial', 'Grupos de hasta 12 personas', 1),
('Conecta con tu Comunidad con QQVoice','Con QQVoice, conoce las emociones de tus clientes y empleados. Usa un dashboard personalizado para impulsar tu marca y diferenciarte de la competencia.','Toma decisiones acertadas al conocer las emociones de tus clientes y empleados. Mantén siempre a mano tu dashboard personalizado de resultados. Impulsa la experiencia de tu marca y diferénciate de la competencia con QQVoice. Guarda en tu bolsillo las emociones y opiniones que importan para tu negocio.', 'Categoria5', './archivos/servicios/fotoVoice.jpg','./archivos/servicios/fondoVoice.png','1500€', 1,'', '','Incluye: Diseño único y personalizado de Cuestionario y Dashboard. Feedback.',1 ),
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
                        (2, 1),
                        (2, 2),
                        (3, 1),
                        (3, 2),
                        (4, 1),
                        (4, 2),
                        (5, 3),
                        (6, 4),
                        (7, 4),
                        (8, 1),
                        (8, 5)");


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





$conexion->query("INSERT INTO faqs (Pregunta, Respuesta, ID_Producto) VALUES
    ('¿Cuáles son las competencias específicas de liderazgo y trabajo en equipo que se desarrollarán durante la experiencia?', 'El programa se enfoca en mejorar habilidades de liderazgo eficaz, comunicación, cooperación, resolución de problemas y trabajo en equipo a través de dinámicas en el campo de golf.', 1),
    ('¿Cómo se alinea esta experiencia con los objetivos estratégicos de nuestra compañía?', 'La experiencia está diseñada para reflejar situaciones reales del entorno laboral, promoviendo competencias clave que apoyan los objetivos estratégicos de liderazgo y trabajo en equipo de su compañía.', 1),
    ('¿Qué tipo de feedback y métricas se utilizan para medir el impacto de esta experiencia?', 'Se incluyen sesiones de retroalimentación detalladas, análisis de desempeño y encuestas de satisfacción para evaluar el impacto y aplicabilidad de los aprendizajes en el entorno profesional.', 1),
    ('¿Cómo se asegura que los aprendizajes se traduzcan en cambios concretos en el trabajo?', 'Las sesiones de reflexión y feedback están diseñadas para conectar directamente las experiencias del campo de golf con situaciones y desafíos específicos del entorno laboral.', 1),
    ('¿Qué tipo de apoyo está disponible si tengo preguntas o necesito más información?', 'Ofrecemos soporte a través de chat en vivo, una línea de contacto y un formulario para solicitar más información. Nuestro equipo está disponible para responder cualquier duda que tenga.', 1),
    ('¿Cómo funciona la modalidad \"scramble\" y las dinámicas de equipo?', 'La modalidad \"scramble\" es un formato de golf en equipo donde se fomenta la cooperación y la estrategia en grupo. Se juega en equipos de 4 personas, combinando participantes con y sin experiencia en golf.', 1),
    ('¿Qué opciones de duración del programa están disponibles?', 'Ofrecemos opciones de uno o dos días completos de actividades intensivas, adaptándose a las necesidades y disponibilidad de su equipo.', 1),
    ('¿Qué perfil y credenciales tienen los coaches?', 'Nuestro equipo de coaches, liderado por Javier Ontiveros, cuenta con vasta experiencia en liderazgo, coaching y desarrollo de competencias directivas. Sus perfiles y logros están detallados en la página para su referencia.', 1),

    ('¿Cuáles son las competencias específicas de liderazgo y trabajo en equipo que se desarrollarán durante la experiencia de orquesta?', 'El programa está diseñado para mejorar habilidades de liderazgo, cohesión, comunicación, adaptabilidad y sentido de pertenencia a través de la integración y participación en una orquesta.', 2),
    ('¿Cómo se alinea esta experiencia con los objetivos estratégicos de nuestra compañía?', 'La experiencia en la orquesta refuerza la cohesión del equipo y el liderazgo, aspectos clave para el éxito organizacional y alineados con los objetivos estratégicos de su compañía.', 2),
    ('¿Qué tipo de feedback y métricas se utilizan para medir el impacto de esta experiencia?', 'Utilizamos encuestas de satisfacción completamente personalizadas, sesiones de retroalimentación y análisis de desempeño para medir el impacto y la aplicabilidad de los aprendizajes en el entorno profesional.', 2),
    ('¿Cómo se asegura que los aprendizajes se traduzcan en cambios concretos en el trabajo?', 'Las sesiones de reflexión post-actividad están diseñadas para conectar directamente la experiencia musical con situaciones y desafíos específicos del entorno laboral.', 2),
    ('¿Cómo funciona la formación de la orquesta y las dinámicas de equipo?', 'Todos los participantes, sin importar su experiencia musical, se integran en una orquesta bajo la dirección de un renombrado director. Esta actividad promueve la inclusión, la adaptabilidad y la cohesión del equipo.', 2),
    ('¿Qué opciones de duración del programa están disponibles?', 'Ofrecemos opciones flexibles de una a tres horas, adaptadas a las necesidades de su evento o convención.', 2),
    ('¿Qué perfil y credenciales tienen los coaches?', 'Nuestro equipo de coaches, liderado por Javier Ontiveros, cuenta con vasta experiencia en liderazgo, coaching y desarrollo de competencias directivas. Sus perfiles y logros están detallados en la página para su referencia.', 2),

    ('¿Cuáles son las competencias específicas de liderazgo y trabajo en equipo que se desarrollarán durante la creación del mural?', 'El programa se enfoca en mejorar habilidades de liderazgo, cohesión de equipo, colaboración, creatividad y comunicación a través de la creación conjunta de un mural.', 3),
    ('¿Cómo se alinea esta experiencia con los objetivos estratégicos de nuestra compañía?', 'La creación del mural refuerza la importancia de cada aportación individual al éxito colectivo, promoviendo una cultura de colaboración y cohesión que es crucial para los objetivos estratégicos de su compañía.', 3),
    ('¿Qué tipo de feedback y métricas se utilizan para medir el impacto de esta experiencia?', 'Se incluyen sesiones de retroalimentación detalladas y encuestas de satisfacción para evaluar el impacto y la aplicabilidad de los aprendizajes en el entorno profesional.', 3),
    ('¿Cómo se asegura que los aprendizajes se traduzcan en cambios concretos en el trabajo?', 'Las sesiones de reflexión y feedback están diseñadas para conectar directamente la experiencia creativa con situaciones y desafíos específicos del entorno laboral, fomentando la aplicación práctica de las lecciones aprendidas.', 3),
    ('¿Cómo funciona la creación del mural y las dinámicas de equipo?', 'El programa comienza con una introducción a la actividad y la asignación de roles y materiales. Luego, bajo guía experta, cada participante contribuye con su trazo al mural, reflejando la diversidad y unidad del equipo. Finalmente, se realiza una sesión de reflexión y feedback para discutir el proceso y las lecciones aprendidas.', 3),
    ('¿Qué opciones de duración del programa están disponibles?', 'Ofrecemos opciones flexibles de duración que se adaptan a las necesidades de su evento o convención.', 3),
    ('¿Qué perfil y credenciales tienen los coaches?', 'Nuestro equipo de coaches, liderado por Javier Ontiveros, cuenta con vasta experiencia en liderazgo, coaching y desarrollo de competencias directivas. Sus perfiles y logros están detallados en la página para su referencia.', 3),

    ('¿Cuáles son las competencias específicas de liderazgo y trabajo en equipo que se desarrollarán durante la experiencia de vela?', 'El programa se enfoca en mejorar habilidades de liderazgo, toma de decisiones, adaptabilidad, comunicación, y cohesión de equipo a través de actividades de navegación y desafíos en el mar.', 4),
    ('¿Cómo se alinea esta experiencia con los objetivos estratégicos de nuestra compañía?', 'La experiencia en vela refuerza la colaboración y el liderazgo en situaciones desafiantes, alineándose con los objetivos estratégicos de su compañía de promover un entorno de trabajo cooperativo y resiliente.', 4),
    ('¿Qué tipo de feedback y métricas se utilizan para medir el impacto de esta experiencia?', 'Utilizamos encuestas de satisfacción, sesiones de retroalimentación y análisis de desempeño para evaluar el impacto de la actividad y cómo los aprendizajes se aplican en el entorno profesional.', 4),
    ('¿Cómo se asegura que los aprendizajes se traduzcan en cambios concretos en el trabajo?', 'Las sesiones de reflexión y feedback post-actividad están diseñadas para conectar directamente la experiencia de navegación con situaciones y desafíos específicos del entorno laboral, promoviendo cambios concretos en la dinámica de trabajo.', 4),
    ('¿Cómo funciona la formación de equipos y las dinámicas de navegación?', 'La actividad comienza con una bienvenida y briefing inicial, seguido de la formación de equipos y la distribución de roles. Los participantes practican técnicas de navegación y maniobras bajo la guía de un capitán experimentado, enfrentando desafíos que simulan escenarios reales del entorno profesional.', 4),
    ('¿Qué opciones de duración del programa están disponibles?', 'Ofrecemos opciones flexibles de uno o dos días, permitiendo una inmersión completa en la experiencia náutica y adaptándose a las necesidades de su equipo.', 4),
    ('¿Qué perfil y credenciales tienen los coaches?', 'Nuestro equipo de coaches, liderado por Javier Ontiveros, cuenta con vasta experiencia en liderazgo, coaching y desarrollo de competencias directivas. Sus perfiles y logros están detallados en la página para su referencia.', 4),
    ('¿Dónde puedo reservar o solicitar más información?', 'Puede reservar su taller directamente desde la página o utilizar el formulario para solicitar más información. Ambos botones están claramente visibles y accesibles en la página.', 4),

    ('¿Cómo puede QQVoice ayudar a mejorar la imagen de marca de nuestra compañía?', 'QQVoice permite crear una voz de marca auténtica y resonante mediante la co-creación de preguntas y la personalización de los cuestionarios y dashboards. Esto asegura que la imagen de su marca se destaque y se diferencie de la competencia.', 5),
    ('¿De qué manera QQVoice ayuda a entender mejor a nuestros clientes y empleados?', 'QQVoice proporciona un análisis detallado de las emociones y opiniones de sus clientes y empleados a través de un dashboard personalizado. Esto facilita la toma de decisiones informadas y rápidas basadas en datos reales y en tiempo real.', 5),
    ('¿Qué tipo de feedback y análisis se proporcionan con este servicio?', 'QQVoice ofrece reportes periódicos que incluyen un análisis exhaustivo de los datos recopilados y recomendaciones para la mejora continua. Además, se reciben alertas en tiempo real sobre los resultados obtenidos.', 5),
    ('¿Cómo se asegura la integración de los KPIs de QQVoice con nuestros otros indicadores de negocio?', 'El dashboard personalizado de QQVoice permite la integración con otros KPIs de su negocio, proporcionando una vista completa y unificada de todas las métricas importantes para su empresa.', 5),
    ('¿Cómo funciona la consultoría inicial y la identificación de necesidades?', 'Comenzamos con una consultoría inicial para analizar el programa de voz actual (si lo hay) y determinar las necesidades específicas de sus clientes y empleados, asegurando que el plan de voz esté perfectamente alineado con sus objetivos. Además, nos empapamos de la cultura de la marca y los valores.', 5),
    ('¿Qué implica la co-creación de la voz de la marca y las preguntas?', 'Trabajamos en colaboración con su equipo para desarrollar una voz de marca que resuene con sus valores y estilo. También co-creamos las preguntas que se alineen con esta voz y objetivos, asegurando una conexión auténtica con su audiencia.', 5),
    ('¿Qué incluye el diseño del producto digital?', 'El diseño del producto digital incluye la creación de un cuestionario personalizado que destaca la imagen de su marca y un dashboard que muestra todos los KPIs relevantes, con alertas en tiempo real sobre los resultados obtenidos.', 5),
    ('¿Qué tipo de reportes periódicos se proporcionan y con qué frecuencia?', 'Proporcionamos reportes periódicos con un análisis detallado de los datos recopilados y recomendaciones para la mejora continua. La frecuencia de los reportes puede adaptarse a sus necesidades específicas. En la oferta de este servicio están contemplados reportes mensuales.', 5),

    ('¿Cómo puede el taller de omnicanalidad mejorar los procesos y la experiencia de nuestros clientes?', 'El taller de omnicanalidad ayuda a evaluar y mejorar la coherencia y consistencia de la atención al cliente a través de todos los canales de comunicación. Proporciona estrategias para integrar de manera efectiva los canales, mejorando la experiencia del cliente y la eficiencia operativa.', 6),
    ('¿Qué diferencia hay entre multicanalidad y omnicanalidad?', 'La multicanalidad implica tener varios canales de comunicación disponibles, mientras que la omnicanalidad se enfoca en integrar estos canales para proporcionar una experiencia de cliente coherente y sin fisuras en todos los puntos de contacto.', 6),
    ('¿Cómo se evalúa la integración de canales en mi empresa?', 'Durante el taller, realizamos una evaluación personalizada de la integración de canales en su empresa, identificando brechas y oportunidades para mejorar la experiencia del cliente.', 6),
    ('¿Qué tipo de plan de acción se desarrolla durante el taller?', 'Se desarrolla un plan de acción específico para alcanzar la omnicanalidad, incluyendo la implementación de mejores prácticas y el seguimiento de resultados para asegurar una mejora continua en la atención al cliente.', 6),
    ('¿Cómo funciona el mapeo del recorrido del cliente y la identificación de áreas de mejora?', 'El taller incluye una sesión dedicada a mapear el recorrido del cliente a través de los canales digitales, identificando puntos de contacto clave y áreas de mejora para optimizar la experiencia del cliente.', 6),
    ('¿Qué estrategias se enseñan para una integración efectiva de los canales?', 'Se enseñan estrategias para diferenciar entre multicanalidad y omnicanalidad, y cómo integrar efectivamente todos los canales de comunicación para proporcionar una experiencia de cliente coherente y eficiente.', 6),
    ('¿Qué implica el diagnóstico de integración de canales?', 'El diagnóstico incluye una evaluación personalizada de la efectividad y coherencia de los canales de atención al cliente en su empresa, identificando brechas y oportunidades para mejorar la integración de los canales.', 6),
    ('¿Qué se incluye en el plan de acción para alcanzar la omnicanalidad?', 'El plan de acción incluye el desarrollo e implementación de mejores prácticas, estrategias específicas para la integración de canales, y un seguimiento de resultados para asegurar una mejora continua en la experiencia del cliente.', 6),

    ('¿Cómo puede el taller de Customer Journey mejorar los procesos y la experiencia de nuestros clientes en la compañía farmacéutica?', 'El taller de Customer Journey ayuda a comprender profundamente cada etapa del recorrido del cliente, identificando áreas de mejora y desarrollando estrategias para optimizar la experiencia en cada punto de contacto. Esto incrementa la satisfacción y lealtad del cliente, diferenciando a su compañía de la competencia.', 7),
    ('¿Qué se incluye en la definición del público objetivo?', 'La definición del público objetivo incluye la creación de un Mapa de Empatía para identificar detalladamente las necesidades, deseos y comportamientos de sus clientes. Esto permite entender mejor a su audiencia y personalizar las experiencias de cliente.', 7),
    ('¿Cómo se analizan las etapas del ciclo de vida del cliente?', 'Se realiza un análisis exhaustivo de cada fase del customer journey: antes, durante y después de la interacción con su empresa. Esto ayuda a identificar puntos de contacto clave y áreas donde se pueden hacer mejoras significativas.', 7),
    ('¿Qué implica la detección de “momentos de dolor”?', 'La detección de “momentos de dolor” implica localizar las fricciones y problemas que enfrentan los clientes durante su recorrido. Esto permite identificar áreas críticas que necesitan atención para mejorar la experiencia del cliente.', 7),
    ('¿Cómo se desarrollan las estrategias de mejora?', 'Se identifican oportunidades y se desarrollan estrategias específicas para optimizar la experiencia del cliente en cada etapa del customer journey. Esto incluye acciones concretas para mejorar la satisfacción y lealtad del cliente.', 7),
    ('¿Cómo se realiza la definición del público objetivo y el mapeo de empatía?', 'El taller incluye sesiones dedicadas a la creación de un Mapa de Empatía, identificando detalladamente las necesidades, deseos y comportamientos de sus clientes para entender mejor a su audiencia.', 7),
    ('¿Qué implica la identificación y análisis de las etapas del ciclo de vida del cliente?', 'Se analizan cada una de las fases del customer journey (antes, durante y después) para identificar puntos de contacto clave y áreas de mejora, asegurando una experiencia de cliente coherente y fluida.', 7),
    ('¿Cómo se lleva a cabo la detección de “momentos de dolor” y oportunidades de mejora?', 'Se realiza un análisis profundo para localizar las fricciones y problemas que enfrentan los clientes. Luego, se desarrollan estrategias específicas para abordar estos problemas y mejorar la satisfacción del cliente.', 7),
    ('¿Qué tipo de plan de acción se desarrolla durante el taller?', 'El plan de acción incluye la implementación de mejores prácticas y estrategias específicas para optimizar la experiencia del cliente en cada punto de contacto, con un enfoque en mejorar la satisfacción y lealtad del cliente.', 7),

    ('¿Qué es el coaching y cómo se diferencia de la terapia o la consultoría?', 'El coaching se centra en ayudarte a alcanzar objetivos específicos y desarrollar habilidades a través de un proceso de colaboración y guía. A diferencia de la terapia, que trata problemas emocionales profundos y del pasado, el coaching se enfoca en el presente y el futuro. La consultoría implica ofrecer soluciones específicas y consejos expertos, mientras que el coaching te ayuda a descubrir tus propias respuestas y estrategias.', 8),
    ('¿Cómo se realiza la evaluación 360º en el programa de Neuroliderazgo?', 'La evaluación 360º en nuestro programa se basa en el modelo i4 de neuroliderazgo. Incluye retroalimentación de compañeros, superiores y subordinados para proporcionar una visión completa de tus competencias de liderazgo. Esto ayuda a identificar tus fortalezas, áreas de mejora y puntos ciegos.', 8),
    ('¿Qué incluye el Plan de Mejora Individual (PMI)?', 'El PMI es un plan detallado que se elabora durante el programa y que incluye metas claras, objetivos específicos y estrategias personalizadas para mejorar tus competencias de liderazgo. Este plan es fundamental para guiar tu desarrollo y seguimiento continuo.', 8),
    ('¿Cuánto tiempo tarda en ver resultados con el programa de coaching?', 'Los resultados varían según el individuo y sus objetivos específicos, pero generalmente los participantes empiezan a notar mejoras significativas en sus habilidades de liderazgo y gestión emocional dentro de los primeros 3 a 6 meses del programa.', 8),
    ('¿Qué tipo de apoyo continuo está disponible durante y después del programa?', 'Durante el programa, tendrás acceso continuo a tu coach para orientación y seguimiento. Después de la finalización del programa, puedes optar por sesiones adicionales para mantener y seguir desarrollando tus habilidades.', 8),
    ('¿Cómo se mide el impacto del programa de coaching?', 'El impacto se mide a través de una sesión final de evaluación donde el coach y el coachee revisan los resultados logrados en comparación con los objetivos establecidos al inicio del programa. También se realiza una reevaluación del entorno profesional y del alcance de los cambios implementados.', 8),
    ('¿Qué es el modelo i4 de neuroliderazgo?', 'El modelo i4 de neuroliderazgo es un marco avanzado que evalúa y desarrolla competencias de liderazgo basándose en principios de neurociencia. Este modelo ayuda a entender y mejorar cómo piensas, te comportas y lideras, utilizando evaluaciones 360º y planes personalizados para fomentar el crecimiento y la efectividad.', 8),
    ('¿El programa incluye formación en habilidades técnicas específicas?', 'El enfoque principal del programa es el desarrollo de competencias de liderazgo y gestión emocional. Sin embargo, también se proporcionan herramientas y recursos específicos según las necesidades individuales identificadas durante el proceso de coaching.', 8),
    ('¿Quién es Javier Ontiveros y cuál es su experiencia?', 'Javier Ontiveros es el fundador y CEO de Quid Qualitas y vicepresidente de la Comunidad de Experiencia de Cliente AEC. Es un coach certificado en neuroliderazgo con más de 20 años de experiencia en puestos directivos en multinacionales del sector tecnológico y consultoría. Ha diseñado y desarrollado numerosos programas de desarrollo directivo, transformación cultural y experiencia del cliente.', 8),
    ('¿Cuál es el costo del programa y qué incluye?', 'El costo del programa es XXX€ e incluye 6 sesiones individuales (1 to 1) con la opción de ampliación a demanda. Cada sesión está diseñada para proporcionar un seguimiento personalizado y efectivo de tu desarrollo como líder.', 8)
");

}


//Llamamos a la función para insetar los datos
insertarDatos($conexion);


// Cerrar conexión
$conexion->close();