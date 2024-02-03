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
        !empty($_POST["nombre"])
        && (!preg_match("/[0-9]/", $_POST["nombre"]))
        && (strlen($_POST["nombre"]) < 15)
    ) {
        $nombre = trim($_POST["nombre"]);
        $nombre = filter_var($nombre, FILTER_SANITIZE_SPECIAL_CHARS);
        
    } else {
        $errors["nombre"] = "El nombre introducido no es válido :(";
    }

    //Campo Apellidos
    if (
        !empty($_POST["apellidos"])
        && (!preg_match("/[0-9]/", $_POST["apellidos"]))
        && (strlen($_POST["apellidos"]) < 20)
    ) {
        $apellidos = trim($_POST["apellidos"]);
        $apellidos = filter_var($apellidos, FILTER_SANITIZE_SPECIAL_CHARS);
        
    } else {
        $errors["apellidos"] = "Los apellidos introducidos no son válidos :(";
    }

    //Campo Nick
    if (
        !empty($_POST["nick"])
        && (!preg_match("/[0-9]/", $_POST["nick"]))
        && (strlen($_POST["nick"]) < 15)
    ) {
        $nick = trim($_POST["nick"]);
        $nick = filter_var($nick, FILTER_SANITIZE_SPECIAL_CHARS);
        
    } else {
        $errors["nick"] = "El nick introducido no es válido :(";
    }

    //Campo Email
    if (!empty($_POST["email"])) {
        $correo = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            
        }
    } else {
        $errors["email"] = "La dirección email introducida no es válida :(";
    }

    //Campo Password
    if (
        !empty($_POST["password"]) && (strlen($_POST["password"]) > 6)
        && (strlen($_POST["password"]) <= 10)
    ) {
        
    } else {
        $errors["password"] = "Introduzca una contraseña válida (6-10  
        caracteres) :(";
    }

    //Campo Imagen
    if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
        
    } else {
        $errors["image"] = "Seleccione una imagen válida :(";
    }

    //Campo Rol
    if (!empty($_POST["role"])) {
        
    } else {
        $errors["role"] = "Seleccione un perfil de usuario :(";
    }
} //Fin boton Enviar