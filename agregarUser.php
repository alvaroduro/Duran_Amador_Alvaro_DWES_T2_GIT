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
?>
<?php include 'valform.php'; ?>
<?php include 'config.php' ?>
<?php require 'includes/header.php'; ?>
<?php
// Mensaje que indicará al usuario si la inserción se realizó correctamente o no 
$msgresultado = "";

// Si se ha pulsado el botón guardar... 
if (isset($_POST['submit'])) {
    // y hemos recibido las variables del formulario y éstas no están vacías... 
    if (isset($_POST) and (!empty($_POST))) {
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $nick = $_POST['nick'];
        $email = $_POST['email'];
        $password = sha1($_POST['password']);
        $rol = $_POST['role'];
        $imagen = null;
        //Imagen
        if (isset($_FILES["image"]) && (!empty($_FILES["image"]["tmp_name"]))) {
            // Comprobamos si existe el directorio fotos, y si no, lo creamos 
            if (!is_dir("fotos")) {
                $dir = mkdir("fotos", 0777, true);
            } else {
                $dir = true;
            }

            //Verificado  que  la  carpeta  fotos  existe  movemos  a  ella  fichero  seleccionado         
            if ($dir) {
                //Para asegurarnos que el nombre va a ser único 
                $nombrefichimg = time() . "-" . $_FILES["image"]["name"];
                // Movemos el fichero  de la carpeta temportal a la nuestra 
                $movfichimg = move_uploaded_file(
                    $_FILES["image"]["tmp_name"],
                    "fotos/" . $nombrefichimg
                );
                $imagen = $nombrefichimg;
                // Verficamos que la carga se ha realizado correctamente 
                if ($movfichimg) {
                    $imagencargada = true;
                } else {
                    $imagencargada = false;
                    $errores["image"] = "Error: La imagen no se cargó correctamente! :(";
                }
            } //Fin crear directorio
        } //Fin Imagen

        //Comprobamos los errores
        if (count($errors) == 0) {
            //Definimos la instrucción SQL parametrizada
            try {
                $sql = "INSERT INTO usuarios(nombre,apellidos,nick,email,password,imagen_avatar,idRol)
                           VALUES (:nombre,:apellidos,:nick,:email,:password,:imagen,:rol)";
                // Preparamos la consulta...
                $query = $conexion->prepare($sql);
                // y la ejecutamos indicando los valores que tendría cada parámetro
                $query->execute([
                    'nombre'   => $nombre,
                    'apellidos'   => $apellidos,
                    'nick'   => $nick,
                    'email'    => $email,
                    'password' => $password,
                    'imagen'   => $imagen,
                    'rol'   => $rol
                ]);

                //Supervisamos si la inserción se realizó correctamente... 
                if ($query) {
                    $msgresultado = '<div class="alert alert-success">' . "El usuario se registró correctamente!! :)" . '</div>';
                    header('location:admin.php');
                } // o no :(
            } catch (PDOException $ex) {
                $msgresultado = '<div class="alert alert-danger">' . "El usuario no pudo registrarse!!" . '</div>';
                //die();
                echo "Mensaje error: " . $ex->getMessage();
            }
        } else {
            $msgresultado = '<div class="alert alert-danger">' . "Datos de registro erróneos" . '</div>';
            //die()
        }
    }  //Final errores vacios
}   //Final Enviar

?>
<title>Agregar Usuario</title>
</head>
<!---------------------------------------------------------------->

<body>

    <div class="centrar">
        <div class="container cuerpo text-center">
            <p>
            <h2><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                    <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                </svg> Datos de usuario
            </h2>
            </p>
            <?php echo $msgresultado ?>
        </div>
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
        </div>
        <div class="container">
            <!--Ingresamos el formulario-->
            <form action="agregarUser.php" method="POST" enctype="multipart/form-data">

                <!--Campo Nombre-->
                <label for="nombre">Nombre:
                    <input type="text" name="nombre" class="form-control" <?php if (isset($_POST["nombre"])) {
                                                                                echo "value='{$_POST["nombre"]}'";
                                                                            } ?> />
                    <?php echo  mostrar_error($errors, "nombre"); ?>
                </label>
                <br />

                <!--Campo Apellidos-->
                <label for="apellidos"> Apellidos:
                    <input type="text" name="apellidos" class="form-control" <?php if (isset($_POST["apellidos"])) {
                                                                                    echo  "value='{$_POST["apellidos"]}'";
                                                                                } ?> />
                    <?php echo  mostrar_error($errors, "apellidos"); ?>
                </label>
                <br />

                <!--Campo Nick-->
                <label for="nick">Nick:
                    <input type="text" name="nick" class="form-control" <?php if (isset($_POST["nick"])) {
                                                                            echo "value='{$_POST["nick"]}'";
                                                                        } ?> />
                    <?php echo  mostrar_error($errors, "nick"); ?>
                </label>
                <br />

                <!--Campo Email-->
                <label for="email">Correo:
                    <input type="email" name="email" class="form-control" <?php if (isset($_POST["email"])) {
                                                                                echo  "value='{$_POST["email"]}'";
                                                                            } ?> />
                    <?php echo  mostrar_error($errors, "email"); ?>
                </label>
                <br />

                <!--Campo Password-->
                <label for="password">Contraseña:
                    <input type="password" name="password" class="form-control" <?php if (isset($_POST["password"])) {
                                                                                    echo  "value='{$_POST["password"]}'";
                                                                                } ?> />
                    <?php echo  mostrar_error($errors, "password"); ?>
                </label>
                <br />

                <!--Campo Imagen-->
                <label for="image">Imagen:
                    <input type="file" name="image" id="image" class="form-control-file" />
                    <?php echo  mostrar_error($errors, "image"); ?>
                </label>
                <br />

                <!--Campo Rol-->
                <label for="role">Rol:
                    <select name="role" class="form-control">
                        <option selected value="0">Elige Opción</option>
                        <option value="1" <?php if (isset($_POST["role"])) {
                                                if ($_POST["role"] == 1) {
                                                    echo  "selected='selected'";
                                                }
                                            } ?>>
                            Usuario Lector</option>
                        <option value="2" <?php if (isset($_POST["role"])) {
                                                if ($_POST["role"] == 2) {
                                                    echo  " selected='selected'";
                                                }
                                            } ?>>
                            Administrador</option>
                    </select>
                    <?php echo  mostrar_error($errors, "role"); ?>
                </label>
                <br />

                <!--Botón Enviar-->
                <input type="submit" value="Enviar" name="submit" class="btn btn-success my-2" />

            </form>
        </div><!--Fin Container-->
        <!--Botón para cerrar sesión(salir al login)-->
        <p class="m-2">Atras
            <a class="link-opacity-10-hover" href="admin.php"> <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-back-up" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 14l-4 -4l4 -4" />
                    <path d="M5 10h11a4 4 0 1 1 0 8h-1" />
                </svg></a>
        </p>
    </div> <!--Fin centrar-->
    <hr class="my5">
    <?php require 'includes/footer.php'; ?>
</body>

</html>