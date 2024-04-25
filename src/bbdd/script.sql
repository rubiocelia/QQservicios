CREATE DATABASE IF NOT EXISTS QQservicios;

USE QQservicios;

CREATE TABLE IF NOT EXISTS Usuarios (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(255),
    Apellidos VARCHAR(255),
    Correo_electronico VARCHAR(255),
    Numero_telefono VARCHAR(20),
    Foto VARCHAR(255),
    Fecha_Registro DATE
);

CREATE TABLE IF NOT EXISTS Coaches (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(255),
    Apellidos VARCHAR(255),
    Titulacion VARCHAR(255),
    Formacion VARCHAR(255),
    Experiencia VARCHAR(255),
    Foto VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS Atributos (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS Productos (
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
);

CREATE TABLE IF NOT EXISTS DatosAcceso (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Usuario VARCHAR(255),
    Contrasena VARCHAR(255),
    Administrador BOOLEAN,
    FechaLogin DATETIME,
    ID_usuario INT,
    FOREIGN KEY (ID_usuario) REFERENCES Usuarios(ID)
);

CREATE TABLE IF NOT EXISTS Contenidos (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Titulo VARCHAR(255),
    Descripcion TEXT,
    ID_Producto INT,
    FOREIGN KEY (ID_Producto) REFERENCES Productos(ID)
);

CREATE TABLE IF NOT EXISTS Compra (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    FechaHora DATETIME,
    Confirmacion BOOLEAN,
    ID_usuario INT,
    ID_Producto INT,
    FOREIGN KEY (ID_usuario) REFERENCES Usuarios(ID),
    FOREIGN KEY (ID_Producto) REFERENCES Productos(ID)
);

CREATE TABLE IF NOT EXISTS Testimonios (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(255),
    Descripcion TEXT,
    Foto VARCHAR(255),
    ID_Producto INT,
    FOREIGN KEY (ID_Producto) REFERENCES Productos(ID)
);

CREATE TABLE IF NOT EXISTS ArchivosUsuarios (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Ruta VARCHAR(255),
    Descripcion TEXT,
    ID_usuario INT,
    FOREIGN KEY (ID_usuario) REFERENCES Usuarios(ID)
);

INSERT INTO Coaches (Nombre, Apellidos, Titulacion, Formacion, Experiencia, Foto) VALUES
('Coach1', 'Apellido1', 'Titulacion1', 'Formacion1', 'Experiencia1', 'foto1Coaches.jpg'),
('Coach2', 'Apellido2', 'Titulacion2', 'Formacion2', 'Experiencia2', 'foto2Coaches.jpg'),
('Coach3', 'Apellido3', 'Titulacion3', 'Formacion3', 'Experiencia3', 'foto3Coaches.jpg'),
('Coach4', 'Apellido4', 'Titulacion4', 'Formacion4', 'Experiencia4', 'foto4Coaches.jpg'),
('Coach5', 'Apellido5', 'Titulacion5', 'Formacion5', 'Experiencia5', 'foto5Coaches.jpg'),
('Coach6', 'Apellido6', 'Titulacion6', 'Formacion6', 'Experiencia6', 'foto6Coaches.jpg'),
('Coach7', 'Apellido7', 'Titulacion7', 'Formacion7', 'Experiencia7', 'foto7Coaches.jpg'),
('Coach8', 'Apellido8', 'Titulacion8', 'Formacion8', 'Experiencia8', 'foto8Coaches.jpg'),
('Coach9', 'Apellido9', 'Titulacion9', 'Formacion9', 'Experiencia9', 'foto9Coaches.jpg'),
('Coach10', 'Apellido10', 'Titulacion10', 'Formacion10', 'Experiencia10', 'foto10Coaches.jpg');

INSERT INTO Atributos (Nombre) VALUES
('Individual'),
('Grupal'),
('Coaching'),
('Atributo4'),
('Atributo5'),
('Atributo6'),
('Atributo7'),
('Atributo8'),
('Atributo9'),
('Atributo10');

INSERT INTO Usuarios (Nombre, Apellidos, Correo_electronico, Numero_telefono, Foto, Fecha_Registro) VALUES
('Nombre1', 'Apellido1', 'email1@example.com', '1234567890', 'foto1Usuario.jpg', CURDATE()),
('Nombre2', 'Apellido2', 'email2@example.com', '1234567891', 'foto2Usuario.jpg', CURDATE()),
('Nombre3', 'Apellido3', 'email3@example.com', '1234567892', 'foto3Usuario.jpg', CURDATE()),
('Nombre4', 'Apellido4', 'email4@example.com', '1234567893', 'foto4Usuario.jpg', CURDATE()),
('Nombre5', 'Apellido5', 'email5@example.com', '1234567894', 'foto5Usuario.jpg', CURDATE()),
('Nombre6', 'Apellido6', 'email6@example.com', '1234567895', 'foto6Usuario.jpg', CURDATE()),
('Nombre7', 'Apellido7', 'email7@example.com', '1234567896', 'foto7Usuario.jpg', CURDATE()),
('Nombre8', 'Apellido8', 'email8@example.com', '1234567897', 'foto8Usuario.jpg', CURDATE()),
('Nombre9', 'Apellido9', 'email9@example.com', '1234567898', 'foto9Usuario.jpg', CURDATE()),
('Nombre10', 'Apellido10', 'email10@example.com', '1234567899', 'foto10Usuario.jpg', CURDATE());

INSERT INTO Productos (Nombre, Descripcion, Categorias, Foto, Videos, Precio, Adquirible, ID_coaches, Id_atributo) VALUES
('Producto1', 'Descripcion1', 'Categoria1', 'producto1.jpg', 'video1producto.mp4', 100, 1, 1, 1),
('Producto2', 'Descripcion2', 'Categoria2', 'producto2.jpg', 'video2producto.mp4', 200, 0, 2, 2),
('Producto3', 'Descripcion3', 'Categoria3', 'producto3.jpg', 'video3producto.mp4', 300, 1, 3, 3),
('Producto4', 'Descripcion4', 'Categoria4', 'producto4.jpg', 'video4producto.mp4', 400, 0, 4, 4),
('Producto5', 'Descripcion5', 'Categoria5', 'producto5.jpg', 'video5producto.mp4', 500, 1, 5, 5),
('Producto6', 'Descripcion6', 'Categoria6', 'producto6.jpg', 'video6producto.mp4', 600, 0, 6, 6),
('Producto7', 'Descripcion7', 'Categoria7', 'producto7.jpg', 'video7producto.mp4', 700, 1, 7, 7),
('Producto8', 'Descripcion8', 'Categoria8', 'producto8.jpg', 'video8producto.mp4', 800, 0, 8, 8),
('Producto9', 'Descripcion9', 'Categoria9', 'producto9.jpg', 'video9producto.mp4', 900, 1, 9, 9),
('Producto10', 'Descripcion10', 'Categoria10', 'producto10.jpg', 'video10producto.mp4', 1000, 0, 10, 10);

INSERT INTO DatosAcceso (Usuario, Contrasena, Administrador, FechaLogin, ID_usuario) VALUES
('Javier', '$2y$10$krEmZftPDYJjP9oylw9kiOCHFqyIVj/RrJ2fAoyIWiMFnOqIGvJg6', 1, NOW(), 1),
('usuario2', '$2y$10$krEmZftPDYJjP9oylw9kiOCHFqyIVj/RrJ2fAoyIWiMFnOqIGvJg6', 0, NOW(), 2),
('usuario3', '$2y$10$krEmZftPDYJjP9oylw9kiOCHFqyIVj/RrJ2fAoyIWiMFnOqIGvJg6', 0, NOW(), 3),
('usuario4', '$2y$10$krEmZftPDYJjP9oylw9kiOCHFqyIVj/RrJ2fAoyIWiMFnOqIGvJg6', 0, NOW(), 4),
('usuario5', '$2y$10$krEmZftPDYJjP9oylw9kiOCHFqyIVj/RrJ2fAoyIWiMFnOqIGvJg6', 0, NOW(), 5),
('usuario6', '$2y$10$krEmZftPDYJjP9oylw9kiOCHFqyIVj/RrJ2fAoyIWiMFnOqIGvJg6', 0, NOW(), 6),
('usuario7', '$2y$10$krEmZftPDYJjP9oylw9kiOCHFqyIVj/RrJ2fAoyIWiMFnOqIGvJg6', 0, NOW(), 7),
('usuario8', '$2y$10$krEmZftPDYJjP9oylw9kiOCHFqyIVj/RrJ2fAoyIWiMFnOqIGvJg6', 0, NOW(), 8),
('usuario9', '$2y$10$krEmZftPDYJjP9oylw9kiOCHFqyIVj/RrJ2fAoyIWiMFnOqIGvJg6', 0, NOW(), 9),
('usuario10', '$2y$10$krEmZftPDYJjP9oylw9kiOCHFqyIVj/RrJ2fAoyIWiMFnOqIGvJg6', 0, NOW(), 10);

INSERT INTO Contenidos (Titulo, Descripcion, ID_Producto) VALUES
('Titulo1', 'Descripcion del contenido 1', 1),
('Titulo2', 'Descripcion del contenido 2', 2),
('Titulo3', 'Descripcion del contenido 3', 3),
('Titulo4', 'Descripcion del contenido 4', 4),
('Titulo5', 'Descripcion del contenido 5', 5),
('Titulo6', 'Descripcion del contenido 6', 6),
('Titulo7', 'Descripcion del contenido 7', 7),
('Titulo8', 'Descripcion del contenido 8', 8),
('Titulo9', 'Descripcion del contenido 9', 9),
('Titulo10', 'Descripcion del contenido 10', 10);

INSERT INTO Compra (FechaHora, Confirmacion, ID_usuario, ID_Producto) VALUES
(NOW(), 1, 1, 1),
(NOW(), 0, 2, 2),
(NOW(), 1, 3, 3),
(NOW(), 0, 4, 4),
(NOW(), 1, 5, 5),
(NOW(), 0, 6, 6),
(NOW(), 1, 7, 7),
(NOW(), 0, 8, 8),
(NOW(), 1, 9, 9),
(NOW(), 0, 10, 10);

INSERT INTO Testimonios (Nombre, Descripcion, Foto, ID_Producto) VALUES
('Testigo1', 'Gran producto', 'testimonio1.jpg', 1),
('Testigo2', 'Excelente calidad', 'testimonio2.jpg', 2),
('Testigo3', 'Muy recomendable', 'testimonio3.jpg', 3),
('Testigo4', 'Satisfecho con la compra', 'testimonio4.jpg', 4),
('Testigo5', 'Buen servicio', 'testimonio5.jpg', 5),
('Testigo6', 'Me encantó', 'testimonio6.jpg', 6),
('Testigo7', 'No cumplió mis expectativas', 'testimonio7.jpg', 7),
('Testigo8', 'Volvería a comprar', 'testimonio8.jpg', 8),
('Testigo9', 'El mejor del mercado', 'testimonio9.jpg', 9),
('Testigo10', 'Pudo ser mejor', 'testimonio10.jpg', 10);

INSERT INTO ArchivosUsuarios (Ruta, Descripcion, ID_usuario) VALUES
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
('ruta/archivo10.pdf', 'Manual del usuario 10', 10);
