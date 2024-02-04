<?php
//Si no existe una sesion rol
session_start();

if (!isset($_SESSION['rol'])) {
    header('location: index.php');
} else {
    if ($_SESSION['rol'] != 2) { //Si es usuario
        header('location: usuario.php');
    }
}

//Consulta nombre usuario registrado
$nickUser = $_SESSION['nick'];
$id = $_SESSION['id'];
echo $id;
?>

<?php require 'includes/header.php'; ?>
<title>Admin</title>
</head>
<!---------------------------------------------------------------->
<?php include 'config.php' ?>
<!--Buscamos en la Base de Datos-LISTAR USUARIOS--->
<?php
// Mensaje que indicará al usuario si la consulta se realizó correctamente o no
$msgresultado = "";

// Generamos el listado de los usuarios...
try {  //Definimos la instrucción SQL parametrizada 
    $sql = "SELECT * FROM usuarios";
    // Preparamos la consulta...
    $resultsquery = $conexion->query($sql);
    //Supervisamos si la inserción se realizó correctamente... 
    if ($resultsquery) {
        $msgresultado = '<div class="alert alert-success">' . "El listado se realizó correctamente!! :)" . '</div>';
    } // o no 
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
        </div>
    </div>

    <h1>Usuarios
        <small class="text-body-secondary">Perfil Administrador</small>
    </h1>
    <!--Botones-->
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

        <!--Botón para Agregar Usuario-->
        <p class="m-2">Agregar Usuario
            <a class="link-opacity-10-hover" href="agregarUser.php"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users-plus" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                    <path d="M3 21v-2a4 4 0 0 1 4 -4h4c.96 0 1.84 .338 2.53 .901" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    <path d="M16 19h6" />
                    <path d="M19 16v6" />
                </svg></a>
        </p>

        <!--Botón para Agregar Usuario-->
        <p class="m-2">Agregar Entrada Blog
            <a class="link-opacity-10-hover" href="agregarEntrada.php"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-book-2" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
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


    <!--Listado Usuarios-->
    <div class="container col-md-8 m-4">
        <table class="table table-bordered">
            <!--Titulo Tabla usuarios-->
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NICK</th>
                    <th>NOMBRE</th>
                    <th>APELLIDOS</th>
                    <th>EMAIL</th>
                    <th>PASSWORD</th>
                    <th>IMAGEN_AVATAR</th>
                    <th>ROL</th>
                    <!-- Añadimos una columna para las operaciones que podremos realizar con cada registro -->
                    <th>Operaciones</th>
                </tr>
            </thead>
            <?php

            //Agregamos resultados de la consiulta por campos
            while ($fila = $resultsquery->fetch()) {
                echo '<tr>';
                echo '<td>' . $fila['id']   . '</td>';
                echo '<td>' . $fila['nick']    . '</td>';
                echo '<td>' . $fila['nombre']    . '</td>';
                echo '<td>' . $fila['apellidos']    . '</td>';
                echo '<td>' . $fila['email']    . '</td>';
                echo '<td>' . $fila['password']    . '</td>';
                echo '<td>';
                if ($fila['imagen_avatar'] != null) {
                    echo '<img src="fotos/' . htmlspecialchars($fila['imagen_avatar']) . '"with="40" alt="imagen-avatar"/>';
                } else {
                    echo "---";
                }


                echo '<td>' . $fila['idRol'] . '</td>';
                //Añadimos a la columna Operaciones
                echo '<td>' . '<a id="editar" href="actuser.php?id=' . $fila['id'] . '"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit-circle" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M12 15l8.385 -8.415a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3z" />
                <path d="M16 5l3 3" />
                <path d="M9 7.07a7 7 0 0 0 1 13.93a7 7 0 0 0 6.929 -6" />

              </svg></a>' . '<a class="m-2" id="editar" href="eliminarUser.php?id=' . $fila['id'] . '"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
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