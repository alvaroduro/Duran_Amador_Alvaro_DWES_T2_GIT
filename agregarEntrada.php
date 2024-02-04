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
$id = $_SESSION['id']; //Guardamos el id del usario
?>

<?php include 'valentrada.php'; ?>
<?php include 'config.php' ?>
<?php require 'includes/header.php'; ?>
<?php

// Mensaje que indicará al usuario si la inserción se realizó correctamente o no 
$msgresultado = "";

// Si se ha pulsado el botón guardar... 
if (isset($_POST['submit'])) {

    // y hemos recibido las variables del formulario y éstas no están vacías... 
    if (isset($_POST) and (!empty($_POST))) {
        $titulo = $_POST['titulo'];
        $nombre_categoria = $_POST['nombre_categoria'];
        $descripcion = $_POST['descripcion'];
        $fecha =  date("d-m-Y"); // Formato: Año-Mes-Día
        $imagen = null;       

        //Imagen
        if (isset($_FILES["image"]) && (!empty($_FILES["image"]["tmp_name"]))) {
            // Comprobamos si existe el directorio fotos, y si no, lo creamos 
            if (!is_dir("imgEntrada")) {
                $dir = mkdir("imgEntrada", 0777, true);
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
                    "imgEntrada/" . $nombrefichimg
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
            
            //INSERTAR EN LA TABLA CATEGORIAS (nombre)
            try {  
                $sql1 = "INSERT INTO categorias(nombre) 
                VALUES(:nombre_categoria)";

                // Preparamos la consulta...
                $query = $conexion->prepare($sql1);

                // y la ejecutamos indicando los valores que tendría cada parámetro
                $query->execute(['nombre_categoria' => $nombre_categoria]);

            }catch (PDOException $ex) {
                echo "No se ha realizado categorias";               
                $msgresultado = '<div class="alert alert-danger">' . "No se pudo insertar la categoría!!" . '</div>';
                //die();
            }

            //RECUPERAR ID DE CATEGORIAS POR NOMBRE
            try {  //Definimos la instrucción SQL parametrizada 
                $sql2 = "SELECT id FROM categorias where nombre=:nombre_categoria";
                
                // Preparamos la consulta...
                $query = $conexion->prepare($sql2);
                $query->execute(['nombre_categoria' => $nombre_categoria]);
                //Supervisamos si la recuperación se realizó correctamente... 
                if ($query) {
                    $msgresultado = '<div class="alert alert-success">' .
                        "Los datos del usuario se obtuvieron correctamente!! :)" . '</div>';
                } // o no :( 
            } catch (PDOException $ex) {
                $msgresultado = '<div class="alert alert-success">' .
                    "No se pudieron obtener los datos de usuario!! :( <br/>(" .
                    $ex->getMessage() . ')</div>';
                die();
            }

            //Recuperamos el id_categoria y lo añadimos
            $fila = $query->fetch(PDO::FETCH_ASSOC);
            $id_categoria = $fila['id'];

            //INSERTAR ELEMENTOS TABLA ENTRADAS
            try {
                $sql = "INSERT INTO entradas(usuario_id,categoria_id,titulo,imagen,descripcion,fecha)
                           VALUES (:usuario_id,:categoria_id,:titulo,:imagen,:descripcion,:fecha)";
                // Preparamos la consulta...
                $query = $conexion->prepare($sql);
                // y la ejecutamos indicando los valores que tendría cada parámetro
                $query->execute([
                    'usuario_id'    => $id,
                    'categoria_id'   => $id_categoria,
                    'titulo'    => $titulo, 
                    'imagen'   => $imagen,
                    'descripcion'   => $descripcion,
                    'fecha' =>$fecha
                ]);

                //Supervisamos si la inserción se realizó correctamente... 
                if ($query) {
                    $msgresultado = '<div class="alert alert-success">' . "El Blog se registró correctamente!! :)" . '</div>';
                    header('location:admin.php');
                } // o no :(
            } catch (PDOException $ex) {
                $msgresultado = '<div class="alert alert-danger">' . "El Blog no pudo registrarse!!" . '</div>';
                //die();
                echo "Mensaje error: ".$ex->getMessage();
            }
        } else {
            $msgresultado = '<div class="alert alert-danger">' . "Datos de registro erróneos" . '</div>';
            //die()
        }
    }  //Final campos vacíos
}   //Final Enviar

?>
<title>Agregar Entrada Blog</title>
</head>
<!---------------------------------------------------------------->

<body>

    <div class="centrar">
        <div class="container cuerpo text-center">
            <p>
            <h2><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
  <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
  <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
  <path d="M16 3.13a4 4 0 0 1 0 7.75" />
  <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
</svg> Datos de Blog
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
            <form action="agregarEntrada.php" method="POST" enctype="multipart/form-data">

                <!--Campo Título-->
                <label for="titulo">Título 
                    <input class="my-4" type="text" name="titulo" class="form-control" <?php if (isset($_POST["titulo"])) {
                        echo "value='{$_POST["titulo"]}'";} ?> />
                    <?php echo  mostrar_error($errors, "titulo"); ?>
                </label>
                <br />

                <!--Campo Categoría-->
                <label for="nombre_categoria">Nombre  Categoría 
                    <input class="my-4" type="text" name="nombre_categoria" class="form-control" <?php if (isset($_POST["nombre_categoria"])) {
                        echo "value='{$_POST["nombre_categoria"]}'";} ?> />
                    <?php echo  mostrar_error($errors, "nombre_categoria"); ?>
                </label>
                <br />

                <!--Campo Imagen-->
                <label for="image">Imagen:
                    <input class="my-2" type="file" name="image" id="image" class="form-control-file" />
                    <?php echo  mostrar_error($errors, "image"); ?>
                </label>
                <br />

                <!--Campo Descripción-->
                <label class="mt-2" for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control my-2" name="descripcion" <?php if (isset($_POST["descripcion"])) {
                        echo  "value='{$_POST["descripcion"]}'";} ?>  id="descripcion" rows="4"></textarea>
                <?php echo  mostrar_error($errors, "descripcion"); ?>
                <br />

                <!--Botón Enviar-->
                <input type="submit" value="Guardar" name="submit" class="btn btn-success my-2" />

            </form>
        </div><!--Fin Container-->
    </div> <!--Fin centrar-->
    <hr class="my5">
    <?php require 'includes/footer.php'; ?>
</body>

</html>