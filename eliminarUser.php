<?php
require_once "config.php";
//Si no existe una sesion rol
session_start();

if (!isset($_SESSION['rol'])) {
    header('location: index.php');
} else {
    if ($_SESSION['rol'] != 2) { //Si es usuario
        header('location: usuario.php');
    }
}

$id = $_GET['id'];

//Verificamos si tenemos el id del usuario a eliminar
if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    //Definimos la instrucción SQL parametrizada
    try {
        $sql = "DELETE FROM usuarios WHERE id=:id";
        // Preparamos la consulta...
        $query = $conexion->prepare($sql);
        // y la ejecutamos indicando los valores que tendría cada parámetro
        $query->execute(['id' => $id]);

        //Supervisamos si la inserción se realizó correctamente... 
        if ($query) {
            $msgresultado = '<div class="alert alert-success">' . "El usuario se eliminó correctamente!! :)" . '</div>';
            header("location:admin.php");
        } // o no :(
    } catch (PDOException $ex) {
        $msgresultado = '<div class="alert alert-danger">' . "No se pudo acceder al usuario a elminar!!" . '</div>';
        //die();
        echo "Mensaje error: " . $ex->getMessage();
        header("location:admin.php");
    }
} else {
    $msgresultado = '<div class="alert alert-danger">' . "No se pudo acceder al id del usuario a elminar!!" . '</div>';
    //header("location:admin.php");
}
