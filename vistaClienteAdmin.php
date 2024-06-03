<?php
// Incluir archivo de conexión a la base de datos
include './bbdd/conecta.php';

// Verificar si se proporcionó un ID de usuario en la URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Obtener el ID de usuario de la URL
    $idUsuario = $_GET['id'];

    // Obtener los datos del usuario
    $conexion = getConexion();
    $sql = "SELECT * FROM Usuarios WHERE ID = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idUsuario); // 'i' para indicar que es un entero (ID)
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 0) {
        // No se encontraron resultados para el ID proporcionado
        echo "No se encontraron datos para el cliente con el ID proporcionado.";
        exit();
    }

    // Obtener los datos del usuario
    $usuario = $resultado->fetch_assoc();
} else {
    // Si no se proporcionó un ID de usuario en la URL, mostrar un mensaje de error
    echo "Error: No se proporcionó un ID de usuario.";
    exit();
}

$sql2 = "SELECT AU.ID, AU.Ruta, AU.Descripcion, AU.Fecha, P.Nombre AS TipoArchivo, P.ID AS ID_Producto 
        FROM ArchivosUsuarios AU 
        INNER JOIN Productos P ON AU.ID_Producto = P.ID 
        WHERE AU.ID_usuario = ?
        ORDER BY P.ID";
$stmt2 = $conexion->prepare($sql2);
$stmt2->bind_param("i", $idUsuario);
$stmt2->execute();
$result2 = $stmt2->get_result();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['eliminar_archivo']) && isset($_GET['id_archivo']) && isset($_GET['id'])) {
    // Obtener el ID del archivo y el ID del usuario a eliminar
    $idArchivo = $_GET['id_archivo'];
    $idUsuario = $_GET['id'];

    // Consulta SQL para eliminar el archivo
    $sqlEliminarArchivo = "DELETE FROM archivosusuarios WHERE ID = ?";
    $stmtEliminarArchivo = $conexion->prepare($sqlEliminarArchivo);
    $stmtEliminarArchivo->bind_param("i", $idArchivo);

    // Ejecutar la consulta
    if ($stmtEliminarArchivo->execute()) {
        // Eliminación exitosa
        // Redirigir de nuevo a la página actual para evitar la reenvío del formulario
        header("Location: {$_SERVER['PHP_SELF']}?id=$idUsuario");
        exit();
    } else {
        // Error al eliminar el archivo
        echo "Error al eliminar el archivo.";
    }

    // Cerrar la declaración preparada
}

// Consulta SQL para obtener los productos comprados por el cliente
$sqlCompras = "SELECT c.ID, c.FechaHora, c.Confirmacion, p.Nombre, p.DescripcionCorta
               FROM Compra c
               INNER JOIN Productos p ON c.ID_Producto = p.ID
               WHERE c.ID_usuario = ?
               ORDER BY c.ID";
$stmtCompras = $conexion->prepare($sqlCompras);
$stmtCompras->bind_param("i", $idUsuario);
$stmtCompras->execute();
$resultCompras = $stmtCompras->get_result();

// $sqlAccesos = "SELECT FechaConexion, COUNT(*) as accesos 
//                FROM tiempoConexion 
//                WHERE ID_usuario = ? AND FechaConexion >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) 
//                GROUP BY FechaConexion 
//                ORDER BY FechaConexion";
// $stmtAccesos = $conexion->prepare($sqlAccesos);
// $stmtAccesos->bind_param("i", $idUsuario);
// $stmtAccesos->execute();
// $resultAccesos = $stmtAccesos->get_result();

// $fechas = [];
// $accesos = [];

// while ($rowAcceso = $resultAccesos->fetch_assoc()) {
//     $fechas[] = $rowAcceso['FechaConexion'];
//     $accesos[] = $rowAcceso['accesos'];
// }

$conexion = getConexion();
$productos = $conexion->query("
SELECT p.ID, p.Nombre 
FROM Productos p
INNER JOIN Compra c ON p.ID = c.ID_Producto
WHERE c.ID_usuario = $idUsuario;
");

define("ID_USUARIO", $idUsuario);


$sql2 = "SELECT AU.ID, AU.Ruta, AU.Descripcion, AU.Fecha, AU.Deshabilitado, P.Nombre AS TipoArchivo, P.ID AS ID_Producto 
        FROM ArchivosUsuarios AU 
        INNER JOIN Productos P ON AU.ID_Producto = P.ID 
        WHERE AU.ID_usuario = ?
        ORDER BY P.ID";
$stmt2 = $conexion->prepare($sql2);
$stmt2->bind_param("i", $idUsuario);
$stmt2->execute();
$result2 = $stmt2->get_result();


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Cliente - Vista Administrador</title>
    <link rel="stylesheet" type="text/css" href="./estilos/css/index.css">
    <link rel="stylesheet" type="text/css" href="./estilos/css/vistaClienteAdmin.css">
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<?php include('menu_sesion_iniciada.php'); ?>

<body class="miCuenta">
    <h1 class="bienvenido">Cliente</h1>
    <main>
        <button type="button" class="volver" onclick="window.location.href = 'mi_cuenta_admin.php';">⬅​ Volver</button>
        <div id="perfil" class="seccion">
            <h1>Datos cliente</h1>
            <form action="guardar_perfil.php" method="post" enctype="multipart/form-data">
                <div class="perfil">
                    <div class="foto">
                        <img src="<?php echo htmlspecialchars($usuario['Foto']); ?>" alt="Foto de Perfil"
                            class="fotoPerfil">
                        <input type="file" id="foto" name="foto" style="display:none;">
                        <!-- Ocultamos el input real -->
                        <button type="button" id="btnSeleccionarFoto">Cambiar foto</button>
                        <!-- Botón estilizado para seleccionar foto -->
                    </div>
                    <div class="datos">
                        <!-- Fila para Nombre y Apellidos -->
                        <div class="fila">
                            <div class="campo">
                                <label for="nombre">Nombre:</label>
                                <input type="text" id="nombre" name="nombre"
                                    value="<?php echo htmlspecialchars($usuario['Nombre']); ?>" readonly>
                            </div>
                            <div class="campo">
                                <label for="apellidos">Apellidos:</label>
                                <input type="text" id="apellidos" name="apellidos"
                                    value="<?php echo htmlspecialchars($usuario['Apellidos']); ?>" readonly>
                            </div>
                        </div>
                        <!-- Fila para Email, Teléfono y Organización -->
                        <div class="fila">
                            <div class="campo">
                                <label for="email">Correo electrónico:</label>
                                <input type="email" id="email" name="email"
                                    value="<?php echo htmlspecialchars($usuario['Correo_electronico']); ?>" readonly>
                            </div>
                            <div class="campo">
                                <label for="telefono">Número de teléfono:</label>
                                <input type="tel" id="telefono" name="telefono"
                                    value="<?php echo htmlspecialchars($usuario['Numero_telefono']); ?>" readonly>
                            </div>
                            <div class="campo">
                                <label for="organizacion">Organización:</label>
                                <input type="text" id="organizacion" name="organizacion"
                                    value="<?php echo htmlspecialchars($usuario['Organizacion']); ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="acciones">
                        <button type="button" id="btnModificar" onclick="habilitarEdicion()">Modificar</button>
                        <button type="submit" id="btnGuardar" style="display:none;">Guardar cambios</button>
                        <button type="button" id="btnCancelar" style="display:none;"
                            onclick="cancelarEdicion()">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Aquí se muestra la tabla de los productos comprados -->
        <div class="seccion">
            <h1>Productos comprados por el cliente</h1>
            <table class="productos-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción Corta</th>
                        <th>Fecha de Compra</th>
                        <th>Confirmación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($rowCompra = $resultCompras->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $rowCompra['Nombre']; ?></td>
                        <td><?php echo $rowCompra['DescripcionCorta']; ?></td>
                        <td><?php echo $rowCompra['FechaHora']; ?></td>
                        <td><?php echo $rowCompra['Confirmacion'] == 1 ? 'Si' : 'No'; ?></td>
                        <td><button class="btn-eliminar"
                                onclick="eliminarCompra(<?php echo $rowCompra['ID']; ?>);">Eliminar</button></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="seccion">
            <h1>Documentos del cliente</h1>
            <?php
            $currentProducto = null; // Para verificar cambios en el tipo de producto
            while ($row = $result2->fetch_assoc()) :
                // Si el tipo de producto es diferente al anterior, crea una nueva tabla
                if ($currentProducto !== $row['ID_Producto']) :
                    $currentProducto = $row['ID_Producto'];
            ?>
            <table class="archivos-table">
                <caption><a><?php echo $row['TipoArchivo']; ?></a></caption>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Fecha de Subida</th>
                        <th>Accion</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php endif; ?>
                    <tr>
                        <td><a href="<?php echo $row['Ruta']; ?>"
                                target="_blank"><?php echo basename($row['Ruta']); ?></a></td>
                        <td><?php echo $row['TipoArchivo']; ?></td>
                        <td><?php echo $row['Descripcion']; ?></td>
                        <td><?php echo $row['Fecha']; ?></td>
                        <td><button class="btn-eliminar"
                                onclick="eliminarArchivo(<?php echo $row['ID']; ?>, <?php echo $_GET['id']; ?>);">Eliminar</button>
                        </td>
                        <td>
                            <!-- Si es un 0, el usuario puede ver el archivo, Si es un 1, no puede verlo -->
                            <?php if ($row['Deshabilitado'] == 0) : ?>
                            <button class="btn-deshabilitar"
                                onclick="cambiarEstadoArchivo(<?php echo $row['ID']; ?>, 1);">Deshabilitar</button>
                            <?php else : ?>
                            <button class="btn-habilitar"
                                onclick="cambiarEstadoArchivo(<?php echo $row['ID']; ?>, 0);">Habilitar</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <button type="button" class="volver" onclick="mostrarFormulario();">Añadir Archivo</button>

            <!-- Contenedor del formulario que se muestra/oculta -->
            <div id="formularioArchivo" class="form-container" style="display: none;">
                <form id="formArchivo" class="styled-form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="archivo" class="form-label">Seleccionar archivo:</label>
                        <input type="file" id="archivo" name="archivo" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="producto" class="form-label">Producto:</label>
                        <select id="producto" name="producto" class="form-input" required>
                            <?php
                                    while ($producto = $productos->fetch_assoc()) {
                                        echo "<option value='" . $producto['ID'] . "'>" . $producto['Nombre'] . "</option>";
                                    }
                                    ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <textarea id="descripcion" name="descripcion" class="form-input" required></textarea>
                    </div>

                    <input type="hidden" name="idUsuario" value="<?php echo  $_GET['id']; ?>">

                    <button type="button" class="form-button" onclick="subirArchivo();">Subir Archivo</button>
                </form>
                <button type="button" class="btn-eliminar" onclick="ocultarFormulario();">Cancelar</button>

            </div>
        </div>

        <h2>Estadísticas de Usuario</h2>
        <div class="container-stats">
            <div class="chart-container">
                <h2>Última semana: Veces y tiempo en la plataforma</h2>
                <canvas id="graficoSemana"></canvas>
            </div>
            <div class="chart-container">
                <h2>Últimos 3 meses: Veces en la plataforma</h2>
                <canvas id="graficoTresMeses"></canvas>
            </div>
        </div>

        <div class="export-button">
            <button id="exportarCsv">Exportar datos a CSV</button>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const params = new URLSearchParams(window.location.search);
            const id_usuario = params.get('id');

            if (id_usuario) {
                fetch(`obtener_datos_graficos.php?id=${id_usuario}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error('Error al obtener los datos:', data.error);
                            return;
                        }

                        const datosSemana = data.semana;
                        const datosTresMeses = data.tres_meses;

                        // Datos para el gráfico semanal
                        const labelsSemana = datosSemana.map(d => d.fecha);
                        const vecesSemana = datosSemana.map(d => d.veces);
                        const tiempoSemana = datosSemana.map(d => d.tiempo);

                        const ctxSemana = document.getElementById('graficoSemana').getContext('2d');
                        const graficoSemana = new Chart(ctxSemana, {
                            type: 'bar',
                            data: {
                                labels: labelsSemana,
                                datasets: [{
                                        label: 'Veces',
                                        data: vecesSemana,
                                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                        borderColor: 'rgba(75, 192, 192, 1)',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Tiempo (minutos)',
                                        data: tiempoSemana,
                                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                        borderColor: 'rgba(153, 102, 255, 1)',
                                        borderWidth: 1,
                                        type: 'line',
                                        pointRadius: 10, // Tamaño de los puntos
                                        pointHoverRadius: 15 // Tamaño de los puntos al pasar el mouse
                                    }
                                ]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            color: 'black', // Color de los números del eje y
                                            font: {
                                                size: 12 // Tamaño de fuente de los números del eje y
                                            }
                                        }
                                    },
                                    x: {
                                        ticks: {
                                            color: 'black', // Color de los números del eje x
                                            font: {
                                                size: 12 // Tamaño de fuente de los números del eje x
                                            }
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: 'black', // Color de las etiquetas de la leyenda
                                            font: {
                                                size: 12 // Tamaño de fuente de las etiquetas de la leyenda
                                            }
                                        }
                                    }
                                }
                            }
                        });

                        // Datos para el gráfico de los últimos 3 meses
                        const labelsTresMeses = datosTresMeses.map(d => d.mes);
                        const vecesTresMeses = datosTresMeses.map(d => d.veces);

                        const ctxTresMeses = document.getElementById('graficoTresMeses').getContext('2d');
                        const graficoTresMeses = new Chart(ctxTresMeses, {
                            type: 'bar',
                            data: {
                                labels: labelsTresMeses,
                                datasets: [{
                                    label: 'Veces',
                                    data: vecesTresMeses,
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            color: 'black', // Color de los números del eje y
                                            font: {
                                                size: 12 // Tamaño de fuente de los números del eje y
                                            }
                                        }
                                    },
                                    x: {
                                        ticks: {
                                            color: 'black', // Color de los números del eje x
                                            font: {
                                                size: 12 // Tamaño de fuente de los números del eje x
                                            }
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: 'black', // Color de las etiquetas de la leyenda
                                            font: {
                                                size: 12 // Tamaño de fuente de las etiquetas de la leyenda
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    })
                    .catch(error => console.error('Error al obtener los datos:', error));
                document.getElementById('exportarCsv').addEventListener('click', function() {
                    window.location.href = `exportar_csv.php?id=${id_usuario}`;
                });
            } else {
                console.error('ID de usuario no proporcionado en la URL');
            }

        });
        </script>
    </main>

    <script src="./scripts/scriptPopUp.js"></script>
    <script src="./scripts/botonesPerfilVistaAdmin.js"></script>
    <script src="./scripts/scriptArchivoAdmin.js"></script>
    <?php include('footer.php'); ?>
</body>

</html>