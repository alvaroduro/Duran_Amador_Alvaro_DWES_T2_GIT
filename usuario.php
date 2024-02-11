<?php
//Si no existe una sesion rol
session_start();

if (!isset($_SESSION['rol'])) {
    header('location: index.php');
} else {
    if ($_SESSION['rol'] != 1) { //Si es admin
        header('location: admin.php');
    }
}

//Consulta nombre usuario registrado
$nickUser = $_SESSION['nick'];
$id = $_SESSION['id'];
?>

<?php require 'includes/header.php'; ?>
<title>Usuario</title>
</head>
<!---------------------------------------------------------------->
<?php include 'config.php' ?>
<!--Buscamos en la Base de Datos-LISTAR ENTRADAS--->
<?php
// Mensaje que indicará al usuario si la consulta se realizó correctamente o no
$msgresultado = "";
$msgresultadoUser = "";

// GENERAMOS LA CONSULTA DE LOS ENTRADAS Y SUS DATOS
try {  //Definimos la instrucción SQL parametrizada 
    $sql = "SELECT  
    entradas.id,
    entradas.usuario_id,
    entradas.categoria_id,
    categorias.nombre,
    entradas.titulo,
    entradas.imagen,
    entradas.descripcion,
    entradas.fecha
FROM 
    entradas
JOIN 
    categorias ON entradas.categoria_id = categorias.id
    WHERE usuario_id = $id
    ORDER BY entradas.fecha DESC;";

    // Preparamos la consulta...
    $query = $conexion->query($sql);
    //Supervisamos si la inserción se realizó correctamente... 
    if ($query) {
        $msgresultado = '<div class="alert alert-success">' . "El listado de entradas realizó correctamente!! :)" . '</div>';
    }
} catch (PDOException $ex) {
    $msgresultado = '<div class="alert alert-danger">' . "El listado no pudo realizarse correctamente!! :( (" . $ex->getMessage() . ')</div>';
    die();
}
?>
<!---------------------------------------------------------------->

<body>

    <!--Título-->
    <div class="container centrar">
        <div class="container text-center">
            <p>
            <h2><img class="alineadoTextoImagen" src="imagenes/iconoLogin.png" width="50px" />Administrador</h2>
            </p>
            <?php echo $msgresultado; ?>
            <?php echo $msgresultadoUser; ?>
        </div>
    </div>

    <h1>Usuarios
        <small class="text-body-secondary">Perfil Usuario</small>
    </h1>

    <!--BOTONES INICIO-->
    <div class="container-fluid d-flex">
        <!--Botón para cerrar sesión(salir al login)-->
        <p class="m-2">Cerrar Sesión
            <a class="link-opacity-10-hover" href="cerrar_sesion.php"> <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-logout" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                    <path d="M9 12h12l-3 -3" />
                    <path d="M18 15l3 -3" />
                </svg></a>
        </p>

        <!--Botón para Agregar Entrada Blog-->
        <p class="m-2">Agregar Entrada Blog
            <a class="link-opacity-10-hover" href="agregarEntrada.php?id=<?php $id ?>"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-book-2" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M19 4v16h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12z" />
                    <path d="M19 16h-12a2 2 0 0 0 -2 2" />
                    <path d="M9 8h6" />
                </svg></a>
        </p>

        <!--Mostrar usuario sesion-->
        <div class="ms-auto p-2">
            <p class="mostrarUser">Bievenido <?php echo ", " . $nickUser ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-square" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 10a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                    <path d="M6 21v-1a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v1" />
                    <path d="M3 5a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14z" />
                </svg>
            </p>
        </div>
    </div>


    <!--LISTAR ENTRADAS-->
    <div class="container">
        <table class="table table-bordered">
            <h2 class="text-center">TABLA ENTRADAS</h2>
            <!--Titulo Tabla ENTRADAS-->
            <thead>
                <tr>
                    <th>ID</th>
                    <th>USUARIO_ID</th>
                    <th>CATEGORIA_ID</th>
                    <th>NOMBRE CATEGORIA</th>
                    <th>TITULO</th>
                    <th>IMAGEN</th>
                    <th>DESCRIPCION</th>
                    <th>FECHA</th>
                    <!-- Añadimos una columna para las operaciones que podremos realizar con cada registro -->
                    <th>OPERACIONES</th>
                </tr>
            </thead>
            <?php

            //Agregamos resultados de la consulta por campos a la tabla
            while ($fila = $query->fetch()) {
                echo '<tr>';
                echo '<td>' . $fila['id'] . '</td>';
                echo '<td>' . $fila['usuario_id'] . '</td>';
                echo '<td>' . $fila['categoria_id'] . '</td>';
                echo '<td>' . $fila['nombre'] . '</td>';
                echo '<td>' . $fila['titulo'] . '</td>';
                if ($fila['imagen'] != null) {
                    echo '<td><img src="imgEntrada/' . htmlspecialchars($fila['imagen']) . '"with="40" alt="imagen"/></td>';
                } else {
                    echo '<td>' . "---" . '</td>';
                }
                echo '<td>' . $fila['descripcion'] . '</td>';
                echo '<td>' . $fila['fecha'] . '</td>';

                //Añadimos a la columna Operaciones
                echo '<td>' . '<a id="editar" href="actuser.php?id=' . $fila['id'] . '"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit-circle" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M12 15l8.385 -8.415a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3z" />
                <path d="M16 5l3 3" />
                <path d="M9 7.07a7 7 0 0 0 1 13.93a7 7 0 0 0 6.929 -6" />

                </svg></a>' . '<a class="m-2" id="editar" href="eliminarEntrada.php?id=' . $fila['id'] . '"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M4 7l16 0" />
                <path d="M10 11l0 6" />
                <path d="M14 11l0 6" />
                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                </svg></a>' . '<a class="m-2" id="editar" href="detalleUser.php?id=' . $fila['id'] . '"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-id" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                <path d="M15 8l2 0" />
                <path d="M15 12l2 0" />
                <path d="M7 16l10 0" />
                </svg></a>' . '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
    <?php require 'includes/footer.php'; ?>
</body>

</html>