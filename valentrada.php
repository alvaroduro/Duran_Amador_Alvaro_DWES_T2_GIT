<?php
/** 
 * Script que muestra en una tabla los valores enviados por el usuario a  
 * través del formulario utilizando el método POST 
 */
// Definimos e inicializamos el array de errores 
$errors = [];

// Función que muestra el mensaje de error bajo el campo que no ha  
// superado el proceso de validación 
function mostrar_error($errors, $campo)
{
    //Mensaje para mostrar el error
    $alert = "";
    if (isset($errors[$campo]) && (!empty($campo))) {
        $alert = '<div class="alert alert-danger" style="margin-top:5px;">'.$errors[$campo].'</div>';
    }
    return $alert;
}

// Verificamos si todos los campos han sido validados 
function validez($errors)
{
    //Si se ha pulsado Enviar y no hay errores
    if (isset($_POST["submit"]) && (count($errors) == 0)) {
        return '<div class="alert alert-success" style="margin-top:5px;">Formulario validado correctamente!! :) </div>';
    }
}

//Si se pulsa el botón Enviar
if (isset($_POST["submit"])) {

    //Campo Nombre
    if (
        !empty($_POST["titulo"])
        && (preg_match("/[a-z0-9\-_*]/", $_POST["titulo"]))
        && (strlen($_POST["titulo"]) < 15)
    ) {
        $titulo = trim($_POST["titulo"]);
        $titulo = filter_var($titulo, FILTER_SANITIZE_SPECIAL_CHARS);
        
    } else {
        $errors["titulo"] = "El titulo introducido no es válido :(";
    }

    //Campo Nombre Categoría
    if (
        !empty($_POST["nombre_categoria"])
        && (preg_match("/[a-z0-9\-_*]/", $_POST["nombre_categoria"]))
        && (strlen($_POST["nombre_categoria"]) < 15)
    ) {
        $nombre_categoria = trim($_POST["nombre_categoria"]);
        $nombre_categoria = filter_var($nombre_categoria, FILTER_SANITIZE_SPECIAL_CHARS);
        
    } else {
        $errors["nombre_categoria"] = "El nombre de la categoria introducido no es válido :(";
    }

    //Campo Descripcion
    if (
        !empty($_POST["descripcion"])
        && (preg_match("/[a-z0-9\-_*]/", $_POST["descripcion"]))
        && (strlen($_POST["descripcion"]) < 255)
    ) {
        $descripcion = trim($_POST["descripcion"]);
        $descripcion = filter_var($descripcion, FILTER_SANITIZE_SPECIAL_CHARS);
        
    } else {
        $errors["descripcion"] = "La descripcion introducida no es válida :(";
    }

    //Campo Imagen
    if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
        
    } else {
        $errors["image"] = "Seleccione una imagen válida :(";
    }

} //Fin boton Enviar