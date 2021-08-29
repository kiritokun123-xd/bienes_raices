<?php

//IMPORTAR LA CONEXION

require 'includes/app.php';
$db = conectarDB();

//CREAR UN EMAIL Y PASSWORD
$email = "david@gmail.com";
$password = "123456";

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

//var_dump($passwordHash);

//QUERY PARA CREAR AL USUARIO
$query = "INSERT INTO usuarios (email, password) VALUES ('${email}','${passwordHash}')";

//AGREGAR A LA BASE DE DATOS
mysqli_query($db, $query);