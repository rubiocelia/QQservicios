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
        "productos" => "CREATE TABLE IF NOT EXISTS Productos (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            Nombre VARCHAR(255),
            DescripcionCorta TEXT,
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

        "carrusel_multimedia" => "CREATE TABLE IF NOT EXISTS carruselMultimedia (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            Link_Video TEXT,
            Nombre_carrusel VARCHAR(255),
            RutaArchivos VARCHAR(255),
            ID_Producto INT,
            FOREIGN KEY (ID_Producto) REFERENCES Productos(ID)
            )",
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

    
    $conexion->query("INSERT INTO Productos (Nombre, DescripcionCorta, Descripcion, Categorias, Foto, Videos, Precio, Adquirible, ID_coaches, Id_atributo) VALUES
                        ('Impulsa tus Competencias como líder. Neuroliderazgo', '¿Listo para potenciar tu liderazgo y gestión emocional? Nuestro programa de Neuroliderazgo mejora tu desempeño, equilibrio personal, creatividad, colaboración y gestión de diversidad.', '¿Estás listo para potenciar tus habilidades de liderazgo y gestión emocional? Nuestro programa completo de Neuroliderazgo está diseñado para ayudarte a alcanzar un desempeño óptimo en tus funciones y mejorar tu equilibrio personal. Descubre cómo impulsar tu creatividad, fomentar la colaboración y gestionar la diversidad en tu equipo.<br><em>6 sesiones 1 to 1 incluidas. Opción de ampliación a demanda.</em>', 'Categoria1', './archivos/servicios/Producto1.jpg', 'video1producto.mp4', 100, 1, 1, 1),
                        ('Producto2', 'Lorem ipsu ejemplo de una descripción corta para la card', 'Descripcion2', 'Categoria2', './archivos/servicios/Producto2.jpg', 'video2producto.mp4', 200, 0, 2, 2),
                      ('Producto3', 'Lorem ipsu ejemplo de una descripción corta para la card', 'Descripcion3', 'Categoria3', './archivos/servicios/Producto3.jpg', 'video3producto.mp4', 300, 1, 3, 3),
                      ('Producto4', 'Lorem ipsu ejemplo de una descripción corta para la card', 'Descripcion4', 'Categoria4', './archivos/servicios/Producto4.jpg', 'video4producto.mp4', 400, 0, 4, 4),
                      ('Producto5', 'Lorem ipsu ejemplo de una descripción corta para la card', 'Descripcion5', 'Categoria5', './archivos/servicios/Producto5.jpg', 'video5producto.mp4', 500, 1, 5, 5),
                      ('Producto6', 'Lorem ipsu ejemplo de una descripción corta para la card', 'Descripcion6', 'Categoria6', './archivos/servicios/Producto6.jpg', 'video6producto.mp4', 600, 0, 6, 6),
                      ('Producto7', 'Lorem ipsu ejemplo de una descripción corta para la card', 'Descripcion7', 'Categoria7', './archivos/servicios/Producto7.jpg', 'video7producto.mp4', 700, 1, 7, 7),
                      ('Producto8', 'Lorem ipsu ejemplo de una descripción corta para la card', 'Descripcion8', 'Categoria8', './archivos/servicios/Producto8.jpg', 'video8producto.mp4', 800, 0, 8, 8),
                      ('Producto9', 'Lorem ipsu ejemplo de una descripción corta para la card', 'Descripcion9', 'Categoria9', './archivos/servicios/Producto9.jpg', 'video9producto.mp4', 900, 1, 9, 9),
                      ('Producto10', 'Lorem ipsu ejemplo de una descripción corta para la card', 'Descripcion10', 'Categoria10', './archivos/servicios/Producto10.jpg', 'video10producto.mp4', 1000, 0, 10, 10)");


    $usuarios = [
        ['adminJavier','ejemplo1', 0, 1],
        ['usuario2', 'ejemplo1',1, 2],
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
    ('Sesión Previa Programa Coaching', 
        '<ul>
            <li>Definición clara de lo que es y no es coaching.</li>
            <li>Evaluación del momento actual del líder/coachee y sus objetivos de cambio.</li>
            <li>Planteamiento de un camino evolutivo hacia la autonomía, despliegue de talento y desarrollo directivo.</li>
        </ul>', 1),
        ('Situación Actual Líder. Evaluación 360º', 
        '<ul>
            <li>Evaluación de competencias según el modelo i4 de neuroliderazgo.</li>
            <li>Comprensión de la visión personal del presente y definición clara del rol profesional actual.</li>
            <li>Identificación de necesidades de cambio, áreas de desarrollo y fortalezas.</li>
        </ul>', 1),
        ('Toma de Conciencia y Definición del Cambio', 
        '<ul>
            <li>Análisis detallado de competencias (propias y externas).</li>
            <li>Identificación de fortalezas, debilidades y puntos ciegos.</li>
            <li>Definición de la visión deseada, metas y objetivos.</li>
            <li>Elaboración de un Plan de Mejora Individual (PMI) y generación de nuevos compromisos de cambio.</li>
        </ul>', 1),
        ('Proceso de Desarrollo Individual', 
        '<ul>
            <li>Evaluación conjunta del impacto y resultados del programa.</li>
            <li>Revisión de la situación profesional actual y alcance de los objetivos definidos.</li>
            <li>Provisión de herramientas necesarias para el cambio.</li>
            <li>Identificación de avances, barreras y apoyos necesarios.</li>
        </ul>', 1),
        ('Sesión Final de Evaluación de Impacto', 
        '<ul>
            <li>Coach y Coachee evalúan el impacto y resultados del Programa de Coaching.</li>
            <li>Situación actual del entorno profesional y el alcance de los objetivos definidos.</li>
            <li>Planteamiento de los pasos a seguir después del coaching.</li>
        </ul>', 1)");


    // Insertar datos en Compra
    $conexion->query("INSERT INTO Compra (FechaHora, Confirmacion, ID_usuario, ID_Producto) VALUES
                        (NOW(), 1, 1, 1),
                        (NOW(),1,1,2),
                        (NOW(),1,1,3),
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
    $conexion->query("INSERT INTO Testimonios (Nombre, Subtitulo, Descripcion, Foto, ID_Producto) VALUES
                        ('Mª Rosa León Mateo', 'Socia Fundadora','Mi proceso como coachee contigo fue una de las mejores decisiones de mi
                        carrera profesional. Este aprendizaje sigue vivo en mí, ayudándome a sacar mi
                        mejor versión diariamente. Lo único que siento es no haberlo hecho antes... Si la forma de
                        ejercer el liderazgo es siempre la base para obtener los mejores resultados, tener al equipo
                        motivado, en definitiva tener el mejor retorno de nuestras acciones profesionales, en el
                        periodo que estamos viviendo es fundamental. Mi proceso de aprendizaje como tu cochee
                        sigue vivo y presente en mi, todos los días y procuro ejercerlo a diario, sacando mi mejor
                        versión en este periodo tan complicado que estamos viviendo, con la satisfacción que eso
                        supone.', './archivos/foto1.jpg', 1),
                        ('Beatriz Achaques','CEO & Founder ', 'Javier tiene un don para ayudar a los demás. Me ha brindado herramientas que
                        me permiten crecer de manera independiente. Solo tengo palabras de
                        agradecimiento. Es una bellísima persona y un profesional HUMANO. y una sabiduría
                        infinita. Es una de esas personas a las que acudir en momentos claves de tu vida. Sabe
                        escuchar, leer a las personas y sembrar la semilla de crecimiento en el coachee para que
                        una vez acabado el proceso sea uno mismo el que con las herramientas conseguidas en el
                        proceso, pueda hacerla crecer de forma independiente. Me ha ayudado a alzar el vuelo. Solo
                        tengo palabras de agradecimiento y gratitud hacia Javier.', './archivos/foto1.jpg', 1),
                        ('Ramón Fco. Pérez Ruiz', 'Senior National Manager','Javier fue una influencia muy positiva en nuestro desarrollo profesional.
                        GRACIAS ', './archivos/foto1.jpg', 1)");

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





                // Insertar datos en faqs
                $conexion->query("INSERT INTO faqs (Pregunta, Respuesta, ID_Producto) VALUES
                        ('¿Qué es el coaching y cómo se diferencia de la terapia o la consultoría?', 'El coaching se centra en ayudarte a alcanzar objetivos específicos y desarrollar habilidades a través de un proceso de colaboración y guía. A diferencia de la terapia, que trata problemas emocionales profundos y del pasado, el coaching se enfoca en el presente y el futuro. La consultoría implica ofrecer soluciones específicas y consejos expertos, mientras que el coaching te ayuda a descubrir tus propias respuestas y estrategias.', 1),
                        ('¿Cómo se realiza la evaluación 360º en el programa de Neuroliderazgo?', 'La evaluación 360º en nuestro programa se basa en el modelo i4 de neuroliderazgo. Incluye retroalimentación de compañeros, superiores y subordinados para proporcionar una visión completa de tus competencias de liderazgo. Esto ayuda a identificar tus fortalezas, áreas de mejora y puntos ciegos.', 1),
                        ('¿Qué incluye el Plan de Mejora Individual (PMI)?', 'El PMI es un plan detallado que se elabora durante el programa y que incluye metas claras, objetivos específicos y estrategias personalizadas para mejorar tus competencias de liderazgo. Este plan es fundamental para guiar tu desarrollo y seguimiento continuo.', 1),
                        ('¿Cuánto tiempo tarda en ver resultados con el programa de coaching?', 'Los resultados varían según el individuo y sus objetivos específicos, pero generalmente los participantes empiezan a notar mejoras significativas en sus habilidades de liderazgo y gestión emocional dentro de los primeros 3 a 6 meses del programa.', 1),
                        ('¿Qué tipo de apoyo continuo está disponible durante y después del programa?', 'Durante el programa, tendrás acceso continuo a tu coach para orientación y seguimiento. Después de la finalización del programa, puedes optar por sesiones adicionales para mantener y seguir desarrollando tus habilidades.', 1),
                        ('¿Cómo se mide el impacto del programa de coaching?', 'El impacto se mide a través de una sesión final de evaluación donde el coach y el coachee revisan los resultados logrados en comparación con los objetivos establecidos al inicio del programa. También se realiza una reevaluación del entorno profesional y del alcance de los cambios implementados.', 1),
                        ('¿Qué es el modelo i4 de neuroliderazgo?', 'El modelo i4 de neuroliderazgo es un marco avanzado que evalúa y desarrolla competencias de liderazgo basándose en principios de neurociencia. Este modelo ayuda a entender y mejorar cómo piensas, te comportas y lideras, utilizando evaluaciones 360º y planes personalizados para fomentar el crecimiento y la efectividad.', 1),
                        ('¿El programa incluye formación en habilidades técnicas específicas?', 'El enfoque principal del programa es el desarrollo de competencias de liderazgo y gestión emocional. Sin embargo, también se proporcionan herramientas y recursos específicos según las necesidades individuales identificadas durante el proceso de coaching.', 1)");



                // Insertar datos en carruselMultimedia
                $conexion->query("INSERT INTO carruselMultimedia (RutaArchivos, Link_Video, Nombre_carrusel, ID_Producto) VALUES
                ('./archivos/productos/carruselProducto1/juliaYjavierSofa.jpeg','','Carrusel 1', 1),
                ('./archivos/productos/carruselProducto1/javier_ontiveros.jpeg','','Carrusel 1', 1),
                (' ','https://www.youtube.com/watch?v=9cAUjEHHhxs&pp=ygUMcXVpZHF1YWxpdGFz','Carrusel 1', 1),
                ('./archivos/productos/carruselProducto1/javier_ontiveros.jpeg','','Carrusel 1', 1),
                ('./archivos/productos/carruselProducto1/javier_ontiveros.jpeg','','Carrusel 1', 1),
                ('./archivos/productos/carruselProducto1/javier_ontiveros.jpeg','','Carrusel 2', 2),
                ('./archivos/productos/carruselProducto1/javier_ontiveros.jpeg','','Carrusel 2', 2),
                ('./archivos/productos/carruselProducto1/javier_ontiveros.jpeg','','Carrusel 2', 2),
                ('./archivos/productos/carruselProducto1/javier_ontiveros.jpeg','','Carrusel 2', 2)
                ");
}


    //Llamamos a la función para insetar los datos
insertarDatos($conexion);


// Cerrar conexión
$conexion->close();