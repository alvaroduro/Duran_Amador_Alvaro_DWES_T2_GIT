<?php

//Si tengo que cerrar sesión
    echo "cerrar sesion";
    @session_start();

    session_destroy();
    header('location: index.php');

?>