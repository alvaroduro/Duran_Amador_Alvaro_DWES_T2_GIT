<?php
  // Conexión a la base de datos usando PDO
  $dbHost = 'localhost'; //Servidor
  $dbName = 'bd_blog'; //Nombre BD
  $dbUser = 'root'; //Usuario
  $dbPass= ''; //Contraseña

  try{
      $conexion = new PDO("mysql:host=$dbHost;dbname=$dbName",$dbUser,$dbPass);
      $conexion ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      echo '<div class="alert alert-success">' . "Conectado a la  Base de Datos de la Empresa!! :)" . '</div>';
  }catch (PDOException $ex){
      echo '<div class="alert alert-danger">' . "No se pudo conectar a la Base de Datos de la Empresa!! :( <br/>" .$ex->getMessage(). '</div>';
  }
?>