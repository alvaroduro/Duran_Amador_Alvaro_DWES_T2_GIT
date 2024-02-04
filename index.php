<?php require 'includes/header.php'; ?>
    <title>Index</title>
</head>
<?php include 'config.php' ?>
<body>
<!--Comprobamos los datos-->
<?php
$msgresultado = ""; //Mensaje para el usuario

//Utilizamos sesiones
session_start();

//Si se pulsa el boton INICIAR SESION
if (isset($_POST['btningresar'])) {
    
    //Comprobamos que no están vacías
    if (isset($_POST['email']) && isset($_POST['password'])) {
        //Almacenamos variables
        $email = $_POST['email'];
        $password = sha1($_POST['password']);

        //Definimos la consulta
        try {
            $consulta = "SELECT * FROM usuarios WHERE email = :email AND password = :password ";

            //Preparamos y Ejecutamos la consulta
            $resultado = $conexion->prepare($consulta);
            $resultado->execute(['email' => $email, 'password' => $password]);

            //Guardamos el resultado
            $fila = $resultado->fetch(PDO::FETCH_ASSOC);

            //Vemos si la consulta se realizó correctamente y existe contenido en el array
            if ($fila) {
                $msgresultado = '<div class="alert alert-success">'."La consulta se realizó correctamente".'</div>';
                //Validamos rol
                $rol = $fila['idRol'];
                $nick = $fila['nick'];
                $id = $fila['id'];
                $_SESSION['rol'] = $rol; //Almacenamos el rol en una sesión

                //Almacenamos nick usuario
                $_SESSION['nick'] = $nick;
                $_SESSION['id'] = $id;

                //Comprobamos el rol
                if (isset($_SESSION['rol'])) {

                    switch ($_SESSION['rol']) {
                        case 1:
                            header('location: usuario.php'); //User
                            break;
                        case 2:
                            header('location: admin.php'); //Admin
                            break;
                        default:}       
                }
            } else {
                //No existe el usuario
                $msgresultado = '<div class="alert alert-danger">'."El usuario no se encuentra en la Base de Datos!!(".'</div>'; 
                }

        } catch (PDOException $ex) {
            $msgresultado = '<div class="alert alert-danger">'."La consulta no pudo realizarse correctamente!!(".'</div>'; 
            die();}
    } //Fin comprobación usuario y password
} //Fin botón ingresar 

?>

    <!--Título-->
    <div class="container centrar">
        <div class="container text-center">
            <p>
            <h2><img class="alineadoTextoImagen" src="imagenes/iconoLogin.png" width="50px" />Control Aceeso Usuarios</h2>
            </p>
            <?php echo $msgresultado; ?>
        </div>
    </div>

    <!--Formulario login de Bootstrap-->
    <div class="container centrar">
        <form action="" method="POST"><!--Enviamos los datos-->
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label"> Usuario</label>               
                <input class="form-control" name="email" type="text" placeholder="Email@email.com" aria-label="default input example">
                <div id="email1" class="form-text"></div>
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="password">
            </div>
            <button name="btningresar" type="submit" class="btn btn-primary">INICIAR SESION</button>
        </form>
    </div>

<?php require 'includes/footer.php'; ?>
</body>
</html>